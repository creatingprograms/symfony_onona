<? if ($order->getStatus() == "Оплачен"): ?>
  <script src="/js/jquery.starRating.js"></script>
    <? /* <script async="" src="http://onona.ru/newdis/js/selectstyle.js"></script> */ ?>
    <script src="/js/jquery-validate.js"></script>

    <script>
      $(document).ready(function () {
        $("#oprosnik").submit(function () {
            if (validateRating())
                return true
            else
                return false;
        });

        $('form#oprosnik').validate({
            onKeyup: true,
            sendForm: true,
            eachValidField: function () {

                $(this).closest('div').removeClass('error').addClass('success');
            },
            eachInvalidField: function () {

                $(this).closest('div').removeClass('success').addClass('error');
            },
            description: {
                comments: {
                    required: '<div class="alert alert-error">Укажите рейтинг товара</div>',
                    pattern: '<div class="alert alert-error">Pattern</div>',
                    conditional: '<div class="alert alert-error">Conditional</div>',
                    valid: '<div class="alert alert-success">Спасибо</div>'
                }
            }
        });
      });

      function validateRating() {
        //testing regular expression
        if ($("select[name=shopBall] option:selected").text().length < 2) {
            $("select[name=shopBall]").addClass("error");
            $("#ratingShopStat").text("Обязательное поле для заполнения");
            $("#ratingShopStat").addClass("error");
            return false;
        }
        //if it's valid
        else {
            $("select[name=shopBall]").removeClass("error");
            $("#ratingShopStat").text("");
            $("#ratingShopStat").removeClass("error");
            return true;
        }
      }
    </script>
    <h1 style="text-align: center; font-size: 18px; color:#C3060E; font-weight: normal;">Оценка качества работы магазина. № заказа <?= $order->getPrefix() ?><?= $order->getId() ?></h1>
    <? if ((($sf_request->isMethod(sfRequest::POST) and ! $errorCap) or $orderExist)) { ?>
      Спасибо за отзыв.
    <? }
    else { ?>
        <form action="" method="POST" id="oprosnik">
        <?= csSettings::get('top_oprosnik') ?>
            <table style="width: 100%; border: 0px;" class="noBorder oprosnik-table">
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Работа менеджеров</td><td style="padding-top: 50px;">
                        <script type='text/javascript'>

                            function selectManager(star) {
                                switch ($("select[name=managerBall] :selected").text()) {
                                    case 'Отлично':
                                        bal = 5;
                                        break;
                                    case 'Хорошо':
                                        bal = 4;
                                        break;
                                    case 'Так себе':
                                        bal = 3;
                                        break;
                                    case 'Плохо':
                                        bal = 2;
                                        break;
                                    case 'Ужасно!':
                                        bal = 1;
                                        break;
                                }
                                $('#managerWork').val(bal);
                                if (bal < 4) {
                                    $("#commentManagerAdd").fadeIn();
                                } else {
                                    $("#commentManagerAdd").fadeOut();
                                }
                                $(".managerWork").each(function (index) {
                                    if (index < bal) {
                                        $(this).attr('src', '/images/star_select.png');
                                    } else {
                                        $(this).attr('src', '/images/star_noselect.png');
                                    }
                                });
                            }
                        </script>
                        <input type="hidden" name="managerWork" id="managerWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="managerWork"></td>
                </tr>
                <tr>
                    <td>Насколько корректно и вежливо с Вами общался менеджер?</td><td><select name="managerComunication" class="styledSelect">
                            <option></option>
                            <option>Очень профессионально</option>
                            <option>Отчасти профессионально</option>
                            <option>Непрофессионально</option>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td>Смог ли менеджер проконсультировать Вас по всем Вашим вопросам?</td><td><select name="managerAdvise" class="styledSelect">
                            <option></option>
                            <option>Да</option>
                            <option>Частично</option>
                            <option>Нет</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Как быстро перезвонил Вам менеджер для подтверждения оформленного Вами заказа?</td><td><select name="managerSpeedCalled" class="styledSelect">
                            <option></option>
                            <option>В течение 30 минут</option>
                            <option>В течение 1-го часа</option>
                            <option>В течение 3-х часов</option>
                            <option>Прошло более 3-х часов</option>
                        </select>


                    </td>
                </tr>
                <tr>
                    <td>Если были изменены комплектация или срок доставки заказа, вовремя ли менеджер сообщил Вам об этом?</td><td><select name="managerListProduct" class="styledSelect">
                            <option></option>
                            <option>Изменений не было</option>
                            <option>Вовремя</option>
                            <option>Поздно</option>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td>Если Вы отправляли нам вопросы через онлайн чат или через обратную связь на сайте, достаточно ли быстро Вы получили ответ?</td><td><select name="managerLiveText" class="styledSelect">
                            <option></option>
                            <option>Не отправлял(а)</option>
                            <option>Быстро</option>
                            <option>Пришлось долго ждать</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Если Вы подавали запрос на ремонт/замену товара, достаточно ли быстро наш сервис-менеджер обработал Вашу заявку и связался с Вами?</td><td><select name="managerReturn" class="styledSelect">
                            <option></option>
                            <option>Не подавал(а)</option>
                            <option>Быстро</option>
                            <option>До сих пор не получил(а) ответ</option>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td>Оцените работу менеджеров по пятибалльной шкале</td><td><select name="managerBall" class="styledSelect" onchange="selectManager()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select></td>
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
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Работа службы доставки</td><td style="padding-top: 50px;"> <script type='text/javascript'>

                        function selectDelivery(star) {
                            /*   $('#deliveryWork').val($(star).attr('title'));
                             $( ".deliveryWork" ).each(function( index ) {
                             if(index<$(star).attr('title')){
                             $(this).attr('src','/images/star_select.png');
                             }else{
                             $(this).attr('src','/images/star_noselect.png');
                             }
                             });*/
                            switch ($("select[name=deliveryBall] :selected").text()) {
                                case 'Отлично':
                                    bal = 5;
                                    break;
                                case 'Хорошо':
                                    bal = 4;
                                    break;
                                case 'Так себе':
                                    bal = 3;
                                    break;
                                case 'Плохо':
                                    bal = 2;
                                    break;
                                case 'Ужасно!':
                                    bal = 1;
                                    break;
                            }

                            if (bal < 4) {
                                $("#commentDeliveryAdd").fadeIn();
                            } else {
                                $("#commentDeliveryAdd").fadeOut();
                            }
                            $('#deliveryWork').val(bal);
                            $(".deliveryWork").each(function (index) {
                                if (index < bal) {
                                    $(this).attr('src', '/images/star_select.png');
                                } else {
                                    $(this).attr('src', '/images/star_noselect.png');
                                }
                            });
                        }
                        </script>
                        <input type="hidden" name="deliveryWork" id="deliveryWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="deliveryWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="deliveryWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="deliveryWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="deliveryWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="deliveryWork"></td>
                </tr>
                <tr>
                    <td>Был ли Ваш заказ доставлен Вам в согласованное с нашим менеджером время? </td><td><select name="deliveryTime" class="styledSelect">
                            <option></option>
                            <option>Да</option>
                            <option>Пришлось ждать менее 1-го часа</option>
                            <option>Пришлось ждать менее 3-х часов</option>
                            <option>Пришлось ждать более 3-х часов</option>
                        </select>


                    </td>
                </tr>
                <tr>
                    <td> Корректно ли общался с Вами курьер при доставке Вашего заказа?</td><td><select name="kurerSleng" class="styledSelect">
                            <option></option>
                            <option>Да</option>
                            <option>Нет</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Была ли сохранена целостность упаковки при доставке Вашего заказа?</td><td><select name="productPacket" class="styledSelect">
                            <option></option>
                            <option>Да, не повреждена</option>
                            <option>Немного повреждена</option>
                            <option>Сильно повреждена</option>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td>Был ли Вам предоставлен кассовый чек?</td><td><select name="orderCheck" class="styledSelect">
                            <option></option>
                            <option>Да</option>
                            <option>Нет</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Оцените работу доставки по пятибалльной шкале</td><td><select name="deliveryBall" class="styledSelect" onchange="selectDelivery()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select></td>
                </tr>
                <tr><td></td><td><div id="commentDeliveryAdd" style="border-bottom: 2px dashed #AAA;float: left; cursor: pointer;display: none;" onClick="if ($(this).text() == 'Здесь Вы можете оставить свои комментарии к оценке +') {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке -');
                                    $('[name=commentDelivery]').fadeIn();
                                } else {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке +');
                                    $('[name=commentDelivery]').fadeOut();
                                }">Здесь Вы можете оставить свои комментарии к оценке +</div>
                        <textarea name="commentDelivery" style="border-radius: 5px 5px 5px 5px;width: 416px; height: 100px; border: 1px solid #EEEEEE;display: none;"></textarea></td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Качество товаров</td><td style="padding-top: 50px;"> <script type='text/javascript'>

                        function selectProduct(star) {
                            /*  $('#productWork').val($(star).attr('title'));
                             $( ".productWork" ).each(function( index ) {
                             if(index<$(star).attr('title')){
                             $(this).attr('src','/images/star_select.png');
                             }else{
                             $(this).attr('src','/images/star_noselect.png');
                             }
                             });*/
                            switch ($("select[name=productBall] :selected").text()) {
                                case 'Отлично':
                                    bal = 5;
                                    break;
                                case 'Хорошо':
                                    bal = 4;
                                    break;
                                case 'Так себе':
                                    bal = 3;
                                    break;
                                case 'Плохо':
                                    bal = 2;
                                    break;
                                case 'Ужасно!':
                                    bal = 1;
                                    break;
                            }

                            if (bal < 4) {
                                $("#commentProductAdd").fadeIn();
                            } else {
                                $("#commentProductAdd").fadeOut();
                            }
                            $('#productWork').val(bal);
                            $(".productWork").each(function (index) {
                                if (index < bal) {
                                    $(this).attr('src', '/images/star_select.png');
                                } else {
                                    $(this).attr('src', '/images/star_noselect.png');
                                }
                            });
                        }
                        </script>
                        <input type="hidden" name="productWork" id="productWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="productWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="productWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="productWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="productWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="productWork"></td>
                </tr>
                <tr>
                    <td>Соответствует ли качество и характеристики товаров заявленным (описанию) на сайте? </td><td><select name="qualityProduct" class="styledSelect">
                            <option></option>
                            <option>Да</option>
                            <option>Частично</option>
                            <option>Нет</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Оцените качество товаров по пятибалльной шкале</td><td><select name="productBall" class="styledSelect" onchange="selectProduct()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select></td>
                </tr>
                <tr><td></td><td><div id="commentProductAdd" style="border-bottom: 2px dashed #AAA;float: left; cursor: pointer;display: none;" onClick="if ($(this).text() == 'Здесь Вы можете оставить свои комментарии к оценке +') {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке -');
                                    $('[name=commentProduct]').fadeIn();
                                } else {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке +');
                                    $('[name=commentProduct]').fadeOut();
                                }">Здесь Вы можете оставить свои комментарии к оценке +</div>
                        <textarea name="commentProduct" style="border-radius: 5px 5px 5px 5px;width: 416px; height: 100px; border: 1px solid #EEEEEE;display: none;"></textarea></td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Сайт</td><td style="padding-top: 50px;"> <script type='text/javascript'>

                        function selectWWW(star) {
                            /* $('#wwwWork').val($(star).attr('title'));
                             $( ".wwwWork" ).each(function( index ) {
                             if(index<$(star).attr('title')){
                             $(this).attr('src','/images/star_select.png');
                             }else{
                             $(this).attr('src','/images/star_noselect.png');
                             }
                             });*/
                            //console.log($("select[name=shopBall] :selected").text());
                            switch ($("select[name=wwwBall] :selected").text()) {
                                case 'Отлично':
                                    bal = 5;
                                    break;
                                case 'Хорошо':
                                    bal = 4;
                                    break;
                                case 'Так себе':
                                    bal = 3;
                                    break;
                                case 'Плохо':
                                    bal = 2;
                                    break;
                                case 'Ужасно!':
                                    bal = 1;
                                    break;
                            }

                            if (bal < 4) {
                                $("#commentWWWAdd").fadeIn();
                            } else {
                                $("#commentWWWAdd").fadeOut();
                            }
                            $('#wwwWork').val(bal);
                            $(".wwwWork").each(function (index) {
                                if (index < bal) {
                                    $(this).attr('src', '/images/star_select.png');
                                } else {
                                    $(this).attr('src', '/images/star_noselect.png');
                                }
                            });
                        }
                        </script>
                        <input type="hidden" name="wwwWork" id="wwwWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="wwwWork"></td></tr>
                <tr>
                    <td>Удобно ли пользоваться сайтом?</td><td><select name="wwwEasy" class="styledSelect">
                            <option></option>
                            <option>Удобно</option>
                            <option>Неудобно</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Были ли у Вас трудности с поиском нужного Вам товара на сайте?</td><td><select name="wwwSearchProduct" class="styledSelect">
                            <option></option>
                            <option>Не было трудностей</option>
                            <option>Возникли трудности</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Легко ли Вы нашли информацию по доставке и оплате?</td><td><select name="wwwSearchDelPay" class="styledSelect">
                            <option></option>
                            <option>Легко</option>
                            <option>Возникли трудности</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Удобно ли пользоваться корзиной?</td><td><select name="wwwCart" class="styledSelect">
                            <option></option>
                            <option>Удобно</option>
                            <option>Неудобно</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Оцените работу сайта по пятибалльной шкале</td><td><select name="wwwBall" class="styledSelect" onchange="selectWWW()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select></td>
                </tr>
                <tr><td></td><td><div id="commentWWWAdd" style="border-bottom: 2px dashed #AAA;float: left; cursor: pointer;display: none;" onClick="if ($(this).text() == 'Здесь Вы можете оставить свои комментарии к оценке +') {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке -');
                                    $('[name=commentWWW]').fadeIn();
                                } else {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке +');
                                    $('[name=commentWWW]').fadeOut();
                                }">Здесь Вы можете оставить свои комментарии к оценке +</div>
                        <textarea name="commentWWW" style="border-radius: 5px 5px 5px 5px;width: 416px; height: 100px; border: 1px solid #EEEEEE;display: none;"></textarea></td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Общие вопросы</td><td style="padding-top: 50px;"><script type='text/javascript'>

                        function selectOther() {
                            //console.log($("select[name=shopBall] :selected").text());
                            switch ($("select[name=shopBall] :selected").text()) {
                                case 'Отлично':
                                    bal = 5;
                                    break;
                                case 'Хорошо':
                                    bal = 4;
                                    break;
                                case 'Так себе':
                                    bal = 3;
                                    break;
                                case 'Плохо':
                                    bal = 2;
                                    break;
                                case 'Ужасно!':
                                    bal = 1;
                                    break;
                            }
                            $('#otherWork').val(bal);
                            $(".otherWork").each(function (index) {
                                if (index < bal) {
                                    $(this).attr('src', '/images/star_select.png');
                                } else {
                                    $(this).attr('src', '/images/star_noselect.png');
                                }
                            });
                        }
                        </script>
                        <input type="hidden" name="otherWork" id="otherWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="otherWork"> </td></tr>
                <tr>
                    <td>Сколько раз (примерно) Вы заказывали в нашем интернет-магазине за последний год?</td><td><select name="wwwOrder" class="styledSelect">
                            <option></option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>Больше 3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Оцените по пятибалльной шкале работу магазина* <img src="/images/question_mark.png" height="14" title="Поле обязательно для заполнения"></td><td>
                        <select name="shopBall" class="styledSelect" onchange="selectOther()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select><span id="ratingShopStat"></span></td>
                </tr>
                <tr>
                    <td>Что Вы могли бы порекомендовать нам?</td><td><textarea name="recomendation" style="width: 416px; height: 100px; border: 1px solid #EEEEEE;border-radius: 5px 5px 5px 5px;"></textarea></td>
                </tr>
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
                ?></table>

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

<? elseif ($order->getStatus() == "Отмена"): ?>

    <script> $(document).ready(function () {
            $("#oprosnik").submit(function () {
                if (validateRating())
                    return true
                else
                    return false;
            });
        });


        function validateRating() {
            //testing regular expression
            if ($("select[name=shopBall] option:selected").text().length < 2) {
                $("select[name=shopBall]").addClass("error");
                $("#ratingShopStat").text("Обязательное поле для заполнения");
                $("#ratingShopStat").addClass("error");
                return false;
            }
            //if it's valid
            else {
                $("select[name=shopBall]").removeClass("error");
                $("#ratingShopStat").text("");
                $("#ratingShopStat").removeClass("error");
                return true;
            }
        }
    </script>
    <h1 style="text-align: center; font-size: 18px; color:#C3060E; font-weight: normal;">Оценка качества работы магазина. № заказа <?= $order->getPrefix() ?><?= $order->getId() ?></h1>
    <?
    if ((($sf_request->isMethod(sfRequest::POST) and ! $errorCap) or $orderExist)) {
        ?>Спасибо за отзыв.<?
    } else {
        ?>
        <form action="" method="POST" id="oprosnik">
            Оцените качество работы нашего магазина и уровень квалификации наших сотрудников, заполнив приведенную ниже анкету. Укажите причину отмены Вашего заказа, это поможет нам улучшить наши сервисы и качество обслуживания.
            <br />Мы хотим сделать мир «Он и Она» еще лучше и удобнее, чтобы Вам действительно было хорошо с нами.

            <table style="width: 100%; border: 0px;" class="noBorder oprosnik-table">
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Работа менеджеров</td><td style="padding-top: 50px;">
                        <script type='text/javascript'>

                            function selectManager(star) {
                                /* $('#managerWork').val($(star).attr('title'));
                                 $( ".managerWork" ).each(function( index ) {
                                 if(index<$(star).attr('title')){
                                 $(this).attr('src','/images/star_select.png');
                                 }else{
                                 $(this).attr('src','/images/star_noselect.png');
                                 }
                                 });*/
                                switch ($("select[name=managerBall] :selected").text()) {
                                    case 'Отлично':
                                        bal = 5;
                                        break;
                                    case 'Хорошо':
                                        bal = 4;
                                        break;
                                    case 'Так себе':
                                        bal = 3;
                                        break;
                                    case 'Плохо':
                                        bal = 2;
                                        break;
                                    case 'Ужасно!':
                                        bal = 1;
                                        break;
                                }
                                $('#managerWork').val(bal);
                                if (bal < 4) {
                                    $("#commentManagerAdd").fadeIn();
                                } else {
                                    $("#commentManagerAdd").fadeOut();
                                }
                                $(".managerWork").each(function (index) {
                                    if (index < bal) {
                                        $(this).attr('src', '/images/star_select.png');
                                    } else {
                                        $(this).attr('src', '/images/star_noselect.png');
                                    }
                                });
                            }
                        </script>
                        <input type="hidden" name="managerWork" id="managerWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="managerWork"></td>
                </tr>
                <tr>
                    <td>Насколько корректно и вежливо с Вами общался менеджер?</td><td><select name="managerComunication" class="styledSelect">
                            <option></option>
                            <option>Очень профессионально</option>
                            <option>Отчасти профессионально</option>
                            <option>Непрофессионально</option>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td>Смог ли менеджер проконсультировать Вас по всем Вашим вопросам?</td><td><select name="managerAdvise" class="styledSelect">
                            <option></option>
                            <option>Да</option>
                            <option>Частично</option>
                            <option>Нет</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Как быстро перезвонил Вам менеджер для подтверждения оформленного Вами заказа?</td><td><select name="managerSpeedCalled" class="styledSelect">
                            <option></option>
                            <option>В течение 30 минут</option>
                            <option>В течение 1-го часа</option>
                            <option>В течение 3-х часов</option>
                            <option>Прошло более 3-х часов</option>
                        </select>


                    </td>
                </tr>
                <? /*  <tr>
                  <td>Если были изменены комплектация или срок доставки заказа, вовремя ли менеджер сообщил Вам об этом?</td><td><select name="managerListProduct" class="styledSelect">
                  <option></option>
                  <option>Изменений не было</option>
                  <option>Вовремя</option>
                  <option>Поздно</option>
                  </select>

                  </td>
                  </tr>
                  <tr>
                  <td>Менеджер разговаривал по телефону вежливо?</td><td><select name="managerPolitely" class="styledSelect">
                  <option></option>
                  <option>Да</option>
                  <option>Нет</option>
                  </select></td>
                  </tr>
                  <tr>
                  <td>Смог ли менеджер Вам помочь, когда Вы звонили нам по телефону?</td><td><select name="managerCalledHelp" class="styledSelect">
                  <option></option>
                  <option>Да</option>
                  <option>Нет</option>
                  </select></td>
                  </tr> */ ?>
                <tr>
                    <td>Если Вы отправляли нам вопросы через онлайн чат или через обратную связь на сайте, достаточно ли быстро Вы получили ответ?</td><td><select name="managerLiveText" class="styledSelect">
                            <option></option>
                            <option>Не отправлял(а)</option>
                            <option>Быстро</option>
                            <option>Пришлось долго ждать</option>
                        </select>
                    </td>
                </tr>
                <? /* <tr>
                  <td>Если Вы подавали запрос на ремонт/замену товара, достаточно ли быстро наш сервис-менеджер обработал Вашу заявку и связался с Вами?</td><td><select name="managerReturn" class="styledSelect">
                  <option></option>
                  <option>Не подавал</option>
                  <option>Быстро</option>
                  <option>До сих пор не получил ответ</option>
                  </select>

                  </td>
                  </tr> */ ?>
                <tr>
                    <td>Оцените работу менеджеров по пятибалльной шкале</td><td><select name="managerBall" class="styledSelect" onchange="selectManager()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select></td>
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
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Сайт</td><td style="padding-top: 50px;"> <script type='text/javascript'>

                        function selectWWW(star) {
                            /* $('#wwwWork').val($(star).attr('title'));
                             $( ".wwwWork" ).each(function( index ) {
                             if(index<$(star).attr('title')){
                             $(this).attr('src','/images/star_select.png');
                             }else{
                             $(this).attr('src','/images/star_noselect.png');
                             }
                             });*/
                            //console.log($("select[name=shopBall] :selected").text());
                            switch ($("select[name=wwwBall] :selected").text()) {
                                case 'Отлично':
                                    bal = 5;
                                    break;
                                case 'Хорошо':
                                    bal = 4;
                                    break;
                                case 'Так себе':
                                    bal = 3;
                                    break;
                                case 'Плохо':
                                    bal = 2;
                                    break;
                                case 'Ужасно!':
                                    bal = 1;
                                    break;
                            }
                            if (bal < 4) {
                                $("#commentWWWAdd").fadeIn();
                            } else {
                                $("#commentWWWAdd").fadeOut();
                            }
                            $('#wwwWork').val(bal);
                            $(".wwwWork").each(function (index) {
                                if (index < bal) {
                                    $(this).attr('src', '/images/star_select.png');
                                } else {
                                    $(this).attr('src', '/images/star_noselect.png');
                                }
                            });
                        }
                        </script>
                        <input type="hidden" name="wwwWork" id="wwwWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="wwwWork"></td></tr>
                <tr>
                    <td>Удобно ли пользоваться сайтом?</td><td><select name="wwwEasy" class="styledSelect">
                            <option></option>
                            <option>Удобно</option>
                            <option>Неудобно</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Были ли у Вас трудности с поиском нужного Вам товара на сайте?</td><td><select name="wwwSearchProduct" class="styledSelect">
                            <option></option>
                            <option>Не было трудностей</option>
                            <option>Возникли трудности</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Легко ли Вы нашли информацию по доставке и оплате?</td><td><select name="wwwSearchDelPay" class="styledSelect">
                            <option></option>
                            <option>Легко</option>
                            <option>Возникли трудности</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Удобно ли пользоваться корзиной?</td><td><select name="wwwCart" class="styledSelect">
                            <option></option>
                            <option>Удобно</option>
                            <option>Неудобно</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Оцените работу сайта по пятибалльной шкале</td><td><select name="wwwBall" class="styledSelect" onchange="selectWWW()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select></td>
                </tr>
                <tr><td></td><td><div id="commentWWWAdd" style="border-bottom: 2px dashed #AAA;float: left; cursor: pointer;display: none;" onClick="if ($(this).text() == 'Здесь Вы можете оставить свои комментарии к оценке +') {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке -');
                                    $('[name=commentWWW]').fadeIn();
                                } else {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке +');
                                    $('[name=commentWWW]').fadeOut();
                                }">Здесь Вы можете оставить свои комментарии к оценке +</div>
                        <textarea name="commentWWW" style="border-radius: 5px 5px 5px 5px;width: 416px; height: 100px; border: 1px solid #EEEEEE;display: none;"></textarea></td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Общие вопросы</td><td style="padding-top: 50px;"><script type='text/javascript'>

                        function selectOther() {
                            //console.log($("select[name=shopBall] :selected").text());
                            switch ($("select[name=shopBall] :selected").text()) {
                                case 'Отлично':
                                    bal = 5;
                                    break;
                                case 'Хорошо':
                                    bal = 4;
                                    break;
                                case 'Так себе':
                                    bal = 3;
                                    break;
                                case 'Плохо':
                                    bal = 2;
                                    break;
                                case 'Ужасно!':
                                    bal = 1;
                                    break;
                            }
                            $('#otherWork').val(bal);
                            $(".otherWork").each(function (index) {
                                if (index < bal) {
                                    $(this).attr('src', '/images/star_select.png');
                                } else {
                                    $(this).attr('src', '/images/star_noselect.png');
                                }
                            });
                        }
                        </script>
                        <input type="hidden" name="otherWork" id="otherWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="otherWork"> </td></tr>
                <tr>
                    <td>Сколько раз (примерно) Вы заказывали в нашем интернет-магазине за последний год?</td><td><select name="wwwOrder" class="styledSelect">
                            <option></option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>Больше 3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Оцените по пятибалльной шкале работу магазина* <img src="/images/question_mark.png" height="14" title="Поле обязательно для заполнения"></td><td><select name="shopBall" class="styledSelect" onchange="selectOther()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select><span id="ratingShopStat"></span></td>
                </tr>
                <tr>
                    <td>Сообщите, пожалуйста, причину отмены заказа:</td><td><textarea name="recomendation" style="width: 416px; height: 100px; border: 1px solid #EEEEEE;border-radius: 5px 5px 5px 5px;"></textarea></td>
                </tr></table>
            <?= csSettings::get('footer_oprosnik') ?><br /><br />
            <? /* <table class="noBorder"><tr><td>
              Введите код с картинки: </td><td><? If ($errorCap) { ?>
              <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
              <br /><? } ?><img width="139" height="48" alt="captcha" class="captchao" src="/captcha/ocaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>">
              <br />
              <input type="text" name="captcha" style="border-radius: 5px 5px 5px 5px; border: 1px solid #EEEEEE;width: 139px;" />
              </td></tr></table> */ ?>
            <a class="red-btn colorWhite oprosnik-button" href="#" onClick="$('#oprosnik').submit()"><span style="width: 150px;">Отправить</span></a>
        </form>
    <? }
    ?>

<? elseif ($order->getStatus() == "Возврат"): ?>



    <script> $(document).ready(function () {
            $("#oprosnik").submit(function () {
                if (validateRating())
                    return true
                else
                    return false;
            });
        });


        function validateRating() {
            //testing regular expression
            if ($("select[name=shopBall] option:selected").text().length < 2) {
                $("select[name=shopBall]").addClass("error");
                $("#ratingShopStat").text("Обязательное поле для заполнения");
                $("#ratingShopStat").addClass("error");
                return false;
            }
            //if it's valid
            else {
                $("select[name=shopBall]").removeClass("error");
                $("#ratingShopStat").text("");
                $("#ratingShopStat").removeClass("error");
                return true;
            }
        }
    </script>
    <h1 style="text-align: center; font-size: 18px; color:#C3060E; font-weight: normal;">Оценка качества работы магазина. № заказа <?= $order->getPrefix() ?><?= $order->getId() ?></h1>
    <?
    if ((($sf_request->isMethod(sfRequest::POST) and ! $errorCap) or $orderExist)) {
        ?>Спасибо за отзыв.<?
    } else {
        ?>
        <form action="" method="POST" id="oprosnik">
            Оцените качество работы нашего магазина и уровень квалификации наших сотрудников, заполнив приведенную ниже анкету. Укажите причину возврата Вашего заказа, это поможет нам улучшить наши сервисы и качество обслуживания.
            <br />
            Мы хотим сделать мир «Он и Она» еще лучше и удобнее, чтобы Вам действительно было хорошо с нами.
            <table style="width: 100%; border: 0px;" class="noBorder oprosnik-table">
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Работа менеджеров</td><td style="padding-top: 50px;">
                        <script type='text/javascript'>

                            function selectManager(star) {
                                /* $('#managerWork').val($(star).attr('title'));
                                 $( ".managerWork" ).each(function( index ) {
                                 if(index<$(star).attr('title')){
                                 $(this).attr('src','/images/star_select.png');
                                 }else{
                                 $(this).attr('src','/images/star_noselect.png');
                                 }
                                 });*/
                                switch ($("select[name=managerBall] :selected").text()) {
                                    case 'Отлично':
                                        bal = 5;
                                        break;
                                    case 'Хорошо':
                                        bal = 4;
                                        break;
                                    case 'Так себе':
                                        bal = 3;
                                        break;
                                    case 'Плохо':
                                        bal = 2;
                                        break;
                                    case 'Ужасно!':
                                        bal = 1;
                                        break;
                                }
                                if (bal < 4) {
                                    $("#commentManagerAdd").fadeIn();
                                } else {
                                    $("#commentManagerAdd").fadeOut();
                                }
                                $('#managerWork').val(bal);
                                $(".managerWork").each(function (index) {
                                    if (index < bal) {
                                        $(this).attr('src', '/images/star_select.png');
                                    } else {
                                        $(this).attr('src', '/images/star_noselect.png');
                                    }
                                });
                            }
                        </script>
                        <input type="hidden" name="managerWork" id="managerWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="managerWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="managerWork"></td>
                </tr>
                <tr>
                    <td>Насколько корректно и вежливо с Вами общался менеджер?</td><td><select name="managerComunication" class="styledSelect">
                            <option></option>
                            <option>Очень профессионально</option>
                            <option>Отчасти профессионально</option>
                            <option>Непрофессионально</option>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td>Смог ли менеджер проконсультировать Вас по всем Вашим вопросам?</td><td><select name="managerAdvise" class="styledSelect">
                            <option></option>
                            <option>Да</option>
                            <option>Частично</option>
                            <option>Нет</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Как быстро перезвонил Вам менеджер для подтверждения оформленного Вами заказа?</td><td><select name="managerSpeedCalled" class="styledSelect">
                            <option></option>
                            <option>В течение 30 минут</option>
                            <option>В течение 1-го часа</option>
                            <option>В течение 3-х часов</option>
                            <option>Прошло более 3-х часов</option>
                        </select>


                    </td>
                </tr>
                <tr>
                    <td>Если были изменены комплектация или срок доставки заказа, вовремя ли менеджер сообщил Вам об этом?</td><td><select name="managerListProduct" class="styledSelect">
                            <option></option>
                            <option>Изменений не было</option>
                            <option>Вовремя</option>
                            <option>Поздно</option>
                        </select>

                    </td>
                </tr>
                <? /* <tr>
                  <td>Менеджер разговаривал по телефону вежливо?</td><td><select name="managerPolitely" class="styledSelect">
                  <option></option>
                  <option>Да</option>
                  <option>Нет</option>
                  </select></td>
                  </tr>
                  <tr>
                  <td>Смог ли менеджер Вам помочь, когда Вы звонили нам по телефону?</td><td><select name="managerCalledHelp" class="styledSelect">
                  <option></option>
                  <option>Да</option>
                  <option>Нет</option>
                  </select></td>
                  </tr> */ ?>
                <tr>
                    <td>Если Вы отправляли нам вопросы через онлайн чат или через обратную связь на сайте, достаточно ли быстро Вы получили ответ?</td><td><select name="managerLiveText" class="styledSelect">
                            <option></option>
                            <option>Не отправлял(а)</option>
                            <option>Быстро</option>
                            <option>Пришлось долго ждать</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Оцените работу менеджеров по пятибалльной шкале</td><td><select name="managerBall" class="styledSelect" onchange="selectManager()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select></td>
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
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Сайт</td><td style="padding-top: 50px;"> <script type='text/javascript'>

                        function selectWWW(star) {
                            /* $('#wwwWork').val($(star).attr('title'));
                             $( ".wwwWork" ).each(function( index ) {
                             if(index<$(star).attr('title')){
                             $(this).attr('src','/images/star_select.png');
                             }else{
                             $(this).attr('src','/images/star_noselect.png');
                             }
                             });*/
                            //console.log($("select[name=shopBall] :selected").text());
                            switch ($("select[name=wwwBall] :selected").text()) {
                                case 'Отлично':
                                    bal = 5;
                                    break;
                                case 'Хорошо':
                                    bal = 4;
                                    break;
                                case 'Так себе':
                                    bal = 3;
                                    break;
                                case 'Плохо':
                                    bal = 2;
                                    break;
                                case 'Ужасно!':
                                    bal = 1;
                                    break;
                            }
                            if (bal < 4) {
                                $("#commentWWWAdd").fadeIn();
                            } else {
                                $("#commentWWWAdd").fadeOut();
                            }
                            $('#wwwWork').val(bal);
                            $(".wwwWork").each(function (index) {
                                if (index < bal) {
                                    $(this).attr('src', '/images/star_select.png');
                                } else {
                                    $(this).attr('src', '/images/star_noselect.png');
                                }
                            });
                        }
                        </script>
                        <input type="hidden" name="wwwWork" id="wwwWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="wwwWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="wwwWork"></td></tr>
                <tr>
                    <td>Удобно ли пользоваться сайтом?</td><td><select name="wwwEasy" class="styledSelect">
                            <option></option>
                            <option>Удобно</option>
                            <option>Неудобно</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Были ли у Вас трудности с поиском нужного Вам товара на сайте?</td><td><select name="wwwSearchProduct" class="styledSelect">
                            <option></option>
                            <option>Не было трудностей</option>
                            <option>Возникли трудности</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Легко ли Вы нашли информацию по доставке и оплате?</td><td><select name="wwwSearchDelPay" class="styledSelect">
                            <option></option>
                            <option>Легко</option>
                            <option>Возникли трудности</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Удобно ли пользоваться корзиной?</td><td><select name="wwwCart" class="styledSelect">
                            <option></option>
                            <option>Удобно</option>
                            <option>Неудобно</option>
                        </select></td>
                </tr>
                <tr>
                    <td>Оцените работу сайта по пятибалльной шкале</td><td><select name="wwwBall" class="styledSelect" onchange="selectWWW()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select></td>
                </tr>
                <tr><td></td><td><div id="commentWWWAdd" style="border-bottom: 2px dashed #AAA;float: left; cursor: pointer;display: none;" onClick="if ($(this).text() == 'Здесь Вы можете оставить свои комментарии к оценке +') {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке -');
                                    $('[name=commentWWW]').fadeIn();
                                } else {
                                    $(this).text('Здесь Вы можете оставить свои комментарии к оценке +');
                                    $('[name=commentWWW]').fadeOut();
                                }">Здесь Вы можете оставить свои комментарии к оценке +</div>
                        <textarea name="commentWWW" style="border-radius: 5px 5px 5px 5px;width: 416px; height: 100px; border: 1px solid #EEEEEE;display: none;"></textarea></td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Общие вопросы</td><td style="padding-top: 50px;"><script type='text/javascript'>

                        function selectOther() {
                            //console.log($("select[name=shopBall] :selected").text());
                            switch ($("select[name=shopBall] :selected").text()) {
                                case 'Отлично':
                                    bal = 5;
                                    break;
                                case 'Хорошо':
                                    bal = 4;
                                    break;
                                case 'Так себе':
                                    bal = 3;
                                    break;
                                case 'Плохо':
                                    bal = 2;
                                    break;
                                case 'Ужасно!':
                                    bal = 1;
                                    break;
                            }
                            $('#otherWork').val(bal);
                            $(".otherWork").each(function (index) {
                                if (index < bal) {
                                    $(this).attr('src', '/images/star_select.png');
                                } else {
                                    $(this).attr('src', '/images/star_noselect.png');
                                }
                            });
                        }
                        </script>
                        <input type="hidden" name="otherWork" id="otherWork" />
                        <img src="/images/star_noselect.png" id="rate_divStarRating1" alt="1 " style="width: 20px;" title="1" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating2" alt="2 " style="width: 20px;" title="2" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating3" alt="3 " style="width: 20px;" title="3" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating4" alt="4 " style="width: 20px;" title="4" class="otherWork">
                        <img src="/images/star_noselect.png" id="rate_divStarRating5" alt="5 " style="width: 20px;" title="5" class="otherWork"> </td></tr>
                <tr>
                    <td>Сколько раз (примерно) Вы заказывали в нашем интернет-магазине за последний год?</td><td><select name="wwwOrder" class="styledSelect">
                            <option></option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>Больше 3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Оцените по пятибалльной шкале работу магазина* <img src="/images/question_mark.png" height="14" title="Поле обязательно для заполнения"></td><td><select name="shopBall" class="styledSelect" onchange="selectOther()">
                            <option></option>
                            <option>Отлично</option>
                            <option>Хорошо</option>
                            <option>Так себе</option>
                            <option>Плохо</option>
                            <option>Ужасно!</option>
                        </select><span id="ratingShopStat"></span></td>
                </tr>
                <tr>
                    <td>Сообщите, пожалуйста, причину почему Вы не получили свой заказ:</td><td><textarea name="recomendation" style="width: 416px; height: 100px; border: 1px solid #EEEEEE;border-radius: 5px 5px 5px 5px;"></textarea></td>
                </tr>
            </table>
            <?= csSettings::get('footer_oprosnik') ?><br /><br />

            <a class="red-btn colorWhite oprosnik-button" href="#" onClick="$('#oprosnik').submit()"><span style="width: 150px;">Отправить</span></a>
        </form>
    <? }
    ?>

<? endif; ?>
