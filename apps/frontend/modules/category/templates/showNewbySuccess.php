<?php
global $isTest;
$keywordsTemplate='{name} купить цена отзывы доставка';
$descriptionTemplate='{name}. Продажа по доступным ценам с доставкой в онлайн интим-магазине Он и Она, 18+';
$h1='Для новичков';
// die('dfgo');
slot('metaTitle', str_replace("{name}", $h1, csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
slot('metaKeywords', str_replace("{name}",  $h1, $keywordsTemplate));
slot('metaDescription', str_replace("{name}", $h1, $descriptionTemplate));
slot('canonical', '/category/dlya_novichkov' );
$breadcrumbs[]=['text' => $h1];
slot('breadcrumbs', $breadcrumbs);
slot('h1', $h1);
slot('catalog-class', '-forHer ');
?>
<? if(!$isTest):?>
  <script type="text/javascript">
      (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function () {
          try {
            rrApi.categoryView(271);//Для новичков
          } catch (e) {
          }
      })
  </script>
<? endif ?>
<section class="wrapper showcase novice-icons">

  <div class="novice-icon for-pairs js-novice-filter<?=$isNovice=='for_pairs' ? ' active' : ''?>" data-value="for_pairs">Для пар >></div>
  <div class="novice-icon for-her js-novice-filter<?=$isNovice=='for_she' ? ' active' : ''?>" data-value="for_she">Для нее >></div>
  <div class="novice-icon for-him js-novice-filter<?=$isNovice=='for_her' ? ' active' : ''?>" data-value="for_her">Для него >></div>
</section>
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
      'isNewby' => true,
      // 'isStock'=>$isStock,
      'isNovice'=>$isNovice,
      'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
    )
  );
 ?>
  <main class="cont-right -filtr">
    <?//= '<pre>~~~--'.print_r( $sortOrder, true).'||---~</pre>';?>
    <? include_component('category', 'catalogsorters',
        array(
          'link'=> '',//'/catalog/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
          'sortOrder'=>$sortOrder,
          'direction'=>$direction,
          'set'=>3, //У каталога несколько иная логика работы сортировщиков
        )
    );?>

    <?
    include_component('category', 'listItems',
      array(
        'sf_cache_key' => 'product-list-full-'.$sortOrder.'-'.$direction.sfContext::getInstance()->getRequest()->getParameter('slug').'-'.sfContext::getInstance()->getRequest()->getParameter('page', 1),
        'ids' => $ids,
        // 'style' => 'full',
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
    //if(mb_strlen(strip_tags($content))>330)//В новых реалиях работать не будет
    //$content='<div class="spoiler-category">'.$content.'</div>'.'<div class="show-more"></div>';
  ?>
  <article class="intro-block wrapper">
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
    'page' => sfContext::getInstance()->getRequest()->getParameter('page', 1)
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
