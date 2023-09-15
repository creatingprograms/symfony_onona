<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<?php use_stylesheet('/newdis/css/jquery-ui-1.10.3.custom.css', 'first') ?>
<?php use_javascript('/newdis/js/jquery-ui-1.10.3.custom.js'); ?>
<?php use_javascript('/newdis/js/i18n/jquery-ui-i18n.js'); ?>
<script>$(function () {
        $.datepicker.setDefaults(
                $.extend($.datepicker.regional["ru"])
                );
        $("#mail_db_login_from").datepicker();
        $("#mail_db_login_to").datepicker();
        $("#mail_db_order_from").datepicker();
        $("#mail_db_order_to").datepicker();
        $("#mail_db_newuser_from").datepicker();
        $("#mail_db_newuser_to").datepicker();
    });</script>
<div id="sf_admin_container">
    <?php if(isset($error)) :?>
      <p style="color: red; font-weight: bold;"><?=$error?></p>
    <?php endif ?>
    <p><strong>Обязательно должно быть заполнено хотя бы одна строка в фильтре</strong></p>
    <p><strong>При выборе периода должны быть заполнены и начальная и конечная дата</strong></p>
    <form action="/backend.php/getmaildb2" method="post">
        <table cellspacing="0" width="100%">

            <tbody>
                <tr class="sf_admin_row sf_admin_list_th_name sf_admin_setting_group">
                    <th colspan="2">
                        По городу              </th>
                </tr>
                <tr class="sf_admin_row odd">
                    <td class="sf_admin_text sf_admin_list_td_name">
                        Города, через запятую <br/>
                        <span style="font-size: 11px;">Пример: "москва,Москва,москв,moscow"</span>
                    </td>
                    <td class="sf_admin_text sf_admin_list_td_value">
                        <input type="text" name="mail_db_citys" value="" id="mail_db_citys">
                    </td>
                </tr>



                <tr class="sf_admin_row sf_admin_list_th_name sf_admin_setting_group">
                    <th colspan="2">
                        Пользователи, которые заходили на сайт, за выбранный период</th>
                </tr>
                <tr class="sf_admin_row odd">
                    <td class="sf_admin_text sf_admin_list_td_name">
                        Период
                    </td>
                    <td class="sf_admin_text sf_admin_list_td_value">
                        <input id="mail_db_login_from" name="mail_db_login[from]" value=""> - <input id="mail_db_login_to" name="mail_db_login[to]" value="">

                        <br>
                    </td>

                </tr>








                <tr class="sf_admin_row sf_admin_list_th_name sf_admin_setting_group">
                    <th colspan="2">
                        Пользователи, которые совершали заказы, за выбранный период</th>
                </tr>
                <tr class="sf_admin_row odd">
                    <td class="sf_admin_text sf_admin_list_td_name">
                        Период
                    </td>
                    <td class="sf_admin_text sf_admin_list_td_value">
                        <input id="mail_db_order_from" name="mail_db_order[from]" value=""> - <input id="mail_db_order_to" name="mail_db_order[to]" value="">
                        <br>
                    </td>

                </tr>







                <tr class="sf_admin_row sf_admin_list_th_name sf_admin_setting_group">
                    <th colspan="2">
                        Пользователи, кто заказывал определенный товар</th>
                </tr>
                <tr class="sf_admin_row odd">
                    <td class="sf_admin_text sf_admin_list_td_name">
                        ID товара, через запятую <br/>
                        <span style="font-size: 11px;">Пример: "14308,14309"</span>
                    </td>
                    <td class="sf_admin_text sf_admin_list_td_value">
                        <input type="text" name="mail_db_prod_id" value="" id="mail_db_prod_id">
                    </td>
                </tr>







<!--
                <tr class="sf_admin_row sf_admin_list_th_name sf_admin_setting_group">
                    <th colspan="2">
                        Все пользователи</th>
                </tr>
                <tr class="sf_admin_row odd">
                    <td class="sf_admin_text sf_admin_list_td_name">
                        Все пользователи <br/>
                    </td>
                    <td class="sf_admin_text sf_admin_list_td_value">
                        <input type="checkbox" name="mail_db_all" value="1" id="mail_db_all">
                    </td>
                </tr>
 -->






                <tr class="sf_admin_row sf_admin_list_th_name sf_admin_setting_group">
                    <th colspan="2">
                        Новые пользователи</th>
                </tr>
                <tr class="sf_admin_row odd">
                    <td class="sf_admin_text sf_admin_list_td_name">
                        Период
                    </td>
                    <td class="sf_admin_text sf_admin_list_td_value">
                        <input id="mail_db_newuser_from" name="mail_db_newuser[from]" value=""> - <input id="mail_db_newuser_to" name="mail_db_newuser[to]" value="">
                        <br>
                    </td>

                </tr>



            </tbody>
        </table>
        <input type="submit" value="Получить список" />
    </form>
</div>
