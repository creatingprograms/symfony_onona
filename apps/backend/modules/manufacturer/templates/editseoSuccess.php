<?php use_helper('I18N', 'Date') ?>
<?php include_partial('manufacturer/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Редактирование производителя "%%name%%"', array('%%name%%' => $manufacturer->getName()), 'messages') ?></h1>

  <?php include_partial('manufacturer/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('manufacturer/form_header', array('manufacturer' => $manufacturer, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('manufacturer/form', array('manufacturer' => $manufacturer, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('manufacturer/form_footer', array('manufacturer' => $manufacturer, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
