<?php
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
slot('canonicalSlugCategory', $category->getSlug() . $canonDop);
slot('leftBlock', true);
slot('metaTitle', $category->getTitle() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getTitle());
slot('metaKeywords', $category->getKeywords() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getKeywords());
slot('metaDescription', $category->getDescription() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getDescription());
/*
  slot('metaTitle', $category->getTitle() == '' ? $category->getName() : $category->getTitle());
  slot('metaKeywords', $category->getKeywords() == '' ? $category->getName() : $category->getKeywords());
  slot('metaDescription', $category->getDescription() == '' ? $category->getName() : $category->getDescription()); */
?>
<? /* Прозрачность товара */ ?>
<script>
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
    <li><?php echo $category->getName() ?></li>
</ul>
<h1 class="title"><?php echo $category->getName() ?></h1>
<? If ($sf_request->getParameter('page', 1) == 1) { ?>
    <?php echo $category->getContent() ?><br /><? } ?>

<?php
if ($sf_user->isAuthenticated()):

    if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == 17101) {
        //echo phpinfo();
        /* $filters=  unserialize($category->getFilters());
          foreach($filters as $diId => $filter){
          $dopInfoCategory=  DopInfoCategoryTable::getInstance()->findOneById($diId);
          echo $dopInfoCategory;
          }
          print_r($filters); */
    }
endif;
?>
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
<? /* <ul class="item-list"> */ ?>

<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>

<?php
//$productsCategory = $products;


$products = $pager->getResults();
 if ($products->count() == 0) 
        echo "К сожалению, товаров, соответствующих выбранным параметрам, не найдено. Пожалуйста, выберите другие параметры.";
$prouctCountAction = 0;
if ($products[2]->getEndaction() != "" and $products[2]->getCount() > 0) {
    $prouctCountAction = 2;
}
if ($products[5]->getEndaction() != "" and $products[5]->getCount() > 0) {
    $prouctCountAction = 5;
}

//$products = $productsCategory;
?><?

if ($prouctCountAction > 0) {
    /*?><br /><span style="color: #ff5b00;font: 23px/24px Tahoma,Geneva,sans-serif; text-align: center; display: block; margin: auto">
    <?= csSettings::get('sloganCatBestprice') ?>
        </span><? ?>
    <div style="background: url('/newdis/images/ram_bestprice.png');width:772px; height: 18px;"></div>*/?>
    <ul class="item-list" style="  margin: 0 -7px 0 0;">
        <?
    } else {
        ?><ul class="item-list"><?
} ?>

<?php
foreach ($products as $prodNum => $product):
    // echo "!";
    if ($product->getCount() > 0) {
        $prodCount = 1;
    } else {
        $prodCount = 0;
    }
    ?>
        <?php
       /* if ($prouctCountAction > 0 and $prodNum <= $prouctCountAction) {
            //echo "productsbestprice";
            echo ((($prodNum + 1) % 3) == 0 ? true : false);
            include_component('category', 'productsbestprice', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article'), 'last' => ((($prodNum + 1) % 3) == 0 ? true : false), 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum));
        } else {*/
            include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article')."-".((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast'), 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false)));
        //}
        ?>

        <?php
        if ($prouctCountAction > 0 and $prodNum == $prouctCountAction) {
            ?>
        </ul>
        <ul class="item-list">
        <?
    }
endforeach;
?>
</ul>  <? 
?>
        <!--item-list end-->

<?php if ($pager->haveToPaginate()): ?>
    <div class="paginator-box" style="width: <?= (count($pager->getLinks(9)) * 37) ?>px;">
    <?php if ($pager->getPage() == 1) {
        ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
        <?php
    } else {
        ?>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=1" onclick="$('#pageFromFilter').val(1);jQuery('#categoryFilters').submit();window.scrollTo(0, 0);return false;" class="first"></a>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo ($pager->getPage() - 1) ?>" onclick="$('#pageFromFilter').val(<?php echo ($pager->getPage() - 1) ?>);jQuery('#categoryFilters').submit();window.scrollTo(0, 0);return false;" class="prev"></a>
    <?php }
    ?>
        <?php if ($pager->getPage() == (count($pager->getLinks(20)))) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
        <?php
    } else {
        ?>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo ($pager->getPage()) ?>" onclick="$('#pageFromFilter').val(<?php echo ($pager->getPage()) ?>);jQuery('#categoryFilters').submit();window.scrollTo(0, 0);return false;" class="next"></a>
            <a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?= count($pager->getLinks(20)) ?>" onclick="$('#pageFromFilter').val(<?= count($pager->getLinks(20)) ?>);jQuery('#categoryFilters').submit();window.scrollTo(0, 0);return false;" class="last"></a>
    <?php }
    ?>
        <div class="paginator" style="width: <?= (count($pager->getLinks(9)) * 37) ?>px;">
            <ul>
    <?php foreach ($pager->getLinks(9) as $page): ?>
                    <?php if ($page == $sf_request->getParameter('page', 1)): ?>
                        <li class="active"><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page ?>" onclick="$('#pageFromFilter').val(<?php echo $page ?>);jQuery('#categoryFilters').submit();window.scrollTo(0, 0);return false;"><span><?php echo $page ?></span></a></li>
                    <?php else: ?>
                        <li><a href="<?php echo url_for('category_show', array('slug' => $category->getSlug())) ?>?page=<?php echo $page ?>" onclick="$('#pageFromFilter').val(<?php echo $page ?>);jQuery('#categoryFilters').submit();window.scrollTo(0, 0);return false;"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>

            </ul>
        </div>
    </div>
<?php endif; ?>
<?
if ($category->getTags() != "") {
    ?><div style="float: right; color: #c0c0c0">Тэги: <a href="#" onclick="$('#tags').toggle();return false" class="more-expand" style="float: right;margin: 0 5px;"></a> <div id="tags" style="display: none; float: right;margin: 0 5px;"><?= $category->getTags() ?></div> </div><?
}
?>
<script type="text/javascript">
    var ad_category = "<?= $category->getId() ?>";   // required

    window._retag = window._retag || [];
    window._retag.push({code: "9ce8886735", level: 1});
    (function () {
        var s=document.createElement("script");
        s.async=true;
        s.src=(document.location.protocol == "https:" ? "https:" : "http:") + "//cdn.admitad.com/static/js/retag.js";
        var a=document.getElementsByTagName("script")[0]
        a.parentNode.insertBefore(s, a);
    })()
</script>