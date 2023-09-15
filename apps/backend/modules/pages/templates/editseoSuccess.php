<?php use_helper('I18N', 'Date') ?>
<?php include_partial('pages/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Редактирование страницы "%%name%%"', array('%%name%%' => $page->getName()), 'messages') ?></h1>

  <?php include_partial('pages/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('pages/form_header', array('page' => $page, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('pages/form', array('page' => $page, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('pages/form_footer', array('page' => $page, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
