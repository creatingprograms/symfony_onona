<?php use_helper('I18N') ?>
<div id="registerPage">
    <h1 style="font-size: 16px; color: #c3060e;margin: 10px;">Регистрация на сайте OnOna.ru</h1>
    <form action="<?php echo url_for('@sf_guard_register') ?>" method="post" id="register">

        <?php echo get_partial('sfGuardRegister/form', array('form' => $form)) ?>

        <div class="rulesAndButton">
            <span style="color: #c3060e;">*</span> - Поля, обязательны для заполнения.<br /> 
            <span style="color: #c3060e;">**</span> - Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить Ваш заказ.<br /> <br /> 

            <img src="/images/registration/krug-registration.png" style="position: relative; top: 3px;"> Нажимая на кнопку «Зарегистрироваться», я соглашаюсь с условиями <a href="http://m.onona.ru/dogovor-oferta" target="_blank">Публичной оферты</a>.

            <br />  <br />
            <div style="margin: 20px;">
                <a class="redButton" href="#" onClick="$('#register').find('[type=\'submit\']').trigger('click');"><span>Зарегистрироваться</span></a>
            </div>   
            <input type="submit" class="submitButton" style="display: none;" />
        </div>
    </form>
</div>