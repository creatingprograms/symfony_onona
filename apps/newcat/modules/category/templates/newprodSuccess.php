<?php
slot('leftBlock', true);
slot('metaTitle', "Новые товары > ".$catalog->getName() . " " . $catalog->getDescription() .(sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница ".sfContext::getInstance()->getRequest()->getParameter('page', 1): "")." – Секс шоп Он и Она" );
slot('metaKeywords', "Новые товары > ".$catalog->getName() . " " . $catalog->getDescription());
slot('metaDescription', "Новые товары > ".$catalog->getName() . " " . $catalog->getDescription());
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
slot('canonicalSlugNewprod', $catalog->getSlug() . $canonDop);

$products = $pager->getResults();
$prouctCountAction = 0;
if ($products[2]->getEndaction() != "" and $products[2]->getCount() > 0) {
    $prouctCountAction = 2;
}
if ($products[5]->getEndaction() != "" and $products[5]->getCount() > 0) {
    $prouctCountAction = 5;
}
?>    <script>
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
    <li>Новые товары раздела "<?= $catalog->getName(). " " . $catalog->getDescription() ?>"</li>
</ul>
<h1 class="title">Новые товары раздела "<?= $catalog->getName(). " " . $catalog->getDescription() ?>"</h1>
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
        /*if ($prouctCountAction > 0 and $prodNum <= $prouctCountAction) {
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
            <li style="display: none"><?php if ($product->getCount() < 1): ?>
                    <!--
                Сообщить о поступлении
                    -->
                    <div style="display: none" class="highslide-maincontent" id="ContentToSend_<?= $product->getId() ?>">
                        <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>

                        <div style="color: #C3060E; font: 17px/21px Tahoma,Geneva,sans-serif;margin-bottom: 10px; text-align: center;">Сообщить о поступлении товара</div>

                        <?php if (!$errorCapSu and $sf_params->get('senduser')): ?>
                            <div style="width:100%; text-align: center;">Спасибо за запрос. Вам будет сообщено о поступление товара.</div>
                        <?php else: ?>
                            <script>
                                $(document).ready(function() {
                                    var options = {
                                        target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                                        //beforeSubmit:  showRequest,  // pre-submit callback
                                        success: showResponse_<?= $product->getId() ?>  // post-submit callback

                                                // other available options:
                                                //url:       url         // override for form's 'action' attribute
                                                //type:      type        // 'get' or 'post', override for form's 'method' attribute
                                                //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                                                //clearForm: true        // clear all form fields after successful submit
                                                //resetForm: true        // reset the form after successful submit

                                                // $.ajax options can be used here too, for example:
                                                //timeout:   3000
                                    };

                                    // bind form using 'ajaxForm'
                                    $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
                                });

                                function enableAjaxFormSendUser_<?= $product->getId() ?>() {

                                    var options = {
                                        target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                                        //beforeSubmit:  showRequest,  // pre-submit callback
                                        success: showResponse_<?= $product->getId() ?>  // post-submit callback

                                                // other available options:
                                                //url:       url         // override for form's 'action' attribute
                                                //type:      type        // 'get' or 'post', override for form's 'method' attribute
                                                //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                                                //clearForm: true        // clear all form fields after successful submit
                                                //resetForm: true        // reset the form after successful submit

                                                // $.ajax options can be used here too, for example:
                                                //timeout:   3000
                                    };

                                    // bind form using 'ajaxForm'
                                    $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
                                }

                                function showResponse_<?= $product->getId() ?>(responseText, statusText, xhr, $form) {
                                    var options = {
                                        target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                                        //beforeSubmit:  showRequest,  // pre-submit callback
                                        success: showResponse_<?= $product->getId() ?>  // post-submit callback

                                                // other available options:
                                                //url:       url         // override for form's 'action' attribute
                                                //type:      type        // 'get' or 'post', override for form's 'method' attribute
                                                //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                                                //clearForm: true        // clear all form fields after successful submit
                                                //resetForm: true        // reset the form after successful submit

                                                // $.ajax options can be used here too, for example:
                                                //timeout:   3000
                                    };

                                    // bind form using 'ajaxForm'
                                    $('.highslide-container #senduser_<?= $product->getId() ?>').ajaxForm(options);

                                    $('.senduser_<?= $product->getId() ?>').each(function() {
                                        $(this).ajaxForm(options);
                                    });
                                }
                            </script>
                 <div id="senduserdiv_<?= $product->getId() ?>">
                                <form id="senduser_<?= $product->getId() ?>" class="senduser_<?= $product->getId() ?>"  action="/product/<?= $product->getSlug() ?>/senduser" method="post">
                                           Данный товар скоро появится в продаже на сайте. Чтобы своевременно узнать об этом, нужно отправить нам запрос. Для этого в соответствующих полях напишите свое имя, e-mail и нажмите на кнопку "Отправить запрос". Как только товар поступит на склад, мы отправим уведомление на указанный вами e-mail.<br /><br />
                   <div style="clear: both; color:#4e4e4e; text-align: left;">
                                        <table style=" width: 100%" class="noBorder">
                                            <tbody><tr>
                                                    <td style="width: 120px;padding: 5px 0;text-align: left;">
                                                        Представьтесь*:
                                                    </td>
                                                    <td style="padding: 5px 0;text-align: left;">
                                                        <input type="text" name="name" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") ?>" style="width: 254px;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 120px; padding: 5px 0;text-align: left;">
                                                        Ваш e-mail*:
                                                    </td>
                                                    <td style="padding: 5px 0;text-align: left;">
                                                        <input type="text" name="mail" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") ?>" style="width: 254px;">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 120px; padding: 5px 0;text-align: left;">
                                                        Введите код*:
                                                    </td>
                                                    <td style="padding: 5px 0;text-align: left;">

                                                        <img  class="captchasu" src="/images/pixel.gif" data-original="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />
                                                        <input type="text" name="sucaptcha" style="position: relative; top: -45px; width: 130px;">
            <?php if ($errorCapSu) { ?><br />
                                                            <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                                            <br />
            <?php } ?>
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        <span style="font-size: 10px;">* - обязательны для заполнения.</span>
                                        <a  style="margin-top: 20px; margin-left: 155px;" class = "red-btn colorWhite" href = "#" onclick = "$('#senduser_<?= $product->getId() ?>').submit();
                                                return false;"><span style = "width: 195px;">Отправить запрос</span></a>

                                    </div>

                                </form></div>
        <?php endif; ?>
                    </div>
                    <!--
                Сообщить о поступлении
                    -->
                <? Endif; ?></li>
            <?php
        if ($prouctCountAction > 0 and $prodNum == $prouctCountAction) {
            ?>
        </ul>
        <ul class="item-list">
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
