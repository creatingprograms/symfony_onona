<td>
    <ul class="sf_admin_td_actions">

        <?php
        if (sfContext::getInstance()->getUser()->hasPermission("All") or sfContext::getInstance()->getUser()->hasPermission("Admin contentportal") or $photos_user->getManagerId() == $sf_user->getGuardUser()->getId()):
            ?>
            <?php echo $helper->linkToEdit($photos_user, array('params' => array(), 'class_suffix' => 'edit', 'label' => 'Edit',)) ?>
            <?php echo $helper->linkToDelete($photos_user, array('params' => array(), 'confirm' => 'Are you sure?', 'class_suffix' => 'delete', 'label' => 'Delete',)) ?>
            <?php
        else:
            ?><?php echo $helper->linkToEdit($photos_user, array('params' => array(), 'class_suffix' => 'edit', 'label' => 'Просмотр',)) ?>
        <?php
        endif;
        ?>
    </ul>
</td>
