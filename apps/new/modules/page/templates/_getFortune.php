<? if ($needToShow) : ?>
  <noindex>
    <div class="wheel-shutter js-wheel-close js-wheel-counter wheel-shutter-mod" data-nextshow="<?= $nextShow - time() ?>">
      <div class="wheel-icon"></div>
      <?/*Испытайте удачу!*/ ?>
      Получи скидку до 30%
    </div>
    <div class="wheel-wrapper wheel-wrapper-mod">
      <div class="js-wheel-close close"></div>
      <div class="wheel-container">
        <div class="wheel-body"></div>
        <div class="wheel-pointer"></div>
      </div>
      <form action="/wheel-result" class="js-submit-form">
        <div class="wheel-header">Розыгрыш до 30% скидки</div>
        <div class="wheel-text">Испытайте удачу, чтобы выиграть скидку. Введите ваш email и вращайте колесо!</div>
        <input placeholder="Email" name="email" type="email" class="js-rr-send js-hide-wheel">
        <div class="wheel-button js-submit-button">Испытать удачу!</div>
        <div class="small-text">Нажимая кнопку "Испытать удачу", я соглашаюсь с <a href="/personal_accept">политикой обработки персональных данных</a></div>
      </form>
    </div>
  </noindex>
<? endif ?>