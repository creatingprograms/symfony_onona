<?php

$timerComment = sfTimerManager::getTimer('_mainPageProduct: Загрузка комментариев');
if (implode(",", $products->getPrimaryKeys()) != "") {
    $comments = Doctrine_Core::getTable('Comments')
            ->createQuery('c')
            ->select('product_id')
            ->addSelect("count(product_id) as countcomm")
            ->where("is_public = '1'")
            ->addWhere('product_id in (' . implode(",", $products->getPrimaryKeys()) . ")")
            ->groupBy("product_id")
            ->execute();
    foreach ($comments as $comment) {
        $commentsArray[$comment->getProductId()] = $comment->get('countcomm');
    }
}
$timerComment->addTime();

$timerPhoto = sfTimerManager::getTimer('_mainPageProduct: Загрузка фото');
if (implode(",", $products->getPrimaryKeys()) != "") {
    $photos = PhotoTable::getInstance()->createQuery("p")
                    ->select("*")->addSelect("product_id as product_id")
                    ->leftJoin("p.Photoalbum pa")
                    ->leftJoin("pa.ProductPhotoalbum ppa")
                    ->where("ppa.product_id in (" . implode(",", $products->getPrimaryKeys()) . ")")
                    ->orderBy("p.position")->execute();
}
foreach ($photos as $photo) {
    $photosArray[$photo->get("product_id")] = $photo->get('filename');
}
$timerPhoto->addTime();

foreach ($products as $product):



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

    include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-productShowItems-last-mainpage", 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'last' => true, 'mainpage' => true, 'agentIsMobile' => $agentIsMobile, "productsKeys" => implode(",", $products->getPrimaryKeys()), "prodInCart" => $prodInCart, "prodInDesire" => $prodInDesire, "prodInCompare" => $prodInCompare, "productShowItems" => true, "commentsCount" => $commentsArray[$product->getId()], "photoFilename" => $photosArray[$product->getId()]));

endforeach;
