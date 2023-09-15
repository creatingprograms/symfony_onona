<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_category_products_list<?php $form['category_products_list']->hasError() and print ' errors' ?>">
    <?php echo $form['category_products_list']->renderError() ?>
    <div>
        <?php echo $form['category_products_list']->renderLabel('Категории') ?> 

        <div class = "content"><?php echo $form['category_products_list']->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes) ?></div>
        <div class = "content"><?php
            if (count($form['category_products_list']->getValue()) > 0) {
                $categorys = CategoryTable::getInstance()->createQuery()->where('id in(' . implode(",", $form['category_products_list']->getValue()) . ')')->execute();
                foreach ($categorys as $category) {
                    ?><div><?= $category->getName() ?> <span style="cursor:pointer;font-weight: bold" onclick="$('#product_category_products_list option[value=<?= $category->getId() ?>]').removeAttr('selected');
                            $(this).parent().remove()">X</span> <br /></div>
                        <?
                    }
                }
                ?></div>
    </div>
</div>