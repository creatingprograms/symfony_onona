<!DOCTYPE html>
<html lang="ru">
  <head>
    <?
      global $isTest;
      if (
        sfContext::getInstance()->getRouting()->getCurrentRouteName() != "support" and
        sfContext::getInstance()->getRouting()->getCurrentRouteName() != "no18" and
        sfContext::getInstance()->getRequest()->getCookie('age18') != true and
        ! get_slot('18ageNo')
        and !$isYaMarket
      ) $isTest = true;
    ?>
    <?php// include_http_metas() ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php include_metas() ?>
    <?php //include_title() ?>
    <title><?= str_replace(array("'", '"'), "", get_slot('metaTitle') == '' ? csSettings::get('defaultTitle') : get_slot('metaTitle')) ?></title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="preload" as="font" href="/frontend/fonts/MyriadPro-SemiExt.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/HelveticaNeueCyr-Roman.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/HelveticaNeueCyr-Bold.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/HelveticaNeueCyr-Light.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/HelveticaNeueCyr-Medium.woff" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/6859-webfont.woff2" crossorigin>
    <link rel="preload" as="font" href="/frontend/fonts/MyriadPro-BoldSemiExt.woff" crossorigin>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <?
      if((isset($_GET['utm_source']) && $_GET['utm_source']=='YandexMarket')){//Если пришли из яндексМаркета  - считаем что 18+
        sfContext::getInstance()->getResponse()->setCookie('age18', true, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
        $isYaMarket=true;
      }
      else $isYaMarket=false;
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?= str_replace(array("'", '"'), "", get_slot('metaKeywords') == '' ? csSettings::get('defaultKeywords') : get_slot('metaKeywords')) ?>" />
    <meta name="description" content="<?= str_replace(array("'", '"'), "", get_slot('metaDescription') == '' ? csSettings::get('defaultDescription') : get_slot('metaDescription')) ?>" />
    <meta name="format-detection" content="telephone=no">
    <?php if ($_SERVER['REQUEST_URI'] == '/') { ?>
      <meta name="robots" content="noyaca"/>
      <? /*if (!$isTest && !$isYaMarket):?>
        <script type="application/javascript" async src="https://embed.quizyworld.tech/on-ona/embed.js"></script>
      <? endif*/ ?>
    <?php } ?>
    <? if (get_slot('canonical')) :?>
      <link rel="canonical" href="https://onona.ru<? include_slot('canonical') ?>"/>
    <? endif ?>
    <? if($og=get_slot('OpenGraph')) foreach ($og as $key => $value) :?>
      <meta property="og:<?= $key ?>" content="<?= $value ?>"/>
    <? endforeach ?>
    <!--[if (lt IE 9) ]>
    <script src="/frontend/js/html5shiv-printshiv.min.js" type="text/javascript"></script>
    <![endif]-->
    <? if(!$isTest) include_component('page', 'headerscripts', array('sf_cache_key' => "headerscripts")); ?>
    <?/*<script data-ad-client="ca-pub-6132126415138605" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>*/?>
    <meta name="google-site-verification" content="DOkN9zIewbzwZe7odaV1DfG4Astq-LSqcymyIOMeCwA" />
  </head>

  <body class="<?=get_slot('catalog-class') ?>">
    <noscript><div><img src="https://mc.yandex.ru/watch/144683" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <?/*
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M546PQV"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    */?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KBNZ6ZH" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?

      if (
        sfContext::getInstance()->getRouting()->getCurrentRouteName() != "support" and
        sfContext::getInstance()->getRouting()->getCurrentRouteName() != "no18" and
        sfContext::getInstance()->getRequest()->getCookie('age18') != true and
        ! get_slot('18ageNo')
        and !$isYaMarket
      )
        include_partial('noncache/18age', array());
      if (sfContext::getInstance()->getRouting()->getCurrentRouteName() == "no18")
        include_partial('noncache/no18', array());
    ?>
    <div id="main">
      <div id="wrapper">
        <div class="header-wrap">
          <header>
            <div class="wrapper">
              <div class="header-col-left">
                <? include_component('page', 'topMenu', array(
                  'path' => $_SERVER['REQUEST_URI'],
                  'sf_cache_key' => "TopMenu-new".$_SERVER['REQUEST_URI']
                )); ?>
                <div class="header-nav-but">&nbsp;</div>
                <a href="/" class="logo">
                  <img src="/frontend/images/logo-min.jpg" alt="" width="203" class="-desctop">
                  <img src="/frontend/images/logo_2x-min.png" alt="" width="63" class="-tablet">
                </a>
                <form action="/search" class="header-form">
                  <input type="text" name="searchString" class="js-search search-string" value="">
                  <input type="submit" value="">
                </form>
              </div>
              <div class="header-col-right">
                <div class="header-services-top -clf">
                    <div class="header-services -largeTabletHide">
                      <?php if (!$sf_user->isAuthenticated()): ?>
                        <a href="/guard/login">Войдите</a> или
                        <a href="/register">Зарегистрируйтесь</a>
                      <?php else: ?>
                        <a href="/lk" class="gtm-user-id" data-id="<?= sfContext::getInstance()->getUser()->getGuardUser()->getId() ?>">Личный кабинет</a>
                        <? include_component('noncache', 'bonuscount', array()); ?>
                      <?php endif; ?>
                    </div>
                  <div class="header-basket" id="header-basket">
                    <? include_component('cart_new', 'topCart', array()); ?>
                  </div>
                </div>
                <div class="header-inf">
                  <p class="-largeTabletTel -mobileHide">
                    <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone2')) ?>"><?= csSettings::get('phone1') ?></a> по России (бесплатно)
                  </p>
                  <p class="-largeTabletHide">
                    <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone2')) ?>"><?= csSettings::get('phone2') ?></a> по Москве (круглосуточно)
                  </p>
                </div>
              </div>
            </div>
          </header>
            <? if(!true) :?>
              <nav class="main-nav">
                <div class="wrapper">
                  <?  include_component('page', 'topMenuMain', array('path' => $_SERVER['REQUEST_URI'], 'sf_cache_key' => "TopMenu-new-main".$_SERVER['REQUEST_URI'])); ?>
                </div>
              </nav>
              <? else :?>
              <nav class="main-nav-new">
                <div class="wrapper">
                  <? include_component('page', 'topMenuMainNew', array('path' => $_SERVER['REQUEST_URI'],'sf_cache_key' => "TopMenu-new-main-2".$_SERVER['REQUEST_URI'])); ?>
                </div>
              </nav>
            <? endif ?>
        </div>
        <? include_component('page', 'mobileMenu', array('sf_cache_key' => "mobileMenu".$sf_user->isAuthenticated())); ?>
        <? include_component('noncache', 'breadcrumbs', array('breadcrumbs' => get_slot('breadcrumbs'))); ?>
        <? $h1=get_slot('h1'); ?>
        <?= $h1 ? '<div class="wrapper topHead"><h1>'.$h1.'</h1></div>' : '' ?>
        <?php echo $sf_content ?>

      </div>
    </div>
    <footer>
      <? include_component('page', 'footer', array('path' => $_SERVER['REQUEST_URI'], 'sf_cache_key' => "footer".$_SERVER['REQUEST_URI'])); ?>
      <? if(!get_slot('not_show_timer')) include_component('noncache', 'timeAction', array('sf_cache_key' => "timeAction")); ?>
      <? if(!get_slot('not_show_timer')) include_component('noncache', 'timeBanner', array('sf_cache_key' => "timeBanner")); ?>
    </footer>
    <div id="blackout" class="blackout"></div>
    <?php $hideMess=get_slot('hide_mess');//скрыть живосайт, вацап, колесо фортуны ?>
    <?php/* if (!$isTest && !$isYaMarket && !$sf_user->isAuthenticated()): ?>
      <? include_component('page', 'get300', array('sf_cache_key' => $sf_user->isAuthenticated()));?>
    <? endif */?>
    <?php if ( !$sf_user->isAuthenticated() && !$hideMess && get_slot('is_main')): ?>
      <? include_component('page', 'getFortune', array('sf_cache_key' => $sf_user->isAuthenticated()));?>
    <? endif ?>
    <? include_component('page', 'svg', array('sf_cache_key' => "svg")); ?>
    <script>
      var require = {
        baseUrl: '/frontend/js',
        urlArgs: "bust=v<?=filectime($_SERVER['DOCUMENT_ROOT'].'/frontend/js/app/site.js')?>"
      };
    </script>
    <script src="/frontend/js/require-jquery.js"></script>
    <script>

      require(['app/site'], function() {
        require(['site/common'], function() {});
        require(['site/map'], function() {});
        require(['site/gtm'], function() {});
      });
    </script>
    <? if(get_slot('is_dadata_need')) ://полключение файлов для адресных подсказок?>
      <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/css/suggestions.min.css" rel="stylesheet" />
      <?/*<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.2.2/dist/js/jquery.suggestions.min.js"></script>*/?>
    <? endif ?>
    <? if(get_slot('js-euroset')) ://полключение файлов для показа виджета расчета стоимости евросети?>
      <script defer src="https://api.drhl.ru/api/deliverypoints" type="text/javascript"></script>
    <? endif ?>
    <? if(get_slot('js-pickpoint')) ://полключение файлов для показа виджета расчета стоимости Pickpoint?>
      <script defer type="text/javascript" src="https://pickpoint.ru/select/postamat.js" charset="utf-8"></script>
    <? endif ?>
    <? if(get_slot('js-yandex')) ://полключение файлов для показа виджета расчета стоимости Pickpoint?>
      <script defer src="https://api-maps.yandex.ru/2.1.75/?lang=ru-RU&amp;load=package.full" type="text/javascript"></script>
    <? endif ?>
    <? if(get_slot('is-blur')) ://полключение файлов для показа виджета расчета стоимости Pickpoint?>
      <div class="js-need-to-blur"></div>
    <? endif ?>

    <?/*<div data-retailrocket-markup-block="5ba3a68c97a5283ed03172e9"></div>*/?>
    <? if(!$isTest) include_component('page', 'footerscripts', array('hideMess'=>$hideMess, 'sf_cache_key' => "footerscripts".$hideMess)); ?>
    <?php/* if (!$isTest && !$sf_user->isAuthenticated()): ?>
      <? include_component('noncache', 'hoversignal'); ?>
    <? endif */?>
    <? include_component('noncache', 'advcake'); ?>
    <? /*if(!$isTest)*/ include_component('noncache', 'caltat'); ?>
    <div class="after caltat"></div>
    <?php if (!$isTest && $sf_user->isAuthenticated()): ?>
        <?php
          preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $sf_user->getGuardUser()->getBirthday(), $bArr);
        ?>
        <script>
            rrApiOnReady.push(function () {
                rrApi.setEmail("<?= $sf_user->getGuardUser()->getEmailAddress() ?>", { BirthDate: "<?php echo $bArr[3].'.'.$bArr[2].'.'.$bArr[1]; ?>" });
            });
        </script>
        <div id="gtm-user-id" data-id="<?=$sf_user->getGuardUser()->getId()?>"></div>
    <?php endif; ?>
    <div data-retailrocket-markup-block="5f61bdb597a528169cf2361f"></div>
  </body>

</html>
