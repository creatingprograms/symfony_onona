  <a href="#" onClick="changeAdult(<?php echo $mcategory->getId() ?>); return false;"><?php echo get_partial('mcategory/list_field_boolean', array('value' => $mcategory->getAdult())) ?></a>
