<?php

class synccatalogTask extends sfBaseTask {
    private $ftpLogin;
    private $ftpPass;
    private $ftpServer;
    private $ftpConnection;
    private $isTest;

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "new"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('mode', null, sfCommandOption::PARAMETER_REQUIRED, 'mode', 'test'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'synccatalog';
        $this->briefDescription = '';
        $this->detailedDescription = "\nThe [synccatalog|INFO]Синхронизации с 1С каталог с 1С\n[php symfony synccatalog|INFO]";
    }

    protected function execute($arguments = array(), $options = array()) {
        $this->ftpLogin='4partner';
        $this->ftpPass='bamyietjitLa';
        $this->ftpServer='onona.ru';

        $localPath=str_replace('lib/task', '', __DIR__).'web/';

      if ($options['env']=='dev'){
        $this->isTest=true;
      }
      else {
        $this->isTest= false;
        $localPath=str_replace('lib/task', '', __DIR__).'web/';
        // die($localPath);
      }
      switch ($options['mode']){
        case 'restore_catalog':
          $this->myLog("Пытаемся восстановить каталог");
          $database=simplexml_load_file($localPath.'onona_sber.xml');
          if ($database===false) {
            $this->myLog("Ошибка разбора файла database.xml");
            die('Ошибка разбора файла database.xml'."\n");
          }
          $i=0;
          $databaseManager = new sfDatabaseManager($this->configuration);
          $q=Doctrine_Manager::getInstance()->getCurrentConnection();
          foreach ($database->xpath('shop/categories/category') as $category) {
            // $this->myLog(__LINE__);
            // $arCat = json_decode(json_encode($category));
            //var_dump($arCat);
            // var_dump([trim($category['id']), trim($category)]);
            $sqlBody = "UPDATE `category` SET `name`='".trim($category)."' WHERE `id`=".trim($category['id']).';';
            $q->execute($sqlBody);
            // Doctrine_Core::getTable('Category')->findOneById(trim($category['id']));
            $this->myLog($sqlBody);
            // if($i++>2) break;
          }
          $this->myLog("done");

        break;

        case 'shop_stocks':
          $databaseManager = new sfDatabaseManager($this->configuration);
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $sqlBody = "CREATE TABLE IF NOT EXISTS `shop_stocks` (`product_id` BIGINT(20) NOT NULL, `shop_id` CHAR(255) NOT NULL COLLATE utf8_unicode_ci, `stock` SMALLINT UNSIGNED, PRIMARY KEY(`product_id`, `shop_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
          $result=$q->execute($sqlBody);
          $sqlBody = "TRUNCATE TABLE `shop_stocks`";
          $result=$q->execute($sqlBody);
          $sqlBody="SELECT `id`, `stock` FROM `product` WHERE `stock` LIKE '%storage\"%'";
          $result=$q->execute($sqlBody);
          $table=$result->fetchAll(Doctrine_Core::FETCH_ASSOC);
          foreach ($table as $line) {
            $data=unserialize($line['stock']);

            if(empty($data['storages']['storage'])) continue;
            if(empty($data['storages']['storage']['@attributes']))
              foreach ($data['storages']['storage'] as $storage) {
                $values[]='('.$line['id'].', "'.$storage['@attributes']['code1c'].'", '.$storage['@attributes']['stock'].')';
              }
            else
              $values[]='('.$line['id'].', "'.$data['storages']['storage']['@attributes']['code1c'].'", '.$data['storages']['storage']['@attributes']['stock'].')';
          }
          $sqlBody="INSERT INTO `shop_stocks` (`product_id`, `shop_id`, `stock`) VALUES ".implode(', ', $values);
          $result=$q->execute($sqlBody);

          //Убираем все остатки магазинов для товаров без остатков
          $sqlBody = "DELETE FROM `shop_stocks` WHERE `product_id` IN(SELECT `id` FROM `product` WHERE `count` <1)";
          $q->execute($sqlBody);

          break;

        case 'paymets_export':
          $this->myLog('Отправляем xml c оплатами');
          $filename='payments.xml';
          // $payments=false;
          $payments=simplexml_load_file($localPath.$filename);
          if($payments===false){//если загрузить файл не удалось - создаем его
            $xmlstr=
            "<?xml version='1.0'?>".
            "<payments></payments>";
            $payments = new SimpleXMLElement($xmlstr);
          }
          $databaseManager = new sfDatabaseManager($this->configuration);
          $prefix=csSettings::get('order_prefix');

          $docs=Doctrine_Core::getTable('Paymentdoc')
            ->createQuery('p')
            ->where('sync_status=0')
            ->execute();
          foreach ($docs as $doc) {
            $payment=$payments->addChild('payment');
            $payment->addAttribute('order_id', $prefix.$doc->get('order_id'));
            $payment->addAttribute('id', $doc->get('order_id'));
            $payment->addAttribute('amount', $doc->get('amount')/100);
            $payment->addAttribute('card_no', $doc->get('card_no'));
            $payment->addAttribute('fio', $doc->get('fio'));
            $payment->addAttribute('created_at', $doc->get('created_at'));
            $payment->addAttribute('payment_type', $doc->get('payment_type'));
            $doc->set('sync_status', 1);
            $doc->save();
            // $payment->addChild('reason', $doc->get('response_code'));
          }
          if(!isset($payments->attributes()->checkdate))
            $payments->addAttribute('checkdate', date('c'));
          $payments->attributes()->checkdate=date('c');
          $paymentsText=$payments->asXML();
          $paymentsText=str_replace(["\n",'<payment'], ['', "\n<payment"], $paymentsText);
          file_put_contents($localPath.$filename, $paymentsText);
          if(!$this->isTest){
            $this->ftpConnect();
            ftp_put($this->ftpConnection, $filename, $localPath.$filename,  FTP_BINARY);
            $this->ftpDisconnect();
          }

          break;

        case 'sync_items':
          // $this->myLog("Пытаемся синхронизировать каталог");
          if(!$this->isTest)
            $this->ftpConnect();
          $ftpFile='database.xml';
          // if($this->isTest) $ftpFile='database-full.xml';
          $ftpFileStock='stock.xml';
          if(!$this->isTest){
            ftp_get($this->ftpConnection, $localPath.$ftpFileStock, $ftpFileStock, FTP_BINARY);
            // die('download '.$localPath.$ftpFileStock);
          }
          if(!$this->isTest)
            ftp_get($this->ftpConnection, $localPath.$ftpFile, $ftpFile, FTP_BINARY);
          $this->myLogItems("--------------------------------\n".date('d.m.Y H:i:s')."\nПытаемся синхронизировать каталог", 'sync_catalog_log.txt', true);

          $database=simplexml_load_file($localPath.$ftpFile);
          if ($database===false) {
            $this->myLogItems("Ошибка разбора файла database.xml");
            die('Ошибка разбора файла database.xml'."\n");
          }
          $this->myLogItems("database.xml успешно разобран");
          $stocks=simplexml_load_file($localPath.$ftpFileStock, 'SimpleXMLElement', LIBXML_NOCDATA);
          if ($stocks===false) {
            $this->myLogItems("Ошибка разбора файла stock.xml");
            die('Ошибка разбора файла stock.xml'."\n");
          }
          $this->myLogItems("stock.xml успешно разобран");

          foreach ($stocks->xpath('products/product') as $product) {
            $product = json_decode(json_encode($product), TRUE);
            if (@$product['storages']['storage']['@attributes']['code1c'] == "000000044") {
                unset($product['storages']['storage']);
            }
            unset ($product['fullname']);
            unset ($product['description']);
            $productsBase[trim($product['@attributes']['code'])]=[
              'attributes' => serialize($product),
              'stock' => trim($product['@attributes']['stock']),
              'price' => trim($product['@attributes']['price']),
              'name' => trim($product['name']),
              'code' => trim($product['@attributes']['code']),
            ];
          }

          $databaseManager = new sfDatabaseManager($this->configuration);
          $i=0;
          foreach ($database->xpath('products/product') as $product) {
            $code=trim($product['code']);
            if(!$code) continue;
            $productsBase[$code]['code']=$code;
            $productsBase[$code]['weight']=trim($product['weight']);
            $productsBase[$code]['price']=trim($product['price']);
            $productsBase[$code]['in-stock']=trim($product['in-stock']);
            $productsBase[$code]['sale-price']=trim($product['sale-price']);
            $productsBase[$code]['code1c']=trim($product['code1c']);
          }
          $this->myLogItems(  'Найдено  '.sizeof($productsBase).' элементов ');
          $ids[]=-1;//Чтобы не был пустой IN
          foreach($productsBase as $key => $product){
            $prodBase=Doctrine_Core::getTable('Product')->findOneByCode($key);
            if(!is_object($prodBase)) {
              $this->myLogItems(  'Товар с артикулом "'.$key.'" не найден в базе');
              continue;
            }
            $ids[]=$prodBase->getId();//Собираем сюда все товары, которые есть в базе синхронизации
            if(!$prodBase->getIsPublic()){
              $this->myLogItems(  'Товар с артикулом "'.$key.'" неактивен - пропускаем');
              continue;
            }
            if(!$prodBase->getSync()){
              $this->myLogItems(  'Товар с артикулом "'.$key.'" отключен от синхронизации - пропускаем');
              continue;
            }
            if($this->isUpToDate($prodBase, $product, $key== 'XRTF1137')){
              $this->myLogItems(  'Товар с артикулом "'.$key.'" без изменений - пропускаем');
              continue;
            }
            $stock=isset($product['in-stock']) ? $product['in-stock'] : 0;
            $needToSend = false;
            if(!$prodBase->getCount() && $stock) $needToSend = true;
            $prodBase->setCount($stock);
            if (isset($product['weight'])) {
              $prodBase->setWeight($product['weight']);
            }
            if (isset($product['attributes'])) $prodBase->setStock($product['attributes']);
            else $prodBase->setStock('');
            if (isset($product['sale-price'])){//Из 1С цена приходит и возможно выставить скидку
              if($product['sale-price']!=$product['price']){//Скидка есть
                $prodBase->setOldPrice($product['price']);
                $discount=round((100*($product['price']-$product['sale-price'])/$product['price']));
                $prodBase->setDiscount($discount);
                $prodBase->setPrice(round($product['price']*(1-$discount/100)));

              }
              else{//Скидки нет
                $prodBase->setOldPrice(0);
                $prodBase->setDiscount(0);
                $prodBase->setPrice($product['price']);
              }
            }
            else{//Иначе все скидки удаляем нах
              $prodBase->setOldPrice(0);
              $prodBase->setDiscount(0);
              $prodBase->setPrice($product['price']);
            }
            $i++;
            // die("test done\n");
            $prodBase->save();
            if($needToSend) $this->sendLetterAtChangeStatus($prodBase);
            $this->myLogItems(  'Товар с артикулом "'.$key.'" обновили');
          }
          $this->myLogItems(  'Закончили обновление товаров');
          $sqlBody='update product set count=0 where sync=1 and is_public=1 and count>0 and id NOT IN('.implode(', ', $ids).')';
          $q=Doctrine_Manager::getInstance()->getCurrentConnection();
          $q->execute($sqlBody);
          $this->myLogItems(  'Выставили всем остальным активным синхронизируемым товарам остаток 0');
          // if($this->isTest) die ($sqlBody);
          if(!$this->isTest)
            $this->ftpDisconnect();

          $this->myLogItems('Обновлено '.$i.' товаров');
          $this->myLogItems("\n--------------------------------\n".date('d.m.Y H:i:s'));

          break;

        case 'send_orders':
          $this->myLog("Загружаем заказы ис 1С");
          $filename='orders.xml';
          $filename2='confirm.xml';
          $file1C='1c_to_web.xml';
          $databaseManager = new sfDatabaseManager($this->configuration);

          sfContext::createInstance($this->configuration);

          $prefix=csSettings::get('order_prefix');
          if(!$prefix){
            $this->myLog("Пустой префикс", "sync_log_prefix.log");
            die();
          }
          mb_internal_encoding('UTF-8');
          if(!$this->isTest){
            $this->ftpConnect();
          }

          //Сначала принимаем подтверждения из 1с: обновляем заказы и формируем подтверждения по ним
          if(!$this->isTest){
            // $this->ftpConnect();
            ftp_get($this->ftpConnection, $localPath.$file1C, $file1C, FTP_BINARY);
          }
          $file1CXML= simplexml_load_file($localPath.$file1C);
          if ($file1CXML===false) die('Ошибка разбора файла '.$file1C."\n");

          $confStr='<?xml version="1.0" encoding="utf-8"?>'.
            '<database version="2.1" createdate="'.date('c').'">'.
            '<confirmations createdate="'.date('c').'">'.
            '</confirmations>'.
            '</database>';
          $confXml = new SimpleXMLElement($confStr);

          foreach ($file1CXML->xpath('orders/order') as $order){

            $id=str_replace($prefix, '', trim($order['id']));
            $sync_status='processing';
            if(''.trim($order['status']) !='Новый') $sync_status='complete';
            $orderDB=OrdersTable::getInstance()->findOneById($id);
            if(!is_object($orderDB)) {
              // $this->myLog("Заказ $id не найден");
              continue;
            }
            // $this->myLog("_______________________________");
            // $this->myLog("Обрабатываем заказ ".$id);
            unset($prodXml);
            unset($arOrderProducts);
            foreach ($order->products->children() as $product) {
              // echo trim($product['article'])."\n";
              $prodDb=ProductTable::getInstance()->findOneByCode(trim($product['article']));
              if(!is_object($prodDb)) {
                $this->myLog("Товар ".trim($product['article'])." не найден");
                continue;
              }
              $arOrderProducts[]=$prodDb;
              $prodId=$prodDb->getId();
              $prodXml[trim($product['article'])]=[
                'article' => trim($product['article']),
                'product_id'  => $prodId,//Получить из базы
                'title' => trim($product->title),
                'price' => trim($product['price']),
                'price_w_discount' => round(trim($product['price_w_discount'])),
                'price_final' => round(trim($product['price_final'])),
                'count' => trim($product['count']),
                'section' => trim($product->section),
                'quantity' => trim($product['count']),
                'productId'  => $prodId,//Получить из базы
              ];
            }
            // die('|'.print_r(serialize($prodXml), true).'|'.$id."\n");

            $this->setBonusAtChangeStatus($orderDB, trim($order['status']), $arOrderProducts, $prodXml);
            $this->sendMailForChangeStatus($orderDB, trim($order['status']));

            $orderDB->set('comment_1c', trim($order->comment1C));
            $orderDB->set('comments', trim($order->comment));
            $orderDB->set('manager', trim($order->manager));
            $orderDB->set('updated_at', ''.trim($order['modifydate']));
            $orderDB->set('sync_status', $sync_status);
            $orderDB->set('status', trim($order['status']));
            $orderDB->set('status_detail', trim($order['status_detail']));
            if(!$orderDB->get('firsttext')) $orderDB->set('firsttext', $orderDB->get('text'));
            $orderDB->set('text', serialize($prodXml));


            // var_dump($orderDB);
            // die('|'.'-----------------------------------'.'|'."\n");

            $orderDB->save();

            $confXmlOrder=$confXml->confirmations->addChild('order');
            $confXmlOrder->addAttribute('id', trim($order['id']));
            $confXmlOrder->addAttribute('modifydate', ''.trim($order['modifydate']));
          }

          // die($confXml->asXml()."\n");
          //Затем выгружаем новые заказы
          $this->myLog("Пытаемся выгрузить заказы со статусом new");
          $xmlstr= '<?xml version="1.0" encoding="utf-8"?>'.
                  '<database version="2.1" createdate="'.date('c').'">'.
                    '<orders version="2.1" createdate="'.date('c').'">'.
                    '</orders>'.
                    //
                    // '<partners>'.
                    //   '<partner partner_id="65" name="Павлычев Андрей Геннадьевич" type="" info1="" info2=""/>'.
                    //   '<partner partner_id="46" name="Свиридов Вадим Сергеевич" type="" info1="" info2=""/>'.
                    // '</partners>'.
                    // '<shops>'.
                    //   '<shop partner_id="46" shop_id="11" name="Он и Она" url="https://onona.ru" info1="" info2=""/>'.
                    // '</shops>'.
                  '</database>';
          $xml = new SimpleXMLElement($xmlstr);
          $orders=Doctrine_Core::getTable('Orders')
            ->createQuery('o')
            ->where('sync_status="new"')
            ->execute();
          foreach ($orders as $order) {
            $xmlOrder=$xml->orders->addChild('order');
            $xmlOrder->addAttribute('id', $prefix.$order->getId());
            $xmlOrder->addAttribute('status', $order->getStatus());
            $xmlOrder->addAttribute('createdate', $order->getCreatedAt());
            $xmlOrder->addAttribute('payment_type', $order->getPayment());
            $customer=sfGuardUserTable::getInstance()->findOneById($order->getCustomerId());

              $xmlDelivery=$xmlOrder->addChild('delivery');
              $xmlDelivery->addAttribute('type', $order->getDelivery());
              $xmlDelivery->addAttribute('price', $order->getDeliveryPrice());
              $xmlDelivery->addChild('city', $customer->getCity());
              $xmlDelivery->addChild('index', $customer->getIndexHouse());
              $xmlDelivery->addChild('street', $customer->getStreet());
              $xmlDelivery->addChild('house', $customer->getHouse());
              // $xmlDelivery->addChild('housing', $customer->getHousing());
              $xmlDelivery->addChild('flat', $customer->getApartament());

              $xmlCustomer=$xmlOrder->addChild('customer');
              $xmlCustomer->addAttribute('full_name', $customer->getName());
              $xmlCustomer->addChild('phone', $customer->getPhone());
              $xmlCustomer->addChild('email', $customer->getEmailAddress());

              $xmlOrder->addChild('comment', $order->getComments());

            $xmlOrder->addAttribute('customer_login', $customer->getUsername());//Когда будет пользователь

            $xmlOrder->addAttribute('ref_partner_id', '');//Уточнить
            $xmlOrder->addAttribute('partner_id', 46);//Уточнить
            $xmlOrder->addAttribute('shop_id', 11);
            $xmlOrder->addAttribute('coupon', $order->getCoupon());
            $xmlOrder->addAttribute('bonuspay', $order->getBonuspay());
            $products=unserialize($order->getFirsttext());
            $orderSum=0;
              $xmlProducts=$xmlOrder->addChild('products');
              foreach ($products as $product) {
                //Посмотреть как рулится доставка
                $productDB=ProductTable::getInstance()->findOneById($product['productId']);
                if(!is_object($productDB)) {
                  if($this->isTest) die (print_r($product, true));
                  continue;
                }
                $xmlProduct=$xmlProducts->addChild('product');
                $xmlProduct->addChild('title', $productDB->getName());
                $xmlProduct->addAttribute('id', $product['productId']);
                $xmlProduct->addAttribute('article', $productDB->getCode());
                $xmlProduct->addAttribute('count', $product['quantity']);
                $xmlProduct->addAttribute('price', $product['price']);
                $xmlProduct->addAttribute('price_w_discount', $product['price_w_discount']);
                $xmlProduct->addAttribute('price_w_bonuspay', $product['price_w_bonuspay']);
                $xmlProduct->addAttribute('price_final', $product['price_final']);
                $xmlProduct->addAttribute('discount', isset($product['discount']) ? $product['discount'] : 0);
                $xmlProduct->addAttribute('weight', $productDB->getWeight());
                $xmlProduct->addAttribute('id1c', $productDB->getid1c());
              }
            // die("\n--------------------------".print_r($products, true)."\n");
            $xmlOrder->addAttribute('total_price', $order->getFirsttotalcost()+$order->getDeliveryPrice());
          }
          //а теперь все сохраняем
          $confXmlText=$confXml->asXML();
          $xmlText=$xml->asXML();
          $xmlText=str_replace(['<order', '<product'], ["\n<order", "\n<product"], $xmlText);
          $confXmlText=str_replace(['<order', '<product'], ["\n<order", "\n<product"], $confXmlText);
          file_put_contents($localPath.$filename2, $confXmlText);
          file_put_contents($localPath.$filename, $xmlText);
          if(!$this->isTest){
            ftp_put($this->ftpConnection, $filename, $localPath.$filename,  FTP_BINARY);
            $this->myLog("Отправили на FTP");
            ftp_put($this->ftpConnection, $filename2, $localPath.$filename2,  FTP_BINARY);
            $this->ftpDisconnect();
          }
          // die($xml->asXml()."\n");
          // die('==='."\n");


          break;

        case 'orders_shop':
          $this->myLog("Пытаемся импортировать чеки магазинов");
          // if(!$this->isTest)
            $this->ftpConnect();
          $ftpFile='checks.xml';
          // if(!$this->isTest)
          ftp_get($this->ftpConnection, $localPath.$ftpFile, $ftpFile, FTP_BINARY);
          $xml = simplexml_load_string(file_get_contents( $localPath.$ftpFile ), 'SimpleXMLElement', LIBXML_NOCDATA);
          $this->ftpDisconnect();
          if ($xml===false) die('Ошибка разбора файла '.$ftpFile."\n");

          sfContext::createInstance($this->configuration);

          foreach ($xml->checks->check as $order) {

              $orderShop = OrdersShopTable::getInstance()->createQuery()->where('dopid=?', (string) $order->attributes()->id)->addwhere('checknumber=?', str_replace(" ", '', (string) $order->attributes()->number))->addwhere('price=?', (string) $order->attributes()->total_price)->fetchOne();
              if (!$orderShop) {
                  $orderShop = new OrdersShop();
              }

              $prodsSetDb = array();
              foreach ($order->products->product as $product) {

                  $productDb = ProductTable::getInstance()->findOneByCode((string) $product->attributes()->article);
                  $prodSetDb = array();
                  if ($productDb) {
                      $prodSetDb['productId'] = $productDb->getId();
                  }
                  $prodSetDb["quantity"] = (string) $product->attributes()->count;
                  $prodSetDb["price"] = (string) $product->attributes()->price_w_discount;
                  $prodSetDb["article"] = (string) $product->attributes()->article;
                  $prodSetDb['title'] = trim((string) $product->title);
                  $prodsSetDb[] = $prodSetDb;
              }
              $orderShop->set('text', serialize($prodsSetDb));


              if ($order->attributes()->email != "") {
                  $findUserThisOrder = sfGuardUserTable::getInstance()->findOneByEmailAddress((string) $order->attributes()->email);
                  if ($findUserThisOrder) {
                      if (!$orderShop->getActive()) {
                          $total_price = (string) $order->attributes()->total_price;
                          $bonusCount = round(($total_price * csSettings::get("bonus_percent_shop")) / 100);
                          $bonusSet = new Bonus();
                          $bonusSet->setBonus($bonusCount);
                          $bonusSet->setComment("Зачисление за покупку в магазине. Номер чека " . str_replace(" ", '', (string) $order->attributes()->number) . ". Сумма: " . ((string) $order->attributes()->total_price) . "");
                          $bonusSet->setUserId($findUserThisOrder);
                          $bonusSet->save();

                          if (filter_var(trim((string) $order->attributes()->email), FILTER_VALIDATE_EMAIL)) {
                              $message = Swift_Message::newInstance()
                                      ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                                      ->setTo(trim((string) $order->attributes()->email))
                                      ->setSubject("Уведомления о зачисление бонусов на сайте onona.ru")
                                      ->setBody(str_replace(array('{firstname}', '{shop}', '{bonus}', '{perbonus}', "\r\n"), array($findUserThisOrder->getName(), "OnOna.ru", $bonusCount, csSettings::get("PERSENT_BONUS_PAY"), "<br />"), csSettings::get("html_mail_add_bonus")), 'text/html')
                                      ->setContentType('text/html');


                              $numSent = $this->getMailer()->send($message);
                              sleep(1);
                          }

                          $orderShop->setActive(true);
                          $orderShop->setDateactive(date("Y-m-d H:i:s", time()));
                      }
                  }
              }
              $orderShop->set('dopid', (string) $order->attributes()->id);
              $orderShop->set('date', (string) $order->attributes()->date);
              $orderShop->set('checknumber', str_replace(" ", '', (string) $order->attributes()->number));

              $orderShop->set('smena', (string) $order->attributes()->smena);
              $orderShop->set('discountcard', (string) $order->attributes()->discountcard);
              $orderShop->set('cardownername', (string) $order->attributes()->cardownername);
              $orderShop->set('cardowner', (string) $order->attributes()->cardowner);
              $orderShop->set('price', (string) $order->attributes()->total_price);
              $orderShop->save();
          }
          $this->myLog("Импорт чеков магазинов - успешно");


          break;

        default:
          echo "Отсутствует режим работы. Никаких действий не выполнено\n";
      }
      // echo print_r(['args' => $arguments, 'opts'=> $options], true);
      echo "\n";
    }

    private function sendLetterAtChangeStatus($product){//Отправить письмо при поступлении товара
      $status = 'notify';
      $template=MailTemplatesTable::getInstance()->findOneBySlug($status);

      $text = $template->getText();

      $titlemail=$template->getSubject();
      $productText='<strong><a href="https://onona.ru/product/'.$product->getSlug().'">'.$product->getName().'</a></strong>';

      $sqlBody="SELECT * FROM `notify` WHERE `product_id`=".$product->getId();
      $text = str_replace('{product}', $productText, $text);
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      sfContext::createInstance($this->configuration);
      $result = $q->execute($sqlBody);
      $table=$result->fetchAll(Doctrine_Core::FETCH_ASSOC);
      // print_r($sqlBody);
      if(sizeof($table)) foreach($table as $user){
        $emailText=str_replace('{nameCustomer}', $user['name'], $text);
        // echo $user['email']."\n";
        if($this->is_email($user['email'])){
          try {
            $message = Swift_Message::newInstance()
            ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
            ->setTo($user['email'])
            ->setSubject($titlemail)
            ->setBody($emailText, 'text/html')
            ->setContentType('text/html')
            ;
            $this->getMailer()->send($message);
          } catch (\Exception $e) { }
          $ids[]=$user['id'];

        }
        // print_r($user);
      }
      if(!empty($ids)) {
        $sqlBody="DELETE FROM `notify` WHERE `id` IN(".implode(', ', $ids).")";
        $result = $q->execute($sqlBody);
        // echo $sqlBody."\n";
      }
      // die();



        // $template = str_replace('{linkOprosnik}', '<a href="' . $link . '" style="color:white;text-decoration:none;line-height:60px;text-transform:uppercase;padding:0 30px;font-weight: bold;background: #fb0605;display: inline-block;">Оценить работу магазина</a>', $template->getContent());
        // $template = str_replace('{orderId}', $orderDB->getId(), $template);
        // $template = str_replace('{name}', $customer->get('first_name'), $template);


      // die("try to send letters\n");
    }

    private function setBonusAtChangeStatus($orderDB, $status, $arOrderProducts, $prodXml){//Проверяем статус для начисления бонусов
      // $this->myLog("Начисляем бонусы:");
      if ((mb_strtolower($status, 'UTF-8') == "оплачен" and mb_strtolower($orderDB->getStatus(), 'UTF-8') != "оплачен")) {
        $persent_bonus_add=csSettings::get('persent_bonus_add');
        $persent_bonus_pay=csSettings::get('PERSENT_BONUS_PAY');
        // $this->myLog("Статус изменился на нужный");
        // $this->myLog("Процесс зачисления:".$persent_bonus_add);
        // $this->myLog("Сколько процентов может использовать для оплаты :".$persent_bonus_pay);

        $bonusSum = 0;
        foreach ($arOrderProducts as $productForBonus) {
          $artInfoForBonus=$prodXml[$productForBonus->getCode()];
          // $this->myLog("Содержимое заказа ".print_r($artInfoForBonus, true));
          $linePrice = ($artInfoForBonus['discount']==100 ||  $artInfoForBonus['price_w_discount']>0) ? $artInfoForBonus['price_w_discount'] : $artInfoForBonus['price'];
          if(isset($artInfoForBonus['price_final'])) $linePrice = $artInfoForBonus['price_final'];
          if ($productForBonus->getBonus() !== null) {
              $bonusSum += round( $linePrice * $artInfoForBonus['count'] * $productForBonus->getBonus() / 100);
          } else {
              $bonusSum += round( $linePrice * $artInfoForBonus['count'] * $persent_bonus_add / 100);
          }
        }
        // $this->myLog("За заказ ".$orderDB->getId().' будет начислено '.$bonusSum);
        $haveBonus=false;
        $bonusList=Doctrine_Core::getTable('Bonus')
          ->createQuery('b')
          // ->where("comment like '%Зачисление за заказ #".($orderDB->getPrefix())  . "197%'")
          ->where("comment like '%Зачисление за заказ #".($orderDB->getPrefix()) . $orderDB->getId() . "%'")
          ->execute();
        foreach ($bonusList as $bonus) {
          $haveBonus=true;
          break;//достаточно одной записи
        }
        if(!$haveBonus){
          // $this->myLog("За заказ бонусы начисляем");
          $bonus= new Bonus();
          // $bonus->set('created_at', time());
          $bonus->set('comment', "Зачисление за заказ #" . $orderDB->getPrefix() . $orderDB->getId() );
          $bonus->set('bonus', $bonusSum);
          $bonus->set('user_id', $orderDB->getCustomerId());
          $bonus->save();
          $SITE_NAME = "OnOna.ru";
          $html_mail_add_bonus=csSettings::get('html_mail_add_bonus');

          $customer=sfGuardUserTable::getInstance()->findOneById($orderDB->getCustomerId());

          $html_mail_add_bonus = str_replace(
            array('{firstname}', '{lastname}', '{bonus}', '{shop}', '{perbonus}'),
            array($customer->get('first_name'), $customer->get('last_name'), $bonusSum, $SITE_NAME, $persent_bonus_pay), $html_mail_add_bonus);

          try {
            $message = Swift_Message::newInstance()
              ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
              ->setTo($customer->getEmailAddress())
              ->setSubject(csSettings::get("subject_bonus_add"))
              ->setBody(nl2br($html_mail_add_bonus), 'text/html')
              ->setContentType('text/html')
            ;
            $this->getMailer()->send($message);

          } catch (\Exception $e) {
            $this->myLog("Ошибка отправки почты");
          }

        }
        else{
          // $this->myLog("Бонусы уже есть");
        }

      }
    }

    private function sendMailForChangeStatus($orderDB, $status){//Отправляем письмо при смене статуса
      // $this->myLog("Отправляем письма, если надо:");
      if (mb_strtolower($status) != mb_strtolower($orderDB->getStatus())) {
        $template=MailchangestatusTable::getInstance()->findOneByStatus($status);
        if($template && $template->getIsPublic()){
          $sqlBody = "DELETE FROM `oprosnik` WHERE orderid=".$orderDB->getId();
          // $this->myLog($sqlBody);
          // $this->myLog(print_r([$template->getTitleMail(), $status], true));

          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute($sqlBody);

          $templateName = $template->getStatus();
          $titlemail=$template->getTitlemail();
          $customer=sfGuardUserTable::getInstance()->findOneById($orderDB->getCustomerId());

          $link = 'http://www.onona.ru/oprosnik/' . md5($orderDB->getId());
          $template = str_replace('{linkOprosnik}', '<a href="' . $link . '" style="color:white;text-decoration:none;line-height:60px;text-transform:uppercase;padding:0 30px;font-weight: bold;background: #fb0605;display: inline-block;">Оценить работу магазина</a>', $template->getContent());
          $template = str_replace('{orderId}', $orderDB->getId(), $template);
          $template = str_replace('{name}', $customer->get('first_name'), $template);
          $SITE_NAME = "OnOna.ru";
          try {
            $message = Swift_Message::newInstance()
              ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
              ->setTo($customer->getEmailAddress())
              ->setSubject($titlemail)
              ->setBody($template, 'text/html')
              ->setContentType('text/html')
            ;
            $this->getMailer()->send($message);

            $mlog = new Mailsendlog();
            $mlog->setComment("В связи с изменением статуса заказа. <br> №" . (csSettings::get('order_prefix')) . $orderDB->getId() . "<br> Новый статус: " . $status . "<br> Шаблон: " . ($templateName) . "<br> Почта: " . ($customer->getEmailAddress()));
            $mlog->save();

          } catch (\Exception $e) {
            $this->myLog("Ошибка отправки почты");
          }

          // die('-----------------------');
        }
      }
    }

    private function isUpToDate($prodBase, $product, $needLog=false){//Проверяем, что у товара изменилось хоть что-то
      if (isset($product['weight']))
        if($prodBase->getWeight()!=$product['weight']) {
          return false;
        }

      // if (isset($product['attributes']))
      if($prodBase->getStock()!=$product['attributes']) {
        return false;
      }

      //Так как остатки могут приходить из разных файлов - проверяем оба
      if(isset($product['in-stock'])){
        if($prodBase->getCount()!=$product['in-stock']) {
          return false;
        }
      }
      else{
        if($prodBase->getCount()!=$product['stock']) {
          return false;
        }
      }



      if(isset($product['sale-price'])){
        if(
          $product['price']==$product['sale-price']
          && $product['price']==$prodBase->getPrice()
          && !$prodBase->getDiscount()
          && !$prodBase->getOldPrice()
        )
          return true;

        $discount=round((100*($product['price']-$product['sale-price'])/$product['price']));
        $price=round($product['price']*(1-$discount/100));

        if($prodBase->getPrice()!=$price || $prodBase->getOldPrice()!=$product['price']) {
          /*
          $this->myLog('sale-price'.print_r([
            $prodBase->getPrice(),
            $prodBase->getOldPrice(),
            $price,
            $discount,
            $product], true)
          );
          die('sale');*/
          return false;
        }
      }
      else{
        if($prodBase->getPrice()!=$product['price'] || $prodBase->getDiscount() || $prodBase->getOldPrice() ) {
          return false;
        }
      }

      /*if($needLog)
        $this->myLogItems('-----------------------------------'.print_r(
          [
            '$product'=>$product,
            '$prodBase'=>[
              'getWeight' => $prodBase->getWeight(),
              'getStock' => $prodBase->getStock(),
              'getCount' => $prodBase->getCount(),
              'getPrice' => $prodBase->getPrice(),
              'getOldPrice' => $prodBase->getOldPrice(),
              '$price' => $price,
              '$discount' => $discount,

            ]
          ], true
        ));*/

      return true;
    }

    private function ftpConnect(){
      $connId = ftp_connect($this->ftpServer)
        or die ("Невозможно подключиться к ftp-серверу ".$this->ftpServer."\n");
      $loginResult=ftp_login($connId, $this->ftpLogin, $this->ftpPass);
      ftp_pasv($connId, true);
      if ((!$connId) || (!$loginResult))
        die("FTP Connection Failed");
      $this->ftpConnection=$connId;
    }

    private function ftpDisconnect(){
      ftp_close($this->ftpConnection);
    }

    private function myLogItems($text='', $filename='sync_catalog_log.txt', $rewrite=false){
      // if ($this->isTest) echo $text."\n";
      if (false) echo $text."\n";
      else file_put_contents(str_replace('lib/task', '', __DIR__). 'web/'.$filename, "\n".$text, !$rewrite ? FILE_APPEND : false);
    }

    private function myLog($text='', $filename='sync_log.txt'){
      if ($this->isTest) echo $text."\n";
      else file_put_contents(str_replace('lib/task', '', __DIR__). $filename, "\n---------------------\n".date('d.m.Y H:i')."\n".$text, FILE_APPEND);
    }

    private function is_email($email) {
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          return true;
      } else {
          return false;
      }
    }
}
