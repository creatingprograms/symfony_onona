<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_dop_info_products_list">
    <div>
        <label for="product_dop_info_products_list">Дополнительные характеристики</label>
        <div class="content">
            <?php
            $dop_info = DopInfoCategoryTable::getInstance()->createQuery()->addOrderBy('position ASC')->execute();
            foreach ($dop_info as $dop_info_name):
                ?>
                <a onclick="$('#properties_<?= $dop_info_name->getId(); ?>').toggle('slow'); return false;" href="#"><span style="font-size:14px"><?= $dop_info_name->getName(); ?></span></a><br />

                <div style="padding-left: 40px;display: none;" id="properties_<?= $dop_info_name->getId(); ?>">
                    <?php
                    $dop_info_values = DopInfoTable::getInstance()->createQuery()->where("dicategory_id=?", $dop_info_name->getId())->addOrderBy('value ASC')->execute();
                    foreach ($dop_info_values as $dop_info_value):
                        ?><?$productRequest=sfContext::getInstance()->getRequest()->getParameter("product");
                        if(!is_array($productRequest))
                            $productRequest=array("dop_info_products_list"=>array());?>
                        <div align="left"><input type="checkbox" value="<?= $dop_info_value->getId(); ?>"  
                            <?php if ((array_search($dop_info_value->getId(), $form['dop_info_products_list']->getValue()) !== false and $form->isNew() === false)
                                or (isset($productRequest['dop_info_products_list']) and array_search($dop_info_value->getId(), $productRequest['dop_info_products_list'])!==false)) echo " checked=\"\"" ?>
                                                 name="product[dop_info_products_list][]">&nbsp;&nbsp;<?= $dop_info_value->getValue(); ?></div>
                    <?php endforeach; ?> 
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>