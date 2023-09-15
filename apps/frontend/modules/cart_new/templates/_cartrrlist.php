<?php
$products_old = $sf_context->getUser()->getAttribute('products_to_cart');
$products_old = $products_old != '' ? unserialize($products_old) : '';
$keys=array_keys($products_old);
echo implode(',',$keys);

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
