<?
global $isTest;
$h1='Мои данные';
slot('breadcrumbs', [
  ['text' => 'Личный кабинет', 'link'=>'/lk'],
  ['text' => $h1],
]);
slot('h1', $h1);
// $birthday=explode('-',$user->getBirthday());
?>
<?php //use_helper('I18N') ?>
<?
//die('<pre>'.print_r($user->getBirthday(), true));
?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <form action="/customer/mydata" class="form form-edit js-ajax-form">
        <div class="col">
          <div class="form-edit__title">Основная информация:</div>
          <div class="form-edit__content">
            <div class="field field-default">
              <label for="">Имя*</label>
              <input type="text" placeholder="Иван" value="<?= $user->getFirstName() ?>" name="sf_guard_user[first_name]">
            </div>
            <div class="field field-default">
              <label for="">Фамилия*</label>
              <input type="text" name="sf_guard_user[last_name]" placeholder="Иванов" value="<?= $user->getLastName() ?>">
            </div>
            <div class="field field-default">
              <label for="">E-mail (логин)*</label>
              <input type="email" name="sf_guard_user[email_address]" placeholder="mail@mail.ru" value="<?= $user->getEmailAddress() ?>">
            </div>
            <div class="field field-default">
              <label for="">Пароль(заполняйте, если хотите изменить)</label>
              <div class="btn-show-pass">
                <svg>
                  <use xlink:href="#pass-i"></use>
                </svg>
              </div>
              <input type="password" name="sf_guard_user[password]" value="">
            </div>
            <div class="group-field">
              <div class="field field-default field_w">
                <label for="">Дата рождения</label>
                <input type="date" placeholder="" name="sf_guard_user[birthday]" value="<?=$user->getBirthday()?>">
              </div>
              <div class="group-field group-field_gender">
                <label>Пол*</label>
                <div class="custom-check custom-check_circle ">
                  <div class="check-check_block">
                    <input type="radio" name="sf_guard_user[sex]" id="sex-g" class="check-check_input" value="g" <?= $user->getSex()=="g" ? 'checked' : '' ?>>
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="sex-g" class="custom-check_label">Ж</label>
                </div>
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" id="sex-m" class="check-check_input" value="m" name="sf_guard_user[sex]" <?= $user->getSex()=="m" ? 'checked' : '' ?>>
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="sex-m" class="custom-check_label">М</label>
                </div>
              </div>
            </div>
            <div class="field field-default field_w">
              <label for="">Телефон для связи*</label>
              <input type="tel" placeholder="+7 950 000 123" class="js-phone-mask" name="sf_guard_user[phone]" value="<?= $user->getPhone() ?>">
            </div>
          </div>
        </div>
        <div class="col">
          <div class="form-edit__title">Информация для доставки:</div>
          <div class="form-edit__content">
            <div class="field field-default">
              <label for="">Индекс</label>
              <input type="text" placeholder="" name="sf_guard_user[index_house]" value="<?= $user->getIndexHouse() ?>">
            </div>
            <div class="field field-default">
              <label for="">Город*</label>
              <input type="text" placeholder="" name="sf_guard_user[city]" value="<?= $user->getCity() ?>">
            </div>
            <div class="field field-default">
              <label for="">Улица*</label>
              <input type="text" placeholder="" name="sf_guard_user[street]" value="<?= $user->getStreet() ?>">
            </div>
            <div class="group-field group-field_address">
              <div class="field field-default field_w">
                <label for="">Дом</label>
                <input type="text" placeholder="" name="sf_guard_user[house]" value="<?= $user->getHouse() ?>">
              </div>
              <div class="field field-default field_w">
                <label for="">Корпус/строение</label>
                <input type="text" placeholder="" name="sf_guard_user[korpus]" value="<?= $user->getKorpus() ?>">
              </div>
              <div class="field field-default field_w">
                <label for="">Квартира/офис</label>
                <input type="text" placeholder="" name="sf_guard_user[apartament]" value="<?= $user->getApartament() ?>">
              </div>
            </div>
            <div class="form-note">
              <p>* - поля, обязательны для заполнения.</p>
              <p>Пожалуйста, указывайте достоверные данные, <br> чтобы мы могли доставить ваш заказ.</p>
            </div>
          </div>
        </div>
        <div class="wrap-btn">
          <button class="btn-full btn-full_rad js-submit-button">Сохранить изменения</button>
        </div>
      </form>
    </div>
  </div>
</div>
