<?php use_helper('I18N', 'Date') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<?
if (isset($file)) { ?>
    <pre>
      <?//= print_r($str, true) ?>
    </pre>
    <b>Данные записаны. Товары:</b><?
    foreach ($productName as $prodName) {
        echo "<br />" . $prodName;
    }
    ?>
    <br><strong>Всего <?=sizeof($productName)?> товаров</strong>
    <hr>
    <strong>Пропущенные артикулы:</strong>
    <? foreach ($artNotFound as $prodName) {
        echo "<br />" . $prodName;
    }
    ?>
    <br><strong>Всего <?=sizeof($artNotFound)?> артикулов</strong>
    <?
} else {
    ?><form enctype="multipart/form-data" action="" method="post">
        <?php if (sizeof($cats)) :?>
          <pre>
            <?//= print_r($cats, true) ?>
          </pre>
          Категория
          <select name="cat">
          <?php foreach ($cats as $cat) :?>
            <option value="<?=$cat['id']?>"><?=$cat['name']?></option>
            <?php if (isset($cat['sub'])) foreach ($cat['sub'] as $sub) {?>
              <option value="<?=$sub['id']?>">----<?=$sub['name']?></option>
            <?php } ?>
          <?php endforeach ?>
        </select>
        <?php endif ?>
        Файл с артикулами: <input name="file" type="file">
        <input type="submit" value="Отправить">
        <br>
        <br>
        <label><input type="checkbox" name="with_stock" value="1"> С остатками, ценами и привязкой</label>
    </form>
    <div style="margin-top: 20px; border-radius: 5px; border: 1px solid #00f; background-color: #bDe8f6; color: #00f; padding: 10px;">
      формат файла <strong>csv</strong>, кодировка <strong>win-1251</strong>, разделитель <strong>;</strong><br>
      [1] Первый столбец содержит обрабатываемые артикулы<br>
      [2] содержит доступное количество<br>
      [3] содержит текущую цену<br>
      [4] содержит акционную цену<br>
      [5] Для пар<br>
      [6] Для нее<br>
      [7] Для него<br>
      [8] БДСМ<br>
      [9] Косметика<br>
      [10] Белье<br>
      [11] Разное<br>
      [12] и далее не обрабатываются<br>
      Первая строка содержит заголовок таблицы и не обрабатывается.<br>
      Строки с пустым артикулом будут пропущены
      Артикулы будут добавлены в выбранную категорию
    </div><?
}
