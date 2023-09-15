  <a href="#" onClick="changeRelated(<?php echo $faq->getId() ?>); return false;"><?php echo get_partial('faq/list_field_boolean', array('value' => $faq->getIsRelated())) ?></a>
