<?php
//slot('articleleftBlock', true);
slot('testsleftBlock', true);
slot('metaTitle', $test->getName() . " * Тесты * Главная");
slot('metaKeywords', $test->getName() . " * Тесты * Главная");
slot('metaDescription', $test->getName() . " * Тесты * Главная");
slot('canonicalSlugLovetest', "tests/" . $sf_request->getParameter('slug'));

slot('articlerightpad', true);
slot('no-float', true);
?>

<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li><a href="/lovetest">Тесты</a></li>
    <li><?= $test->getName() ?></li>
</ul>

<h1 class="title"><?= $test->getName() ?></h1>

<div style=" padding:10px 0; min-height: 140px;" class="divArticleHover no-border">
    <div style="float: left;text-align: center;" class="mobile-hide">
      <?php if ($test->getImg() != "") { ?>
        <a href="/tests/<?= $test->getSlug() ?>">
          <img class="thumbnails" src="/images/pixel.gif" data-original="/uploads/photo/<?= $test->getImg() ?>" alt="<?= str_replace(array("'", '"'), "", $test->getName()) ?>" style="margin: 0 10px;"/>
        </a>
        <?php }
      else { ?>
        &nbsp;
      <?php } ?>
    </div>
    <div class="test-description">
      <div>
        <a href="/tests/<?= $test->getSlug() ?>">
          <span style="font: 14px/16px Tahoma,Geneva,sans-serif;"><?= $test->getName() ?></span>
        </a>
        <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
        <div class="yashare-wrapper" style="background: none; height: 22px; float:right;">
          <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div>
        </div>
      </div>
      <div id="rate_div" style="display: inline; position: relative; top: 4px;"></div>
      <span><?= $test->getVotesCount() ?></span>
      <script src="/js/jquery.starRating.js"></script>
      <script>
        $(document).ready(function () {
          $('#rate_div').starRating({
          basicImage  : '/images/star.gif',
          ratedImage : '/images/star_hover.gif',
          hoverImage : '/images/star_hover2.gif',
          ratingStars   : 10,
          ratingUrl       : '/tests/rate',
          paramId       :  'test',
          paramValue  : 'value',
          rating			  : '<?= $test->getRating() > 0 ? @round($test->getRating() / $test->getVotesCount()) : 0 ?>',
          customParams : {testId : '<?= $test->getId() ?>'},

          <? if (sfContext::getInstance()->getRequest()->getCookie("ratete_" . $test->getId())) { ?>
            clickable : false,
            hoverable : false
          <? } ?>

          });
        });
      </script>
      <div  style="min-height: 70px;">
        <p>
            <?= strip_tags($test->getContent()) ?>
        </p>
      </div>
      <div  style="height: 30px;">
        <div style="float: left;">Вопросов: <?= $test->Question->count() ?></div>
        <div style="float: right; margin-right: 10px;">Тест прошли: <?= $test->getWriting() == "" ? 0 : $test->getWriting() ?> человек</div>
      </div>
    </div>
</div>

<?php if ($sf_request->getParameter('page', 1) > count($pager->getLinks(20))) {
    if ($result): ?>
        <div class="test-question" style="font-size: 16px;">Результаты теста</div>
        <div style="min-height: 100px;">  <? echo $result->getResults(); ?></div>
        <a href="/tests/<?= $sf_request->getParameter('slug') ?>" class="red-btn colorWhite test-one-more-button" id="ButtonTest"><span style="width: 249px;">Пройти тест еще раз</span></a>
        <div class="test-one-more hide-mobile">Пройти ещё тест <img src="/newdis/images/strelka.png" alt="strelka"></div>
        <? if ($allTests->count() > 1): ?>
          <div class="promo-gallery-holder two-item test-result mobile-hide">
            <a href="#" class="prev" style="top:70px"></a>
            <a href="#" class="next" style="top:70px"></a>
            <div class="promo-gallery">
              <ul>
                <?php foreach ($allTests as $testDop): ?>
                  <?php if ($testDop->getId() != $test->getId()): ?>
                    <li>
                      <a href="/tests/<?= $testDop->getSlug() ?>" style="border: 0px;height: 170px; width: auto;">
                        <img src="/uploads/photo/thumbnails_250x250/<?= $testDop->getImg(); ?>" style="width: 186px;" alt="<?= $testDop->getName() ?>" />
                        <br />
                        <?= mb_substr(stripslashes($testDop->getName()), 0, 30, 'UTF-8') ?>
                        <?php if (mb_strlen($testDop->getName()) > 30) echo "..."; ?>
                      </a>
                    </li>

                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>


    <? endif;
}
 else { ?>
    <script>
      function probeAnswer() {
        $resultButton = true;
        $("#question ul").each(function (i, elem) {
          $questionSelect = false;
          $($("#question" + i + " li input")).each(function (i, elem) {
              if ($(elem).prop("checked"))
                  $questionSelect = true
          });
          if (!$questionSelect)
              $resultButton = false;
        });
        if ($resultButton)
          $("#ButtonTest").fadeIn();
      }
      $(document).ready(function () {

      });
    </script>
    <form action="/tests/<?= $sf_request->getParameter('slug') ?>?page=<?= ($pager->getPage()) + 1 ?>" method="POST" id="question">
      <?php
        $testQuestion = $pager->getResults();
        $indexQuestion = 0;
        foreach ($testQuestion as $question): ?>
            <div class="test-question"><?= $question->getNumber() ?>. <?= $question->getQuestion() ?></div>
            <ul style="list-style: none;padding-left: 20px;" id="question<?= $indexQuestion ?>">
              <?php foreach ($question->Answer as $answer): ?>
                <li style="margin-top: 5px;">
                  <input type="radio" name="answer[<?= $question->getId() ?>]" value="<?= $answer->getBalls() ?>" onclick="probeAnswer()" style="margin-top: 0px;" id="answer_<?= $question->getId() ?>_<?= $answer->getId() ?>" hidden />
                  <label for="answer_<?= $question->getId() ?>_<?= $answer->getId() ?>"><?= $answer->getAnswer() ?></label>
                </li>
              <?php endforeach; ?>
            </ul>
            <?php
              $indexQuestion = $indexQuestion + 1;
        endforeach; ?>
    </form>
    <?php if ($sf_request->getParameter('page', 1) == count($pager->getLinks(20))) { ?>
      <a onclick="$('#question').submit()" class="red-btn colorWhite test-one-more-button" style="display:none;" id="ButtonTest"><span style="width: 249px;">Результаты теста</span></a>
    <? }
    else if ($sf_request->getParameter('page', 1) < count($pager->getLinks(20))) { ?>
        <a onclick="$('#question').submit()" class="red-btn colorWhite test-one-more-button" style="display:none;" id="ButtonTest"><span style="width: 272px;">Следующая страница вопросов</span></a><?
    }
  } ?>
