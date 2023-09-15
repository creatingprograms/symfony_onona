<td class="sf_admin_text sf_admin_list_td_name">
  <a href="/faq/<?=$faq->getSlug()?>" target="_blank"><?=$faq->getName()?></a>

</td>
<td class="sf_admin_text sf_admin_list_td_slug">
  <?php echo $faq->getSlug() ?>
</td>

<td class="sf_admin_boolean sf_admin_list_td_is_public_<?php echo $faq->getId() ?>">
  <a href="#" onClick="changePublic(<?php echo $faq->getId() ?>); return false;"><?php echo get_partial('faq/list_field_boolean', array('value' => $faq->getIsPublic())) ?></a>
</td>


<td class="sf_admin_boolean sf_admin_list_td_is_related_<?php echo $faq->getId() ?>">
  <a href="#" onClick="changeRelated(<?php echo $faq->getId() ?>); return false;"><?php echo get_partial('faq/list_field_boolean', array('value' => $faq->getIsRelated())) ?></a>
</td>

<td class="sf_admin_text sf_admin_list_td_positionrelated">
    <input value="<?php echo $faq->getPositionrelated() ?>" id="positionrelated_<?php echo $faq->getId() ?>" name="positionrelated" type="text" style="width: 100px;"><input type="button" value="OK"
     onClick="javascript:$.post('/backend_dev.php/faq/positionrelatedChange/<?= $faq->getId() ?>', { positionrelated: $('#positionrelated_<?php echo $faq->getId() ?>').val() },  function(data) {});">
</td>
