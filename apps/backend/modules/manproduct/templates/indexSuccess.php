<?php use_helper('I18N', 'Date') ?>
<?php include_partial('manproduct/assets') ?>

<div id="sf_admin_container">
    <h1><?php echo __('Товары', array(), 'messages') ?></h1>

    <?php include_partial('manproduct/flashes') ?>

    <div id="sf_admin_header">
        <?php include_partial('manproduct/list_header', array('pager' => $pager)) ?>
    </div>

    <div id="sf_admin_bar">
        <?php  include_partial('manproduct/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
    </div>

    <div id="sf_admin_content">
        <!--<ul class="sf_admin_actions">
            <?php include_partial('manproduct/list_actions', array('helper' => $helper)) ?>  </ul>-->

            Количество товаров добавленное вами: <?php $productsCount=  ProductTable::getInstance()->findByUser(sfContext::getInstance()->getUser()->getGuardUser()->getId()); echo $productsCount->count(); ?>

        <form action="<?php echo url_for('product_collection', array('action' => 'batch')) ?>" method="post">
            <?php include_partial('manproduct/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
            <ul class="sf_admin_actions">
                <?php include_partial('manproduct/list_batch_actions', array('helper' => $helper)) ?>
                <?php include_partial('manproduct/list_actions', array('helper' => $helper)) ?>
            </ul>
        </form>
    </div>

    <div id="sf_admin_footer">
        <?php include_partial('manproduct/list_footer', array('pager' => $pager)) ?>
    </div>
</div>
