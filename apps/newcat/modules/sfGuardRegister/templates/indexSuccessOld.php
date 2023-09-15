<?php use_helper('I18N') ?>
<script type="text/javascript" src="/js/jquery.maskedinput-1.2.2.js"></script>
<script type="text/javascript" src="/js/validation.js?v=2"></script>
<div class="wrapwer"><?php if(sfContext::getInstance()->getUser()->getAttribute('deliveryId')=="") echo '<div align="center" class="pink bold" style="padding:5px;color: #ba272d;">'.__('Register', null, 'sf_guard').'</div>'; else{
    
    slot('topMenu', true);?>
    <div class="borderCart">
        <div align="center" class="pink bold" style="padding:5px;color: #ba272d;">Моя корзина </div>
        <div style="display: block;">
            <div style="position: relative; z-index: 10; float: left; background: url('/newdis/images/cart/top1_act.png') repeat scroll 0pt 0pt transparent; height: 32px; width: 186px;"></div>
            <div style="position: relative; float: left; height: 32px; width: 186px; left: -13px; z-index: 9; background: url('/newdis/images/cart/top2_act.png') repeat scroll 0pt 0pt transparent;"></div>
            <div style="position: relative; float: left; height: 32px; width: 186px; left: -26px; z-index: 8; background: url('/newdis/images/cart/top3_act.png') repeat scroll 0pt 0pt transparent;"></div>
        </div>
        <div style="clear: both;margin-bottom: 20px;"></div>
<?php if ($sf_user->isAuthenticated()): ?>
        <div style="clear: both;margin-bottom: 20px;"></div>Проверьте, пожалуйста, правильность заполненных данных.
    <?php endif; ?>
    <?}?>
<?php echo get_partial('sfGuardRegister/form', array('form' => $form)) ?>
    <?php        if(sfContext::getInstance()->getUser()->getAttribute('deliveryId')!="") echo "</div>";?>
    </div>