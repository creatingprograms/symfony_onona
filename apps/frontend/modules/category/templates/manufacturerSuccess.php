<?php
$h1=$dopinfoProduct[0]->getDopInfo()->getValue();
$title=$manufacturer->getTitle();
$keywords = $manufacturer->getKeywords();
$description =  $manufacturer->getDescription();
$page = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? ', страница №'. sfContext::getInstance()->getRequest()->getParameter('page', 1) :'';
slot('metaTitle', ($title ? $title.$page : $h1.$page.'. Интим товары и игрушки в Он и Она') );
slot('metaKeywords', $keywords ? $keywords : $h1);
slot('metaDescription', $description ? $description.$page : $h1.$page.'. Он и Она - сеть магазинов для взрослых. Только проверенные товары с сертификатом качества. Быстрая анонимная доставка.');
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
if ($manufacturer){
  slot('canonical', '/manufacturer/'.$manufacturer->getSlug() . $canonDop);
  if(in_array('/manufacturer/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
    [
      // '/manufacturer/baci-lingerie-ssha',
      '/manufacturer/baci-lingerie-white-collection-ssha',
      '/manufacturer/baci-lingerie-black-label-ssha',
      '/manufacturer/envy-ssha',
    ]
  ))
  slot('is-blur', true);
}
else{
  slot('canonical', '/manufacturer/'.sfContext::getInstance()->getRequest()->getParameter('slug') . $canonDop);
  if(in_array('/manufacturer/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
    [
      // '/manufacturer/baci-lingerie-ssha',
      '/manufacturer/baci-lingerie-white-collection-ssha',
      '/manufacturer/baci-lingerie-black-label-ssha',
      '/manufacturer/envy-ssha',
    ]
  ))
  slot('is-blur', true);
}

slot('breadcrumbs', [
  ['text' => 'Производители', 'link' => '/manufacturers'],
  ['text' => $h1],
]);?>
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right">
    <h1><?= $h1 ?></h1>
    <? if ($manufacturer) :?>
      <div class="collection-page -clf">
        <?= ($pager->getPage() == 1 ) ? $manufacturer->getContent() : ''  ?>
        <? include_component("noncache", "notificationCategory", array(
          'collectionId' => $manufacturer->getId()+200000,
        )); ?>
      </div>
    <? endif?>
    <div class="cat-list-wrap">
      <? include_component('category', 'catalogsorters',
          array(
            'link'=> '/manufacturer/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
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
            'catName' => 'Производитель '.$h1,
            'catId' => $manufacturer ? (200000+$manufacturer->getId()) : 0,
          )
        );
      ?>
      <?php if ($pager->haveToPaginate()):?>
        <? include_component("noncache", "pagination", array(
          'pager' => $pager,
          'sortingUrl' => $sortOrder != "" ? "&sortOrder=" . $sortOrder . "&direction=" . $direction : '',
          'baselink' => '/manufacturer/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
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
