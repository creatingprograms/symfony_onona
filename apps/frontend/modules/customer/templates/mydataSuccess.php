<?
global $isTest;
$h1='Мои данные';
slot('breadcrumbs', [
  ['text' => 'Личный кабинет', 'link'=>'/lk'],
  ['text' => $h1],
]);
slot('h1', $h1);
$birthday=explode('-',$user->getBirthday());
?>
<?php //use_helper('I18N') ?>
<?
//die('<pre>'.print_r($user->getBirthday(), true));
?>
<?/*<script type="text/javascript" src="/js/jquery.maskedinput-1.2.2.js"></script>
<script type="text/javascript" src="/js/validation.js?v=2"></script>*/?>
<main class="wrapper">
  <div class="lk-mydata">
    <form action="/customer/mydata" method="post" class="form js-ajax-form">
      <?php echo $form['_csrf_token']->render(); ?>
      <div class="lk-mydata-form">
        <div class="lk-mydata-element">
          <div class="white">
            <div class="header">Основная информация:</div>
            <div class="form-row">
              <div class="form-label">
                Имя*
              </div>
              <input type="text" name="sf_guard_user[first_name]" value="<?= $user->getFirstName() ?>">
            </div>
            <div class="form-row">
              <div class="form-label">
                Фамилия*
              </div>
              <input type="text" name="sf_guard_user[last_name]" value="<?= $user->getLastName() ?>">
            </div>
            <div class="form-row">
              <div class="form-label">
                E-mail (логин)*
              </div>
              <input type="text" name="sf_guard_user[email_address]" value="<?= $user->getEmailAddress() ?>">
            </div>
            <div class="form-row">
              <div class="form-label">
                Пароль(заполняйте, если хотите изменить)
              </div>
              <input type="password" name="sf_guard_user[password]" value="">
            </div>
            <div class="form-row">
              <div class="form-label">
                Дата рождения
              </div>
              <select name="sf_guard_user[birthday][day]">
                <option value=""></option>
                <? for($i=1; $i<32; $i++) :?>
                  <option value="<?=$i?>" <?= $i==$birthday[2] ? 'selected' : ''?>><?= $i<10 ? '0' : ''?><?=$i?></option>
                <? endfor ?>
              </select>
              <select name="sf_guard_user[birthday][month]">
                <option value=""></option>
                <? for($i=1; $i<13; $i++) :?>
                  <option value="<?=$i?>" <?= $i==$birthday[1] ? 'selected' : ''?>><?= $i<10 ? '0' : ''?><?=$i?></option>
                <? endfor ?>
              </select>
              <select name="sf_guard_user[birthday][year]">
                <option value=""></option>
                <? $year=date('Y')*1; ?>
                <? for($i=$year-18; $i>$year-100; $i--) :?>
                  <option value="<?=$i?>" <?= $i==$birthday[0] ? 'selected' : ''?>><?=$i?></option>
                <? endfor ?>
              </select>
            </div>
            <div class="form-row">
              <div class="form-label">
                Телефон*
              </div>
              <input type="tel" class="js-phone-mask" name="sf_guard_user[phone]" value="<?= $user->getPhone() ?>" placeholder="+7(___)___-__-__">
            </div>
            <div class="form-row">
              <div class="form-label">
                Индекс
              </div>
              <input type="text" name="sf_guard_user[index_house]" value="<?= $user->getIndexHouse() ?>">
            </div>
          </div>
        </div>

        <div class="lk-mydata-element">
          <div class="white">
            <div class="header">Информация для доставки:</div>
            <div class="form-row">
              <div class="form-label">
                Город*
              </div>
              <input type="text" name="sf_guard_user[city]" value="<?= $user->getCity() ?>">
            </div>
            <div class="form-row">
              <div class="form-label">
                Улица*
              </div>
              <input type="text" name="sf_guard_user[street]" value="<?= $user->getStreet() ?>">
            </div>
            <div class="form-row">
              <div class="form-label">
                Дом
              </div>
              <input type="text" name="sf_guard_user[house]" value="<?= $user->getHouse() ?>">
            </div>
            <div class="form-row">
              <div class="form-label">
                Корпус/строение
              </div>
              <input type="text" name="sf_guard_user[korpus]" value="<?= $user->getKorpus() ?>">
            </div>
            <div class="form-row">
              <div class="form-label">
                Квартира/офис
              </div>
              <input type="text" name="sf_guard_user[apartament]" value="<?= $user->getApartament() ?>">
            </div>
            <div class="form-row">
              <div class="form-label">
                Пол
              </div>
              <div class="male-female">
                <input type="radio" name="sf_guard_user[sex]" class="styleCH" value="m" id="sex-m" <?= $user->getSex()=="m" ? 'checked' : '' ?>>
                <label for="sex-m"> М </label>
                <input type="radio" name="sf_guard_user[sex]" class="styleCH" value="g" id="sex-f" <?= $user->getSex()=="g" ? 'checked' : '' ?>>
                <label for="sex-f"> Ж </label>
              </div>
            </div>
          </div>
          <p> * - поля, обязательны для заполнения.</p>
          <p class="red">Пожалуйста, указывайте достоверные данные, <br>чтобы мы могли доставить ваш заказ.</p>
        </div>
      </div>
      <div class="but js-submit-button">Сохранить</div>
      <?/*
        <table class="tableRegister">
            <?php
            foreach ($form as $key => $field):
                if ($field->renderLabel() == "<label for=\"sf_guard_user_city\">*Город</label>"):
                    ?>
                    <tbody>
                        <tr>
                            <td colspan="2">
                                <span><b>Информация для доставки**</b></span>
                                <div class="dashetCart"></div>
                            </td>
                        </tr>
                    </tbody>
                    <?
                endif;
                if (!$field->isHidden()):
                    ?>

                    <tbody>
                        <?php echo $field->renderRow() ?>
                    </tbody>

                    <?php
                else:
                    echo $field;
                endif;
                if ($field->renderLabel() == "<label for=\"sf_guard_user_phone\">*Телефон</label>" and $sf_user->getGuardUser()->getActivephone()):
                    ?>
                    <tbody>
                        <tr>
                            <th>
                            </th>
                            <td><img src="/images/tick.png" /> Телефон подтвержден
                            </td>
                        </tr>
                    </tbody>
                    <?
                endif;
            endforeach;
            ?>
        </table>
        <span>*</span> - поля, обязательны для заполнения.<br />
        <span>**</span> - Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить ваш заказ.<br /> <br />
        <div>
            <a class="red-btn" href="#" onClick="$('#register').submit()"><span>Сохранить</span></a>
        </div>*/?>
    </form>

</main>
