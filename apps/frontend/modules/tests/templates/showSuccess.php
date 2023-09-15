<?php
global $isTest;
$h1=$test->getName();
slot('metaTitle', $h1 . " * Тесты * Главная");
slot('metaKeywords', $h1 . " * Тесты * Главная");
slot('metaDescription', $h1 . " * Тесты * Главная");
// slot('canonical', "/tests/" . $sf_request->getParameter('slug'));

slot('breadcrumbs', [
  ['link'=>'/lovetest/', 'text' => 'Тесты'],
  ['text' => $h1],
]);
?>
<div class="wrapper wrap-cont -video -clf">
  <main class="cont-right">
    <div class="test-detail">
      <div class="test-detail-left">
        <img src="/uploads/photo/<?= $test->getImg() ?>" alt="<?= str_replace(array("'", '"'), "", $test->getName()) ?>"/>
        <?php $rating = round(($test->getRating() / $test->getVotesCount())/ 2)+0.1; ?>
        <div class="rating">
          <?php for ($i=1; $i < 6; $i++): ?>
            <div class="rating-item<?= $i < $rating ? " -isActive": ''?>">
              <svg>
                <use xlink:href="#rateItemIcon" />
              </svg>
            </div>
          <?php endfor; ?>
        </div>
        <div class="text">Вопросов: <?= $test->Question->count() ?></div>
        <div class="text">Тест прошли: <?= $test->getWriting() == "" ? 0 : $test->getWriting() ?> чел.</div>
      </div>
      <div class="test-detail-right">
        <h1><?= $h1 ?></h1>
        <?= strip_tags($test->getContent()) ?>
      </div>
    </div>
    <div class="test-questions">
      <form action="/tests/done/<?= $sf_request->getParameter('slug') ?>" method="POST" id="question" class="js-ajax-form">
        <?php
          $testQuestion = $pager->getResults();
        ?>
        <?php foreach ($testQuestion as $question): ?>
          <div class="v-block-plate -int"><?= $question->getNumber() ?>. <?= $question->getQuestion() ?></div>
          <div class="js-question-wrapper">
            <?php foreach ($question->Answer as $answer): ?>
              <input type="radio" class="styleCH js-need-all" name="answer[<?= $question->getId() ?>]" value="<?= $answer->getBalls() ?>" id="answer_<?= $question->getId() ?>_<?= $answer->getId() ?>"  />
              <label for="answer_<?= $question->getId() ?>_<?= $answer->getId() ?>"><?= $answer->getAnswer() ?></label>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>

        <div class="but -big js-submit-button disabled">Результаты теста</div>
      </form>
    </div>
  </main>
  <? include_component("page", "testsSidebar", array('sf_cache_key' => 'testsSidebar')); ?>
</div>
