<a href="/cart" class="<?= $type=='fixed' ? 'mobile-bottom-menu_item' : 'wrap-cart'?>">
  <!-- class .btn-cart_count добоаляетя когда в корзине что-то есть -->
  <div class="btn-cart <?= ($productsCount || $type=='fixed') ? 'btn-cart_count' : ''?>" count="<?=$productsCount?>">
    <svg>
      <use xlink:href="#cart-svg"></use>
    </svg>
  </div>
  <?php if (isset($type) && $type=='fixed'): ?>
    <span>Корзина</span>
  <? else :?>
    <span class="cart-info"><span class="js-cart-info"><?=number_format($totalCost, 0, '.', ' ')?></span> ₽</span>
  <?php endif; ?>
</a>
<?/*//= '<pre>~~'.print_r($products_old, true) .'||</pre>' ?>
