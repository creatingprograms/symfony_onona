
<td class="sf_admin_text sf_admin_list_td_id">
    <?php
    $prod=  ProductTable::getInstance()->findOneById($product_action_log->getProdid());
    if($prod){
    ?>
  <a href="/product/<?=$prod->getSlug()?>"><?=$prod->getName()?></a><?}?>
</td>
<td class="sf_admin_text sf_admin_list_td_name">
    <?php
    $man=  ManufacturerTable::getInstance()->findOneById($product_action_log->getManid());
    ?>
  <a href="/manufacturer/<?=$man->getSlug()?>"><?=$man->getName()?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_code">
  <?php echo $product_action_log->getCount() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_slug">
  <?php echo $product_action_log->getDiscount() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_public">
  <?php echo $product_action_log->getEndaction() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_related">
  <?php echo $product_action_log->getStep() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_code">
  <?php echo $product_action_log->getCreatedAt() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_code">
  <?php 
  $prodAct=  ProductActionLogTable::getInstance()->findByProdid($product_action_log->getProdid());
  echo $prodAct->count() ?>
</td>