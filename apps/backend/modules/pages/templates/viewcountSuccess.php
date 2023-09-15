<?php use_helper('I18N', 'Date') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<h1>Статистика просмотров страниц</h1>
<? if(sizeof($pages) || sizeof($cats)) :?>
<table style="width: 100%;">
  <thead>
    <tr>
      <th>Название</th>
      <th>URL</th>
      <th>Просмотров</th>
    </tr>
  </thead>
  <tbody>
    <? if(sizeof($pages)) foreach($pages as $page):?>
      <tr>
        <td><?= $page['name']?></td>
        <td><a href="/<?= $page['slug']?>"><?= $page['slug']?></a></td>
        <td><?= $page['views_count']?></td>
      </tr>
    <? endforeach ?>
    <? if(sizeof($cats)) foreach($cats as $page):?>
      <tr>
        <td><?= $page['name']?></td>
        <td><a href="/category/<?= $page['slug']?>"><?= $page['slug']?></a></td>
        <td><?= $page['views_count']?></td>
      </tr>
    <? endforeach ?>
  </tbody>
</table>
<? endif ?>
