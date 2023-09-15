<?php
slot('metaTitle', "Любовные тесты онлайн | Сеть магазинов «Он и Она»" . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
slot('metaKeywords', "Любовные тесты онлайн");
slot('metaDescription', "Любовные тесты онлайн. На страницах секс-шопа «Он и Она» вы найдете много интересного.");
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";

slot('canonical', "/lovetest" . $canonDop);
$h1='Любовные тесты онлайн';
slot('breadcrumbs', [
  ['text' => $h1],
]);

?>
<div class="wrapper wrap-cont -video -clf">
  <main class="cont-right">
    <div class="test-page">
      <div class="v-block-plate -int">
        <h1>Любовные тесты на сексуальную тематику онлайн</h1>
      </div>
      <? include_component('category', 'catalogsorters',
          array(
            'link'=> '/lovetest',
            'sortOrder'=>$sortOrder,
            'direction'=>false,
            'set'=>2, //набор правил сортировки
          )
      );?>
      <? $tests = $pager->getResults(); ?>
      <div class="test-list -clf">
        <!------------------------- page data ----------------------------->
        <?php foreach ($tests as $test): ?>
          <?php
            $rating = round(($test->getRating() / $test->getVotesCount())/ 2)+0.1;
          ?>
          <div class="test-item">
            <a href="/tests/<?= $test->getSlug() ?>" class="test-img">
              <?
                $image=false;
                if($test->getImg() && file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/photo/'.$test->getImg()))
                  $image='/uploads/photo/'.$test->getImg();
              ?>
              <img src="<?= $image ? $image : '/frontend/images/no-image.png' ?>" alt="<?= str_replace(array("'", '"'), "", $test->getName()) ?>">
            </a>
            <div class="test-wrap">

              <h2>
                <a href="/tests/<?= $test->getSlug() ?>">
                  <?= $test->getName() ?>
                </a>
              </h2>

              <div class="rating">
                <?php for ($i=1; $i<6; $i++): ?>
                  <div class="rating-item<?= $i < $rating ? " -isActive": ''?>">
                    <svg>
                      <use xlink:href="#rateItemIcon" />
                    </svg>
                  </div>
                <?php endfor; ?>

              </div>

              <div class="test-title">
                <p><?= strip_tags($test->getContent()) ?></p>
              </div>
              <div class="test-foot">
                <p>Вопросов: <?= $test->Question->count() ?></p>
                <p>Тест прошли: <?= $test->getWriting() == "" ? 0 : $test->getWriting() ?> человек</p>
              </div>
              <div class="test-more">
                <a href="/tests/<?= $test->getSlug() ?>">Начать</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <!------------------------- page data ----------------------------->
      </div>
      <?php if ($pager->haveToPaginate()):?>
        <?
          if($sortOrder!="")
            $sortingUrl="&sortOrder=".$sortOrder."&direction=".$direction;
          else
            $sortingUrl='';
        ?>

        <? include_component("noncache", "pagination", array(
          'pager' => $pager,
          'sortingUrl' => $sortingUrl,
          'baselink' => url_for('tests_general'),
          'show_more' => true,
          'numbers' => true,
        )); ?>

      <?php endif; ?>

    </div>
  </main>
  <? include_component("page", "testsSidebar", array('sf_cache_key' => 'testsSidebar')); ?>
</div>
