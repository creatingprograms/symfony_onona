<?php
slot('rightBlock', true);
if ($sf_request->isMethod(sfRequest::POST) and !$errorCap) {
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
    <form id="support" width="100%" method="post" action="/support" onsubmit="if(!$('#accept-support').is(':checked')) { alert('Необходимо принять пользовательское соглашение!'); return false; } else return true;">
        <table class="tableRegister" calss="noBorder">
            <tbody>
                <tr>
                    <th><label for="name">Ваше имя:*</label></th>
                    <td><input type="text" id="name" value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>" name="name"></td>
                </tr>
            </tbody>


            <tbody>
                <tr>
                    <th><label for="email">Ваш e-mail:*</label></th>
                    <td><input type="text" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" name="email"></td>
                </tr>
            </tbody>


            <tbody>
                <tr>
                    <th><label for="sub">Тема сообщения:*</label></th>
                    <td><input type="text" id="sub" value="<?= isset($_POST['sub']) ? $_POST['sub'] : ''?>" name="sub"></td>
                </tr>
            </tbody>


            <tbody>
                <tr>
                    <th><label for="msg">Сообщение:*</label></th>
                    <td><textarea id="msg" value="" name="msg"><?= isset($_POST['msg']) ? $_POST['msg'] : '' ?></textarea></td>
                </tr>
            </tbody>


            <tbody>
                <tr>
                    <th><label for="code">Укажите код:*</label></th>
                    <td><img border="0" src="/captcha/supportcaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" style="float: left; margin-right: 10px;"><input type="text" id="code" value="" name="code"><br />
                        <?php if (isset($errorCap) && $errorCap) echo "Ошибка. Попробуйте ещё раз." ?></td>
                </tr>
            </tbody>

        </table>
        <input style="margin-left: 0;" id='accept-support' data-rule='acception' type='checkbox'>
        <label for='accept-support' >Я принимаю условия
          <a href='/personal_accept' target='_blank'>Пользовательского соглашения</a></label><br/>
        * - поля, отмеченные * обязательны для заполнения.<br />

        <!-- <center> -->
          <a onclick="$('#support').submit(); return false;" href="#" class="red-btn colorWhite support-submit"><span style="width: 120px;">Отправить</span></a>
        <!-- </center> -->
    </form>
    <?
}
?>
