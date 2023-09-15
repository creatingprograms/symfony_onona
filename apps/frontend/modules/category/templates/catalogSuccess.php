<?php
global $isTest;
$pageN = sfContext::getInstance()->getRequest()->getParameter('page', 0);
$h1=$catalog->getName() . " " . $catalog->getDescription();
$metaTitle= ($catalog->getTitle() == '' ? $h1."  в интернет-магазине «Он и Она» " : $catalog->getTitle()) . ($pageN ? ', страница '.$pageN : '');
$metaKeyw = ($catalog->getKeywords() == '' ? $h1 : $catalog->getKeywords()).($pageN ? ' страница '.$pageN : '');
$metaDesc = ($pageN ? 'Страница '.$pageN.'. ' : '').($catalog->getMetadescription() == '' ? $catalog->getName() . " " . $catalog->getDescription() : $catalog->getMetadescription());
slot('metaTitle', $metaTitle );
slot('metaKeywords', $metaKeyw);
slot('metaDescription', $metaDesc);

if($catalog->getCanonical()) slot('canonical', $catalog->getCanonical() );
else slot('canonical', '/catalog/'.$catalog->getSlug() );
slot('catalog-class', '-forHer '.$catalog->getClass());
slot('breadcrumbs', [
  ['text' => $h1],
]);
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
<?/* отключено в задаче 154860 - onona.ru - Доработки по сайту (SEO) */?>
<?/* if($catalog->get('slug')=='sex-igrushki-dlja-par'):?>
  <? include_component("page", "blockSliders", array('sf_cache_key' => 'slider_sl_pairs', 'is_onlymoscow' => false, 'positionpage'=>'sl_pairs')); ?>
<? elseif($catalog->get('slug')=='sex-igrushki-dlya-muzhchin'):?>
  <? include_component("page", "blockSliders", array('sf_cache_key' => 'slider_sl_man', 'is_onlymoscow' => false, 'positionpage'=>'sl_man')); ?>
<? elseif($catalog->get('slug')=='sex-igrushki-dlya-zhenschin'):?>
  <? include_component("page", "blockSliders", array('sf_cache_key' => 'slider_sl_woman', 'is_onlymoscow' => false, 'positionpage'=>'sl_woman')); ?>
<? elseif($catalog->getImgTop()) :?>
  <div class="wrapper"><img src="<?= '/uploads/photo/'.$catalog->getImgTop()?>" alt="<?= $catalog->getName() ?>"></div>
<? endif */?>

<? //include_component("page", "blockSliders", array('sf_cache_key' => 'slider', 'is_onlymoscow' => false)); ?>
<?
  include_component( 'category', 'catalogicons',
    array(
      'slug' => $sf_request->getParameter('slug'),
      'id' => $catalog->get('id'),
      'sf_cache_key' => $catalog->get('slug') . "-icons",
    )
  );
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
      // 'isStock'=>$isStock,
      // 'isNovice'=>$isNovice,
      'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
    )
  ); ?>
  <main class="cont-right -filtr">
    <? include_component('category', 'catalogsorters',
        array(
          'link'=> '',//'/catalog/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
          'sortOrder'=>$sortOrder,
          'direction'=>$direction,
          'set'=>3, //У каталога несколько иная логика работы сортировщиков
        )
    );?>
    <div data-retailrocket-markup-block="5ba3a57697a52530d41bb22d" data-category-id="<?= end($categorys)['this_id'] ?>" data-colorset="<?=$catalog->getClass()?>"></div>
    <? include_component('category', 'catalogPopularCats',
        array(
          'catalog'=> sfContext::getInstance()->getRequest()->getParameter('slug'),
          'category' => ''
        )
    );?>

    <?/*class="gtm-category-show" data-list="<?= stripslashes(end($categorys)['this_name'])?>"*/?>
    <?
    include_component('category', 'listItems',
      array(
        'sf_cache_key' => 'product-list-full-'.$sortOrder.'-'.$direction.sfContext::getInstance()->getRequest()->getParameter('slug').'-'.sfContext::getInstance()->getRequest()->getParameter('page', 1),
        'ids' => $ids,
        // 'style' => 'full',
        'catName' => $h1,
        'catId' => end($categorys)['this_id'],
      )
    );
    ?>
    <?php if ($pagesCount > 1 ):?>
      <? include_component("noncache", "paginationCatalog", array(
        'page' => $page,
        'pagesCount' => $pagesCount,
        'baselink' => '/catalog/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
        'show_more' => true,
        'numbers' => true,
      )); ?>
    <?php endif; ?>
  </main>
</div>
<? if ($sf_request->getParameter('page', 1) == 1 and $sortOrder == "rating" and sizeof($sf_request->getParameter('filters')) == 0) : ?>
  <?
    $content = str_replace(array("{blockAction}", "{blockRandProd}"), array($blockAction, $blockRandProd), $catalog->get('page'));
    $content = str_replace(["{replaceSpoiler}", "{endSpoiler}"], '', $content);
    $content = preg_replace('/<iframe.+src="(.+)".+<\/iframe>/mU', '<span class="video-container">$0</span>', $content);
    /*if(mb_strlen(strip_tags($content))>330)//В новых реалиях работать не будет
      $content='<div class="spoiler-category">'.$content.'</div>'.'<div class="show-more"></div>';*/
  ?>
  <article class="intro-block wrapper">
    <div class="intro-block-wrap">
      <? if($catalog->getImgBottom()) :?>
      <figure class="intro-block-img">
        <img src="/uploads/photo/<?= $catalog->getImgBottom() ?>" width="359px" />
      </figure>
      <? endif ?>
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
      'id' => end($categorys)['this_id'],
      'name' => $h1,
    ],
  ]);
?>
