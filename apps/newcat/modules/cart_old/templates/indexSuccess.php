<?php
slot("cartFirstPage", $cartFirstPage);
slot('topMenu', true);
?>
<script type="text/javascript">
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
    $(document).ready(function() {
<?
$products_old = unserialize($sf_user->getAttribute('products_to_cart'));
//print_r($products_old);
foreach ($products_old as $key => $product) {
    echo "changeButtonToGreen(" . $product["productId"] . ");";
}
//changeButtonToGreen(id)
?>
    });   
</script>
<?php if (is_array($products_old) and count($products_old) > 0):
    ?>
    <div class="borderCart">
        <div align="center" class="pink bold" style="padding:5px;color: #ba272d;">Моя корзина </div>
        <div style="display: block;">
            <div style="position: relative; z-index: 10; float: left; background: url('/newdis/images/cart/top1_act.png') repeat scroll 0pt 0pt transparent; height: 32px; width: 186px;"></div>
            <div style="position: relative; float: left; height: 32px; width: 186px; left: -13px; z-index: 9; background: url('/newdis/images/cart/top2_noact.png') repeat scroll 0pt 0pt transparent;"></div>
            <div style="position: relative; float: left; height: 32px; width: 186px; left: -26px; z-index: 8; background: url('/newdis/images/cart/top3_noact.png') repeat scroll 0pt 0pt transparent;"></div>
        </div>
        <div style="clear: both;margin-bottom: 20px;"></div>

        <?php if (!$sf_user->isAuthenticated()): ?>
            <div style="float: left; width: 50%; font-size: 13px; border: 1px solid #c3060e; padding: 10px;letter-spacing: -0.1px;height: 125px;"><span style="color: #c3060e; font-size: 14px;">Зарегистрированный покупатель</span> <br />
                Авторизуйтесь для быстрого оформления заказа. Возможно, на вашем 
                счете уже есть Бонусы на оплату от 20 до 50% стоимости заказа.<br /><br />
                <a href="#" class="login"><img src="/newdis/images/cart/enterButtom.png"></a>
            </div>

            <div style="margin-left: 501px; font-size: 13px; border: 1px solid #c3060e; border-left: 0px; padding: 10px;letter-spacing: -0.1px;height: 125px;"><span style="color: #c3060e; font-size: 14px;">Новый покупатель</span><br />
                Зарегистрируйтесь и получите 300 приветственных бонусов, которыми вы 
                сможете оплатить свой заказ прямо сейчас.<br /><br />
                <a href="/register"><img src="/newdis/images/cart/registerButtom.png"></a>
            </div>
        <?php endif; ?>
        <div style="clear: both;margin-bottom: 20px;"></div>



        <table width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000" border="0" align="center" class="cartContent">
            <thead><tr>
                    <th>Наименование</th>
                    <th style=" width: 111px;">Цена, руб.</th>
                    <th style=" width: 88px;">Скидка, %</th>
                    <th style=" width: 110px;">Кол-во</th>
                    <th style=" width: 108px;">Сумма, руб.</th>
                    <th style=" width: 40px;">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $TotalSumm = 0;
                $bonusAddUser = 0;
                $reTagProd = '';
                foreach ($products_old as $key => $productInfo):
                    $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    $reTagProd.='{
        "id": "' . ($product->getId()) . '",   // required
        "number": "' . ($productInfo['quantity']) . '",
    },';
                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);

                    $photoalbum = $product->getPhotoalbums();
                    $photos = $photoalbum[0]->getPhotos();
                    if ($product->getBonus() != 0) {
                        $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $productInfo['quantity'] * $product->getBonus()) / 100);
                    } else {
                        $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $productInfo['quantity'] * csSettings::get("persent_bonus_add")) / 100);
                    }
                    ?>
                    <tr>
                        <td style="text-align: left;"><?php if (isset($photos[0])): ?>
                                <div style="float:left;margin: -10px 10px -10px 0"> <a href="/product/<?= $product->getSlug() ?>"><img width="70" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>"></a></div>
                            <?php endif; ?>

                            <a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a>
                        </td>
                        <td><?= $product->getDiscount() > 0 ? $product->getOldPrice()  : $productInfo['price'] ?></td>
                        <td><?= $product->getDiscount() > 0 ? $product->getDiscount() : '0'; ?></td>
                        <td class="count">
                            <div style="background-image: url('/newdis/images/cart/min_count.png'); display: inline-block; position: relative;left: 4px; height: 23px; width: 18px; top: 9px;" onClick='
                                        if($("#quantity_<?= $productInfo['productId'] ?>").html()-1>0){
                                            $.post("/cart/addtocartcount/<?= $productInfo['productId'] ?>", { count: $("#quantity_<?= $productInfo['productId'] ?>").html()-1 },
                                            function(data) {
                                                var totalCost=0;
                                                $("#quantity_<?= $productInfo['productId'] ?>").html(data);
                                                $("#totalcost_<?= $productInfo['productId'] ?>").html(eval($("#quantity_<?= $productInfo['productId'] ?>").html())*eval($("#price_<?= $productInfo['productId'] ?>").html()));
                            <? /* $("#cartTotalCost").html(eval($("#cartTotalCost").html())-(eval($("#price_<?= $productInfo['productId'] ?>").html()))); */ ?>
                                                $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

                                                    totalCost=eval(totalCost)+eval(parseInt($(this).children(":eq(4)").find("div").html()));
                                                    console.log(totalCost);
                                                });
                                                $("#cartTotalCost").html(eval(totalCost));
                                            });
                                        }
                                 '> </div>
                            <div id="price_<?= $productInfo['productId'] ?>" style="display: none"><?= $productInfo['price'] ?></div>
                            <div id="quantity_<?= $productInfo['productId'] ?>" style="" class="cartCount"><?= $productInfo['quantity'] ?></div>
                            <div style="background-image: url('/newdis/images/cart/pl_count.png'); display: inline-block; position: relative;left: -4px; top: 9px; height: 23px; width: 18px;" onClick='

                                        $.post("/cart/addtocartcount/<?= $productInfo['productId'] ?>", { count: eval($("#quantity_<?= $productInfo['productId'] ?>").html())+1 },
                                        function(data) {
                                            var totalCost=0;
                                            $("#quantity_<?= $productInfo['productId'] ?>").html(data);
                                            $("#totalcost_<?= $productInfo['productId'] ?>").html(eval($("#quantity_<?= $productInfo['productId'] ?>").html())*eval($("#price_<?= $productInfo['productId'] ?>").html()));
                                            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

                                                totalCost=eval(totalCost)+eval(parseInt($(this).children(":eq(4)").find("div").html()));
                                                console.log(totalCost);
                                            });
                                            $("#cartTotalCost").html(eval(totalCost));
                                        });

                                 '> </div>
                        </td>
                        <td><div style="display: inline-block;" id="totalcost_<?= $productInfo['productId'] ?>"><?= $productInfo['quantity'] * $productInfo['price'] ?></div></td>
                        <td><a onClick='$("div#content").load("/deletefromcart/<?= $key + 1 ?>");'><img width="16" border="0" height="16" align="absmiddle" title="Удалить товар из корзины" alt="Удалить" src="/images/icons/cross.png"></a></th>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>





        <div style="padding:10px 0px 10px 0; color: #414141;">
            <div style="float: left;"><span style="color: #c3060e;">*</span> За этот заказ вы получите <span style="color: #c3060e;"><?= $bonusAddUser ?></span> <a href="/programma-on-i-ona-bonus" target="_blank"><u>бонусных рублей</u></a></div>
            <div style="text-align: right;">Сумма заказа: <span class="bold" id="cartTotalCost"><?= $TotalSumm ?></span> <span class="normal">руб.</span></div>
            <div style="clear: both;margin-bottom: 20px;"></div>
            <div style="float: left;"><img src="/newdis/images/cart/str-left.png" style="right: -5px; position: relative;"><img src="/newdis/images/cart/str-left.png" style="position: relative;"> <a href="/sexshop" style="color: #707070;">Продолжить покупки</a></div>
            <div style="float: left;margin-left: 205px;"><a href="/cart/processorder/" class="red-btn colorWhite"><span style="width: 170px;">Оформить заказ</span></a></div>

        </div>
        <div style="clear: both;margin-bottom: 20px;"></div>
        <div style="color: #c3060e;width: 934px;background-color: #f1f1f1;padding: 10px 10px 10px 15px; font-size: 14px;">Вам может понадобиться</div>
        
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
        <ul class="item-list">
            <?php
            $items2cart = csSettings::get('items2cart');
            $items2cart = explode(",", $items2cart);
            foreach ($items2cart as $product):
                $product = ProductTable::getInstance()->findOneById($product);

                if ($product->getId() != ""):
                    $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
                    $comments = Doctrine_Core::getTable('Comments')
                            ->createQuery('c')
                            ->where("is_public = '1'")
                            ->addWhere('product_id = ?', $product->getId())
                            ->orderBy('id ASC')
                            ->execute();
                    ?>



                    <li style="margin: 0 12px 32px 0;">
                        <!--
                Сообщить о поступлении
                        -->
                        <div style="display: none" class="highslide-maincontent" id="ContentToSend_<?= $product->getId() ?>">
                            <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>
                            <img src="/images/topToSend.png">

                            <?php if (!$errorCapSu and $sf_params->get('senduser')): ?>
                                <center>Спасибо за запрос. Вам будет сообщено о поступление товара.</center>
                            <?php else: ?>
                                <form id="senduser" action="/product/<?= $product->getSlug() ?>/senduser" method="post">
                                    <div style="clear: both; color:#4e4e4e; text-align: left;">    
                                        <table width="100%" class="noBorder">
                                            <tbody><tr>
                                                    <td width="120" style="color:#073f72;padding: 5px 0;text-align: left;">
                                                        Представьтесь<span style="color: #ff94bc;">*</span>
                                                    </td>
                                                    <td style="padding: 5px 0;text-align: left;">
                                                        <input type="text" name="name" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #073f72;" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="120" style="color:#073f72;padding: 5px 0;text-align: left;">
                                                        Ваш e-mail<span style="color: #ff94bc;">*</span>
                                                    </td>
                                                    <td style="padding: 5px 0;text-align: left;">
                                                        <input type="text" name="mail" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #073f72;" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") ?>">
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        <span style="font-size: 10px;">Поля отмеченные * обязательны для заполнения.</span>
                                        <center>
                                            <br />
                                            <?php /* if (sfConfig::get('app_recaptcha_active', false)): ?>
                                              <?php echo recaptcha_get_html(sfConfig::get('app_recaptcha_publickey'), $reCaptcha['response']->getError()) ?>
                                              <?php endif ?>
                                              <?php if ($reCaptcha['response']->getError() != "") { ?>
                                              <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                              <br />
                                              <?php } */ ?>
                                            <img border="0" src="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>">
                                            <br /><br />
                                            <input type="text" name="sucaptcha" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #073f72;">
                                            <br />
                                            <?php if ($errorCapSu) { ?>
                                                <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                                <br />
                                            <?php } ?>
                                            <span style="font-size: 10px;">Введите текст с картинки.</span>
                                            <br />
                                            <br />
                                            <input type="submit" style="width: 198px; position: relative; left: 0px; top: 0px; margin: 5px 0px 7px 0px;" value="Отправить запрос" id="addToBasket">
                                        </center>

                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!--
                    Сообщить о поступлении
                        -->
                        <!--
                     Быстрый заказ
                        -->
                        <div style="display: none" class="highslide-maincontent" id="ContentFastOrder_<?= $product->getId() ?>">
                            <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>
                            <img src="/images/logoHightOrder.png" style="float:left;left: -7px; position: relative;">
                            <div style="text-align: justify; color: #5a0fac;">Оставьте свои контактные данные, наши менеджеры свяжутся с вами в течение 20 минут и оформят ваш заказ максимально быстро и правильно. А так же сделают вам <span style="color: red;">скидку 10%</span> от суммы вашего заказа. Менеджеры с удовольствием проконсультируют вас по всем интересующим вопросам.</div>

                            <form method="post" action="/fastorder/<?= $product->getId() ?>" id="fastOrder">
                                <div style="clear: both">    
                                    <table width="100%" class="noBorder">
                                        <tbody><tr>
                                                <td width="120" style="color:#0174fd;padding: 5px 0;text-align: left;">
                                                    Представьтесь<span style="color: red;">*</span>
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <input type="text" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #5a0fac;" name="name">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="color:#0174fd;padding: 5px 0;text-align: left;">
                                                    Ваш телефон<span style="color: red;">*</span>
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <input type="text" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #5a0fac;" name="phone">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="color:#0174fd;padding: 5px 0;text-align: left;">
                                                    Ваш e-mail
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <input type="text" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #5a0fac;" name="mail">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="120" style="color:#0174fd;padding: 5px 0;text-align: left;">
                                                    Введите символы<span style="color: red;">*</span>
                                                </td>
                                                <td style="padding: 5px 0;text-align: left;">
                                                    <img src="/captcha/smallcaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" border="0"><br><br>

                                                    <input type="text" name="cText"  style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #5a0fac;" id="cText">
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    <center>
                                        <input type="submit" id="addToBasket" value="Отправить запрос" style="width: 198px; position: relative; left: 0px; top: 0px; margin: 5px 0px 7px 0px;">
                                    </center>

                                </div>
                            </form>
                            <div style="text-align: justify; color: #5a0fac;">
                                Обработка быстрых заказов осуществляется с 10 до 18 часов только в рабочии дни.
                                <br><span style="font-size: 10px;">Поля отмеченные * обязательны для заполнения.</span>
                            </div>

                        </div>

                        <!--
                        Быстрый заказ
                        -->

                        <div class="t"></div>
                        <div class="c">
                            <div class="content">
                                <?= $product->getDiscount() > 0 ? '<span class="sale' . $product->getDiscount() . '">-' . $product->getDiscount() . '%</span>' : ''; ?>
                                <div class="title"><a href="/product/<?= $product->getSlug() ?>"><? mb_internal_encoding('UTF-8'); ?><?= mb_substr($product->getName(), 0, 45) ?></a></div>
                                <div class="img-holder">
                                    <a href="/product/<?= $product->getSlug() ?>"><img id="photoimg_<?= $product->getId() ?>" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt="<?= $product->getName() ?>" /></a>
                                    <? /* <div class="countdown">
                                      <a href="#" class="buy">Успейте купить!</a>
                                      <span class="time">72:26:32</span>
                                      </div> */ ?>
                                </div>
                                <div class="bottom-box">
                                    <div class="row">
                                        <? if ($comments->count() > 0) { ?><a href="/product/<?= $product->getSlug() ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $comments->count() ?></a><? } ?>
                                        <div class="stars">
                                            <span style="width:<?= $product->getRating() > 0 ? (@round($product->getRating() / $product->getVotesCount())) * 10 : 0 ?>%;"></span>
                                        </div>
                                    </div>
                                    <div class="price-box">
                                        <?php if ($product->getOldPrice() > 0) { ?>
                                            <span class="old-price"><?= $product->getOldPrice() ?> р.</span>
                                            <span class="new-price"><?= $product->getPrice() ?> р.</span>
                                        <? } else { ?>
                                            <span class="price"><?= $product->getPrice() ?>    р.</span>
                                        <? } ?>
                                    </div>
                                    <div class="tools">
                                        <a href="#" class="att"></a><?php if ($product->getCount() > 0): ?>
                                            <a href="#" id="buttonId_<?= $product->getId() ?>" class="red-btn small to-card" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', { quantity: 1 },  function(data) {location.reload();});
                                                                addToCartAnim('Cart', '#photoimg_<?= $product->getId() ?>', true);changeButtonToGreen(<?= $product->getId() ?>);
                                               "> 

                                                <span>В корзину</span>
                                            </a>
                                            <a href="#" class="to-desire" onClick='javascript: addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>");$("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");'></a>

                                        <?php else: ?>
                                               <a id="addToBasket" class="red-btn to-card small" onclick="return hs.htmlExpand(this, { contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar',
                                                                   headingText: '', width: 553, height: 470, slideshowGroup: 'groupToSend', left: -9 } )"
                                               class="highslide" style=""/><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;">Сообщить о поступлении</span></a>

                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="popup-content" style="display:none">
                                    <h1 class="title centr"><?= $product->getName() ?></h1>
                                    <div class="item-box">
                                        <div class="item-media">
                                            <div class="img-holder">
                                                <a href="#"><img id="photoimg_pr_<?= $product->getId() ?>" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt="image description" /></a>
                                            </div>
                                            <? /* <div class="more-photo">
                                              <a href="#">Фотогалерея <?= $photos->count() ?> фото</a>
                                              <?php
                                              foreach ($photos as $key => $photo):
                                              if ($key != 0){
                                              echo '<a href = "/uploads/photo/' . $photo->getFilename() . '" title = " " style = "display:none;" class = "highslide" onclick = "return hs.expand(this)"></a>';

                                              }else{
                                              echo '<a href = "/uploads/photo/' . $photo->getFilename() . '" title = " " style = "" class = "highslide" onclick = "return hs.expand(this)">55</a>';


                                              }
                                              endforeach;
                                              ?>
                                              </div> */ ?>
                                        </div>
                                        <div class="item-char">
                                            <form action="/cart/addtocart/<?= $product->getId() ?>" class="search">
                                                <fieldset>
                                                    <?php
                                                    $arrayDopInfo = array();
                                                    $dopInfos = $product->getDopInfoProducts();
                                                    /* foreach ($dopInfos as $info):
                                                      $dopParam = $info->getDopInfoProduct()->getData();
                                                      $arraySubInfo['value'] = $info->getValue();
                                                      $arraySubInfo['code'] = $dopParam[0]->getCode();
                                                      $arraySubInfo['dopInfoID'] = $info->getId();
                                                      $arrayInfo[$info->getName()][] = $arraySubInfo;
                                                      endforeach; */
                                                    if ($product->getParent() != "")
                                                        $productProp = $product->getParent();
                                                    else
                                                        $productProp = $product;
                                                    $i = 0;
                                                    $childrens = $productProp->getChildren();
                                                    $childrens[] = $productProp;
                                                    foreach ($dopInfos as $key => $property):
                                                        $doparray['value'] = $property['value'];
                                                        $doparray['product_id'] = $product->getSlug() != "" ? $product->getSlug() : $product->getId();

                                                        $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($property['dicategory_id']);
                                                        $doparray['sort'] = $dopInfoCategory->getPosition();


                                                        $doparray['dopInfoID'] = $property->getId();
                                                        $arrayDopInfo[$property['dicategory_id']][] = $doparray;
                                                        foreach ($childrens as $children) {
                                                            foreach ($children->getDopInfoProducts() as $dopInfoChildren) {
                                                                //echo $dopInfoChildren['value'];
                                                                //echo $dopInfoChildren['dicategory_id'].' '.$property['dicategory_id']." упымап ";
                                                                if ($dopInfoChildren['dicategory_id'] == $property['dicategory_id'] and $children->getId() != $product->getId()) {
                                                                    /* if (/$dopInfoChildren['dicategory_id'] == 62) {
                                                                      //echo $dopInfoChildren['value'];
                                                                      } */
                                                                    $in_array_di = false;

                                                                    foreach ($arrayDopInfo[$property['dicategory_id']] as $value) {
                                                                        //print_r($value);
                                                                        if (in_array($dopInfoChildren['value'], $value)) {
                                                                            //print_r($dopInfoChildren['value']);

                                                                            $in_array_di = true;
                                                                        }
                                                                    }

                                                                    if ($in_array_di === false) {
                                                                        //echo "<pre>".$dopInfoChildren['value'];
                                                                        $doparray['value'] = $dopInfoChildren['value'];
                                                                        $doparray['product_id'] = $children->getSlug() != "" ? $children->getSlug() : $children->getId();
                                                                        $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($property['dicategory_id']);
                                                                        $doparray['sort'] = $dopInfoCategory->getPosition();
                                                                        $arrayDopInfo[$property['dicategory_id']][] = $doparray;
                                                                    }
                                                                    //print_r($arrayDopInfo);
                                                                }
                                                            }
                                                        }
                                                        $i++;

                                                    endforeach; //print_r($arrayDopInfo);
                                                    //print_r($arrayDopInfo);
                                                    unset($sort_numcie, $arrayDopInfoKeys);
                                                    foreach ($arrayDopInfo as $c => $key) {
                                                        $sort_numcie[$c] = $key['0']['sort'];
                                                    }
                                                    $sort_numcie2 = $sort_numcie;
                                                    array_multisort($sort_numcie, SORT_ASC, $arrayDopInfo);
                                                    asort($sort_numcie2);

                                                    foreach ($sort_numcie2 as $c => $null) {
                                                        $arrayDopInfoKeys[] = $c;
                                                    }
                                                    //print_r($arrayDopInfo);
                                                    $arrayDopInfo = array_combine($arrayDopInfoKeys, $arrayDopInfo);
                                                    if ($arrayDopInfo):
                                                        foreach ($arrayDopInfo as $key => $property):
                                                            $key_old = $key;
                                                            $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($key);
                                                            $key = $dopInfoCategory->getName();
                                                            ?>

                                                            <?php
                                                            if (count($property) == 1):

                                                                if ($key == "Таблица размеров" and $property[0]['value'] == 1):
                                                                    $tableSize = true;
                                                                else:
                                                                    ?>
                                                                    <dl>
                                                                        <dt>
                                                                        <input type="hidden" name="productOptions[]" value="<?= $property[0]['dopInfoID'] ?>" />
                                                                        <?= $key ?>:</dt>
                                                                        <dd><?php
                                            if ($key == "Производитель") {
                                                echo "<a href=\"/manufacturer/" . $property[0]['dopInfoID'] . "\">" . $property[0]['value'] . "</a>";
                                            } else {
                                                echo $property[0]['value'];
                                            }
                                                                        ?></dd></dl>
                                                                <?php
                                                                endif;
                                                            else:
                                                                ?><dl>
                                                                    <dt><?= $key ?>:</dt>
                                                                    <dd>
                                                                        <script>
                                                                        function changeArtCode(select){
                                                                            document.location.href = "/product/"+select.value;
                                                                        }
                                                                        </script>
                                                                        <select name="productOptions[]" onchange="changeArtCode(this)" style="width: 82px;" id="select_<?= $i ?>">
                                                                            <?php foreach ($property as $keySub => $sub): ?>
                                                                                <option value="<?= $sub['product_id'] ?>"><?= $sub['value'] ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </dd></dl>
                                                            <?php endif; ?>

                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>

                                                    <dl>
                                                        <dt><label for="count">Количество:</label></dt>
                                                        <dd><?php if ($product->getCount() > 0): ?><input type="number" required="required" id="count_<?= $product->getId() ?>" value="1" min="1" max="100" name="quantity" style="width: 30px;" /><?php else: ?>
                                                                Нет в наличии

                                                            <?php endif; ?></dd>
                                                    </dl>
                                                    <div class="more-expand-holder">
                                                        <dl style="display: none;" class="productCode">
                                                            <dt>Артикул:</dt>
                                                            <dd class="artCode"><?= $product->getCode() ?></dd>
                                                        </dl>
                                                        <a href="#" class="more-expand" onclick=" $(this).prevAll('.productCode').toggle();return false"></a>
                                                    </div>
                                                    <div class="price-box">
                                                        <div class="row" style="padding: 0 0 5px;">
                                                            <div class="btn-holder">
                                                                <?php if ($product->getCount() > 0): ?>
                                                                    <a href="#" id="buttonIdP_<?= $product->getId() ?>" class="red-btn to-card  colorWhite" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', { quantity: $('.popup-holder #count_<?= $product->getId() ?>').val() },  function(data) {});
                                                                                    addToCartAnim('Cart', '#photoimg_pr_<?= $product->getId() ?>');
                                                                       "
                                                                       style="top: -6px;">
                                                                        <span>В корзину</span>
                                                                    </a>
                                                                    <a href="#" class="to-desire" title="Добавьте товар в список своих желаний и вы сможете легко вернуться к просмотру данного товара, удалить из списка желаний или заказать его" onClick='javascript: addToCartAnim("Cart", "#photoimg_pr_<?= $product->getId() ?>");$("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");'></a>
                                                                <?php else: ?>
                                                                       <a id="addToBasket" class="red-btn to-card small  colorWhite" onclick="return hs.htmlExpand(this, { contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar',
                                                                                       headingText: '', width: 553, height: 470, slideshowGroup: 'groupToSend', left: -9 } )"
                                                                       class="highslide" style=""/><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;">Сообщить о поступлении</span></a>

                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="price-col">
                                                                <div class="title">&nbsp;</div>
                                                                <span class="new-price"><?= $product->getPrice() ?> р.</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="bonus-box">
                                        <a target="_blank" href="http://club.onona.ru/index.php/topic/116-programma-on-i-ona--bonus/" class="bonus-item">
                                            <span class="icon">
                                                <strong><?= $product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add') ?>%</strong>
                                                <em>бонусы</em>
                                            </span>
                                            <span class="text">возвращаем на ваш личный счет</span>
                                        </a>
                                           <a href="#" class="bonus-item" onclick="return hs.htmlExpand(this, { contentId: 'ContentFastOrder_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar red-header',
                                                   headingText: '<img src=/images/fastOrderTitle.jpg>', width: 553, height: 495, slideshowGroup: 'groupFastOrder', left: -9 } )">
                                            <span class="icon">
                                                <img src="/newdis/images/ico01.png" width="40" height="40" alt="image description" />
                                            </span>
                                            <span class="text">быстрый, выгодный заказ товара</span>
                                        </a>
                                    </div>
                                    <h2 class="title">Описание товара</h2>
                                    <div class="info-box"><?php if ($product->getVideo() != ''): ?>
                                            <div class="video-holder">
                                                <a href="#" class="player" onClick="
                                                        hdwebplayer({ 
                                                            id       : 'playerVideoDiv',
                                                            swf      : '/player/player.swf?api=true',
                                                            width    : '620',
                                                            height   : '366',
                                                            margin   : '15',
                                                            video    : '<?= str_replace(array("http://www.onona.ru/video/", "http://onona.ru/video/", "http://new.onona.ru/video/"), "/uploads/video/", $product->getVideo()) ?>',
                                                            autoStart: 'true',
                                                            shareDock: 'false'
                                                        });
                                                        $('.close').click(function(){
                                                            $('#playerBG').remove();
                                                            $('#playerdiv').css('display','none');
                                                            player = document.getElementById('playerVideoDiv');
                                                            player.stopVideo();
                                                        });

                                                        $('#playerdiv').css({'display':'block',
                                                            'position':'fixed',
                                                            'top':(($(window).height() - $('#playerdiv').height())/2),
                                                            'left':(($(window).width() - $('#playerdiv').width())/2)});
                                                                                                                                                                                                                                         
                                                                                                                                                                                                                                        
                                                        return false;
                                                   ">
                                                    <img src="/newdis/images/video.png" width="142" height="90" alt="image description" />
                                                    <span class="name">Видео-презентация</span>
                                                    <span class="play"></span>
                                                </a>
                                            </div><?php endif; ?>
                                        <p><?= $product->getContent() ?></p>
                                    </div>
                                    <div class="social-box">
                                        <div class="row">
                                            <a href="javascript:CallPrint('.quick-view');" class="print"><img src="/newdis/images/ico02.png" width="16" height="16" alt="image description" /></a>
                                            <? /* <ul class="social">
                                              <li><a href="#"><img src="images/ico03.png" width="16" height="16" alt="image description" /></a></li>
                                              <li><a href="#"><img src="images/ico04.png" width="16" height="16" alt="image description" /></a></li>
                                              <li><a href="#"><img src="images/ico05.png" width="16" height="16" alt="image description" /></a></li>
                                              <li><a href="#"><img src="images/ico06.png" width="16" height="16" alt="image description" /></a></li>
                                              <li><a href="#"><img src="images/ico07.png" width="16" height="16" alt="image description" /></a></li>
                                              <li><a href="#"><img src="images/ico08.png" width="16" height="16" alt="image description" /></a></li>
                                              <li><a href="#"><img src="images/ico09.png" width="16" height="16" alt="image description" /></a></li>
                                              <li><a href="#"><img src="images/ico10.png" width="16" height="16" alt="image description" /></a></li>
                                              <li><a href="#"><img src="images/ico11.png" width="16" height="16" alt="image description" /></a></li>
                                              </ul>
                                              <div class="yashare-wrapper" style="background: none; text-align: center; width: 294px; padding-top: 24px; height: 32px; ">
                                              <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="icon" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,gplus" style="width: 196px;float: left; padding-left: 11px;"></div>
                                              </div> */ ?>
                                        </div>
                                        <div class="row">
                                            <? /* <a href="#" class="more-expand"></a>
                                              <ul class="share">
                                              <li><a href="#"><img src="/newdis/images/vk.png" width="102" height="20" alt="image description" /></a></li>
                                              <li><a href="#"><img src="/newdis/images/fb.png" width="123" height="20" alt="image description" /></a></li>
                                              </ul> */ ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="b"></div>
                    </li>











                    <?php
                endif;
            endforeach;
            ?>

        </ul>
    </div>
<?php else: ?><div class="borderCart">

        <div align="center" class="pink bold" style="padding:5px;color: #ba272d;">Моя корзина </div>
        <?
        $cartEmpty = PageTable::getInstance()->findOneBySlug("stranica-pustoi-korziny");
        echo $cartEmpty->getContent();
        ?>

    </div>
<?php endif; ?>






<script type="text/javascript">
var ad_products = [<?= $reTagProd ?>
];

window._retag = window._retag || [];
window._retag.push({code: "9ce8886733", level: 3});
(function () {
var s=document.createElement("script");
s.async=true;
s.src=(document.location.protocol == "https:" ? "https:" : "http:") + "//cdn.admitad.com/static/js/retag.js";
var a=document.getElementsByTagName("script")[0]
a.parentNode.insertBefore(s, a);
})()
</script>


