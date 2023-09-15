<?php if ($type == 'notify') : ?>
  <noindex>
    <div class="white-popup-block block-popup popup-login mfp-hide" id="popup-notify">
      <div class="form-popup-wrapper">
        <div class="h2 block-popup__title" style="font-size: 150%;">Уведомить о поступлении</div><br />

        <form action="/popup-notify" method="post" class="form form-popup js-ajax-form">
          <p class="block-popup__descr"></p>
          <?/*<input type="hidden" name="sf_guard_user[_csrf_token]" value="<?php echo $csrf ?>">*/ ?>
          <div class="field field-default">
            <label>
              Ваше имя:
            </label>
            <input type="text" name="fio" value="<?= $fio ?>" />
          </div>
          <div class="field field-default">
            <label>
              Ваш email:*
            </label>
            <input type="email" class="" name="email" required placeholder="example@mail.ru" value="<?= $email ?>" />
          </div>
          <input type="hidden" name="product-id" value="" />
          <div class="btn-full btn-full_rad js-submit-button">Отправить</div>
        </form>
      </div>
    </div>
  </noindex>
<?php endif; ?>