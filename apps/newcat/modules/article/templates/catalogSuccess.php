<?php
$startTitle = $catalog->getName();

slot('metaTitle', $startTitle . " * Энциклопедия секса * Главная");
slot('metaKeywords', $startTitle . " * Энциклопедия секса * Главная");
slot('metaDescription', $startTitle . " * Энциклопедия секса * Главная");
slot('articleleftBlock', true);
slot('articlerightpad', true);
?><ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li><a href="/sexopedia">Энциклопедия секса </a></li>
    <li><?= $catalog->getName() ?> </li>
</ul>
<h1 class="title"><?= $catalog->getName() ?></h1>
<?= $catalog->getContent() ?>


<?php
//$articles = $pager->getResults();
$articles=$pagerArticles;
foreach ($articles as $article):
    ?>
    <div style=" padding:10px 0;border-bottom: 1px dashed #DDD;" class="divArticleHover">
        <div style="float: left;text-align: center;"><?php if ($article->getImg() != "") { ?><a href="/sexopedia/<?= $article->getSlug() ?>"><img class="thumbnails" src="/images/pixel.gif" data-original="/uploads/photo/<?= $article->getImg() ?>" style="margin: 0 10px;"/></a><?php } else { ?>&nbsp; <?php } ?></div>
        <div style="width: 762px;">
            <div><a href="/sexopedia/<?= $article->getSlug() ?>"><span style="font: 14px/16px Tahoma,Geneva,sans-serif;"><?= $article->getName() ?></span></a></div>
            <?php
            $comments = Doctrine_Core::getTable('Comments')
                    ->createQuery('c')
                    ->where("is_public = '1'")
                    ->addWhere('article_id = ?', $article->getId())
                    ->orderBy('created_at desc')
                    ->execute();
            ?>
    <?php if ($comments->count() > 0): ?>

                <div style="float: right;color: #707070;  font: 10px/13px Tahoma,Geneva,sans-serif;"><a class="rewiev" href="/sexopedia/<?= $article->getSlug() ?>/?comment=true#comments">Отзывы: <?= $comments->count() ?></a></div>
    <?php endif; ?>
            <div class="stars" style="margin: 10px 0 4px;">
                <span style="width:<?= $article->getRating() > 0 ? (@round($article->getRating() / $article->getVotesCount())) * 10 : 0 ?>%;"></span>
            </div>
            <div  style="height: 100px;">
                <p>
    <?= strip_tags($article->getPrecontent()) ?>
                </p>
            </div>
                <?php if ($article->getTags() != "") { ?>
                <div style="color: #888">Тэги:
                    <?php
                    $tags = explode(",", $article->getTags());
                    foreach ($tags as $key => $tag) {
                        echo trim($tag);
                        if ($key != (count($tags) - 1))
                            echo ", ";
                    }
                    ?>
                </div>
    <?php } ?>
        </div>
    </div>
<?php endforeach; ?>


    <?php if ($pager->haveToPaginate()): ?>
    <div class="paginator-box" style="width: <?= count($pager->getLinks(9)) * 37 ?>px;">
        <?php if ($pager->getPage() == 1) {
            ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=1" class="first"></a>
            <a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?php echo ($pager->getPage() - 1) ?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($pager->getPage() == count($pager->getLinks(20))) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?php echo ($pager->getPage() + 1) ?>" class="next"></a>
            <a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?= count($pager->getLinks(20)) ?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= count($pager->getLinks(9)) * 37 ?>px;">
            <ul>
                <?php foreach ($pager->getLinks(9) as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                        <li class="active"><a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
    <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
