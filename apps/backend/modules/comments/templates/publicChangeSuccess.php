  <a href="#" onClick="changePublic(<?php echo $comments->getId() ?>); return false;"><?php echo get_partial('comments/list_field_boolean', array('value' => $comments->getIsPublic())) ?></a>
