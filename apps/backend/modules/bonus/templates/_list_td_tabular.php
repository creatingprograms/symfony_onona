<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo link_to($bonus->getId(), 'bonus_edit', $bonus) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_user_id">
  <?php echo $bonus->getSfGuardUser()->getEmailAddress() ?> (<?php echo $bonus->getUserId() ?>)
</td>
<td class="sf_admin_text sf_admin_list_td_bonus">
  <?php echo $bonus->getBonus() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_comment">
  <?php echo $bonus->getComment() ?>
</td>
<?/*<td class="sf_admin_text sf_admin_list_td_log">
  <?php echo $bonus->getLog() ?>
</td>*/?>
<td class="sf_admin_date sf_admin_list_td_created_at">
  <?php echo false !== strtotime($bonus->getCreatedAt()) ? format_date($bonus->getCreatedAt(), "f") : '&nbsp;' ?>
</td>
<td class="sf_admin_date sf_admin_list_td_activatelk">
<?php echo get_partial('product/list_field_boolean', array('value' => $bonus->getActivatelk())) ?>
</td>
