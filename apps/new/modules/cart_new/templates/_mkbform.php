<div class="payment-form">
  <div class="payment-form_header">
    Вы выбрали онлайн-оплату.
  </div>
  <form action="<?=$mkbForm['action']?>" method="<?= $mkbForm['method'] ?>">
    <input id='mid' type='hidden' value='<?= $mkbForm['mid'] ?>' name='mid' >
    <input id='aid' type='hidden' value='<?= $mkbForm['aid'] ?>' name='aid' >
    <input id='amount' type='hidden' value='<?= $mkbForm['amount'] ?>' name='amount'>
    <input id='oid' type='hidden' value='<?= $mkbForm['oid'] ?>' name='oid' >
    <input id='signature' type='hidden' value='<?= $mkbForm['signature'] ?>' name='signature'>
    <input id='redirect_url' type='hidden' value='<?= $mkbForm['redirect_url'] ?>' name='redirect_url'>
    <input id='cancel_link' type='hidden' value='<?= $mkbForm['cancel_link'] ?>' name='cancel_link'>
    <input id='directposturl' type='hidden' value='<?= $mkbForm['directposturl'] ?>' name='directposturl'>
    <input id='merchant_mail' type='hidden' value='<?= $mkbForm['merchant_mail'] ?>' name='merchant_mail'>
    <input id='client_mail' type='hidden' value='<?= $mkbForm['client_mail'] ?>' name='client_mail'>
    <input type="submit" value="Оплатить" class="but">
  </form>
</div>
