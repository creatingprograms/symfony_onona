
<?php if ($senduserexist): ?>
    <div id="sendUser">
        <center>Вы уже подписаны на данный товар.</senter><br/>
            <a href="<?php echo url_for('@product_show?slug=' . $product['slug']) ?>" class="redButton">Вернуться к просмотру товара.</a></div>
<?php elseif (!$errorCapSu and $sf_request->isMethod(sfRequest::POST)):
    ?>
    <div id="sendUser">
        <center>Спасибо за запрос. Вам будет сообщено о поступление товара.</center><br/>
        <a href="<?php echo url_for('@product_show?slug=' . $product['slug']) ?>" class="redButton">Вернуться к просмотру товара.</a>
        <img src="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" width="0" height="0" class="captchak" alt="captcha"/>
    </div>
<?php else: ?>
    <div id="sendUser">
        <form id="sendUserForm" class="form" action="" method="post">

            <?php
            JSInPages("$(document).ready(function () {

                    $('form#sendUserForm').validate({
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
                            personalFields: {
                                required: '<div class=\"alert alert-error\">Необходимо принять пользовательское соглашение!</div>',
                                // pattern: '<div class=\"alert alert-error\">Можно использовать только буквы кирилицы</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            }
                        }
                    });
                    });");
            ?>
            <div class="row">
                <div class="label-holder">
                    <label>Представьтесь*</label>
                </div>
                <div id="description-name" class="requeredDescription"></div>
                <div class="input-holder" style="border:0;padding: 0;">
                    <div id="rate_div_comment"></div>
                    <input type="text" name="name" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") ?>"
                           data-describedby="description-name" data-required="true" data-description="allFields" class="required">

                </div>
            </div>
            <div class="row">
                <div class="label-holder">
                    <label>Ваш e-mail*</label>
                </div>
                <div id="description-mail" class="requeredDescription"></div>
                <div class="input-holder" style="border:0;padding: 0;">
                    <div id="rate_div_comment"></div>
                    <input type="email" name="mail" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") ?>"
                           data-describedby="description-mail" data-required="true" data-description="allFields" class="required">

                </div>
            </div>

            <div class="row">
                <div class="label-holder" style="float:left;">
                    <label>Укажите код:*</label>
                </div>
                <div class="capcha-holder">
                    <img src="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" width="139" height="48" class="captchak" alt="captcha"/>
                </div>
            </div>
            <div class="row">
                <div id="description-sucaptcha" class="requeredDescription">
                    <?php if ($errorCapSu) { ?>
                        <div class="alert alert-error">Ошибка. Попробуйте ещё раз.</div>
                    <?php } ?></div>
                <div class="input-holder">
                    <input type="text" name="sucaptcha"
                           data-describedby="description-sucaptcha" data-required="true" data-description="allFields" class="required">
                </div>

            </div>
            <div class="row">
                <div class="label-holder">
                  <label for='accept-outstock' >
                    <input style="margin: 0; width: auto;"
                    id='accept-outstock'
                    type='checkbox'
                    data-describedby="description-accept"
                    data-required="true"
                    data-description="personalFields"
                    class="required"
                    >
                    Я принимаю
                    <a href='/personal_accept' target='_blank'>Пользовательское соглашение</a></label>
                </div>
                <div id="description-accept" class="requeredDescription"></div>
                <div class="input-holder" style="border:0;padding: 0;">

                </div>
            </div>
            <span style="font-size: 10px;">* - обязательны для заполнения.</span>
            <div class="redButton" onclick="$('#sendUserForm').find('[type=\'submit\']').trigger('click');">Отправить запрос</div>

            <input type="submit" class="sendCommentButton" style="display: none;" />

        </form>
    </div>
<?php endif; ?>
