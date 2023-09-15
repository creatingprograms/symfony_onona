<?php

$timer = sfTimerManager::getTimer('Templates: Передача переменных в главный шаблон');
mb_internal_encoding('UTF-8');

$timer->addTime();
?>
<?php

if (count($products) > 0):
    foreach ($products as $product['id'] => $product) {
        echo '<li class="prodTable-' . $product['id'] . (($product['count'] == 0) ? " liProdNonCount" : "") . '">';
        if (!isset($childrensAll[$product['id']])) {
            $childrensAll[$product['id']] = array();
        }

        include_partial("product/productBooklet", array(
            'sf_cache_key' => $product['id'],
            'products' => $products,
            'product' => $product,
            'childrens' => $childrensAll[$product['id']],
            'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
            'commentsAll' => $commentsAll,
            'photo' => $photosAll[$product['id']],
            'photosAll' => $photosAll,
            'autoLoadPhoto' => false
                )
        );

        echo "</li>";
    }
endif;
?>
<div id="paginationBoxInfo" style="display: none;">
<?
    include_component('category', 'paginatorNew', array('category' => end($categorys), 'sortOrder' => $sortOrder, 'direction' => $direction, "page" => $page, "pagesCount" => $pagesCount));
?>
</div>