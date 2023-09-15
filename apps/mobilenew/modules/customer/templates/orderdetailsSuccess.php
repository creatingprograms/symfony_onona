<?php if ($order): ?>
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
    <div align="left" style="padding:5px;">
        <font style="font-size: 16px;">Номер заказа: <span style="color: #C3060E;"><?= $order->getPrefix() ?><?= $order->getId() ?></span></font>
        <div style="clear: both;margin-bottom: 20px;"></div>
        <div style="border: 1px solid #C3060E;width: 350px;"><div style="float: left;width: 140px;background-color: #f5f5f5;padding: 10px;">
                Способ доставки:<br />
                Стоимость доставки:<br />
                Форма оплаты:<br />
                Общая сумма заказа:<br />
                Состояние заказа:<br /></div>
            <div style="padding: 10px;margin-left: 170px;"><?= $order->getDelivery() ?><br />
                <?= $order->getDeliveryPrice() ?><br />
                <?= $order->getPayment() ?><br />
                <?= $TotalSumm ?><br />
                <?= $order->getStatus() ?></div></td>
        </div>
        <div style="clear: both;margin-bottom: 20px;"></div>
        <table width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000" border="0" align="center" class="cartContent">
            <thead><tr>
                    <th>Наименование</th>
                    <th style=" width: 111px;">Цена, руб.</th>
                    <th style=" width: 88px;">Скидка, %</th>
                    <th style=" width: 110px;">Кол-во</th>
                    <th style=" width: 108px;">Сумма, руб.</th>
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
                                <td style="text-align: left;"><?php if (isset($photos[0])): ?>
                                        <div style="float:left;margin: -10px 10px -10px 0"> <a href="/product/<?= $product->getSlug() ?>"><img width="70" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>"></a></div>
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
                    <td style="text-align: left;height: 30px;"><b>Итого</b>
                    </td>
                    <td style="height: 30px;"></td>
                    <td style="height: 30px;"></td>
                    <td style="height: 30px;">

                    </td>
                    <td style="height: 30px;"><?= $TotalSumm ?></td>
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
    Такого заказа нету.<br /><br />
<?php endif; ?>

