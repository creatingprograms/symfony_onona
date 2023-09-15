<?php
/*кэшируется*/
if ($sortOrder != "") {
    $sortingUrl = "&amp;sortOrder=" . $sortOrder . "&amp;direction=" . $direction;
}
if ($category->getSlug() == "Luchshaya_tsena"):
    ?>
    <div class="paginator-box" style="width: <?= (count($pager->getLinks(9)) * 33) + 33 ?>px;">
        <?php if ($sf_request->getParameter('page', 1) == 1) {
            ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=1<?= $sortingUrl ?>" class="first"></a>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo ($sf_request->getParameter('page', 1) - 1) ?><?= $sortingUrl ?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($sf_request->getParameter('page', 1) == (count($pager->getLinks(20))) + 1) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo ($pager->getPage() + 1) ?><?= $sortingUrl ?>" class="next"></a>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?= count($pager->getLinks(100)) + 1 ?><?= $sortingUrl ?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= (count($pager->getLinks(9)) * 33) + 33 ?>px;">
            <ul>
                <?php foreach ($pager->getLinks(9) as $page): ?>
                    <?php if ($page == $sf_request->getParameter('page', 1)): ?>
                        <li class="active"><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page ?><?= $sortingUrl ?>"><span><?php echo $page ?></span></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page ?><?= $sortingUrl ?>"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if ($page + 1 == $sf_request->getParameter('page', 1)): ?>
                    <li class="active"><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page + 1 ?><?= $sortingUrl ?>"><span><?php echo $page + 1 ?></span></a></li>
                <?php else: ?>
                    <li><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page + 1 ?><?= $sortingUrl ?>"><span><?php echo $page + 1 ?></span></a></li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
<?php
elseif ($pager->haveToPaginate()):

    $pagesCount = count($pager->getLinks(200));
    $page = $sf_request->getParameter('page', 1);
    JSInPages("function setPages(id){
    document.location.href = '" . url_for('category_show', array('slug' => $category['slug'])) . "?page='+id+'" . $sortingUrl . "';
                }");
    ?>
    <div class="paginator-box" style="width: <?= ((($pagesCount - 9) > 0 ? 9 : $pagesCount)) * 30 + 15 ?>px;">
        <?php if ($page != 1) {
            ?>
            <a class="prev" href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>" onclick="setPages(<?= $page - 1 ?>);
                        return false;"></a>
           <?php }
           ?>
           <?php if ($page != ($pagesCount)) {
               ?>
            <a class="next" href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>" onclick="setPages(<?= $page + 1 ?>);
                        return false;"></a>
           <?php }
           ?>
        <div class="paginator" style="width: <?= ((($pagesCount - 9) > 0 ? 9 : $pagesCount)) * 30 + 15 ?>px;">
            <ul>
                <?php /* $stopPageNum = ((($sf_request->getParameter('page', 1) - 10) > 1 ? ($sf_request->getParameter('page', 1) - 10) : 1) + 14);
                  $stopPageNum = $stopPageNum > $pagesCount ? $pagesCount : $stopPageNum;
                  for ($page = (($sf_request->getParameter('page', 1) - 10) > 1 ? ($sf_request->getParameter('page', 1) - 10) : 1); $page <= $stopPageNum; $page++):
                  ?>
                  <?php if ($page == $sf_request->getParameter('page', 1)): ?>
                  <li class="active" id="pageId-<?= $page ?>"><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?= $page ?>" onclick="setPages(<?= $page ?>);
                  return false;"><span><?php echo $page ?></span></a></li>
                  <?php else: ?>
                  <li id="pageId-<?= $page ?>"><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?= $page ?>" onclick="setPages(<?= $page ?>);
                  return false;"><span><?php echo $page ?></span></a></li>
                  <?php endif; ?>
                  <?php endfor; */ ?>

                <?php
                if ($pagesCount <= 9) {
                    for ($i = 1; $i <= $pagesCount; $i++) {
                        ?>
                        <li<?= $i == $page ? ' class="active"' : '' ?>>
                            <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)">
            <?= $i ?>
                            </div>
                        </li>
                        <?php
                    }
                } elseif ($page <= 5) {
                    for ($i = 1; $i <= 7; $i++) {
                        ?>
                        <li<?= $i == $page ? ' class="active"' : '' ?>>
                            <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)" class="pageId-<?= $i ?>">
            <?= $i ?>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                    <li>
                        •••
                    </li>
                    <li>
                        <div onclick="setPages(<?= $pagesCount ?>)">
        <?= $pagesCount ?>
                        </div>
                    </li>
                    <?php
                } elseif ($page < $pagesCount - 4) {
                    ?>
                    <li>
                        <div onclick="setPages(1)" class="pageId-1">
                            1
                        </div>
                    </li>
                    <li>
                        •••
                    </li>
                    <?php
                    for ($i = $page - 2; $i <= $page + 2; $i++) {
                        ?>
                        <li<?= $i == $page ? ' class="active"' : '' ?>>
                            <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)" class="pageId-<?= $i ?>">
            <?= $i ?>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                    <li>
                        •••
                    </li>
                    <li>
                        <div onclick="setPages(<?= $pagesCount ?>)">
        <?= $pagesCount ?>
                        </div>
                    </li>
                    <?php
                } else {
                    ?>
                    <li>
                        <div onclick="setPages(1)" class="pageId-1">
                            1
                        </div>
                    </li>
                    <li>
                        •••
                    </li>
                    <?php
                    for ($i = $pagesCount - 6; $i <= $pagesCount; $i++) {
                        ?>
                        <li<?= $i == $page ? ' class="active"' : '' ?>>
                            <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)" class="pageId-<?= $i ?>">
            <?= $i ?>
                            </div>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>

<?/*

    <div class="paginator-box" style="width: <?= (count($pager->getLinks(9)) * 33) ?>px;">
        <?php if ($pager->getPage() == 1) {
            ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=1<?= $sortingUrl ?>" class="first"></a>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo ($pager->getPage() - 1) ?><?= $sortingUrl ?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($pager->getPage() == (count($pager->getLinks(20)))) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo ($pager->getPage() + 1) ?><?= $sortingUrl ?>" class="next"></a>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?= count($pager->getLinks(20)) ?><?= $sortingUrl ?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= (count($pager->getLinks(9)) * 33) ?>px;">
            <ul>
                <?php foreach ($pager->getLinks(9) as $page): ?>
                    <?php if ($page == $sf_request->getParameter('page', 1)): ?>
                        <li class="active"><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page ?><?= $sortingUrl ?>"><span><?php echo $page ?></span></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page ?><?= $sortingUrl ?>"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
    <?php endforeach; ?>

            </ul>
        </div>
    </div>*/?>
<?php endif; ?>
<?
if ($category->getTags() != "") {
    ?><div style="float: right; color: #c0c0c0">Тэги: <a href="#" onclick="$('#tags').toggle();
                    return false" class="more-expand" style="float: right;margin: 0 5px;"></a> <div id="tags" style="display: none; float: right;margin: 0 5px;"><?= $category->getTags() ?></div> </div><?
    }
    ?>
<script type="text/javascript">
    var ad_category = "<?= $category->getId() ?>";   // required

    window._retag = window._retag || [];
    window._retag.push({code: "9ce8886735", level: 1});
    (function () {
        var s = document.createElement("script");
        s.async = true;
        s.src = (document.location.protocol == "https:" ? "https:" : "http:") + "//cdn.admitad.com/static/js/retag.js";
        var a = document.getElementsByTagName("script")[0]
        a.parentNode.insertBefore(s, a);
    })()
</script>
