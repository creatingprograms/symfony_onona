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
    <?php
    if (sfContext::getInstance()->getUser()->hasPermission("All") or sfContext::getInstance()->getUser()->hasPermission("Admin contentportal")):
        ?>  
        <a href="#" onClick="changePublic(<?php echo $video->getId() ?>);
                return false;"><?php echo get_partial('video/list_field_boolean', array('value' => $video->getIsPublic())) ?></a>
    <?php else: ?>
        <?php echo get_partial('video/list_field_boolean', array('value' => $video->getIsPublic())) ?>
<?php endif; ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_publicmainpage_<?php echo $video->getId() ?>"><?php
    if (sfContext::getInstance()->getUser()->hasPermission("All") or sfContext::getInstance()->getUser()->hasPermission("Admin contentportal")):
        ?>  
        <a href="#" onClick="changePublicMainPage(<?php echo $video->getId() ?>);
            return false;"><?php echo get_partial('video/list_field_boolean', array('value' => $video->getIsPublicmainpage())) ?></a>
    <?php else: ?>
        <?php echo get_partial('video/list_field_boolean', array('value' => $video->getIsPublicmainpage())) ?>
<?php endif; ?>
    
</td>
