<? if (sizeof($icons)) :?>
  <? $i=$index=0; /* индекс текущего слайда */?>
  <section class="wrapper showcase<?= $hide_text ? ' hide-text' : ''?>" id="showcaseSlider">
    <div class="swiper-button-prev">
      <svg>
        <use xlink:href="#backArrowIcon" />
      </svg>
    </div>
    <div class="swiper-button-next">
      <svg>
        <use xlink:href="#backArrowIcon" />
      </svg>
    </div>
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <?php foreach ($icons as $icon): ?>
          <a href="/category/<?=$add_link ? $add_link : ''?><?= mb_strtolower($icon['slug']) ?>" class="showcase-item swiper-slide<?= mb_strtolower($icon['slug']) ==$slug ? ' active' : ''?>">
            <span class="<?= $hide_text ? '' : 'showcase-img'?>">
              <img src="/uploads/catalog_icons/<?=$icon['img']?>" alt="">
            </span>
            <? if(!$hide_text):?>
              <span class="showcase-text">
                <?= $icon['icon_name'] ? $icon['icon_name'] : $icon['name']  ?>
              </span>
            <? endif ?>
          </a>
          <?
            if(mb_strtolower($icon['slug']) ==$slug ) $index=$i;
            $i++;
          ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <div id="current-slide-index" data-index="<?=$index?>"></div>
<? endif ?>
