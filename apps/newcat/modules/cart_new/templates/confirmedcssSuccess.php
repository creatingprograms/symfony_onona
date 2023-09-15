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
    <script type="text/javascript">function readCookie(name) {if (document.cookie.length > 0) { offset = document.cookie.indexOf(name + "="); if (offset != -1) { offset = offset + name.length + 1; tail = document.cookie.indexOf(";", offset); if (tail == -1) tail = document.cookie.length; return unescape(document.cookie.substring(offset, tail)); } } return null; } var url='https://track.leadhit.io/stat/lead_form?f_orderid=';

        //Вместо {{order_id}} должен подставляться номер заказа.

        url += "<?=$order->getId()?>";



        url += '&url='; url += encodeURIComponent(window.location.href); url += '&action=lh_orderid'; url += '&uid=' + readCookie('_lhtm_u'); url += '&vid='; url += readCookie('_lhtm_r').split('|')[1]; url += '&ref=direct&f_cart_sum=&clid='+'5604fd6fbbddbd5e6a27efb2'; var sc = document.createElement("script"); sc.type = 'text/javascript'; var headID = document.getElementsByTagName("head")[0]; sc.src = url; headID.appendChild(sc);
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
