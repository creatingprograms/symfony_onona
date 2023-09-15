<?php
$products_to_cart = $sf_user->getAttribute('products_to_cart');
$products_to_cart = $products_to_cart != '' ? unserialize($products_to_cart) : '';

$products_desire = $sf_user->getAttribute('products_to_desire');
$products_desire = $products_desire != '' ? unserialize($products_desire) : '';

$products_compare = $sf_user->getAttribute('products_to_compare');
$products_compare = $products_compare != '' ? unserialize($products_compare) : '';


$timerDopInfo = sfTimerManager::getTimer('_productBooklet: Загрузка доп характеристик товаров');
$q = Doctrine_Manager::getInstance()->getCurrentConnection();
if (count($childrens) > 1) {
    $allProductIdChildrens = array();
    foreach ($childrens as $children) {
        $allProductIdChildrens[] = $children['id'];
    }
    $result = $q->execute("select *, dpс.name as dpc_name from (SELECT*,count(dop_info_id) as count FROM dop_info_product where product_id in (" . implode(",", $allProductIdChildrens) . ") group by dop_info_id) as params left join dop_info as dp on dp.id=params.dop_info_id left join dop_info_category as dpс on dpс.id=dp.dicategory_id where params.count <" . (count($allProductIdChildrens)));
    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $childrensDopInfo = $result->fetchAll();
    //print_r($childrensDopInfo);
} else {
    $childrensDopInfo = array();
}
foreach ($childrensDopInfo as $dopInfo) {

    $tempArray['name'] = $dopInfo['name'] != "" ? $dopInfo['name'] : $dopInfo['dpc_name'];
    $tempArray['value'] = $dopInfo['value'];
    $childrenDopInfo[$dopInfo['product_id']][] = $tempArray;
    unset($tempArray);
}
$timerDopInfo->addTime();
?>
<div class="c">
    <div class="content<?php if ($product['count'] == 0): ?> notcount" style="opacity: 0.3;"<?php else: ?>"<?php endif; ?>>
        <? if ($product['bonuspay'] > 0) { ?>
            <div style="position: absolute; left: 0px; top: 60px;z-index: 10; cursor:pointer;" onClick="$('.liTipContent .bonusDiscountText_<?= $product['id'] ?>').each(function (index) {
                        $(this).toggle();
                    });">
                <img src="/images/bonDisc/podarok.png" alt="Управляй ценой">
                <div style='    position: absolute; /* Абсолютное позиционирование */
                     left: 35px; top: 2px; /* Положение подсказки */
                     z-index: 1; /* Отображаем подсказку поверх других элементов */
                     background: #fff; /* Полупрозрачный цвет фона */
                     font-family: Arial, sans-serif; /* Гарнитура шрифта */
                     font-size: 13px; /* Размер текста подсказки */
                     padding: 5px 10px; /* Поля */
                     border: 1px solid #c4040d; /* Параметры рамки */
                     width: 150px;
                     -webkit-border-radius: 5px;
                     -moz-border-radius: 5px;
                     color: #000;
                     border-color: #c4040d;display: none;' class='bonusDiscountText_<?= $product['id'] ?>'>
                    <div style="text-align: center;margin-bottom: 10px;"><span style="color: #c4040d;
                                                                               font-weight: bold;">УПРАВЛЯЙ ЦЕНОЙ!</span></div>
                    <div style="margin-bottom: 10px;">Оплата накопленными<br />
                        Бонусами до <?= $product['bonuspay'] > 0 ? $product['bonuspay'] : csSettings::get('PERSENT_BONUS_PAY') ?>% от<br />
                        стоимости товара.</div>
                    <a href="/programma-on-i-ona-bonus" target="_blank">Условия программы ></a>
                </div>

            </div>

            <?
            echo '<span class="sale' . $product['bonuspay'] . '">-' . $product['bonuspay'] . '%</span>';
        } elseif ($product['discount'] > 0) {
            ?>
            <div style="position: absolute; left: 0px; top: 60px;z-index: 10;" class="loveprice" data-title="Лучшая цена в Рунете!"><a href="/category/Luchshaya_tsena"><img src="/newdis/images/loveprice.png" alt="Лучшая цена"></a></div>
            <?
        } else {
            echo strtotime($product['created_at']) > time() - (csSettings::get('logo_new') * 24 * 60 * 60) ? '<span class="newProduct"></span>' : '';
        }
        ?>
        <span class="productInDesire-<?= $product['id'] ?> productInDesire"<?= @in_array($product['id'], $products_desire) ? ' style="display: block;"' : '' ?>></span>
        <span class="productInCompare-<?= $product['id'] ?> productInCompare"<?= @in_array($product['id'], $products_compare) ? ' style="display: block;"' : '' ?>></span>
        <span class="productInCart-<?= $product['id'] ?> productInCart"<?= @is_array($products_to_cart[$product['id']]) ? ' style="display: block;"' : '' ?>></span>
        <?= $product['discount'] > 0 ? '<span class="sale' . $product['discount'] . '">-' . $product['discount'] . '%</span>' : ''; ?>

        <?= ($product['video'] != '' or $product['videoenabled'] ) ? '<span class="videoProduct" title="К этому товару есть видео-презентация"></span>' : ''; ?>

        <div class="title"><a href="/product/<?= $product['slug'] ?>"><? mb_internal_encoding('UTF-8'); ?><?= mb_substr($product['name'], 0, 55) ?></a></div>
        <div class="img-holder">
            <a href="/product/<?= $product['slug'] ?>">
                <img <?php if($autoLoadPhoto){echo 'src="/uploads/photo/thumbnails_250x250/'. $photo['filename'].'"';}else{echo 'src="/images/pixel.gif" data-original="/uploads/photo/thumbnails_250x250/'. $photo['filename'].'"';}?> alt='<?= str_replace(array("'", '"'), "", $product['name']) ?>' title='<?= str_replace(array("'", '"'), "", $product['name']) ?>' class="thumbnails" />

            </a>
            <? if ($product['endaction'] != ""): ?>
                <div class="countdown">
                    <script>
                        if (typeof ($.fn.countdown) === 'undefined' && !$("script").is("#countdownSRC")) {

                            (function () {
                                var lt = document.createElement('script');
                                lt.type = 'text/javascript';
                                lt.async = true;
                                lt.src = '/newdis/js/jquery.countdown.js';
                                lt.id = 'countdownSRC';
                                var s = document.getElementsByTagName('script')[0];
                                s.parentNode.insertBefore(lt, s);
                            })();
                        }
                    </script>
                    <a class="buy">Успейте купить!</a>
                    <span class="time actionProduct_<?= $product['id'] ?>"></span>
                    <?php
                    $timer = sfTimerManager::getTimer('_productBooklet: Расчет времени Успей купить');
                    $step = array("1 сутки" => "1", "2 суток" => "2", "3 суток" => "3", "4 суток" => "4", "5 суток" => "5");
                    if (($product['step'] != "") and ( (strtotime($product['endaction']) - time() + 24 * 60 * 60) > $step[$product['step']] * 24 * 60 * 60)) {
                        $dateEnd = date("Y, m-1, d", (strtotime($product['endaction']) - floor((strtotime($product['endaction']) - time() + 24 * 60 * 60) / ($step[$product['step']] * 24 * 60 * 60)) * $step[$product['step']] * 24 * 60 * 60));
                    } else {
                        $dateEnd = date("Y, m-1, d", strtotime($product['endaction']));
                    }
                    $timer->addTime();
                    ?>
                    <script type="text/javascript">
                        $("#countdownSRC").load(function () {
                            $('.countdown .actionProduct_<?= $product['id'] ?>').countdown({
                                //format: 'd hh:mm:ss',
                                format: 'hh:mm:ss',
                                compact: true,
                                description: '',
                                until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                        //timezone: +6
                            });
                        });
                        if (typeof ($.fn.countdown) !== 'undefined') {
                            $('.countdown .actionProduct_<?= $product['id'] ?>').countdown({
                                //format: 'd hh:mm:ss',
                                format: 'hh:mm:ss',
                                compact: true,
                                description: '',
                                until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                        //timezone: +6
                            });
                        }</script>
                </div> 
                <? Endif; ?>
        </div>
        <div class="bottom-box">
            <div class="row">
                <? if ($comment['countcomm'] > 0) { ?><a href="/product/<?= $product['slug'] ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $comment['countcomm'] ?></a><? } ?>
                <div class="stars">
                    <span style="width:<?= $product['rating'] > 0 ? (@round($product['rating'] / $product['votes_count'])) * 10 : 0 ?>%;"></span>
                </div>
            </div>
            <div class="price-box">
                <?php if ($product['old_price'] > 0) { ?>
                    <span class="old-price"><?= number_format($product['old_price'], 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                    <span class="new-price"><?= number_format($product['price'], 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                <? } elseif ($product['bonuspay'] > 0) { ?>
                    <span class="old-price"><?= number_format($product['price'], 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                    <span class="new-price"><?= number_format($product['price'] - $product['price'] * $product['bonuspay'] / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                <? } else { ?>
                    <span class="price"><?= number_format($product['price'], 0, '', ' ') ?>  <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                <? } ?>
            </div>

            <div class="bonus-box-price"><?php
                $timer = sfTimerManager::getTimer('_productBooklet: Блок сколько будет начислено бонусов');
                if ($product['bonuspay'] > 0) {
                    $bonusAddUser = round(($product['price'] - $product['price'] * ($product['bonuspay'] / 100)) * (($product['bonus'] > 0 ? $product['bonus'] : csSettings::get('persent_bonus_add')) / 100));
                    if ($bonusAddUser > 0) {
                        ?>+<?= $bonusAddUser ?> бонусов<?
                    }
                } else {
                    ?>+<?= round($product['price'] * (($product['bonus'] > 0 ? $product['bonus'] : csSettings::get('persent_bonus_add')) / 100)) ?> бонусов
                    <?
                }
                $timer->addTime();
                ?>
            </div>
        </div>
    </div>
</div>

<div class="b"></div>
<div class="preShowProduct-<?= $product['id'] ?>">
    <div  class="preShowProduct">
        <ul class="item-list" style="padding: 0px;"><li style="border:0px;padding: 0px;">
                <div class="c">
                    <div class="content contentPreShowProduct-<?= $product['id'] ?><?php if ($product['count'] == 0): ?> notcount<?php endif; ?>"<?php if ($product['count'] == 0): ?> style="opacity: 0.3;"<?php endif; ?>>
                        <?
                        include_partial("product/changechildren", [
                            'sf_cache_key' => $product['id'],
                            'products' => $products,
                            'product' => $product,
                            'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
                            'photo' => $photosAll[$product['id']]
                                ]
                        );
                        ?>
                    </div>
                    <div style="float: left; position: relative; margin-left: 250px;  max-height: 420px; /*overflow-y: scroll*/">

                        <?
                        foreach ($childrens as $numChildren => $children) {
                            ?><div class="childrenBottom childrenBottom-<?= $product['id'] ?><?= ($numChildren == 0 ? " activeChildrenBottom" : "") ?>" onclick='$(".childrenBottom-<?= $product['id'] ?>").each(function (i) {
                                            $(this).removeClass("activeChildrenBottom")
                                        });
                                        $(this).addClass("activeChildrenBottom");
                                        $(".contentPreShowProduct-<?= $product['id'] ?>").each(function (i) {
                                            $(this).load("/product/changechildren/<?= $children['id'] ?>", {productsKeys: "<?= implode(",", array_keys($products)) ?>", photo: "<?= $photosAll[$children['id']]['filename'] ?>", comments: "<?= (isset($commentsAll[$children['id']]) ? $commentsAll[$children['id']]['countcomm'] : 0) ?>"},
                                                    function () {
                                                        $(this).find("img.thumbnails").each(function () {
                                                            $(this).attr("src", $(this).attr("data-original"));
                                                        });
                                                    });
                                        });'><img src="/uploads/photo/thumbnails_60x60/<?= $photosAll[$children['id']]['filename'] ?>" style="max-width: 60px; max-height: 60px;" alt="<?= str_replace(array("'", '"'), "", $children['name']) ?>" />
                                <?
                                if (isset($childrenDopInfo)) {
                                    if (count($childrenDopInfo[$children['id']]) > 0) {
                                        ?><span><?
                                            foreach ($childrenDopInfo[$children['id']] as $dopinfo) {
                                                echo $dopinfo['name'] . ": " . $dopinfo['value'] . "";
                                            }
                                            ?></span><?
                                    }
                                }
                                ?></div>
                                <?
                            }
                            ?>
                    </div>
                </div></li></ul>
    </div>
</div>

        <?php
        If (sfContext::getInstance()->getUser()->hasPermission('Show article')) {
            echo $product['code'];
        }
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
        $('li.prodTable-<?= $product['id'] ?>').each(function (index) {
            $(this).liTip({
                timehide: 0/*1000*/,
                posY: 'productShowItems',
                radius: '0px',
                maxWidth: '320px',
                content: $(".preShowProduct-<?= $product['id'] ?> .preShowProduct").html()
            });
        });
    });

    if (typeof ($.fn.liTip) !== 'undefined') {

        $('li.prodTable-<?= $product['id'] ?>').each(function (index) {
            $(this).liTip({
                timehide: 0/*1000*/,
                posY: 'productShowItems',
                radius: '0px',
                maxWidth: '320px',
                content: $(".preShowProduct-<?= $product['id'] ?> .preShowProduct").html()
            });
        });
    }
</script>







