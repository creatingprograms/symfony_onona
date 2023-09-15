<?php
  $h1='Лидеры продаж';
  slot('metaTitle', 'Лидеры продаж | Сеть магазинов «Он и Она»'.($page!=1 ? ', страница '.$page : '').'');
  slot('metaKeywords', 'Лидеры продаж');
  slot('metaDescription', 'Предлагаем вам ознакомиться с лидерами продаж в секс-шопе «Он и Она». Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.'.($page!=1 ? ', страница '.$page : ''));

  slot('breadcrumbs', [
    ['text' => $h1],
  ]);?>
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
            'baselink' => '/related',
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
