<?php if ($catalog=='main' || $catalog=='inner'): ?>
  <? if(count($list)) :?>
    <div class="wrap-block wrap-block_top">
      <div class="container">
        <div class="h2 block-title ">Новости <a href="/news" class="link-text">Все новости</a></div>
        <div class="block-catalog">
          <div class="swiper-container sl-news">
            <div class="swiper-wrapper">
              <? foreach ($list as $article) :?>
                <?
                  // $category=$article->getCategoryArticles();
                  $img = "/uploads/news/".$article->getPhotoUrl();
                  if(!$article->getPhotoUrl() || !file_exists($_SERVER['DOCUMENT_ROOT'].$img)) {
                    // $img = "/uploads/news/".$article->getPhotoUrl();
                      $img='/frontend/images/no-image.png';
                  }
                ?>
                <a href="/news/<?=$article->getSlug()?>" class="swiper-slide sexopedy-element">
                  <span class="img-wrapper">
                    <img src="<?= $img ?>" alt="<?=$article->getName()?>">
                  </span>
                  <span class="article-cat-name"><?=date('d.m.Y', strtotime($article->getCreatedAt()))?></span>
                  <span class="article-name"><?=$article->getName()?></span>
                  <br>
                  <span>Подробнее ></span>
                </a>
              <? endforeach ?>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </div>
    </div>
  <? endif ?>
<? else : ?>

<?php endif; ?>
