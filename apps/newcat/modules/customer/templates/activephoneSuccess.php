<?php use_helper('I18N') ?><?
if ($sf_request->getParameter('step') == "") {
    ?>
    <script type="text/javascript" src="/js/jquery.maskedinput-1.2.2.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#sf_guard_user_phone").mask("+7(999)999-9999");
        });
    </script>
    Проверьте номер своего телефона и при необходимости исправьте его. <br /><br />
    <form action="/customer/activephone" method="post" width="100%" id="activephone">
        <input type="hidden" value="1" name="step">
        <div style="float: left;margin-right: 10px;">Номер телефона: </div>
        <input type="text" name="phone" value="<?= $sf_user->getGuardUser()->getPhone() ?>" id="sf_guard_user_phone"><br /><br />
        <input type="submit" value="Получить код" />
    </form>
    <?
} elseif ($sf_request->getParameter('step') == "1") {
    ?>
    Введите код подтверждения телефона.<br /><br />
    <form action="/customer/activephone" method="post" width="100%" id="activephone">
        <input type="hidden" value="2" name="step">
        <div style="float: left;margin-right: 10px;">Код: </div>
        <input type="text" name="code" id="sf_guard_user_phone"><br /><br />
        <input type="submit" value="Подтвердить" />
    </form><?
} elseif ($sf_request->getParameter('step') == "2") {
    if ($errorActive == "badAction") {
        echo "Ошибка обработки запроса. Попробуйте ещё раз.";
        ?><br /><br />
        <script type="text/javascript" src="/js/jquery.maskedinput-1.2.2.js"></script>
        <script type="text/javascript">
        $(document).ready(function () {
            $("#sf_guard_user_phone").mask("+7(999)999-9999");
        });
        </script>
        Проверьте номер своего телефона и при необходимости исправьте его. <br /><br />
        <form action="/customer/activephone" method="post" width="100%" id="activephone">
            <input type="hidden" value="1" name="step">
            <div style="float: left;margin-right: 10px;">Номер телефона: </div>
            <input type="text" name="phone" value="<?= $sf_user->getGuardUser()->getPhone() ?>" id="sf_guard_user_phone"><br /><br />
            <input type="submit" value="Получить код" />
        </form>
        <?
    } elseif ($errorActive == "badCode") {
        echo "Не верный код. Попробуйте ещё раз.";
        ?><br /><br />
        Введите код подтверждения телефона.<br /><br />
        <form action="/customer/activephone" method="post" width="100%" id="activephone">
            <input type="hidden" value="2" name="step">
            <div style="float: left;margin-right: 10px;">Код: </div>
            <input type="text" name="code" id="sf_guard_user_phone"><br /><br />
            <input type="submit" value="Подтвердить" />
        </form><?
    } else {
        ?>
        Телефон подтверждён.<?
    }
}
?>