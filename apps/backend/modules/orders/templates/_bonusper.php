<?php

/*
$products_old = unserialize($orders->getFirsttext());
  $TotalSumm = 0;
  foreach ($products_old as $key => $productInfo):
  $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']));


  endforeach; */

            $TotalSumm = $orders->getFirsttotalcost() + $orders->getBonuspay();
echo round(($orders->getBonuspay() / $TotalSumm) * 100);
?>
