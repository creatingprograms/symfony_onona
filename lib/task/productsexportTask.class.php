<?php

class productsexportTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "newcat"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('mode', null, sfCommandOption::PARAMETER_REQUIRED, 'mode', 'test'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'productsexport';
        $this->briefDescription = 'Формирует файл csv в зависимости от переданного режима';
        $this->detailedDescription = <<<EOF
The [productsexport|INFO] Формирует файл csv в зависимости от переданного режима
Call it with:

  [php symfony productsexport|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $isTest =false;
        // $isTest =true;
        // if ($isTest) echo "\n---------------------- Products export --------------------------\n";
        if ($isTest) echo "Started at ".date('H:i:s')."\n";

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        // $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        ini_set("max_execution_time", 1800);
        $delimiter='|';
        $localPath=str_replace('lib/task', '', __DIR__).'web/';

        switch($options['mode']){

          case '162094':
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $sqlBody="INSERT IGNORE INTO `coupons` (`startaction`, `endaction`, `is_active`, `text`, `discount_sum`, `min_sum`) VALUES ";
            for($i=0; $i< ($isTest ? 3 : 1000); $i++){
              $coupons[]="('2020-12-16 11:00', '2021-12-16 11:00', 1, 'NYSL".sprintf("%'.03d", $i)."', 1000, 3000)";
            }
            $sqlBody.=implode(', ', $coupons);
            echo $sqlBody."\n";
            $q->execute($sqlBody);
            break;

          case '157815':
            $filename='products-157815';
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $catId=256;//Распродажа на dev
            $catId=269;//Распродажа на хосте
            echo "\n---------------------- Products export for task 157815 --------------------------\n";
            $result=[implode($delimiter, [
              'Артикул',
              'Остаток (придет из 1С)',
              'Старая цена (придет из 1С)',
              'Цена (придет из 1С)',
              'Для пар',
              'Для нее',
              'Для него',
              'БДСМ',
              'Косметика',
              'Белье',
              'Разное',
              'Название (игнорируется при импорте)',
            ])];
            $sqlBody=
              "SELECT `name`, `code`, `price`, `old_price`, `count`, `for_pairs`, `bdsm`, `for_she`, `for_her`, `cosmetics`, `belie`, `other` "
              ."FROM `product` p "
              ."LEFT JOIN `category_product` cp ON p.`id`=cp.`product_id` "
              ."WHERE cp.`category_id` = $catId "
              ."";
            $rsProducts = $q->execute($sqlBody);
            $rsProducts->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $products=$rsProducts->fetchAll();
            foreach ($products as $product) {
              $result[]=implode($delimiter,[
                $product['code'],
                $product['count'],
                $product['old_price'],
                $product['price'],
                $product['for_pairs'],
                $product['for_she'],
                $product['for_her'],
                $product['bdsm'],
                $product['cosmetics'],
                $product['belie'],
                $product['other'],
                $product['name'],
              ]);
            }
            // echo $sqlBody."\n";
            echo $localPath.$filename.'.csv'."\n";
            file_put_contents($localPath.$filename.'.csv', implode("\r\n",$result));
            unset($result);
            break;

          case '156173':
            $filename='products-156173';
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            echo "\n---------------------- Products export for task 156173 --------------------------\n";
            $manufacturers=[
              'jo-system-ssha',
              'pjur-germaniya',
              'real',
              // '',//хэппи егг
              'womanizer-germaniya',
              'romp-germania',
              // ''//canicule
              // '',
              // '',
            ];
            $result=[implode($delimiter, [
              'ID',
              'Название',
              'Производитель',
              // 'Урл-товара',
              // 'Стоимость сегодня',
              // 'Код',
              'Артикул',
              // 'Текст',
              'Цена',
              // 'Старая цена',
              'Остаток',
              // 'Дата добавления',
              // 'Дата изменения',
              'Доступность',
              // 'Главная категория',
              // 'Видео',
              // 'Фото',
              // 'Свойства'
            ])];
            foreach ($manufacturers as $manufacturerSlug) {

              echo "try to find $manufacturerSlug\n";
              $manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySlug($manufacturerSlug);
              if (is_object($manufacturer)) {
                  $idFind = $manufacturer->getSubid();
              } else {
                echo "not found $manufacturerSlug, skip\n";
                continue;
                  $manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySubid($manufacturerSlug);
                  $idFind = $manufacturerSlug;
              }
              if (!$idFind) {
                if ($isTest) echo "No entry '$manufacturerSlug', skip\n";
                continue;
              }
              $dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);
              /*if ($isTest)*/ echo "items for entry '".$manufacturer->getName()."', idFind = $idFind\n";
              $dopinfo = Doctrine_Core::getTable('DopInfo')->findOneById($idFind);
              if (!$dopinfo){
                if ($isTest) echo "No dopinfo '$idFind', skip\n";
                continue;
              }

              if ($dopinfo->getDopInfoCategory()->getName() != "Производитель"){
                if ($isTest) echo "Dopinfo '$idFind' is not manufacturer, skip\n";
                continue;
              }
              $dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);
              if(!count($dopinfoProduct)){
                if ($isTest) echo "DopInfoProduct '$idFind' not found, skip\n";
                continue;
              }
              foreach ($dopinfoProduct as $key => $product) {
                // $tableTmp[$key]=$product;
                $prod[]=$product->get('product_id');
              }
              $prod[]=-1;
              $sqlBody=
                "SELECT * from product p ".
                // "LEFT JOIN category c ON c.id=p.generalcategory_id ".
                "WHERE p.id IN(".implode(',', $prod).") ".
                "";
              $db_result = $q->execute($sqlBody);
              $db_result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
              $tmp = $db_result->fetchAll();
              foreach($tmp as $product){
                $sqlBody="SELECT filename FROM product_photoalbum pp LEFT JOIN photo ON photo.album_id=pp.photoalbum_id WHERE product_id=".$product['id'];
                $db_result = $q->execute($sqlBody);
                $db_result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
                $tmp2 = $db_result->fetchAll();
                unset ($photos);
                foreach($tmp2 as $photo){
                  $photos[]='https://onona.ru/uploads/photo/'.$photo['filename'];
                }
                $result[$product['id']]=implode($delimiter,[
                  $product['id'],
                  $product['name'],
                  $manufacturer->getName(),
                  $product['code'],
                  // $product['is_public'] ? 'https://onona.ru/product/'.$product['slug'] : 'На сайте не представлен',
                  $product['price'],
                  // $product['slug'],
                  // str_replace([$delimiter, "\n","\r"], ['', ""], $product['content']),
                  // $product['old_price'],
                  $product['count'],
                  // $product['created_at'],
                  // $product['updated_at'],
                  $product['is_public'],
                  // $product['c_name'],
                  // ($product['video'] ? 'https://onona.ru/uploads/video/'.$product['video'] : ''),
                  // implode(';', $photos),
                ]);

              }
              // if ($isTest) echo "prod: '".print_r($result, true)."'\n";

              // if ($isTest) echo "prod: '".implode(',', $prod)."'\n";
              unset($prod);
            }
            echo $localPath.$filename.'.csv';
            file_put_contents($localPath.$filename.'.csv', implode("\r\n",$result));
            unset($result);

            break;

          case '154181':
            $filename='products-154181';
            echo "\n---------------------- Products export for task 154181 --------------------------\n";
            // $isTest =true;
            if(!$isTest) $localPath=str_replace('lib/task', '', __DIR__).'onona.ru/';
            $result=[implode($delimiter, [
              // 'ID',
              'Название',
              'Цена',
              'Урл-товара',
              'Категория',
              // 'Артикул',
              // 'Скидка',
              // 'Старая цена',
              // 'Остаток',
              // 'Доступен',
              // 'Синхронизируется с партнеркой',
            ])];
            $prodBase=Doctrine_Core::getTable('Product')->findAll();
            $i=0;
            foreach ($prodBase as $product) {
              if($product->getParentsId()) continue;
              if(!$product->getIsPublic()) continue;
              if($isTest && $i++> 5) break;
              $result[$product->getId()]=implode($delimiter,[
                // $product->getId(),
                $product->getName(),
                $product->getPrice(),
                $product->getIsPublic() ? 'https://onona.ru/product/'.$product->getSlug() : 'На сайте не представлен',
                $product->getGeneralcategoryId(),
                // $product->getCode(),
                // $product->getDiscount(),
                // $product->getOldPrice(),
                // $product->getCount(),
                // $product->getIsPublic(),
                // $product->getSync(),

              ]);
            }
            echo $localPath.$filename.'.csv';
            file_put_contents($localPath.$filename.'.csv', implode("\r\n",$result));
            unset($result);
            if ($isTest) die( "\nEnded at ".date('H:i:s')."\n"."-----------------------------------------------------------------\n\n");

            break;
          case '147647':
            $isTest =false;
            echo "\n---------------------- Products export for task 147647 --------------------------\n";
            $result=[implode($delimiter, [
              'ID',
              'Название',
              'Урл-товара',
              'Код',
              // 'Артикул',
              'Цена',
              'Скидка',
              'Старая цена',
              'Остаток',
              'Доступен',
              'Синхронизируется с партнеркой',
            ])];
            $prodBase=Doctrine_Core::getTable('Product')->findAll();
            $i=0;
            foreach ($prodBase as $product) {
              if($isTest && $i++> 5) break;
              $result[$product->getId()]=implode($delimiter,[
                $product->getId(),
                $product->getName(),
                $product->getIsPublic() ? 'https://onona.ru/product/'.$product->getSlug() : 'На сайте не представлен',
                $product->getCode(),
                $product->getPrice(),
                $product->getDiscount(),
                $product->getOldPrice(),
                $product->getCount(),
                $product->getIsPublic(),
                $product->getSync(),

              ]);
            }
            if ($isTest) file_put_contents('/home/i9s/p702/run/web/products-export-'.'all'.'.csv', implode("\r\n",$result));
            else file_put_contents('/var/www/ononaru/data/www/onona.ru/products-export-'.'all'.'.csv', implode("\r\n",$result));
            unset($result);
            if ($isTest) die( "\nEnded at ".date('H:i:s')."\n"."-----------------------------------------------------------------\n\n");

            break;

          
          case '199843':
            $localPath = str_replace('lib/task', '', __DIR__) . '';
            $filename = 'cardsmobile-users.csv';
            
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();

            $sqlBody = "SELECT cm.`id`, cm.`user_id`, cm.`phone`, cm.`email`, cm.`sex`, cm.`user_name`, cm.`user_family`, cm.`user_subname`, cm.`birthday` FROM `cardsmobile` cm WHERE cm.`is_reserved` = 0;";
            $arCards[] = implode($delimiter, ['Номер', 'ID пользователя', 'Телефон', 'Email', 'Пол', 'Имя', 'Фамилия', 'Отчество', 'День рождения']);

            $db_result = $q->execute($sqlBody);
            $db_result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $cards = $db_result->fetchAll();

            foreach($cards as $card) $arCards[] = implode($delimiter, $card);

            $data = implode("\n", $arCards);
            file_put_contents($localPath.$filename, $data);
            

            $sqlBody = "SELECT cm.`id`, cm.`user_id`, cm.`phone`, cm.`email`, cm.`sex`, cm.`user_name`, cm.`user_family`, cm.`user_subname`, cm.`birthday`, COUNT(o.`id`) AS `count_orders`, SUM(o.`firsttotalcost`) AS `sum_orders` FROM `cardsmobile` cm LEFT JOIN `orders` o ON cm.`user_id` = o.`customer_id` WHERE cm.`is_reserved` = 0 AND o.`status`= 'оплачен' GROUP BY cm.`id`, cm.`user_id`, cm.`phone`, cm.`email`, cm.`sex`, cm.`user_name`, cm.`user_family`, cm.`user_subname`, cm.`birthday`;";
            $filename = 'cardsmobile-orders.csv';
            unset($arCards);
            $arCards[] = implode($delimiter, ['Номер', 'ID пользователя', 'Телефон', 'Email', 'Пол', 'Имя', 'Фамилия', 'Отчество', 'День рождения', 'Количество оплаченных заказов', 'Первоначальная сумма заказов']);
            
            $db_result = $q->execute($sqlBody);
            $db_result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $cards = $db_result->fetchAll();

            foreach($cards as $card) $arCards[] = implode($delimiter, $card);

            $data = implode("\n", $arCards);

            file_put_contents($localPath.$filename, $data);

            die ("Файлы записаны" . "\n");
            break;

          case '191053':
            $i=0;
            define('SLUG', 1);
            define('TYPE', 3);
            define('LINK', 5);
            $filename='onona-shops.csv';

            $lines = explode("\n", file_get_contents($localPath.$filename));

            foreach ($lines as $line) {
              $value = explode($delimiter, $line);
              $table[trim($value[SLUG])][trim($value[TYPE])] = trim($value[LINK]);
            }

            foreach ($table as $slug => $data) {
              $shop = Doctrine_Core::getTable('Shops')->findOneBySlug($slug);

              if(!is_object($shop)) continue;

              if(!empty($data['2gis'])) $shop->set2gis($data['2gis']);
              if(!empty($data['yandex'])) $shop->setYandex($data['yandex']);
              if(!empty($data['google'])) $shop->setGoogle($data['google']);

              $shop->save();
              $i++;
            }

            echo "Succesfully import shops qr-redirects data. $i shops affected\n";
            break;
            
          default:
            $manufacturers=[
              // 'satisfyer-germaniya',
              // 'womanizer-germaniya',
              // 'jo-system-ssha',
              'we-vibe-kanada',
              // 'fun-factory-germaniya',
              'california-exotic-novelties-ssha',
              'real',
              'shots-toys-niderlandy',
              // '',
              // '',
            ];/*
            $manufacturers=Doctrine_Core::getTable('Manufacturer')->findAll();*/
            $result=[implode($delimiter, [
              'ID',
              'Название',
              'Производитель',
              'Урл-товара',
              // 'Стоимость сегодня',
              // 'Код',
              // 'Артикул',
              // 'Текст',
              'Цена',
              'Старая цена',
              'Остаток',
              // 'Дата добавления',
              // 'Дата изменения',
              'Доступность',
              'Главная категория',
              // 'Видео',
              // 'Фото',
              // 'Свойства'
            ])];
            // foreach ($manufacturers as $manufacturer) {
            foreach ($manufacturers as $manufacturerSlug) {
            /*
              $result=[implode($delimiter, [
                'Название',
                'Урл-товара',
                'Стоимость сегодня',
                // 'Код',
                // 'Артикул',
                'ID',
                'Текст',
                'Цена',
                'Старая цена',
                'Остаток',
                'Дата добавления',
                'Дата изменения',
                'Доступность',
                'Главная категория',
                'Видео',
                'Фото',
                // 'Свойства'
              ])];*/
              echo "try to find $manufacturerSlug\n";
              $manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySlug($manufacturerSlug);
              if (is_object($manufacturer)) {
                  $idFind = $manufacturer->getSubid();
              } else {
                echo "not found $manufacturerSlug, skip\n";
                continue;
                  $manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySubid($manufacturerSlug);
                  $idFind = $manufacturerSlug;
              }
              if (!$idFind) {
                if ($isTest) echo "No entry '$manufacturerSlug', skip\n";
                continue;
              }
              $dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);
              /*if ($isTest)*/ echo "items for entry '".$manufacturer->getName()."', idFind = $idFind\n";
              $dopinfo = Doctrine_Core::getTable('DopInfo')->findOneById($idFind);
              if (!$dopinfo){
                if ($isTest) echo "No dopinfo '$idFind', skip\n";
                continue;
              }

              if ($dopinfo->getDopInfoCategory()->getName() != "Производитель"){
                if ($isTest) echo "Dopinfo '$idFind' is not manufacturer, skip\n";
                continue;
              }
              $dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);
              if(!count($dopinfoProduct)){
                if ($isTest) echo "DopInfoProduct '$idFind' not found, skip\n";
                continue;
              }
              foreach ($dopinfoProduct as $key => $product) {
                // $tableTmp[$key]=$product;
                $prod[]=$product->get('product_id');
              }
              $prod[]=-1;
              $sqlBody=
                "SELECT p.*, c.name as c_name from product p ".
                "LEFT JOIN category c ON c.id=p.generalcategory_id ".
                "WHERE p.id IN(".implode(',', $prod).") ".
                "";
              $db_result = $q->execute($sqlBody);
              $db_result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
              $tmp = $db_result->fetchAll();
              foreach($tmp as $product){
                $sqlBody="SELECT filename FROM product_photoalbum pp LEFT JOIN photo ON photo.album_id=pp.photoalbum_id WHERE product_id=".$product['id'];
                $db_result = $q->execute($sqlBody);
                $db_result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
                $tmp2 = $db_result->fetchAll();
                unset ($photos);
                foreach($tmp2 as $photo){
                  $photos[]='https://onona.ru/uploads/photo/'.$photo['filename'];
                }
                $result[$product['id']]=implode($delimiter,[
                  $product['id'],
                  $product['name'],
                  $manufacturer->getName(),
                  $product['is_public'] ? 'https://onona.ru/product/'.$product['slug'] : 'На сайте не представлен',
                  $product['price'],
                  // $product['slug'],
                  // $product['code'],
                  // str_replace([$delimiter, "\n","\r"], ['', ""], $product['content']),
                  // $product['price'],
                  $product['old_price'],
                  $product['count'],
                  // $product['created_at'],
                  // $product['updated_at'],
                  $product['is_public'],
                  $product['c_name'],
                  // ($product['video'] ? 'https://onona.ru/uploads/video/'.$product['video'] : ''),
                  // implode(';', $photos),
                ]);

              }
              // if ($isTest) echo "prod: '".print_r($result, true)."'\n";

              // if ($isTest) echo "prod: '".implode(',', $prod)."'\n";
              unset($prod);
            }
            if ($isTest) file_put_contents('/home/i9s/p702/run/web/products-export-'.'all'.'.csv', implode("\r\n",$result));
            else file_put_contents('/var/www/ononaru/data/www/onona.ru/products-export-'.'all'.'.csv', implode("\r\n",$result));
            unset($result);
            if ($isTest) die( "\nEnded at ".date('H:i:s')."\n"."-----------------------------------------------------------------\n\n");
        }
        // add your code here



    }

}
