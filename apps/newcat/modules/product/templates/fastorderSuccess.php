<?php
slot('metaTitle', "Быстрый заказ");
slot('metaKeywords', "Быстрый заказ");
slot('metaDescription', "Быстрый заказ");
if ($captcha_error != ""):
    ?>
    <div style="display: block" class="highslide-maincontent" id="ContentFastOrder"> <img src="/images/logoHightOrder.png" style="float:left;left: -7px; position: relative;">
        <div style="text-align: justify; color: #5a0fac;">Оставьте свои контактные данные, наши менеджеры свяжутся с вами в течение 20 минут и оформят ваш заказ максимально быстро и правильно. А так же сделают вам <span style="color: red;">скидку 10%</span> от суммы вашего заказа. Менеджеры с удовольствием проконсультируют вас по всем интересующим вопросам.</div>

        <form method="post" action="/fastorder/<?= $product->getId() ?>" id="fastOrder">
            <div style="clear: both">
                <table width="100%" class="noBorder">
                    <tbody><tr>
                            <td width="120" style="color:#0174fd;padding: 5px 0;text-align: left;">
                                Представьтесь<span style="color: red;">*</span>
                            </td>
                            <td style="padding: 5px 0;text-align: left;">
                                <input type="text" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #5a0fac;" name="name" value="<?= $name ?>">
                            </td>
                        </tr>
                        <tr>
                            <td width="120" style="color:#0174fd;padding: 5px 0;text-align: left;">
                                Ваш телефон<span style="color: red;">*</span>
                            </td>
                            <td style="padding: 5px 0;text-align: left;">
                                <input type="text" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #5a0fac;" name="phone" value="<?= $phone ?>">
                            </td>
                        </tr>
                        <tr>
                            <td width="120" style="color:#0174fd;padding: 5px 0;text-align: left;">
                                Ваш e-mail
                            </td>
                            <td style="padding: 5px 0;text-align: left;">
                                <input type="text" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #5a0fac;" name="mail" value="<?= $mail ?>">
                            </td>
                        </tr>
                        <tr>
                            <td width="120" style="color:#0174fd;padding: 5px 0;text-align: left;">
                                Введите символы<span style="color: red;">*</span>
                            </td>
                            <td style="padding: 5px 0;text-align: left;">
                                <img src="/captcha/smallcaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" border="0"><br>
                                <div align="center" class="error">
                                    Неправильно введены контрольные символы. <br>Попробуйте еще раз.<br>

                                    <input type="text" name="cText"  style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #5a0fac;" id="cText">
                                    </td>
                                    </tr>
                                    </tbody></table>
                                    <center>
                                        <input type="submit" id="addToBasket" value="Отправить запрос" style="width: 198px; position: relative; left: 0px; top: 0px; margin: 5px 0px 7px 0px;">
                                    </center>

                                </div>
                                </form>
                                <div style="text-align: justify; color: #5a0fac;">
                                    Обработка быстрых заказов осуществляется с 10 до 18 часов только в рабочии дни.
                                    <br><span style="font-size: 10px;">Поля отмеченные * обязательны для заполнения.</span>
                                </div>

                                </div>
                            <?php else: ?>
                                <?
                                /* $pixel = PageTable::getInstance()->findOneBySlug("pikseli-bystrogo-zakaza");
                                  echo str_replace(array('{$price}','{$uidAdmitad}'),array($product->getPrice(),sfContext::getInstance()->getRequest()->getCookie('uidAdmitad')),$pixel->getContent()); */
                                ?>

                                <?/* if ($order->getReferal() == "2801045062") { //117612 - onona.ru - Проверка обновления трекинг-кода?>
                                    <img alt="" height="1" src="http://ad.admitad.com/register/f0be12d9c8/script_type/img/payment_type/sale/product/1/cart/<?= $product->getPrice() ?>/order_id/<?= $order->getId() ?>/uid/<?= sfContext::getInstance()->getRequest()->getCookie('uidAdmitad') ?>/" width="1" />
                                <? } */?>
                                <? if ($order->getReferal() == "2764355315") { ?>
                                         <img src="http://a47.myragon.ru/track/id/<?= $order->getId() ?>/key/<?= md5('604' . $order->getId()) ?>/target/t1">
                                     <? } ?>
                                Ваш запрос отправлен. Скоро наш менеджер с Вами свяжется.<br />
                                <a href="/product/<?= $product->getSlug() ?>">Вернуться к просмотру товара "<?= $product->getName() ?>"</a>
                            <?php endif; ?>
