<?php
slot('leftBlock', true);
slot('metaTitle', "Лидеры продаж > ".$catalog->getName() . " " . $catalog->getDescription() .(sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница ".sfContext::getInstance()->getRequest()->getParameter('page', 1): "")." – Секс шоп Он и Она" );
slot('metaKeywords', "Лидеры продаж > ".$catalog->getName() . " " . $catalog->getDescription());
slot('metaDescription', "Лидеры продаж > ".$catalog->getName() . " " . $catalog->getDescription());
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
slot('canonicalSlugRelCat', $catalog->getSlug() . $canonDop);
$products = $pager->getResults();
$prouctCountAction = 0;
if ($products[2]->getEndaction() != "" and $products[2]->getCount() > 0) {
    $prouctCountAction = 2;
}
if ($products[5]->getEndaction() != "" and $products[5]->getCount() > 0) {
    $prouctCountAction = 5;
}
?>   <script>
    $(document).ready(function(){
        /*$("div.c div.notcount").hover(function(){
            $(this).stop().animate({"opacity": 1});
        },function(){
            $(this).stop().animate({"opacity": 0.3});
        });*/
    });
    function changeButtonToGreen(id){
        $("#buttonId_"+id).removeClass("red-btn");
        $("#buttonId_"+id).addClass("green-btn");
        $("#buttonId_"+id).html("<span>В корзине</span>");
        $("#buttonId_"+id).attr("onclick","");
        $("#buttonId_"+id).attr("title","Перейти в корзину");
        $(".popup-holder #buttonIdP_"+id).removeClass("red-btn");
        $(".popup-holder #buttonIdP_"+id).addClass("green-btn");
        $(".popup-holder #buttonIdP_"+id).html("<span>В корзине</span>");
        $(".popup-holder #buttonIdP_"+id).attr("onclick","");
        $(".popup-holder #buttonIdP_"+id).attr("title","Перейти в корзину");

        ;
        window.setTimeout('$("#buttonId_'+id+'").attr("href","/cart")', 1000);
        window.setTimeout('$(".popup-holder #buttonIdP_'+id+'").attr("href","/cart")', 1000);
        $("#buttonIdP_"+id).removeClass("red-btn");
        $("#buttonIdP_"+id).addClass("green-btn");
        $("#buttonIdP_"+id).html("<span>В корзине</span>");
        $("#buttonIdP_"+id).attr("onclick","");
        $("#buttonIdP_"+id).attr("title","Перейти в корзину");

        window.setTimeout('$("#buttonIdP_'+id+'").attr("href","/cart")', 1000);
    }
</script>
<ul class="breadcrumbs">
    <li><a href="/">Секс-шоп главная</a></li>
    <li>Лидеры продаж</li>
</ul>
<h1 class="title">Лидеры продаж</h1>
<?if($catalog->getId()==1)://для мущщин ?>
  <div class="catalog-wpapper new">
  	<a class="catalog-element" href="/category/Vaginy-Realistiki"><img alt="Вагины – Реалистики в интим магазине Он и Она" src="/images/catalog/realistic.png" /> Вагины-<br />
  	реалистики </a> <a class="catalog-element" href="/category/muzhskie-masturbatory"> <img alt="Мастурбаторы в интим магазине Он и Она" src="/images/catalog/masturb.png" /> Мастурбаторы </a> <a class="catalog-element" href="/category/Sex_kukly"> <img alt="Секс куклы в интим магазине Он и Она" src="/images/catalog/sexdoll.png" /> Секс-куклы </a> <a class="catalog-element" href="/category/Massazhery-prostaty"> <img alt="Массажеры простаты в интим магазине Он и Она" src="/images/catalog/massager.png" /> Массажеры<br />
  	простаты </a> <a class="catalog-element" href="/category/Vakuumnye-pompy"> <img alt="Вакуумные помпы в интим магазине Он и Она" src="/images/catalog/pump.png" /> Ваккуумные<br />
  	помпы </a> <a class="catalog-element" href="/category/udlinyauschie-nasadki-na-penis"> <img alt="Увеличение члена в интим магазине Он и Она" src="/images/catalog/case.png" /> Увеличение<br />
  	члена </a> <a class="catalog-element" href="/category/erekcionnye-kolca-nasadki"> <img alt="Эрекционные кольца в интим магазине Он и Она" src="/images/catalog/ring.png" /> Эрекционные кольца </a>
  </div>
<? endif ?>
<ul class="item-list">
    <?php
    foreach ($products as $prodNum => $product):
        if ($product->getCount() > 0) {
            $prodCount = 1;
        } else {
            $prodCount = 0;
        }
        $gdeslonCodes[]='{ id : "'.$product->getId().'", quantity: 1, price: '.$product->getPrice().'}';
        $advcakeItems[]=[
          'id' => $product->getId(),
          'name' => $product->getName(),
          'categoryId' => $product->getGeneralCategory()->getId(),
          'categoryName' => $product->getGeneralCategory()->getName(),
          // 'price' => $product->getPrice(),
        ];
        ?>
        <?php
       /* if ($prouctCountAction > 0 and $prodNum <= $prouctCountAction) {
            //echo "productsbestprice";
            include_component('category', 'productsbestprice', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId(), 'last' => (($prodNum + 1) % 3) == 0 ? true : false));
        } else {
            if ($product->getCount() < 1) {
                $productCountLast = ProductTable::getInstance()->createQuery()->where("parents_id=?", $product->getId())->addWhere("count>0")->addwhere("is_public=1")->fetchOne();
                if ($productCountLast)
                    $product = $productCountLast;
            }*/
            include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId()."-".((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast'), 'prodCount' => $prodCount, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false)));
        //}
        ?>

        <?php
        if ($prouctCountAction > 0 and $prodNum == $prouctCountAction) {
            ?>
        </ul><ul class="item-list">
            <?
        }
    endforeach;
    ?>
</ul><!--item-list end-->
<?/*

<?php if ($pager->haveToPaginate()): ?>
    <div class="paginator-box" style="width: <?= (count($pager->getLinks(9)) * 37) ?>px;">
        <?php if ($pager->getPage() == 1) {
            ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('new_prod', array()) ?>?page=1" class="first"></a>
            <a href="<?php echo url_for('new_prod', array()) ?>?page=<?php echo ($pager->getPage() - 1) ?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($pager->getPage() == (count($pager->getLinks(20)))) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('new_prod', array()) ?>?page=<?php echo ($pager->getPage()) ?>" class="next"></a>
            <a href="<?php echo url_for('new_prod', array()) ?>?page=<?= count($pager->getLinks(20)) ?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= (count($pager->getLinks(9)) * 37) ?>px;">
            <ul>
                <?php foreach ($pager->getLinks(9) as $page): ?>
                    <?php if ($page == $sf_request->getParameter('page', 1)): ?>
                        <li class="active"><a href="<?php echo url_for('new_prod', array()) ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo url_for('new_prod', array()) ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>

            </ul>
        </div>
    </div>
<?php endif; ?>*/?>
<? slot('gdeSlonMode', 'list'); ?>
<? slot('gdeSlonCodes', 'products: [ '.implode(', '."\n", $gdeslonCodes).' ],'); ?>
<? slot('advcake', 7); ?>
<? slot('advcake_list', [
  'products' => $advcakeItems,
]);?>
