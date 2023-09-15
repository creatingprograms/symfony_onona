<?
// die('<pre>view'.print_r($ids, true));
$h1 = $category->getName();
slot('breadcrumbs', [
  ['text' => $h1],
]);

?>
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right">
    <h1><?= $h1 ?></h1>
    <?/*<div data-retailrocket-markup-block="5e1c429797a52836ac752058"></div>*/?>
    <form action="/category/<?=$category->getSlug()?>" type="get" class="discount-60-form js-filters-form">
      <input type="hidden" name="page" value="<?= $page ? $page : 1 ?>" id="form-filter-page">
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
    <?php if (sizeof($ids)) :?>
      <?
      include_component('category', 'listItems',
        array(
          'sf_cache_key' => 'product-list-full-discount-60'.$page,
          'ids' => $ids,
          // 'style' => 'full',
          'catName' => $h1,
          'catId' => $category->getId(),
        )
      );
      // die('<pre>'.print_r($ids, true));
      ?>
      <?php if ($pagesCount > 1 ):?>
        <? include_component("noncache", "paginationCatalog", array(
          'page' => $page,
          'pagesCount' => $pagesCount,
          'baselink' => '/caterory/skidki_do_60_percent'.sfContext::getInstance()->getRequest()->getParameter('slug'),
          'show_more' => true,
          'numbers' => true,
        )); ?>
      <?php endif; ?>
      <?
      slot('advcake', 7);
      slot('advcake_list', [
        'products' => get_slot('advcakeItems'),
      ]);
      ?>
    <?php else : ?>
      <p>Подходящих товаров не найдено</p>
    <?php endif ?>
  </main>
  <aside class="sidebar">
    <? include_component('page', 'catalogSidebar', array('sf_cache_key' => "catalogSidebar")); ?>
    <? //include_component('article', 'leftPreviewBlock', array('sf_cache_key' => "catalogSidebar")); ?>
    <?// include_component('category', 'brandsSidebar', array('sf_cache_key' => "brandsSidebar".rand(1, 30))); ?>
  </aside>
</div>
<? slot('not_show_timer', true) ?>
