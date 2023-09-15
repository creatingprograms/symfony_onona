<?php
slot('metaTitle', "Обзоры | Секс-шоп «Он и Она»");
slot('metaKeywords', "Обзоры");
slot('metaDescription', "Обзоры");
$h1 = 'Обзоры';
slot('breadcrumbs', [
  ['text' => $h1],
]);
slot('h1', $h1);
$i=0;
$folder="/uploads/photo/thumbnails_250x250/";

$reviews = $pager->getResults();
//if(sizeof($reviews)):?>
  <div class="wrap-block">
    <div class="container">
        <div class="reviews-list">
          <? include_component('noncache', 'reviewsFilter', ['filter' => $filters, 'page' => $page]); ?>

          <?php if (count($reviews)): ?>
            <!------------------------- page data ----------------------------->
            <?php foreach ($reviews as $review): ?>
              <?
                $product=$review->getProduct();
                $image=$product->getProductPhotoalbums()[0]->getPhotoalbum()->getPhotos()[0]->getFilename();
                if(!$image || !file_exists($_SERVER['DOCUMENT_ROOT'].$folder.$image))
                  $image="/frontend/images/no-image.png";
                else $image=$folder.$image;
              ?>
              <div class="reviews-list__element">
                <?php if ($product->getIsPublic()): ?>
                  <a class="reviews-list__image" href="/product/<?=mb_strtolower($product->getSlug())?>">
                    <img src="<?= $image ?>" alt="<?$product->getName()?>">
                  </a>
                <?php else :?>
                  <div class="reviews-list__image">
                    <img src="<?= $image ?>" alt="<?$product->getName()?>">
                  </div>
                <?php endif; ?>
                <div class="reviews-list__text_wrapper">
                  <div class="reviews-list__name"><?= $review->getUsername() ? $review->getUsername() : 'Анонимно'?></div>
                  <div class="reviews-list__date"><?= date('d.m.Y',strtotime($review->getCreatedAt()))?></div>
                  <div class="reviews-list__text">
                    <?= $review->getText()?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
            <!------------------------- page data ----------------------------->
          <?php endif; ?>
        </div>
        <?php if ($pager->haveToPaginate()):?>
          <? include_component("noncache", "pagination", array(
            'pager' => $pager,
            'sortingUrl' => '',
            'baselink' => '/reviews',
            'show_more' => false,
            'numbers' => true,
            'class' => 'js-pagination',
          )); ?>
        <?php endif; ?>


    </div>
  </div>
<? //endif ?>
