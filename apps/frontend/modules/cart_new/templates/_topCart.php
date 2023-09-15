<?//= '<pre>~~'.print_r($products_old, true) .'||</pre>' ?>
<a href="/cart">
  <span class="-largeTabletHide">В корзине <?=$productsCount ? $productsCount : 'нет'?> товаров</span>
  <svg>
    <use xlink:href="#basketIcon" />
  </svg>
  <span class="header-basket-numb">
    <?= $productsCount ?>
  </span>
</a>
