
<?php

if (!sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")):
    echo $helper->linkToNew(array('params' => array(), 'class_suffix' => 'new', 'label' => 'New',));
endif;
?>
