<form action="/konultsexolog" class="form  js-ajax-form">
  <div class="form-wrapper">
    <div class="form-wrapper-element">
      <div class="form-row">
        <div class="form-label">
          Ваше имя/всевдоним:*
        </div>
        <input type="text" value="<?$sf_user->isAuthenticated() ? $sf_user->getGuardUser()->getFirstName() .' '.$sf_user->getGuardUser()->getLastName()  : ''?>" name="fio" >
      </div>
      <div class="form-row">
        <div class="form-label">
          Ваш e-mail:*
        </div>
        <input type="email" value="<?= $sf_user->isAuthenticated() ? $sf_user->getGuardUser()->getEmailAddress() : ''?>" name="email" <?=$sf_user->isAuthenticated() ? ' readonly' : ''?> class="js-rr-send">
      </div>
      <div class="form-row">
        <input type="checkbox" class="styleCH" name="agreement" id="val-rev-1" value="1">
        <label for="val-rev-1">Я принимаю условия <a target="_blank" href="/personal_accept">Пользовательского соглашения *</a></label>
      </div>
      <div class="form-row">
        <div class="form-label">
          * - поля, отмеченные * обязательны для заполнения.
        </div>
      </div>
    </div>
    <div class="form-wrapper-element">
      <div class="form-row">
        <div class="form-label">
          Тема сообщения:*
        </div>
        <input type="text" value="" name="sub">
      </div>
      <div class="form-row">
        <div class="form-label">
          Сообщение:*
        </div>
        <textarea name="comment"></textarea>
      </div>
    </div>
  </div>
  <div class="but js-submit-button">Отправить</div>
</form>
