<?php

/**
 * cart actions.
 *
 * @package    Magazin
 * @subpackage cart
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cart_newActions extends sfActions {

  // protected $csrf;

  public function __construct($context, $moduleName, $controllerName) {
    parent::__construct($context, $moduleName, $controllerName);

    $this->csrf = new CSRFToken($this);
  }

  /**
   *
   * @param sfRequest $request A request object
   */
  private function addBonusForOrder($orderDB){//Зачисляем бонусы при онлайн оплате
    // global $isTest;

    $persent_bonus_add=csSettings::get('persent_bonus_add');

    $haveBonus=false;
    $bonusList=Doctrine_Core::getTable('Bonus')
      ->createQuery('b')
      ->where("comment like '%Зачисление за заказ #".($orderDB->getPrefix()) . $orderDB->getId() . "%'")
      ->execute();
    foreach ($bonusList as $bonus) {
      $haveBonus=true;
      break;//достаточно одной записи
    }
    if($haveBonus) return;

    $arArtInfoForBonus = unserialize($orderDB->getText());

    $bonusSum = 0;
    foreach ($arArtInfoForBonus as $product) {
      if($product['productId']==14613) continue;  //За доставку не начисляем

      $productDB = ProductTable::getInstance()->findOneById($product['productId']);
      if(!is_object($productDB)) continue;
      $prodPrice=((isset($product['discount']) && $product['discount']==100) ||  $product['price_w_discount']>0) ? $product['price_w_discount'] : $product['price'];

      if(isset($product['price_final'])) $prodPrice=$product['price_final'];
      $prodBonus=$productDB->getBonus()  ? $productDB->getBonus() : $persent_bonus_add;

      $bonusSum += round($prodPrice * $product['quantity'] * $prodBonus / 100);

      // die('<pre>'.print_r([ $product, $prodPrice, $prodBonus, $bonusSum, $persent_bonus_add], true));
    }

    if(!$bonusSum) return;

    $SITE_NAME = 'onona.ru';
    $persent_bonus_pay=csSettings::get('PERSENT_BONUS_PAY');

    $bonusAddComment = '';
    /*if($orderDB->getPaymentId() == 60){//Если это Тинькофф запись о бонусах всё-равно создадим, чтобы потом не морочиться
      $bonusAddComment = '. За покупки в рассрочку бонусы не начисляются';
      $bonusSum = 0;
    }*/

    $bonus= new Bonus();
    $bonus->set('comment', "Зачисление за заказ #" . $orderDB->getPrefix() . $orderDB->getId() . $bonusAddComment );
    $bonus->set('bonus', $bonusSum);
    $bonus->set('user_id', $orderDB->getCustomerId());
    $bonus->save();

    if(!$bonusSum) return;

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
      // $this->myLog("Ошибка отправки почты");
    }

    return;

  }

  private function redirectToYookassa($order){//перенаправляем покупателя сразу на оплату
    global $isTest;

    $ordPaym = new OrdersPayments;
    $ordPaym->setOrderId($order->getId());
    $ordPaym->setStatus('new');
    $ordPaym->setPaymentService('yookassa');

    $productOrder = $this->order->getText();
    $products_old = $productOrder != '' ? unserialize($productOrder) : '';
    $amount=0;
    foreach ($products_old as $key => $productInfo)//считаем сумму
    $amount+=(($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price'])*$productInfo['quantity'];
    $amount-=$this->order->getBonuspay();

    $confirmUrl='https://'.($isTest ? 'dev.' : '').'onona.ru/customer/orderdetails/'.$order->getId();

    $idempotenceKey = uniqid('', true);
    $ordPaym->setIdempotence($idempotenceKey);

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

    $this->redirect($confirmationUrl);

  }

  public function executeCartTop(sfWebRequest $request) {//Показать корзину, AJAX
    $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

    $this->totalCost = 0;
    $this->productsCount = 0;
    foreach ($products_old as $product) {
        $this->productsCount += $product["quantity"];
        $this->totalCost += ($product["quantity"] * ($product['price_w_discount'] > 0 ? $product['price_w_discount'] : $product['price']));
    }
    $this->products_old = $products_old;
    return $this->renderPartial('cart_new/topCart', array('productsCount'=>$this->productsCount, 'totalCost' => $this->totalCost));
  }

  public function executeClearcart(sfWebRequest $request) {//Очистить корзину
    $this->getUser()->setAttribute('products_to_cart', '');
    return $this->renderText("Ваша корзина пуста.");
  }

  // пересчёт стоимости товаров при наличии купона
  private function getDiscountProductsArray($coupon, $productsIn=false){
    $couponText=$coupon;
    $maxCart = csSettings::get('max_cart_items');
    $deliveryId = (isset($_POST['deliveryId']) ? (int) $_POST['deliveryId'] : 0);

    if(!$couponText)
      $couponText=false;

    if($couponText) $coupon = CouponsTable::getInstance()
      ->createQuery()
      ->where("`text`='".$couponText."'")
      ->addWhere("`is_active`=1")
      ->addWhere("'".date('Y-m-d H:i:s')."' BETWEEN `startaction` AND `endaction`")
      ->execute();
    $couponData = [];
    if(!sizeof($coupon) || $couponText===false){
      $discount=0;
      $couponFail=true;
    }
    else{
      $couponData = $coupon[0]->getData();
      $couponFail=false;
      $discountSum=$coupon[0]->getDiscountSum();
      $discountSecond=$coupon[0]->getDiscountSecond();
      $thirdFree=$coupon[0]->getFreeThird();
      if(!$discountSum && !$discountSecond && !$thirdFree){
        $discount=$coupon[0]->getDiscount();
        $conditions=$coupon[0]->getConditions();
        if($conditions) {
          $conditions=unserialize($conditions);

        }
      }
      else $discount=0;
      if ($discountSecond  || $thirdFree) $discountSum=0;
      if ($thirdFree) $discountSecond=0;
      if(is_object($coupon[0]) && $coupon[0]->getIsImportant()) $isImportant=true;
      else $isImportant=false;
      if(is_object($coupon[0])) $minTotal=$coupon[0]->getMinSum();
      else $minTotal=0;
    }
    /*if(!empty($bonusData) && $bonusData['bonusCount']*$bonusData['bonusApply']){//нужно списать бонусы

    }*/

    if(!$couponText) $couponText='';
    $express_disc_50_if_gt_3 = (isset($couponData['express_disc_50_if_gt_3']) && $couponData['express_disc_50_if_gt_3']);
    if($discount>0 || $discountSum>0 || $discountSecond || $thirdFree || $express_disc_50_if_gt_3) $arrReturn['successCoupon'] = true;
    $arrReturn['success'] = 'success';
    $arrReturn['discount'] = $discount;
    $arrReturn['discountSum'] = $discountSum;
    $arrReturn['couponText'] = $couponText;
    $arrReturn['express_disc_50_if_gt_3'] = $express_disc_50_if_gt_3;

    if(!$productsIn) $products=unserialize($this->getUser()->getAttribute('products_to_cart'));
    else $products=$productsIn;
    $productsRes=$products;

    $orderTotal=0;
    $orderTotalWODiscount=0;
    $expressDeliveryProductsCount = 0; // число товаров с экспресс-доставкой

    foreach ($products as $key => $product) {
      if($maxCart && ($product['quantity'] > $maxCart)) $products[$key]['quantity'] = $maxCart;
      $orderTotal+=$product['price_w_discount']*$product['quantity']; //Вычисляем текущую сумму заказа
      if($product['price_w_discount']==$product['price']) $orderTotalWODiscount +=$product['price_w_discount']*$product['quantity'];
      if ($arrReturn['express_disc_50_if_gt_3'] && $product['is_express']) {
        $expressDeliveryProductsCount += $products[$key]['quantity'];
      }
    }

    // если 3 товара с экспресс-доставкой + купон = скидка 50%
    $userExpressDeliveryDiscount = ($deliveryId === 16 && $expressDeliveryProductsCount === 3);

    $arrReturn['order_total']=$orderTotal;
    $arrReturn['order_total_wo_discount']=$orderTotalWODiscount;

    if(!$arrReturn['successCoupon'] || $couponFail) {
      unset($arrReturn['success']);
      $arrReturn['products'] = array_values($products);
      // $arrReturn['products'] = $products;
      return $arrReturn;
    }
    $inn=array_keys($products);
    $inn[]=-1;//чтобы не был пустой массив
    $inn=implode(', ', $inn);
    $sqlBody="SELECT `id`, `is_coupon_enabled`, `slug` FROM `product` WHERE `id` IN($inn)";
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $prodDb=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE);


    if($couponText!='' && (!$minTotal || $minTotal < $orderTotal)){//если есть купон и нет минимальной суммы купона или сумма в корзине больше ее
      $orderSum=0; $orderCount=0;
      foreach ($productsRes as $key => $product) {
        // die(__LINE__.'|'.print_r(['$products'=>$products, '$productsRes'=> $productsRes, ], true));
        // return $this->renderText(json_encode($conditions));
        if ($product['is_express'] && $userExpressDeliveryDiscount) {
          $products[$key]['price_w_discount'] = $product['price_w_discount'] = floor($product['price'] * 0.5);
          $products[$key]['discount'] = $product['discount'] = $product['price'] - $product['price_w_discount'];
          $discountSum += $products[$key]['discount'] * $products[$key]['quantity'];
        } else if($isImportant && !$discountSum){//Если скидка на все и она не в рублях
          $products[$key]['price_w_discount']=round((1-$discount/100)*$product['price_w_discount']) ;
          $products[$key]['discount']=100 - round($product['price_w_discount']/$product['price']*100);
          // die('<pre>'.print_r($product, true));
        }
        elseif($product['price_w_discount']==$product['price'] && $discount && $prodDb[$key]['is_coupon_enabled']){//Если скидки на товар еще нет и есть скидка в процентах
          $notHaveDiscountCat=$notHaveDiscountDop=false;
          if(isset($conditions['cats']) && sizeof($conditions['cats'])){//Проверяем принадлежность категориям
            $sqlBody=
              "SELECT `category_id` "
              ."FROM `category_product` "
              ."WHERE `product_id` = ".$product['productId']." "
              ."AND `category_id` IN(".implode(', ', $conditions['cats']).") "
              ."";
            $res=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
            if(!sizeof($res)) $notHaveDiscountCat=true;
            // return $this->renderText(json_encode($sqlBody));
          }
          if(isset($conditions['brands']) || isset($conditions['collections']) || isset($conditions['suitable-for'])){
            $arDops=array_merge(
              sizeof($conditions['brands']) ? $conditions['brands'] : [],
              sizeof($conditions['collections']) ? $conditions['collections'] : [],
              sizeof($conditions['suitable-for']) ? $conditions['suitable-for'] : []
             );


             if(sizeof($arDops)){//Проверяем наличие дополнительной характеристики
              $sqlBody=
                "SELECT `dop_info_id` "
                ."FROM `dop_info_product` "
                ."WHERE `product_id` = ".$product['productId']." "
                ."AND `dop_info_id` IN(".implode(', ', $arDops).") "
                ."";
              $res=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
              if(!sizeof($res)) $notHaveDiscountDop=true;
              // return $this->renderText(json_encode($res));
            }
          }
          if($notHaveDiscountDop && !sizeof($conditions['cats'])) continue; //категорий нет, по допхарактеристикам не катит
          if($notHaveDiscountCat && !sizeof($arDops)) continue; //по категориям не катит, допов нет
          // die(__LINE__.'|'.print_r([$notHaveDiscountDop, $notHaveDiscountCat, $conditions], true));
          if($notHaveDiscountDop && $notHaveDiscountCat) continue;//нет ни того ни того, но и то и то задано

          $products[$key]['price_w_discount']=round((1-$discount/100)*$product['price']);
          $products[$key]['discount']=$discount;
        }
        elseif($product['price_w_discount']==$product['price'] && $discountSum && $prodDb[$key]['is_coupon_enabled']){//Если скидки на товар еще нет и есть скидка в рублях
          $orderSum+=$product['price']*$product['quantity'];
          $orderCount+=$product['quantity'];
        }
        // elseif(($thirdFree || $discountSecond) && /*$prodDb[$key]['is_coupon_enabled'] &&*/ ($thirdFree || $product['price_w_discount']==$product['price'])){
        elseif(($thirdFree || $discountSecond)){//если скидка на второй или третий товар
          // die(__LINE__.'|'.print_r(['$products'=>$product, '$arProductsSecond'=> $arProductsSecond], true));
          $arProductsPrice[] = $product['price'];
          $arProductsSecond['s_'.$key] = $product;
        }
      }
      // die(__LINE__.'|'.print_r(['$products'=>$products, '$arProductsSecond'=> $arProductsSecond], true));
      array_multisort($arProductsPrice, SORT_DESC, $arProductsSecond,SORT_DESC);
      // die(print_r([/*'$arProductsPrice'=> $arProductsPrice, */'$arProductsSecond'=> $arProductsSecond], true));
      if($discountSum > 0 ){//Если надо предоставить скидку в рублях
        if( !$isImportant){
          if($orderSum - $orderCount < $discountSum  ) { //Если величина скидки больше, чем сумма заказа
            $discountSum = $orderSum - $orderCount;
          }
          $orderSumPercent = $discountSum/$orderSum*100;
          if($coupon[0]->getDiscount() && $orderSumPercent > $coupon[0]->getDiscount() ) $orderSumPercent = $coupon[0]->getDiscount() ;
          foreach ($products as $key => $product) {
            if($product['price_w_discount']==$product['price'] && $prodDb[$key]['is_coupon_enabled']){
              $newPrice=round((1-$orderSumPercent/100)*$product['price']);
              if($newPrice<1) $newPrice=1;
              $products[$key]['price_w_discount']=$newPrice;
              $products[$key]['discount']=round($orderSumPercent);
            }
          }
        }
        else{
          if($orderTotal - $orderCount < $discountSum  ) { //Если величина скидки больше, чем сумма заказа
            $discountSum = $orderTotal - $orderCount;
          }
          $orderSumPercent = $discountSum/$orderTotal*100;
          // if($coupon[0]->getDiscount() && $orderSumPercent > $coupon[0]->getDiscount() ) $orderSumPercent = $coupon[0]->getDiscount() ;
          foreach ($products as $key => $product) {
            // if($product['price_w_discount']==$product['price'] && $prodDb[$key]['is_coupon_enabled']){
              $newPrice=round((1-$orderSumPercent/100)*$product['price_w_discount']);
              if($newPrice<1) $newPrice=1;
              $products[$key]['price_w_discount']=$newPrice;
              $products[$key]['discount']=100 - round($products[$key]['price_w_discount']/$product['price']*100);
            // }
          }
        }
      }
      if($discountSecond > 0) {//Скидка на второй товар
        $arrReturn['discountSecond']=$discountSecond;
        $i=0;
        foreach ($arProductsSecond as  $value) {
          if(!$prodDb[$value['productId']]['is_coupon_enabled']) continue;
          if ($i){
            $newPrices[]=$value['productId'];
            $products[$value['productId']]['price_w_discount']=round((1-$discountSecond/100)*$value['price']);
          }
          $i=!$i;

        }
      }
      if($thirdFree) {//третий бесплатно
        $i=1;
        // die(__LINE__.'|'.print_r(['$products'=>$products, '$arProductsSecond'=> $arProductsSecond], true));
        foreach ($arProductsSecond as  $value) {
          if(!$prodDb[$value['productId']]['is_coupon_enabled']) continue;
          if($value['quantity'] > 1) continue;
          if (!($i++%3)){
            $arrReturn['thirdFree']=true;
            $newPrices[]=$value['productId'];
            $products[$value['productId']]['price_w_discount']=0;
            $products[$value['productId']]['discount']=100;
          }

        }
      }
    }
    else{
      $arrReturn['discountSum'] = $arrReturn['discount'] = $arrReturn['successCoupon'] = 0;
      unset($arrReturn['success']);
    }

    $arrReturn['products'] = array_values($products);

    $orderTotal=0;
    foreach ($products as $key => $product) {
      $orderTotal+=$product['price_w_discount']*$product['quantity']; //Вычисляем текущую сумму заказа
    }
    $arrReturn['order_total']=$orderTotal;
    return $arrReturn;
  }

  public function executeCheck(sfWebRequest $request) {//Проверка купона и пересчет корзины при необходимости

    $couponText=mb_strtoupper($request->getParameter('coupon', 0), 'UTF-8');

    $arrReturn= $this->getDiscountProductsArray($couponText);
    return $this->renderText(json_encode($arrReturn));
  }

  public function executeAddtocart(sfWebRequest $request) {//Добавить в корзину

    $maxCart=csSettings::get('max_cart_items');

    $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
    $row = array();
    $row["productId"] = (int) $request->getParameter('id');
    $row["productOptions"] = $request->getParameter('productOptions');
    $row["quantity"] = $request->getParameter('quantity', 1);

    $product = ProductTable::getInstance()->findOneById($row["productId"]);

    $row["price"] = $product->getPrice();
    $row["is_express"] = $product->getIsExpress();
    if ($product->getOldPrice() > 0) {
      $row["price"] = $product->getOldPrice();
      $row["price_w_discount"] = $product->getPrice();
      $row["discount"] = round((1 - ($product->getPrice() / $product->getOldPrice())) * 100);
    } else {
      $row["price"] = $product->getPrice();
      $row["price_w_discount"] = $product->getPrice();
    }
    $this->product=$product;
    $photos = PhotoTable::getInstance()
      ->createQuery()
      ->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $this->product->getId() . " limit 0,1)")
      ->orderBy("position")
      ->execute();
    $this->photo=false;
    if(sizeof($photos))
      $this->photo="/uploads/photo/thumbnails_250x250/".$photos[0]->getFilename();
    if(!$this->photo || !file_exists($_SERVER['DOCUMENT_ROOT'].$this->photo))
      $this->photo='/frontend/images/no-image.png';

      $this->bonus = round(
        //(
        $this->product->getPrice()// - $this->product->getPrice() * ($this->product->getBonuspay() > 0 ? $this->product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100)
        *
        (($this->product->getBonus() > 0 ? $this->product->getBonus() : csSettings::get('persent_bonus_add')) / 100)
      ) ;
    $this->stock=$this->getCountShopForProduct($product);

    $products_old[$row["productId"]] = $row;
    // $productDB = ProductTable::getInstance()->findOneById($row["productId"]);
    // $generalCategory = $productDB->getGeneralCategory();

    // $personalRecomendation = unserialize(base64_decode(sfContext::getInstance()->getRequest()->getCookie('personalRecomendationCategory')));
    // $personalRecomendation['category'][$generalCategory->getId()] = $personalRecomendation['category'][$generalCategory->getId()] + 2;
    // $personalRecomendation['products'][$row["productId"]] = $personalRecomendation['products'][$row["productId"]] + 3;

    // sfContext::getInstance()->getResponse()->setCookie('personalRecomendationCategory', base64_encode(serialize($personalRecomendation)), time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
    // if ($this->getUser()->isAuthenticated()) {
    //   $GuardUser = $this->getUser()->getGuardUser();
    //   $GuardUser->set("personal_recomendation", serialize($personalRecomendation));
    //   $GuardUser->save();
    // }

    $this->totalCost = 0;
    $this->productsCount = 0;
    foreach ($products_old as &$product) {
      if($maxCart && $product["quantity"]>$maxCart) $product["quantity"]=$maxCart;
      $this->productsCount += $product["quantity"];
      $this->totalCost += ($product["quantity"] * $product["price"]);
    }
    $this->getUser()->setAttribute('productsCount', $this->productsCount);
    $this->getUser()->setAttribute('totalCost', $this->totalCost);
    $this->getUser()->setAttribute('products_to_cart', serialize($products_old));

    if ($this->getUser()->isAuthenticated()) {
      $GuardUser = $this->getUser()->getGuardUser();
      $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
      $GuardUser->save();
    }
    $this->productslist=implode(',', array_keys($products_old));

    // return $this->renderPartial('');
  }

  public function executeCartfromlanding(sfWebRequest $request) {//Добавление в корзину из лендинга
    // $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
    $row = array();
    $row["productId"] = (int) $request->getParameter('id');

    $row["quantity"] = 1;
    $product = ProductTable::getInstance()->findOneById($row["productId"]);
    $this->forward404Unless($product);
    $this->totalCost = 0;
    $this->productsCount=0;
    if(is_object($product)){

      $row["price"] = $product->getPrice();
      $row["is_express"] = $product->getIsExpress();
      if ($product->getOldPrice() > 0) {
          $row["price"] = $product->getOldPrice();
          $row["price_w_discount"] = $product->getPrice();
          $row["discount"] = round((1 - ($product->getPrice() / $product->getOldPrice())) * 100);
      } else {
          $row["price"] = $product->getPrice();
          $row["price_w_discount"] = $product->getPrice();
      }
      $row["quantity"] = 1;
      $this->totalCost += ($row["quantity"] *   $row["price_w_discount"]);
      $this->productsCount= 1;
      $products_old[$row["productId"]] = $row;
    }

    $this->getUser()->setAttribute('productsCount', $this->productsCount);
    $this->getUser()->setAttribute('totalCost', $this->totalCost);
    $this->getUser()->setAttribute('products_to_cart', serialize($products_old));

    if ($this->getUser()->isAuthenticated()) {
        $GuardUser = $this->getUser()->getGuardUser();
        $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
        $GuardUser->save();
    }
    // die('<pre>!'.print_r($products_old, true).'!</pre>');
    $this->redirect('/cart?username='.$request->getParameter('name').'&phone='.$request->getParameter('phone'));
    //  https://dev.onona.ru/cart-from-landing?id=14126&name=username&phone=userphone
  }

  public function executeAddtocartya(sfWebRequest $request) {//Добавление в корзину для турбо-страниц

    $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
    $row = array();
    $row["productId"] = (int) $request->getParameter('id');
    $row["productOptions"] = $request->getParameter('productOptions');
    $row["quantity"] = $request->getParameter('quantity', 1);
    $product = ProductTable::getInstance()->findOneById($row["productId"]);
    if(is_object($product)){

      $row["price"] = $product->getPrice();
      $row["is_express"] = $product->getIsExpress();
      if ($product->getOldPrice() > 0) {
          $row["price"] = $product->getOldPrice();
          $row["price_w_discount"] = $product->getPrice();
          $row["discount"] = round((1 - ($product->getPrice() / $product->getOldPrice())) * 100);
      } else {
          $row["price"] = $product->getPrice();
          $row["price_w_discount"] = $product->getPrice();
      }
      $products_old[$row["productId"]] = $row;
    }

    $this->totalCost = 0;
    $this->productsCount = 0;
    $text=
      '<p>Onona.ru</p>'.
      '<h1>Ваша корзина</h1>'.
      '<table>'.
        '<thead>'.
          '<tr>'.
            '<th>№</th>'.
            '<th>Наименование</th>'.
            '<th>Количество</th>'.
            '<th>Цена</th>'.
            '<th>Сумма</th>'.
          '</tr>'.
        '</thead>'.
        '<tbody>'.
    '';
    $i=1;
    foreach ($products_old as $key => $product) {
      $this->productsCount += $product["quantity"];
      $this->totalCost += ($product["quantity"] * $product["price"]);
      $productBase = ProductTable::getInstance()->findOneById($key);
      if(is_object($productBase))
        $text.=
          '<tr>'.
            '<td>'.$i++.'</td>'.
            '<td>'.$productBase->getName().'</td>'.
            '<td>'.$product["quantity"].'</td>'.
            '<td>'.$product["price"].'</td>'.
            '<td>'.($product["quantity"] * $product["price"]).'</td>'.
          '</tr>'.
          '';
    }
    $text.=
        '</tbody>'.
        '<tfoot>'.
        '<tfoot>'.
        '<tr>'.
          '<th></th>'.
          '<th>Итого</th>'.
          '<th>'.$this->productsCount.'</th>'.
          '<th></th>'.
          '<th>'.$this->totalCost.'</th>'.
        '</tr>'.
      '</table>'.
    '';
    $this->getUser()->setAttribute('productsCount', $this->productsCount);
    $this->getUser()->setAttribute('totalCost', $this->totalCost);
    $this->getUser()->setAttribute('products_to_cart', serialize($products_old));

    if ($this->getUser()->isAuthenticated()) {
        $GuardUser = $this->getUser()->getGuardUser();
        $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
        $GuardUser->save();
    }

    header('Content-Type: text/html; charset=utf-8');
    die($text);

  }

  public function executeDeletefromcart(sfWebRequest $request) {//Удалить элемент из корзины
    $this->products_old = $this->getUser()->getAttribute('products_to_cart');
    $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';
    //Получили корзину
    $productId = (int) $request->getParameter('id');
    if ($productId > 0 and is_array($this->products_old)) {
      // $productId = $productId - 1;
      unset($this->products_old[$productId]);
    }
    //Удалили товар, если он есть
    $this->getUser()->setAttribute('products_to_cart', serialize($this->products_old));
    if ($this->getUser()->isAuthenticated()) {
      $GuardUser = $this->getUser()->getGuardUser();
      $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
      $GuardUser->save();
    }
    $data=['success' => 'success'];

    //Сохранили в профиль, если пользователь авторизован
    return $this->renderText(json_encode($data));
  }

  private function printReceipt($orderId)  {
    global $isTest;
    try {
      $cass= new atolPos($isTest);
      $res=$cass->printDoc($orderId);
      if(is_object($res->error) && $res->error->code){
        if($res->error->code==10 || $res->error->code==11) $cass->getToken(true);
        // echo 'try two';
        $res=$cass->printDoc($orderId);
        if(is_object($res->error) && $res->error->code){
          file_put_contents(
            $_SERVER['DOCUMENT_ROOT'].'/../atol.log',
            "\n\n----printReceipt----------- ".date('d.m.Y H:i:s')." ---------------\n".
            "\n---- Ошибка повторной печати чека -----------------------------------\n".
            print_r($res, true),
            FILE_APPEND
          );
        }
      }
    } catch (\Exception $e) {
      file_put_contents(
        $_SERVER['DOCUMENT_ROOT'].'/../atol.log',
        "\n\n----printReceipt----------- ".date('d.m.Y H:i:s')." ---------------\n".
        "\n---- Ошибка инициализации объекта -----------------------------------\n".
        print_r($res, true),
        FILE_APPEND
      );
    }



  }

  private function getBonusSettings($orderTotal){//Выставляет допустимые размеры использования бонусов
    $step3=csSettings::get('PERSENT_BONUS_STEP_TOP');
    $step2=csSettings::get('PERSENT_BONUS_STEP_MIDDLE');
    if($orderTotal>$step3-1){
      $this->percentBase=csSettings::get('PERSENT_BONUS_PAY_6990');
      $this->bonusMinLimit=$step3-1;  //При достижении этой суммы в корзине бонусы нужно будет пересчитать
      $this->bonusTopLimit=0;     //При достижении этой суммы в корзине бонусы нужно будет пересчитать
    }
    elseif ($orderTotal>$step2) {
      $this->percentBase=csSettings::get('PERSENT_BONUS_PAY_2991');
      $this->bonusMinLimit=$step2;
      $this->bonusTopLimit=$step3;
    }
    else{
      $this->percentBase=csSettings::get('PERSENT_BONUS_PAY');
      $this->bonusMinLimit=0;
      $this->bonusTopLimit=$step2-1;
    }
  }

  private function getCountShopForProduct($product){//Возвращает количество магазинов, в которых есть товар
    if(!is_object($product)) return 0;
    $stock = unserialize($product->getStock());
    if (( count($stock['storages']['storage']) > 0)) {
      foreach ($stock['storages']['storage'] as $storage){
        if ($storage['@attributes']['code1c']) {
            $shop = ShopsTable::getInstance()->findOneById1c($storage['@attributes']['code1c']);
            $codeShop1cIsStock[] = "'".$storage['@attributes']['code1c']."'";
        } else {
            $shop = ShopsTable::getInstance()->findOneById1c($storage['code1c']);
            $codeShop1cIsStock[] = "'".$storage['code1c']."'";
        }//Получаем магазин

        if ($shop){
          if(!$shop->getIsActive()) continue;
        }
        else continue;
        $shopsInStock[]=$shop;
      }

    }
    return count($shopsInStock);
  }

  public function executeIndex(sfWebRequest $request) {//чекаут

    if(isset($_GET['ildebug'])) die ('<pre>'.print_r($_SERVER, true));

    $this->cartFirstPage = 1;
    $needToSaveBasket = false;
    if ($this->getUser()->isAuthenticated()) {
        $this->bonus = BonusTable::getInstance()->findBy('user_id', $this->getUser()->getGuardUser()->getId());
        $this->bonusCount = 0;
        foreach ($this->bonus as $bonus) {
            $this->bonusCount = $this->bonusCount + $bonus->getBonus();
        }
    } else {
        $this->bonusCount = 0;
    }
    if($this->bonusCount<0) $this->bonusCount=0;
    // if($isTest) $this->bonusCount = 5000;
    $bonusCountTemp = $this->bonusCount;
    $this->products_old = $this->getUser()->getAttribute('products_to_cart');
    $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';
    /*
    if (is_array($this->products_old)) {
      $needToSaveBasket = true;
        unset($this->products_old['д1']);
    }*/
    $i=0;
    $isCouponsEnabled=true;
    $orderTotal=0;

    foreach ($this->products_old as $keyProdCart => $prod) {
      $product = ProductTable::getInstance()->findOneById($prod["productId"]);
      if(!is_object($product) || 1*$product->getCount() < 1){//Если товара в базе нет - в топку его из корзины
        $needToSaveBasket = true;
        unset($this->products_old[$keyProdCart]);
        continue;
      }
      $stock[$keyProdCart]=$this->getCountShopForProduct($product);

      $products[$keyProdCart]=$product;

      $orderTotal+=$prod['price_w_discount']*$prod['quantity'];
    }//Посчитали сумму, удалили лишние товары, выяснили разрешен ли купон
    $this->stock=$stock;

    $this->isYandexNewEnabled=ILTools::isTaxiEnabed($products);


    $this->getBonusSettings($orderTotal);

    foreach ($this->products_old as $keyProdCart => $prod) {//теперь будем рулить бонусами
      if ($products[$keyProdCart]->getIsBonusEnabled() && $prod['price'] == $prod['price_w_discount'] && $bonusCountTemp > 0) {
        if ($products[$keyProdCart]->getBonuspay() != '') {
          $percentBonuspay = $products[$keyProdCart]->getBonuspay();
        } else {
          $percentBonuspay = $this->percentBase;
        }
        $procBonus = $bonusCountTemp / ($prod['quantity'] * $prod['price']) * 100;
        $percentBonusPayForCount = floor($procBonus / 5) * 5;
        if ($percentBonusPayForCount < $percentBonuspay) {
          $percentBonuspay = $percentBonusPayForCount;
        }
        $bonusCountTemp = $bonusCountTemp - round($prod['price'] * ($percentBonuspay / 100));
        $this->products_old[$keyProdCart]['bonuspay'] = round($prod['quantity'] * $prod['price'] * ($percentBonuspay / 100));
        $this->products_old[$keyProdCart]['percentbonuspay'] = $percentBonuspay;
        $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
      } else {
        $this->products_old[$keyProdCart]['bonuspay'] = 0;
        $this->products_old[$keyProdCart]['percentbonuspay'] = 0;
        $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
      }
    }

    if ($this->getUser()->getAttribute('actionCartCode') != "") {
      $this->executeActioninfo($request);
    }
    if($needToSaveBasket){//Сохранили, если нужно
      $this->getUser()->setAttribute('products_to_cart', serialize($this->products_old));

      if ($this->getUser()->isAuthenticated()) {
        $GuardUser = $this->getUser()->getGuardUser();
        $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
        $GuardUser->save();
      }
    }
    $this->products=$products;
    $this->delivers = DeliveryTable::getInstance()->createQuery()->where('is_public = \'1\'')->orderBy('position ASC')->execute();
    $isYandexEnable=false;
    // if(date('G')>=10 || date('G')<=8){
    if(date('G')>=21 || date('G')<=8){
      $isYandexEnable=true;
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      foreach($this->products_old as $productCart){
        $sqlBody="select c.slug, cp.category_id from category_product cp left join category c on cp.category_id=c.id where cp.product_id=".$productCart['productId']." and c.slug='express';";
        $count= $q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
        // echo $sql_body.'<br>';
        if(!sizeof($count)){
          $isYandexEnable=false;
          break;
        }
      }
      // die('<pre>'.print_r($count, true));
    }
    $user=false;
    if($this->getUser()->isAuthenticated()) $user = $this->getUser()->getGuardUser();
    $this->user=$user;
    $this->isYandexEnable=$isYandexEnable;

    $this->isCouponsEnabled=$isCouponsEnabled;

    $this->discountNotApply=$this->getDiscountNotApply();
    // die('<pre>'.print_r($discountNotApply, true));
  }

  private function getDiscountNotApply(){//Формирует массив товаров, для которых запрещено применять скидку 7% за оплату онлайн
    $discountNotApplyTmp=csSettings::get('discount_not_apply');
    if(trim($discountNotApplyTmp)){
      foreach (explode(',', $discountNotApplyTmp) as $value) {
        $discountNotApply[]=(int) $value;
      }
    }
    $discountNotApply[]=-1;
    return $discountNotApply;
  }

  public function executeAddtocartcount(sfWebRequest $request) { //Добавить товар в корзине

    $maxCart=csSettings::get('max_cart_items');

    $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

    foreach ($products_old as $key => $product) {
        if (@$product["productId"] == @$request->getParameter('id')) {
            $products_old[$key]["quantity"] = $request->getParameter('count');
            if($maxCart && $products_old[$key]["quantity"]>$maxCart) $products_old[$key]["quantity"]=$maxCart;
        }
    }

    $this->getUser()->setAttribute('products_to_cart', serialize($products_old));
    if ($this->getUser()->isAuthenticated()) {
      $GuardUser = $this->getUser()->getGuardUser();
      $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
      $GuardUser->save();
    }

    return $this->renderText($request->getParameter('count'));
  }

  public function executeConfirmedcheck(sfWebRequest $request) { //Проверка формы заказа
    if (!$this->csrf->isValidKey('_csrf_token', $_POST['sf_guard_user'])) {
      return $this->renderText(json_encode(['error' => 'некорректный CSRF-токен']));
    }
    if($request->getParameter('token') != 'Роботы нам тут не нужны!')
      return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
    if(!$request->getParameter('agreement'))
      return $this->renderText(json_encode(['error' => 'Необходимо принять пользовательское соглашение']));
    if(!$request->getParameter('agreement-18'))
      return $this->renderText(json_encode(['error' => 'Необходимо подтвердить, что Вам больше 18 лет']));
    if(!$request->getParameter('deliveryId'))
      return $this->renderText(json_encode(['error' => 'Не выбран способ доставки', 'field' => 'move', 'element' => '#delivery-block']));
      // return $this->renderText(json_encode(['error' => 'Не выбран способ доставки', 'field' => 'alert']));
    if(!$request->getParameter('paymentId'))
      return $this->renderText(json_encode(['error' => 'Не выбрана форма оплаты', 'field' => 'move', 'element' => '#payment-block']));
      // return $this->renderText(json_encode(['error' => 'Не выбрана форма оплаты', 'field' => 'alert']));
    if(!$request->getParameter('first_name'))
      return $this->renderText(json_encode(['error' => 'Заполните имя', 'field' => 'first_name']));
    if(!$request->getParameter('phone'))
      return $this->renderText(json_encode(['error' => 'Укажите телефон', 'field' => 'phone']));
    if(!$this->is_email($request->getParameter('user_mail')))
      return $this->renderText(json_encode(['error' => 'Заполните электронную почту', 'field' => 'user_mail']));
    if(!in_array($request->getParameter('deliveryId'), [10, 11, 14, 17, 18, 19, 21])){//Если вид доставки самовывоз - адрес не проверяем
      if(!$request->getParameter('city'))
        return $this->renderText(json_encode(['error' => 'Укажите город', 'field' => 'city']));
      if(!$request->getParameter('street'))
        return $this->renderText(json_encode(['error' => 'Укажите улицу', 'field' => 'street']));
      if(!$request->getParameter('house'))
        return $this->renderText(json_encode(['error' => 'Укажите дом', 'field' => 'house']));
    }
    if($request->getParameter('deliveryId') == 21 && empty($request->getParameter('via_id')))
      return $this->renderText(json_encode(['error' => 'Не выбрана точка доставки', 'field' => 'alert', 'element' => '#address-21']));
    if($request->getParameter('deliveryId') == 14 && empty($request->getParameter('dlh_shop_id')))
      return $this->renderText(json_encode(['error' => 'Не выбрана точка доставки', 'field' => 'alert', 'element' => '#address-14']));
    if($request->getParameter('deliveryId') == 19 && empty($request->getParameter('sdek_id')))
      return $this->renderText(json_encode(['error' => 'Не выбрана точка доставки', 'field' => 'alert', 'element' => '#address-19']));
    if($request->getParameter('deliveryId') == 18 && empty($request->getParameter('post_id')))
      return $this->renderText(json_encode(['error' => 'Не выбрана точка доставки', 'field' => 'alert', 'element' => '#address-18']));
    if($request->getParameter('deliveryId') == 11 && empty($request->getParameter('pickpoint_id')))
      return $this->renderText(json_encode(['error' => 'Не выбрана точка доставки', 'field' => 'alert', 'element' => '#address-11']));
    if(60 == $request->getParameter('paymentId')){
      // Возможно нужны дополнительные проверки
      // return $this->renderText(json_encode(['error' => 'Выбрана оплата Тинькоф', 'field' => 'alert']));
      return $this->renderText(json_encode(['tinkoff' => 'tinkoff']));
    }
    $phone = ILTools::cleanPhone($request->getParameter('phone', false));
    if ('on' == csSettings::get("red_sms_active")) {
      $user = $this->getUser();
      $checkByTime = true;  //становится false если у авторизованного пользователя есть оплаченные заказы за 180 дней
      if ($user->isAuthenticated()) {
        $orders = OrdersTable::getInstance()
          ->createQuery('o')
          ->where('customer_id=?', $user->getGuardUser()->getId())
          ->addWhere('status = "Оплачен"')
          ->addWhere('created_at > ?', date('Y-m-d H:i:s', strtotime('-180 days')))
          ->orderBy("id DESC")
          ->limit(1)
          ->execute();
        if(sizeof($orders)) $checkByTime = false;
      }
      $current = Doctrine_Core::getTable('SmsProof')->findOneByPhone($phone);

      if ($checkByTime && (empty($current) || $current->getUpdatedAt() < date('Y-m-d H:i:s', time() - 24 * 60 * 60) || !empty($current->getText()))) {
        return $this->renderText(json_encode(['error' => 'Подтвердите телефон', 'field' =>'create_proof', 'proofType' => 'basket']));
      }
    }

    return $this->renderText(json_encode(['checkout' => 'checkout']));
  }

  public function executeConfirmedtest(sfWebRequest $request) { //Подтверждение заказа тест
    // die($_SERVER['DOCUMENT_ROOT'].'/mkb_pay_test.log');
    $this->order = OrdersTable::getInstance()->findOneById('198173');
    $this->customer = sfGuardUserTable::getInstance()->findOneById($this->order->getCustomerId());
    $this->pay = PaymentTable::getInstance()->findOneById((int) $this->order->getPaymentId());
    $this->paymentDoc = PaymentdocTable::getInstance()->findOneByOrderId((int) $this->order->getId());
    $this->bonusAddUser=324;
    // die('!'.is_object($this->paymentDoc).'!');
  }

  private function url_safe_base64_encode( $input) {// для executeDirectposturlime
    // return base64_encode($input);
    return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
  }

  public function executeDirectposturlime(sfWebRequest $request) { //Подтверждение оплаты заказа iMe
    $needLog=true;
    $serviceKey=trim(csSettings::get('key_ime'));
    $storeId=csSettings::get('shop_id_ime');
    $exchangeRate=csSettings::get('change_rate_ime');
    $logData="\n--------------------------------------------------\n".date('d.m.Y H:i:s')."\n";
    $token=explode('.',$request->getParameter('token'));
    $logData.='token'.print_r($token, true)."\n";
    $tokenData=base64_decode($token[1]);
    $logData.=print_r($tokenData, true)."\n";
    /*
      {
      	// Обязательные поля
      	'type': 'PAYMENT_FOR_ORDER_FROM_PARTNER_SITE_DONE'
      	'storeId': string;         // Идентификатор интернет-магазина
      	'orderId': string;         // Идентификатор заказа
      	'costInRub': number;       // стоимость в рублях (с доставкой и скидкой)
      	'costInAiCoin': number;    // стоимость в AiCoin (с доставкой и скидкой)
      	'paymentDate': string;      // дата и время оплаты (по гринвичу, ISO8601)
      }
    */
    $haveError=false;
    //Проверяем корректность полей. Если есть проблема - отдаем 404

    // if(hash_hmac('sha256', $token[0].'.'.$token[1], $serviceKey)!=$this->url_safe_base64_encode($token[2]))
    //   die('token sign error!');
    $tokenObj=json_decode($tokenData);
    if($tokenObj->type!='PAYMENT_FOR_ORDER_FROM_PARTNER_SITE_DONE'){
      $haveError=true;
      $logData.="Неправильный тип операции\n";
    }
    if($tokenObj->storeId!=$storeId){
      $haveError=true;
      $logData.="Неправильный $storeId\n";
    }
    $order = OrdersTable::getInstance()->findOneById($tokenObj->orderId);
    if(!is_object($order)){
      $haveError=true;
      $logData.="Нет заказа ".$tokenObj->orderId."\n";
    }
    if ($haveError) $this->forward404Unless(false);
    /* //Возможно проверить тип оплаты
    if(!is_object($order)){
      $haveError=true;
      $logData.="Нет заказа ".$tokenObj->orderId."\n";
    }
    */

    if(is_object($order)){//Если есть заказ
      //Возможно надо проверять на форму оплаты заказа
      $customer = sfGuardUserTable::getInstance()->findOneById($order->getCustomerId());
      if(is_object($customer)){//Если нашли пользователя - можно отправить ему письмо

      }
      $order->setComments($order->getComments() . " \r\n Оплачено в iMe Wallet");
      $order->save();
      $logData.="Обновлена информация в заказе\n";
      $paymentdoc=new Paymentdoc();
      $paymentdoc->set('order_id', $tokenObj->orderId);
      $paymentdoc->set('amount', (int)100*$tokenObj->costInRub);
      // $paymentdoc->set('card_no', $_POST['PaddedCardNo']);
      // $paymentdoc->set('fio', $_POST['fio']);
      $paymentdoc->set('payment_type', 'ime');
      // $paymentdoc->set('response_code', $_POST['ReasonCode']);
      $paymentdoc->save();
    }
    if ($needLog) file_put_contents($_SERVER['DOCUMENT_ROOT'].'/ime_pay_test.log', $logData."\n", FILE_APPEND);
    die(json_encode(['result'=>'true']));
  }

  private function saveLog($filname, $name, $data){
    // return;
    file_put_contents(
      $filname,
      "\n\n---- $name ----------- ".date('d.m.Y H:i:s')." ---------------\n".
      print_r($data, true),
      FILE_APPEND
    );
  }

  public function executeDirectposturlyandex(sfWebRequest $request) { //Подтверждение оплаты заказа яндекс кассы yookassa
    $needLog=true;
    global $isTest;
    $logname=[
      'file' => $_SERVER['DOCUMENT_ROOT'].'/../yookassa.log',
      'string' => 'executeDirectposturlyandex',
    ];
    $source = file_get_contents('php://input');
    $requestBody = json_decode($source, true);

    if($needLog) $this->saveLog($logname['file'], $logname['string'], $requestBody);

    require $_SERVER['DOCUMENT_ROOT'].'/../lib/vendor/yookassa-sdk-php-master/lib/autoload.php';

    // Создайте объект класса уведомлений в зависимости от события
    // NotificationSucceeded, NotificationWaitingForCapture,
    // NotificationCanceled,  NotificationRefundSucceeded

    // use YooKassa\Model\Notification\NotificationSucceeded;
    // use YooKassa\Model\Notification\NotificationWaitingForCapture;
    // use YooKassa\Model\NotificationEventType;

    try {
      $notification = ($requestBody['event'] === YooKassa\Model\NotificationEventType::PAYMENT_SUCCEEDED)
        ? new YooKassa\Model\Notification\NotificationSucceeded($requestBody)
        : new YooKassa\Model\Notification\NotificationWaitingForCapture($requestBody);
    }
    catch (Exception $e) {
      $this->saveLog($logname['file'], $logname['string']. ': Исключение в строке 795', 'само ниже');
      // $this->saveLog($logname['file'], $logname['string']. ': Исключение в строке 795', $e);
    }

    $payment = $notification->getObject();
    if($payment->getStatus()=='succeeded'){
      $ordPaym=OrdersPaymentsTable::getInstance()->findOneByPaymentId($payment->getId());
      if(!is_object($ordPaym)){
        // $this->saveLog($logname['file'], $logname['string']. ': Заказ по id yokassa не найден. Ищем по номеру заказа ', $payment);
        $orderId=$payment->getDescription();
        $orderId=str_replace('Заказ №'.csSettings::get('order_prefix'), '', $orderId);
        $ordPaym = OrdersPaymentsTable::getInstance()->findOneByOrderId($orderId);
        // $this->saveLog($logname['file'], $logname['string']. ': Найденный id по номеру ', $payment);
      }
      if(is_object($ordPaym)){
        if($ordPaym->getStatus()!='succeeded'){
          $order = OrdersTable::getInstance()->findOneById($ordPaym->getOrderId());
          if(is_object($order)){
            //Возможно надо проверять на форму оплаты заказа
            $customer = sfGuardUserTable::getInstance()->findOneById($order->getCustomerId());
            if(is_object($customer)){//Если нашли пользователя - можно отправить ему письмо

            }
            $amount=$payment->getAmount()->getValue();
            $order->setComments($order->getComments() . " \r\n Оплачено картой " . $amount);
            $order->save();
            $this->addBonusForOrder($order);
            // $logData.="Обновлена информация в заказе\n";
            $paymentdoc=new Paymentdoc();
            $paymentdoc->set('order_id', $order->getId());
            $paymentdoc->set('amount', 100*$amount);
            // $paymentdoc->set('card_no', $_POST['PaddedCardNo']);
            // $paymentdoc->set('fio', $_POST['fio']);
            $paymentName = 'yookassa';
            try {
              if('sbp' == $payment->getPaymentMethod()->getType()) $paymentName = 'sbp';
            } catch (Exception $th) {
              $this->saveLog($logname['file'], $logname['string'] . ': Ошибка получения типа оплаты. строка '.__LINE__, '');
            }
            $paymentdoc->set('payment_type', $paymentName);
            $paymentdoc->set('response_code', $payment->getStatus());
            $paymentdoc->save();
            $ordPaym->setStatus($payment->getStatus());
            $ordPaym->save();
            if(!$isTest) $this->printReceipt($order->getId());
          }
          else{
            $this->saveLog($logname['file'], $logname['string']. ': Оплата уже подтверждена', $ordPaym->getPaymentId());
          }
        }
        else{
          $this->saveLog($logname['file'], $logname['string']. ': Ошибка поиска заказа по платежу', $requestBody);
        }
      }
      else{
        $this->saveLog($logname['file'], $logname['string']. ': Ошибка поиска связки платежа и заказа', $requestBody);
      }
    }
    else
      $this->saveLog($logname['file'], $logname['string']. ': Статус платежа отличается от succeeded', $requestBody);

    die();

  }

  public function executeDirectposturl(sfWebRequest $request) { //Подтверждение оплаты заказа МКБ
    $needLog=true;
    // $needLog=false;
    // $password='1LsLNYeg';//Заменить на актуальный
    // $password='p13evP3B';//боевой Поварёшкин
    $password='k2gZmzVv';//боевой Ип Зеновка

    $logData="\n--------------------------------------------------\n".date('d.m.Y H:i:s')."\n";
    if (isset($_POST['OrderID'])) $logData.="Заказ №".$_POST['OrderID']."\n";
    switch($_POST['ResponseCode']){
      case 1://Успешная оплата. Остальные кода нас особо не интересуют
        if($_POST['SignatureMethod']=='SHA256'){

          $signatureBase=$password.$_POST['MerID'].$_POST['AcqID'].$_POST['OrderID'].$_POST['ResponseCode'].$_POST['ReasonCode'];
          $signature=
            base64_encode( hash('sha256', $signatureBase, true ) );
          if($signature==$_POST['Signature']){
            $logData.="Подпись банка корректна\n";
            $isCorrectSign=true;
          }
          else{
            $logData.="Подпись банка некорректна. Это попытка взлома\n";
            $logData.=print_r($_POST, true);
            //Возможно как-то обрабатывать здесь
            $isCorrectSign=false;
          }
        }
        else {
          $logData.="Подпись банка не проверялась\n";
          $isCorrectSign=true;
        }
        if($isCorrectSign){
          $order = OrdersTable::getInstance()->findOneById($_POST['OrderID']);
          if(is_object($order)){//Если есть заказ
            //Возможно надо проверять на форму оплаты заказа
            $customer = sfGuardUserTable::getInstance()->findOneById($order->getCustomerId());
            if(is_object($customer)){//Если нашли пользователя - можно отправить ему письмо

            }
            $order->setComments($order->getComments() . " \r\n Оплачено картой " . $_POST["PaddedCardNo"]. " \r\n Владелец карты " . $_POST["fio"]);
            $order->save();
            $this->addBonusForOrder($order);
            $logData.="Обновлена информация в заказе\n";
            $paymentdoc=new Paymentdoc();
            $paymentdoc->set('order_id', $_POST['OrderID']);
            $paymentdoc->set('amount', (int)$_POST['amt']);
            $paymentdoc->set('card_no', $_POST['PaddedCardNo']);
            $paymentdoc->set('fio', $_POST['fio']);
            $paymentdoc->set('payment_type', 'mkb');
            $paymentdoc->set('response_code', $_POST['ReasonCode']);
            $paymentdoc->save();
            $this->printReceipt($_POST['OrderID']);
          }

        }

        break;

      case 2://Отказ
        $logData.="Получен отказ при оплате заказа\n";
        $logData.="Код ответа ".$_POST['ResponseCode'];
        $logData.="\nКод причины ответа ".$_POST['ReasonCode'];
        $logData.="\nОписание причины ответа ".$_POST['ReasonCodeDes'];
        $logData.=print_r($_POST, true);

        break;

      case 3://Ошибка
        $logData.="Возникла ошибка при оплате заказа\n";
        $logData.="Код ответа ".$_POST['ResponseCode'];
        $logData.="\nКод причины ответа ".$_POST['ReasonCode'];
        $logData.="\nОписание причины ответа ".$_POST['ReasonCodeDes'];
        $logData.=print_r($_POST, true);
        break;

      default:
        $logData.="Неизвестный код ответа сервера\n_POST=>".print_r($_POST, true);

    }
    if ($needLog) file_put_contents($_SERVER['DOCUMENT_ROOT'].'/mkb_pay_test.log', $logData."\n", FILE_APPEND);
    die(1);

  }

  private function tinkoffPaymentConfirm($order){//Создание оплаты с типом тинькофф. все заказы создаются после подписания кредита
    global $isTest;

    if(is_object($order)){
      $orderData=unserialize($order->getText());
      $amount=0;

      if(is_array($orderData)) foreach ($orderData as $product) {
        $price=$product['price'];
        if(isset($product['price_w_discount']) && $product['price_w_discount'] < $price) $price=$product['price_w_discount'];
        if(isset($product['price_final'])) $price=$product['price_final'];

        $amount+=$price*$product['quantity'];
      }

      $paymentdoc=new Paymentdoc();
      $paymentdoc->set('order_id', $order->getId());
      $paymentdoc->set('amount', 100*$amount);
      $paymentdoc->set('payment_type', 'tinkoff');

      $paymentdoc->save();
      $this->paymentDoc = $paymentdoc;
      // if(!$isTest) $this->printReceipt($order->getId());

      return $this->addBonusForOrder($order);
    }
  }

  public function executeConfirmed(sfWebRequest $request) { //Подтверждение заказа
    global $isTest;
    $this->paymentDoc=false;
    // $this->exchangeRate=csSettings::get('change_rate_ime');

    $ordersPerHour=csSettings::get('orders_per_hour');
    // die('<pre>'.print_r($_POST, true));
    if (!$request->getParameter('checked') == 1) $this->forward404();//Если пришли не из правленой формы - в топку


    $ip = $_SERVER['REMOTE_ADDR'];
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sqlBody="SELECT COUNT(*) as order_count from `orders` WHERE ipuser='$ip' AND created_at >= '".date('Y-m-d H:i', time()-60*60)."'";
    $count= $q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);

    if(!$isTest && $ip!='87.244.36.210'){//Если не тест, проверяем количество заказав в час с ip и блокируем, если слишком много
      if ($ordersPerHour <= $count[0]['order_count'] )
        return $this->redirect('/ip_denied');
    }

    $autorizationUserNow = false;
    if ($this->getUser()->isAuthenticated()) {
      $user = $this->getUser()->getGuardUser();
    }
    else {

      $user = sfGuardUserTable::getInstance()->findOneByEmailAddress(trim($request->getParameter('user_mail')));
      if (!$user) {
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
        $password = rand(100000, 999999);
        $user->set("password", $password);
        $autorizationUserNow = true;
      }
    }

    //Считаем, что теперь у нас есть пользователь

    $user->setFirstName($request->getParameter('first_name'));
    // $user->setLastName($userForm['last_name']);  //В новой форме отсутствует
    $user->setEmailAddress(trim($request->getParameter('user_mail')));
    $user->setPhone(trim($request->getParameter('phone')));
    if($birthdate=trim($request->getParameter('birthday'))){
      $birthdate=implode('-', array_reverse(explode('.',$birthdate)));
      $user->setBirthday($birthdate);
    }
    $user->setRegionFiasId(trim($request->getParameter('region-fias-id')));
    if(trim($request->getParameter('city')) != false) $user->setCity(trim($request->getParameter('city')));
    if(trim($request->getParameter('street')) != false) $user->setStreet(trim($request->getParameter('street')));
    if(trim($request->getParameter('house')) != false) $user->setHouse(trim($request->getParameter('house')));
    if(trim($request->getParameter('sex')) != false) $user->setSex(trim($request->getParameter('sex')));
    // if(trim($request->getParameter('apartament')) != false)  $user->setApartament(trim($request->getParameter('apartament')));  //В новой форме отсутствует

    $user->save();

    //Roistat получение данных клиента

		$roisUser = array(
			'name'           => $user->getFirstName(),
			'email'          => $user->getEmailAddress(),
			'phone'          => $user->getPhone(),
			'city'           => $user->getCity(),
			'street'         => $user->getStreet(),
			'house'          => $user->getHouse(),
			// 'apartament' => $userForm['apartament'], //В данной реализации такого поля нет
			'sex'            => $user->getSex(),
			'birthday'       => $user->getBirthday(),
			'customer_login' => $user->getUsername(),
			'form_name'      => 'Корзина'
		);
    //Roistat END

    $userPhone = $user->getPhone();

    if ($autorizationUserNow)
      $this->getUser()->signin($user);
    $userId = $user->getId();
    $userEmailAddress = $user->getEmailAddress();
    $userSave = $user;
    $user = $this->getUser();

    if ($user->getAttribute('products_to_cart') == "" or count(unserialize($user->getAttribute('products_to_cart'))) == 0 or unserialize($user->getAttribute('products_to_cart')) == "") {
      return $this->redirect('/');//редирект на корзину, если она пустая. смысл неясен)
    }

    if ($_POST['deliveryId'] == "" or $_POST['deliveryId'] == 0) {
      $_POST['deliveryId'] = 12;
    }
    if ($_POST['paymentId'] == "" or $_POST['paymentId'] == 0) {
      $_POST['paymentId'] = 46;
    }

    if ($user->getAttribute('products_to_cart') != "") {//Основная обработка
      if ($this->getUser()->isAuthenticated()) {
        $this->bonus = BonusTable::getInstance()->findBy('user_id', $user->getGuardUser()->getId());
        $this->bonusCount = 0;
        foreach ($this->bonus as $bonus) {
          $this->bonusCount = $this->bonusCount + $bonus->getBonus();
        }
      } else {
        $this->bonusCount = 0;
      }
      //Посчитали сколько у пользователя бонусов по базе

      $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
      $TotalSumm = 0;
      $bonusPay = 0;
      // $bonusPercent = $request->getParameter('bonusPercentForm');
      $isbonuspayper = false;
      $notadddelivery = true;
      $roisUser['products'] = '';

      $bonusToPay=1*$request->getParameter('bonus');
      if($bonusToPay > $this->bonusCount) $bonusToPay=$this->bonusCount;

      $couponText=mb_strtoupper($request->getParameter('coupon', 0), 'UTF-8');

      $arrReturn = $this->getDiscountProductsArray($couponText, false);
      $products_old=$arrReturn['products'];
      $this->getBonusSettings($arrReturn['order_total']);
      if(isset($arrReturn['successCoupon'])) $bonusToPay=0;//Если успешно применен купон - бонусов быть не должно
      $bonusPercent=0;
      if($arrReturn['order_total_wo_discount']) $bonusPercent=$bonusToPay/$arrReturn['order_total_wo_discount']*100;
      if($bonusPercent > $this->percentBase) $bonusPercent = $this->percentBase;

      $pickpointPayment = false;
      $pickpointDiscountValue = 0.02; // Скидка за пикпойнт
      if ($_POST['deliveryId'] == 11) {
        $pickpointPayment = true;
      }

      $pay=PaymentTable::getInstance()->findOneById((int) $_POST['paymentId']);

      if(is_object($pay)) {
        $this->pay=$pay;
        // $onlinePayment=$pay->getIsOnline();
      }
      // $discountNotApply=$this->getDiscountNotApply();//получили список товаров, на которые 7% не распространяется
      $TotalSumm=0;


      foreach ($products_old as $key => $productInfo){//теперь манипуляции с бонусами
        $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
        if(!is_object($product)){//Пропускаем, если добрые люди удалили запись о товаре из БД
          unset($products_old[$key]);
          continue;
        }
        $products[$key]=$product;

        if (!$product->get("is_notadddelivery")) {
            $notadddelivery = false;
        }
        $product->setCount($product->getCount() - $productInfo['quantity']);
        $product->save();
        /*
        //Манипуляции с кэшем, возможно позже исправить ключи кэша
        $cacheManager = sfContext::getInstance()->getViewCacheManager();
        if ($cacheManager) {
            $cacheManager->remove('@sf_cache_partial?module=category&action=_products&sf_cache_key=' . ($product->getId()) . '*');
            $cacheManager->remove('@sf_cache_partial?module=category&action=_changechildren&sf_cache_key=' . ($product->getId()) . '*');
            $cacheManager->remove('@sf_cache_partial?module=product&action=_params&sf_cache_key=' . ($product->getId()) . '*');
            $cacheManager->remove('@sf_cache_partial?module=product&action=_stock&sf_cache_key=' . ($product->getId()) . '*');
        }*/

        $roisUser['products'] .= "{$product->get('name')} кол-во: {$productInfo['quantity']} Цена: {$productInfo['price']}\n";

        $products_old[$key]['price_w_bonuspay'] = $productInfo['price_w_discount'] - ($productInfo['price']==$productInfo['price_w_discount'] ? round(($bonusPercent / 100) * $productInfo['price_w_discount']) : 0);
        $products_old[$key]['weight'] = $product->get('weight');

        if ($bonusPercent > 0 && $productInfo['price']==$productInfo['price_w_discount']) {
            $bonusPay = $bonusPay + round(($bonusPercent / 100) * $productInfo['price_w_discount'] * $productInfo['quantity']);

            // $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price_w_discount'] ) - round(($bonusPercent / 100) * $productInfo['price'] * $productInfo['quantity']);

            $products_old[$key]['bonus_percent'] = $bonusPercent;
        } else {
            $products_old[$key]['bonus_percent'] = 0;
            // $TotalSumm = $TotalSumm + ($productInfo['quantity'] * (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0 )? $productInfo['price_w_discount'] : $productInfo['price']) );
        }
        if ($products_old[$key]['bonus_percent'] > 49)
            $isbonuspayper = true;

        $itempickpointMultyplier = 1 - $pickpointDiscountValue * $pickpointPayment;// * ($onlinePayment * !in_array($products_old[$key]['productId'], $discountNotApply));
        //если не выстален признак оплаты онлайн или товар входит в список запрета умножится на 1. Или на 0,98
        $products_old[$key]['price_final']=round($itempickpointMultyplier*$products_old[$key]['price_w_bonuspay']);

        $TotalSumm+=$products_old[$key]['quantity'] * $products_old[$key]['price_final'];


      };//добавили в роистат, уменьшили остаток и посчитали сумму

      $order = new Orders();
      $order->setComments($_POST['comments']);
      $order->setCustombonusper($isbonuspayper);
      if($bonusPay){
        $commentBonus = " \r\n\r\n Пользователь оплатил " . $bonusPay . " рублей бонусами со своего счёта";
        $order->setComments($order->getComments() . $commentBonus);
      }
      else{
        $commentBonus = " \r\n\r\n Пользователь не использовал бонусы со своего счёта";
        $order->setComments($order->getComments() . $commentBonus);
      }
      if($pickpointPayment) $order->setComments($order->getComments() . " \r\n\r\n Пользователь получил скидку ".round(100 * $pickpointDiscountValue)."%за выбор Pickpoint");
      $order->setBonuspay($bonusPay);
      $this->bonusDropCost=$bonusDropCost=$bonusPay;

      $this->bonusDropCost = $bonusDropCost;


      if (!empty($_POST["timeCall"])) {//в данной реализации нет
        $order->setComments($order->getComments() . " \r\n Удобное время звонка: " . $_POST["timeCall"]);
      }
      if($_POST['deliveryId']==10){//Самовывоз
        $shop_id = trim($_POST["shop_id"]);
        if (!empty($shop_id)) {
          $order->setComments($order->getComments() . " \r\n ID магазина: " . $shop_id);
        }
        $shop_address = trim($_POST["shop_address_line"]);
        if (!empty($shop_address)) {
          $order->setComments($order->getComments() . " \r\n Адрес магазина: " . $shop_address);
        }
      }
      if($_POST['deliveryId']==11){//PickPoint
        $pickpoint_id = trim($_POST["pickpoint_id"]);
        if (!empty($pickpoint_id)) {
          $order->setComments($order->getComments() . " \r\n ID постамата: " . $pickpoint_id);
        }
        $pickpoint_address = trim($_POST["pickpoint_address"]);
        if (!empty($pickpoint_address)) {
          $order->setComments($order->getComments() . " \r\n Адрес постамата: " . $pickpoint_address);
        }
      }
      if($_POST['deliveryId']==14){//Евросеть
        $dlh_shop_id = trim($_POST["dlh_shop_id"]);
        if (!empty($dlh_shop_id)) {
          $order->setComments($order->getComments() . " \r\n ID точки доставки DLH: " . $dlh_shop_id);
        }
        $dlh_address_line = trim($_POST["dlh_address_line"]);
        if (!empty($dlh_address_line)) {
          $order->setComments($order->getComments() . " \r\n Адрес точки DLH: " . $dlh_address_line);
        }
      }
      if($_POST['deliveryId']==17){//Ozon
        $ozon_id = trim($_POST["ozon_id"]);
        if (!empty($ozon_id)) {
          $order->setComments($order->getComments() . " \r\n ID пункта выдачи: " . $ozon_id);
        }
        $ozon_address = trim($_POST["ozon_address"]);
        if (!empty($ozon_address)) {
          $order->setComments($order->getComments() . " \r\n Адрес пункта выдачи: " . $ozon_address);
        }
      }
      if($_POST['deliveryId']==21){//VIA delivery
        $via_id = trim($_POST["via_id"]);
        if (!empty($via_id)) {
          $order->setComments($order->getComments() . " \r\n ID пункта выдачи: " . $via_id);
        }
        $via_address = trim($_POST["via_address"]);
        if (!empty($via_address)) {
          $order->setComments($order->getComments() . " \r\n Адрес пункта выдачи: " . $via_address);
        }
      }
      if($_POST['deliveryId']==18){//Почта России постаматы
        $post_id = trim($_POST["post_id"]);
        if (!empty($post_id)) {
          $order->setComments($order->getComments() . " \r\n ID пункта выдачи: " . $post_id);
        }
        $post_address = trim($_POST["post_address"]);
        if (!empty($post_address)) {
          $order->setComments($order->getComments() . " \r\n Адрес пункта выдачи: " . $post_address);
        }
      }
      if($_POST['deliveryId']==19){ //СДЭК постаматы
        $sdek_id = trim($_POST["sdek_id"]);
        if (!empty($sdek_id)) {
          $order->setComments($order->getComments() . " \r\n ID пункта выдачи: " . $sdek_id);
        }
        $sdek_city_id = trim($_POST["sdek_city_id"]);
        if (!empty($sdek_city_id)) {
          $order->setComments($order->getComments() . " \r\n ID города в системе СДЭК: " . $sdek_city_id);
        }
        $sdek_tariff = trim($_POST["sdek_tariff"]);
        if (!empty($sdek_tariff)) {
          $order->setComments($order->getComments() . " \r\n ID тарифа в системе СДЭК: " . $sdek_tariff);
        }
        $sdek_address = trim($_POST["sdek_address"]);
        if (!empty($sdek_address)) {
          $order->setComments($order->getComments() . " \r\n Адрес пункта выдачи: " . $sdek_address);
        }
      }
      if($_POST['deliveryId']==16){//ЯндексТакси

        if($_POST['lon']!=$_POST['lat']){//Координаты обычно разные, если они не 0 :)
          $point=[
            'lon' => $_POST['lon'],
            'lat' => $_POST['lat'],
            'full' => $userSave->getCity().', '.$userSave->getStreet().', '.$userSave->getHouse()
          ];

          $userData = [
            'email' => $userSave->getEmailAddress(),
            'phone' => $userSave->getPhone(),
            'name'  => $userSave->getFirstName(),
          ];
          $yandexDelivery = ILTools::getYandexTaxiObject();
          $res=$yandexDelivery->createClaim( $point, $products_old, $userData );
          ILTools::logToFile(['data'=> [$point, $products_old, $userData], 'res' => $res], 'yataxi_log.log');
          if(!empty($res->id)) {
            $order->setComments($order->getComments() . " \r\n ID заказа ЯндексТакси: " . $res->id);
            $order->setYataxiId($res->id);
            $order->setYataxiStatus($res->status);
          }
        }

      }
      $deliveryPriceSend=str_replace(' ', '', $_POST['deliveryPriceSend']);

      if (($deliveryPriceSend > 0) && !$notadddelivery) {//Если доставка платная и нет товара с бесплатной доставкой добавляем доставку в корзину
        $row = array();
        $row["productId"] = 14613;
        $row["quantity"] = 1;

        $row["price"] = $row["price_w_discount"] = $row["price_final"] = $row["price_w_bonuspay"] = $deliveryPriceSend;

        $products_old[] = $row;
        $product = ProductTable::getInstance()->findOneById(14613);
        $products[]=$product;
      }

      $this->products=$products;
      $serProucts=serialize($products_old);

      // $user->setAttribute('products_to_cart',$serProucts );
      $order->setText($serProucts);
      $order->setFirsttext($serProucts);
      $order->setDeliveryId((int) $_POST['deliveryId']);
      $order->setPaymentId((int) $_POST['paymentId']);
      $order->setCustomerId($userId);
      $order->setDeliveryPrice($deliveryPriceSend);

      $order->setCoupon($_POST['coupon']);
      $order->setStatus('Новый');
      $order->setFirsttotalcost($TotalSumm);
      $order->set('sync_status', 'new');

      $ref = 'NULL';

      if (defined('REFERALID')) {
          $ref = REFERALID;
      }
      if($_COOKIE['utm_source']=='advcake'){//Если заказ пришел от advcake - заполняем их служебные поля
        $order->setAdvcakeUrl($_COOKIE['advcake_url']);
        $order->setAdvcakeTrackid($_COOKIE['advcake_trackid']);
      }

      $order->set('referurl', $_COOKIE['referalurl']);

      $order->set('referal', $ref);

      $prxCityads = 'NULL';
      if (defined('PRXCITYADS')) {
        $prxCityads = PRXCITYADS;
      }
      $order->set('prxcityads', $prxCityads);
      $order->set('ipUser', $_SERVER['REMOTE_ADDR']);
      $order->set('prefix', csSettings::get('order_prefix'));

      $order->save();


      //Roistat Отправка данных в проксилид
      if(file_exists($_SERVER["DOCUMENT_ROOT"].'/roistat/proxilid.php')){

        $roisUser['diliveryPrice'] = $deliveryPriceSend;
        $deliver = DeliveryTable::getInstance()->findOneById($_POST['deliveryId']);
        // $pay = PaymentTable::getInstance()->findOneById($_POST['paymentId']);
        $roisUser['delivery'] = is_object($deliver) ? $deliver->get('name') : $_POST['deliveryId'];
        $roisUser['payment'] = is_object($pay) ? $pay->get('name') : $_POST['paymentId'];
        $roisUser['order_id'] = $order->getPrefix() . $order->getId();
        include $_SERVER["DOCUMENT_ROOT"].'/roistat/proxilid.php';
        proxilid($roisUser);
      }
      //Roistat END

      $this->getUser()->setAttribute('RegisterSuccessRedirect', false);

      if ($bonusDropCost > 0) {
        $bonusLog = new Bonus();
        $bonusLog->setUserId($userId);
        $bonusLog->setBonus("-" . $bonusDropCost);
        $bonusLog->setComment("Снятие бонусов в счет оплаты заказа #" . csSettings::get('order_prefix') . $order->getId());
        $bonusLog->save();
      }
      elseif ($this->getUser()->isAuthenticated()) {
          $newBonusActive = new Bonus();
          $newBonusActive->setUserId($userId);
          $newBonusActive->setBonus(0);
          $newBonusActive->setComment('Продление жизни бонусов за заказ');
          $newBonusActive->save();
      }

      $this->order = $order;

      // $TotalSumm = 0;
      $yaParams = '';
      $googleParams = "";
      $bonusAddUser = 0;

      $tableOrderTaxi =
        '<h3>Состав заказа</h3>'.
        '<table  cellpadding="0" cellspacing="0" border="0" style="width: 100%;border-collapse: collapse;background: white;">'.
          '<tr>'.
            '<td style="width: 55px;"></td>'.
            '<td>'.
              '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 170px; border-collapse: collapse;background: white;">'.
                '<tr>'.
                  '<th colspan="2" style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;">Наименование</th>'.
                  '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;width:140px;">Количество</th>'.
                  '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;">Цена, руб</th>'.
                  '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;">Артикул</th>'.
                  '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;width: 150px;">Сумма, руб</th>'.
                '</tr>';
      $tableOrder =
        '<table  cellpadding="0" cellspacing="0" border="0" style="width: 100%;border-collapse: collapse;background: white;">'.
          '<tr>'.
            '<td style="width: 55px;"></td>'.
            '<td>'.
              '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 170px; border-collapse: collapse;background: white;">'.
                '<tr>'.
                  '<th colspan="2" style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;">Наименование</th>'.
                  '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;">Цена, руб</th>'.
                  // '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;">Скидка, %</th>'.
                  '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;width:140px;">Цена со скидкой и бонусами</th>'.
                  '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;width: 150px;">Сумма, руб</th>'.
                '</tr>';
      foreach ($products_old as $key => $productInfo)://Считаем для писем о заказе
        if ($productInfo['productId'] > 0){
            $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
            $photoalbum = $product->getPhotoalbums();
            $photos = $photoalbum[0]->getPhotos();
            // $TotalSumm = $TotalSumm + ($productInfo['quantity'] * (($productInfo['price_w_discount'] > 0 || $productInfo['discount']==100) ? $productInfo['price_w_discount'] : $productInfo['price']) );
            $categoryProd = $product->getGeneralCategory();
            if ($product->getId() != 14613)//на доставку бонусов не будет
              if ($product->getBonus() != 0) {
                $bonusAddUser += round( $productInfo['price_final'] * $productInfo['quantity'] * $product->getBonus() / 100);
              } else {
                $bonusAddUser += round($productInfo['price_final'] * $productInfo['quantity'] * csSettings::get("persent_bonus_add") / 100);
              }

            $this->googleParams.="_gaq.push(['_addItem',
              '" . $order->getId() . "',
              '" . $product->getCode() . "',
              '" . $product->getName() . "',
              '" . $categoryProd->getName() . "',
              '" . $productInfo['price_final'] . "',
              '" . $productInfo['quantity'] . "'
              ]);";
            $this->yaParams.="{
              id: \"" . $product->getId() . "\",
              name: '" . addslashes($product->getName()) . "',
              price: " . $productInfo['price_final'] . ",
              quantity: " . $productInfo['quantity'] . "
            }, ";


            if ($product->getId() != 14613) {

                // $priceOneProd = min($productInfo['price_w_discount'], $productInfo['price_w_bonuspay']);

                $priceOneProd = $productInfo['price_final'];

              $priceAllProd = $priceOneProd * $productInfo['quantity'];
              $tableOrderTaxi .=
                '<tr>'.
                  '<td style="width: 161px; height: 123px;border:5px solid #dfdbdc;border-right: 0;border-top: 5px solid #f2cfb9;">'.
                    '<img style="display: block; height:123px; margin: auto;" src="https://onona.ru/uploads/photo/thumbnails_250x250/'. $photos[0]->getFilename().'">'.
                  '</td>'.
                  '<td style="width: 280px; border:5px solid #dfdbdc;border-left: 0;border-top: 5px solid #f2cfb9;">'.
                    '<a href="https://onona.ru/product/'.$product->getSlug().'" style="display: inline-block;padding: 30px;color:black;">'.
                      $product->getName().
                    '</a>'.
                  '</td>'.
                  '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;">'.$productInfo['quantity'].'</td>'.
                  '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;">'.$productInfo['price'].'</td>'.
                  '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;">'.$product->getCode().'</td>'.
                  '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;"><b>'.$priceAllProd.'</b></td>'.
                '</tr>';
              $tableOrder .=
                '<tr>'.
                  '<td style="width: 161px; height: 123px;border:5px solid #dfdbdc;border-right: 0;border-top: 5px solid #f2cfb9;">'.
                    '<img style="display: block; height:123px; margin: auto;" src="https://onona.ru/uploads/photo/thumbnails_250x250/'. $photos[0]->getFilename().'">'.
                  '</td>'.
                  '<td style="width: 280px; border:5px solid #dfdbdc;border-left: 0;border-top: 5px solid #f2cfb9;">'.
                    '<a href="https://onona.ru/product/'.$product->getSlug().'" style="display: inline-block;padding: 30px;color:black;">'.
                      $product->getName().
                    '</a>'.
                  '</td>'.
                  '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;">'.$productInfo['price'].'</td>'.
                  // '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;">'.(( ($productInfo['discount']==100 || $productInfo['price_w_discount']) > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0').'</td>'.
                  '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;">'.$priceOneProd.'</td>'.
                  '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;"><b>'.$priceAllProd.'</b></td>'.
                '</tr>';
              }
          }
      endforeach;
      $tableOrderTaxi .=
              '</table>'.
            '</td>'.
            '<td style="width: 55px;"></td>'.
          '</tr>'.
        '</table>'.
        '';
      $tableOrder .=
              '</table>'.
            '</td>'.
            '<td style="width: 55px;"></td>'.
          '</tr>'.
        '</table>'.
        '';
      $this->TotalSumm = $TotalSumm;

      $sPhone = ILTools::cleanPhone($userPhone);

      if ($sPhone[0] == 7 && !$isTest && $sPhone != "77777777777" ) {//Отправляем SMS
          $sms_text = "Вы сделали заказ " . $order->getPrefix() . $order->getId() . " на сумму " . (($TotalSumm) - $bonusDropCost) . "р. Ожидайте звонок для уточнения способа и адреса доставки. Благодарим за покупку onona.ru";

          // Параметры сообщения
          // Если скрипт в кодировке UTF-8, не используйте iconv
          $sms_from = "OnOna";
          $sms_to = $sPhone;
          $u = 'http://www.websms.ru/http_in6.asp';
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_TIMEOUT, 10);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, 'Http_username=' . urlencode("onona") . '&Http_password=' . urlencode("77onona") . '&Phone_list=' . $sms_to . '&Message=' . urlencode($sms_text) . '&fromPhone=' . urlencode("OnOna"));
          curl_setopt($ch, CURLOPT_URL, $u);
          $u = trim(curl_exec($ch));
          curl_close($ch);
          preg_match("/message_id\s*=\s*[0-9]+/i", $u, $arr_id);
          $id = preg_replace("/message_id\s*=\s*/i", "", @strval($arr_id[0]));
          preg_match("/message_cost\s*=\s*[0-9,]+/i", $u, $arr_cost);
          $message_cost = preg_replace("/message_cost\s*=\s*/i", "", @strval($arr_cost[0]));
          $order->setSmsId($id);
          $order->setSmsPrice($message_cost);
          $order->save();
        }

      if (ILTools::is_email($userEmailAddress)) {//Отправляем письмо пользователю

        $this->bonus = BonusTable::getInstance()->findBy('user_id', $userId);
        $this->bonusCount = 0;
        foreach ($this->bonus as $bonus) {
            $this->bonusCount = $this->bonusCount + $bonus->getBonus();
        }

        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('order_create');
        $mailTemplate->setText(str_replace('{dateOrder}', date('d.m.Y'), $mailTemplate->getText()));
        $mailTemplate->setText(str_replace('{idOrder}', $order->getPrefix() . $order->getId(), $mailTemplate->getText()));
        $mailTemplate->setText(str_replace('{nameCustomer}', $userSave->getFirstName(), $mailTemplate->getText()));
        $mailTemplate->setText(str_replace('{bonysCustomer}', $this->bonusCount, $mailTemplate->getText()));
        $mailTemplate->setText(str_replace('{summOrder}', $TotalSumm, $mailTemplate->getText()));
        $mailTemplate->setText(str_replace('{deliveryPriceOrder}', $deliveryPriceSend, $mailTemplate->getText()));
        $mailTemplate->setText(str_replace('{bonusPayOrder}', ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
        $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm+$deliveryPriceSend), $mailTemplate->getText()));
        $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));

        $tableOrderHeader=$tableOrderFooter='';
        $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText()));

        $message = Swift_Message::newInstance()
                ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                ->setTo($userEmailAddress)
                ->setSubject($mailTemplate->getSubject())
                ->setBody($mailTemplate->getText())
                ->setContentType('text/html')
        ;
        try{
          $this->getMailer()->send($message); //exit;
        }
        catch(Exception $e){
          //Как обрабатывать - ХЗ
        }
        if(16==$_POST['deliveryId']){//Яндекс-доставка
          if(!$isTest) $emailMag=[
            'shurova.d@onona.ru' => '',
            'a.izmaylova@onona.ru' => '',
            'i.nikoladze@onona.ru' => '',
            'bahteeva@onona.ru' => '',
            'khristova@onona.ru' => '',
          ];
          else $emailMag=[
            "aushakov@interlabs.ru" => '',
            "shurova.d@onona.ru" => '',
          ];
          $tableOrderTaxiHead=
            '<p>Новый заказ на Доставку Яндекс</p>'.
            '<table>'.
              '<tr>'.
                '<td>Имя Фамилия</td>'.
                '<td>'.$userSave->getFirstName().'</td>'.
              '</tr>'.
              '<tr>'.
                '<td>Сумма заказа</td>'.
                '<td>'.($TotalSumm /*- $bonusDropCost*/).'</td>'.
              '</tr>'.
              '<tr>'.
                '<td>Адрес доставки</td>'.
                '<td>'.($userSave->getCity().', '. $userSave->getStreet().', '.$userSave->getHouse()).'</td>'.
              '</tr>'.
              '<tr>'.
                '<td>Телефон</td>'.
                '<td>'.$sPhone.'</td>'.
              '</tr>'.
              '<tr>'.
                '<td>ID Заказа в интерфейсе Яндекса</td>'.
                '<td>'.$order->getYataxiId().'</td>'.
              '</tr>'.
              '<tr>'.
                '<td>Стоимость доставки для клиента</td>'.
                '<td>'.$deliveryPriceSend.'</td>'.
              '</tr>'.
            '</table>';
          $message = Swift_Message::newInstance()
                  ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                  ->setTo($emailMag)
                  ->setSubject("Заказ на Яндекс-доставку")
                  ->setBody($tableOrderTaxiHead.$tableOrderTaxi)
                  ->setContentType('text/html')
          ;
          try{
            $this->getMailer()->send($message); //exit;
          }
          catch(Exception $e){
            //Как обрабатывать - ХЗ
          }
        }
        if(15==$_POST['deliveryId']){//Экспресс доставка в Марьино
          $tableOrderTaxiHead=
            '<p>Новый заказ на Доставку Яндекс</p>'.
            '<table>'.
              '<tr>'.
                '<td>Имя Фамилия</td>'.
                '<td>'.$userSave->getFirstName().'</td>'.
              '</tr>'.
              '<tr>'.
                '<td>Сумма заказа</td>'.
                '<td>'.($TotalSumm /*- $bonusDropCost*/).'</td>'.
              '</tr>'.
              '<tr>'.
                '<td>Адрес доставки</td>'.
                '<td>'.($userSave->getCity().', '. $userSave->getStreet().', '.$userSave->getHouse()).'</td>'.
              '</tr>'.
              '<tr>'.
                '<td>Телефон</td>'.
                '<td>'.$sPhone.'</td>'.
              '</tr>'.
            '</table>';
          if(!$isTest) $emailMag=[
            'm.roscha@mag.onona.ru' => '',
            'povar@onona.ru' => '',
          ];
          else $emailMag=[
            "aushakov@interlabs.ru" => '',
            "ctapocta13@gmail.com" => '',
          ];
          $message = Swift_Message::newInstance()
                  ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                  ->setTo($emailMag)
                  ->setSubject("Заказ на Яндекс-доставку")
                  ->setBody($tableOrderTaxiHead.$tableOrderTaxi)
                  ->setContentType('text/html')
          ;
          try{
            $this->getMailer()->send($message); //exit;
          }
          catch(Exception $e){
            //Как обрабатывать - ХЗ
          }
        }

      }

      $user->setAttribute('deliveryId', "");

      $this->getUser()->setAttribute('products_to_cart', '');

      if ($this->getUser()->isAuthenticated()) {
        $GuardUser = $this->getUser()->getGuardUser();
        $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
        $GuardUser->save();
      }
      if(isset($GuardUser))
        $this->customer=$GuardUser;
      else
        $this->customer=$userSave;
      // $this->page = PageTable::getInstance()->findOneById(91);
      // if($_POST['paymentId']==59) $this->redirectToYookassa($order);
      if($request->getParameter('tinkoff-payment', false)){
        $order->setComments($order->getComments() . "\n\n Заказ оформлен в кредит через Тинькоф банк\n");
        $order->save();
        $this->tinkoffPaymentConfirm($order);
      }
    }
    $this->bonusAddUser=$bonusAddUser;
  }

  public function executePayments(sfWebRequest $request) {
    $delivery = DeliveryTable::getInstance()->FindOneById($request->getParameter('id'));
    $this->payments = $delivery->getDeliveryPayments();

    $products_old = $this->getUser()->getAttribute('products_to_cart');
    $products_old = $products_old != '' ? unserialize($products_old) : '';
    // die('<pre>'.print_r($products_old, true));
    $this->hideOnline=false;
    foreach ($products_old as $product) {
      $productBase = ProductTable::getInstance()->findOneById($product["productId"]);
      if(!is_object($productBase)){//Если товара в базе нет - в топку его из корзины
        continue;
      }
      if(!$productBase->getCount()) $this->hideOnline=true;
    }
    return $this->renderPartial('cart_new/payments');
  }

  public function is_email($email) {
    //return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    } else {
      return false;
    }
  }

  public function executeCompare(sfWebRequest $request) {//Сравнение
    $productId = (int) $request->getParameter('id');

    $this->products_compare = $this->getUser()->getAttribute('products_to_compare');
    $this->products_compare = $this->products_compare != '' ? unserialize($this->products_compare) : '';
    if ($productId > 0 and is_array($this->products_compare)) {
      $d = array_search($productId, $this->products_compare);
      unset($this->products_compare[$d]);
    }

    $this->getUser()->setAttribute('products_to_compare', serialize($this->products_compare));
  }

  public function executeDelalltocompare(sfWebRequest $request) {//Очистить список сравнения
      $this->getUser()->setAttribute('products_to_compare', "");

      return $this->renderText("Список сравнения очищен.");
  }

  public function executeAddtocompare(sfWebRequest $request) {//Добавить в сравнение
      $products_compare = unserialize($this->getUser()->getAttribute('products_to_compare'));

      $row = array();
      $row["productId"] = (int) $request->getParameter('id');

      foreach ($products_compare as $key => $product) {
          if (@$product["productId"] == @$row["productId"]) {
              $row = array();
          }
      }

      if (isset($row["productId"]) && $row["productId"] > 0) {
          $products_compare[] = $row["productId"];
      }

      $this->getUser()->setAttribute('products_to_compare', serialize($products_compare));

      return $this->renderText(count($products_compare));
  }

  public function executeAddtodesire(sfWebRequest $request) { //Добавить в желаемое
    if ($this->getUser()->isAuthenticated()) {
        $desirePrivate = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("rule='Личный'")->fetchOne();

        if (!$desirePrivate) {
            $desirePrivate = new Compare();
            $desirePrivate->setUserId($this->getUser()->getGuardUser());
            $desirePrivate->setRule("Личный");
        }

        $desirePrivateArray = unserialize($desirePrivate->getProducts());
        $desirePrivateArrayInfo = unserialize($desirePrivate->getProductsinfo());

        $row = array();
        $row["productId"] = (int) $request->getParameter('id');
        $row["time"] = (int) time();

        if (in_array($row["productId"], $desirePrivateArray) === true)
            $row = array();

        if (isset($row["productId"]) && $row["productId"] > 0) {
            $desirePrivateArray[] = $row["productId"];
            $desirePrivateArrayInfo[] = $row;
        }

        $desirePrivate->setProducts(serialize($desirePrivateArray));
        $desirePrivate->setProductsinfo(serialize($desirePrivateArrayInfo));
        $desirePrivate->save();

        $this->getUser()->setAttribute('products_to_desire', serialize($desirePrivateArray));
    } else {
        $desirePrivateArray = unserialize($this->getUser()->getAttribute('products_to_desire'));

        $row = array();
        $row["productId"] = (int) $request->getParameter('id');

        if (in_array($row["productId"], $desirePrivateArray) === true)
            $row = array();

        if (isset($row["productId"]) && $row["productId"] > 0) {
            $desirePrivateArray[] = $row["productId"];
        }
        $this->getUser()->setAttribute('products_to_desire', serialize($desirePrivateArray));
    }



    $productDB = ProductTable::getInstance()->findOneById((int) $request->getParameter('id'));
    $generalCategory = $productDB->getGeneralCategory();
    $personalRecomendation = unserialize(base64_decode(sfContext::getInstance()->getRequest()->getCookie('personalRecomendationCategory')));
    $personalRecomendation['category'][$generalCategory->getId()] = $personalRecomendation['category'][$generalCategory->getId()] + 2;
    $personalRecomendation['products'][$row["productId"]] = $personalRecomendation['products'][$row["productId"]] + 2;
    sfContext::getInstance()->getResponse()->setCookie('personalRecomendationCategory', base64_encode(serialize($personalRecomendation)), time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
    if ($this->getUser()->isAuthenticated()) {
        $GuardUser = $this->getUser()->getGuardUser();
        $GuardUser->set("personal_recomendation", serialize($personalRecomendation));
        $GuardUser->save();
    }

    return $this->renderText(count($desirePrivateArray));
  }


  public function executeDesire(sfWebRequest $request) {//Список желаемого
      $productId = (int) $request->getParameter('id');

      if ($this->getUser()->isAuthenticated()) {
          //$desire = CompareTable::getInstance()->findOneByUserId($this->getUser()->getGuardUser()->getId());
          $this->products_desire_private = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("rule='Личный'")->fetchOne();

          if ($this->products_desire_private)
              $this->products_jel = $this->products_desire_private->getProducts() != '' ? unserialize($this->products_desire_private->getProducts()) : '';
          else {
              $this->products_jel = $this->getUser()->getAttribute('products_to_desire');
              //var_dump($this->products_jel);
              $this->products_jel = $this->products_jel != '' ? unserialize($this->products_jel) : array();

              //var_dump($this->products_jel);
          }
          $this->products_desire_public = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("rule='Публичный'")->fetchOne();
          $this->products_desire_completed = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("rule='Исполненый'")->fetchOne();
      } else {
          $this->products_jel = $this->getUser()->getAttribute('products_to_desire');
          $this->products_jel = $this->products_jel != '' ? unserialize($this->products_jel) : array();
      }
      if ($productId > 0 and is_array($this->products_jel)) {
          $d = array_search($productId, $this->products_jel);
          if ($d !== false) {
              unset($this->products_jel[$d]);
              if ($this->products_desire_private) {

                  $this->products_desire_private->setProducts(serialize($this->products_jel));
                  $this->products_desire_private->save();
              }
          }
      }
      if ($this->products_desire_public) {
          if ($productId > 0 and is_array($this->products_jel_public = unserialize($this->products_desire_public->getProducts()))) {
              $d = array_search($productId, $this->products_jel_public);
              if ($d !== false) {
                  unset($this->products_jel_public[$d]);
                  if ($this->products_desire_public) {

                      $this->products_desire_public->setProducts(serialize($this->products_jel_public));
                      $this->products_desire_public->save();
                  }
              }
          }
      }
      if ($this->products_desire_completed) {
          if ($productId > 0 and is_array($this->products_jel_completed = unserialize($this->products_desire_completed->getProducts()))) {
              $d = array_search($productId, $this->products_jel_completed);
              if ($d !== false) {
                  unset($this->products_jel_completed[$d]);
                  if ($this->products_desire_completed) {

                      $this->products_desire_completed->setProducts(serialize($this->products_jel_completed));
                      $this->products_desire_completed->save();
                  }
              }
          }
      }
      $this->getUser()->setAttribute('products_to_desire', serialize($this->products_jel));
  }

  public function executeCheckatoldoc(sfWebRequest $request) {//callback при печати чека atol_login
    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);
    if($data['status']=='done'){
      $orderId=str_replace(csSettings::get('order_prefix'), '', $data['external_id']);
      $paymentdoc = PaymentdocTable::getInstance()->FindOneByOrderId($orderId);
      if(is_object($paymentdoc)){
        /*file_put_contents(
          $_SERVER['DOCUMENT_ROOT'].'/../atol.log',
          "\n\n----executeCheckatoldoc проверка данных----------- ".date('d.m.Y H:i:s')." ---------------\n".
          print_r(['data'=> $data], true),
          FILE_APPEND
        );*/
        $paymentdoc->setReceiptDate($data['payload']['receipt_datetime']);
        $paymentdoc->setReceiptUrl($data['payload']['ofd_receipt_url']);
        $paymentdoc->save();
      }
      else{
        file_put_contents(
          $_SERVER['DOCUMENT_ROOT'].'/../atol.log',
          "\n\n----executeCheckatoldoc----------- ".date('d.m.Y H:i:s')." ---------------\n".
          print_r(['data'=> $data], true),
          FILE_APPEND
        );
      }
    }
    else{
      file_put_contents(
        $_SERVER['DOCUMENT_ROOT'].'/../atol.log',
        "\n\n----executeCheckatoldoc----------- ".date('d.m.Y H:i:s')." ---------------\n".
        print_r(['data'=> $data], true),
        FILE_APPEND
      );
    }
    die();
  }
  /*************************************** old **************************************************/



    public function executeActioninfo(sfWebRequest $request) {
      if ($_POST['text'] == "" and $this->getUser()->getAttribute('actionCartCode') != "") {
          $_POST['text'] = $this->getUser()->getAttribute('actionCartCode');
      }
      $this->cartFirstPage = 1;
      $this->getUser()->setAttribute('actionCartCode', $_POST['text']);
      $this->products_old = $this->getUser()->getAttribute('products_to_cart');
      $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';
      $discountAction = ActionsDiscountTable::getInstance()->createQuery()->where("text=?", $_POST['text'])->addWhere("startaction <= ?", date("Y-m-d H-i-s"))->addWhere("endaction >= ?", date("Y-m-d H-i-s"))->fetchOne();

      if ($discountAction) {
          if ($this->getUser()->isAuthenticated()) {
              $this->bonus = BonusTable::getInstance()->findBy('user_id', $this->getUser()->getGuardUser()->getId());
              $this->bonusCount = 0;
              foreach ($this->bonus as $bonus) {
                  $this->bonusCount = $this->bonusCount + $bonus->getBonus();
              }
          } else {
              $this->bonusCount = 0;
          }
          $bonusCountTemp = $this->bonusCount;
          $this->totalCost = 0;
          foreach ($this->products_old as $product) {
              $this->totalCost += ($product["quantity"] * $product["price"]);
          }
          $discountActionInterval = ActionsDiscountIntervalTable::getInstance()->createQuery()->where("actionsdiscount_id=?", $discountAction->getId())->addWhere("start <= ?", $this->totalCost)->addWhere("end >= ?", $this->totalCost)->fetchOne();
          if ($discountActionInterval) {
              $discountValue = $discountActionInterval->getDiscount();
          } else {
              $discountValue = $discountAction->getDiscount();
          }
          $products_old = $this->products_old;
          foreach ($products_old as $key => $product) {

              $productDB = ProductTable::getInstance()->findOneById($product["productId"]);
              if (@$product['discount'] < $discountValue) {
                  $products_old[$key]['discount'] = $discountValue;

                  $products_old[$key]["price_w_discount"] = $product["price"] * (1 - ($products_old[$key]['discount'] / 100));
              } elseif (@$product['discount'] > $productDB->getDiscount()) {
                  if (@$productDB->getDiscount() < $discountValue) {

                      $products_old[$key]['discount'] = $discountValue;

                      $products_old[$key]["price_w_discount"] = $product["price"] * (1 - ($products_old[$key]['discount'] / 100));
                  } else {

                      $products_old[$key]['discount'] = $productDB->getDiscount();

                      $products_old[$key]["price_w_discount"] = $product["price"] * (1 - ($products_old[$key]['discount'] / 100));
                  }
              }
          }

          $this->products_old = $products_old;
          foreach ($this->products_old as $keyProdCart => $prod) {
              if ($prod['price'] == $prod['price_w_discount'] and $bonusCountTemp > 0) {
                  $product = ProductTable::getInstance()->findOneById($prod["productId"]);
                  if ($product->getBonuspay() != '') {
                      $percentBonuspay = $product->getBonuspay();
                  } else {
                      $percentBonuspay = csSettings::get('PERSENT_BONUS_PAY');
                  }
                  $procBonus = $bonusCountTemp / ($prod['quantity'] * $prod['price']) * 100;
                  $percentBonusPayForCount = floor($procBonus / 5) * 5;
                  if ($percentBonusPayForCount < $percentBonuspay) {
                      $percentBonuspay = $percentBonusPayForCount;
                  }
                  $bonusCountTemp = $bonusCountTemp - round($prod['price'] * ($percentBonuspay / 100));
                  $this->products_old[$keyProdCart]['bonuspay'] = round($prod['quantity'] * $prod['price'] * ($percentBonuspay / 100));
                  $this->products_old[$keyProdCart]['percentbonuspay'] = $percentBonuspay;
                  $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
              } else {
                  $this->products_old[$keyProdCart]['bonuspay'] = 0;
                  $this->products_old[$keyProdCart]['percentbonuspay'] = 0;
                  $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
              }
          }
          $this->getUser()->setAttribute('products_to_cart', serialize($this->products_old));
      }
    }

    public function executeCartinfoheader(sfWebRequest $request) {
        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($products_old as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * ($product['price_w_discount'] > 0 ? $product['price_w_discount'] : $product['price']));
        }
        $this->products_old = $products_old;
    }

    public function executeJelinfoheader(sfWebRequest $request) {
        $this->products_jel = unserialize($this->getUser()->getAttribute('products_to_desire'));
        if ($this->products_jel == "") {
            return $this->renderText('0');
        } else {
            return $this->renderText(count($this->products_jel));
        }
    }
}

class atolPos {//обмен с кассовым сервисом atol v.4

  const API_VERSION = 'v4';

  private $login;
  private $pass;
  private $company;
  private $group;
  private $inn;
  private $site;
  private $nds;
  private $tax;

  private $token;
  private $url;

  public function __construct($isTest){
    if ($isTest){
      $this->url='https://testonline.atol.ru/possystem/';
      $this->login='v4-online-atol-ru';
      $this->pass='iGFFuihss';
      $this->group='v4-online-atol-ru_4179';
      $this->inn='5544332219';
      $this->company='АТОЛ';
      $this->site='https://v4.online.atol.ru';
      $this->nds=csSettings::get('atol_nds');
      $this->tax=csSettings::get('atol_tax');
      $this->callback="https://dev.";
    }
    else {
      $this->url='https://online.atol.ru/possystem/';
      $this->login=csSettings::get('atol_login');
      $this->pass=csSettings::get('atol_pass');
      $this->group=csSettings::get('atol_group');
      $this->inn=csSettings::get('atol_inn');
      $this->company=csSettings::get('atol_company');
      $this->site='https://onona.ru';
      $this->nds=csSettings::get('atol_nds');
      $this->tax=csSettings::get('atol_tax');
      $this->callback="https://";
    }
    $this->callback.='onona.ru/check-atol-doc';
    $this->getToken();

  }

  public function printDoc($orderId){//Печать чека по документу
    $order=OrdersTable::getInstance()->findOneById($orderId);
    $orderData=unserialize($order->getText());
    $prefix=csSettings::get('order_prefix');
    $url=$this->group.'/sell';
    $data['external_id']=$prefix.$orderId;
    $data['timestamp']=date('d.m.Y H:i:s');
    $data['service']['callback_url']=$this->callback;
    $customer=sfGuardUserTable::getInstance()->findOneById($order->getCustomerId());
    $receipt['client']=[
      'email' => $customer->getEmailAddress(),
      'phone' => $customer->getPhone(),
    ];
    $receipt['company']=[
      'email' => 'info@onona.ru',
      'sno' => $this->tax,
      'inn' => $this->inn,
      'payment_address' => $this->site,
    ];
    $sum=0;
    foreach ($orderData as $product) {
      $productDB=ProductTable::getInstance()->findOneById($product['productId']);
      $price=$product['price'];
      if(isset($product['price_w_discount']) && $product['price_w_discount'] < $price) $price=$product['price_w_discount'];
      if(isset($product['price_final']) && $product['price_final']) $price=$product['price_final'];
      if(isset($product['price_final']) && !$product['price_final'] && !$product['price_w_discount']) $price = 0;
      // if($product['price_w_discount'] < $price) $price=$product['price_w_discount'];
      $items[]=[
        // 'name'=> 'item '.$product['productId'],
        'name'=> $product['productId'] == 14613 ? 'Доставка' : $productDB->getName(),
        'price' => 1*$price,
        'quantity' => 1*$product['quantity'],
        'sum' => $price*$product['quantity'],
        // 'measurement_unit' => 'шт',
        'payment_method' => 'full_prepayment',
        'payment_object' => 'commodity',
        'vat'=>[
          'type' => 'none',
          'sum' => 0,
        ],
      ];
      $sum+=$price*$product['quantity'];
    }
    $receipt['items']=$items;
    $receipt['payments']=[
      [
      'type'=> 1,
      'sum' => $sum,
      ]
    ];
    $receipt['total']=$sum;

    $data['receipt']=$receipt;
    // $this->log($data);
    // die ('<pre>'.print_r(['order' => $orderData, 'data' => $data, 'url' => $url], true));
    // $data=[];
    $result=$this->sendRequest( $data, $url );
    return $result;
    // die('!~<pre>'.print_r(, true));
  }

  public function getToken($force=false){
    $settings=AtolsettingsTable::getInstance()->findOneById(1);
    if($force || (time() - strtotime($settings->getUpdatedAt()) > 23*60*60)) {
      $this->requestToken();
      if($this->token>0) {
        $settings->setToken($this->token);
        $settings->save();
      }
    }
    else $this->token=$settings->getToken();
  }

  private function requestToken(){
    $result=$this->sendRequest([
      'login'=>$this->login,
      'pass' => $this->pass,
    ], 'getToken');
    if(!$result->error) $this->token=$result->token;
    else {
      $this->log(['text'=>'Ошибка получения токена', 'data'=>$result]);
      $this->token=-1;
    }
  }

  private function sendRequest( $data, $methodUrl, $protocol='POST'){
    error_reporting(E_ALL);
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', '1');

    $url=$this->url.self::API_VERSION.'/'.$methodUrl;

    $headers = [
      'Accept: application/json; charset=utf-8',
    ];
    if($methodUrl!='getToken'){
      $headers[] ='Token: '.$this->token;
    }

    if ('POST' == $protocol) {
      $headers[] = 'Content-Type: application/json';
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $protocol);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if ('POST' == $protocol) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);


    $error = null;
    if (false === $response) {
      $error = curl_error($ch);
    }
    if($error) $this->log( [
      'url' => $url,
      'headers' => $headers,
      'data' => $data,
      'errors'=> $error,
      'response'=> $response,
    ]);

    curl_close($ch);

    return json_decode($response);
  }

  public function log($data){
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/../atol.log', "\n\n--------------- ".date('d.m.Y H:i:s')." ---------------\n".print_r($data, true), FILE_APPEND);
  }

}
