<? if (sizeof($icons)) :?>
  <?php if ($type=='new'): ?>
    <div class="wrap-block wrap-block_top">
      <div class="container">
        <div class="block-catalog">
          <div class="swiper-container sl-catalog__new">
            <div class="swiper-wrapper">
              <?php if ($isShowTop20): ?>
                <div class="swiper-slide wrap-sl-catalog__new">
                  <a href="/catalog/<?=$slug ?>/top20" class="top-20">
                    Топ 20
                  </a>
                </div>
              <?php endif; ?>
              <?php foreach ($icons as $icon): ?>
                <div class="swiper-slide wrap-sl-catalog__new">
                  <a href="/category/<?=$add_link ? $add_link : ''?><?= mb_strtolower($icon['slug']) ?>" class="">
                    <span class="img">
                      <img src="/uploads/catalog_icons/<?=$icon['img']?>" alt="<?= $icon['icon_name'] ? $icon['icon_name'] : $icon['name']  ?>">
                    </span>
                    <span class="catalog-item__title"><?= $icon['icon_name'] ? $icon['icon_name'] : $icon['name']  ?></span>
                  </a>
                </div>
              <? endforeach ?>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <? $i=$index=0; /* индекс текущего слайда */?>
    <div class="wrap-block">
      <div class="container">

        <div class="block-catalog">
          <div class="swiper-container sl-catalog">
            <div class="swiper-wrapper">
              <?php foreach ($icons as $icon): ?>
                <div class="swiper-slide wrap-sl-catalog">
                  <a href="/category/<?=$add_link ? $add_link : ''?><?= mb_strtolower($icon['slug']) ?>" class="catalog-item sl-item">
                    <div class="catalog-item__content">
                      <div class="catalog-item__title"><?= $icon['icon_name'] ? $icon['icon_name'] : $icon['name']  ?></div>
                      <div href="/category/<?=$add_link ? $add_link : ''?><?= mb_strtolower($icon['slug']) ?>" class="catalog-item__btn">
                        <svg>
                          <use xlink:href="#single-arrow"></use>
                        </svg>
                      </div>
                    </div>
                    <div class="catalog-item__img">
                      <img src="/uploads/catalog_icons/<?=$icon['img']?>" alt="<?= $icon['icon_name'] ? $icon['icon_name'] : $icon['name']  ?>">
                    </div>
                  </div>
                </a>
                <?
                  if(mb_strtolower($icon['slug']) ==$slug ) $index=$i;
                  $i++;
                ?>
              <? endforeach ?>
            </div>
          </div>
        </div>
      </div>
      <div id="current-slide-index" data-index="<?=$index?>"></div>
    </div>
  <? endif ?>
<? endif ?>
