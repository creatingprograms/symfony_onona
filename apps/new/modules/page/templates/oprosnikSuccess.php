<?
  $h1= "Оценка качества работы магазина";
  slot('breadcrumbs', [
    $allShops,
    ['text' => $h1],
  ]);
  slot('catalog-class', ' -oprosnik');
  slot('h1', $h1);
  // die(__FILE__.'|'.__LINE__.'<pre>'.print_r([md5(198086)], true).'</pre>');
  // $order->setStatus("Возврат");
?>
<div class="wrap-block">
  <div class="container">
    <div class="block-top">
      <div class="subheader">№ заказа <?= $order->getPrefix() ?><?= $order->getId() ?></div>
      <div class="subheader">СПАСИБО, ЧТО ВЫБРАЛИ НАС!</div>
    </div>
    <? if(!$orderExist) :?>
   
      <form class="js-ajax-form form form-oprosnik" action="/oprosnik/save" methos="post">
        <input type="hidden" name="id" value="<?= $order->getId() ?>">
        <input type="hidden" name="status" value="<?= $order->getStatus() ?>">
        <? switch($order->getStatus()) {
            case 'Возврат': ?>
              <div class="form-row js-question-wrapper flat">
                <div class="form-label">
                  Расскажите о своей покупке в интернет-магазине onona.ru
                  чтобы мы не останавливались на достигнутом, росли
                  и&nbsp;предлагали клиентам лучший сервис!
                  Укажите причину возврата вашего заказа?
                </div>
                <div class="form-controls">
                  <textarea placeholder="" name="comment_short" class="js-need-all"></textarea>
                </div>
              </div>
              <div class="form-row js-question-wrapper">
                <div class="form-label">
                  Оцените работу менеджера,
                  принявшего и оформившего ваш заказ.
                  1 - низший балл, 5 - высший
                </div>
                <div class="form-controls">
                  <div class="custom-check custom-check_circle">
                    <div class="check-check_block">
                    <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="1" id="q21">
                    <div class="custom-check_shadow"></div>
                  </div>
                    <label class="custom-check_label" for="q21"> 1 </label>
                  </div>
                  <div class="custom-check custom-check_circle">
                    <div class="check-check_block">
                      <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="2" id="q22">
                      <div class="custom-check_shadow"></div>
                    </div>
                    <label class="custom-check_label" for="q22"> 2 </label>
                  </div>
                  <div class="custom-check custom-check_circle">
                    <div class="check-check_block">
                      <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="3" id="q23">
                      <div class="custom-check_shadow"></div>
                    </div>
                    <label class="custom-check_label" for="q23"> 3 </label>
                  </div>
                  <div class="custom-check custom-check_circle">
                    <div class="check-check_block">
                      <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="4" id="q24">
                      <div class="custom-check_shadow"></div>
                    </div>
                    <label class="custom-check_label" for="q24"> 4 </label>
                  </div>
                  <div class="custom-check custom-check_circle">
                    <div class="check-check_block">
                      <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="5" id="q25">
                      <div class="custom-check_shadow"></div>
                    </div>
                    <label class="custom-check_label" for="q25"> 5 </label>
                  </div>
                </div>
              </div>
              <div class="form-row js-question-wrapper">
                <div class="form-label">
                  Обратитесь ли вы вновь в магазин onona.ru, 
                  порекомендуете ли его друзьям и знакомым?
                </div>
                <div class="form-controls">
                  <div class="custom-check custom-check_circle">
                    <div class="check-check_block">
                      <input type="radio" name="is_recommend" class="check-check_input  js-need-all" value="да" id="q11">
                      <div class="custom-check_shadow"></div>
                    </div>
                    <label class="custom-check_label" for="q11"> Да </label>
                  </div>
                  <div class="custom-check custom-check_circle">
                    <div class="check-check_block">
                      <input type="radio" name="is_recommend" class="check-check_input  js-need-all" value="нет" id="q12">
                      <div class="custom-check_shadow"></div>
                    </div> 
                    <label class="custom-check_label" for="q12"> Нет </label>
                  </div>
                    
                </div>
              </div>
            <? break; ?>
          <? default :?>
            <div class="form-row js-question-wrapper">
              <div class="form-label">
                Расскажите о своей покупке в интернет-магазине onona.ru
                чтобы мы не останавливались на достигнутом, росли
                и&nbsp;предлагали клиентам лучший сервис!
                Обратитесь ли вы вновь в магазин onona.ru, 
                порекомендуете ли его друзьям и знакомым?
              </div>
              <div class="form-controls">
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="is_recommend" class="check-check_input js-need-all" value="да" id="q11">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q11" class="custom-check_label"> Да </label>
                </div>
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="is_recommend" class="check-check_input js-need-all" value="нет" id="q12">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q12" class="custom-check_label"> Нет </label>
                </div>
              </div>
            </div>
            <div class="form-row js-question-wrapper">
              <div class="form-label">
                Удобен ли сайт нашего Интернет-магазина?
              </div>
              <div class="form-controls">
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="is_easy" class="check-check_input js-need-all" value="да" id="eq11">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="eq11" class="custom-check_label"> Да </label>
                </div>
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="is_easy" class="check-check_input js-need-all" value="нет" id="eq12">
                    <div class="custom-check_shadow"></div>
                  </div>

                  <label for="eq12" class="custom-check_label"> Нет </label>
                </div>
              </div>
            </div>
            <div class="form-row js-question-wrapper">
              <div class="form-label">
                Оцените работу менеджера,
                принявшего и оформившего ваш заказ.
                1 - низший балл, 5 - высший
              </div>
              <div class="form-controls">
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="1" id="q21">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q21" class="custom-check_label"> 1 </label>
                </div>
                  
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="2" id="q22">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q22" class="custom-check_label"> 2 </label>
                </div>
                  
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="3" id="q23">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q23" class="custom-check_label"> 3 </label>
                </div>

                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="4" id="q24">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q24" class="custom-check_label"> 4 </label>
                </div>
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="manager_rate" class="check-check_input js-need-all" value="5" id="q25">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q25" class="custom-check_label"> 5 </label>
                </div>
              </div>
            </div>
            <div class="form-row js-question-wrapper">
              <div class="form-label">
                Оцените работу курьера,
                доставившего ваш заказ.
                1 - низший балл, 5 - высший
              </div>
              <div class="form-controls">
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="delivery_rate" class="check-check_input js-need-all" value="1" id="q31">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q31" class="custom-check_label"> 1 </label>
                </div>
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="delivery_rate" class="check-check_input js-need-all" value="2" id="q32">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q32" class="custom-check_label"> 2 </label>
                </div>  
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="delivery_rate" class="check-check_input js-need-all" value="3" id="q33">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q33" class="custom-check_label"> 3 </label>
                </div>  
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="delivery_rate" class="check-check_input js-need-all" value="4" id="q34">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q34" class="custom-check_label"> 4 </label>
                </div>  
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="delivery_rate" class="check-check_input js-need-all" value="5" id="q35">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="q35" class="custom-check_label"> 5 </label>
                </div>  
              </div>
            </div>
            <div class="form-row js-question-wrapper">
              <div class="form-label">
                Довольны ли Вы приобретенным товаром? 
              </div>
              <div class="form-controls">
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="is_items_ok" class="check-check_input js-need-all" value="да" id="eq21">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="eq21" class="custom-check_label"> Да </label>
                </div>
                  
                <div class="custom-check custom-check_circle">
                  <div class="check-check_block">
                    <input type="radio" name="is_items_ok" class="check-check_input js-need-all" value="нет" id="eq22">
                    <div class="custom-check_shadow"></div>
                  </div>
                  <label for="eq22" class="custom-check_label"> Нет </label>
                </div>
              </div>
            </div>
            <div class="form-row flat js-question-wrapper">
              <div class="form-label">
                Комментарий*
              </div>
              <div class="form-controls">
                <textarea placeholder="Очень классный товар, рекомендую!" name="comment_short" class="js-need-all"></textarea>
              </div>
            </div>
        <? } ?>
        <div class="flex">
          <div class="btn-full btn-full_rad js-submit-button disabled">Отправить</div>
          <div class="subheader">Мы хотим, чтобы покупки в нашем магазине приносили только положительные эмоции.</div>
        </div>
      </form>
    <? else : ?>
      <div class="text">
        Вы уже заполнили анкету и Ваше мнение учтено!
      </div>
    <? endif ?>
  </div>
</div>
