<div class="v-block-plate -int">РЕЗУЛЬТАТЫ</div>
<div class="test-result">
  <h2><?= $result->getName() ?></h2>
  <p><?= $result->getResults() ?></p>
  <p><a href="<?= $result->getLink() ?>">Посмотреть</a></p>
  <? if(trim($result->getLink_1())) :?>
    <p><a href="<?= $result->getLink_1() ?>">Посмотреть</a></p>
  <? endif ?>
  <? if(trim($result->getLink_2())) :?>
    <p><a href="<?= $result->getLink_2() ?>">Посмотреть</a></p>
  <? endif ?>
  <p>Промокод отправлен на Вашу почту</p>
  <br><p>&nbsp;</p>
</div>
