<?
global $isTest;
$h1='Регистрация';
slot('breadcrumbs', [
  // ['text' => 'Личный кабинет', 'link'=>'/lk'],
  ['text' => $h1],
]);
// slot('h1', $h1);

?>
<?
//die('<pre>'.print_r($user->getBirthday(), true));

?>
<main class="wrapper">
  <div class="lk-mydata register">
    <form action="/register" method="post" class="form js-ajax-form">
      <input type="hidden" name="sf_guard_user[_csrf_token]" value="<?php echo $csrf ?>">
      <div class="h1">Регистрация на сайте onona.ru</div>
      <div class="lk-mydata-form">
        <div class="lk-mydata-element">
          <div class="header">Основная информация**:</div>
          <div class="white">
            <div class="form-row">
              <div class="form-label">
                Имя*
              </div>
              <input type="text" name="sf_guard_user[first_name]" value="" class="js-rr-send-name">
            </div>
            <div class="form-row">
              <div class="form-label">
                Фамилия*
              </div>
              <input type="text" name="sf_guard_user[last_name]" value="">
            </div>
            <div class="form-row">
              <div class="form-label">
                E-mail (логин)*
              </div>
              <input type="text" name="sf_guard_user[email_address]" value="" class="js-rr-send-email">
            </div>
            <div class="form-row">
              <div class="form-label">
                Телефон*
              </div>
              <input type="tel" class="js-phone-mask" name="sf_guard_user[phone]" value="" placeholder="+7(___)___-__-__">
            </div>
            <div class="form-row">
              <div class="form-label">
                Пароль*
              </div>
              <input type="password" name="sf_guard_user[password]" value="" class="js-password">
            </div>
            <div class="form-row">
              <div class="form-label">
                Повторите пароль*
              </div>
              <input type="password" name="sf_guard_user[password_again]" value="" class="js-password-again">
            </div>
            <div class="form-row form-row-long">
              <div class="form-label">
                Дата рождения
              </div>
              <select name="sf_guard_user[birthday][day]" class="js-rr-send-day">
                <option value=""></option>
                <? for($i=1; $i<32; $i++) :?>
                  <option value="<?=$i?>" ><?= $i<10 ? '0' : ''?><?=$i?></option>
                <? endfor ?>
              </select>
              <select name="sf_guard_user[birthday][month]" class="js-rr-send-month">
                <option value=""></option>
                <? for($i=1; $i<13; $i++) :?>
                  <option value="<?=$i?>" ><?= $i<10 ? '0' : ''?><?=$i?></option>
                <? endfor ?>
              </select>
              <select name="sf_guard_user[birthday][year]"  class="js-rr-send-year">
                <option value=""></option>
                <? $year=date('Y')*1; ?>
                <? for($i=$year-18; $i>$year-100; $i--) :?>
                  <option value="<?=$i?>" ><?=$i?></option>
                <? endfor ?>
              </select>
            </div>
            <div class="form-row form-row-long">
              <div class="form-label">
                Пол
              </div>
              <div class="male-female">
                <input type="radio" name="sf_guard_user[sex]" class="styleCH js-rr-send-gender" value="m" id="sex-m">
                <label for="sex-m"> М </label>
                <input type="radio" name="sf_guard_user[sex]" class="styleCH js-rr-send-gender" value="g" id="sex-f">
                <label for="sex-f"> Ж </label>
              </div>
            </div>
          </div>
        </div>

        <div class="lk-mydata-element">
          <div class="header">Информация для доставки**:</div>
          <div class="white">
            <div class="form-row">
              <div class="form-label">
                Индекс
              </div>
              <input type="text" name="sf_guard_user[index_house]" value="">
            </div>
            <div class="form-row">
              <div class="form-label">
                Город*
              </div>
              <input type="text" name="sf_guard_user[city]" value="">
            </div>
            <div class="form-row">
              <div class="form-label">
                Улица*
              </div>
              <input type="text" name="sf_guard_user[street]" value="">
            </div>
            <div class="form-row">
              <div class="form-label">
                Дом
              </div>
              <input type="text" name="sf_guard_user[house]" value="">
            </div>
            <div class="form-row">
              <div class="form-label">
                Корпус/строение
              </div>
              <input type="text" name="sf_guard_user[korpus]" value="">
            </div>
            <div class="form-row">
              <div class="form-label">
                Квартира/офис
              </div>
              <input type="text" name="sf_guard_user[apartament]" value="">
            </div>
            <div class="form-row form-row-long">
              <div class="form-review-consent">
                <input type="checkbox" class="styleCH" name="agreement" id="val-rev-1" value="1">
                <label for="val-rev-1">Я принимаю условия <a target="_blank" href="/personal_accept">Пользовательского соглашения</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <p><span class="red">*</span> - Поля, обязательны для заполнения.</p>
      <p><span class="red">**</span> - Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить ваш заказ.</p>
      <p class="red">Все данные наших клиентов надежно защищены и не подлежат разглашению.</p><br>
      <div class="but js-submit-button js-rr-send-delay">ЗАРЕГИСТРИРОВАТЬСЯ</div>

    </form>
    <div class="content"><div class="wrapper"><?= $page->getContent() ?></div></div>
</main>
