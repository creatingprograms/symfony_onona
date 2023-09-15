<?php
  $h1='Новые товары';
  slot('metaTitle', "Новые товары | Сеть магазинов «Он и Она»" . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
  slot('metaKeywords', "Новые товары");
  slot('metaDescription', "Предлагаем вам ознакомиться с новинками в секс-шопе «Он и Она». Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.");

  slot('breadcrumbs', [
    ['text' => $h1],
  ]);?>
  <div class="wrap-block">
    <div class="container">
      <div class="block-content block-content_catalog">
        <aside class="sidebar-filter">
          <? include_component(
            'page',
            'catalogSidebar',
            array(
              'isCat' => true,
              'isShowFull' => true,
              'filter_service_category' => $filter_service_category,
              'sf_cache_key' => "catalogSidebar".$isCat.'|'.$isSale.'|'.$filter_service_category,
            )
          ); ?>
        </aside>
        <div class="wrap-catalog-result">
          <?
            $products = $pager->getResults();
            include_component('category', 'listItems',
              array(
                'sf_cache_key' => 'product-list-full-'.$sortOrder.'-'.$direction.sfContext::getInstance()->getRequest()->getParameter('slug').'-'.sfContext::getInstance()->getRequest()->getParameter('page', 1).'|'.$sf_user->isAuthenticated(),
                'products' => $products,
                'bannerType' => 'Каталог',
                'catName' => $h1,
                'catId' => 300000,
              )
            ); ?>
          <?php if ($pager->haveToPaginate()):?>
            <? include_component("noncache", "pagination", array(
              'pager' => $pager,
              'sortingUrl' => '',
              'baselink' => '/newprod',
              'show_more' => false,
              'numbers' => true,
            )); ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
