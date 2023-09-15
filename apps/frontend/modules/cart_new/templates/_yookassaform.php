<? if($link) :?>
  <div class="payment-form">
    <div class="payment-form_header">
      <br>Вы выбрали онлайн-оплату.<br>
      <a href="<?=$link?>" class="but <?= $instantRedirect ? 'js-instant-redirect' : ''?>">Оплатить</a>
    </div>
  </div>
<? endif ?>
