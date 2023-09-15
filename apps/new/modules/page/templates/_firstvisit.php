<!-- блок формы подписки -->
<div class="wrap-block wrap-block-form">
  <div class="container">
    <noindex>
      <div class="block-form">
        <div class="block-form__content">
          <div class="h2 form__title">Хотите быть в курсе <br>наших предложений?</div>
          <p>Новости и спецпредложения</p>
        </div>
        <form action="/firstvisit/yes" class="form form-subsc js-submit-form">
          <div class="field">
            <input type="text" placeholder="Ваша электронная почта" name="email">
            <button class="btn link-fill -red js-submit-button">Подписаться</button>
          </div>
          <div class="custom-check">
            <div class="check-check_block" style="display: none;">
              <input type="checkbox" name="agreement" id="footer_agreement" class="check-check_input" checked>
              <div class="custom-check_shadow"></div>
            </div>
            <?/*<label for="footer_agreement" class="custom-check_label">Согласен с <a href="/personal_accept">политикой обработки</a> персональных данных</label>*/ ?>
            <label for="footer_agreement" class="custom-check_label">Нажимая на кнопку "Подписаться", Вы соглашаетесь с <a href="/personal_accept">политикой обработки</a> персональных данных.</label>
          </div>
        </form>
      </div>
    </noindex>
  </div>
</div>