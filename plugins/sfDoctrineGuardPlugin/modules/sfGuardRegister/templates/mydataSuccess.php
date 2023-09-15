<?php use_helper('I18N') ?>
<?/*<header>
                                                        <ul>
                                                                <li>
                                                                        <a href="/">Главная</a>
                                                                    </li>
                                                                <li>
                                                                        <a href="/customer/">личный кабинет</a>
                                                                    </li>
                                                                <li>
                                                                        мои данные
                                                                    </li>
                                                            </ul>
                                                                                </header>*/?>
<div class="wrapwer">
<div style="display: block;">
    <a href="/cart"><div style="position: relative; z-index: 10; float: left; background:url('/images/topInfoSel.png');width: 177px; height:19px;padding:7px;font-size: 13px;color: #FFF;text-shadow: black 1px 1px 2px, red 0 0 1em;">1. Оформление заказа</div></a>
    <a href="/cart/processorder"><div style="position: relative; z-index: 9; float: left; background:url('/images/topInfoSel.png');width: 157px; height:19px;left: -20px;padding:7px 7px 7px 27px;font-size: 13px;color:#FFF;text-shadow: black 1px 1px 2px, red 0 0 1em;">2. Доставка/оплата</div></a>
    <div style="position: relative; z-index: 8; float: left; background:url('/images/topInfoSel.png');width: 157px; height:19px;left: -40px;padding:7px 7px 7px 27px;font-size: 13px;color:#FFF;text-shadow: black 1px 1px 2px, red 0 0 1em;">3. Контактные данные</div>
</div><div style="clear: both;"></div><br />
<form action="/register" method="post" width="100%">
    <table style="width:100%;">
        <tbody>
            <tr>
                <th colspan="2">
        <u>Основная информация</u>
        </th>
        </tr>
        </tbody>
        <?php
        foreach ($form as $key => $field):
            if ($field->renderLabel() == "<label for=\"sf_guard_user_city\">Город*</label>"):
                ?> 
                <tbody>
                    <tr>
                        <th colspan="2">
                <br /><br /><u>Адрес и контактная информация</u>
                </th>
                </tr>
                </tbody>
                <?
            endif;
            if (!$field->isHidden()):
                ?>

                <tbody>
                    <?php echo $field->renderRow() ?>
                </tbody>

                <?php
            else:
                echo $field;
            endif;
        endforeach;
        ?>
        <tfoot>
            <tr>
                <td colspan="2"><br />
                    <center><input type="submit" name="register" value="Сохранить" /></center>
                </td>
            </tr>
        </tfoot>
    </table>
</form>
</div>