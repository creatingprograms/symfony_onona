<?php
global $isTest;

$h1=$category['this_h1'];
slot('metaTitle', $category['title'] == '' ? $h1 . " – Секс шоп Он и Она" : $category['title'] );
slot('metaKeywords', $category['keywords'] == '' ? $h1 : $category['keywords']);
slot('metaDescription', $category['description'] == '' ? $h1 : $category['description']);
if($category['canonical']) slot('canonical', $category['canonical'] );

slot('catalog-class', '-forHer -full');
slot('breadcrumbs', [
  ['text' => $h1],
]);
// slot('h1', $h1);

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
<div class="wrapper wrap-cont -clf">
  <?php include_component( 'category', 'catalogfilters',
    array(
      'slug' => $sf_request->getParameter('slug'),
      'page' => $page,
      'price' => $price,
      // 'slot' => get_slot('category_slug'),
      'filtersCategory'=>$category,
      'sortOrder'=>$sortOrder,
      'direction'=>$direction,
      'filters'=>$filters,
      'isFullCatalog' => true,
      // 'isStock'=>$isStock,
      // 'isNovice'=>$isNovice,
      'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
    )
  ); ?>
  <main class="cont-right -filtr">
    <h1><?= $h1 ?></h1>
    <? include_component('category', 'catalogsorters',
        array(
          'link'=> '',
          'sortOrder'=>$sortOrder,
          'direction'=>$direction,
          'set'=>3, //У каталога несколько иная логика работы сортировщиков
        )
    );?>

    <?
    include_component('category', 'listItems',
      array(
        'sf_cache_key' => 'product-list-full-'.$sortOrder.'-'.$direction.'service_all'.'-'.sfContext::getInstance()->getRequest()->getParameter('page', 1),
        'ids' => $ids,
        'catName' => $h1,
        'catId' => $category['this_id'],
      )
    );
    ?>
    <?php if ($pagesCount > 1 ):?>
      <? include_component("noncache", "paginationCatalog", array(
        'page' => $page,
        'pagesCount' => $pagesCount,
        'baselink' => '/catalog',
        'show_more' => true,
        'numbers' => true,
      )); ?>
    <?php endif; ?>
  </main>
</div>
<? if ($sf_request->getParameter('page', 1) == 1 and $sortOrder == "rating" and sizeof($sf_request->getParameter('filters')) == 0) : ?>
  <?
    $content = str_replace(array("{blockAction}", "{blockRandProd}"), array($blockAction, $blockRandProd), $category['this_content']);
    $content = str_replace(["{replaceSpoiler}", "{endSpoiler}"], '', $content);
    $content = preg_replace('/<iframe.+src="(.+)".+<\/iframe>/mU', '<span class="video-container">$0</span>', $content);
  ?>
  <article class="intro-block wrapper">
    <div class="intro-block-wrap">
      <div class="intro-block-desc  art-detail">
        <?= $content ?>
      </div>
    </div>
  </article>
<? endif?>
<?
  slot('caltat', 2);
  slot('caltat_cat', $category['this_id']);
  foreach (get_slot('advcakeItems') as $item) {
    $items[]=$item['id'];
  }
  slot('caltat_cat_params', [
    'products' => $items,
    'page' => $page
  ]);
  slot('advcake', 3);
  slot('advcake_list', [
    'products' => get_slot('advcakeItems'),// пример формата внизу страницы
    'category' => [
      'id' => $category['this_id'],
      'name' => $h1,
    ],
  ]);
?>
