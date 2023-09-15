<?
  global $isTest;
  $keywordsTemplate='{name} купить цена отзывы доставка';
  $descriptionTemplate='{name}. Продажа по доступным ценам с доставкой в онлайн интим-магазине Он и Она, 18+';
  $h1='Товары в магазине '.$shop->getName();
  // die('dfgo');
  if(isset($_GET['page'])) slot('canonical', $baseLink);
  slot('metaTitle', str_replace("{name}", $h1, csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
  slot('metaKeywords', str_replace("{name}",  $h1, $keywordsTemplate));
  slot('metaDescription', str_replace("{name}", $h1, $descriptionTemplate));
  //
  slot('breadcrumbs', [
    ['link' => '/shops/'.$shop->getSlug(), 'text' => $shop->getName()],
    ['text' => $h1],
  ]);
  slot('h1', $h1);
  slot('catalog-class', 'page-express');
  // $products = $pager->getResults();
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
<div class="wrap-block wrap-block-img">
  <?/*<div class="container">
    <div class="block-img-top">
      <img src="/frontend/images_new/work_express/top-img.jpg" alt="">
    </div>
  </div>*/?>
</div>
<div class="wrap-block wrap-info-express">
  <?/*<div class="container">
    <div class="block-content">
      <p>Дорогие наши клиенты,мы запускаем сервис ЭКСПРЕСС ДОСТАВКИ по Москве. <br>
        Любой товар из категории ЭКСПРЕСС Вы можете получить в течении 2 часов с момента подтверждения заказа.</p>
      <p>Основное условие: оформить заказ до 21:00 по мск времени. <br>
        Обязательно указывайте в коментарии слово «Экспресс» <br>
        Стоимость доставки 500 рублей. <br>
        Если Ваш заказ из раздела ЭКСПРЕСС превышает сумму 5990 рублей, <br>
        мы доставим его Вам абсолютно БЕСПЛАТНО.</p>
    </div>
  </div>*/?>
</div>
<?/*
  include_component( 'category', 'catalogicons',
    array(
      'slug' => $sf_request->getParameter('slug'),
      'parent_slug' => 'express',
      'add_link' => 'express/',
      'sf_cache_key' => $category->get('slug') . "-icons",
    )
  );*/
?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <div class="wrap-catalog-result">
        <? include_component('category', 'catalogsorters',
            array(
              'link'=> $baseLink,
              'sortOrder'=>$sortOrder,
              'direction'=>$direction,
              'set'=>1, //набор правил сортировки
            )
        );?>
        <? include_component('category', 'listItems',
          array(
            'sf_cache_key' => $h1.$sortOrder.'-'.$direction.sfContext::getInstance()->getRequest()->getParameter('slug').'-'.sfContext::getInstance()->getRequest()->getParameter('page', 1).'|'.$sf_user->isAuthenticated(),
            'ids' => $ids,
            'bannerType' => 'Каталог',
            'catName' => $h1,
            'class' => 'catalog-result-express',
            'catId' => '500000'.$shop->getId(),
          )
        ); ?>
        <?php if ($pagesCount > 1 ):?>
          <? include_component("noncache", "paginationCatalog", array(
            'page' => $page,
            'pagesCount' => $pagesCount,
            'baselink' => $baselink,
            'sortingUrl' => $sortOrder != "" ? "&sortOrder=" . $sortOrder . "&direction=" . $direction : '',
            'classicLinkPagination' => true,
            // 'show_more' => true,
            'numbers' => true,
          )); ?>
        <?php endif; ?>
        <?/*
        <?php if ($pager->haveToPaginate()):?>
          <? include_component("noncache", "pagination", array(
            'pager' => $pager,
            'sortingUrl' => $sortOrder != "" ? "&sortOrder=" . $sortOrder . "&direction=" . $direction : '',
            'baselink' => $baseLink,
            // 'show_more' => true,
            'numbers' => true,
          )); ?>
        <?php endif; */?>
      </div>
    </div>
  </div>
</div>
