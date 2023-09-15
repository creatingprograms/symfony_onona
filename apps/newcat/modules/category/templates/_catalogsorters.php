<?
  /*
    echo '<pre>'.print_r(
      [
        '$filters' =>$filters,
        // '$filtersCategory'=>$filtersCategory,
        '$price' =>$price,
        '$page' =>$page,
        '$sortOrder' =>$sortOrder,
        '$direction' =>$direction,
        '$filtersCountProducts' =>$filtersCountProducts,
        '$isStock' =>$isStock,
        // '' =>
      ],true).'</pre>';
      */
    ?>
<div class="blockSort">
  <div>
    Сортировать по:
    <select class="js-sort-order-simple" data-link="<?=$link?>">
      <? if($set==1):?>
        <option value="sortorder"<?= $sortOrder == "sortorder" ? "selected" : "" ?>>Популярные</option>
        <option value="date"<?= $sortOrder == "date" ? "selected" : "" ?>>Новинки</option>
        <option value="actions"<?= $sortOrder == "actions" ? "selected" : "" ?>>Акции и скидки</option>
        <option value="price" <?= $sortOrder == "price" && $direction=="desc" ? "selected" : "" ?> data-direction="desc">Цена ↓</option>
        <option value="price" <?= $sortOrder == "price" && $direction=="asc" ? "selected" : "" ?> data-direction="asc">Цена ↑</option>
        <option value="comments"<?= $sortOrder == "comments" ? "selected" : "" ?>>Отзывы</option>
      <?endif?>
      <? if($set==2):?>
        <option value="date"<?= $sortOrder == "date" ? "selected" : "" ?>>Новинки</option>
        <option value="price" <?= $sortOrder == "price" && $direction=="desc" ? "selected" : "" ?> data-direction="desc">Цена ↓</option>
        <option value="price" <?= $sortOrder == "price" && $direction=="asc" ? "selected" : "" ?> data-direction="asc">Цена ↑</option>
        <option value="comments"<?= $sortOrder == "comments" ? "selected" : "" ?>>Отзывы</option>
        <option value="rating"<?= $sortOrder == "rating" ? "selected" : "" ?>>Рейтинг</option>
      <?endif?>
    </select>
  </div>
  <?/*<div>
    <label>
      <input type="checkbox" class="js-is-stock-simple" value="1"<?= $isStock ? " checked" : ''?>>
      <span>Скрыть</span> отсутствующие товары
    </label>
  </div>*/?>
</div>
<?/*
    <div class="js-show-filters show-filters-button">показать все фильтры</div>
    <div class="filters-hided-block">
      <div class="filters-block-name">Фильтры</div>

    </div>
*/?>
