  <a href="#" onClick="changePublic(<?php echo $photosUser->getId() ?>); return false;"><?php echo get_partial('userphotos/list_field_boolean', array('value' => $photosUser->getIsPublic())) ?></a>
