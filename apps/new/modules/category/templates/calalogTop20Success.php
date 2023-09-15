<?
  $h1='Топ 20 товаров в разделе '.$catalog->getMenuName();
  $metaTitle= $h1;
  $metaKeyw = $h1 ;
  $metaDesc = $h1;
  slot('metaTitle', $metaTitle );
  slot('metaKeywords', $metaKeyw);
  slot('metaDescription', $metaDesc);
  $lowerSlug=mb_strtolower($catalog->get('slug'));

  // slot('catalog-class', '-forHer '.$catalog->getClass());
  slot('breadcrumbs', [
    ['text' => 'Каталог', 'link' => '/catalog'],
    ['text' => $catalog->getMenuName(), 'link' => '/catalog/'.$lowerSlug],
    ['text' => $h1, ],
  ]);
  slot('h1', $h1);
  include_component( 'page', 'blockSliders',
    array(
      'positionpage' => 'catalog_'.$lowerSlug.'_1',
      'isNew' => true,
      'sf_cache_key' => 'slider_catalog_'.$lowerSlug.'_1',
    )
  );
  include_component( 'category', 'sliderItems',
    array(
      'strIds' => $catalog->getTop20List(),
      'type' => 'by-ids',
      'no_limit' => true,
      // 'blockName' => 'Распродажа и акции',
      'sf_cache_key' => $lowerSlug . "-top20",
    )
  );

  include_component( 'page', 'blockSliders',
    array(
      'positionpage' => 'catalog_'.$lowerSlug.'_3',
      'isNew' => true,
      'sf_cache_key' => 'slider_catalog_'.$lowerSlug.'_3',
    )
  );
  include_component("category", "popularBrands",
    array(
      'type'=>$lowerSlug,
      'sf_cache_key' => 'popularBrands_catalog_'.$lowerSlug,
    )
  );


?>
