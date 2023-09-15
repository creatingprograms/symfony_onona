<?php
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
//slot('canonicalSlugCategory', $category->getSlug() . $canonDop);
slot('leftBlock', true);
/* slot('metaTitle', $category->getTitle() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getTitle());
  slot('metaKeywords', $category->getKeywords() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getKeywords());
  slot('metaDescription', $category->getDescription() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getDescription()); */
/*
  slot('metaTitle', $category->getTitle() == '' ? $category->getName() : $category->getTitle());
  slot('metaKeywords', $category->getKeywords() == '' ? $category->getName() : $category->getKeywords());
  slot('metaDescription', $category->getDescription() == '' ? $category->getName() : $category->getDescription()); */
?>

<ul class="breadcrumbs">
    <li><a href="/">Секс-шоп главная</a></li>
    <li>Лучшая цена</li>
</ul>
<h1 class="title">Лучшая цена</h1>
<? /*
  <div class="item-list-tools">
  <form id="i2p2" action="/category/bestprice" method="post" name="i2p">
  <fieldset>
  <div class="col">
  <label>Количество товаров на странице:</label>
  <div class="select-holder">
  <select class="w57" name="items2page">
  <option <?= $items2page == 15 ? "selected=\"\"" : "" ?> value="15">15</option>
  <option <?= $items2page == 30 ? "selected=\"\"" : "" ?> value="30">30</option>
  <option <?= $items2page == 60 ? "selected=\"\"" : "" ?> value="60">60</option>
  <option <?= $items2page == 10000 ? "selected=\"\"" : "" ?> value="10000">Все</option>
  </select>
  </div>
  </div>
  <div class="col">
  <label>Сортировка товаров:</label>
  <div class="select-holder">
  <select class="w146" name="sortOrder">
  <option <?= $sortOrder == "sortorder" ? "selected=\"\"" : "" ?> value="sortorder">по умолчанию</option>
  <option <?= $sortOrder == "date" ? "selected=\"\"" : "" ?> value="date">дата поступления</option>
  <option <?= $sortOrder == "price" ? "selected=\"\"" : "" ?> value="price">цена</option>
  <!--<option value="name"  >название</option>-->
  <option <?= $sortOrder == "rating" ? "selected=\"\"" : "" ?> value="rating">рейтинг</option>
  <option <?= $sortOrder == "views" ? "selected=\"\"" : "" ?> value="views">кол-во просмотров</option>
  </select>
  </div>
  <div class="select-holder">
  <select class="w146" name="direction">
  <option <?= $direction == "asc" ? "selected=\"\"" : "" ?> value="asc">по возрастанию</option>
  <option <?= $direction == "desc" ? "selected=\"\"" : "" ?> value="desc">по убыванию</option>
  </select>
  </div>
  <div class="btn-holder">
  <div class="gray-btn">
  <span>ок</span>
  <input type="submit" value="ок" class="gray-btn" />
  </div>
  </div>
  </div>
  </fieldset>
  </form>
  </div> */ ?>
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

<?php
//$products = $pager->getResults();
foreach ($categorys as $category) {
    $countAction = 0;
    //echo $countAction;
    $products = ProductTable::getInstance()->createQuery()->where('step <> ""')->addWhere('generalcategory_id=' . $category->getId())->execute();
    if ($products->count() > 2):
        $products = ProductTable::getInstance()->createQuery()->where('step <> ""')->addWhere('generalcategory_id=' . $category->getId())->limit(3 * floor($products->count() / 3))->execute();
        ?><span style="color: #ff5b00;font: 17px/20px Tahoma,Geneva,sans-serif;"><a href="/category/<?= $category->getSlug() ?>" style="color: #ff5b00;"><? echo $category->getLovepricename() != "" ? $category->getLovepricename() : $category->getName() . "</a>";
        echo $category->getLovepricename()==""?" по лучшей цене в Рунете!":'';
        ?></span><? ?>
        <div style="background: url('/newdis/images/ram_bestprice.png');width:772px; height: 18px;"></div>
        <ul class="item-list" style="  margin: 0 -7px 0 0; background-color: #fffff6;">

            <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
            <?
        foreach ($products as $key => $product):
            ?>

                <?php include_component('category', 'productsbestprice', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId(), 'last' => (($key + 1) % 3) == 0 ? true : false)) ?>

                <?php
            endforeach;
            ?>
        </ul>
        <div style="background: url('/newdis/images/ram_bestprice.png') repeat scroll 0 18px transparent;width:772px; height: 18px;"></div><!--item-list end-->

        <?
    endif;
}
?>

<?php if ($pageCount > 1): ?>
    <div class="paginator-box" style="width: <?= ($pageCount > 9 ? 9 : $pageCount) * 37 ?>px;">
        <?php if ($page == 1) {
            ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_bestprice') ?>?page=1" class="first"></a>
            <a href="<?php echo url_for('category_bestprice') ?>?page=<?php echo ($page - 1) ?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($page == $pageCount) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('category_bestprice') ?>?page=<?php echo ($page + 1) ?>" class="next"></a>
            <a href="<?php echo url_for('category_bestprice') ?>?page=<?= $pageCount ?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= ($pageCount > 9 ? 9 : $pageCount) * 37 ?>px;">
            <ul>
                <?php
                if ($page > 5 and $page < $pageCount - 4) {
                    $pageStart = $page - 4;
                    $pageEnd = $page + 4;
                    $pageEnd = $pageEnd > $pageCount ? $pageCount : $pageEnd;
                    echo "444";
                } elseif ($page < 6) {
                    $pageStart = 1;
                    $pageEnd = 9;
                    $pageEnd = $pageEnd > $pageCount ? $pageCount : $pageEnd;
                } elseif ($page > $pageCount - 5) {
                    $pageStart = $pageCount - 8;
                    $pageEnd = $pageCount;
                }
                for ($i = $pageStart; $i <= $pageEnd; $i++) {
                    if ($page == $i):
                        ?>
                        <li class="active"><a href="<?php echo url_for('category_bestprice') ?>?page=<?php echo $i ?>"><span><?php echo $i ?></span></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo url_for('category_bestprice') ?>?page=<?php echo $i ?>"><span><?php echo $i ?></span></a></li>
                    <?php
                    endif;
                }
                /* foreach ($pager->getLinks(9) as $page): ?>
                  <?php if ($page == $pager->getPage()): ?>
                  <li class="active"><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                  <?php else: ?>
                  <li><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                  <?php endif; ?>
                  <?php endforeach; */
                ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
