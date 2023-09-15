<?
  $rating = round(($product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0) / 2 +0.1);
  if(!$rating || $rating > 5) $rating=5;
  if($rating < 1) $rating=1;
  $agregate=0;
  $i=0;
?>
<div class="features-tab__content tab__content">
  <div class="h2">Отзывы</div>
  <br>
  <div class="block-overall-rating">
    <?/*<div class="column">
      <div class="rating-number"><span><?=number_format($rating, 1, '.', '')?></span> <span>/</span> <span>5.0</span></div>
      <div class="wrap-rating-star" rating="<?=$rating?>">
        <span class="rating-star"></span>
        <span class="rating-star"></span>
        <span class="rating-star"></span>
        <span class="rating-star"></span>
        <span class="rating-star"></span>
      </div>
    </div>*/?>
    <div class="column">
      <p>Оставьте свой отзыв о товаре: <br> <?=$product->getName()?></p>
      <a href="#write-review" class="btn-full">Написать отзыв</a>
    </div>
  </div>
  <? if (sizeof($comments)) :?>
    <div class="list-reviews">
      <?php foreach ($comments as $comment): ?>
        <?
          $rating = round($comment->getRateSet() > 0 ? $comment->getRateSet() / 2 : ($product->getRating() > 0 ? $product->getRating() / $product->getVotesCount() / 2  : 0))+0.1;
          if($rating>5) $rating=5;
          if($rating<1) $rating=1;
          $agregate+=round($rating);
        ?>
        <div class="review-item"  itemprop="review" itemscope itemtype="http://schema.org/Review">
          <div class="review-item__top">
            <div class="column">
              <div class="review-item__name" itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="Name">
                <?
                  $authorName=htmlspecialchars($comment->getUsername());
                  if(!$authorName) $authorName= htmlspecialchars($comment->getSfGuardUser()->getFirstName() != "" ? $comment->getSfGuardUser()->getFirstName() : $comment->getSfGuardUser()->getLastName());
                  if(!$authorName) $authorName='Анонимно';
                  echo $authorName;
                ?>
                <?php /*if ($comment->getUsername() != ""): ?>
                    <?= htmlspecialchars($comment->getUsername()) ?>
                <?php else: ?>
                    <?= htmlspecialchars($comment->getSfGuardUser()->getFirstName() != "" ? $comment->getSfGuardUser()->getFirstName() : $comment->getSfGuardUser()->getLastName()) ?>
                <?php endif; */?>
              </span></div>
              <div class="review-item__date" itemprop="datePublished" content="<?= date("Y-m-d", strtotime($comment->getCreatedAt())) ?>"><?= date("d.m.Y", strtotime($comment->getCreatedAt())) ?></div>
            </div>
            <div class="column"<?/* itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"*/?>>
              <?/*
              <meta itemprop="worstRating" content = "1">
              <meta itemprop="ratingValue" content = "<?= round($rating)?>">
              <meta itemprop="bestRating" content = "5">
              <div class="wrap-rating-star" rating="<?=$rating?>">
                <span class="rating-star active"></span>
                <span class="rating-star active"></span>
                <span class="rating-star active"></span>
                <span class="rating-star active"></span>
                <span class="rating-star"></span>
              </div>*/?>
            </div>
          </div>
          <div class="review-item__bottom">
            <div class="review-item__content" itemprop="description">
              <p><?= $comment->getText() ?></p>
            </div>
            <div class="btn-show-more" text-main="Развернуть отзыв" text-replace="Свернуть отзыв">
              <span>Развернуть отзыв</span>
              <svg>
                <use xlink:href="#single-arrow"></use>
              </svg>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?/*
    <div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
      <meta itemprop="ratingValue" content="<?= $agregate/sizeof($comments) ?>">
      <meta itemprop="reviewCount" content="<?= sizeof($comments) ?>">
    </div>*/?>
  <? endif ?>
  <?/*
    <div class="wrap-btn">
      <div class="btn-full btn-full_gray">Показать все отзывы</div>
    </div>
  */?>
  <div id="write-review" class="block-feedback catalog-feedback">
    <div class="h2">Написать отзыв</div>
    <?/*<div class="wrap-rating-star put-rating">
      <span class="rating-star"></span>
      <span class="rating-star"></span>
      <span class="rating-star"></span>
      <span class="rating-star"></span>
      <span class="rating-star"></span>
    </div>*/?>
    <form action="/product/add-comment" class="form form-feedback js-ajax-form">
      <!-- в .input-rating записывается поставленная оценка]  -->
      <input class="input-rating" type="hidden" name="rating" value="10">
      <input type="hidden" name="id" value="<?=$product->getId()?>">
      <div class="group-field">
        <div class="field field-default">
          <label for="comm-fio">ФИО:*</label>
          <input id="comm-fio" type="text" placeholder="" value="<?$sf_user->isAuthenticated() ? $sf_user->getGuardUser()->getFirstName() .' '.$sf_user->getGuardUser()->getLastName()  : ''?>" name="fio">
        </div>
        <div class="field field-default">
          <label for="comm-email">E-mail:*</label>
          <input id="comm-email" type="email" placeholder=""  value="<?= $sf_user->isAuthenticated() ? $sf_user->getGuardUser()->getEmailAddress() : ''?> " name="email" <?=$sf_user->isAuthenticated() ? ' readonly' : ''?> class="js-rr-send">
        </div>
      </div>
      <div class="field field-default">
        <label for="comm-comm">Комментарий:*</label>
        <textarea name="comment" id="comm-comm" cols="30" rows="5" placeholder=""></textarea>
      </div>
      <br>
      <div class="custom-check">
        <div class="check-check_block">
          <input type="checkbox" name="agreement" class="check-check_input" value="1" id="agreement-review" checked="">
          <div class="custom-check_shadow"></div>
        </div>
        <label for="agreement-review" class="custom-check_label">Я принимаю условия <a href="/personal_accept" target="_blank">Пользовательского соглашения</a></label>
      </div>
      <div class="wrap-btn">
        <button class="btn-full btn-form js-submit-button">Добавить отзыв</button>
      </div>
    </form>
  </div>
</div>
<? slot('comments-count', sizeof($comments)) ?>
