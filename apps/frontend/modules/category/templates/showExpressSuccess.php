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
  // slot('h1', $h1);
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
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right">
    <h1><?= $h1 ?></h1>
    <div><?= $category->getContent() ?></div>
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
    <? include_component('category', 'catalogsorters',
        array(
          'link'=> $baseLink,
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
          'catName' => $category->getName(),
          'catId' => $category->getId(),
        )
      );
    ?>
    <?php if ($pager->haveToPaginate()):?>
      <? include_component("noncache", "pagination", array(
        'pager' => $pager,
        'sortingUrl' => $sortOrder != "" ? "&sortOrder=" . $sortOrder . "&direction=" . $direction : '',
        'baselink' => $baseLink,
        'show_more' => true,
        'numbers' => true,
      )); ?>
    <?php endif; ?>
  </main>
  <aside class="sidebar">
    <? include_component('article', 'leftPreviewBlock', array('sf_cache_key' => "articlesPreSidebar")); ?>
    <? include_component('category', 'brandsSidebar', array('sf_cache_key' => "brandsSidebar".rand(1, 30))); ?>
  </aside>
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
