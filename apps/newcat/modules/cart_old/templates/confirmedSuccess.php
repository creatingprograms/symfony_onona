<? sfContext::getInstance()->getUser()->setAttribute('deliveryId', "");
slot('topMenu', true);
?>
<div class="borderCart">
    <center><span style="font-size:24px;">Уважаемый покупатель,<br />
            благодарим вас за заказ в «Он и Она»!</span></center>

    <script type="text/javascript">
        (function() {
            function f() {
                yaCounter144683.reachGoal("ConfirmOrderEnable", {
                    order_id: "<?= $order->getId() ?>",
                    order_price: "<?= $TotalSumm ?>", 
                    currency: "RUR",
                    exchange_rate: "1",
                    goods: 
                        [<?= $yaParams ?> ]
                }); 
            }

            if (window.addEventListener) {
                window.addEventListener("load", f, false);
            } else if (window.attachEvent) {
                window.attachEvent("onload", f);
            }
        })();
    </script><? /*
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
                <td style="border:1px solid #C3060E; vertical-align: top;"><div style="float: left;width: 150px;background-color: #f5f5f5;padding: 10px;">
                        Способ доставки:<br />
                        Стоимость доставки:<br />
                        Форма оплаты:<br />
                        Общая сумма заказа:<br /></div>
                    <div style="padding: 10px;margin-left: 170px;"><?= $order->getDelivery() ?><br /><?= $order->getDeliveryPrice() ?><br /><?= $order->getPayment() ?>
                        <br /><?= ($TotalSumm + ($order->getDeliveryPrice()) - $bonusDropCost) ?></div></td>
                <td width="50%" style="border:1px solid #C3060E;padding: 10px; vertical-align: top;">    Вы заказали:<br />
                    <?php
                    $productOrder = $order->getText();
                    $products_old = $productOrder != '' ? unserialize($productOrder) : '';
                    foreach ($products_old as $key => $productInfo):
                        $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                     /*   if ($key == 0) {
                            $productCityads = $product->getId();
                            $productCityadsQuantity = $productInfo['quantity'];
                        } else {
                            $productCityads.=", " . $product->getId();
                            $productCityadsQuantity = ", " . $productInfo['quantity'];
                        }*/
                        ?><img src="/newdis/images/cart/galka.png"> <? echo $product->getName(); ?> - <? echo $productInfo['quantity']; ?>шт., цена: <?= $productInfo['price'] ?> р. <br /><?
                endforeach;
                    ?></td>
            </tr>
        </table>

        <div style="clear: both;margin-bottom: 20px;"></div>

        <div align="left" style="" class="bold">
            <?php
            $page = PageTable::getInstance()->findOneById(91);
            //echo $page->getContent();
            ?>
            * Окончательную стоимость заказа с учетом всех скидок рассчитывает менеджер при подтверждении заказа.<br />
            ** Если вы сделали заказ поздно вечером или ночью, наш менеджер позвонит вам на следующий день после 10 утра (московское время) по указанному вами телефону для подтверждения заказа.
            <br /><br />
            <br />
            Статус заказа вы можете узнать в своем личном кабинете.<br />
            Для получения дополнительной информации о заказе вы можете связаться с менеджером в рабоче время (с 10 до 18 часов по московскому времени) по тел. <?=csSettings::get('phone1')?> (по Москве), <?=csSettings::get('phone2')?> (по России) или задать вопросы через он-лайн консультант.
            <br /><br />
            <br />
            Будем признательны, если вы оставите свои отзывы о приобретенных товарах на нашем сайте. Это поможет другим покупателям принять правильное решение при выборе товаров. А вам заработать дополнительные бонусы на свой бонусный счет.
            <br /><br />
            Ждем вас снова!



<?php include_component('cart', 'partner', array('orderId' => $order->getId(), 'price' => $TotalSumm, 'order' => $order)) ?>

        </div>				<nav class="pager">
            <ol>
            </ol>
        </nav></div>
</div>