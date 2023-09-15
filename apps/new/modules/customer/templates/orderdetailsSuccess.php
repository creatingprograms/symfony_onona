<?
global $isTest;
$h1='Заказ №: '.$order->getPrefix().$order->getId();
// slot('caltat', 4);
slot('breadcrumbs', [
  ['text' => 'Личный кабинет', 'link'=>'/lk'],
  ['text' => $h1],
]);
slot('h1', $h1);
slot('catalog-class', 'order-confirm');
$productOrder = $order->getText();
$products_old = $productOrder != '' ? unserialize($productOrder) : '';
$count=0;
$costWoOnline=$totalDiscount=0;
$comments=$order->getComments();
$deliveryLine =
  $customer->getCity().
  ($customer->getStreet() ? ', '.$customer->getStreet() : '').
  ($customer->getHouse() ? ', '.$customer->getHouse() : '')
;

$bonusToAdd=($order->getFirsttotalcost()) * csSettings::get("persent_bonus_add")/100;
foreach ($products_old as $key => $productInfo){
  if ($productInfo['productId'] > 0){
    $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
    if(!is_object($product)) continue;
    $products_old[$key]['name']=$product->getName();
    $products_old[$key]['general_catogory_name']=$product->getGeneralCategory()->getName();
    $products_old[$key]['general_catogory_id']=$product->getGeneralCategory()->getId();
    $products_old[$key]['slug']=$product->getSlug();
    $discount=$productInfo['price']-$productInfo['price_w_discount'];
    if(isset($productInfo['price_final'])) $discount=$productInfo['price']-$productInfo['price_final'];
    $products_old[$key]['discount']=$discount;
    $totalDiscount+= $productInfo['quantity']*$discount;
    // $products_old[$key]['price_wo_online']=round($productInfo['price']*(100-$productInfo['discount'])/100);


    if($productInfo['productId']!=14613 ){//Если не доставка
      $count+=$productInfo['quantity'];
      $costWoOnline+=$products_old[$key]['price']*$productInfo['quantity'];

    }

  }
}
if($order->getDelivery()->getId()==10){
  $re='/ID магазина:\s(\d+)/m';
  preg_match_all($re, $comments=$order->getComments(), $matches, PREG_SET_ORDER, 0);
  $shopId=$matches[0][1];
  if($shopId){
    $shop=ShopsTable::getInstance()->findOneById($shopId);
  }
  else $shop=false;
}
if($order->getDelivery()->getId()==11){//pickpoint
  $re='/Адрес постамата: (.+)/m';
  preg_match_all($re, $comments=$order->getComments(), $matches, PREG_SET_ORDER, 0);
  if(isset($matches[0][1])) $deliveryLine=$matches[0][1];
}
if($order->getDelivery()->getId()==14){
  $re='/Адрес точки DLH: (.+)/m';
  preg_match_all($re, $comments=$order->getComments(), $matches, PREG_SET_ORDER, 0);
  if(isset($matches[0][1])) $deliveryLine=$matches[0][1];
}
if($order->getDelivery()->getId()==17){
  $re='/Адрес пункта выдачи: (.+)/m';
  preg_match_all($re, $comments=$order->getComments(), $matches, PREG_SET_ORDER, 0);
  if(isset($matches[0][1])) $deliveryLine=$matches[0][1];
}
// echo(__LINE__.'<pre>'.print_r([$bonusToAdd, $totalDiscount, '$products_old'=>$products_old, 'create' => $order->getCreatedAt(), 'id'=>$order->getId()], true).'</pre>');
$onlinePayment=true;
?>
<? if(/*!$isTest && */$_GET['payed'] == 'Y'): ?>
  <? 
    $gender = trim($customer->getSex());
    if ($gender == 'm') $gender = 'male';
    if ($gender == 'g') $gender = 'female';
  ?>
  <div id="promocode-element-container"></div>
  <script type="text/javascript">
    var _iPromoBannerObj = function() {
    this.htmlElementId = 'promocode-element-container';
    this.params = {
    '_shopId': 1029,
    '_bannerId': 2869,
    '_customerFirstName': '<?= $customer->getFirstName() ?>',
    '_customerLastName': '<?= $customer->getLastName() ?>',
    '_customerEmail': '<?= $customer->getEmailAddress() ?>',
    '_customerPhone': '<?= $customer->getPhone() ?>',
    '_customerGender': '<?= $gender ?>',
    '_orderId': '<?= $order->getPrefix().$order->getId() ?>',
    '_orderValue': '<?= $order->getFirsttotalcost()+$order->getDeliveryPrice() ?>',
    '_orderCurrency': 'RUB',
    '_usedPromoCode': '<?=$order->getCoupon()?>'
    };
    this.lS=function(s){document.write('<sc'+'ript type="text/javascript" src="'+s+'" async="true"></scr'+'ipt>');},
    this.gc=function(){return document.getElementById(this.htmlElementId);};
    var r=[];for(e in
    this.params){if(typeof(e)==='string'){r.push(e+'='+encodeURIComponent(this.params[e]));}}r.push('method=main');r.push('jsc=iPromoCpnObj');this.lS(('https:'==document.location.protocol ?
    'https://':'http://')+'get4click.ru/wrapper.php?'+r.join('&'));};
    var iPromoCpnObj = new _iPromoBannerObj();
  </script>
<? endif ?>
<div class="wrap-block">
  <div class="container lk-detail-order" id="gtm-thank-you" data-coupon="<?=$order->getCoupon()?>">
    <div class="order-wrapper">
      <div class="left-side">
        <p>
          <?= $customer->getFirstName(). ' '. $customer->getLastName() ?>,
          уведомление о статусе Вашего заказа вы получите на номер
          <?= $customer->getPhone() ?>
          и по электронной почте
          <?= $customer->getEmailAddress() ?><br><br>

        </p>
        <div class="delivery-info">
          <div class="left-side-h">
            <?= $order->getDelivery() ?>
          </div>
            <? if(is_object($shop)):?>
              <?
                if($shop->getPreviewImage()) $image="/uploads/assets/images/".$shop->getPreviewImage();
                if(!isset($image) || !file_exists($_SERVER['DOCUMENT_ROOT'].$image)) $image='/uploads/assets/images/001.jpg';
              ?>
              <div class="shop-block">
                <div class="image-block" style="background-image:url('<?=$image?>');"></div>
                <div class="info-block">
                  <? if($shop->getMetro()) :?>
                    <div class="delivery-info-metro">
                      <?=$shop->getMetro()?>
                    </div>
                  <? endif ?>
                  <div class="delivery-info-address">
                    <?=$shop->getAddress()?>
                  </div>
                  <div class="delivery-info-exclam">
                    Можно забрать в течение 5 дней
                  </div>
                  <div class="info-block-bottom">
                    <a href="/shops/<?= $shop->getSlug()?>">O магазине ></a>
                    <a href="/shops/<?= $shop->getSlug()?>">Показать на карте ></a>
                  </div>
                </div>
              </div>

            <? else: ?>
              <div class="delivery-info-address">
                <?= $deliveryLine ?>
              </div>
            <? endif ?>

        </div>
      </div>
      <div class="right-side">
        <p>В заказе <?=ILTools::getWordForm($count, 'товар')?> на сумму</p>
        <div class="order-sum"> <?=ILTools::formatPrice($costWoOnline)?> ₽</div>
        <div class="bonus">+<?=ILTools::formatPrice($bonusToAdd)?> <?=ILTools::getWordForm($bonusToAdd, 'бонус', true)?> на счет</div>
        <?php foreach ($products_old as  $productInfo): ?>
          <? if($productInfo['productId']==14613) continue; ?>
          <div class="item item-name"><?=$productInfo['name']?></div>
          <div class="item item-detail"><?=$productInfo['quantity']?> шт × <?=ILTools::formatPrice($productInfo['price'])?> ₽</div>
        <?php endforeach; ?>
        <? if($order->getDeliveryPrice()):?>
          <p>Доставка</p>
          <div class="order-sum"><?=ILTools::formatPrice($order->getDeliveryPrice())?> ₽</div>
        <? endif ?>
        <? if($totalDiscount-$order->getBonuspay()):?>
          <p>Скидка</p>
          <div class="order-sum">
            <?=ILTools::formatPrice($totalDiscount-$order->getBonuspay())?> ₽
          </div>
        <? endif ?>
        <? if($order->getBonuspay()):?>
          <br>
          <p>Списано бонусов</p>
          <div class="order-sum order-sum-bonus">
            <?=ILTools::formatPrice($order->getBonuspay())?>
          </div>
        <? endif ?>
        <div class="order-total">
          <p>Сумма к оплате</p>
          <div class="order-sum">
            <?=ILTools::formatPrice($order->getFirsttotalcost()+$order->getDeliveryPrice())?> ₽
          </div>
          <? if(!is_object($paymentDoc) && is_object($pay)) if ($pay->getId()==59)
              include_component("cart_new", "yookassaform", array(
                'order'=> $order,
                'instantRedirect' => true,
              ));
          ?>
          <? if(!is_object($paymentDoc) && is_object($pay)) if ($pay->getId()==57)
              include_component("cart_new", "mkbform", array(
                'order'=> $order,
                'customer' => $customer,
              ));
          ?>
        </div>

      </div>
    </div>
  </div>
</div>

<? include_component("category", "sliderItems", array('sf_cache_key' => 'RecommendItems', 'type'=>'recommend'))?>
<div class="hide-title">
  <? include_component("page", "subpage", array( 'page'=>'advantages-item')); ?>
</div>
