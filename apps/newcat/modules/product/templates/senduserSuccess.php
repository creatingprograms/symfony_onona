<? if ($senduserexist):
    /* ?><div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>
      <img src="/images/topToSend.png"><br /> */
    ?>
    Вы уже подписаны на данный товар.
    <?
else:
    /* ?>
      <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>
      <img src="/images/topToSend.png"> */
    ?>

    <?php if (!$errorCapSu and $sf_params->get('senduser')): ?>
        <center>Спасибо за запрос. Вам будет сообщено о поступление товара.</center>
    <?php else: ?>
        <script>
            $(document).ready(function () {
                var options = {
                    target: '.highslide-maincontent #senduser_<?= $product->getId() ?>', // target element(s) to be updated with server response 
                    //beforeSubmit:  showRequest,  // pre-submit callback 
                    success: showResponse_<?= $product->getId() ?>  // post-submit callback 

                            // other available options: 
                            //url:       url         // override for form's 'action' attribute 
                            //type:      type        // 'get' or 'post', override for form's 'method' attribute 
                            //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
                            //clearForm: true        // clear all form fields after successful submit 
                            //resetForm: true        // reset the form after successful submit 

                            // $.ajax options can be used here too, for example: 
                            //timeout:   3000 
                };

                // bind form using 'ajaxForm' 
                $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
            });


            function enableAjaxFormSendUser_<?= $product->getId() ?>() {

                var options = {
                    target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response 
                    //beforeSubmit:  showRequest,  // pre-submit callback 
                    success: showResponse_<?= $product->getId() ?>  // post-submit callback 

                            // other available options: 
                            //url:       url         // override for form's 'action' attribute 
                            //type:      type        // 'get' or 'post', override for form's 'method' attribute 
                            //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
                            //clearForm: true        // clear all form fields after successful submit 
                            //resetForm: true        // reset the form after successful submit 

                            // $.ajax options can be used here too, for example: 
                            //timeout:   3000 
                };

                // bind form using 'ajaxForm' 
                $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
            }
            function showResponse_<?= $product->getId() ?>(responseText, statusText, xhr, $form) {
                var options = {
                    target: '.highslide-maincontent #senduser_<?= $product->getId() ?>', // target element(s) to be updated with server response 
                    //beforeSubmit:  showRequest,  // pre-submit callback 
                    success: showResponse_<?= $product->getId() ?>  // post-submit callback 

                            // other available options: 
                            //url:       url         // override for form's 'action' attribute 
                            //type:      type        // 'get' or 'post', override for form's 'method' attribute 
                            //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
                            //clearForm: true        // clear all form fields after successful submit 
                            //resetForm: true        // reset the form after successful submit 

                            // $.ajax options can be used here too, for example: 
                            //timeout:   3000 
                };

                // bind form using 'ajaxForm' 
                $('.highslide-container #senduser_<?= $product->getId() ?>').ajaxForm(options);

                $('.senduser_<?= $product->getId() ?>').each(function () {
                    $(this).ajaxForm(options);
                });
            }
        </script>
        <form id="senduser_<?= $product->getId() ?>" class="senduser_<?= $product->getId() ?>" action="/product/<?= $product->getSlug() ?>/senduser" method="post">
            <div style="clear: both; color:#4e4e4e; text-align: left;">    
                <table style=" width: 100%" class="noBorder">
                    <tbody><tr>
                            <td style="width: 120px;padding: 5px 0;text-align: left;">
                                Представьтесь*:
                            </td>
                            <td style="padding: 5px 0;text-align: left;">
                                <input type="text" name="name" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") ?>" style="width: 254px;">
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 120px; padding: 5px 0;text-align: left;">
                                Ваш e-mail*:
                            </td>
                            <td style="padding: 5px 0;text-align: left;">
                                <input type="text" name="mail" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") ?>" style="width: 254px;">
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 120px; padding: 5px 0;text-align: left;">
                                Ваш телефон*:
                            </td>
                            <td style="padding: 5px 0;text-align: left;">
                                <input type="text" name="phone" value="<?= sfContext::getInstance()->getRequest()->getParameter("phone") ?>" style="width: 254px;">
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 120px; padding: 5px 0;text-align: left;">
                                Введите код*:
                            </td>
                            <td style="padding: 5px 0;text-align: left;">

                                <img  class="captchasu" src="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />
                                <input type="text" name="sucaptcha" style="position: relative; top: -45px; width: 130px;">
        <?php if ($errorCapSu) { ?><br />
                                    <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                    <br />
        <?php } ?>
                            </td>
                        </tr>
                    </tbody></table>
                <span style="font-size: 10px;">* - обязательны для заполнения.</span>
                <a  style="margin-top: 20px; margin-left: 155px;" class = "red-btn colorWhite" href = "#" onclick = "$('#senduser_<?= $product->getId() ?>').submit();
                                setTimeout('enableAjaxFormSendUser_<?= $product->getId() ?>()', 500);
                                return false;"><span style = "width: 195px;">Отправить запрос</span></a>

            </div>
        </form>
    <?php endif; ?>
<?php endif; ?>
