<?php
// use Ahc\Jwt\JWT;
class cart_newComponents extends sfComponents
{
  private function url_safe_base64_encode( $input) {
    // return base64_encode($input);
    return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
  }
  public function executeImeform(sfWebRequest $request) { //Форма оплаты  iMe wallet
    global $isTest;
    $exchangeRate=csSettings::get('change_rate_ime');
    $serviceKey=trim(csSettings::get('key_ime'));
    $storeId=csSettings::get('shop_id_ime');
    $walletId=csSettings::get('shop_username');
    $productOrder = $this->order->getText();
    $products_old = $productOrder != '' ? unserialize($productOrder) : '';
    $amount=0;
    foreach ($products_old as $key => $productInfo){//считаем сумму
      $amount+=(($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price'])*$productInfo['quantity'];
      // die('<pre>'.print_r($this->products[$key]->getName(), true));
      $goods[]=$this->products[$key]->getName();
    }
    // die('bar');
    $amount-=$this->order->getBonuspay();
    $token=[
      // Обязательные поля
    	'type' => 'PURCHASE_ON_PARTNER_SITE',
    	'storeId' => $storeId,         // Идентификатор интернет-магазина
    	'storeUsername' => $walletId,   // Username интернет-магазина в telegram
      //Заменить потом
    	'orderId' => $this->order->getId(),         // Идентификатор заказа
    	'sellerUsername' => $walletId,  // Username продавца в telegram
    	'deliveryType' => ''.$this->order->getDelivery(),   // Способ доставки
    	'deliveryAddress' =>
          $this->customer->getIndexHouse().' '.
          $this->customer->getCity().' '.
          $this->customer->getStreet(). ' '.
          $this->customer->getHouse(). ' '.
          $this->customer->getApartament(),// Адрес доставки
    	'buyerFullName' => $this->customer->getFirstName().' '.$this->customer->getLastName(),   // ФИО покупателя
    	'buyerPhone'=> $this->customer->getPhone(),      // телефон покупателя
    	'buyerEmail' => $this->customer->getEmailAddress(),   // email покупателя
    	'goodsList' => $goods,     // массив наименований товаров
    	'costInRub' => $amount,       // стоимость в рублях (с доставкой и скидкой)
    	'costInAiCoin' => $amount/$exchangeRate,    // стоимость в AiCoin (с доставкой и скидкой)
    	'createDate' => date('c', strtotime($this->order->getCreatedAt())),

    	// // Опциональные поля
    	// 'buyerGender': string;     // пол покупателя ('male' или 'female')
    	// 'buyerBirthday': string;   // день рождения покупателя
    	// 'orderComments': string;   // комментарий к заказу
    ];
    $header=[
      'alg' => 'HS256',
      "typ" => "JWT"
    ];
    $segments[]=$this->url_safe_base64_encode(json_encode($header));
    // $segments[]='eyJ0eXBlIjoiUFVSQ0hBU0VfT05fUEFSVE5FUl9TSVRFIiwic3RvcmVJZCI6IjcxODI2OTIzNjJkYjQzMTM4ODQzYTBhN2Q1ZmNmMjMyIiwic3RvcmVVc2VybmFtZSI6IkB2b2xzdGVybSIsIm9yZGVySWQiOiIxOTgwNTQiLCJzZWxsZXJVc2VybmFtZSI6IkB2b2xzdGVybSIsImRlbGl2ZXJ5VHlwZSI6ItCh0LDQvNC-0LLRi9Cy0L7QtyIsImRlbGl2ZXJ5QWRkcmVzcyI6IjU2NTY1NiBjdnZiY3Z2YiBjdmJjdnZiIGhvdXNlIDUiLCJidXllckZ1bGxOYW1lIjoi0KLQtdGB0YIgYmFyIiwiYnV5ZXJQaG9uZSI6Iis3KDc3Nyk3NzctNzctNzciLCJidXllckVtYWlsIjoiY3RhcG9jdGExM0BnbWFpbC5jb20iLCJnb29kc0xpc3QiOlsi0J_QtdGA0YHQvtC90LDQu9GM0L3Ri9C5INC70YPQsdGA0LjQutCw0L3RgiBNb2lzdCBQZXJzb25hbCBMdWJyaWNhbnQg0L3QsCDQstC-0LTQvdC-0Lkg0L7RgdC90L7QstC1IOKAkyAxMCDQvNC7Il0sImNvc3RJblJ1YiI6MSwiY29zdEluQWlDb2luIjoyfQ';
    $segments[]=$this->url_safe_base64_encode(json_encode($token));
    $string=implode('.', $segments);
    $segments[] =
      $this->url_safe_base64_encode(
        hash_hmac('sha256', $string, $serviceKey, true)
        )
    ;
    // $segments[]=$string;
    $tokenJWT=implode('.', $segments);
    $this->tokenJWT=$tokenJWT;
    $this->segments=$segments;
    $this->serviceKey=$serviceKey;
    $this->token=json_encode($token);

    if($isTest) $this->link='https://refstage.imem.app/?link=https://imem.app/?actionByToken='.$tokenJWT.'&apn=com.iMe.android.stage';
    else $this->link='https://ref.imem.app/?link=https://imem.app/?actionByToken='.$tokenJWT.'&apn=com.iMe.android';

    // die('<pre>'.print_r($tokenJWT, true));
  }

  public function executeYookassaform(sfWebRequest $request) { //Форма оплаты Yookassa
    global $isTest;
    $order = $this->order;
    $ordPaym=OrdersPaymentsTable::getInstance()->findOneByOrderId($order->getId());
    if(!is_object($ordPaym) || $ordPaym->status!='succeeded'){//Если объекта еще нет или статус у него не оплачен
      $productOrder = $this->order->getText();
      $products_old = $productOrder != '' ? unserialize($productOrder) : '';
      $amount=0;
      foreach ($products_old as $key => $productInfo)//считаем сумму
      $amount+=(($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price'])*$productInfo['quantity'];
      $amount-=$this->order->getBonuspay();
      if(!is_object($ordPaym)) {//Если платеж еще не отправлялся
        $ordPaym = new OrdersPayments;
        $ordPaym->setOrderId($order->getId());
        $ordPaym->setStatus('new');
        $ordPaym->setPaymentService('yookassa');
      }
      // $confirmUrl='https://'.($isTest ? 'dev.' : '').'onona.ru/directpost_yandex';
      $confirmUrl='https://'.($isTest ? 'dev.' : '').'onona.ru/customer/orderdetails/'.$order->getId();

      // if(strtotime($ordPaym->getUpdatedAt()) < time()-23*60*60 || !$ordPaym->getIdempotence()){
      if(true){
        $idempotenceKey = uniqid('', true);
        $ordPaym->setIdempotence($idempotenceKey);
      }
      else{
        $idempotenceKey=$ordPaym->getIdempotence();
      }
      require $_SERVER['DOCUMENT_ROOT'].'/../lib/vendor/yookassa-sdk-php-master/lib/autoload.php';
      $client = new YooKassa\Client;
      $shopId=csSettings::get('YOO_KASSA_SHOP_ID');
      $apiKey=csSettings::get('YOO_KASSA_API_KEY');
      $client->setAuth($shopId, $apiKey);

      $response = $client->createPayment(
        array(
            'amount' => array(
                'value' => $amount,
                'currency' => 'RUB',
            ),
            // 'payment_method_data' => array(
            //     'type' => 'bank_card',
            // ),
            'confirmation' => array(
                'type' => 'redirect',
                'return_url' => $confirmUrl,
            ),
            'capture' => true,//Платеж в одну стадию, без подтверждения
            'description' => 'Заказ №'.csSettings::get('order_prefix').$order->getId(),
        ),
        $idempotenceKey
      );
      $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();
      $ordPaym->setPaymentId($response->getId());
      $ordPaym->save();

      $this->link=$confirmationUrl;
      if(!isset($this->instantRedirect))//Если приходим со страницы "Спасибо" - сразу перенаправляем пользователя на оплату
        $this->instantRedirect=false;
      // die($amount);

    }
    else {
      $this->link=false;
    }

  }

  public function executeMkbform(sfWebRequest $request) { //Форма оплаты МКБ
    // $password='1LsLNYeg';
    // $password='p13evP3B';//Боевой Поварешкин
    $password='k2gZmzVv';//Боевой Ип Зеновка
    $currencyId=643;

    $productOrder = $this->order->getText();
    $products_old = $productOrder != '' ? unserialize($productOrder) : '';
    $amount=0;
    foreach ($products_old as $key => $productInfo)//считаем сумму
      $amount+=(($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price'])*$productInfo['quantity'];
    // die('bar');
    $amount-=$this->order->getBonuspay();
    $amount*=100;
    $amount=str_pad($amount, 12, '0', STR_PAD_LEFT );

    // die('|'.$amount.'|');

    $mkbForm=[
      // 'mid' => '600000000000141', // Идентификатор Мерчанта (магазина).
      'mid' => '620000000002289', // Идентификатор Мерчанта (магазина) боевой. Ип Зеновка
      // 'mid' => '618000000003424', // Идентификатор Мерчанта (магазина) боевой. Поварешкин
      'method' => 'post', //заменить на post после отладки
      // 'action' => 'https://mpi.mkb.ru:9443/MPI_payment/', //URL обработчика. Заменить после теста
      'action' => 'https://mpi.mkb.ru/MPI_payment/', //URL обработчика. Боевой
      'aid' => '443222',  //Идентификатор Банка-Эквайера.
      'oid' => $this->order->getId(), //Номер заказа на сервере. Должен быть уникальным
      // 'directposturl' => 'http://dev.onona.ru/cart/directposturl', //сервисная страница подтверждения оплаты
      'directposturl' => 'https://onona.ru/cart/directposturl', //сервисная страница подтверждения оплаты
      /*
        URL страницы сайта, на которую будет передаваться ответ методом POST.

        На странице обязательно должен быть валидный SSL сертификат протокола TLS 1.2
        На сервера с сертификатом «ниже» TLS 1.2 сервер возвращать ответ не будет.
      */
      'redirect_url' => 'https://onona.ru/success_payment', //Страница клиента после оплаты
      // 'redirect_url' => 'https://onona.ru/customer/myorders', //Страница клиента после оплаты
      'cancel_link' => 'https://onona.ru/payment_cancel', //URL, на который будет перенаправлен клиент в случае нажатия на первой странице кнопки «Отмена».
      'client_mail' => $this->customer->getEmailAddress(),//email клиента
      'site_link' => 'https://onona.ru', //URL сайта
      'merchant_mail' => 'povar@onona.ru', //E-mail оператора/магазина/администратора.
      // 'merchant_mail' => 'aushakov@interlabs.ru', //E-mail оператора/магазина/администратора.
      'amount' => $amount,
    ];
    $signatureBase=$password.$mkbForm['mid'].$mkbForm['aid'].$mkbForm['oid'].$amount.$currencyId;
    $signature=
    base64_encode(
      // hex2bin(
        hash('sha256',
          $signatureBase, true
          )
        // )
    )
    ;/*
    die('<pre>'.print_r(
      [
        'base' => $signatureBase,
        '$signature'=>$signature,
        $password, $mkbForm['mid'],$mkbForm['aid'], $mkbForm['oid'], $amount, $currencyId, 'url проверки'=>'https://mpi.mkb.ru:9443/WebResource/'
      ], true)
    );*/
    // die('base|'.$signatureBase.'|');
    $mkbForm['signature']=$signature;
    $this->mkbForm=$mkbForm;
  }

  public function executeTopCart(sfWebRequest $request) {//Корзина в шапке
    $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

    $this->totalCost = 0;
    $this->productsCount = 0;
    foreach ($products_old as $product) {
        $this->productsCount += $product["quantity"];
        $this->totalCost += ($product["quantity"] * (($productInfo['discount']==100 || $product['price_w_discount'] > 0) ? $product['price_w_discount'] : $product['price']));
    }
    $this->products_old = $products_old;
  }

  /*public function executePartner(sfWebRequest $request)
  {

  }*/
}
?>
