<?
global $isTest;
$h1 = 'Регистрация';
slot('breadcrumbs', [
  // ['text' => 'Личный кабинет', 'link'=>'/lk'],
  ['text' => $h1],
]);
// slot('h1', $h1);

?>
<?
//die('<pre>'.print_r($user->getBirthday(), true));
?>

<div class="wrap-block">
  <div class="container">
    <div class="block-content width_min">
      <? include_component("page", "subpage", array('page' => '_register',)); ?>
    </div>
  </div>
</div>

<div class="wrap-block wrap-block-reg">
  <div class="container">
    <div class="block-content">
      <form action="/register" class="form form-reg width_min js-ajax-form" method="post">
        <input type="hidden" name="sf_guard_user[password_again]">
        <fieldset>
          <legend class="h4">Основная информация</legend>
          <div class="fieldset-block">
            <div class="field field-default field-name">
              <label for="">Логин<span class="-red">*</span></label>
              <input type="text" placeholder="" name="sf_guard_user[username]" value="">
            </div>
            <div class="field field-default field-email">
              <label for="">Пароль<span class="-red">*</span></label>
              <input type="password" placeholder="" value="" name="sf_guard_user[password]" class="js-duplicate-pass">
            </div>
            <div class="field field-default field-name">
              <label for="">Электронная почта<span class="-red">*</span></label>
              <input type="email" placeholder="" value="" name="sf_guard_user[email_address]">
            </div>
            <div class="field field-default field-email">
              <label for="">Телефон для связи<span class="-red">*</span></label>
              <input type="tel" placeholder="" class="js-phone-mask" name="sf_guard_user[phone]" value="">
            </div>

            <div class="field field-default field-name">
              <label for="">Отмеченное <span class="-red">*</span> является обязательным к заполнению</label>
            </div>

            <?/*<div class="field field-default field-rass-repl">
              <label for="">Повторите пароль</label>
              <input type="password" placeholder="" value="" name="sf_guard_user[password_again]">
            </div>*/ ?>
          </div>
        </fieldset>
        <fieldset>
          <legend class="h4">Дополнительная информация</legend>
          <div class="fieldset-block">
            <div class="field field-default field-name">
              <label for="">Ваше имя</label>
              <input type="text" placeholder="" name="sf_guard_user[first_name]" value="">
            </div>
            <div class="field field-default field-email">
              <label for="">Фамилия</label>
              <input type="text" placeholder="" name="sf_guard_user[last_name]" value="">
            </div>
            <div class="group-field group-field_gender field">
              <label for="">Пол</label>
              <div class="custom-check custom-check_circle ">
                <div class="check-check_block">
                  <input type="radio" name="sf_guard_user[sex]" id="sex-f" value="g" class="check-check_input">
                  <div class="custom-check_shadow"></div>
                </div>
                <label for="sex-f" class="custom-check_label">Ж</label>
              </div>
              <div class="custom-check custom-check_circle">
                <div class="check-check_block">
                  <input type="radio" name="sf_guard_user[sex]" value="m" id="sex-m" class="check-check_input">
                  <div class="custom-check_shadow"></div>
                </div>
                <label for="sex-m" class="custom-check_label">М</label>
              </div>
            </div>
            <div class="field field-default field-email">
              <label for="">Дата рождения</label>
              <input type="date" name="sf_guard_user[birthday]" placeholder="" value="">
            </div>
            <div class="field field-default"></div>
          </div>
        </fieldset>

        <?/*<fieldset>
          <legend class="h4">Информация для доставки</legend>
          <div class="fieldset-block">
            <div class="field field-default">
              <label for="">Индекс</label>
              <input type="text" placeholder="" value="" name="sf_guard_user[index_house]">
            </div>
            <div class="field field-default field-city">
              <label for="">Город</label>
              <input type="text" placeholder="" value="" name="sf_guard_user[city]">
            </div>
            <div class="field field-default field-street">
              <label for="">Улица</label>
              <input type="text" placeholder="" value="" name="sf_guard_user[street]">
            </div>
            <div class="group-field group-field_address">
              <div class="field field-default field_w">
                <label for="">Дом</label>
                <input type="text" placeholder="" value="" name="sf_guard_user[house]">
              </div>
              <div class="field field-default field_w">
                <label for="">Корпус</label>
                <input type="text" placeholder="" value="" name="sf_guard_user[korpus]">
              </div>
              <div class="field field-default field_w">
                <label for="">Квартира/офис</label>
                <input type="text" placeholder="" value="" name="sf_guard_user[apartament]">
              </div>
            </div>
          </div>
        </fieldset>*/ ?>
        <?/*<div class="reg-note">Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить ваш заказ.
          Все данные наших клиентов надежно защищены и не подлежат разглашению.</div>*/ ?>
        <div class="form-reg__bottom">
          <div class="wrap-btn">
            <button class="btn-full btn-full_rad js-submit-button">Зарегистрироваться в системе</button>
          </div>
          <div class="custom-check">
            <div class="check-check_block" style="display: none;">
              <input type="checkbox" value="1" name="agreement" id="val-rev-1" class="check-check_input" checked>
              <div class="custom-check_shadow"></div>
            </div>
            <?/*<label for="val-rev-1" class="custom-check_label">Принимаю условия <a href="/personal_accept">Пользовательского соглашения</a></label>*/?>
            <label for="val-rev-1" class="custom-check_label">Регистрируясь на сайте Вы принимаете условия <a href="/personal_accept">Пользовательского соглашения</a></label>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>