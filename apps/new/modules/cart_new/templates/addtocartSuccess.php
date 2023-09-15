<?
  $rating = round(($product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0) / 2 +0.1);
  if(!$rating || $rating > 5) $rating=5;
?>
<div id="popup-cart" class="white-popup-block block-popup popup-cart">
  <h2 class="block-popup__title">Товар добавлен в корзину</h2>
  <div class="table-basket">
    <div class="basket-item">
      <div class="col">
        <div class="basket-item__img"><img src="<?= $photo ?>" alt=""></div>
      </div>
      <div class="col">
        <div class="row-col">
          <div class="basket-item__info">
            <div class="product-item__bar status-block-item">
              <div class="wrap-rating-star" rating="<?=$rating?>">
                <span class="rating-star <?=$rating > 0 ? 'active' : ''?>"></span>
                <span class="rating-star <?=$rating > 1 ? 'active' : ''?>"></span>
                <span class="rating-star <?=$rating > 2 ? 'active' : ''?>"></span>
                <span class="rating-star <?=$rating > 3 ? 'active' : ''?>"></span>
                <span class="rating-star <?=$rating > 4 ? 'active' : ''?>"></span>
              </div>
              <div class="product-item__reviews"><?=ILTools::getWordForm($product->getVotesCount(), 'отзыв')?></div>
            </div>
            <div class="basket-item__title"><?= $product->getName() ?></div>
            <div class="purchase-card__item -status">
              <? if($product->getCount()>0):?>
                <span>В наличии</span>
              <? endif ?>
              <? if($stock) :?>
                <a href="/adresa-magazinov-on-i-ona-v-moskve-i-mo">в <?= ILTools::getWordForm($stock, 'магазин')?></a>
              <? endif ?>
            </div>

          </div>
          <div class="field-number">
            <span class="number-min js-popup-basket-button" data-dir="-1">
              <svg>
                <use xlink:href="#minus-i"></use>
              </svg>
            </span>
            <input type="number" name="" min="1" id="" value="1" data-id=<?=$product->getId()?> class="js-popup-basket-input">
            <span class="number-plus js-popup-basket-button" data-dir="1">
              <svg>
                <use xlink:href="#plus-i"></use>
              </svg></span>
          </div>
        </div>
        <div class="row-col">
          <div class="wrap-btn">
            <a href="/cart" class="btn-full btn-full btn-full_rad">Перейти к оформлению</a>
            <a href="#" class="btn-full btn-full btn-full_white btn-full_rad js-magnific-close">Продолжить покупки</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="product-item__price" <?=$product->getDiscount() > 0 ? 'old-price="'.ILTools::formatPrice($product->getOldPrice()).' руб"' : ''?>><?=ILTools::formatPrice($product->getPrice())?> ₽</div>
        <div class="purchase-card__bonus">+<span id="popup-bonus-count"><?=ILTools::formatPrice($bonus)?></span> <?=ILTools::getWordForm($bonus, 'бонус', true)?> на счет</div>
      </div>
    </div>
  </div>
  <div data-retailrocket-markup-block="5ba3a62997a52530d41bb247" data-products="<?=$productslist?>"></div>
  <script>
    try {
      retailrocket.markup.render();

    } catch (e) {
    }
  </script>

  <!-- <div class="wrap-sl-popup-cart">
    <div class="swiper-container sl-popup shadow-white shadow-white_active">
      <div class="swiper-wrapper">
        <div class="swiper-slide wrap-sl-popup">
          <div class="sl-popup-item">
            <a href="#" class="sl-popup__img">
              <img src="/frontend/images_new/product/1.jpg" alt="">
            </a>
            <div class="sl-popup__content">
              <a href="#" class="sl-popup__title">Мастурбатор в виде яйца happy eggs в ассортименте и нет</a>
              <div class="product-item__price">590 ₽</div>
              <div class="product-item__cart">
                <svg>
                  <use xlink:href="#cart-svg"></use>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <div class="swiper-slide wrap-sl-popup">
          <div class="sl-popup-item">
            <a href="#" class="sl-popup__img">
              <img src="/frontend/images_new/product/1.jpg" alt="">
            </a>
            <div class="sl-popup__content">
              <a href="#" class="sl-popup__title">Мастурбатор в виде яйца happy eggs в ассортименте и нет</a>
              <div class="product-item__price">590 ₽</div>
              <div class="product-item__cart">
                <svg>
                  <use xlink:href="#cart-svg"></use>
                </svg>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide wrap-sl-popup">
          <div class="sl-popup-item">
            <a href="#" class="sl-popup__img">
              <img src="/frontend/images_new/product/1.jpg" alt="">
            </a>
            <div class="sl-popup__content">
              <a href="#" class="sl-popup__title">Мастурбатор в виде яйца happy eggs в ассортименте и нет</a>
              <div class="product-item__price">590 ₽</div>
              <div class="product-item__cart">
                <svg>
                  <use xlink:href="#cart-svg"></use>
                </svg>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide wrap-sl-popup">
          <div class="sl-popup-item">
            <a href="#" class="sl-popup__img">
              <img src="/frontend/images_new/product/1.jpg" alt="">
            </a>
            <div class="sl-popup__content">
              <a href="#" class="sl-popup__title">Мастурбатор в виде яйца happy eggs в ассортименте и нет</a>
              <div class="product-item__price">590 ₽</div>
              <div class="product-item__cart">
                <svg>
                  <use xlink:href="#cart-svg"></use>
                </svg>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide wrap-sl-popup">
          <div class="sl-popup-item">
            <a href="#" class="sl-popup__img">
              <img src="/frontend/images_new/product/1.jpg" alt="">
            </a>
            <div class="sl-popup__content">
              <a href="" class="sl-popup__title">Мастурбатор в виде яйца happy eggs в ассортименте и нет</a>
              <div class="product-item__price">590 ₽</div>
              <div class="product-item__cart">
                <svg>
                  <use xlink:href="#cart-svg"></use>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="wrap-nav">
        <div class="swiper-button-prev custom-nav">
          <svg>
            <use xlink:href="#arr-sl-l"></use>
          </svg>
        </div>
        <div class="swiper-button-next custom-nav">
          <svg>
            <use xlink:href="#arr-sl-r"></use>
          </svg>
        </div>
      </div>
    </div>
  </div> -->
</div>
<? die() ?>
