<?
  global $isTest;
  $keywordsTemplate='{name} купить цена отзывы доставка';
  $descriptionTemplate='{name}. Продажа по доступным ценам с доставкой в онлайн интим-магазине Он и Она, 18+';
  $h1=$category->getName();
  $baseLink='/category/'.($category->getSlug()=='express' ? '': 'express/').$category->getSlug();
  // die('dfgo');
  if(isset($_GET['page'])) slot('canonical', $baseLink);
  slot('metaTitle', str_replace("{name}", $h1, csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
  slot('metaKeywords', str_replace("{name}",  $h1, $keywordsTemplate));
  slot('metaDescription', str_replace("{name}", $h1, $descriptionTemplate));
  //
  slot('breadcrumbs', $breadcrumbs);
  slot('h1', $h1);
  slot('catalog-class', 'page-express');
  $products = $pager->getResults();
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
  <div class="container">
    <div class="block-img-top">
      <img src="/frontend/images_new/work_express/top-img.jpg" alt="">
    </div>
  </div>
</div>
<div class="wrap-block wrap-info-express">
  <div class="container">
    <div class="block-content">
      <p>Дорогие наши клиенты, мы запускаем сервис ЭКСПРЕСС ДОСТАВКИ по Москве. <br>
        Любой товар из категории ЭКСПРЕСС Вы можете получить в течении 2 часов с момента подтверждения заказа.</p>
      <p>
        В вечернее и ночное время с 21:00 до 09:00 заказы принимаются по телефону <a href="tel:+74953749878">8 (495) 374-98-78</a><br>
        Обязательно указывайте в комментарии слово «Экспресс» <br>
        Стоимость доставки 500 рублей. Стоимость доставки в ночное время, рассчитывается индивидуально. Ночные экспресс доставки осуществляются только по Москве и МО.<br>
        Если Ваш заказ из раздела ЭКСПРЕСС превышает сумму 5990 рублей, <br>
        мы доставим его Вам абсолютно БЕСПЛАТНО.</p>
    </div>
  </div>
</div>
<?
  include_component( 'category', 'catalogicons',
    array(
      'slug' => $sf_request->getParameter('slug'),
      'parent_slug' => 'express',
      'add_link' => 'express/',
      'sf_cache_key' => $category->get('slug') . "-icons",
    )
  );
?>
<div data-retailrocket-markup-block="5ba3a57697a52530d41bb22d" data-category-id="<?= $category->getId() ?>" data-colorset=""></div>
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
        <?include_component('category', 'listItems',
          array(
            'sf_cache_key' => 'product-list-'.$sf_user->isAuthenticated().'|'.$h1,
            'products' => $products,
            'catName' => $category->getName(),
            'catId' => $category->getId(),
            'class' => 'catalog-result-express',
            'break' => 100,
          )
        );?>
        <?php if ($pager->haveToPaginate()):?>
          <? include_component("noncache", "pagination", array(
            'pager' => $pager,
            'sortingUrl' => $sortOrder != "" ? "&sortOrder=" . $sortOrder . "&direction=" . $direction : '',
            'baselink' => $baseLink,
            // 'show_more' => true,
            'numbers' => true,
          )); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?
  slot('caltat', 2);
  slot('caltat_cat', $category->getId());
  foreach (get_slot('advcakeItems') as $item) {
    $items[]=$item['id'];
  }
  slot('caltat_cat_params', [
    'products' => $items,
    'page' => sfContext::getInstance()->getRequest()->getParameter('page', 1)
  ]);
  slot('advcake', 3);
  slot('advcake_list', [
    'products' => get_slot('advcakeItems'),
    'category' => [
      'id' => $category->getId(),
      'name' => $category->getName(),
    ],
  ]);
?>
