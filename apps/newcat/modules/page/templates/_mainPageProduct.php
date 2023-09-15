<?

// $personalRecomendationCategory = unserialize(base64_decode(sfContext::getInstance()->getRequest()->getCookie('personalRecomendationCategory')));
// arsort($personalRecomendationCategory['category']);
// arsort($personalRecomendationCategory['products']);

$q = Doctrine_Manager::getInstance()->getCurrentConnection();
/*  118473 - onona.ru - Переместить блоки на основной странице
$timerPage = sfTimerManager::getTimer('_mainPageProduct: Загрузка товаров персональной рекомендации');
if (csSettings::get("notRecomendationProducts") != "") {

    foreach (array_keys(array_slice($personalRecomendationCategory['category'], 0, 5, true)) as $numCat => $catId) {
        if ($numCat == 0) {
            $sqlQueryPersonalRecomendation = "(SELECT * from product WHERE generalcategory_id='" . $catId . "' and is_public='1' and count>0 and id not in (" . csSettings::get("notRecomendationProducts") . ") order by countsell desc limit 0,3)";
        } elseif ($numCat < 3) {
            $sqlQueryPersonalRecomendation.="union(SELECT * from product WHERE generalcategory_id='" . $catId . "' and is_public='1' and count>0 and id not in (" . csSettings::get("notRecomendationProducts") . ") order by countsell desc limit 0,2)";
        } else {
            $sqlQueryPersonalRecomendation.="union(SELECT * from product WHERE generalcategory_id='" . $catId . "' and is_public='1' and count>0 and id not in (" . csSettings::get("notRecomendationProducts") . ") order by countsell desc limit 0,1)";
        }
    }

    unset($personalRecomendationCategory['products']['']);
    $personalRecomendationBlockProductId = array();

    if ($sqlQueryPersonalRecomendation != "") {
        $productsPersonalRecomendation = $q->execute($sqlQueryPersonalRecomendation)->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $randProductIdCategorys = array_rand(array_keys($productsPersonalRecomendation), ((count($productsPersonalRecomendation) > 5) ? 6 : count($productsPersonalRecomendation)));
        foreach ($randProductIdCategorys as $value) {
            $personalRecomendationBlockProductId[] = array_keys($productsPersonalRecomendation)[$value];
        }
    }
    $randProductIdProducts = array_rand(array_keys(array_slice($personalRecomendationCategory['products'], 0, 10, true)), 4);
    foreach ($randProductIdProducts as $value) {
        $personalRecomendationBlockProductId[] = array_keys(array_slice($personalRecomendationCategory['products'], 0, 10, true))[$value];
    }
}
else {

    foreach (array_keys(array_slice($personalRecomendationCategory['category'], 0, 5, true)) as $numCat => $catId) {
        if ($numCat == 0) {
            $sqlQueryPersonalRecomendation = "(SELECT * from product WHERE generalcategory_id='" . $catId . "' and is_public='1' and count>0 order by countsell desc limit 0,3)";
        } elseif ($numCat < 3) {
            $sqlQueryPersonalRecomendation.="union(SELECT * from product WHERE generalcategory_id='" . $catId . "' and is_public='1' and count>0 order by countsell desc limit 0,2)";
        } else {
            $sqlQueryPersonalRecomendation.="union(SELECT * from product WHERE generalcategory_id='" . $catId . "' and is_public='1' and count>0 order by countsell desc limit 0,1)";
        }
    }

    unset($personalRecomendationCategory['products']['']);
    $personalRecomendationBlockProductId = array();

    if ($sqlQueryPersonalRecomendation != "") {
        $productsPersonalRecomendation = $q->execute($sqlQueryPersonalRecomendation)->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $randProductIdCategorys = array_rand(array_keys($productsPersonalRecomendation), 6);
        foreach ($randProductIdCategorys as $value) {
            $personalRecomendationBlockProductId[] = array_keys($productsPersonalRecomendation)[$value];
        }
    }

    $randProductIdProducts = array_rand(array_keys(array_slice($personalRecomendationCategory['products'], 0, 10, true)), 4);
    foreach ($randProductIdProducts as $value) {
        $personalRecomendationBlockProductId[] = array_keys(array_slice($personalRecomendationCategory['products'], 0, 10, true))[$value];
    }
}
//print_r($products);
if (count($personalRecomendationBlockProductId) > 0) {
    $productsPersonalRecomendation = ProductTable::getInstance()->createQuery()->where("id in (" . implode(",", $personalRecomendationBlockProductId) . ")")->addWhere("is_public='1'")->addWhere("count>0")->orderBy("(count>0) DESC, rand()")->limit(10)->execute();
} else {
    unset($productsPersonalRecomendation);
}
$timerPage->addTime();*/
?>

<div class="tabset productNewMainPage" style="width: 100%;margin: 0 0 20px">
    <ul class="tab-control">
        <li><a href="/category/skidki_do_60_percent">Акции</a></li>

    </ul>
    <div class="tab" style="display: block;">
      <div class="swiper-container new-prod" id="action-prod">
        <div class="swiper-wrapper gtm-category-show" data-list="Главная страница. Акции">

                <?

                $productsNew = ProductTable::getInstance()
                  ->createQuery()
                  // ->where("id=18797")
                  ->where("old_price > 0 AND old_price>price")
                  ->addWhere("is_public='1'")
                  ->addWhere("count>0")
                  ->orderBy("(count>0) DESC, rand()")
                  ->limit(10)
                  ->execute();


                $timerPhoto = sfTimerManager::getTimer('_mainPageProduct: Загрузка фото');
                if (implode(",", $productsNew->getPrimaryKeys()) != "") {
                    $photos = PhotoTable::getInstance()->createQuery("p")
                                    ->select("*")->addSelect("product_id as product_id")
                                    ->leftJoin("p.Photoalbum pa")
                                    ->leftJoin("pa.ProductPhotoalbum ppa")
                                    ->where("ppa.product_id in (" . implode(",", $productsNew->getPrimaryKeys()) . ")")
                                    ->orderBy("p.position")->execute();
                }
                foreach ($photos as $photo) {
                    if ($photosArray[$photo->get("product_id")] == "")
                        $photosArray[$photo->get("product_id")] = $photo->get('filename');
                }
                $timerPhoto->addTime();
                foreach ($productsNew as $productNew):



                    if (in_array($productNew->getId(), $arrayProdCart) === true)
                        $prodInCart = true;
                    else
                        $prodInCart = false;

                    if (in_array($productNew->getId(), $products_desire) === true)
                        $prodInDesire = true;
                    else
                        $prodInDesire = false;

                    if (in_array($productNew->getId(), $products_compare) === true)
                        $prodInCompare = true;
                    else
                        $prodInCompare = false;

                    include_component('category', 'productsnewmain',
                      array('slug' => $sf_request->getParameter('slug'),
                      'product' => $productNew,
                      'sf_cache_key' => $productNew->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-productShowItems-last-mainpage",
                      'prodCount' => $prodCount,
                      'prodNum' => $prodNum,
                      "productsKeys" => implode(",", $productsNew->getPrimaryKeys()),
                      "prodInCart" => $prodInCart,
                      "prodInDesire" => $prodInDesire,
                      "prodInCompare" => $prodInCompare,
                      "productShowItems" => true,
                      "photoFilename" => $photosArray[$productNew->getId()],
                      'listname'=> 'Главная страница. Акции'
                    ));

                endforeach; ?>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

</div>

<script type="text/javascript" src="/js/jquery.lbslider.js"></script>
<div data-retailrocket-markup-block="5ba3a52e97a52530d41bb22b"></div>
<?/* //118473 - onona.ru - Переместить блоки на основной странице
<div class="tabset productMainPage gtm-tabset" style="width: 100%;margin: 0 0 20px">
    <ul class="tab-control">
        <?php
        if (count($productsPersonalRecomendation) > 0) {
            ?>
            <li class="active"><a href="/"><span>Персональные рекомендации</span></a></li>
            <li class="gtm-check-invisible"><a href="/related"><span>Лидеры продаж</span></a></li><?
        } else {
            ?>
            <li class="active"><a href="/related"><span>Лидеры продаж</span></a></li><?
        }
        ?>
        <li class="gtm-check-invisible"><a href="/category/Luchshaya_tsena"><span>Лучшая цена</span></a></li>
        <li class="gtm-check-invisible"><a href="/category/upravlyai-cenoi"><span>Управляй ценой!</span></a></li>

    </ul>
    <?php if (count($productsPersonalRecomendation) > 0) {
        ?>
        <div class="tab" style='display: block'>

            <script>
                $(document).ready(function () {
                  var visible=3;
                  if (window.screen.width<768) visible=1;
                    $('.blockPersonalRecomendationContentNew').lbSlider({
                        leftBtn: '.PersonalRecomendation.sa-left',
                        rightBtn: '.PersonalRecomendation.sa-right',
                        visible: visible,
                        autoPlay: false,
                        autoPlayDelay: 5,
                        cyclically: true
                    });
                });</script>
            <div class="blockPersonalRecomendationContentNew">
                <ul class="ulPersonalRecomendationContent item-list item-list-mainpage-prod gtm-category-show" data-list="Главная страница. Персональные рекомендации">

                    <?
                    //print_r($productsBuywithitem->getPrimaryKeys());
                    if (count($productsPersonalRecomendation) > 0) {
                        $timerComment = sfTimerManager::getTimer('_mainPageProduct: Загрузка комментариев');
                        if (implode(",", $productsPersonalRecomendation->getPrimaryKeys()) != "") {
                            $comments = Doctrine_Core::getTable('Comments')
                                    ->createQuery('c')
                                    ->select('product_id')
                                    ->addSelect("count(product_id) as countcomm")
                                    ->where("is_public = '1'")
                                    ->addWhere('product_id in (' . implode(",", $productsPersonalRecomendation->getPrimaryKeys()) . ")")
                                    ->groupBy("product_id")
                                    ->execute();
                            foreach ($comments as $comment) {
                                $commentsArray[$comment->getProductId()] = $comment->get('countcomm');
                            }
                        }
                        $timerComment->addTime();

                        $timerPhoto = sfTimerManager::getTimer('_mainPageProduct: Загрузка фото');
                        if (implode(",", $productsPersonalRecomendation->getPrimaryKeys()) != "") {
                            $photos = PhotoTable::getInstance()->createQuery("p")
                                            ->select("*")->addSelect("product_id as product_id")
                                            ->leftJoin("p.Photoalbum pa")
                                            ->leftJoin("pa.ProductPhotoalbum ppa")
                                            ->where("ppa.product_id in (" . implode(",", $productsPersonalRecomendation->getPrimaryKeys()) . ")")
                                            ->orderBy("p.position")->execute();
                        }
                        foreach ($photos as $photo) {
                            if ($photosArray[$photo->get("product_id")] == "")
                                $photosArray[$photo->get("product_id")] = $photo->get('filename');
                        }
                        $timerPhoto->addTime();

                        foreach ($productsPersonalRecomendation as $productPersonalRecomendation):

                            if (in_array($productPersonalRecomendation->getId(), $arrayProdCart) === true)
                                $prodInCart = true;
                            else
                                $prodInCart = false;

                            if (in_array($productPersonalRecomendation->getId(), $products_desire) === true)
                                $prodInDesire = true;
                            else
                                $prodInDesire = false;

                            if (in_array($productPersonalRecomendation->getId(), $products_compare) === true)
                                $prodInCompare = true;
                            else
                                $prodInCompare = false;

                            include_component(
                              'category',
                              'products',
                              array(
                                'slug' => $sf_request->getParameter('slug'),
                                'product' => $productPersonalRecomendation,
                                'sf_cache_key' => $productPersonalRecomendation->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-productShowItems-last-mainpage",
                                'prodCount' => $prodCount,
                                'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'),
                                'prodNum' => $prodNum,
                                'last' => true,
                                'mainpage' => true,
                                'agentIsMobile' => $agentIsMobile,
                                "productsKeys" => implode(",", $productsPersonalRecomendation->getPrimaryKeys()),
                                "prodInCart" => $prodInCart,
                                "prodInDesire" => $prodInDesire,
                                "prodInCompare" => $prodInCompare,
                                "productShowItems" => true,
                                "commentsCount" => $commentsArray[$productPersonalRecomendation->getId()],
                                "photoFilename" => $photosArray[$productPersonalRecomendation->getId()],
                                'listname'=> 'Главная страница. Персональные рекомендации'
                              )
                            );

                        endforeach;
                    }
                    ?>
                </ul>
            </div>
            <a href="#" class="PersonalRecomendation sa-left" style="left: 15px; top: 170px"></a>
            <a href="#" class="PersonalRecomendation sa-right" style="right: 15px; top: 170px"></a>
        </div>
        <?
    } ?>
    <div class="tab" style='display: block'>

        <script>
            $(document).ready(function () {
              var visible=3;
              if (window.screen.width<768) visible=1;
                $('.blockBestsellersContentNew').lbSlider({
                    leftBtn: '.Bestsellers.sa-left',
                    rightBtn: '.Bestsellers.sa-right',
                    visible: visible,
                    autoPlay: false,
                    autoPlayDelay: 5,
                    cyclically: true
                });
                <?php
                if (count($productsPersonalRecomendation) > 0) {
                    ?>
                                    $('.blockBestsellersContentNew').parent().hide();
                <? } ?>
            });
        </script>
        <div class="blockBestsellersContentNew">
            <ul class="ulBestsellersContent item-list item-list-mainpage-prod gtm-category-show" data-list="Главная страница. Лидеры продаж">

                <?
                $settingBestsellersProducts=csSettings::get("bestsellersProducts");
                if ($settingBestsellersProducts != "") {
                    $productsBestsellers = ProductTable::getInstance()->createQuery()->where("id in (" . $settingBestsellersProducts . ")")->addWhere("is_public='1'")->addWhere("count>0")->orderBy("(count>0) DESC, rand()")->limit(10)->execute();
                    //print_r($productsBuywithitem->getPrimaryKeys());

                    $timerComment = sfTimerManager::getTimer('_mainPageProduct: Загрузка комментариев');
                    if (implode(",", $productsBestsellers->getPrimaryKeys()) != "") {
                        $comments = Doctrine_Core::getTable('Comments')
                                ->createQuery('c')
                                ->select('product_id')
                                ->addSelect("count(product_id) as countcomm")
                                ->where("is_public = '1'")
                                ->addWhere('product_id in (' . implode(",", $productsBestsellers->getPrimaryKeys()) . ")")
                                ->groupBy("product_id")
                                ->execute();
                        foreach ($comments as $comment) {
                            $commentsArray[$comment->getProductId()] = $comment->get('countcomm');
                        }
                    }
                    $timerComment->addTime();

                    $timerPhoto = sfTimerManager::getTimer('_mainPageProduct: Загрузка фото');
                    if (implode(",", $productsBestsellers->getPrimaryKeys()) != "") {
                        $photos = PhotoTable::getInstance()->createQuery("p")
                                        ->select("*")->addSelect("product_id as product_id")
                                        ->leftJoin("p.Photoalbum pa")
                                        ->leftJoin("pa.ProductPhotoalbum ppa")
                                        ->where("ppa.product_id in (" . implode(",", $productsBestsellers->getPrimaryKeys()) . ")")
                                        ->orderBy("p.position")->execute();
                    }
                    foreach ($photos as $photo) {
                        if ($photosArray[$photo->get("product_id")] == "")
                            $photosArray[$photo->get("product_id")] = $photo->get('filename');
                    }
                    $timerPhoto->addTime();

                    foreach ($productsBestsellers as $productBestsellers):



                        if (in_array($productBestsellers->getId(), $arrayProdCart) === true)
                            $prodInCart = true;
                        else
                            $prodInCart = false;

                        if (in_array($productBestsellers->getId(), $products_desire) === true)
                            $prodInDesire = true;
                        else
                            $prodInDesire = false;

                        if (in_array($productBestsellers->getId(), $products_compare) === true)
                            $prodInCompare = true;
                        else
                            $prodInCompare = false;

                        include_component(
                          'category',
                          'products',
                          array(
                            'slug' => $sf_request->getParameter('slug'),
                            'product' => $productBestsellers,
                            'sf_cache_key' => $productBestsellers->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-productShowItems-last-mainpage",
                            'prodCount' => $prodCount,
                            'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'),
                            'prodNum' => $prodNum,
                            'last' => true,
                            'mainpage' => true,
                            'agentIsMobile' => $agentIsMobile,
                            "productsKeys" => implode(",", $productsBestsellers->getPrimaryKeys()),
                            "prodInCart" => $prodInCart,
                            "prodInDesire" => $prodInDesire,
                            "prodInCompare" => $prodInCompare,
                            "productShowItems" => true,
                            "commentsCount" => $commentsArray[$productBestsellers->getId()],
                            "photoFilename" => $photosArray[$productBestsellers->getId()],
                            'listname'=> 'Главная страница. Лидеры продаж'
                          )
                        );

                    endforeach;
                }
                ?>
            </ul>
        </div>
        <a href="#" class="Bestsellers sa-left" style="left: 15px; top: 170px"></a>
        <a href="#" class="Bestsellers sa-right" style="right: 15px; top: 170px"></a>
    </div>


    <div class="tab" style='display: block'>


        <script>
            $(document).ready(function () {
              var visible=3;
              if (window.screen.width<768) visible=1;
                $('.blockSimilarItemContentNew').lbSlider({
                    leftBtn: '.SimilarItem.sa-left',
                    rightBtn: '.SimilarItem.sa-right',
                    visible: visible,
                    autoPlay: false,
                    autoPlayDelay: 5,
                    cyclically: true
                });

                $('.blockSimilarItemContentNew').parent().hide();
            });</script>
        <div class="blockSimilarItemContentNew">
            <ul class="ulSimilarItemContent item-list item-list-mainpage-prod gtm-category-show" data-list="Главная страница. Лучшая цена">

                <?
                $productsDiscount = ProductTable::getInstance()->createQuery()->addWhere("is_public='1'")->addWhere("discount>0")->addWhere("count>0")->orderBy("(count>0) DESC, rand()")->limit(10)->execute();

                $timerComment = sfTimerManager::getTimer('_mainPageProduct: Загрузка комментариев');
                if (implode(",", $productsDiscount->getPrimaryKeys()) != "") {
                    $comments = Doctrine_Core::getTable('Comments')
                            ->createQuery('c')
                            ->select('product_id')
                            ->addSelect("count(product_id) as countcomm")
                            ->where("is_public = '1'")
                            ->addWhere('product_id in (' . implode(",", $productsDiscount->getPrimaryKeys()) . ")")
                            ->groupBy("product_id")
                            ->execute();
                    foreach ($comments as $comment) {
                        $commentsArray[$comment->getProductId()] = $comment->get('countcomm');
                    }
                }
                $timerComment->addTime();

                $timerPhoto = sfTimerManager::getTimer('_mainPageProduct: Загрузка фото');
                if (implode(",", $productsDiscount->getPrimaryKeys()) != "") {
                    $photos = PhotoTable::getInstance()->createQuery("p")
                                    ->select("*")->addSelect("product_id as product_id")
                                    ->leftJoin("p.Photoalbum pa")
                                    ->leftJoin("pa.ProductPhotoalbum ppa")
                                    ->where("ppa.product_id in (" . implode(",", $productsDiscount->getPrimaryKeys()) . ")")
                                    ->orderBy("p.position")->execute();
                }
                foreach ($photos as $photo) {
                    if ($photosArray[$photo->get("product_id")] == "")
                        $photosArray[$photo->get("product_id")] = $photo->get('filename');
                }

                $timerPhoto->addTime();
                foreach ($productsDiscount as $productDiscount):



                    if (in_array($productDiscount->getId(), $arrayProdCart) === true)
                        $prodInCart = true;
                    else
                        $prodInCart = false;

                    if (in_array($productDiscount->getId(), $products_desire) === true)
                        $prodInDesire = true;
                    else
                        $prodInDesire = false;

                    if (in_array($productDiscount->getId(), $products_compare) === true)
                        $prodInCompare = true;
                    else
                        $prodInCompare = false;

                    include_component('category', 'products',
                      array(
                        'slug' => $sf_request->getParameter('slug'),
                        'product' => $productDiscount,
                        'sf_cache_key' => $productDiscount->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-productShowItems-last-mainpage",
                        'prodCount' => $prodCount,
                        'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'),
                        'prodNum' => $prodNum,
                        'last' => true,
                        'mainpage' => true,
                        'agentIsMobile' => $agentIsMobile,
                        "productsKeys" => implode(",", $productsDiscount->getPrimaryKeys()),
                        "prodInCart" => $prodInCart,
                        "prodInDesire" => $prodInDesire,
                        "prodInCompare" => $prodInCompare,
                        "productShowItems" => true,
                        "commentsCount" => $commentsArray[$productDiscount->getId()],
                        "photoFilename" => $photosArray[$productDiscount->getId()],
                        'listname'=> 'Главная страница. Лучшая цена'
                    ));

                endforeach;
                ?>
            </ul>
        </div>
        <a href="#" class="SimilarItem sa-left" style="left: 15px; top: 170px"></a>
        <a href="#" class="SimilarItem sa-right" style="right: 15px; top: 170px"></a>
    </div>
    <div class="tab" style='display: block'>

        <script>
            $(document).ready(function () {
              var visible=3;
              if (window.screen.width<768) visible=1;
                $('.blockShowItemContentNew').lbSlider({
                    leftBtn: '.ShowItem.sa-left',
                    rightBtn: '.ShowItem.sa-right',
                    visible: visible,
                    autoPlay: false,
                    autoPlayDelay: 5,
                    cyclically: true
                });

                $('.blockShowItemContentNew').parent().hide();
            });</script>
        <div class="blockShowItemContentNew">
            <ul class="ulShowItemContent item-list item-list-mainpage-prod gtm-category-show" data-list="Главная страница. Управляй ценой">

                <?
                $productsBonuspay = ProductTable::getInstance()->createQuery()->addWhere("is_public='1'")->addWhere("bonuspay>30")->addWhere("count>0")->orderBy("(count>0) DESC, rand()")->limit(10)->execute();

                $timerComment = sfTimerManager::getTimer('_mainPageProduct: Загрузка комментариев');
                if (implode(",", $productsBonuspay->getPrimaryKeys()) != "") {
                    $comments = Doctrine_Core::getTable('Comments')
                            ->createQuery('c')
                            ->select('product_id')
                            ->addSelect("count(product_id) as countcomm")
                            ->where("is_public = '1'")
                            ->addWhere('product_id in (' . implode(",", $productsBonuspay->getPrimaryKeys()) . ")")
                            ->groupBy("product_id")
                            ->execute();
                    foreach ($comments as $comment) {
                        $commentsArray[$comment->getProductId()] = $comment->get('countcomm');
                    }
                }
                $timerComment->addTime();

                $timerPhoto = sfTimerManager::getTimer('_mainPageProduct: Загрузка фото');
                if (implode(",", $productsBonuspay->getPrimaryKeys()) != "") {
                    $photos = PhotoTable::getInstance()->createQuery("p")
                                    ->select("*")->addSelect("product_id as product_id")
                                    ->leftJoin("p.Photoalbum pa")
                                    ->leftJoin("pa.ProductPhotoalbum ppa")
                                    ->where("ppa.product_id in (" . implode(",", $productsBonuspay->getPrimaryKeys()) . ")")
                                    ->orderBy("p.position")->execute();
                }
                foreach ($photos as $photo) {
                    if ($photosArray[$photo->get("product_id")] == "")
                        $photosArray[$photo->get("product_id")] = $photo->get('filename');
                }
                $timerPhoto->addTime();
                foreach ($productsBonuspay as $productBonuspay):



                    if (in_array($productBonuspay->getId(), $arrayProdCart) === true)
                        $prodInCart = true;
                    else
                        $prodInCart = false;

                    if (in_array($productBonuspay->getId(), $products_desire) === true)
                        $prodInDesire = true;
                    else
                        $prodInDesire = false;

                    if (in_array($productBonuspay->getId(), $products_compare) === true)
                        $prodInCompare = true;
                    else
                        $prodInCompare = false;

                    include_component('category', 'products',
                      array('slug' => $sf_request->getParameter('slug'),
                      'product' => $productBonuspay,
                      'sf_cache_key' => $productBonuspay->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-productShowItems-last-mainpage",
                      'prodCount' => $prodCount,
                      'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'),
                      'prodNum' => $prodNum,
                      'last' => true,
                      'mainpage' => true,
                      'agentIsMobile' => $agentIsMobile,
                      "productsKeys" => implode(",", $productsBonuspay->getPrimaryKeys()),
                      "prodInCart" => $prodInCart,
                      "prodInDesire" => $prodInDesire,
                      "prodInCompare" => $prodInCompare,
                      "productShowItems" => true,
                      "commentsCount" => $commentsArray[$productBonuspay->getId()],
                      "photoFilename" => $photosArray[$productBonuspay->getId()],
                      'listname'=> 'Главная страница. Управляй ценой'
                    ));

                endforeach;
                ?>

            </ul>
        </div>
        <a href="#" class="ShowItem sa-left" style="left: 15px; top: 170px"></a>
        <a href="#" class="ShowItem sa-right" style="right: 15px; top: 170px"></a>
    </div>


</div>
*/?>
<div data-retailrocket-markup-block="5ba3a54197a52530d41bb22c"></div>
<div class="tabset productNewMainPage" style="width: 100%;margin: 0 0 20px">
    <?/*<ul class="tab-control">
        <li>*/?><a class="new-postup" href="/newprod">Новые поступления</a><?/*</li>

    </ul>*/?>
    <div class="tab" style='display: block'>
      <div class="swiper-container new-prod" id="new-prod">
        <div class="swiper-wrapper gtm-category-show" data-list="Главная страница. Новые поступления">

                <? if (csSettings::get('logo_new') == "") {
                    $day_logo_new = 7;
                } else {
                    $day_logo_new = csSettings::get('logo_new');
                }
                /*Задача 90079 - товары новинки - списком $newList*/

                $newList = csSettings::get('optimization_newProductId');

                $productsNew = ProductTable::getInstance()
                  ->createQuery()
                  ->where("DATEDIFF(NOW(),created_at) < " . $day_logo_new /*. ($newList=="" ? "" : " OR id IN($newList) ")*/)
                  ->orWhere("id IN(".($newList=="" ? "0" : $newList). ")") /*90079*/
                  ->addWhere("is_public='1'")
                  ->addWhere("count>0")
                  ->orderBy("(count>0) DESC, rand()")
                  ->limit(10)
                  ->execute();


                $timerPhoto = sfTimerManager::getTimer('_mainPageProduct: Загрузка фото');
                if (implode(",", $productsNew->getPrimaryKeys()) != "") {
                    $photos = PhotoTable::getInstance()->createQuery("p")
                                    ->select("*")->addSelect("product_id as product_id")
                                    ->leftJoin("p.Photoalbum pa")
                                    ->leftJoin("pa.ProductPhotoalbum ppa")
                                    ->where("ppa.product_id in (" . implode(",", $productsNew->getPrimaryKeys()) . ")")
                                    ->orderBy("p.position")->execute();
                }
                foreach ($photos as $photo) {
                    if ($photosArray[$photo->get("product_id")] == "")
                        $photosArray[$photo->get("product_id")] = $photo->get('filename');
                }
                $timerPhoto->addTime();
                foreach ($productsNew as $productNew):



                    if (in_array($productNew->getId(), $arrayProdCart) === true)
                        $prodInCart = true;
                    else
                        $prodInCart = false;

                    if (in_array($productNew->getId(), $products_desire) === true)
                        $prodInDesire = true;
                    else
                        $prodInDesire = false;

                    if (in_array($productNew->getId(), $products_compare) === true)
                        $prodInCompare = true;
                    else
                        $prodInCompare = false;

                    include_component('category', 'productsnewmain',
                    // include_component('category', 'products',
                      array('slug' => $sf_request->getParameter('slug'),
                      'product' => $productNew,
                      'sf_cache_key' => $productNew->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-productShowItems-last-mainpage",
                      'prodCount' => $prodCount,
                      // 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'),
                      'prodNum' => $prodNum,
                      // 'last' => true,
                      // 'mainpage' => true,
                      // 'agentIsMobile' => $agentIsMobile,
                      "productsKeys" => implode(",", $productsNew->getPrimaryKeys()),
                      "prodInCart" => $prodInCart,
                      "prodInDesire" => $prodInDesire,
                      "prodInCompare" => $prodInCompare,
                      "productShowItems" => true,
                      // "commentsCount" => $commentsArray[$productNew->getId()],
                      "photoFilename" => $photosArray[$productNew->getId()],
                      'listname'=> 'Главная страница. Новые поступления'
                    ));

                endforeach; ?>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

</div>
<script>
$(document).ready(function(){
    if ($('#new-prod').length){
      var count=4;
      if (window.screen.width<768) count=1;
      mySwiper = new Swiper ('#new-prod', {
        loop: true,
        slidesPerView: count,
        spaceBetween: 0,
        roundLengths: true,
        // autoHeight: true,
        // autoplay: 3000, //4 секунды
        // pagination: '.swiper-pagination',
        // paginationClickable: true,
        nextButton: '#new-prod .swiper-button-next',
        prevButton: '#new-prod .swiper-button-prev',
      })
    }
    if ($('#action-prod').length){
      var count=4;
      if (window.screen.width<768) count=1;
      mySwiper = new Swiper ('#action-prod', {
        loop: true,
        slidesPerView: count,
        spaceBetween: 0,
        roundLengths: true,
        nextButton: '#action-prod .swiper-button-next',
        prevButton: '#action-prod .swiper-button-prev',
      })
    }
});
</script>
