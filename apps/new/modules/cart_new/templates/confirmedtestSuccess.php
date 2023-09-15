<?
global $isTest;
$h1='Спасибо за заказ';
slot('caltat', 4);
slot('breadcrumbs', [
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
    $products_old[$key]['discount']=$discount;
    $totalDiscount+= $productInfo['quantity']*$discount;
    // $products_old[$key]['price_wo_online']=round($productInfo['price']*(100-$productInfo['discount'])/100);

    $gdeslonCodes[]=
      '{ id : "'.$productInfo['productId'].
        '", quantity: '.$productInfo['quantity'].
        ', price: '.($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']).
      '}';
    if($productInfo['productId']!=14613 ){//Если не доставка
      $count+=$productInfo['quantity'];
      $costWoOnline+=$products_old[$key]['price']*$productInfo['quantity'];
      $advcakeItems[]=[
        'id' => $productInfo['productId'],
        'name' => $products_old[$key]['name'],
        'price' => (($productInfo['discount']==100 || $productInfo['price_w_discount']) > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ,
        'quantity' => $productInfo['quantity'],
        'categoryId' => $products_old[$key]['general_catogory_id'],
        'categoryName' => $products_old[$key]['general_catogory_name'],
      ];
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
echo(__LINE__.'<pre>'.print_r([$bonusToAdd, $totalDiscount, '$products_old'=>$products_old, 'create' => $order->getCreatedAt(), 'id'=>$order->getId()], true).'</pre>');
$onlinePayment=true;
?>

<? if(!$isTest) :?>
  <script type="text/javascript">
    (function () {
      function f() {
        yaCounter144683.reachGoal("ConfirmOrderEnable", {
          order_id: "<?= $order->getId() ?>",
          order_price: "<?= $TotalSumm ?>",
          currency: "RUR",
          exchange_rate: "1",
          goods:
          [<?= $yaParams ?>]
        });
      }

      if (window.addEventListener) {
        window.addEventListener("load", f, false);
      } else if (window.attachEvent) {
        window.attachEvent("onload", f);
      }
    })();
  </script>
  <script type="text/javascript">
    rrApiOnReady.push(function () {
    <?/*rrApi.setEmail("<?//= $sf_user->getGuardUser()->getEmailAddress() ?>");*/?>
    try {
      rrApi.order({
        transaction: <?= $order->getId() ?>,
        items: [
          <?php
            foreach ($products_old as $key => $productInfo):
              if ($productInfo['productId'] > 0 && $productInfo['productId']!=14613):
                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                ?>
                { id: <?= $productInfo['productId'] ?>, qnt: <? echo $productInfo['quantity']; ?>, price: <?= (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']) ?> }<?= count($products_old) != ($key + 1) ? ',' : '' ?>
                <? endif;
              endforeach; ?>
          ]
        });
      } catch (e) {}
    })
  </script>
  <script type="text/javascript">//admitad 114338 - onona.ru - Обновить трекинг-код
    // $(document).on('ready', function(){//jquery тут не работает
    ADMITAD = window.ADMITAD || {};
    ADMITAD.Invoice = ADMITAD.Invoice || {};
    var broker = '<?=$_COOKIE['utm_source'] ? $_COOKIE['utm_source'] : 'na'?>';
    ADMITAD.Invoice.broker = broker;     // параметр дедупликации (по умолчанию для admitad)
    ADMITAD.Invoice.category = "2";     // код целевого действия (определяется при интеграции)
    var orderedItem = [];               // временный массив для товарных позиций
    <? foreach ($products_old as $key => $productInfo):?>
      <? if (!$productInfo['productId'] || $productInfo['productId']==14613) continue; ?>
      orderedItem.push({
        Product: {
          productID: <?= $productInfo['productId'] ?>, // внутренний код продукта (не более 100 символов, соответствует ID из товарного фида).
          category: '1',               // код тарифа (определяется при интеграции)
          price: <?= (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']) ?>,          // цена товара
          priceCurrency: "RUB",        // код валюты ISO-4217 alfa-3
        },
        orderQuantity: <?= $productInfo['quantity']; ?>,   // количество товара
        additionalType: "sale"           // всегда sale
      });
    <? endforeach ?>

    ADMITAD.Invoice.referencesOrder = ADMITAD.Invoice.referencesOrder || [];
    // добавление товарных позиций к заказу

    ADMITAD.Invoice.referencesOrder.push({

      orderNumber: '<?= $order->getPrefix().$order->getId() ?>', // внутренний номер заказа (не более 100 символов)

      orderedItem: orderedItem

    });
  </script>
<? endif ?>
<?
  slot('advcake', 6);
  slot('advcake_order', [
  'id' => $order->getId(),
  'totalPrice' => $TotalSumm,
  ]);
  /*
  slot('googleCart', "    _gaq.push(['_addTrans',
    '" . $order->getId() . "',
    '',
    '" . $TotalSumm . "',
    '',
    '" . str_replace(" р.", "", $order->getDeliveryPrice()) . "',
    '" . $customer->getCity() . "',
    '',
    'Россия'
    ]);


    " . $googleParams . "
    _gaq.push(['_trackTrans']); ");*/
?>

<div class="wrap-block">
  <div class="container lk-detail-order" id="gtm-thank-you" data-coupon="<?=$order->getCoupon()?>" data-id="<?= $order->getPrefix() ?><?= $order->getId() ?>">
    <div class="order-wrapper">
      <div class="left-side">
        <div class="left-side-h">
          Заказ №<?= $order->getPrefix() ?><?= $order->getId() ?> создан
          <a class="js-print print-block" href="javascript: window.print();">Распечатать</a>
        </div>
        <p>
          <?= $customer->getFirstName(). ' '. $customer->getLastName() ?>,
          уведомление о статусе Вашего заказа вы получите на номер
          <?= $customer->getPhone() ?>
          и по электронной почте
          <?= $customer->getEmailAddress() ?><br><br>

          Проверить статус заказа в <a href="/lk">личном кабинете</a>
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
        <? $i=0; ?>
        <?php foreach ($products_old as  $productInfo): ?>
          <? if($productInfo['productId']==14613) continue; ?>
          <div class="item item-name gtm-thankyou-item"
            data-index="<?= $i++ ?>"
            data-id="<?= $productInfo['productId'] ?>"
            data-name="<?= $productInfo['name'] ?>"
            data-price="<?= $productInfo['price_w_bonuspay'] > $productInfo['price_w_discount'] ? $productInfo['price_w_discount'] : $productInfo['price_w_bonuspay']?>"
            data-quantity="<?= $productInfo['quantity'] ?>"
            data-category="<?= $productInfo['general_catogory_name'] ?>"
          ><?=$productInfo['name']?></div>
          <div class="item item-detail"><?=$productInfo['quantity']?> шт × <?=ILTools::formatPrice($productInfo['price'])?> ₽</div>
        <?php endforeach; ?>
        <? if($order->getDeliveryPrice()):?>
          <p>Доставка</p>
          <div class="order-sum gtm-thank-you-delivery" data-delivery="<?=$order->getDeliveryPrice()?>"><?=ILTools::formatPrice($order->getDeliveryPrice())?> ₽</div>
        <? else : ?>
          <div style="display: none!important;" class="gtm-thank-you-delivery" data-delivery="0"></div>
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
        <div class="order-total gtm-thank-you-total" data-total="<?$order->getFirsttotalcost()+$order->getDeliveryPrice()?>">
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


<? if(sizeof($advcakeItems)) slot('advcake_order_basket', $advcakeItems) ?>
