<?/*
<pre>
  <?= print_r(['ids'=> $ids,'$productNotIn'=> $productNotIn, 'products' => $products], true)?>

</pre>
*/?>
  <div id="productsShow" class="article-products">
    <?php if (count($products) > 0): ?>
      <ul class="product item-list">
              <?php
              $index=0;
              foreach ($products as $product['id'] => $product) {
                $gdeslonCodes[]='{ id : "'. $product['id'].'", quantity: 1, price: '.$product['price'].'}';
                  echo
                    '<li id="prodTable-' . $product['id'] . (($product['count'] == 0) ? " liProdNonCount" : "") . '" '.
                      'data-id="'.$product['id'].'" '.
                      'data-name="'.$product['name'].'" '.
                      'data-category="'.$product['cat_name'].'" '.
                      'data-price="'.$product['price'].'" '.
                      'data-position="'.$index++.'" '.
                      // 'data-debug="'.print_r($product, true).'" '.
                    '>';
                  if (!isset($childrensAll[$product['id']])) {
                      $childrensAll[$product['id']] = array();
                  }

                  include_partial("product/productBooklet", array(
                      'sf_cache_key' => $product['id'],
                      'products' => $products,
                      'product' => $product,
                      'childrens' => $childrensAll[$product['id']],
                      'comment' =>  0,
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
        <br>
    <?php endif; ?>

  </div>
<? slot('gdeSlonCodes', 'products: [ '.implode(', '."\n", $gdeslonCodes).' ],'); ?>
