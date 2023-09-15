<?
  $h1= "Оценка качества работы магазина";
  slot('breadcrumbs', [
    $allShops,
    ['text' => $h1],
  ]);
  slot('catalog-class', '-innerWhite -oprosnik');
?>
<main class="wrapper">
  <h1><?= $h1 ?></h1>
  <div class="-innerpage">
    <div class="subheader"> № заказа <?= $order->getPrefix() ?><?= $order->getId() ?></div>
    <? if(!$orderExist) :?>
      <div class="image-box">
        <div class="header">
          СПАСИБО, ЧТО ВЫБРАЛИ НАС!
        </div>
        <div class="text">
          Расскажите о своей покупке в интернет-магазине onona.ru
          чтобы мы не останавливались на достигнутом, росли
          и&nbsp;предлагали клиентам лучший сервис!
        </div>
      </div>
      <form class="js-ajax-form form form-oprosnik" action="/oprosnik/save" methos="post">
        <input type="hidden" name="id" value="<?= $order->getId() ?>">
        <? switch($order->getStatus()) {
            case 'Возврат': ?>
              <div class="form-row js-question-wrapper">
                <div class="form-label">
                  Укажите причину возврата вашего заказа?
                </div>
                <textarea placeholder="" name="comment_short" class=" js-need-all"></textarea>
              </div>
              <div class="form-row js-question-wrapper">
                <div class="form-label">
                  Оцените работу менеджера,<br>
                  принявшего и оформившего ваш заказ.
                  <label>1 - низший балл, 5 - высший</label>
                </div>
                <div class="male-female">
                  <input type="radio" name="manager_rate" class="styleCH js-need-all" value="1" id="q21">
                  <label for="q21"> 1 </label>
                  <input type="radio" name="manager_rate" class="styleCH js-need-all" value="2" id="q22">
                  <label for="q22"> 2 </label>
                  <input type="radio" name="manager_rate" class="styleCH js-need-all" value="3" id="q23">
                  <label for="q23"> 3 </label>
                  <input type="radio" name="manager_rate" class="styleCH js-need-all" value="4" id="q24">
                  <label for="q24"> 4 </label>
                  <input type="radio" name="manager_rate" class="styleCH js-need-all" value="5" id="q25">
                  <label for="q25"> 5 </label>
                </div>
                <hr>
              </div>
              <div class="form-row js-question-wrapper">
                <div class="form-label">
                  Обратитесь ли вы вновь в магазин onona.ru,<br>
                  порекомендуете ли его друзьям и знакомым?
                </div>
                <div class="male-female">
                  <input type="radio" name="is_recommend" class="styleCH  js-need-all" value="да" id="q11">
                  <label for="q11"> Да </label>
                  <input type="radio" name="is_recommend" class="styleCH  js-need-all" value="нет" id="q12">
                  <label for="q12"> Нет </label>
                </div>
                <hr>
              </div>
            <? break; ?>
          <? default :?>
            <div class="form-row">
              <div class="form-label">
                Обратитесь ли вы вновь в магазин onona.ru,<br>
                порекомендуете ли его друзьям и знакомым?
              </div>
              <div class="male-female">
                <input type="radio" name="is_recommend" class="styleCH js-submit-enable" value="да" id="q11">
                <label for="q11"> Да </label>
                <input type="radio" name="is_recommend" class="styleCH js-submit-enable" value="нет" id="q12">
                <label for="q12"> Нет </label>
              </div>
              <hr>
            </div>
            <div class="form-row">
              <div class="form-label">
                Удобен ли сайт нашего Интернет-магазина?
              </div>
              <div class="male-female">
                <input type="radio" name="is_easy" class="styleCH js-submit-enable" value="да" id="eq11">
                <label for="eq11"> Да </label>
                <input type="radio" name="is_easy" class="styleCH js-submit-enable" value="нет" id="eq12">
                <label for="eq12"> Нет </label>
              </div>
              <hr>
            </div>
            <div class="form-row">
              <div class="form-label">
                Оцените работу менеджера,<br>
                принявшего и оформившего ваш заказ.
                <label>1 - низший балл, 5 - высший</label>
              </div>
              <div class="male-female">
                <input type="radio" name="manager_rate" class="styleCH js-submit-enable" value="1" id="q21">
                <label for="q21"> 1 </label>
                <input type="radio" name="manager_rate" class="styleCH js-submit-enable" value="2" id="q22">
                <label for="q22"> 2 </label>
                <input type="radio" name="manager_rate" class="styleCH js-submit-enable" value="3" id="q23">
                <label for="q23"> 3 </label>
                <input type="radio" name="manager_rate" class="styleCH js-submit-enable" value="4" id="q24">
                <label for="q24"> 4 </label>
                <input type="radio" name="manager_rate" class="styleCH js-submit-enable" value="5" id="q25">
                <label for="q25"> 5 </label>
              </div>
              <hr>
            </div>
            <div class="form-row">
              <div class="form-label">
                Оцените работу курьера,<br>
                доставившего ваш заказ.
                <label>1 - низший балл, 5 - высший</label>
              </div>
              <div class="male-female">
                <input type="radio" name="delivery_rate" class="styleCH js-submit-enable" value="1" id="q31">
                <label for="q31"> 1 </label>
                <input type="radio" name="delivery_rate" class="styleCH js-submit-enable" value="2" id="q32">
                <label for="q32"> 2 </label>
                <input type="radio" name="delivery_rate" class="styleCH js-submit-enable" value="3" id="q33">
                <label for="q33"> 3 </label>
                <input type="radio" name="delivery_rate" class="styleCH js-submit-enable" value="4" id="q34">
                <label for="q34"> 4 </label>
                <input type="radio" name="delivery_rate" class="styleCH js-submit-enable" value="5" id="q35">
                <label for="q35"> 5 </label>
              </div>
              <hr>
            </div>
            <div class="form-row">
              <div class="form-label">
                Довольны ли Вы приобретенным товаром? 
              </div>
              <div class="male-female">
                <input type="radio" name="is_items_ok" class="styleCH js-submit-enable" value="да" id="eq21">
                <label for="eq21"> Да </label>
                <input type="radio" name="is_items_ok" class="styleCH js-submit-enable" value="нет" id="eq22">
                <label for="eq22"> Нет </label>
              </div>
              <hr>
            </div>
            <div class="form-row">
              <div class="form-label">
                Мы хотим, чтобы покупки в нашем магазине<br>
                приносили только положительные эмоции.
              </div>
              <textarea placeholder="Пожалуйста, оставьте Ваш комментарий по поводу нашей работы, для нас это очень важно и мы хотим становиться лучше )))" name="comment_short" class="js-submit-enable"></textarea>
            </div>
        <? } ?>
        <div class="but -big js-submit-button disabled">ОТПРАВИТЬ</div>
      </form>
    <? else : ?>
      <div class="image-box">
        <div class="header">
          СПАСИБО, ЧТО ВЫБРАЛИ НАС!
        </div>
        <div class="text">
          Вы уже заполнили анкету и Ваше мнение учтено!
        </div>
      </div>
    <? endif ?>
  </div>
  <div class="advantages">
    <div class="advantages-element basket-cart">
      <div class="header">
        Наш магазин работает с 1992 года
      </div>
      Уже десять лет мы радуем своих покупателей, предоставляя отличный сервис
    </div>
    <div class="advantages-element deliv">
      <div class="header">
        Доставлено более 500 000 заказов
      </div>
      Сложно найти город в России, куда мы еще не отправили заказ
    </div>
    <div class="advantages-element finger">
      <div class="header">
        70% клиентов заказывают снова
      </div>
      Система накопительных скидок позволяет экономить постоянным покупателям
    </div>
  </div>
</main>
