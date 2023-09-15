
<h1 style="text-align: center; font-size: 18px; color:#C3060E; font-weight: normal;">Оценка качества работы магазина.</h1>
<?
if ((($sf_request->isMethod(sfRequest::POST) and ! $errorCap) or $orderExist)) {
    ?>Спасибо за отзыв.<?
} else {
    ?>
    <form action="" method="POST" id="oprosnik">
        <?= csSettings::get('top_oprosnik') ?>
        <table style="width: 100%; border: 0px;" class="noBorder oprosnik-table">
            <tr>
                <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Оценка Продавца-консультанта</td><td style="padding-top: 50px;"></td>
            </tr>
            <tr>
                <td>Подошел ли к Вам Продавец-консультант сразу?</td><td><select name="managerComunication[]" class="styledSelect">
                        <option></option>
                        <option>да</option>
                        <option>только после Вашей просьбы</option>
                    </select><br /><br />

                    Другое: <input type="text" name="managerComunication[other]" style="
                                                                                         width: 363px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
                </td>
            </tr>
            <tr>
                <td>Рекомендовал ли Продавец-консультант товар, или Вы осуществили свой выбор самостоятельно?</td><td><select name="managerRecomendation" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>Частично</option>
                        <option>Нет</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Предложил ли ознакомится, посмотреть и потрогать предлагаемый товар?</td><td><select name="managerViewProd" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>только после просьбы</option>
                        <option>Нет</option>
                    </select>


                </td>
            </tr>
            <tr>
                <td>Предложил ли несколько вариантов товара?</td><td><select name="managerListProduct" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>только после просьбы</option>
                        <option>Нет</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td>Рассказал ли о особенностях эксплуатации материалов?</td><td><select name="managerMaterial" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>только после просьбы</option>
                        <option>Нет</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Предложил ли Продавец-консультант заказать товар специально для Вас?</td><td><select name="managerOrderProd" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>только после просьбы</option>
                        <option>Нет</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td>Поинтересовался ли наличием дисконтной карты?</td><td><select name="managerDiscountCard" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>Нет</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td>Предложил ли воспользоваться скидками?</td><td><select name="managerDiscount" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>только после просьбы</option>
                        <option>Нет</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td>Записал ли для Продавец-консультант интересующий Вас товар (При отсутствии товара на данный момент)?</td><td><select name="managerWriteProduct" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>только после просьбы</option>
                        <option>Нет</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td>В целом Продавец-консультант был вежлив с Вами?</td><td><select name="managerPolite" class="styledSelect">
                        <option></option>
                        <option>Отлично</option>
                        <option>Хорошо</option>
                        <option>Так себе</option>
                        <option>Плохо</option>
                        <option>Ужасно!</option>
                    </select></td>
            </tr>
            <tr>
                <td>Оцените, пожалуйста, Ваше впечатление от Продавца-консультанта (внешний вид, подача и изложение описания товара и т.д.)</td><td><select name="managerBall" class="styledSelect">
                        <option></option>
                        <option>Отлично</option>
                        <option>Хорошо</option>
                        <option>Так себе</option>
                        <option>Плохо</option>
                        <option>Ужасно!</option>
                    </select></td>
            </tr>
            <tr>
                <td>Захотелось ли Вам вернуться за будущей покупкой именно к этому Продавцу-консультанту?</td><td><select name="managerReturn" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>Нет</option>
                    </select>

                </td>
            </tr>
            <tr><td></td><td><div id="commentManagerAdd" style="border-bottom: 2px dashed #AAA;float: left; cursor: pointer;display: none;" onClick="if ($(this).text() == 'Здесь Вы можете оставить свои комментарии к оценке +') {
                            $(this).text('Здесь Вы можете оставить свои комментарии к оценке -');
                            $('[name=commentManager]').fadeIn();
                        } else {
                            $(this).text('Здесь Вы можете оставить свои комментарии к оценке +');
                            $('[name=commentManager]').fadeOut();
                        }">Здесь Вы можете оставить свои комментарии к оценке +</div>
                    <textarea name="commentManager" style="border-radius: 5px 5px 5px 5px;width: 416px; height: 100px; border: 1px solid #EEEEEE;display: none;"></textarea></td>
            </tr>
            <tr>
                <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Оценка Магазина</td><td style="padding-top: 50px;"></td>
            </tr>
            <tr>
                <td>Ваше впечатления от Магазина/Продавца-консультанта</td><td><input type="text" name="shopImpression" style="
                                                                                      width: 413px;
                                                                                      border-radius: 2px;
                                                                                      border: 1px solid #eee;
                                                                                      height: 17px;
                                                                                      ">


                </td>
            </tr>
            <tr>
                <td>Будете ли Вы рекомендовать знакомым данный магазин?</td><td><select name="shopRecomendation" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>Нет</option>
                    </select></td>
            </tr>
            <tr>
                <td>Что, на Ваш взгляд, не хватает для идеальной работы Магазина?</td><td><input type="text" name="shopMissing" style="
                                                                                                 width: 413px;
                                                                                                 border-radius: 2px;
                                                                                                 border: 1px solid #eee;
                                                                                                 height: 17px;
                                                                                                 ">

                </td>
            </tr>
            <tr>
                <td>Какую услугу Вы рекомендовали бы добавить в Магазине?</td><td><input type="text" name="shopAddService" style="
                                                                                         width: 413px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
            </tr>
            <tr>
                <td>Укажите главное достоинство нашего Магазина для себя?</td><td>
                    <input type="checkbox" name="shopQuality[]" value="Эксклюзивность , оригинальность вещей">Эксклюзивность , оригинальность вещей<Br>
                    <input type="checkbox" name="shopQuality[]" value="Выгодные / привлекательные цены, акции">Выгодные / привлекательные цены, акции<Br>
                    <input type="checkbox" name="shopQuality[]" value="Высокое качество товара">Высокое качество товара<Br>
                    <input type="checkbox" name="shopQuality[]" value="Наличие известных брендов">Наличие известных брендов<Br>
                    <input type="checkbox" name="shopQuality[]" value="Качественное обслуживания , хороший сервис">Качественное обслуживания , хороший сервис<Br>
                    Другое: <input type="text" name="shopQuality[other]" style="
                                                                                         width: 363px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
                    </td>
            </tr>
            <tr>
                <td>Что влияет на ваш выбор Магазина?</td><td>
                    <input type="checkbox" name="shopChoiceReason[]" value="Удобство расположения">Удобство расположения<Br>
                    <input type="checkbox" name="shopChoiceReason[]" value="Доступные цены">Доступные цены<Br>
                    <input type="checkbox" name="shopChoiceReason[]" value="Качество товара">Качество товара<Br>
                    <input type="checkbox" name="shopChoiceReason[]" value="Актуальность коллекций">Актуальность коллекций<Br>
                    <input type="checkbox" name="shopChoiceReason[]" value="Качество обслуживания">Качество обслуживания<Br>
                    Другое: <input type="text" name="shopChoiceReason[other]" style="
                                                                                         width: 363px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
                    </td>
            </tr>

            <tr>
                <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Оценка полок в Магазине</td><td style="padding-top: 50px;"> </td>
            </tr>
            <tr>
                <td> Оцените, пожалуйста, ассортимент Магазина (на сколько полный ассортимент)</td><td><select name="shopProductChoice" class="styledSelect">
                        <option></option>
                        <option>Отлично</option>
                        <option>Хорошо</option>
                        <option>Так себе</option>
                        <option>Плохо</option>
                        <option>Ужасно!</option>
                    </select></td>
            </tr>
            <tr>
                <td>Вы приобрели, что хотели?</td><td><select name="payProd" class="styledSelect">
                        <option></option>
                        <option>Да</option>
                        <option>Нет</option>
                    </select>                    <br/><br/>
                    Если нет, то почему: <input type="text" name="payProdReason" style="
                                                                                         width: 283px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
                </td>
            </tr>
            <tr>
                <td>Как вы охарактеризуете цены в нашем Магазине?</td><td><select name="productPrice" class="styledSelect">
                        <option></option>
                        <option>Низкие</option>
                        <option>Доступные</option>
                        <option>Высокие</option>
                    </select>                    <br/><br/>
                    Иное: <input type="text" name="productPriceOther" style="
                                                                                         width: 375px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
                </td>
            </tr>
            <tr>
                <td>Если Вы частый Покупатель, скажите, какой товар у Вас пользуется большим спросом?</td><td><input type="text" name="prodGreaterDemand" style="
                                                                                         width: 413px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
            </tr>
            <tr>
                <td>Что Вы хотели бы видеть на полках в нашем Магазине? (определенный производитель, определенную серию, определенный товар)</td><td><input type="text" name="addShop" style="
                                                                                         width: 413px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
            </tr>
            <tr>
                <td>Какой товар на Ваш взгляд необходимо добавить в ассортимент?</td><td><input type="text" name="prodAddShop" style="
                                                                                         width: 413px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
            </tr>
            <tr>
                <td>Какая дополнительная услуга с приобретением товара Вас может заинтересовать? </td><td>
                    <input type="checkbox" name="shopAddServicePayProd[]" value="Получение подарка к покупке">Получение подарка к покупке<Br><Br>
                    <input type="checkbox" name="shopAddServicePayProd[]" value="Круглосуточная доставка товара">Круглосуточная доставка товара<Br><Br>
                    <input type="checkbox" name="shopAddServicePayProd[]" value="Подарочная упаковка товара (не фирменный или подарочный пакет, а именно подарочная – нарядная бумага или коробка, банты, шарики…)">Подарочная упаковка товара (не фирменный или подарочный пакет, а именно подарочная – нарядная бумага или коробка, банты, шарики…)<Br><Br>
                    <input type="checkbox" name="shopAddServicePayProd[]" value="Услуга «С праздником» С днем Рождения. С 23 Февраля и 8-м Марта и т.д. (когда курьер доставляет товар виновнику торжества и поздравляет с праздником. Возможно читает стихи, говорит поздравительную речь)">Услуга «С праздником» С днем Рождения. С 23 Февраля и 8-м Марта и т.д. (когда курьер доставляет товар виновнику торжества и поздравляет с праздником. Возможно читает стихи, говорит поздравительную речь)<Br><Br>
                    <input type="checkbox" name="shopAddServicePayProd[]" value="Возможность посещения тренингов по сексологии, техникам секса, психологии отношений между парами">Возможность посещения тренингов по сексологии, техникам секса, психологии отношений между парами<Br><Br>
                    <input type="checkbox" name="shopAddServicePayProd[]" value="Сертификат «Замена товара в течении …год, 6 месяцев» Пример: Игрушка стоит 10000,00 рублей, покупатель приобретает Сертификат за цену закупки+50% наценки.и">Сертификат «Замена товара в течении …год, 6 месяцев» Пример: Игрушка стоит 10000,00 рублей, покупатель приобретает Сертификат за цену закупки+50% наценки.<Br><Br>
                    <input type="checkbox" name="shopAddServicePayProd[]" value="Периодическое получение Издания LoveStyle">Периодическое получение Издания LoveStyle<Br><Br>
                    Если возможно, предложите, пожалуйста, свой вариант:<br/> <input type="text" name="shopAddServicePayProd[other]" style="
                                                                                         width: 413px;
                                                                                         border-radius: 2px;
                                                                                         border: 1px solid #eee;
                                                                                         height: 17px;
                                                                                         "> </td>
            </tr>
            <tr>
                <td>Что Вы могли бы порекомендовать нам?</td><td><textarea name="recomendation" style="width: 416px; height: 100px; border: 1px solid #EEEEEE;border-radius: 5px 5px 5px 5px;"></textarea></td>
            </tr><?/*
            <tr>
                <td colspan="2">        <?= csSettings::get('center_oprosnik') ?></td>
            </tr>
            <?
            $products_old = $order->getText();
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            $i = 0;
            foreach ($products_old as $key2 => $productInfo):
                $i++;
                if (isset($productInfo['article'])) {
                    $product = ProductTable::getInstance()->findOneByCode($productInfo['article']);
                }
                if (isset($productInfo['productId']) and ! $product) {
                    $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                }
                if ($product) {
                    if ($product->getSlug() != "dostavka") {
                        $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
                        if ($i == 1) {
                            ?>
                            <tr>
                                <td colspan="2">Вы заказали:</td>
                            </tr><? }
                        ?>
                        <tr>
                            <td><table><tr><td><img border="0" class="item_picture" alt="<?= $product->getName() ?>" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>" height="100">
                                        </td><td><a href="/product/<?= $product->getSlug() ?>" target="_blank"><?= $product->getName() ?></a>

                                            <script>
                                                $(document).ready(function () {
                                                    $('#rate_div_comment_<?= $product->getId() ?>').starRating({
                                                        basicImage: '/images/star.gif',
                                                        ratedImage: '/images/star_hover.gif',
                                                        hoverImage: '/images/star_hover2.gif',
                                                        ratingStars: 10,
                                                        ratingUrl: '/product/rate',
                                                        paramId: 'product',
                                                        paramValue: 'value',
                                                        rating: '0',
                                                        sucessData: function (data) {
                                                            $.fn.starRating.clickable["rate_div_comment_<?= $product->getId() ?>"] = true;
                                                            $.fn.starRating.hoverable["rate_div_comment_<?= $product->getId() ?>"] = false;
                                                            $("#cRate_<?= $product->getId() ?>").val(data);
                                                            $("#cRate_<?= $product->getId() ?>").get(0).setCustomValidity('');
                                                        },
                                                        customParams: {productId: '<?= $product->getId() ?>', nonAdd: '1'}

                                                    });
                                                });</script>
                                            <div style='clear: both; height: 10px;'></div>
                                            <div id="rate_div_comment_<?= $product->getId() ?>"></div>
                                            <input type="text" oninvalid="this.setCustomValidity('Укажите рейтинг товара')" oninput="this.setCustomValidity('')"  name="cRate[<?= $product->getId() ?>]" id="cRate_<?= $product->getId() ?>" data-describedby="comment-description-<?= $product->getId() ?>" data-required="true" data-description="comments" class="required"  style="border: 0;width: 1px; height: 0px;" />
                                            <div id="comment-description-<?= $product->getId() ?>" class="requeredDescription"></div>

                                        </td></tr></table>

                            </td><td><textarea name="comments[<?= $product->getId() ?>]" style="border-radius: 5px 5px 5px 5px;width: 416px; height: 100px; border: 1px solid #EEEEEE;"></textarea></td>
                        </tr>
                        <?
                    }
                }
            endforeach;
            ?>*/?></table>

        <?= csSettings::get('footer_oprosnik') ?><br /><br />
        <? /* <table class="noBorder"><tr><td>
          Введите код с картинки: </td><td><? If ($errorCap) { ?>
          <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
          <br /><? } ?><img width="139" height="48" alt="captcha" class="captchao" src="/captcha/ocaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>">
          <br />
          <input type="text" name="captcha" style="border-radius: 5px 5px 5px 5px; border: 1px solid #EEEEEE;width: 139px;" />
          </td></tr></table> */ ?>
        <input type="submit" class="sendOprosnikButton" style="display: none;">
        <a class="red-btn colorWhite oprosnik-button" href="#" onClick="$('.sendOprosnikButton').trigger('click');
                    /*$('#commentForm').submit();*/

                    return false;"><span style="width: 150px;">Отправить</span></a>
    </form>
<? }
?>
