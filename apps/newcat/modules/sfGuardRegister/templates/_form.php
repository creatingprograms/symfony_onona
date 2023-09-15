<?php use_helper('I18N') ?>
<link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
<script type="text/javascript" src="/js/jquery.tooltipster.js"></script>
<script>
  $(document).ready(function() {
    $('#sf_guard_user_first_name').tooltipster({
        content: $('<span style="width: 250px; display: block; font-size: 13px;">Пожалуйста, укажите своё реальное имя. Используйте кириллицу.</span>'),
        position: 'bottom'
    });
    $('#sf_guard_user_last_name').tooltipster({
        content: $('<span style="width: 250px; display: block; font-size: 13px;">Обязательное поле для доставки почтой России. Пожалуйста, укажите свою реальную фамилию. Используйте кириллицу.</span>'),
        position: 'bottom'
    });
    $('#sf_guard_user_phone').tooltipster({
        content: $('<span style="width: 250px; display: block; font-size: 13px;">Пожалуйста, укажите свой реальный телефон. Это необходимо для подтверждения заказа.</span>'),
        position: 'bottom'
    });
    $('#sf_guard_user_password').tooltipster({
        content: $('<span style="width: 250px; display: block; font-size: 13px;">Может содержать любые буквы (заглавные и маленькие, кириллицу и латиницу), цифры и нижнее подчеркивание. Все остальные символы, в т. ч. пробел, использовать нельзя.</span>'),
        position: 'bottom'
    });
  });
</script>
<form action="<?php echo url_for('@sf_guard_register') ?>" method="post" id="register" class="form-register">

    <div align="center" class="pink bold">Регистрация на сайте OnOna.ru</div>
    <?/*
    <div class="pink bold" style="padding:5px;color: #ba272d; font-size: 16px; margin-bottom: 0px;">Регистрация через соц. сети</div><script src="//ulogin.ru/js/ulogin.js"></script>
          <div id="uLogin" style="margin-bottom: 20px;" data-ulogin="display=panel;fields=first_name,last_name,email;optional=bdate,city;providers=yandex,vkontakte,facebook,twitter,odnoklassniki,mailru,googleplus;hidden=other;redirect_uri=http%3A%2F%2Fonona.ru%2Fregss"></div>
    */?>
    <table class="tableRegister">
        <tbody>
            <tr>
                <td colspan="2" style="text-align: left">
                    <span>Основная информация **</span>
                    <?/*<div class="dashetCart" style="width: 65%;"></div>*/?>

                </td>
            </tr>
        </tbody>
        <?php
         foreach ($form as $key => $field):
            if ($field->renderLabel() == "<label for=\"sf_guard_user_index_house\">Индекс</label>"): ?>
                <tbody>
                    <tr>
                        <td colspan="2" style="text-align: left">
                            <span>Информация для доставки**</span>
                            <?/*<div class="dashetCart" style="width: 65%;"></div>*/?>
                            </u>
                        </td>
                    </tr>
                </tbody>
                <?
            endif;
            if (!$field->isHidden()):

                    ?>

                    <tbody style="font-weight: normal;">
                        <tr>
                            <th>
                              <?php
                                if ($field->renderLabel() == "<label for=\"sf_guard_user_birthday\">Дата рождения</label>") {
                                    echo "<label for=\"sf_guard_user_birthday\" style=\"position: relative; top: -7px;\">Дата рождения</label>";
                                }
                                else
                                    echo $field->renderLabel()
                              ?>
                            </th>
                            <td>

                                <?php echo $field->renderError() ?>
                                <?php echo $field ?>

                                <?
                                if ($field->renderLabel() == "<label for=\"sf_guard_user_birthday\">Дата рождения</label>") {
                                  echo "<p style='clear: both;'>Чтобы получать от нас приятные подарки и <br />скидки, пожалуйста, укажите свои данные.</p>";
                                }
                                ?>

                            </td>
                        </tr>
                    </tbody>

                    <?php

            else:
                echo $field;

            endif;
        endforeach;
        ?>
        <tfoot>
            <tr>
                <td colspan="2"><br />
                    <span>*</span> - Поля, обязательны для заполнения.<br />
                    <span>**</span> - Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить Ваш заказ.<br />
                    <span>Все данные наших клиентов надежно защищены и не подлежат разглашению.</span><br />
        <center>
            <div style="margin-top: 20px;">
                <a class="red-btn colorWhite" href="#" onClick="$('#register').submit()"><span>Зарегистрироваться</span></a>
            </div></center>  <br />  <br />  <br />
        <img src="/images/registration/krug-registration.png" style="position: relative; top: 3px;"> Нажимая на кнопку «Зарегистрироваться», я соглашаюсь с условиями <a href="https://onona.ru/dogovor-oferta" target="_blank">Публичной оферты</a>
        и  принимаю условия <a href="/personal_accept" title="Пользовательское соглашение" target="_blank">Пользовательского соглашения.</a>
        </td>
        </tr>
        </tfoot>
    </table>
    <div style="clear: both;margin-bottom: 20px;"></div>
</form>
<div class="form-register-right">
    <?
      $footer = PageTable::getInstance()->findOneBySlug("tekst-stranicy-registracii");
      echo $footer->getContent();
    ?>
</div>
