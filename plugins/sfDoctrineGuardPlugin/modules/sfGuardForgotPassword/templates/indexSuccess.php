<?php use_helper('I18N') ?>
<? global $isNew; ?>
<? if(!$isNew) :?>
  <main class="wrapper">
      <h2><?php echo __('Forgot your password?', null, 'sf_guard') ?></h2>

      <p>
        <?php echo __('Do not worry, we can help you get back in to your account safely!', null, 'sf_guard') ?>
        <?php echo __('Fill out the form below to request an e-mail with information on how to reset your password.', null, 'sf_guard') ?>
      </p>
      <form action="<?php echo url_for('@sf_guard_forgot_password') ?>" method="post" class="form-er" style="margin: 20px 0;">
        <table>
          <tbody>
              <?if($noneMail) echo "<b style=\"color: red;\">Данная почта не зарегистрирована. Попробуйте ещё раз.</b>"; ?>
            <?php echo $form ?>
          </tbody>
          <tfoot><tr><td><input class="but" type="submit" name="change" value="<?php echo __('Request', null, 'sf_guard') ?>" /></td></tr></tfoot>
        </table>
      </form><br />
      Если письмо не пришло в течении минуты проверьте папку Спам.
  </main>
<? else :?>
  <?
    $h1='Восстановление пароля';
    slot('breadcrumbs', [
      // ['text' => 'Личный кабинет', 'link'=>'/lk'],
      ['text' => $h1],
    ]);
    slot('h1', $h1);
  ?>
  <div class="wrap-block wrap-block-reg">
    <div class="container">
      <div class="block-content">
        <p>
          <?php echo __('Do not worry, we can help you get back in to your account safely!', null, 'sf_guard') ?>
          <?php echo __('Fill out the form below to request an e-mail with information on how to reset your password.', null, 'sf_guard') ?>
        </p>
        <form action="/guard/forgot_password" method="post" class="form form-reg width_min">
          <?if($noneMail) echo "<b style=\"color: red;\">Данная почта не зарегистрирована. Попробуйте ещё раз.</b>"; ?>
          <div class="fieldset-block">
            <div class="field field-default field-name">
              <?php echo $form ?>
            </div>
            <div class="field field-default field-name">
            </div>
          </div>
          <div class="form-reg__bottom">
            <div class="wrap-btn">
              <input class="but btn-full btn-full_rad" type="submit" name="change" value="<?php echo __('Request', null, 'sf_guard') ?>" />
            </div>
          </div>

        </form>
        <p><br>Если письмо не пришло в течении минуты проверьте папку Спам.</p>
      </div>
    </div>
  </div>
<? endif ?>
