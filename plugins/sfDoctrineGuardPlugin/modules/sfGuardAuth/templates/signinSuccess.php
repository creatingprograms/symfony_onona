<?php use_helper('I18N') ?>
<?

if ($_SERVER['SCRIPT_NAME'] == "/backend_dev.php" or $_SERVER['SCRIPT_NAME'] == "/backend.php" or $_SERVER['SCRIPT_NAME'] == "/contentportal_dev.php" or $_SERVER['SCRIPT_NAME'] == "/contentportal.php") :?>
    <?= get_partial('sfGuardAuth/signin_form', array('form' => $form));?>
<?else: ?>
  <? global $isNew ?>
  <? if (!$isNew) :?>
    <main class="wrapper">
      <div class="regPage">
        <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" id="AuthForm" class="regPage-item form-er -bg -isBut">
          <?php echo $form['_csrf_token']->render(); ?>
          <div class="form-er-h">
            Вход для зарегистрированных пользователей
          </div>
          <div class="form-er-intro">
            Авторизуйтесь на сайте, указав свои данные:
          </div>
          <div class="regPage-group">
            <div class="form-er-row">
              <div class="form-er-label">
                Адрес электронной почты:*
              </div>
              <input type="email" name="signin[username]" id="signin_username" required />
            </div>
            <div class="form-er-row">
              <div class="form-er-label -clf">
                <span class="form-er-align-left">Пароль:*</span>
                <a href="#forgot-password" class="form-er-align-right form-er-forgot inlinePopupJS">Забыли пароль?</a>
              </div>
              <input type="password" name="signin[password]" id="signin_password" required />
            </div>
            <div class="form-er-ch-services">
              <input type="checkbox" name="signin[remember]" id="signin_remember" class="styleCH"  />
              <label for="signin_remember">Запомнить меня на этом сайте</label>
            </div>
          </div>
          <input type="submit" name="" value="Авторизоваться" class="but">
        </form>
        <div class="regPage-item form-er -bg -isBut">
          <div class="form-er-h">
            Регистрация для новых пользователей
          </div>
          <div class="form-er-intro">
            Рады видеть Вас в OnOna.ru!
          </div>
          <div class="regPage-mob-group">
            <p>Быстро, удобно, легко!</p>
            <p>Преимущества регистрации:</p>
            <ul>
              <li>Использование введенных ранее данных при оформлении заказа.</li>
              <li>Отслеживание статуса своих заказов/история заказов.</li>
              <li>Подарок 300 бонусов на оплату уже первой покупки!</li>
              <li>Накопление Бонусных рублей и оплата ими товаров.</li>
              <li>Добавление товаров в список желаний/сравнения.</li>
              <li>Возможность настроить оповещения для товара.</li>
              <li>Получение персональных предложений, акций, ск</li>
            </ul>
            <a href="/register" class="but">Зарегистрироваться</a>
          </div>
        </div>
        <div class="regPage-item form-er -bg -smm">
          <div class="form-er-h">
            Войти, используя аккаунт&nbsp;соцсети
          </div>
          <div class="regPage-smm">
            <script src="//ulogin.ru/js/ulogin.js"></script>
            <div id="uLoginAuth" data-ulogin="display=panel;fields=first_name,last_name,email;optional=bdate,city;providers=yandex,vkontakte,facebook,twitter,odnoklassniki,mailru,googleplus;hidden=other;redirect_uri=http%3A%2F%2Fonona.ru%2Fregss"></div>

          </div>
        </div>
        <div class="regPage-item form-er -bg -smm">
          <div class="form-er-h">
            зарегистрироваться, используя аккаунт соцсети
          </div>
          <div class="regPage-smm">
            <div id="uLoginReg" data-ulogin="display=panel;fields=first_name,last_name,email;optional=bdate,city;providers=yandex,vkontakte,facebook,twitter,odnoklassniki,mailru,googleplus;hidden=other;redirect_uri=http%3A%2F%2Fonona.ru%2Fregss"></div>

          </div>
        </div>
      </div>
    </main>
    <div class="popup-form-wrapper mfp-hide popup" id="forgot-password">
        <div class="form-popup-wrapper">
            <div class="form-er-h">Восстановление пароля</div><br/>
            <b>Почему не стоит регистрироваться заново, если Вы забыли свой пароль?</b> <br />
            1. История ваших покупок будет потеряна, Вы не сможете воспользоваться персональными рекомендациями, скидками и специальными ценами.<br />
            2. Вы утрачиваете свои накопленные бонусы.<br />
            3. Теряете товары, добавленные в список желаний/в сравнения/оповещения. Что делать, если Вы забыли свой пароль?<br /><br />

            <b>Что делать, если Вы забыли свой пароль?</b><br />
            В форму ниже введите свой электронный адрес, указанный при регистрации, и через несколько минут на Ваш E-mail придёт письмо с паролем.

          <form action="/guard/forgot_password" method="post" class="ForgotPasswordForm form-er">
            <div class="form-er-row">
              <?php echo $form2['_csrf_token']->render(); ?>
              <div class="form-er-label">
                Адрес электронной почты:*
              </div>
              <input type="text" name="forgot_password[email_address]" id="forgot_password_email_address" required />

              </div>
              <button class="but">Восстановить пароль</button>
          </form>


        </div>

    </div>
  <? else : ?>
    <?
      $h1='Вход на сайт';
      slot('breadcrumbs', [
        // ['text' => 'Личный кабинет', 'link'=>'/lk'],
        ['text' => $h1],
      ]);
      slot('h1', $h1);
    ?>
    <div class="wrap-block wrap-block-reg">
      <div class="container">
        <div class="block-content">
          <form action="/guard/login" method="post" id="AuthForm" class="form form-reg width_min">
            <?php //echo $form['_csrf_token']->render(); ?>
            <input type="hidden" name="signin[remember]" id="signin_remember" value="1" />

            <div class="fieldset-block">
              <div class="field field-default field-name">
                <label for="">Адрес электронной почты:*</label>
                <input type="email" name="signin[username]" id="signin_username" required />
              </div>
              <div class="field field-default field-name">
                <label for="">Пароль:*</label>
                <input type="password" name="signin[password]" id="signin_password" required />
              </div>
            </div>

            <div class="form-reg__bottom">
              <div class="wrap-btn">
                <input type="submit" name="" value="Авторизоваться" class="btn-full btn-full_rad">
              </div>
            </div>
            <br>


            <a href="#popup-recovery" class="form-er-align-right form-er-forgot inlinePopupJS">Забыли пароль?</a>
            <?/*  <label for="signin_remember">Запомнить меня на этом сайте</label>*/?>

          </form>
        </div>
      </div>
    </div>
  <? endif ?>
<? endif ?>
