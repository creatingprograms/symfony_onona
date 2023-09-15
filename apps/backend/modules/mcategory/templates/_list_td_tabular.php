
<td class="sf_admin_text sf_admin_list_td_id">
    <?php echo $mcategory->getId() ?>
</td><td class="sf_admin_text sf_admin_list_td_name">
    <?php echo ($mcategory->getParentsId() != "") ? ' -- ' : '' ?><?php echo $mcategory->getName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_slug">
    <input value="<?php echo $mcategory->getLovepricename() ?>" id="lovepricename_<?php echo $mcategory->getId() ?>" name="lovepricename" type="text" style="width: 400px;"><input type="button" value="OK" 
     onClick="javascript:$.post('/backend_dev.php/mcategory/lovepricenameChange/<?= $mcategory->getId() ?>', { positionrelated: $('#lovepricename_<?php echo $mcategory->getId() ?>').val() },  function(data) {});">
    
</td>
<td class="sf_admin_text sf_admin_list_td_slug">
    <?php

     $products = ProductTable::getInstance()->createQuery()->where('step <> ""')->addWhere('generalcategory_id=' . $mcategory->getId())->execute();
    
    echo $products->count();//$mcategory->getCategoryProducts()->count() ?>
</td>
