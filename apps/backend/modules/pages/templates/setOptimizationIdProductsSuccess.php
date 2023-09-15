<?php use_helper('I18N', 'Date') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?> 
<?
if (is_array($file)) {
    ?><b>Данные записаны. Товары:</b><?
    foreach ($productName as $prodName) {
        echo "<br />" . $prodName;
    }
} else {
    ?><form enctype="multipart/form-data" action="" method="post">
        Файл с артикулами: <input name="file" type="file">
        <input type="submit" value="Отправить">
    </form><?
}