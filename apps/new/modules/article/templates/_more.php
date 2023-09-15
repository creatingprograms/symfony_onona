<div class="wrap-block wrap-block-other">
  <div class="container">
    <div class="h2 block-title">Статьи на другие темы</div>
    <div class="articles-list">
      <? foreach ($list as $article):?>
        <div class="overview-item article-item">
          <a href="/sexopedia/<?= $article->getSlug() ?>" class="overview-item__img">
            <img src="/uploads/photo/<?= $article->getImg() ?>" alt="">
          </a>
          <?
            $category=$article->getCategoryArticles()
            // $category[0]->getArticlecategory()->getSlug();
          ?>
          <a href="/sexopedia/category/<?=$category[0]->getArticlecategory()->getSlug()?>" class="overview-item__tag"><?=$category[0]->getArticlecategory()->getName()?></a>
          <a href="/sexopedia/<?= $article->getSlug() ?>" class="overview-item__text"><?= $article->getName() ?></a>
        </div>
      <? endforeach ?>
    </div>
  </div>
</div>
