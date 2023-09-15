<?php

if ($oprosnik->getShop() != "Магазин") {
    $order = OrdersTable::getInstance()->findOneById($oprosnik->getOrderId());
    echo $order->getStatus();
}
?>
