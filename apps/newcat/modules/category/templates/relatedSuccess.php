<?php
/*$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
slot('canonicalSlugCategory', $category->getSlug() . $canonDop);
slot('leftBlock', true);
slot('metaTitle', $category->getTitle() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getTitle());
slot('metaKeywords', $category->getKeywords() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getKeywords());
slot('metaDescription', $category->getDescription() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getDescription());
*/

slot('leftBlock', true);
$page=$pager->getPage();
slot('metaTitle', 'Лидеры продаж | Сеть магазинов «Он и Она»'.($page!=1 ? ', страница '.$page : '').'');
slot('metaKeywords', 'Лидеры продаж');
slot('metaDescription', 'Предлагаем вам ознакомиться с лидерами продаж в секс-шопе «Он и Она». Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.'.($page!=1 ? ', страница '.$page : ''));
?>

<ul class="breadcrumbs">
    <li><a href="/">Секс-шоп главная</a></li>
    <li>Лидеры продаж</li>
</ul>
<h1 class="title">Лидеры продаж</h1>
<div class="blockSort">
        <div style="float: left;">
            Сортировать по: <a href="/related"<?= $sortOrder == "sortorder" ? " style='color: #c3060e;'" : "" ?>>Лучшая цена</a><span style="padding: 0 10px;">|</span><a href="/related?sortOrder=date"<?= $sortOrder == "date" ? " style='color: #c3060e;'" : "" ?>>Новинки</a><span style="padding: 0 10px;">|</span><a href="/related?sortOrder=price&direction=<?= ($direction == "asc" and $sortOrder == "price") ? 'desc' : 'asc' ?>"<?= $sortOrder == "price" ? " style='color: #c3060e;'" : "" ?>>Цена <span style="font-weight: bold;"><?= $sortOrder == "price" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a><span style="padding: 0 10px;">|</span><a href="/related?sortOrder=comments"<?= $sortOrder == "comments" ? " style='color: #c3060e;'" : "" ?>>Отзывы</a><span style="padding: 0 10px;">|</span><a href="/related?sortOrder=rating"<?= $sortOrder == "rating" ? " style='color: #c3060e;'" : "" ?>>Рейтинг</a>
        </div>
        <div style="float: right;"><span class="nonCountShow" onclick="if ($(this).text() == 'Скрыть отсутствующие товары') {
                    $(this).text('Показать отсутствующие товары');
                    $('.liProdNonCount').each(function (i) {
                        $(this).fadeOut(0);
                    })
                } else {
                    $(this).text('Скрыть отсутствующие товары');
                    $('.liProdNonCount').each(function (i) {
                        $(this).fadeIn(0);
                    })
                }
                ;
                viravnivanie();">Скрыть отсутствующие товары</span>
        </div>
    </div>
<script type="text/javascript">
    function CallPrint(strid)
    {
        var prtContent = $(strid);
        var prtCSS = '<link rel="stylesheet" href="/newdis/css/all.css" type="text/css" />';
        var WinPrint = window.open('','','left=50,top=50,width=800,height=700,toolbar=0,scrollbars=1,status=0');
        WinPrint.document.write('<div class="popup quick-view" style="margin: 0px; top: 10px; left: 10px;">');
        WinPrint.document.write(prtCSS);
        WinPrint.document.write(prtContent.html());
        WinPrint.document.write('</div></div></article>');
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }

</script>
<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
<div style="clear: both;"></div>
<ul class="item-list">


    <?php
    $products = $pager->getResults();

    foreach ($products as $product):
        ?>

        <?php include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId())) ?>

        <?php
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
            <a href="/related?page=1" class="first"></a>
            <a href="/related?page=<?php echo ($pager->getPage() - 1) ?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($pager->getPage() == count($pager->getLinks(20))) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="/related?page=<?php echo ($pager->getPage() + 1) ?>" class="next"></a>
            <a href="/related?page=<?= count($pager->getLinks(20)) ?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= count($pager->getLinks(9)) * 37 ?>px;">
            <ul>
                <?php foreach ($pager->getLinks(9) as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                        <li class="active"><a href="/related?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                    <?php else: ?>
                        <li><a href="/related?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
