<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?> 
<?
if (is_array($file)) {
    ?><b>Данные записаны. Товары:</b><?
    foreach ($productName as $code=> $prodName) {
        echo "<br />".$code." - ".$prodName['discount']."% - " . $prodName['name'];
    }
} else {
    ?>
<form enctype="multipart/form-data" action="" method="post">
    <input type="hidden" name="form" value="discount" />
        Файл с артикулами(Лучшая цена): <input name="file" type="file">
        <input type="submit" value="Отправить">
    </form>
<form enctype="multipart/form-data" action="" method="post">
    <input type="hidden" name="form" value="bonus" />
        Файл с артикулами(Управляй ценой): <input name="file" type="file">
        <input type="submit" value="Отправить">
    </form><?
}