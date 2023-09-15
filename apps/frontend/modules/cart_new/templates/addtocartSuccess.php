<div class="popup popup-add" id="addBasket">
  <div class="popup-add-align">
    <div class="popup-add-head">
      Товар добавлен в корзину
    </div>
    <div class="popup-add-wrap">
      <a href="/product/<?= $product->getSlug() ?>" class="popup-add-img">
        <img src="<?= $photo ?>">
      </a>
      <div class="popup-add-desc">
        <div class="popup-add-h">Наименование:</div>
        <div class="popup-add-title"><?= $product->getName() ?></div>
        <div class="popup-add-inf">
          <a href="/product/<?= $product->getSlug() ?>" class="popup-add-img-mobile">
            <img src="<?= $photo ?>">
          </a>
          <div class="popup-add-inf-wrap">
            <div class="popup-add-price">
              <div class="popup-add-h">Стоимость:</div>
              <p><?= number_format( $product->getPrice(), 0, '', '&nbsp;') ?>&nbsp;руб.</p>
            </div>
            <div class="popup-add-bonus">
              <div class="popup-add-h">За этот товар вы получите:</div>
              <p><?= number_format( $bonus, 0, '', '&nbsp;') ?> бонусных рублей</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="popup-add-services">
      <a href="#" class="but -roze js-magnific-close">Продолжить покупки</a>
      <a href="/cart" class="but -black">Оформить заказ</a>
    </div>
  </div>
  <div data-retailrocket-markup-block="5ba3a62997a52530d41bb247" data-product-id="<?= $product->getId() ?>"></div>
  <script>retailrocket.markup.render();</script>
</div>
<? die() ?>
