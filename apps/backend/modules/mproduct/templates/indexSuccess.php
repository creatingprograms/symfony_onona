<?php use_helper('I18N', 'Date') ?>
<?php include_partial('mproduct/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Товары', array(), 'messages') ?></h1>

  <?php include_partial('mproduct/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('mproduct/list_header', array('pager' => $pager)) ?>
  </div>

  <div id="sf_admin_bar">
    <?php include_partial('mproduct/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <form action="<?php echo url_for('product_mproduct_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('mproduct/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('mproduct/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
