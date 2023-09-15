<? if(!$photo || !file_exists($_SERVER['DOCUMENT_ROOT'].$photo)) $photo='/frontend/images/no-image.png';?>
<?if($style=='full') : //Не используется?>
  <div class="cat-list-item gtm-cat-list-item"
    data-id="<?= $product->getId() ?>"
    data-name="<?= $product->getName() ?>"
    data-price="<?= $product->getPrice() ?>"
    data-cat="<?= $product->getGeneralCategory() ?>"
    itemscope
    itemtype="http://schema.org/Product"
  >
  <meta itemprop="sku" content="<?= $product->getCode() ?>" />
    <a href="/product/<?= $product->getSlug() ?>" class="cat-list-img">
      <?= $product->getCount()>0 ? '' : '<span class="-byOrder"></span>' ?>
      <?= $product->getCount()*$product->getDiscount()>0 ? '<span class="cat-list-action"> '.$product->getDiscount().'% </span>' : '' ?>
      <?= $product->getBonuspay() ?  '<span class="-managePrice"></span>' : '' ?>
      <img src="<?= $photo ?>" alt="" <?=$needBlured ? 'class="blured"' : ''?>>
    </a>
    <div class="cat-list-desc -normal">
      <div class="rating -normal">
        <? for ($i=0; $i<5; $i++) :?>
          <div class="rating-item<?= $i<$numStars ? ' -isActive' : ""?>">
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
        </a>*/?>
      </div>
    </div>
  </div>
<? else : //карточка везде?>
  <div class="cat-list-item gtm-cat-list-item"
    data-id="<?= $product->getId() ?>"
    data-name="<?= $product->getName() ?>"
    data-price="<?= $product->getPrice() ?>"
    data-cat="<?= $product->getGeneralCategory() ?>"
    data-count="<?= $product->getCount() ?>"
    itemscope
    itemtype="http://schema.org/Product"
  >
    <meta itemprop="sku" content="<?= $product->getCode() ?>" />
    <meta itemprop="name" content="<?= $product->getName() ?>" />
    <?php $productDesc = $product->getContent(); ?>
    <meta itemprop="description" content="<?= $productDesc ? mb_substr(strip_tags($productDesc), 0, 150) . '...' : '' ?>" />

    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer" class=" mfp-hide">
      <link itemprop="availability" href="http://schema.org/<?=1*$product->getCount()>0 ? 'InStock' : 'PreOrder'?>" />

      <span itemprop="price" content="<?= $product->getPrice() ?>" class=" mfp-hide">
        <?= number_format( $product->getPrice(), 0, '', ' ') ?> <?/*₽*/?>руб.
        <meta itemprop="priceValidUntil" content="<?= date('c', 24*60*60+time()) ?>" />
        <meta itemprop="priceCurrency" content="RUB" />
        <link itemprop="url" href="https://onona.ru/product/<?=$product->getSlug()?>" />
        <?/*<meta itemprop="url" content="https://onona.ru/product/<?=$product->getSlug()?>" />*/?>
      </span>
    </span>

    <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/'.$product->getSlug() ?>" class="cat-list-img" >
      <?= 1*$product->getCount() > 0 ? '' : '<span class="-byOrder"></span>' ?>
      <?= $product->getCount()*$product->getDiscount()>0 ? '<span class="cat-list-action"> '.$product->getDiscount().'% </span>' : '' ?>
      <? /*= $product->getBonuspay() ?  '<span class="-managePrice"></span>' : '' */?>
      <? if($is_swiper):?>
        <img data-src="<?= $photo ?>" alt="<?= $product->getName() ?>" class="swiper-lazy<?=$needBlured ? ' blured' : ''?>" src="<?= $photo ?>" loading="lazy">
      <? else :?>
        <img src="<?= $photo ?>" alt="<?= $product->getName() ?>" <?=$needBlured ? 'class="blured"' : ''?>>
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
      <? endif */?>
    </a>
    <div class="cat-list-desc">
      <div class="rating">
        <? for ($i=0; $i<5; $i++) :?>
          <div class="rating-item<?=$i<$numStars ? ' -isActive' : ""?>">
            <svg>
              <use xlink:href="#rateItemIcon" />
            </svg>
          </div>
        <? endfor ?>
      </div>
      <div class="cat-list-title -normal">
        <a href="<?= $product->getExternallink() ? $product->getExternallink() : '/product/'.$product->getSlug() ?>"><?= $product->getName() ?></a>
      </div>
      <div class="cat-list-bal">
        +<?= $bonus ?> бонусов
      </div>
      <div class="cat-list-price<?= ($product->getDiscount()/* || $product->getBonuspay()*/) ? ' -oldPrice' : ''?>">
        <?= $product->getDiscount() ? '<span>'.$product->getOldPrice().' руб.</span>' : ''?>
        <?/*= (!$product->getDiscount() && $product->getBonuspay()) ? '<span>'.$product->getPrice().' руб.</span>' : ''?>*/?>
        <?= /*(!$product->getDiscount() && $product->getBonuspay()) ? round($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100) :*/ $product->getPrice() ?>&nbsp;руб.
      </div>

      <a href="#" class="but js-basket-add <?= 1*$product->getCount() > 0 ? 'gtm-list-add-to-basket' : 'gtm-list-add-to-basket'?>" data-id="<?= $product->getId() ?>"> В корзину </a>
    </div>
  </div>
<? endif ?>
