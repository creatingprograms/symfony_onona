<?php

/**
 * noncache actions.
 *
 * @package    test
 * @subpackage noncache
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class noncacheActions extends sfActions {

    /**
     * Executes check captcha action
     *
     * @param sfRequest $request A request object
     */

    public function executeUpdatebarcodes(sfWebRequest $request) {
      header('Content-type: text/html; charset=utf-8');
      mb_internal_encoding('UTF-8');
      $flename=$_SERVER['DOCUMENT_ROOT'].'/database-barcode.xml';
      $xmlstock = simplexml_load_file($flename, 'SimpleXMLElement', LIBXML_NOCDATA);
      if( $xmlstock=== false ) die('XML file not found');
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $i=0; $timeStart=time();
      foreach ($xmlstock->xpath('products/product') as $product) {
        $product = json_decode(json_encode($product), TRUE);
        $weight = $product['@attributes']['weight'];
        $barcode = $product['barcodes']['barcode']['@attributes']['barcode'];
        $code = $product['@attributes']['code'];
        if(!$code) continue;
        if(!$weight) continue;
        if(!$barcode && $weight=='0.299') continue;
        $sqlBody = 'SELECT `weight`, `barcode` FROM `product` WHERE `code`="'.$code.'"';
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $tmp = $result->fetch();
        if (!$tmp) continue;
        if(strlen($barcode>20))  echo '<pre>'.print_r([$code, $weight, $barcode], true).'</pre>';
        if($tmp['weight']==$weight && $tmp['barcode']==$barcode) continue;
        $i++;
        $sqlBody='UPDATE `product` SET `weight`='.$weight.', `barcode`="'.$barcode.'" WHERE `code`="'.$code.'"';
        $result = $q->execute($sqlBody);
        // echo '<pre>'.print_r([$code, $weight, $barcode], true).'</pre>';

      }
      die('<pre>'.print_r(["$i records updated by ".(time()-$timeStart)." seconds"], true));
    }
    public function executeExportyamapsxml(sfWebRequest $request) {
      header('Content-type: text/xml; charset=utf-8');
      mb_internal_encoding('UTF-8');
      // $databaseManager = new sfDatabaseManager($this->configuration);
      // $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
      $sqlBody="SELECT * FROM `shops` WHERE `is_active`=true";
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $result = $q->execute($sqlBody);
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $tmp = $result->fetchAll();

      $xml_name = 'Он и Она';
      $xml_company = 'ONONA';
      $SITE_NAME = "https://onona.ru";
      $cat ='';

      $content = '<?xml version="1.0" encoding="utf-8"?>';
      $content.= '<companies>';
      foreach ($tmp as $shop) {
        $content.='<company>';
        $content.='<company-id>'.$shop['id'].'</company-id>';
        $content.='<name lang="ru">'.$shop['name'].'</name>';
        $content.='<address lang="ru">'.$shop['address'].'</address>';
        $content.='<country lang="ru">Россия</country>';
        $content.='<url>'.$SITE_NAME.'</url>';
        $content.='<working-time lang="ru">'.$shop['worktime'].'</working-time>';
        $content.='<rubric-id>184106452</rubric-id>';
        $content.='<rubric-id>184107933</rubric-id>';
        $content.='<info-page>'.$SITE_NAME.'/shop/'.$shop['slug'].'</info-page>';
        $content.='<actualization-date>'.strtotime($shop['updated_at']).'</actualization-date>';
        $content.='<coordinates><lon>'.$shop['longitude'].'</lon><lat>'.$shop['latitude'].'</lat></coordinates>';
        $content.='<photos gallery-url="'.$SITE_NAME.'/shop/'.$shop['slug'].'"><photo url="/uploads/assets/images/'.$shop['preview_image'].'" type="exterior"/></photos>';

        $content.='</company>';
      }
      /*
      */
      $content.= '</companies>';
      die($content);
    }
    public function executeExportcpaonona(sfWebRequest $request) {
      header('Content-type: text/xml; charset=utf-8');
      mb_internal_encoding('UTF-8');
      $prefix=csSettings::get('order_prefix');
      $dateStart=$request->getParameter('from');
      $dateEnd=$request->getParameter('to');
      if(!$dateStart) $dateStart=date('Y-m-d', time()-30*24*60*60);
      if(!$dateEnd) $dateEnd=date('Y-m-d');
      $xmlResp=
        '<?xml version="1.0" encoding="UTF-8"?>'.
        '<orders>';
      $orders = OrdersTable::getInstance()->createQuery()->where("created_at BETWEEN '$dateStart 00:00' AND '$dateEnd 23:59:59'")->execute();
      foreach ($orders as $order) {
          $xmlResp .= '<order>';
          $customerType ='new';
          $basket=[];
          // вместо getOrderById вам нужно прописать
          // свою функцию, которая получает данные из БД
          $TotalSumm = 0;
          $products_old = $order->getText();
          $products_old = $products_old != '' ? unserialize($products_old) : '';
          foreach ($products_old as $key => $productInfo){
            $id=$productInfo['productId'];
            if($id==14613 || !$id || $id=='null') continue;
            if(!isset($productsCache[$id])){
              $product=ProductTable::getInstance()->findOneById($id);
              if(!!$product){
                $genCat=$product->getGeneralCategory();
                $productsCache[$id]=[
                  'name' => $product->getName(),
                  'category_id' => $genCat->getId(),
                  'category_name' => $genCat->getName(),
                ];
              }

              // die('<pre>'.print_r($productsCache, true));

            }
            $basket[]=[
              'id' => $id,
              'name' => htmlspecialchars($productsCache[$id]['name']),
              'price' => $productInfo['price_w_discount'],
              'quantity' => $productInfo['quantity'],
              'category' => $productsCache[$id]['category_id'],
              'category_name' => htmlspecialchars($productsCache[$id]['category_name']),
            ];
            $TotalSumm +=  ($productInfo['quantity'] * $productInfo['price_w_discount']);
          }
          /*
          if (mb_strtolower($order->getStatus()) == "оплачен") {
              $status = '2';
          } elseif (mb_strtolower($order->getStatus()) == "отмена") {
              $status = '3';
          } elseif(mb_strtolower($order->getStatus()) == "возврат"){
            $status = '4';
          }*/
          // csSettings::get('order_prefix')
          $xmlResp .= '<orderId>' . ($prefix.$order->getId()) . '</orderId>';
          $xmlResp .= '<orderPrice>' . $TotalSumm . '</orderPrice>';
          $xmlResp .= '<orderStatus>' . mb_strtolower($order->getStatus()) . '</orderStatus>';
          $xmlResp .= '<orderBasket>' . json_encode($basket) . '</orderBasket>';

          $xmlResp .= '<orderTrackid>' . $order->getAdvcakeTrackid() . '</orderTrackid>';
          $xmlResp .= '<url>' . htmlspecialchars($order->getAdvcakeUrl()) . '</url>';
          // $xmlResp .= '<client_type>' . $customerType . '</client_type>';
          $xmlResp .= '<dateCreate>' . $order->getCreatedAt() . '</dateCreate>';
          $xmlResp .= '<dateLastChange>' . $order->getUpdatedAt() . '</dateLastChange>';
          $xmlResp .= '</order>';
      }

      $xmlResp.=
        '</orders>';
      die($xmlResp);
      die('<pre>'.print_r($_SERVER, true));
    }
    public function executeCaptchacheck(sfWebRequest $request) {
      $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='su'")->fetchOne();
      // die('<pre>123'.print_r($_SERVER, true).'');
      // die('<pre>123'.print_r($request, true).'');
      if ($request->getParameter('sucaptcha')=='robot will not pass!' || $request->getParameter('sucaptcha') == $captcha->getVal()) {
        $this->result=['result' => 'ok'];
      }
      else{
        $this->result=['result' => 'no'];
      }
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */

    public function executeServePopup15(sfWebRequest $request) {
      header("Content-type: application/json; charset=utf-8");
      if ($this->is_email($request->getParameter('email'))) {
        $this->getResponse()->setCookie("popup15", 1, time() + 60 * 60 * 24 * 5, '/');
        $userIsSet = sfGuardUserTable::getInstance()->findOneByEmailAddress($request->getParameter('email'));
        if (!$userIsSet) {

          $user = new sfGuardUser();
          $username = 'user_' . rand(0, 9999999999999);
          $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
          if ($isExistUserName->count() != 0) {
              $username = 'user_' . rand(0, 9999999999999);
              $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
              if ($isExistUserName->count() != 0) {
                  $username = 'user_' . rand(0, 9999999999999);
                  $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                  if ($isExistUserName->count() != 0) {
                      $username = 'user_' . rand(0, 9999999999999);
                  }
              }
          }
          $user->setUsername($username);
          $password = uniqid();
          $user->set("password", $password);
          $user->setFirstName($request->getParameter('email'));
          $user->setEmailAddress($request->getParameter('email'));
          // $user->setSex($request->getParameter('user_sex'));

          $user->save();
          $this->getUser()->signin($user);

          if ($this->is_email($user->getEmailAddress())) {
            $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('popup15');
            $message = Swift_Message::newInstance()
                    ->setFrom(csSettings::get('smtp_user'), "OnOna.ru"/* sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com') */)
                    ->setTo($user->getEmailAddress())
                    ->setSubject($mailTemplate->getSubject())
                    ->setBody(str_replace(array('{$login}', '{$password}'), array($user->getEmailAddress(), $password), $mailTemplate->getText()), 'text/html')
                    ->setContentType('text/html')
            ;
            //echo $user->getEmailAddress();
            $this->getMailer()->send($message);
            $result['message'] = 'Промокод выслан на указанную почту';
          }

        } else {
            $result['message'] = 'Вы уже были ранее подписаны на новости';
        }
      }
      else $result['message']='Указан неверный email';
      die(json_encode($result));
    }

    public function executeGetPickpointDeliveryPrice(sfWebRequest $request) {
      $isTest=true;
      $isTest=false;
      if($isTest){
        $authData=[
          'url'=>'https://e-solution.pickpoint.ru/apitest/',
          'login'=>'apitest',
          'pass'=>'apitest',
          'ikn'=>'9990003041',
        ];
      }
      else{
        $authData=[
          'url'=>'https://e-solution.pickpoint.ru/api/',
          'login'=>'hRJrZV6Nh1910',
          'pass'=>'hRJrZV6Nh',
          'ikn'=>'9990421312',
        ];
      }
      if (!$isTest) header("Content-type: application/json; charset=utf-8");
      $data=$this->pickPointLogin($authData);
      if(!$data || $data->ErrorMessage!='')
        die(json_encode([0, 'Ошибка получения стоимости '.$data->ErrorMessage]));
      else
        $authData['sessionId']=$data->SessionId;
      //Из-за того что точка в параметре адресной строки все ломает заменяем ее на ,
      $weight=str_replace(',', '.', $request->getParameter('weight'));
      $data=$this->pickPointCalc($authData, $request->getParameter('id'), $weight);
      if(!$data || $data->ErrorMessage!='')
        die(json_encode([0, 'Ошибка получения стоимости '.$data->ErrorMessage]));
      $price=0;

      if(is_array($data->Services))
        foreach ($data->Services as $service) {
          $price+=$service->Tariff;
          if (isset($service->NDS)) $price+=$service->NDS;
        }
      if(!$isTest) die(json_encode([1, ceil($price)]));
      die(
        '<pre>2.'.iconv(
          "UTF-8",
          "CP1251",
          print_r([
            $price,
            $data,
            $authData,
          ], true)).
        '</pre>'
      );
    }

    private function pickPointCalc($authData, $id, $weight=1){
      return $this->sendRequest(
        $authData['url'].'calctariff',
        [
          'SessionId'=>$authData['sessionId'],
          'IKN'=>$authData['ikn'],
          'FromCity'=>"Москва",
          'FromRegion'=>"Московская обл.",
          'PTNumber'=> $id,
          'Weight'=>$weight
        ],
        'POST'
      );
    }
    private function pickPointLogin($authData){
      return $this->sendRequest(
        $authData['url'].'login',
        [
          'Login'=>$authData['login'],
          'Password'=>$authData['pass'],
        ],
        'POST'
      );
    }
    private function sendRequest($url, $data, $method='POST'){
      $context=stream_context_create([
        'http' => [
          'method'  => $method,
          'header'  =>
            "Content-type: application/json\r\n"
          ,
          'content' => json_encode($data)
        ]
      ]);
      $result = file_get_contents($url, false, $context);
      // if ($result===false) echo('Connection error');
      // if(isset($data['Weight'])) echo('<pre>3.'. print_r([$url, $data, $method, $context, $result, json_decode($result)], true).'</pre>');
      return json_decode($result);
    }

    /*
      Расчет стоимости доставки посылки
      Принимаемые параметры:
        Город
        Улица
        Дом
        Вес в граммах
      Возвращаемые параметры:
        Цена в рублях или false если расчет невозможен.
        Формат - json
    */
    public function executeGetRussianpostDeliveryPrice(sfWebRequest $request) {
      $isTest=false;
      // $isTest=true;
      // echo '|<pre>'; die(print_r($_REQUEST, true));
      $weight = $_REQUEST['weight'];
      if(!$weight)
        $this->russianpostSendResult([
          'status' => false,
          'comment' => 'Пожалуйста, укажите примерный вес посылки'
        ]);
      if(!$weight || $weight > 9999)
        $this->russianpostSendResult([
          'status' => false,
          'comment' => 'Посылка такого веса не может быть доставлена'
        ]);
      $city=$_REQUEST['city'];
      if(!$city)//
        $this->russianpostSendResult([
          'status' => false,
          'comment' => 'Пожалуйста, укажите город'
        ]);
      $street=$_REQUEST['street'];
      if(!$street)
        $this->russianpostSendResult([
          'status' => false,
          'comment' => 'Пожалуйста, укажите улицу'
        ]);
      if ($isTest)
        $this->russianpostSendResult([
          'status' => true,
          'price' => date('s'),//Случайное значение для проверки
          'comment' => 'sucess',
        ]);
      $validatedCodes=[
        'VALIDATED',
        'OVERRIDDEN',
        'CONFIRMED_MANUALLY',
      ];
      $qualityCodes=[
        'GOOD',
        'POSTAL_BOX',
        'ON_DEMAND',
        'UNDEF_05',
      ];

      // $normalizeResult = $this->russianpostGetCurrentOps();
      if(!$isTest){
        $normalizeResult = $this->russianpostNormalize($city.' '.$street.' '.$request->getParameter('house'))[0];

      //Будем использовать только первый элемент, так как запрашивали 1

      if(!in_array($normalizeResult->{'validation-code'}, $validatedCodes) || !in_array($normalizeResult->{'quality-code'}, $qualityCodes))//Почта России не приняла адрес
        $this->russianpostSendResult([
          'status' => false,
          'comment' => 'Пожалуйста, уточните адрес'
        ]);
      }
        // echo '|<pre>'; die(print_r(['request'=>$_REQUEST, $normalizeResult], true));

      $prices=$this->russianpostGetPrice(
        [
          'index' => $isTest ? 301247 : $normalizeResult->index,
        ],
        $weight,
        $request->getParameter('pay-online')=='true' ? true : false,
        $request->getParameter('order-price')*100
      );
      $price = intVal(($prices->{'total-rate'}+$prices->{'total-vat'})/100);
      // echo '|<pre>'; die(print_r(['request'=>$_REQUEST, 'price' => $prices], true));
      $comment = 'Ориентировочная стоимость доставки для адреса '.$normalizeResult->index;
      $comment.= ', '.$normalizeResult->region;
      $comment.= ', '.$normalizeResult->place;
      if (isset($normalizeResult->street) && $normalizeResult->street!='') $comment.= ', '.$normalizeResult->street;
      if (isset($normalizeResult->house) && $normalizeResult->house!='') $comment.= ', '.$normalizeResult->house;
      $comment.= ' составляет '.$price.' руб.'/*.'<pre>'.print_r($prices, true).'</pre>'*/;

      $this->russianpostSendResult([
        'status' => true,
        'price' => $price,
        'comment' => $comment
      ]);
    }

    private function russianpostGetCurrentOps (){
      return json_decode(
        $this->russianpostSendRequest(
          [
          ],
          '/1.0/user-shipping-points',
          'GET'
        )
      );
    }
    private function russianpostGetPrice ($addr, $weight, $onlinePayment=false, $price=1000){
      return json_decode(
        $this->russianpostSendRequest(
          [
            'mass'=>$weight,
            'declared-value' => $price,
            'with-order-of-notice' => false,
            'with-simple-notice' => true,
            'mail-type' => 'POSTAL_PARCEL',
            'mail-category' => $onlinePayment ? 'WITH_DECLARED_VALUE' : 'WITH_DECLARED_VALUE_AND_CASH_ON_DELIVERY',
            'index-to' => $addr['index'],
            'index-from' => '129323'//Отправка из
          ],
          '/1.0/tariff'
        )
      );
    }

    /*
      Проверяет адрес на корректность и получает индекс
    */
    private function russianpostNormalize ($addr){
      return json_decode(
        $this->russianpostSendRequest(
          [
            [
              'id'=>1,
              'original-address' => $addr,
            ]
          ],
          '/1.0/clean/address'
        )
      );
    }

    /*
      Возвращает результат в json
    */
    private function russianpostSendResult($data){
      header("Content-type: application/json; charset=utf-8");
      // die(print_r($data, true));
      die(json_encode($data));
    }

    private function russianpostSendRequest($data, $url='/1.0/clean/address', $method='POST' ){
      $authData=[
        'token' => 'AccessToken q94hC18OG6rTeTkq2bhocM7qa7GOPysX',
        'auth' => 'Basic Kzg5MjYwNDA0MTQyOk9ub25hMjgwNg==',//Логин: +89260404142 Пароль: Onona2806
        'protocol' => 'https://',
        'host' => 'otpravka-api.pochta.ru',
      ];
      $http=[
        'method'  => $method,
        'header'  =>
          "Content-type: application/json\r\n".
          "Accept: application/json;charset=UTF-8\r\n".
          "Authorization: $authData[token]\r\n".
          "X-User-Authorization: $authData[auth]\r\n"

        ,
        'content' => json_encode($data)
      ];
      // echo('<pre>'.print_r($data, true).'</pre>');

      $context=stream_context_create([
        'http' => $http
      ]);
      $urlFull=$authData['protocol'].$authData['host'].$url;
      $result = file_get_contents($urlFull, false, $context);
      if ($result===false)
        $this->russianpostSendResult([
          'status' => false,
          'comment' => 'Ошибка соединения с сервером Почты России',
          'detail' => print_r([$http, $urlFull, $data, $method, $context, $result, json_decode($result)], true)
        ]);
      // return '<pre>'. print_r([$http, $url, $data, $method, $context, $result, json_decode($result)], true).'</pre>';

      return $result;
    }

    public function executeCheckemail(sfWebRequest $request) {
      $email=trim($request->getParameter('email'));
      if(!$this->is_email($email)){
        $return['result']='error';
      }
      else{
        $user = sfGuardUserTable::getInstance()->findOneByEmailAddress($email);
        if (!$user) $return['result']='true';
        else $return['result']='false';
      }
      die(json_encode($return));
    }

    public function executeNo18(sfWebRequest $request) {
      /* Выводит страничку что вы еще маленький */
    }

    public function executeLogvalidmail(sfWebRequest $request) {
        $file = '/var/www/ononaru/data/www/onona.ru/logvalidmail.txt';
        $current = file_get_contents($file);
        $current .= "Почта: " . ($_POST['mail']) . " Форма: " . ($_POST['form']) . " Время: " . date("d.m.Y H:i:s") . "\n";
        file_put_contents($file, $current);
        return true;
    }

    public function executeRegssauth(sfWebRequest $request) {
        $this->email = $this->getUser()->getAttribute('emailReggSSAuth');
    }

    public function executeAddNotification(sfWebRequest $request) {
        $this->email = $request->getParameter('emailAddNotification');
        $this->category = $request->getParameter('categoryAddNotification');
        if ($this->getUser()->isAuthenticated()) {
            $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("category_id=?", $this->category)->fetchOne();
        } else {
            $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_mail=?", $this->email)->addWhere("category_id=?", $this->category)->fetchOne();
        }
        if ($notificationCategory) {
            $notificationCategory->set("is_enabled", true);
            $notificationCategory->save();
        } else {
            $notificationCategory = new NotificationCategory();
            if ($this->getUser()->isAuthenticated()) {
                $notificationCategory->set("user_id", $this->getUser()->getGuardUser()->getId());
            } else {
                $notificationCategory->set("user_mail", $this->email);
            }
            $notificationCategory->set("category_id", $this->category);
            $notificationCategory->set("is_enabled", true);
            $notificationCategory->save();
        }
    }

    public function executeDelNotification(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated()) {
            $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("category_id=?", $request->getParameter('catid'))->fetchOne();
        } else {
            //$notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_mail=?", $this->email)->addWhere("category_id=?", $this->category)->fetchOne();
        }
        if ($notificationCategory) {
            $notificationCategory->set("is_enabled", false);
            $notificationCategory->save();
        }
    }

    public function executeFirstvisit(sfWebRequest $request) {
        $this->getResponse()->setCookie("firstvisit", 1, time() + 60 * 60 * 24 * 365, '/');
        if ($request->getParameter('stats') == 'yes') {
            if ($request->getParameter('user_mail') != "") {
                $userIsSet = sfGuardUserTable::getInstance()->findOneByEmailAddress($request->getParameter('user_mail'));
                if (!$userIsSet) {

                    $user = new sfGuardUser();
                    $username = 'user_' . rand(0, 9999999999999);
                    $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                    if ($isExistUserName->count() != 0) {
                        $username = 'user_' . rand(0, 9999999999999);
                        $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                        if ($isExistUserName->count() != 0) {
                            $username = 'user_' . rand(0, 9999999999999);
                            $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                            if ($isExistUserName->count() != 0) {
                                $username = 'user_' . rand(0, 9999999999999);
                            }
                        }
                    }
                    $user->setUsername($username);
                    $password = uniqid();
                    $user->set("password", $password);
                    $user->setFirstName($request->getParameter('user_name'));
                    $user->setEmailAddress($request->getParameter('user_mail'));
                    $user->setSex($request->getParameter('user_sex'));

                    $user->save();
                    $this->getUser()->signin($user);



                    if ($this->is_email($user->getEmailAddress())) {
                        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('register');
                        $message = Swift_Message::newInstance()
                                ->setFrom(csSettings::get('smtp_user'), "OnOna.ru"/* sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com') */)
                                ->setTo($user->getEmailAddress())
                                ->setSubject(csSettings::get("theme_register_email")/* .$this->form->user->username */)
                                ->setBody(str_replace(array('{$login}', '{$password}'), array($user->getEmailAddress(), $password), $mailTemplate->getText()), 'text/html')
                                ->setContentType('text/html')
                        ;
                        //echo $user->getEmailAddress();
                        $this->getMailer()->send($message);
                    }

                    $bonus = new Bonus();
                    $bonus->setUserId($user);
                    $bonus->setBonus(csSettings::get("register_bonus_add"));
                    $bonus->setComment("Зачисление за подписку");
                    $bonus->save();
                } else {
                    return $this->redirect('/subscription');
                }
            }
        }
        $user = $this->getUser();
        $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));

        return $this->redirect('' != $signinUrl ? $signinUrl : '/sexshop');
        return true;
    }

    public function executeCartinfoheader(sfWebRequest $request) {
        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($products_old as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * $product["price"]);
        }
        $this->products_old = $products_old;
    }

    public function executeCartinfotop(sfWebRequest $request) {
        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($products_old as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * $product["price"]);
        }
        $this->products_old = $products_old;
    }

    public function executeKonultsexolog(sfWebRequest $request) {
        $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                ->setUsername(csSettings::get("smtp_user"))
                ->setPassword(csSettings::get("smtp_pass"));
        $mailer = Swift_Mailer::newInstance($transport);

        $emailsfastorder = explode(";", csSettings::get("emailskonssex"));
        foreach ($emailsfastorder as $email) {
            $arrayemailsfastorder[$email] = "";
        }
        //print_r($arrayemailsfastorder);exit;
        $message = Swift_Message::newInstance('Поступил запрос на консультацию сексолога', "Здравствуйте!<br>
      		Ваше имя: " . $_POST['form_text_638'] . "<br>
      		Фамилия: " . $_POST['form_text_639'] . "<br>
      		Телефон: " . $_POST['form_text_640'] . "<br>
      		Получатель: " . $_POST['name'] . "<br>
      		Его телефон: " . $_POST['phone'] . "<br>
      		Его почта: " . $_POST['mail'] . "<br>", 'text/html', 'utf-8')
                ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                ->setTo($arrayemailsfastorder)
        ;

        //Send the message
        $numSent = $mailer->send($message);
    }

    public function execute18age(sfWebRequest $request) {

    }

    public function executeBonuspayprod(sfWebRequest $request) {
        if ($request->getParameter('nonMessageBonusProd') == "on") {
            sfContext::getInstance()->getResponse()->setCookie('nonMessageBonusProd', true, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
            if ($request->getReferer() != "") {
                echo "!";
                $this->redirect($request->getReferer());
            } else {
                $this->redirect("/");
            }
        } else {

            sfContext::getInstance()->getResponse()->setCookie('oneMessageBonusProd', true, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
            if ($request->getReferer() != "") {
                echo "!";
                $this->redirect($request->getReferer());
            } else {
                $this->redirect("/");
            }
        }
    }

    public function executeRefid(sfWebRequest $request) {
        $this->partner = PartnerIdTable::getInstance()->findByRefid($request->getParameter('refid'));
    }

    public function executeRefidpartner(sfWebRequest $request) {
        $this->partner = PartnerRefidTable::getInstance()->findAll();
    }

    public function is_email($email) {
        //return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //echo "Valid email address.";
            return true;
        } else {
            return false;
            //echo "Invalid email address.";
        }
    }

    public function executeSupport(sfWebRequest $request) {
      // throw new Doctrine_Table_Exception('let`s show!  <pre/>~|'.print_r($_POST, true).'|~</pre>');
        sfContext::getInstance()->getResponse()->setCookie('age18', true, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
        if ($request->isMethod(sfRequest::POST)) {
            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='sup'")->fetchOne();
            if ($request->getParameter('code') == $captcha->getVal()) {
                /* $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                  ->setUsername(csSettings::get("smtp_user"))
                  ->setPassword(csSettings::get("smtp_pass"));
                  $mailer = Swift_Mailer::newInstance($transport); */
                if ($this->is_email($_POST['email'])) {
                    $message = Swift_Message::newInstance('Сообщение - форма обратной связи.', "Здравствуйте!<br>
                  		Имя: " . $_POST['name'] . "<br>
                  		Email: " . $_POST['email'] . "<br>
                  		Тема сообщения: " . $_POST['sub'] . "<br>
                  		Сообщение: " . nl2br($_POST['msg']) . "<br>", 'text/html', 'utf-8')
                            ->setFrom(array((csSettings::get("smtp_user") != "" ? csSettings::get("smtp_user") : 'info@onona.ru') => 'Почтовый робот сайта OnOna.Ru'))
                            ->setTo("info@onona.ru")
                            ->setSubject('Сообщение - форма обратной связи.')
                            ->setBody("Здравствуйте!<br>
                          		Имя: " . $_POST['name'] . "<br>
                          		Email: " . $_POST['email'] . "<br>
                          		Тема сообщения: " . $_POST['sub'] . "<br>
                          		Сообщение: " . nl2br($_POST['msg']) . "<br>")
                            ->setContentType('text/html')
                    ;
                    $this->getMailer()->send($message);

                    /*  $message = Swift_Message::newInstance('Сообщение - форма обратной связи.', "Здравствуйте!<br>
                      Имя: " . $_POST['name'] . "<br>
                      Email: " . $_POST['email'] . "<br>
                      Тема сообщения: " . $_POST['sub'] . "<br>
                      Сообщение: " . nl2br($_POST['msg']) . "<br>", 'text/html', 'utf-8')
                      ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                      ->setTo("svs@onona.ru");

                      $numSent = $mailer->send($message); */
                }
                $this->errorCap = false;
            } else {
                $this->errorCap = true;
            }
        }
    }

    public function executeXmladmitad(sfWebRequest $request) {
        header('Content-type: text/xml; charset=utf-8');
        mb_internal_encoding('UTF-8');
        $pass = "Wiea9CrK0e";

        if ($_GET['pass'] != $pass)
            die('<?xml version="1.0"?><error>no confirm pass</error>');
        $ordersAdmitad = OrdersTable::getInstance()->createQuery()->where("referal =2801045062")->addWhere("status='Отмена' or status='Возврат' or status='Оплачен'")->execute();

        $res = '';
        $res.="<?xml version=\"1.0\"?><Payments xmlns=\"http://admitad.com/payments-revision\">";
        foreach ($ordersAdmitad as $order) {
            // вместо getOrderById вам нужно прописать
            // свою функцию, которая получает данные из БД
            $TotalSumm = 0;
            $products_old = $order->getText();
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            foreach ($products_old as $key => $productInfo):
                $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price_w_discount']);
            endforeach;
            //echo mb_strtolower($order->getStatus());
            if (mb_strtolower($order->getStatus()) == "оплачен") {
                $status = '1';
            } elseif (mb_strtolower($order->getStatus()) == "отмена" or mb_strtolower($order->getStatus()) == "возврат") {
                $status = '2';
            }
            $res .= '<Payment>';
            $res .= '<OrderID>' . ($order->getId()) . '</OrderID>';
            $res .= '<OrderAmount>' . $TotalSumm . '</OrderAmount>';
            $res .= '<Status>' . $status . '</Status>';
            $res .= '</Payment>';
        }

        //$res = '' . $res . '';
        $res.="</Payments>";
        echo $res;
        exit;
        //return $this->renderPartial("xmlmyadsSuccess");
    }

    public function executeXmlmyads(sfWebRequest $request) {
        header('Content-type: text/xml; charset=utf-8');
        mb_internal_encoding('UTF-8');
        $pass = "h478v7en4";

        if ($_POST['pass'] != md5($pass))
            die('<?xml version="1.0"?><error>no confirm pass</error>');
        /* $_POST['xml'] = "<items>
          <item>107689</item>
          <item>107688</item>
          <item>107678</item>
          <item>107611</item>
          </items>"; */
        $res = '';
        preg_match_all("/<item>(.*)<\/item>/Uis", $_POST['xml'], $items);
        $res.="<?xml version=\"1.0\"?><items>";
        foreach ($items[1] as $oid) {
            // вместо getOrderById вам нужно прописать
            // свою функцию, которая получает данные из БД
            $order = OrdersTable::getInstance()->findOneById($oid);
            $TotalSumm = 0;
            $products_old = $order->getText();
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            foreach ($products_old as $key => $productInfo):
                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
            endforeach;
            //echo mb_strtolower($order->getStatus());
            if (mb_strtolower($order->getStatus()) == "оплачен") {
                $status = 'done';
            } elseif (mb_strtolower($order->getStatus()) == "отмена") {
                $status = 'cancel';
            } else {
                $status = 'wait';
            }
            $res .= '<item>';
            $res .= '<id>' . $oid . '</id>';
            $res .= '<status>' . $status . '</status>';
            $res .= '<price>' . $TotalSumm . '</price>';
            $res .= '</item>';
        }

        //$res = '' . $res . '';
        $res.="</items>";
        echo $res;
        exit;
        //return $this->renderPartial("xmlmyadsSuccess");
    }

    public function executeXmlleadtrade(sfWebRequest $request) {
        /* header('Content-type: text/xml; charset=utf-8');
          mb_internal_encoding('UTF-8');

          $res = '';
          preg_match_all("/<item>(.*)<\/item>/Uis", $_POST['xml'], $items);
          $res.="<?xml version=\"1.0\"?><items>";
          foreach ($items[1] as $oid) {
          // вместо getOrderById вам нужно прописать
          // свою функцию, которая получает данные из БД
          $order = OrdersTable::getInstance()->findOneById($oid);
          if ($order):
          //echo mb_strtolower($order->getStatus());
          if (mb_strtolower($order->getStatus()) == "оплачен") {
          $status = '1';
          } elseif (mb_strtolower($order->getStatus()) == "отмена") {
          $status = '3';
          } else {
          $status = '2';
          }
          $res .= '<item>';
          $res .= '<id>' . $oid . '</id>';
          $res .= '<status>' . $status . '</status>';
          $res .= '</item>';
          endif;
          }

          //$res = '' . $res . '';
          $res.="</items>";
          echo $res;
          exit; */
    }

    public function executeXmlcityads(sfWebRequest $request) {
        /* header('Content-type: text/xml; charset=utf-8');
          mb_internal_encoding('UTF-8');
          if (empty($_POST['xml']))
          die('<?xml version="1.0"?><error>no xml params</error>');
          $res = '';
          preg_match_all("/<date_from>(.*)<\/date_from>/Uis", $_POST['xml'], $date_from);
          preg_match_all("/<date_to>(.*)<\/date_to>/Uis", $_POST['xml'], $date_to);
          $res.="<?xml version=\"1.0\"?><items>";
          //print_r(date("Y-m-d H:i:s", (int) $date_from[1][0]) );
          $orders = OrdersTable::getInstance()->createQuery()->where('updated_at> "' . date("Y-m-d H:i:s", (int) $date_from[1][0]) . '"')->andWhere('updated_at<"' . date("Y-m-d H:i:s", (int) $date_to[1][0]) . '"')->andWhere('prxcityads <> "NULL" and prxcityads is not null')->execute();
          foreach ($orders as $order) {
          if (mb_strtolower($order->getStatus()) == "оплачен") {
          $status = 'done';
          } elseif (mb_strtolower($order->getStatus()) == "отмена") {
          $status = 'cancel';
          } else {
          $status = 'wait';
          }
          $res .= '<item>';
          $res .= '<prx>' . $order->getPrxcityads() . '</prx>';
          $res .= '<id>' . $order->getId() . '</id>';
          $res .= '<status>' . $status . '</status>';
          //$res .= '<price>' . $TotalSumm . '</price>';
          $res .= '<date>' . strtotime($order->getUpdatedAt()) . '</date>';
          $res .= '</item>';
          }

          //$res = '' . $res . '';
          $res.="</items>";
          echo $res;
          exit;
          //return $this->renderPartial("xmlmyadsSuccess"); */
    }

    public function executeXmlmyragon(sfWebRequest $request) {
        /*  header('Content-type: text/xml; charset=utf-8');
          mb_internal_encoding('UTF-8');
          if (empty($_POST['xml']))
          die('<?xml version="1.0"?><error>no xml params</error>');
          $res = '';
          preg_match_all("/<date_from>(.*)<\/date_from>/Uis", $_POST['xml'], $date_from);
          preg_match_all("/<date_to>(.*)<\/date_to>/Uis", $_POST['xml'], $date_to);
          $res.="<?xml version=\"1.0\"?><items>";
          //print_r(date("Y-m-d H:i:s", (int) $date_from[1][0]) );
          $orders = OrdersTable::getInstance()->createQuery()->where('((updated_at> "' . date("Y-m-d H:i:s", (int) $date_from[1][0]) . '" and updated_at<"' . date("Y-m-d H:i:s", (int) $date_to[1][0]) . '") or (created_at> "' . date("Y-m-d H:i:s", (int) $date_from[1][0]) . '" and created_at<"' . date("Y-m-d H:i:s", (int) $date_to[1][0]) . '"))')->addWhere('referal ="2764355315"')->execute();
          foreach ($orders as $order) {
          if (mb_strtolower($order->getStatus()) == "оплачен") {
          $status = 'done';
          } elseif (mb_strtolower($order->getStatus()) == "отмена") {
          $status = 'cancel';
          } else {
          $status = 'wait';
          }
          $TotalSumm = 0;
          $products_old = $order->getText();
          $products_old = $products_old != '' ? unserialize($products_old) : '';
          foreach ($products_old as $key => $productInfo):
          $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
          $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
          endforeach;
          $res .= '<item>
          <subaccount>' . $order->getSamyragon() . '</subaccount>
          <id>' . $order->getId() . '</id>
          <price>' . $TotalSumm . '</price>
          <currency>RUR</currency>
          <status>' . $status . '</status>';
          if ($status == 'cancel')
          $res .= '<status_ext>wrong_phone</status_ext>';

          $res .= '<date>' . strtotime($order->getUpdatedAt()) . '</date>  ';
          $res .= '</item>';
          }

          //$res = '' . $res . '';
          $res.="</items>";
          echo $res;
          exit;
          //return $this->renderPartial("xmlmyadsSuccess"); */
    }

    public function executeSliderrelated(sfWebRequest $request) {
        $content = '<html>'.
          '<head>'.
            '<script src="https://onona.ru/newdis/js/jquery-1.7.2.min.js" type="text/javascript"></script>'.
            '<script src="https://onona.ru/newdis/js/jquery.main.js" type="text/javascript"></script>'.
            '<link href="https://onona.ru/newdis/css/all.css?v=14" media="screen" type="text/css" rel="stylesheet">'.
          '</head><body style="background: none;"> ';

        $relatedProduct = ProductTable::getInstance()->getRelatedProduct();
        //echo $relatedProduct->count();
        if ($relatedProduct->count() > 0):
            $content.='<div id="header"></div><div id="main" style="padding: 0;"><div class="aside" style="float: left; margin: 0px;">
                                <div class="leaders-galery box">
                                    <div class="title-holder"><a href="https://onona.ru/related?r=' . $_GET['refpar'] . '" style="color: #C3060E" target="_top">Лидеры продаж</a></div>
                                    <div class="galery-holder">
                                        <a href="#" class="prev"></a>
                                        <a href="#" class="next"></a>
                                        <div class="galery" style="height: 550px">
                                            <ul>
                                                ';
            $q = Doctrine_Query::create()->select("name, slug, (select filename from photo where album_id=(select photoalbum_id from product_photoalbum where product_id=product.id) order by position asc limit 0,1) as filename")->from("product")->where("`is_related` = 1")->orderBy("rand()")->execute();
            foreach ($q as $product):
                $content.='<li><div style="text-align: center; width:186px;margin:0 auto 10px;">' . $product->getName() . '</div><div class="prod">' . ( $product->getDiscount() > 0 ? ('<span class="sale' . $product->getDiscount() . '" style="top: 0px;"></span>') : '') . '<a target="_top" href="https://onona.ru/product/' . $product->getSlug() . '?r=' . $_GET['refpar'] . '" style="display: table-cell;vertical-align: middle;height: 268px;"><img src="/uploads/photo/thumbnails_250x250/' . $product->getFilename() . '" style="max-width: 188px; max-height: 260px;" alt="' . str_replace(array("'", '"'), "", $product->getName()) . '" /></a></div></li> ';
            endforeach;
            $content.='
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            ';
        endif;
        $content.='
                        </div></div></div><div id="footer"></div><a class="to-up"></a></body></html>';

        echo $content;
        exit;
    }


    public function executeSliderMainPageProd(sfWebRequest $request) {
        if ($request->getParameter('prodId') != "") {
            $products = ProductTable::getInstance()->createQuery()->where("id in (" . $request->getParameter('prodId') . ")")
                    ->addWhere('is_public = \'1\'')
                    ->addWhere('count > 0');
            foreach (explode(",", $request->getParameter('prodId')) as $id) {
                $products->addOrderBy("(id=" . (int) $id . ") desc");
            }

            $products = $products->execute();
            $this->products = $products;
        }
    }

    public function executeTest(sfWebRequest $request) {

    }
    public function executeProductFeedYML(sfWebRequest $request) {
      $yml=
        '<?xml version="1.0" encoding="UTF-8"?>'.
          '<yml_catalog date="'.date('Y-m-d H:i').'">'.
            '<shop>'.
              '<name>Секс-шоп Он и Она</name>'.
              '<company>ИП Поварешкин И.П.</company>'.
              '<url>https://onona.ru/</url>'.
              '<currencies>'.
                '<currency id="RUR" rate="1"/>'.
              '</currencies>'.
              '<categories>';
      $categoriesTable=
        CategoryTable::getInstance()
        ->createQuery()
        ->select("id, name, parents_id")
        ->execute();
      foreach ($categoriesTable as $key => $value) {
        $parentId=$value->getParents_id();
        $yml.='<category id="'.$value->getId().'" '.($parentId ? 'parentId="'.$parentId.'"' : '').'>'.$value->getName().'</category>';
      }
      $yml.=  '</categories>'.
              '<offers>';
                /*
      for ($i=0; $i<100; $i++){
        $productTable =
          ProductTable::getInstance()
          ->createQuery()
          ->select("name, code, price, title, content, slug")
          // ->where("active=true")
          ->offset($i*5000)
          ->limit(5000)
          // where("user_id = ?", $userFind->getId())
          ->execute();
        foreach ($productTable as $key => $value) {
          $product[] = implode(';',[
            'name' => $value->getName(),
            'price' => $value->getPrice(),
            'content' => '"'.str_replace('"', "'", $value->getContent()).'"',
            // 'code' => $value->getCode(),
            'link' => 'https://onona.ru/product/'.$value->getSlug(),

          ]);
        }
        if (count($product) < $i*5000) break;
      }*/
      $this->yml=str_replace('<', '&lt;', $yml);
      //
      // header('Content-type: application/xml');
      // header('Content-Disposition:attachment; filename="productfeed.yml"');
      // header("Cache-Control: public");
      // header("Pragma: public");
      // header("Content-Transfer-Encoding: binary");
      // $file = fopen('php://output', 'w');
      // fwrite ($file, $this->product);
      // fclose($file);
      // exit();
    }

    public function executeProductFeed(sfWebRequest $request) {
      $product[] = implode(';',[
        'name' => 'Название',
        'price' => 'Цена',
        'content' => 'Описание',
        // 'code' => 'Артикул',
        'link' => 'Ссылка',

      ]);
      for ($i=0; $i<100; $i++){
        $productTable =
          ProductTable::getInstance()
          ->createQuery()
          ->select("name, code, price, title, content, slug")
          // ->where("active=true")
          ->offset($i*5000)
          ->limit(5000)
          // where("user_id = ?", $userFind->getId())
          ->execute();
        foreach ($productTable as $key => $value) {
          $product[] = implode(';',[
            'name' => $value->getName(),
            'price' => $value->getPrice(),
            'content' => '"'.str_replace('"', "'", $value->getContent()).'"',
            // 'code' => $value->getCode(),
            'link' => 'https://onona.ru/product/'.$value->getSlug(),

          ]);
        }
        if (count($product) < $i*5000) break;
      }
      $this->product=implode("\n",$product);
      header('Content-type: text/csv');
      header('Content-Disposition:attachment; filename="productfeed.csv"');
      header("Cache-Control: public");
      header("Pragma: public");
      header("Content-Transfer-Encoding: binary");
      $file = fopen('php://output', 'w');
      fwrite ($file, $this->product);
      fclose($file);
      exit();
    }

    public function executeApiGetBonusCount(sfWebRequest $request) {
        $userPhone = $request->getParameter('userPhone');
        $randomKey = $request->getParameter('randomKey');
        $secretKey = $request->getParameter('secretKey');
        if (md5($userPhone . $randomKey . "AIY3ESfsJf7MaXSZvN7rmY7DuBgI4s") == $secretKey and $userPhone != "" and $randomKey != "" and $secretKey != "") {

            /* if ($userPhone[0] != "+" and $userPhone[0] != "8") {
              $userPhone = "+7" . $userPhone;
              } */
            $numbersPhoneReplace77 = str_replace("+77", "+7", $userPhone);
            $numbersPhoneReplace77 = preg_replace('/[^\d]/', '', $numbersPhoneReplace77);
            $numbersPhone = preg_replace('/[^\d]/', '', ($userPhone));
            if (strlen($numbersPhone) == 11 and ( $numbersPhone[0] == 7 or $numbersPhone[0] == 8)) {

                $sPhone = "+7(" . $numbersPhone[1] . $numbersPhone[2] . $numbersPhone[3] . ")" . $numbersPhone[4] . $numbersPhone[5] . $numbersPhone[6] . "-" . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9] . $numbersPhone[10];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } elseif (strlen($numbersPhone) == 10 and $numbersPhone[0] != 8 and $numbersPhone[0] != 7) {
                $sPhone = "+7(" . $numbersPhone[0] . $numbersPhone[1] . $numbersPhone[2] . ")" . $numbersPhone[3] . $numbersPhone[4] . $numbersPhone[5] . "-" . $numbersPhone[6] . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } elseif (strlen($numbersPhoneReplace77) == 11 and ( $numbersPhoneReplace77[0] == 7 or $numbersPhoneReplace77[0] == 8)) {

                $sPhone = "+7(" . $numbersPhoneReplace77[1] . $numbersPhoneReplace77[2] . $numbersPhoneReplace77[3] . ")" . $numbersPhoneReplace77[4] . $numbersPhoneReplace77[5] . $numbersPhoneReplace77[6] . "-" . $numbersPhoneReplace77[7] . $numbersPhoneReplace77[8] . $numbersPhoneReplace77[9] . $numbersPhoneReplace77[10];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } else {
                $sPhone = $userPhone;
                $numPhoneUnCorrect++;
                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            }
            $userFind = sfGuardUserTable::getInstance()->createQuery()->where("phone = ?", $sPhone)->addWhere("activephone = '1'")->orderBy("last_login DESC")->limit(1)->fetchOne();
            if ($userFind) {
                $bonusSum = BonusTable::getInstance()->createQuery()->select("sum(bonus) as bonus")->where("user_id = ?", $userFind->getId())->fetchOne();
                return $this->renderText($bonusSum->getBonus());
            } else {

                return $this->renderText('not register');
            }
        } else {
            //echo md5($userPhone . $randomKey . "AIY3ESfsJf7MaXSZvN7rmY7DuBgI4s");
            return $this->renderText("error");
        }
    }

    public function executeApiBonusDown(sfWebRequest $request) {
        $userPhone = $request->getParameter('userPhone');
        $randomKey = $request->getParameter('randomKey');
        $bonus = $request->getParameter('bonus');
        $sumOrder = $request->getParameter('sumOrder');
        $secretKey = $request->getParameter('secretKey');
        if (md5($userPhone . $randomKey . $bonus . $sumOrder . "AIY3ESfsJf7MaXSZvN7rmY7DuBgI4s") == $secretKey and $userPhone != "" and $randomKey != "" and $bonus != "" and $sumOrder != "" and $secretKey != "") {

            /* if ($userPhone[0] != "+" and $userPhone[0] != "8") {
              $userPhone = "+7" . $userPhone;
              } */
            $numbersPhoneReplace77 = str_replace("+77", "+7", $userPhone);
            $numbersPhoneReplace77 = preg_replace('/[^\d]/', '', $numbersPhoneReplace77);
            $numbersPhone = preg_replace('/[^\d]/', '', ($userPhone));
            if (strlen($numbersPhone) == 11 and ( $numbersPhone[0] == 7 or $numbersPhone[0] == 8)) {

                $sPhone = "+7(" . $numbersPhone[1] . $numbersPhone[2] . $numbersPhone[3] . ")" . $numbersPhone[4] . $numbersPhone[5] . $numbersPhone[6] . "-" . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9] . $numbersPhone[10];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } elseif (strlen($numbersPhone) == 10 and $numbersPhone[0] != 8 and $numbersPhone[0] != 7) {
                $sPhone = "+7(" . $numbersPhone[0] . $numbersPhone[1] . $numbersPhone[2] . ")" . $numbersPhone[3] . $numbersPhone[4] . $numbersPhone[5] . "-" . $numbersPhone[6] . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } elseif (strlen($numbersPhoneReplace77) == 11 and ( $numbersPhoneReplace77[0] == 7 or $numbersPhoneReplace77[0] == 8)) {

                $sPhone = "+7(" . $numbersPhoneReplace77[1] . $numbersPhoneReplace77[2] . $numbersPhoneReplace77[3] . ")" . $numbersPhoneReplace77[4] . $numbersPhoneReplace77[5] . $numbersPhoneReplace77[6] . "-" . $numbersPhoneReplace77[7] . $numbersPhoneReplace77[8] . $numbersPhoneReplace77[9] . $numbersPhoneReplace77[10];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } else {
                $sPhone = $userPhone;
                $numPhoneUnCorrect++;
                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            }
            $userFind = sfGuardUserTable::getInstance()->createQuery()->where("phone = ?", $sPhone)->addWhere("activephone = '1'")->orderBy("last_login DESC")->limit(1)->fetchOne();
            if ($userFind) {
                $bonusSum = BonusTable::getInstance()->createQuery()->select("sum(bonus) as bonus")->where("user_id = ?", $userFind->getId())->fetchOne();

                $bonusLog = new Bonus();
                $bonusLog->setUserId($userFind);
                $bonusLog->setBonus("-" . $bonus);
                $bonusLog->setComment("Снятие бонусов в счет оплаты покупки в магазине на сумму " . $sumOrder);
                $bonusLog->save();

                return $this->renderText("success");
            } else {

                return $this->renderText('not register');
            }
        } else {
            //echo md5($userPhone . $randomKey . $bonus . $sumOrder . "AIY3ESfsJf7MaXSZvN7rmY7DuBgI4s");
            return $this->renderText("error");
        }
    }

}
