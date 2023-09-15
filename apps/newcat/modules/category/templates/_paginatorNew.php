
<?php
if ($sortOrder != "") {
    $sortingUrl = "&amp;sortOrder=" . $sortOrder . "&amp;direction=" . $direction;
}
if ($category['slug'] == "Luchshaya_tsena"):
    ?>
    <div class="paginator-box" style="width: <?= ($pagesCount * 33) + 33 ?>px;">
        <?php if ($sf_request->getParameter('page', 1) == 1) {
            ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_show', array('slug' => $category['slug'])) ?>?page=1<?= $sortingUrl ?>" class="first"></a>
            <a href="<?php echo url_for('category_show', array('slug' => $category['slug'])) ?>?page=<?php echo ($sf_request->getParameter('page', 1) - 1) ?><?= $sortingUrl ?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($sf_request->getParameter('page', 1) == ($pagesCount) + 1) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_show', array('slug' => $category['slug'])) ?>?page=<?php echo ($page + 1) ?><?= $sortingUrl ?>" class="next"></a>
            <a href="<?php echo url_for('category_show', array('slug' => $category['slug'])) ?>?page=<?= $pagesCount + 1 ?><?= $sortingUrl ?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= ($pagesCount * 33) + 33 ?>px;">
            <ul>
                <?php foreach ($pager->getLinks(9) as $page): ?>
                    <?php if ($page == $sf_request->getParameter('page', 1)): ?>
                        <li class="active"><a href="<?php echo url_for('category_show', array('slug' => $category['slug'])) ?>?page=<?php echo $page ?><?= $sortingUrl ?>"><span><?php echo $page ?></span></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo url_for('category_show', array('slug' => $category['slug'])) ?>?page=<?php echo $page ?><?= $sortingUrl ?>"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if ($page + 1 == $sf_request->getParameter('page', 1)): ?>
                    <li class="active"><a href="<?php echo url_for('category_show', array('slug' => $category['slug'])) ?>?page=<?php echo $page + 1 ?><?= $sortingUrl ?>"><span><?php echo $page + 1 ?></span></a></li>
                <?php else: ?>
                    <li><a href="<?php echo url_for('category_show', array('slug' => $category['slug'])) ?>?page=<?php echo $page + 1 ?><?= $sortingUrl ?>"><span><?php echo $page + 1 ?></span></a></li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
<?php else: ?>
    <?php if ($page != ($pagesCount)) {
        ?>
        <div class="btnLoadProducts" onclick="LoadProducts(this);" style="
    width: 150px;
    background: none;
    float:left;">
            <div style="    background-color: #c3060e;
                 border-radius: 15px;
                 width: 30px;
                 color: #fff;
    font-size: 25px;
    text-align: center;
    padding: 4px 0;
    height: 22px;
    float: left">+</div>
    <div class="Text" style="float: left;
    margin-left: 5px;
    margin-top: 5px;
    text-decoration: underline;">
                Показать еще 30</div>
        </div>
    <?php } ?>
    <?php
    JSInPages("function LoadProducts(button){
                    $('#pageId-'+$('#pageFromFilter').val()).removeClass('active');
                    $('#pageFromFilter').val(parseInt($('#pageFromFilter').val(),10)+1);
                    var queryString = $('#categoryFilters').formSerialize();
                    var redirect = '" . explode('?', $_SERVER['REQUEST_URI'])[0] . "?' + queryString;
                    history.pushState('', '', redirect);


                    $('input[name=loadProducts]').val('1');
                    var queryString = $('#categoryFilters').formSerialize();
                    var redirect = '" . explode('?', $_SERVER['REQUEST_URI'])[0] . "?' + queryString;

                    $.post( redirect, queryString, function( data ) {
                        $('div#paginationBoxPage').html($('<div>'+data+'</div>').find('div#paginationBoxInfo').html());
                        jQuery.each($(data), function(i, val) {
                            if($(val).attr('id')!='paginationBoxInfo'){
                                $('#productsShow ul.product').append(val);
                            }
                        });
                    } ).always(function() {
                        $('#productsShow ul.product').find('img.thumbnails').each(function () {
                            $(this).attr('src', $(this).attr('data-original'));
                        });
                    });



                    $('input[name=loadProducts]').val('0');

                    $('#pageId-'+$('#pageFromFilter').val()).addClass('active');
                    if(parseInt($('#pageFromFilter').val(),10)==" . $pagesCount . "){
                        $(button).remove();
                    }

                    return false;
                }");
    ?>

    <?php
    JSInPages("function setPages(id){

                    $('#pageFromFilter').val(id);

                    jQuery('#categoryFilters').submit();
                    window.scrollTo(0,0);
                    return false;
                }");
    ?>
    <div class="paginator-box" style="width: <?= ((($pagesCount - 9) > 0 ? 9 : $pagesCount)) * 30 + 5?>px;">
        <?php if ($page != 1) {
            ?>
            <a class="prev-btn" href="<?php echo url_for('category_show', array('slug' => $category['this_slug'])) ?>" onclick="setPages(<?= $page - 1 ?>);
                    return false;">Предыдущая</a>
           <?php }
           ?>
           <?php if ($page != ($pagesCount)) {
            ?>
            <a class="next-btn" href="<?php echo url_for('category_show', array('slug' => $category['this_slug'])) ?>" onclick="setPages(<?= $page + 1 ?>);
                    return false;">Следующая</a>
           <?php }
           ?>
        <div class="paginator" style="width: <?= ((($pagesCount - 9) > 0 ? 9 : $pagesCount)) * 30 + 5 ?>px;">
            <ul>
                <?php
                /*$stopPageNum = ((($sf_request->getParameter('page', 1) - 10) > 1 ? ($sf_request->getParameter('page', 1) - 10) : 1) + 14);
                $stopPageNum = $stopPageNum > $pagesCount ? $pagesCount : $stopPageNum;
                for ($page = (($sf_request->getParameter('page', 1) - 10) > 1 ? ($sf_request->getParameter('page', 1) - 10) : 1); $page <= $stopPageNum; $page++):
                    ?>
                    <?php if ($page == $sf_request->getParameter('page', 1)): ?>
                        <li class="active" id="pageId-<?= $page ?>"><a href="<?php echo url_for('category_show', array('slug' => $category['this_slug'])) ?>?page=<?= $page ?>" onclick="setPages(<?= $page ?>);
                                return false;"><span><?php echo $page ?></span></a></li>
                        <?php else: ?>
                        <li id="pageId-<?= $page ?>"><a href="<?php echo url_for('category_show', array('slug' => $category['this_slug'])) ?>?page=<?= $page ?>" onclick="setPages(<?= $page ?>);
                                return false;"><span><?php echo $page ?></span></a></li>
                        <?php endif; ?>
                    <?php endfor;*/ ?>

                <?php
        if ($pagesCount <= 9) {
            for ($i = 1; $i <= $pagesCount; $i++) {
                ?>
                <li<?= $i == $page ? ' class="active"' : '' ?>>
                    <div<?= $i == $page ? ' class="active pageId-'.$i.'"' : ' class="pageId-'.$i.'"' ?> onclick="setPages(<?=$i?>)">
                        <?= $i ?>
                    </div>
                </li>
                <?php
            }
        } elseif ($page <= 5) {
            for ($i = 1; $i <= 7; $i++) {
                ?>
                <li<?= $i == $page ? ' class="active"' : '' ?>>
                    <div<?= $i == $page ? ' class="active pageId-'.$i.'"' : ' class="pageId-'.$i.'"' ?> onclick="setPages(<?=$i?>)" class="pageId-<?=$i?>">
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
                <div onclick="setPages(<?=$pagesCount?>)">
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
            for ($i = $page-2; $i <= $page+2; $i++) {
                ?>
                <li<?= $i == $page ? ' class="active"' : '' ?>>
                    <div<?= $i == $page ? ' class="active pageId-'.$i.'"' : ' class="pageId-'.$i.'"' ?> onclick="setPages(<?=$i?>)" class="pageId-<?=$i?>">
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
                <div onclick="setPages(<?=$pagesCount?>)">
                    <?= $pagesCount ?>
                </div>
            </li>
            <?php
        }else{
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
            for ($i = $pagesCount-6; $i <= $pagesCount; $i++) {
                ?>
                <li<?= $i == $page ? ' class="active"' : '' ?>>
                    <div<?= $i == $page ? ' class="active pageId-'.$i.'"' : ' class="pageId-'.$i.'"' ?> onclick="setPages(<?=$i?>)" class="pageId-<?=$i?>">
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
<?php endif; ?>
<?
if ($category['tags'] != "") {
    ?><div style="float: right; color: #c0c0c0">Тэги: <a href="#" onclick="$('#tags').toggle();
                return false" class="more-expand" style="float: right;margin: 0 5px;"></a> <div id="tags" style="display: none; float: right;margin: 0 5px;"><?= $category['tags'] ?></div> </div><?
    }
    ?>
<script type="text/javascript">
    var ad_category = "<?= $category['this_id'] ?>";   // required

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
