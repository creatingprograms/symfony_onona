<?/*<hr><pre>|<?=print_r($catalog, true) ?>|</pre><hr>*/?>
<? if(sizeof($catalog)) :?>
  <!-- блок Каталог -->
  <div class="wrap-block wrap-block_top">
    <div class="container">
      <div class="h2 block-title hide-mobile">Каталог <a href="/catalog" class="link-text">Все категории</a></div>
      <div class="block-catalog">
        <div class="swiper-container sl-catalog <?=$type=='new' ? 'sl-catalog-new' : ''?>">
          <div class="swiper-wrapper">
            <? foreach ($catalog as $cat) :?>
              <?php if ($type=='new'): ?>
                <a href="/catalog/<?=$cat['slug']?>" class="swiper-slide wrap-sl-catalog">
                  <div class="mobile-cat-wrapper">
                    <div class="mobile-cat">
                      <svg class="cat-icon">
                        <use xlink:href="#cat<?= $cat['class'] ?>" />
                      </svg>
                    </div>
                    <div class="mobile-cat-text"><?=$cat['name']?></div>
                  </div>
                  <div class="catalog-item sl-item catalog-item_new">
                    <div class="img_wrapper">
                      <img src="/uploads/photo/<?=$cat['img_bottom']?>" alt="<?=$cat['menu_name']?>">
                    </div>
                    <div class="text-wrapper">
                      <div class="catalog-item__title"><?=$cat['menu_name']?></div>
                      <div class="catalog-item__text sl-text"><?=$cat['count']?> товаров <br> от <?=$cat['minprice']?> ₽</div>
                    </div>
                  </div>
                </a>
              <? else :?>
                <a href="/catalog/<?=$cat['slug']?>" class="swiper-slide wrap-sl-catalog">
                  <div class="catalog-item sl-item">
                    <div class="catalog-item__content">
                      <div class="catalog-item__title"><?=$cat['menu_name']?></div>
                      <div class="catalog-item__text sl-text"><?=$cat['count']?> товаров <br> от <?=$cat['minprice']?> ₽</div>
                      <div href="/catalog/<?=$cat['slug']?>" class="catalog-item__btn">
                        <svg>
                          <use xlink:href="#single-arrow"></use>
                        </svg>
                      </div>
                    </div>
                    <div class="catalog-item__img">
                      <img src="/uploads/photo/<?=$cat['img_top']?>" alt="<?=$cat['menu_name']?>">
                    </div>
                  </div>
                </a>
              <?php endif; ?>
            <? endforeach ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- END - блок Каталог -->
<? endif ?>
