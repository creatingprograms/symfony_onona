<?
  $h1="Новые товары ".$catalog->getName(). " " . $catalog->getDescription();
  slot('metaTitle', $h1 . " " .(sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница ".sfContext::getInstance()->getRequest()->getParameter('page', 1): "").'. Секс-шопа "Он и Она"' );
  slot('metaKeywords', $h1 );
  slot('metaDescription', $h1 .  ' по доступной цене в каталоге секс-шопа "Он и Она"');
  // $canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
  slot('canonical', '/catalog/'.$catalog->getSlug().'/newprod');
  slot('breadcrumbs', [
    ['text' => $catalog->getName(). " " . $catalog->getDescription(), 'link' => '/catalog/'.$catalog->getSlug()],
    ['text' => $h1],
  ]);
?>
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right">
    <h1><?= $h1 ?></h1>
    <div class="cat-list-wrap">
      <?
        $products = $pager->getResults();
        include_component('category', 'listItems',
          array(
            'sf_cache_key' => 'product-list'.$h1,
            'products' => $products,
            'catName' => $h1,
            'catId' =>  300001,
          )
        );
      ?>
      <?php if ($pager->haveToPaginate()):?>
        <? include_component("noncache", "pagination", array(
          'pager' => $pager,
          'sortingUrl' => '',
          'baselink' => '/catalog/'.$catalog->getSlug().'/newprod',
          'show_more' => true,
          'numbers' => true,
        )); ?>
      <?php endif; ?>
    </div>
  </main>
  <aside class="sidebar">
    <? include_component('page', 'catalogSidebar', array('sf_cache_key' => "catalogSidebar")); ?>
    <? include_component('category', 'brandsSidebar', array('sf_cache_key' => "brandsSidebar".rand(1, 30))); ?>
  </aside>
</div>
