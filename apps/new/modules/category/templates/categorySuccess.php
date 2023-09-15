<?php
global $isTest;
$h1 = end($categorys)['this_h1'] != "" ? stripslashes(end($categorys)['this_h1']) : stripslashes(end($categorys)['this_name']);
$keywordsTemplate = '{name} купить цена отзывы доставка';
$descriptionTemplate = '{name}. Продажа по доступным ценам с доставкой в онлайн интим-магазине Он и Она, 18+';
/*
if (end($categorys)['title'] != "") {
  slot('metaTitle', end($categorys)['title'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
} else {
  slot('metaTitle', str_replace("{name}", end($categorys)['this_name'], csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
*/
if (end($categorys)['keywords'] != "") {
  slot('metaKeywords', end($categorys)['keywords'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
} else {
  slot('metaKeywords', str_replace("{name}", end($categorys)['this_name'], $keywordsTemplate));
}
/*
if (end($categorys)['description'] != "") {
  slot('metaDescription', end($categorys)['description'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
} else {
  slot('metaDescription', str_replace("{name}", end($categorys)['this_name'], $descriptionTemplate));
}
*/
if(sizeof($breadcrumbs) > 1){//подраздел
  $title = "$h1 в Москве, купить по выгодной цене с анонимной доставкой в интернет секс-шопе";
  $description = "Купите " . mb_strtolower($h1) . " по выгодной цене в нашем секс-шопе онлайн. Широкий ассортимент, надежность и конфиденциальность гарантированы. Закажите прямо сейчас и получите быструю доставку в любую точку Москвы.";
}
else{ //раздел
  $title = "$h1 в Москве, купить " . mb_strtolower($h1) . " по выгодной цене с анонимной доставкой в интернет секс-шопе";
  $description = "$h1 по выгодной цене в нашем секс-шопе онлайн. Широкий ассортимент, надежность и конфиденциальность гарантированы. Закажите прямо сейчас и получите быструю доставку в любую точку Москвы.";
}
if(!empty(end($categorys)['description'])) $description = end($categorys)['description'];
if(!empty(end($categorys)['title'])) $title = end($categorys)['title'];

slot('metaTitle', $title);
slot('metaDescription', $description);

if (!empty($_GET)) slot('canonical', '/category/' . sfContext::getInstance()->getRequest()->getParameter('slug'));
if (end($categorys)['canonical']) slot('canonical', end($categorys)['canonical']);
$lowerSlug = mb_strtolower($sf_request->getParameter('slug'));
$breadcrumbs[] = ['text' => $h1];
slot('breadcrumbs', $breadcrumbs);
slot('catalog-class', '-forHer ' . (is_object($catalog) ? $catalog->getClass() : ''));
if ($lowerSlug == 'dlya_novichkov') {
  $h1 = 'Выбираем первую секс-игрушку с Он&nbsp;и&nbsp;Она';
  slot('catalog-class', '-forHer -forNewbie' . (is_object($catalog) ? $catalog->getClass() : ''));
}
slot('h1', $h1);

$content = str_replace(array("{blockAction}", "{blockRandProd}"), array($blockAction, $blockRandProd), end($categorys)['this_content']);
$content = str_replace(["{replaceSpoiler}", "{endSpoiler}"], '', $content);
$content = preg_replace('/<iframe.+src="(.+)".+<\/iframe>/mU', '<span class="video-container">$0</span>', $content);
//if(mb_strlen(strip_tags($content))>330)//В новых реалиях работать не будет
//$content='<div class="spoiler-category">'.$content.'</div>'.'<div class="show-more"></div>';

?>
<? if (!$isTest) : ?>
  <script type="text/javascript">
    (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
      try {
        rrApi.categoryView(<?= end($categorys)['this_id'] ?>);
      } catch (e) {}
    })
  </script>
<? endif ?>
<? include_component(
  'page',
  'blockSliders',
  array(
    'positionpage' => 'category_' . $lowerSlug,
    'isNew' => true,
    'sf_cache_key' => 'slider_catalog_' . $lowerSlug,
  )
); ?>
<? if ($lowerSlug == 'skidki_do_60_percent') : ?>
  <div data-retailrocket-markup-block="5e1c429797a52836ac752058"></div>
<? endif ?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content block-content_catalog">
      <?php include_component(
        'category',
        'catalogfilters',
        array(
          'slug' => $sf_request->getParameter('slug'),
          'page' => $page,
          'price' => $price,
          // 'slot' => get_slot('category_slug'),
          'filtersCategory' => end($categorys),
          'sortOrder' => $sortOrder,
          'direction' => $direction,
          'filters' => $filters,
          'isCat' => true,
          'filter_service_category' => $filter_service_category,
          'shops' => $shops,
          // 'isStock'=>$isStock,
          // 'isNovice'=>$isNovice,
          'isSale' => $isSale,
          'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
        )
      );
      ?>
      <div class="wrap-catalog-result">
        <? if ($lowerSlug == 'dlya_novichkov' && $sf_request->getParameter('page', 1) == 1 and $sortOrder == "rating" and sizeof($sf_request->getParameter('filters')) == 0) : ?>
          <!-- <article class="intro-block wrapper art-detail"> -->
          <?= $content ?>
          <!-- </article> -->
          <br>
        <? else : ?>
          <div data-retailrocket-markup-block="5ba3a57697a52530d41bb22d" data-category-id="<?= end($categorys)['this_id'] ?>"></div>
        <? endif ?>

        <? include_component(
          'category',
          'catalogsorters',
          array(
            'link' => '', //'/catalog/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
            'sortOrder' => $sortOrder,
            'direction' => $direction,
            'set' => 3, //У каталога несколько иная логика работы сортировщиков
          )
        ); ?>
        <? include_component(
          'category',
          'listItems',
          array(
            'sf_cache_key' => 'product-list-full-' . $sortOrder . '-' . $direction . sfContext::getInstance()->getRequest()->getParameter('slug') . '-' . sfContext::getInstance()->getRequest()->getParameter('page', 1) . '|' . $sf_user->isAuthenticated(),
            'ids' => $ids,
            'bannerType' => 'Каталог',
            'catName' => end($categorys)['this_name'],
            'catId' => end($categorys)['this_id'],
          )
        ); ?>
        <?php if ($pagesCount > 1) : ?>
          <? include_component("noncache", "paginationCatalog", array(
            'page' => $page,
            'pagesCount' => $pagesCount,
            'baselink' => '/category/' . sfContext::getInstance()->getRequest()->getParameter('slug'),
            'show_more' => true,
            'numbers' => true,
          )); ?>
        <?php endif; ?>
        <div data-retailrocket-markup-block="6461e46593434a585bb7b852"></div>
      </div>
    </div>
    
  </div>

  <? if ($lowerSlug != 'dlya_novichkov' && $sf_request->getParameter('page', 1) == 1 and $sortOrder == "rating" and sizeof($sf_request->getParameter('filters')) == 0) : ?>
    <article class="intro-block wrapper art-detail">
      <?= $content ?>
    </article>
  <? endif ?>

  <? if ($lowerSlug == 'dlya_novichkov' && $sf_request->getParameter('page', 1) == 1 and $sortOrder == "rating" and sizeof($sf_request->getParameter('filters')) == 0) : ?>
    <? include_component("page", "lastReviews", array(
      'cat_id' => end($categorys)['this_id'],
      'sf_cache_key' => 'lastReviews' . date('d.m.Y'),
    )); ?>
  <? endif ?>

  <?
  slot('caltat', 2);
  slot('caltat_cat', end($categorys)['this_id']);
  foreach (get_slot('advcakeItems') as $item) {
    $items[] = $item['id'];
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
      'name' => end($categorys)['this_name'],
    ],
  ]);
  ?>