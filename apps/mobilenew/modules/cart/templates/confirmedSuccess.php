<?
sfContext::getInstance()->getUser()->setAttribute('deliveryId', "");
slot('topMenu', true);
?>
<div class="borderCart">
    <center><span style="font-size:24px;">Уважаемый покупатель,<br />
            благодарим вас за заказ в «Он и Она»!</span></center>

    <script type="text/javascript">
<?php ob_start(); ?>
        rrApiOnReady.push(function () {
            try {
            rrApi.order({
            transaction: <?= $order->getId() ?>,
                    items: [
<?php
$productOrder = $order->getText();
$products_old = $productOrder != '' ? unserialize($productOrder) : '';
foreach ($products_old as $key => $productInfo):
    if ($productInfo['productId'] > 0):
        $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
        ?>
                            { id: <?= $productInfo['productId'] ?>, qnt: <? echo $productInfo['quantity']; ?>, price: <?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?> }<?= count($products_old) != ($key + 1) ? ',' : '' ?><?
    endif;
endforeach;
?>
                    ]
            });
            } catch (e) {}
        })

<?php
$rrJS = ob_get_contents();
ob_clean();
ob_end_clean();

JSInPages($rrJS);
?>

    </script>
    <?php
    if ($order->getReferal() == "2801045062") {

        $productOrder = $order->getText();
        $products_old = $productOrder != '' ? unserialize($productOrder) : '';
        ?><script type="text/javascript">

            (function (d, w) {

                w._admitadPixel = {
                    response_type: 'img',
                    action_code: '2',
                    campaign_code: 'f0be12d9c8'

                };

                w._admitadPositions = w._admitadPositions || [];

                w._admitadPositions.push({
                    uid: '<?= sfContext::getInstance()->getRequest()->getCookie('uidAdmitad') ?>',
                    order_id: '<?= $order->getId() ?>',
                    position_id: '1',
                    client_id: '',
                    tariff_code: '1',
                    currency_code: '',
                    position_count: '1',
                    price: '<?= ($TotalSumm - $order->getDeliveryPrice()) ?>',
                    quantity: '1',
                    product_id: '',
                    screen: '',
                    tracking: '',
                    old_customer: '',
                    coupon: '',
                    payment_type: 'sale'

                });

                var id = '_admitad-pixel';

                if (d.getElementById(id)) {
                    return;
                }

                var s = d.createElement('script');

                s.id = id;

                var r = (new Date).getTime();

                var protocol = (d.location.protocol === 'https:' ? 'https:' : 'http:');

                s.src = protocol + '//cdn.asbmit.com/static/js/pixel.min.js?r=' + r;

                d.head.appendChild(s);

            })(document, window)

        </script>

        <noscript>

        <img src="//ad.admitad.com/r?campaign_code=f0be12d9c8&action_code=2&response_type=img&uid=<?= sfContext::getInstance()->getRequest()->getCookie('uidAdmitad') ?>&order_id=<?= $order->getId() ?>&position_id=1&tariff_code=1&currency_code=&position_count=1&price=<?= $TotalSumm ?>&quantity=1&product_id=&coupon=&payment_type=sale" width="1" height="1" alt="">

        </noscript>
<? } ?>
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
                })();</script><? /*
  <script type="text/javascript">
  $(document).ready(function(){
  yaCounter144683.reachGoal("ConfirmOrderEnable", {
  order_id: "<?= $order->getId() ?>",
  order_price: "<?= $TotalSumm ?>",
  currency: "RUR",
  exchange_rate: "1",
  goods:
  [<?= $yaParams ?> ]
  });
  });
  </script> */ ?>
    <?
    $customer = sfGuardUserTable::getInstance()->findOneById($order->getCustomerId());
    /*
      <script>
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-19032058-1']);
      _gaq.push(['_trackPageview']); */


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
    /*


      (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

      </script> */
    ?>
    <div style="clear: both;margin-bottom: 20px;"></div>
    Ваш заказ поступил в обработку. Номер заказа: <span style="color: #c3060e; font-size: 14px;"><?= $order->getPrefix() ?><?= $order->getId() ?></span>
    <div style="clear: both;margin-bottom: 20px;"></div>
    <div>

        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; vertical-align: top;">
            <tr>
                <td style="border:1px solid #C3060E; vertical-align: top;">
                    <table style="width: 100%;">
                        <tr><td style="background-color: #f5f5f5;">Способ доставки:</td><td><?= $order->getDelivery() ?></td></tr>
                        <tr><td style="background-color: #f5f5f5;">Стоимость доставки:</td><td><?= $order->getDeliveryPrice() ?></td></tr>
                        <tr><td style="background-color: #f5f5f5;">Форма оплаты:</td><td><?= $order->getPayment() ?></td></tr>
                        <tr><td style="background-color: #f5f5f5;">Общая сумма заказа:</td><td><?= ($TotalSumm - $bonusDropCost) ?></td></tr>
                    </table>
                </td></tr><tr>
                <td width="50%" style="border:1px solid #C3060E;padding: 10px; vertical-align: top;">    Вы заказали:<br />
                    <?php
                    $productOrder = $order->getText();
                    $products_old = $productOrder != '' ? unserialize($productOrder) : '';
                    foreach ($products_old as $key => $productInfo):
                        if ($productInfo['productId'] > 0):
                            $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                            /*   if ($key == 0) {
                              $productCityads = $product->getId();
                              $productCityadsQuantity = $productInfo['quantity'];
                              } else {
                              $productCityads.=", " . $product->getId();
                              $productCityadsQuantity = ", " . $productInfo['quantity'];
                              } */
                            ?><img src="/newdis/images/cart/galka.png"> <? echo $product->getName(); ?> - <? echo $productInfo['quantity']; ?>шт., цена: <?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?> р. <br /><?
                        endif;
                    endforeach;
                    ?></td>
            </tr>
        </table>

        <div style="clear: both;margin-bottom: 20px;"></div>

        <div align="left" style="" class="bold">
            <?php
            $page = PageTable::getInstance()->findOneById(91);
            echo $page->getContent();
            /*  ?>
             * Окончательную стоимость заказа с учетом всех скидок рассчитывает менеджер при подтверждении заказа.<br />
             * * Если вы сделали заказ поздно вечером или ночью, наш менеджер позвонит вам на следующий день после 10 утра (московское время) по указанному вами телефону для подтверждения заказа.
              <br /><br />
              <br />
              Статус заказа вы можете узнать в своем личном кабинете.<br />
              Для получения дополнительной информации о заказе вы можете связаться с менеджером в рабоче время (с 10 до 18 часов по московскому времени) по тел. +7 (495) 787 98 86 (по Москве), 8 800 700 98 85 (по России) или задать вопросы через он-лайн консультант.
              <br /><br />
              <br />
              Будем признательны, если вы оставите свои отзывы о приобретенных товарах на нашем сайте. Это поможет другим покупателям принять правильное решение при выборе товаров. А вам заработать дополнительные бонусы на свой бонусный счет.
              <br /><br />
              Ждем вас снова! */
            ?>




        </div>				<nav class="pager">
            <ol>
            </ol>
        </nav></div>
</div>