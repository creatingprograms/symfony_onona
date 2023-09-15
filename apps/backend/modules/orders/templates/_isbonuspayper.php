<?php

$isbonuspayper = false;

foreach (unserialize($orders->getFirsttext()) as $products) {
    if (@$products['bonus_percent'] > 0 and $products['bonus_percent'] != csSettings::get('PERSENT_BONUS_PAY'))
        $isbonuspayper = true;
}
echo get_partial('orders/list_field_boolean', array('value' => $isbonuspayper))
?>