<?
$timerAll = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Весь шаблон');
$newsList=csSettings::get('optimization_newProductId');
$newsListAr=explode(',', $newsList);
$timer = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Основной блок');
if ($photoFilename == "") {
    $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
    $photoFilename = $photos[0]['filename'];
}
if ($commentsCount == "") {
    $comments = Doctrine_Core::getTable('Comments')
            ->createQuery('c')
            ->where("is_public = '1'")
            ->addWhere('product_id = ?', $product->getId())
            ->orderBy('id ASC')
            ->execute();
    $commentsCount = $comments->count();
}
?>
<?
if ($product->getBonuspay() > 0) {

    $timer2 = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Основной блок: BonusPay>0');
    ?>
    <script>
        $(document).ready(function () {
            $('.blockUC-<?= $product->getId() ?>').mouseover(function () {
                $(".liTipContent .bonusDiscountText_<?= $product->getId() ?>").each(function (index) {
                    $(this).show();
                });
            }).mouseout(function () {
                $(".liTipContent .bonusDiscountText_<?= $product->getId() ?>").each(function (index) {
                    $(this).hide();
                });
            });
        });
    </script>
    <div style="position: absolute; left: 0px; top: 60px;z-index: 10; cursor:pointer;" onClick="$('.liTipContent .bonusDiscountText_<?= $product->getId() ?>').each(function (index) {
                $(this).toggle();
            });" class="blockUC-<?= $product->getId() ?>">
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
             border-color: #c4040d;display: none;' class='bonusDiscountText_<?= $product->getId() ?>'>
            <div style="text-align: center;margin-bottom: 10px;"><span style="color: #c4040d;
                                                                       font-weight: bold;">УПРАВЛЯЙ ЦЕНОЙ!</span></div>
            <div style="margin-bottom: 10px;">Оплата накопленными<br />
                Бонусами до <?= $product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?>% от<br />
                стоимости товара.</div>
            <a href="/programma-on-i-ona-bonus" target="_blank">Условия программы ></a>
        </div>

    </div>

    <?
    echo '<span class="sale' . /*$product->getBonuspay() . */'">-' . $product->getBonuspay() . '%</span>';

    $timer2->addTime();
} elseif ($product->getDiscount() > 0) {

    $timer2 = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Основной блок: Discount>0');
    ?>
    <div style="position: absolute; left: 0px; top: 60px;z-index: 10;" class="loveprice" data-title="Лучшая цена в Рунете!"><a href="/category/Luchshaya_tsena"><img src="/newdis/images/loveprice.png" alt="Лучшая цена"></a></div>
    <?
    $timer2->addTime();
} else {

    $timer2 = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Основной блок: BonusPay=0 и Discount=0');
    $isNew=false;
    if (strtotime($product->getCreatedAt()) > time() - (csSettings::get('logo_new') * 24 * 60 * 60)) $isNew= true;
    if (in_array($product->getId(), $newsListAr)) $isNew= true;
    echo $isNew ? '<span class="newProduct"></span>' : '';
    // echo strtotime($product->getCreatedAt()) > time() - (csSettings::get('logo_new') * 24 * 60 * 60) ? '<span class="newProduct"></span>' : '';
    $timer2->addTime();
}
$timer2 = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Основной блок: Вывод скидки и картинки видео');
?>

<?= $product->getDiscount() > 0 ? '<span class="sale' . /*$product->getDiscount() . */'">-' . $product->getDiscount() . '%</span>' : ''; ?>

<?=
($product->getVideo() != '' or $product->getVideoenabled() ) ? '<span class="videoProduct" title="К этому товару есть видео-презентация"></span>' : '';
$timer2->addTime();
?>

<div
  class="title gtm-list-item"
  data-id="<?= $product->getId() ?>"
  data-name="<?= $product->getName() ?>"
  data-category="<?= $product->getGeneralCategory() ?>"
  data-price="<?= $product->getPrice() ?>"
  data-position="-1"
>
  <a
    class="gtm-link-item"
    href="/product/<?= $product->getSlug() ?>"
    style="text-decoration: underline;"
  >
    <? mb_internal_encoding('UTF-8'); ?><?= $product->getName() ?>
  </a></div>
<div class="img-holder imgProd-<?= $product->getId() ?>" onmouseover="
        $('.liTipInner .ButtonPreShow-<?= $product->getId() ?>').each(function (i) {
            $(this).show();
        });"
     onmouseout="
             $('.liTipInner .ButtonPreShow-<?= $product->getId() ?>').each(function (i) {
                 $(this).hide();
             });">
    <a href="/product/<?= $product->getSlug() ?>" class="gtm-link-item">
        <? /* <img src="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' title='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' /> */ ?>
        <img <? /* id="photoimg_<?= $product->getId() ?>" */ ?>src="/images/pixel.gif" data-original="/uploads/photo/thumbnails_250x250/<?= $photoFilename ?>" alt='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' title='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' class="thumbnails" />

    </a>
    <div class="silverButtonPreShow ButtonPreShow-<?= $product->getId() ?>" style="display:none;" onclick="loadPreShow(<?= $product->getId() ?>, '<?= $productsKeys ?>', <?= $product->getParentsId() > 0 ? $product->getParentsId() : 0 ?>);
            $(this).parents('.liTipContent').fadeOut(0);
            $(this).parents('.liTipContent').css({left: '-99999px', top: '-99999px'});
            var $parThis = this;
            setTimeout(function () {
                $($parThis).parents('.liTipContent').fadeIn(0);
            }, 1000);"></div>
    <script>
        /*$(document).ready(function () {
         $('.imgProd-<?= $product->getId() ?>').mouseover(function () {
         $(".liTipInner .ButtonPreShow-<?= $product->getId() ?>").each(function (i) {
         $(this).show();
         });
         }).mouseout(function () {
         $(".liTipInner .ButtonPreShow-<?= $product->getId() ?>").each(function (i) {
         $(this).hide();
         });
         });
         });
         $(".liTipInner").load(function () {
         $('.imgProd-<?= $product->getId() ?>').mouseover(function () {
         $(".liTipInner .ButtonPreShow-<?= $product->getId() ?>").each(function (i) {
         $(this).show();
         });
         }).mouseout(function () {
         $(".liTipInner .ButtonPreShow-<?= $product->getId() ?>").each(function (i) {
         $(this).hide();
         });
         });
         });*/
//        });

        function loadPreShow($id, $productsKeys, $parentsId) {
            $('<div/>').click(function (e) {
                if (e.target != this)
                    return;
                $(this).remove();
            }).attr('id', 'preShow-' + $id).css("padding-top", $(window).scrollTop() + 100).addClass("blockPreShow").appendTo('body');
            (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
          		try{
                 rrApi.view($id);
                 //console.log('show preshow api request id='+$id);
               } catch(e) {}
          	})
            $.post("/product/preshow/" + $id, {productsKeys: $productsKeys, parentsId: $parentsId},
                    function (data) {
                        $('.blockPreShow').html(data);
                        $(document).keyup(function (e) {

                            if (e.keyCode == 27) {
                                $('.blockPreShow').remove();
                            }   // esc
                        });
                    });
        }
    </script>
    <? if ($product->getEndaction() != ""): ?>
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
            <span class="time actionProduct_block_<?= $product->getId() ?>"></span>
            <?php
            $timer2 = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Основной блок: Расчёт окончания акции');
            $step = array("1 сутки" => "1", "2 суток" => "2", "3 суток" => "3", "4 суток" => "4", "5 суток" => "5");
            if ($product->getStep() != "") {
                //echo $product->getEndaction() - time() + 24 * 60 * 60;
                if ((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) > $step[$product->getStep()] * 24 * 60 * 60) {
                    $dateEnd = date("Y, m-1, d", (strtotime($product->getEndaction()) - floor((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) / ($step[$product->getStep()] * 24 * 60 * 60)) * $step[$product->getStep()] * 24 * 60 * 60));
                } else {
                    $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()));
                }
            } else {
                $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()));
            }
            $timer2->addTime();
            ?>
            <script type="text/javascript">

                $("#countdownSRC").load(function () {
                    $('.countdown .actionProduct_block_<?= $product->getId() ?>').countdown({
                        //format: 'd hh:mm:ss',
                        format: 'hh:mm:ss',
                        compact: true,
                        description: '',
                        until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                //timezone: +6
                    });
                });
                if (typeof ($.fn.countdown) !== 'undefined') {
                    $('.countdown .actionProduct_block_<?= $product->getId() ?>').countdown({
                        //format: 'd hh:mm:ss',
                        format: 'hh:mm:ss',
                        compact: true,
                        description: '',
                        until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                //timezone: +6
                    });
                }/*
                 $(document).ready(function () {
                 $('.countdown .actionProduct_block_<?= $product->getId() ?>').countdown({
                 //format: 'd hh:mm:ss',
                 format: 'hh:mm:ss',
                 compact: true,
                 description: '',
                 until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                 //timezone: +6
                 });
                 });*/</script>
        </div>
        <? Endif; ?>
</div>
<?
$timer->addTime();
$timer = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Расчёт рейтинга и цен');
?>
<div class="bottom-box">
    <div class="row">
        <? if ($commentsCount > 0) { ?><a class="gtm-link-item" href="/product/<?= $product->getSlug() ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $commentsCount ?></a><? } ?>
        <div class="stars">
            <span style="width:<?= $product->getRating() > 0 ? (@round($product->getRating() / $product->getVotesCount())) * 10 : 0 ?>%;"></span>
        </div>
    </div>
    <div class="price-box">
        <?php if ($product->getOldPrice() > 0) { ?>
            <span class="old-price"><?= number_format($product->getOldPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
            <span class="new-price"><?= number_format($product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
        <? } elseif ($product->getBonuspay() > 0) { ?>
            <span class="old-price"><?= number_format($product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
            <span class="new-price"><?= number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
        <? } else { ?>
            <span class="price"><?= number_format($product->getPrice(), 0, '', ' ') ?>  <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
        <? } ?>
    </div>

    <div class="bonus-box-price"><?php
        if (true) {
        // if ($product->getBonuspay() > 0) {
            // $bonusAddUser = round(($product->getPrice() - $product->getPrice() * ($product->getBonuspay() / 100)) * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
            $bonusAddUser = round(($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
            if ($bonusAddUser > 0) {
                ?>+<?= $bonusAddUser ?> бонусов<?
            }
        } else {
            ?>+<?= round($product->getPrice() * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100)) ?> бонусов<? } ?>
    </div>
</div>
<div style="clear: both;"></div>
<?php
$timer->addTime();
$timer = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Сообщить о поступлении или кнопка в корзину');
if ($product->getCount() > 0 and $product->get("is_public")):
    ?>
    <? If ($prodInCart) { ?>
        <a href="/cart" class="greenButtonAddedToCart"></a>
    <? } else { ?>
        <div class="redButtonAddToCart gtm-list-add-to-basket" onClick="javascript:

                tagDiv = this;
                $.ajax({
                    url: '/cart/addtocart/<?= $product->getId() ?>',
                    cache: false
                }).done(function (html) {

                    addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photoFilename ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');
                    $(tagDiv).after($('<a class=\'greenButtonAddedToCart\'>').attr('href', '/cart')).remove();
                    $('.productInCart-<?= $product->getId() ?>').fadeIn(0);

                });

                try {
                    rrApi.addToBasket(<?= $product->getId() ?>)
                } catch (e) {
                }
             "></div>
         <? } ?>
         <?php
     else:
         ?>
         <div
           class="js-mobile-button-buy gtm-list-add-to-basket redButtonSmallUserSend bar"
           data-id="<?=$product['id']?>"
           data-name="<?= str_replace(array("'", '"', '\\'), "", $product['name']) ?>"
           data-image="<?=$photo['filename']?>"
           data-price="<?=$product['price']?>"
           data-bonus-add="<?= round(($product['price'] * csSettings::get("persent_bonus_add")) / 100) ?>"
         >Заказать</div>
         <?/*
            <a class="redButtonSmallUserSend foo" onclick="return hs.htmlExpand(this, {contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar red-header',
                headingText: '', width: 553, height: 400, slideshowGroup: 'groupToSend2', left: -9})" style="" ><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;" onclick="$('.captchasu').attr('src', $('.captchasu').attr('data-original'));
                        setTimeout('enableAjaxFormSendUser_<?= $product->getId() ?>()', 500)"></span>Заказать</a>
                        */?>
    <!--
      Сообщить о поступлении
    -->
    <div style="display: none" class="highslide-maincontent" id="ContentToSend_<?= $product->getId() ?>">
        <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>
        <? /* <img src="/images/topToSend.png" alt="Сообщить о поступлении" /> */ ?>
        <div style="color: #C3060E; font: 17px/21px Tahoma,Geneva,sans-serif;margin-bottom: 10px; text-align: center;">Сообщить о поступлении товара</div>
        <?php if (!$errorCapSu and $sf_params->get('senduser')): ?>
            <div style="width:100%; text-align: center;">Спасибо за запрос. Вам будет сообщено о поступление товара.</div>
        <?php else: ?>
            <script>
                /*   $(document).ready(function() {
                 var options = {
                 target:        '.highslide-maincontent', // target element(s) to be updated with server response
                 //beforeSubmit:  showRequest,  // pre-submit callback
                 success:       showResponse  // post-submit callback

                 // other available options:
                 //url:       url         // override for form's 'action' attribute
                 //type:      type        // 'get' or 'post', override for form's 'method' attribute
                 //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                 //clearForm: true        // clear all form fields after successful submit
                 //resetForm: true        // reset the form after successful submit

                 // $.ajax options can be used here too, for example:
                 //timeout:   3000
                 };
                 // bind form using 'ajaxForm'
                 $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
                 });
                 function showResponse(responseText, statusText, xhr, $form)  {
                 var options = {
                 target:        '.highslide-maincontent', // target element(s) to be updated with server response
                 //beforeSubmit:  showRequest,  // pre-submit callback
                 success:       showResponse  // post-submit callback

                 // other available options:
                 //url:       url         // override for form's 'action' attribute
                 //type:      type        // 'get' or 'post', override for form's 'method' attribute
                 //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                 //clearForm: true        // clear all form fields after successful submit
                 //resetForm: true        // reset the form after successful submit

                 // $.ajax options can be used here too, for example:
                 //timeout:   3000
                 };
                 // bind form using 'ajaxForm'
                 $('.highslide-container #senduser_<?= $product->getId() ?>').ajaxForm(options);
                 }*/
                $(document).ready(function () {
                    var options = {
                        target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                        success: showResponse_<?= $product->getId() ?>
                    };
                    $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
                });
                function enableAjaxFormSendUser_<?= $product->getId() ?>() {

                    var options = {
                        target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                        success: showResponse_<?= $product->getId() ?>
                    };
                    $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
                }
                function showResponse_<?= $product->getId() ?>(responseText, statusText, xhr, $form) {
                    var options = {
                        target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                        success: showResponse_<?= $product->getId() ?>
                    };
                    $('.highslide-container #senduser_<?= $product->getId() ?>').ajaxForm(options);
                    $('.senduser_<?= $product->getId() ?>').each(function () {
                        $(this).ajaxForm(options);
                    });
                }
            </script>
            <div id="senduserdiv_<?= $product->getId() ?>"><form id="senduser_<?= $product->getId() ?>" action="/product/<?= $product->getSlug() ?>/senduser" method="post">
                    Данный товар скоро появится в продаже на сайте. Чтобы своевременно узнать об этом, нужно отправить нам запрос. Для этого в соответствующих полях напишите свое имя, e-mail и нажмите на кнопку "Отправить запрос". Как только товар поступит на склад, мы отправим уведомление на указанный вами e-mail.<br /><br />
                    <div style="clear: both; color:#4e4e4e; text-align: left;">
                        <table style=" width: 100%" class="noBorder">
                            <tbody><tr>
                                    <td style="width: 120px;padding: 5px 0;text-align: left;">
                                        Представьтесь*:
                                    </td>
                                    <td style="padding: 5px 0;text-align: left;">
                                        <input type="text" name="name" value="<? /* = sfContext::getInstance()->getRequest()->getParameter("name") != "" ? sfContext::getInstance()->getRequest()->getParameter("name") : $nameSenduser */ ?>" style="width: 254px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 120px; padding: 5px 0;text-align: left;">
                                        Ваш e-mail*:
                                    </td>
                                    <td style="padding: 5px 0;text-align: left;">
                                        <input type="text" name="mail" value="<? /* = sfContext::getInstance()->getRequest()->getParameter("mail") != "" ? sfContext::getInstance()->getRequest()->getParameter("mail") : $mailSenduser */ ?>" style="width: 254px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 120px; padding: 5px 0;text-align: left;">
                                        Введите код*:
                                    </td>
                                    <td style="padding: 5px 0;text-align: left;">

                                        <img  class="captchasu" src="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />
                                        <input type="text" name="sucaptcha" style="position: relative; top: -45px; width: 130px;">
                                        <?php if ($errorCapSu) { ?><br />
                                            <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                            <br />
                                        <?php } ?>
                                    </td>
                                </tr>
                            </tbody></table>
                        <span style="font-size: 10px;">* - обязательны для заполнения.</span>
                        <a  style="margin-top: 20px; margin-left: 155px;" class = "red-btn colorWhite" href = "#" onclick = "enableAjaxFormSendUser_<?= $product->getId() ?>();$('#senduser_<?= $product->getId() ?>').submit();
                                setTimeout('enableAjaxFormSendUser_<?= $product->getId() ?>()', 500);
                                return false;"><span style = "width: 195px;">Отправить запрос</span></a>

                    </div>
                </form></div>
        <?php endif; ?>
    </div>
    <!--
    Сообщить о поступлении
    -->
<?php
endif;

$timer->addTime();
$timer = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Проверка на вхождение');
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
    <a class="greenButtonAddToDesire tipDesire" href='/desire' data-title="Добавлен в список желаний"></a>
    <?
} else {
    ?>
    <div class="redButtonAddToDesire tipDesire" onClick='javascript: $(this).after($("<a class=\"greenButtonAddToDesire tipDesire\"  data-title=\"Добавлен в список желаний\">").attr("href", "/desire")).remove();
            addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
            $("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");
            $(".productInDesire-<?= $product->getId() ?>").fadeIn(0);' data-title="Добавить в список желаний"></div>
     <? } ?>

<? if ($prodInCompare === true) {
    ?>
    <a class="greenButtonAddToCompare tipCompare" href='/compare' data-title="Добавлен в сравнение"></a>
    <?
} else {
    ?>
    <div class="redButtonAddToCompare tipCompare" onClick='javascript: $(this).after($("<a class=\"greenButtonAddToCompare tipCompare\"  data-title=\"Добавлен в сравнение\">").attr("href", "/compare")).remove();
            // addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
            $.post("/cart/addtocompare/<?= $product->getId() ?>");
            $(".productInCompare-<?= $product->getId() ?>").fadeIn(0);' data-title="Добавить в сравнение"></div>
     <? } ?>

<?
$timer->addTime();
If ($showarticle) {
    echo "Артикул: " . $product->getCode();
}
?>
<?/*
<script>
    $(document).ready(function () {
        $("div.notcount").hover(function () {
            $(this).stop().animate({"opacity": 1});
        }, function () {
            $(this).stop().animate({"opacity": 0.3});
        });
    });
</script>
*/?>

<?
$timerAll->addTime();
