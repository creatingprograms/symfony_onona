<?
global $isTest;
$h1 = $product->getH1() != "" ? stripslashes($product->getH1()) : stripslashes($product->getName());
if ($canonical) slot('canonical', '/product/' . $canonical);
slot('caltat', 0);
slot('caltat_product', $product->getId());
slot('advcake', 2);
slot('advcake_detail', [
  'product' => [
    'id' => $product->getId(),
    'name' => $product->getName(),
    'price' => $product->getPrice(),
  ],
  'category' => [
    'id' => $generalCategory->getId(),
    'name' => $generalCategory->getName(),
  ],
]);
$crumbs[] = [
  'text' => $h1,
];
slot('h1', $h1);
slot('breadcrumbs', $crumbs);
slot('catalog-class', 'catalog-cart');
$i = 0;
$j = 1;
$rating = round(($product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0) / 2 + 0.1);
if (!$rating || $rating > 5) $rating = 5;
if ($rating < 1) $rating = 1;
$deliveryPriceText = '';
if ($product->getPrice() >= csSettings::get('free_deliver')) $deliveryPriceText = ' бесплатно';

$videoUrl = $product->getVideo();
$videoUrlArr = explode('.', $videoUrl);

if ($videoUrlArr[1] == 'webm') $videoUrlArr[1] = 'mp4';
else $videoUrlArr[1] = 'webm';

if ($videoUrl) $videoUrl = '/uploads/video/' . $videoUrl;
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $videoUrl)) $videoUrl = false;

if (!$videoUrl && file_exists($_SERVER['DOCUMENT_ROOT'] . '/uploads/video/' . implode('.', $videoUrlArr))) $videoUrl = '/uploads/video/' . implode('.', $videoUrlArr);

// товар в корзине
// $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
// if (is_array($products_old))
//   foreach ($products_old as $key => $productInCart) {
//       $arrayProdCart[] = $productInCart['productId'];
//   }
//Получаем информацию по доступности в магазинах - она нужна раньше, чем встретится в коде
ob_start();

include_component(
  'product',
  'stock',
  array(
    'slug' => $sf_request->getParameter('slug'),
    'product' => $product,
    'sf_cache_key' => $product->getId()
  )
);

$shops = ob_get_contents();
ob_clean();

//Получаем информацию о видах товара. она не используется в новом дизайне, но нужна
ob_start();
include_component(
  'product',
  'comments',
  array(
    'slug' => $sf_request->getParameter('slug'),
    'product' => $product,
    'sf_cache_key' => $product->getId()
  )
);
$comments = ob_get_contents();
ob_clean();

//Таже песня с комментариями
ob_start();
include_component(
  'product',
  'squ',
  array(
    'slug' => $sf_request->getParameter('slug'),
    'product' => $product,
    'sf_cache_key' => $product->getId() . "-squ",
    "productsKeys" => $productsKeys,
    'productShow' => true
  )
);
$sku = ob_get_contents();
ob_clean();

ob_start();
include_component(
  'product',
  'params',
  array(
    'slug' => $sf_request->getParameter('slug'),
    'product' => $product,
    'sf_cache_key' => $product->getId() . "-productShow",
    "productsKeys" => $productsKeys,
    'productShow' => true,
    'squ' => get_slot('squ'),
  )
);
$params = ob_get_contents();
ob_clean();

$manufacturerName = explode(',', get_slot('product_manufacturer'));

?>
<? if (!$isTest) : ?>
  <script type="text/javascript">
    rrApiOnReady.push(function() {
      try {
        rrApi.view(<?= $product->getId() ?>);
      } catch (e) {}
    })
    // admitad
    // required object
    window.ad_product = {
      "id": "<?= $product->getId() ?>", // required
      "vendor": "",
      "price": "<?= $product->getPrice() ?>",
      "url": "",
      "picture": "",
      "name": "<?= $product->getName() ?>",
      "category": ""
    };

    window._retag = window._retag || [];
    window._retag.push({
      code: "9ce8886734",
      level: 2
    });
    (function() {
      var id = "admitad-retag";
      if (document.getElementById(id)) {
        return;
      }
      var s = document.createElement("script");
      s.async = true;
      s.id = id;
      var r = (new Date).getDate();
      s.src = (document.location.protocol == "https:" ? "https:" : "http:") + "//cdn.lenmit.com/static/js/retag.min.js?r=" + r;
      var a = document.getElementsByTagName("script")[0]
      a.parentNode.insertBefore(s, a);
    })()
    window.isProductPage = true
  </script>
<? endif ?>

<div class="wrap-block">
  <div class="container ">
    <div class="product-item__status product-item__status--inner">
      <? if ($product->getIsExpress()) : ?>
        <span class="orange">Доставка за час</span>
      <? endif ?>
      <? if (ILTools::isOfDay($product->getId())) : ?>
        <span>Товар дня</span>
      <? endif ?>
      <? if (ILTools::isExpress($product->getId())) : ?>
        <span>
          <svg class="dev-i">
            <use xlink:href="#dev-i"></use>
          </svg>
        </span>
      <? endif ?>
      <? if (ILTools::isNew($product)) : ?>
        <span>NEW</span>
      <? endif ?>
    </div>
    <div class="block-content status-bar-product">
      <div class="column">
        <?/*
          <div class="product-item__bar status-block-item">
            <div class="wrap-rating-star" rating="<?=$rating?>">
              <span class="rating-star"></span>
              <span class="rating-star"></span>
              <span class="rating-star"></span>
              <span class="rating-star"></span>
              <span class="rating-star"></span>
            </div>
            <?php if (get_slot('comments-count')): ?>
              <div class="product-item__reviews"><?= ILTools::getWordForm(get_slot('comments-count'), 'отзыв')?></div>
            <?php endif; ?>
          </div>
        */ ?>
        <? //include_component('noncache', 'shareblock', array('path' => $_SERVER['REQUEST_URI']));
        ?>

      </div>
      <div class="column">
        <div class="status-product-item status-block-item">
          <? if ($product->getCount() > 0) : ?>
            <svg>
              <use xlink:href="#status-yes-i"></use>
            </svg>
            <span>В наличии</span>
          <? else : ?>
            <svg>
              <use xlink:href="#status-yes-i"></use>
            </svg>
            <span>На заказ</span>
          <? endif ?>
        </div>
        <div class="article-product status-block-item">
          <span class="js-art-show" data-content="Артикул: <?= $product->getCode(); ?>">№:&nbsp;<?= str_pad($product->getId(), 5, "0", STR_PAD_LEFT) ?></span>
          <?/*<span>Артикул:</span>&nbsp;<span>№: <?= $product->getCode()?></span>*/ ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="wrap-block wrap-block-features">
  <div class="container gtm-detail-product-page" itemscope itemtype="http://schema.org/Product" data-name="<?= $product->getName() ?>" data-id="<?= $product->getId() ?>" data-price="<?= $product->getPrice() ?>" data-category="<?= $product->getGeneralCategory() ?>" data-manufacturer="<?= trim(get_slot('product_manufacturer')) ?>">
    <? if (!empty($manufacturerName[0])) : ?>
      <meta itemprop="brand" content="<?= trim($manufacturerName[0]) ?>" />
    <? endif ?>
    <meta itemprop="sku" content="<?= $product->getCode() ?>" />
    <meta itemprop="name" content="<?= $product->getName() ?>" />
    <div class="block-content">
      <div class="wrap-product-features width_min">
        <div class="product-features ">
          <div class="wrap-double-slider">
            <? if (sizeof($photos)) : ?>

              <div class="swiper-button-next swiper-button-grey">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M13 6L8 11L3 6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>
              <div class="swiper-button-prev swiper-button-grey">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M3 10L8 5L13 10" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>

              <div class="swiper-container gallery-thumbs">
                <div class="swiper-wrapper">
                  <? $j = 0 ?>
                  <? if ($product->getFile()) : ?>
                    <div class="swiper-slide">
                      <div class="product-features__thumbs js-hover-change" data-index="<?= $j++ ?>" style="background-image: url('/uploads/photo/<?= $product->getFile() ?>')"></div>
                    </div>
                  <? endif ?>
                  <? foreach ($photos as $photo) : ?>
                    <div class="swiper-slide">
                      <div class="product-features__thumbs js-hover-change" data-index="<?= $j++ ?>" style="background-image: url('/uploads/photo/thumbnails_60x60/<?= $photo->getFilename() ?>')"></div>
                    </div>
                  <? endforeach ?>
                  <? if ($videoUrl) : ?>
                    <div class="swiper-slide">
                      <div class="product-features__thumbs video-icon js-hover-change" data-index="<?= $j++ ?>" style="background-image: url(<?= $thumb ? $thumb : '' ?>)"></div>
                    </div>
                  <? endif ?>
                </div>
              </div>
            <? endif ?>
            <div class="swiper-container gallery-top<?= ILTools::isBlured($product) ? ' blured' : '' ?>">
              <div class="swiper-wrapper">
                <? $i = 0;
                if ($product->getFile()) : ?>
                  <div class="swiper-slide">
                    <a href="/uploads/photo/<?= $product->getFile() ?>" data-index="<?= $i++ ?>" class="product-features__full js-image-popup<?= sizeof($photos) > 1 ? '-gallery' : '' ?>" style="background-image: url('/uploads/photo/<?= $product->getFile() ?>')"></a>
                  </div>
                <? endif ?>
                <? foreach ($photos as $photo) : ?>
                  <div class="swiper-slide">
                    <a href="/uploads/photo/<?= $photo->getFilename() ?>" data-index="<?= $i++ ?>" class="product-features__full js-image-popup<?= sizeof($photos) > 1 ? '-gallery' : '' ?>" style="background-image: url('/uploads/photo/<?= $photo->getFilename() ?>')"></a>
                  </div>
                <? endforeach ?>
                <? if ($videoUrl) : ?>
                  <div class="swiper-slide">
                    <a class="inlinePopupJS video-review product-features__full" href="#video-review" data-index="<?= $i++ ?>">
                      <video muted="true" id="slider-video">
                        <source src="<?= $videoUrl ?>">
                      </video>
                    </a>
                  </div>
                <? endif ?>

              </div>

            </div>
          </div>
          <?= $params; ?>
          <? if (sizeof($photos)) : ?>
            <div style=" display: none;" id="gallery-container">
              <? if ($product->getFile()) : ?>
                <img style="display: none;" src="/uploads/photo/<?= $product->getFile() ?>" itemprop="image" alt="<?= $h1 ?>">
                <a href="/uploads/photo/<?= $product->getFile() ?>" class="gallery-element"></a>
              <? endif ?>
              <? foreach ($photos as $photo) : ?>
                <img style="display: none;" src="/uploads/photo/<?= $photo->getFilename() ?>" itemprop="image" alt="<?= $h1 ?>">
                <a href="/uploads/photo/<?= $photo->getFilename() ?>" class="gallery-element"></a>
              <? endforeach ?>
            </div>
          <? endif ?>
        </div>

        <div class="purchase-card purchase-card_fixed" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
          <link itemprop="availability" href="http://schema.org/<?= $product->getCount() > 0 ? 'InStock' : 'PreOrder' ?>" />
          <div class="wrap">
            <?php if ($isForHimCatalog && $showForHimBar) { ?>
              <a href="/for-him" class="product-for-him-bar">Скидка 15% по промокоду KING</a>
            <?php } ?>

            <div class="product-item__price" itemprop="price" content="<?= $product->getPrice() ?>" <?= $product->getDiscount() > 0 ? ' old-price="' . number_format($product->getOldPrice(), 0, '', ' ') . ' руб"' : '' ?>><?= number_format($product->getPrice(), 0, '', ' ') ?>
              ₽
              <meta itemprop="priceCurrency" content="RUB" />
              <link itemprop="url" href="https://onona.ru/product/<?= $product->getSlug() ?>" />
            </div>
            <div class="purchase-card__bonus">+<?= $bonus ?> бонусов на счет</div>
            <div class="wrap-btn">

              <?php if (1 * $product->getCount() > 0) : ?>
                <a href="#popup-cart" class="btn-full js-basket-add gtm-detail-add-to-basket" data-id="<?= $product->getId() ?>">В корзину</a>
              <?php else : ?>
                <a href="#popup-notify" class="btn-full btn-full_white btn-full_small js-form-open" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>">Уведомить о поступлении</a>
              <?php endif; ?>
              <? if ($showBtnOneClickBuy && 1 * $product->getCount() > 0) : ?>
                <a href="#one-click-form" class="btn-full btn-full_white js-rr-send-to-basket js-popup-form" data-id="<?= $product->getId() ?>">Купить товар в 1 клик</a>
              <? endif ?>
              <?/*
              <a href="#popup-cart" class="btn-full js-basket-add <?=1*$product->getCount() > 0 ? 'gtm-detail-add-to-basket' : 'gtm-detail-add-to-basket'?>" data-id="<?=$product->getId()?>">Добавить в корзину</a>
              <? if($showBtnOneClickBuy && 1*$product->getCount() > 0) :?>
                <a href="#one-click-form" class="btn-full btn-full_white js-rr-send-to-basket js-popup-form"  data-id="<?= $product->getId() ?>">Купить товар в 1 клик</a>
              <? endif ?>*/ ?>
            </div>
            <a <?= $authUser ? 'class="btn-chosen js-add-choosen' . ($isChoosen ? ' active' : '') . '" data-id="' . $product->getId() . '"' : 'class="btn-chosen js-popup-form" href="#popup-login"' ?>>
              <svg>
                <use xlink:href="#chosen-svg"></use>
              </svg>
              <span><?= $isChoosen ? 'Убрать из избранного' : 'Добавить в избранное' ?></span>
            </a>
          </div>
          <div class="wrap">
            <a href="/dostavka" class="purchase-card__receive">
              <span>Как получить товар?</span>
              <svg>
                <use xlink:href="#map-i"></use>
              </svg>
            </a>
            <a href="#how-to-credit" class="purchase-card__receive --tinkoff js-popup-form">
              <span>Купить в кредит или рассрочку</span>
            </a>
            <div class="purchase-card__list">
              <? $inShops = get_slot('shops-in-stock'); ?>
              <? if ($product->getCount() > 0) : ?>
                <div class="purchase-card__item"><span>В наличии</span>
                  <?php if ($inShops > 0) : ?>
                    <span class="js-show-stocks">в <?= ILTools::getWordForm($inShops, 'магазин') ?></span>
                  <? else : ?> <span></span>
                  <?php endif; ?>
                </div>
              <? endif ?>
              <div class="purchase-card__item">
                <span><span style="margin-left: 0">Анонимная</span> доставка<?= $deliveryPriceText ?></span>
                <span></span>
                <?/*<span>за 266 ₽ </span>*/ ?>
              </div>
              <? if ($inShops > 0) : ?>
                <div class="purchase-card__item"><span>Самовывоз</span><span>Сегодня</span></div>
              <? endif ?>
            </div>
          </div>
        </div>
        <div class="mobile_params">
          <?= $params ?>
        </div>
        <?
        // формируем описание
        // замута с вынесением видео в верх страницы и оборачивание в контейнер
        $content = preg_replace('/style=".*"/mU', '', $product->getContent());
        $re = '/<iframe.+src="(.+)".+<\/iframe>/mU';
        preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

        $videos = '';
        foreach ($matches as $match) {
          $content = str_replace($match[0], '', $content);
          $videos .= '<div class="video-container"><iframe allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" frameborder="0" src="' . str_replace('http://', 'https://', $match[1]) . '"></iframe></div><br>';
        }
        /*
          $re='/(<p.*>(.|\n)*<\/p>|<div.*>(.|\n)*<\/div>)+/mU';
          preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
          $i=0; $newContent='';
          foreach ($matches as $match) {
            $newContent.=$match[0];
            //Раскрытие
            // if($i++==1) $newContent.='<div class="-hide">';//Если больше 2х абзацев - вставляем остальные в скрытый блок
          }
          // if($i>1) $newContent.='</div>'; //Закрываем скрытый блок, если надо
          */
        ?>
        <div class="features-tabs tabs">
          <div class="features-tabs__title tabs__title">
            <div class="features-tab__title tab__title active">Описание товара</div>
            <div class="hide-desktop js-mobile-content">
              <noindex>
                <div class="h2">Описание</div>
                <br>
                <?= $videos . $content ?>
              </noindex>
            </div>
            <div class="features-tab__title tab__title">Отзывы <span><?= get_slot('comments-count') ?></span></div>
            <div class="hide-desktop js-mobile-content"></div>
            <div class="features-tab__title tab__title shop_stocks">Наличие в магазинах <span><?= $inShops > 0 ? $inShops : '' ?></span></div>
            <div class="hide-desktop js-mobile-content"></div>
            <?php if ($product->getDoc1() . $product->getDoc2() . $product->getDoc3() . $product->getDoc4() . $product->getDoc5()) : ?>
              <div class="features-tab__title tab__title">Инструкции</div>
              <div class="hide-desktop js-mobile-content"></div>
            <?php endif; ?>
            <?php if (!empty($articles)) : ?>
              <div class="features-tab__title tab__title">Статьи</div>
              <div class="hide-desktop js-mobile-content"></div>
            <?php endif; ?>
          </div>
          <div class="features-tabs__content tabs__content">
            <!-- Описание товара-->
            <div class="features-tab__content tab__content active" itemprop="description">
              <div class="h2">Описание</div>
              <br>

              <?= $videos . $content ?>
              <?/* if($i>1) : //Показываем разворачивающую кнопку?>
                <div class="more">
                  <a href="#productCardDescBlock" class="allButJS" data-all="Свернуть описание">
                    <span>Развернуть описание</span>
                    <svg>
                      <use xlink:href="#arrowMoreIcon" />
                    </svg>
                  </a>
                </div>
              <? endif */ ?>
            </div>
            <div class="tab__content"></div>
            <!-- Отзывы-->
            <?= $comments ?>
            <div class="tab__content"></div>
            <!-- Наличие в магазинах-->
            <?= $shops ?>
            <div class="tab__content"></div>
            <?php if ($product->getDoc1() . $product->getDoc2() . $product->getDoc3() . $product->getDoc4() . $product->getDoc5()) : ?>
              <div class="features-tab__content tab__content">
                <div class="h2">Инструкции</div>
                <br>
                <ul>
                  <? if ($product->getDoc1()) : ?>
                    <li><a href="/uploads/docs/<?= $product->getDoc1() ?>" target="_blank">Смотреть</a></li>
                  <? endif ?>
                  <? if ($product->getDoc2()) : ?>
                    <li><a href="/uploads/docs/<?= $product->getDoc2() ?>" target="_blank">Смотреть</a></li>
                  <? endif ?>
                  <? if ($product->getDoc3()) : ?>
                    <li><a href="/uploads/docs/<?= $product->getDoc3() ?>" target="_blank">Смотреть</a></li>
                  <? endif ?>
                  <? if ($product->getDoc4()) : ?>
                    <li><a href="/uploads/docs/<?= $product->getDoc4() ?>" target="_blank">Смотреть</a></li>
                  <? endif ?>
                  <? if ($product->getDoc5()) : ?>
                    <li><a href="/uploads/docs/<?= $product->getDoc5() ?>" target="_blank">Смотреть</a></li>
                  <? endif ?>
                </ul>
              </div>
              <div class="tab__content"></div>
            <?php endif; ?>

            <?php if (!empty($articles)) : ?>
              <div class="features-tab__content tab__content">
                <div class="h2">Статьи</div>
                <br>
                <ul>
                  <? foreach ($articles as $article) : ?>
                    <li><a href="/sexopedia/<?= $article['slug'] ?>" target="_blank"><?= $article['name'] ?></a></li>
                  <? endforeach ?>
                </ul>
              </div>
              <div class="tab__content"></div>
            <? endif ?>

          </div>
        </div>

      </div>
      <aside class="product-sidebar">
        <?php if ($isForHimCatalog && $showForHimBar) { ?>
          <a href="/for-him" class="product-for-him-bar">Скидка 15% по промокоду KING</a>
        <?php } ?>

        <div class="purchase-card">
          <div class="wrap">
            <div class="product-item__price" <?= $product->getDiscount() > 0 ? ' old-price="' . number_format($product->getOldPrice(), 0, '', ' ') . ' руб"' : '' ?>><?= number_format($product->getPrice(), 0, '', ' ') ?> ₽</div>
            <div class="purchase-card__bonus icon-bonus">+<?= $bonus ?> бонусов на счет</div>
            <div class="wrap-btn">
              <?php if (1 * $product->getCount() > 0) : ?>
                <a href="#popup-cart" class="btn-full js-basket-add gtm-detail-add-to-basket" data-id="<?= $product->getId() ?>">В корзину</a>
              <?php else : ?>
                <a href="#popup-notify" class="btn-full btn-full_white btn-full_small js-form-open" data-id="<?= $product->getId() ?>" data-name="<?= $product->getName() ?>">Уведомить о поступлении</a>
              <?php endif; ?>
              <? if ($showBtnOneClickBuy && 1 * $product->getCount() > 0) : ?>
                <a href="#one-click-form" class="btn-full btn-full_white js-rr-send-to-basket js-popup-form" data-id="<?= $product->getId() ?>">Купить товар в 1 клик</a>
              <? endif ?>
            </div>
            <a <?= $authUser ? 'class="btn-chosen js-add-choosen' . ($isChoosen ? ' active' : '') . '" data-id="' . $product->getId() . '"' : 'class="btn-chosen js-popup-form" href="#popup-login"' ?>>
              <svg>
                <use xlink:href="#chosen-svg"></use>
              </svg>
              <span><?= $isChoosen ? 'Убрать из избранного' : 'Добавить в избранное' ?></span>
            </a>
          </div>
          <div class="wrap">
            <a href="/dostavka" class="purchase-card__receive">
              <span>Как получить товар?</span>
              <svg>
                <use xlink:href="#map-i"></use>
              </svg>
            </a>
            <a href="#how-to-credit" class="purchase-card__receive --tinkoff js-popup-form">
              <span>Купить в кредит или рассрочку</span>
            </a>
            <div class="purchase-card__list">
              <? $inShops = get_slot('shops-in-stock') ?>
              <? if ($product->getCount()) : ?>
                <div class="purchase-card__item"><span>В наличии</span>
                  <?php if ($inShops > 0) : ?>
                    <span class="js-show-stocks">в <?= ILTools::getWordForm($inShops, 'магазин') ?></span>
                  <? else : ?> <span></span>
                  <? endif ?>
                </div>
              <? endif ?>
              <div class="purchase-card__item">
                <span><span style="margin-left: 0">Анонимная</span> доставка<?= $deliveryPriceText ?></span>
                <span></span>
                <?/*<span>за 266 ₽ </span>*/ ?>
              </div>
              <? if ($inShops > 0) : ?>
                <div class="purchase-card__item"><span>Самовывоз</span><span>Сегодня</span></div>
              <? endif ?>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</div>
<?/*Комплект
  <div class="wrap-block wrap-best-offer">
    <div class="container">
      <div class="block-content">
        <div class="best-offer">
          <div class="best-offer__item">
            <div class="wrap">
              <div class="best-offer__title">Анальная пробка с хвостом fox tail buttplug...</div>
              <div class="best-offer__price">4 290 ₽</div>
            </div>
            <div class="best-offer__img">
              <img src="/frontend/images_new/it-1.jpg" alt="">
            </div>
          </div>
          <div class="best-offer__item">
            <div class="wrap">
              <div class="best-offer__title lead-text">Мастурбатор в виде яйца happy eggs в ассортименте </div>
              <div class="best-offer__price">590 ₽</div>
            </div>
            <div class="best-offer__img">
              <img src="/frontend/images_new/it-2.jpg" alt="">
            </div>
          </div>
          <div class="best-offer__item best-offer__item-result">
            <div class="best-offer__title">Ваша выгода при покупке комплекта <span>составит 238 ₽</span></div>
            <div class="best-offer__price">
              <div class="product-item__price" old-price="5 883 ₽">5 590 ₽</div>
            </div>
            <a href="#" class="btn-full btn-full_rad">Купить комплект</a>
          </div>
        </div>
      </div>
    </div>
  </div>
*/ ?>
<?/*
<!-- блок Рекомендуем -->
<div class="wrap-block">
  <div class="container">
    <h2 class="h2 block-title">Рекомендуем </h2>
    <div class="block-content">
      <div class="products-tabs tabs">
        <div class="products-tabs__title tabs__title">
          <div class="products-tab__title tab__title active">Аксессуары</div>
          <div class="products-tab__title tab__title">Покупают вместе</div>
          <div class="products-tab__title tab__title">Популярные</div>
        </div>
        <div class="products-tabs__content tabs__content">
          <!-- Аксессуары товара-->
          <?php require $_SERVER['DOCUMENT_ROOT'].'/new-template-page/catalog-cart-tabs/sl-tab-1.php' ?>
          <!-- Покупают вместе-->
          <?php require $_SERVER['DOCUMENT_ROOT'].'/new-template-page/catalog-cart-tabs/sl-tab-2.php' ?>
          <!-- Популярные-->
          <?php require $_SERVER['DOCUMENT_ROOT'].'/new-template-page/catalog-cart-tabs/sl-tab-3.php' ?>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- END блок Рекомендуем -->
*/ ?>
<!-- блок Рекомендуем -->
<div class="wrap-block">
  <div class="container">
    <? if (1 * $product->getCount() > 0) : ?>
      <div data-retailrocket-markup-block="5ba3a5b897a52530d41bb235" data-product-id="<?= $product->getId() ?>"></div>
    <? else : ?>
      <div data-retailrocket-markup-block="6461e48693434a585bb7b86b" data-product-id="<?= $product->getId() ?>"></div>
    <? endif ?>
  </div>
</div>
<div class="wrap-block">
  <div class="container">
    <? include_component("page", "banner", array('type' => 'Карточка товара', 'sf_cache_key' => 'banner-basket', 'not_wrap' => true, 'add_class' => 'block-info_catalog')); ?>
  </div>
</div>

<? include_component("page", "subpage", array('page' => 'advantages-item')); ?>
<? if ($showBtnOneClickBuy && 1 * $product->getCount() > 0) : ?>
  <div class="white-popup-block block-popup popup-login mfp-hide" id="one-click-form">
    <div class="form-popup-wrapper">
      <div class="h2 block-popup__title">Быстрый заказ</div><br />

      <form action="/fastorder/<?= $product->getId() ?>" method="post" class="form form-popup js-ajax-form">
        <p class="block-popup__descr"><?= $product->getName() ?></p>
        <input type="hidden" name="sf_guard_user[_csrf_token]" value="<?php echo $csrf ?>">
        <div class="field field-default">
          <label>
            Ваше имя:
          </label>
          <input type="text" name="fio" />
        </div>
        <div class="field field-default">
          <label>
            Ваш email:*
          </label>
          <input type="email" class="" name="email" required placeholder="example@mail.ru" />
        </div>
        <div class="field field-default">
          <label>
            Ваш телефон:*
          </label>
          <input type="tel" class="js-phone-mask" name="phone" required placeholder="+7 (___) ___-__-__" />
        </div>
        <div class="field field-default">
          <label>
            Комментарий:
          </label>
          <textarea name="comment"></textarea>
        </div>
        <input type="hidden" name="product-id" value="<?= $product->getId() ?>" />
        <div class="btn-full btn-full_rad  js-submit-button">Отправить заказ</div>
      </form>
    </div>
  </div>
<? else : ?>
  <? slot('need_form_notify', true) ?>
<? endif ?>
<? //Собираем меты

$manufacturer = get_slot('product_manufacturer');
$color = get_slot('product_color');
$material = get_slot('product_material');
$product_property = get_slot('product_property');
//это мы получили из компонента _params

slot(
  'metaTitle',
  $product->getTitle() == '' ?
    str_replace(
      ["{name}", "{articul}", "{manufacturer}, "],
      [
        str_replace(array("'", '"'), "", $product->getName()), ' ' . $product->getCode(), $manufacturer == '' ? '' : $manufacturer . ', '
      ],
      csSettings::get('titleProduct')
    ) :
    $product->getTitle()
);
slot(
  'metaKeywords',
  $product->getKeywords() == '' ?
    'Купить ' . str_replace(["'", '"'], "", $product->getName()) . ', ' . $material . $color . $product_property . ' цена отзывы доставка'
    : $product->getKeywords()
);
slot(
  'metaDescription',
  $product->getDescription() == '' ?
    str_replace(["'", '"'], "", $product->getName()) . '. ' . $manufacturer . $material . $color . $product_property
    : $product->getDescription()
);
?>
<noindex>
  <div class="white-popup-block block-popup popup-login mfp-hide" id="how-to-credit">
    <div class="form-popup-wrapper">
      <div class="h2 block-popup__title" style="font-size: 150%;">Как купить в кредит или рассрочку</div><br />
      <? include_component("page", "subpage", array('page' => '_tinkoff_howto')); ?>
    </div>
  </div>
</noindex>