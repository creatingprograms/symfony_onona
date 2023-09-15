<?
if ($products->count() > 0) {

    $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
    if (is_array($products_old))
        foreach ($products_old as $key => $product) {
            $arrayProdCart[] = $product['productId'];
        }

    $products_desire = $sf_user->getAttribute('products_to_desire');
    $products_desire = $products_desire != '' ? unserialize($products_desire) : '';

    $products_compare = $sf_user->getAttribute('products_to_compare');
    $products_compare = $products_compare != '' ? unserialize($products_compare) : '';
    ?><link href="/css/jquery.mCustomScrollbar.css" rel="stylesheet">
    <script src="/js/jquery.mCustomScrollbar.js"></script>
    
    <script>
        (function ($) {
            $(window).load(function () {


                $(".blockSliderRRContent").mCustomScrollbar({
                    axis: "x",
                    scrollButtons: {enable: true},
                    theme: "3d",
                    scrollbarPosition: "outside",
                    autoExpandScrollbar: true,
                    autoDraggerLength: false,
                    advanced: {autoExpandHorizontalScroll: true}
                });
            });
        })(jQuery);</script>
    <table style="width: 750px; margin-top: 30px; margin-bottom: 30px;">
        <tr style="background: #e0e0e0;color: #707070;border: 1px solid #e0e0e0;"><td style="padding: 5px 10px;"><div style="float: left;">Лидеры продаж данной категории</div><div style="float: right;  border-bottom: 1px solid #707070; cursor: pointer;" onclick="if ($(this).text() == 'Свернуть') {
                        $(this).text('Развернуть');
                        $('.blockProdRR').each(function (i) {
                            $(this).fadeOut(0);
                        })
                    } else {
                        $(this).text('Свернуть');
                        $('.blockProdRR').each(function (i) {
                            $(this).fadeIn(0);
                        })
                    }
                    ;
                    viravnivanie();">Свернуть</div></td></tr>
        <tr style="border: 1px solid #e0e0e0;" class="blockProdRR"><td style="border: 0;padding: 0px;">
                <div class="blockSliderRRContent" style="overflow-x: auto; width: 750px;">
                    <ul class="ulSimilarItemContent item-list" style="padding: 0;">
                        <? foreach ($products as $key => $product): ?>


                            <?php
                            //include_component('category', 'productsbestprice', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article'), 'last' => ((($key + 1) % 3) == 0 ? true : false), 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), "productsKeys" => implode(",", $products->getPrimaryKeys())))

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
                            //include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-" . ((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast'), 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false), 'agentIsMobile' => $agentIsMobile, "productsKeys" => implode(",", $products->getPrimaryKeys()), "prodInCart" => $prodInCart, "prodInDesire" => $prodInDesire, "prodInCompare" => $prodInCompare));
                            include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-line4-nolast-prodnum0" . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare."-rrMETHOD_NAME-CategoryToItems", 'rrMETHOD_NAME' => 'CategoryToItems', 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => 0, 'line4' => false, "productShowItems" => true, 'last' => false, "prodInCart" => $prodInCart, "prodInDesire" => $prodInDesire, "prodInCompare" => $prodInCompare));
                            ?>

                        <?php endforeach; ?>
                    </ul>
                </div></td></tr>
        <tr style="background: #e0e0e0;color: #707070;border: 1px solid #e0e0e0;" class="blockProdRR"><td style="padding: 0px; height: 5px;"></td></tr>
    </table>
    <?
}?>