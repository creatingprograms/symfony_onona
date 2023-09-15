
<?php
  $productsToCart = $sf_context->getUser()->getAttribute('products_to_cart');
  $productsToCart = $productsToCart != '' ? unserialize($productsToCart) : '';
  /*
    if ($sf_user->isAuthenticated()):

      if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == 30419) {
        echo '<div style="position: absolute; top: 0; left: 0; heught: 100%; width: 100%; background: #ccc; overflow: auto;">'.
          '<pre>'.print_r($productsToCart,true).'</pre>'.
        '</div>';
      }
    endif;
  */
?>
<div class="card-box">
    <?
    $totalCost = 0;
    $productsCount = 0;
    $totalCostPreShow = 0;
    $productsCountPreShow = 0;
    $bonusIsPay = 0;
    ?>
    <?php
    if (is_array($productsToCart) and count($productsToCart) > 0):

        $timerProducts = sfTimerManager::getTimer('Корзина в шапке: Загрузка загрузка товаров из базы в корзине');
        $productsDB = $q->execute("SELECT * from product as p WHERE id in (" . implode(",", array_keys($productsToCart)) . ")", array())->fetchAll(Doctrine_Core::FETCH_UNIQUE);

        $timerProducts->addTime();

        $timerPhotos = sfTimerManager::getTimer('Корзина в шапке: Загрузка фото для всех товаров в корзине');
        $photosAllCart = $q->execute("SELECT ppa.product_id,photo.filename as filename from photo "
                        . "left join product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                        . "WHERE ppa.product_id in (" . implode(",", array_keys($productsToCart)) . ") "
                        . "ORDER BY photo.position DESC")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerPhotos->addTime();
        ?>
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
                    foreach ($productsToCart as $product) {
                        $productsCountPreShow += $product["quantity"];
                        ?>
                        <tr style="padding:20px;">
                            <td style="padding: 5px;width:60px;"><a href="/product/<?= $productsDB[$product['productId']]['slug'] ?>" style="display: block; border:1px solid #e0e0e0; width: 60px; height: 60px;cursor: pointer; text-align: center;display: inline-block;vertical-align: middle;"><img style="max-width: 60px; max-height: 60px;" border="0" src="/uploads/photo/thumbnails_250x250/<?= $photosAllCart[$product['productId']]['filename'] ?>"></a>
                            </td>
                            <td style="padding: 5px;"><a href="/product/<?= $productsDB[$product['productId']]['slug'] ?>"><?= $productsDB[$product['productId']]['name'] ?></a>
                                <br /><br />
                                <div style="font-size: 11px; line-height: 12px;"> <? If ($productsDB[$product['productId']]['bonuspay'] > 0) { ?>
                                        Управляй ценой: до <?= $productsDB[$product['productId']]['bonuspay'] ?>% оплата бонусами
                                    <? } elseif ($productsDB[$product['productId']]['discount'] > 0) { ?>
                                        Лучшая цена: скидка <?= $productsDB[$product['productId']]['discount'] ?>%
                                    <? } ?>
                                </div>
                            </td>
                            <td style="width:120px;">
                                <?
                                If ($productsDB[$product['productId']]['bonuspay'] > 0) {

                                    $totalCostPreShow = $totalCostPreShow + ($productsDB[$product['productId']]['price'] - ($productsDB[$product['productId']]['price'] * $productsDB[$product['productId']]['bonuspay'] / 100));
                                    $bonusIsPay += round(number_format(($productsDB[$product['productId']]['price'] - ($productsDB[$product['productId']]['price'] * $productsDB[$product['productId']]['bonuspay'] / 100)), 0, '', ' ') * (($productsDB[$product['productId']]['bonus'] > 0 ? $productsDB[$product['productId']]['bonus'] : csSettings::get('persent_bonus_add')) / 100));
                                    ?>
                                    <div style="float: left;font-size: 11px; color: #707070;width:25px; margin-right: 10px;">Цена:</div>
                                    <div style="float: left;"><span style="font-size: 13px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= number_format($productsDB[$product['productId']]['price'], 0, '', ' ') ?> р.</span><br/><span style="font-size: 16px; margin-right: 10px;"><?= number_format($productsDB[$product['productId']]['price'] - $productsDB[$product['productId']]['price'] * $productsDB[$product['productId']]['bonuspay'] / 100, 0, '', ' ') ?> р.</span></div>
                                    <div style="clear:both;  height: 10px;"></div>
                                    <div style="float: left;font-size: 11px; color: #707070;width:55px; margin-right: 5px; line-height: 12px;">Оплата бонусами:</div>
                                    <div style="float: left;font-size: 13px; color: #414141;"><?= number_format($productsDB[$product['productId']]['price'] * $productsDB[$product['productId']]['bonuspay'] / 100, 0, '', ' ') ?></div>
                                    <?
                                } elseif ($productsDB[$product['productId']]['discount'] > 0) {

                                    $totalCostPreShow = $totalCostPreShow + $productsDB[$product['productId']]['price'];
                                    $bonusIsPay += round($productsDB[$product['productId']]['price'] * (($productsDB[$product['productId']]['bonus'] > 0 ? $productsDB[$product['productId']]['bonus'] : csSettings::get('persent_bonus_add')) / 100));
                                    ?>
                                    <div style="float: left;font-size: 11px; color: #707070;width:25px; margin-right: 10px;">Цена:</div>
                                    <div style="float: left;"><span style="font-size: 13px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= $productsDB[$product['productId']]['old_price'] ?> р.</span><br/><span style="font-size: 16px;  margin-right: 10px;"><?= $productsDB[$product['productId']]['price'] ?> р.</span></div>
                                    <div style="clear:both;  height: 10px;"></div>
                                    <div style="float: left;font-size: 11px; color: #707070;width:55px; margin-right: 5px;">Экономия:</div>
                                    <div style="float: left;font-size: 13px; color: #414141;"><?= number_format($productsDB[$product['productId']]['old_price'] - $productsDB[$product['productId']]['price'], 0, '', ' ') ?> р.</div>
                                    <?
                                } else {

                                    $totalCostPreShow = $totalCostPreShow + $productsDB[$product['productId']]['price'];
                                    $bonusIsPay += round(($productsDB[$product['productId']]['price'] - $productsDB[$product['productId']]['price'] * ($productsDB[$product['productId']]['bonuspay'] > 0 ? $productsDB[$product['productId']]['bonuspay'] : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($productsDB[$product['productId']]['bonus'] > 0 ? $productsDB[$product['productId']]['bonus'] : csSettings::get('persent_bonus_add')) / 100));
                                    ?>
                                    <div style="float: left;font-size: 11px; color: #707070;width:25px; margin-right: 10px;">Цена:</div>
                                    <div style="float: left;"><span style="font-size: 16px;  margin-right: 10px;"><?= $productsDB[$product['productId']]['price'] ?> р.</span></div>
                                    <div style="clear:both;  height: 10px;"></div>
                                    <div style="float: left;font-size: 11px; color: #707070;width:55px; margin-right: 5px; line-height: 12px;">Бонусы за покупку:</div>
                                    <div style="float: left;font-size: 13px; color: #414141;">
                                        <?=
                                        /* round($productsDB[$product['productId']]['price'] * (($productsDB[$product['productId']]['bonus'] > 0 ? $productsDB[$product['productId']]['bonus'] : csSettings::get('persent_bonus_add')) / 100)) */
                                        round(($productsDB[$product['productId']]['price'] - $productsDB[$product['productId']]['price'] * ($productsDB[$product['productId']]['bonuspay'] > 0 ? $productsDB[$product['productId']]['bonuspay'] : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($productsDB[$product['productId']]['bonus'] > 0 ? $productsDB[$product['productId']]['bonus'] : csSettings::get('persent_bonus_add')) / 100))
                                        ?>

                                    </div>

                                <? } ?></td>
                        </tr>
                    <? } ?>
                </table>
                <div style="width: 90%; margin: auto; border-top: 1px dotted #c3060e;"></div>
                <?
                if ($sf_user->isAuthenticated()) {

                    $timerBonus = sfTimerManager::getTimer('Корзина в шапке: Подсчет бонусов');

                    $bonusCount = $q->execute("SELECT sum(bonus) FROM `bonus` where user_id=?", array($sf_user->getGuardUser()->getId()))->fetch(Doctrine_Core::FETCH_COLUMN);

                    $timerBonus->addTime();
                    ?>
                    <a href="/programma-on-i-ona-bonus">
                        <div style="border: 1px solid #c3060e; max-width: 180px; height: 56px; margin-top: 20px;margin-left: -1px;float: left;">
                            <img style="right: -5px; position: relative; float: left;" src="/newdis/images/cart/surprice.png">
                            <div style="float: left; margin: 2px 5px 0; width: 117px;">Сейчас на вашем Бонусном счете <span style="color: #c3060e;"><?= $bonusCount ?></span> рублей.</div>
                        </div>
                    </a>
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
        if (is_array($productsToCart) and count($productsToCart) > 0):
            $totalCost = $totalCostPreShow;
            $productsCount = $productsCountPreShow;

            slot('totalCost', $totalCost);
            slot('productsCount', $productsCount);
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
            <a><div class="text" style="padding-top: 7px;">Корзина пуста
                </div></a>

        <? endif; ?>
    </div>
</div>
