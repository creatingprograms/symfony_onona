
<ul class="item-list">
    <?php
    $products = $pager->getResults();

    foreach ($products as $product):
        $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
        $comments = Doctrine_Core::getTable('Comments')
                ->createQuery('c')
                ->where("is_public = '1'")
                ->addWhere('product_id = ?', $product->getId())
                ->orderBy('id ASC')
                ->execute();
  
        
        include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId()."-".sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article')));
        
    endforeach;
    ?>
</ul><!--item-list end-->


<?php if ($pager->haveToPaginate()): ?>
    <div class="paginator-box" style="width: <?= count($pager->getLinks(9)) * 37 ?>px;">
        <?php if ($pager->getPage() == 1) {
            ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_noncount') ?>?page=1" class="first"></a>
            <a href="<?php echo url_for('category_noncount') ?>?page=<?php echo ($pager->getPage() - 1) ?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($pager->getPage() == count($pager->getLinks(100))) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_noncount') ?>?page=<?php echo ($pager->getPage() + 1) ?>" class="next"></a>
            <a href="<?php echo url_for('category_noncount') ?>?page=<?= count($pager->getLinks(100)) ?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= count($pager->getLinks(9)) * 37 ?>px;">
            <ul>
                <?php foreach ($pager->getLinks(9) as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                        <li class="active"><a href="<?php echo url_for('category_noncount') ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo url_for('category_noncount') ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>








