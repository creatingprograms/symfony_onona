  <a href="#" onClick="changePublic(<?php echo $video->getId() ?>); return false;"><?php echo get_partial('video/list_field_boolean', array('value' => $video->getIsPublic())) ?></a>
