<?php use_helper('I18N') ?>
<script type="text/javascript" src="/js/jquery.maskedinput-1.2.2.js"></script>
<script type="text/javascript" src="/js/validation.js?v=2"></script>
<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li>
        <a href="/lk">личный кабинет</a>
    </li>
    <li>
        мои данные
    </li>
</ul>

<form action="/customer/mydata" method="post" width="100%" id="register" class="form-register">
    <table class="tableRegister">
        <tbody>
            <tr>
                <td colspan="2">
                    <span style="color: #c3060e;"><b>Основная информация **</b></span>
                    <div class="dashetCart"></div>
                </td>
            </tr>
        </tbody>
        <?php
        foreach ($form as $key => $field):
            if ($field->renderLabel() == "<label for=\"sf_guard_user_city\">*Город</label>"):
                ?>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <span style="color: #c3060e;"><b>Информация для доставки**</b></span>
                            <div class="dashetCart" style="width: 76%;"></div>
                        </td>
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
            if ($field->renderLabel() == "<label for=\"sf_guard_user_phone\">*Телефон</label>" and $sf_user->getGuardUser()->getActivephone()):
                ?>
                <tbody>
                    <tr>
                        <th>
                        </th>
                        <td><img src="/images/tick.png" /> Телефон подтвержден
                        </td>
                    </tr>
                </tbody>
                <?
            endif;
        endforeach;
        ?>
    </table><div style="clear: both;margin-bottom: 20px;"></div><br />
    <span style="color: #c3060e;">*</span> - поля, обязательны для заполнения.<br />
    <span style="color: #c3060e;">**</span> - Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить ваш заказ.<br /> <br />
    <div>
        <a class="red-btn" href="#" onClick="$('#register').submit()"><span>Сохранить</span></a>
    </div>
</form>
