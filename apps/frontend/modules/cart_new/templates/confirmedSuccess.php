<?
global $isTest;
$h1='Спасибо за заказ';
slot('caltat', 4);
slot('breadcrumbs', [
  ['text' => $h1],
]);
// slot('h1', $h1);
?>
<main class="wrapper">
  <div  class="lk-detail-order" id="gtm-thank-you" data-coupon="<?=$order->getCoupon()?>">
    <div class="lk-detail-order-white">
      <h2>Уважаемый покупатель благодарим вас за заказ в «Он и Она»!</h2>
      <p>&nbsp;</p>
      <? //if(!false) :?>
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
                    $productOrder = $order->getText();
                    $products_old = $productOrder != '' ? unserialize($productOrder) : '';
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

            /*$(".gtm-thankyou-item").each(function (index, item){ // повторить для каждой товарной позиции в корзине
              var $item=$(item);

              orderedItem.push({
                Product: {
                    productID: $item.data('id'), // внутренний код продукта (не более 100 символов, соответствует ID из товарного фида).
                    category: '1',               // код тарифа (определяется при интеграции)
                    price: $item.data('price'),          // цена товара
                    priceCurrency: "RUB",        // код валюты ISO-4217 alfa-3
                },
                orderQuantity: $item.data('quant'),   // количество товара
                additionalType: "sale"           // всегда sale
              });
            });*/

            ADMITAD.Invoice.referencesOrder = ADMITAD.Invoice.referencesOrder || [];
            // добавление товарных позиций к заказу

            ADMITAD.Invoice.referencesOrder.push({

              orderNumber: '<?= $order->getPrefix().$order->getId() ?>', // внутренний номер заказа (не более 100 символов)

              orderedItem: orderedItem

            });
            //console.log('-----------------------------------------ADMITAD.Invoice');
            //console.log(ADMITAD.Invoice);


            // Важно! Если данные по заказу admitad подгружаются через AJAX раскомментируйте следующую строку.

            // ADMITAD.Tracking.processPositions();
          // });
        </script>
      <? endif ?>
      <?
        slot('advcake', 6);
        slot('advcake_order', [
          'id' => $order->getId(),
          'totalPrice' => $TotalSumm,
        ]);
      ?>
      <h1><?= $customer->getFirstName(). ' '. $customer->getLastName() ?></h1>
      <?
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
              _gaq.push(['_trackTrans']); ");
      ?>
      <div class="lk-detail-order-header">Ваш заказ поступил в обработку. Номер заказа: <span  id="order-id"><?= $order->getPrefix() ?><?= $order->getId() ?></span></div>
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
      <? if(!is_object($paymentDoc) && is_object($pay)) if ($pay->getId()==58)
          include_component("cart_new", "imeform", array(
            'order'=> $order,
            'customer' => $customer,
            'products' => $products,
          ));
      ?>
      <table class="basket">
        <thead>
          <tr>
            <th></th>
            <th class="size-name">Наименование</th>
            <th>Цена, руб.</th>
            <th>Кол-во</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $productOrder = $order->getText();
            $products_old = $productOrder != '' ? unserialize($productOrder) : '';
             // die('<pre>'.print_r($products_old, true).'</pre>') ;
            $index=0;
            foreach ($products_old as $key => $productInfo):
                if ($productInfo['productId'] > 0):
                  $product = ProductTable::getInstance()->findOneById($productInfo['productId']);

                  $image=false;
                  if ($product->getSlug() != "dostavka"){
                    $photoalbum = $product->getPhotoalbums();
                    $photos = $photoalbum[0]->getPhotos();
                    if (isset($photos[0]))
                      $image=$photos[0]->getFilename();
                  }
                  // for($gdeI=0; $gdeI<$productInfo['quantity']; $gdeI++)
                  $gdeslonCodes[]='{ id : "'.$productInfo['productId'].'", quantity: '.$productInfo['quantity'].', price: '.($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']).'}';
                  if($productInfo['productId']!=14613 )//Если не доставка
                    $advcakeItems[]=[
                      'id' => $productInfo['productId'],
                      'name' => $product->getName(),
                      'price' => (($productInfo['discount']==100 || $productInfo['price_w_discount']) > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ,
                      'quantity' => $productInfo['quantity'],
                      'categoryId' => $product->getGeneralCategory()->getId(),
                      'categoryName' => $product->getGeneralCategory()->getName(),
                    ];
                    // $gdeslonCodes[]=$productInfo['productId'].':'.($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']);
                ?>

                  <tr
                    class="gtm-thankyou-item"
                    data-index="<?=$index++?>"
                    data-quant="<?=$productInfo['quantity']?>"
                    data-name="<?=$product->getName()?>"
                    data-id="<?=$product->getId()?>"
                    data-price="<?= (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']) ?>"
                    data-category="<?=$product->getGeneralCategory()?>"
                  >
                    <td class="basket-pic">
                      <? if($image) :?>
                        <a href="/product/<?= $product->getSlug() ?>">
                          <img src="/uploads/photo/thumbnails_250x250/<?= $image ?>">
                        </a>
                      <? endif ?>
                    </td>
                    <td class="basket-name -leftAlign"><a href="/product/<?= $product->getSlug() ?>"><?=$product->getName();?></a></td>
                    <td class="basket-price" data-mobtext="Цена, руб."><?= (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']) ?></td>
                    <td class="basket-numb" data-mobtext="Кол-во"> <?= $productInfo['quantity']; ?> </td>

                  </tr>
                <? endif;
            endforeach;
          ?>
        </tbody>
      </table>
      <div class="lk-detail-order-summary">
        <div class="row">
          <div class="header first">Способ доставки:</div><div class="detail first"><?= $order->getDelivery() ?></div>
        </div>
        <div class="row">
          <div class="header">Стоимость доставки:</div><div class="detail" id="order-shipping"><?= $order->getDeliveryPrice() ?></div>
        </div>
        <div class="row">
          <div class="header">Форма оплаты:</div><div class="detail"><?= $order->getPayment() ?></div>
        </div>
        <? if(is_object($pay)) if ($pay->getId()==57):?>
          <div class="row">
            <div class="header">Статус оплаты:</div>
            <div class="detail">
              <span style="color: <?= is_object($paymentDoc) ? 'green;">оплачен':'red;">требуется оплата' ?></span>
            </div>
          </div>
        <? endif ?>
        <? if(is_object($pay)) if ($pay->getId()==58):?>
          <div class="row">
            <div class="header">Статус оплаты:</div>
            <div class="detail">
              <span style="color: <?= is_object($paymentDoc) ? 'green;">оплачен':'red;">требуется оплата' ?></span>
            </div>
          </div>
          <div class="row">
            <div class="header">Сумма в AiCoin:</div>
            <div class="detail">
              <span><?= round(($TotalSumm - $order->getBonuspay())/$exchangeRate) ?></span>
            </div>
          </div>
        <? endif ?>
        <? if($order->getBonuspay()):?>
          <div class="row">
            <div class="header">Принято бонусов:</div>
            <div class="detail">
              <?= $order->getBonuspay() ?>
            </div>
          </div>
        <? endif ?>
        <div class="row">
          <div class="header">Общая сумма заказа:</div><div class="detail" id="order-total"><?= ($TotalSumm - $bonusDropCost) ?></div>
        </div>
        <div class="row">
          <div class="header last">Состояние заказа:</div><div class="detail last">Новый</div>
        </div>
      </div>

      <?= $page->getContent(); ?>
    </div>
  </div>
</main>
<? slot('advcake_order_basket', $advcakeItems) ?>
