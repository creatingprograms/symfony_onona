<?php if ($errorCap or $pol): ?>
    <div align="center" id="commentDiv">
        <form id="commentForm" method="post" action="/product/<?= $product->getSlug() ?>/addcomment" name="comment">
            <table width="450" cellspacing="0" cellpadding="3" border="0">
                <tbody><tr>
                        <td></td>
                        <td>Ваш комментарий будет отображен на сайте после модерации!</td>
                    </tr>
                    <tr>
                        <td width="100">Ваше имя*</td>
                        <td><?=$cName==""?'<span style="color: red">Обязательно для заполнения</span>':''?>
                            <input type="text" name="cName" class="input required" style="width:300px;" value="<?=$cName?>" required></td>
                    </tr>
                    <tr>
                        <td width="100">E-mail</td>
                        <td>
                            <input type="text" name="cEmail" class="input" style="width:300px;" value="<?=$cEmail?>"></td>
                    </tr>
                    <tr>
                        <td width="100">Сообщение*</td>
                        <td><?=$cComment==""?'<span style="color: red">Обязательно для заполнения</span>':''?>
                            <textarea style="width:300px; overflow:auto; border:1px solid #999;" class="required" rows="5" name="cComment" value="" required><?=$cComment?></textarea></td>
                    </tr><tr>
                        <td width="100">
                            Введите символы*
                        </td>
                        <td style="padding:0px 0px 0px 3px">


                            <img border="0" src="/captcha/kcaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>">
                            <br /><br />
                            <input type="text" name="cText" style="width:300px;">
                            <br /><? If($errorCap){?>
                            <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                            <br /><?}?>
                        </td>
                    </tr>

                    <tr>
                        <td>    </td>
                        <td style="padding:15px 0px 10px 67px;"><a onclick="$('#commentForm').submit(); return false;" href="#"><img width="106" border="0" height="27" src="/images/tmpl0/send_button.png"></a></td>
                    </tr>

                </tbody></table>
        </form>
    </div>
<?php else: ?>
    Комментарий успешно добавлен. После проверки модератором, он станет доступен для просмотра. 
    <a href="/product/<?= $product->getSlug() ?>">Вернуться к просмотру товара.</a>
<?php endif; ?>