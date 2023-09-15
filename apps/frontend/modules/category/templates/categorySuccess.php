<?php
global $isTest;
$h1=end($categorys)['this_h1'] != "" ? stripslashes(end($categorys)['this_h1']) : stripslashes(end($categorys)['this_name']);
$keywordsTemplate='{name} купить цена отзывы доставка';
$descriptionTemplate='{name}. Продажа по доступным ценам с доставкой в онлайн интим-магазине Он и Она, 18+';
if (end($categorys)['title']!=""){
  slot('metaTitle', end($categorys)['title'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
else {
  slot('metaTitle', str_replace("{name}", end($categorys)['this_name'], csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
if (end($categorys)['keywords']!=""){
  slot('metaKeywords', end($categorys)['keywords'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
else {
  slot('metaKeywords', str_replace("{name}", end($categorys)['this_name'], $keywordsTemplate));
}
if (end($categorys)['description']!=""){
  slot('metaDescription', end($categorys)['description'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
else {
  slot('metaDescription', str_replace("{name}", end($categorys)['this_name'], $descriptionTemplate));
}
if(isset($_GET['page'])) slot('canonical', '/category/'.sfContext::getInstance()->getRequest()->getParameter('slug'));
if(end($categorys)['canonical']) slot('canonical', end($categorys)['canonical']);
slot('catalog-class', '-forHer '.(is_object($catalog) ? $catalog->getClass() : ''));
$breadcrumbs[]=['text' => $h1];
slot('breadcrumbs', $breadcrumbs);
slot('h1', $h1);
// die('~~~'.$catalog->getClass().'$catalog->getClass()');

// slot('filtersCategory', end($categorys));
// slot('filtersParams', serialize($filters));
// slot('filtersPrice', serialize($price));
// slot('filtersPage', serialize($page));
// slot('filtersSortOrder', serialize($sortOrder));
// slot('filtersDirection', serialize($direction));
// slot('filtersCountProducts', serialize($filtersCountProducts));
// slot('filtersIsStock', serialize($isStock));

?>
<? if(!$isTest):?>
  <script type="text/javascript">
      (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function () {
          try {
              rrApi.categoryView(<?= end($categorys)['this_id'] ?>);
          } catch (e) {
          }
      })
  </script>
<? endif ?>
<? //include_component("page", "blockSliders", array('sf_cache_key' => 'slider', 'is_onlymoscow' => false)); ?>
<?
  /*include_component( 'category', 'catalogicons',
    array(
      'slug' => $sf_request->getParameter('slug'),
      'id' => $catalog->get('id'),
      'sf_cache_key' => $catalog->get('slug') . "-icons",
    )
  );*/
?>

<div class="wrapper wrap-cont -clf">
  <?php include_component( 'category', 'catalogfilters',
    array(
      'slug' => $sf_request->getParameter('slug'),
      'page' => $page,
      'price' => $price,
      // 'slot' => get_slot('category_slug'),
      'filtersCategory'=>end($categorys),
      'sortOrder'=>$sortOrder,
      'direction'=>$direction,
      'filters'=>$filters,
      'isCat' => true,
      'filter_service_category' => $filter_service_category,
      // 'isStock'=>$isStock,
      // 'isNovice'=>$isNovice,
      'isSale' => $isSale,
      'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
    )
  );
 ?>
  <main class="cont-right -filtr">
    <? include_component('category', 'catalogsorters',
        array(
          'link'=> '',//'/catalog/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
          'sortOrder'=>$sortOrder,
          'direction'=>$direction,
          'set'=>3, //У каталога несколько иная логика работы сортировщиков
        )
    );?>
    <div data-retailrocket-markup-block="5ba3a57697a52530d41bb22d" data-category-id="<?= end($categorys)['this_id'] ?>" data-colorset="<?=(is_object($catalog) ? $catalog->getClass() : '')?>"></div>
    <? include_component('category', 'catalogPopularCats',
        array(
          'catalog'=> '',
          'category' => sfContext::getInstance()->getRequest()->getParameter('slug')
        )
    );
    ?>

    <?
    include_component('category', 'listItems',
      array(
        'sf_cache_key' => 'product-list-full-'.$sortOrder.'-'.$direction.sfContext::getInstance()->getRequest()->getParameter('slug').'-'.sfContext::getInstance()->getRequest()->getParameter('page', 1),
        'ids' => $ids,
        // 'style' => 'full',
        'catName' => end($categorys)['this_name'],
        'catId' => end($categorys)['this_id'],
      )
    );
    ?>
    <?php if ($pagesCount > 1 ):?>
      <? include_component("noncache", "paginationCatalog", array(
        'page' => $page,
        'pagesCount' => $pagesCount,
        'baselink' => '/category/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
        'show_more' => true,
        'numbers' => true,
      )); ?>
    <?php endif; ?>
  </main>
</div>

<? if ($sf_request->getParameter('page', 1) == 1 and $sortOrder == "rating" and sizeof($sf_request->getParameter('filters')) == 0) : ?>
  <?
    $content = str_replace(array("{blockAction}", "{blockRandProd}"), array($blockAction, $blockRandProd), end($categorys)['this_content']);
    $content = str_replace(["{replaceSpoiler}", "{endSpoiler}"], '', $content);
    $content = preg_replace('/<iframe.+src="(.+)".+<\/iframe>/mU', '<span class="video-container">$0</span>', $content);
    //if(mb_strlen(strip_tags($content))>330)//В новых реалиях работать не будет
    //$content='<div class="spoiler-category">'.$content.'</div>'.'<div class="show-more"></div>';
  ?>
  <article class="intro-block wrapper art-detail">
    <?= $content ?>
  </article>
<? endif ?>
<?
  slot('caltat', 2);
  slot('caltat_cat', end($categorys)['this_id']);
  foreach (get_slot('advcakeItems') as $item) {
    $items[]=$item['id'];
  }
  slot('caltat_cat_params', [
    'products' => $items,
    'page' => $page
  ]);
  slot('advcake', 3);
  slot('advcake_list', [
    'products' => get_slot('advcakeItems'),
    'category' => [
      'id' => end($categorys)['this_id'],
      'name' =>end($categorys)['this_name'],
    ],
  ]);
?>
