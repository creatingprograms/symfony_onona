<?php
global $isTest;
$pageN = sfContext::getInstance()->getRequest()->getParameter('page', 0);
$h1 = $pageForHim->getName();
// $h1=$catalog->getName() . " " . $catalog->getDescription();
$metaTitle = ($pageForHim->getTitle() == '' ? $h1 : $pageForHim->getTitle()) . ($pageN ? ', страница ' . $pageN : '');
$metaKeyw = ($pageForHim->getKeywords() == '' ? $h1 : $pageForHim->getKeywords()) . ($pageN ? ' страница ' . $pageN : '');
$metaDesc = ($pageN ? 'Страница ' . $pageN . '. ' : '') . $pageForHim->getDescription();
slot('metaTitle', $metaTitle);
slot('metaKeywords', $metaKeyw);
slot('metaDescription', $metaDesc);

slot('catalog-class', '-forHim '.$catalog->getClass());
slot('breadcrumbs', [
  ['text' => 'Каталог', 'link' => '/catalog'],
  ['text' => $h1],
]);
slot('h1', $h1);

include_component(
  'page',
  'blockSliders',
  array(
    'positionpage' => $lowerSlug,
    'isNew' => true,
    'sf_cache_key' => 'slider_catalog_' . $lowerSlug,
  )
);
?>
<article class="intro-block wrapper">
  <div class="intro-block-wrap">
    <div class="intro-block-desc">
      <br>
      <?php echo $pageForHim->getContent(); ?>
    </div>
  </div>
</article>

<div class="wrap-block">
  <div class="container">
    <div class="block-content block-content_catalog">
      <?php include_component(
        'category',
        'catalogfilters',
        array(
          'slug' => $slug,
          'page' => $page,
          'price' => $price,
          'slot' => get_slot('category_slug'),
          'filtersCategory' => ['this_id' => $categoriesIds,] + end($categorys),
          'sortOrder' => $sortOrder,
          'direction' => $direction,
          'filters' => $filters,
          'isCat' => true,
          'filter_service_category' => $filter_service_category,
          'shops' => $shops,
          'isStock'=>$isStock,
          'isNovice'=>$isNovice,
          'isSale' => $isSale,
          'sf_cache_key' => $slug . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
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
</div>
<?php
/*
include_component(
  'category',
  'catalogfilters',
  array(
    'slug' => 'for-him',
    'page' => 1,
    'price' => [0, 10000],
    // 'slot' => get_slot('category_slug'),
    'filtersCategory' => $categoriesIdsStr,
    'sortOrder' => 'ASC',
    //'direction' => $direction,
    //'filters' => $filters,
    'shops' => 0,
    // 'isStock'=>$isStock,
    // 'isNovice'=>$isNovice,
    'sf_cache_key' => $this->slug . '-' . sfContext::getInstance()->getRouting()->getCurrentRouteName()
  )
);
*/
?>
