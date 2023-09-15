<? if(sizeof($products)):?>
  <? foreach($products as $product):?>
    <? include_component(
      "product",
      "productInList",
      array(
        'sf_cache_key' => 'product'.$product->getId().'product-of-day',
        'product'=>$product,
        'is_swiper' => false,
        'showChoosen' => false,
        'isShortText' => true,
      )); ?>
    <? break ?>
  <? endforeach ?>
<? endif ?>
