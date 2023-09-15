<?php

if ($oprosnik->getShop() != "Магазин") {
    $order = OrdersTable::getInstance()->findOneById($oprosnik->getOrderId());

    $TotalSumm = 0;
    $bonusAddUser = 0;
    $products_old = $order->getText() != '' ? unserialize($order->getText()) : '';
    foreach ($products_old as $key => $productInfo):

        @$TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']));
    endforeach;
    echo $TotalSumm;
}
?>
