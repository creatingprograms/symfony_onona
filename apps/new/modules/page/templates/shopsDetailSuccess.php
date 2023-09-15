 <?
  mb_internal_encoding('UTF-8');
  $h1=$shop->getName();
  slot('metaTitle', $h1);
  slot('metaKeywords', $h1);
  slot('metaDescription', $h1);
  slot('breadcrumbs', [
    $allShops,
    ['text' => $h1],
  ]);
  slot('h1', $h1);
  slot('catalog-class', 'shop-detail');
?>
<div class="wrap-block">
  <div class="container">
    <div class="shop-wrapper">
      <div class="left-side">
        <div class="h2">Подробнее о магазине</div>
        <?php if($shop->getAddress()): ?>
          <div class="label">Адрес:</div>
          <p><?= $shop->getAddress() ?></p>
        <?php endif ?>
        <?php if($shop->getPhone()): ?>
          <div class="label">Телефон:</div>
          <p><?= $shop->getPhone() ?></p>
        <?php endif ?>
        <?php if($shop->getWorktime()): ?>
          <div class="label"> Режим работы: </div>
          <p><?=$shop->getWorktime()?></p>
        <?php endif ?>
        <?php if($shop->getOutdescription()): ?>
          <div class="label">Схема проезда:</div>
          <p><?=$shop->getMetro()?> <?=$shop->getOutdescription()?></p>
        <?php endif ?>
        <?php if($haveStocks): ?>
          <p><a class="link-to-catalog" href="/shop-stocks/<?=$shop->getSlug()?>">Смотреть ассортимент</a></p>
        <?php endif ?>

      </div>
      <div class="right-side">
        <?php if( $shop->getLatitude() && $shop->getLongitude() ): ?>
          <div class="shop-item-map" id="mapShop" data-lat="<?= $shop->getLatitude() ?>" data-lon="<?= $shop->getLongitude() ?>"></div>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>
<? if(sizeof($images[1])) : ?>
  <div class="wrap-block">
    <div class="container">
      <div class="h2 block-title">Фотографии магазина</div>
      <div class="block-overview">
        <div class="swiper-container sl-overview-shop">
          <div class="swiper-wrapper">
            <? foreach ($images[1] as $imageSrc) :?>
              <div class="swiper-slide wrap-sl-item">
                <div class="overview-item">
                  <a href="<?= $imageSrc ?>" class="overview-item__img js-image-popup" style="background-image:url('<?= $imageSrc ?>');">
                    <?/*<img src="<?= $imageSrc ?>" alt="">*/?>
                  </a>
                  <?/*<div class="overview-item__tag">Обзор</div>
                  <div class="overview-item__text sl-text">Эта секс-игрушка для внутренних эрогенных зон. </div>
                  <a href="#" class="link-text">Полный обзор</a>*/?>
                </div>
              </div>
            <? endforeach ?>
          </div>
        </div>
        <div class="wrap-nav">
          <div class="swiper-button-prev custom-nav">
            <svg>
              <use xlink:href="#arr-sl-l"></use>
            </svg>
          </div>
          <div class="swiper-button-next custom-nav">
            <svg>
              <use xlink:href="#arr-sl-r"></use>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
<? endif ?>

<? include_component("page", "subpage", array( 'page'=>'advantages-item')); ?>
<?/*
  <div class="shop-item-review">
    <div class="shop-item-review-h">
      отзывы о магазине
    </div>
    <div class="shop-item-review-call-form">
      <a href="#formShopItemReview" class="downLinkJS ">Оставьте свой отзыв или вопрос</a>
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

        <div class="but -big js-submit-button">добавить отзыв</div>
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
*/?>
