  <a href="#" onClick="changePublic(<?php echo $faq->getId() ?>); return false;"><?php echo get_partial('faq/list_field_boolean', array('value' => $faq->getIsPublic())) ?></a>
