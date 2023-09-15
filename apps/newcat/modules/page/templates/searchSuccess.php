<?php use_helper('I18N', 'Date') ?>
<?php
mb_internal_encoding('UTF-8');
slot('metaTitle', strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString")));
slot('metaKeywords', strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString")));
slot('metaDescription', strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString")));
slot('rightBlock', true);
?>
<div class="popup-15-need"></div>
<div style="padding: 10px;">
    <?php if ($collections->count() > 0):
        ?>
        <h3><?php echo __('Коллекции', array(), 'messages') ?></h3>


        <?= $collections->count() ?> результата(ов)<br /><br />

        <?php foreach ($collections as $i => $collection): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
            <?= $i ?>. <a href="/collection/<?= $collection->getSlug(); ?>"><?= $collection->getName(); ?></a><br />

        <?php endforeach; ?>

        <br />
        <br />
        <?php
    endif;
    if ($manufacturer->count() > 0):
        ?>
        <h3><?php echo __('Производители', array(), 'messages') ?></h3>


        <?= $manufacturer->count() ?> результата(ов)<br /><br />

        <?php foreach ($manufacturer as $i => $manufacture): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
            <?= $i ?>. <a href="/manufacturer/<?= $manufacture->getSlug(); ?>"><?= $manufacture->getName(); ?></a><br />

        <?php endforeach; ?>

        <br />
        <br />
        <?php
    endif;
    if ($categorys->count() > 0):
        ?>
        <h3><?php echo __('Категории', array(), 'messages') ?></h3>


        <?= $categorys->count() ?> результата(ов)<br /><br />

        <?php foreach ($categorys as $i => $category): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
            <?= $i ?>. <a href="/category/<?= $category->getSlug(); ?>"><?= $category->getName(); ?></a><br />

        <?php endforeach; ?>

        <br />
        <br />
        <?php
    endif;

    $products = $pager->getResults();
    if ($pager->getNbResults() > 0):
        ?>
        <h3><?php echo __('Товары', array(), 'messages') ?></h3>


        <?= $pager->getNbResults() ?> результата(ов)
        <?/*
        <style type="text/css">
            ul.item-list li{
                //margin: 0 12px 32px 0;
                //width: 247px;
            }
        </style>*/?>

        <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>

        <ul class="item-list gtm-category-show rr-crouch" data-list="Результаты поиска">
            <?
            foreach ($products as $product):
              $gdeslonCodes[]=$product->getId().':'.$product->getPrice();
              $advcakeItems[]=[
                'id' => $product->getId(),
                'name' => $product->getName(),
                'categoryId' => $product->getGeneralCategory()->getId(),
                'categoryName' => $product->getGeneralCategory()->getName(),
                // 'price' => $product->getPrice(),
              ];
                if ((strtotime($product->getEndaction()) + 86399) < time() and $product->getEndaction() != "") {
                    $product->setEndaction(NULL);
                    if ($product->getDiscount() > 0) {
                        $product->setPrice($product->getOldPrice());
                        $product->setOldPrice(Null);
                    }
                    $product->setDiscount(0);
                    $product->setBonuspay(NULL);
                    $product->setStep(NULL);
                    $product->save();
                }

                include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article')));

            endforeach;
            ?>
        </ul><!--item-list end-->



        <br /><br />

        <?php if ($pager->haveToPaginate()): ?>
            <div class="paginator-box" style="width: <?= (count($pager->getLinks(9)) * 37) ?>px;">
                <?php if ($pager->getPage() == 1) {
                    ?>
                    <a class="first disable"></a>
                    <a class="prev disable"></a>
                    <?php
                } else {
                    ?>
                    <a href="<?php echo url_for('search', array()) ?>?searchString=<?= $_GET['searchString'] ?>&page=1" class="first"></a>
                    <a href="<?php echo url_for('search', array()) ?>?searchString=<?= $_GET['searchString'] ?>&page=<?php echo ($pager->getPage() - 1) ?>" class="prev"></a>
                <?php }
                ?>
                <?php if ($pager->getPage() == (count($pager->getLinks(20)))) {
                    ?>
                    <a class="next disable"></a>
                    <a class="last disable"></a>
                    <?php
                } else {
                    ?>
                    <a href="<?php echo url_for('search', array()) ?>?searchString=<?= $_GET['searchString'] ?>&page=<?php echo ($pager->getPage()) ?>" class="next"></a>
                    <a href="<?php echo url_for('search', array()) ?>?searchString=<?= $_GET['searchString'] ?>&page=<?= count($pager->getLinks(20)) ?>" class="last"></a>
                <?php }
                ?>
                <div class="paginator" style="width: <?= (count($pager->getLinks(9)) * 37) ?>px;">
                    <ul>
                        <?php foreach ($pager->getLinks(9) as $page): ?>
                            <?php if ($page == $sf_request->getParameter('page', 1)): ?>
                                <li class="active"><a href="<?php echo url_for('search', array()) ?>?searchString=<?= $_GET['searchString'] ?>&page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                                        <?php else: ?>
                                <li><a href="<?php echo url_for('search', array()) ?>?searchString=<?= $_GET['searchString'] ?>&page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <br />
        <br />
        <div data-retailrocket-markup-block="5ba3a5f597a52530d41bb240" data-search-phrase="<?=strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString"))?>"></div>
        <?php
    endif;
    if ($pages->count() > 0):
        ?>
        <h3><?php echo __('Страницы', array(), 'messages') ?></h3>


        <?= $pages->count() ?> результата(ов) <br /><br />

        <?php foreach ($pages as $i => $page): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
            <?= $i ?>. <a href="/<?= $page->getSlug(); ?>"><?= $page->getName(); ?></a><br />
        <?php endforeach; ?>

        <?php
    endif;
    if ($articles->count() > 0):
        ?>
        <h3><?php echo __('Статьи', array(), 'messages') ?></h3>


        <?= $articles->count() ?> результата(ов) <br /><br />

        <?php foreach ($articles as $i => $article): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
            <?= $i ?>. <a href="/sexopedia/<?= $article->getSlug(); ?>"><?= $article->getName(); ?></a><br />
        <?php endforeach; ?>

        <?php
    endif;
    if ($pages->count() == 0 and $products->count() == 0 and $categorys->count() == 0 and $manufacturer->count() == 0 and $articles->count() == 0):
        echo "По вашему запросу ничего не найдено.";?>
        <div data-retailrocket-markup-block="5ba3a60c97a52530d41bb246" data-search-phrase="<?=strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString"))?>"></div>
    <?else : ?>

    <? endif; ?>
    </div>
    <? //slot('gdeSlonCodes', '&codes='.implode(',', $gdeslonCodes)); ?>
    <? slot('gdeSlonMode', 'list'); ?>
    <? slot('advcake', 7); ?>
    <? slot('advcake_list', [
      'products' => $advcakeItems,
    ]);?>
