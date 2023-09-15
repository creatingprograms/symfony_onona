<?= $isFullSize = false; ?>
<? $i = 0; ?>
<?php if ($isNew) : ?>
  <?php if (!empty($slides) && count($slides)) : ?>
    <div class="wrap-block wrap-block_top">
      <div class="container">
        <div class="block-top">
          <div class="swiper-container sl-top-new" data-timer="<?= csSettings::get('slider_timer'); ?>">
            <div class="swiper-wrapper">
              <? foreach ($slides as $slide) : ?>
                <div class="swiper-slide sl-top-new_item">
                  <div class="element-wrapper">
                    <? if ($slide->getHref()) : ?>
                      <a href="<?= $slide->getHref() ?>" class="js-save-click" data-id="<?= $slide->getId() ?>">
                      <? endif ?>
                      <img src="/uploads/media/<?= $slide->getFile() ? $slide->getFile() : $slide->getSrc() ?>" alt="<?= $slide->getAlt() ?>" class="swiper-lazy desktop-only-new" loading="lazy">
                      <img src="/uploads/media/<?= $slide->getSrcMobile() ?>" alt="<?= $slide->getAlt() ?>" class="swiper-lazy mobile-only-new">
                      <? if ($slide->getHref()) : ?>
                      </a>
                    <? endif ?>
                  </div>
                </div>
              <? endforeach ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev custom-nav"><svg><use xlink:href="#arr-sl-l"></use></svg></div>
            <div class="swiper-button-next custom-nav"><svg><use xlink:href="#arr-sl-r"></use></svg></div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php else : ?>
  <? if (sizeof($slides) && ($isFullSize || sizeof($half_slides))) : ?>
    <!-- блок топ-слайдер сайдбар -->
    <div class="wrap-block wrap-block_top">
      <div class="container">
        <div class="block-top">
          <div class="swiper-container sl-top <?= $isFullSize ? 'sl-top--hard' : '' ?>" data-timer="<?= csSettings::get('slider_timer'); ?>">
            <div class="swiper-wrapper">
              <? foreach ($slides as $slide) : ?>
                <div class="swiper-slide wrap-sl-item">
                  <div class="sl-item sl-top-item block-info <? //= $slide->getClass() 
                                                              ?>">
                    <div class="block-info__img">
                      <? if ($slide->getHref()) : ?>
                        <a href="<?= $slide->getHref() ?>" <?/* class="link-fill"*/ ?>>
                          <? //= $slide->getButton() 
                          ?>
                        <? endif ?>
                        <img alt="<?= $slide->getAlt() ?>" data-src="/uploads/media/<?= $slide->getFile() ? $slide->getFile() : $slide->getSrc() ?>" class="swiper-lazy" data-id="<?= $slide->getId() ?>" src="/uploads/media/<?= $slide->getFile() ? $slide->getFile() : $slide->getSrc() ?>" loading="lazy">
                        <? if ($slide->getHref()) : ?>
                        </a>
                      <? endif ?>
                    </div>
                    <?/*
                      <div class="block-info__title"><?= $slide->getAlt() ?></div>
                      <? if ($slide->getDescription()): ?>
                        <div class="block-info__text"><?= $slide->getDescription() ?></div>
                      <? endif */ ?>
                    <?/* if($slide->getHref()) :?>
                        <a href="<?= $slide->getHref() ? $slide->getHref() : 'javascript:void(1)'?>" class="link-fill"><?= $slide->getButton() ?></a>
                      <? endif */ ?>

                  </div>
                </div>
              <? endforeach ?>
              <?/*
                  <div class="swiper-slide wrap-sl-item">
                    <div class="sl-item sl-top-item block-info" style="background: linear-gradient(270deg, #F20013 0%, #96001A 100%), #DE0014">
                    <div class="block-info__img">
                      <img src="/frontend/images_new/top/1.png" alt="">
                    </div>
                      <div class="block-info__title">Подарочные купоны до 3 000 ₽ каждому покупателю Test</div>
                      <div class="block-info__text">Даем вам специальные промокоды в нашем Интернет-магазине</div>
                      <a href="#" class="link-fill">Условия акции</a>
                    </div>
                  </div>
                */ ?>
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
            <div class="wrap-pag">
              <div class="swiper-pagination"></div>
            </div>
          </div>
          <?php if (!$isFullSize) : //179160 - onona.ru - Оценить изменения
          ?>
            <div class="swiper-container sl-sidebar">
              <div class="swiper-wrapper">
                <? foreach ($half_slides as $key => $slide) : ?>
                  <div class="swiper-slide wrap-sl-item">
                    <div class="sl-item sl-top-item block-info block-info_sidebar<?/* promotion-item_blue*/ ?>">
                      <div class="block-info__img">
                        <? if ($slide->getHref()) : ?>
                          <a href="<?= $slide->getHref() ?>" <?/* class="link-fill"*/ ?>>
                          <? endif ?>
                          <img alt="<?= $slide->getAlt() ?>" data-src="/uploads/media/<?= $slide->getFile() ? $slide->getFile() : $slide->getSrc() ?>" class="swiper-lazy" data-id="<?= $slide->getId() ?>" src="/uploads/media/<?= $slide->getFile() ? $slide->getFile() : $slide->getSrc() ?>" loading="lazy">
                          <? if ($slide->getHref()) : ?>
                          </a>
                        <? endif ?>
                      </div>
                      <?/*
                        <div class="block-info__title"><?= $slide->getAlt() ?></div>
                        <? if ($slide->getDescription()): ?>
                          <div class="block-info__text"><?= $slide->getDescription() ?></div>
                        <? endif ?>
                        <? if($slide->getHref()) :?>
                          <a href="<?= $slide->getHref() ? $slide->getHref() : 'javascript:void(1)'?>" class="link-fill"><?= $slide->getButton() ?></a>
                        <? endif */ ?>
                    </div>
                  </div>
                  <? break; //Предполагается один слайд и один  товар 
                  ?>
                <? endforeach ?>
                <div class="swiper-slide wrap-sl-item">
                  <? include_component(
                    "product",
                    "productOfDay",
                    array(
                      'sf_cache_key' => 'product-of-day' . date('d.m.Y'),
                    )
                  ); ?>

                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- END - блок топ-слайдер сайдбар -->
  <? endif ?>
  <? if (false /*sizeof($slides)*/) : ?>
    <section class="top-slider wrapper -typeSlider <?= $half_size ? 'half-size' : '' ?>">
      <? if ($half_size) : ?>
        <div class="small-slider">
        <? endif ?>
        <div class="swiper-container">
          <div class="swiper-wrapper">
            <? foreach ($slides as $slide) : ?>
              <div class="swiper-slide" data-iddqd="<?= $slide->getId() ?>">
                <a href="<?= $slide->getHref() ? $slide->getHref() : 'javascript:void(1)' ?>" class="gtm-banner" data-name="<?= $slide->getAlt() ?>" data-id="<?= $slide->getId() ?>" data-creative="top-slider" data-position="top-slider">
                  <img alt="<?= $slide->getAlt() ?>" data-src="/uploads/media/<?= $slide->getSrc() ?>" class="swiper-lazy" data-id="<?= $slide->getId() ?>" src="/uploads/media/<?= $slide->getSrc() ?>" loading="lazy">
                </a>
              </div>
            <? endforeach ?>
          </div>
        </div>
        <div class="swiper-pagination<?= sizeof($slides) < 2 ? ' mfp-hide' : '' ?>"></div>
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
        <? if ($half_size) : ?>
        </div>
        <div class="sidebar-sliders">
          <? foreach ($half_slides as $slide) : ?>
            <a href="<?= $slide->getHref() ? $slide->getHref() : 'javascript:void(1)' ?>" class="gtm-banner" data-name="<?= $slide->getAlt() ?>" data-id="<?= $slide->getId() ?>" data-creative="top-slider" data-position="top-slider">
              <img alt="<?= $slide->getAlt() ?>" src="/uploads/media/<?= $slide->getSrc() ?>">
            </a>
          <? endforeach ?>
        </div>
      <? endif ?>
    </section>
  <? endif ?>
<?php endif; ?>