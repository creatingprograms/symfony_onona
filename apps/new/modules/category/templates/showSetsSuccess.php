<?
  global $isTest;
  $keywordsTemplate='{name} купить цена отзывы доставка';
  $descriptionTemplate='{name}. Продажа по доступным ценам с доставкой в онлайн интим-магазине Он и Она, 18+';
  $h1=$category->getName();
  $baseLink='/category/sets';
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
<?/* if(!$isTest):?>
  <script type="text/javascript">
      (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function () {
          try {
              rrApi.categoryView(<?= end($categorys)['this_id'] ?>);
          } catch (e) {
          }
      })
  </script>
<? endif */?>
<?= $category->getContent() ?>

<?/*<div data-retailrocket-markup-block="5ba3a57697a52530d41bb22d" data-category-id="<?= $category->getId() ?>" data-colorset=""></div>*/?>

<div class="wrap-block">
  <div class="container">
    <form action="/category/sets" method="post" class="form form-sets">
      <div class="sets-type <?=$_POST['sets-list-filtr']=='her' ? 'active' : ''?>">
        <input type="radio" name="sets-list-filtr" id="sets-type-1" class="js-sets-switch" value="her" <?=$_POST['sets-list-filtr']=='her' ? 'checked' : ''?>>
        <label for="sets-type-1" class="sets-type__label">
          <span class="sets-type__img"> <img src="/frontend/images_new/sets_img/2.svg" alt=""></span>
          <span class="sets-type__text h3">Для неё</span>
        </label>
      </div>
      <div class="sets-type <?=$_POST['sets-list-filtr']=='him' ? 'active' : ''?>">
        <input type="radio" name="sets-list-filtr" id="sets-type-2" class="js-sets-switch" value="him" <?=$_POST['sets-list-filtr']=='him' ? 'checked' : ''?>>
        <label for="sets-type-2" class="sets-type__label">
          <span class="sets-type__img"> <img src="/frontend/images_new/sets_img/1.svg" alt=""></span>
          <span class="sets-type__text h3">Для него</span>
        </label>
      </div>
      <div class="sets-type <?=$_POST['sets-list-filtr']=='pairs' ? 'active' : ''?>">
        <input type="radio" name="sets-list-filtr" id="sets-type-3" class="js-sets-switch" value="pairs" <?=$_POST['sets-list-filtr']=='pairs' ? 'checked' : ''?>>
        <label for="sets-type-3" class="sets-type__label">
          <span class="sets-type__img"> <img src="/frontend/images_new/sets_img/3.svg" alt=""></span>
          <span class="sets-type__text h3">Для пар</span>
        </label>
      </div>
    </form>

    <div class="block-content">
      <div class="wrap-catalog-result">
        <?include_component('category', 'listItems',
          array(
            'sf_cache_key' => 'product-list'.$h1.'|'.$sf_user->isAuthenticated(),
            'products' => $products,
            'catName' => $category->getName(),
            'catId' => $category->getId(),
            'class' => 'catalog-result-express',
            'break' => 1000,
            'isSets' => true,
          )
        );?>
      </div>
    </div>
  </div>
</div>
<div class="wrap-block" style="margin-bottom: -30px;">
  <div class="container">
    <h4 class="title-link"><a href="/catalog"><b>Перейти в каталог</b></a></h4>
  </div>
</div>
<?
  include_component( 'category', 'catalogicons',
    array(
      'slug' => 'sex-igrushki-dlja-par',
      'id' => 2,
      'sf_cache_key' => "sex-igrushki-dlja-par-icons",
    )
  );
?>

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
