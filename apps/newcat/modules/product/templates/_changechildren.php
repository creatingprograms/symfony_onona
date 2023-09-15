<?php
$products_to_cart = $sf_user->getAttribute('products_to_cart');
$products_to_cart = $products_to_cart != '' ? unserialize($products_to_cart) : '';

$products_desire = $sf_user->getAttribute('products_to_desire');
$products_desire = $products_desire != '' ? unserialize($products_desire) : '';

$products_compare = $sf_user->getAttribute('products_to_compare');
$products_compare = $products_compare != '' ? unserialize($products_compare) : '';

$newsList=csSettings::get('optimization_newProductId');
$newsListAr=explode(',', $newsList);

?>
<div class="c">
    <div class="content<?= ($product['count'] == 0) ? ' notcount' : '' ?>">
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
            echo '<span class="sale'/* . $product['bonuspay']*/ . '"><span>-<strong>' . $product['bonuspay'] . '</strong>%</span></span>';
        } elseif ($product['discount'] > 0) {
            ?>
            <div style="position: absolute; left: 0px; top: 60px;z-index: 10;" class="loveprice" data-title="Лучшая цена в Рунете!"><a href="/category/Luchshaya_tsena"><img src="/newdis/images/loveprice.png" alt="Лучшая цена"></a></div>
            <?
        } else {
          if (strtotime($product['created_at']) > time() - (csSettings::get('logo_new') * 24 * 60 * 60)) $isNew= true;
          if (in_array($product['id'], $newsListAr)) $isNew= true;
          echo $isNew ? '<span class="newProduct"></span>' : '';
            // echo strtotime($product['created_at']) > time() - (csSettings::get('logo_new') * 24 * 60 * 60) ? '<span class="newProduct"></span>' : '';
        }
        ?>
        <span class="productInDesire-<?= $product['id'] ?> productInDesire"<?= @in_array($product['id'], $products_desire) ? ' style="display: block;"' : '' ?>></span>
        <span class="productInCompare-<?= $product['id'] ?> productInCompare"<?= @in_array($product['id'], $products_compare) ? ' style="display: block;"' : '' ?>></span>
        <span class="productInCart-<?= $product['id'] ?> productInCart"<?= @is_array($products_to_cart[$product['id']]) ? ' style="display: block;"' : '' ?>></span>
        <?= $product['discount'] > 0 ? '<span class="sale'/* . $product['discount'] */. '"><span>-<strong>' . $product['discount'] . '</strong>%</span></span>' : ''; ?>

        <?= ($product['video'] != '' or $product['videoenabled'] ) ? '<span class="videoProduct" title="К этому товару есть видео-презентация"></span>' : ''; ?>

        <div
          class="title gtm-list-item"
          data-id="<?= $product['id'] ?>"
          data-name="<?= $product['name'] ?>"
          data-category="<?= $product['cat_name'] ?>"
          data-price="<?= $product['price'] ?>"
          data-position="-1">
        <a class="gtm-link-item" href="/product/<?= $product['slug'] ?>"><? mb_internal_encoding('UTF-8'); ?><?= $product['name'] ?></a></div><?php /* mb_substr($product['name'], 0, 55) */ ?>
        <div class="img-holder" onmouseover="
        $('.liTipInner .ButtonPreShow-<?= $product['id'] ?>').each(function (i) {
            $(this).show();
        });"
     onmouseout="
             $('.liTipInner .ButtonPreShow-<?= $product['id'] ?>').each(function (i) {
                 $(this).hide();
             });">
            <a href="/product/<?= $product['slug'] ?>" class="gtm-link-item">
                <img src="/uploads/photo/thumbnails_250x250/<?= $photo['filename'] ?>" alt='<?= str_replace(array("'", '"'), "", $product['name']) ?>' title='<?= str_replace(array("'", '"'), "", $product['name']) ?>' class="thumbnails" />

            </a>
    <div class="silverButtonPreShow ButtonPreShow-<?= $product['id'] ?>" style="display:none;" onclick="loadPreShow(<?= $product['id'] ?>, '<?= implode(',',array_keys($products)) ?>', <?= $product['parents_id'] > 0 ? $product['parents_id'] : 0 ?>);
            $(this).parents('.liTipContent').fadeOut(0);
            $(this).parents('.liTipContent').css({left: '-99999px', top: '-99999px'});
            var $parThis = this;
            setTimeout(function () {
                $($parThis).parents('.liTipContent').fadeIn(0);
            }, 1000);"></div>
    <script>
        function loadPreShow($id, $productsKeys, $parentsId) {
            $('<div/>').click(function (e) {
                if (e.target != this)
                    return;
                $(this).remove();
            }).attr('id', 'preShow-' + $id).css("padding-top", $(window).scrollTop() + 100).addClass("blockPreShow").appendTo('body');
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
                <? if ($comment['countcomm'] > 0) { ?><a class="gtm-link-item" href="/product/<?= $product['slug'] ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $comment['countcomm'] ?></a><? } ?>
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
                if (true) {
                // if ($product['bonuspay'] > 0) {
                    $bonusAddUser = round(($product['price'] - $product['price'] * ($product['bonuspay'] > 0 ? $product['bonuspay'] : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($product['bonus'] > 0 ? $product['bonus'] : csSettings::get('persent_bonus_add')) / 100));
                    // $bonusAddUser = round(($product['price'] - $product['price'] * ($product['bonuspay'] / 100)) * (($product['bonus'] > 0 ? $product['bonus'] : csSettings::get('persent_bonus_add')) / 100));
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
<div style="
    clear: both;
"></div>
        <?
        $timer = sfTimerManager::getTimer('ComponentsInclude: Children as _products: Сообщить о поступлении или кнопка в корзину');
        if ($product['count'] > 0 and $product["is_public"]):
            ?>
            <? If (@is_array($products_to_cart[$product['id']])) { ?>
                <a href="/cart" class="greenButtonAddedToCart"></a>
            <? } else { ?>
                <div class="redButtonAddToCart gtm-list-add-to-basket" onClick="javascript:
                                try { rrApi.addToBasket(<?= $product['id'] ?>) } catch(e) {}

                                tagDiv = this;
                        $.ajax({
                            url: '/cart/addtocart/<?= $product['id'] ?>',
                            cache: false
                        }).done(function (html) {

                            addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photo['filename'] ?>', '<?= str_replace(array("'", '"', '\\'), "", $product['name']) ?>', '<?= $product['price'] ?>', '<?= round(($product['price'] * csSettings::get("persent_bonus_add")) / 100) ?>');
                            $(tagDiv).after($('<a class=\'greenButtonAddedToCart\'>').attr('href', '/cart')).remove();
                            $('.productInCart-<?= $product['id'] ?>').fadeIn(0);

                        });

                     "></div>
                 <? } ?>
                 <?php
             else:
                 ?>
            <?/*<a class="redButtonSmallUserSend bar" onclick="return hs.htmlExpand(this, {contentId: 'ContentToSend_<?= $product['id'] ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar red-header',
                        headingText: '', width: 553, height: 400, slideshowGroup: 'groupToSend2', left: -9})" style="" ><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;" onclick="$('.captchasu').attr('src', $('.captchasu').attr('data-original'));
                                setTimeout('enableAjaxFormSendUser_<?= $product['id'] ?>()', 500)"></span>Заказать</a>*/?>
            <div
              class="js-mobile-button-buy redButtonSmallUserSend bar gtm-list-add-to-basket"
              data-id="<?=$product['id']?>"
              data-name="<?= str_replace(array("'", '"', '\\'), "", $product['name']) ?>"
              data-image="<?=$photo['filename']?>"
              data-price="<?=$product['price']?>"
              data-bonus-add="<?= round(($product['price'] * csSettings::get("persent_bonus_add")) / 100) ?>"
            >Заказать</div>
            <!--
              Сообщить о поступлении
            -->
            <div style="display: none" class="highslide-maincontent" id="ContentToSend_<?= $product['id'] ?>">
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
                         $('#senduser_<?= $product['id'] ?>').ajaxForm(options);
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
                         $('.highslide-container #senduser_<?= $product['id'] ?>').ajaxForm(options);
                         }*/
                        $(document).ready(function () {
                            var options = {
                                target: '.highslide-maincontent #senduserdiv_<?= $product['id'] ?>', // target element(s) to be updated with server response
                                //beforeSubmit:  showRequest,  // pre-submit callback
                                success: showResponse_<?= $product['id'] ?>  // post-submit callback

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
                            $('#senduser_<?= $product['id'] ?>').ajaxForm(options);
                        });
                        function enableAjaxFormSendUser_<?= $product['id'] ?>() {

                            var options = {
                                target: '.highslide-maincontent #senduserdiv_<?= $product['id'] ?>', // target element(s) to be updated with server response
                                //beforeSubmit:  showRequest,  // pre-submit callback
                                success: showResponse_<?= $product['id'] ?>  // post-submit callback

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
                            $('#senduser_<?= $product['id'] ?>').ajaxForm(options);
                        }
                        function showResponse_<?= $product['id'] ?>(responseText, statusText, xhr, $form) {
                            var options = {
                                target: '.highslide-maincontent #senduserdiv_<?= $product['id'] ?>', // target element(s) to be updated with server response
                                //beforeSubmit:  showRequest,  // pre-submit callback
                                success: showResponse_<?= $product['id'] ?>  // post-submit callback

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
                            $('.highslide-container #senduser_<?= $product['id'] ?>').ajaxForm(options);
                            $('.senduser_<?= $product['id'] ?>').each(function () {
                                $(this).ajaxForm(options);
                            });
                        }
                    </script>
                    <div id="senduserdiv_<?= $product['id'] ?>"><form id="senduser_<?= $product['id'] ?>" action="/product/<?=  $product['slug'] ?>/senduser" method="post">
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
                                                Ваш телефон*:
                                            </td>
                                            <td style="padding: 5px 0;text-align: left;">
                                                <input type="text" name="phone" value="<? /* = sfContext::getInstance()->getRequest()->getParameter("phone") != "" ? sfContext::getInstance()->getRequest()->getParameter("phone") : $phoneSenduser */ ?>" style="width: 254px;">
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
                                <a  style="margin-top: 20px; margin-left: 155px;" class = "red-btn colorWhite" href = "#" onclick = "enableAjaxFormSendUser_<?= $product['id'] ?>();
                                        $('#senduser_<?= $product['id'] ?>').submit();
                                        setTimeout('enableAjaxFormSendUser_<?= $product['id'] ?>()', 500);
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
        if (@in_array($product['id'], $products_desire)) {
            ?>
            <a class="greenButtonAddToDesire tipDesire" href='/desire' data-title="Добавлен в список желаний"></a>
            <?
        } else {
            ?>
            <div class="redButtonAddToDesire tipDesire" onClick='javascript: $(this).after($("<a class=\"greenButtonAddToDesire tipDesire\"  data-title=\"Добавлен в список желаний\">").attr("href", "/desire")).remove();
                    addToCartAnim("Jel", "#photoimg_<?= $product['id'] ?>", true);
                    $("#JelHeader").load("/cart/addtodesire/<?= $product['id'] ?>");
                    $(".productInDesire-<?= $product['id'] ?>").fadeIn(0);' data-title="Добавить в список желаний"></div>
             <? } ?>

        <? if (@in_array($product['id'], $products_compare)) {
            ?>
            <a class="greenButtonAddToCompare tipCompare" href='/compare' data-title="Добавлен в сравнение"></a>
            <?
        } else {
            ?>
            <div class="redButtonAddToCompare tipCompare" onClick='javascript: $(this).after($("<a class=\"greenButtonAddToCompare tipCompare\"  data-title=\"Добавлен в сравнение\">").attr("href", "/compare")).remove();
                    // addToCartAnim("Jel", "#photoimg_<?= $product['id'] ?>", true);
                    $.post("/cart/addtocompare/<?= $product['id'] ?>");
                    $(".productInCompare-<?= $product['id'] ?>").fadeIn(0);' data-title="Добавить в сравнение"></div>
             <? } ?>

        <?
        $timer->addTime();
        ?>
    </div>
</div>
