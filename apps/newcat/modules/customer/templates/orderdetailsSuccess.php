<?php if ($order): ?>
    <ul class="breadcrumbs">
        <li>
            <a href="/">Главная</a>
        </li>
        <li>
            <a href="/lk">личный кабинет</a>
        </li>
        <li>
            <a href="/customer/myorders">мои заказы</a>
        </li>
        <li>
            Заказ №<?= $order->getPrefix() ?><?= $order->getId() ?>
        </li>
    </ul>
    <?php
    $TotalSumm = 0;
    $bonusAddUser = 0;
    $products_old = $order->getText() != '' ? unserialize($order->getText()) : '';
    foreach ($products_old as $key => $productInfo):
        if (isset($productInfo['article'])) {
            $product = ProductTable::getInstance()->findOneByCode($productInfo['article']);
        }
        if (isset($productInfo['productId']) and !$product) {
            $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
        }
        $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']));
        if ($product) {
            if ($product->getBonus() != 0) {
                $bonusAddUser = $bonusAddUser + round((($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) * $productInfo['quantity'] * $product->getBonus()) / 100);
            } else {
                $bonusAddUser = $bonusAddUser + round((($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) * $productInfo['quantity'] * csSettings::get("persent_bonus_add")) / 100);
            }
        }
    endforeach;
    ?>
    <div class="my-order-details">
      <div class="header">Номер заказа:
        <span><?= $order->getPrefix() ?><?= $order->getId() ?></span>
      </div>
      <div style="clear: both;margin-bottom: 20px;"></div>
      <div class="container">
        <div class="left">
          Способ доставки:<br />
          Стоимость доставки:<br />
          Форма оплаты:<br />
          Общая сумма заказа:<br />
          Состояние заказа:<br />
        </div>
          <div class="right">
            <?= $order->getDelivery() ?><br />
            <?= $order->getDeliveryPrice() ?><br />
            <?= $order->getPayment() ?><br />
            <?= $TotalSumm ?><br />
            <?= $order->getStatus() ?>
          </div>
      </div>
        <table class="cartContent">
          <thead>
            <tr>
              <th>Наименование</th>
              <th>Цена, руб.</th>
              <th>Скидка, %</th>
              <th>Кол-во</th>
              <th>Сумма, руб.</th>
            </tr>
          </thead>
          <tbody>
                <?php
                // $TotalSumm = 0;
                $products_old = $order->getText() != '' ? unserialize($order->getText()) : '';
                foreach ($products_old as $key => $productInfo):
                    if (isset($productInfo['article']))
                        $product = ProductTable::getInstance()->findOneByCode($productInfo['article']);
                    else
                        $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    // $product = ProductTable::getInstance()->findOneByCode($productInfo['article']);
                    if ($product) {
                        if ($product->getSlug() != "dostavka") {
                            // $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']));

                            $photoalbum = $product->getPhotoalbums();
                            $photos = $photoalbum[0]->getPhotos();
                            ?>

                            <tr>
                                <td>
                                  <?php if (isset($photos[0])): ?>
                                        <div>
                                          <a href="/product/<?= $product->getSlug() ?>">
                                            <img src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>"></a>
                                        </div>
                                    <?php endif; ?>

                                    <a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a>
                                </td>
                                <td><?= $productInfo['price'] ?></td>
                                <td><?= ($productInfo['price_w_discount'] > 0 ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0') ?></td>
                                <td>
                                    <?= $productInfo['quantity'] ?>

                                </td>
                                <td><div style="display: inline-block;" id="totalcost_<?= $productInfo['productId'] ?>"><?= $productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?></div></td>
                            </tr>

                            <?php
                        }
                    }
                endforeach;
                ?> <tr>
                    <td>
                      <strong>Итого</strong>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?= $TotalSumm ?></td>
                </tr>
            </tbody></table>
        <div style="clear: both;margin-bottom: 20px;"></div>
        <span style="color: #c3060e;">*</span> За этот заказ вам будут начислены <span style="color: #c3060e;"><?= $bonusAddUser ?></span> бонусных рублей.<br /><br />
        <a href="/programma-on-i-ona-bonus">Подробнее об условиях программы «Он и Она – Бонус»</a>
        <? /*
          <div style="padding:10px 0px 0px 5px" class="dark_blue bold fs12 pl5"><b>Доставка</b></div>
          <div style="padding:0px 0px 0px 5px"><?= $order->getDelivery()->getName() ?></div>

          <div style="padding:10px 0px 0px 5px" class="dark_blue bold fs12 pl5"><b>Оплата</b></div>
          <div style="padding:0px 0px 0px 5px"><?= $order->getPayment()->getName() ?></div>

          <div style="padding:10px 0px 0px 5px" class="dark_blue bold fs12 pl5"><b>Комментарий</b></div>
          <div style="padding:0px 0px 0px 5px"><?= $order->getComments() ?></div>

          <div style="padding:10px 0px 0px 5px" class="dark_blue bold fs12 pl5"><b>Купон на скидку</b></div>
          <div style="padding:0px 0px 0px 5px"><?= $order->getCoupon() ?></div> */ ?>
    </div>
<?php else: ?>
    Такого заказа нет.<br /><br />
<?php endif; ?>
