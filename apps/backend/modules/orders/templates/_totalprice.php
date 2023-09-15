<?php

$products_old = unserialize($orders->getText());
$TotalSumm = 0;
foreach ($products_old as $key => $productInfo):
    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']));
   

endforeach;
echo $TotalSumm;
?>
