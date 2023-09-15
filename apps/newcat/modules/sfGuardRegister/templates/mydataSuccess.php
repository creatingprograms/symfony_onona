<?php use_helper('I18N');
slot('topMenu', true);
?>

    <div class="borderCart">
<div align="center" class="pink bold" style="padding:5px;color: #ba272d;">Моя корзина </div>
<div style="display: block;">
    <div style="position: relative; z-index: 10; float: left; background: url('/newdis/images/cart/top1_act.png') repeat scroll 0pt 0pt transparent; height: 32px; width: 186px;"></div>
    <div style="position: relative; float: left; height: 32px; width: 186px; left: -13px; z-index: 9; background: url('/newdis/images/cart/top2_act.png') repeat scroll 0pt 0pt transparent;"></div>
    <div style="position: relative; float: left; height: 32px; width: 186px; left: -26px; z-index: 8; background: url('/newdis/images/cart/top3_act.png') repeat scroll 0pt 0pt transparent;"></div>
</div>

<?php if ($sf_user->isAuthenticated()): ?>
        <div style="clear: both;margin-bottom: 20px;"></div>Проверьте, пожалуйста, правильность заполненных данных.
    <?php endif; ?>
<div style="clear: both;margin-bottom: 20px;"></div>
<form action="/register" method="post" width="100%" id="register">
    <table style="width:100%;" class="tableRegister">
        <tbody>
            <tr>
                <td colspan="2">
                    <span style="color: #c3060e;">Основная информация **</span>
                    <div class="dashetCart"></div>
                </td>
            </tr>
        </tbody>
        <?php
        foreach ($form as $key => $field):
            if ($field->renderLabel() == "<label for=\"sf_guard_user_index_house\">Индекс</label>"):
                ?> 
                <tbody>
                    <tr>
                        <td colspan="2">
                            <span style="color: #c3060e;">Информация для доставки**</span>
                            <div class="dashetCart" style="width: 76%;"></div>
                        </td>
                    </tr>
                </tbody>
                <?
            endif;
            if (!$field->isHidden()):
                ?>

                <tbody>
                    <tr>
                        <th><label for="sf_guard_user_first_name" style="font-weight: normal;">
                            <?php echo $field->renderLabel() ?></label></th>
                        <td>
                            <? if ($field->renderLabel() == "<label for=\"sf_guard_user_password\">Пароль*</label>") {
                                ?><script>
                                    function mtRand(min, max) 
                                    {
                                        var range = max - min + 1;
                                        var n = Math.floor(Math.random() * range) + min;
                                        return n;
                                    }
                                                                                                                                         
                                                                                                                                         
                                    function showPass()
                                    {
                                        genPass=mkPass(mtRand(10, 14));
                                        $('#sf_guard_user_password').val(genPass);
                                        $('#sf_guard_user_password_again').val(genPass);
                                    }
                                                                                                                                         
                                    function mkPass(len) 
                                    {
                                        var len=len?len:14;
                                        var pass = '';
                                        var rnd = 0;
                                        var c = '';
                                        for (i = 0; i < len; i++) {
                                            rnd = mtRand(0, 2); // Латиница или цифры
                                            if (rnd == 0) {
                                                c = String.fromCharCode(mtRand(48, 57));
                                            }
                                            if (rnd == 1) {
                                                c = String.fromCharCode(mtRand(65, 90));
                                            }
                                            if (rnd == 2) {
                                                c = String.fromCharCode(mtRand(97, 122));
                                            }
                                            pass += c;
                                        }
                                        return pass;
                                    }
                                </script>

                                <? /* <a href="javascript: showPass();">Сгенерировать надежный пароль</a> */ ?>
                            <? }
                            ?>
                            <?php echo $field->renderError() ?>
                            <?php echo $field ?>

                            <?
                            if ($field->renderLabel() == "<label for=\"sf_guard_user_birthday\">Дата рождения</label>") {
                                echo "&nbsp;&nbsp;Получайте в этот день от нас приятные подарки!";
                            }
                            ?>

                        </td>
                    </tr>
                </tbody>

                <?php
            else:
                echo $field;
                if ($field->renderLabel() == "<label for=\"sf_guard_user_birthday\">Дата рождения</label>") {
                    echo "&nbsp;&nbsp;Получайте в этот день от нас приятные подарки!";
                }
            endif;
        endforeach;
        ?>
    </table><div style="clear: both;margin-bottom: 20px;"></div><br />
    <span style="color: #c3060e;">*</span> - поля, обязательны для заполнения.<br /> 
    <span style="color: #c3060e;">**</span> - Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить ваш заказ.<br /> 
    <div style="margin-top: 20px;">
        <a class="red-btn colorWhite" href="#" onClick="$('#register').submit()"><span>Отправить заказ</span></a>
    </div><div style="clear: both;margin-bottom: 20px;"></div>
</form>
</div>