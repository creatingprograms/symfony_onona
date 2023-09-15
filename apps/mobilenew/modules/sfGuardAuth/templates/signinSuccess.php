<?php
use_helper('I18N');
JSInPages("function setEqualHeight(columns)
    {
        var tallestcolumn = 0;
        columns.each(
                function ()
                {
                    currentHeight = $(this).height();
                    if (currentHeight > tallestcolumn)
                    {
                        tallestcolumn = currentHeight;
                    }
                }
        );
        columns.height(tallestcolumn);
    }
    $(document).ready(function () {
        setEqualHeight($('.loginRegisterBlock > div .title'));
        setEqualHeight($('.loginRegisterBlock > div .block'));
    });");
?>
<div id="loginRegister">
    <div class="loginRegisterBlock">
        <div class="login">
            <div class="title">
                Вход для зарегистрированных пользователей
                <br />
                <span>Авторизуйтесь на сайте, указав свои данные:</span>
            </div>
            <div class="block" style="">
                <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" id="AuthForm">
                    <label for="signin_username">Адрес электронной почты:*</label>
                    <br />
                    <span style="color: #ba272d; font-weight: bold;"><?= $form['username']->renderError() ?></span>
                    <input type="text" name="signin[username]" id="signin_username" required />

                    <label for="signin_password">Пароль:*</label><br />
                    <input type="password" name="signin[password]" id="signin_password" required/>
                    <br />

                    <input type="checkbox" name="signin[remember]" id="signin_remember" /><label for="signin_remember">Запомнить меня на этом сайте</label>

                    <br />
                    <br />

                    <div class="redButton" onclick="$('#AuthForm').submit();">Авторизоваться</div>
                    <a href="<?php echo url_for('@sf_guard_forgot_password') ?>" class="silverButton">Забыли пароль?</a>



                </form>
            </div>
            <div class="socNetwork">    
                <div class="pink bold" style="color: #ba272d; font-size: 16px; margin-bottom: 10px;">Войти, используя аккаунт соцсети</div><script src="//ulogin.ru/js/ulogin.js"></script>
                <div id="uLoginAuth" data-ulogin="display=panel;fields=first_name,last_name,email;optional=bdate,city;providers=yandex,vkontakte,facebook,twitter,odnoklassniki,mailru,googleplus;hidden=other;redirect_uri=http%3A%2F%2Fm.onona.ru%2Fregss"></div>

            </div>
        </div>
        <div class="register">
            <div class="title">
                Регистрация для новых пользователей
                <br />
                <span>Рады видеть Вас в OnOna.ru!</span>
            </div>

            <div class="block">
                Быстро, удобно, легко!                     
                <br />
                <br />
                Преимущества регистрации:                        
                <br />
                <br />
                <ul class="red-list">
                    <li><div class="text">Использование введенных ранее данных при оформлении заказа.</div></li>
                    <li><div class="text">Отслеживание статуса своих заказов/история заказов.</div></li>
                    <li><div class="text">Подарок 300 бонусов на оплату уже первой покупки!</div></li>
                    <li><div class="text">Накопление Бонусных рублей и оплата ими товаров.</div></li>
                    <li><div class="text">Добавление товаров в список желаний/сравнения.</div></li>
                    <li><div class="text">Возможность настроить оповещения для товара.</div></li>
                    <li><div class="text">Получение персональных предложений, акций, скидок. </div></li>
                </ul>

                <br />
                <a href="<?php echo url_for('@sf_guard_register') ?>" class="redButton">Зарегистрироваться</a>
            </div>
            <div class="socNetwork">    
                <div class="pink bold" style="color: #ba272d; font-size: 16px; margin-bottom: 10px;">Зарегистрироваться, используя аккаунт соцсети</div>
                <div id="uLoginReg" data-ulogin="display=panel;fields=first_name,last_name,email;optional=bdate,city;providers=yandex,vkontakte,facebook,twitter,odnoklassniki,mailru,googleplus;hidden=other;redirect_uri=http%3A%2F%2Fm.onona.ru%2Fregss"></div>

            </div>
        </div>
    </div>

    <div style="clear: both"></div>
    <script>
                        function forgotPassword() {
                            $('<div/>').click(function (e) {
                                if (e.target != this)
                                    return;
                                $(this).remove();
                            }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockForgotPassword").appendTo('body');
                            $('.blockForgotPassword').html($(".ForgotPasswordBlock").html());
                            $('.blockForgotPassword').css("padding-top", $(window).scrollTop() + ($(window).height() / 2 - $('.blockForgotPassword').children().height() / 2))
                            $(document).keyup(function (e) {

                                if (e.keyCode == 27) {
                                    $('.blockForgotPassword').remove();
                                }   // esc
                            });
                        }
    </script>
    <div class="ForgotPasswordBlock" style="display: none;">
        <div style="
             margin: 0 auto;
             position: relative;
             width: 1000px;
             ">
            <div style="width:515px; margin-left: 240px;
                 background-color: rgba(255, 255, 255, 1);
                 border:1px solid #c3060e; padding: 0px; ">

                <div onClick="$('.blockForgotPassword').remove();" class='close'></div>
                <div style="padding: 20px;">
                    <div style="color:#c3060e; text-align: center; width: 100%; font-size: 18px;">Восстановление пароля</div><br/>
                    <b>Почему не стоит регистрироваться заново, если Вы забыли свой пароль?</b> <br />
                    1. История ваших покупок будет потеряна, Вы не сможете воспользоваться персональными рекомендациями, скидками и специальными ценами.<br />
                    2. Вы утрачиваете свои накопленные бонусы.<br />
                    3. Теряете товары, добавленные в список желаний/в сравнения/оповещения. Что делать, если Вы забыли свой пароль?<br /><br />

                    <b>Что делать, если Вы забыли свой пароль?</b><br />
                    В форму ниже введите свой электронный адрес, указанный при регистрации, и через несколько минут на Ваш E-mail придёт письмо с паролем.

                </div>
                <form action="/guard/forgot_password" method="post" class="ForgotPasswordForm">
                    <div style="padding: 20px;background-color: #f3f3f3; width: 475px;">
                        <label for="signin_username">Адрес электронной почты:*</label>
                        <input type="text" name="forgot_password[email_address]" id="forgot_password_email_address" required  style="height: 30px;
                               width: 280px;
                               background: #fff;
                               border: 1px solid #dfdfdf;
                               padding: 0 10px;" />

                    </div>

                    <div style="padding: 20px;"> 
                        <div class="ForgotPasswordButton" onclick="$('.blockForgotPassword .ForgotPasswordForm').submit();"></div>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>