<?
global $isTest;
$h1='Спасибо за заказ';
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

      <h1><?= $customer->getCity() ?></h1>
      <div class="lk-detail-order-header">Ваш заказ поступил в обработку. Номер заказа: <span  id="order-id"><?= $order->getPrefix() ?><?= $order->getId() ?></span></div>
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
                ?>

                  <tr
                    class="gtm-thankyou-item"
                    data-index="<?=$index++?>"
                    data-quant="<?=$productInfo['quantity']?>"
                    data-name="<?=$product->getName()?>"
                    data-id="<?=$product->getId()?>"
                    data-price="<?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?>"
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
                    <td class="basket-price" data-mobtext="Цена, руб."><?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?></td>
                    <td class="basket-numb" data-mobtext="Кол-во"> <?= $productInfo['quantity']; ?> </td>

                  </tr>
                <? endif;
            endforeach;
          ?>
        </tbody>
      </table>

      <? if(!is_object($paymentDoc) && is_object($pay)) if ($pay->getId()==57)
          include_component("cart_new", "mkbform", array(
            'order'=> $order,
            'customer' => $customer,
          ));
      ?>
    </div>
  </div>
</main>
