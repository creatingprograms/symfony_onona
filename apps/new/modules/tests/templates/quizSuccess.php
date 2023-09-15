<?php
global $isTest;
$h1=$quiz->getName();
// slot('metaTitle', $h1 . " * Тесты * Главная");
// slot('metaKeywords', $h1 . " * Тесты * Главная");
// slot('metaDescription', $h1 . " * Тесты * Главная");
$i=0;
$quizQuestions=$quiz->getQuizQuestion();
$quizSize=sizeof($quizQuestions);
slot('breadcrumbs', [
  // ['link'=>'/lovetest/', 'text' => 'Тесты'],
  ['text' => $h1],
]);
?>
<div class="wrapper wrap-cont -video -clf quiz-container">
  <main<?/* class="cont-right"*/?>>
    <div class="test-detail quiz">
      <div class="test-detail-left img-container">
        <? if ($quiz->getImg()) :?>
          <img src="/uploads/photo/<?= $quiz->getImg() ?>">
        <? endif ?>
      </div>
      <div class="test-detail-right">
        <h1><?= $h1 ?></h1>
        <p><?=$quiz->getContent()?></p>
        <div class="js-quiz-next but" data-id="quiz-start">Начать</div>
      </div>
    </div>
    <div class="test-questions">
      <form action="/quiz/done/<?= $quiz->getSlug() ?>" method="POST" id="question" class="js-ajax-form">
        <? foreach ($quizQuestions as $question) :?>
          <div class="quiz-block" <?=!$i++ ? 'id="quiz-start"' : ''?>>
            <div class="v-block-plate -int"><?= $question->getNumber() ?>. <?= $question->getQuestion() ?></div>
            <div class="progress-wrapper">
              <div class="progress-percent">Готово: <span class="red"><?=ceil(100*($i-1)/$quizSize)?>%</span></div>
              <div class="progress-bar">
                <div class="progress-line" style="width: <?=100*($i-1)/$quizSize?>%;"></div>
              </div>
            </div>
            <div class="js-question-wrapper question-container">
              <div class="question-left">
                <?php foreach ($question->QuizAnswer as $answer): ?>
                  <div class="answer-wrapper <?=$answer->getIsCorrect() ? 'correct' : ''?>">
                    <input type="radio" class="styleCH js-quiz-answer" name="answer[<?= $question->getId() ?>]" value="<?= $answer->getBalls() ?>" id="answer_<?= $question->getId() ?>_<?= $answer->getId() ?>"  />
                    <label for="answer_<?= $question->getId() ?>_<?= $answer->getId() ?>"><?= $answer->getAnswer() ?></label>
                    <? if(trim($answer->getComment())) :?>
                      <div class="answer-comment">
                        <?= $answer->getComment() ?>
                      </div>
                    <? endif ?>
                  </div>
                <?php endforeach; ?>
              </div>
              <? if($question->getImg()):?>
                <div class="question-right img-container">
                  <img src="/uploads/photo/<?= $question->getImg() ?>">
                </div>
              <? endif ?>
            </div>
            <div class="buttons-wrapper">
              <div class="<?=$i==1 ? '' : 'but but-quiz js-quiz-prev'?>" <?=$i==1 ? ' style="opacity: 0;"': ''?>>Назад</div>
              <div class="but js-quiz-next disabled">Далее</div>
            </div>
          </div>

        <? endforeach ?>
        <div class="quiz-block quiz-block-form">
          <div class="test-detail quiz">
            <div class="test-detail-left img-container">
              <img src="/frontend/images/quiz-form.jpg" alt="">
            </div>
            <div class="test-detail-right quiz-last">
              <p><span class="quiz-icon strawberry"></span>Оставьте свой e-mail и получите персональные результаты вместе с подборкой секс-гаджетов</p>
              <p><span class="quiz-icon fire"></span>Ваши ночи точно станут жаркими и незабываемыми!</p>
              <p><span class="quiz-icon hearts"></span>А еще вас ждет промокод на скидку на первую покупку</p>
              <p class="input-name">Введите Имя:</p>
              <input class="name" type="text" name="name" placeholder="ИМЯ">
              <p class="input-name">Введите E-mail:</p>
              <input class="email js-rr-send" type="text" name="email" placeholder="MAIL@MAIL.RU">
              <div class="but -big js-submit-button">Получить результаты!</div>
              <input type="checkbox" id="quiz-agreement" class="styleCH" name="agreement" value="1" checked>
              <label for="quiz-agreement">с политикой<br>конфиденциальности<br>ознакомлен(а)</label>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>
  <? //include_component("page", "testsSidebar", array('sf_cache_key' => 'testsSidebar')); ?>
</div>
