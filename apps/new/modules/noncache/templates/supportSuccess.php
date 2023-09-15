<?
  $h1="Обратная связь";
  slot('h1',$h1);
  slot('breadcrumbs', [
    ['text' => $h1],
  ]);
  slot('metaTitle', $h1.' | Сеть магазинов для взрослых 18+ "Он и Она"');
  slot('metaKeywords', $h1);
  slot('metaDescription', $h1.' | Интернет-магазин "Он и Она"');
?>
<div class="wrapper  -action">
  <div class="support-page">
    <form class="support-item form  -isWhite" method="post" action="/support">
      <div class="support-header">
        Заполните форму обратной связи:
      </div>
      <?/*<div class="regPage-group">*/?>
        <div class="form-row">
          <div class="form-label">
            Имя:*
          </div>
          <input type="text" value="" name="name">
        </div>
        <div class="form-row">
          <div class="form-label">
            E-mail:*
          </div>
          <input type="email" value="" name="email">
        </div>
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
          <textarea placeholder="" name="comment"></textarea>
        </div>
        <div class="form-row">
          <input type="checkbox" name="agreement" class="styleCH" id="agreement" value="1">
          <label for="agreement">Я принимаю условия <a target="_blank" href="/personal_accept">Пользовательского соглашения</a></label>
        </div>
      <?/*</div>*/?>
      <div class="but -big js-submit-button">Отправить</div>
      <div class="form-row">
        <div class="form-label gray">
          *-поля, обязательны для заполнения
        </div>
      </div>
    </form>
    <div class="support-item">
      <p>Уважаемый пользователь!</p>
      <p>
        Мы ценим ваше время и стремимся максимально быстро и качественно предоставить ответ на ваш вопрос. Если у вас
        возникли проблемы* или вы хотите внести предложения по работе сайта OnOna.ru, напишите нам, мы постараемся их
        оперативно решить в короткие сроки.
      </p>
      <p class="red">*Пожалуйста, не забудьте указать ссылку на страницу сайта, браузер, который вы используете, и опишите
        подробно возникшую проблему (если у вас таковая возникла).</p>
      <p>Ответ на ваш запрос будет предоставлен специалистом в срок от 1 до 48 часов</p>
      <img src="/frontend/images/support.jpg">
    </div>
  </div>

</div>
