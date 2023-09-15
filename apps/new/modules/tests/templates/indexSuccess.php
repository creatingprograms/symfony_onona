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
slot('h1', $h1);
slot('catalog-class', 'page-test');
$tests = $pager->getResults();
?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <? include_component('category', 'catalogsorters',
          array(
            'link'=> '/lovetest',
            'sortOrder'=>$sortOrder,
            'direction'=>false,
            'set'=>2, //набор правил сортировки
          )
      );?>
      <div class="test-list">
        <!------------------------- page data ----------------------------->
        <?php foreach ($tests as $test): ?>
          <?php
            $rating = round(($test->getRating() / $test->getVotesCount())/ 2+0.1);
            if(!$rating) $rating=4;
            if($test->getImg() && file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/photo/'.$test->getImg()))
              $image='/uploads/photo/'.$test->getImg();
            else
              $image='/frontend/images/no-image.png';
          ?>
          <div class="test-item">
            <div class="test-item__img" style="background-image: url(<?=$image?>)"></div>
            <div class="test-item__content">
              <div class="wrap-rating-star" rating="<?=$rating?>">
                <span class="rating-star"></span>
                <span class="rating-star"></span>
                <span class="rating-star"></span>
                <span class="rating-star"></span>
                <span class="rating-star"></span>
              </div>
              <div class="test-item__title"><?= $test->getName() ?></div>
              <div class="test-item__text"><?= ILTools::GetShortText(strip_tags($test->getContent()), 500) ?></div>
              <div class="test-item__bottom">
                <a href="/tests/<?= $test->getSlug() ?>" class="btn-full btn-full_rad">Начать</a>
                <div class="test-item__info">
                  <span>Вопросов: <?= $test->Question->count() ?></span>
                  <span>Тест прошли: <?= ILTools::getWordForm($test->getWriting(), 'человек')?></span>
                </div>
              </div>
            </div>
        </div>
        <? endforeach ?>
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
          'show_more' => false,
          'numbers' => true,
        )); ?>

      <?php endif; ?>
    </div>
  </div>
</div>
