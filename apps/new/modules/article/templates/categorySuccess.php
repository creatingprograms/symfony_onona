<?php
$slug='';
if ($category) {
    $startTitle = $category->getName();
    slot('canonical', '/sexopedia/category/'.$category->getSlug());
    $h1=$category->getName();
    $slug=$category->getSlug();
}else{
  $h1 = $startTitle = $categoryName;
}
slot('metaTitle', $startTitle . " * Энциклопедия секса * Главная");
slot('metaKeywords', $startTitle . " * Энциклопедия секса * Главная");
slot('metaDescription', $startTitle . " * Энциклопедия секса * Главная");
slot('breadcrumbs', [
  ['link'=>'/sexopedia', 'text' => 'Энциклопедия секса'],
  ['text' => $h1],
]);
slot('h1', $h1);
$articles = $pager->getResults();
if(sizeof($articles)):
?>
<div class="wrap-block">
  <div class="container">
    <? include_component("article", "topMenu", array('sf_cache_key' => 'articlesTopMenu', 'active' => $slug)); ?>

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
            foreach($cat as $currentCat){
              $artCat = $currentCat;
              if($currentCat->getArticlecategory()->getIsPublic()) break;
            }
          ?>
          <div class="overview-item article-item">
            <a href="/sexopedia/<?=$article->getSlug()?>" class="overview-item__img">
              <img src="<?=$img?>" alt="<?= $article->getName()?>">
            </a>
            <a href="/sexopedia/category/<?=$artCat->getArticlecategory()->getSlug()?>" class="overview-item__tag"><?=$artCat->getArticlecategory()->getName()?></a>
            <a href="/sexopedia/<?=$article->getSlug()?>" class="overview-item__text"><?= $article->getName()?></a>
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
          'baselink' => '/sexopedia/category/'.$category->getSlug(),
          'show_more' => false,
          'numbers' => true,
        )); ?>

      <?php endif; ?>
    <?php endif; ?>

  </div>
</div>
<? endif ?>
