 <?
  mb_internal_encoding('UTF-8');
  slot('metaTitle', $shop->getName());
  slot('metaKeywords', $shop->getName());
  slot('metaDescription', $shop->getName());
  slot('breadcrumbs', [
    $allShops,
    ['text' => $shop->getName()],
  ]);
?>
<main class="wrapper">
  <div class="shop-item">
    <div class="shop-item-top">
      <div class="shop-item-info">
        <div class="shop-item-plate">
          <h1><?= $shop->getName() ?></h1>
        </div>
        <div class="shop-item-info-desc">
          <?php if($shop->getAddress()): ?>
            <div class="shop-item-group">
              <p>
                <b>Адрес:</b>
              </p>
              <p><?= $shop->getAddress() ?></p>
            </div>
          <?php endif ?>
          <?php if($shop->getWorktime()): ?>
            <div class="shop-item-group">
              <b>Режим работы:</b> <?=$shop->getWorktime()?>
            </div>
          <?php endif ?>
          <?php if($shop->getOutdescription()): ?>
            <div class="shop-item-group">
              <p>
                <b>Схема проезда:</b>
              </p>
              <p><?=$shop->getMetro()?> <?=$shop->getOutdescription()?></p>
            </div>
          <?php endif ?>
        </div>
      </div>
      <?php if( $shop->getLatitude() && $shop->getLongitude() ): ?>
        <div class="shop-item-map" id="map" data-lat="<?= $shop->getLatitude() ?>" data-lon="<?= $shop->getLongitude() ?>"></div>
      <?php endif ?>
    </div>
    <? if(sizeof($images[1])) : ?>
      <div class="shop-item-slider -typeSlider">
        <div class="swiper-container">
          <div class="swiper-wrapper">
            <? foreach ($images[1] as $imageSrc) :?>
              <div class="swiper-slide">
                <img src="<?= $imageSrc ?>">
              </div>
            <? endforeach ?>
          </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev">
          <svg>
            <use xlink:href="#backArrowIcon" />
          </svg>
        </div>
        <div class="swiper-button-next">
          <svg>
            <use xlink:href="#backArrowIcon" />
          </svg>
        </div>
      </div>
    <? endif ?>
    <div class="shop-item-review">
      <div class="shop-item-review-h">
        отзывы о магазине
      </div>
      <div class="shop-item-review-call-form">
        <a href="#formShopItemReview" class="downLinkJS <?/*-isVis*/?>">Оставьте свой отзыв или вопрос</a>
      </div>
      <form action="/shops/add-comment" class="form form-review js-ajax-form" id="formShopItemReview" style="display:none;">
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
          <input type="email" value="<?= $sf_user->isAuthenticated() ? $sf_user->getGuardUser()->getEmailAddress() : ''?>" placeholder="Введите ваш e-mail" name="email" <?=$sf_user->isAuthenticated() ? ' readonly' : ''?> class="js-rr-send">
          <div class="form-hint">
            E-mail не публикуется при отзыве и необходим для отсеивания искусственных отзывов, оставляемых роботами.
          </div>
        </div>
        <div class="form-row">
          <div class="form-label">
            Комментарий:*
          </div>
          <textarea placeholder="Очень классный магазин, рекомендую!" name="comment"></textarea>
        </div>
        <div class="form-row form-review-subm">
          <div class="form-review-consent">
            <input type="hidden" name="id" value="<?= $shop->getId() ?>">
            <input type="checkbox" class="styleCH" name="agreement" id="val-rev-1" value="1">
            <label for="val-rev-1">Я принимаю условия <a target="_blank" href="/personal_accept">Пользовательского соглашения</a></label>
          </div>
          <?/*
          <div class="form-capcha">
            <div class="form-capcha-label">
              4534535
            </div>
            <input type="text" name="" value="" placeholder="Введите символы">
          </div>*/?>
          <div class="but -big js-submit-button">добавить отзыв</div>
          <?/*<input type="submit" value="добавить отзыв" class="but -big">*/?>
        </div>
      </form>
      <?php if(isset($comments) && $comments->count()) :?>
        <div class="review-list review-list-down" id="reviewList">
          <?php foreach ($comments as $comment2) :?>
            <div class="review-list-item">
              <div class="review-list-plate">
                <b class="review-list-name"><?= $comment2->getUsername() ?></b>

                <span class="review-list-date"><?= date('d.m.Y', strtotime($comment2->getCreatedAt()))?></span>
              </div>
              <div class="review-list-desc">
                <p><?=nl2br($comment2->getText())?></p>
              </div>
            </div>
          <?php endforeach ?>
        </div>
        <? if($comments->count()>3) :?>
          <div class="review-list-all">
            <a href="#reviewList" class="allButJS" data-all="Свернуть отзывы">
              <span>Смотреть больше отзывов</span>
              <svg>
                <use xlink:href="#arrowMoreIcon" />
              </svg>
            </a>
          </div>
        <?php endif ?>
      <?php endif ?>
    </div>
  </div>
</main>
