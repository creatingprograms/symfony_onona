<? if(sizeof($slides)) :?>
<!-- блок Акции -->
<div class="wrap-block">
  <div class="container">
    <div class="block-promotions">
    <div class="swiper-container sl-promotions">
      <div class="swiper-wrapper">
        <? foreach ($slides as $slide) :?>

          <div class="swiper-slide wrap-sl-item">
            <div class="sl-item promotion-item <?=$slide->getClass()?>"<?/* style="background: linear-gradient(180deg, #FCA11E 0%, #F08101 100%), #C4C4C4;"*/?>>
              <div class="block-info__img">
                <img src="/uploads/media/<?=$slide->getSrc()?>" alt="">
              </div>
              <div class="promotion-item__title"><?=$slide->getAlt()?></div>
              <div class="promotion-item__text"><?=$slide->getDescription()?></div>
              <? if($slide->getHref()) :?>
                <a href="<?=$slide->getHref()?>" class="link-fill"><?=$slide->getButton()?></a>
              <? endif ?>
            </div>
          </div>
        <? endforeach ?>
        <a class="swiper-slide wrap-sl-item" href="/category/podarochnye-sertifikaty">
          <img src="/frontend/images_new/sert.jpg" alt="Подарочные сертификаты">


        </a>
      </div>
    </div>
    <?/*
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
    </div>*/?>
  </div>
</div>
</div>
<!-- END - блок Акции -->
<? endif ?>
