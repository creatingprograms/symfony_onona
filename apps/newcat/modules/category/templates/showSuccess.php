<?php
slot('personalRecomendationCategoryId', array($category->getId(), 1));
$timerAll = sfTimerManager::getTimer('Templates: Весь шаблон');

$products = $pager->getResults();

$timer = sfTimerManager::getTimer('Templates: Передача meta тегов');
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
slot('canonicalSlugCategory', $category->getSlug() . $canonDop);
slot('leftBlock', true);
slot('metaTitle', $category->getTitle() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "") : $category->getTitle() . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "") );
slot('metaKeywords', $category->getKeywords() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getKeywords());
slot('metaDescription', $category->getDescription() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getDescription());
$timer->addTime();

$products_old = unserialize($sf_user->getAttribute('products_to_cart'));
if (is_array($products_old))
    foreach ($products_old as $key => $product) {
        $arrayProdCart[] = $product['productId'];
    }

$products_desire = $sf_user->getAttribute('products_to_desire');
$products_desire = $products_desire != '' ? unserialize($products_desire) : '';

$products_compare = $sf_user->getAttribute('products_to_compare');
$products_compare = $products_compare != '' ? unserialize($products_compare) : '';
?>
<?
$agentIsMobile = false;
$user_agent = strtolower(getenv('HTTP_USER_AGENT'));
$accept = strtolower(getenv('HTTP_ACCEPT'));

if ((strpos($accept, 'text/vnd.wap.wml') !== false) ||
        (strpos($accept, 'application/vnd.wap.xhtml+xml') !== false)) {
    $agentIsMobile = true; // Мобильный браузер обнаружен по HTTP-заголовкам
}

if (isset($_SERVER['HTTP_X_WAP_PROFILE']) ||
        isset($_SERVER['HTTP_PROFILE'])) {
    $agentIsMobile = true; // Мобильный браузер обнаружен по установкам сервера
}

if (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|' .
                'wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|' .
                'lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|' .
                'mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|' .
                'm881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|' .
                'r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|' .
                'i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|' .
                'htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|' .
                'sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|' .
                'p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|' .
                '_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|' .
                's800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|' .
                'd736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |' .
                'sonyericsson|samsung|240x|x320vx10|nokia|sony cmd|motorola|' .
                'up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|' .
                'pocket|kindle|mobile|psp|treo|android|iphone|ipod|webos|wp7|wp8|' .
                'fennec|blackberry|htc_|opera m|windowsphone)/', $user_agent)) {
    $agentIsMobile = true; // Мобильный браузер обнаружен по сигнатуре User Agent
}

if (in_array(substr($user_agent, 0, 4), Array("1207", "3gso", "4thp", "501i", "502i", "503i", "504i", "505i", "506i",
            "6310", "6590", "770s", "802s", "a wa", "abac", "acer", "acoo", "acs-",
            "aiko", "airn", "alav", "alca", "alco", "amoi", "anex", "anny", "anyw",
            "aptu", "arch", "argo", "aste", "asus", "attw", "au-m", "audi", "aur ",
            "aus ", "avan", "beck", "bell", "benq", "bilb", "bird", "blac", "blaz",
            "brew", "brvw", "bumb", "bw-n", "bw-u", "c55/", "capi", "ccwa", "cdm-",
            "cell", "chtm", "cldc", "cmd-", "cond", "craw", "dait", "dall", "dang",
            "dbte", "dc-s", "devi", "dica", "dmob", "doco", "dopo", "ds-d", "ds12",
            "el49", "elai", "eml2", "emul", "eric", "erk0", "esl8", "ez40", "ez60",
            "ez70", "ezos", "ezwa", "ezze", "fake", "fetc", "fly-", "fly_", "g-mo",
            "g1 u", "g560", "gene", "gf-5", "go.w", "good", "grad", "grun", "haie",
            "hcit", "hd-m", "hd-p", "hd-t", "hei-", "hiba", "hipt", "hita", "hp i",
            "hpip", "hs-c", "htc ", "htc-", "htc_", "htca", "htcg", "htcp", "htcs",
            "htct", "http", "huaw", "hutc", "i-20", "i-go", "i-ma", "i230", "iac",
            "iac-", "iac/", "ibro", "idea", "ig01", "ikom", "im1k", "inno", "ipaq",
            "iris", "jata", "java", "jbro", "jemu", "jigs", "kddi", "keji", "kgt",
            "kgt/", "klon", "kpt ", "kwc-", "kyoc", "kyok", "leno", "lexi", "lg g",
            "lg-a", "lg-b", "lg-c", "lg-d", "lg-f", "lg-g", "lg-k", "lg-l", "lg-m",
            "lg-o", "lg-p", "lg-s", "lg-t", "lg-u", "lg-w", "lg/k", "lg/l", "lg/u",
            "lg50", "lg54", "lge-", "lge/", "libw", "lynx", "m-cr", "m1-w", "m3ga",
            "m50/", "mate", "maui", "maxo", "mc01", "mc21", "mcca", "medi", "merc",
            "meri", "midp", "mio8", "mioa", "mits", "mmef", "mo01", "mo02", "mobi",
            "mode", "modo", "mot ", "mot-", "moto", "motv", "mozz", "mt50", "mtp1",
            "mtv ", "mwbp", "mywa", "n100", "n101", "n102", "n202", "n203", "n300",
            "n302", "n500", "n502", "n505", "n700", "n701", "n710", "nec-", "nem-",
            "neon", "netf", "newg", "newt", "nok6", "noki", "nzph", "o2 x", "o2-x",
            "o2im", "opti", "opwv", "oran", "owg1", "p800", "palm", "pana", "pand",
            "pant", "pdxg", "pg-1", "pg-2", "pg-3", "pg-6", "pg-8", "pg-c", "pg13",
            "phil", "pire", "play", "pluc", "pn-2", "pock", "port", "pose", "prox",
            "psio", "pt-g", "qa-a", "qc-2", "qc-3", "qc-5", "qc-7", "qc07", "qc12",
            "qc21", "qc32", "qc60", "qci-", "qtek", "qwap", "r380", "r600", "raks",
            "rim9", "rove", "rozo", "s55/", "sage", "sama", "samm", "sams", "sany",
            "sava", "sc01", "sch-", "scoo", "scp-", "sdk/", "se47", "sec-", "sec0",
            "sec1", "semc", "send", "seri", "sgh-", "shar", "sie-", "siem", "sk-0",
            "sl45", "slid", "smal", "smar", "smb3", "smit", "smt5", "soft", "sony",
            "sp01", "sph-", "spv ", "spv-", "sy01", "symb", "t-mo", "t218", "t250",
            "t600", "t610", "t618", "tagt", "talk", "tcl-", "tdg-", "teli", "telm",
            "tim-", "topl", "tosh", "treo", "ts70", "tsm-", "tsm3", "tsm5", "tx-9",
            "up.b", "upg1", "upsi", "utst", "v400", "v750", "veri", "virg", "vite",
            "vk-v", "vk40", "vk50", "vk52", "vk53", "vm40", "voda", "vulc", "vx52",
            "vx53", "vx60", "vx61", "vx70", "vx80", "vx81", "vx83", "vx85", "vx98",
            "w3c ", "w3c-", "wap-", "wapa", "wapi", "wapj", "wapm", "wapp", "wapr",
            "waps", "wapt", "wapu", "wapv", "wapy", "webc", "whit", "wig ", "winc",
            "winw", "wmlb", "wonu", "x700", "xda-", "xda2", "xdag", "yas-", "your",
            "zeto", "zte-"))) {
    $agentIsMobile = true; // Мобильный браузер обнаружен по сигнатуре User Agent
}
?>
<script type="text/javascript">

    rrApiOnReady.push(function () {

        try {
            rrApi.categoryView(<?= $category->getId() ?>);
        } catch (e) {
        }

    })

</script>
<script>
    hs.graphicsDir = '/js/highslide/graphics/';
    hs.align = 'center';
    hs.transitions = ['expand', 'crossfade'];
    hs.outlineType = 'rounded-white';
    hs.fadeInOut = true;
    hs.dimmingOpacity = 0.75;
</script>
<script>/*
    $(document).ready(function () {
        $("div.notcount").hover(function () {
            $(this).stop().animate({"opacity": 1});
        }, function () {
            $(this).stop().animate({"opacity": 0.3});
        });

    });*/
</script>

<!--<script src="/js/liTip_newcat.js"></script>-->
<? $timer = sfTimerManager::getTimer('Templates: Вывод хлебных крошек'); ?>
<ul class="breadcrumbs" style="
    width: 530px;
    ">
    <li><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title">Секс-шоп главная</span></a></div></li>
    <?php
    if ($category->getParentsId() != "") {
        echo '
    <li><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/category/' . $category->getParent()->getSlug() . '" itemprop="url"><span itemprop="title">' . $category->getParent()->getName() . '</span></a></div></li>';
    }
    ?>
    <li><?php echo $category->getName() ?></li>
</ul>
<?
$timer->addTime();

$timer = sfTimerManager::getTimer('Templates: Вывод шапки категории');
?>
<h1 class="title"><?= $category->getH1() != "" ? stripslashes($category->getH1()) : stripslashes($category->getName()) ?></h1>

<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
<div class="yashare-wrapper" style="background: none;  width: 200px; height: 22px; margin-left:-6px;margin-top:-6px;">
    <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
         data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

         ></div>
</div>
<?
$timer->addTime();
?>

<?
$timer = sfTimerManager::getTimer('Templates: Вывод таблицы с набором сортировок');


if ($sf_user->isAuthenticated()):
    $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_id=?", sfContext::getInstance()->getUser()->getGuardUser()->getId())->addWhere("category_id=?", $category->getId())->addWhere("is_enabled='1'")->fetchOne();
    if ($notificationCategory) {
        ?>
        <div class="NotificationCategory">
            <div style='color: #c3060e;margin-bottom: 10px;'>Вы подписаны на уведомления о новых поступлениях товаров в этом разделе. <a href="/delnotification/<?= $category->getId() ?>" onclick=" $('.NotificationCategory').load($(this).attr('href'));
                return false;">Отписаться</a></div>
        </div><?
    } else {
        ?><div class="NotificationCategory">
            <div style="float: left;color: #c3060e;padding-top: 5px; margin-right: 10px;"><input type="checkbox" id="checkboxNotify">Я хочу подписаться на уведомления о новых поступлениях товаров в этом разделе:</div>
            <div style="float: left;" class="silverButtonAddToNotification" onClick='if (!$("#checkboxNotify").prop("checked")) {
                        return false;
                    }
                    $(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= $category->getId() ?>});'></div>

        </div><?
    }
else:
    ?><div class="NotificationCategory">
        <div style="float: left;color: #c3060e;width: 360px;    position: relative;top: -4px;">Подпишитесь на уведомления о новых поступлениях товаров в этом разделе:</div>
        <div style="float: right;">
            <div style="float: left;"><input style="border: 1px solid #b2b2b2; height: 26px; background-color: #FFF;width: 250px;padding-left: 10px;" id="notificationMail" value="Введите свой e-mail" onClick="$(this).val('');" /></div>
            <div style="float: left;" class="silverButtonAddToNotification" onClick='$(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= $category->getId() ?>, emailAddNotification: $("#notificationMail").val()});'></div>
        </div>
    </div><?
endif;
?>

<?
//If ($sf_request->getParameter('page', 1) == 1) {
//    /* if ($category->getLastupdaterrproductid() < (time() - 3600)) {
//      $rrContent = file_get_contents('https://api.retailrocket.ru/api/1.0/Recomendation/CategoryToItems/550fdc951e99461fc47c015c/' . $category->getId());
//      //echo str_replace(array("[","]"), "", $rrContent);
//      $category->setLastupdaterrproductid(time());
//      $category->setRrproductid(str_replace(array("[", "]"), "", $rrContent));
//      $category->save();
//      }
//      if ($category->getRrproductid() != "") */
//    if ($sortOrder == "sortorder")
//        include_component('category', 'sliderCategory', array('sf_cache_key' => $category->getId(), 'category' => $category, 'productsCategory' => $products/* $category->getRrproductid(), 'prodId' => $category->getRrproductid(), 'catId' => $category->getId() */));
//}

If ($sf_request->getParameter('page', 1) == 1 and $sortOrder != "sortorder" and sizeof($sf_request->getParameter('filters'))==0) {

    if (substr_count($category->getContent(), "{replaceSpoiler}")) {

        echo "<div class=\"descriptionCategory\">" . str_replace("{replaceSpoiler}", '<div class="btnSpoiler" onclick="toggleSpoiler(this);">
			<div class="Text">
				Развернуть</div>
                                </div><div class="spoiler">', $category->getContent());
        ?></div></div>

        <script type="text/javascript">
            function toggleSpoiler(tag) {
                $(tag).parents('.descriptionCategory').find('.spoiler').fadeIn(0);
                $(tag).remove();
            }
        </script>
        <?
    } elseif($sortOrder != "sortorder" and sizeof($sf_request->getParameter('filters'))==0) {
        echo $category->getContent()
        ?><br />
        <?
    }
}
?>

<div class="blockSort">
    <div style="float: left;">
        Сортировать по: <a href="/category/<?php echo $category->getSlug() ?>"<?= $sortOrder == "sortorder" ? " style='color: #c3060e;'" : "" ?>>Популярные</a><span style="padding: 0 10px;">|</span><a href="/category/<?php echo $category->getSlug() ?>?sortOrder=date"<?= $sortOrder == "date" ? " style='color: #c3060e;'" : "" ?>>Новинки</a><span style="padding: 0 10px;">|</span><a href="/category/<?php echo $category->getSlug() ?>?sortOrder=actions"<?= $sortOrder == "actions" ? " style='color: #c3060e;'" : "" ?>>Акции и скидки</a><span style="padding: 0 10px;">|</span><a href="/category/<?php echo $category->getSlug() ?>?sortOrder=price&amp;direction=<?= ($direction == "asc" and $sortOrder == "price") ? 'desc' : 'asc' ?>"<?= $sortOrder == "price" ? " style='color: #c3060e;'" : "" ?>>Цена <span style="font-weight: bold;"><?= $sortOrder == "price" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a><span style="padding: 0 10px;">|</span><a href="/category/<?php echo $category->getSlug() ?>?sortOrder=comments"<?= $sortOrder == "comments" ? " style='color: #c3060e;'" : "" ?>>Отзывы</a><? /* <span style="padding: 0 10px;">|</span><a href="/category/<?php echo $category->getSlug() ?>?sortOrder=rating"<?= $sortOrder == "rating" ? " style='color: #c3060e;'" : "" ?>>Рейтинг</a> */ ?>
    </div>
    <div style="float: right;"><span class="nonCountShow" onclick="if ($(this).text() == 'Скрыть отсутствующие товары') {
                $(this).text('Показать отсутствующие товары');
                $('.liProdNonCount').each(function (i) {
                    $(this).fadeOut(0);
                })
            } else {
                $(this).text('Скрыть отсутствующие товары');
                $('.liProdNonCount').each(function (i) {
                    $(this).fadeIn(0);
                })
            }
            ;
            viravnivanie();">Скрыть отсутствующие товары</span>
    </div>
</div>
<div style="clear: both;"></div>
<?
$timer->addTime();
?>
<script type="text/javascript">
    function CallPrint(strid)
    {
        var prtContent = $(strid);
        var prtCSS = '<link rel="stylesheet" href="/newdis/css/all.css" type="text/css" />';
        var WinPrint = window.open('', '', 'left=50,top=50,width=800,height=700,toolbar=0,scrollbars=1,status=0');
        WinPrint.document.write('<div class="popup quick-view" style="margin: 0px; top: 10px; left: 10px;">');
        WinPrint.document.write(prtCSS);
        WinPrint.document.write(prtContent.html());
        WinPrint.document.write('</div></div></article>');
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }

</script>


<?php
$timer = sfTimerManager::getTimer('Templates: Определение количества товаров для блока лучшая цена');
if ($category->getSlug() == "upravlyai-cenoi") {
    $prouctCountAction = 0;
} else {
    $prouctCountAction = 0;
    if ($products[2]->getEndaction() != "" and $products[2]->getCount() > 0 and $products[2]->getDiscount() > 0) {
        $prouctCountAction = 2;
    }
    if ($products[5]->getEndaction() != "" and $products[5]->getCount() > 0 and $products[5]->getDiscount() > 0) {
        $prouctCountAction = 5;
    }
}
$timer->addTime();
?><ul class="item-list"><?
?>
    <?php
    if ($sf_user->isAuthenticated()):
        $nameSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getFirstName();
        $mailSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress();
    endif;
    $timerAllProd = sfTimerManager::getTimer('Templates: Вывод товаров');
    foreach ($products as $prodNum => $product):
        if ($product->getCount() > 0) {
            $prodCount = 1;
        } else {
            $prodCount = 0;
        }
        ?>
        <?php
        if ($product->getCount() < 1) {
            $productCountLast = ProductTable::getInstance()->createQuery()->where("parents_id=?", $product->getId())->addWhere("count>0")->addwhere("is_public=1")->fetchOne();
            if ($productCountLast)
                $product = $productCountLast;
        }
        if (in_array($product->getId(), $arrayProdCart) === true)
            $prodInCart = true;
        else
            $prodInCart = false;

        if (in_array($product->getId(), $products_desire) === true)
            $prodInDesire = true;
        else
            $prodInDesire = false;

        if (in_array($product->getId(), $products_compare) === true)
            $prodInCompare = true;
        else
            $prodInCompare = false;


        $isAdmin = false;
        if ($sf_user->isAuthenticated()):

            if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == 17101) {
                $isAdmin = true;
            }
        endif;
        if ($isAdmin) {
            //include_component('category', 'productsnew', array('slug' => $sf_request->getParameter('slug'), 'product' => $product->toArray(), 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-" . ((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast') . "-showarticle-" . $showarticle, 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false), 'agentIsMobile' => $agentIsMobile, "productsKeys" => implode(",", $products->getPrimaryKeys()), "prodInCart" => $prodInCart, "prodInDesire" => $prodInDesire, "prodInCompare" => $prodInCompare));
            include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-" . ((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast') . "-showarticle-" . $showarticle, 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false), 'agentIsMobile' => $agentIsMobile, "productsKeys" => implode(",", $products->getPrimaryKeys()), "prodInCart" => $prodInCart, "prodInDesire" => $prodInDesire, "prodInCompare" => $prodInCompare));
        } else {
            include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-" . ((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast') . "-showarticle-" . $showarticle, 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false), 'agentIsMobile' => $agentIsMobile, "productsKeys" => implode(",", $products->getPrimaryKeys()), "prodInCart" => $prodInCart, "prodInDesire" => $prodInDesire, "prodInCompare" => $prodInCompare));
        }
        ?>
        <?php
    endforeach;

    $timerAllProd->addTime();
    ?>
</ul><!--item-list end-->
<?
include_component('category', 'paginator', array('slug' => $sf_request->getParameter('slug'), 'sortOrder' => $sortOrder, 'sf_cache_key' => $sf_request->getParameter('slug') . "-" . $sortOrder . "-" . $direction . "-" . $pager->getPage() . "-" . $sf_request->getParameter('page', 1), 'direction' => $direction, 'category' => $category, 'pager' => $pager));

$timerAll->addTime();
slot('gdeSlonMode', 'list');
?>
