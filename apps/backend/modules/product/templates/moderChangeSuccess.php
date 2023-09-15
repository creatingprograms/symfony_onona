  <a href="#" onClick="changeModer(<?php echo $product->getId() ?>); return false;"><?php echo get_partial('product/list_field_boolean_moder', array('value' => $product->getModer())) ?></a>
