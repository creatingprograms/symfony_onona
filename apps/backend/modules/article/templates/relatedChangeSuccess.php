  <a href="#" onClick="changeRelated(<?php echo $article->getId() ?>); return false;"><?php echo get_partial('article/list_field_boolean', array('value' => $article->getIsRelated())) ?></a>
