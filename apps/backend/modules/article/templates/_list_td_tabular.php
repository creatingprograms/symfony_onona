<td class="sf_admin_text sf_admin_list_td_name">
  <a href="/sexopedia/<?=$article->getSlug()?>" target="_blank"><?=$article->getName()?></a>

</td>
<td class="sf_admin_text sf_admin_list_td_slug">
  <?php echo $article->getSlug() ?>
</td>

<td class="sf_admin_boolean sf_admin_list_td_is_public_<?php echo $article->getId() ?>">
  <a href="#" onClick="changePublic(<?php echo $article->getId() ?>); return false;"><?php echo get_partial('article/list_field_boolean', array('value' => $article->getIsPublic())) ?></a>
</td>


<td class="sf_admin_boolean sf_admin_list_td_is_related_<?php echo $article->getId() ?>">
  <a href="#" onClick="changeRelated(<?php echo $article->getId() ?>); return false;"><?php echo get_partial('article/list_field_boolean', array('value' => $article->getIsRelated())) ?></a>
</td>

<td class="sf_admin_text sf_admin_list_td_positionrelated">
    <input value="<?php echo $article->getPositionrelated() ?>" id="positionrelated_<?php echo $article->getId() ?>" name="positionrelated" type="text" style="width: 100px;"><input type="button" value="OK" 
     onClick="javascript:$.post('/backend_dev.php/article/positionrelatedChange/<?= $article->getId() ?>', { positionrelated: $('#positionrelated_<?php echo $article->getId() ?>').val() },  function(data) {});">
</td>
