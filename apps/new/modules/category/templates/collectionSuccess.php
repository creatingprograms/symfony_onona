<?php
$h1=$dopinfoProduct[0]->getDopInfo()->getValue();
// print_r($dopinfoProduct[0]->getDopInfoId());die();
$pageMeta== sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? ', страница №'. sfContext::getInstance()->getRequest()->getParameter('page', 1) :'';
slot('metaTitle', ((!empty($collection) && $collection->getTitle()) ? $collection->getTitle() : $h1.', Интим товары и игрушки в Он и Она').$pageMeta );
slot('metaKeywords', ((!empty($collection) && $collection->getKeywords()) ? $collection->getKeywords() : $h1));
slot('metaDescription', ((!empty($collection) && $collection->getDescription()) ? $collection->getDescription() : $h1.'. Он и Она - сеть магазинов для взрослых. Только проверенные товары с сертификатом качества. Быстрая анонимная доставка').$pageMeta);


$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ?
 "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1)
 : "";

if ($collection)
  slot('canonical', '/collection/'.$collection->getSlug() . $canonDop);
else
  slot('canonical', '/collection/'.sfContext::getInstance()->getRequest()->getParameter('slug') . $canonDop);
slot('h1', $h1);

slot('breadcrumbs', [
  ['text' => 'Коллекции', 'link' => '/collections'],
  ['text' => $h1],
]);?>

<div class="wrap-block">
  <div class="container">

    <div class="block-content block-content_catalog">
      <? include_component('category', 'brandsSidebarFilter', array('current' =>$dopinfoProduct[0]->getDopInfoId(), 'filters'=>$filters, 'type'=> 'collections', 'sf_cache_key' => "brandsSidebar".$dopinfoProduct[0]->getDopInfoId())); ?>
      <div class="wrap-catalog-result">
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
              'sf_cache_key' => 'product-list'.$h1.'|'.$sf_user->isAuthenticated(),
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
            // 'show_more' => true,
            'numbers' => true,
          )); ?>
        <?php endif; ?>
        <? if ($collection) :?>
          <article class="intro-block art-detail">
            <?= ($pager->getPage() == 1 ) ? $collection->getContent() : ''  ?>
            <? /*include_component("noncache", "notificationCategory", array(
              'collectionId' => $collection->getId()+100000,
            )); */?>
          </article>
        <? endif?>
      </div>
    </div>


  </div>
</div>
