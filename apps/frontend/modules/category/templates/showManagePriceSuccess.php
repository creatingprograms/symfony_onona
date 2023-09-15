<?php
  $h1=$category->getH1() != "" ? stripslashes($category->getH1()) : stripslashes($category->getName());
  $canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
  slot('canonical', '/category/'.$category->getSlug() . $canonDop);

  slot('metaTitle', $category->getTitle() == '' ? str_replace("{name}", $h1, csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "") : $category->getTitle() . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "") );
  slot('metaKeywords', $category->getKeywords() == '' ? str_replace("{name}", $h1, csSettings::get('titleCategory')) : $category->getKeywords());
  slot('metaDescription', $category->getDescription() == '' ? str_replace("{name}", $h1, csSettings::get('titleCategory')) : $category->getDescription());

  slot('breadcrumbs', [
    ['text' => $h1],
  ]);

?>
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right">
    <h1><?= $h1 ?></h1>
    <div class="collection-page -clf">
      <?= ($pager->getPage() == 1 ) ? $category->getContent() : ''  ?>
      <? include_component("noncache", "notificationCategory", array(
        'collectionId' => $category->getId(),
      )); ?>
    </div>
    <div class="cat-list-wrap">
      <? include_component('category', 'catalogsorters',
          array(
            'link'=> '/category/'.$category->getSlug(),
            'sortOrder'=>$sortOrder,
            'direction'=>$direction,
            'set'=>1, //набор правил сортировки
          )
      );?>
      <?
        $products = $pager->getResults();
        include_component('category', 'listItems',
          array(
            'sf_cache_key' => 'product-list'.$h1,
            'products' => $products,
            'catName' => $h1,
            'catId' => $category->getId() ,
          )
        );
      ?>
      <?php if ($pager->haveToPaginate()):?>
        <? include_component("noncache", "pagination", array(
          'pager' => $pager,
          'sortingUrl' => $sortOrder != "" ? "&sortOrder=" . $sortOrder . "&direction=" . $direction : '',
          'baselink' => '/category/'.$category->getSlug(),
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
