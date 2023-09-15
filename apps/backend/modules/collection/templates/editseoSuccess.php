<?php use_helper('I18N', 'Date') ?>
<?php include_partial('collection/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Редактирование коллекции "%%name%%"', array('%%name%%' => $collection->getName()), 'messages') ?></h1>

  <?php include_partial('collection/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('collection/form_header', array('collection' => $collection, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('collection/form', array('collection' => $collection, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('collection/form_footer', array('collection' => $collection, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
