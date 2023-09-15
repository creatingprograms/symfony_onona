<!DOCTYPE html>
<html lang="ru">

<head>
  <?
  global $isTest;
  $maxCart = csSettings::get('max_cart_items');
  ILTools::getRegion();
  ?>
  <? if (!$isTest) : ?>
    <script data-skip-moving='true' async src='https://antisovetnic.ru/anti/816c6eef49840cb6f7ffeb7a29f00a3a'></script>
  <? endif ?>
  <?php /* include_http_metas()*/ ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <?php include_metas() ?>
  <?php //include_title() 
  ?>
  <title><?= str_replace(array("'", '"'), "", get_slot('metaTitle') == '' ? csSettings::get('defaultTitle') : get_slot('metaTitle')) ?></title>
  <link rel="shortcut icon" href="/favicon.ico" />
  <?/*<link rel="preload" as="font" href="/frontend/fonts/MyriadPro-SemiExt.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/HelveticaNeueCyr-Roman.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/HelveticaNeueCyr-Bold.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/HelveticaNeueCyr-Light.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/HelveticaNeueCyr-Medium.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/6859-webfont.woff2" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/MyriadPro-BoldSemiExt.woff" crossorigin> */ ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <?php include_stylesheets() ?>
  <?php include_javascripts() ?>

  <?
  if ((isset($_GET['utm_source']) && $_GET['utm_source'] == 'YandexMarket')) { //Если пришли из яндексМаркета  - считаем что 18+
    sfContext::getInstance()->getResponse()->setCookie('age18', true, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
    $isYaMarket = true;
  } else $isYaMarket = false;
  ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="<?= str_replace(array("'", '"'), "", get_slot('metaKeywords') == '' ? csSettings::get('defaultKeywords') : get_slot('metaKeywords')) ?>" />
  <meta name="description" content="<?= str_replace(array("'", '"'), "", get_slot('metaDescription') == '' ? csSettings::get('defaultDescription') : get_slot('metaDescription')) ?>" />
  <meta name="format-detection" content="telephone=no">
  <?php if ($_SERVER['REQUEST_URI'] == '/') { ?>
    <meta name="robots" content="noyaca" />
  <?php } ?>

  <? if (get_slot('canonical')) : ?>
    <link rel="canonical" href="https://onona.ru<?= mb_strtolower(get_slot('canonical')) ?>" />
  <? elseif (false !== mb_stripos($_SERVER['REQUEST_URI'], '?') && empty($_GET)) : ?>
    <? //Если нет get-параметров, но при этом есть знак вопроса - пуляем каноникал
    ?>
    <link rel="canonical" href="https://onona.ru<?= mb_strtolower(str_replace('?', '', $_SERVER['REQUEST_URI'])) ?>" />
    <? //die('<pre>'.print_r($_SERVER, true));
    ?>
  <? endif ?>


  <? if ($og = get_slot('OpenGraph')) foreach ($og as $key => $value) : ?>
    <meta property="og:<?= $key ?>" content="<?= $value ?>" />
  <? endforeach ?>
  <!--[if (lt IE 9) ]>
    <script src="/frontend/js/html5shiv-printshiv.min.js" type="text/javascript"></script>
    <![endif]-->
  <? if (!$isTest) include_component('page', 'headerscripts', array('sf_cache_key' => "headerscripts")); ?>
  <meta name="google-site-verification" content="DOkN9zIewbzwZe7odaV1DfG4Astq-LSqcymyIOMeCwA" />
</head>

<body class="<?= get_slot('catalog-class') ?> <?= get_slot('is_main') ? 'fixed-head' : '' ?>" data-max-cart-count=<?= $maxCart ?>>
  <noscript>
    <div><img src="https://mc.yandex.ru/watch/144683" style="position:absolute; left:-9999px;" alt="" /></div>
  </noscript>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KBNZ6ZH" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <?

  if (
    sfContext::getInstance()->getRouting()->getCurrentRouteName() != "support" and
    sfContext::getInstance()->getRouting()->getCurrentRouteName() != "no18" and
    sfContext::getInstance()->getRequest()->getCookie('age18') != true and
    !get_slot('18ageNo')
    and !$isYaMarket
  )
    include_partial('noncache/18age', array());
  if (sfContext::getInstance()->getRouting()->getCurrentRouteName() == "no18")
    include_partial('noncache/no18', array());

  ?>
  <? if (get_slot('is_main')) : ?>
    <a class="for-newbies" href="/category/dlya_novichkov">Для новичков</a>
  <? endif ?>

  <div id="main">
    <div id="wrapper">
      <div class="header-wrap">
        <noindex>
          <header class="header">
            <div class="wrap-header-top">
              <div class="container">
                <div class="header-top">
                  <div class="header-nav-but">
                    <div class="btn-menu">
                      <span></span>
                    </div>
                  </div>
                  <a href="/" class="logo-new">
                    <img src="/frontend/images_new/On_i_ona_logo_novoe.svg" alt="onona.ru" class="-desktop">
                    <img src="/frontend/images_new/On_i_ona_logo_novoe_pazzle.svg" alt="onona.ru" class="-mobile">
                    <?/*<img src="/frontend/images_new/logo_v4.svg" alt="" width="63" class="-tablet">*/ ?>
                  </a>
                  <?/* include_component('page', 'topMenu', array(
                            'path' => $_SERVER['REQUEST_URI'],
                            'sf_cache_key' => "TopMenu-new".$_SERVER['REQUEST_URI']
                          )); */ ?>
                  <div class="wrap-form-search">
                    <div class="btn-search submit-js">
                      <svg>
                        <use xlink:href="#search-svg"></use>
                      </svg>
                    </div>
                    <form action="/search" class="header-form">
                      <input type="text" name="searchString" class="js-search search-string" value="" placeholder="Поиск по сайту...">
                      <input class="-hidden" type="submit" value="">
                    </form>
                    <div class="btn-search-close js-search-toggle" data-action="close">×</div>
                  </div>
                  <div class="smm">
                    <a href="https://www.youtube.com/channel/UCrZ-3sU3RtG5g1YhKF8JJyg" target="_blank" class="" rel="nofollow">
                      <svg>
                        <use xlink:href="#youtube-small-svg"></use>
                      </svg>
                    </a>
                    <?/*<a href="https://www.facebook.com/groups/537686253037357/?ref=share" target="_blank" class="" rel="nofollow">
                      <svg>
                        <use xlink:href="#fb-small-svg"></use>
                      </svg>
                    </a>*/ ?>
                    <a href="https://vk.com/sex_shop_onona" target="_blank">
                      <svg>
                        <use xlink:href="#vk-small-svg"></use>
                      </svg>
                    </a>
                    <?/*<a href="https://www.instagram.com/onona.ru/" target="_blank">
                      <svg>
                        <use xlink:href="#inst-small-svg"></use>
                      </svg>
                    </a>*/ ?>
                    <a href="https://t.me/ononaaaa" target="_blank">
                      <svg>
                        <use xlink:href="#telegram-small-svg" />
                      </svg>
                    </a>
                    <?/*<a href="https://dzen.ru/ononaa" target="_blank" class="js-yandex-send" data-target="perekhod-v-dcen-ikonka">
                      <svg>
                        <use xlink:href="#dzen-small-svg" />
                      </svg>
                    </a>*/ ?>
                  </div>
                  <div class="header-tel">
                    <div class="header-tel__item">
                      <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone1')) ?>"> <?= csSettings::get('phone1') ?></a>
                      <span>по России бесплатно</span>
                    </div>
                    <div class="header-tel__item">
                      <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone2')) ?>"> <?= csSettings::get('phone2') ?></a>
                      <span>круглосуточно по МСК</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="wrap-header-bottom">
              <div class="container">
                <div class="header-bottom">
                  <?
                  // if(true)
                  include_component('page', 'topMenuMainNewNew', array('path' => $_SERVER['REQUEST_URI'], 'sf_cache_key' => "TopMenu-new-main-2" . $_SERVER['REQUEST_URI']));
                  // else include_component('page', 'topMenuMainNew', array('path' => $_SERVER['REQUEST_URI'],'sf_cache_key' => "TopMenu-new-main-2".$_SERVER['REQUEST_URI']));
                  ?>
                  <?/*<div class="wrap-form-search">
                    <div class="btn-search submit-js">
                      <svg>
                        <use xlink:href="#search-svg"></use>
                      </svg>
                    </div>
                    <form action="/search" class="header-form">
                      <input type="text" name="searchString" class="js-search search-string" value="" placeholder="Поиск по сайту...">
                      <input class="-hidden" type="submit" value="">
                    </form>
                  </div>*/ ?>
                  <div class="header-services">
                    <? if (!$sf_user->isAuthenticated()) : ?>
                      <div class="wrap-login">
                        <a href="#popup-login" class="btn-login js-popup-form">
                          <svg>
                            <use xlink:href="#login-svg"></use>
                          </svg>
                        </a>
                      </div>
                      <div class="wrap-chosen">
                        <a href="#popup-login" class="btn-chosen js-popup-form">
                          <svg>
                            <use xlink:href="#chosen-svg"></use>
                          </svg>
                        </a>
                      </div>
                    <? else : ?>
                      <div class="wrap-login">
                        <a href="/lk" class="btn-login gtm-user-id" data-id="<?= sfContext::getInstance()->getUser()->getGuardUser()->getId() ?>">
                          <svg>
                            <use xlink:href="#login-svg"></use>
                          </svg>
                        </a>
                      </div>
                      <div class="wrap-chosen">
                        <? $favCount = ILTools::getFavCount(sfContext::getInstance()->getUser()->getGuardUser()); ?>
                        <a href="/customer/favorites" class="btn-chosen is-active" <?= $favCount ? 'count="' . $favCount . '"' : '' ?>>
                          <svg>
                            <use xlink:href="#chosen-svg"></use>
                          </svg>
                        </a>
                      </div>
                    <? endif ?>
                    <div class="wrap-geo mobile-only">
                      <div class="btn-search js-search-toggle" data-action="open">
                        <svg>
                          <use xlink:href="#search-svg"></use>
                        </svg>
                      </div>
                    </div>
                    <?php if (true) : ?>
                      <div class="wrap-geo js-geo-find">
                        <div class="btn-geo-find btn-login">
                          <svg>
                            <use xlink:href="#geo-svg"></use>
                          </svg>
                        </div>
                      </div>
                    <?php endif; ?>
                    <div id="header-basket">
                      <? include_component('cart_new', 'topCart', array()); ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </header>
        </noindex>
      </div>
      <? include_component('page', 'mobileMenu', array('sf_cache_key' => "mobileMenu" . $sf_user->isAuthenticated())); ?>
      <? if (!get_slot('is_main')) : ?>
        <div class="wrapper-page <?= get_slot('catalog-class') ?>">
          <? include_component('noncache', 'breadcrumbs', array('breadcrumbs' => get_slot('breadcrumbs'))); ?>
          <? $h1 = get_slot('h1'); ?>
          <? if ($h1) : ?>
            <div class="wrap-title-page">
              <div class="container">
                <!-- блок .tag-title-page используется только в файле article-sex-educ.php  -->
                <? if (get_slot('tagTitle')) : ?>
                  <div class="tag-title-page"><?= get_slot('tagTitle') ?></div>
                <? endif ?>
                <h1 class="title-page"><?= $h1; ?></h1>
              </div>
            </div>
          <? endif ?>
          <div class="page-content">
          <? endif ?>

          <?php echo $sf_content ?>

          <? if (!get_slot('is_main')) : ?>
          </div>
        </div>
      <? endif ?>

      <? include_component('page', 'firstvisit', array('path' => $_SERVER['REQUEST_URI'], 'sf_cache_key' => "footer" . $_SERVER['REQUEST_URI'])); ?>

    </div>
  </div>
  <footer class="footer">
    <? include_component('page', 'footer', array('path' => $_SERVER['REQUEST_URI'], 'sf_cache_key' => "footer" . $_SERVER['REQUEST_URI'] . ($sf_user->isAuthenticated() ? ILTools::getFavCount(sfContext::getInstance()->getUser()->getGuardUser()) : ''))); ?>
    <? //if(!get_slot('not_show_timer')) include_component('noncache', 'timeAction', array('sf_cache_key' => "timeAction")); 
    ?>
    <? //if(!get_slot('not_show_timer')) include_component('noncache', 'timeBanner', array('sf_cache_key' => "timeBanner")); 
    ?>
  </footer>
  <div id="blackout" class="blackout"></div>
  <?php $hideMess = get_slot('hide_mess'); //скрыть живосайт, вацап, колесо фортуны 
  ?>

  <?php if (true/* !$sf_user->isAuthenticated()*/) : ?>
    <? include_component('page', 'getFortune', array('sf_cache_key' => $sf_user->isAuthenticated())); ?>
  <? endif ?>
  <? include_component('page', 'svg', array('sf_cache_key' => "svg")); ?>

  <? if (get_slot('is_dadata_need')) : //подключение файлов для адресных подсказок
  ?>
    <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>
    <style>
      .suggestions-promo {
        display: none !important;
      }
    </style>
  <? endif ?>
  <? if (get_slot('js-euroset')) : //подключение файлов для показа виджета расчета стоимости евросети
  ?>
    <script defer src="https://api.drhl.ru/api/deliverypoints" type="text/javascript"></script>
  <? endif ?>
  <? if (get_slot('tinkoff')) : //подключение файлов для работы тинькофф банка
  ?>
    <script async src="https://forma.tinkoff.ru/static/onlineScript.js"></script>
    <script>
      var tinkoffSettings = {
        shopId: '<?= csSettings::get('TINKOFF_SHOP_ID') ?>',
        showcaseId: '<?= csSettings::get('TINKOFF_SHOWCASE_ID') ?>',
        minimumPrice: '<?= csSettings::get('TINKOFF_MIN_SUM') ?>'
      }
    </script>
  <? endif ?>
  <? if (get_slot('js-sdek')) : //полключение файлов для показа виджета СДЭК
  ?>
    <script id="ISDEKscript" type="text/javascript" src="/frontend/js/plugins/sdek/widjet.js" charset="utf-8"></script>
  <? endif ?>
  <? if (get_slot('js-pickpoint')) : //полключение файлов для показа виджета расчета стоимости Pickpoint
  ?>
    <script defer type="text/javascript" src="https://pickpoint.ru/select/postamat.js" charset="utf-8"></script>
  <? endif ?>
  <? if (get_slot('js-russian-post')) : //полключение файлов для показа виджета Почты России 
  ?>
    <script src="https://widget.pochta.ru/map/widget/widget.js"></script>
  <? endif ?>
  <? if (get_slot('js-yandex')) : //полключение файлов для показа виджета расчета стоимости Pickpoint
  ?>
    <script defer src="https://api-maps.yandex.ru/2.1.75/?lang=ru-RU&amp;load=package.full" type="text/javascript"></script>
  <? endif ?>

  <div data-retailrocket-markup-block="5ba3a68c97a5283ed03172e9"></div>
  <? if (!$isTest) include_component('page', 'footerscripts', array('hideMess' => $hideMess, 'sf_cache_key' => "footerscripts" . $hideMess)); ?>

  <? if (!$isTest) include_component('noncache', 'advcake'); ?>
  <? if (!$isTest) include_component('noncache', 'caltat'); ?>
  <div class="after caltat"></div>
  <?php if (!$isTest && $sf_user->isAuthenticated()) : ?>
    <?php
    preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $sf_user->getGuardUser()->getBirthday(), $bArr);
    ?>
    <script>
      rrApiOnReady.push(function() {
        rrApi.setEmail("<?= $sf_user->getGuardUser()->getEmailAddress() ?>", {
          BirthDate: "<?php echo $bArr[3] . '.' . $bArr[2] . '.' . $bArr[1]; ?>"
        });
      });
    </script>
    <div id="gtm-user-id" data-id="<?= $sf_user->getGuardUser()->getId() ?>"></div>
  <?php endif; ?>
  <div data-retailrocket-markup-block="5f61bdb597a528169cf2361f"></div>
  <? if (!$sf_user->isAuthenticated()) : ?>
    <? include_component('page', 'popup', array('sf_cache_key' => "popup|" . $sf_user->isAuthenticated())); ?>
  <? endif ?>
  <? if (get_slot('need_form_notify')) include_component('page', 'forms', array('type' => 'notify', 'sf_cache_key' => "form-notify")); ?>
</body>

</html>