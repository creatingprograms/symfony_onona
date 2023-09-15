<div class="product-card-row shop-item-review">
  <div class="shop-item-review-h">
    отзывы
  </div>
  <div class="shop-item-review-call-form">
    <a href="#formShopItemReview" class="downLinkJS">Оставьте свой отзыв или вопрос</a>
  </div>
  <form action="/product/add-comment" class="form form-review -none js-ajax-form" id="formShopItemReview">
    <input type="hidden" name="sf_guard_user[_csrf_token]" value="<?php echo $csrf ?>">
    <div class="rating -chose">
      <? for ($i=0; $i<5; $i++) :?>
        <div class="rating-item -isActive">
          <svg>
            <use xlink:href="#rateItemIcon" />
          </svg>
        </div>
      <? endfor ?>
      <input type="hidden" name="rating" value="10" id="rating">
    </div>
    <div class="form-row">
      <div class="form-label">
        ФИО:*
      </div>
      <input type="text" value="<?$sf_user->isAuthenticated() ? $sf_user->getGuardUser()->getFirstName() .' '.$sf_user->getGuardUser()->getLastName()  : ''?>" name="fio" placeholder="Константин Константинович Константинопольский">
    </div>
    <div class="form-row">
      <div class="form-label">
        E-mail:*
      </div>
      <input type="email" value="<?= $sf_user->isAuthenticated() ? $sf_user->getGuardUser()->getEmailAddress() : ''?> " placeholder="Введите ваш e-mail" name="email" <?=$sf_user->isAuthenticated() ? ' readonly' : ''?> class="js-rr-send">
      <div class="form-hint">
        E-mail не публикуется при отзыве и необходим для отсеивания искусственных отзывов, оставляемых роботами.
      </div>
    </div>
    <div class="form-row">
      <div class="form-label">
        Комментарий:*
      </div>
      <textarea placeholder="Очень классный товар, рекомендую!" name="comment"></textarea>
    </div>
    <div class="form-row form-review-subm">
      <div class="form-review-consent">
        <input type="hidden" name="id" value="<?= $product->getId() ?>">
        <input type="checkbox" class="styleCH" name="agreement" id="val-rev-1" value="1">
        <label for="val-rev-1">Я принимаю условия <a href="/personal_accept">Пользовательского соглашения</a></label>
      </div>
      <?/*<div class="form-capcha">
        <div class="form-capcha-label">
          4534535
        </div>
        <input type="text" name="" value="" placeholder="Введите символы">
      </div>*/?>
      <div class="but -big js-submit-button">добавить отзыв</div>
      <?/*<input type="submit" value="добавить отзыв" class="but -big">*/?>
    </div>
  </form>
  <? if (sizeof($comments)) :?>
    <div class="review-list review-list-down" id="reviewList">
      <?php foreach ($comments as $comment): ?>
        <div class="review-list-item" itemprop="review" itemscope itemtype="http://schema.org/Review">
          <div class="review-list-plate">
            <meta itemprop="itemReviewed" content = "<?= $product->getName() ?>">
            <?
              $rating = round($comment->getRateSet() > 0 ? $comment->getRateSet() / 2 : ($product->getRating() > 0 ? $product->getRating() / $product->getVotesCount() / 2  : 0))+0.1;
              if($rating>5) $rating=5
            ?>
            <div class="rating" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
              <meta itemprop="worstRating" content = "1">
              <meta itemprop="ratingValue" content = "<?= round($rating)?>">
              <meta itemprop="bestRating" content = "5">

              <? for ($i=1; $i<6; $i++) :?>
                <div class="rating-item<?= $i < $rating ? ' -isActive' : ''?>">
                  <svg>
                    <use xlink:href="#rateItemIcon" />
                  </svg>
                </div>
              <? endfor ?>
            </div>
            <b class="review-list-name" itemprop="author">
              <?php if ($comment->getUsername() != ""): ?>
                  <?= htmlspecialchars($comment->getUsername()) ?>
              <?php else: ?>
                <?
                  $author = htmlspecialchars($comment->getSfGuardUser()->getFirstName() != "" ? $comment->getSfGuardUser()->getFirstName() : $comment->getSfGuardUser()->getLastName());
                  if($author=='') $author='Анонимно';
                ?>
                  <?= $author ?>
              <?php endif; ?>
            </b>
            <span class="review-list-date" itemprop="datePublished" content="<?= date("Y-m-d", strtotime($comment->getCreatedAt())) ?>"><?= date("d.m.Y", strtotime($comment->getCreatedAt())) ?></span>
          </div>
          <div class="review-list-desc" itemprop="description">
            <p><?= $comment->getText() ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <? if(sizeof($comments) > 3): ?>
      <div class="review-list-all">
        <a href="#reviewList" class="allButJS" data-all="Свернуть отзывы">
          <span>Смотреть больше отзывов</span>
          <svg>
            <use xlink:href="#arrowMoreIcon" />
          </svg>
        </a>
      </div>
    <? endif ?>
  <? else :?>
    <div class="review-list review-list-down" id="reviewList">
      Об этом товаре отзывов пока нет. Будьте первым.
    </div>
  <? endif?>
  <? slot('comments-count', sizeof($comments)) ?>
</div>
