<?php use_helper('I18N', 'Date') ?>
<?php include_partial('comments/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Создание комментария', array(), 'messages') ?></h1>

  <?php include_partial('comments/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('comments/form_header', array('comments' => $comments, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('comments/form', array('comments' => $comments, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('comments/form_footer', array('comments' => $comments, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
