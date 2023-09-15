<?php use_helper('I18N', 'Date') ?>
<?php include_partial('product/assets') ?>


    <?php include_partial('product/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
 