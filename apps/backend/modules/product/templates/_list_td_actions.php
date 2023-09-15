
<td>
    <ul class="sf_admin_td_actions">
        <?php
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")):
            ?>
            <li class="sf_admin_action_promote">
                <?php echo link_to(__('Редактировать', array(), 'messages'), 'product/editseo?id=' . $product->getId(), array()) ?>
            </li>
            <?php
        elseif (sfContext::getInstance()->getUser()->hasPermission("Просмотр фото и видео товаров")):
            ?>
            <li class="sf_admin_action_promote">
                <?php echo link_to(__('Посмотреть', array(), 'messages'), 'product/edit?id=' . $product->getId(), array()) ?>
            </li>
            <?php
        else:
            ?>
            <?php echo $helper->linkToEdit($product, array('params' => array(), 'class_suffix' => 'edit', 'label' => 'Edit',)) ?>
            <?php echo $helper->linkToDelete($product, array('params' => array(), 'confirm' => 'Are you sure?', 'class_suffix' => 'delete', 'label' => 'Delete',)) ?>

        <?php
        endif;
        ?>
    </ul>
</td>
