<? if(sizeof($comments)) :?>
  <!-- блок Отзывы -->
  <div class="wrap-block">
    <div class="container">
      <div class="h2 block-title">Последние отзывы <?/*<a href="#" class="link-text">Все отзывы</a>*/?></div>
      <div class="block-reviews">
        <div class="swiper-container sl-reviews">
          <div class="swiper-wrapper">
            <? foreach ($comments as $comment) :?>
              <?
                $product=$comment->getProduct();
                if(!$product->getIsPublic()) continue;
              ?>
              <div class="swiper-slide wrap-sl-item">
                <div class="sl-item review-item">
                  <div class="review-item__reviwe">
                    <div class="review-item__bar-top">
                      <span class="review-item__name"><?= $comment->getUsername() ? $comment->getUsername() : "Аноним"?></span>
                      <span class="review-item__date"><?=date('d.m.Y', strtotime($comment->getCreatedAt()))?></span>
                    </div>
                    <div class="review-item__text sl-text"><?=$text=mb_substr($comment->getText(), 0, 150).(mb_strlen($text)==mb_strlen($comment->getText()) ? '' : '...')?></div>
                    <a href="/product/<?=$product->getSlug()?>" class="link-text">Полный отзыв</a>
                  </div>
                  <div class="review-item__cart">
                    <a href="/product/<?=$product->getSlug()?>" class="product-item__img">
                      <img src="<?=$photoAlbum[$product->getId()] ? $photoAlbum[$product->getId()] : '/frontend/images_new/no-photo.jpg'?>" alt="<?=$product->getName()?>">
                    </a>
                    <div class="review-item__cart-content">
                      <?/*<div class="wrap-rating-star" rating="<?=round($comment->getRateSet() ? ($comment->getRateSet()/2+0.1) : 4)?>">
                        <span class="rating-star"></span>
                        <span class="rating-star"></span>
                        <span class="rating-star"></span>
                        <span class="rating-star"></span>
                        <span class="rating-star"></span>
                      </div>*/?>
                      <a href="/product/<?=$product->getSlug()?>" class="product-item__text sl-text"><?=$product->getName()?></a>
                      <div class="product-item__price"><?=number_format($product->getPrice(), 0, ',', ' ')?> ₽</div>
                    </div>
                  </div>
                </div>
              </div>
            <? endforeach ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
        <?/*<div class="wrap-nav">
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
        </div>*/?>
      </div>
    </div>
  </div>
  <!-- END - блок Отзывы -->
<? endif ?>
