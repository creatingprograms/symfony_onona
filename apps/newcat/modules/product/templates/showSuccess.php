<?
  $generalCategory = $product->getGeneralCategory();
  slot('personalRecomendationCategoryId', array($generalCategory->getId(), 1));
  slot('personalRecomendationProductId', array($product->getId(), 1));
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
  $timer = sfTimerManager::getTimer('Шаблон товара: проверка наличия в корзине, в списке желаний, в списке сравнений');
  $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
  if (is_array($products_old))
    foreach ($products_old as $key => $productInCart) {
        $arrayProdCart[] = $productInCart['productId'];
    }

  $products_desire = $sf_user->getAttribute('products_to_desire');
  $products_desire = $products_desire != '' ? unserialize($products_desire) : '';

  $products_compare = $sf_user->getAttribute('products_to_compare');
  $products_compare = $products_compare != '' ? unserialize($products_compare) : '';

  $timer->addTime();

  $timer = sfTimerManager::getTimer('Шаблон товара: хлебные крошки, дочернии товары, фотографии');
  $itemscope=1;
?>
<script type="text/javascript">
    rrApiOnReady.push(function () {
        try {
            rrApi.view(<?= $product->getId() ?>);
        } catch (e) {
        }
    })
    function playVideo() {
        $("#playerVideoDiv").html($("#playerVideoDivHidden").html());
    }
</script>
<div id="playerVideoDivHidden" style="display: none;">
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
<script type="text/javascript">
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
</script>
<link href="/css/jquery.mCustomScrollbar.css" rel="stylesheet">
<script src="/js/jquery.mCustomScrollbar.js"></script>

<!--<script src="/js/liTip_newcat.js"></script>-->
<ul class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><div><a itemprop="item" href="/" itemprop="url"><span itemprop="name"><meta itemprop="position" content="<?=$itemscope++?>" />Секс-шоп главная</span></a></div></li>
    <?php
    if ($generalCategory->getParentsId() != "") {
        echo "<li itemprop=\"itemListElement\" itemscope itemtype=\"http://schema.org/ListItem\"><div>
            <a href=\"/category/" . $generalCategory->getParent()->getSlug() . "\"  itemprop=\"item\" ><span itemprop=\"name\">".'<meta itemprop="position" content="'.$itemscope++.'" />' . $generalCategory->getParent()->getName() . "</span></a></div>
        </li>";
    }
    ?>
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
      <div>
            <a itemprop="item" href="/category/<?php
            $categorys = $product->getCategoryProducts();
            //$categoryProduct = $generalCategory->getId();

            echo $generalCategory->getSlug();
            //echo $categorys[0]->getSlug();

            mb_internal_encoding('UTF-8');

            slot('category_slug', $generalCategory->getSlug());
            slot('canonicalSlugProduct', $product->getSlug());

            ?>"><span itemprop="name"><meta itemprop="position" content="<?=$itemscope++?>" />
                       <?php
                       echo $generalCategory->getName();
                       $catReTag = $generalCategory->getName();
                       ?></span></a></div>
    </li>

    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><div><a itemprop="item" href="<?= explode("?", $_SERVER['REQUEST_URI'])[0] ?>"></a><span itemprop="name"><meta itemprop="position" content="<?=$itemscope++?>" /><?=$product->getName() ?></span></div></li>
</ul>
<?/*<ul class="breadcrumbs">
    <li><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title">Секс-шоп главная</span></a></div></li>
    <?php
    if ($generalCategory->getParentsId() != "") {
        echo "<li><div itemscope itemtype=\"http://data-vocabulary.org/Breadcrumb\">
            <a href=\"/category/" . $generalCategory->getParent()->getSlug() . "\" itemprop=\"url\"><span itemprop=\"title\">" . $generalCategory->getParent()->getName() . "</span></a></div>
        </li>";
    }
    ?>
    <li>
      <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
            <a href="/category/<?php
            $categorys = $product->getCategoryProducts();
            //$categoryProduct = $generalCategory->getId();

            echo $generalCategory->getSlug();
            //echo $categorys[0]->getSlug();

            mb_internal_encoding('UTF-8');

            slot('category_slug', $generalCategory->getSlug());
            slot('canonicalSlugProduct', $product->getSlug());

            ?>" itemprop="url"><span itemprop="title">
                       <?php
                       echo $generalCategory->getName();
                       $catReTag = $generalCategory->getName();
                       ?></span></a></div>
    </li>

    <li><?=$product->getName() ?><?php  ?></li>
</ul>*/?>

<?
$photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
$photosUser = PhotosUserTable::getInstance()->createQuery()->where("product_id=" . $product->getId())->addWhere("is_public = '1'")->execute();
if ($product->getParent() != "")
    $productProp = $product->getParent();
else
    $productProp = $product;
$i = 0;
$childrens = $productProp->getChildren();
$childrensId = $childrens->getPrimaryKeys();
$childrensId[] = $productProp->getId();

$comments = Doctrine_Core::getTable('Comments')
        ->createQuery('c')
        ->where("is_public = '1'")
        ->addWhere('product_id in (' . implode(',', $childrensId) . ')')
        ->orderBy('created_at desc')
        ->execute();
$count_comm = $comments->count();
if (sfContext::getInstance()->getRequest()->getParameter("video")) {
    if ($product->getVideo() != '') {
        ?><script>
        $(document).ready(function () {
        setTimeout('$("#ButtonVideoOfTabset").click()', 500);
        });</script>
        <?
    }
}
$timer->addTime();

$timer = sfTimerManager::getTimer('Шаблон товара: верхний блок товара');
?>

<div
  class="product-wrapper gtm-detail-product-page"
  id="ContentToProduct"
  itemscope itemtype="http://schema.org/Product"
  data-name="<?=$product->getName()?>"
  data-id="<?=$product->getId()?>"
  data-price="<?=$product->getPrice()?>"
  data-category="<?=$product->getGeneralCategory()?>"
>
    <script type="text/javascript" src="/js/jquery.lbslider.js"></script>

    <div class="product-left-part">
        <div class="product-images-holder<?=$product->getCount() ? '': ' outstock'?>">
            <div class="product-image">
                <div class="img-holder">
                  <script>
                    hs.graphicsDir = '/js/highslide/graphics/';
                    hs.align = 'center';
                    hs.transitions = ['expand', 'crossfade'];
                    hs.outlineType = 'rounded-white';
                    hs.fadeInOut = true;
                    hs.dimmingOpacity = 0.75;
                    // Add the controlbar
                    hs.addSlideshow({
                        //slideshowGroup: 'group1',
                        interval: 5000,
                        repeat: false,
                        useControls: true,
                        fixedControls: 'fit',
                        overlayOptions: {
                            opacity: 0.75,
                            position: 'bottom center',
                            hideOnMouseOut: true
                        }
                    });
                    // Add the controlbar
                    hs.addSlideshow({
                        slideshowGroup: 'groupToSend',
                        interval: 5000,
                        repeat: false,
                        useControls: true,
                        fixedControls: 'fit',
                        overlayOptions: {
                            opacity: 0.75,
                            position: 'bottom center',
                            hideOnMouseOut: true
                        }
                    });
                    $(document).ready(function () {
                      <?php if ($sf_params->get('senduser')): ?>
                        $(".addToUserSend").click();
                      <?php endif; ?>
                        $("#goods").ajaxForm(function () {
                            $("section.backetWrap").load("/cart/cartinfoheader");
                            //alert("Спасибо за добавление в корзину");
                        });

                    });
                    /*
                    $(document).ready(function() {
                      $('.jqzoom').jqzoom({
                                zoomType: 'reverse',
                                lens: true,
                                preloadImages: false,
                                zoomWidth: 300,
                                zoomHeight: 249,
                                xOffset: 10,
                                yOffset: 0,
                                position: 'right',
                                preloadText: 'Идет увеличение...',
                                title: true,
                                imageOpacity: 0.4, // Для zoomType 'reverse'
                                showEffect: 'show',
                                hideEffect: 'fadeout',
                                fadeinSpeed: 'slow', // Если для showEffect выбран 'fadein'
                                fadeoutSpeed: 500 // Если для hideEffect выбран 'fadeout'
                            });
                    });*/

                  </script>
                  <script src="/js/jquery.starRating.js"></script>
                  <script>
                    $(document).ready(function () {
                      /*$('.zoomPad').css('top', (($('#main div.img-holder').height() - $('#photoimg_<?= $product->getId() ?>').height())/2)-);*/
                      /*$('.zoomPad').css('left', (($('#main div.img-holder').width() - $('#photoimg_<?= $product->getId() ?>').width()) / 2) - 2);*/
                      $('#rate_div').starRating({
                        basicImage  : '/images/star.gif',
                        ratedImage : '/images/star_hover.gif',
                        hoverImage : '/images/star_hover2.gif',
                        ratingStars   : 10,
                        ratingUrl       : '/product/rate',
                        paramId       :  'product',
                        paramValue  : 'value',
                        rating			  : '<?= $product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0 ?>',
                        customParams : {productId : '<?= $product->getId() ?>'}

                        <? if (sfContext::getInstance()->getRequest()->getCookie("rate_" . $product->getId()) or ! $sf_user->isAuthenticated()) { ?>,
                          clickable : false,
                          hoverable : false
                        <? } ?>

                      });
                      $(".fade").hover(function () {
                          $(this).fadeOut(100).fadeIn(500);
                      });
                    });
                  </script>
                  <a href="/uploads/photo/<?= $photos[0]->getFilename() ?>" class = "jqzoom highslide zoom" rel = "<? /* $product->getName() */ ?>nofollow" title = "<?= str_replace(array("'", '"'), "", $product->getName()) ?>"<? /* alt='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' */ ?> onclick = "return hs.expand(this)" style="max-width: 400px; <? /* max-height: 400px; */ ?> height: 14px; line-height: 0; display: inline-block;">
                      <?/*<img src="/images/pix.gif" style="height: 400px;left: 0;position: absolute;width: 400px;z-index: 3;" alt="pix" />*/?>
                      <div class="product-main-image">
                          <img class="prod-main-img" id = "photoimg_<?= $product->getId() ?>" src="/uploads/photo/<?= $photos[0]->getFilename() ?>" alt="<?= str_replace(array("'", '"'), "", $product->getName()) ?>"  title="<?= str_replace(array("'", '"'), "", $product->getName()) ?>" />
                      </div>
                  </a>
                  <?
                    if ($product->getBonuspay() > 0) {
                        slot('bonusPayPercent', $product->getBonuspay());
                        echo '<span class="sale' . $product->getBonuspay() . '" style="top: -7px; right: -12px;"></span>';
                    } elseif ($product->getDiscount() > 0) {

                        echo '<span class="sale' . $product->getDiscount() . '" style="top: -7px; right: -12px;"></span>';
                    } else{
                      $newsList=csSettings::get('optimization_newProductId');
                      $newsListAr=explode(',', $newsList);

                      if (strtotime($product->getCreatedAt()) > time() - (csSettings::get('logo_new') * 24 * 60 * 60)) $isNew= true;
                      if (in_array($product->getId(), $newsListAr)) $isNew= true;
                        echo $isNew ? '<span class="newProduct" style=" left: -5px; top: -6px;"></span>' : '';
                    }
                    ?>
                </div>
            </div>
            <div class="product-extra-images">
                <?
                if ($product->getVideo() != '') {
                    $videoCount = 1;
                }
                if (($photos->count() + $videoCount) > 4) { ?>
                    <div style="position: relative;">
                      <script>
                        $(document).ready(function () {
                            $('.SliderProductPreShow').lbSlider({
                                leftBtn: '.sa-left',
                                rightBtn: '.sa-right',
                                visible: 4,
                                autoPlay: false,
                                autoPlayDelay: 5,
                                cyclically: false
                            });

                        });
                      </script>



                      <div class="SliderProductPreShow">
                        <ul class="photosPreShow" itemscope="" itemtype="http://schema.org/ImageObject">
                          <? foreach ($photos as $numPhotos => $photo) {?>
                              <li>
                                <span<? If ($numPhotos == 0) { ?> class="activePhotosPreShow"<? } ?>>
                                    <? If ($numPhotos != 0) { ?>
                                      <a href = "/uploads/photo/<?= $photo->getFilename() ?> "
                                        title = " "
                                        class = "highslide"
                                        onclick = "return hs.expand(this)" style="display: none;">
                                      </a>
                                    <? } ?>
                                    <img itemprop="image" src="/uploads/photo/thumbnails_60x60/<?= $photo['filename'] ?>"
                                      style="max-width: 60px; max-height: 60px;"
                                      onclick='$(".photosPreShow li span").each(function (i) {
                                                $(this).removeClass("activePhotosPreShow")
                                              });
                                            $(this).parent().addClass("activePhotosPreShow");
                                            $("#photoimg_<?= $product->getId() ?>").attr("src", "/uploads/photo/<?= $photo['filename'] ?>");
                                            /*$(".zoomWrapperImage").find("img").attr("src", "/uploads/photo/<?= $photo['filename'] ?>");
                                              $(".jqzoom").unbind();
                                             $(".jqzoom").removeData("jqzoom");
                                             $(".jqzoom").empty();
                                             $(".jqzoom").attr("href", "/uploads/photo/<?= $photo['filename'] ?>")*/
                                             //$("<div/>").attr("class", "product-main-image").append("<img id = \"photoimg_<?= $product->getId() ?>\" src=\"/uploads/photo/<?= $photo['filename'] ?>\" alt=\"<?= str_replace(array("'", '"'), "", $product->getName()) ?>\"  title=\"<?= str_replace(array("'", '"'), "", $product->getName()) ?>\" />").appendTo(".jqzoom");

                                             /*$(".jqzoom").jqzoom();*/'
                                      alt="<?= str_replace(array("'", '"'), "", $product->getName()) ?>">
                                </span>
                              </li>
                            <? }?>
                            <?php if ($product->getVideo() != ''):
                                  if (substr_count($product->getVideo(), 'video/') == 0) {
                                      $videoSRC = "/uploads/video/" . $product->getVideo();
                                  } else {
                                      $videoSRC = str_replace(array("http://www.onona.ru/video/", "https://onona.ru/video/", "http://new.onona.ru/video/"), "/uploads/video/", $product->getVideo());
                                  }
                                  ?>
                              <li>
                                <span>
                                  <a href="#" class="player buttonVideoPlayMini" onClick="
                                      $('#BackgrounAddProductToCard').fadeIn();
                                      playVideo();
                                      /*hdwebplayer({
                                          id: 'playerVideoDiv',
                                          swf: '/player/player.swf?api=true',
                                          width: '640',
                                          height: '360',
                                          margin: '15',
                                          video: '<?= $videoSRC ?>',
                                          autoStart: 'true',
                                          shareDock: 'false'
                                      });*/
                                      $('.close').click(function () {

                                          $('#BackgrounAddProductToCard').fadeOut();
                                          $('#playerBG').remove();
                                          $('#playerdiv').css('display', 'none');
                                      player = $('#playerVideoDiv video');
                                      player[0].pause();
                                      });
                                      $('#playerdiv').css({'display': 'block'/*,
                                          'position': 'fixed',
                                          'top': (($(window).height() - $('#playerdiv').height()) / 2),
                                          'left': (($(window).width() - $('#playerdiv').width()) / 2)*/});
                                      return false;">
                                    </a>
                                  </span>
                                </li>
                            <?php endif; ?>
                          </ul>
                        </div>
                        <a href="#" class="slider-arrow sa-left"></a>
                        <a href="#" class="slider-arrow sa-right"></a>
                    </div>
                <? }
                else { ?>
                    <div class="SliderProductPreShow">
                      <ul class="photosPreShow">
                        <? foreach ($photos as $numPhotos => $photo) {?>
                          <li>
                            <span<? If ($numPhotos == 0) { ?> class="activePhotosPreShow"<? } ?>>
                              <? If ($numPhotos != 0) { ?><a href = "/uploads/photo/<?= $photo->getFilename() ?> " title = " " class = "highslide" onclick = "return hs.expand(this)" style="display: none;"></a><? } ?>
                                <img
                                  src="/uploads/photo/thumbnails_60x60/<?= $photo['filename'] ?>"
                                  style="max-width: 60px; max-height: 60px;"
                                  onclick='$(".photosPreShow li span").each(function (i) {
                                      $(this).removeClass("activePhotosPreShow")
                                    });
                                    $(this).parent().addClass("activePhotosPreShow");
                                    $("#photoimg_<?= $product->getId() ?>").attr("src", "/uploads/photo/<?= $photo['filename'] ?>");
                                    $(".zoomWrapperImage").find("img").attr("src", "/uploads/photo/<?= $photo['filename'] ?>");
                                         $(".jqzoom").unbind();
                                    $(".jqzoom").removeData("jqzoom");
                                    $(".jqzoom").empty();
                                    $(".jqzoom").attr("href", "/uploads/photo/<?= $photo['filename'] ?>")
                                    $("<div/>").attr("class", "product-main-image").append("<img id = \"photoimg_<?= $product->getId() ?>\" src=\"/uploads/photo/<?= $photo['filename'] ?>\" alt=\"<?= str_replace(array("'", '"'), "", $product->getName()) ?>\"  title=\"<?= str_replace(array("'", '"'), "", $product->getName()) ?>\" />").appendTo(".jqzoom");

                                    $(".jqzoom").jqzoom();'
                                  alt="<?= str_replace(array("'", '"'), "", $product->getName()) ?>">
                            </span>
                          </li>
                        <? } ?>
                        <?php if ($product->getVideo() != ''):
                          if (substr_count($product->getVideo(), 'video/') == 0) {
                              $videoSRC = "/uploads/video/" . $product->getVideo();
                          }
                          else {
                              $videoSRC = str_replace(array("http://www.onona.ru/video/", "https://onona.ru/video/", "http://new.onona.ru/video/"), "/uploads/video/", $product->getVideo());
                          } ?>
                          <li>
                            <span>
                              <a
                                href="#"
                                class="player buttonVideoPlayMini"
                                onClick=" $('#BackgrounAddProductToCard').fadeIn();
                                  playVideo();
                                  /*hdwebplayer({
                                      id: 'playerVideoDiv',
                                      swf: '/player/player.swf?api=true',
                                      width: '640',
                                      height: '360',
                                      margin: '15',
                                      video: '<?= $videoSRC ?>',
                                      autoStart: 'true',
                                      shareDock: 'false'
                                  });*/
                                  $('.close').click(function () {

                                      $('#BackgrounAddProductToCard').fadeOut();
                                      $('#playerBG').remove();
                                      $('#playerdiv').css('display', 'none');
                                      player = $('#playerVideoDiv video');
                                      player[0].pause();
                                  });
                                  $('#playerdiv').css({'display': 'block'/*,
                                      'position': 'fixed',
                                      'top': (($(window).height() - $('#playerdiv').height()) / 2),
                                      'left': (($(window).width() - $('#playerdiv').width()) / 2)*/});
                                  return false; ">
                              </a>
                            </span>
                          </li>
                        <?php endif; ?>
                      </ul>
                    </div>
                <? } ?>
            </div>
        </div>
        <div class="product-description">
            <div class="product-name-wrapper">
                <div class="product-name">
                    <h1 class="title" itemprop="name"><?= $product->getH1() != "" ? stripslashes($product->getH1()) : stripslashes($product->getName()) ?></h1>
                    <script> window.isProductPage = true</script>
                </div>
            </div>
            <div class="product-stars-wrapper">
                <span id="id1c" onClick="$('#id1c').hide();
                  $('#idcode').show();">№: <?= str_pad($product->getId(), 5, "0", STR_PAD_LEFT); /* substr($product->getId1c(), -5, 5) != "" ? substr($product->getId1c(), -5, 5) : $product->getCode(); */ ?></span>
                <span id="idcode" onClick="$('#id1c').show();
                  $('#idcode').hide();" style="display: none;">№: <?= $product->getCode(); ?></span><br />
                <span itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating" class=" mobile-hide">
                  <meta itemprop="ratingValue" content="<?= round(($product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0) / 2) ?>" />
                  <? if ($count_comm != 0) { ?>
                  <meta itemprop="reviewCount" content="<?= $count_comm ?>" />
                  <? } ?>
                  <meta itemprop="ratingCount" content="<?= $product->getVotesCount() ?>" />

                </span>
                <div class="product-rating-name">Рейтинг </div>
                <div style="float: left;" class=" mobile-hide">
                    <div onmouseout="$('#qestionRate').fadeOut()" onmouseover="$('#qestionRate').fadeIn()" onclick="$('#qestionRate').toggle();" class="li-tip-question"></div>
                    <div style=" position: relative;">
                        <div id="qestionRate" class="li-tip-answer">Средний рейтинг, основанный на голосовании покупателей.</div>
                    </div>
                </div>
                <div class="stars" id="rate_div" style="float: left;margin: 3px 0 0 5px;"></div>
                <div class=" mobile-hide" style="float: left;"><?= $product->getVotesCount() ?></div>

                <div style="clear:both;"></div>
            </div>
            <div class="product-properties-wrapper">
              <div class="item-char">
                <fieldset>
                  <? include_component('product', 'params', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-productShow", "productsKeys" => $productsKeys, 'productShow' => true)); ?>
                </fieldset>
              </div>
            </div>
        </div>
    </div>

    <?
      $timer->addTime();
      $timer = sfTimerManager::getTimer('Шаблон товара: цены, кнопки');
    ?>

    <div class="product-right-part<?=($product->getEndaction() != "") ? ' product-right-part--long' : ''?>">
        <div style="clear:both;  height: 52px;" class="mobile-hide"></div>

        <div itemscope itemtype="http://schema.org/Offer" itemprop="offers">
            <meta itemprop=priceCurrency content=RUR />
            <? If ($product->getBonuspay() > 0) { ?>
                <div class="product-offer-type">Стоимость с учетом бонусов:</div>
                <div style="float: left;"><span class="product-price-now" itemprop=price><?= number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                <div class="br"></div>
                <div class="product-offer-type">Полная стоимость:</div>
                <div style="float: left;"><span class="product-price-other old"><?= number_format($product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                <div class="br"></div>
                <div class="product-offer-type">Оплата бонусами:</div>
                <div class="product-price-other"><?= number_format($product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></div>

            <? } elseif ($product->getDiscount() > 0) { ?>
                <div class="product-offer-type">Стоимость сегодня:</div>
                <div style="float: left;"><span class="product-price-now" itemprop=price><?= $product->getPrice() ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                <div class="br"></div>
                <div class="product-offer-type">Стоимость без скидки:</div>
                <div style="float: left;"><span class="product-price-other old"><?= $product->getOldPrice() ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                <div class="br"></div>
                <div class="product-offer-type">Экономия:</div>
                <div class="product-price-other"><?= number_format($product->getOldPrice() - $product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></div>
            <? } else { ?>
                <div class="product-offer-type">Стоимость сегодня:</div>
                <div style="float: left;"><span class="product-price-now" itemprop=price><?= number_format($product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span></div>
                <div class="br"></div>

                <? if ($product->getBonuspay() != '0') { ?>
                    <div class="product-offer-type">Стоимость при оплате бонусами:</div>
                    <div style="float: left;">
                      <span class="product-price-other underline"><?= number_format($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                    </div>
                    <div style="float: left;">
                        <div
                          onmouseout="$('#qestionPayBonus').fadeOut(0)"
                          onmouseover="$('#qestionPayBonus').fadeIn(0)"
                          onclick="$('#qestionPayBonus').toggle();"
                          class="li-tip-question"
                          <?/*style="z-index: 12;width: 16px; height: 18px; background: url(/images/questionicon.png); position: relative;"*/?>
                        >
                          <div style="position: relative;">
                            <div id="qestionPayBonus" class="li-tip-answer">
                              <div style="color:#c3060e; text-align: center; width: 100%; font-size: 15px;margin-bottom: 10px;">Программа "Он и Она - Бонус"</div>
                              <?= $product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?>% от стоимости данного товара можно оплатить Бонусами.<br /><br />
                              <a href="/programma-on-i-ona-bonus" target="_blank">Условия программы &gt;</a>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="br"></div>
                <? } ?>
                <div class="product-offer-type">Бонусы за покупку:</div>
                <div style="float: left;">
                  <span class="product-price-other underline">
                    <?php echo round(($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100)) ?>
                    <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span>
                  </span><? /* number_format($product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') */ ?>
                </div>
                <div style="float: left;">
                    <div
                      onmouseout="$('#qestionAddBonus').fadeOut(0)"
                      onmouseover="$('#qestionAddBonus').fadeIn(0)"
                      onclick="$('#qestionAddBonus').toggle();"
                      class="li-tip-question"
                    >

                      <div style=" position: relative;">
                        <div id="qestionAddBonus" class="li-tip-answer">
                            <div style="color:#c3060e; text-align: center; width: 100%; font-size: 15px;margin-bottom: 10px;">Программа "Он и Она - Бонус"</div>
                            После оплаты этого товара на ваш счет будут зачислены Бонусы в размере <?= round(($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100)) ?>.<br /><br />
                            <a href="/programma-on-i-ona-bonus" target="_blank">Условия программы &gt;</a>
                        </div>
                      </div>
                    </div>
                </div>
            <? } ?>
        </div>
        <div class="br"></div>
        <?php $stockCount=$product->getCount();?>
        <? if(!$stockCount) :?>
          <div class="stock-out">Под заказ</div>
        <? else :?>
          <div class="stock-positive">В наличии (<?= $stockCount ?>)</div>
        <? endif ?>
        <? if ($product->getEndaction() != ""): ?>
          <script>
            if (typeof ($.fn.countdown) === 'undefined' && !$("script").is("#countdownSRC")) {
              (function () {
                  var lt = document.createElement('script');
                  lt.type = 'text/javascript';
                  lt.async = true;
                  lt.src = '/newdis/js/jquery.countdown.js';
                  lt.id = 'countdownSRC';
                  var s = document.getElementsByTagName('script')[0];
                  s.parentNode.insertBefore(lt, s);
              })();
            }
          </script>
          <script type="text/javascript">
            <?php
              $step = array("1 сутки" => "1", "2 суток" => "2", "3 суток" => "3", "4 суток" => "4", "5 суток" => "5");
              if ($product->getStep() != "") {
                //echo $product->getEndaction() - time() + 24 * 60 * 60;
                  if ((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) > $step[$product->getStep()] * 24 * 60 * 60) {
                      $i = 0;
                      while ((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) > $i * $step[$product->getStep()] * 24 * 60 * 60) {
                          $dateStart = date("Y, m-1, d+1", strtotime($product->getEndaction()) - $i * $step[$product->getStep()] * 24 * 60 * 60 - $step[$product->getStep()] * 24 * 60 * 60);
                          $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()) - $i * $step[$product->getStep()] * 24 * 60 * 60);
                          $i++;
                      }
                  } else {
                      $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()));
                      $dateStart = date("Y, m-1, d+1", strtotime($product->getEndaction()) - $step[$product->getStep()] * 24 * 60 * 60);
                  }
              } else {
                $dateStart = date("Y, m-1, d", strtotime($product->getUpdatedAt()));
                $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()));
              }
            ?>
                $("#countdownSRC").load(function () {
                  var
                    endPoint = new Date(<?= $dateEnd ?>, 23, 59, 59),
                    startPoint = new Date(<?= $dateStart ?>, 0, 0, 0),
                    progressBar = $('.stock-line .line');
                  $('#timeaction').countdown({
                      layout: '<div style="float: left;background: url(\'/newdis/images/day.png\');background-size: 30px auto; width:30px; height: 18px;padding: 1px 0px 0px 0px;font-size: 16px; text-align: center;" class="countdownNew"><span class="day">{dn}</span></div>\n\
                                  \n\
                                  <div style="background: url(\'/newdis/images/time.png\') repeat scroll 0% 0% transparent;background-size: 101px auto; margin-left: 35px; margin-top: 5px; font-size: 16px; height: 18px; padding: 1px 0px 0px 0px; width: 101px;text-align: center;" class="countdownNew"><span class="time">{hnn} : {mnn} : {snn}</span></div>',
                      compact: true,
                      description: '',
                      until: endPoint,
                      alwaysExpire: true,
                      expiryText: '<div class="over">акция закончилась</div>',
                      onTick: function (periods) {
                          var result = 140 - ((endPoint.getTime() - new Date().getTime()) / ((endPoint.getTime() - startPoint.getTime()) / 140));
                          progressBar.css('width', (140 - result) + "px");
                      }
                  });
                });
                if (typeof ($.fn.countdown) !== 'undefined') {
                  var endPoint = new Date(<?= $dateEnd ?>, 23, 59, 59),
                          startPoint = new Date(<?= $dateStart ?>, 0, 0, 0)
                  progressBar = $('.stock-line .line');
                  $('#timeaction').countdown({
                      layout: '<div style="float: left;background: url(\'/newdis/images/day.png\');background-size: 30px auto; width:30px; height: 18px;padding: 1px 0px 0px 0px;font-size: 16px; text-align: center;" class="countdownNew"><span class="day">{dn}</span></div>\n\
                              <div style="background: url(\'/newdis/images/time.png\') repeat scroll 0% 0% transparent;background-size: 101px auto; margin-left: 35px; margin-top: 5px; font-size: 16px; height: 18px; padding: 1px 0px 0px 0px; width: 101px;text-align: center;" class="countdownNew"><span class="time">{hnn} : {mnn} : {snn}</span></div>',
                      compact: true,
                      description: '',
                      until: endPoint,
                      alwaysExpire: true,
                      expiryText: '<div class="over">акция закончилась</div>',
                      onTick: function (periods) {
                          var result = 140 - ((endPoint.getTime() - new Date().getTime()) / ((endPoint.getTime() - startPoint.getTime()) / 140));
                          progressBar.css('width', (140 - result) + "px");
                      }
                  });
                }
                /*
                 $(document).ready(function () {
                 var endPoint = new Date(<?= $dateEnd ?>, 23, 59, 59),
                 startPoint = new Date(<?= $dateStart ?>, 0, 0, 0)
                 progressBar = $('.stock-line .line');
                 $('#timeaction').countdown({
                 layout: '<div style="float: left;background: url(\'/newdis/images/day.png\');background-size: 30px auto; width:30px; height: 18px;padding: 1px 0px 0px 0px;font-size: 16px; text-align: center;" class="countdownNew"><span class="day">{dn}</span></div>\n\
                 \n\
                 <div style="background: url(\'/newdis/images/time.png\') repeat scroll 0% 0% transparent;background-size: 101px auto; margin-left: 35px; margin-top: 5px; font-size: 16px; height: 18px; padding: 1px 0px 0px 0px; width: 101px;text-align: center;" class="countdownNew"><span class="time">{hnn} : {mnn} : {snn}</span></div>',
                 compact: true,
                 description: '',
                 until: endPoint,
                 alwaysExpire: true,
                 expiryText: '<div class="over">акция закончилась</div>',
                 onTick: function (periods) {
                 var result = 140 - ((endPoint.getTime() - new Date().getTime()) / ((endPoint.getTime() - startPoint.getTime()) / 140));
                 progressBar.css('width', (140 - result) + "px");
                 }
                 });
                 });*/
            </script>
            <div class="stock-count">
                <div class="title countdown" style=" letter-spacing: -0.5px;text-align: left;">Действует ещё:</div>
                <div class="stock-line">
                    <span class="line"></span>
                </div>
                <div id="timeaction"></div>
                <div style="clear:both;"></div>
                <div style="float: left;width:30px; height: 13px;margin-top:0px;text-align: center;" class="title">дней</div>
                <div style=" width:101px; height: 13px;margin-left: 37px;margin-top:0px;text-align: center;" class="title">час.&nbsp;&nbsp;мин.&nbsp;&nbsp;сек.</div>
                <? if ($product->getBonuspay() > 0) { ?>
                    <div class="bonus-discount-image" onClick="$('#bonusDiscountText_<?= $product->getId() ?>').toggle()">
                        <img src="/images/newcat/podarok_name_new.png" alt="Управляй ценой">
                        <div class="bonus-discount-text" id='bonusDiscountText_<?= $product->getId() ?>'>
                            <div style="text-align: center;margin-bottom: 10px;"><span style="color: #c4040d; font-weight: bold;">УПРАВЛЯЙ ЦЕНОЙ!</span></div>
                            <div style="margin-bottom: 10px;">Оплата накопленными<br />
                                Бонусами до <?= $product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?>% от<br />
                                стоимости товара.</div>
                            <a href="/programma-on-i-ona-bonus" target="_blank">Условия программы ></a>
                        </div>

                    </div>

                    <?
                } elseif ($product->getDiscount() > 0) {
                    ?>
                    <div style="position: absolute; z-index: 10; right: -2px; top: -10px; cursor:pointer;" onClick="$('#bonusDiscountText_<?= $product->getId() ?>').toggle()">
                        <img src="/images/newcat/loveprice.png" alt="Лучшая цена">
                        <div style='    position: absolute; /* Абсолютное позиционирование */
                             right: 35px; top: 2px; /* Положение подсказки */
                             z-index: 1; /* Отображаем подсказку поверх других элементов */
                             background: #fff; /* Полупрозрачный цвет фона */
                             font-family: Arial, sans-serif; /* Гарнитура шрифта */
                             font-size: 13px; /* Размер текста подсказки */
                             padding: 5px 10px; /* Поля */
                             border: 1px solid #c4040d; /* Параметры рамки */
                             width: 150px;

                             color: #000;
                             border-color: #c4040d;display: none;' id='bonusDiscountText_<?= $product->getId() ?>'>
                            <div style="text-align: center;margin-bottom: 10px;"><span style="color: #c4040d;
                                                                                       font-weight: bold;">ЛУЧШАЯ ЦЕНА</span></div>
                            <div style="margin-bottom: 10px;">Бестселлеры по самым низким ценам в Рунете!</div>
                        </div>

                    </div>
                <? }
                ?>
            </div>
            <div style="clear:both;  height: 10px;"></div><? endif; ?>

          <div style=" height:<?= (($product->getPrice() >= csSettings::get('free_deliver')) or $product->getCount() == 0) ? '219' : '200' ?>px; <?/*position: absolute; bottom: 0px;*/?>">
            <?php if ($product->getCount() > 0 and $product->get("is_public")): ?>
                <?= $product->getPrice() >= csSettings::get('free_deliver') ?
                  '<div class="freeDelivery" '.
                    'style="margin-top:0px;" '.
                    'onmouseout="$(\'#freeDeliveryBlock\').fadeOut(0)" '.
                    'onmouseover="$(\'#freeDeliveryBlock\').fadeIn(0)"'. '>'.
                    '<div style=" position: relative;">'.
                      '<div id="freeDeliveryBlock" '.
                        'class="li-tip-answer" '.
                      '>'.
                        'Бесплатная доставка предоставляется при итоговой сумме заказа более 2990 <span style="font-family: \'PT Sans Narrow\', sans-serif;">&#8381;</span>.'.
                      '</div>'.
                    '</div>'.
                  '</div>' : ''
                ?>

                <?
                $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
                if (is_array($products_old)) {
                  foreach ($products_old as $key => $productCart) {
                      $arrayProdCart[] = $productCart['productId'];
                  }
                }
                if (in_array($product->getId(), $arrayProdCart) === true) { ?>
                    <div class="product-added-cart">Товар добавлен в корзину</div>
                    <a href="/cart" class="greenButtonPreShowAddedToCart"></a>
                <? }
                else { ?>
                    <div
                      class="redButtonPreShowAddToCart gtm-detail-add-to-basket"
                      onClick="
                        tagDiv = this;
                        $.ajax({
                            url: '/cart/addtocart/<?= $product->getId() ?>',
                            cache: false
                        }).done(function (html) {
                            $(tagDiv).after($('<a class=\'greenButtonPreShowAddedToCart\'>').attr('href', '/cart')).remove();
                            $('.greenButtonPreShowAddedToCart').before($('<div class=\'product-added-cart\' >').text('Товар добавлен в корзину'))
                            $('.freeDelivery').remove();

                            addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');

                        });

                        try {
                            rrApi.addToBasket(<?= $product->getId() ?>)
                        } catch (e) {
                        }
                      "
                      ></div>
                <? } ?>
                <div style="clear:both;  height: 5px;"></div>

                <? if($showBtnOneClickBuy) :?>
                  <div class="btn-oneclick" data-dspopup-id="dsoneclick" data-dsconfig="{'product_name':'<?= $product->getH1() != "" ? stripslashes($product->getH1()) : stripslashes($product->getName()) ?>'}">Быстрый заказ</div>
                <? endif ?>

                <script>
                    function MinToPrice() {
                        $('<div/>').click(function (e) {
                            if (e.target != this)
                                return;
                            $(this).remove();
                        }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockMinToPrice").appendTo('body');
                        $('.blockMinToPrice').html($(".MinToPriceBlock").html());
                        $('.blockMinToPrice').css("padding-top", $(window).scrollTop() + ($(window).height() / 2 - $('.blockMinToPrice').children().height() / 2))

                        $('.blockMinToPrice').height($("body").outerHeight() - $('.blockMinToPrice').css("padding-top").replace("px", "") - 1);
                        $(document).keyup(function (e) {

                            if (e.keyCode == 27) {
                                $('.blockMinToPrice').remove();
                            }   // esc
                        });
                    }
                </script>
                <div class="MinToPriceBlock" style="display: none;">
                    <div class="form-popup-wrapper">
                        <div class="form-popup-content product-min-price-form">

                            <div onClick="$('.blockMinToPrice').remove();" class='close'></div>

                            <div class="redButtonMinToPrice"></div>
                            <div class="header">Программа "Хочу другую цену"</div><br/>
                            <div class="settingMinToPriceContent">
                                Мы проверяем, вы экономите! <br />
                                Хотите купить данный товар, но нашли на другом сайте цену ниже?<br />
                                Мы изменим ее для вас!<br /><br />
                                Заполните заявку и наши эксперты оперативно проверят все данные. Если у конкурента цена будет ниже, сделаем скидку! <br /><br />
                                <a href="/hochu-druguu-cenu" target="blank" style="color:#0e8f01;">Подробные условия программы "Хочу другую цену"</a> <br /><br />
                                <? if (!$sf_user->isAuthenticated()) { ?>
                                  <div class="notification-buttons">
                                    Для участия в программе, вы должны быть авторизованны.<br/><br/>
                                    <div><a class="AuthMinToPriceButton" href="/guard/login"></a>Для постоянных клиентов</div>
                                    <div><a class="RegMinToPriceButton" href="/register"></a>Для новых клиентов</div>
                                  </div>

                                <? }
                                else { ?>
                                    <script src="/js/jquery-validate.js"></script>
                                    <script>
                                      $(document).ready(function () {
                                        $('.blockMinToPrice form.setMinToPrice').validate({
                                            onKeyup: true,
                                            sendForm: true,
                                            eachValidField: function () {

                                                $(this).closest('div').removeClass('error').addClass('success');
                                            },
                                            eachInvalidField: function () {

                                                $(this).closest('div').removeClass('success').addClass('error');
                                            },
                                            description: {
                                                price: {
                                                    required: '<div class="alert alert-error">Укажите цену конкурента</div>',
                                                    pattern: '<div class="alert alert-error">Pattern</div>',
                                                    conditional: '<div class="alert alert-error">Conditional</div>',
                                                    valid: '<div class="alert alert-success">Спасибо</div>'
                                                },
                                                link: {
                                                    required: '<div class="alert alert-error">Укажите ссылкe на товар на сайте конкурента</div>',
                                                    pattern: '<div class="alert alert-error">Pattern</div>',
                                                    conditional: '<div class="alert alert-error">Conditional</div>',
                                                    valid: '<div class="alert alert-success">Спасибо</div>'
                                                }
                                            }
                                          });
                                        });
                                    </script>
                                    <form action="/setmintoprice" class="setMinToPrice" method="POST">
                                        <input type="hidden" name="productId" value="<?= $product->getId() ?>">
                                        <table>
                                            <tr>
                                                <td>Цена товара у конкурента:</td>
                                                <td><input type="text" name="price" data-describedby="price-description-<?= $product->getId() ?>" data-required="true" data-description="price" class="required">
                                                    <div id="price-description-<?= $product->getId() ?>" class="requeredDescription"></div></td>
                                            </tr>
                                            <tr>
                                                <td>Ссылка на товар на сайте конкурента:</td>
                                                <td><input type="text" name="link" data-describedby="link-description-<?= $product->getId() ?>" data-required="true" data-description="link" class="required">
                                                    <div id="link-description-<?= $product->getId() ?>" class="requeredDescription"></div></td>
                                            </tr>
                                            <? if ($sf_user->getGuardUser()->getPhone() == "") { ?>
                                                <tr>
                                                    <td>Ваш номер телефона:</td>
                                                    <td><input type="text" name="phone" required></td>
                                                </tr>
                                            <? } ?>
                                        </table>
                                        <input type="checkbox" onClick="if ($(this).prop('checked')) {
                                                            $('.blockMinToPrice .ButtonMinToPrice').fadeIn();
                                                        } else {
                                                            $('.blockMinToPrice .ButtonMinToPrice').fadeOut();
                                                        }" style="margin:3px 3px 3px 0px;"> Я согласен c условиями программы "Хочу другую цену"<br/><br/>

                                        <input type="submit" style="display: none;" />
                                        <div style=" width: 300px;text-align: center; margin: auto;">
                                          <a class="ButtonMinToPrice" href="#" onclick="$('.blockMinToPrice .setMinToPrice').ajaxForm(function (result) {

                                                            $('.blockMinToPrice .setMinToPrice').html(result);
                                                        });
                                                        $('.blockMinToPrice .setMinToPrice').find('[type=\'submit\']').trigger('click');
                                                        //$('.blockMinToPrice .setMinToPrice').submit();
                                                        return false;" style="display: none;">
                                          </a>
                                        </div>
                                    </form>
                                <? } ?>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>

                <div class="redButtonMinToPrice" onclick="MinToPrice()">
                  <div style="background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px;width: 170px"><u>Хочу другую цену</u></div>
                </div>

            <?php else: ?>
                <?/*<div style="width: 100%; text-align: center; color: #c3060e; margin-bottom: 5px;">Товара нет в наличии</div>*/?>
                <? if ($sf_user->isAuthenticated()):
                    $nameSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getFirstName();
                    $mailSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress();
                    $phoneSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getPhone();
                  endif;
                ?>
                <?/*
                <a
                  class="redButtonPreShowUserSend"
                  onclick="return hs.htmlExpand(this, {contentId: 'ContentToSend', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar red-header',
                            headingText: '', width: 553, height: 400, slideshowGroup: 'groupToSend2', left: -9})" >
                  <span
                    style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;"
                    onclick="$('.captchasu').attr('src', $('.captchasu').attr('data-original')); setTimeout('enableAjaxFormSendUser_<?= $product->getId() ?>()', 500)">
                  </span>
                </a>*/?>
                <div
                  class="js-mobile-button-buy redButtonPreShowUserSend"
                  data-id="<?=$product['id']?>"
                  data-name="<?= str_replace(array("'", '"', '\\'), "", $product['name']) ?>"
                  data-image="<?=$photo['filename']?>"
                  data-price="<?=$product['price']?>"
                  data-bonus-add="<?= round(($product['price'] * csSettings::get("persent_bonus_add")) / 100) ?>"
                ></div>
                <!--
                  Сообщить о поступлении
                -->
                <div style="display: none" class="highslide-maincontent" id="ContentToSend">
                    <div class="highslide-header" style="height: 0;">
                      <ul>
                        <li class="highslide-previous">
                          <a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#">
                            <span>Предыдущая</span>
                          </a>
                        </li>
                        <li class="highslide-next">
                          <a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#">
                            <span>Следующая</span>
                          </a>
                        </li>
                        <li class="highslide-move">
                          <a onclick="return false" title="Переместить" href="#">
                            <span>Переместить</span>
                          </a>
                        </li>
                        <li class="highslide-close">
                          <a onclick="return hs.close(this)" title="Закрыть (esc)" href="#">
                            <span>Закрыть</span>
                          </a>
                        </li>
                      </ul>
                    </div>
                    <? /* <img src="/images/topToSend.png" alt="Сообщить о поступлении" /> */ ?>
                    <div style="color: #C3060E; font: 17px/21px Tahoma,Geneva,sans-serif;margin-bottom: 10px; text-align: center;">Сообщить о поступлении товара</div>
                    <?php if (!$errorCapSu and $sf_params->get('senduser')): ?>
                        <div style="width:100%; text-align: center;">Спасибо за запрос. Вам будет сообщено о поступление товара.</div>
                    <?php else: ?>
                        <script>
                            $(document).ready(function () {
                              var options = {
                                  target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                                  //beforeSubmit:  showRequest,  // pre-submit callback
                                  success: showResponse_<?= $product->getId() ?>  // post-submit callback

                                          // other available options:
                                          //url:       url         // override for form's 'action' attribute
                                          //type:      type        // 'get' or 'post', override for form's 'method' attribute
                                          //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                                          //clearForm: true        // clear all form fields after successful submit
                                          //resetForm: true        // reset the form after successful submit

                                          // $.ajax options can be used here too, for example:
                                          //timeout:   3000
                              };
                              // bind form using 'ajaxForm'
                              $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
                            });
                            function enableAjaxFormSendUser_<?= $product->getId() ?>() {
                                var options = {
                                    target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                                    //beforeSubmit:  showRequest,  // pre-submit callback
                                    success: showResponse_<?= $product->getId() ?>  // post-submit callback

                                            // other available options:
                                            //url:       url         // override for form's 'action' attribute
                                            //type:      type        // 'get' or 'post', override for form's 'method' attribute
                                            //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                                            //clearForm: true        // clear all form fields after successful submit
                                            //resetForm: true        // reset the form after successful submit

                                            // $.ajax options can be used here too, for example:
                                            //timeout:   3000
                                };
                                // bind form using 'ajaxForm'
                                $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
                            }
                            function showResponse_<?= $product->getId() ?>(responseText, statusText, xhr, $form) {
                                var options = {
                                    target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                                    //beforeSubmit:  showRequest,  // pre-submit callback
                                    success: showResponse_<?= $product->getId() ?>  // post-submit callback

                                            // other available options:
                                            //url:       url         // override for form's 'action' attribute
                                            //type:      type        // 'get' or 'post', override for form's 'method' attribute
                                            //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                                            //clearForm: true        // clear all form fields after successful submit
                                            //resetForm: true        // reset the form after successful submit

                                            // $.ajax options can be used here too, for example:
                                            //timeout:   3000
                                };
                                // bind form using 'ajaxForm'
                                $('.highslide-container #senduser_<?= $product->getId() ?>').ajaxForm(options);
                                $('.senduser_<?= $product->getId() ?>').each(function () {
                                    $(this).ajaxForm(options);
                                });
                            }
                        </script>
                        <div id="senduserdiv_<?= $product->getId() ?>">
                          <form id="senduser_<?= $product->getId() ?>" action="/product/<?= $product->getSlug() ?>/senduser" method="post"
                            <?php //onsubmit="if(!$('#accept-outstock').is(':checked')) { alert('Необходимо принять пользовательское соглашение!'); return false; } else return true;"?>
                            >
                                Данный товар скоро появится в продаже на сайте. Чтобы своевременно узнать об этом, нужно отправить нам запрос. Для этого в соответствующих полях напишите свое имя, e-mail и нажмите на кнопку "Отправить запрос". Как только товар поступит на склад, мы отправим уведомление на указанный вами e-mail.<br /><br />
                                <div style="clear: both; color:#4e4e4e; text-align: left;">
                                    <table style=" width: 100%" class="noBorder">
                                        <tbody>
                                            <tr>
                                                <td style="width: 120px;padding: 5px 0;text-align: left;">
                                                    Представьтесь*:
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <input type="text" name="name" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") != "" ? sfContext::getInstance()->getRequest()->getParameter("name") : $nameSenduser ?>" style="width: 254px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 120px; padding: 5px 0;text-align: left;">
                                                    Ваш e-mail*:
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <input type="text" name="mail" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") != "" ? sfContext::getInstance()->getRequest()->getParameter("mail") : $mailSenduser ?>" style="width: 254px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 120px; padding: 5px 0;text-align: left;">
                                                    Ваш телефон*:
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <input type="text" name="phone" value="<?= sfContext::getInstance()->getRequest()->getParameter("phone") != "" ? sfContext::getInstance()->getRequest()->getParameter("phone") : $phoneSenduser ?>" style="width: 254px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 120px; padding: 5px 0;text-align: left;">
                                                    Введите код*:
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">

                                                    <img  class="captchasu" src="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />
                                                    <input type="text" name="sucaptcha" style="position: relative; top: -45px; width: 130px;">
                                                    <?php if ($errorCapSu) { ?><br />
                                                        <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                                        <br />
                                                    <?php } ?>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>

                                    <label>
                                      <input id='accept-outstock' type='checkbox' style="margin: 0 5px 0 0;">Я принимаю условия
                                      <a href='/personal_accept' target='_blank'>Пользовательского соглашения</a>
                                    </label>
                                    <br/>
                                    <span style="font-size: 10px;">* - обязательны для заполнения.</span>
                                    <a  style="margin-top: 20px; margin-left: 155px;" class = "red-btn colorWhite" href = "#"
                                      onclick = "
                                        if(!$('#accept-outstock').is(':checked')) {
                                          alert('Необходимо принять пользовательское соглашение!');
                                          return false;
                                        }

                                        $('#senduser_<?= $product->getId() ?>').submit();
                                              setTimeout('enableAjaxFormSendUser_<?= $product->getId() ?>()', 500);
                                              return false;">
                                      <span style = "width: 195px;">Отправить запрос</span>
                                    </a>

                                </div>
                          </form>
                        </div>

                    <?php endif; ?>
                </div>
                <!--
                Сообщить о поступлении
                -->
            <?php endif; ?>

            <?
            if (!isset($prodInDesire)) {

                $products_desire = $sf_user->getAttribute('products_to_desire');
                $products_desire = $products_desire != '' ? unserialize($products_desire) : '';

                if (in_array($product->getId(), $products_desire) === true)
                    $prodInDesire = true;
                else
                    $prodInDesire = false;
            }
            if (!isset($prodInCompare)) {

                $products_compare = $sf_user->getAttribute('products_to_compare');
                $products_compare = $products_compare != '' ? unserialize($products_compare) : '';
                if (in_array($product->getId(), $products_compare) === true)
                    $prodInCompare = true;
                else
                    $prodInCompare = false;
            }
            ?>
            <div style="clear:both;  height: 5px;"></div>

            <script>
              function settingDesire() {
                $('<div/>').click(function (e) {
                    if (e.target != this)
                        return;
                    $(this).remove();
                }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockSettingDesire").appendTo('body');
                $('.blockSettingDesire').html($(".SettingDesireBlock").html());
                $('.blockSettingDesire').css("padding-top", $(window).scrollTop() + ($(window).height() / 2 - $('.blockSettingDesire').children().height() / 2))
                $('.blockSettingDesire').height($("body").outerHeight() - $('.blockSettingDesire').css("padding-top").replace("px", "") - 1);
                $(document).keyup(function (e) {

                    if (e.keyCode == 27) {
                        $('.blockSettingDesire').remove();
                    }   // esc
                });
              }
            </script>
            <script>
              function setRatePopUp($prodId, $rate) {
                $('.blockSettingDesire .redButtonAddToDesirePopUp' + $prodId).each(function (index) {
                    if (index < $rate) {
                        $(this).css("background", 'url(/images/newcat/heart.png)');
                    } else {
                        $(this).css("background", 'url(/images/newcat/heart-non.png)');
                    }
                });
                $('.blockSettingDesire .DesireRatePopUp').val($rate);
                $.post("/desire/rate", {productId: $prodId, value: $rate})
              }
            </script>
            <div class="SettingDesireBlock" style="display: none;">
                <div class="form-popup-wrapper">
                    <div style="width:700px; margin-left: 150px;
                         background-color: rgba(255, 255, 255, 1);
                         border:1px solid #c3060e; padding: 20px; ">

                        <div onClick="$('.blockSettingDesire').remove();" class='close'></div>

                        <div class="redButtonAddToDesire"></div><div style="color:#c3060e; text-align: center; width: 100%; font-size: 18px;">Добавление товара в список желаний</div><br/>
                        <div class="settingDesireContent">
                            <? if (!$sf_user->isAuthenticated()) { ?>
                                Добавляйте товар в список своих желаний. Это позволит не забыть о понравившемся товаре и вернуться к покупке немного позже или же поделиться своими желаниями с близкими людьми и помочь им с выбором подарка для Вас.<br/><br/>
                                Чтобы добавить товар в список желаний, вы должны быть авторизированы.<br/><br/>
                                <div style="float: left; width: 240px;text-align: center;    margin-left: 100px;"><a class="AuthDesireButton" href="/guard/login"></a>Для постоянных клиентов</div>
                                <div style="float: left; width: 240px;text-align: center;"><a class="RegDesireButton" href="/register"></a>Для новых клиентов</div>

                            <? } else { ?>
                                <form action="/setdesire" class="setDesire<?= $product->getId() ?>" method="POST">
                                    <input type="hidden" name="productId" value="<?= $product->getId() ?>">
                                    <br />
                                    <span style="color: #42b039">Выберите список: </span>
                                    <?
                                    if ($sf_user->isAuthenticated()) {
                                        $desiresPublic = CompareTable::getInstance()->createQuery()->where("rule='Публичный'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                        unset($productInPublic, $products_jelId);
                                        if ($desiresPublic) {
                                            $products_jelId = $desiresPublic->getProducts() != '' ? unserialize($desiresPublic->getProducts()) : array();

                                            if (in_array($product->getId(), $products_jelId) === true) {
                                                $productInPublic = true;
                                            } else {
                                                $productInPublic = false;
                                            }
                                        }
                                        $desiresPrivate = CompareTable::getInstance()->createQuery()->where("rule='Личный'")->addWhere("user_id=?", $sf_user->getGuardUser()->getId())->fetchOne();
                                        unset($productInPrivate, $products_jelId);
                                        if ($desiresPrivate) {
                                            $products_jelId = $desiresPrivate->getProducts() != '' ? unserialize($desiresPrivate->getProducts()) : array();

                                            if (in_array($product->getId(), $products_jelId) === true) {
                                                $productInPrivate = true;
                                            } else {
                                                $productInPrivate = false;
                                            }
                                        }
                                    }
                                    /* ?>
                                      <input type="checkbox" name="private"<?= $productInPrivate ? ' checked="checked"' : '' ?>> Личный &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="public"<?= $productInPublic ? ' checked="checked"' : '' ?>> Хочу в подарок
                                     */
                                    ?>

                                    <input type="radio" value="private" name="selCat"<?= $productInPrivate ? ' checked="checked"' : '' ?>> Личный &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="public" name="selCat"<?= $productInPublic ? ' checked="checked"' : '' ?>> Хочу в подарок &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="completed" name="selCat"<?= $productInComleted ? ' checked="checked"' : '' ?>> Исполненый

                                    <br />
                                    <br />
                                    <div style="float: left; margin-right: 5px;"><span style="color: #42b039">Степень желания: </span></div>

                                    <div class="redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 0 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;float: left;width: 24px;height: 22px;margin-right: 2px;cursor: pointer;" onClick="setRatePopUp(<?= $product->getId() ?>, 1)"></div>
                                    <div class="redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 1 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;float: left;width: 24px;height: 22px;margin-right: 2px;cursor: pointer;" onClick="setRatePopUp(<?= $product->getId() ?>, 2)"></div>
                                    <div class="redButtonAddToDesirePopUp<?= $product->getId() ?>" style="background: <?= $productDopInfo['rate'] > 2 ? 'url(/images/newcat/heart.png)' : 'url(/images/newcat/heart-non.png)' ?>;float: left;width: 24px;height: 22px;margin-right: 2px;cursor: pointer;" onClick="setRatePopUp(<?= $product->getId() ?>, 3)"></div>
                                    <input name="rate" class="DesireRatePopUp" type="hidden" value="0" />

                                    <br /><div style="clear: both"></div>
                                    <span style="float: left;color: #42b039;margin-top: 10px;display: block;">Комментарий: </span>
                                    <textarea name="comment" style="width: 400px; height: 100px;  margin-top: 10px;display: block;float: left;margin-left: 5px; padding: 10px;"><?= $productDopInfo['comment'] ?></textarea>




                                    <div style="float: left; width: 100%;text-align: center;    margin-top: 10px;"><a class="ButtonSave" href="" onclick='$(".blockSettingDesire .setDesire<?= $product->getId() ?>").ajaxForm(function (result) {
                                                    $(".blockSettingDesire .settingDesireContent").html(result);
                                                });
                                                $(".blockSettingDesire .setDesire<?= $product->getId() ?>").submit();
                                                $(".redButtonAddToDesire").after($("<a class=\"greenButtonAddToDesire\">").attr("href", "/desire").html("<div style=\"background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px;width: 170px\">Перейти к желаниям</div>")).remove();
                                                //* $("#content").load("/desire");*/
                                                return false;'></a></div>
                                    <div style="float: left; width: 300px;text-align: center;"><a class="SettingDesireButton" href="/customer/notification"></a></div>

                                    <div style="clear: both;"></div>
                                </form>
                            <? } ?>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                </div>
            </div>

            <? if ($prodInDesire === true) {
                ?>
                <a class="greenButtonAddToDesire" href='/desire'><div style="background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px;width: 170px">Перейти к желаниям</div></a>
                <?
            }
            else {
                ?>
                <a href="" class="redButtonAddToDesire" style="margin-top: 5px;" onClick='javascript:

                                    $(this).after($("<a class=\"greenButtonAddToDesire\">").attr("href", "/desire").html("<div style=\"background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px;width: 170px\">Перейти к желаниям</div>")).remove();
                            addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                            $("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");
                            /*settingDesire();*/
                            return false;'> <div style="background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px;width: 170px">В список желаний</div></a>
               <? } ?>

            <div style="clear:both;  height: 5px;"></div>

            <script>
              function settingNotification() {
                $('<div/>').click(function (e) {
                    if (e.target != this)
                        return;
                    $(this).remove();
                }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockSettingNotification").appendTo('body');
                $('.blockSettingNotification').html($(".SettingNotificationBlock").html());
                $('.blockSettingNotification').css("padding-top", $(window).scrollTop() + ($(window).height() / 2 - $('.blockSettingNotification').children().height() / 2))
                $('.blockSettingNotification').height($("body").outerHeight() - $('.blockSettingNotification').css("padding-top").replace("px", "") - 1);
                $(document).keyup(function (e) {

                    if (e.keyCode == 27) {
                        $('.blockSettingNotification').remove();
                    }   // esc
                });
              }
            </script>
            <div class="SettingNotificationBlock" style="display: none;">
                <div class="form-popup-wrapper">
                    <div class="form-popup-content">
                        <div onClick="$('.blockSettingNotification').remove();" class='close'></div>

                        <div class="redButtonNotificationProduct"></div>
                        <div class="header">Настроить оповещения</div>
                        <br/>
                        <div class="settingNotificationContent">
                            Оповестить меня по e-mail, когда:
                            <form action="/setnotification" class="setNotification" method="POST">
                                <input type="hidden" name="productId" value="<?= $product->getId() ?>">
                                <table>
                                  <tr>
                                        <td>
                                            <div class="notificationAction"><span>Акции</span></div>

                                            <div class="dinb"><input type="checkbox" name="ThisProd"
                                                                             <?
                                                                             if ($sf_user->getGuardUser())
                                                                                 if (NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='Action'")->addWhere("product_id=?", $product->getId())->fetchOne()) {
                                                                                     echo " checked";
                                                                                 }
                                                                             ?>>
                                            </div>
                                            <div  class="notification-field"> Акция только для этого товара</div><br>
                                            <div class="dinb"><input type="checkbox" name="ActionAllProd"<?
                                                if ($sf_user->getGuardUser())
                                                    if (NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='Action'")->addWhere("product_id is null")->fetchOne()) {
                                                        echo " checked";
                                                    }
                                                ?>>
                                            </div>
                                            <div class="notification-field"> Все акции сайта</div>
                                        </td>
                                        <td>
                                            <div class="notificationComment"><span>Информация</span></div>

                                            <div class="dinb"><input type="checkbox" name="CommentThisProd"<?
                                                if ($sf_user->getGuardUser())
                                                    if (NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='Comment'")->addWhere("product_id=?", $product->getId())->fetchOne()) {
                                                        echo " checked";
                                                    }
                                                ?>>
                                            </div>
                                            <div class="notification-field"> Новые отзывы к этому товару</div><br>
                                            <div class="dinb"><input type="checkbox" name="UserPhotoThisProd"<?
                                                if ($sf_user->getGuardUser())
                                                    if (NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='UserPhoto'")->addWhere("product_id=?", $product->getId())->fetchOne()) {
                                                        echo " checked";
                                                    }
                                                ?>>
                                            </div>
                                            <div class="notification-field"> Новые фото от пользователей</div>
                                        </td>
                                        <td>
                                            <div class="notificationReminderThisProd"><span>Напоминание</span></div>
                                            <? /* <div class="titleNotificationReminder">
                                              <div style="background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px;width: 170px">Напоминание</div>
                                              </div> */ ?>
                                            <div class="dinb">
                                              <input type="checkbox" name="ReminderThisProd"<?
                                                if ($sf_user->getGuardUser())
                                                    if ($notificationReminder = NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='ReminderThisProd'")->addWhere("product_id=?", $product->getId())->fetchOne()) {
                                                        echo " checked";
                                                    }
                                                ?>>
                                            </div>
                                            <div  class="notification-field"> Напомнить купить к моему событию</div>
                                            <script type="text/javascript" src="/js/datepicker.js"></script>
                                            <link rel="stylesheet" type="text/css" media="all" href="/css/datepicker.css" />
                                            <input type="text" value="<?
                                            if ($notificationReminder) {
                                                echo date("d.m.Y", strtotime($notificationReminder->getDateevent()));
                                            } else {
                                                echo "Выберите дату";
                                            }
                                            ?>" name="dateevent" class="datepicker" id="anyID" style="font-size: 11px;color: #707070;padding: 2px 10px;"><br/>
                                            <input type="text" value="<?
                                            if ($notificationReminder) {
                                                echo $notificationReminder->getComment();
                                            } else {
                                                echo "Оставьте комментарий";
                                            }
                                            ?>" name="comment" onclick="if ($(this).val() == 'Оставьте комментарий')
                                            $(this).val('');" style="width: 193px; border: 1px solid #e0e0e0; height: 23px; margin-top: 5px; padding-left: 2px;font-size: 11px;color: #707070;padding: 0 10px;">
                                        </td>
                                  </tr>
                                </table>
                                <? if (!$sf_user->isAuthenticated()) { ?>
                                  <div class="notification-buttons">
                                    Чтобы настроить оповещения, вы должны быть авторизованны.<br/><br/>
                                    <div>
                                      <a class="AuthNotificationButton" href="/guard/login"></a>
                                      Для постоянных клиентов
                                    </div>
                                    <div>
                                      <a class="RegNotificationButton" href="/register"></a>
                                      Для новых клиентов
                                    </div>
                                  </div>

                                <? } else { ?>
                                  <div class="notification-buttons">
                                    <div>
                                      <a class="SaveNotificationButton" href="" onclick="$('.blockSettingNotification .setNotification').ajaxForm(function (result) {
                                              $('.blockSettingNotification .settingNotificationContent').html(result);
                                          });
                                          $('.blockSettingNotification .setNotification').submit();
                                          $('.redButtonNotificationProduct').removeClass('redButtonNotificationProduct').addClass('greenNotificationProduct');
                                          $('.blockSettingNotification .setNotification input').each(function (i) {
                                              $('.SettingNotificationBlock input[name=' + $(this).attr('name') + ']').attr('value', $(this).val());
                                              if ($(this).prop('checked')) {
                                                  $('.SettingNotificationBlock input[name=' + $(this).attr('name') + ']').attr('checked', '')
                                              } else {
                                                  $('.SettingNotificationBlock input[name=' + $(this).attr('name') + ']').removeAttr('checked')
                                              }
                                          });
                                          return false;">
                                      </a>
                                    </div>
                                    <div>
                                      <a class="SettingNotificationButton" href="/customer/notification"></a>
                                    </div>
                                  </div>

                                <? } ?>

                                <div style="clear: both;"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div
              class="<? if ($sf_user->getGuardUser()) {
                  if (NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("product_id=?", $product->getId())->fetchOne())
                  {
                    echo "greenNotificationProduct";
                  }
                  else {
                    echo "redButtonNotificationProduct";
                  }
              } else {
                  echo "redButtonNotificationProduct";
              }
              ?>"
              onclick="settingNotification()"><div style="background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px;width: 170px">
              <u>Настроить оповещение</u>
            </div>
          </div>

          <div style="clear:both;  height: 5px;"></div>

          <script>
              function settingCompare() {
                  $('<div/>').click(function (e) {
                      if (e.target != this)
                          return;
                      $(this).remove();
                  }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockSettingCompare").appendTo('body');
                  $('.blockSettingCompare').html($(".SettingCompareBlock").html());
                  $('.blockSettingCompare').css("padding-top", $(window).scrollTop() + ($(window).height() / 2 - $('.blockSettingCompare').children().height() / 2))
                  $('.blockSettingCompare').height($("body").outerHeight() - $('.blockSettingCompare').css("padding-top").replace("px", "") - 1);
                  $(document).keyup(function (e) {

                      if (e.keyCode == 27) {
                          $('.blockSettingCompare').remove();
                      }   // esc
                  });
              }
            </script>
            <div class="SettingCompareBlock" style="display: none;">
                <div class="form-popup-wrapper">
                    <div style="width:700px; margin-left: 150px;
                         background-color: rgba(255, 255, 255, 1);
                         border:1px solid #c3060e; padding: 20px; ">

                        <div onClick="$('.blockSettingCompare').remove();" class='close'></div>

                        <div style="color:#c3060e; text-align: center; width: 100%; font-size: 18px;">Добавление товара в сравнение</div><br/>
                        <div class="settingCompareContent">
                            <? if (!$sf_user->isAuthenticated()) { ?>

                                Добавляйте товар в сравнение. Это позволит с легкостью сравнить характеристики товара и выбрать для себя самый лучший.<br/><br/>
                                Чтобы добавить товар в сравнение, вы должны быть авторизированы.<br/><br/>
                                <div style="float: left; width: 240px;text-align: center;    margin-left: 100px;"><a class="AuthCompareButton" href="/guard/login"></a>Для постоянных клиентов</div>
                                <div style="float: left; width: 240px;text-align: center;"><a class="RegCompareButton" href="/register"></a>Для новых клиентов</div>

                            <? } else { ?>
                                Товар добавлен в сравнение.<br /><br />
                                Для сравнения вы добавили всего:
                                <?
                                echo count($products_compare) + 1;
                                ?>
                                товара<br /><br />
                                <div style="float: left;text-align: center;    margin-left: 90px;"><a class="CompareEnableButton" href="/compare"></a></div>
                                <div style="float: left;text-align: center;margin-left: 10px;"><div class="CompareLastButton" onClick="$('.blockSettingCompare').remove();"></div></div>

                            <? } ?>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                </div>
            </div>


            <? if ($prodInCompare === true) { ?>
                <a class="greenButtonAddToCompare" href='/compare'><div style="background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px;width: 170px">Перейти к сравнению</div></a>
            <? }
            else { ?>
                <a href="" class="redButtonAddToCompare" style="margin-top: 5px;" onClick='javascript:
                            $(this).after($("<a class=\"greenButtonAddToCompare\">").attr("href", "/compare").html("<div style=\"background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px; width: 170px\">Перейти к сравнению</div>")).remove();
                            // addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                            $.post("/cart/addtocompare/<?= $product->getId() ?>");
                            /*settingCompare();*/
                            return false;'>
                  <div style="background: none repeat scroll 0% 0% #fff; margin-left: 30px; height: 21px;width: 170px">Добавить в сравнение</div>
                </a>
            <? } ?>
        </div>
        <div style="width: 100%; border-bottom:1px solid #e0e0e0;height: 1px;bottom: 0;position: absolute;"></div>
    </div>

    <?
      $timer->addTime();
      $timer = sfTimerManager::getTimer('Шаблон товара: описание, отзывы, наличие в магазинах');
    ?>

    <div style="clear:both;  height: 10px;"></div>



    <div class="product-middle-block">
        <a class="redButtonBigIcoPhoto" href="#photoUsersAnchor" onClick="$('.blockUserPhoto').fadeIn()"><div>Фотографии товара <br />от покупателей</div></a>

        <a class="redButtonBigIcoCompare" href="#tabsRelBlock"><div>Похожие товары</div></a>

        <a class="redButtonBigIcoTime" href="#tabsRelBlock"><div>Просмотренные товары</div></a>

        <a class="redButtonBigIcoCart" href="#tabsRelBlock"><div>С этим товаром покупают</div></a>
    </div>
    <div class="buttonReadOurAdvantages mobile-hide"
      onmouseover="$('.blockReadOurAdvantages').fadeIn(0);"
      onmouseout="$('.blockReadOurAdvantages').fadeOut(0);">
        <div style="text-decoration: underline;">Ознакомтесь с нашими преимуществами</div>
        <div class="blockReadOurAdvantages">
            <div class="benefits-box box">
                <?
                $footer = PageTable::getInstance()->findOneBySlug("nashi-preimuschestva");
                echo $footer->getContent();
                ?>
            </div>
        </div>
    </div>

    <div style="clear:both;  height: 25px;"></div>
    <?php if ($product->getVideo() != ''):
        if (substr_count($product->getVideo(), 'video/') == 0) {
          $videoSRC = "/uploads/video/" . $product->getVideo();
        }
        else {
            $videoSRC = str_replace(array("http://www.onona.ru/video/", "https://onona.ru/video/", "http://new.onona.ru/video/"), "/uploads/video/", $product->getVideo());
        }
      ?>

        <script>
            $(document).ready(function () {
              $('#ButtonVideoOfTabset').unbind('click');
              $('#ButtonVideoOfTabset *').unbind('click');
              $("#ButtonVideoOfTabset").click(function (event) {

                $('#BackgrounAddProductToCard').fadeIn();
                playVideo();
                /*hdwebplayer({
                    id: 'playerVideoDiv',
                    swf: '/player/player.swf?api=true',
                    width: '640',
                    height: '360',
                    margin: '15',
                    video: '<?= $videoSRC ?>',
                    autoStart: 'true',
                    shareDock: 'false'
                });*/
                $('.close').click(function () {

                    $('#BackgrounAddProductToCard').fadeOut();
                    $('#playerBG').remove();
                    $('#playerdiv').css('display', 'none');
                                                        player = $('#playerVideoDiv video');
                                                        player[0].pause();
                });
                $('#playerdiv').css({'display': 'block'/*,
                    'position': 'fixed',
                    'top': (($(window).height() - $('#playerdiv').height()) / 2),
                    'left': (($(window).width() - $('#playerdiv').width()) / 2)*/});
                event.preventDefault();
                event.stopPropagation();
                return false;
              });
            });
          </script>
    <?php endif; ?>
    <div class="product-bottom-block">
        <div class="tabset" style="margin-bottom: 20px;position: relative;">
            <ul class="tab-control" style="display: block;">
                <li<?
                if (@!$_GET['comment']) {
                    echo " class=\"active wide\"";
                }
                else{
                  echo 'class="wide"';
                }
                ?>><a href="#"><span style="margin:0 11px;padding: 5px 0px 0 0px;text-align: left;width: auto;">Подробная информация о товаре</span></a></li>
                <li<?
                if (@$_GET['comment']) {
                    echo " class=\"active\"";
                }
                ?>><a href="#"><span style="margin:0 11px;padding: 5px 0px 0 0px;text-align: left;width: auto;">Отзывы (<?= $count_comm ?>)</span></a></li>
                <li><a href="#"><span style="margin:0 11px;padding: 5px 0px 0 0px;text-align: left;width: auto;">Доставка и оплата</span></a></li>
                <li><a href="#"><span style="margin:0 11px;padding: 5px 0px 0 0px;text-align: left;width: auto;">Наличие в магазинах</span></a></li>

                <?php if ($product->getVideo() != ''): ?> <li id="ButtonVideoOfTabset"><a href="#"><span style="margin:0 11px;padding: 5px 0px 0 0px;text-align: left;width: auto;">Видео</span></a></li> <?php endif; ?>

            </ul>
            <div class="tab" style="position: relative; <?
              if (@!$_GET['comment']) {
                  echo "display:block;";
              }
              ?>"  itemprop="description"  oncontextmenu="return false;" ondragstart="return false;">

              <?php
                $dom = new DOMDocument;
                //$dom->loadHTML($product['content']);
                $dom->loadHTML(mb_convert_encoding($product->getContent(), 'HTML-ENTITIES', 'UTF-8'));
                $wiwthSpoilerHeight = 300;
                foreach ($dom->getElementsByTagName('iframe') as $node) {
                    if ($node->hasAttribute('src')) {
                        echo '<div style="display: block;position: relative;padding-top: 56.2857%;">'.
                          '<iframe allowfullscreen="" frameborder="0" height="425" src="' . str_replace('http://', 'https://', $node->getAttribute('src')) . '" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;"></iframe></div>';
                        $wiwthSpoilerHeight = 150;
                    }
                }
                ?>
                <div id="blockDescriptionContentProduct">
                    <script type="text/javascript">
                        function disableSelection(target) {
                            if (typeof target.onselectstart != "undefined")
                                target.onselectstart = function () {
                                    return false
                                }
                            else if (typeof target.style.MozUserSelect != "undefined")
                                target.style.MozUserSelect = "none"
                            else
                                target.onmousedown = function () {
                                    return false
                                }
                            target.style.cursor = "default"
                        }

                        disableSelection(document.getElementById("blockDescriptionContentProduct"));
                    </script>


                    <?php
                    if ($product->getVideo() != ''):
                      if (substr_count($product->getVideo(), 'video/') == 0) {
                          $videoSRC = "/uploads/video/" . $product->getVideo();
                      }
                      else {
                          $videoSRC = str_replace(array("http://www.onona.ru/video/", "https://onona.ru/video/", "http://new.onona.ru/video/"), "/uploads/video/", $product->getVideo());
                      }
                      ?>
                      <div class="video-holder" style="z-index: 1;">
                          <a href="#" class="player" onClick="

                                      $('#BackgrounAddProductToCard').fadeIn();
                                      playVideo();
                                      /*hdwebplayer({
                                          id: 'playerVideoDiv',
                                          swf: '/player/player.swf?api=true',
                                          width: '640',
                                          height: '360',
                                          margin: '15',
                                          video: '<?= $videoSRC ?>',
                                          autoStart: 'true',
                                          shareDock: 'false'
                                      });*/
                                      $('.close').click(function () {

                                          $('#BackgrounAddProductToCard').fadeOut();
                                          $('#playerBG').remove();
                                          $('#playerdiv').css('display', 'none');
                                                          player = $('#playerVideoDiv video');
                                                          player[0].pause();
                                      });
                                      $('#playerdiv').css({'display': 'block'/*,
                                          'position': 'fixed',
                                          'top': (($(window).height() - $('#playerdiv').height()) / 2),
                                          'left': (($(window).width() - $('#playerdiv').width()) / 2)*/});
                                      return false;
                             ">
                                 <? /* <img src="/newdis/images/video.png" width="142" height="90" alt="image description" />
                                   <span class="name">Видео-презентация</span>
                                   <span class="play"></span> */ ?>
                          </a>
                      </div>
                    <?php endif; ?>
                    <?php
                    $dom = new DOMDocument;
                    //$dom->loadHTML($product['content']);
                    $dom->loadHTML(mb_convert_encoding($product->getContent(), 'HTML-ENTITIES', 'UTF-8'));
                    foreach ($dom->getElementsByTagName('iframe') as $node) {
                        if ($node->hasAttribute('src')) {

                            $node->parentNode->removeChild($node);
                        }
                    }
                    $productContent = mb_convert_encoding($dom->saveHTML(), 'UTF-8', 'HTML-ENTITIES');
                    $productContent= str_replace('http://onona.ru', '', $productContent);
                    echo $productContent ;
                    ?>
                    <?
                    if ($product->getTags() != "") { ?>
                        <div style="float: right; color: #c0c0c0">Тэги:
                          <a href="#" onclick="$('#tags').toggle(); return false" class="more-expand" style="float: right;margin: 0 5px;"></a>
                          <div id="tags" style="display: none; float: right;margin: 0 5px;"><?= $product->getTags() ?></div>
                        </div>
                    <? } ?>
                </div>
            </div>
            <div class="tab"
              <?
                if (@$_GET['comment']) {
                    echo " style=\"display:block;\"";
                }
              ?>>

                <?php if ($count_comm == 0): ?>
                    Об этом товаре отзывов пока нет. Будьте первым.<br />
                <?php endif; ?>
                Ваше мнение важно для нас. Таким образом, Вы помогаете другим клиентам сделать правильный выбор среди товаров.
                <div class="add-review">
                  <a href="#" onclick="$('#commentDiv').toggle(); $('.captchak').attr('src', $('.captchak').attr('data-original')); return false;">
                    Оставить свой отзыв о товаре
                  </a>
                </div>
                <p id="comments"></p>

                <div class="add-coment" style="display:none;background: none; margin: 0px;padding: 0;" id="commentDiv">
                    <form id="commentForm" method="post" action="/product/<?= $product->getSlug() ?>/addcomment" name="comment"<?php if ($count_comm > 0): ?> style="border-bottom: 1px solid #e0e0e0; margin-bottom: 20px;"<?php endif; ?>>
                        <fieldset>
                            <script>
                                $(document).ready(function () {
                                    $('#rate_div_comment').starRating({
                                        basicImage: '/images/star.gif',
                                        ratedImage: '/images/star_hover.gif',
                                        hoverImage: '/images/star_hover2.gif',
                                        ratingStars: 10,
                                        ratingUrl: '/product/rate',
                                        paramId: 'product',
                                        paramValue: 'value',
                                        rating: '0',
                                        sucessData: function (data) {
                                            $.fn.starRating.clickable["rate_div_comment"] = true;
                                            $.fn.starRating.hoverable["rate_div_comment"] = false;
                                            $("#cRate").val(data);
                                        },
                                        customParams: {productId: '<?= $product->getId() ?>', nonAdd: '1'}

                                    });
                                });
                            </script>
                            <script src="/js/jquery-validate.js"></script>
                            <script> $(document).ready(function () {


                                    $('form#commentForm').validate({
                                        onKeyup: true,
                                        sendForm: true,
                                        eachValidField: function () {

                                            $(this).closest('div').removeClass('error').addClass('success');
                                        },
                                        eachInvalidField: function () {

                                            $(this).closest('div').removeClass('success').addClass('error');
                                        },
                                        description: {
                                            comments: {
                                                required: '<div class="alert alert-error">Укажите рейтинг товара</div>',
                                                pattern: '<div class="alert alert-error">Pattern</div>',
                                                conditional: '<div class="alert alert-error">Conditional</div>',
                                                valid: '<div class="alert alert-success">Спасибо</div>'
                                            },
                                            allFields: {
                                                required: '<div class="alert alert-error">Обязательное поле</div>',
                                                pattern: '<div class="alert alert-error">Pattern</div>',
                                                conditional: '<div class="alert alert-error">Conditional</div>',
                                                valid: '<div class="alert alert-success">Спасибо</div>'
                                            }
                                        }
                                    });
                                });
                            </script>
                            <div class="row">
                                <div class="label-holder">
                                    <label>Ваша оценка:*</label>
                                </div>
                                <div class="input-holder" style="border:0;padding: 0;">
                                    <div id="rate_div_comment"></div>
                                    <input type="text" name="cRate" id="cRate" data-describedby="comment-description-<?= $product->getId() ?>" data-required="true" data-description="comments" class="required" style="width: 1px; height: 0px;" />

                                </div>
                                <div id="comment-description-<?= $product->getId() ?>" class="requeredDescription" style="  float: left;margin-left: 10px;"></div>
                            </div>
                            <? if (!$sf_user->isAuthenticated()) { ?>
                                <div class="row">
                                    <div class="label-holder">
                                        <label>Ваше имя:*</label>
                                    </div>
                                    <div class="input-holder">
                                        <input type="text" name="cName" data-describedby="cname-description-<?= $product->getId() ?>" data-required="true" data-description="allFields" class="required" />

                                    </div>
                                    <div id="cname-description-<?= $product->getId() ?>" class="requeredDescription" style="  float: left;margin-left: 10px;"></div>
                                </div>
                                <div class="row">
                                    <div class="label-holder">
                                        <label>Ваш e-mail:</label>
                                    </div>
                                    <div class="input-holder">
                                        <input type="text" name="cEmail" />
                                    </div>
                                </div>
                            <? } ?>
                            <div class="row">
                                <div class="label-holder">
                                    <label>Сообщение:*</label>
                                </div>
                                <div class="textarea-holder">
                                    <textarea cols="30" rows="5" name="cComment" data-describedby="cComment-description-<?= $product->getId() ?>" data-required="true" data-description="allFields" class="required"></textarea>

                                </div>
                                <div id="cComment-description-<?= $product->getId() ?>" class="requeredDescription" style="  float: left;margin-left: 10px;"></div>
                            </div>
                            <div class="row">
                                <div class="label-holder">
                                    <label>Укажите код:*</label>
                                </div>
                                <div class="capcha-holder">
                                    <img src="/images/pixel.gif" data-original="/captcha/kcaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" width="139" height="48" class="captchak" alt="captcha"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="label-holder">&nbsp;</div>
                                <div class="input-holder">
                                    <input type="text" name="cText"  data-describedby="cText-description-<?= $product->getId() ?>" data-required="true" data-description="allFields" class="required" />
                                </div>
                                <div id="cText-description-<?= $product->getId() ?>" class="requeredDescription" style="  float: left;margin-left: 10px;"></div>

                            </div>
                            <div class="row">
                                <div class="label-holder ">
                                    <label></label>
                                </div>
                                <div class="textarea-holder" style="border:0;padding: 0;">
                                  <input id='accept-coment' type='checkbox'
                                  data-describedby="cAccept-description-<?= $product->getId() ?>"
                                  data-required="true"
                                  data-description="allFields"
                                  class="required">
                                  <label for='accept-coment' >Я принимаю условия <a href='/personal_accept' target='_blank'>Пользовательского соглашения</a></label>

                                </div>
                                <div id="cAccept-description-<?= $product->getId() ?>" class="requeredDescription" style="  float: left;margin-left: 10px;"></div>
                            </div>
                            <div class="required">* - поля, отмеченные * обязательны для заполнения.</div>

                            <div class="descr" style="text-align: left;color:#414141;">Внимание! Публикация отзывов производится после предварительной модерации.</div>
                            <div class="btn-holder"<?php if ($count_comm > 0): ?> style="margin-bottom: 20px;"<?php endif; ?>>
                                <a class="sendCommentButton" href="" onclick="$('#commentForm').find('[type=\'submit\']').trigger('click');
                                        /*$('#commentForm').submit();*/

                                        return false;"></a>
                                <input type="submit" class="sendCommentButton" style="display: none;" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <?php if ($count_comm > 0): ?>

                    <div class="AllCommentsTurn" onclick="if ($(this).children('.AllCommentsTurnButtom').html() == '-') {
                                    $(this).children('.AllCommentsTurnButtom').css('background', 'url(\'/images/newcat/+.png\')');
                                    $(this).children('.AllCommentsTurnButtom').html('').removeAttr('data-minus');
                                    $(this).children('.AllCommentsTurnText').html('Развернуть все отзывы');
                                    $('.blockComment').each(function (i) {
                                        $(this).children('.blockCommentContent').fadeOut();
                                        $(this).children('.blockCommentTop').children('.blockCommentTopPlus').html('').removeAttr('data-minus');
                                        $(this).children('.blockCommentTop').children('.blockCommentTopPlus').css('background', 'url(\'/images/newcat/+.png\')');
                                        //console.log($(this).children('.blockCommentTopPlus'));
                                    });
                                } else {
                                    $(this).children('.AllCommentsTurnButtom').css('background', 'none repeat scroll 0 0 #c3060e');
                                    $(this).children('.AllCommentsTurnButtom').html('-').attr('data-minus', '1');
                                    $(this).children('.AllCommentsTurnText').html('Свернуть все отзывы');
                                    $('.blockComment').each(function (i) {
                                        $(this).children('.blockCommentTop').children('.blockCommentTopPlus').css('background', 'none repeat scroll 0 0 #c3060e');
                                        $(this).children('.blockCommentContent').fadeIn();
                                        $(this).children('.blockCommentTop').children('.blockCommentTopPlus').html('-').attr('data-minus', '1');
                                    });
                                }"><div class="AllCommentsTurnButtom" data-minus="1">-</div><div class="AllCommentsTurnText">Свернуть все отзывы</div></div>


                    <script>
                        function NoLikeDislike() {
                            $('<div/>').click(function (e) {
                                if (e.target != this)
                                    return;
                                $(this).remove();
                            }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockNoLikeDislike").appendTo('body');
                            $('.blockNoLikeDislike').html($(".NoLikeDislikeBlock").html());
                            $('.blockNoLikeDislike').css("padding-top", $(window).scrollTop() + ($(window).height() / 2 - $('.blockNoLikeDislike').children().height() / 2))

                            $('.blockNoLikeDislike').height($("body").outerHeight() - $('.blockNoLikeDislike').css("padding-top").replace("px", "") - 1);
                            $(document).keyup(function (e) {

                                if (e.keyCode == 27) {
                                    $('.blockNoLikeDislike').remove();
                                }   // esc
                            });
                        }
                    </script>
                    <div class="NoLikeDislikeBlock" style="display: none;">
                        <div class="form-popup-wrapper">
                            <div class="form-popup-content">
                                <div onClick="$('.blockNoLikeDislike').remove();" class='close'></div>

                                <div class="settingNoLikeDislikeContent">

                                    Для голосования, вы должны быть авторизованны.<br/><br/>
                                    <div><a class="AuthNoLikeDislikeButton" href="/guard/login"></a>Для постоянных клиентов</div>
                                    <div><a class="RegNoLikeDislikeButton" href="/register"></a>Для новых клиентов</div>


                                </div>
                                <div style="clear: both;"></div>
                            </div>
                        </div>
                    </div>
                    <ul class="review-list" style="background: url('/newdis/images/dashed-line3.gif') repeat-x scroll 0 0 transparent;   padding: 10px 0 0;">
                        <?php foreach ($comments as $numComment => $comment): ?>
                            <li style="background: none;">
                                <div class="blockComment">
                                    <div class="blockCommentTop">
                                        <div class="blockCommentTopPlus" data-minus="1" onclick="$(this).parents('.blockComment').children('.blockCommentContent').toggle();
                                                        if ($(this).html() == '-') {
                                                            $(this).html('').removeAttr('data-minus');
                                                            $(this).css('background', 'url(\'/images/newcat/+.png\')');
                                                        } else {
                                                            $(this).html('-').attr('data-minus', '1');
                                                            $(this).css('background', 'none repeat scroll 0 0 #c3060e');
                                                        }"><?= $numComment < 5 ? '-' : '+' ?></div>
                                        <div class="blockCommentTopName">
                                            <?php if ($comment->getUsername() != ""): ?>
                                                <?= htmlspecialchars($comment->getUsername()) ?>
                                            <?php else: ?>
                                                <?= htmlspecialchars($comment->getSfGuardUser()->getFirstName() != "" ? $comment->getSfGuardUser()->getFirstName() : $comment->getSfGuardUser()->getLastName()) ?>

                                            <?php endif; ?>
                                        </div>
                                        <div class="blockCommentTopDataRate">
                                            <div style="float: left;"><?= date("d.m.Y H:i", strtotime($comment->getCreatedAt())) ?></div><div style="float: right">
                                                <div class="stars" style="margin: 3px 0 0 5px;">
                                                    <span style="width:<?= $comment->getRateSet() > 0 ? $comment->getRateSet() * 10 : ($product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) * 10 : 0) ?>%;"></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="blockCommentContent"<?= $numComment < 5 ? '' : ' style="display: none;"' ?>>
                                      <div><?= $comment->getText() ?>

                                          <?php if ($comment->getAnswer() != ""): ?>
                                              <div style="margin-left: 15px;"><b>Ответ:</b><p><?= $comment->getAnswer() ?></p></div>
                                          <?php endif; ?>
                                      </div>
                                      <script>
                                          function LikeDislike($commId, $params) {
                                              $("rateComment_" + $commId).load("/product/ratecomment/" + $commId, {rate: $params});
                                          }
                                      </script>
                                      <div class="LikeDislikeComment LikeDislikeCommentId-<?= $comment->getId() ?>">
                                        <? /* if (sfContext::getInstance()->getRequest()->getCookie("rateComment_" . $comment->getId()) != "") { */ ?>
                                        <script>
                                          function LikeDislikeCommentSend($comId, $rate) {
                                            <? if (!$sf_user->isAuthenticated()) { ?>
                                                        NoLikeDislike();
                                            <? }
                                            else { ?>
                                              $('.LikeDislikeCommentId-' + $comId).load('/product/ratecomment/' + $comId, {rate: $rate});
                                            <? } ?>
                                          }
                                        </script>
                                            <div
                                              class="LikeDislikeCommentLike<? if (sfContext::getInstance()->getRequest()->getCookie("rateComment_" . $comment->getId()) == "like") { ?> active<? } ?>"
                                              <? if (sfContext::getInstance()->getRequest()->getCookie("rateComment_" . $comment->getId()) == "dislike") { ?>
                                                 style="cursor: default;"
                                              <? }
                                              else { ?>
                                                onclick="LikeDislikeCommentSend(<?= $comment->getId() ?>, 'plus');"
                                              <? } ?>>
                                              <?= $comment->getRatePlus() == "" ? '0' : $comment->getRatePlus() ?>
                                            </div>
                                            <div
                                              class="LikeDislikeCommentDislike<? if (sfContext::getInstance()->getRequest()->getCookie("rateComment_" . $comment->getId()) == "dislike") { ?> active<? } ?>"
                                              <? if (sfContext::getInstance()->getRequest()->getCookie("rateComment_" . $comment->getId()) == "like") { ?>
                                                style="cursor: default;"
                                              <? }
                                              else { ?>
                                                onclick="LikeDislikeCommentSend(<?= $comment->getId() ?>, 'minus');"
                                              <? } ?>>
                                                <?= $comment->getRateMinus() == "" ? '0' : $comment->getRateMinus() ?>
                                            </div>

                                            <? /* } else { ?>
                                              <div class="LikeDislikeCommentLike" onClick="if ($(this).hasClass('active')) {
                                              } else {
                                              }"><?= $comment->getRatePlus() == "" ? '0' : $comment->getRatePlus() ?></div>
                                              <div class="LikeDislikeCommentDislike" onClick="if ($(this).hasClass('active')) {
                                              } else {
                                              }"><?= $comment->getRateMinus() == "" ? '0' : $comment->getRateMinus() ?></div>

                                              <? } */ ?>
                                        </div>
                                    </div>
                                </div>

                                <? /*

                                  <div class="head">
                                  <?php if ($comment->getUsername() != ""): ?>
                                  <a onClick="return false;" class="name"><?= $comment->getUsername() ?></a>
                                  <?php else: ?> <a onClick="return false;" class="name"><?= $comment->getSfGuardUser()->getFirstName() != "" ? $comment->getSfGuardUser()->getFirstName() : $comment->getSfGuardUser()->getLastName() ?></a>

                                  <?php endif; ?> ,
                                  <span class="date"><?= date("d.m.Y H:i", strtotime($comment->getCreatedAt())) ?></span>
                                  </div>
                                  <p><?= $comment->getText() ?></p>

                                  <?php if ($comment->getAnswer() != ""): ?>
                                  <div style="margin-left: 15px;"><b>Ответ:</b><p><?= $comment->getAnswer() ?></p></div>
                                  <?php endif; ?> */ ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="tab">
              <?
                $dostavkaIoplata = PageTable::getInstance()->findOneBySlug("dostavka-i-oplata");
                echo $dostavkaIoplata->getContent();
              ?>
            </div>
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
            <!-- <div class="tab"></div> -->


        </div>
        <div style="margin: 40px 0 0px 0; width:100%;height: 1px;clear: both;"></div>
        <?
          $timer->addTime();
          $timer = sfTimerManager::getTimer('Шаблон товара: фотографии клиентов');
        ?>
        <div class="separator" style="height: 14px; background-size: 750px auto; background-position: 0px -115px; width: 100%;margin-bottom: 10px;">
            &nbsp;</div>
        <a id="photoUsersAnchor" style="  position: absolute;
           margin-top: -40px;"></a>
        <div style="clear: both; height: 10px"></div>
        <script>
            function uploadUserPhoto() {
                $('<div/>').click(function (e) {
                    if (e.target != this)
                        return;
                    $(this).remove();
                })/*.attr('id', 'blockUploadUserPhoto-' + $id)*/.css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockUploadUserPhoto").appendTo('body');
                $('.blockUploadUserPhoto').html($(".uploadUserPhotoBlock").html());
                $(document).keyup(function (e) {

                    if (e.keyCode == 27) {
                        $('.blockUploadUserPhoto').remove();
                    }   // esc
                });
                var wrapper = $(".blockUploadUserPhoto .inputUploadUserPhoto"),
                        inp = wrapper.find("input"),
                        btn = wrapper.find(".button"),
                        lbl = wrapper.find("mark");
                // Crutches for the :focus style:
                inp.focus(function () {
                    wrapper.addClass("focus");
                }).blur(function () {
                    wrapper.removeClass("focus");
                });
                var file_api = (window.File && window.FileReader && window.FileList && window.Blob) ? true : false;
                inp.change(function () {
                    var file_name;
                    if (file_api && inp[ 0 ].files[ 0 ])
                        file_name = inp[ 0 ].files[ 0 ].name;
                    else
                        file_name = inp.val().replace("C:\\fakepath\\", '');
                    if (!file_name.length)
                        return;
                    if (lbl.is(":visible")) {
                        lbl.text(file_name);
                    } else
                        btn.text(file_name);
                }).change();
            }
            $(window).resize(function () {
                $(".blockUploadUserPhoto .inputUploadUserPhoto input").triggerHandler("change");
            });
            function uploadingFiles() {
                //var fd = new FormData('file', $(".blockUploadUserPhoto .inputUploadUserPhoto").find("input").files);
                var fd = new FormData();
                fd.append("image", $(".blockUploadUserPhoto .inputUploadUserPhoto").find("input")[0].files[0]); // Append the file
                $.ajax({url: '/product/uploaduserphoto/<?= $product->getId() ?>',
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (result) {
                        if (result == 'true') {
                            $(".blockUploadUserPhoto .ContentUploadUserPhoto").html("Фотография загружена. После модерации она станет доступна.");
                        } else {
                            $(".blockUploadUserPhoto .ContentUploadUserPhoto").html("<b style='color: #c3060e'>Произошла ошибка. Попробуйте ещё раз.</b><br/><br/>" + $(".blockUploadUserPhoto .ContentUploadUserPhoto").html());
                        }
                    }
                });
            }
        </script>
        <div class="uploadUserPhotoBlock" style="display: none">

            <div class="form-popup-wrapper">
                <div class="form-popup-content" <?/* style="width:500px; margin-left: 100px;
                     background-color: rgba(255, 255, 255, 1);
                     border:1px solid #c3060e; padding: 20px; "*/?>>
                    <div onClick="$('.blockUploadUserPhoto').remove();" class='close'></div>

                    <div class="header">Загрузка фотографии</div><br/>
                    <div class="ContentUploadUserPhoto">
                        <? if (!$sf_user->isAuthenticated()) { ?>
                            Чтобы добавить свои фотографии в альбом, вы должны быть авторизированы.<br/><br/><br/>
                            <div style="float: left; width: 240px;text-align: center;"><a class="AuthPhotoUploadButton" href="/guard/login"></a>Для постоянных клиентов</div>
                            <div style="float: left; width: 240px;text-align: center;"><a class="RegPhotoUploadButton" href="/register"></a>Для новых клиентов</div>

                            <div style="clear: both;"></div>
                        <? }
                        else { ?>
                            Нажмите на кнопку “Обзор”, затем выберите на своем компьютере нужное фото и нажмите на кнопку “Загрузить”.<br/><br/>
                            <a href="/pravila-razmescheniya-foto" target="_blank">Правила размещения фото</a><br/><br/>
                            <label class="inputUploadUserPhoto">
                                <span class="button"></span>
                                <mark>Файл не выбран</mark>
                                <input type="file" name="inputUploadUserPhoto">
                            </label><br/><br/>
                            <input type="checkbox" onClick="if ($(this).prop('checked')) {
                                        $('.blockUploadUserPhoto .ButtonUploadUserPhoto').fadeIn();
                                    } else {
                                        $('.blockUploadUserPhoto .ButtonUploadUserPhoto').fadeOut();
                                    }"> Я согласен c правилами размещения фото<br/><br/>

                            <div style="color:#c3060e; text-align: center; width: 100%; font-size: 15px;"><div class="ButtonUploadUserPhoto" onclick="uploadingFiles()"></div></div>
                        <? } ?>
                    </div><? /* <input type="file" name="userPhoto" class="inputUploadUserPhoto"> */ ?>
                </div>
            </div>
        </div>
        <div class="blockUserPhoto"<? if ($photosUser->count() > 0) { ?> style="display: block;"<? } ?>>
            <div class="blockUserPhotoTop">
              <div class="blockUserPhotoTopName">Фотографии этого товара от покупателей</div>
              <div class="blockUserPhotoTopUpload"><div class="redButtonSmallIcoPhoto" onclick="uploadUserPhoto()"><div>Загрузить свои фото<div class="redButtonSmallIcoStrel"></div></div></div></div>
              <div class="blockUserPhotoTopCount">Всего фото: <?= $photosUser->count() ?></div>
            </div>
            <? if ($photosUser->count() > 0) { ?>
              <script>
                (function ($) {
                  $(window).load(function () {


                      $(".blockUsePhotosContent").mCustomScrollbar({
                          axis: "x",
                          scrollButtons: {enable: true},
                          theme: "3d",
                          scrollbarPosition: "outside",
                          autoExpandScrollbar: true,
                          autoDraggerLength: false,
                          advanced: {autoExpandHorizontalScroll: true}
                      });
                  });
                })(jQuery);
              </script>
            <? } ?>
            <div class="blockUsePhotosContent">
                <? if ($photosUser->count() > 0) { ?>
                    <ul class="UserPhotosPreShow" style="<? /* width:<?= $photos->count() * 185 ?>px; */ ?>height: 185px;">
                        <? foreach ($photosUser as $numPhotos => $photo) { ?>
                            <li>
                                <span>
                                  <img src="/uploads/photouser/thumbnails/<?= $photo->getFilename() ?>" style="max-width: 175px; max-height: 175px;" onclick='$(".UserPhotosPreShow li span").each(function (i) {
                                            $(this).removeClass("activePhotosPreShow")
                                        });
                                        $(this).parent().addClass("activePhotosPreShow");
                                        $("<div/>").click(function (e) {
                                            if (e.target != this)
                                                return;
                                            $(this).remove();
                                        }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 400 - $(window).scrollTop()).addClass("blockShowUserPhoto").appendTo("body");
                                        $.post("/product/showuserphotos/" + <?= $photo->getId() ?>, {photosKeys: "<?= implode(",", $photosUser->getPrimaryKeys()) ?>"},
                                                function (data) {
                                                    $(".blockShowUserPhoto").html(data);
                                                });
                                        $(document).keyup(function (e) {

                                            if (e.keyCode == 27) {
                                                $(".blockShowUserPhoto").remove();
                                            }   // esc
                                        });'>
                                </span>
                            </li>
                          <? } ?>
                    </ul>

                <? }
                else { ?>
                  <br />
                  <div style="text-align: center;">Еще никто не загрузил фото этого товара. Будьте первым.</div>
                <? } ?>
            </div>
        </div>
        <?
          $timer->addTime();
          $timer = sfTimerManager::getTimer('Шаблон товара: нижний блок с товарами');
          /*
        ?>
        <script>
            $(document).ready(function () {
                $(".tab-control li").each(function (index) {
                    if ($(this).find('a').attr("href") != "#") {
                        $(this).click(function (event) {
                            if ($(this).attr("countClick") > 0) {
                                $(this).unbind('click');
                                $(this).find('*').unbind('click');
                                location.href = $(this).find('a').attr("href");
                            } else {

                                $(".tab-control li").each(function (index) {
                                    $(this).attr("countClick", "0");
                                });
                                if ($(this).attr("countClick") === undefined) {
                                    $(this).attr("countClick", "0");
                                }
                                $(this).attr("countClick", eval($(this).attr("countClick")) + 1);
                            }
                        });
                    } else {
                        $(this).click(function (event) {
                            $(".tab-control li").each(function (index) {
                                $(this).attr("countClick", "0");
                            });
                        });
                    }
                });
            });
        </script>
        <a id="SimilarItem" style="position: absolute; margin-top: -40px;"></a>

        <div class="tabset" style="margin-bottom: 20px;position: relative;    overflow: visible;">
            <ul class="tab-control" style="display: block;">
                <li class="active"><a href="#" id="SimilarItemInset"><span style="margin:0 17px;padding: 5px 0px 0 0px;text-align: left;width: auto;">Похожие товары</span></a></li>
                <li class="wide"><a href="#" id="BuyWithItemInset"><span style="margin:0 17px;padding: 5px 0px 0 0px;text-align: left;width: auto;">Вместе c этим товаром покупают</span></a></li>
                <li><a href="#" id="ViewItemInset"><span style="margin:0 17px;padding: 5px 0px 0 0px;text-align: left;width: auto;">Просмотренные товары</span></a></li>
                <li><a href="/related"><span style="margin:0 17px;padding: 5px 0px 0 0px;text-align: left;width: auto;">Лидеры продаж</span></a></li>

            </ul>
            <div class="tab" style='display: block'>

                <script>
                    (function ($) {
                        $(window).load(function () {


                            $(".blockSimilarItemContent").mCustomScrollbar({
                                axis: "x",
                                scrollButtons: {enable: true},
                                theme: "3d",
                                scrollbarPosition: "outside",
                                autoExpandScrollbar: true,
                                autoDraggerLength: false,
                                advanced: {autoExpandHorizontalScroll: true}
                            });
                        });
                    })(jQuery);
                  </script>
                <div class="blockSimilarItemContent">
                    <ul class="ulSimilarItemContent item-list gtm-detail-similar">

                        <?
                        $index=0;
                        foreach ($categoryProduct as $productFooter['id'] => $productFooter) {
                            echo '<li class="prodTable-' . $productFooter['id'] . (($productFooter['count'] == 0) ? " liProdNonCount" : "") . '">';
                            echo '<div '.
                                    'class="gtm-detail-item" '.
                                    'style="display: none;" '.
                                    'data-id="'.$productFooter['id'].'" '.
                                    'data-name="'.$productFooter['name'].'" '.
                                    'data-category="'.$productFooter['cat_name'].'" '.
                                    'data-price="'.$productFooter['price'].'" '.
                                    'data-index="'.$index++.'" '.
                                    'data-list="Похожие товары" '.
                                    '>'.
                                    // print_r($productFooter, true).
                                  '</div>';
                            if (!isset($childrensAll[$productFooter['id']])) {
                                $childrensAll[$productFooter['id']] = array();
                            }

                            include_partial("product/productBooklet", array(
                                'sf_cache_key' => $productFooter['id'],
                                'products' => $categoryProduct,
                                'product' => $productFooter,
                                'childrens' => $childrensAll[$productFooter['id']],
                                'comment' => isset($commentsAll[$productFooter['id']]) ? $commentsAll[$productFooter['id']] : 0,
                                'commentsAll' => $commentsAll,
                                'photo' => $photosAll[$productFooter['id']],
                                'photosAll' => $photosAll,
                                'autoLoadPhoto' => true
                                    )
                            );

                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="tab">

                <script>
                    (function ($) {
                        $(window).load(function () {
                            $(".blockBuyWithItemContent").mCustomScrollbar({
                                axis: "x",
                                scrollButtons: {enable: true},
                                theme: "3d",
                                scrollbarPosition: "outside",
                                autoExpandScrollbar: true,
                                autoDraggerLength: false,
                                advanced: {autoExpandHorizontalScroll: true}
                            });
                        });
                    })(jQuery);
                </script>
                <div class="blockBuyWithItemContent">
                    <ul class="ulBuyWithItemContent item-list">

                        <?
                        if (count($productsBuywithitem) > 0) {

                            foreach ($productsBuywithitem as $productFooter['id'] => $productFooter) {
                                echo '<li class="prodTable-' . $productFooter['id'] . (($productFooter['count'] == 0) ? " liProdNonCount" : "") . '">';
                                echo '<div '.
                                        'class="gtm-detail-item" '.
                                        'style="display: none;" '.
                                        'data-id="'.$productFooter['id'].'" '.
                                        'data-name="'.$productFooter['name'].'" '.
                                        'data-category="'.$productFooter['cat_name'].'" '.
                                        'data-price="'.$productFooter['price'].'" '.
                                        'data-index="'.$index++.'" '.
                                        'data-list="С этим товаром покупают" '.
                                        '>'.
                                        // print_r($productFooter, true).
                                      '</div>';
                                if (!isset($childrensAll[$productFooter['id']])) {
                                    $childrensAll[$productFooter['id']] = array();
                                }

                                include_partial("product/productBooklet", array(
                                    'sf_cache_key' => $productFooter['id'],
                                    'products' => $productsBuywithitem,
                                    'product' => $productFooter,
                                    'childrens' => $childrensAll[$productFooter['id']],
                                    'comment' => isset($commentsAll[$productFooter['id']]) ? $commentsAll[$productFooter['id']] : 0,
                                    'commentsAll' => $commentsAll,
                                    'photo' => $photosAll[$productFooter['id']],
                                    'photosAll' => $photosAll,
                                    'autoLoadPhoto' => true
                                        )
                                );

                                echo "</li>";
                            }
                        } else {
                            $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
                            $ProductsRandBlock = Doctrine_Core::getTable('Product')
                                    ->createQuery('p')->select("*")->addSelect($selectDate)
                                    ->addWhere('parents_id IS NULL')
                                    ->addWhere('is_public = \'1\'')
                                    ->addWhere('count>0')
                                    ->addWhere("generalcategory_id IN (" . $product->getGeneralCategory()->getId() . ")")
                                    ->addOrderBy("rand()")
                                    ->limit(10)
                                    ->execute();
                            foreach ($ProductsRandBlock as $productBuywithitem):
                                if ($productBuywithitem->getId() != $product->getId()):



                                    if (in_array($productBuywithitem->getId(), $arrayProdCart) === true)
                                        $prodInCart = true;
                                    else
                                        $prodInCart = false;

                                    if (in_array($productBuywithitem->getId(), $products_desire) === true)
                                        $prodInDesire = true;
                                    else
                                        $prodInDesire = false;

                                    if (in_array($productBuywithitem->getId(), $products_compare) === true)
                                        $prodInCompare = true;
                                    else
                                        $prodInCompare = false;

                                    include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $productBuywithitem, 'sf_cache_key' => $productBuywithitem->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-productShowItems", 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false), 'agentIsMobile' => $agentIsMobile, "productsKeys" => implode(",", $ProductsRandBlock->getPrimaryKeys()), "prodInCart" => $prodInCart, "prodInDesire" => $prodInDesire, "prodInCompare" => $prodInCompare, "productShowItems" => true));

                                endif;
                            endforeach;
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="tab">
                <script>
                    (function ($) {
                        $(window).load(function () {


                            $(".blockShowItemContent").mCustomScrollbar({
                                axis: "x",
                                scrollButtons: {enable: true},
                                theme: "3d",
                                scrollbarPosition: "outside",
                                autoExpandScrollbar: true,
                                autoDraggerLength: false,
                                advanced: {autoExpandHorizontalScroll: true}
                            });
                        });
                    })(jQuery);
                </script>
                <div class="blockShowItemContent">
                    <ul class="ulShowItemContent item-list">

                        <?
                        if (count($ShowProdus) > 1):
                            foreach ($ShowProducts as $productFooter['id'] => $productFooter) {
                                echo '<li class="prodTable-' . $productFooter['id'] . (($productFooter['count'] == 0) ? " liProdNonCount" : "") . '">';
                                echo '<div '.
                                        'class="gtm-detail-item" '.
                                        'style="display: none;" '.
                                        'data-id="'.$productFooter['id'].'" '.
                                        'data-name="'.$productFooter['name'].'" '.
                                        'data-category="'.$productFooter['cat_name'].'" '.
                                        'data-price="'.$productFooter['price'].'" '.
                                        'data-index="'.$index++.'" '.
                                        'data-list="Просмотренные товары" '.
                                        '>'.
                                        // print_r($productFooter, true).
                                      '</div>';
                                if (!isset($childrensAll[$productFooter['id']])) {
                                    $childrensAll[$productFooter['id']] = array();
                                }

                                include_partial("product/productBooklet", array(
                                    'sf_cache_key' => $productFooter['id'],
                                    'products' => $ShowProducts,
                                    'product' => $productFooter,
                                    'childrens' => $childrensAll[$productFooter['id']],
                                    'comment' => (isset($commentsAll[$productFooter['id']]) ? $commentsAll[$productFooter['id']] : 0),
                                    'commentsAll' => $commentsAll,
                                    'photo' => $photosAll[$productFooter['id']],
                                    'photosAll' => $photosAll,
                                    'autoLoadPhoto' => true
                                        )
                                );

                                echo "</li>";
                            }
                        endif;
                        ?>

                    </ul>
                </div></div>
            <div class="tab">
                <script>
                    (function ($) {
                        $(window).load(function () {


                            $(".blockBestsellersContent").mCustomScrollbar({
                                axis: "x",
                                scrollButtons: {enable: true},
                                theme: "3d",
                                scrollbarPosition: "outside",
                                autoExpandScrollbar: true,
                                autoDraggerLength: false,
                                advanced: {autoExpandHorizontalScroll: true}
                            });
                        });
                    })(jQuery);
                </script>
                <div class="blockBestsellersContent">
                    <ul class="ulBestsellersContent item-list">

                        <?
                        if (csSettings::get("bestsellersProducts") != "") {
                            foreach ($productsBestsellers as $productFooter['id'] => $productFooter) {
                                echo '<li class="prodTable-' . $productFooter['id'] . (($productFooter['count'] == 0) ? " liProdNonCount" : "") . '">';
                                echo '<div '.
                                        'class="gtm-detail-item" '.
                                        'style="display: none;" '.
                                        'data-id="'.$productFooter['id'].'" '.
                                        'data-name="'.$productFooter['name'].'" '.
                                        'data-category="'.$productFooter['cat_name'].'" '.
                                        'data-price="'.$productFooter['price'].'" '.
                                        'data-index="'.$index++.'" '.
                                        'data-list="Лидеры продаж" '.
                                        '>'.
                                        // print_r($productFooter, true).
                                      '</div>';
                                if (!isset($childrensAll[$productFooter['id']])) {
                                    $childrensAll[$productFooter['id']] = array();
                                }

                                include_partial("product/productBooklet", array(
                                    'sf_cache_key' => $productFooter['id'],
                                    'products' => $productsBestsellers,
                                    'product' => $productFooter,
                                    'childrens' => $childrensAll[$productFooter['id']],
                                    'comment' => (isset($commentsAll[$productFooter['id']]) ? $commentsAll[$productFooter['id']] : 0),
                                    'commentsAll' => $commentsAll,
                                    'photo' => $photosAll[$productFooter['id']],
                                    'photosAll' => $photosAll,
                                    'autoLoadPhoto' => true
                                        )
                                );

                                echo "</li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>

        </div>
        */?>
        <a id="tabsRelBlock"></a>
        <div data-retailrocket-markup-block="5ba3a5b897a52530d41bb235" data-product-id="<?=$product->getId()?>"></div>

    </div>
    <?
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Шаблон товара:преимущества, банеры справа, рекомендуемые твары.');
    ?>
    <div class="product-bottom-block-right mobile-hide">

      <?
        $footer = PageTable::getInstance()->findOneBySlug("Banery_sprava");
        echo $footer->getContent();
      ?>
    </div>
</div>

<?
$timer->addTime();
?>
<?php
  $manufacturer = get_slot('product_manufacturer');
  $color = get_slot('product_color');
  $material = get_slot('product_material');
  $product_property = get_slot('product_property');



  slot('metaTitle','Производитель - '.$manufacturer.'   Верхняя категория - '.$generalCategory->getParent()->getName().'  Родительская категория - '.$product->getGeneralCategory());


  slot('metaTitle',
    $product->getTitle() == '' ?
    str_replace(
      array("{name}", "{articul}", "{manufacturer}, "),
      array(
        str_replace(array("'", '"'), "", $product->getName()),
        ' '.$product->getCode(),
        $manufacturer=='' ? '' : $manufacturer.', '
      ),
      csSettings::get('titleProduct')) :
    $product->getTitle()
  );
  slot('metaKeywords',
    $product->getKeywords() == '' ?
    'Купить '.str_replace(array("'", '"'), "", $product->getName()).', '.$material.$color.$product_property.' цена отзывы доставка'
    : $product->getKeywords());
  slot('metaDescription',
    $product->getDescription() == '' ?
    str_replace(array("'", '"'), "", $product->getName()).'. '.$manufacturer.$material.$color.$product_property
    : $product->getDescription());

  $product->getGeneralCategory() == '' ? $gen_cat_name = $generalCategory->getParent()->getName() : $gen_cat_name = $product->getGeneralCategory();

if (strtolower($generalCategory->getParent()->getSlug()) == 'vibratory-dlya-par' || strtolower($generalCategory->getSlug()) == 'vibratory-dlya-par'
    || strtolower($generalCategory->getParent()->getSlug()) == 'dvustoronnie_fallosy' || strtolower($generalCategory->getSlug()) == 'dvustoronnie_fallosy'
    || strtolower($generalCategory->getParent()->getSlug()) == 'nasadki-i-kolca' || strtolower($generalCategory->getSlug()) == 'nasadki-i-kolca'
    || strtolower($generalCategory->getParent()->getSlug()) == 'podarochnye-nabory' || strtolower($generalCategory->getSlug()) == 'podarochnye-nabory'
    || strtolower($generalCategory->getParent()->getSlug()) == 'seks-mashiny' || strtolower($generalCategory->getSlug()) == 'seks-mashiny') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить по выгодной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'bezremnevye-strapony' || strtolower($generalCategory->getSlug()) == 'bezremnevye-strapony'
  || strtolower($generalCategory->getParent()->getSlug()) == 'strapony_harness' || strtolower($generalCategory->getSlug()) == 'strapony_harness'
  || strtolower($generalCategory->getParent()->getSlug()) == 'nasadki_harness' || strtolower($generalCategory->getSlug()) == 'nasadki_harness'
  || strtolower($generalCategory->getParent()->getSlug()) == 'trusiki_harness' || strtolower($generalCategory->getSlug()) == 'trusiki_harness'
  || strtolower($generalCategory->getParent()->getSlug()) == 'strapony-na-nogu-golovu-i-dr' || strtolower($generalCategory->getSlug()) == 'strapony-na-nogu-golovu-i-dr'
  || strtolower($generalCategory->getParent()->getSlug()) == 'falloprotezy' || strtolower($generalCategory->getSlug()) == 'falloprotezy'
  || strtolower($generalCategory->getParent()->getSlug()) == 'strapony_dvoynoy' || strtolower($generalCategory->getSlug()) == 'strapony_dvoynoy') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить по доступной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, выгодные цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'analnye-ukrasheniya' || strtolower($generalCategory->getSlug()) == 'analnye-ukrasheniya'
  || strtolower($generalCategory->getParent()->getSlug()) == 'analnye_shariki_elochki' || strtolower($generalCategory->getSlug()) == 'analnye_shariki_elochki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'analnye_stimulyatory' || strtolower($generalCategory->getSlug()) == 'analnye_stimulyatory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'analnaya-probki-rasshiriteli' || strtolower($generalCategory->getSlug()) == 'analnaya-probki-rasshiriteli'
  || strtolower($generalCategory->getParent()->getSlug()) == 'fisting' || strtolower($generalCategory->getSlug()) == 'fisting'
  || strtolower($generalCategory->getParent()->getSlug()) == 'ochistitelnye-klizmy' || strtolower($generalCategory->getSlug()) == 'ochistitelnye-klizmy'
  || strtolower($generalCategory->getParent()->getSlug()) == 'analnye_falloimitatory' || strtolower($generalCategory->getSlug()) == 'analnye_falloimitatory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'analnye_vibratory' || strtolower($generalCategory->getSlug()) == 'analnye_vibratory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'analnye_rasshiriteli' || strtolower($generalCategory->getSlug()) == 'analnye_rasshiriteli') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить по приемлемой цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, оптимальные цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'stimulyatory-iz-metalla' || strtolower($generalCategory->getSlug()) == 'stimulyatory-iz-metalla'
    || strtolower($generalCategory->getParent()->getSlug()) == 'icicles' || strtolower($generalCategory->getSlug()) == 'icicles'
    || strtolower($generalCategory->getParent()->getSlug()) == 'stimulyatory-iz-keramiki' || strtolower($generalCategory->getSlug()) == 'stimulyatory-iz-keramiki') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить по доступной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, разумные цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'mega-masturbatory-realistiki' || strtolower($generalCategory->getSlug()) == 'mega-masturbatory-realistiki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'vaginy-realistiki' || strtolower($generalCategory->getSlug()) == 'vaginy-realistiki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'massazhery-prostaty' || strtolower($generalCategory->getSlug()) == 'massazhery-prostaty'
  || strtolower($generalCategory->getParent()->getSlug()) == 'erekcionnye-kolca-nasadki' || strtolower($generalCategory->getSlug()) == 'erekcionnye-kolca-nasadki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'udlinyauschie-nasadki-na-penis' || strtolower($generalCategory->getSlug()) == 'udlinyauschie-nasadki-na-penis'
  || strtolower($generalCategory->getParent()->getSlug()) == 'vakuumnye-pompy' || strtolower($generalCategory->getSlug()) == 'vakuumnye-pompy'
  || strtolower($generalCategory->getParent()->getSlug()) == 'ekstendery' || strtolower($generalCategory->getSlug()) == 'ekstendery'
  || strtolower($generalCategory->getParent()->getSlug()) == 'uretralnye-stimulyatory' || strtolower($generalCategory->getSlug()) == 'uretralnye-stimulyatory') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить по выгодной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'realistichnye_seks-kukly' || strtolower($generalCategory->getSlug()) == 'realistichnye_seks-kukly'
  || strtolower($generalCategory->getParent()->getSlug()) == 'kukly-prostye' || strtolower($generalCategory->getSlug()) == 'kukly-prostye'
  || strtolower($generalCategory->getParent()->getSlug()) == 'mini-seks-kukly' || strtolower($generalCategory->getSlug()) == 'mini-seks-kukly'
  || strtolower($generalCategory->getParent()->getSlug()) == 'rezinovye_sex_kukly' || strtolower($generalCategory->getSlug()) == 'rezinovye_sex_kukly'
  || strtolower($generalCategory->getParent()->getSlug()) == 'muzhchiny_i_transseksualy' || strtolower($generalCategory->getSlug()) == 'muzhchiny_i_transseksualy'
  || strtolower($generalCategory->getParent()->getSlug()) == 'silikonovye_seks_kukly' || strtolower($generalCategory->getSlug()) == 'silikonovye_seks_kukly') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): заказать по доступной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, разумные цены, огромный выбор и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'vaginy' || strtolower($generalCategory->getSlug()) == 'vaginy'
  || strtolower($generalCategory->getParent()->getSlug()) == 'popki' || strtolower($generalCategory->getSlug()) == 'popki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'rotiki' || strtolower($generalCategory->getSlug()) == 'rotiki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'universalnye' || strtolower($generalCategory->getSlug()) == 'universalnye') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): заказать по выгодной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, выгодные цены, огромный выбор и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'vibratory-hi-tech' || strtolower($generalCategory->getSlug()) == 'vibratory-hi-tech'
  || strtolower($generalCategory->getParent()->getSlug()) == 'stimulyatory-tochki-g' || strtolower($generalCategory->getSlug()) == 'stimulyatory-tochki-g'
  || strtolower($generalCategory->getParent()->getSlug()) == 'stimulyatory-klitora' || strtolower($generalCategory->getSlug()) == 'stimulyatory-klitora'
  || strtolower($generalCategory->getParent()->getSlug()) == 'vibroyajtsa-vibropuli' || strtolower($generalCategory->getSlug()) == 'vibroyajtsa-vibropuli'
  || strtolower($generalCategory->getParent()->getSlug()) == 'zhenskie-pompy' || strtolower($generalCategory->getSlug()) == 'zhenskie-pompy') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить по низкой цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, разумные цены и абсолютная конфиденциальность. Все товары проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'we-vibe' || strtolower($generalCategory->getSlug()) == 'we-vibe'
  || strtolower($generalCategory->getParent()->getSlug()) == 'jopen-izyskannye-stimulyatory-premium-klassa' || strtolower($generalCategory->getSlug()) == 'jopen-izyskannye-stimulyatory-premium-klassa'
  || strtolower($generalCategory->getParent()->getSlug()) == 'zini' || strtolower($generalCategory->getSlug()) == 'zini'
  || strtolower($generalCategory->getParent()->getSlug()) == 'ohmibod-vibratory-dlya-apple' || strtolower($generalCategory->getSlug()) == 'ohmibod-vibratory-dlya-apple'
  || strtolower($generalCategory->getParent()->getSlug()) == 'leaf-by-swan-ekologicheski-chistye-i-bezopasnye-vibratory' || strtolower($generalCategory->getSlug()) == 'leaf-by-swan-ekologicheski-chistye-i-bezopasnye-vibratory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'swan-vibromassazhery-premium-klassa' || strtolower($generalCategory->getSlug()) == 'swan-vibromassazhery-premium-klassa'
  || strtolower($generalCategory->getParent()->getSlug()) == 'lelo-seks-igrushki-1-v-evrope-i-ssha' || strtolower($generalCategory->getSlug()) == 'lelo-seks-igrushki-1-v-evrope-i-ssha'
   || strtolower($generalCategory->getParent()->getSlug()) == 'calexotics-dizainerskie-elitnye-vibratory' || strtolower($generalCategory->getSlug()) == 'calexotics-dizainerskie-elitnye-vibratory') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): заказать по доступной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Выгодные цены, быстрая доставка и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'dildo-bez-vibracii' || strtolower($generalCategory->getSlug()) == 'dildo-bez-vibracii'
  || strtolower($generalCategory->getParent()->getSlug()) == 'dildo-s-vibraciei' || strtolower($generalCategory->getSlug()) == 'dildo-s-vibraciei'
  || strtolower($generalCategory->getParent()->getSlug()) == 'bolshie-falloimitatory' || strtolower($generalCategory->getSlug()) == 'bolshie-falloimitatory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'realistichnye_falloimitatory' || strtolower($generalCategory->getSlug()) == 'realistichnye_falloimitatory'
    || strtolower($generalCategory->getParent()->getSlug()) == 'falloimitatory_na_prisoske' || strtolower($generalCategory->getSlug()) == 'falloimitatory_na_prisoske') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): заказать по доступной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, оптимальные цены и полная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'originalnye-vibratory' || strtolower($generalCategory->getSlug()) == 'originalnye-vibratory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'pulsatory' || strtolower($generalCategory->getSlug()) == 'pulsatory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'universalnye-vibratory' || strtolower($generalCategory->getSlug()) == 'universalnye-vibratory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'perezaryazhaemye' || strtolower($generalCategory->getSlug()) == 'perezaryazhaemye'
  || strtolower($generalCategory->getParent()->getSlug()) == 'dvoinogo-deistviya' || strtolower($generalCategory->getSlug()) == 'dvoinogo-deistviya'
  || strtolower($generalCategory->getParent()->getSlug()) == 'mini-vibratory' || strtolower($generalCategory->getSlug()) == 'mini-vibratory'
  || strtolower($generalCategory->getParent()->getSlug()) == 's-radioupravleniem' || strtolower($generalCategory->getSlug()) == 's-radioupravleniem'
  || strtolower($generalCategory->getParent()->getSlug()) == 'massazhery' || strtolower($generalCategory->getSlug()) == 'massazhery') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить с анонимной доставкой | '.$gen_cat_name.' по доступным ценам | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, разумные цены и полная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'trenazhery-kegelya' || strtolower($generalCategory->getSlug()) == 'trenazhery-kegelya') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): заказать с анонимной доставкой | '.$gen_cat_name.' по доступным ценам | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она» по доступной цене. Быстрая доставка и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'osheiniki-sbrui' || strtolower($generalCategory->getSlug()) == 'osheiniki-sbrui'
  || strtolower($generalCategory->getParent()->getSlug()) == 'naruchniki-bondazh' || strtolower($generalCategory->getSlug()) == 'naruchniki-bondazh'
  || strtolower($generalCategory->getParent()->getSlug()) == 'zazhimy-dlya-soskov-i-polovyh-gub' || strtolower($generalCategory->getSlug()) == 'zazhimy-dlya-soskov-i-polovyh-gub'
  || strtolower($generalCategory->getParent()->getSlug()) == 'elektrostimulyatory' || strtolower($generalCategory->getSlug()) == 'elektrostimulyatory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'bdsm-nabory' || strtolower($generalCategory->getSlug()) == 'bdsm-nabory'
  || strtolower($generalCategory->getParent()->getSlug()) == 'seks-mebel-kacheli' || strtolower($generalCategory->getSlug()) == 'seks-mebel-kacheli'
  || strtolower($generalCategory->getParent()->getSlug()) == 'bdsm_odezhda' || strtolower($generalCategory->getSlug()) == 'bdsm_odezhda') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): заказать по выгодной цене | '.$gen_cat_name.' с доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая и анонимная доставка, приемлемые цены. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'spank-shlepalki' || strtolower($generalCategory->getSlug()) == 'spank-shlepalki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'stek-krop' || strtolower($generalCategory->getSlug()) == 'stek-krop'
  || strtolower($generalCategory->getParent()->getSlug()) == 'odnohvostye-pleti' || strtolower($generalCategory->getSlug()) == 'odnohvostye-pleti'
  || strtolower($generalCategory->getParent()->getSlug()) == 'mnogohvostye-pleti' || strtolower($generalCategory->getSlug()) == 'mnogohvostye-pleti') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить с анонимной доставкой | '.$gen_cat_name.' по доступной цене | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она» по выгодной цене. Быстрая доставка и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'klyapy' || strtolower($generalCategory->getSlug()) == 'klyapy'
  || strtolower($generalCategory->getParent()->getSlug()) == 'maski' || strtolower($generalCategory->getSlug()) == 'maski') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): по приемлемой цене | '.$gen_cat_name.': заказать с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она» с доставкой по России. Оптимальные цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'preparaty' || strtolower($generalCategory->getSlug()) == 'preparaty'
  || strtolower($generalCategory->getParent()->getSlug()) == 'massazhnye-masla' || strtolower($generalCategory->getSlug()) == 'massazhnye-masla'
  || strtolower($generalCategory->getParent()->getSlug()) == 'kosmetika-s-afrodiziakami' || strtolower($generalCategory->getSlug()) == 'kosmetika-s-afrodiziakami') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): по доступной цене | '.$gen_cat_name.' - купить с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, разумные цены и полная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'smazki-na-vodnoi-osnove' || strtolower($generalCategory->getSlug()) == 'smazki-na-vodnoi-osnove'
  || strtolower($generalCategory->getParent()->getSlug()) == 'smazki-na-silikonovoi-osnove' || strtolower($generalCategory->getSlug()) == 'smazki-na-silikonovoi-osnove'
  || strtolower($generalCategory->getParent()->getSlug()) == 'aromatizirovannye-smazki' || strtolower($generalCategory->getSlug()) == 'aromatizirovannye-smazki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'vozbuzhdauschie-smazki' || strtolower($generalCategory->getSlug()) == 'vozbuzhdauschie-smazki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'prolongiruuschie-smazki' || strtolower($generalCategory->getSlug()) == 'prolongiruuschie-smazki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'analnye-smazki' || strtolower($generalCategory->getSlug()) == 'analnye-smazki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'vaginalnye-smazki' || strtolower($generalCategory->getSlug()) == 'vaginalnye-smazki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'oralnye-smazki' || strtolower($generalCategory->getSlug()) == 'oralnye-smazki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'smazki-dlya-fistinga' || strtolower($generalCategory->getSlug()) == 'smazki-dlya-fistinga'
  || strtolower($generalCategory->getParent()->getSlug()) == 'ochischauschie-dlya-seks-igrushek' || strtolower($generalCategory->getSlug()) == 'ochischauschie-dlya-seks-igrushek') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить по выгодной цене с доставкой | '.$gen_cat_name.' от секс-шопа «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, привлекательные цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'duhi-s-feromonami-dlya-muzhchin' || strtolower($generalCategory->getSlug()) == 'duhi-s-feromonami-dlya-muzhchin'
  || strtolower($generalCategory->getParent()->getSlug()) == 'duhi-s-feromonami-dlya-zhenschin' || strtolower($generalCategory->getSlug()) == 'duhi-s-feromonami-dlya-zhenschin'
  || strtolower($generalCategory->getParent()->getSlug()) == 'dezodoranty-dlya-muzhchin' || strtolower($generalCategory->getSlug()) == 'dezodoranty-dlya-muzhchin'
  || strtolower($generalCategory->getParent()->getSlug()) == 'dezodoranty-dlya-zhenschin' || strtolower($generalCategory->getSlug()) == 'dezodoranty-dlya-zhenschin') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): заказать по доступной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая и конфиденциальная доставка, доступные цены, большой выбор. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'maski-dlya-sna' || strtolower($generalCategory->getSlug()) == 'maski-dlya-sna') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить по низкой цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'igrovye-kostumy' || strtolower($generalCategory->getSlug()) == 'igrovye-kostumy'
  || strtolower($generalCategory->getParent()->getSlug()) == 'seksualnye-bodi' || strtolower($generalCategory->getSlug()) == 'seksualnye-bodi'
  || strtolower($generalCategory->getParent()->getSlug()) == 'eroticheskie-komplekty' || strtolower($generalCategory->getSlug()) == 'eroticheskie-komplekty'
  || strtolower($generalCategory->getParent()->getSlug()) == 'korsety-buste' || strtolower($generalCategory->getSlug()) == 'korsety-buste'
  || strtolower($generalCategory->getParent()->getSlug()) == 'penuary' || strtolower($generalCategory->getSlug()) == 'penuary'
  || strtolower($generalCategory->getParent()->getSlug()) == 'sorochki-mini-platya' || strtolower($generalCategory->getSlug()) == 'sorochki-mini-platya'
  || strtolower($generalCategory->getParent()->getSlug()) == 'chulki-na-telo-ketsuits' || strtolower($generalCategory->getSlug()) == 'chulki-na-telo-ketsuits'
  || strtolower($generalCategory->getParent()->getSlug()) == 'chulki-kolgoty' || strtolower($generalCategory->getSlug()) == 'chulki-kolgoty'
  || strtolower($generalCategory->getParent()->getSlug()) == 'seksualnye-trusiki' || strtolower($generalCategory->getSlug()) == 'seksualnye-trusiki') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.')- купить по доступной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, выгодные цены и абсолютная конфиденциальность. Вся продукция проверена на безопасность и имеет сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'igrovye-kostumy-plus-size' || strtolower($generalCategory->getSlug()) == 'igrovye-kostumy-plus-size'
  || strtolower($generalCategory->getParent()->getSlug()) == 'korsety-buste-plus-size' || strtolower($generalCategory->getSlug()) == 'korsety-buste-plus-size'
  || strtolower($generalCategory->getParent()->getSlug()) == 'chulki-na-telo-ketsuits-plus-size' || strtolower($generalCategory->getSlug()) == 'chulki-na-telo-ketsuits-plus-size'
  || strtolower($generalCategory->getParent()->getSlug()) == 'seksualnye-trusiki-plus-size' || strtolower($generalCategory->getSlug()) == 'seksualnye-trusiki-plus-size'
  || strtolower($generalCategory->getParent()->getSlug()) == 'platya-sorochki-penuary-plus-size' || strtolower($generalCategory->getSlug()) == 'platya-sorochki-penuary-plus-size'
  || strtolower($generalCategory->getParent()->getSlug()) == 'eroticheskie-komplekty-plus-size' || strtolower($generalCategory->getSlug()) == 'eroticheskie-komplekty-plus-size'
  || strtolower($generalCategory->getParent()->getSlug()) == 'chulki-kolgoty-plus-size' || strtolower($generalCategory->getSlug()) == 'chulki-kolgoty-plus-size'
  || strtolower($generalCategory->getParent()->getSlug()) == 'soblaznitelnye-bodi' || strtolower($generalCategory->getSlug()) == 'soblaznitelnye-bodi') {
  slot('metaTitle',$product->getName().' от '.$manufacturer.': купить по привлекательной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, оптимальные цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'eroticheskie-kostumy' || strtolower($generalCategory->getSlug()) == 'eroticheskie-kostumy'
  || strtolower($generalCategory->getParent()->getSlug()) == 'muzhskoe-bele' || strtolower($generalCategory->getSlug()) == 'muzhskoe-bele') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): заказать по доступной цене | '.$gen_cat_name.' с доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, выгодные цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'eroticheskie-kostumy-bolshie' || strtolower($generalCategory->getSlug()) == 'eroticheskie-kostumy-bolshie'
  || strtolower($generalCategory->getParent()->getSlug()) == 'muzhskoe-bele-bolshoe' || strtolower($generalCategory->getSlug()) == 'muzhskoe-bele-bolshoe') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить с доставкой | '.$gen_cat_name.' по выгодной цене | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, выгодные цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'pariki' || strtolower($generalCategory->getSlug()) == 'pariki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'podvyazki' || strtolower($generalCategory->getSlug()) == 'podvyazki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'karnavalnye-maski' || strtolower($generalCategory->getSlug()) == 'karnavalnye-maski'
  || strtolower($generalCategory->getParent()->getSlug()) == 'perchatki-i-vorotnichki' || strtolower($generalCategory->getSlug()) == 'perchatki-i-vorotnichki'
  || strtolower($generalCategory->getParent()->getSlug()) == 'pestisy-nakladki-na-soski' || strtolower($generalCategory->getSlug()) == 'pestisy-nakladki-na-soski'
  || strtolower($generalCategory->getParent()->getSlug()) == 'ukrasheniya' || strtolower($generalCategory->getSlug()) == 'ukrasheniya'
  || strtolower($generalCategory->getParent()->getSlug()) == 'poyasa-dlya-chulok' || strtolower($generalCategory->getSlug()) == 'poyasa-dlya-chulok') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.') по доступной цене | '.$gen_cat_name.': купить с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она» разумные цены, быстрая доставка, огромный выбор и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'eroticheskie-suveniry-igry' || strtolower($generalCategory->getSlug()) == 'eroticheskie-suveniry-igry'
  || strtolower($generalCategory->getParent()->getSlug()) == 'eroticheskaya-literatura' || strtolower($generalCategory->getSlug()) == 'eroticheskaya-literatura'
  || strtolower($generalCategory->getParent()->getSlug()) == 'podarochnaya-upakovka' || strtolower($generalCategory->getSlug()) == 'podarochnaya-upakovka'
  || strtolower($generalCategory->getParent()->getSlug()) == 'raznoe' || strtolower($generalCategory->getSlug()) == 'raznoe'
  || strtolower($generalCategory->getParent()->getSlug()) == 'podarochnye-sertifikaty' || strtolower($generalCategory->getSlug()) == 'podarochnye-sertifikaty'
  || strtolower($generalCategory->getParent()->getSlug()) == 'raznoe-1' || strtolower($generalCategory->getSlug()) == 'raznoe-1'
  || strtolower($generalCategory->getParent()->getSlug()) == 'seks-treningi' || strtolower($generalCategory->getSlug()) == 'seks-treningi') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): заказать по доступной цене | '.$gen_cat_name.' с анонимной доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, выгодные цены, огромный выбор и конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}

if (strtolower($generalCategory->getParent()->getSlug()) == 'my-size' || strtolower($generalCategory->getSlug()) == 'my-size'
  || strtolower($generalCategory->getParent()->getSlug()) == 'sitabella' || strtolower($generalCategory->getSlug()) == 'sitabella'
  || strtolower($generalCategory->getParent()->getSlug()) == 'sagami' || strtolower($generalCategory->getSlug()) == 'sagami'
  || strtolower($generalCategory->getParent()->getSlug()) == 'vitalis' || strtolower($generalCategory->getSlug()) == 'vitalis'
  || strtolower($generalCategory->getParent()->getSlug()) == 'masculan' || strtolower($generalCategory->getSlug()) == 'masculan'
  || strtolower($generalCategory->getParent()->getSlug()) == 'ganzo' || strtolower($generalCategory->getSlug()) == 'ganzo'
  || strtolower($generalCategory->getParent()->getSlug()) == 'okamoto' || strtolower($generalCategory->getSlug()) == 'okamoto'
  || strtolower($generalCategory->getParent()->getSlug()) == 'unilatex' || strtolower($generalCategory->getSlug()) == 'unilatex') {
  slot('metaTitle',$product->getName().' ('.$manufacturer.'): купить по доступной цене | '.$gen_cat_name.' с быстрой доставкой | Секс-шоп «Он и Она»');
  slot('metaDescription',$product->getName().' в интернет-магазине «Он и Она». Быстрая доставка, низкие цены, огромный выбор и конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
}
?>
<? //slot('gdeSlonCodes', '&codes='.$product->getId().':'.$product->getPrice()); ?>
<? slot('gdeSlonMode', 'card'); ?>
<? slot('gdeSlonCodes', 'products: [ {id: '.$product->getId().', quantity: 1, price: '.$product->getPrice().'} ],'); ?>
