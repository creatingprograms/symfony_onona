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
slot('h1', $h1);
slot('catalog-class', 'page-test-detaly');

$rating = round(($test->getRating() / $test->getVotesCount())/ 2+0.1);
if(!$rating) $rating=4;
$testQuestion = $pager->getResults();
if($test->getImg() && file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/photo/'.$test->getImg()))
  $image='/uploads/photo/'.$test->getImg();
else
  $image='/frontend/images/no-image.png';

?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <div class="test-item test-item_only">
        <div class="test-item__img" style="background-image: url(<?=$image?>)"></div>
        <div class="test-item__content">
          <div class="wrap-rating-star" rating="<?=$rating?>">
            <span class="rating-star"></span>
            <span class="rating-star"></span>
            <span class="rating-star"></span>
            <span class="rating-star"></span>
            <span class="rating-star"></span>
          </div>
          <div class="test-item__text">
            <?=$test->getContent()?>
          </div>
        </div>
      </div>
      <form action="/tests/done/<?= $test->getSlug() ?>" method="POST" id="question"  class="form form-test js-ajax-form">
        <?php foreach ($testQuestion as $question): ?>
          <div class="block-question js-question-wrapper">
            <div class="question__title item-offset"><span><?= $question->getNumber() ?>. </span><span><?= $question->getQuestion() ?></span></div>
            <?php foreach ($question->Answer as $answer): ?>
              <div class="custom-check item-offset">
                <div class="check-check_block">
                  <input type="radio" name="answer[<?= $question->getId() ?>]" value="<?= $answer->getBalls() ?>" id="answer_<?= $question->getId() ?>_<?= $answer->getId() ?>" class="check-check_input js-need-all">
                  <div class="custom-check_shadow"></div>
                </div>
                <label for="answer_<?= $question->getId() ?>_<?= $answer->getId() ?>" class="custom-check_label"><?= $answer->getAnswer() ?></label>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
        <div class="wrap-btn">
          <button class="btn-full btn-full_rad js-submit-button disabled">Результаты теста</button>
        </div>
      </form>
    </div>
  </div>
</div>
