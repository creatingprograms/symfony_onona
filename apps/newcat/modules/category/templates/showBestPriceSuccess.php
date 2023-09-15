<?php
  $timerAll = sfTimerManager::getTimer('Templates: Весь шаблон');
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
  $agentIsMobile = false;

?>
<script>
    hs.graphicsDir = '/js/highslide/graphics/';
    hs.align = 'center';
    hs.transitions = ['expand', 'crossfade'];
    hs.outlineType = 'rounded-white';
    hs.fadeInOut = true;
    hs.dimmingOpacity = 0.75;
</script>
<?/*<script>
    $(document).ready(function () {
        $("div.notcount").hover(function () {
            $(this).stop().animate({"opacity": 1});
        }, function () {
            $(this).stop().animate({"opacity": 0.3});
        });

    });
</script>
*/?>
<?/*<script src="/js/liTip_newcat.js"></script>*/?>
<? $timer = sfTimerManager::getTimer('Templates: Вывод хлебных крошек'); ?>
<ul class="breadcrumbs">
    <li><a href="/">Секс-шоп главная</a></li>
    <?php
    if ($category->getParentsId() != "") {
        echo '
    <li><a href="/category/' . $category->getParent()->getSlug() . '">' . $category->getParent()->getName() . '</a></li>';
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
    >
    </div>
</div>
<?
$timer->addTime();

If ($sf_request->getParameter('page', 1) == 1) {
    ?>
    <?php echo $category->getContent() ?><br /><?
}
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
$timer = sfTimerManager::getTimer('Templates: Вывод товаров категории лучшая цена');
if ($page == 1) {?>
  <div class="gtm-category-show" data-list="Лучшая цена">
    <?foreach ($categorys as $categoryBestpric) {
        $countAction = 0;
        $products = ProductTable::getInstance()->createQuery()->where('step <> "" and Endaction<>"" and (bonuspay="" or bonuspay is null or bonuspay=0)')->addWhere('generalcategory_id=' . $categoryBestpric->getId())->addWhere("count>0")->execute();
        // echo '<div style="display: none;">'.print_r($products->count(), true).'</div>';
        // continue;
        if ($products->count() > 1):
            if (floor($products->count() / 3) > 2) {
                $florCount = 2;
            } else {
                $florCount = floor($products->count() / 3);
            }
            $products = ProductTable::getInstance()->createQuery()->where('step <> "" and Endaction<>"" and discount>0')->addWhere('generalcategory_id=' . $categoryBestpric->getId())->addWhere("count>0")->limit(3 * $florCount)->execute();
            ?><br />

            <div class="best-price-cat-name">
                <span style="font: 14px/17px Tahoma,Geneva,sans-serif;"><a href="/category/<?= $categoryBestpric->getSlug() ?>" ><?
                        echo $categoryBestpric->getLovepricename() != "" ? $categoryBestpric->getLovepricename() : $categoryBestpric->getName() . "</a>";
                        echo $categoryBestpric->getLovepricename() == "" ? " по лучшей цене в Рунете!" : '';
                        ?></span>
            </div>
            <? /* ?>
              <div style="background: url('/newdis/images/ram_bestprice.png');width:772px; height: 18px;"></div> */ ?>
            <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>

            <ul class="item-list" style="  margin: 0 -7px 0 0;">
                <? foreach ($products as $key => $product): ?>
                    <?php
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
                    include_component('category', 'products', [
                        'slug' => "Luchshaya_tsena",
                        'product' => $product,
                        'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-" . ((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast'),
                        'prodCount' => $prodCount,
                        'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'),
                        'prodNum' => $prodNum, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false),
                        'agentIsMobile' => $agentIsMobile,
                        "productsKeys" => implode(",", $products->getPrimaryKeys()),
                        "prodInCart" => $prodInCart,
                        "prodInDesire" => $prodInDesire,
                        "prodInCompare" => $prodInCompare
                            ]
                    );
                    ?>

                    <?php
                endforeach;
                unset($products);
                ?>
            </ul>
            <?
        endif;
    }
  ?></div><?
    $timer->addTime();
}else {

    $products = $pager->getResults();
    ?><ul class="item-list gtm-category-show" data-list="Лучшая цена. Страница <?=$page?>"><?
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
            include_component('category', 'products', array('slug' => "Luchshaya_tsena", 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-" . ((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast') . "-showarticle-" . $showarticle, 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false), 'agentIsMobile' => $agentIsMobile, "productsKeys" => implode(",", $products->getPrimaryKeys()), "prodInCart" => $prodInCart, "prodInDesire" => $prodInDesire, "prodInCompare" => $prodInCompare));

        endforeach;

        $timerAllProd->addTime();
        ?>
    </ul><!--item-list end-->
    <?
}

include_component('category', 'paginator', array('slug' => "Luchshaya_tsena", 'sortOrder' => $sortOrder, 'sf_cache_key' => "Luchshaya_tsena" . "-" . $sortOrder . "-" . $direction . "-" . $pager->getPage() . "-" . $sf_request->getParameter('page', 1), 'direction' => $direction, 'category' => $category, 'pager' => $pager));

$timerAll->addTime();
?>
