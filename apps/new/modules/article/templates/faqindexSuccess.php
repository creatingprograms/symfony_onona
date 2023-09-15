<?php
slot('metaTitle', "FAQ | Секс-шоп «Он и Она»");
slot('metaKeywords', "FAQ , частые вопросы о сексе");
slot('metaDescription', "FAQ. вопросы о сексуальном здоровье, о взаимоотношениях в семье, о сексе и любви ");
$h1 = 'FAQ';
slot('breadcrumbs', [
  ['text' => $h1],
]);
slot('h1', $h1);

$articles = $pager->getResults();
if(sizeof($articles)):
?>
<div class="wrap-block">
  <div class="container">
    <? //include_component("article", "topMenu", array('sf_cache_key' => 'faqTopMenu', 'active' => '', 'type'=>'faq')); ?>

    <?php if (count($articles)): ?>
      <div class="articles-list">
        <!------------------------- page data ----------------------------->
        <?php foreach ($articles as $article): ?>
          <?
            // $cat=$article->getCategoryArticles();
            $img = "/uploads/photo/".$article->getImgPreview();
            if(!$article->getImgPreview() || !file_exists($_SERVER['DOCUMENT_ROOT'].$img)) {
              $img = "/uploads/photo/".$article->getImg();
              if(!$article->getImg() || !file_exists($_SERVER['DOCUMENT_ROOT'].$img))
                $img='/frontend/images/no-image.png';
            }
          ?>
          <div class="overview-item article-item">
            <a href="/faq/<?=$article->getSlug()?>" class="overview-item__img">
              <img src="<?=$img?>" alt="<?= $article->getName()?>">
            </a>
            <?/*<a href="/faq/category/<?=$cat[0]->getArticlecategory()->getSlug()?>" class="overview-item__tag"><?=$cat[0]->getArticlecategory()->getName()?></a>*/?>
            <a href="/faq/<?=$article->getSlug()?>" class="overview-item__text"><?= $article->getName()?></a>
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
          'baselink' => '/faq/',
          'show_more' => false,
          'numbers' => true,
        )); ?>

      <?php endif; ?>

    <?php endif; ?>

  </div>
</div>
<? endif ?>
