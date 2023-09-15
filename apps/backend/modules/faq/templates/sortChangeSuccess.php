<?php use_helper('I18N', 'Date') ?>
<?php include_partial('faq/assets') ?>


    <?php include_partial('faq/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
