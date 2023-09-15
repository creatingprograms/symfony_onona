<?php if(sizeof($payments))  foreach ($payments as $key => $payment): ?>
  <? if($hideOnline && $payment->getId()==57) continue;?>
  <? if(!$hideOnline && $payment->getId()==52) continue;?>
  <? if($hideOnline && $payment->getId()==58) continue;?>
  <div class="basket-payment-item">
    <input type="radio" name="paymentId" value="<?= $payment->getId() ?>" id="payment-<?= $key ?>" class="js-payment" data-online="<?= $payment->getIsOnline() ?>" <?=($payment->getId()==58 && !$isAndroid) ? 'disabled' : '' ?>>
    <label for="payment-<?= $key ?>">
      <span class="basket-payment-img">
        <img src="/uploads/delivery/<?= $payment->getPicture() ?>" width="34px">
        <img src="/uploads/delivery/<?= $payment->getPicturehover() ?>" class="-hoveredImg" width="34px">
      </span>
      <span class="basket-payment-h"> <?= $payment->getName() ?> </span>
      <span class="basket-payment-title"><?= $payment->getDescription() ?> </span>
    </label>
  </div>
<?php endforeach; ?>

<div class="basket-payment-hint">
  Реквизиты для оплаты вам сообщит менеджер после подтверждения заказа.
</div>
