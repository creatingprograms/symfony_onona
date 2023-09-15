<?php

if($orders->getBonuspay()>0)
    $isbonuspay=true;
else
    $isbonuspay=false;
echo get_partial('orders/list_field_boolean', array('value' => $isbonuspay)) ?>