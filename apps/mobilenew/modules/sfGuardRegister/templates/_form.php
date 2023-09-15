<?php use_helper('I18N') ?>
<?php
JSInPages("$(document).ready(function () {
                        
                    $('form#register').validate({
                        onKeyup: true,
                        sendForm: true,
                        eachValidField: function () {

                            $(this).closest('div').removeClass('error').addClass('success');
                        },
                        eachInvalidField: function () {

                            $(this).closest('div').removeClass('success').addClass('error');
                        },
                        description: {
                            allFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Pattern</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            },
                            mailFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Неправильный формат e-mail</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            },
                            phoneFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Неправильный формат телефона. Пример: +7(777)777-7777</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            },
                            nameFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Можно использовать только буквы кирилицы</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            },
                            passwordFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Минимум 6 символов c цифрами и буквами латиницы</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            }
                        }
                    });
                    });");
?>
<table class="tableRegister">
    <tbody>
        <tr>
            <td colspan="2" style="text-align: left">
                <span style="color: #c3060e;">Основная информация **</span>
                <div class="dashetCart" style="width: 65%;"></div>

            </td>
        </tr>
    </tbody>
    <?php
    foreach ($form as $key => $field):
        if ($field->getName() == "index_house"):
            ?> </table>
        <table class="tableRegister">
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: left">
                        <span style="color: #c3060e;">Информация для доставки**</span>
                        <div class="dashetCart" style="width: 65%;"></div>
                        </u>
                    </td>
                </tr>
            </tbody>
            <?
        endif;
        if ($field->getName() == "email_address"):
            ?>

            <tbody style="font-weight: normal;">
                <tr>
                    <td>
                        <?php
                        echo $field->renderLabel()
                        ?><br />

                        <span style="color:#c3060e; font-weight: bold;"><?php echo $field->renderError() ?></span>
                        <div id="description-mail" class="requeredDescription"></div>
                        <input type="text" name="sf_guard_user[email_address]" value="<?=$field->getValue()?>" id="sf_guard_user_email_address" 
                               data-describedby="description-mail" data-required="true" data-pattern="^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$" data-description="mailFields" class="required">
                    </td>
                </tr>
            </tbody>

        <?php elseif ($field->getName() == "phone"):
            ?>

            <tbody style="font-weight: normal;">
                <tr>
                    <td>
                        <?php
                        echo $field->renderLabel()
                        ?><br />

                        <span style="color:#c3060e; font-weight: bold;"><?php echo $field->renderError() ?></span>
                        <div id="description-phone" class="requeredDescription"></div>
                        <input type="text" name="sf_guard_user[phone]" value="<?=$field->getValue()?>" id="sf_guard_user_phone" placeholder="+7(777)777-7777"
                               data-describedby="description-phone" data-required="true" data-pattern="^\+\d{1}\(\d{3}\)\d{3}-\d{4}$" data-description="phoneFields" class="required">
                    </td>
                </tr>
            </tbody>

        <?php elseif ($field->getName() == "first_name"):
            ?>

            <tbody style="font-weight: normal;">
                <tr>
                    <td>
                        <?php
                        echo $field->renderLabel()
                        ?><br />

                        <span style="color:#c3060e; font-weight: bold;"><?php echo $field->renderError() ?></span>
                        <div id="description-name" class="requeredDescription"></div>
                        <input type="text" name="sf_guard_user[first_name]" id="sf_guard_user_first_name" value="<?=$field->getValue()?>"
                               data-describedby="description-name" data-required="true" data-pattern="^[а-яА-ЯёЁ]+$" data-description="nameFields" class="required">
                    </td>
                </tr>
            </tbody>

        <?php elseif ($field->getName() == "password"):
            ?>

            <tbody style="font-weight: normal;">
                <tr>
                    <td>
                        <?php
                        echo $field->renderLabel()
                        ?><br />

                        <span style="color:#c3060e; font-weight: bold;"><?php echo $field->renderError() ?></span>
                        <div id="description-password" class="requeredDescription"></div>
                        <input type="text" name="sf_guard_user[password]" id="sf_guard_user_password" value="<?=$field->getValue()?>"
                               data-describedby="description-password" data-required="true" data-pattern="(?=^.{6,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" data-description="passwordFields" class="required">
                        
                    </td>
                </tr>
            </tbody>

        <?php elseif (!$field->isHidden()):
            ?>

            <tbody style="font-weight: normal;" <?php if ($field->getName() == "birthday" or $field->getName() == "sex"): ?> class="silverBlock"<?php endif; ?>>
                <tr>
                    <td>
                        <?php
                        if ($field->getName() == "birthday") {
                            echo "Чтобы получать от нас приятные подарки и <br />скидки, пожалуйста, укажите свои данные.<br /><br />";
                        }
                        echo $field->renderLabel()
                        ?><br />

                        <span style="color:#c3060e; font-weight: bold;"><?php echo $field->renderError() ?></span>
                        <?php echo $field ?>

                    </td>
                </tr>
            </tbody>

            <?php
        else:
            echo $field;

        endif;
    endforeach;
    ?>
</table>
<div style="clear: both"></div>