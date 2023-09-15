<?php use_helper('I18N', 'Date') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<?
if (isset($file)) { ?>
    <pre>
      <?//= print_r($str, true) ?>
    </pre>
    <b>Данные записаны.</b><?/*
    foreach ($productName as $prodName) {
        echo "<br />" . $prodName;
    }
    */?>
    <br><strong>Всего <?=sizeof($productName)?> купонов</strong>
    <hr>
    <strong>Новые купоны:</strong>
    <? foreach ($artNotFound as $prodName) {
        echo "<br />" . $prodName;
    }
    ?>
    <br><strong>Всего <?=sizeof($artNotFound)?> купонов</strong>
    <?
} else {
    ?><form enctype="multipart/form-data" action="" method="post">
        Файл с купонами: <input name="file" type="file">
        <input type="submit" value="Отправить">
        <br>
        <br>
    </form>
    <div style="margin-top: 20px; border-radius: 5px; border: 1px solid #00f; background-color: #bDe8f6; color: #00f; padding: 10px;">
      формат файла <strong>csv</strong>, кодировка <strong>UTF-8</strong>, разделитель <strong>;</strong><br>
      [1] Первый столбец текст купона<br>
      [2] процент<br>
      [3] содержит дату начала действия купона<br>
      [4] содержит дату окончания действия купона<br>
      [5] и далее не обрабатываются<br>
      Первая строка содержит заголовок таблицы и не обрабатывается.<br>
      Строки с пустым купоном будут пропущены
      В случае присутствия купона диапазон дат и скидка будут обновлены
      Формат даты dd.mm.YYYY
    </div><?
}
