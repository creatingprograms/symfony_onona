<? $i=0; ?>
<? if (sizeof($slides)) : ?>
  <section class="top-slider wrapper -typeSlider <?=$half_size ? 'half-size' : ''?>">
    <? if ($half_size) :?>
      <div class="small-slider">
    <? endif ?>
        <div class="swiper-container">
          <div class="swiper-wrapper">
            <? foreach ($slides as $slide) :?>
              <div class="swiper-slide"  data-iddqd="<?=$slide->getId()?>">
                <a href="<?= $slide->getHref() ? $slide->getHref() : 'javascript:void(1)'?>" class="gtm-banner" data-name="<?= $slide->getAlt() ?>" data-id="<?= $slide->getId() ?>" data-creative="top-slider" data-position="top-slider">
                  <img alt="<?= $slide->getAlt() ?>" data-src="/uploads/media/<?= $slide->getFile() ? $slide->getFile() : $slide->getSrc() ?>" class="swiper-lazy" data-id="<?=$slide->getId()?>" src="/uploads/media/<?= $slide->getFile() ? $slide->getFile() : $slide->getSrc() ?>" loading="lazy">
                </a>
              </div>
            <? endforeach?>
          </div>
        </div>
        <div class="swiper-pagination<?=sizeof($slides)<2 ? ' mfp-hide' : ''?>"></div>
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
    <? if ($half_size) :?>
      </div>
        <div class="sidebar-sliders">
          <? foreach ($half_slides as $slide) :?>
            <a href="<?= $slide->getHref() ? $slide->getHref() : 'javascript:void(1)'?>" class="gtm-banner" data-name="<?= $slide->getAlt() ?>" data-id="<?= $slide->getId() ?>" data-creative="top-slider" data-position="top-slider">
              <img alt="<?= $slide->getAlt() ?>" src="/uploads/media/<?= $slide->getFile() ? $slide->getFile() : $slide->getSrc() ?>">
            </a>
          <? endforeach ?>
        </div>
    <? endif ?>
  </section>
<? endif ?>
