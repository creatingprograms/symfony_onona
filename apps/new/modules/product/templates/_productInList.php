<? if (!$photo || !file_exists($_SERVER['DOCUMENT_ROOT'] . $photo)) $photo = '/frontend/images/no-image.png'; ?>
<? if ($style == 'fav') : //Избранное 
?>
  <div class="product-item product-item__fav gtm-cat-list-item<?= $isSets ? ' product-item_sets' : '' ?><?= is_array($setParms) ? ' js-status-sets-' . $setParms['class'] : '' ?>" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>" data-price="<?= $product->getPrice() ?>" data-cat="<?= $product->getGeneralCategory() ?>" data-count="<?= $product->getCount() ?>">
    <a href="<?= '/product/' . mb_strtolower(trim($product->getSlug())) ?>" class="product-item__img">
      <?/* if($product->getCount()*$product->getDiscount()>0):?>
        <div class="product-item__status -status-discount">
          <span>-<?=$product->getDiscount()?>%</span>
        </div>
      <? endif ?>

      <? if($product->getIsExpress()) :?>
        <span class="orange">Доставка за час</span>
      <? endif ?>

      <? if($isOfDay) :?>
        <span>Товар дня</span>
      <? endif ?>

      <? if($isExpress) :?>
        <span>
          <svg class="dev-i">
            <use xlink:href="#dev-i"></use>
          </svg>
        </span>
      <? endif ?>

      <? if($isNew) :?>
        <span>NEW</span>
      <? endif ?>

      <?php if (is_array($setParms)): ?>
        <div class="product-item__status -status-sets -status-sets-<?=$setParms['class']?>">
          <span class="status-sets__img"></span><span>Набор для <?=$setParms['name']?></span>
        </div>
      <?php endif; */ ?>

      <?php if (!empty($photo)) : ?>
        <img src="<?= $photo ?>" alt="<?= $product->getName() ?>" original-img="<?= $photo ?>">
      <?php else : ?>
        <img src="/frontend/images/no-image.png" alt="<?= $product->getName() ?>">
      <?php endif; ?>
    </a>
    <div class="product-item__descripion">
      <div class="art"><span>Артикул:</span> №<?= $product->getCode() ?></div>
      <a class="link" href="/product/<?= mb_strtolower(trim($product->getSlug())) ?>"><?= $product->getName() ?></a>
      <? include_component("product", "productStock", array('product' => $product,)); ?>
    </div>
    <div class="product-item__price_block">
      <div class="product-item__price" <?= $product->getDiscount() ? 'old-price="' . number_format($product->getOldPrice(), 0, ',', ' ') . ' ₽"' : '' ?>>
        <?= number_format($product->getPrice(), 0, ',', ' ') ?> ₽
      </div>
      <div class="purchase-card__bonus icon-bonus">+ <?= $bonus ?><span><?= ILTools::getWordForm($bonus, 'бонус', true) ?> на счет <a href="#bonus-info" class="question js-popup-form">?</a></span></div>
    </div>
    <div class="product-item__buy_block">
      <?php if (1 * $product->getCount() > 0) : ?>
        <a href="#popup-cart" class="btn-full_rad btn-full js-basket-add gtm-detail-add-to-basket" data-id="<?= $product->getId() ?>">В корзину</a>
      <?php else : ?>
        <a href="#popup-notify" class="btn-full_rad btn-full btn-full_white btn-full_small js-form-open" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>">Уведомить</a>
      <?php endif; ?>
    </div>
    <div class="product-item__del_block js-fav-remove" data-id="<?= $product->getId() ?>">
      <svg>
        <use xlink:href="#basket-del-i"></use>
      </svg>
    </div>
  </div>
<? endif ?>
<? if ($style == 'default') : //Обычный
?>
  <div class="product-item sl-item gtm-cat-list-item<?= ILTools::isBlured($product) ? ' blured' : '' ?><?= $isSets ? ' product-item_sets' : '' ?><?= is_array($setParms) ? ' js-status-sets-' . $setParms['class'] : '' ?>" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>" data-price="<?= $product->getPrice() ?>" data-cat="<?= $product->getGeneralCategory() ?>" data-count="<?= $product->getCount() ?>" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="sku" content="<?= $product->getCode() ?>" />
    <meta itemprop="name" content="<?= $product->getName() ?>" />
    <?php $productDesc = $product->getContent(); ?>
    <meta itemprop="description" content="<?= $productDesc ? mb_substr(strip_tags($productDesc), 0, 150) . '...' : '' ?>" />
    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer" style="display: none;">
      <link itemprop="availability" href="http://schema.org/<?= 1 * $product->getCount() > 0 ? 'InStock' : 'PreOrder' ?>" />

      <span itemprop="price" content="<?= $product->getPrice() ?>" class=" mfp-hide">
        <?= number_format($product->getPrice(), 0, '', ' ') ?> <?/*₽*/ ?>руб.
        <meta itemprop="priceValidUntil" content="<?= date('c', 24 * 60 * 60 + time()) ?>" />
        <meta itemprop="priceCurrency" content="RUB" />
        <link itemprop="url" href="https://onona.ru/product/<?= $product->getSlug() ?>" />
        <?/*<meta itemprop="url" content="https://onona.ru/product/<?=$product->getSlug()?>" />*/ ?>
      </span>
    </span>
    <div class="product-item__status">
      <? if ($product->getIsExpress()) : ?>
        <span class="orange">Доставка за час</span>
      <? endif ?>
      <? if ($isOfDay) : ?>
        <span>Товар дня</span>
      <? endif ?>
      <? if ($isExpress) : ?>
        <span>
          <svg class="dev-i">
            <use xlink:href="#dev-i"></use>
          </svg>
        </span>
      <? endif ?>
      <? if ($isNew) : ?>
        <span>NEW</span>
      <? endif ?>

      <? if ($product->getPrice() > 3000) : ?>
        <span>Доступно в кредит</span>
      <? endif ?>
    </div>
    <? if ($showChoosen) : ?>
      <div class="btn-chosen js-add-favorite<?= $isChoosen ? ' active' : '' ?>" data-id="<?= $product->getId() ?>">
        <svg>
          <use xlink:href="#chosen-svg"></use>
        </svg>
      </div>
    <? endif ?>
    <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/' . $product->getSlug() ?>" class="product-item__img">
      <? if ($product->getCount() * $product->getDiscount() > 0) : ?>
        <div class="product-item__status -status-discount">
          <span>-<?= $product->getDiscount() ?>%</span>
        </div>
      <? endif ?>
      <?php if (is_array($setParms)) : ?>
        <div class="product-item__status -status-sets -status-sets-<?= $setParms['class'] ?>">
          <span class="status-sets__img"></span><span>Набор для <?= $setParms['name'] ?></span>
        </div>
      <?php endif; ?>
      <? if ($product->getCount() < 1) : ?>
        <div class="product-item__status -status-order">
          <span>На заказ</span>
        </div>
      <? endif ?>
      <img src="<?= $photo ?>" alt="<?= $product->getName() ?>">
    </a>
    <div class="product-item__content-sl">
      <div class="product-item__bar">
        <div class="wrap-rating-star" rating="<?= $numStars ?>">
          <span class="rating-star"></span>
          <span class="rating-star"></span>
          <span class="rating-star"></span>
          <span class="rating-star"></span>
          <span class="rating-star"></span>
        </div>
        <? if ($reviewText) : ?>
          <div class="product-item__reviews"><span><?/*=$product->getVotesCount()?></span> */ ?><?= $reviewText ?></div>
        <? endif ?>
      </div>
      <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/' . $product->getSlug() ?>" class="product-item__text sl-text">
        <?= $isShortText ? mb_substr($product->getName(), 0, 37) . (mb_strlen($product->getName()) > 37 ? '...' : '') : $product->getName() ?>
      </a>
      <div class="product-item__bar-bottom">
        <div class="product-item__price" <?= $product->getDiscount() ? 'old-price="' . number_format($product->getOldPrice(), 0, ',', ' ') . ' ₽"' : '' ?>>
          <?= number_format($product->getPrice(), 0, ',', ' ') ?> ₽
        </div>
        <div class="product-item__cart js-basket-add <?= 1 * $product->getCount() > 0 ? 'gtm-list-add-to-basket' : 'gtm-list-add-to-basket' ?>" data-id="<?= $product->getId() ?>">
          <svg>
            <use xlink:href="#cart-svg"></use>
          </svg>
        </div>
      </div>
    </div>
  </div>
<? endif ?>
<? if ($style == 'picture-slide-hover') : //Слайдер по наведению
?>
  <div class="product-item sl-item slider-inside -mod-cart gtm-cat-list-item<?= ILTools::isBlured($product) ? ' blured' : '' ?><?= $isSets ? ' product-item_sets' : '' ?><?= is_array($setParms) ? ' js-status-sets-' . $setParms['class'] : '' ?>" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>" data-price="<?= $product->getPrice() ?>" data-cat="<?= $product->getGeneralCategory() ?>" data-count="<?= $product->getCount() ?>" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="sku" content="<?= $product->getCode() ?>" />
    <meta itemprop="name" content="<?= $product->getName() ?>" />
    <?php $productDesc = $product->getContent(); ?>
    <meta itemprop="description" content="<?= $productDesc ? mb_substr(strip_tags($productDesc), 0, 150) . '...' : '' ?>" />
    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer" style="display: none;">
      <link itemprop="availability" href="http://schema.org/<?= 1 * $product->getCount() > 0 ? 'InStock' : 'PreOrder' ?>" />

      <span itemprop="price" content="<?= $product->getPrice() ?>" class=" mfp-hide">
        <?= number_format($product->getPrice(), 0, '', ' ') ?> <?/*₽*/ ?>руб.
        <meta itemprop="priceValidUntil" content="<?= date('c', 24 * 60 * 60 + time()) ?>" />
        <meta itemprop="priceCurrency" content="RUB" />
        <link itemprop="url" href="https://onona.ru/product/<?= $product->getSlug() ?>" />
        <?/*<meta itemprop="url" content="https://onona.ru/product/<?=$product->getSlug()?>" />*/ ?>
      </span>
    </span>
    <noindex>
      <div class="product-item__status">
        <? if ($product->getPrice() > csSettings::get('TINKOFF_MIN_SUM') /*&& !$product->getDiscount()*/) : ?>
          <span class="tinkoff_plate"><span>Доступно </span>в кредит</span>
        <? endif ?>
        <? if ($product->getIsExpress()) : ?>
          <span class="orange">Доставка за час</span>
        <? endif ?>
        <? if ($isOfDay) : ?>
          <span>Товар дня</span>
        <? endif ?>
        <? if ($isExpress) : ?>
          <span>
            <svg class="dev-i">
              <use xlink:href="#dev-i"></use>
            </svg>
          </span>
        <? endif ?>
        <? if ($isNew) : ?>
          <span>NEW</span>
        <? endif ?>

      </div>
    </noindex>
    <? if ($showChoosen) : ?>
      <a <?= $authUser ? 'class="btn-chosen js-add-choosen' . ($isChoosen ? ' active' : '') . '" data-id="' . $product->getId() . '"' : 'class="btn-chosen js-popup-form" href="#popup-login"' ?>>
        <!-- <div class="btn-chosen js-add-favorite<?= $isChoosen ? ' active' : '' ?>" data-id="<?= $product->getId() ?>"> -->
        <svg>
          <use xlink:href="#chosen-svg"></use>
        </svg>
      </a>
    <? endif ?>
    <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/' . $product->getSlug() ?>" class="product-item__img">
      <? if ($product->getCount() * $product->getDiscount() > 0) : ?>
        <div class="product-item__status -status-discount">
          <span>-<?= $product->getDiscount() ?>%</span>
        </div>
      <? endif ?>
      <?php if (is_array($setParms)) : ?>
        <div class="product-item__status -status-sets -status-sets-<?= $setParms['class'] ?>">
          <span class="status-sets__img"></span><span>Набор для <?= $setParms['name'] ?></span>
        </div>
      <?php endif; ?>
      <? if ($product->getCount() < 1) : ?>
        <div class="product-item__status -status-order">
          <span>На заказ</span>
        </div>
      <? endif ?>
      <?php if (!empty($photos)) : ?>
        <img src="<?= $photos[0] ?>" alt="<?= str_replace('"', '', $product->getName()) ?>" original-img="<?= $photos[0] ?>" itemprop="image">
      <?php else : ?>
        <img src="/frontend/images/no-image.png" alt="<?= $product->getName() ?>">
      <?php endif; ?>
      <?php /*if (!empty($photos)): ?>
        <div class="swiper-container swiper-product-<?=$product->getId()?>">
          <div class="swiper-wrapper">
            <?php foreach ($photos as $photo): ?>
              <? if(!$photo || !file_exists($_SERVER['DOCUMENT_ROOT'].$photo)) continue ?>
              <div class="swiper-slide">
                <img src="<?=$photo?>" alt="<?= $product->getName() ?>">
              </div>
            <?php endforeach; ?>
          </div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      <?php else :?>
        <img src="/frontend/images/no-image.png" alt="<?= $product->getName() ?>">
      <?php endif; */ ?>
    </a>
    <div class="product-item__content-sl">
      <?php /*
        <div class="product-item__bar">
          <div class="wrap-rating-star" rating="<?= $numStars ?>">
            <span class="rating-star"></span>
            <span class="rating-star"></span>
            <span class="rating-star"></span>
            <span class="rating-star"></span>
            <span class="rating-star"></span>
          </div>
          <? if($reviewText) :?>
            <div class="product-item__reviews">
              <span>
                <?//=$product->getVotesCount()?>
              </span>
              <?=$reviewText?></div>
          <? endif ?>
        </div>
      */ ?>
      <div class="product-item__sub-img">
        <? $i = 1; ?>
        <?php if (!empty($photos)) : ?>
          <?php foreach ($photos as $photo) : ?>
            <? if (!$photo || !file_exists($_SERVER['DOCUMENT_ROOT'] . $photo)) continue ?>
            <div class="-item" full-img="<?= $photo ?>">
              <img src="<?= $photo ?>" alt="">
            </div>
            <? if (3 < $i++) break; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/' . $product->getSlug() ?>" class="product-item__text sl-text">
        <?= $isShortText ? mb_substr($product->getName(), 0, 37) . (mb_strlen($product->getName()) > 37 ? '...' : '') : $product->getName() ?>
      </a>
      <?php if (!empty($props)) : ?>
        <div class="product-item__details">
          <noindex>
            <ul>
              <?php foreach ($props as $name => $prop) : ?>
                <li><?= $name ?> <?= $prop ?></li>
              <?php endforeach; ?>
            </ul>
          </noindex>
        </div>
      <?php endif; ?>
      <div class="product-item__bar-bottom">
        <div class="product-item__price" <?= $product->getDiscount() ? 'old-price="' . number_format($product->getOldPrice(), 0, ',', ' ') . ' ₽"' : '' ?>>
          <?= number_format($product->getPrice(), 0, ',', ' ') ?> ₽
        </div>
        <?php if (1 * $product->getCount() > 0) : ?>
          <div class="product-item__cart js-basket-add <?= 1 * $product->getCount() > 0 ? 'gtm-list-add-to-basket' : 'gtm-list-add-to-basket' ?>" data-id="<?= $product->getId() ?>">
            <svg>
              <use xlink:href="#cart-svg"></use>
            </svg>
          </div>
        <?php else : ?>
          <a href="#popup-notify" class="product-item__cart product-item__not-stroke js-form-open" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>">
            <svg>
              <use xlink:href="#letter-svg"></use>
            </svg>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
<? endif ?>
<? if ($style == 'picture-slide') : //Слайдер картинок
?>
  <div class="product-item sl-item slider-inside gtm-cat-list-item<?= ILTools::isBlured($product) ? ' blured' : '' ?><?= $isSets ? ' product-item_sets' : '' ?><?= is_array($setParms) ? ' js-status-sets-' . $setParms['class'] : '' ?>" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>" data-price="<?= $product->getPrice() ?>" data-cat="<?= $product->getGeneralCategory() ?>" data-count="<?= $product->getCount() ?>" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="sku" content="<?= $product->getCode() ?>" />
    <meta itemprop="name" content="<?= $product->getName() ?>" />
    <?php $productDesc = $product->getContent(); ?>
    <meta itemprop="description" content="<?= $productDesc ? mb_substr(strip_tags($productDesc), 0, 150) . '...' : '' ?>" />
    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer" style="display: none;">
      <link itemprop="availability" href="http://schema.org/<?= 1 * $product->getCount() > 0 ? 'InStock' : 'PreOrder' ?>" />

      <span itemprop="price" content="<?= $product->getPrice() ?>" class=" mfp-hide">
        <?= number_format($product->getPrice(), 0, '', ' ') ?> <?/*₽*/ ?>руб.
        <meta itemprop="priceValidUntil" content="<?= date('c', 24 * 60 * 60 + time()) ?>" />
        <meta itemprop="priceCurrency" content="RUB" />
        <link itemprop="url" href="https://onona.ru/product/<?= $product->getSlug() ?>" />
        <?/*<meta itemprop="url" content="https://onona.ru/product/<?=$product->getSlug()?>" />*/ ?>
      </span>
    </span>
    <div class="product-item__status">
      <? if ($product->getIsExpress()) : ?>
        <span class="orange">Доставка за час</span>
      <? endif ?>
      <? if ($isOfDay) : ?>
        <span>Товар дня</span>
      <? endif ?>
      <? if ($isExpress) : ?>
        <span>
          <svg class="dev-i">
            <use xlink:href="#dev-i"></use>
          </svg>
        </span>
      <? endif ?>
      <? if ($isNew) : ?>
        <span>NEW</span>
      <? endif ?>
    </div>
    <? if ($showChoosen) : ?>
      <div class="btn-chosen js-add-favorite<?= $isChoosen ? ' active' : '' ?>" data-id="<?= $product->getId() ?>">
        <svg>
          <use xlink:href="#chosen-svg"></use>
        </svg>
      </div>
    <? endif ?>
    <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/' . $product->getSlug() ?>" class="product-item__img">
      <? if ($product->getCount() * $product->getDiscount() > 0) : ?>
        <div class="product-item__status -status-discount">
          <span>-<?= $product->getDiscount() ?>%</span>
        </div>
      <? endif ?>
      <?php if (is_array($setParms)) : ?>
        <div class="product-item__status -status-sets -status-sets-<?= $setParms['class'] ?>">
          <span class="status-sets__img"></span><span>Набор для <?= $setParms['name'] ?></span>
        </div>
      <?php endif; ?>
      <? if ($product->getCount() < 1) : ?>
        <div class="product-item__status -status-order">
          <span>На заказ</span>
        </div>
      <? endif ?>
      <?php if (!empty($photos)) : ?>
        <div class="swiper-container swiper-product-<?= $product->getId() ?>">
          <div class="swiper-wrapper">
            <?php foreach ($photos as $photo) : ?>
              <? if (!$photo || !file_exists($_SERVER['DOCUMENT_ROOT'] . $photo)) continue ?>
              <div class="swiper-slide">
                <img src="<?= $photo ?>" alt="<?= $product->getName() ?>">
              </div>
            <?php endforeach; ?>
          </div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      <?php else : ?>
        <img src="/frontend/images/no-image.png" alt="<?= $product->getName() ?>">
      <?php endif; ?>
    </a>
    <div class="product-item__content-sl">
      <div class="product-item__bar">
        <div class="wrap-rating-star" rating="<?= $numStars ?>">
          <span class="rating-star"></span>
          <span class="rating-star"></span>
          <span class="rating-star"></span>
          <span class="rating-star"></span>
          <span class="rating-star"></span>
        </div>
        <? if ($reviewText) : ?>
          <div class="product-item__reviews"><span><?/*=$product->getVotesCount()?></span> */ ?><?= $reviewText ?></div>
        <? endif ?>
      </div>
      <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/' . $product->getSlug() ?>" class="product-item__text sl-text">
        <?= $isShortText ? mb_substr($product->getName(), 0, 37) . (mb_strlen($product->getName()) > 37 ? '...' : '') : $product->getName() ?>
      </a>
      <div class="product-item__bar-bottom">
        <div class="product-item__price" <?= $product->getDiscount() ? 'old-price="' . number_format($product->getOldPrice(), 0, ',', ' ') . ' ₽"' : '' ?>>
          <?= number_format($product->getPrice(), 0, ',', ' ') ?> ₽
        </div>
        <div class="product-item__cart js-basket-add <?= 1 * $product->getCount() > 0 ? 'gtm-list-add-to-basket' : 'gtm-list-add-to-basket' ?>" data-id="<?= $product->getId() ?>">
          <svg>
            <use xlink:href="#cart-svg"></use>
          </svg>
        </div>
      </div>
    </div>
  </div>
<? endif ?>
<? if ($style == 'full') : //Не используется
?>
  <div class="cat-list-item gtm-cat-list-item" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>" data-price="<?= $product->getPrice() ?>" data-cat="<?= $product->getGeneralCategory() ?>" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="sku" content="<?= $product->getCode() ?>" />
    <a href="/product/<?= $product->getSlug() ?>" class="cat-list-img">
      <?= $product->getCount() > 0 ? '' : '<span class="-byOrder"></span>' ?>
      <?= $product->getCount() * $product->getDiscount() > 0 ? '<span class="cat-list-action"> ' . $product->getDiscount() . '% </span>' : '' ?>
      <?= $product->getBonuspay() ?  '<span class="-managePrice"></span>' : '' ?>
      <img src="<?= $photo ?>" alt="">
    </a>
    <div class="cat-list-desc -normal">
      <div class="rating -normal">
        <? for ($i = 0; $i < 5; $i++) : ?>
          <div class="rating-item<?= $i < $numStars ? ' -isActive' : "" ?>">
            <svg>
              <use xlink:href="#rateItemIcon"></use>
            </svg>
          </div>
        <? endfor ?>
        <div class="rating-numb"><?= $product->getVotesCount() ?></div>
      </div>
      <div class="cat-list-title -normal">
        <a href="/product/<?= $product->getSlug() ?>">
          <?= $product->getName() ?>
        </a>
      </div>
      <div class="cat-list-price -smallLH">
        <?= $product->getPrice() ?>
        <span class="-rub">a</span>
        <span class="-plus">+<?= $bonus ?>&nbsp;б.</span>
      </div>
      <div class="cat-list-but">
        <a href="#" class="-basket js-basket-add gtm-list-add-to-basket" data-id="<?= $product->getId() ?>">
          <svg>
            <use xlink:href="#basketIcon" />
          </svg>
        </a>
        <?/*<a href="#" class="-compare js-compare-add" data-id="<?= $product->getId() ?>">
          <svg>
            <use xlink:href="#compareIcon" />
          </svg>
        </a>
        <a href="#" class="-like js-like-add" data-id="<?= $product->getId() ?>">
          <svg>
            <use xlink:href="#likeIcon" />
          </svg>
        </a>*/ ?>
      </div>
    </div>
  </div>
<? elseif (false) : //карточка везде
?>
  <div class="cat-list-item gtm-cat-list-item" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>" data-price="<?= $product->getPrice() ?>" data-cat="<?= $product->getGeneralCategory() ?>" data-count="<?= $product->getCount() ?>" itemscope itemtype="http://schema.org/Product">
    <meta itemprop="sku" content="<?= $product->getCode() ?>" />
    <meta itemprop="name" content="<?= $product->getName() ?>" />
    <?php $productDesc = $product->getContent(); ?>
    <meta itemprop="description" content="<?= $productDesc ? mb_substr(strip_tags($productDesc), 0, 150) . '...' : '' ?>" />

    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer" class=" mfp-hide">
      <link itemprop="availability" href="http://schema.org/<?= 1 * $product->getCount() > 0 ? 'InStock' : 'PreOrder' ?>" />

      <span itemprop="price" content="<?= $product->getPrice() ?>" class=" mfp-hide">
        <?= number_format($product->getPrice(), 0, '', ' ') ?> <?/*₽*/ ?>руб.
        <meta itemprop="priceValidUntil" content="<?= date('c', 24 * 60 * 60 + time()) ?>" />
        <meta itemprop="priceCurrency" content="RUB" />
        <meta itemprop="url" content="https://onona.ru/product/<?= $product->getSlug() ?>" />
      </span>
    </span>

    <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/' . $product->getSlug() ?>" class="cat-list-img">
      <?= 1 * $product->getCount() > 0 ? '' : '<span class="-byOrder"></span>' ?>
      <?= $product->getCount() * $product->getDiscount() > 0 ? '<span class="cat-list-action"> ' . $product->getDiscount() . '% </span>' : '' ?>
      <? /*= $product->getBonuspay() ?  '<span class="-managePrice"></span>' : '' */ ?>
      <? if ($is_swiper) : ?>
        <img data-src="<?= $photo ?>" alt="<?= $product->getName() ?>" class="swiper-lazy" src="<?= $photo ?>" loading="lazy">
      <? else : ?>
        <img src="<?= $photo ?>" alt="<?= $product->getName() ?>">
      <? endif ?>
      <? /*if ($product->getEndaction() != ""): ?>
        <?php
          $step = array("1 сутки" => "1", "2 суток" => "2", "3 суток" => "3", "4 суток" => "4", "5 суток" => "5");
          if ($product->getStep() != "") {
              //echo $product->getEndaction() - time() + 24 * 60 * 60;
              if ((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) > $step[$product->getStep()] * 24 * 60 * 60) {
                  $dateEnd = (strtotime($product->getEndaction()) - floor((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) / ($step[$product->getStep()] * 24 * 60 * 60)) * $step[$product->getStep()] * 24 * 60 * 60);
              } else {
                  $dateEnd = strtotime($product->getEndaction());
              }
          } else {
              $dateEnd = strtotime($product->getEndaction());
          }
        ?>
        <span class="countdown js-countdown" data-until="<?= date('Y/m/d 23:59:59',$dateEnd) ?>"></span>
      <? endif */ ?>
    </a>
    <div class="cat-list-desc">
      <div class="rating">
        <? for ($i = 0; $i < 5; $i++) : ?>
          <div class="rating-item<?= $i < $numStars ? ' -isActive' : "" ?>">
            <svg>
              <use xlink:href="#rateItemIcon" />
            </svg>
          </div>
        <? endfor ?>
      </div>
      <div class="cat-list-title -normal">
        <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/' . $product->getSlug() ?>"><?= $product->getName() ?></a>
      </div>
      <div class="cat-list-bal">
        +<?= $bonus ?> бонусов
      </div>
      <div class="cat-list-price<?= ($product->getDiscount()/* || $product->getBonuspay()*/) ? ' -oldPrice' : '' ?>">
        <?= $product->getDiscount() ? '<span>' . $product->getOldPrice() . ' руб.</span>' : '' ?>
        <?/*= (!$product->getDiscount() && $product->getBonuspay()) ? '<span>'.$product->getPrice().' руб.</span>' : ''?>*/ ?>
        <?= /*(!$product->getDiscount() && $product->getBonuspay()) ? round($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100) :*/ $product->getPrice() ?>&nbsp;руб.
      </div>

      <a href="#" class="but js-basket-add <?= 1 * $product->getCount() > 0 ? 'gtm-list-add-to-basket' : 'disabled' ?>" data-id="<?= $product->getId() ?>"> В корзину </a>
    </div>
  </div>
<? endif ?>
<? slot('need_form_notify', true); ?>