<?php
slot('leftBlock', true);
slot('metaTitle', "Новые товары | Сеть магазинов «Он и Она»" . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
slot('metaKeywords', "Новые товары");
slot('metaDescription', "Предлагаем вам ознакомиться с новинками в секс-шопе «Он и Она». Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.");
$products = $pager->getResults();
$prouctCountAction = 0;
if ($products[2]->getEndaction() != "" and $products[2]->getCount() > 0) {
    $prouctCountAction = 2;
}
if ($products[5]->getEndaction() != "" and $products[5]->getCount() > 0) {
    $prouctCountAction = 5;
}
?><script>/*
    $(document).ready(function () {
        $("div.c div.notcount").hover(function () {
            $(this).stop().animate({"opacity": 1});
        }, function () {
            $(this).stop().animate({"opacity": 0.3});
        });
    });*/
    function changeButtonToGreen(id) {
        $("#buttonId_" + id).removeClass("red-btn");
        $("#buttonId_" + id).addClass("green-btn");
        $("#buttonId_" + id).html("<span>В корзине</span>");
        $("#buttonId_" + id).attr("onclick", "");
        $("#buttonId_" + id).attr("title", "Перейти в корзину");
        $(".popup-holder #buttonIdP_" + id).removeClass("red-btn");
        $(".popup-holder #buttonIdP_" + id).addClass("green-btn");
        $(".popup-holder #buttonIdP_" + id).html("<span>В корзине</span>");
        $(".popup-holder #buttonIdP_" + id).attr("onclick", "");
        $(".popup-holder #buttonIdP_" + id).attr("title", "Перейти в корзину");

        ;
        window.setTimeout('$("#buttonId_' + id + '").attr("href","/cart")', 1000);
        window.setTimeout('$(".popup-holder #buttonIdP_' + id + '").attr("href","/cart")', 1000);
        $("#buttonIdP_" + id).removeClass("red-btn");
        $("#buttonIdP_" + id).addClass("green-btn");
        $("#buttonIdP_" + id).html("<span>В корзине</span>");
        $("#buttonIdP_" + id).attr("onclick", "");
        $("#buttonIdP_" + id).attr("title", "Перейти в корзину");

        window.setTimeout('$("#buttonIdP_' + id + '").attr("href","/cart")', 1000);
    }
</script>
<ul class="breadcrumbs">
    <li><a href="/">Секс-шоп главная</a></li>
    <li>Новые товары</li>
</ul>
<h1 class="title">Новые товары</h1>

<?
if ($sf_user->isAuthenticated()):
    $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_id=?", sfContext::getInstance()->getUser()->getGuardUser()->getId())->addWhere("category_id='300000'")->addWhere("is_enabled='1'")->fetchOne();
    if ($notificationCategory) {
        ?>
        <div class="NotificationCategory">
            <div style='color: #c3060e;margin-bottom: 10px;'>Вы подписаны на уведомления о новых поступлениях товаров в этом разделе. <a href="/delnotification/300000" onclick=" $('.NotificationCategory').load($(this).attr('href'));
                return false;">Отписаться</a></div>
        </div><?
    } else {
        ?><div class="NotificationCategory">
            <div style="float: left;color: #c3060e;padding-top: 5px; margin-right: 10px;"><input type="checkbox" id="checkboxNotify">Я хочу подписаться на уведомления о новых поступлениях товаров в этом разделе:</div>
            <div style="float: left;" class="silverButtonAddToNotification" onClick='if (!$("#checkboxNotify").prop("checked")) {
                                return false;
                            }
                            $(".NotificationCategory").load("/addnotification", {categoryAddNotification: "300000"});'></div>

        </div><?
    }
else:
    ?><div class="NotificationCategory">
        <div style="float: left;color: #c3060e;width: 360px;    position: relative;top: -4px;">Подпишитесь на уведомления о новых поступлениях товаров в этом разделе:</div>
        <div style="float: right;">
            <div style="float: left;"><input style="border: 1px solid #b2b2b2; height: 26px; background-color: #FFF;width: 250px;padding-left: 10px;" id="notificationMail" value="Введите свой e-mail" onClick="$(this).val('');" /></div>
            <div style="float: left;" class="silverButtonAddToNotification" onClick='$(".NotificationCategory").load("/addnotification", {categoryAddNotification: "300000", emailAddNotification: $("#notificationMail").val()});'></div>
        </div>
    </div><?
endif;
?>
<div style="clear: both;" class="mobile-only"></div>
<ul class="item-list">
    <?php
    if ($sf_user->isAuthenticated()):
        $nameSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getFirstName();
        $mailSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress();
    endif;
    foreach ($products as $prodNum => $product):
        if ($product->getCount() < 1) {
            $productCountLast = ProductTable::getInstance()->createQuery()->where("parents_id=?", $product->getId())->addWhere("count>0")->addwhere("is_public=1")->addwhere("is_visiblecategory=0")->fetchOne();
            if ($productCountLast)
                $product = $productCountLast;
        }
        if ($product->getCount() > 0) {
            $prodCount = 1;
        } else {
            $prodCount = 0;
        }
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
          } */
        include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . ((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast') . "-" . implode(",", $products->getPrimaryKeys()), 'prodCount' => $prodCount, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false), "productsKeys" => implode(",", $products->getPrimaryKeys())));
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
                            $(document).ready(function () {
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

                                $('.senduser_<?= $product->getId() ?>').each(function () {
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
                                                    <input type="text" name="name" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") != "" ? sfContext::getInstance()->getRequest()->getParameter("name") : $nameSenduser ?>" style="width: 254px;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 120px; padding: 5px 0;text-align: left;">
                                                    Ваш e-mail*:
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <input type="text" name="mail" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") != "" ? sfContext::getInstance()->getRequest()->getParameter("mail") : $mailSenduser ?>" style="width: 254px;">
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
    endforeach;
    ?>
</ul><!--item-list end-->


<?php if ($pager->haveToPaginate()):
    $pagesCount=count($pager->getLinks(2000));
$page=$pager->getPage();?>


    <?php
    JSInPages("function setPages(id){
     window.location.href = '".url_for('new_prod', array())."?page='+id;
                    return false;
                }");
    ?>
    <div class="paginator-box" style="width: <?= ((($pagesCount - 9) > 0 ? 9 : $pagesCount)) * 30 + 5 ?>px;float: right;margin-top: 25px;padding: 0 90px 0 90px;">
        <?php if ($page != 1) {
            ?>
            <a href="<?php echo url_for('new_prod', array('slug' => $category['this_slug'])) ?>" onclick="setPages(<?= $page - 1 ?>);
                    return false;" style="    width: 80px;
    height: 23px;
    top: 0;
    left: 0;
    overflow: hidden;
    position: absolute;
    text-decoration: none;
    padding: 6px 0;
    ">Предыдущая</a>
           <?php }
           ?>
           <?php if ($page != ($pagesCount)) {
            ?>
            <a href="<?php echo url_for('new_prod', array('slug' => $category['this_slug'])) ?>" onclick="setPages(<?= $page + 1 ?>);
                    return false;" style="    width: 80px;
    height: 23px;
    top: 0;
    overflow: hidden;
    position: absolute;
    text-decoration: none;
    right:0;
    padding: 6px 0;">Следующая</a>
           <?php }
           ?>
        <div class="paginator" style="width: <?= ((($pagesCount - 9) > 0 ? 9 : $pagesCount)) * 30 + 5 ?>px;">
            <ul>
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
