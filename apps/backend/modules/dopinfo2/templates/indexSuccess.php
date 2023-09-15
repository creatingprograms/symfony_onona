<div class="backend-left">
<h1>Дополнительные характеристики</h1>
<p class="small">Страница <strong><?= $pageNum ?></strong> из <strong><?= $pagesCount ?></strong>.</p>
<a href="<?php echo url_for('dopinfo2/new') ?>">Новый элемент</a>
<?php
  $i=0;
  $dicat = isset($_GET['dicategory']) ? $_GET['dicategory'] : 0;
?>
<!-- <pre><? //=print_r($dopinfoCats, true)?></pre> -->
<table>
  <thead>
    <tr>
      <!-- <th>Название</th> -->
      <th>Название</th>
      <th>Значение</th>
      <th>Смотреть</th>
      <th>Редактировать</th>
      <!-- <th>Created at</th>
      <th>Updated at</th>
      <th>Position</th>
      <th>Slug</th> -->
    </tr>
  </thead>
  <tbody>
    <?php foreach ($dopinfos as $dopinfo): ?>
    <tr>
      <!-- <td><?php //echo $dopinfo->getName() ?></td> -->
      <td><?= isset($dopinfoCats[$dopinfo->getDicategoryId()]) ? $dopinfoCats[$dopinfo->getDicategoryId()] : '' ?></td>
      <td><?php echo $dopinfo->getValue(); $i++; ?></td>
      <td><a href="<?php echo url_for('dopinfo2/show?id='.$dopinfo->getId()) ?>">Смотреть</a></td>
      <td><a href="<?php echo url_for('dopinfo2/edit?id='.$dopinfo->getId()) ?>">Редактировать</a></td>
      <!-- <td><?php //echo $dopinfo->getCreatedAt() ?></td>
      <td><?php //echo $dopinfo->getUpdatedAt() ?></td>
      <td><?php //echo $dopinfo->getPosition() ?></td>
      <td><?php //echo $dopinfo->getSlug() ?></td> -->
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<p>Показаны <strong><?=$i?></strong> из <strong><?= $count ?></strong> результата(ов)</p>
<? if ( $pagesCount>1 ):?>
  <?php include_partial('pagination', array(
    'pageNum' => $pageNum,
    'pagesCount' => $pagesCount,
    'filter' => $filter,
  )) ?>
<? endif ?>
<a href="<?php echo url_for('dopinfo2/new') ?>">Новый элемент</a>
</div>
<div class="backend-right">
  <form action="<?php echo url_for('dopinfo2/index')?>">
    <table>
      <tbody>
        <tr>
          <td>Категория:</td>
          <td>
            <select name="dicategory">
              <option value="0">Все</option>
              <? if (sizeof($dopinfoCats)) :?>
                <? foreach ($dopinfoCats as $key => $value) :?>
                  <option value="<?= $key ?>" <?= ($dicat==$key ? ' selected' : '') ?>><?= $value ?></option>
                <? endforeach ?>
              <? endif ?>
            </select>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" value="Фильтр"></td>
      </tbody>
    </table>

  </form>
</div>
