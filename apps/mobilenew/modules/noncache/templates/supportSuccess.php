<div id="pageShow"><?php
    slot('rightBlock', true);
    if ($sf_request->isMethod(sfRequest::POST) and ! $errorCap) {
        $page = PageTable::getInstance()->findOneBySlug("tehpodderzhka-obraschenie-otpravleno");
        slot('metaTitle', $page->getTitle() == '' ? $page->getName() : $page->getTitle());
        slot('metaKeywords', $page->getKeywords() == '' ? $page->getName() : $page->getKeywords());
        slot('metaDescription', $page->getDescription() == '' ? $page->getName() : $page->getDescription());
        ?>
        <h1 class="title centr"><?= $page->getName() ?></h1>
        <?= $page->getContent() ?>
        <?
    } else {
        $page = PageTable::getInstance()->findOneBySlug("tehpodderzhka-pomosch-v-reshenii-problem");
        slot('metaTitle', $page->getTitle() == '' ? $page->getName() : $page->getTitle());
        slot('metaKeywords', $page->getKeywords() == '' ? $page->getName() : $page->getKeywords());
        slot('metaDescription', $page->getDescription() == '' ? $page->getName() : $page->getDescription());
        ?><h1 class="title centr"><?= $page->getName() ?></h1>
        <?= $page->getContent() ?>

            <?php
            JSInPages("$(document).ready(function () {

                    $('form#support').validate({
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
                            nameFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Можно использовать только буквы кирилицы</div>',
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
        <form id="support" width="100%" method="post" action="/support" class="form" style="padding: 0;">

            <div class="row">
                <div class="label-holder">
                    <label for="name">Ваше имя:*</label>
                </div>
                <div id="description-name" class="requeredDescription"></div>
                <div class="input-holder" style="border:0;padding: 0;">
                    <input type="text" id="name" value="<?= $_POST['name'] ?>" name="name"
                           data-describedby="description-name" data-required="true" data-pattern="^[а-яА-ЯёЁ]+$" data-description="nameFields" class="required">
                </div>
            </div>
            <div class="row">
                <div class="label-holder">
                    <label for="email">Ваш e-mail:*</label>
                </div>
                <div id="description-mail" class="requeredDescription"></div>
                <div class="input-holder" style="border:0;padding: 0;">
                    <input type="text" id="email" value="<?= $_POST['email'] ?>" name="email"
                           data-describedby="description-mail" data-required="true" data-pattern="^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$" data-description="mailFields" class="required">
                </div>
            </div>
            <div class="row">
                <div class="label-holder">
                    <label for="sub">Тема сообщения:*</label>
                </div>
                <div id="description-subject" class="requeredDescription"></div>
                <div class="input-holder" style="border:0;padding: 0;">
                    <input type="text" id="sub" value="<?= $_POST['sub'] ?>" name="sub"
                           data-describedby="description-subject" data-required="true" data-description="allFields" class="required">
                </div>
            </div>
            <div class="row">
                <div class="label-holder">
                    <label for="msg">Сообщение:*</label>
                </div>
                <div id="description-text" class="requeredDescription"></div>
                <div class="input-holder" style="border:0;padding: 0;">
                    <textarea id="msg" value="" name="msg"
                           data-describedby="description-text" data-required="true" data-description="allFields" class="required"><?= $_POST['msg'] ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="label-holder">
                    <label for="code">Укажите код:*</label>
                </div>
                <div id="description-code" class="requeredDescription"></div>
                <div class="input-holder" style="border:0;padding: 0;">
                    <img border="0" src="/captcha/supportcaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" style=" margin-right: 10px;">
                    <input type="text" id="code" value="" name="code"
                           data-describedby="description-code" data-required="true" data-description="allFields" class="required"><br />
                    <?php if ($errorCap) echo "Ошибка. Попробуйте ещё раз." ?>
                </div>
            </div>
            <div class="row">
                <div class="label-holder">
                  <label for='accept-support' >
                    <input style="margin: 0; width: auto;"
                    id='accept-support'
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
            <div>
                * - поля, отмеченные * обязательны для заполнения.<br />
            </div>
            <div class="redButton" onclick="$('#support').submit(); return false;">Отправить</div>

        </form>
        <?
    }
    ?>
</div>
