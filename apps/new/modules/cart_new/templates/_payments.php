<?php if(sizeof($payments))  :?>
  <div class="block-payment">
    <? foreach ($payments as $key => $payment): ?>
      <? if($hideOnline && $payment->getId()==57) continue;?>
      <? if($hideOnline && $payment->getId()==59) continue;?>
      <? if(!$hideOnline && $payment->getId()==52) continue;?>
      <? //if($_SERVER['REMOTE_ADDR']!= '87.244.36.210' && $payment->getId()==60) continue;?>

      <label class="payment-item<?= $payment->getId() == 60 ? ' js-tinkoff-payment' : ''?>" for="payment-<?= $key ?>">
        <input type="radio" name="paymentId" value="<?= $payment->getId() ?>" id="payment-<?= $key ?>" class="js-payment" data-online="<?= $payment->getIsOnline() ?>" >
        <span><?= $payment->getName() ?></span>
        <?php if ($payment->getId()!=57 && 59!=$payment->getId()): ?>
          <span><?= $payment->getDescription() ?></span>
        <?php else :?>
          <div class="icons">
            <div class="icons-wrap"><img src="/frontend/images/payments/card.svg" title="Банковская карта"></div>
            <div class="icons-wrap"><img src="/frontend/images/payments/yoomoney.svg" title="ЮMoney"></div>
            <div class="icons-wrap"><img src="/frontend/images/payments/qiwi.svg" title="Qiwi"></div>
            <div class="icons-wrap"><img src="/frontend/images/payments/alfa.svg" title="Альфа-клик"></div>
            <div class="icons-wrap"><img src="/frontend/images/payments/webmoney.svg" title="Webmoney"></div>
            <div class="icons-wrap"><img src="/frontend/images/payments/tinkoff.svg" title="Тинькофф"></div>
          </div>
        <?php endif; ?>
      </label>

    <?php endforeach; ?>
  </div>
  <?/*<p style="font-size: 13px; line-height: 18px; color: #616C7C;">* Реквизиты для оплаты вам сообщит менеджер после подтверждения заказа.</p>*/?>
<? endif ?>
