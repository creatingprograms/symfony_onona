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

<form action="/backend.php/ordershop/stats/action" method="POST">

    Период:      <input  id="datepicker" name="fromDate" value="<?= @$_POST['fromDate'] ?>"> - <input  id="datepicker2" name="to" value="<?= @$_POST['to'] ?>">     <input type="submit" value="Отправить запрос">
</form>
<br /><br />
<table><tr><th>Префикс магазина</th><th>Всего чеков</th><th>Сумма всех чеков</th><th>Средний чек</th><th>Активировано чеков</th><th>Сумма активированных чеков</th><th>Средний активированный чек</th></tr>
    <?
    mb_internal_encoding('UTF-8');
    foreach ($checkStats as $data) {
        $All['Count'] = $All['Count'] + $data['Count'];
        $All['Summ'] = $All['Summ'] + $data['Summ'];
        $All['countActive'] = $All['countActive'] + $data['countActive'];
        $All['summActive'] = $All['summActive'] + $data['summActive'];
        echo "<tr><td>" . mb_substr($data['dopid'], 0, 3) . "</td><td>" . $data['Count'] . "</td><td>" . $data['Summ'] . "</td><td>" . round($data['Summ'] / $data['Count']) . "</td><td>" . $data['countActive'] . "</td><td>" . $data['summActive'] . "</td><td>" . round($data['summActive'] / $data['countActive']) . "</td></tr>";
    }echo "<tr style=\"font-weight: bold;\"><td>Всего</td><td>" . $All['Count'] . "</td><td>" . $All['Summ'] . "</td><td>" . round($All['Summ'] / $All['Count']). "</td><td>" . $All['countActive'] . "</td><td>" . $All['summActive'] . "</td><td>" . round($All['summActive'] / $All['countActive'])  . "</td></tr>";
    
    ?>
</table>