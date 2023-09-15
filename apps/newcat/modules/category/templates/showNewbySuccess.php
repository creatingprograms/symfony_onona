<?php
/*
echo('<pre>'.print_r([
  // '$query'=>$query,
  '$slug'=>$slug,
  // '$categorys'=>$categorys,

  // 'slug' => $sf_request->getParameter('slug'),
  // 'filtersCategory'=>end($categorys),
  'filters'=>$filters,
  'isStock'=>$isStock,
  '$isNovice'=>$isNovice,
  'sortOrder'=>$sortOrder,
  'direction'=>$direction,
  '$addWhere'=>$addWhere,
], true).'</pre>');
*/
$catName="Для новичков";
$timer = sfTimerManager::getTimer('Templates: Передача переменных в главный шаблон');
$keywordsTemplate='{name} купить цена отзывы доставка';
$descriptionTemplate='{name}. Продажа по доступным ценам с доставкой в онлайн интим-магазине Он и Она, 18+';
if (end($categorys)['title']!=""){
  slot('metaTitle', end($categorys)['title'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
else {
  slot('metaTitle', str_replace("{name}", end($categorys)['this_name'], csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
if (end($categorys)['keywords']!=""){
  slot('metaKeywords', end($categorys)['keywords'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
else {
  slot('metaKeywords', str_replace("{name}", end($categorys)['this_name'], $keywordsTemplate));
}
if (end($categorys)['description']!=""){
  slot('metaDescription', end($categorys)['description'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
else {
  slot('metaDescription', str_replace("{name}", end($categorys)['this_name'], $descriptionTemplate));
}
slot('rightBlockCategory', true);
slot('filtersCategory', end($categorys));
slot('filtersParams', serialize($filters));
slot('filtersPrice', serialize($price));
slot('filtersPage', serialize($page));
slot('filtersSortOrder', serialize($sortOrder));
slot('filtersDirection', serialize($direction));
slot('filtersCountProducts', serialize($filtersCountProducts));
slot('filtersIsStock', serialize($isStock));
if(isset($_GET['page']))
  slot('canonicalSlugCategory', end($categorys)['this_slug'], true);



$timer->addTime();
?>
<div id="categoryShow" class="gtm-category-show" data-list="<?= stripslashes(end($categorys)['this_name'])?>">
    <script type="text/javascript">
        (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function () {
            try {
                rrApi.categoryView(<?= end($categorys)['this_id'] ?>);
            } catch (e) {
            }
        })
    </script>
    <ul class="breadcrumbs">
        <li><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title">Секс-шоп главная</span></a></div></li>

        <li><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="<?= explode("?", $_SERVER['REQUEST_URI'])[0] ?>" itemprop="url"><span itemprop="title">Для новичков</span></a></div></li>
    </ul>


    <h1 class="title">Для новичков</h1>

    <?
    $timer = sfTimerManager::getTimer('Templates: Вывод таблицы с набором сортировок');
    if ($sf_request->getParameter('page', 1) == 1 and $sortOrder == "price" and sizeof($sf_request->getParameter('filters')) == 0) {
      if (substr_count(end($categorys)['this_content'], "{replaceSpoiler}")) {
            echo
              "<div class=\"descriptionCategory\">".
                str_replace("{replaceSpoiler}", '<div class="btnSpoiler" onclick="toggleSpoiler(this);"><div class="Text">Развернуть</div></div><div class="spoiler">', end($categorys)['this_content']);
            ?></div></div>

        <script type="text/javascript">
            function toggleSpoiler(tag) {
                $(tag).parents('.descriptionCategory').find('.spoiler').fadeIn(0);
                $(tag).remove();
            }
        </script>
        <?
      }
      else {
          echo end($categorys)['this_content'];
          ?>
          <br />
          <?
      }
    }?>
    <div class="novice-icons">
      <div class="novice-icon for-pairs js-novice-filter<?=$isNovice=='for_pairs' ? ' active' : ''?>" data-value="for_pairs">Для пар >></div>
      <div class="novice-icon for-her js-novice-filter<?=$isNovice=='for_she' ? ' active' : ''?>" data-value="for_she">Для нее >></div>
      <div class="novice-icon for-him js-novice-filter<?=$isNovice=='for_her' ? ' active' : ''?>" data-value="for_her">Для него >></div>
    </div>
    <?php include_component(
      'category',
      'catalogfilters',
      array(
        'slug' => $sf_request->getParameter('slug'),
        'slot' => get_slot('category_slug'),
        'filtersCategory'=>end($categorys),
        'filters'=>$filters,
        'isStock'=>$isStock,
        'isNovice'=>$isNovice,
        'sortOrder'=>$sortOrder,
        'direction'=>$direction,
        'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
      )
    );
?>
<?php if (is_array($filters)) { ?>
    <div class="blockCountProducts">
        Товаров найдено: <b><?= $productsCount ?></b>
    </div>
<?php } ?>
<?
  $timer->addTime();
?>
<div id="productsShow">
    <?php
    if (count($products) > 0):
        ?>
    <ul class="product item-list">
            <?php
            $index=0;
            foreach ($products as $product['id'] => $product) {
              $gdeslonCodes[]='{ id : "'.$product['id'].'", quantity: 1, price: '.$product['price'].'}';
              $advcakeItems[]=[
                'id' => $product['id'],
                'name' => $product['name'],
                'categoryId' => end($categorys)['this_id'],
                'categoryName' => end($categorys)['this_name'],

                // 'price' => $product['price'],
              ];
                echo
                  '<li'.
                    ' class="gtm-list-item prodTable-' . $product['id'] . (($product['count'] == 0) ? " liProdNonCount" : ""). '"'.
                    ' data-id="'.$product['id'].'"'.
                    ' data-name="'.$product['name'].'"'.
                    ' data-price="'.$product['price'].'"'.
                    ' data-category="'.$product['cat_name'].'"'.
                    ' data-position="'.$index++.'"'.
                    // ' data-debug="'.print_r($product, true).'"'.
                  '>';
                if (!isset($childrensAll[$product['id']])) {
                    $childrensAll[$product['id']] = array();
                }

                include_partial("product/productBooklet", array(
                    'sf_cache_key' => $product['id'],
                    'products' => $products,
                    'product' => $product,
                    'childrens' => $childrensAll[$product['id']],
                    'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
                    'commentsAll' => $commentsAll,
                    'photo' => $photosAll[$product['id']],
                    'photosAll' => $photosAll,
                    'autoLoadPhoto' => false
                        )
                );

                echo "</li>";
            }
            ?>
        </ul>
        <?php
    else:
        ?><div style="padding: 20px;
             ">Нет подходящих результатов</div><?php
         endif;
         ?>
    <div style="clear: both;
         "></div>

</div>
<div id="paginationBoxPage">
    <?
    include_component('category', 'paginatorNew', array('category' => end($categorys), 'sortOrder' => $sortOrder, 'direction' => $direction, "page" => $page, "pagesCount" => $pagesCount));
    ?>
</div>
<?php if (@is_array($_POST['filters']) and @ is_array($filtersCountProducts)): ?>
    <div id="filtersCountProducts" style="display: none;"><?= json_encode($filtersCountProducts) ?></div>
<?php endif; ?>
</div>
<? slot('gdeSlonMode', 'list'); ?>
<? slot('gdeSlonCatId', 'category_id: "'.end($categorys)['this_id'].'",'); ?>
<? slot('gdeSlonCodes', 'products: [ '.implode(', '."\n", $gdeslonCodes).' ],'); ?>

<? slot('advcake', 7); ?>
<? slot('advcake_list', [
  'products' => $advcakeItems,
]);?>
