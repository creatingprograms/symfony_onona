<div class="backend-left">
<h1>Просмотр характеристики <?=$dopinfo->getName()?> : <?=$dopinfo->getValue()?></h1>
<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $dopinfo->getId() ?></td>
    </tr>
    <tr>
      <th>Название:</th>
      <td><?php echo $dopinfo->getName() ?></td>
    </tr>
    <tr>
      <th>Категория:</th>
      <td><?php echo $dopinfo->getDicategoryId() ?></td>
    </tr>
    <tr>
      <th>Значение:</th>
      <td><?php echo $dopinfo->getValue() ?></td>
    </tr>
    <tr>
      <th>Создан:</th>
      <td><?php echo $dopinfo->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Изменен:</th>
      <td><?php echo $dopinfo->getUpdatedAt() ?></td>
    </tr>
    <tr>
      <th>Позиция:</th>
      <td><?php echo $dopinfo->getPosition() ?></td>
    </tr>
    <tr>
      <th>Slug:</th>
      <td><?php echo $dopinfo->getSlug() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('dopinfo2/edit?id='.$dopinfo->getId()) ?>">Редактировать</a>
&nbsp;
<a href="<?php echo url_for('dopinfo2/index') ?>">Список</a>
</div>
