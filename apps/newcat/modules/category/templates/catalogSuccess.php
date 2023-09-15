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
slot('metaTitle', $catalog->getTitle() == '' ? $catalog->getName() . " " . $catalog->getDescription() . " – Секс шоп Он и Она" : $catalog->getTitle() );
slot('metaKeywords', $catalog->getKeywords() == '' ? $catalog->getName() . " " . $catalog->getDescription() : $catalog->getKeywords());
slot('metaDescription', $catalog->getMetadescription() == '' ? $catalog->getName() . " " . $catalog->getDescription() : $catalog->getMetadescription());
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
slot('canonicalSlugCatalog', $catalog->getSlug() . $canonDop);

slot('rightBlockCategory', true);
slot('filtersCategory', end($categorys));
slot('filtersParams', serialize($filters));
slot('filtersPrice', serialize($price));
slot('filtersPage', serialize($page));
slot('filtersSortOrder', serialize($sortOrder));
slot('filtersDirection', serialize($direction));
slot('filtersCountProducts', serialize($filtersCountProducts));
slot('filtersIsStock', serialize($isStock));
// die('foo '.date('H:i:s'));


// $timer->addTime();
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
        <li><a href="/">Секс-шоп главная</a></li>
        <li><?php echo $catalog->getName() ?> <?php echo $catalog->getDescription() ?></li>
    </ul>
    <h1 class="title"><?php echo $catalog->getName() ?> <?php echo $catalog->getDescription() ?></h1>

    <?php
      include_component(
        'category',
        'catalogicons',
        array(
          'slug' => $sf_request->getParameter('slug'),
          'id' => $catalog->get('id'),
          // 'slug' => $sf_request->getParameter('slug'),
          'sf_cache_key' => $sf_request->getParameter('slug') . "-icons-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
        )
      );
    ?>
    <?
    $timer = sfTimerManager::getTimer('Templates: Вывод таблицы с набором сортировок');
    if ($sf_request->getParameter('page', 1) == 1 and $sortOrder == "actions" and sizeof($sf_request->getParameter('filters')) == 0) {
      if (substr_count($catalog->get('page'), "{replaceSpoiler}")) {

          $content = str_replace(array("{blockAction}", "{blockRandProd}"), array($blockAction, $blockRandProd), $catalog->get('page'));
          echo "<div class=\"descriptionCategory\">" . str_replace(array("{replaceSpoiler}", "{endSpoiler}"), array('<div class="btnSpoiler" onclick="toggleSpoiler(this);">
      			<div class="Text">
      				Развернуть</div>
                                      </div><div class="spoiler">', "</div>"), $content);
          if (substr_count($catalog->get('page'), "{endSpoiler}") == 0) {
              echo "</div>";
          }
          ?></div>

          <script type="text/javascript">
            function toggleSpoiler(tag) {
                $(tag).parent('.descriptionCategory').children('.spoiler').fadeIn(0);
                $(tag).remove();
            }
          </script>
          <?
      } else {
        $content = $catalog->get('page');
        $content = str_replace(array("{blockAction}", "{blockRandProd}"), array($blockAction, $blockRandProd), $content);
        if(mb_strlen(strip_tags($content))>330){

          echo '<div class="spoiler-category">'.$content.'</div>'.'<div class="show-more"></div>';
          ?>
          <script>
            $(document).ready(function(){
              $('.show-more').on('click', function(e){
                e.preventDefault();
                $(this).toggleClass('active');
                $(this).prev().toggleClass('active');
                console.log('.show-more click');
              });
            });
          </script>
          <?
        } else
            echo $content;
      }
    }?>
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
    ); ?>
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
              // $gdeslonCodes[]=$product['id'].':'.$product['price'];
              $gdeslonCodes[]='{ id : "'.$product['id'].'", quantity: 1, price: '.$product['price'].'}';
              $advcakeItems[]=[
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
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
<? //slot('gdeSlonCatId', '&cat_id='.end($categorys)['this_id']); ?>
<? slot('gdeSlonCatId', 'category_id: "'.end($categorys)['this_id'].'",'); ?>
<? slot('gdeSlonCodes', 'products: [ '.implode(', '."\n", $gdeslonCodes).' ],'); ?>
<? //slot('gdeSlonCodes', '&codes='.implode(',', $gdeslonCodes)); ?>
<? slot('gdeSlonMode', 'list'); ?>
<? slot('advcake', 3); ?>
<? slot('advcake_list', [
  'products' => $advcakeItems,
  'category' => [
    'id' => end($categorys)['this_id'],
    // 'dump' => print_r(end($categorys), true),
    'name' =>end($categorys)['this_name'],
  ],
]);?>
