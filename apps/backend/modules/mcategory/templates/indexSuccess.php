<?php use_helper('I18N', 'Date') ?>
<?php include_partial('mcategory/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Категории', array(), 'messages') ?></h1>

  <?php include_partial('mcategory/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('mcategory/list_header', array('pager' => $pager)) ?>
  </div>

  <div id="sf_admin_bar">
    <?php include_partial('mcategory/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <!--<ul class="sf_admin_actions">
      <?php include_partial('mcategory/list_actions', array('helper' => $helper)) ?>
    </ul>-->
    <form action="<?php echo url_for('category_mcategory', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('mcategory/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('mcategory/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
