<td>
    <ul class="sf_admin_td_actions">
        <?php
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")):
            ?>
            <li class="sf_admin_action_promote">
                <?php echo link_to(__('Редактировать', array(), 'messages'), 'category/editseo?id=' . $category->getId(), array()) ?>
            </li>
            <?php
        else:
            ?>
            <li class="sf_admin_action_promote">
                <?php echo link_to(__('Вверх', array(), 'messages'), 'category/promote?id=' . $category->getId(), array()) ?>
            </li>
            <li class="sf_admin_action_demote">
                <?php echo link_to(__('Вниз', array(), 'messages'), 'category/demote?id=' . $category->getId(), array()) ?>
            </li>
            <li class="sf_admin_action_prodstats">
                <?php echo link_to(__('Статистика', array(), 'messages'), 'category/prodstats?id=' . $category->getId(), array()) ?>
            </li>
            <?php echo $helper->linkToEdit($category, array('params' => array(), 'class_suffix' => 'edit', 'label' => 'Edit',)) ?>
            <?php echo $helper->linkToDelete($category, array('params' => array(), 'confirm' => 'Are you sure?', 'class_suffix' => 'delete', 'label' => 'Delete',)) ?>
        <?php
        endif;
        ?>
    </ul>
</td>
