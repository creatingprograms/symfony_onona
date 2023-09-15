<?php
$h1=$dopinfoProduct[0]->getDopInfo()->getValue();
$pageMeta== sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? ', страница №'. sfContext::getInstance()->getRequest()->getParameter('page', 1) :'';
slot('metaTitle', ((!empty($collection) && $collection->getTitle()) ? $collection->getTitle() : $h1.', Интим товары и игрушки в Он и Она').$pageMeta );
slot('metaKeywords', ((!empty($collection) && $collection->getKeywords()) ? $collection->getKeywords() : $h1));
slot('metaDescription', ((!empty($collection) && $collection->getDescription()) ? $collection->getDescription() : $h1.'. Он и Она - сеть магазинов для взрослых. Только проверенные товары с сертификатом качества. Быстрая анонимная доставка').$pageMeta);


$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ?
 "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1)
 : "";

if ($collection){

  slot('canonical', '/collection/'.$collection->getSlug() . $canonDop);

  if(in_array('/collection/'.$collection->getSlug(),
    [
      '/collection/vivid-raw',
    ]
  )) slot('is-blur', true);
}
else{
  if(in_array('/collection/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
    [
      '/collection/vivid-raw',
    ]
  )) slot('is-blur', true);

  slot('canonical', '/collection/'.sfContext::getInstance()->getRequest()->getParameter('slug') . $canonDop);
}

slot('breadcrumbs', [
  ['text' => 'Коллекции', 'link' => '/collections'],
  ['text' => $h1],
]);?>
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right">
    <h1><?= $h1 ?></h1>
    <? if ($collection) :?>
      <div class="collection-page -clf">
        <?= ($pager->getPage() == 1 ) ? $collection->getContent() : ''  ?>
        <? include_component("noncache", "notificationCategory", array(
          'collectionId' => $collection->getId()+100000,
        )); ?>
      </div>
    <? endif?>
    <div class="cat-list-wrap">
      <? include_component('category', 'catalogsorters',
          array(
            'link'=> '/collection/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
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
            'catName' => 'Коллекция '.$h1,
            'catId' => $collection ? (100000+$collection->getId()) : 0,
          )
        );
      ?>
      <?php if ($pager->haveToPaginate()):?>
        <? include_component("noncache", "pagination", array(
          'pager' => $pager,
          'sortingUrl' => $sortOrder != "" ? "&sortOrder=" . $sortOrder . "&direction=" . $direction : '',
          'baselink' => '/collection/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
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
