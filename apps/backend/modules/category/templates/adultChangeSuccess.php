  <a href="#" onClick="changeAdult(<?php echo $category->getId() ?>); return false;"><?php echo get_partial('category/list_field_boolean', array('value' => $category->getAdult())) ?></a>
