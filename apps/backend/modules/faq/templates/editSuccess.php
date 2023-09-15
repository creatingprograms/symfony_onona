<?php use_helper('I18N', 'Date') ?>
<?php include_partial('faq/assets') ?>

<div id="sf_admin_container">
  <h1><a href="/faq/<?=$faq->getSlug()?>" target="_blank"><?php echo __('Редактирование статьи "%%name%%"', array('%%name%%' => $faq->getName()), 'messages') ?></a></h1>

  <?php include_partial('faq/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('faq/form_header', array('faq' => $faq, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('faq/form', array('faq' => $faq, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('faq/form_footer', array('faq' => $faq, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
