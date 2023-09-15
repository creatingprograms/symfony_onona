<?php
define('DEV_USER', 159759);
if(isset($_GET['debug']))
  define('DEBUG', true);
else
  define('DEBUG', false);
// die(print_r($_GET, true));
//Проверяем наличие ? в урле и если нет переданных параметров падаем в 404
//Если передан параметр page но у него нет значения - падаем в 404
//задача 104229 - onona.ru - Доработки по сайту -- SEO
if( !get_slot('already404')=='already404' && ((strpos($_SERVER['REQUEST_URI'],'?')!==false && !sizeof($_GET)) || (isset($_GET['page']) && $_GET['page']==''))){
  slot('already404', 'already404'); //Отмечаем что мы в процессе показа 404
  sfContext::getInstance()->getController()->forward('page', 'error404');
}

$q = Doctrine_Manager::getInstance()->getCurrentConnection();
if ($sf_user->isAuthenticated()):
    if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == DEV_USER && DEBUG) {
        // sfContext::getInstance()->getUser()->setAttribute('regMO', true);
        $ip = $_SERVER['REMOTE_ADDR'];
        $SxGeo = new SxGeo('../apps/newcat/lib/SxGeoCity.dat');
        $geoData = $SxGeo->getCityFull($ip); //Москва Московская область
        echo '<div style="position: fixed; background:white; top: 0; left: 0; width: 100%; z-index: 4000;"><pre>!';
        echo(print_r($geoData, true));
        echo "!</pre></div>";
    }
endif;

$timerPersonalRecomendation = sfTimerManager::getTimer('Layout: Персональные рекомендации');
$personalRecomendation = unserialize(base64_decode(sfContext::getInstance()->getRequest()->getCookie('personalRecomendationCategory')));
if (count(get_slot('personalRecomendationCategoryId')) > 1) {
    $personalRecomendationCategoryId = get_slot('personalRecomendationCategoryId');
    $personalRecomendationProductId = get_slot('personalRecomendationProductId');

    if (@!isset($personalRecomendation['category'])) {
        $tempPersonalRecomendation = $personalRecomendation;
        unset($personalRecomendation);
        $personalRecomendation['category'] = $tempPersonalRecomendation;
    }
    $personalRecomendation['category'][$personalRecomendationCategoryId[0]] = $personalRecomendation['category'][$personalRecomendationCategoryId[0]] + $personalRecomendationCategoryId[1];
    if (is_array($personalRecomendationProductId)) {
        $personalRecomendation['products'][$personalRecomendationProductId[0]] = $personalRecomendation['products'][$personalRecomendationProductId[0]] + $personalRecomendationProductId[1];
    }
    sfContext::getInstance()->getResponse()->setCookie('personalRecomendationCategory', base64_encode(serialize($personalRecomendation)), time() + 60 * 60 * 24 * 30, '/', ".onona.ru");

    if ($sf_user->isAuthenticated()) {
        $GuardUser = $sf_user->getGuardUser();
        $GuardUser->set("personal_recomendation", serialize($personalRecomendation));
        $GuardUser->save();
    }
}
$timerPersonalRecomendation->addTime();

$timerIP = sfTimerManager::getTimer('Layout: Определение региона');
$ip = $_SERVER['REMOTE_ADDR'];
if (isset($_GET['ip']) && DEBUG) {$ip=$_GET['ip'];}
$SxGeo = new SxGeo('../apps/newcat/lib/SxGeoCity.dat');
$geoData = $SxGeo->getCityFull($ip); //Москва Московская область

if (@sfContext::getInstance()->getUser()->getAttribute('regMO') !== true and @ sfContext::getInstance()->getUser()->getAttribute('regMO') !== false) {
    if ($geoData['region']['name_ru'] == "Москва" or $geoData['region']['name_ru'] == "Московская область") {
        sfContext::getInstance()->getUser()->setAttribute('regMO', true);
    } else {
        sfContext::getInstance()->getUser()->setAttribute('regMO', false);
    }

}

if ($sf_user->isAuthenticated()):

    if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == 159759) {
        sfContext::getInstance()->getUser()->setAttribute('regMO', true);
    }
endif;
$regionMoscow=@sfContext::getInstance()->getUser()->getAttribute('regMO') ? 'Moscow' : 'Other region';
$timerIP->addTime();

$timerAll = sfTimerManager::getTimer('Layout: Весь шаблон');
$timer = sfTimerManager::getTimer('Layout: Передача времени жизни страницы');
if (get_slot('mainPage')) {
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 432000));
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', time() - 432000));
} elseif (get_slot('canonicalSlugCategory') or get_slot('canonicalSlugArticlecategory') or get_slot('canonicalSlugCollection') or get_slot('canonicalSlugManufacturer') or get_slot('canonicalSlugCatalog') or get_slot('canonicalSlugNewprod') or get_slot('canonicalSlugRelCat')) {
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 1209600));
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', time() - 1209600));
} elseif (get_slot('canonicalSlugProduct') or get_slot('canonicalSlugArticle')) {
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', time() - 2592000));
} else {
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', time() - 2592000));
}

$timer->addTime();
$timer = sfTimerManager::getTimer('Layout: Запись реф мастеров');
if (@isset($_GET['r']) and isset($_GET['wmaster']) && @(integer) $_GET['r'] !== 0) {
    $partnerRef = PartnerRefidTable::getInstance()->createQuery()->where("refid=?", $_GET['r'])->fetchOne();
    if (!$partnerRef) {
        $partnerRef = new PartnerRefid();
        $partnerRef->setRefid($_GET['r']);
        $partnerRef->save();
    }
    $partner = PartnerIdTable::getInstance()->createQuery()->where("refid=?", $_GET['r'])->addWhere('webmaster=?', $_GET['wmaster'])->fetchOne();
    if (!$partner) {
        $partner = new PartnerId();
        $partner->setRefid($_GET['r']);
        $partner->setWebmaster($_GET['wmaster']);
        $partner->save();
    }
}
else if (@isset($_GET['r']) && @(integer) $_GET['r'] !== 0) {
    $partnerRef = PartnerRefidTable::getInstance()->createQuery()->where("refid=?", $_GET['r'])->fetchOne();
    if (!$partnerRef) {
        $partnerRef = new PartnerRefid();
        $partnerRef->setRefid($_GET['r']);
        $partnerRef->save();
    }
}
else if (@isset($_COOKIE['referal']) and isset($_COOKIE['wmaster']) && @(integer) $_GET['r'] !== 0) {
    $partnerRef = PartnerRefidTable::getInstance()->createQuery()->where("refid=?", $_COOKIE['referal'])->fetchOne();
    if (!$partnerRef) {
        $partnerRef = new PartnerRefid();
        $partnerRef->setRefid($_GET['r']);
        $partnerRef->save();
    }
    $partner = PartnerIdTable::getInstance()->createQuery()->where("refid=?", $_COOKIE['referal'])->addWhere('webmaster=?', $_COOKIE['wmaster'])->fetchOne();
    if (!$partner) {
        $partner = new PartnerId();
        $partner->setRefid($_GET['r']);
        $partner->save();
    }
}
else if (@isset($_COOKIE['referal']) && (integer) @$_GET['r'] !== 0) {
    $partnerRef = PartnerRefidTable::getInstance()->createQuery()->where("refid=?", $_COOKIE['referal'])->fetchOne();
    if (!$partnerRef) {
        $partnerRef = new PartnerRefid();
        $partnerRef->setRefid($_GET['r']);
        $partnerRef->save();
    }
}
else if (@isset($_COOKIE['referal']) and isset($_COOKIE['wmaster'])) {
    $partnerRef = PartnerRefidTable::getInstance()->createQuery()->where("refid=?", $_COOKIE['referal'])->fetchOne();
    if (!$partnerRef) {
        $partnerRef = new PartnerRefid();
        $partnerRef->setRefid($_COOKIE['referal']);
        $partnerRef->save();
    }
    $partner = PartnerIdTable::getInstance()->createQuery()->where("refid=?", $_COOKIE['referal'])->addWhere('webmaster=?', $_COOKIE['wmaster'])->fetchOne();
    if (!$partner) {
        $partner = new PartnerId();
        $partner->setRefid($_COOKIE['referal']);
        $partner->setWebmaster($_COOKIE['wmaster']);
        $partner->save();
    }
}
else if (@isset($_COOKIE['referal'])) {
    $partnerRef = PartnerRefidTable::getInstance()->createQuery()->where("refid=?", $_COOKIE['referal'])->fetchOne();
    if (!$partnerRef) {
        $partnerRef = new PartnerRefid();
        $partnerRef->setRefid($_COOKIE['referal']);
        $partnerRef->save();
    }
}

$timer->addTime();
$timer = sfTimerManager::getTimer('Layout: Если на товар не закончилась акция, сбрасываем его кэш');
$pre = "";
$timer->addTime();

$timer = sfTimerManager::getTimer('Layout: Header страницы до CSS');
?>
<!DOCTYPE HTML>
<html>
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <title><?= str_replace(array("'", '"'), "", get_slot('metaTitle') == '' ? csSettings::get('defaultTitle') : get_slot('metaTitle')) ?></title>
        <meta name="keywords" content="<?= str_replace(array("'", '"'), "", get_slot('metaKeywords') == '' ? csSettings::get('defaultKeywords') : get_slot('metaKeywords')) ?>" />
        <meta name="description" content="<?= str_replace(array("'", '"'), "", get_slot('metaDescription') == '' ? csSettings::get('defaultDescription') : get_slot('metaDescription')) ?>" />
        <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="format-detection" content="telephone=no">

        <link rel="shortcut icon" href="/favicon.ico" />
        <!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="/newdis/css/ie.css" media="screen"/><![endif]-->
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KBNZ6ZH');</script>
        <!-- End Google Tag Manager -->
        <?
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Layout: Header генерация ссылок CSS');
        ?>
        <?php include_stylesheets() ?>
        <?
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Layout: Header генерация ссылок JavaScript');
        ?>
        <?php include_javascripts() ?>
        <?
        if (get_slot('cartFirstPage')) { ?>
            <script type="text/javascript" src="/newdis/js/jquery-ui-1.10.3.custom.js"></script>
        <? }
        else { ?>
            <script type="text/javascript" src="/newdis/js/jquery-ui.js"></script>
            <script type="text/javascript" src="/newdis/js/ui.js"></script><?
        } ?>
        <?php if ($_SERVER['HTTP_HOST']!='dev.onona.ru') :?>
          <script type="text/javascript" src="/newdis/js/metrika.js"></script>
        <?php endif  ?>
        <?
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Layout: Header канонические ссылки');
        ?>
        <?php if (get_slot('canonicalSlugManufacturer')): ?>
            <link rel="canonical" href="https://onona.ru/manufacturer/<? include_slot('canonicalSlugManufacturer') ?>"/>
        <?php endif; ?>
        <?php if (get_slot('canonicalSlugCollection')): ?>
            <link rel="canonical" href="https://onona.ru/collection/<? include_slot('canonicalSlugCollection') ?>"/>
        <?php endif; ?>

        <?php if (get_slot('canonicalSlugProduct')): ?>
            <link rel="canonical" href="https://onona.ru/product/<? include_slot('canonicalSlugProduct') ?>"/>
        <?php endif; ?>

        <?php if (get_slot('canonicalSlugCategory')): ?>
            <link rel="canonical" href="https://onona.ru/category/<? include_slot('canonicalSlugCategory') ?>"/>
        <?php endif; ?>

        <?php if (get_slot('canonicalSlugCatalog')): ?>
            <link rel="canonical" href="https://onona.ru/catalog/<? include_slot('canonicalSlugCatalog') ?>"/>
        <?php endif; ?>

        <?php if (get_slot('canonicalSlugNewprod')): ?>
            <link rel="canonical" href="https://onona.ru/catalog/<? include_slot('canonicalSlugNewprod') ?>/newprod"/>
        <?php endif; ?>

        <?php if (get_slot('canonicalSlugRelCat')): ?>
            <link rel="canonical" href="https://onona.ru/catalog/<? include_slot('canonicalSlugRelCat') ?>/relatecategory"/>
        <?php endif; ?>

        <?php if (get_slot('canonicalSlugArticle')): ?>
            <link rel="canonical" href="https://onona.ru/sexopedia/<? include_slot('canonicalSlugArticle') ?>"/>
        <?php endif; ?>

        <?php if (get_slot('canonicalSlugArticlecategory')): ?>
            <link rel="canonical" href="https://onona.ru/sexopedia/category/<? include_slot('canonicalSlugArticlecategory') ?>"/>
        <?php endif; ?>

        <?php if (get_slot('canonicalSlugArticlecatalog')): ?>
            <link rel="canonical" href="https://onona.ru/sexopedia/catalog/<? include_slot('canonicalSlugArticlecatalog') ?>"/>
        <?php endif; ?>

        <?php if (get_slot('canonicalSlugLovetest')): ?>
            <link rel="canonical" href="https://onona.ru/<? include_slot('canonicalSlugLovetest') ?>"/>
        <?php endif; ?>
        <?php
        if (sfContext::getInstance()->getRouting()->getCurrentRouteName() == "new_prod"):

            $canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
            ?>
            <link rel="canonical" href="https://onona.ru/newprod<?= $canonDop ?>"/>
        <?php endif; ?>

        <?
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Layout: Оставшаяся часть head');
        ?>
        <? if (get_slot('mainPage')) { ?>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/css/swiper.min.css">

            <style type="text/css">
                .promo-gallery ul li {
                    padding: 0;
                }
                .coin-slider .cs-buttons a {
                    display: inline-block;
                    height: 13px;
                    letter-spacing: normal;
                    margin: 0 8px;
                    width: 180px;
                    text-decoration: none;
                    vertical-align: top;
                    overflow: visible;
                    text-indent: 0;
                    color: #969696;

                }
                .coin-slider .cs-buttons {
                    height: 30px;
                }
            </style>

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

        <? } ?>

        <script src="/js/rrPartner.js" type="text/javascript"></script>
        <script src="/js/gs.js?v=3" type="text/javascript" async></script><? // где слон ?>
        <?php /*if ($_SERVER['HTTP_HOST']!='dev.onona.ru') : ?> 135322 - onona.ru - Список внешних сервисов
          <link rel="manifest" href="/manifest_pushworld.json">
        <?php endif */?>
        <?/* 114338 - onona.ru - Обновить трекинг-код  admitad*/?>
        <script src="https://www.artfut.com/static/tagtag.min.js?campaign_code=f0be12d9c8" async onerror='var self = this;window.ADMITAD=window.ADMITAD||{},ADMITAD.Helpers=ADMITAD.Helpers||{},ADMITAD.Helpers.generateDomains=function(){for(var e=new Date,n=Math.floor(new Date(2020,e.getMonth(),e.getDate()).setUTCHours(0,0,0,0)/1e3),t=parseInt(1e12*(Math.sin(n)+1)).toString(30),i=["de"],o=[],a=0;a<i.length;++a)o.push({domain:t+"."+i[a],name:t});return o},ADMITAD.Helpers.findTodaysDomain=function(e){function n(){var o=new XMLHttpRequest,a=i[t].domain,D="https://"+a+"/";o.open("HEAD",D,!0),o.onload=function(){setTimeout(e,0,i[t])},o.onerror=function(){++t<i.length?setTimeout(n,0):setTimeout(e,0,void 0)},o.send()}var t=0,i=ADMITAD.Helpers.generateDomains();n()},window.ADMITAD=window.ADMITAD||{},ADMITAD.Helpers.findTodaysDomain(function(e){if(window.ADMITAD.dynamic=e,window.ADMITAD.dynamic){var n=function(){return function(){return self.src?self:""}}(),t=n(),i=(/campaign_code=([^&]+)/.exec(t.src)||[])[1]||"";t.parentNode.removeChild(t);var o=document.getElementsByTagName("head")[0],a=document.createElement("script");a.src="https://www."+window.ADMITAD.dynamic.domain+"/static/"+window.ADMITAD.dynamic.name.slice(1)+window.ADMITAD.dynamic.name.slice(0,1)+".min.js?campaign_code="+i,o.appendChild(a)}});'>
        </script>
        <meta name="yandex-verification" content="5486b08711caa9ab" />
        <meta name="yandex-verification" content="1b488de790d13fa1" />
        <meta name="yandex-verification" content="c7b2835e0967fbb7" />
        <meta name="yandex-verification" content="e342656c9b01c07c" />
        <meta name="google-site-verification" content="8I6-KWXS9MVbfe3DT0ZOX5HJbY193_ujFTvv0ZvyEl0" />
        <?php if ($_SERVER['REQUEST_URI'] == '/') { ?>
        <meta name="robots" content="noyaca"/>
        <?php } ?>
        <script type="text/javascript" src="/ds-comf/ds-form/js/dsforms.js"></script>
        <script src="/jqzoom/js/jquery.jqzoom-core.js" type="text/javascript"></script>
        <link rel="stylesheet" href="/jqzoom/css/jquery.jqzoom.css" type="text/css" />
        <script type="text/javascript" id="advcakeAsync">
          (function ( a ) {
          var b = a.createElement("script");
          b.async = 1;
          b.src = "//code.acstat.com/";
          a=a.getElementsByTagName("script")[0];
          a.parentNode.insertBefore(b,a)
          })(document);
        </script>
        <?
          /* 135322 - onona.ru - Список внешних сервисов
          // $code=get_slot('gdeSlonCodes');//'&codes={ID1}:{PRICE1},{ID2}:{PRICE2}';
          $catId=get_slot('gdeSlonCatId');//'&cat_id={CATEGORY_ID}';
          $orderId=get_slot('gdeSlonOrderId');//'&order_id={ORDER_ID}';
          $mode=get_slot('gdeSlonMode');
          $products = get_slot('gdeSlonCodes');
          if(sfContext::getInstance()->getRouting()->getCurrentRouteName() == 'homepage') $mode='main';
          if(!$mode) $mode='other';


          // $gdeSlonDev='~~~mid=92355&mode='.$mode.$code.$catId.$orderId.'~~~';
          //echo $gdeSlonDev;
          $gdeSlon=
          'window.gdeslon_q = window.gdeslon_q || [];'.//"\n".
          'window.gdeslon_q.push({'.//"\n".
            'page_type:  "'.$mode.'",'.//"\n".
            'merchant_id: "92355", './/"\n".
            ($orderId ? $orderId : ''). //"\n".//'order_id:    "1",'
            ($catId ? $catId : ''). //"\n". //category_id: "1",
            ($products ? $products : '') .//Выводятся в корзине
            ' deduplication: "'.$_COOKIE['utm_source'].'"'.//"\n".
          '});';
          echo '<script type="text/javascript">'.$gdeSlon.'</script>';

          */
          // $gdeSlon='<script async="true" type="text/javascript" src="https://www.gdeslon.ru/landing.js?mid=92355&mode='.$mode.$code.$catId.$orderId.'"></script>';

        ?>
    </head>
    <body class="birthday-2">
      <?/*<div style="z-index: 10; position: fixed; top: 0; left: 0; background: #DED; width: 452px; min-height: 250px;"><pre><?= $gdeSlon ?></pre></div>*/?>
      <!-- Yandex.Metrika counter -->
        <script type="text/javascript" >
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter144683 = new Ya.Metrika({
                            id:144683,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true,
                            webvisor:true,
                            triggerEvent:true,
                            ecommerce:"dataLayer"
                        });
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/144683" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
      <!-- /Yandex.Metrika counter -->
      <!-- Google Tag Manager (noscript) -->
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KBNZ6ZH" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
      <!-- End Google Tag Manager (noscript) -->
      <script>
        function sendRRWelcome(email){
          $.ajax({
            data: 'email='+email,
            dataType: 'json',
            type: 'get',
            url: '/checkemail',
            success: function (response){
              if(response.result=='true'){
                // console.log('sendRR  '+email);
                rrApi.welcomeSequence(email)
              }
              else{
                // console.log('not sendRR '+email);
              }
            }
          });
        }
      </script>
        <?
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Layout: Определение клиента(мобильный или десктоп)');

        // $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
        // $accept = strtolower(getenv('HTTP_ACCEPT'));

        $agentIsMobile = false;


        $timer->addTime();

        $timer = sfTimerManager::getTimer('Layout: Блок подписки на рассылку');

        $timer->addTime();
        $timer = sfTimerManager::getTimer('Layout: Шаблоны добавления товара в корзину и видеоплеера');
        ?>
        <div id="TextAddProductToCard">
            <div class="titleAddToCart">Товар добавлен в корзину</div><a class="close" href="#" onClick="$('#TextAddProductToCard').fadeOut();
                $('#BackgrounAddProductToCard').fadeOut();
                return false;"></a>
            <div style="clear:both; "></div>
            <div style="float: left;margin-right: 10px;">
              <img style="height:100px;" src="/images/pixel.gif" class="ProdImg" alt=" Image Product Add To Cart" />
            </div>
            <div style="float: left;">Наименование: <span class="ProdName"></span><br /> Стоимость: <span class="ProdPrice"></span> р.<br /> За этот товар вы получите: <span class="ProdBonus"></span> бонусных рублей.</div>
            <div style="clear:both; "></div>
            <div class="notification-buttons">
              <a class="red-btn colorWhite" href="#" onClick="$('#TextAddProductToCard').fadeOut();
                    $('#BackgrounAddProductToCard').fadeOut();
                    return false;">
                <span style="width: 250px;">Продолжить покупки</span>
              </a>
              <a class="green-btn colorWhite" href="<?= url_for('cart/') ?>">
                <span style="width: 250px;">Оформить заказ</span>
              </a>
            </div>
            <div id="rr-basket" data-retailrocket-markup-block="5ba3a62997a52530d41bb247" data-products="<? //include_partial("cart_new/cartrrlist"); ?>"></div>
        </div>

        <div id="BackgrounAddProductToCard"></div>

        <div id="playerdiv" class="simple_overlay">
            <div id="playerVideoDiv"></div>
            <a class="close"></a>

        </div>
        <?php
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Layout: Всплывайка в томарах с бонусами');
        if (false) {//116514 - onona.ru - Убрать всплывающее окно
          // if (!sfContext::getInstance()->getRequest()->getCookie('nonMessageBonusProd') and get_slot('bonusPayPercent') != "" and ! sfContext::getInstance()->getRequest()->getCookie('oneMessageBonusProd')) {
            ?>
            <div id="TextBonusProduct">
                <div class="titleAddToCart">Данный товар участвует в Акции УПРАВЛЯЙ ЦЕНОЙ</div>
                <a class="close" href="#" onClick="$('#TextBonusProduct').fadeOut();
                        $('.BackgroundBlack:visible').fadeOut();
                        return false;"></a>
                <div style="color: rgb(195, 6, 14);">
                    <ul style="padding-left: 22px;">
                        <li>
                            <span style="color: #414141">Товары-участники акции УПРАВЛЯЙ ЦЕНОЙ могут быть оплачены Бонусами от 50 до 100% от их стоимости.</span></li>
                        <li>
                            <span style="color: #414141">Для использования Бонусов необходимо быть авторизованным под своим логином.</span></li>
                        <li>
                            <span style="color: #414141">Узнать количество своих Бонусов вы можете в Личном кабинете в разделе Мои Бонусы.</span></li>
                        <li style="margin-top: 20px;">Максимальная Бонусная скидка этого товара <?= get_slot('bonusPayPercent') ?>% от стоимости. </li>
                        <li>
                            <span style="color: #414141">Управление ценой происходит в вашей корзине во время оформления заказа, т.е. изменять Бонусную скидку можно в корзине в ячейке «Бонусы, %».</span></li>
                        <li>
                            <span style="color: #414141">Если на вашем Бонусном счете недостаточно Бонусов, то вы оплачиваете: <br />
                                - частично своими Бонусами (сколько есть на счете) + оставшуюся часть деньгами,<br />
                                - полную стоимость товара деньгами.</span></li>
                    </ul></div>
                <form id="Bonuspayprod" action="<?= url_for('bonuspayprod/') ?>">
                    <input type="checkbox" name="nonMessageBonusProd"> Больше не показывать это сообщение.</form>
                <div style="clear: both; height: 20px;"></div>
                <a onclick="$('#Bonuspayprod').submit()" class="green-btn colorWhite">
                  <span style="width: 180px;">Продолжить покупки</span>
                </a>
                <a href="<?= url_for('programma-on-i-ona-bonus/') ?>" class="red-btn colorWhite">
                  <span>Все условия акции УПРАВЛЯЙ ЦЕНОЙ</span>
                </a>
            </div>
            <div class="BackgroundBlack" style="display: block;"></div><?
        }
        if (get_slot('bonusPayPercent') != "") {

            sfContext::getInstance()->getResponse()->setCookie('oneMessageBonusProd', false, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
        }
        $timer->addTime();
        $timer = sfTimerManager::getTimer('Layout: Плашка 18+');
        if (
          sfContext::getInstance()->getRouting()->getCurrentRouteName() != "support" and
          sfContext::getInstance()->getRouting()->getCurrentRouteName() != "no18" and
          sfContext::getInstance()->getRequest()->getCookie('age18') != true and
          ! get_slot('18ageNo')
        )
            include_partial('noncache/18age', array("agentIsMobile"=>@$agentIsMobile));

        if (sfContext::getInstance()->getRouting()->getCurrentRouteName() == "no18")
          include_partial('noncache/no18', array("agentIsMobile"=>@$agentIsMobile));

        $timer->addTime();
        ?>

        <div class="wrapper-holder">
            <?php
            $bannerTop = PageTable::getInstance()->createQuery()->where("id=460")->fetchOne();
            if ($bannerTop->getIsPublic()) {
                ?>
                <div id="banner-top"><?php
                    echo $bannerTop->getContent();
                    ?></div>
                <?php
            }
            ?>
            <?php
              ob_start();

              include_partial("cart_new/cartBlockHeader", array("q" => $q));

              $basketContent = ob_get_clean();
            ?>
            <div id="wrapper">
              <div class="sandwich"></div>
                <?
                $timer = sfTimerManager::getTimer('Layout: Шапка');
                ?>
                <div id="header">
                    <div class="head-row">
                        <div class="right">
                            <div class="age">
                                <div class="tips">Сайт предназначен только для лиц, достигших 18-ти летнего возраста.</div>
                            </div>
                            <ul class="join-menu">
                                <?php if (!$sf_user->isAuthenticated()): ?>
                                    <li><a class="login" href="#">Вход</a></li>
                                    <li><a href="/guard/login">Регистрация</a></li>
                                <?php else: ?>
                                    <li><a class="showLK" href="#">Личный кабинет</a></li>
                                <?php endif; ?>
                            </ul>
                            <span class="search-button js-search-trigger mobile-only"></span>
                            <a href="<?= url_for('desire/') ?>" class="desire"><div style="" id="JelHeader"><span>
                                        <?
                                        $products_desire = unserialize(sfContext::getInstance()->getUser()->getAttribute('products_to_desire'));
                                        if ($products_desire == "") {
                                            echo '0';
                                        } else {
                                            echo count($products_desire);
                                        }
                                        ?></span></div></a>
                            <a class="mobile-basket" href="<?= url_for('cart/')?>"><span><?= get_slot("productsCount") ? get_slot("productsCount") : 0 ?></span></a>
                            <div class="mobile-search">
                              <form action="/search" class="search">
                                <input class="js-search search-string" type="text" name="searchString" autocomplete="off" placeholder="Введите запрос">
                                <input class="srch-btn" type="submit" value="">
                              </form>
                            </div>
                        </div>

                        <?
                          $timerMenu = sfTimerManager::getTimer('Layout: Шапка: Меню над шапкой');
                        ?>
                        <?include_component('page', 'topMenuNew', array('sf_cache_key' => "TopMenu-new"));?>
                        <?/*
                        <ul class="top-menu">
                            <?php $menu = MenuTable::getInstance()->findByPositionmenu('Над шапкой(новый дизайн)');?>
                            <? foreach ($menu as $link):
                                ?>
                                <li><a href="<?= $link->getUrl() ?>"><?= $link->getText() ?></a></li>
                                <?php
                            endforeach;
                            ?>
                        </ul>
                        */?>
                        <div class="footer-menu">
                          <ul>
                              <?php $menu = MenuTable::getInstance()->findByPositionmenu('footer-fix');?>
                              <? foreach ($menu as $link): ?>
                                  <li><a <?=$link->getTargetBlank() ? 'target="_blank" ' : ''?>href="<?= mb_strtolower($link->getUrl(), 'utf-8') ?>"><?= $link->getText() ?></a></li>
                                  <li class="divider"></li>
                              <?php endforeach; ?>
                          </ul>
                          <div class="js-footer-menu footer-sandwich"></div>
                        </div>

                    </div>
                    <div class="header-frame">
                      <div class="left-col">
                        <div class="logo">
                            <a href="<?= url_for('@homepage') ?>">Сеть магазинов для взрослых Он и Она</a>
                            <div class="slogan">Работаем с <span>1992</span> года</div>
                        </div>
                        <div class="phone-box">
                            <ul>
                                <?php if (@$agentIsMobile) { ?>
                                    <li>
                                        <span class="phone"><a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone1')) ?>"><?= csSettings::get('phone1') ?></a></span>
                                        <span class="descr">по России (бесплатно)</span>
                                    </li>
                                    <li>
                                        <span class="phone"><a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone2')) ?>"><?= csSettings::get('phone2') ?></a></span>
                                        <span class="descr">по Москве</span>
                                    </li>
                                <?php } else { ?>
                                    <li>
                                        <span class="phone"><a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone1')) ?>"><?= csSettings::get('phone1') ?></a><span class="mobile-hide"><?= csSettings::get('phone1') ?></span></span>
                                        <span class="descr">по России (бесплатно)</span>
                                    </li>
                                    <li>
                                        <span class="phone"><a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone2')) ?>"><?= csSettings::get('phone2') ?></a><span class="mobile-hide"><?= csSettings::get('phone2') ?></span></span>
                                        <span class="descr">по Москве (круглосуточно)</span>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                      </div>
                      <div class="middle-col">
                        <? if (@sfContext::getInstance()->getUser()->getAttribute('regMO') !== true) :?>
                          <a href="/dostavka" class="delivery-banner <?=strtolower($geoData['region']['iso'])?>"></a>
                        <? else : ?>
                          <?include_component('page', 'topShops', array('sf_cache_key' => "TopMenu-topShops"));?>
                        <? endif ?>
                      </div>
                      <div class="right-col">
                        <?
                          $timerMenu->addTime();
                          $timerCart = sfTimerManager::getTimer('Layout: Шапка: Корзина');

                          echo $basketContent;

                        ?>
                      </div>
                    </div>
                    <?
                    $timerCart->addTime();
                    $timerMenu = sfTimerManager::getTimer('Layout: Шапка: Меню под шапкой');
                    // if (!get_slot('topMenu')) //показываем на всех страницах
                        include_component('noncache', 'topMenuNew', array('sf_cache_key' => "TopMenu-" . rand(1, 5)));
                    $timerMenu->addTime();
                    ?>
                </div>
                <? $timer->addTime();
                ?>
                <div id="main"<?php if (get_slot('articlerightpad')) echo " style=\"  padding: 21px 0 0 10px;\"" ?>>
                      <?php
                        $timer = sfTimerManager::getTimer('Layout: Левый блок: Тестовый');
                        if (get_slot('testsleftBlock')): ?>

                          <div id="sidebar" class="articleLeft"><ul class="cat-menu">
                                <li><a onclick="location.replace('<?= url_for('sexopedia/') ?>/')" href="<?= url_for('sexopedia/') ?>" style="height: 150px; width: 210px;" id="sexopediaMenu">
                                        <span style="height: 150px; width: 210px;float: left;" class="img">
                                        </span>
                                        <span class="deco-left"></span>
                                        <span class="deco-right"></span>
                                    </a>
                                </li>
                                <li><a onclick="location.replace('<?= url_for('horoscope/') ?>')" href="<?= url_for('horoscope/') ?>" style="height: 150px; width: 210px;" id="horoscopeMenu">
                                        <span style="height: 150px; width: 210px;float: left;" class="img">
                                        </span>
                                        <span class="deco-left"></span>
                                        <span class="deco-right"></span>
                                    </a>
                                </li>
                                <li><a onclick="location.replace('<?= url_for('lovetest/') ?>')" href="<?= url_for('lovetest/') ?>" style="height: 150px; width: 210px;" id="testsMenu" class="selectMenu">
                                        <span style="height: 150px; width: 210px;float: left;" class="img">
                                        </span>
                                        <span class="deco-left"></span>
                                        <span class="deco-right"></span>
                                    </a>
                                </li>
                            </ul><?
                            $horoscopeleftBlockTempl = PageTable::getInstance()->findOneBySlug("levyi-blok-goroskopov");
                            echo $horoscopeleftBlockTempl->getContent();
                            ?>
                          </div>

                        <? endif;
                        $timer->addTime();
                        $timer = sfTimerManager::getTimer('Layout: Левый блок: Гороскопы');
                        if (get_slot('horoscopeleftBlock')): ?>

                          <div id="sidebar" class="articleLeft">
                            <ul class="cat-menu">
                                <li><a onclick="location.replace('<?= url_for('sexopedia/') ?>')" href="<?= url_for('sexopedia/') ?>" style="height: 150px; width: 210px;" id="sexopediaMenu">
                                        <span style="height: 150px; width: 210px;float: left;" class="img">
                                        </span>
                                        <span class="deco-left"></span>
                                        <span class="deco-right"></span>
                                    </a>
                                </li>
                                <li><a onclick="location.replace('<?= url_for('horoscope/') ?>')" href="<?= url_for('horoscope/') ?>" style="height: 150px; width: 210px;" id="horoscopeMenu" class="selectMenu">
                                        <span style="height: 150px; width: 210px;float: left;" class="img">
                                        </span>
                                        <span class="deco-left"></span>
                                        <span class="deco-right"></span>
                                    </a>
                                </li>
                                <li><a onclick="location.replace('<?= url_for('lovetest/') ?>')" href="<?= url_for('lovetest/') ?>" style="height: 150px; width: 210px;" id="testsMenu">
                                        <span style="height: 150px; width: 210px;float: left;" class="img">
                                        </span>
                                        <span class="deco-left"></span>
                                        <span class="deco-right"></span>
                                    </a>
                                </li>
                            </ul>
                            <?
                              $horoscopeleftBlockTempl = PageTable::getInstance()->findOneBySlug("levyi-blok-goroskopov");
                              echo $horoscopeleftBlockTempl->getContent();
                            ?>
                          </div>

                        <? endif;
                        $timer->addTime();
                        $timer = sfTimerManager::getTimer('Layout: Левый блок: Статьи');
                        if (get_slot('articleleftBlock')):
                          $ArticleCatalogs = ArticlecatalogTable::getInstance()->createQuery()->where('is_public=1')->orderBy('position ASC')->execute();
                          ?>
                          <div id="sidebar" class="articleLeft">
                            <?php foreach ($ArticleCatalogs as $keyArt => $ArticleCatalog): ?>
                                <div class="benefits-box box">
                                    <div class="title-holder-box">
                                        <div class="title-holder"><a href="/sexopedia<?= $keyArt == 0 ? "" : "/catalog/" . $ArticleCatalog->getSlug() ?>" style="color: rgb(195, 6, 14);"><?= $ArticleCatalog->getName() ?></a></div>
                                    </div>
                                    <ul class="cat-menu-article">
                                        <li>
                                            <ul style="display: block;" class="drop">
                                                <?php
                                                //$categoryArt= ArticlecategoryTable::getInstance()->createQuery()->
                                                foreach ($ArticleCatalog->getCategory() as $ArticleCattegory):
                                                    ?>
                                                    <li<?php if ($sf_request->getParameter('slug') == $ArticleCattegory->getSlug() or get_slot('articcategorySlug') == $ArticleCattegory->getSlug()) { ?> class="active"<?php } ?> style="margin-left: 10px;">
                                                        <a href="<?= '/sexopedia/category/' . $ArticleCattegory->getSlug() ?>"><?= $ArticleCattegory->getName() ?></a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            <?php endforeach; ?>

                            <div class="articleLeft">
                              <ul class="cat-menu">
                                    <li><a onclick="location.replace('<?= url_for('sexopedia/') ?>')" href="<?= url_for('sexopedia/') ?>" style="height: 150px; width: 210px;" id="sexopediaMenu" class="selectMenu">
                                            <span style="height: 150px; width: 210px;float: left;" class="img">
                                            </span>
                                            <span class="deco-left"></span>
                                            <span class="deco-right"></span>
                                        </a>
                                    </li>
                                    <li><a onclick="location.replace('<?= url_for('horoscope/') ?>')" href="<?= url_for('horoscope/') ?>" style="height: 150px; width: 210px;" id="horoscopeMenu">
                                            <span style="height: 150px; width: 210px;float: left;" class="img">
                                            </span>
                                            <span class="deco-left"></span>
                                            <span class="deco-right"></span>
                                        </a>
                                    </li>
                                    <li><a onclick="location.replace('<?= url_for('lovetest/') ?>')" href="<?= url_for('lovetest/') ?>" style="height: 150px; width: 210px;" id="testsMenu">
                                            <span style="height: 150px; width: 210px;float: left;" class="img">
                                            </span>
                                            <span class="deco-left"></span>
                                            <span class="deco-right"></span>
                                        </a>
                                    </li>
                              </ul>
                            </div>
                          </div>

                        <? endif;
                        $timer->addTime(); ?>

                        <?php if (get_slot('leftBlock')):
                            $timer = sfTimerManager::getTimer('Layout: Левый блок: Категории и бренды');
                            ?>

                          <div id="sidebar" class="sidebar-category">
                            <?php
                              include_component(
                                'category',
                                'catalogDev',
                                array(
                                  'slug' => $sf_request->getParameter('slug'),
                                  'slot' => get_slot('category_slug'),
                                  'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
                                )
                              );

                              $timerArt = sfTimerManager::getTimer('Layout: Левый блок: Категории и бренды: статьи');
                              mb_internal_encoding('UTF-8');
                              $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                              $articles = $q->execute("SELECT *  "
                                              . "FROM article "
                                              . "WHERE is_public='1' ")->fetchAll(Doctrine_Core::FETCH_UNIQUE);
                              shuffle($articles);
                              //$articles = ArticleTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("rand()")/* ->limit(3) */->execute();
                            ?>
                            <div class="encSex box" style="overflow: hidden;">
                                <noindex>
                                <div class="title-holder"><a href="<?= url_for('sexopedia/') ?>" style="color: #C3060E" id="sexopedia">Энциклопедия секса</a></div>
                                <ul class="benefits-list"><?
                                    foreach ($articles as $key => $article) {
                                        echo
                                          "<li>".
                                            "<div class=\"title\">".
                                              "<a href=\"/sexopedia/" . $article['slug'] . "\">" . $article['name'] . "</a>".
                                            "</div>".
                                            "<div style=\" margin: 4px 0 0;\" class=\"stars\"><span style=\"width:" . ($article['votes_count'] > 0 ? (($article['rating'] / $article['votes_count']) * 10) : "0") . "%;\"></span></div>".
                                            "<p style=\"margin: 7px 0 10px 0; font: 11px/18px Tahoma,Geneva,sans-serif;color: #707070;\">" . mb_substr(strip_tags(preg_replace("/&(.+?);/", "", $article['content'])), 0, 130) . "...</p>".
                                          "</li>	";
                                    }

                                    $timerArt->addTime();
                                    ?>

                                </ul>
                              </noindex>
                            </div>
                            <div class="brand-galery box brandspro">
                                <div class="title-holder"><a href="/manufacturers#our_brend" style="color: #C3060E" id="brend">Наши бренды</a></div>
                                <div class="galery-holder">
                                    <a class="prev" href="#"></a>
                                    <a class="next" href="#"></a>
                                    <div class="galery" style="height: 190px; min-height: 190px;"><?
                                        $footer = PageTable::getInstance()->findOneBySlug("blok-brendov");
                                        echo $footer->getContent();
                                        ?>

                                    </div>
                                </div>
                            </div>
                          </div>


                          <?php $timer->addTime();
                        endif; ?>

                    <?php if (get_slot('rightBlockCategory')):
                        $timer = sfTimerManager::getTimer('Layout: Правый блок: Категории и бренды');
                        ?>

                        <div id="sidebar" class="sidebar-category">
                          <?php
                            include_component(
                              'category',
                              'catalog',
                              array(
                                'slug' => $sf_request->getParameter('slug'),
                                'slot' => get_slot('category_slug'),
                                'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
                              )
                            );


                            $timerArt = sfTimerManager::getTimer('Layout: Правый блок: Категории и бренды: статьи');
                            mb_internal_encoding('UTF-8');
                            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                            $articles = $q->execute("SELECT *  "
                                            . "FROM article "
                                            . "WHERE is_public='1' ")->fetchAll(Doctrine_Core::FETCH_UNIQUE);
                            shuffle($articles);
                            //$articles = ArticleTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("rand()")/* ->limit(3) */->execute();
                            ?> <div class="encSex box" style="overflow: hidden;">
                              <noindex>
                                <div class="title-holder"><a href="<?= url_for('sexopedia/') ?>" style="color: #C3060E" id="sexopedia">Энциклопедия секса</a></div>
                                <ul class="benefits-list"><?
                                  foreach ($articles as $key => $article) {
                                      echo
                                        "<li>".
                                          "<div class=\"title\">".
                                            "<a href=\"/sexopedia/" . $article['slug'] . "\">" . $article['name'] . "</a>".
                                          "</div>".
                                          "<div style=\" margin: 4px 0 0;\" class=\"stars\"><span style=\"width:" . ($article['votes_count'] > 0 ? (($article['rating'] / $article['votes_count']) * 10) : "0") . "%;\"></span></div>".
                                          "<p style=\"margin: 7px 0 10px 0; font: 11px/18px Tahoma,Geneva,sans-serif;color: #707070;\">" . mb_substr(strip_tags(preg_replace("/&(.+?);/", "", $article['content'])), 0, 130) . "...</p>".
                                        "</li>";
                                  }

                                  $timerArt->addTime();
                                  ?>

                                </ul>
                              </noindex>
                            </div>


                            <div class="brand-galery box brandspro2">
                                <div class="title-holder"><a href="/manufacturers#our_brend" style="color: #C3060E" id="brend">Наши бренды</a></div>
                                <div class="galery-holder">
                                    <a class="prev" href="#"></a>
                                    <a class="next" href="#"></a>
                                    <div class="galery" style="height: 220px; min-height: 190px;"><?
                                        $footer = PageTable::getInstance()->findOneBySlug("blok-brendov");
                                        echo $footer->getContent();
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php
                        $timer->addTime();
                    endif;
                    $timer = sfTimerManager::getTimer('Layout: Правый блок');
                    ?>

                    <?php if (get_slot('rightBlock')): ?>
                        <div class="aside"><div class="buttonReadOurAdvantages" onmouseover="$('.blockReadOurAdvantages').fadeIn(0);" onmouseout="$('.blockReadOurAdvantages').fadeOut(0);"><div style="text-decoration: underline;">Ознакомтесь с нашими преимуществами</div>

                                <div class="blockReadOurAdvantages" style="padding: 0;width: 210px;">
                                    <div class="benefits-box box" style="background: #f1f1f1;">
                                        <?
                                        $footer = PageTable::getInstance()->findOneBySlug("nashi-preimuschestva");
                                        echo $footer->getContent();
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!--
                            <div class="ads">
                                <a href="/akcia5"><img src="/newdis/images/akcia5.jpg" width="210" alt="image description" /></a>
                            </div>
                            <div class="ads">
                                <a href="<?= $pre ?>/rassylka"><img src="/newdis/images/img33.jpg" width="210" height="180" alt="image description" /></a>
                            </div>
                            <div class="ads">
                                <a target="_blank" href="http://club.onona.ru/index.php/topic/130-specialnaja-premija-roznichnaja-set-goda/"><img src="/newdis/images/img01.jpg" width="210" height="160" alt="image description" /></a>
                            </div>-->
                            <?
                            $footer = PageTable::getInstance()->findOneBySlug("Banery_sprava");
                            echo $footer->getContent();
                            ?>
                            <?php
                            $timerRelated = sfTimerManager::getTimer('Layout: Рекомендуемые товары');

                            $timerRelated->addTime();
                            ?>
                        </div>

                        <?php
                    endif;
                    $timer->addTime();
                    $timer = sfTimerManager::getTimer('Layout: Основная часть');
                    if (get_slot('mainPage')) {
                        ?><div class="mainpage"><?
                        echo $sf_content;
                        ?></div><?
                    }
                    else {
                      if (get_slot('sexshop')) { ?><div class="sexshop"><? } ?>
                            <div id="content"
                            <? if ((!get_slot('rightBlock') or ! get_slot('leftBlock')) and ( get_slot('rightBlock') or get_slot('leftBlock'))) { ?> class="left"<? } ?>
                            <? if(get_slot('no-float')) echo 'class="no-float"'?>
                            >
                                <?php echo $sf_content ?>
                            </div><? if (get_slot('sexshop')) { ?></div><? } ?>
                        <?
                    }
                    $timer->addTime();
                    ?>
                </div>
                <div style="clear: both;margin-bottom: 20px;"></div>
                <div id="footer-new-top">
                    <?php
                    if ($sf_user->isAuthenticated()) { ?>
                      <div class="grey-block-1 autorized">
                        <div class="grey-block-element delivery">Доставка в подарок<div>При заказе от 2990 ₽</div></div>
                        <div class="grey-block-element subsribe">Будьте в курсе!<div>Новости, акции,<br>спецпредложения</div></div>
                        <div class="grey-block-element gift">Бонусы покупателям!<div>за каждый заказ</div></div>
                      </div>
                    <? }
                    else {
                        ?>

                        <div class="grey-block-1">
                          <div class="grey-block-element delivery">Доставка в подарок<div>При заказе от 2990 ₽</div></div>
                          <div class="grey-block-element subsribe">Будьте в курсе!<div>Новости, акции,<br>спецпредложения</div></div>
                        </div>
                        <div class="grey-block-2">
                            <form action="<?= url_for('firstvisit/yes/') ?>" id="firstVisitYesNews" method="POST">
                                <div class="input-line">
                                    <input type="hidden" name="user_sex" />
                                    <input type="text" value="Имя" name="user_name" style="" onClick="if ($(this).val() == 'Имя')
                                                $(this).val('');" />
                                    <input type="text" value="E-mail" onblur="var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                            if (regex.test(this.value)) {
                                                try {
                                                    //rrApi.setEmail(this.value);
                                                    sendRRWelcome(this.value);
                                                } catch (e) {
                                                }
                                            }" name="user_mail"
                                            onClick="if ($(this).val() == 'E-mail')
                                                        $(this).val('');" />
                                    <div class="validation-error"></div>
                                    <div class="statusValidMail" style="margin-left: 0px;"></div>
                                </div>
                                <div class="buttons-line">
                                    <div>ПОДПИСАТЬСЯ:</div>
                                    <div onclick="validateEmailRassilka('m')" class="buttonSignUpMale"></div>
                                    <div onclick="validateEmailRassilka('g')" class="buttonSignUpFemale"></div>
                                </div>
                                <div class="footer-personal">
                                  <input id='accept-form' type='checkbox'>
                                  <label for='accept-form' >Я принимаю условия <a href='/personal_accept' target='_blank'>Пользовательского соглашения</a></label>
                                </div>

                            </form>
                        </div><?
                    }
                    ?>
                </div>
                <?
                $timer = sfTimerManager::getTimer('Layout: Вывод футера');
                $footer = PageTable::getInstance()->findOneBySlug("footer");
                if (@$partnerRef)
                    if (@$partner) {
                        echo str_replace('{partnetId}',
                          '<div style="text-align: center; font-size: 16px; padding-top: 15px;  bottom: 20px; width: 250px; left: 13px;"> Ваш ID: <div style="color: rgb(195, 6, 14); background-color: rgb(255, 255, 255); border-radius: 3px 3px 3px 3px; float: right; border: 1px solid rgb(68, 68, 68); margin-top: -5px; padding-top: 4px; height: 22px; margin-right: 16px; width: 128px;">' . $partnerRef->getId() . '-' . $partner->getId() . '</div></div>', $footer->getContent());
                    }
                    else {
                        echo str_replace('{partnetId}',
                          '<div style="text-align: center; font-size: 16px; padding-top: 15px; bottom: 20px; width: 250px; left: 13px;"> Ваш ID: <div style="color: rgb(195, 6, 14); background-color: rgb(255, 255, 255); border-radius: 3px 3px 3px 3px; float: right; border: 1px solid rgb(68, 68, 68); margin-top: -5px; padding-top: 4px; height: 22px; margin-right: 16px; width: 128px;">' . $partnerRef->getId() . '</div></div>', $footer->getContent());
                    }
                else
                    echo str_replace('{partnetId}',
                      '<div style="text-align: center; font-size: 16px; padding-top: 15px; bottom: 20px; width: 250px; left: 13px;"> Ваш ID: <div style="color: rgb(195, 6, 14); background-color: rgb(255, 255, 255); border-radius: 3px 3px 3px 3px; float: right; border: 1px solid rgb(68, 68, 68); margin-top: -5px; padding-top: 4px; height: 22px; margin-right: 16px; width: 128px;">777</div></div>', $footer->getContent());

                $timer->addTime();
                ?>
            </div>
            <a href="#" class="to-up">наверх</a>

        </div>

        <div id="login" class="login-popup">
            <a href="#" class="close"></a>
            <span class="deco-left"></span>
            <span class="deco-right"></span>
            <form action="<?php echo url_for('@sf_guard_signin') ?>" method="POST">
                <?php $authForm = new sfGuardFormSignin() ?>
                <fieldset>
                    <div class="title">Авторизация</div>
                    <div class="error-msg">Неправильный логин или пароль</div>
                    <div class="input-frame parent-active"><div style="text-align: left; margin-left: 10px; color: rgb(153, 153, 153); font-size: 11px;">Введите логин (e-mail)</div>
                        <div class="input-holder">
                            <input type="text" name="signin[username]" onblur="var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                                    if (regex.test(this.value)) {
                                        try {
                                            rrApi.setEmail(this.value);
                                        } catch (e) {
                                        }
                                    }" class=" text-active" />
                        </div>
                    </div>
                    <div class="input-frame parent-active"><div style="text-align: left; margin-left: 10px; color: rgb(153, 153, 153); font-size: 11px;">Введите пароль</div>
                        <div class="input-holder">
                            <input type="password" name="signin[password]" class=" text-active" />

                        </div>
                    </div>
                    <div class="checkbox-holder">
                        <input id="lbl01" type="checkbox" class="checkbox" name="signin[remember]" />
                        <label for="lbl01">Запомнить</label>
                    </div>
                    <div class="btn-holder">
                        <div class="red-btn">
                            <span onclick="$(this).next('input').click()">Вход</span>
                            <input type="submit" value="Вход" class="button" />
                        </div>
                    </div>
                    <div class="forgot">
                        <a class="forgot-password" href="#">Забыли пароль?</a>
                    </div>
                </fieldset>
            </form>
        </div>

        <div id="forgot-password" class="login-popup">
            <a href="#" class="close"></a>
            <span class="deco-left"></span>
            <span class="deco-right"></span>
            <form action="<?php echo url_for('@sf_guard_forgot_password') ?>" method="POST">
                <?php $fpForm = new sfGuardRequestForgotPasswordForm() ?>
                <fieldset>
                    <div class="title">Восстановление <br /> пароля</div>
                    <div class="input-frame parent-active">
                        <div class="input-holder">
                            <input type="text" <? /* value="E-mail" */ ?> name="forgot_password[email_address]" class=" text-active"<? /* onclick="$(this).attr('value','');$(this).parent().parent().parent().children('.input-frame:last').addClass('parent-active');$(this).parent().parent().parent().children('.input-frame:last').find('input:first').addClass('text-active');" */ ?>  />
                            <? /* <?= $fpForm['_csrf_token'] ?> */ ?>
                        </div>
                    </div>
                    <div class="text">Введите e-mail, который указывали при регистрации</div>
                    <div class="btn-holder">
                        <div class="red-btn send">
                            <span>Отправить</span>
                            <input type="submit" value="Отправить" class="button" />
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        <div id="LK" class="login-popup">
            <a href="#" class="close"></a>
            <span class="deco-left"></span>
            <span class="deco-right"></span>
            <fieldset>
                <div class="title"><a href="<?= url_for('lk/') ?>">Личный кабинет</a></div>
                <div class="text"><a href="<?= url_for('customer/mydata/') ?>">Ваши данные</a> </div>
                <div class="text"><a href="<?= url_for('customer/myorders/') ?>">Ваши заказы</a></div>
                <div class="text"><a href="<?= url_for('customer/bonus/') ?>">Ваши бонусы</a></div>
                <div class="text"><a href="<?= url_for('customer/myphoto/') ?>">Ваши фотографии</a> </div>
                <div class="text"><a href="<?= url_for('customer/notification/') ?>">Ваши оповещения</a> </div>
                <?
                if ($sf_user->isAuthenticated()) {
                    if (@substr_count("vk.com", sfContext::getInstance()->getUser()->getGuardUser()->getSsidentity()) > 0) {
                        ?>
                        <div class="text"><a href="<?= url_for('customer/enterVkGroup/') ?>">Получить бонусы за вступление в группу Вконтакте</a></div>
                        <?
                    }
                }
                ?>
                <div class="text"><a href="<?= url_for('guard/logout') ?>">Выход</a></div>
            </fieldset>
        </div>
        <?/* 132119 - onona.ru - Плашка на сайте
          <div class="floating-menu">
              <div class="floating-menu-frame">
                  <ul class="menu right">
                      <li><a href="<?= url_for('desire/') ?>" class="desire"><span><?
                                  if ($products_desire == "") {
                                      echo '0';
                                  } else {
                                      echo count($products_desire);
                                  }
                                  ?></span></a></li>
                      <?php
                      $timer = sfTimerManager::getTimer('Layout: Вывод в плавающем меню количества бонусов');
                      if (sfContext::getInstance()->getUser()->isAuthenticated()) {
                          include_component('noncache', 'bonusCount', array('sf_cache_key' => sfContext::getInstance()->getUser()->getGuardUser()->getId()));
                      }

                      $timer->addTime();
                      ?>
                      <li><a href="<?= url_for('cart/') ?>" class="card"><?php if (@get_slot("productsCount") > 0): ?>
                                  <span><?= get_slot("productsCount") ?> товаров / <?= get_slot("totalCost") ?> р.</span>
                              <?php else: ?>
                                  <span>Корзина пуста</span>
                              <?php endif; ?>
                          </a></li>
                  </ul>
                  <ul class="menu">
                    <?php
                      $timer = sfTimerManager::getTimer('Layout: Вывод плавающего меню');
                      $menu = MenuTable::getInstance()->findByPositionmenu('Над шапкой(новый дизайн)');
                      foreach ($menu as $link): ?>
                        <li><a href="<?= url_for($link->getUrl() == "/" ? url_for('@homepage') : trim($link->getUrl(), "/") . '/') ?>"><?= $link->getText() ?></a></li>
                      <?php endforeach;
                        $timer->addTime();
                    ?>
                  </ul>
              </div>
          </div>
        */?>

        <script type="text/javascript">
          <?php include_slot("JSInPages") ?>
        </script>

        <div id="dynamic-to-top" style="display: none;" onclick="$('html, body').animate({scrollTop: 0}, 'slow');
                return false;"><span></span>
        </div>

        <script type='text/javascript'>
            $(document).ready(function () {
                if ($('html').scrollTop() > 150 || $('body').scrollTop() > 150) {
                    $('#dynamic-to-top').fadeIn();
                } else {
                    $('#dynamic-to-top').fadeOut();
                }

                $(window).scroll(function () {
                    if ($('html').scrollTop() > 150 || $('body').scrollTop() > 150) {
                        $('#dynamic-to-top').fadeIn();
                    } else {
                        $('#dynamic-to-top').fadeOut();
                    }
                });
                if (($(window).width() - $(".mainWrap").width()) / 2 > 50) {
                    $("#dynamic-to-top").css("left", ($(window).width() - $(".mainWrap").width()) / 2 - 90);
                } else {
                    $("#dynamic-to-top").css("left", 0);
                }
            });
        </script>

        <!---/Main Wrap-->
        <?php if ($sf_user->isAuthenticated()): ?>
            <?php
              preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $sf_user->getGuardUser()->getBirthday(), $bArr);
            ?>
            <script type="text/javascript">
                rrApiOnReady.push(function () {
                    rrApi.setEmail("<?= $sf_user->getGuardUser()->getEmailAddress() ?>", { BirthDate: "<?php echo $bArr[3].'.'.$bArr[2].'.'.$bArr[1]; ?>" });
                });
            </script>
            <div id="gtm-user-id" data-id="<?=$sf_user->getGuardUser()->getId()?>"></div>
        <?php endif; ?>
        <? if (!sfContext::getInstance()->getRequest()->getCookie('youtube_subscription')) { ?>
          <?
            $aStyle=
             "background-image: url('/newdis/images/sprites/spriteme1.png');"
             ."background-position: -10px -638px;"
             ."width: 13px;"
             ."height: 13px;"
             ."text-decoration: none;"
             ."position: fixed;"
             ."bottom: 50px;"
             ."left: 167px;"
             ."display: block;"
             ."z-index: 25;"
             ;
          ?>
            <a  class="close youtube-subscrition" href="#" onclick="setCookie('youtube_subscription', true, 'Mon, 01-Jan-2101 00:00:00 GMT', '/');
                    $('#youtube_subscription').fadeOut();
                    $(this).fadeOut();
                    return false;"
                style="<?=$aStyle?>"></a>
            <a class="youtube-subscrition" href="https://www.youtube.com/channel/UCrZ-3sU3RtG5g1YhKF8JJyg?sub_confirmation=1" target="_blank">
              <?
                $aStyle=
                  "width: 180px;"
                  ."height: 50px;"
                  ."position: fixed;"
                  ."left: 0;"
                  ."bottom: 0;"
                  ."background: url('/images/youtube_180x50.png') no-repeat;"
                  ."cursor: pointer;"
                  ."z-index: 25;"
                  ;
              ?>
                <div style="<?=$aStyle?>" id="youtube_subscription">
                </div>
            </a>
        <? } ?>


        <script type="text/javascript" src="/js/animImgNew.js?v=5"></script>
        <? if (get_slot('mainPage')) :?>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.jquery.min.js"></script>
        <? endif ?>
        <script type="text/javascript">
          $(document).ready(function(){
            <? if (get_slot('mainPage')) : ?>
              if ($('#main-slider').length){
                mySwiper = new Swiper ('#main-slider', {
                  loop: true,
                  autoplay: 3000, //4 секунды
                  effect: 'fade',
                  // pagination: '.swiper-pagination',
                  // paginationClickable: true,
                  nextButton: '.swiper-button-next',
                  prevButton: '.swiper-button-prev',
                })
              }
            <? endif ?>

          });

            $(document).ready(function () {

                $('#sf_guard_user_phone').focus(function () {
                    if (!$("div").is("#commPhone")) {
                        window.setTimeout("$('#content').css('height',$('.borderCart').height())", 50);
                    }
                });
            });
        </script>


        <script src="/js/jquery.lazyload.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("img.thumbnails").lazyload();
                $("img.thumbnails").each(function (i) {
                    if (i < 6) {
                        $(this).attr("src", $(this).attr("data-original"));
                    }
                });
            });
        </script>
        <script type="text/javascript" src="/player/js/hdwebplayer.js"></script>
        <script type='text/javascript'>
            player = document.getElementById('playerVideoDiv');
        </script>
        <script src="/js/allJSFooter.js?v=31" type="text/javascript"></script>
        <script src="/js/colorbox.js"></script>

        <link rel="stylesheet" type="text/css" media="screen" href="/newdis/css/sale_newcat.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="/css/highslide.css" />
        <?php if ($_SERVER['HTTP_HOST']!='dev.onona.ru') :?>
          <?/* 135322 - onona.ru - Список внешних сервисов <script type="text/javascript" language="javascript"> var _lh_params = {"popup": false}; lh_clid="5604fd6fbbddbd5e6a27efb2"; (function() { var lh = document.createElement('script'); lh.type = 'text/javascript'; lh.async = true; lh.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'track.leadhit.io/track.js?ver=' + Math.floor(Date.now()/100000).toString(); var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lh, s); })(); </script>*/?>
          <div class="mango-callback" data-settings='{"type":"", "id": "MTAwMDY4OTI=","autoDial" : "0", "lang" : "ru-ru", "host":"widgets.mango-office.ru/", "errorMessage": "В данный момент наблюдаются технические проблемы и совершение звонка невозможно"}'></div>
          <script>!function(t){function e(){i=document.querySelectorAll(".button-widget-open");for(var e=0;e<i.length;e++)"true"!=i[e].getAttribute("init")&&(options=JSON.parse(i[e].closest('.'+t).getAttribute("data-settings")),i[e].setAttribute("onclick","alert('"+options.errorMessage+"(0000)'); return false;"))}function o(t,e,o,n,i,r){var s=document.createElement(t);for(var a in e)s.setAttribute(a,e[a]);s.readyState?s.onreadystatechange=o:(s.onload=n,s.onerror=i),r(s)}function n(){for(var t=0;t<i.length;t++){var e=i[t];if("true"!=e.getAttribute("init")){options=JSON.parse(e.getAttribute("data-settings"));var o=new MangoWidget({host:window.location.protocol+'//'+options.host,id:options.id,elem:e,message:options.errorMessage});o.initWidget(),e.setAttribute("init","true"),i[t].setAttribute("onclick","")}}}host=window.location.protocol+"//widgets.mango-office.ru/";var i=document.getElementsByClassName(t);o("link",{rel:"stylesheet",type:"text/css",href:host+"css/widget-button.css"},function(){},function(){},e,function(t){document.documentElement.insertBefore(t,document.documentElement.firstChild)}),o("script",{type:"text/javascript",src:host+"widgets/mango-callback.js"},function(){("complete"==this.readyState||"loaded"==this.readyState)&&n()},n,e,function(t){document.documentElement.appendChild(t)})}("mango-callback");</script>
        <?php endif ?>
        <?php /* if ($_SERVER['HTTP_HOST']!='dev.onona.ru') :?> 135322 - onona.ru - Список внешних сервисов
          <script>!function(e,t,d,s,a,n,c){e[a]={},e[a].date=(new Date).getTime(),n=t.createElement(d),c=t.getElementsByTagName(d)[0],n.type="text/javascript",n.async=!0,n.src=s,c.parentNode.insertBefore(n,c)}(window,document,"script","https://ononaru.push.world/https.embed.js","pw"),pw.websiteId="97e67a8b402849422c63f5aa6bc8d09da5399e8b752a0d94972aaef3ac6d7cea";</script>
        <?php endif */?>
      <div style="display: none;"><?= $regionMoscow ?></div>
      <a href="/programma-on-i-ona-bonus" class="cashback"></a>
      <?php if ($_SERVER['HTTP_HOST']!='dev.onona.ru') :?>
        <!-- BEGIN JIVOSITE CODE {literal} -->
        <script type='text/javascript'>
        (function(){ var widget_id = 'lHhK9V8ak4';var d=document;var w=window;function l(){var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
        </script>
        <!-- {/literal} END JIVOSITE CODE -->
      <?php endif ?>

      <div data-retailrocket-markup-block="5ba3a68c97a5283ed03172e9"></div>
      <script src="/js/ya-params.js"></script>
      <script type="text/javascript">
        var digiScript = document.createElement('script');
        digiScript.src = 'https://cdn.diginetica.net/538/client.js?ts=' + Date.now();
        digiScript.defer = true;
        digiScript.async = true;
        document.body.appendChild(digiScript);
      </script>
      <?/* отправка данных в advcake */?>
      <?
        $advcakeType=get_slot('advcake');
        if (explode('?', $_SERVER['REQUEST_URI'])[0]=='/') $advcakeType=1;
        // $advcakeType=1;

        if($advcakeType){
          $basket=unserialize(sfContext::getInstance()->getUser()->getAttribute('products_to_cart'));
          // $basket=unserialize(sfContext::getInstance()->getUser()->getGuardUser()->getCart());
          foreach($basket as $id => $basketLine){
            $product=ProductTable::getInstance()->findOneById($id);
            $genCat=$product->getGeneralCategory();
            $productsInBasket[]=[
              'id' => $id,
              'name' => $product->getName(),
              'categoryId' => $genCat->getId(),
              'categoryName' => $genCat->getName(),
              'quantity' => $basketLine['quantity'],
              'price' => $basketLine['price_w_discount'],
            ];
          }
          // die('~~~~!');
          ?>

          <?/*
          <div style=" position: fixed; top: 0; left: 0; width: 500px; height: 900px; background: #ccc;"><pre>
          */?>
          <script>
            <? //= print_r($_SERVER, true)?>
            window.advcake_data = window.advcake_data || [];
            window.advcake_data.push({
              pageType: <?= $advcakeType ?>,
              user: <?= $sf_user->isAuthenticated() ? "{email: '".md5(sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress())."'}" : 'false' ?>,
              <? if($advcakeType!=6) { // Страница "спасибо за заказ" ?>
                basketProducts: <?= json_encode($productsInBasket) ?>,
              <? } ?>
              <? //=print_r([sfContext::getInstance()->getUser()->getGuardUser()->getCart()], true) ?>
              <? if($advcakeType==6) { // Страница "спасибо за заказ" ?>
                orderInfo: <?= json_encode(get_slot('advcake_order')) ?>,
                basketProducts: <?= json_encode(get_slot('advcake_order_basket')) ?>,
              <? } ?>
              <? if($advcakeType==2) { // Детальная страница товара
                $product=get_slot('advcake_detail');
              ?>
                currentProduct: <?= json_encode($product['product'])?>,
                currentCategory: <?= json_encode($product['category'])?>,
              <? } ?>
              <? if($advcakeType==7 || $advcakeType==3) { // Страница "спасибо за заказ"
                $productsList=get_slot('advcake_list');
              ?>
                  products: <?= json_encode($productsList['products']) ?>,
                  <? if($advcakeType==3) {?>
                    <?/*currentCategory: <?= print_r($productsList['category'], true) ?>,*/?>
                    currentCategory: <?= json_encode($productsList['category']) ?>,
                  <?}
              } ?>
            });
            <?/*
          </pre></div>
          */?>
          </script>
        <?}
      ?>
      <script src="https://regmarkets.ru/js/r17.js" async type="text/javascript"></script>
    </body>
</html>
<?php
if ($sf_user->isAuthenticated()):

    if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == 17101) {
        //echo phpinfo();
    }
endif;
if ($_SERVER['SCRIPT_NAME'] == "/frontend_dev.php") {
    //echo "1";
}
$timerAll->addTime();
?>
<?/*
<div style="display: none;">
  <pre>~!<?=print_r($_SERVER, true)?>!~</pre>
*/?>
<link rel="stylesheet" type="text/css" href="/js/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="/js/slick/slick-theme.css"/>
<script type="text/javascript" src="/js/slick/slick.min.js"></script>
<script>
$('.brandspro ul').slick({
  dots: true,
  infinite: true,
  speed: 300,
  slidesToShow: 4,
  slidesToScroll: 4,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 660,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        dots: false
      }
    },
    {
      breakpoint: 450,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});
</script>
