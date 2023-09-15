<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<?php use_stylesheet('/newdis/css/jquery-ui-1.10.3.custom.css', 'first') ?>
<?php use_javascript('/newdis/js/jquery-ui-1.10.3.custom.js'); ?>
<?php use_javascript('/newdis/js/i18n/jquery-ui-i18n.js'); ?>
<script>$(function(){
  $.datepicker.setDefaults(
        $.extend($.datepicker.regional["ru"])
  );
  $("#datepicker").datepicker();
  $("#datepicker2").datepicker();
});</script>
<form method="post">
    Период:      <input  id="datepicker" name="from" value="<?=isset($_POST['fromDate'])? $_POST['fromDate'] : ''?>"> -
    <input  id="datepicker2" name="to" value="<?=isset($_POST['to']) ? $_POST['to'] : ''?>">
    <input type="submit" value="Скачать" name="download"><br />
</form>
