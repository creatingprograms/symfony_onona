<?php use_helper('I18N') ?>
<div class="wrapwer"><?php if(sfContext::getInstance()->getUser()->getAttribute('deliveryId')=="") echo __('Register', null, 'sf_guard'); else{ ?>
<div style="display: block;">
    <a href="/cart"><div style="position: relative; z-index: 10; float: left; background:url('/images/topInfoSel.png');width: 177px; height:19px;padding:7px;font-size: 13px;color: #FFF;text-shadow: black 1px 1px 2px, red 0 0 1em;">1. Оформление заказа</div></a>
    <a href="/cart/processorder"><div style="position: relative; z-index: 9; float: left; background:url('/images/topInfoSel.png');width: 157px; height:19px;left: -20px;padding:7px 7px 7px 27px;font-size: 13px;color:#FFF;text-shadow: black 1px 1px 2px, red 0 0 1em;">2. Доставка/оплата</div></a>
    <div style="position: relative; z-index: 8; float: left; background:url('/images/topInfoSel.png');width: 157px; height:19px;left: -40px;padding:7px 7px 7px 27px;font-size: 13px;color:#FFF;text-shadow: black 1px 1px 2px, red 0 0 1em;">3. Контактные данные</div>
</div><div style="clear: both;"></div><br />
    <?}?>

<?php echo get_partial('sfGuardRegister/form', array('form' => $form)) ?></div>
