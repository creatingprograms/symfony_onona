<?php use_helper('I18N') ?>
<div id="addComment">
    <?php if ($form): ?>
        <div class="top">
            Ваше мнение важно для нас. Таким образом, Вы помогаете другим клиентам сделать правильный выбор среди товаров.
        </div>
        <div class="form">

            <form action="" method="post" id="addCommentForm">

                <?php
                JSInPages("$(document).ready(function () {
                        $('#rate_div_comment').starRating({
                            basicImage: '/images/star.gif',
                            ratedImage: '/images/star_hover.gif',
                            hoverImage: '/images/star_hover2.gif',
                            ratingStars: 10,
                            ratingUrl: '/product/rate',
                            paramId: 'product',
                            paramValue: 'value',
                            rating: '" . ($cRate != '' ? $cRate : '0') . "',
                            sucessData: function (data) {
                                $.fn.starRating.clickable['rate_div_comment'] = true;
                                $.fn.starRating.hoverable['rate_div_comment'] = false;
                                $('#cRate').val(data);
                            },
                            customParams: {productId: '" . $product['id'] . "', nonAdd: '1'}

                        });

                    $('form#addCommentForm').validate({
                        onKeyup: true,
                        sendForm: true,
                        eachValidField: function () {

                            $(this).closest('div').removeClass('error').addClass('success');
                        },
                        eachInvalidField: function () {

                            $(this).closest('div').removeClass('success').addClass('error');
                        },
                        description: {
                            comments: {
                                required: '<div class=\"alert alert-error\">Укажите рейтинг товара</div>',
                                pattern: '<div class=\"alert alert-error\">Pattern</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<!--<div class=\"alert alert-success\">Спасибо</div>-->'
                            },
                            allFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Pattern</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<!--<div class=\"alert alert-success\">Спасибо</div>-->'
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
                        <label>Ваша оценка:*</label>
                    </div>
                    <div id="comment-description-<?= $product['id'] ?>" class="requeredDescription"></div>
                    <div class="input-holder" style="border:0;padding: 0;">
                        <div id="rate_div_comment"></div>
                        <input type="text" name="cRate" id="cRate" value="<?= $cRate ?>" data-describedby="comment-description-<?= $product['id'] ?>" data-required="true" data-description="comments" class="required" style="width: 1px; height: 0px;border: 0;margin-bottom:0;" />

                    </div>
                </div>
                <div class="row">
                    <div class="label-holder">
                        <label>Ваше имя:*</label>
                    </div>
                    <div id="cname-description-<?= $product['id'] ?>" class="requeredDescription"></div>
                    <div class="input-holder">
                        <input type="text" name="cName" value="<?= $cName ?>" data-describedby="cname-description-<?= $product['id'] ?>" data-required="true" data-description="allFields" class="required" />

                    </div>
                </div>
                <div class="row">
                    <div class="label-holder">
                        <label>Ваш e-mail:</label>
                    </div>
                    <div class="input-holder">
                        <input type="email" name="cEmail" value="<?= $cEmail ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="label-holder">
                        <label>Сообщение:*</label>
                    </div>
                    <div id="cComment-description-<?= $product['id'] ?>" class="requeredDescription"></div>
                    <div class="textarea-holder">
                        <textarea cols="30" rows="5" name="cComment" data-describedby="cComment-description-<?= $product['id'] ?>" data-required="true" data-description="allFields" class="required"><?= $cComment ?></textarea>

                    </div>
                </div>
                <div class="row">
                    <div class="label-holder" style="float:left;">
                        <label>Укажите код:*</label>
                    </div>
                    <div class="capcha-holder">
                        <img src="/captcha/kcaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" width="139" height="48" class="captchak" alt="captcha"/>
                    </div>
                </div>
                <div class="row">
                    <div id="cText-description-<?= $product['id'] ?>" class="requeredDescription"></div>
                    <div class="input-holder">
                        <input type="text" name="cText"  data-describedby="cText-description-<?= $product['id'] ?>" data-required="true" data-description="allFields" class="required" />
                    </div>

                </div>
                <div class="row">
                    <div class="label-holder">
                      <label for='accept-comment' >
                        <input style="margin: 0; width: auto;"
                        id='accept-comment'
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
                <div class="required">* - поля, отмеченные * обязательны для заполнения.</div>

                <div class="descr" style="text-align: left;color:#414141;">Внимание! Публикация отзывов производится после предварительной модерации.</div>

                <div class="redButton" onClick="$('#addCommentForm').find('[type=\'submit\']').trigger('click');">Отправить отзыв</div>

                <input type="submit" class="sendCommentButton" style="display: none;" />
            </form>
        </div>
    <?php else: ?>
        <div class="form">
            Отзыв успешно добавлен. После проверки модератором, он станет доступен для просмотра. <br/>
            <a href="<?php echo url_for('@product_show?slug=' . $product['slug']) ?>" class="redButton">Вернуться к просмотру товара.</a>
            <img src="/captcha/kcaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" width="0" height="0" class="captchak" alt="captcha"/>

        </div>
    <?php endif; ?>
</div>
