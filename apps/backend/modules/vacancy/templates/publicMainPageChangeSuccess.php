  <a href="#" onClick="changePublicMainPage(<?php echo $video->getId() ?>); return false;"><?php echo get_partial('video/list_field_boolean', array('value' => $video->getIsPublicmainpage())) ?></a>
