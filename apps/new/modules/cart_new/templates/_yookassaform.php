<? if($link) :?>
  <div class="payment-form">
    <div class="payment-form_header">
      Вы выбрали онлайн-оплату
    </div>

      <a href="<?=$link?>" class="but <?= $instantRedirect ? 'js-instant-redirect' : ''?>">Оплатить</a>
  </div>
<? endif ?>
