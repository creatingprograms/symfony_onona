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
        $("#datepicker").datepicker();
        $("#datepicker2").datepicker();
    });</script>

<div id="sf_admin_container">
    <h1>Cамые продоваемые товары</h1>

    <div id="sf_admin_header">
        <form action="" method="POST">

            Период:&nbsp;<input  id="datepicker" name="fromDate" value="<?= @$_POST['fromDate'] ?>"> - <input  id="datepicker2" name="to" value="<?= @$_POST['to'] ?>"> <br />
            Цена:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input  id="price" name="priceFrom" value="<?= @$_POST['priceFrom'] > 0 ? $_POST['priceFrom'] : 0 ?>"> - <input  id="priceto" name="priceTo" value="<?= @$_POST['priceTo'] > 0 ? $_POST['priceTo'] : 999999 ?>"> <br />
            <label><input type="checkbox" value="1" name="in_file">&nbsp;В файл</label><br>
            <input type="submit" value="Отправить запрос">
        </form><br />
    </div>

    <div id="sf_admin_content">
        <div class="sf_admin_list">
            <table>
                <thead>
                    <tr>
                        <th>#</th>

                        <th class="sf_admin_text sf_admin_list_th_id">Id </th>
                        <th class="sf_admin_text sf_admin_list_th_name">Название</th>
                        <th class="sf_admin_text sf_admin_list_th_code">Артикул</th>
                        <th class="sf_admin_text sf_admin_list_th_slug">Ссылка</th>
                        <th class="sf_admin_text sf_admin_list_th_price">Цена</th>
                        <th class="sf_admin_text sf_admin_list_th_count">Кол-во на складе</th>
                        <th class="sf_admin_text sf_admin_list_th_count">Продано</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($arrayProductBestSales as $product) {
                        $i++;
                        ?>
                        <tr class="sf_admin_row odd" id="20798" title="4">
                            <td><?=$i?></td>

                            <td class="sf_admin_text sf_admin_list_td_id">
                                <?=$product['info']['product_id']?></td>
                            <td class="sf_admin_text sf_admin_list_td_name">
                                <a href="/product/<?=$product['infoBD']['slug']?>"><?=$product['infoBD']['name']?></a>
                            </td>
                            <td class="sf_admin_text sf_admin_list_td_code">
                                <?=$product['infoBD']['code']?></td>
                            <td class="sf_admin_text sf_admin_list_td_slug">
                                <?=$product['infoBD']['slug']?></td>
                            <td class="sf_admin_text sf_admin_list_td_price">
                                <?=$product['infoBD']['price']?> руб.
                            </td>
                            <td class="sf_admin_text sf_admin_list_td_code">
                                <?=$product['infoBD']['count']?></td>
                            <td class="sf_admin_text sf_admin_list_td_code">
                                <?=$product['salesCount']?></td>
                        </tr><?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="sf_admin_footer">
    </div>
</div>
