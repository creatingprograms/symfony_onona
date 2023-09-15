<?/*<pre><?=print_r(['style'=> $style], true) ?></pre>*/?>
<? if (sizeof($products)) :?>
  <div class="cat-list -items -int gtm-items-list" data-cat-name="<?= $catName ?>" data-cat-id="<?= $catId ?>">
    <!------------------------- page data ----------------------------->
    <? foreach($products as $product) {
      $advcakeItems[]=[
        'id' => $product->getId(),
        'name' => $product->getName(),
        'categoryId' => $product->getGeneralCategory()->getId(),
        'categoryName' => $product->getGeneralCategory()->getName(),
        'price' => $product->getPrice(),
      ];
      if(!isset($style))
        $style=='short';

      include_component("product", "productInList", array(
        'sf_cache_key' => 'product-'.$style.$product->getId().$product->getEndaction(),
        'product'=>$product,
        'style' => $style));
    } ?>
    <!------------------------- page data ----------------------------->
  </div>
  <? slot('advcakeItems', $advcakeItems); ?>
<? endif ?>
