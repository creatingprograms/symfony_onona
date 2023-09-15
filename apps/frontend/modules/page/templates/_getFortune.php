<? if ($needToShow) :?>
  <div class="wheel-shutter js-wheel-close">
    <div class="wheel-icon"></div>
    Испытайте удачу!
  </div>
  <div class="wheel-wrapper">
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
    </form>
  </div>
<? endif ?>
