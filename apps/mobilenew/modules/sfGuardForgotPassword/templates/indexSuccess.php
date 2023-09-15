<?php use_helper('I18N') ?>
<div id="forgotPasswordBlock">
    <h1 style="font-size: 18px; color: #c3060e;margin-bottom: 10px;"><?php echo __('Forgot your password?', null, 'sf_guard') ?></h1>

    <p>
        <b style="font-weight: bold">Что делать, если Вы забыли свой пароль?</b><br />
        В форму ниже введите свой электронный адрес, указанный при регистрации, и через несколько минут на Ваш E-mail придёт письмо с инструкцией.
    </p>
    <form action="<?php echo url_for('@sf_guard_forgot_password') ?>" method="post" class="forgotPassword">
        <? if ($noneMail) echo "<b style=\"color: red;\">Данная почта не зарегистрирована. Попробуйте ещё раз.</b><br />"; ?>
        Адрес электронной почты: *<br />
        <input type="text" name="forgot_password[email_address]" id="forgot_password_email_address" style="width: 100%; padding: 5px 0;"><br/>
        <? /* <table>
          <tbody>
          <?php  echo $form  ?>
          </tbody>
          <tfoot><tr><td>
          <input type="submit" name="change" value="<?php echo __('Request', null, 'sf_guard') ?>" /></td></tr></tfoot>
          </table> */ ?>
    </form>
    <div style="padding: 20px;">    
        <div class="redButton" onclick="$('.forgotPassword').submit();">Восстановить пароль</div>
    </div>

</div>