<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?> 
<?php

if (count($productsArray) > 0)
    foreach ($productsArray as $prodId => $prodCount) {
        if ($prodId > 0) {
            $product = ProductTable::getInstance()->findOneById($prodId);
            if ($product)
                echo "<b>" . $product->getName() . "</b> - " . $prodCount . " заказ(ов)<br />";
        }
    }