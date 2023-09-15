<?
$h1='Заказ №: '.$order->getPrefix().$order->getId();
slot('breadcrumbs', [
  ['text' => 'Личный кабинет', 'link'=>'/lk'],
  ['text' => 'Мои заказы', 'link'=>'/customer/myorders'],
  ['text' => $h1],
]);
// slot('h1', $h1);
// slot('catalog-class', '-innerWhite');
?>
<main class="wrapper">
  <div class="lk-detail-order">
    <div class="lk-detail-order-white">
      <div class="lk-detail-order-header">Номер заказа: <span><?= $order->getPrefix().$order->getId() ?></span></div>
      <div class="lk-detail-order-summary">
        <div class="row">
          <div class="header first">Способ доставки:</div><div class="detail first"><?= $order->getDelivery() ?></div>
        </div>
        <div class="row">
          <div class="header">Стоимость доставки:</div><div class="detail"><?= $order->getDeliveryPrice() ? $order->getDeliveryPrice() : 'Бесплатно' ?></div>
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
        <? if(is_object($pay)) if ($pay->getId()==59):?>
          <div class="row">
            <div class="header">Статус оплаты:</div>
            <div class="detail">
              <span style="color: <?= is_object($paymentDoc) ? 'green;">оплачен':'red;">требуется оплата' ?></span>
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
          <div class="header">Общая сумма заказа:</div><div class="detail"><?= $TotalSumm - $order->getBonuspay()  ?></div>
        </div>
        <div class="row">
          <div class="header last">Состояние заказа:</div><div class="detail last"><?= $order->getStatus() ?></div>
        </div>
      </div>
      <table class="basket">
        <thead>
          <tr>
            <th></th>
            <th class="size-name">Наименование</th>
            <th>Цена, руб.</th>
            <th>Скидка, %</th>
            <th>Кол-во</th>
            <th>Сумма, руб.</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($products_old as $key => $productInfo):
          if ($products[$key]->getSlug() != "dostavka") {
            $photoalbum = $products[$key]->getPhotoalbums();
            $photos = $photoalbum[0]->getPhotos();
            ?>
            <tr>
                <td class="basket-pic">
                  <?php if (isset($photos[0])): ?>
                    <a href="/product/<?= $products[$key]->getSlug() ?>">
                      <img src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>">
                    </a>
                  <?php endif; ?>

                </td>
                <td class="basket-name -leftAlign"><a href="/product/<?= $products[$key]->getSlug() ?>"><?= $products[$key]->getName() ?></a></td>
                <td class="basket-price" data-mobtext="Цена, руб."><?= $productInfo['price'] ?></td>
                <td class="basket-bonus" data-mobtext="Скидка"><?= (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0') ?></td>
                <td class="basket-numb" data-mobtext="Кол-во"> <?= $productInfo['quantity'] ?> </td>
                <td class="basket-sum" data-mobtext="Сумма"><div id="totalcost_<?= $productInfo['productId'] ?>"><?= $productInfo['quantity'] * (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']) ?></div></td>
            </tr>

            <?php }
          endforeach; ?>
            <tr>
                <td class="total-header">Итого</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="total basket-sum"><?= $TotalSumm ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <? if(!is_object($paymentDoc) && is_object($pay)) if ($pay->getId()==59)
        include_component("cart_new", "yookassaform", array(
          'order'=> $order,
          // 'customer' => $customer,
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
    <div class="lk-detail-order-usual">
      <div class="lk-detail-order-bonus">
        За этот заказ вам будут начислены:<div class="lk-detail-order-bonus-sum"><?= $bonusAddUser ?></div>
      </div>
      <a class="lk-detail-order-bonus-link" href="/programma-on-i-ona-bonus">Подробнее об условиях программы «Он и Она – Бонус»</a>
    </div>
  </div>
</main>
