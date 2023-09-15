<?php
global $isTest;
$pageN = sfContext::getInstance()->getRequest()->getParameter('page', 0);
$h1 = $catalog->getMenuName();
// $h1=$catalog->getName() . " " . $catalog->getDescription();
$metaTitle = ($catalog->getTitle() == '' ? $h1 . "  в интернет-магазине «Он и Она» " : $catalog->getTitle()) . ($pageN ? ', страница ' . $pageN : '');
$metaKeyw = ($catalog->getKeywords() == '' ? $h1 : $catalog->getKeywords()) . ($pageN ? ' страница ' . $pageN : '');
$metaDesc = ($pageN ? 'Страница ' . $pageN . '. ' : '') . ($catalog->getMetadescription() == '' ? $catalog->getName() . " " . $catalog->getDescription() : $catalog->getMetadescription());
slot('metaTitle', $metaTitle);
slot('metaKeywords', $metaKeyw);
slot('metaDescription', $metaDesc);
$lowerSlug = mb_strtolower($catalog->get('slug'));

if ($catalog->getCanonical()) slot('canonical', $catalog->getCanonical());
else slot('canonical', '/catalog/' . $lowerSlug);
// slot('catalog-class', '-forHer '.$catalog->getClass());
slot('breadcrumbs', [
  ['text' => 'Каталог', 'link' => '/catalog'],
  ['text' => $h1],
]);
slot('h1', $h1);

?>
<?php if (true) : ?>
  <?
  include_component(
    'page',
    'blockSliders',
    array(
      'positionpage' => 'catalog_' . $lowerSlug . '_1',
      'isNew' => true,
      'sf_cache_key' => 'slider_catalog_' . $lowerSlug . '_1',
    )
  );

  include_component(
    'category',
    'catalogicons',
    array(
      'slug' => $lowerSlug,
      'id' => $catalog->get('id'),
      'type' => 'new',
      'isShowTop20' => !empty($catalog->getTop20List()),
      'sf_cache_key' => $lowerSlug . "-icons",
    )
  );
  ?>
  <?/* if (!$isTest): ?>
    <div data-retailrocket-markup-block="5ba3a57697a52530d41bb22d" data-category-id="<?=end($categorys)['this_id']?>"></div>
  <?php else : ?>
    <article class="intro-block wrapper">
      <div class="intro-block-wrap">
        <hr>
        <p>Блок рекомендуем. На боевом сайте предоставляется retailRocket</p>
        <hr>
      </div>
    </article>

  <?php endif;  ?>
  <?
  include_component(//заменен на блок rR
    'category',
    'sliderItems',
    array(
      'strIds' => $catalog->getBestSalesList(),
      'type' => 'by-ids',
      'blockName' => 'Популярное',
      'sf_cache_key' => $lowerSlug . "-gifts" . '|' . $sf_user->isAuthenticated(),
    )
  );*/
  ?>
  <div class="wrap-block wrap-block_top">
    <div class="container">
      <div data-retailrocket-markup-block="63ef8d5ae801b5b9248bd927" data-category-id="<?= end($categorys)['this_id'] ?>"></div>
    </div>
  </div>
  <?
  include_component(
    'page',
    'blockSliders',
    array(
      'positionpage' => 'catalog_' . $lowerSlug . '_2',
      'isNew' => true,
      'sf_cache_key' => 'slider_catalog_' . $lowerSlug . '_2',
    )
  );
  include_component(
    'category',
    'sliderItems',
    array(
      'strIds' => $catalog->getGiftsList(),
      'type' => 'by-ids',
      'blockName' => 'Новинки',
      'sf_cache_key' => $lowerSlug . "-bestsellers" . '|' . $sf_user->isAuthenticated(),
    )
  );
  include_component(
    'page',
    'blockSliders',
    array(
      'positionpage' => 'catalog_' . $lowerSlug . '_3',
      'isNew' => true,
      'sf_cache_key' => 'slider_catalog_' . $lowerSlug . '_3',
    )
  );
  /*include_component(
    'category',
    'sliderItems',
    array(
      'strIds' => $catalog->getSaleList(),
      'type' => 'by-ids',
      'blockName' => 'Распродажа и акции',
      'sf_cache_key' => $lowerSlug . "-sale" . '|' . $sf_user->isAuthenticated(),
    )
  );*/ ?>
  <div class="wrap-block wrap-block_top">
    <div class="container">
      <div data-retailrocket-markup-block="6461e1d393434a585bb7b777" data-category-id="<?= end($categorys)['this_id'] ?>"></div>
    </div>
  </div>
  <? include_component("page", "bannerNew", array(
    'type' => 'slider_' . $lowerSlug,
    'variant' => "double",
    'sf_cache_key' => $lowerSlug,
  ));
  include_component(
    "page",
    "lastReviews",
    array(
      'sf_cache_key' => $lowerSlug . 'lastReviews' . date('d.m.Y'),
      'catalog' => $lowerSlug,
    )
  ); ?>
  <div class="wrap-block wrap-block_top">
    <div class="container">
      <div data-retailrocket-markup-block="6461e44693434a585bb7b845"></div>
    </div>
  </div>
  <? include_component(
    "category",
    "popularBrands",
    array(
      'type' => $lowerSlug,
      'sf_cache_key' => 'popularBrands_catalog_' . $lowerSlug,
    )
  );
  ?>
  <?
  include_component(
    "page",
    "itemsReviews",
    array(
      'sf_cache_key' => $lowerSlug . 'itemsReviews',
      'catalog' => $lowerSlug,
    )
  );
  include_component(
    "article",
    "top",
    array(
      'sf_cache_key' => $lowerSlug . 'sexopedia',
      'catalog' => $lowerSlug,
    )
  );
  include_component(
    "page",
    "topNews",
    array(
      'sf_cache_key' => $lowerSlug . 'news',
      'catalog' => $lowerSlug,
    )
  );
  ?>
  <?
  $content = str_replace(array("{blockAction}", "{blockRandProd}"), array($blockAction, $blockRandProd), $catalog->get('page'));
  $content = str_replace(["{replaceSpoiler}", "{endSpoiler}"], '', $content);
  $content = preg_replace('/<iframe.+src="(.+)".+<\/iframe>/mU', '<span class="video-container">$0</span>', $content);
  // if(mb_strlen(strip_tags($content))>330)//В новых реалиях работать не будет
  //   $content='<div class="spoiler-category">'.$content.'</div>'.'<div class="show-more"></div>';
  ?>
  <article class="intro-block wrapper">
    <div class="intro-block-wrap">
      <div class="intro-block-desc">
        <br>
        <?= $content ?>
      </div>
    </div>
  </article>

<? else : //Старая реализация
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
  <?
  include_component(
    'category',
    'catalogicons',
    array(
      'slug' => $catalog->get('slug'),
      'id' => $catalog->get('id'),
      // 'type' => 'new',
      'sf_cache_key' => $catalog->get('slug') . "-icons",
    )
  );
  // die(__DIR__.__FILE__.'|'.__LINE__.'<pre>'.print_r([$shops], true).'</pre>');
  ?>
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
            'shops' => $shops,
            // 'isStock'=>$isStock,
            // 'isNovice'=>$isNovice,
            'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
          )
        ); ?>
        <div class="wrap-catalog-result">
          <div data-retailrocket-markup-block="5ba3a57697a52530d41bb22d" data-category-id="<?= end($categorys)['this_id'] ?>"></div>
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
              'sf_cache_key' => 'product-list-full-' . $sortOrder . '-' . $direction . sfContext::getInstance()->getRequest()->getParameter('slug') . '-' . sfContext::getInstance()->getRequest()->getParameter('page', 1),
              'ids' => $ids,
              // 'style' => 'full',
              'bannerType' => 'Каталог',
              'catName' => $h1,
              'catId' => end($categorys)['this_id'],
            )
          ); ?>
          <?php if ($pagesCount > 1) : ?>
            <? include_component("noncache", "paginationCatalog", array(
              'page' => $page,
              'pagesCount' => $pagesCount,
              'baselink' => '/catalog/' . sfContext::getInstance()->getRequest()->getParameter('slug'),
              'show_more' => true,
              'numbers' => true,
            )); ?>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

  <? if ($sf_request->getParameter('page', 1) == 1 and $sortOrder == "rating" and sizeof($sf_request->getParameter('filters')) == 0) : ?>
    <?
    $content = str_replace(array("{blockAction}", "{blockRandProd}"), array($blockAction, $blockRandProd), $catalog->get('page'));
    $content = str_replace(["{replaceSpoiler}", "{endSpoiler}"], '', $content);
    $content = preg_replace('/<iframe.+src="(.+)".+<\/iframe>/mU', '<span class="video-container">$0</span>', $content);
    // if(mb_strlen(strip_tags($content))>330)//В новых реалиях работать не будет
    //   $content='<div class="spoiler-category">'.$content.'</div>'.'<div class="show-more"></div>';
    ?>
    <article class="intro-block wrapper">
      <div class="intro-block-wrap">
        <?/* if($catalog->getImgBottom()) :?>
          <figure class="intro-block-img">
            <img src="/uploads/photo/<?= $catalog->getImgBottom() ?>" width="359px" />
          </figure>
        <? endif */ ?>
        <div class="intro-block-desc  art-detail">
          <?= $content ?>
        </div>
      </div>
    </article>
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
    'products' => get_slot('advcakeItems'), // пример формата внизу страницы
    'category' => [
      'id' => end($categorys)['this_id'],
      'name' => $h1,
    ],
  ]);
  ?>

<?php endif; ?>