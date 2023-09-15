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
if ($manufacturer)
  slot('canonical', '/manufacturer/'.$manufacturer->getSlug() . $canonDop);
else
  slot('canonical', '/manufacturer/'.sfContext::getInstance()->getRequest()->getParameter('slug') . $canonDop);
slot('h1', $h1);
slot('breadcrumbs', [
  // ['text' => 'Каталог', 'link' => '/catalog'],
  ['text' => 'Бренды', 'link' => '/manufacturers'],
  ['text' => $h1],
]);?>



<div class="wrap-block">
  <div class="container">

    <div class="block-content block-content_catalog">
      <? include_component('category', 'brandsSidebarFilter', array('current' =>$dopinfoProduct[0]->getDopInfoId(), 'filters'=>$filters, 'type'=> 'brands',  'sf_cache_key' => "brandsSidebar".$dopinfoProduct[0]->getDopInfoId())); ?>
      <div class="wrap-catalog-result">

        <? include_component('category', 'catalogsorters',
            array(
              'link'=> '/manufacturer/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
              'sortOrder'=>$sortOrder,
              'direction'=>$direction,
              'set'=>1, //набор правил сортировки
            )
        );
        ?>

        <?
          $products = $pager->getResults();
          include_component('category', 'listItems',
            array(
              'sf_cache_key' => 'product-list'.$h1.'|'.$sf_user->isAuthenticated(),
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
          // 'show_more' => true,
          'numbers' => true,
        )); ?>
      <?php endif; ?>
      <? if ($manufacturer) :?>
        <article class="intro-block art-detail">
          <?= ($pager->getPage() == 1 ) ? $manufacturer->getContent() : ''  ?>
          <?/* include_component("noncache", "notificationCategory", array(
            'collectionId' => $manufacturer->getId()+200000,
          )); */?>
        </article>
      <? endif?>
      </div>
    </div>

  </div>
</div>
