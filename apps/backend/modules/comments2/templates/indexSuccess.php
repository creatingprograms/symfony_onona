<div class="backend-left">
<h1>Комментарии</h1>
<p class="small">Страница <strong><?= $pageNum ?></strong> из <strong><?= $pagesCount ?></strong>.</p>
<?php
  $i=0;
  $currentShop = isset($_GET['shop']) ? $_GET['shop'] : 0;
  $currentProd = isset($_GET['item']) ? $_GET['item'] : 0;
  $currentPage = isset($_GET['page_id']) ? $_GET['page_id'] : 0;
  $currentArticle = isset($_GET['article']) ? $_GET['article'] : 0;
?>
<div class="">
  <form action="<?php echo url_for('comments2/')?>">
    <table>
      <tbody>
        <tr>
          <td>Товар:</td>
          <td>
            <select name="item" style="width:100%;">
              <option value="0">Любой</option>
              <? if (sizeof($productsArr)) :?>
                <? foreach ($productsArr as $key => $value) :?>
                  <option value="<?= $key ?>" <?= ($currentProd==$key ? ' selected' : '') ?>><?= $value ?></option>
                <? endforeach ?>
              <? endif ?>
            </select>
          </td>
        </tr>
        <tr>
        <tr>
          <td>Магазин:</td>
          <td>
            <select name="shop" style="width:100%;">
              <option value="0">Любой</option>
              <? if (sizeof($shopsArr)) :?>
                <? foreach ($shopsArr as $key => $value) :?>
                  <option value="<?= $key ?>" <?= ($currentShop==$key ? ' selected' : '') ?>><?= $value ?></option>
                <? endforeach ?>
              <? endif ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>Страница:</td>
          <td>
            <select name="shop" style="width:100%;">
              <option value="0">Любая</option>
              <? if (sizeof($pagesArr)) :?>
                <? foreach ($pagesArr as $key => $value) :?>
                  <option value="<?= $key ?>" <?= ($currentPage==$key ? ' selected' : '') ?>><?= $value ?></option>
                <? endforeach ?>
              <? endif ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>Статья:</td>
          <td>
            <select name="shop" style="width:100%;">
              <option value="0">Любая</option>
              <? if (sizeof($articlesArr)) :?>
                <? foreach ($articlesArr as $key => $value) :?>
                  <option value="<?= $key ?>" <?= ($currentArticle==$key ? ' selected' : '') ?>><?= $value ?></option>
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
<a href="<?php echo url_for('comments2/new') ?>">Новый элемент</a>
<table>
  <thead>
    <tr>
      <th style="width: 30%;">Родитель</th>
      <th style="width: 35%;">Комментарий</th>
      <th>Customer</th>
      <th>Доступен</th>
      <th>Username</th>
      <th>Почта</th>
      <th>Ответ</th>
      <th>Рейтинг</th>
      <th>Комментарии менеджера</th>
      <th>Добавлен</th>
      <th>Редактировать</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($commentss as $comments): ?>
    <tr>
      <?
        $parent='<strong>Его нет</strong>';
        if($comments->getProductId()) $parent='<strong>Товар</strong> '.$productsArr[$comments->getProductId()];
        if($comments->getArticleId()) $parent='<strong>Статья</strong> '.$articlesArr[$comments->getArticleId()];
        if($comments->getPageId()) $parent='<strong>Страница</strong> '.$pagesArr[$comments->getPageId()];
        if($comments->getShopsId()) $parent='<strong>Магазин</strong> '.$shopsArr[$comments->getShopsId()];
        $i++;
      ?>
      <?/*<td><a href="<?php echo url_for('comments2/show?id='.$comments->getId()) ?>"><?php echo $comments->getId() ?></a></td>*/?>
      <td><?=$parent ?></td>
      <td><?php echo $comments->getText() ?></td>
      <td><?php echo $comments->getCustomerId() ?></td>
      <td><?php echo $comments->getIsPublic() ? 'Да' : 'Нет'?></td>
      <td><?php echo $comments->getUsername() ?></td>
      <td><?php echo $comments->getMail() ?></td>
      <td><?php echo $comments->getAnswer() ?></td>
      <td><?php echo $comments->getRateSet() + $comments->getRatePlus() - $comments->getRateMinus()?></td>
      <td><?php echo $comments->getCommentManager() ?></td>
      <td><?php echo date('d.m.Y H:i',strtotime($comments->getCreatedAt())) ?></td>
      <td><a href="<?php echo url_for('comments2/edit?id='.$comments->getId()) ?>">Редактировать</a></td>
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
<a href="<?php echo url_for('comments2/new') ?>">Новый элемент</a>
</div>
