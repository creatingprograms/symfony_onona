<?
if ((strtotime($product->getEndaction()) + 86399) < time() and $product->getEndaction() != "") {
    $product->setEndaction(NULL);
    if ($product->getDiscount() > 0) {
        $product->setPrice($product->getOldPrice());
        $product->setOldPrice(Null);
    }
    $product->setDiscount(0);
    $product->setBonuspay(NULL);
    $product->setStep(NULL);
    $product->save();
}
$photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
$comments = Doctrine_Core::getTable('Comments')
        ->createQuery('c')
        ->where("is_public = '1'")
        ->addWhere('product_id = ?', $product->getId())
        ->orderBy('id ASC')
        ->execute();
?><li<? /* echo " style=\"margin:0 11px 32px 11px;\""; */ //If ($last) echo " style=\"margin: 0 5px; 12px\""; else echo " style=\"margin: 0 24px 12px 5px\"" ?>>

    <div class="t"></div>
    <div class="c">
        <div class="content">
            <? if ($product->getBonuspay() > 0) { ?>
                <div style="position: absolute; left: 0px; top: 63px;z-index: 10; cursor:pointer;" onClick="$('#bonusDiscountText_<?= $product->getId() ?>').toggle()">
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
                echo '<span class="sale' . $product->getBonuspay() . '">-' . $product->getBonuspay() . '%</span>';
            } elseif (strtotime($product->getCreatedAt()) <= time() - (csSettings::get('logo_new') * 24 * 60 * 60) and $product->getEndaction() != "") {
                ?>
                <div style="position: absolute; left: -13px; top: 40px;z-index: 10;" class="loveprice" data-title="Лучшая цена в Рунете!"><a href="/category/Luchshaya_tsena"><img src="/newdis/images/loveprice.png" alt="Лучшая цена"></a></div>
            <? } ?>
            <?= $product->getDiscount() > 0 ? '<span class="sale' . $product->getDiscount() . '">-' . $product->getDiscount() . '%</span>' : ''; ?>

            <?= strtotime($product->getCreatedAt()) > time() - (csSettings::get('logo_new') * 24 * 60 * 60) ? '<span class="newProduct"></span>' : ''; ?>
            <?= ($product->getVideo() != '' or $product->getVideoenabled() ) ? '<span class="videoProduct" title="К этому товару есть видео-презентация"></span>' : ''; ?>

            <div class="title"><a href="/product/<?= $product->getSlug() ?>"><? mb_internal_encoding('UTF-8'); ?><?= mb_substr($product->getName(), 0, 55) ?></a></div>
            <div class="img-holder">
                <a href="/product/<?= $product->getSlug() ?>">
                    <? If ($prodNum < 6) { ?>
                        <img id="photoimg_<?= $product->getId() ?>" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' title='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' class="thumbnails" />
                    <? } else { ?>
                        <img id="photoimg_<?= $product->getId() ?>" src="/images/pixel.gif" data-original="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' title='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' class="thumbnails" />
                    <? } ?>
                </a>
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
                        <span class="time actionProduct_<?= $product->getId() ?>"></span>
                        <?php
                        $step = array("1 сутки" => "1", "2 суток" => "2", "3 суток" => "3", "4 суток" => "4", "5 суток" => "5");
                        if ($product->getStep() != "") {
                            //echo $product->getEndaction() - time() + 24 * 60 * 60;
                            if ((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) > $step[$product->getStep()] * 24 * 60 * 60) {
                                $i = 0;
                                while ((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) > $i * $step[$product->getStep()] * 24 * 60 * 60) {
                                    $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()) - $i * $step[$product->getStep()] * 24 * 60 * 60);
                                    $i++;
                                }
                            } else {
                                $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()));
                            }
                        } else {
                            $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()));
                        }
                        ?>
                        <script type="text/javascript">
                                $("#countdownSRC").load(function () {
                                    $('.countdown .actionProduct_<?= $product->getId() ?>').countdown({
                                        //format: 'd hh:mm:ss',
                                        format: 'hh:mm:ss',
                                        compact: true,
                                        description: '',
                                        until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                                //timezone: +6
                                    });
                                });
                                if (typeof ($.fn.countdown) !== 'undefined') {
                                    $('.countdown .actionProduct_<?= $product->getId() ?>').countdown({
                                        //format: 'd hh:mm:ss',
                                        format: 'hh:mm:ss',
                                        compact: true,
                                        description: '',
                                        until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                                //timezone: +6
                                    });
                                }
                            /*$(document).ready(function () {
                                $('.countdown .actionProduct_<?= $product->getId() ?>').countdown({
                                    //format: 'd hh:mm:ss',
                                    format: 'hh:mm:ss',
                                    compact: true,
                                    description: '',
                                    until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                            //timezone: +6
                                });
                            });*/
                        </script>
                    </div>
                    <? Endif; ?>
            </div>
            <div class="bottom-box">
                <div class="row">
                    <? if ($comments->count() > 0) { ?><a href="/product/<?= $product->getSlug() ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $comments->count() ?></a><? } ?>
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
                <div class="tools">
                    <a href="#" class="att" title="Быстрый просмотр товара"></a><?php if ($product->getCount() > 0): ?>
                        <a href="#" id="buttonId_<?= $product->getId() ?>" class="red-btn small to-card  colorWhite" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', {quantity: 1}, function (data) {
                                    $.post('/cart/addtocart/<?= $product->getId() ?>', {quantity: 1}, function (data) {
                                    });
                                });
                        <? /* addToCartAnim('Cart', '#photoimg_<?= $product->getId() ?>', true); */ ?>
                                addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');
                                changeButtonToGreen(<?= $product->getId() ?>);

                                return false;
                           " title="Купить">

                            <span>В корзину</span>
                        </a>
                        <a href="#" class="to-desire" title="Добавьте товар в список своих желаний и вы сможете легко вернуться к просмотру данного товара, удалить из списка желаний или заказать его" onClick='javascript: addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                                $("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");'></a>

                       <?php else: ?>
                        <a id="addToBasket1-<?= $product->getId() ?>" class="red-btn to-card small  colorWhite"
                          onclick="
                            $('.captchasu').attr('src', $('.captchasu').attr('data-original'));
                            return hs.htmlExpand(this, {contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar',
                                    headingText: '', width: 553, height: 400, slideshowGroup: 'groupToSend', left: -9});
                                "
                           class="highslide" style=""/><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;">Сообщить о поступлении</span></a>

<?php endif; ?>
                </div>
            </div>
            <div class="popup-content" style="display:none">

            </div>
        </div>
    </div>
    <div class="b"></div>
<?php if ($product->getCount() < 1): ?>
        <!--
           Сообщить о поступлении
        -->
        <div style="display: none" class="highslide-maincontent" id="ContentToSend_<?= $product->getId() ?>">
            <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>

            <div style="color: #C3060E; font: 17px/21px Tahoma,Geneva,sans-serif;margin-bottom: 10px; text-align: center;">Сообщить о поступлении товара</div>

    <?php if (!$errorCapSu and $sf_params->get('senduser')): ?>
                <div style="width:100%; text-align: center;">Спасибо за запрос. Вам будет сообщено о поступление товара.</div>
    <?php else: ?>
                <script>
                    $(document).ready(function () {
                        var options = {
                            target: '.highslide-maincontent #senduser_<?= $product->getId() ?>', // target element(s) to be updated with server response
                            //beforeSubmit:  showRequest,  // pre-submit callback
                            success: showResponse_<?= $product->getId() ?>  // post-submit callback

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

                    function showResponse_<?= $product->getId() ?>(responseText, statusText, xhr, $form) {
                        var options = {
                            target: '.highslide-maincontent #senduser_<?= $product->getId() ?>', // target element(s) to be updated with server response
                            //beforeSubmit:  showRequest,  // pre-submit callback
                            success: showResponse_<?= $product->getId() ?>  // post-submit callback

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

                        $('.senduser_<?= $product->getId() ?>').each(function () {
                            $(this).ajaxForm(options);
                        });
                    }
                </script>
                <form id="senduser_<?= $product->getId() ?>" class="senduser_<?= $product->getId() ?>"  action="/product/<?= $product->getSlug() ?>/senduser" method="post">
                    Данный товар скоро появится в продаже на сайте. Чтобы своевременно узнать об этом, нужно отправить нам запрос. Для этого в соответствующих полях напишите свое имя, e-mail и нажмите на кнопку "Отправить запрос". Как только товар поступит на склад, мы отправим уведомление на указанный вами e-mail.<br /><br />
                    <div style="clear: both; color:#4e4e4e; text-align: left;">
                        <table style=" width: 100%" class="noBorder">
                            <tbody><tr>
                                    <td style="width: 120px;padding: 5px 0;text-align: left;">
                                        Представьтесь*:
                                    </td>
                                    <td style="padding: 5px 0;text-align: left;">
                                        <input type="text" name="name" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") ?>" style="width: 254px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 120px; padding: 5px 0;text-align: left;">
                                        Ваш e-mail*:
                                    </td>
                                    <td style="padding: 5px 0;text-align: left;">
                                        <input type="text" name="mail" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") ?>" style="width: 254px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 120px; padding: 5px 0;text-align: left;">
                                        Введите код*:
                                    </td>
                                    <td style="padding: 5px 0;text-align: left;">

                                        <img  class="captchasu" src="/images/pixel.gif" data-original="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />
                                        <input type="text" name="sucaptcha" style="position: relative; top: -45px; width: 130px;">
                                        <?php if ($errorCapSu) { ?><br />
                                            <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                            <br />
        <?php } ?>
                                    </td>
                                </tr>
                            </tbody></table>
                        <span style="font-size: 10px;">* - обязательны для заполнения.</span>
                        <a  style="margin-top: 20px; margin-left: 155px;" class = "red-btn colorWhite" href = "#" onclick = "$('#senduser_<?= $product->getId() ?>').submit();
                                return false;"><span style = "width: 195px;">Отправить запрос</span></a>

                    </div>

                </form>
    <?php endif; ?>
        </div>
        <!--
    Сообщить о поступлении
        -->
        <? Endif; ?>
    <?
    If ($showarticle) {
        echo $product->getCode();
    }
    ?>
</li>
