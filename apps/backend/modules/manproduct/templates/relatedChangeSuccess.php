  <a href="#" onClick="changeRelated(<?php echo $product->getId() ?>); return false;"><?php echo get_partial('product/list_field_boolean', array('value' => $product->getIsRelated())) ?></a>
