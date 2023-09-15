<?php
if ($sf_user->isAuthenticated()):

    if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == 17101) {

    }
endif;


$timerIP = sfTimerManager::getTimer('Layout: Определение региона');
if (@sfContext::getInstance()->getUser()->getAttribute('regMO') !== true and @ sfContext::getInstance()->getUser()->getAttribute('regMO') !== false) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $SxGeo = new SxGeo('../apps/newcat/lib/SxGeoCity.dat');
    $geoData = $SxGeo->getCityFull($ip); //Москва Московская область
    if ($geoData['region']['name_ru'] == "Москва" or $geoData['region']['name_ru'] == "Московская область") {
        sfContext::getInstance()->getUser()->setAttribute('regMO', true);
    } else {
        sfContext::getInstance()->getUser()->setAttribute('regMO', false);
    }
}
$timerIP->addTime();


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
} else if (@isset($_GET['r']) && @(integer) $_GET['r'] !== 0) {
    $partnerRef = PartnerRefidTable::getInstance()->createQuery()->where("refid=?", $_GET['r'])->fetchOne();
    if (!$partnerRef) {
        $partnerRef = new PartnerRefid();
        $partnerRef->setRefid($_GET['r']);
        $partnerRef->save();
    }
} else if (@isset($_COOKIE['referal']) and isset($_COOKIE['wmaster']) && @(integer) $_GET['r'] !== 0) {
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
} else if (@isset($_COOKIE['referal']) && (integer) @$_GET['r'] !== 0) {
    $partnerRef = PartnerRefidTable::getInstance()->createQuery()->where("refid=?", $_COOKIE['referal'])->fetchOne();
    if (!$partnerRef) {
        $partnerRef = new PartnerRefid();
        $partnerRef->setRefid($_GET['r']);
        $partnerRef->save();
    }
} else if (@isset($_COOKIE['referal']) and isset($_COOKIE['wmaster'])) {
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
} else if (@isset($_COOKIE['referal'])) {
    $partnerRef = PartnerRefidTable::getInstance()->createQuery()->where("refid=?", $_COOKIE['referal'])->fetchOne();
    if (!$partnerRef) {
        $partnerRef = new PartnerRefid();
        $partnerRef->setRefid($_COOKIE['referal']);
        $partnerRef->save();
    }
}

$timer->addTime();


$productsToCart = $sf_context->getUser()->getAttribute('products_to_cart');
$productsToCart = $productsToCart != '' ? unserialize($productsToCart) : array();
if (!is_array($productsToCart))
    $productsToCart = array();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no"/>
        <title><?= str_replace(array("'", '"'), "", get_slot('metaTitle') == '' ? csSettings::get('defaultTitle') : get_slot('metaTitle')) ?></title>
        <meta name="keywords" content="<?= str_replace(array("'", '"'), "", get_slot('metaKeywords') == '' ? csSettings::get('defaultKeywords') : get_slot('metaKeywords')) ?>" />
        <meta name="description" content="<?= str_replace(array("'", '"'), "", get_slot('metaDescription') == '' ? csSettings::get('defaultDescription') : get_slot('metaDescription')) ?>" />
        <link rel="shortcut icon" href="/favicon.ico" />

    </head>
    <body>
      <div class="snap-drawers">
            <div class="snap-drawer snap-drawer-left" id="leftMenu">
                <div>
                    <div id="snap-toolbar">
                        <div class="search">
                            <form action="<?php echo url_for('@search') ?>" method="post">
                                <input type="text" class="snap-search-input" placeholder="Поиск товара" name="searchString" /><input type="submit" class="snap-search-button" value="" />
                            </form>
                        </div>
                    </div>
                    <?php
                    JSInPages("function toggleSubMenu(tag){
                $(tag).parent().children('ul').toggle();
                if($(tag).children('.chevrone').hasClass('plus')){
                    $(tag).children('.chevrone').removeClass('plus').addClass('minus');
                }else{
                    $(tag).children('.chevrone').removeClass('minus').addClass('plus');
                }
                }");
                    ?>
                    <ul id="snap-nav">
                        <li>
                            <?php
                            if (!$sf_user->isAuthenticated()):
                                ?>
                                <a href="<?php echo url_for('@sf_guard_signin') ?>">Вход / регистрация<div class="chevron_right"></div></a>
                            <?php else: ?>
                                <a href="<?php echo url_for('@customer') ?>">Личный кабинет<div class="chevron_right"></div></a>
                            <?php endif; ?>
                        </li>
                        <li><a href="<?php echo url_for('@homepage') ?>">Главная<div class="chevron_right"></div></a></li>
                        <li><a href="<?php echo url_for('@catalogs') ?>"><div class="menuIcon"><img src="/images/mobile/icons/green/catalog.svg" width="24" /></div>Каталог товаров<div class="chevron_right"></div></a></li>
                        <li><a href="<?= url_for('@cart_index') ?>"><div class="menuIcon"><img src="/images/mobile/icons/green/cart.svg" width="24" /></div>Корзина<?= count($productsToCart) > 0 ? (" (" . count($productsToCart) . ")") : "" ?><div class="chevron_right"></div></a></li>
                        <? /* <li><a href=""><div class="menuIcon"><img src="/images/mobile/icons/green/compare.svg" width="24" /></div>Сравнение товаров<div class="chevron_right"></div></a></li> */ ?>
                        <li><a href="<?php echo url_for('@page?slug=akcii-i-bonusy') ?>"><div class="menuIcon"><img src="/images/mobile/icons/green/bonus.svg" width="24" /></div>Акции и Бонусы<div class="chevron_right"></div></a></li>

                        <li><div onclick="toggleSubMenu(this)">Наши магазины<div class="chevrone plus"></div></div>

                            <ul class="subMenu">
                                <li><a href="<?php echo url_for('@page?slug=Adresa_magazinov_v_Moskve') ?>">Магазины «Он и Она» в <br/>Москве<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=magaziny-on-i-ona-v-sankt-peterburge') ?>">Магазины «Он и Она» в <br/>Санкт-Петербурге<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=set-magazinov-dlya-vzroslyh-eros-v-g-rostov-na-donu') ?>">«ЭРОС» в Ростове-на-Дону<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=set-magazinov-dlya-vzroslyh-on-i-ona-v-g-krasnodar') ?>">Магазины «Он и Она» в <br/>Краснодаре<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=set-magazinov-dlya-vzroslyh-on-i-ona-v-krymu') ?>">Магазины «Он и Она» в <br/>Крыму<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=Adresa_magazinov_v_Rossii') ?>">Магазины «Он и Она» в <br/>других городах России<div class="chevron_right"></div></a></li>

                            </ul></li>
                        <li><div onclick="toggleSubMenu(this)">О компании &laquo;Он и Она&raquo;<div class="chevrone plus"></div></div>

                            <ul class="subMenu">
                                <li><a href="<?php echo url_for('@page?slug=kompaniya_onona') ?>">О нас<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=manufacturers') ?>">Оптовые поставки<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=Vakansii') ?>">Вакансии<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=kontakty') ?>">Контакты<div class="chevron_right"></div></a></li>

                            </ul>
                        </li>
                        <li><div onclick="toggleSubMenu(this)">С нами выгодно<div class="chevrone plus"></div></div>
                            <ul class="subMenu">
                                <li><a href="<?php echo url_for('@page?slug=programma-on-i-ona-bonus') ?>">Программа «ОН И ОНА <br/>- Бонус»<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@category?slug=upravlyai-cenoi') ?>">Акция «Управляй ценой!»<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@category?slug=Luchshaya_tsena') ?>">Акция «Лучшая цена!»<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=akcia1') ?>">Акция -30%<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=hochu-druguu-cenu') ?>">Программа «Хочу <br/>другую цену»<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=diskontnaya_karta') ?>">Дисконтные карты<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=podarochnye_sertifikaty') ?>">Подарочные сертификаты<div class="chevron_right"></div></a></li>

                            </ul></li>
                        <li><div onclick="toggleSubMenu(this)">Помощь покупателю<div class="chevrone plus"></div></div>

                            <ul class="subMenu">
                                <li><a href="<?php echo url_for('@page?slug=kak-sdelat-zakaz') ?>">Как сделать заказ<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=dostavka') ?>">Доставка и оплата<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=Garantii') ?>">Гарантии<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=dogovor-oferta') ?>">Договор оферта<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=newprod') ?>">Новые поступления<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=related') ?>">Лидеры продаж<div class="chevron_right"></div></a></li>
                                <li><a href="<?php echo url_for('@page?slug=support') ?>">Обратная связь<div class="chevron_right"></div></a></li>

                            </ul></li>
                        <li><a href="https://onona.ru/sexopedia">Сексопедия</a></li>

                    </ul>
                </div>
            </div>
            <div class="snap-drawer snap-drawer-right"></div>
        </div>
        <div id="content" class="snap-content">
            <div id="toolbar">
                <a href="#" id="open-left"></a>
                <div class="logo"><a href="<?php echo url_for('@homepage') ?>"><img src="/images/mobile/logo_new.png" /></a></div>

                <div class="search">
                    <div class="search-button" id="openSearchButton"></div>
                </div>
                <div class="rightButtons">

                    <?php
                    if (!$sf_user->isAuthenticated()):
                        ?>
                        <a href="<?php echo url_for('@sf_guard_signin') ?>" class="accountBoxButton"></a>
                    <?php else: ?>
                        <a href="<?php echo url_for('@customer') ?>" class="accountBoxButtonLogin"></a>
                    <?php endif; ?>

                    <?php
                    /* <a href="<?= url_for('@cart_index') ?>" class="cartButton"<?php
                      $productsToCart = $sf_context->getUser()->getAttribute('products_to_cart');
                      $productsToCart = $productsToCart != '' ? unserialize($productsToCart) : '';
                      if(count($productsToCart)>0){?> style="background: url('data:image/svg+xml,%3C%3Fxml%20version%3D%221.0%22%20standalone%3D%22no%22%3F%3E%0A%3Csvg%0A%20%20%20xmlns%3Adc%3D%22http%3A%2F%2Fpurl.org%2Fdc%2Felements%2F1.1%2F%22%0A%20%20%20xmlns%3Acc%3D%22http%3A%2F%2Fcreativecommons.org%2Fns%23%22%0A%20%20%20xmlns%3Ardf%3D%22http%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%22%0A%20%20%20xmlns%3Asvg%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%0A%20%20%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%0A%20%20%20xmlns%3Axlink%3D%22http%3A%2F%2Fwww.w3.org%2F1999%2Fxlink%22%0A%20%20%20xmlns%3Asodipodi%3D%22http%3A%2F%2Fsodipodi.sourceforge.net%2FDTD%2Fsodipodi-0.dtd%22%0A%20%20%20xmlns%3Ainkscape%3D%22http%3A%2F%2Fwww.inkscape.org%2Fnamespaces%2Finkscape%22%0A%20%20%20fill%3D%22%237f7f7f%22%0A%20%20%20height%3D%2228%22%0A%20%20%20viewBox%3D%220%200%2024%2024%22%0A%20%20%20width%3D%2228%22%0A%20%20%20id%3D%22svg2%22%0A%20%20%20version%3D%221.1%22%0A%20%20%20inkscape%3Aversion%3D%220.48.4%20r9939%22%0A%20%20%20sodipodi%3Adocname%3D%22ic_shopping_basket_28px.svg%22%3E%0A%20%20%3Cmetadata%0A%20%20%20%20%20id%3D%22metadata12%22%3E%0A%20%20%20%20%3Crdf%3ARDF%3E%0A%20%20%20%20%20%20%3Ccc%3AWork%0A%20%20%20%20%20%20%20%20%20rdf%3Aabout%3D%22%22%3E%0A%20%20%20%20%20%20%20%20%3Cdc%3Aformat%3Eimage%2Fsvg%2Bxml%3C%2Fdc%3Aformat%3E%0A%20%20%20%20%20%20%20%20%3Cdc%3Atype%0A%20%20%20%20%20%20%20%20%20%20%20rdf%3Aresource%3D%22http%3A%2F%2Fpurl.org%2Fdc%2Fdcmitype%2FStillImage%22%20%2F%3E%0A%20%20%20%20%20%20%20%20%3Cdc%3Atitle%3E%3C%2Fdc%3Atitle%3E%0A%20%20%20%20%20%20%3C%2Fcc%3AWork%3E%0A%20%20%20%20%3C%2Frdf%3ARDF%3E%0A%20%20%3C%2Fmetadata%3E%0A%20%20%3Cdefs%0A%20%20%20%20%20id%3D%22defs10%22%3E%0A%20%20%20%20%3ClinearGradient%0A%20%20%20%20%20%20%20id%3D%22linearGradient3787%22%3E%0A%20%20%20%20%20%20%3Cstop%0A%20%20%20%20%20%20%20%20%20style%3D%22stop-color%3A%23434342%3Bstop-opacity%3A1%3B%22%0A%20%20%20%20%20%20%20%20%20offset%3D%220%22%0A%20%20%20%20%20%20%20%20%20id%3D%22stop3789%22%20%2F%3E%0A%20%20%20%20%20%20%3Cstop%0A%20%20%20%20%20%20%20%20%20id%3D%22stop3793%22%0A%20%20%20%20%20%20%20%20%20offset%3D%220.5%22%0A%20%20%20%20%20%20%20%20%20style%3D%22stop-color%3A%23434342%3Bstop-opacity%3A1%3B%22%20%2F%3E%0A%20%20%20%20%20%20%3Cstop%0A%20%20%20%20%20%20%20%20%20style%3D%22stop-color%3A%23a60e15%3Bstop-opacity%3A1%3B%22%0A%20%20%20%20%20%20%20%20%20offset%3D%220.5%22%0A%20%20%20%20%20%20%20%20%20id%3D%22stop3795%22%20%2F%3E%0A%20%20%20%20%20%20%3Cstop%0A%20%20%20%20%20%20%20%20%20style%3D%22stop-color%3A%23a60e15%3Bstop-opacity%3A1%3B%22%0A%20%20%20%20%20%20%20%20%20offset%3D%221%22%0A%20%20%20%20%20%20%20%20%20id%3D%22stop3791%22%20%2F%3E%0A%20%20%20%20%3C%2FlinearGradient%3E%0A%20%20%20%20%3ClinearGradient%0A%20%20%20%20%20%20%20inkscape%3Acollect%3D%22always%22%0A%20%20%20%20%20%20%20xlink%3Ahref%3D%22%23linearGradient3787%22%0A%20%20%20%20%20%20%20id%3D%22linearGradient3779%22%0A%20%20%20%20%20%20%20x1%3D%221%22%0A%20%20%20%20%20%20%20y1%3D%2211.51%22%0A%20%20%20%20%20%20%20x2%3D%2223%22%0A%20%20%20%20%20%20%20y2%3D%2211.51%22%0A%20%20%20%20%20%20%20gradientUnits%3D%22userSpaceOnUse%22%0A%20%20%20%20%20%20%20spreadMethod%3D%22pad%22%20%2F%3E%0A%20%20%3C%2Fdefs%3E%0A%20%20%3Csodipodi%3Anamedview%0A%20%20%20%20%20pagecolor%3D%22%23ffffff%22%0A%20%20%20%20%20bordercolor%3D%22%23666666%22%0A%20%20%20%20%20borderopacity%3D%221%22%0A%20%20%20%20%20objecttolerance%3D%2210%22%0A%20%20%20%20%20gridtolerance%3D%2210%22%0A%20%20%20%20%20guidetolerance%3D%2210%22%0A%20%20%20%20%20inkscape%3Apageopacity%3D%220%22%0A%20%20%20%20%20inkscape%3Apageshadow%3D%222%22%0A%20%20%20%20%20inkscape%3Awindow-width%3D%221855%22%0A%20%20%20%20%20inkscape%3Awindow-height%3D%221056%22%0A%20%20%20%20%20id%3D%22namedview8%22%0A%20%20%20%20%20showgrid%3D%22false%22%0A%20%20%20%20%20inkscape%3Azoom%3D%228.4285714%22%0A%20%20%20%20%20inkscape%3Acx%3D%22-8.9576271%22%0A%20%20%20%20%20inkscape%3Acy%3D%2214%22%0A%20%20%20%20%20inkscape%3Awindow-x%3D%221985%22%0A%20%20%20%20%20inkscape%3Awindow-y%3D%2224%22%0A%20%20%20%20%20inkscape%3Awindow-maximized%3D%221%22%0A%20%20%20%20%20inkscape%3Acurrent-layer%3D%22svg2%22%20%2F%3E%0A%20%20%3Cpath%0A%20%20%20%20%20d%3D%22M0%200h24v24H0z%22%0A%20%20%20%20%20fill%3D%22none%22%0A%20%20%20%20%20id%3D%22path4%22%20%2F%3E%0A%20%20%3Cpath%0A%20%20%20%20%20d%3D%22M17.21%209l-4.38-6.56c-.19-.28-.51-.42-.83-.42-.32%200-.64.14-.83.43L6.79%209H2c-.55%200-1%20.45-1%201%200%20.09.01.18.04.27l2.54%209.27c.23.84%201%201.46%201.92%201.46h13c.92%200%201.69-.62%201.93-1.46l2.54-9.27L23%2010c0-.55-.45-1-1-1h-4.79zM9%209l3-4.4L15%209H9zm3%208c-1.1%200-2-.9-2-2s.9-2%202-2%202%20.9%202%202-.9%202-2%202z%22%0A%20%20%20%20%20id%3D%22path6%22%0A%20%20%20%20%20style%3D%22fill%3Aurl(%23linearGradient3779)%3Bfill-opacity%3A1.0%22%20%2F%3E%0A%20%20%3Cpath%0A%20%20%20%20%20sodipodi%3Atype%3D%22arc%22%0A%20%20%20%20%20style%3D%22fill%3A%23a60e15%3Bfill-opacity%3A1%3Bstroke%3A%23ffffff%3Bstroke-opacity%3A1%3Bstroke-width%3A0.50000000000000001%3Bstroke-miterlimit%3A4%3Bstroke-dasharray%3Anone%22%0A%20%20%20%20%20id%3D%22path3799%22%0A%20%20%20%20%20sodipodi%3Acx%3D%2220.822035%22%0A%20%20%20%20%20sodipodi%3Acy%3D%228.9576273%22%0A%20%20%20%20%20sodipodi%3Arx%3D%226.8220339%22%0A%20%20%20%20%20sodipodi%3Ary%3D%226.8220339%22%0A%20%20%20%20%20d%3D%22m%2027.644069%2C8.9576273%20a%206.8220339%2C6.8220339%200%201%201%20-13.644068%2C0%206.8220339%2C6.8220339%200%201%201%2013.644068%2C0%20z%22%0A%20%20%20%20%20transform%3D%22scale(0.85714286%2C0.85714286)%22%20%2F%3E%0A%20%20%3Ctext%0A%20%20%20%20%20xml%3Aspace%3D%22preserve%22%0A%20%20%20%20%20style%3D%22font-size%3A34.2857132px%3Bfont-style%3Anormal%3Bfont-weight%3Anormal%3Btext-align%3Acenter%3Bline-height%3A125%25%3Bletter-spacing%3A0px%3Bword-spacing%3A0px%3Btext-anchor%3Amiddle%3Bfill%3A%23ffffff%3Bfill-opacity%3A1%3Bstroke%3Anone%3Bfont-family%3ASans%22%0A%20%20%20%20%20x%3D%2218.051615%22%0A%20%20%20%20%20y%3D%2210.677966%22%0A%20%20%20%20%20id%3D%22text3801%22%0A%20%20%20%20%20sodipodi%3Alinespacing%3D%22125%25%22%3E%3Ctspan%0A%20%20%20%20%20%20%20sodipodi%3Arole%3D%22line%22%0A%20%20%20%20%20%20%20id%3D%22tspan3803%22%0A%20%20%20%20%20%20%20x%3D%2218.051615%22%0A%20%20%20%20%20%20%20y%3D%2210.677966%22%0A%20%20%20%20%20%20%20style%3D%22font-size%3A8px%3Btext-align%3Acenter%3Bline-height%3A125%25%3Btext-anchor%3Amiddle%3Bfill%3A%23ffffff%3Bfill-opacity%3A1%22%3E<?=count($productsToCart)?>%3C%2Ftspan%3E%3C%2Ftext%3E%0A%3C%2Fsvg%3E')  center center no-repeat;"<?php } ?>></a>
                     */
                    ?>
                    <a href="<?= url_for('@cart_index') ?>" class="cartButton"><svg
                            xmlns:dc="http://purl.org/dc/elements/1.1/"
                            xmlns:cc="http://creativecommons.org/ns#"
                            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                            xmlns:svg="http://www.w3.org/2000/svg"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                            xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                            fill="#7f7f7f"
                            height="28"
                            viewBox="0 0 24 24"
                            width="28"
                            version="1.1"
                            inkscape:version="0.48.4 r9939"
                            sodipodi:docname="ic_shopping_basket_product_28px.svg"
                            id="svgCartButton"
                            <?= (count($productsToCart) > 0) ? ' style="display:block;"' : "" ?>>
                            <metadata
                                id="metadata12">
                                <rdf:RDF>
                                    <cc:Work
                                        rdf:about="">
                                        <dc:format>image/svg+xml</dc:format>
                                        <dc:type
                                            rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
                                    </cc:Work>
                                </rdf:RDF>
                            </metadata>
                            <defs
                                id="defs10">
                                <linearGradient
                                    id="linearGradient3787">
                                    <stop
                                        style="stop-color:#434342;stop-opacity:1;"
                                        offset="0"
                                        id="stop3789" />
                                    <stop
                                        id="stop3793"
                                        offset="0.5"
                                        style="stop-color:#434342;stop-opacity:1;" />
                                    <stop
                                        style="stop-color:#a60e15;stop-opacity:1;"
                                        offset="0.5"
                                        id="stop3795" />
                                    <stop
                                        style="stop-color:#a60e15;stop-opacity:1;"
                                        offset="1"
                                        id="stop3791" />
                                </linearGradient>
                                <linearGradient
                                    inkscape:collect="always"
                                    xlink:href="#linearGradient3787"
                                    id="linearGradient3779"
                                    x1="1"
                                    y1="11.51"
                                    x2="23"
                                    y2="11.51"
                                    gradientUnits="userSpaceOnUse"
                                    spreadMethod="pad" />
                            </defs>
                            <sodipodi:namedview
                                pagecolor="#ffffff"
                                bordercolor="#666666"
                                borderopacity="1"
                                objecttolerance="10"
                                gridtolerance="10"
                                guidetolerance="10"
                                inkscape:pageopacity="0"
                                inkscape:pageshadow="2"
                                inkscape:window-width="1855"
                                inkscape:window-height="1056"
                                id="namedview8"
                                showgrid="false"
                                inkscape:zoom="8.4285714"
                                inkscape:cx="-9.0762712"
                                inkscape:cy="14"
                                inkscape:window-x="1985"
                                inkscape:window-y="24"
                                inkscape:window-maximized="1"
                                inkscape:current-layer="svg2" />
                            <path
                                d="M0 0h24v24H0z"
                                fill="none"
                                id="path4" />
                            <path
                                d="M17.21 9l-4.38-6.56c-.19-.28-.51-.42-.83-.42-.32 0-.64.14-.83.43L6.79 9H2c-.55 0-1 .45-1 1 0 .09.01.18.04.27l2.54 9.27c.23.84 1 1.46 1.92 1.46h13c.92 0 1.69-.62 1.93-1.46l2.54-9.27L23 10c0-.55-.45-1-1-1h-4.79zM9 9l3-4.4L15 9H9zm3 8c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"
                                id="path6"
                                style="fill:url(#linearGradient3779);fill-opacity:1.0" />
                            <path
                                sodipodi:type="arc"
                                style="fill:#a60e15;fill-opacity:1;stroke:#ffffff;stroke-opacity:1;stroke-width:0.5;stroke-miterlimit:4;stroke-dasharray:none"
                                id="path3799"
                                sodipodi:cx="20.822035"
                                sodipodi:cy="8.9576273"
                                sodipodi:rx="6.8220339"
                                sodipodi:ry="6.8220339"
                                d="m 27.644069,8.9576273 a 6.8220339,6.8220339 0 1 1 -13.644068,0 6.8220339,6.8220339 0 1 1 13.644068,0 z"
                                transform="scale(0.85714286,0.85714286)" />
                            <text
                                xml:space="preserve"
                                style="font-size:7.71428571px;font-style:normal;font-weight:normal;text-align:center;line-height:125%;letter-spacing:0px;word-spacing:0px;text-anchor:middle;fill:#ffffff;fill-opacity:1;stroke:none;font-family:Sans"
                                x="18.051615"
                                y="10.677966"
                                id="text3801"
                                sodipodi:linespacing="125%"><tspan
                                    sodipodi:role="line"
                                    x="18.051615"
                                    y="10.677966"
                                    style="font-size:7.71428571px;text-align:center;line-height:125%;text-anchor:middle;fill:#ffffff;fill-opacity:1" id="svgCartButtonCount"><?= (count($productsToCart)) ?></tspan></text>
                        </svg></a>
                </div>
            </div>
            <?php
            if (substr_count($sf_request->getReferer(), "onona.ru")) {
                ?>
                <a href="<?= $sf_request->getReferer() ?>" id="aButtonPrev">
                    <div id="buttonPrev">
                        <div class="chevron_right"></div>
                        Назад
                    </div>
                </a>
                <?php
            }
            ?>
            <div id="contentWrapper">
                <?php echo $sf_content ?>
            </div>
            <div style="clear: both;"></div>
            <div id="footer"<?= $sf_user->isAuthenticated() ? ' class="Authenticated"' : '' ?>>
                <?php
                if (!$sf_user->isAuthenticated()):
                    ?>
                    <div class="firstvisit">
                        <?php
                        JSInPages("function validateEmailRassilka() {
                    var email = $('input[name=user_mail]');
                    var emailInfo = $('.statusValidMail');
                    //personal_accept
                    if (!$('#accept-form').is(':checked')){
                      email.addClass('error');
                      emailInfo.html(' <br>Необходимо принять пользовательское соглашение!');
                      emailInfo.addClass('error');
                      return false;
                    }
                    //testing regular expression
                    var a = $('input[name=user_mail]').val();
                    $('input[name=user_mail]').val($.trim(a));
                    var a = $('input[name=user_mail]').val();
                    var filter = /^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/;
                    //if it's valid email
                    if (filter.test(a)) {
                        $('#firstVisitYesNews').submit()
                        return true;
                    }
                    //if it's NOT valid
                    else {
                        /*email.addClass('error');
                        emailInfo.html(' <br>Введите реальную почту.');
                        emailInfo.addClass('error');
                        $.post('/logvalidmail', {
                            mail: a,
                            form: 'rassilka'
                        });
                        return false;*/
                    }
                }");
                        ?>
                        <div class="banner">
                            <img src="/images/mobile/footer-firstvisit.png" />
                        </div>
                        <div class="form">
                            <form action="/firstvisit/yes" id="firstVisitYesNews" method="POST">
                                <div class="left">
                                    <input type="hidden" name="user_sex" />
                                    <input type="text" value="Имя" name="user_name" onclick="if ($(this).val() == 'Имя')
                                                    $(this).val('');" />
                                    <input type="text" value="E-mail" name="user_mail" onclick="if ($(this).val() == 'E-mail')
                                                    $(this).val('');" />
                                </div>
                                <div class="right">
                                    <div class="top">ПОДПИСАТЬСЯ:</div>
                                    <div onclick="validateEmailRassilka('m')" class="buttonSignUpMale">МУЖ.</div>
                                    <div onclick="validateEmailRassilka('g')" class="buttonSignUpFemale">ЖЕН.</div>

                                </div>

                                <div class="footer-personal">
                                  <input id='accept-form' type='checkbox'>
                                  <label for='accept-form' >Я принимаю <a href='/personal_accept' target='_blank'>Пользовательское соглашение</a></label>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="contact">
                    <div class="top">
                        Профессиональная консультация
                    </div>

                    <div class="phone">
                        <a href="tel:<?=preg_replace('/[^\d+]/', '', csSettings::get('phone1'))?>"><?=csSettings::get('phone1')?></a>
                    </div>
                    <div class="commentPhone">
                        по России (бесплатно)
                    </div>

                    <div class="phone">
                        <a href="tel:<?=preg_replace('/[^\d+]/', '', csSettings::get('phone2'))?>"><?=csSettings::get('phone2')?></a>
                    </div>
                    <div class="commentPhone">
                        по Москве
                    </div>
                </div>

                <div class="socialNetwork">
                    <div class="top">
                        Присоединяйтесь к нам:
                    </div>
                    <ul class="socialNetwork-list">
                        <li>
                            <a href="http://www.youtube.com/channel/UCrZ-3sU3RtG5g1YhKF8JJyg?sub_confirmation=1" target="_blank">
                                <span class="img-holder">
                                    <img alt="YouTube" height="96" src="/newdis/images/youtube.png" width="37" />
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="https://vk.com/sex_shop_onona" target="_blank">
                                <span class="img-holder">
                                    <img alt="image description" height="96" src="/newdis/images/img18.png" width="34" />
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="https://instagram.com/onona.ru/" target="_blank">
                                <span class="img-holder">
                                    <img alt="Инстаграм Он и Она" src="/uploads/assets/images/insta.png" />
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/OnOnaTV" target="_blank">
                                <span class="img-holder">
                                    <img alt="image description" height="96" src="/newdis/images/twitter.png" width="34" />
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>


                <div class="age18">
                    <div class="icon">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 36 31" enable-background="new 0 0 36 31" xml:space="preserve" height="50" width="50">
                            <path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" stroke="#C41718" stroke-width="3" stroke-miterlimit="10" d="
                                  M17.5,1.8c7.6,0,13.7,6.1,13.7,13.7s-6.1,13.7-13.7,13.7S3.8,23.1,3.8,15.5S9.9,1.8,17.5,1.8z"/>
                            <text transform="matrix(1 0 0 1 7.2505 20.2891)" fill="#C41718" font-family="'Tahoma'" font-size="13">18+</text>
                        </svg>
                    </div>
                    <div class="text">
                        Сайт предназначен для лиц, <br />достигших 18-ти летнего возраста.
                    </div>
                </div>


                <div class="fullVersion">
                    <a href="https://onona.ru/?fullVersion=1">
                        <div class="button">
                            Перейти на полную версию сайта
                        </div>
                    </a>
                </div>


                <div class="bottom">
                    <div class="copyright">
                        © 2007-2015 «Секс шоп Он и Она»
                    </div>
                    <?
                    if (@$partnerRef) {
                        if (@$partner) {
                            echo '<div class="partnerId">Ваш ID: <div>' . $partnerRef->getId() . '-' . $partner->getId() . '</div></div>';
                        } else {
                            echo '<div class="partnerId">Ваш ID: <div>' . $partnerRef->getId() . '</div></div>';
                        }
                    } else {
                        echo '<div class="partnerId">Ваш ID: <div>777</div></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <link rel="stylesheet" type="text/css" href="/css/mobile/style.css?v=6" />
        <script type="text/javascript" src="/js/mobile/allFooterJSLibrarys.js?v=2"></script>
        <script type="text/javascript" src="/js/mobile/allFooterJS.js?v=3"></script>
        <script type="text/javascript" src="/js/rrPartner.js"></script>
        <?php if ($sf_user->isAuthenticated()): ?>
            <script type="text/javascript">
                rrApiOnReady.push(function () {
                    rrApi.setEmail("<?= $sf_user->getGuardUser()->getEmailAddress() ?>");
                });
            </script>
        <?php endif; ?>
        <script type="text/javascript">
<?php include_slot("JSInPages") ?>
//alert(screen.width);

                                        var _gaq = _gaq || [];
                                        _gaq.push(['_setAccount', 'UA-29176584-1']);
                                        _gaq.push(['_addOrganic', 'images.yandex.ru', 'q', true]);
                                        _gaq.push(['_addOrganic', 'blogsearch.google.ru', 'q', true]);
                                        _gaq.push(['_addOrganic', 'blogs.yandex.ru', 'text', true]);
                                        _gaq.push(['_addOrganic', 'nigma.ru', 's']);
                                        _gaq.push(['_addOrganic', 'webalta.ru', 'q']);
                                        _gaq.push(['_addOrganic', 'aport.ru', 'r']);
                                        _gaq.push(['_addOrganic', 'poisk.ru', 'text']);
                                        _gaq.push(['_addOrganic', 'km.ru', 'sq']);
                                        _gaq.push(['_addOrganic', 'liveinternet.ru', 'ask']);
                                        _gaq.push(['_addOrganic', 'quintura.ru', 'request']);
                                        _gaq.push(['_addOrganic', 'search.qip.ru', 'query', true]);
                                        _gaq.push(['_addOrganic', 'gde.ru', 'keywords']);
                                        _gaq.push(['_addOrganic', 'gogo.ru', 'q']);
                                        _gaq.push(['_addOrganic', 'ru.yahoo.com', 'p', true]);
                                        _gaq.push(['_addOrganic', 'price.ru', 'pnam']);
                                        _gaq.push(['_addOrganic', 'tyndex.ru', 'pnam']);
                                        _gaq.push(['_addOrganic', 'torg.mail.ru', 'q', true]);
                                        _gaq.push(['_addOrganic', 'tiu.ru', 'query']);
                                        _gaq.push(['_addOrganic', 'tech2u.ru', 'text']);
                                        _gaq.push(['_addOrganic', 'akavita.by', 'z']);
                                        _gaq.push(['_addOrganic', 'tut.by', 'query']);
                                        _gaq.push(['_addOrganic', 'all.by', 'query']);
                                        _gaq.push(['_addOrganic', 'meta.ua', 'q']);
                                        _gaq.push(['_addOrganic', 'bigmir.net', 'q']);
                                        _gaq.push(['_addOrganic', 'i.ua', 'q']);
                                        _gaq.push(['_addOrganic', 'online.ua', 'q']);
                                        _gaq.push(['_addOrganic', 'a.ua', 's']);
                                        _gaq.push(['_addOrganic', 'ukr.net', 'search_query']);
                                        _gaq.push(['_addOrganic', 'search.ua', 'query']);
                                        _gaq.push(['_trackPageview']);

<? include_slot('googleCart') ?>

                                        (function () {
                                            var ga = document.createElement('script');
                                            ga.type = 'text/javascript';
                                            ga.async = true;
                                            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                                            var s = document.getElementsByTagName('script')[0];
                                            s.parentNode.insertBefore(ga, s);
                                        })();





                                        var yaParams = {/*Здесь параметры визита*/};
                                        (function (d, w, c) {
                                            (w[c] = w[c] || []).push(function () {
                                                try {
                                                    w.yaCounter144683 = new Ya.Metrika({id: 144683, enableAll: true, webvisor: true, params: window.yaParams || {}});
                                                } catch (e) {
                                                }
                                            });

                                            var n = d.getElementsByTagName("script")[0],
                                                    s = d.createElement("script"),
                                                    f = function () {
                                                        n.parentNode.insertBefore(s, n);
                                                    };
                                            s.type = "text/javascript";
                                            s.async = true;
                                            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

                                            if (w.opera == "[object Opera]") {
                                                d.addEventListener("DOMContentLoaded", f);
                                            } else {
                                                f();
                                            }
                                        })(document, window, "yandex_metrika_callbacks");
        </script>

        <noscript><div><img src="//mc.yandex.ru/watch/144683" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<script type="text/javascript">(function(w,doc) {
if (!w.__utlWdgt ) {
w.__utlWdgt = true;
var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
s.src = ('https:' == w.location.protocol ? 'https' : 'http') + '://w.uptolike.com/widgets/v1/uptolike.js';
var h=d[g]('body')[0];
h.appendChild(s);
}})(window,document);
</script>
<div data-background-alpha="0.0" data-buttons-color="#ffffff" data-counter-background-color="#ffffff" data-share-counter-size="12" data-top-button="false" data-share-counter-type="disable" data-share-style="11" data-mode="share" data-follow-vk="sex_shop_onona" data-like-text-enable="false" data-follow-tw="OnOnaTV" data-hover-effect="rotate-cw" data-mobile-view="true" data-icon-color="#ffffff" data-orientation="fixed-left" data-text-color="#000000" data-share-shape="round" data-sn-ids="vk.ok.mr.fb.tw.gp." data-share-size="30" data-background-color="#ffffff" data-preview-mobile="false" data-mobile-sn-ids="fb.ok.vk.tw.wh." data-pid="1559763" data-counter-background-alpha="1.0" data-follow-in="onona.ru" data-following-enable="true" data-exclude-show-more="true" data-follow-yt="channel/UCrZ-3sU3RtG5g1YhKF8JJyg?sub_confirmation=1" data-selection-enable="false" data-follow-fb="groups/onona" class="uptolike-buttons" ></div>

    </body>
</html>
