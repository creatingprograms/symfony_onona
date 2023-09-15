<?php use_helper('I18N', 'Date') ?>
<?php include_partial('category/assets') ?>


    <?php include_partial('category/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
 