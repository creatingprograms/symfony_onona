<?php use_helper('I18N') ?>
<? global $isNew; ?>
<? if(!$isNew) :?>
  <main class="wrapper">
    <h2><?php echo __('Hello %name%', array('%name%' => $user->getName()), 'sf_guard') ?></h2>

    <h3><?php echo __('Enter your new password in the form below.', null, 'sf_guard') ?></h3>

    <form class="form-er" action="<?php echo url_for('@sf_guard_forgot_password_change?unique_key='.$sf_request->getParameter('unique_key')) ?>" method="POST" style="margin: 20px 0;">
      <table>
        <tbody>
          <?php echo $form ?>
        </tbody>
        <tfoot><tr><td><input class="but" type="submit" name="change" value="<?php echo __('Change', null, 'sf_guard') ?>" /></td></tr></tfoot>
      </table>
    </form>
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
        <form class="form form-reg width_min" action="/guard/forgot_password/<?=$sf_request->getParameter('unique_key') ?>" method="POST">
          <div class="fieldset-block">
            <div class="field field-default field-name">
              <?php echo $form ?>
            </div>
            <div class="field field-default field-name">
            </div>
          </div>
          <div class="form-reg__bottom">
            <div class="wrap-btn">
              <input class="but btn-full btn-full_rad" type="submit" name="change" value="<?php echo __('Change', null, 'sf_guard') ?>" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<? endif ?>
