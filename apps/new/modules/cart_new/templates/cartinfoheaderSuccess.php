<?php /* if ($productsCount > 0): ?>

  <a href="/cart" class="arrow"></a>
  <a href="/cart" class="card-btn"></a>
  <a href="/cart"><div class="text">    <div class="num"><?= $productsCount ?> товаров</div>
  <div class="price">на <?= $totalCost ?> р.</div>
  </div></a>
  <?php else: ?>
  <a class="arrow"></a>
  <a class="card-btn"></a>
  <a><div class="text" style="padding-top: 13px;">Корзина пуста
  </div></a>

  <?php endif; */ ?>
<?
$products_old = $sf_context->getUser()->getAttribute('products_to_cart');
$products_old = $products_old != '' ? unserialize($products_old) : '';
$totalCost = 0;
$productsCount = 0;
?>
<?php if (is_array($products_old) and count($products_old) > 0): ?>
    <? /* <script src="/js/liTip_newcat.js"></script> */ ?>
    <script>
        if (typeof ($.fn.liTip) === 'undefined' && !$("script").is("#liTipSRC")) {

            (function () {
                var lt = document.createElement('script');
                lt.type = 'text/javascript';
                lt.async = true;
                lt.src = '/js/liTip_newcat.js';
                lt.id = 'liTipSRC';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(lt, s);
            })();
        }
        $("#liTipSRC").load(function () {
            $('.card-box .card').liTip({
                timehide: 0/*1000*/,
                posY: 'PreShowCard',
                radius: '0px',
                maxWidth: '320px',
                content: $(".card-box .cardPreShow").html()
            });
        });
    </script>
    <div style="display: none;" class="cardPreShow"> 
        <div class="cardPreShowContent">
            <table class="cardPreShowTable">
                <?
                foreach ($products_old as $product) {

                    $productDB = ProductTable::getInstance()->findOneById($product['productId']);
                    $productsCountPreShow += $product["quantity"];
                    $photoalbum = $productDB->getPhotoalbums();
                    $photos = $photoalbum[0]->getPhotos();
                    ?>
                    <tr style="padding:20px;">
                        <td style="padding: 5px;width:60px;"><a href="/product/<?= $productDB->getSlug() ?>" style="display: block; border:1px solid #e0e0e0; width: 60px; height: 60px;cursor: pointer; text-align: center;display: inline-block;vertical-align: middle;"><img style="max-width: 60px; max-height: 60px;" border="0" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>"></a>
                        </td>
                        <td style="padding: 5px;"><a href="/product/<?= $productDB->getSlug() ?>"><?= $productDB->getName() ?></a>
                            <br /><br />
                            <div style="font-size: 11px; line-height: 12px;"> <? If ($productDB->getBonuspay() > 0) { ?>
                                    Управляй ценой: до <?= $productDB->getBonuspay() ?>% оплата бонусами
                                <? } elseif ($productDB->getDiscount() > 0) { ?>
                                    Лучшая цена: скидка <?= $productDB->getDiscount() ?>%
                                <? } ?>
                            </div>
                        </td>
                        <td style="width:120px;">
                            <?
                            If ($productDB->getBonuspay() > 0) {

                                $totalCostPreShow += number_format($productDB->getPrice() - $productDB->getPrice() * $productDB->getBonuspay() / 100, 0, '', ' ');
                                $bonusIsPay += round(number_format($productDB->getPrice() - $productDB->getPrice() * $productDB->getBonuspay() / 100, 0, '', ' ') * (($productDB->getBonus() > 0 ? $productDB->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                                ?>
                                <div style="float: left;font-size: 11px; color: #707070;width:25px; margin-right: 10px;">Цена:</div>
                                <div style="float: left;"><span style="font-size: 13px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= number_format($productDB->getPrice(), 0, '', ' ') ?> р.</span><br/><span style="font-size: 16px; margin-right: 10px;"><?= number_format($productDB->getPrice() - $productDB->getPrice() * $productDB->getBonuspay() / 100, 0, '', ' ') ?> р.</span></div>
                                <div style="clear:both;  height: 10px;"></div>
                                <div style="float: left;font-size: 11px; color: #707070;width:55px; margin-right: 5px; line-height: 12px;">Оплата бонусами:</div>
                                <div style="float: left;font-size: 13px; color: #414141;"><?= number_format($productDB->getPrice() * $productDB->getBonuspay() / 100, 0, '', ' ') ?></div>
                                <?
                            } elseif ($productDB->getDiscount() > 0) {

                                $totalCostPreShow += $productDB->getPrice();
                                $bonusIsPay += round($productDB->getPrice() * (($productDB->getBonus() > 0 ? $productDB->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                                ?>
                                <div style="float: left;font-size: 11px; color: #707070;width:25px; margin-right: 10px;">Цена:</div>
                                <div style="float: left;"><span style="font-size: 13px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= $productDB->getOldPrice() ?> р.</span><br/><span style="font-size: 16px;  margin-right: 10px;"><?= $productDB->getPrice() ?> р.</span></div>
                                <div style="clear:both;  height: 10px;"></div>
                                <div style="float: left;font-size: 11px; color: #707070;width:55px; margin-right: 5px;">Экономия:</div>
                                <div style="float: left;font-size: 13px; color: #414141;"><?= number_format($productDB->getOldPrice() - $productDB->getPrice(), 0, '', ' ') ?> р.</div>
                                <?
                            } else {

                                $totalCostPreShow += $productDB->getPrice();
                                $bonusIsPay += round(($productDB->getPrice() - $productDB->getPrice() * ($productDB->getBonuspay() > 0 ? $productDB->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($productDB->getBonus() > 0 ? $productDB->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                                ?>
                                <div style="float: left;font-size: 11px; color: #707070;width:25px; margin-right: 10px;">Цена:</div>
                                <div style="float: left;"><span style="font-size: 16px;  margin-right: 10px;"><?= $productDB->getPrice() ?> р.</span></div>
                                <div style="clear:both;  height: 10px;"></div>
                                <div style="float: left;font-size: 11px; color: #707070;width:55px; margin-right: 5px; line-height: 12px;">Бонусы за покупку:</div>
                                <div style="float: left;font-size: 13px; color: #414141;">
                                    <?=
                                    /* round($productDB->getPrice() * (($productDB->getBonus() > 0 ? $productDB->getBonus() : csSettings::get('persent_bonus_add')) / 100)) */
                                    round(($productDB->getPrice() - $productDB->getPrice() * ($productDB->getBonuspay() > 0 ? $productDB->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($productDB->getBonus() > 0 ? $productDB->getBonus() : csSettings::get('persent_bonus_add')) / 100))
                                    ?>

                                </div>

                            <? } ?></td>
                    </tr>
                <? } ?>
            </table>
            <div style="width: 90%; margin: auto; border-top: 1px dotted #c3060e;"></div>
            <?
            if ($sf_user->isAuthenticated()) {
                $bonus = BonusTable::getInstance()->findBy('user_id', $sf_user->getGuardUser()->getId());
                $bonusCount = 0;
                foreach ($bonus as $bonus) {
                    $bonusCount = $bonusCount + $bonus->getBonus();
                }
                ?>
                <div style="border: 1px solid #c3060e; max-width: 180px; height: 56px; margin-top: 20px;margin-left: -1px;float: left;">
                    <img style="right: -5px; position: relative; float: left;" src="/newdis/images/cart/surprice.png">
                    <div style="float: left; margin: 2px 5px 0; width: 117px;">Сейчас на вашем Бонусном счете <span style="color: #c3060e;"><?= $bonusCount ?></span> рублей.</div>
                </div>

            <? } else { ?>
                <div style="border: 0px solid #c3060e; width: 180px; height: 56px; margin-top: 20px;margin-left: -1px;float: left;">
                </div>
            <? } ?>
            <div style=" max-width: 200px;  margin-top: 20px;float: left;">
                <table class="cardPreShowTableSumm">
                    <tr>
                        <td>Количество товаров:</td>
                        <td style="text-align: right"><?= $productsCountPreShow ?></td>
                    </tr>
                    <tr>
                        <td>Бонусы за покупку:</td>
                        <td style="text-align: right"><?= $bonusIsPay ?></td>
                    </tr>
                    <tr>
                        <td>Стоимость:</td>
                        <td style="text-align: right"><span style="font-size: 16px; margin-right: 10px;"><?= $totalCostPreShow ?> р.</span></td>
                    </tr>
                </table> <br/>
                <a href="/cart" class="ButtonGoToCard"></a><br/>
            </div>
        </div>
    </div>
<? endif; ?>
<div class="title"><a href="<?= url_for('cart/') ?>">Моя корзина</a></div>
<div class="card">
    <?php
    if (is_array($products_old) and count($products_old) > 0):
        $totalCost = $totalCostPreShow;
        $productsCount = $productsCountPreShow;
        /* foreach ($products_old as $product) {
          $productsCount += $product["quantity"];
          $totalCost += ($product["quantity"] * ($product['price_w_discount'] > 0 ? $product['price_w_discount'] : $product['price']));
          } */
        ?>
        <a href="<?= url_for('cart/') ?>" class="arrow"></a>
        <a href="<?= url_for('cart/') ?>" class="card-btn"></a>
        <a href="<?= url_for('cart/') ?>"><div class="text">    <div class="num"><?= $productsCount ?> товаров</div>
                <div class="price">на <?= $totalCost ?> р.</div>
            </div></a>
    <? else: ?>
        <a class="arrow"></a>
        <a class="card-btn"></a>
        <a><div class="text" style="padding-top: 13px;">Корзина пуста
            </div></a>

    <? endif; ?>
</div>