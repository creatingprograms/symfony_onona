
<td>
    <ul class="sf_admin_td_actions">
        <?php
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")):
            ?>
            <li class="sf_admin_action_promote">
                <?php echo link_to(__('Редактировать', array(), 'messages'), 'pages/editseo?id=' . $page->getId(), array()) ?>
            </li>
            <?php
        else:
            ?>
            <?php echo $helper->linkToEdit($page, array('params' => array(), 'class_suffix' => 'edit', 'label' => 'Edit',)) ?>
            <?php echo $helper->linkToDelete($page, array('params' => array(), 'confirm' => 'Are you sure?', 'class_suffix' => 'delete', 'label' => 'Delete',)) ?>

        <?php
        endif;
        ?>
    </ul>
</td>
