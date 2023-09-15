<?
sfContext::getInstance()->getUser()->setAttribute('deliveryId', "");
slot('topMenu', true);
?>
<div class="borderCart" id="gtm-thank-you" data-coupon="<?=$order->getCoupon()?>">
    <center><span style="font-size:24px;">Уважаемый покупатель,<br />
            благодарим вас за заказ в «Он и Она»!</span></center>

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
    <?
      slot('advcake', 6);
      slot('advcake_order', [
        'id' => $order->getId(),
        'totalPrice' => $TotalSumm,
      ]);
    ?>
    <?/* 135322 - onona.ru - Список внешних сервисов
    <script type="text/javascript">function readCookie(name) {if (document.cookie.length > 0) { offset = document.cookie.indexOf(name + "="); if (offset != -1) { offset = offset + name.length + 1; tail = document.cookie.indexOf(";", offset); if (tail == -1) tail = document.cookie.length; return unescape(document.cookie.substring(offset, tail)); } } return null; } var url='https://track.leadhit.io/stat/lead_form?f_orderid=';

        //Вместо {{order_id}} должен подставляться номер заказа.

        url += "<?=$order->getId()?>";



        url += '&url='; url += encodeURIComponent(window.location.href); url += '&action=lh_orderid'; url += '&uid=' + readCookie('_lhtm_u'); url += '&vid='; url += readCookie('_lhtm_r').split('|')[1]; url += '&ref=direct&f_cart_sum=&clid='+'5604fd6fbbddbd5e6a27efb2'; var sc = document.createElement("script"); sc.type = 'text/javascript'; var headID = document.getElementsByTagName("head")[0]; sc.src = url; headID.appendChild(sc);
    </script>*/?>
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
                        { id: <?= $productInfo['productId'] ?>, qnt: <? echo $productInfo['quantity']; ?>, price: <?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?> }<?= count($products_old) != ($key + 1) ? ',' : '' ?>
                  <? endif;
                endforeach; ?>
                                  ]
                          });
                          } catch (e) {}
                      })

    </script>
    <?
      $customer = sfGuardUserTable::getInstance()->findOneById($order->getCustomerId());
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
    <div style="clear: both;margin-bottom: 20px;"></div>
    Ваш заказ поступил в обработку. Номер заказа: <span style="color: #c3060e; font-size: 14px;" id="order-id"><?= $order->getPrefix() ?><?= $order->getId() ?></span>
    <div style="clear: both;margin-bottom: 20px;"></div>
    <div>
      <div class="order-result-info">
        <div class="left-side">
          <div class="line">
            <div class="line-header">Способ доставки:</div>
            <div class="line-content"><?= $order->getDelivery() ?></div>
          </div>
          <div class="line">
            <div class="line-header">Стоимость доставки:</div>
            <div class="line-content" id="order-shipping"><?= $order->getDeliveryPrice() ?></div>
          </div>
          <div class="line">
            <div class="line-header">Форма оплаты:</div>
            <div class="line-content"><?= $order->getPayment() ?></div>
          </div>
          <div class="line">
            <div class="line-header">Общая сумма заказа:</div>
            <div class="line-content" id="order-total"><?= ($TotalSumm - $bonusDropCost) ?></div>
          </div>
        </div>
        <div class="right-side">
          <div>Вы заказали:</div>
          <?php
            $productOrder = $order->getText();
            $products_old = $productOrder != '' ? unserialize($productOrder) : '';
             // die('<pre>'.print_r($products_old, true).'</pre>') ;
            $index=0;
            foreach ($products_old as $key => $productInfo):
                if ($productInfo['productId'] > 0):
                  $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                  // for($gdeI=0; $gdeI<$productInfo['quantity']; $gdeI++)
                  $gdeslonCodes[]='{ id : "'.$productInfo['productId'].'", quantity: '.$productInfo['quantity'].', price: '.($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']).'}';
                  if($productInfo['productId']!=14613 )//Если не доставка
                    $advcakeItems[]=[
                      'id' => $productInfo['productId'],
                      'name' => $product->getName(),
                      'price' => ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ,
                      'quantity' => $productInfo['quantity'],
                      'categoryId' => $product->getGeneralCategory()->getId(),
                      'categoryName' => $product->getGeneralCategory()->getName(),
                    ];
                    // $gdeslonCodes[]=$productInfo['productId'].':'.($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']);
                ?>
                  <div
                    class="gtm-thankyou-item"
                    data-index="<?=$index++?>"
                    data-quant="<?=$productInfo['quantity']?>"
                    data-name="<?=$product->getName()?>"
                    data-id="<?=$product->getId()?>"
                    data-price="<?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?>"
                    data-category="<?=$product->getGeneralCategory()?>"
                  >
                    <img src="/newdis/images/cart/galka.png"> <? echo $product->getName(); ?> - <? echo $productInfo['quantity']; ?>шт., цена: <?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?> р.
                  </div>
                <? endif;
            endforeach;
          ?>
        </div>
      </div>

        <div style="clear: both;margin-bottom: 20px;"></div>

        <div align="left" style="" class="bold">
            <?php
            $page = PageTable::getInstance()->findOneById(91);
            echo $page->getContent();
            ?>



            <?php include_component('cart_new', 'partner', array('orderId' => $order->getId(), 'price' => $TotalSumm, 'priceNotDelivery' => ($TotalSumm - $order->getDeliveryPrice()), 'order' => $order)) ?>

        </div>				<nav class="pager">
            <ol>
            </ol>
        </nav></div>
</div>
<script type="text/javascript">//admitad 114338 - onona.ru - Обновить трекинг-код
  $(document).on('ready', function(){
    ADMITAD = window.ADMITAD || {};
    ADMITAD.Invoice = ADMITAD.Invoice || {};
    // var broker='adm';
    var broker = $.cookie('utm_source');
    // if(referalCookie == '1819251538') broker='vk';
    // if(referalCookie == '2801045062') broker='retailrocket';
    // if(referalCookie == '1493006643') broker='retailrocket';
    // if(referalCookie == '1916149974') broker='pushworld';
    // if(referalCookie == )
    // console.log(broker)
    ADMITAD.Invoice.broker = broker;     // параметр дедупликации (по умолчанию для admitad)
    ADMITAD.Invoice.category = "2";     // код целевого действия (определяется при интеграции)
    var orderedItem = [];               // временный массив для товарных позиций

    $(".gtm-thankyou-item").each(function (index, item){ // повторить для каждой товарной позиции в корзине
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
    });

    ADMITAD.Invoice.referencesOrder = ADMITAD.Invoice.referencesOrder || [];
    // добавление товарных позиций к заказу

    ADMITAD.Invoice.referencesOrder.push({

      orderNumber: $('#gtm-thank-you').find('#order-id').text(), // внутренний номер заказа (не более 100 символов)

      orderedItem: orderedItem

    });


    // Важно! Если данные по заказу admitad подгружаются через AJAX раскомментируйте следующую строку.

    // ADMITAD.Tracking.processPositions();
  });
</script>
<? slot('gdeSlonCodes', 'products: [ '.implode(', '."\n", $gdeslonCodes).' ],'); ?>
<? slot('gdeSlonOrderId', 'order_id:    "'.$order->getPrefix().$order->getId().'",'); ?>
<? //slot('gdeSlonOrderId', '&order_id='.implode(',', $order->getId())); ?>
<? slot('gdeSlonMode', 'thanks'); ?>
<? slot('advcake_order_basket', $advcakeItems) ?>
