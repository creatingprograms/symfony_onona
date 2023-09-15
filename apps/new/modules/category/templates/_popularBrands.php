<?
  $i=false;
?>
<? if (sizeof($manufacturerPopular)) :?>
  <div class="wrap-block">
    <div class="container">
      <div class="h2 block-title">Популярные бренды<a href="/manufacturers" class="link-text">Все бренды</a></div>
        <div class="block-products">
          <div class="swiper-container sl-brands">
            <div class="swiper-wrapper">
              <? foreach ($manufacturerPopular as $manufacturer) :?>
                <?php if (!$i): ?>
                  <div class="swiper-slide">
                <?php endif; ?>
                    <a href="/manufacturer/<?= $manufacturer->getSlug() ?>">
                      <img src="<?= ILTools::getImageSrc($manufacturer) ?>" alt="<?= $manufacturer->getName()?>">
                    </a>
                <?php if ($i): ?>
                  </div>
                <?php endif; ?>
                <? $i=!$i ?>
              <? endforeach ?>
              <?php if ($i): ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
      </div>
    </div>
  </div>
<? endif ?>
