<?php //use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<?
  $currentMon=$currentYear=$currentLastMon=$currentLastYear=false;
  if (isset($_POST['start_mon'])) $currentMon=$_POST['start_mon'];
  // if (isset($_POST['end_mon'])) $currentLastMon=$_POST['end_mon'];
  if (isset($_POST['start_year'])) $currentYear=$_POST['start_year'];
  // if (isset($_POST['end_year'])) $currentLastYear=$_POST['start_year'];
?>
<form enctype="multipart/form-data" action="" method="post">
  За&nbsp;
  <select name="start_mon">
    <option value="01" <?=$currentMon=='01' ? 'selected' : ''?>>Январь</option>
    <option value="02" <?=$currentMon=='02' ? 'selected' : ''?>>Февраль</option>
    <option value="03" <?=$currentMon=='03' ? 'selected' : ''?>>Март</option>
    <option value="04" <?=$currentMon=='04' ? 'selected' : ''?>>Апрель</option>
    <option value="05" <?=$currentMon=='05' ? 'selected' : ''?>>Май</option>
    <option value="06" <?=$currentMon=='06' ? 'selected' : ''?>>Июнь</option>
    <option value="07" <?=$currentMon=='07' ? 'selected' : ''?>>Июль</option>
    <option value="08" <?=$currentMon=='08' ? 'selected' : ''?>>Август</option>
    <option value="09" <?=$currentMon=='09' ? 'selected' : ''?>>Сентябрь</option>
    <option value="10" <?=$currentMon=='10' ? 'selected' : ''?>>Октябрь</option>
    <option value="11" <?=$currentMon=='11' ? 'selected' : ''?>>Ноябрь</option>
    <option value="12" <?=$currentMon=='12' ? 'selected' : ''?>>Декабрь</option>
  </select>
  <select name="start_year">
    <?php for($i=date('Y'); $i>2017; $i--) :?>
      <option value="<?= $i ?>" <?=$currentYear==$i ? 'selected' : ''?>><?= $i ?></option>
    <?php endfor ?>
  </select>
  <?php /*&nbsp; &nbsp;По&nbsp;
  <select name="end_mon">
    <option value="01" <?=$currentLastMon=='01' ? 'selected' : ''?>>Январь</option>
    <option value="02" <?=$currentLastMon=='02' ? 'selected' : ''?>>Февраль</option>
    <option value="03" <?=$currentLastMon=='03' ? 'selected' : ''?>>Март</option>
    <option value="04" <?=$currentLastMon=='04' ? 'selected' : ''?>>Апрель</option>
    <option value="05" <?=$currentLastMon=='05' ? 'selected' : ''?>>Май</option>
    <option value="06" <?=$currentLastMon=='06' ? 'selected' : ''?>>Июнь</option>
    <option value="07" <?=$currentLastMon=='07' ? 'selected' : ''?>>Июль</option>
    <option value="08" <?=$currentLastMon=='08' ? 'selected' : ''?>>Август</option>
    <option value="09" <?=$currentLastMon=='09' ? 'selected' : ''?>>Сентябрь</option>
    <option value="10" <?=$currentLastMon=='10' ? 'selected' : ''?>>Октябрь</option>
    <option value="11" <?=$currentLastMon=='11' ? 'selected' : ''?>>Ноябрь</option>
    <option value="12" <?=$currentLastMon=='12' ? 'selected' : ''?>>Декабрь</option>
  </select>
  <select name="end_year">
    <?php for($i=date('Y'); $i>2017; $i--) :?>
      <option value="<?= $i ?>" <?=$currentLastYear==$i ? 'selected' : ''?>><?= $i ?></option>
    <?php endfor ?>
  </select>*/?>
  &nbsp; &nbsp;<input type="submit" name="query" value="Сформировать"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
</form>

<?php /*<pre><?= print_r($data, true) ?></pre>*/?>
