<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo link_to($video->getId(), 'video_edit', $video) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo $video->getName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_slug">
  <?php echo $video->getSlug() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_link">
  <?php echo $video->getLink() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_public_<?php echo $video->getId() ?>">
    <a href="#" onClick="changePublic(<?php echo $video->getId() ?>); return false;"><?php echo get_partial('video/list_field_boolean', array('value' => $video->getIsPublic())) ?></a>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_publicmainpage_<?php echo $video->getId() ?>">
    <a href="#" onClick="changePublicMainPage(<?php echo $video->getId() ?>); return false;"><?php echo get_partial('video/list_field_boolean', array('value' => $video->getIsPublicmainpage())) ?></a>
</td>
