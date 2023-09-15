<? if(is_object($banners) && count($banners)) : ?>
  <noindex>
    <?php if ($variant=='double'): ?>
      <? $i=0; ?>
      <div class="wrap-block wrap-block_min ">
        <div class="container double-view">
          <? foreach ($banners as $banner) :?>
          <?php if ($i++>1) break; ?>
            <div class="element">
              <?php if ($banner->getHref()): ?>
                <a href="<?=$banner->getHref()?>" class="js-save-click-banner" data-id="<?= $banner->getId() ?>">
              <?php endif; ?>
                  <img class="desktop-only" src="/uploads/media/<?=$banner->getSrc()?>" alt="<?=$banner->getAlt()?>">
                  <img class="mobile-only" src="/uploads/media/<?=$banner->getSrcMobile()?>" alt="<?=$banner->getAlt()?>">
              <?php if ($banner->getHref()): ?>
                </a>
              <?php endif; ?>
            </div>
        <? endforeach ?>
      </div>
    </div>
    <?php endif; ?>
    <?php if ($variant=='slider'): ?>
      <div class="wrap-block wrap-block_min ">
        <div class="container">
          <div class="swiper-container sl-b-new">
            <div class="swiper-wrapper">
              <? foreach ($banners as $banner) :?>
                <div class="swiper-slide sl-top-new_item">
                  <div class="element-wrapper">
                    <? if($banner->getHref()) :?>
                      <a href="<?= $banner->getHref()?>" class="js-save-click-banner" data-id="<?= $banner->getId() ?>">
                    <? endif ?>
                          <img src="/uploads/media/<?= $banner->getSrc() ?>" alt="<?=$banner->getAlt()?>" class="swiper-lazy desktop-only" loading="lazy">
                          <img src="/uploads/media/<?= $banner->getSrcMobile() ?>" alt="<?=$banner->getAlt()?>" class="swiper-lazy mobile-only">
                    <? if($banner->getHref()) :?>
                        </a>
                    <? endif ?>
                  </div>
                </div>
              <? endforeach ?>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </div>
    <? endif ?>
  </noindex>
<? endif ?>
