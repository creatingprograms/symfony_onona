<?php
global $isTest;
$pageN = sfContext::getInstance()->getRequest()->getParameter('page', 0);
$h1 = end($categorys)['title'];

slot('metaTitle', end($categorys)['title'] . ($pageN > 1 ? " | Страница " . $pageN : ""));
slot('metaKeywords', end($categorys)['keywords'] . ($pageN > 1 ? " | Страница " . $pageN : ""));
slot('metaDescription', end($categorys)['description'] . ($pageN > 1 ? " | Страница " . $pageN : ""));

slot('breadcrumbs', [
  ['text' => 'Каталог', 'link' => '/catalog'],
  ['text' => $h1],
]);

slot('h1', $h1);

$content = str_replace(array("{blockAction}", "{blockRandProd}"), array($blockAction, $blockRandProd), end($categorys)['this_content']);
$content = str_replace(["{replaceSpoiler}", "{endSpoiler}"], '', $content);
$content = preg_replace('/<iframe.+src="(.+)".+<\/iframe>/mU', '<span class="video-container">$0</span>', $content);

if (!$pageN) {
  include_component(
    'page',
    'blockSliders',
    array(
      'positionpage' => 'catalog_feb14_1',
      'isNew' => true,
      'sf_cache_key' => 'slider_feb14_1',
    )
  );

  include_component(
    'category',
    'catalogicons',
    array(
      'slug' => 'sex-igrushki-dlja-par',
      'id' => 2,
      'type' => 'new',
      'isShowTop20' => true,
      'sf_cache_key' => "sex-igrushki-dlja-par -icons",
    )
  );

  include_component(
    'page',
    'blockSliders',
    array(
      'positionpage' => 'catalog_feb14_2',
      'isNew' => true,
      'sf_cache_key' => 'slider_feb14_2',
    )
  );

  include_component(
    'category',
    'catalogicons',
    array(
      'slug' => 'sex-igrushki-dlya-zhenschin',
      'id' => 3,
      'type' => 'new',
      'isShowTop20' => true,
      'sf_cache_key' => "sex-igrushki-dlya-zhenschin -icons",
    )
  );

  include_component(
    'page',
    'blockSliders',
    array(
      'positionpage' => 'catalog_feb14_3',
      'isNew' => true,
      'sf_cache_key' => 'slider_feb14_3',
    )
  );
  include_component(
    'category',
    'catalogicons',
    array(
      'slug' => 'sex-igrushki-dlya--muzhchin',
      'id' => 1,
      'type' => 'new',
      'isShowTop20' => true,
      'sf_cache_key' => "sex-igrushki-dlya--muzhchin -icons",
    )
  );
}

?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content block-content_catalog">
      <?php include_component(
        'category',
        'catalogfilters',
        array(
          'slug' => end($categorys)['slug'],
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
          'isSale' => $isSale,
          'sf_cache_key' => end($categorys)['slug'] . "-"
        )
      );
      ?>
      <div class="wrap-catalog-result">

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
            'baselink' => end($categorys)['slug'],
            'show_more' => true,
            'numbers' => true,
          )); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <? if ($sf_request->getParameter('page', 1) == 1 and $sortOrder == "rating" and sizeof($sf_request->getParameter('filters')) == 0) : ?>
    <article class="intro-block wrapper art-detail">
      <?= $content ?>
    </article>
  <? endif ?>
</div>

