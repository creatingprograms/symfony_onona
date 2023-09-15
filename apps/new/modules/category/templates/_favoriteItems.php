<div class="wrap-block">
  <div class="container">
    <div class="block-products">
      <?php if (count($products)): ?>
        <?php foreach ($products as $product): ?>
          <? include_component(
            "product",
            "productInList",
            array(
              'sf_cache_key' => 'product'.$product->getId().'fav',
              'product'=>$product,
              'style' => 'fav',
            ));
          ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
