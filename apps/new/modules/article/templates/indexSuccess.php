<?php
slot('metaTitle', "Сексопедия. Энциклопедия секса | Секс-шоп «Он и Она»");
slot('metaKeywords', "сексопедия , все о сексе энциклопедия");
slot('metaDescription', "Сексопедия. Статьи о сексуальном здоровье, о взаимоотношениях в семье, о сексе и любви ");
$h1 = 'Энциклопедия секса - Sexopedia 18+';
slot('breadcrumbs', [
  ['text' => $h1],
]);
slot('h1', $h1);

$articles = $pager->getResults();
if(sizeof($articles)):
?>
<div class="wrap-block">
  <div class="container">
    <? include_component("article", "topMenu", array('sf_cache_key' => 'articlesTopMenu', 'active' => '')); ?>

    <?php if (count($articles)): ?>
      <div class="articles-list">
        <!------------------------- page data ----------------------------->
        <?php foreach ($articles as $article): ?>
          <?
            $cat=$article->getCategoryArticles();
            $img = "/uploads/photo/".$article->getImgPreview();
            if(!$article->getImgPreview() || !file_exists($_SERVER['DOCUMENT_ROOT'].$img)) {
              $img = "/uploads/photo/".$article->getImg();
              if(!$article->getImg() || !file_exists($_SERVER['DOCUMENT_ROOT'].$img))
                $img='/frontend/images/no-image.png';
            }
          ?>
          <div class="overview-item article-item">
            <a href="/sexopedia/<?=$article->getSlug()?>" class="overview-item__img">
              <img src="<?=$img?>" alt="<?= $article->getName()?>">
            </a>
            <a href="/sexopedia/category/<?=$cat[0]->getArticlecategory()->getSlug()?>" class="overview-item__tag"><?=$cat[0]->getArticlecategory()->getName()?></a>
            <a href="/sexopedia/<?=$article->getSlug()?>" class="overview-item__text"><?= $article->getName()?></a>
          </div>
        <?php endforeach; ?>
        <!------------------------- page data ----------------------------->
      </div>

    <?php endif; ?>

  </div>
</div>
<? endif ?>
