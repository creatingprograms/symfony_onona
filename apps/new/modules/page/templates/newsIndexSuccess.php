<?php
slot('metaTitle', "Новости | Секс-шоп «Он и Она»");
slot('metaKeywords', "Новости");
slot('metaDescription', "Новости");
$h1 = 'Новости';
slot('breadcrumbs', [
  ['text' => $h1],
]);
slot('h1', $h1);
$i=0;

$articles = $pager->getResults();
if(sizeof($articles)):?>
  <div class="wrap-block">
    <div class="container">
      <?php if (count($articles)): ?>
        <div class="articles-list news-list">
          <!------------------------- page data ----------------------------->
          <?php foreach ($articles as $article): ?>
            <?
              $img = "/uploads/news/".$article->getPhotoUrl();
              if(!$article->getPhotoUrl() || !file_exists($_SERVER['DOCUMENT_ROOT'].$img)) {
                $img='/frontend/images/no-image.png';
              }
            ?>
            <div class="sexopedy-element news-element" data-i="<?=$i++?>">
              <a href="/news/<?=$article->getSlug()?>" class="">
                <img src="<?=$img?>" alt="<?= $article->getName()?>">
              </a>
              <span class="article-cat-name"><?=date('d.m.Y', strtotime($article->getCreatedAt()))?></span>
              <a href="/news/<?=$article->getSlug()?>" class="article-name"><?= $article->getName()?></a>
              <br>
              <a href="/news/<?=$article->getSlug()?>" class="article-name">Подробнее ></a>
            </div>
          <?php endforeach; ?>
          <? for($j=0; $j<(12-$i); $j++):?>
          <div class="sexopedy-element news-element empty"></div>
          <? endfor ?>
          <!------------------------- page data ----------------------------->
        </div>
        <?php if ($pager->haveToPaginate()):?>
          <? include_component("noncache", "pagination", array(
            'pager' => $pager,
            'sortingUrl' => '',
            'baselink' => '/news',
            'show_more' => false,
            'numbers' => true,
          )); ?>
        <?php endif; ?>

      <?php endif; ?>
    </div>
  </div>
<? endif ?>
