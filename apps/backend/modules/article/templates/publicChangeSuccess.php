  <a href="#" onClick="changePublic(<?php echo $article->getId() ?>); return false;"><?php echo get_partial('article/list_field_boolean', array('value' => $article->getIsPublic())) ?></a>
