<div class="wrap-block wrap-block-resulte">
  <div class="container">
    <div class="block-content">
      <div class="block-test-resulte">
        <div class="wrap">
          <h4>Результаты теста</h4>
          <p>
            <?=is_object($result) ? $result->getResults() : 'К сожалению мы не смогли определить Ваш результат. Попробуйте еще раз.'?>
          </p>
        </div>
      </div>
      <div class="wrap-btn">
        <a href="<?= $url ?>" class="btn-full btn-full_rad">Пройти еще раз</a>
      </div>
    </div>
  </div>
</div>
<? $i=0; ?>
<div class="wrap-block">
  <div class="container">
    <h2 class="h2 block-title">Пройти другие тесты</h2>
    <div class="overview-list_four">
      <?php foreach ($allTests as $testDop): ?>
        <?
        if($i++>3) break;
          $rating = round(($testDop->getRating() / $testDop->getVotesCount())/ 2+0.1);
          if(!$rating) $rating=4;
          if($testDop->getImg() && file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/photo/'.$testDop->getImg()))
            $image='/uploads/photo/'.$testDop->getImg();
          else
            $image='/frontend/images/no-image.png';

        ?>
        <div class="overview-item">
          <a href="/tests/<?= $testDop->getSlug() ?>" class="overview-item__img" style="background-image: url('<?=$image?>')">
          </a>
          <div class="wrap-rating-star" rating="<?=$rating?>">
            <span class="rating-star<?=$rating >0 ? ' active' : ''?>"></span>
            <span class="rating-star<?=$rating >1 ? ' active' : ''?>"></span>
            <span class="rating-star<?=$rating >2 ? ' active' : ''?>"></span>
            <span class="rating-star<?=$rating >3 ? ' active' : ''?>"></span>
            <span class="rating-star<?=$rating >4 ? ' active' : ''?>"></span>
          </div>
          <a href="/tests/<?= $testDop->getSlug() ?>" class="overview-item__text"><?= $testDop->getName()?></a>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</div>
