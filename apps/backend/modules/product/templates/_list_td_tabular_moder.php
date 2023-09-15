<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo $product->getId() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_name">
  <?php echo ($product->getParentsId()!="")?' -- ':'' ?><a href="/product/<?=$product->getSlug()?>"><?=$product->getName()?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_code">
  <?php echo $product->getCode() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_slug">
  <?php echo $product->getSlug() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_public_<?php echo $product->getId() ?>">
  <a href="#" onClick="changePublic(<?php echo $product->getId() ?>); return false;"><?php echo get_partial('product/list_field_boolean', array('value' => $product->getIsPublic())) ?></a>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_related_<?php echo $product->getId() ?>">
  <a href="#" onClick="changeRelated(<?php echo $product->getId() ?>); return false;"><?php echo get_partial('product/list_field_boolean', array('value' => $product->getIsRelated())) ?></a>
</td>
<td class="sf_admin_boolean sf_admin_list_td_adult_<?php echo $product->getId() ?>">
  <a href="#" onClick="changeAdult(<?php echo $product->getId() ?>); return false;"><?php echo get_partial('product/list_field_boolean', array('value' => $product->getAdult())) ?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_price">
  <?php echo $product->getPrice() ?> руб.
</td>
<td class="sf_admin_text sf_admin_list_td_code">
  <?php echo $product->getCount() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_moder_<?php echo $product->getId() ?>">
  <a href="#" onClick="changeModer(<?php echo $product->getId() ?>); return false;"><?php echo get_partial('product/list_field_boolean_moder', array('value' => $product->getModer())) ?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_manager">
  <? if ($product->getUser()): ?>
    <?php $user=  sfGuardUserTable::getInstance()->findOneById($product->getUser());
    echo $user->getEmailAddress() ?>
  <? else : ?>
    Не определен
  <? endif ?>
</td>
