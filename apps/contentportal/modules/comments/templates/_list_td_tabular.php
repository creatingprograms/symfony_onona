<td class="sf_admin_foreignkey sf_admin_list_td_product_id">
  <?php echo get_partial('comments/product_id', array('type' => 'list', 'comments' => $comments)) ?>
</td>
<td class="sf_admin_foreignkey sf_admin_list_td_customer_id">
  <?php echo get_partial('comments/customer_id', array('type' => 'list', 'comments' => $comments)) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_username">
  <?php echo $comments->getUsername() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_text">
  <?php echo $comments->getText() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_public">
  <?php echo get_partial('comments/is_public', array('type' => 'list', 'comments' => $comments)) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_created_at">
  <?php echo false !== strtotime($comments->getCreatedAt()) ? format_date($comments->getCreatedAt(), "f") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_rate_set">
  <?php echo $comments->getRateSet() ?>
</td>
