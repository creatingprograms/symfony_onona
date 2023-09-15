<?php use_helper('I18N', 'Date') ?>
<?php include_partial('manproduct/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Редактирование товара "%%name%%"', array('%%name%%' => $product->getName()), 'messages') ?></h1>

  <?php include_partial('manproduct/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('manproduct/form_header', array('product' => $product, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('manproduct/form', array('product' => $product, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('manproduct/form_footer', array('product' => $product, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
