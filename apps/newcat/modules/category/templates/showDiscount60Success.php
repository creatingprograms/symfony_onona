<?/*
<div>
  <?=print_r([
    // 'get' => $_GET,
    // 'sql' => $sqlBody,
    // 'filterCat' => $filterCat,
    // 'maxPrice'=>$maxPrice,
    // 'minPrice'=>$minPrice,
    'ids'=>$ids,
    'advcakeProducts'=>$advcakeItems,
  ], true)?>
</div>
*/?>


<?php
  // $canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
  slot('leftBlock', true);
?>

<ul class="breadcrumbs">
    <li><a href="/">Секс-шоп главная</a></li>
    <li><?=$category->getName()?></li>
</ul>
<h1 class="title"><?=$category->getName()?></h1>
<div class="discount-sixty gtm-category-show" data-list="Скидки до 60%">
  <form action="/category/<?=$category->getSlug()?>" type="get" class="discount-60-form">
    <div class="catalog-block">
      <?php if (sizeof($catalogs)) foreach ($catalogs as $key => $cat) :?>
        <div class="cat js-cat-filter <?= in_array($key, $filterCat)?' active' :''?>" data-cat="<?= $key ?>"><?= $cat ?></div>
      <?php endforeach ?>
    </div>
    <div class="price-block">
      цена от <input type="text" value="<?=$minPrice ? $minPrice : '' ?>" name="min_price" class="min_price">
      до <input type="text" value="<?=$maxPrice ? $maxPrice : ''?>" name="max_price" class="max_price">
      <button type="submit">Найти</button>
    </div>
  </form>
  <div class="content"><?=$category->getContent()?></div>
  <?php if (sizeof($ids)) :?>
    <?php
      slot('gdeSlonCatId', 'category_id: "'.$category->getId().'",');
      slot('gdeSlonMode', 'list');
      slot('advcake', 7);
      slot('advcake_list', [
        'products' => $advcakeItems,
      ]);
      include_component('category', 'articleproductpart',
        array(
          'ids' => $ids,
          'not_replace' => true,
        )
      );
      return;
    ?>
  <?php else : ?>
    <p>Подходящих товаров не найдено</p>
  <?php endif ?>
</div>
<div style="clear: both;"></div>
<?/*<script>
  $(document).ready(function(){
    console.log('foo');
  });
</script>
*/?>
