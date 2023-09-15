<?
global $isTest;
$h1=$product->getH1() != "" ? stripslashes($product->getH1()) : stripslashes($product->getName());
if($canonical) slot('canonical', '/product/'.$canonical);
slot('caltat', 0);
slot('caltat_product', $product->getId());
slot('advcake', 2);
slot('advcake_detail', [
  'product' => [
    'id' =>$product->getId(),
    'name' => $product->getName(),
    'price' => $product->getPrice(),
  ],
  'category' => [
    'id' => $generalCategory->getId(),
    'name' => $generalCategory->getName(),
  ],
]);
$crumbs[]=[
  'text'=> $h1,
];
slot('breadcrumbs', $crumbs);
$i=0; $j=1;
$rating = round(($product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0) / 2)+0.1;
if($rating>5) $rating=5;
// товар в корзине
// $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
// if (is_array($products_old))
//   foreach ($products_old as $key => $productInCart) {
//       $arrayProdCart[] = $productInCart['productId'];
//   }

?>
<? if(!$isTest):?>
  <script type="text/javascript">
      rrApiOnReady.push(function () {
          try {
              rrApi.view(<?= $product->getId() ?>);
          } catch (e) {
          }
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
      window._retag.push({code: "9ce8886734", level: 2});
      (function () {
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
<main class="wrapper">
  <div class="product-card gtm-detail-product-page"
      itemscope itemtype="http://schema.org/Product"
      data-name="<?=$product->getName()?>"
      data-id="<?=$product->getId()?>"
      data-price="<?=$product->getPrice()?>"
      data-category="<?=$product->getGeneralCategory()?>">
    <meta itemprop="sku" content="<?= $product->getCode() ?>" />
    <div class="product-card-row -top">
      <h1 itemprop="name"><?=$h1?></h1>
      <div class="product-card-wrap">
        <div class="product-card-img-block">
          <?php if($product->getVideo()) :?>
            <a href="#video" class="inlinePopupJS video-shutter" data-bimg="/frontend/images/video-icon.png">
              <img src="/frontend/images/video-icon.png" alt="video-icon">
            </a>
          <?php endif ?>

          <a href="/uploads/photo/<?= $photos[0]->getFilename() ?>" class="Magic360 product-card-img js-image-popup<?= sizeof($photos) > 1 ? '-gallery' : ''?>" id="productCardImg" data-index="0" data-options="columns: 24; filename: {col}.jpg;  spin:hover;">
            <? if (!$product->getFile()):?>
              <img src="/uploads/photo/<?= $photos[0]->getFilename() ?>" itemprop="image" alt="<?=$h1 ?>">
            <? else :?>
              <img src="/uploads/photo/<?= $product->getFile() ?>" itemprop="image" alt="<?=$h1 ?>">
            <? endif ?>
            <?/*<img src="/uploads/3d/1/01.jpg">*/?>
            <?= $product->getDiscount() ? '<span class="cat-list-action">-'.$product->getDiscount().'%</span>' : '' ?>
          </a>

          <?/*<a href="/uploads/photo/<?= $photos[0]->getFilename() ?>" class="Magic360 product-card-img js-image-popup<?= sizeof($photos) > 1 ? '-gallery' : ''?>"  id="productCardImg" data-index="0" data-options="columns: 24; filename: {col}.jpg;  spin:hover;"><img src="/uploads/3d/1/01.jpg"></a>*/?>
          <div class="product-card-check-img" id="checkProductCard">
            <? if(sizeof($photos)):?>
              <div class="swiper-container ">
                <div class="swiper-wrapper">
                  <? if ($product->getFile()):?>
                    <div class="swiper-slide">
                      <a href="/uploads/photo/<?= $product->getFile() ?>" data-bimg="/uploads/photo/<?= $product->getFile() ?>" <?= !$i++ ? 'class="active"' : '' ?>>
                        <img src="/uploads/photo/<?= $product->getFile() ?>" alt="<?=$h1 ?> <?=$j?>"  title="«<?=$generalCategory->getName()?> <?= $h1 ?> - фото No*<?=$j++?>»">
                      </a>
                    </div>
                  <? endif ?>
                  <? foreach ($photos as $photo) :?>
                    <div class="swiper-slide">
                      <a href="/uploads/photo/<?= $photo->getFilename() ?>" data-bimg="/uploads/photo/<?= $photo->getFilename() ?>" <?= !$i++ ? 'class="active"' : '' ?>>
                        <img src="/uploads/photo/thumbnails_60x60/<?= $photo->getFilename() ?>" alt="<?=$h1 ?> <?=$j?>"  title="«<?=$generalCategory->getName()?> <?= $h1 ?> - фото No*<?=$j++?>»">
                      </a>
                    </div>
                  <? endforeach ?>
                </div>
              </div>
              <div class="swiper-button-next">
                <svg>
                  <use xlink:href="#backArrowIcon" />
                </svg>
              </div>

            <? endif ?>
          </div>
          <? if(sizeof($photos)):?>
            <div style="display: none;" id="gallery-container">
              <? foreach ($photos as $photo) :?>
                <a href="/uploads/photo/<?= $photo->getFilename() ?>" class="gallery-element"></a>
              <? endforeach ?>
            </div>
          <? endif ?>
        </div>
        <div class="product-card-desc" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
          <div class="product-card-plate">
            <div class="rating">
              <? for ($i=1; $i<6; $i++) :?>
                <div class="rating-item<?= $i < $rating ? ' -isActive' : ''?>">
                  <svg>
                    <use xlink:href="#rateItemIcon" />
                  </svg>
                </div>
              <? endfor ?>
              <?/*<div class="rating-numb">
                (<?= $product->getVotesCount() ?>)
              </div>*/?>
            </div>
            <div class="product-card-numb">

              <span class="js-art-show" data-content="Арт.: <?= $product->getCode(); ?>">№:&nbsp;<?= str_pad($product->getId(), 5, "0", STR_PAD_LEFT) ?></span>
              <? if($product->getId()==25426) slot('is-blur', true); ?>
            </div>
            <div class="product-card-presence <?= $product->getCount()>0 ? ' -enable' : ''?>">
              <?= $product->getCount()>0 ? 'В наличии' : 'Под заказ'?>
              <link itemprop="availability" href="http://schema.org/<?=$product->getCount()>0 ? 'InStock' : 'PreOrder'?>" />
            </div>
          </div>
          <div class="product-card-services">
            <div class="product-card-by-block">
              <? if ($product->getDiscount() > 0) :?>
                <div class="product-card-old-price">
                  <?= number_format( $product->getOldPrice(), 0, '', ' ') ?> ₽
                </div>
              <? endif ?>
              <div class="product-card-price" itemprop="price" content="<?= $product->getPrice() ?>">
                <?= number_format( $product->getPrice(), 0, '', ' ') ?> <?/*₽*/?>руб.
                <meta itemprop="priceValidUntil" content="<?= date('c', 24*60*60+time()) ?>" />
                <meta itemprop="priceCurrency" content="RUB" />
                <link itemprop="url" href="https://onona.ru/product/<?=$product->getSlug()?>" />
                <?/*<meta itemprop="url" content="https://onona.ru/product/<?=$product->getSlug()?>" />*/?>
              </div>
              <div class="product-card-bonus">
                +<?= $bonus ?> бонусов на счет
              </div>
              <? if ($product->getPrice() >= csSettings::get('free_deliver') ) :?>
                <div class="product-card-delivery">
                  Бесплатная доставка!
                </div>
              <? endif ?>
              <div class="product-card-but-block">
                <a href="#" class="but -big js-basket-add <?=1*$product->getCount() > 0 ? 'gtm-detail-add-to-basket' : 'gtm-detail-add-to-basket'?>" data-id="<?=$product->getId()?>">добавить в корзину</a>
                <? if($showBtnOneClickBuy && 1*$product->getCount() > 0) :?>
                  <a href="#one-click-form" class="but -dotted -big inlinePopupJS js-rr-send-to-basket" data-id="<?= $product->getId() ?>">купить в 1 клик</a>
                <? endif ?>
              </div>
              <?//Выбор предложений
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
              ?>
            </div>
            <?
              include_component(
                'product',
                'params',
                array(
                  'slug' => $sf_request->getParameter('slug'),
                  'product' => $product,
                  'sf_cache_key' => $product->getId() . "-productShow",
                  "productsKeys" => $productsKeys,
                  'productShow' => true,
                  'squ'=>get_slot('squ'),
                )
              );
            ?>
          </div>
        </div>
      </div>
    </div>

    <div class="product-card-row product-card-desc-block gtm-manufacturer-name" data-manufacturer="<?=trim(get_slot('product_manufacturer'))?>">
      <div class="product-card-desc-block-head">
        <div class="h1">описание</div>
        <?= get_slot('tableSize') ? '<a href="/size-table" target="_blank" class="product-card-table-icon">Таблица размеров</a>' : '' ?>
      </div>
      <?/*<div class="product-card-res">
        <a href="#">Как сделать правильный выбор? >></a>
      </div>*/?>
      <div class="product-card-desc-block-text" id="productCardDescBlock" itemprop="description">
        <?php
          // замута с вынесением видео в верх страницы и оборачивание в контейнер
          $content=preg_replace ('/style=".*"/mU', '', $product->getContent());
          $re = '/<iframe.+src="(.+)".+<\/iframe>/mU';
          preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);

          $videos='';
          foreach ($matches as $match) {
            $content=str_replace($match[0], '', $content);
            $videos.='<div class="video-container"><iframe allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" frameborder="0" src="'.str_replace('http://', 'https://', $match[1]).'"></iframe></div><br>';
          }
          $re='/(<p.*>(.|\n)*<\/p>|<div.*>(.|\n)*<\/div>)+/mU';
          preg_match_all($re, $content, $matches, PREG_SET_ORDER, 0);
          $i=0; $newContent='';
          foreach ($matches as $match) {
            $newContent.=$match[0];
            if($i++==1) $newContent.='<div class="-hide">';//Если больше 2х абзацев - вставляем остальные в скрытый блок
          }
          if($i>1) $newContent.='</div>'; //Закрываем скрытый блок, если надо

        ?>
        <?= $videos.$newContent ?>
      </div>
      <? if($i>1) : //Показываем разворачивающую кнопку?>
        <div class="more">
          <a href="#productCardDescBlock" class="allButJS" data-all="Свернуть описание">
            <span>Развернуть описание</span>
            <svg>
              <use xlink:href="#arrowMoreIcon" />
            </svg>
          </a>
        </div>
      <? endif?>
    </div>
    <div data-retailrocket-markup-block="5ba3a5b897a52530d41bb235" data-product-id="<?= $product->getId() ?>"></div>
    <?php
      include_component(
        'product',
        'stock',
        array(
          'slug' => $sf_request->getParameter('slug'),
          'product' => $product,
          'sf_cache_key' => $product->getId()
        )
      );
    ?>
    <?php
      include_component(
        'product',
        'comments',
        array(
          'slug' => $sf_request->getParameter('slug'),
          'product' => $product,
          'sf_cache_key' => $product->getId()
        )
      );
    ?>
    <? if($product->getRating()>0):?>
      <span itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating" class=" mfp-hide">
        <meta itemprop="ratingValue" content="<?= round($rating) ?>" />
        <?= get_slot('comments-count') ? '<meta itemprop="reviewCount" content="'.get_slot('comments-count').'" />' : ''?>
        <meta itemprop="ratingCount" content="<?= $product->getVotesCount() ?>" />
      </span>
    <? endif ?>
  </div>
</main>
<?/*
include_component(
  'category',
  'sliderItems',
  array(
    'slug' => $sf_request->getParameter('slug'),
    'product' => $product,
    'sf_cache_key' => $product->getId() . "-similar",
    "productsKeys" => $productsKeys,
    'type'=>'similar'
  )
);?>
<?
include_component(
  'category',
  'sliderItems',
  array(
    'slug' => $sf_request->getParameter('slug'),
    'product' => $product,
    'sf_cache_key' => $product->getId() . "-recommended",
    "productsKeys" => $productsKeys,
    'type'=>'recommended'
  )
);*/?>

<div class="popup-form-wrapper mfp-hide popup" id="one-click-form">
    <div class="form-popup-wrapper">
        <div class="form-er-h">Быстрый заказ</div><br/>
        <p><?=$product->getName() ?></p>

      <form action="/fastorder/<?=$product->getId()?>" method="post" class="form form-review  js-ajax-form">
        <input type="hidden" name="sf_guard_user[_csrf_token]" value="<?php echo $csrf ?>">
        <div class="form-er-row">
          <div class="form-er-label">
            Ваше имя:
          </div>
          <input type="text" name="fio" />
        </div>
        <div class="form-er-row">
          <div class="form-er-label">
            Ваш email:*
          </div>
          <input type="email" class="" name="email" required placeholder="example@mail.ru"/>
        </div>
        <div class="form-er-row">
          <div class="form-er-label">
            Ваш телефон:*
          </div>
          <input type="tel" class="js-phone-mask" name="phone" required placeholder="+7 (___) ___-__-__"/>
        </div>
        <div class="form-er-row">
          <div class="form-er-label">
            Комментарий:
          </div>
          <textarea name="comment"></textarea>
        </div>
        <input type="hidden" name="product-id" value="<?=$product->getId()?>" />
        <div class="but -big js-submit-button">Отправить заказ</div>
      </form>
    </div>
</div>

<?php if($product->getVideo()) :?>
  <div class="video-popup-wrapper mfp-hide popup" id="video">
      <div class="form-popup-wrapper">
        <video <?php
          $videoData = explode(".", $product->getVideo());
            if ($videoData[1] != "webm") { ?>
              src="/uploads/video/<?= $product->getVideo() ?>"
            <?php } ?>
            controls>
            <?php if ($videoData[1] != "webm") { ?>
              <source src="/uploads/video/<?= $product->getVideo() ?>">
            <?php }
            else { ?>
              <source src="/uploads/video/<?= $videoData[0] ?>.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
              <source src="/uploads/video/<?= $videoData[0] ?>.webm" type='video/ogg; codecs="theora, vorbis"'>
            <?php } ?>
        </video>
      </div>
  </div>
<?php endif ?>

<?//Собираем меты

  $manufacturer = get_slot('product_manufacturer');
  $color = get_slot('product_color');
  $material = get_slot('product_material');
  $product_property = get_slot('product_property');
  //это мы получили из компонента _params

  slot('metaTitle',
    $product->getTitle() == '' ?
    str_replace(
      ["{name}", "{articul}", "{manufacturer}, "],
      [
        str_replace(array("'", '"'), "", $product->getName()), ' '.$product->getCode(), $manufacturer=='' ? '' : $manufacturer.', '
      ],
      csSettings::get('titleProduct')) :
    $product->getTitle()
  );
  slot('metaKeywords',
    $product->getKeywords() == '' ?
    'Купить '.str_replace([ "'", '"' ], "", $product->getName()).', '.$material.$color.$product_property.' цена отзывы доставка'
    : $product->getKeywords());
  slot('metaDescription',
    $product->getDescription() == '' ?
    str_replace([ "'", '"' ], "", $product->getName()).'. '.$manufacturer.$material.$color.$product_property
    : $product->getDescription());
?>
<? if(!get_slot('is-blur')){//Проверяем нужно ли затемнить для юкассы
    include_component(
      'product',
      'needtoblur',
      array(
        'slug' => $sf_request->getParameter('slug'),
        'product' => $product,
      )
    );
}?>
