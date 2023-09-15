<noindex>
<div id="popup-login" class="mfp-hide white-popup-block block-popup popup-login js-submit-form">
  <div class="h2 block-popup__title">Войти в кабинет</div>
  <form class="form form-popup" action="/ajax/login">
    <p class="block-popup__descr">Введите свой логин и пароль</p>
    <div class="field field-default">
      <label for="">Электронная почта</label>
      <input type="email" placeholder="" value="" name="username">
    </div>
    <div class="field field-default">
      <label for="">Пароль <a href="#popup-recovery" class="js-popup-form">Не помню пароль</a></label>
      <input type="password" placeholder="" value="" name="password">
    </div>
    <div class="custom-check">
      <div class="check-check_block">
        <input type="checkbox" name="remember" id="popup_remember" class="check-check_input" value="1">
        <div class="custom-check_shadow"></div>
      </div>
      <label for="popup_remember" class="custom-check_label">Запомнить меня в системе</label>
    </div>
    <div class="wrap-btn">
      <button class="btn-full btn-full_rad js-submit-button">Авторизироваться</button>
      <a href="#popup-reg" class="btn-full btn-full_white btn-full_rad js-popup-form">Зарегистрироваться</a>
    </div>
    <?/*
    <div class="wrap-in-soc">

    </div>
    <p class="text-soc">Войти через соцсети</p>*/?>
  </form>
</div>
<div id="popup-recovery" class="mfp-hide white-popup-block block-popup popup-recovery">
  <div class="h2 block-popup__title">Восстановление <br>пароля</div>
  <p class="block-popup__descr">В форму ниже введите свой электронный адрес, указанный при регистрации, и через несколько минут на Ваш E-mail придёт письмо с новым паролем</p>
  <form class="form form-popup" action="/guard/forgot_password" method="post">
    <div class="field field-default">
      <label for="">Электронная почта</label>
      <input type="email" placeholder="" value="" name="forgot_password[email_address]">
    </div>
    <div class="wrap-btn">
      <?/*<input class="btn-full btn-full_rad" type="submit" name="change" value="Запросить новый пароль" />*/?>
      <button class="btn-full btn-full_rad">Запросить новый пароль</button>
    </div>
  </form>
</div>
<div id="popup-reg" class="mfp-hide white-popup-block block-popup popup-reg">
  <div class="h2 block-popup__title">Зарегистрироваться <br>в ОнОна</div>
  <p class="block-popup__descr">Зарегистрируйтесь и получите <?= csSettings::get('register_bonus_add') ?> бонусов, которыми вы сможете оплатить свой заказ прямо сейчас.</p>
  <div class="wrap-btn">
    <a href="/register" class="btn-full btn-full_rad">Перейти к регистрации</a>
    <a href="#popup-login" class="btn-full btn-full_white btn-full_rad js-popup-form">Войти в личный кабинет</a>
  </div>
  <div class="wrap-in-soc">

  </div>
  <?/*<p class="text-soc">Регистрация через соцсети</p>*/?>
</div>
</noindex>
