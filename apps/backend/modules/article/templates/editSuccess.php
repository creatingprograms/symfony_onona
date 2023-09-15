<?php use_helper('I18N', 'Date') ?>
<?php include_partial('article/assets') ?>

<div id="sf_admin_container">
  <h1><a href="/sexopedia/<?=$article->getSlug()?>" target="_blank"><?php echo __('Редактирование статьи "%%name%%"', array('%%name%%' => $article->getName()), 'messages') ?></a></h1>

  <?php include_partial('article/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('article/form_header', array('article' => $article, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('article/form', array('article' => $article, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('article/form_footer', array('article' => $article, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
