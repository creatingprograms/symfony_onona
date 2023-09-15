<?php
  $h1='Новые товары';
  slot('metaTitle', "Новые товары | Сеть магазинов «Он и Она»" . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
  slot('metaKeywords', "Новые товары");
  slot('metaDescription', "Предлагаем вам ознакомиться с новинками в секс-шопе «Он и Она». Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.");

  slot('breadcrumbs', [
    ['text' => $h1],
  ]);?>
  <div class="wrapper wrap-cont -clf -sidebar">
    <main class="cont-right">
      <h1><?= $h1 ?></h1>
      <div class="collection-page -clf">
        <? include_component("noncache", "notificationCategory", array(
          'collectionId' => 300000,
        )); ?>
      </div>
      <div class="cat-list-wrap">
        <?
          $products = $pager->getResults();
          include_component('category', 'listItems',
            array(
              'sf_cache_key' => 'product-list'.$h1,
              'products' => $products,
              'catName' => $h1,
              'catId' =>  300000,
            )
          );
        ?>
        <?php if ($pager->haveToPaginate()):?>
          <? include_component("noncache", "pagination", array(
            'pager' => $pager,
            'sortingUrl' => '',
            'baselink' => '/newprod',
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
