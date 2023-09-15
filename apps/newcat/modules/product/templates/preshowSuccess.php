<?
$photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
$photosUser = PhotosUserTable::getInstance()->createQuery()->where("product_id=" . $product->getId())->addWhere("is_public = '1'")->execute();
if ($product->getParent() != "")
    $productProp = $product->getParent();
else
    $productProp = $product;
$i = 0;
$childrens = $productProp->getChildren();
$childrensId = $childrens->getPrimaryKeys();
$childrensId[] = $productProp->getId();
//print_r()

$comments = Doctrine_Core::getTable('Comments')
        ->createQuery('c')
        ->where("is_public = '1'")
        ->addWhere('product_id in (' . implode(',', $childrensId) . ')')
        ->orderBy('created_at desc')
        ->execute();
$count_comm = $comments->count();
?>
<div style="
     margin: 0 auto;
     position: relative;
     width: 1000px;
     ">
    <script type="text/javascript" src="/js/jquery.lbslider.js"></script>

    <div style="float:left; width: 100px;    min-height: 1px;">
        <script>
            function loadPreShowLeft($id, $productsKeys, $parentsId){
              (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
            		try{
                   rrApi.view($id);
                   //console.log('show preshow api left request id='+$id);
                 } catch(e) {}
            	})
            $.post("/product/preshow/" + $id, {productsKeys: $productsKeys, parentsId: $parentsId},
                    function (data) {
                    $('.blockPreShow').html(data);
                    });
            }
        </script>
        <? if ($preId != "") { ?>
            <div class="leftProductPreShow" onclick="loadPreShowLeft(<?= $preId ?>, '<?= implode(",", $productsKeys) ?>', 0)"></div>
        <? } ?>
    </div>









    <div style="float:left; width: 800px;">
        <div class="centerProductPreShow" style="padding: 10px;">
            <div onClick="$('.blockPreShow').remove();" class='close'></div>
            <table border="0" cellpadding="0" cellspacing="0" style="width: 780px;margin-bottom: 20px;" class="tablePreShow">
                <tbody>
                    <tr>
                        <td rowspan="3" style="border: 1px solid #e0e0e0;text-align: center;width: 400px; height: 400px;padding: 0px;position: relative;"><div class="img-holder">
                                <a href="/product/<?= $product->getSlug() ?>" style=" height: 15px; line-height: 0; display: inline-block;">

                                    <? /* <img id="photoimg_pr_<?= $product->getId() ?>" src="/uploads/photo/<?= $photos[0]['filename'] ?>" alt="image description" class="thumbnails" style="max-width: 400px; max-height: 400px;" />
                                     */ ?>
                                    <img src="/images/pix.gif" style="height: 100%;left: 0;position: absolute;width: 100%;z-index: 3;">
                                    <div style="display: table-cell;
                                         position: relative;
                                         text-align: center;
                                         vertical-align: middle;
                                         width: 100%;">
                                        <img id = "photoimg_pr_<?= $product->getId() ?>" src="/uploads/photo/<?= $photos[0]->getFilename() ?>" style="max-width: 400px; max-height: 400px;" alt="<?= str_replace(array("'", '"'), "", $product->getName()) ?>"  title="<?= str_replace(array("'", '"'), "", $product->getName()) ?>" />
                                    </div>
                                </a>


                                <? if ($product->getBonuspay() > 0) { ?>
                                    <div style="position: absolute; left: 0px; top: 0px;z-index: 10; cursor:pointer;" onClick="$('#bonusDiscountText_<?= $product->getId() ?>').toggle()">
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
                                             border-color: #c4040d;display: none;' id='bonusDiscountText_<?= $product->getId() ?>'>
                                            <div style="text-align: center;margin-bottom: 10px;"><span style="color: #c4040d;
                                                                                                       font-weight: bold;">УПРАВЛЯЙ ЦЕНОЙ!</span></div>
                                            <div style="margin-bottom: 10px;">Оплата накопленными<br />
                                                Бонусами до <?= $product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?>% от<br />
                                                стоимости товара.</div>
                                            <a href="/programma-on-i-ona-bonus" target="_blank">Условия программы ></a>
                                        </div>

                                    </div>

                                    <?
                                    echo '<span class="sale' . $product->getBonuspay() . '" style="top: -5px; right: -11px;"></span>';
                                } elseif ($product->getDiscount() > 0) {

                                    echo '<span class="sale' . $product->getDiscount() . '" style="top: -5px; right: -11px;"></span>';
                                    ?>
                                    <div style="position: absolute; left: 0px; top: 0px;z-index: 10;" class="loveprice" data-title="Лучшая цена в Рунете!"><a href="/category/Luchshaya_tsena"><img src="/newdis/images/loveprice.png" alt="Лучшая цена"></a></div>
                                    <?
                                } elseif (strtotime($product->getCreatedAt()) > time() - (csSettings::get('logo_new') * 24 * 60 * 60)) {

                                    echo '<span class="newProduct" style=" left: 0px; top: 0px;"></span>';
                                }
                                ?>
                            </div></td>
                        <td style="border: 1px solid #e0e0e0;border-width:0px 0px 1px 1px;color:#c3060e; padding: 0 20px 10px; font-size: 14px;"><?= $product->getName(); ?></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #e0e0e0;border-width:1px 0px 1px 1px; padding: 20px;height: 10px;">
                            <span onClick="$(this).html('<?= $product->getCode(); ?>');">№: <?= $product->getId() != "" ? $product->getId() : $product->getCode(); ?></span><br />
                            <? /* №: <?= $product->getCode(); ?><br />   */ ?>
                            <script src="/js/jquery.starRating.js"></script>
                            <script>
                                $('#rate_div').starRating({
                                basicImage  : '/images/star.gif',
                                        ratedImage : '/images/star_hover.gif',
                                        hoverImage : '/images/star_hover2.gif',
                                        ratingStars   : 10,
                                        ratingUrl       : '/product/rate',
                                        paramId       :  'product',
                                        paramValue  : 'value',
                                        rating			  : '<?= $product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0 ?>',
                                        customParams : {productId : '<?= $product->getId() ?>'}

<? if (sfContext::getInstance()->getRequest()->getCookie("rate_" . $product->getId())) { ?>
                                    ,
                                            clickable : false,
                                            hoverable : false
<? } ?>

                                });</script><div style="float: left;color: #707070;
                                float: left;
                                font: 11px/15px Tahoma,Geneva,sans-serif;
                                margin: 0;
                                              padding: 3px 5px 0 0;">Рейтинг </div><div style="float: left;">
                                <div onmouseout="$('#qestionRate').fadeOut()" onmouseover="$('#qestionRate').fadeIn()" onclick="$(
                                            '#qestionRate').toggle();" style="z-index: 5;width: 16px; height: 18px; background: url(/images/questionicon.png); position: relative;">

                                </div>
                                <div style=" position: relative;">
                                    <div id="qestionRate" style="padding: 10px; z-index: 4; text-align: left; position: absolute; color: #000; width: 215px; left: 7px; top: -11px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 1px solid rgb(195, 6, 14); display: none;">
                                        Средний рейтинг, основанный на голосовании покупателей.
                                    </div>
                                </div>
                            </div><div class="stars" id="rate_div" style="float: left;margin: 3px 0 0 5px;">
                                <? /* <span style="width:<?= $product->getRating() > 0 ? (@round($product->getRating() / $product->getVotesCount())) * 10 : 0 ?>%;"></span> */ ?>
                            </div><div style="float: left;"><?= $product->getVotesCount() ?></div>
                            <? /*  <div style="float: left;color: #707070;
                              float: left;
                              font: 11px/15px Tahoma,Geneva,sans-serif;
                              margin: 0;
                              padding: 3px 5px 0 0;">Рейтинг </div><div class="stars" id="rate_div" style="float: left;margin: 3px 0 0 5px;">
                              <span style="width:<?= $product->getRating() > 0 ? (@round($product->getRating() / $product->getVotesCount())) * 10 : 0 ?>%;"></span>
                              </div> */ ?>

                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #e0e0e0;border-width:1px 0px 1px 1px; padding:20px;">
                            <div class="item-char"><fieldset><?
                                    include_component('product', 'params', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId(), "productsKeys" => $productsKeys));
                                    ?></fieldset></div></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #e0e0e0; text-align: center; width: 400px;position: relative; padding: 10px 0;">
                            <? if ($photos->count() > 4) { ?>
                                <script>
                                    $(document).ready(function () {
                                    $('.SliderProductPreShow').lbSlider({
                                    leftBtn: '.sa-left',
                                            rightBtn: '.sa-right',
                                            visible: 4,
                                            autoPlay: false,
                                            autoPlayDelay: 5,
                                            cyclically: false
                                    });
                                    });</script>
                                <div class="SliderProductPreShow">
                                    <ul class="photosPreShow">
                                        <? foreach ($photos as $numPhotos => $photo) {
                                            ?><li><span<? If ($numPhotos == 0) { ?> class="activePhotosPreShow"<? } ?>><img src="/uploads/photo/thumbnails_250x250/<?= $photo['filename'] ?>" style="max-width: 60px; max-height: 60px;" onclick='$(".photosPreShow li span").each(function (i) {$(this).removeClass("activePhotosPreShow")}); $(this).parent().addClass("activePhotosPreShow"); $("#photoimg_pr_<?= $product->getId() ?>").attr("src", "/uploads/photo/<?= $photo['filename'] ?>");'></span></li><?
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <a href="#" class="slider-arrow sa-left"></a>
                                <a href="#" class="slider-arrow sa-right"></a>
                            <? } else { ?>

                                <div class="SliderProductPreShow">
                                    <ul class="photosPreShow">
                                        <? foreach ($photos as $numPhotos => $photo) {
                                            ?><li><span<? If ($numPhotos == 0) { ?> class="activePhotosPreShow"<? } ?>><img src="/uploads/photo/thumbnails_250x250/<?= $photo['filename'] ?>" style="max-width: 60px; max-height: 60px;" onclick='$(".photosPreShow li span").each(function (i) {$(this).removeClass("activePhotosPreShow")}); $(this).parent().addClass("activePhotosPreShow"); $("#photoimg_pr_<?= $product->getId() ?>").attr("src", "/uploads/photo/<?= $photo['filename'] ?>");'></span></li><?
                                        }
                                        ?>
                                    </ul>
                                </div>
                            <? } ?>
                        </td>

                        <td style="border: 1px solid #e0e0e0;border-width:1px 0px 1px 1px;padding: 20px;">
                            <? If ($count_comm > 0) { ?><a href="/product/<?= $product->getSlug() ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $count_comm ?></a><br /><? } ?>
                            <? If ($photosUser->count() > 0) { ?><a href="/product/<?= $product->getSlug() ?>/?photouser=true#photouser" class="rewiev">Фото пользователей: <?= $photosUser->count() ?></a><br /><? } ?>
                            <? If ($product->getVideo() != "") { ?><a href="/product/<?= $product->getSlug() ?>/?video=true" class="rewiev">Видео: 1</a><br /><? } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="width: 400px;">
                <? If ($product->getBonuspay() > 0) { ?>
                    <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость с учетом бонусов:</div>
                    <div style="float: left;margin-top: 5px;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;"><?= number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                    <div style="float: left;margin-top: 5px;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= number_format($product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                    <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Оплата бонусами:</div>
                    <div style="float: left;font-size: 16px; color: #414141;margin-top: 3px;"><?= number_format($product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?>

                        <div onmouseout="$('#bonusInfo').fadeOut(0)" onmouseover="$('#bonusInfo').fadeIn(0)" onclick="$('#bonusInfo').toggle();" style="width: 16px; height: 18px; background: url('/images/questionicon.png') repeat scroll 0% 0% transparent; display: inline-block; margin: -2px auto; position: relative;">

                            <div id="bonusInfo" style="font-size: 13px; color: #707070;padding: 10px; text-align: left; width: 150px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 1px solid rgb(195, 6, 14); z-index: 105; position: absolute; right: 0px; top: 0px; display: none;">
                                <div style="text-align: center;margin-bottom: 10px;"><span style="color: #c4040d;
                                                                                           font-weight: bold;">УПРАВЛЯЙ ЦЕНОЙ!</span></div>
                                <div style="margin-bottom: 10px;">Оплата накопленными<br />
                                    Бонусами до <?= $product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?>% от<br />
                                    стоимости товара.</div>
                                <a href="/programma-on-i-ona-bonus" target="_blank">Условия программы ></a>
                            </div>
                        </div>
                    </div>
                <? } elseif ($product->getDiscount() > 0) { ?>
                    <div style="float: left;font-size: 11px; color: #707070;width:60px; margin-right: 10px;line-height: 11px;">Стоимость сегодня:</div>
                    <div style="float: left;margin-top: 5px;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;"><?= $product->getPrice() ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                    <div style="float: left;margin-top: 5px;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= $product->getOldPrice() ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                    <div style="float: left;font-size: 11px; color: #707070;width:60px; margin-right: 10px;line-height: 11px;">Экономия:</div>
                    <div style="float: left;font-size: 16px; color: #414141;margin-top: 3px;"><?= number_format($product->getOldPrice() - $product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></div>
                <? } else { ?>
                    <div style="float: left;font-size: 11px; color: #707070;width:60px; margin-right: 10px;line-height: 11px;">Стоимость сегодня:</div>
                    <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;"><?= $product->getPrice() ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                    <?php
                      $bonusAddUser = round(($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                    ?>

                    <div style="float: right;font-size: 16px; color: #414141;">
                        <?= $bonusAddUser ?>
                        <div onmouseout="$('#bonusInfo').fadeOut(0)" onmouseover="$('#bonusInfo').fadeIn(0)" onclick="$('#bonusInfo').toggle();" style="width: 16px; height: 18px; background: url('/images/questionicon.png') repeat scroll 0% 0% transparent; display: inline-block; margin: -2px auto; position: relative;">
                            <div id="bonusInfo" style="font-size: 13px; color: #707070;padding: 10px; text-align: left; width: 215px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 1px solid rgb(195, 6, 14); z-index: 105; position: absolute; right: 0px; top: 0px; display: none;">
                                <div style="color:#c3060e; text-align: center; width: 100%; font-size: 15px;margin-bottom: 10px;">Программа "Он и Она - Бонус"</div>
                                После оплаты этого товара на ваш счет будут зачислены Бонусы в размере
                                <?= $bonusAddUser ?>.<br /><br />
                                <a href="/programma-on-i-ona-bonus" target="_blank">Условия программы &gt;</a>
                            </div>
                        </div>
                    </div>
                    <div style="float: right;font-size: 11px; color: #707070;width:60px; margin-right: 10px;line-height: 11px;">Бонусы за покупку:</div>

                <? } ?>
            </div>
            <div style="clear:both;  height: 30px;"></div>
            <?php if ($product->getCount() > 0 and $product->get("is_public")): ?>
                <?= $product->getPrice() >= csSettings::get('free_deliver') ? '<div class="freeDelivery" style="margin-top:0px;margin-left: 85px;" onmouseout="$(\'#freeDeliveryBlock\').fadeOut(0)" onmouseover="$(\'#freeDeliveryBlock\').fadeIn(0)"><div style=" position: relative;">
                            <div id="freeDeliveryBlock" style="padding: 10px; z-index: 4; text-align: left; position: absolute; color: rgb(0, 0, 0); width: 215px; right: 80px; top: 10px; border: 1px solid rgb(195, 6, 14); display: none; background: none 0% 0% repeat scroll rgb(255, 255, 255);">
                                Бесплатная доставка предоставляется при итоговой сумме заказа более 2990 <span style="font-family: \'PT Sans Narrow\', sans-serif;">&#8381;</span>.
                            </div>
                        </div></div>' : '' ?>
                <?
                $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
                if (is_array($products_old)) {
                    foreach ($products_old as $key => $productCart) {
                        $arrayProdCart[] = $productCart['productId'];
                    }
                }
                if (in_array($product->getId(), $arrayProdCart) === true) {
                    ?>
                    <a href="/cart" class="greenButtonPreShowAddedToCart" style="margin-left: 58px;  margin-right: 15px;"></a>
                <? } else { ?>
                    <div class="redButtonPreShowAddToCart" onClick="tagDiv = this;
                        try { rrApi.addToBasket(<?= $product->getId() ?>) } catch(e) {}
                        $.ajax({
                        url: '/cart/addtocart/<?= $product->getId() ?>',
                                cache: false
                        }).done(function (html) {
                        addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');
                        $(tagDiv).after($('<a class=\'greenButtonPreShowAddedToCart\' style=\'margin-left: 58px;  margin-right: 15px;\'>').attr('href', '/cart')).remove();
                        });
                        " style='    margin-left: 58px;  margin-right: 15px;'></div>


    <? } ?>
                <? /* <div class="redButtonAddToDesire" onClick='javascript: addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                  $("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");'></div> */ ?>
                <?
                if (!isset($prodInDesire)) {

                    $products_desire = $sf_user->getAttribute('products_to_desire');
                    $products_desire = $products_desire != '' ? unserialize($products_desire) : '';

                    if (in_array($product->getId(), $products_desire) === true)
                        $prodInDesire = true;
                    else
                        $prodInDesire = false;
                }
                if (!isset($prodInCompare)) {

                    $products_compare = $sf_user->getAttribute('products_to_compare');
                    $products_compare = $products_compare != '' ? unserialize($products_compare) : '';
                    if (in_array($product->getId(), $products_compare) === true)
                        $prodInCompare = true;
                    else
                        $prodInCompare = false;
                }
                ?>
                <? if ($prodInDesire === true) {
                    ?>
                    <a class="greenButtonAddToDesire" style=" margin-right: 15px;" href='/desire'></a>
                    <?
                } else {
                    ?>
                    <div class="redButtonAddToDesire" style=" margin-right: 15px;" onClick='javascript: $(this).after($("<a class=\"greenButtonAddToDesire\"  style=\" margin-right: 15px;\">").attr("href", "/desire")).remove();
                        addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                        $("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");'></div>
                <? } ?>

                     <? if ($prodInCompare === true) {
                         ?>
                    <a class="greenButtonAddToCompare" href='/compare'></a>
                    <?
                } else {
                    ?>
                    <div class="redButtonAddToCompare" onClick='javascript: $(this).after($("<a class=\"greenButtonAddToCompare\">").attr("href", "/compare")).remove();
                        // addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                        $.post("/cart/addtocompare/<?= $product->getId() ?>");'></div>
                <? } ?>
                     <?php
                 else:


                     if ($sf_user->isAuthenticated()):
                         $nameSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getFirstName();
                         $mailSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress();
                     endif;
                     ?>
                <a id="addToBasket" style="margin-left: 58px;  margin-right: 15px;" class="redButtonPreShowUserSend" onclick="return hs.htmlExpand(this, {contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar red-header',
                            headingText: '', width: 553, height: 400, slideshowGroup: 'groupToSend2', left: - 9})" style="" ><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;" onclick="$('.captchasu').attr('src', $('.captchasu').attr('data-original'));
                                setTimeout('enableAjaxFormSendUser_<?= $product->getId() ?>()', 500)"></span></a>

                              <?php endif; ?>

            <a href="/product/<?= $product->getSlug() ?>" class="greenButtonPreShowFullDescription" style=" margin-left: <?= ($product->getCount() > 0 and $product->get("is_public")) ? '75' : '135' ?>px;"></a>

            <div style="clear:both;  height: 30px;"></div>
        </div>
    </div>










    <div style="float:left; width: 100px;    min-height: 1px;"><? if ($postId != "") { ?><script>
            function loadPreShowRight($id, $productsKeys, $parentsId){
              (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
            		try{
                   rrApi.view($id);
                   // console.log('show preshow api right request id='+$id);
                 } catch(e) {}
            	})
            $.post("/product/preshow/" + $id, {productsKeys: $productsKeys, parentsId: $parentsId},
                    function (data) {
                    $('.blockPreShow').html(data);
                    });
            }
            </script><div class="rightProductPreShow" onclick="loadPreShowRight(<?= $postId ?>, '<?= implode(",", $productsKeys) ?>', 0)"></div><? } ?></div>
</div>
