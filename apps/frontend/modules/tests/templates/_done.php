<div class="v-block-plate -int">РЕЗУЛЬТАТЫ ТЕСТА</div>
<div class="test-result">
  <?= $result->getResults() ?>
</div>
<br><a class="but -big" href="<?= $url ?>">Пройти тест еще раз</a>
<? if ($allTests->count() > 1): ?>
  <div class="v-block-plate -int">Пройти другие тесты</div>
  <div class="test-all">
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <?php foreach ($allTests as $testDop): ?>
          <div class="swiper-slide">
            <a href="/tests/<?= $testDop->getSlug() ?>">
              <?
                $image=false;
                if($testDop->getImg() && file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/photo/thumbnails_250x250/'.$testDop->getImg()))
                  $image='/uploads/photo/thumbnails_250x250/'.$testDop->getImg();
              ?>
              <img src="<?= $image ? $image : '/frontend/images/no-image.png' ?>" alt="<?= $testDop->getName() ?>" />
              <span><?= mb_substr(stripslashes($testDop->getName()), 0, 35, 'UTF-8') ?><?= (mb_strlen(stripslashes($testDop->getName())) > 35) ? "..." : '' ?></span>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
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
  </div>
<?php endif; ?>
