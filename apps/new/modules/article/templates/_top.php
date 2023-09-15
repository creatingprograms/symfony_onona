<?php if ($catalog=='main' || $catalog=='inner'): ?>
  <? if(count($list)) :?>
    <div class="wrap-block wrap-block_top <?= $catalog=='inner' ? 'wrap-block__article-grey' : ''?>">
      <div class="container">
        <?php if ($catalog=='inner'): ?>
          <div class="h2 block-title ">Статьи на другие темы</div>
        <? else :?>
          <div class="h2 block-title ">Сексопедия <a href="/sexopedia" class="link-text">Все статьи</a></div>
        <?php endif; ?>
        <div class="block-catalog">
          <div class="swiper-container sl-sexopedy">
            <div class="swiper-wrapper">
              <? foreach ($list as $article) :?>
                <?
                  $category=$article->getCategoryArticles();
                  $img = "/uploads/photo/".$article->getImgPreview();
                  if(!$article->getImgPreview() || !file_exists($_SERVER['DOCUMENT_ROOT'].$img)) {
                    $img = "/uploads/photo/".$article->getImg();
                    if(!$article->getImg() || !file_exists($_SERVER['DOCUMENT_ROOT'].$img)) 
                      $img='/frontend/images/no-image.png';
                  }
                ?>
                <a href="/sexopedia/<?=$article->getSlug()?>" class="swiper-slide sexopedy-element">
                  <span class="img-wrapper">
                    <img src="<?= $img ?>" alt="<?=$article->getName()?>">
                  </span>
                  <span class="article-cat-name"><?=$category[0]->getArticlecategory()->getName()?></span>
                  <span class="article-name"><?=$article->getName()?></span>
                  <span class="article-bottom">
                    <span class="article-date">
                      <svg>
                        <use xlink:href="#article-date" />
                      </svg>
                      <?=date('d.m', strtotime($article->getCreatedAt()))?>
                    </span>
                    <span class="article-views">
                      <svg>
                        <use xlink:href="#article-views" />
                      </svg>
                      <?= $article->getViewsCount() ?>
                    </span>
                  </span>
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
