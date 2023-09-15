<?php
slot("cartFirstPage", $cartFirstPage);
slot('topMenu', true);
?>

<script type="text/javascript" src="/js/jquery.maskedinput-1.2.2.js"></script>
<script type="text/javascript" src="/js/validation.js?v=2"></script>
<script type="text/javascript">
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
    $(document).ready(function() {
        $("#sf_guard_user_phone").after("<div id='commPhone' style='display: inline-block;margin-left: 5px;'>Введите, пожалуйста, номер в формате +7(и 10 цифр вашего телефона) <br />Пример +74957879886</div>");
        $("#sf_guard_user_phone2").after("<div id='commPhone' style='display: inline-block;margin-left: 5px;'>Введите, пожалуйста, номер в формате +7(и 10 цифр вашего телефона) <br />Пример +74957879886</div>");
<?php
if ($sf_user->isAuthenticated()) {
    echo "$(\"#sf_guard_user_email_address\").attr('readonly','readonly');";
}
?>

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
                    <th style=" width: 110px;">Бонусы, %</th>
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
                $bonusPay = 0;
                $totalBonusPay = 0;
                $this->bonusCount = 0;

                if ($sf_context->getUser()->getGuardUser()) {
                    $this->bonus = BonusTable::getInstance()->findBy('user_id', $sf_context->getUser()->getGuardUser()->getId());
                    foreach ($this->bonus as $bonus) {
                        $this->bonusCount = $this->bonusCount + $bonus->getBonus();
                    }
                }
                foreach ($products_old as $key => $productInfo):
                    $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    $reTagProd.='{
        "id": "' . ($product->getId()) . '",   // required
        "number": "' . ($productInfo['quantity']) . '",
    },';
                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * (($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? $productInfo['price_w_discount'] : round($productInfo['price'] - $productInfo['bonuspay'])));
                    //print_r($productInfo);
                    $photoalbum = $product->getPhotoalbums();
                    $photos = $photoalbum[0]->getPhotos();
                    if ($product->getBonus() != 0) {
                        $bonusAddUser = $bonusAddUser + round((($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) * $productInfo['quantity'] * $product->getBonus()) / 100);
                    } else {
                        $bonusAddUser = $bonusAddUser + round((($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) * $productInfo['quantity'] * csSettings::get("persent_bonus_add")) / 100);
                        // echo $bonusAddUser;
                    }
                    if ($productInfo['price'] == $productInfo['price_w_discount']) {
                        $bonusPay = $bonusPay + round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $productInfo['price']);
                    }
                    $discountProd = ($productInfo['price_w_discount'] > 0 ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0');
                    $totalBonusPay = $totalBonusPay + $productInfo['bonuspay'];
                    ?>
                    <tr>
                        <td style="text-align: left;"><?php if (isset($photos[0])): ?>
                                <div style="float:left;margin: -10px 10px -10px 0"> <a href="/product/<?= $product->getSlug() ?>"><img width="70" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>"></a></div>
                            <?php endif; ?>

                            <a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a>
                        </td>
                        <td id="price_<?= $productInfo['productId'] ?>"><?= $productInfo['price'] ?></td>
                        <td id="discount_<?= $productInfo['productId'] ?>"><?= $discountProd; ?></td>
                        <td class="bonus">
                            <div style="background-image: url('/newdis/images/cart/min_count.png'); display: inline-block; position: relative;left: 4px; height: 23px; width: 18px; top: 9px;" onClick='

                                            if ((eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) - 5) >= 0) {
                                                $("#bonuspercent_<?= $productInfo['productId'] ?>").html(eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) - 5);
                                                if ($("#bonuspercent_<?= $productInfo['productId'] ?>").html() == 0) {
                                                    $("#discount_<?= $productInfo['productId'] ?>").html(<?= $discountProd; ?>);
                                                    $("#bonuspay_<?= $productInfo['productId'] ?>").html(0);

                                                    $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) *<?= $discountProd; ?> / 100));

                                                } else {
                                                    $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));

                                                    $("#bonuspay_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));
                                                }
                                                $("#bonusPercet1Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                $("#bonusPercet2Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                var totalCost = 0;
                                                var totalBonuspay = 0;
                                                $("div.borderCart").children("table:first").children("tbody").children("tr").each(function(i) {

                                                    totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                                                    totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(":eq(1)").html()));
                                                    $("#PriceBonusTable").html(totalBonuspay);
                                                    // console.log(totalBonuspay);
                                                });
                                                $("#cartTotalCost").html(eval(totalCost));
                                                $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));

                                            }
                                 ' > </div>
                            <div id="bonuspay_<?= $productInfo['productId'] ?>" style="display: none"><?= $productInfo['bonuspay'] ?></div>
                            <div id="bonuspercent_<?= $productInfo['productId'] ?>" style="" class="cartCount"><?= $productInfo['percentbonuspay'] ?></div>
                            <div style="background-image: url('/newdis/images/cart/pl_count.png'); display: inline-block; position: relative;left: -4px; top: 9px; height: 23px; width: 18px;" onClick='

                                            var totalBonuspay = 0;
                                            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function(i) {
                                                totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(":eq(1)").html()));
                                                //console.log(totalCost);
                                            });
                                            //console.log(totalBonuspay + Math.round((eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * 5) / 100));
                                            if ((eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) + 5) <=<?= $product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?> && totalBonuspay + Math.round((eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * 5) / 100) < <?= $this->bonusCount ?>) {
                                                $("#bonuspercent_<?= $productInfo['productId'] ?>").html(eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) + 5);
                                                $("#discount_<?= $productInfo['productId'] ?>").html("0");
                                                $("#bonuspay_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));
                                                $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));

                                                $("#bonusPercet1Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                $("#bonusPercet2Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                var totalBonuspay = 0;
                                                var totalCost = 0;
                                                $("div.borderCart").children("table:first").children("tbody").children("tr").each(function(i) {

                                                    totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                                                    totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(":eq(1)").html()));
                                                    $("#PriceBonusTable").html(totalBonuspay);
                                                    //console.log(totalCost);
                                                });
                                                $("#cartTotalCost").html(eval(totalCost));
                                                $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                                            } else {
                                                if ((eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) + 5) <=<?= $product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?>) {
                                                    $("#maxBonusCount_<?= $productInfo['productId'] ?>").fadeIn();
                                                    1
                                                    setTimeout(function() {
                                                        $("#maxBonusCount_<?= $productInfo['productId'] ?>").fadeOut()
                                                    }, 2000);
                                                } else {
                                                    $("#maxBonusPercent_<?= $productInfo['productId'] ?>").fadeIn();
                                                    1
                                                    setTimeout(function() {
                                                        $("#maxBonusPercent_<?= $productInfo['productId'] ?>").fadeOut()
                                                    }, 2000);
                                                }
                                            }
                                 '><div style="position: absolute; color: #c3060e; width: 230px; left: -125px; top: -20px;display: none;" id="maxBonusPercent_<?= $productInfo['productId'] ?>">Максимальная Бонусная скидка</div>
                                <div style="position: absolute; color: #c3060e; width: 275px; left: -125px; top: -20px;display: none;" id="maxBonusCount_<?= $productInfo['productId'] ?>">На вашем счете недостаточно Бонусов</div> </div>

                            <div style="z-index: <?=(count($products_old)*2)-$key*2+3 ?>;width: 16px; height: 18px; background: url(/images/questionicon.png);margin: 20px auto; position: relative;" onClick="$('#qestionBonus_<?= $productInfo['productId'] ?>').toggle();" onMouseOver="$('#qestionBonus_<?= $productInfo['productId'] ?>').fadeIn()" onMouseOut="$('#qestionBonus_<?= $productInfo['productId'] ?>').fadeOut()">

                            </div> <div style=" position: relative;">
                                <div style="padding: 10px;z-index: <?=(count($products_old)*2)-$key*2+2 ?>;text-align: left;position: absolute; color: rgb(195, 6, 14); width: 400px; right: 42px; top: -41px;display: none;background: #FFF; border: 1px solid #c3060e;" id="qestionBonus_<?= $productInfo['productId'] ?>">
                                    <?
                                    /* <span style="color: #c3060e;margin-left:120px;font-size: 16px;">Важная информация</span>
                                      <ul style="padding-left: 22px;">
                                      <li><span style="color: #414141">Для использования Бонусов необходимо быть авторизованным под своим логином.   </span></li>
                                      <li><span style="color: #414141">Вы можете самостоятельно указать количество Бонусов (кратное 5) для оплаты товара. </span></li>
                                      <li><span style="color: #414141">По умолчанию проставлена Максимальная Бонусная скидка в соответствии с вашими накопленными Бонусами, при желании вы можете ее уменьшить до 0 и сохранить свои Бонусы.    </span></li>
                                      <li><span style="color: #414141">При оплате Бонусами (значение в поле "Бонусы, %" >0) товара со скидкой (Акционного товара) скидка аннулируется.     </span></li>
                                      <li><span style="color: #414141">Как вернуть аннулированную скидку? В поле “Бонусы, %” поставить значение 0.  </span></li>
                                      <li><span style="color: #414141">Бонусы и скидки не суммируются.</span></li>
                                      </ul> */
                                    $block = PageTable::getInstance()->findOneBySlug("vazhnaya-informaciya-blok-bonusov-na-stranice-korziny");
                                    echo $block->getContent();
                                    ?>
                                </div>
                            </div>
                        </td>



                        <td class="count">
                            <div style="background-image: url('/newdis/images/cart/min_count.png'); display: inline-block; position: relative;left: 4px; height: 23px; width: 18px; top: 9px;" onClick='
                                            if ($("#quantity_<?= $productInfo['productId'] ?>").html() - 1 > 0) {
                                                $.post("/cart/addtocartcount/<?= $productInfo['productId'] ?>", {count: $("#quantity_<?= $productInfo['productId'] ?>").html() - 1},
                                                function(data) {
                                                    var totalCost = 0;
                                                    $("#quantity_<?= $productInfo['productId'] ?>").html(data);
                                                    $("#totalcost_<?= $productInfo['productId'] ?>").html(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()));
                            <? /* $("#cartTotalCost").html(eval($("#cartTotalCost").html())-(eval($("#price_<?= $productInfo['productId'] ?>").html()))); */ ?>
                                                    $("div.borderCart").children("table:first").children("tbody").children("tr").each(function(i) {

                                                        totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                                                        //console.log(totalCost);
                                                    });
                                                    $("#cartTotalCost").html(eval(totalCost));
                                                    $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                                                    if ($("#cartTotalCost").html() <<?= csSettings::get('free_deliver') ?>) {
                                                        $("#PriceDeliverTable").html("200");
                                                    } else {
                                                        $("#PriceDeliverTable").html("0");
                                                    }



                                                    $valDeliv = $(":radio[name=deliveryId]").filter(":checked").val();

                                                    if ($valDeliv == "9") {

                                                        if ($("#cartTotalCost").html() < 4990) {
                                                            $("#PriceDeliverTable").html("300");
                                                        } else {
                                                            $("#PriceDeliverTable").html("0");
                                                        }
                                                    }


                                                    $("#deliveryPriceSend").val($("#PriceDeliverTable").html());
                                                    if ($("input[name=payBonus][value=1]:visible").prop("checked"))
                                                        $("input[name=payBonus][value=1]:visible").click();



                                                    if ($("#bonuspercent_<?= $productInfo['productId'] ?>").html() == 0) {
                                                        $("#discount_<?= $productInfo['productId'] ?>").html(<?= $discountProd; ?>);
                                                        $("#bonuspay_<?= $productInfo['productId'] ?>").html(0);

                                                        $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) *<?= $discountProd; ?> / 100));

                                                    } else {
                                                        $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));

                                                        $("#bonuspay_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));
                                                    }


                                                    $("#bonusPercet1Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                    $("#bonusPercet2Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                    var totalBonuspay = 0;
                                                    var totalCost = 0;
                                                    $("div.borderCart").children("table:first").children("tbody").children("tr").each(function(i) {

                                                        totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                                                        totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(":eq(1)").html()));
                                                        $("#PriceBonusTable").html(totalBonuspay);
                                                        //console.log(totalCost);
                                                    });
                                                    $("#cartTotalCost").html(eval(totalCost));
                                                    $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                                                });
                                            }
                                 ' > </div>
                            <div id="price_<?= $productInfo['productId'] ?>" style="display: none"><?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?></div>
                            <div id="quantity_<?= $productInfo['productId'] ?>" style="" class="cartCount"><?= $productInfo['quantity'] ?></div>
                            <div style="background-image: url('/newdis/images/cart/pl_count.png'); display: inline-block; position: relative;left: -4px; top: 9px; height: 23px; width: 18px;" onClick='

                                            $.post("/cart/addtocartcount/<?= $productInfo['productId'] ?>", {count: eval($("#quantity_<?= $productInfo['productId'] ?>").html()) + 1},
                                            function(data) {
                                                var totalCost = 0;
                                                $("#quantity_<?= $productInfo['productId'] ?>").html(data);
                                                $("#totalcost_<?= $productInfo['productId'] ?>").html(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()));
                                                $("div.borderCart").children("table:first").children("tbody").children("tr").each(function(i) {

                                                    totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                                                    //console.log(totalCost);
                                                });
                                                $("#cartTotalCost").html(eval(totalCost));
                                                $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                                                if ($("#cartTotalCost").html() <<?= csSettings::get('free_deliver') ?>) {
                                                    $("#PriceDeliverTable").html("200");
                                                } else {
                                                    $("#PriceDeliverTable").html("0");
                                                }

                                                $valDeliv = $(":radio[name=deliveryId]").filter(":checked").val();

                                                if ($valDeliv == "9") {

                                                    if ($("#cartTotalCost").html() < 4990) {
                                                        $("#PriceDeliverTable").html("300");
                                                    } else {
                                                        $("#PriceDeliverTable").html("0");
                                                    }
                                                }


                                                $("#deliveryPriceSend").val($("#PriceDeliverTable").html());
                                                if ($("input[name=payBonus][value=1]:visible").prop("checked"))
                                                    $("input[name=payBonus][value=1]:visible").click();



                                                if ($("#bonuspercent_<?= $productInfo['productId'] ?>").html() == 0) {
                                                    $("#discount_<?= $productInfo['productId'] ?>").html(<?= $discountProd; ?>);
                                                    $("#bonuspay_<?= $productInfo['productId'] ?>").html(0);

                                                    $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) *<?= $discountProd; ?> / 100));

                                                } else {
                                                    $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));

                                                    $("#bonuspay_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));
                                                }
                                                var totalBonuspay = 0;
                                                $("div.borderCart").children("table:first").children("tbody").children("tr").each(function(i) {
                                                    totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(":eq(1)").html()));

                                                });
                                                while (totalBonuspay > <?= $this->bonusCount ?>) {
                                                    //console.log(totalBonuspay);

                                                    $("#bonuspercent_<?= $productInfo['productId'] ?>").html(eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) - 5);
                                                    $("#bonuspay_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));

                                                    var totalBonuspay = 0;
                                                    $("div.borderCart").children("table:first").children("tbody").children("tr").each(function(i) {
                                                        totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(":eq(1)").html()));

                                                    });
                                                }
                                                if ($("#bonuspercent_<?= $productInfo['productId'] ?>").html() != 0) {
                                                    $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));
                                                }
                                                $("#bonusPercet1Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                $("#bonusPercet2Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                var totalBonuspay = 0;
                                                var totalCost = 0;
                                                $("div.borderCart").children("table:first").children("tbody").children("tr").each(function(i) {

                                                    totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                                                    totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(":eq(1)").html()));
                                                    $("#PriceBonusTable").html(totalBonuspay);
                                                    //console.log(totalCost);
                                                });
                                                $("#cartTotalCost").html(eval(totalCost));
                                                $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                                            });

                                 '> </div>
                        </td>
                        <td><div style="display: inline-block;" id="totalcost_<?= $productInfo['productId'] ?>"><?= $productInfo['quantity'] * (($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? $productInfo['price_w_discount'] : round($productInfo['price'] - $productInfo['bonuspay'])) ?></div></td>
                        <td><a onClick='$("div#content").load("/deletefromcart/<?= $key + 1 ?>");'><img width="16" border="0" height="16" align="absmiddle" title="Удалить товар из корзины" alt="Удалить" src="/images/icons/cross.png"></a></th>
                    </tr>
    <?php endforeach; ?>
            </tbody>
        </table>




        <div style=" color: #414141;">
            <div style="padding:10px;float: left;">
                <span style="color: #c3060e;">*</span> После оплаты заказа на ваш счет будут зачислены <span style="color: #c3060e;"><?= $bonusAddUser ?></span> <a href="/programma-on-i-ona-bonus" target="_blank"><u>бонусных рублей</u></a><br />
                <span style="color: #c3060e;">**</span> Товары со скидкой (Акционные товары) нельзя оплатить бонусами.

            </div>
    <? if ($sf_user->isAuthenticated()): ?> <div style="padding:10px;float: left;position: absolute;margin-top: 30px;"> 
                    <div style="border: 1px solid #c3060e; max-width: 390px; height: 56px; margin-top: 20px; border-radius: 4px 4px 4px 4px;">
                        <img src="/newdis/images/cart/surprice.png" style="right: -5px; position: relative; float: left;">
                        <div style="float: left; margin: 18px 5px 0pt 10px;">Сейчас на вашем Бонусном счете <span style="color: #c3060e;"><?
                                echo $this->bonusCount;
                                $bonusCount = $this->bonusCount;
                                ?></span> рублей.</div>
                    </div></div><?
            endif;

            if ($bonusCount == "")
                $bonusCount = 0;
            ?>

            <div style="text-align: left; position: absolute; right: 21px; width: 191px; background-color: #f1f1f1;padding:15px;">
                <div style="clear: both; margin-bottom: 5px;">Сумма заказа: <div style="float:right;"><span id="cartTotalCost" style="font-weight: normal;"><?= $TotalSumm ?></span></div>
                </div>
                <div style="clear: both; margin-bottom: 5px;">Доставка: <div style="float:right;"><span id="PriceDeliverTable"><?= ($TotalSumm < csSettings::get('free_deliver') ? '200' : '0') ?></span></div>
                </div>
                <div style="clear: both; margin-bottom: 5px;">Бонусы: <div style="float:right;"><span id="PriceBonusTable"><?= $totalBonusPay ?></span></div>
                </div>
                <div style="clear: both; margin-bottom: 5px;">Итого к оплате: <div style="float:right;"><span style="font-weight: bold;color: #c3060e;" id="PriceAllTable"><?= $TotalSumm + ($TotalSumm < csSettings::get('free_deliver') ? 200 : 0) ?></span></div>
                </div>
            </div>


        </div>
        <div style="clear: both; height: 93px;"></div>

        <div style="clear: both;margin-bottom: 20px;"></div>
        <div style="color: #c3060e;width: 934px;background-color: #f1f1f1;padding: 10px 10px 10px 15px; font-size: 14px;">Вам может понадобиться<div style="display: inline; margin-left: 30px; cursor: pointer;" onclick="$('#more_products').toggle();
                    if ($('#txtToggle').html() == 'посмотреть')
                        $('#txtToggle').html('скрыть');
                    else
                        $('#txtToggle').html('посмотреть');"><div style="border-bottom: 2px dotted #c3060e;display: inline;" id="txtToggle">посмотреть</div><div style="display: inline-block; background-image: url('/newdis/images/loupe.png'); background-size: 100% auto; position: relative; height: 16px; width: 16px; top: 5px; left: 5px;"></div></div></div>
        
        <script type="text/javascript">
            function CallPrint(strid)
            {
                var prtContent = $(strid);
                var prtCSS = '<link rel="stylesheet" href="/newdis/css/all.css" type="text/css" />';
                var WinPrint = window.open('', '', 'left=50,top=50,width=800,height=700,toolbar=0,scrollbars=1,status=0');
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
        <ul class="item-list" style="display: none;" id="more_products">
            <?php
            $items2cart = csSettings::get('items2cart');
            $items2cart = explode(",", $items2cart);
            foreach ($items2cart as $prodNum => $product):
                $product = ProductTable::getInstance()->findOneById($product);

                if ($product->getId() != ""):

                    if ($product->getCount() > 0) {
                        $prodCount = 1;
                    } else {
                        $prodCount = 0;
                    }
                    include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-line4", 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'line4' => true));

                /*    $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
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


                  <div class="t"></div>
                  <div class="c">
                  <div class="content">
                  <?= $product->getDiscount() > 0 ? '<span class="sale' . $product->getDiscount() . '">-' . $product->getDiscount() . '%</span>' : ''; ?>
                  <div class="title"><a href="/product/<?= $product->getSlug() ?>"><? mb_internal_encoding('UTF-8'); ?><?= mb_substr($product->getName(), 0, 45) ?></a></div>
                  <div class="img-holder">
                  <a href="/product/<?= $product->getSlug() ?>"><img id="photoimg_<?= $product->getId() ?>" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt="<?= $product->getName() ?>" /></a>

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
                  <a href="#" class="to-desire" onClick='javascript: addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>");$("#JelHeader").load("/cart/addtojel/<?= $product->getId() ?>");'></a>

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
                  </div>
                  <div class="item-char">
                  <form action="/cart/addtocart/<?= $product->getId() ?>" class="search">
                  <fieldset>
                  <?php
                  $arrayDopInfo = array();
                  $dopInfos = $product->getDopInfoProducts();
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
                  <a href="#" class="to-desire" title="Добавьте товар в список своих желаний и вы сможете легко вернуться к просмотру данного товара, удалить из списка желаний или заказать его" onClick='javascript: addToCartAnim("Cart", "#photoimg_pr_<?= $product->getId() ?>");$("#JelHeader").load("/cart/addtojel/<?= $product->getId() ?>");'></a>
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
                  <?php if ($product->getoldPrice() == "" or $product->getoldPrice() == 0): ?>
                  <div class="bonus-box"><div class="plashbon" onClick="location.replace('http://club.onona.ru/index.php/topic/116-programma-on-i-ona--bonus/'); "><?= $product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add') ?>%
                  <span class="plashbontxt">= <?= round($product->getPrice() * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100)) ?> бонусов возвращаем на ваш личный счет / 1 бонус = 1 руб.</span></div>
                  </div>
                  <? Endif; ?>
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

                  </div>
                  <div class="row">

                  </div>
                  </div>
                  </div>
                  </div>
                  </div>
                  <div class="b"></div>
                  </li>











                  <?php */
                endif;
            endforeach;
            ?>

        </ul>
        <script>
            function ActionIsset(input) {
                $.post("/cart/actioninfo", {text: $(input).val()},
                function(data) {
                    $(".cartContent").html(data);
                });
            }
        </script>
        <div class="tabset" style="margin-top: 20px;">
            <ul class="tab-control">
                <li class="active" style="margin-left: 10px;"><a href="#"><span>Быстрый заказ в один клик</span></a></li>
                <li><a href="#"><span>Полная форма заказа</span></a></li>
            </ul>
            <div class="tab" style="display:block;">
                <form id="processOrder1" name="confirm_order" method="post" action="/cart/confirmed">  <input name="deliveryPriceSend" type="hidden" value ="<?= ($TotalSumm < csSettings::get('free_deliver') ? '200' : '0') ?>">
                    <?
                    foreach ($products_old as $key => $productInfo) {
                        ?>
                        <input name="bonusPercet1Form[<?= $key ?>]" type="hidden" id="bonusPercet1Form-<?= $key ?>" value="<?= $productInfo['percentbonuspay'] ?>"><? }
            ?>
                    <input name="formType" type="hidden" value="1">
                    <table class="tableRegister" style="margin-bottom: 10px;">
                        <tr>
                            <th style="text-align: right;width: 120px;">*Имя</th>
                            <td><input name="user_name" type="text"<? if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getFirstName() . "'"; ?>><span></span></td>
                        </tr>
                        <tr>
                            <th style="text-align: right;">*E-Mail</th>
                            <td><input name="user_mail" type="text"<? if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getEmailAddress() . "' readonly"; ?>><span></span><? if ($sf_user->isAuthenticated()) echo "<div style='display: inline;'> Изменить свой e-mail вы можете в <a href=\"/customer/mydata\">личном кабинете</a></div>"; ?></td>
                        </tr>
                        <tr>
                            <th style="text-align: right;">*Мобильный телефон</th>
                            <td  style=""><input name="user_phone" type="text" id="sf_guard_user_phone2"<? if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getPhone() . "'"; ?>><span style="margin-left: 295px;float: left;"></span></td>
                        </tr>
                    </table>
                    <input type="hidden" name="coupon" id="coupnumber1">
                    <div style="padding: 0px 0px 15px 38px; text-align: right; float: left; width: 264px;"><div style="width:77px;display: inline-block;"><input name="payBonus" onclick="checkBonus(this);" value="2" type="radio" style="margin-top: -3px;">&nbsp;Акция </div>
                        <input style="margin-left: 5px; width: 170px;" id="coupon" class="couponInput" name="couponTxt" onclick="$(this).keyup(function() {
                                        $(this).prev().attr('checked', 'checked');
                                    });" onchange="$('#coupnumber1').val(this.value);
                                            ActionIsset(this);" onkeyup="" type="text"><br><span style="font-size: 11px;">Введите номер купона или код для скидки</span></div>

                    <? If ($this->bonusCount > 0 and false) { ?><div style="padding:2px 0px 15px 40px;text-align: left; float: left;"><input type="radio" name="payBonus" onclick="checkBonus(this);
                                    $('.couponInput').each(function(i) {
                                        $(this).val('');
                                    });" value="1" style="margin-top: -3px;">&nbsp;<div style="display: inline-block; height: 21px;">Бонусы</div><br><div style="font-size: 11px; padding-left: 27px;">Оплата бонусами <?= csSettings::get('PERSENT_BONUS_PAY') ?>% стоимости заказа</div></div><? } ?>

    <? /* <div style="padding:0px 0px 15px 10px;text-align: left; "><input checked="checked" name="payBonus" onclick="checkBonus(this);" value="3" type="radio">&nbsp;Без скидки</div> */ ?>
                    <div style="clear: both; "></div>
                    <div style=" margin-top: 20px;margin-bottom: 20px;;border-bottom: 2px dotted #c3060e; cursor: pointer; color:#c3060e;float: left;" onclick="$('#commentOrder').toggle();
                                if ($(this).html() == 'Добавить комментарий к заказу')
                                    $(this).html('Скрыть комментарий к заказу');
                                else
                                    $(this).html('Добавить комментарий к заказу');">Добавить комментарий к заказу</div>

                    <div style="clear: both; "></div>
                    <div id="commentOrder" style="margin-top: 20px;display: none;"><div>
                            Укажите удобное время звонка, когда наш менеджер свяжется с вами для подтверждения заказа. <br />
                            <input type="text" id="timeCall" name="timeCall" style="width:300px;margin-bottom: 20px;">
                        </div>       
                        <div>
                            Ваши пожелания по заказу и доставке (любая дополнительная информация, которую мы должны знать). <br />
                            <textarea style="width:300px; height:100px;" id="comments" name="comments"></textarea>
                        </div></div><div style="padding:10px;float: left;">
                        <span style="color: #c3060e;">*</span> - поля, обязательны для заполнения.<br />
                        Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить ваш заказ.<br />
                    </div><div style="clear: both; height: 10px;"></div><input type="checkbox" id="18form1">Подтверждаю, что мне 18 или более лет.<span></span>
                    <div style="clear: both; height: 20px;"></div>
                    <a class="red-btn colorWhite" onClick="$('#processOrder1').submit()"><span style="width: 250px;">Отправить заказ</span></a>
                </form>
            </div>
            <div class="tab">
                <div style="color: #c3060e;width: 934px;background-color: #f1f1f1;padding: 10px 10px 10px 15px; font-size: 14px;margin-bottom: 15px;">Выберите способ доставки и оплаты</div>

                <form id="processOrder2" name="confirm_order" method="post" action="/cart/confirmed"><input name="formType" type="hidden" value="2"><input id="deliveryPriceSend" name="deliveryPriceSend" type="hidden" value ="<?= ($TotalSumm < csSettings::get('free_deliver') ? '200' : '0') ?>">
                    <?
                    foreach ($products_old as $key => $productInfo) {
                        ?>
                        <input name="bonusPercet2Form[<?= $key ?>]" type="hidden" id="bonusPercet2Form-<?= $key ?>" value="<?= $productInfo['percentbonuspay'] ?>"><? }
            ?>
                    <script  type='text/javascript'>
                        var nextStep;
                        function checkPayment(data)
                        {
                            $valDeliv = $(":radio[name=deliveryId]").filter(":checked").val();

                            if ($("#TotalSumm").html() < 2990) {
                                $("#deliverPrice").html('200 р.');
                                $("#deliverPriceTotal").html(200 + eval($("#cartTotalCost").html()) + ' р.');
                            } else if ($("#TotalSumm").html() >= 2990) {
                                $("#deliverPrice").html('0 р.');
                                $("#deliverPriceTotal").html(eval($("#cartTotalCost").html()) + ' р.');
                            }

                            if ($valDeliv == '3' && data.value == '5') {
                                if ($("#cartTotalCost").html() < 2990) {
                                    $("#deliverPrice").html(220 + ' р.');
                                    $("#deliverPriceTotal").html(220 + eval($("#cartTotalCost").html()) + ' р.');
                                } else {
                                    $("#deliverPrice").html('0 р.');
                                    $("#deliverPriceTotal").html(eval($("#cartTotalCost").html()) + ' р.');
                                }
                            } else if ($valDeliv == '3') {
                                $("#deliverPrice").html(Math.round(220 + eval($("#cartTotalCost").html()) * 0.05) + ' р.');
                                $("#deliverPriceTotal").html(Math.round(220 + eval($("#cartTotalCost").html()) * 1.05) + ' р.');
                            } else if ($valDeliv == '9') {

                                if ($("#cartTotalCost").html() < 4990) {
                                    $("#deliverPrice").html(300 + ' р.');
                                    $("#deliverPriceTotal").html(300 + ' р.');
                                } else {
                                    $("#deliverPrice").html('0 р.');
                                    $("#deliverPriceTotal").html(eval($("#cartTotalCost").html()) + ' р.');
                                }
                            }

                            $('#deliveryPriceSend').val($('#deliverPrice').html());
                            $('#ButtonNext').css('display', 'block');
                        }


                        function checkDelivery(data)
                        {
                            //console.log(nextStep);
                            data = data.find('input');

                            if ($("#cartTotalCost").html() <<?= csSettings::get('free_deliver') ?>) {
                                $("#PriceDeliverTable").html('200');
                            } else {
                                $("#PriceDeliverTable").html('0');
                            }

                            if (data.val() == '3' && $('input[type=radio][name=paymentId]:checked').val() == '5') {
                                if ($("#cartTotalCost").html() <<?= csSettings::get('free_deliver') ?>) {
                                    $("#PriceDeliverTable").html(220);
                                } else {
                                    $("#PriceDeliverTable").html('0');
                                }
                            } else if (data.val() == '3') {
                                $("#PriceDeliverTable").html(220 + Math.round(eval($("#cartTotalCost").html()) * 0.05));
                            } else if (data.val() == '9') {

                                if ($("#cartTotalCost").html() < 4990) {
                                    $("#PriceDeliverTable").html(300);
                                } else {
                                    $("#PriceDeliverTable").html('0');
                                }
                            }
                            $('#deliveryPriceSend').val($('#PriceDeliverTable').html());
                            $("#PriceAllTable").html(eval($("#cartTotalCost").html()) + eval($("#PriceDeliverTable").html()) + eval($("#PriceBonusTable").html()));

                            nextStep = true;
                            $('.red-btn').fadeIn();

                        }


                        function checkBonus(data)
                        {
                            if (data.value == 1) {
                                var $bonusPay = 0;
                                $(".cartContent:first tbody tr").each(function(i) {
                                    $children = $(this).children();
                                    if ($($children[2]).text() == 0) {
                                        $bonusPay = $bonusPay + Math.round(eval($($children[4]).text() *<?= csSettings::get('PERSENT_BONUS_PAY') / 100 ?>));
                                    }
                                });

                                if ($bonusPay > <?= $bonusCount ?>) {
                                    $("#PriceBonusTable").html("- <?= $bonusCount ?>");
                                } else {
                                    $("#PriceBonusTable").html("-" + $bonusPay);
                                }
                                /*$("#PriceBonusTable").html(<?
            if ($bonusPay > $bonusCount) {
                echo "-" . $bonusCount;
            } else {
                echo "-" . $bonusPay;
            }
            ?>);*/
                            }
                            if (data.value == 2) {
                                $("#PriceBonusTable").html(0);
                            }
                            if (data.value == 3) {
                                $("#PriceBonusTable").html(0);
                            }
                            $("#PriceAllTable").html(eval($("#cartTotalCost").html()) + eval($("#PriceDeliverTable").html()) + eval($("#PriceBonusTable").html()));

                        }


                        function checkBonusMenu(data)
                        {
                            if (data.val() == 1) {
                                $("#PriceBonusTable").html(<?
            if ((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm > $bonusCount) {
                echo "-" . $bonusCount;
            } else {
                echo "-" . round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm);
            }
            ?>);
                            }
                            if (data.val() == 2) {
                                $("#PriceBonusTable").html(0);
                            }
                            if (data.val() == 3) {
                                $("#PriceBonusTable").html(0);
                            }
                            $("#PriceAllTable").html(eval($("#cartTotalCost").html()) + eval($("#PriceDeliverTable").html()) + eval($("#PriceBonusTable").html()));

                        }
                    </script>

                    <input type="hidden" name="coupon" id="coupnumber2"><script>
                        function SelectDelivery(delivery) {
                            // $(this).find('input').attr('checked', 'checked'); 
                            checkDelivery($(delivery));
                            checkBonusMenu($('input[type=radio][name=payBonus]:checked'));
                            window.setTimeout('$(\'.tabDelivery:visible input[type=radio][name=paymentId]\').first().attr(\'checked\', \'checked\');', 100);
                            window.setTimeout('$("input[type=radio][value=' + $(delivery).find('input').val() + '][name=deliveryId]").attr(\'checked\',\'checked\');', 50);
                            window.setTimeout('$("input[type=radio][value=3][name=payBonus]:visible").attr(\'checked\',\'checked\');', 50);

                            //window.setTimeout("$('#content').css('height',$('.borderCart').height())", 50); 
                            return false;
                        }
                    </script>
                    <div class="tabset_old tabsetDeliver tabsetDelivery">
                        <ul class="tab-controlDelivery" style="margin: 0px; padding: 0px;">
                            <?php
                            $delivers = DeliveryTable::getInstance()->createQuery()->where('is_public = \'1\'')->orderBy('position ASC')->execute();

                            foreach ($delivers as $delivery):
                                ?>
                                <li>
                                    <a href="#" onclick="SelectDelivery(this)">
                                        <div class="center"><input style="float: left;" type="radio" value="<?= $delivery->getId() ?>" id="delivMod" name="deliveryId" onClick=" return false;
                                                                   "><span style=" width: 225px; padding: 0;"> <?= $delivery->getName() ?></span></div>
                                        <div class="helper"></div>
                                    </a>

                                </li>

                        <?php endforeach; ?>
                        </ul>
                        <?php
                        foreach ($delivers as $delivery):
                            ?>
                            <div class="tabDelivery">
                                <?= $delivery->getContent(); ?>
        <? if ($delivery->getId() == 11) { ?>

                                    <script type="text/javascript" src="https://pickpoint.ru/select-test/widget/postamat.js" /></script>
                                    <div id="pickpoint" style=""><br />
                                        <div style="text-align: center; width: 100%;"><a href="#" onclick="PickPoint.open(my_function);
                                                            return false" style="font: 17px/18px Tahoma,Geneva,sans-serif;color: #C3060E;"><b>Выбрать постамат или пункт выдачи на карте</b></a></div>
                                        <div id="address"></div>
                                        <input type="hidden" name="pickpoint_id" id="pickpoint_id">
                                        <input type="hidden" name="pickpoint_address" id="pickpoint_address" value="" /><br /><br />
                                        <script type="text/javascript">
                                            function my_function(result) {
                                                // устанавливаем в скрытое поле ID терминала
                                                document.getElementById('pickpoint_id').value = result['id'];
                                                document.getElementById('pickpoint_address').value = result['name'] + '<br />' + result['address'];

                                                // показываем пользователю название точки и адрес доствки
                                                document.getElementById('address').innerHTML = result['name'] + '<br />' + result['address'];
                                            }
                                        </script></div>
                                    <?
                                }
                                if ($delivery->getId() == 13) {
                                    ?>
                                    <div style="text-align: center; width: 575px;"><a href="#" onclick="openMap();
                                                        return false;" style="font: 17px/18px Tahoma,Geneva,sans-serif;color: #C3060E;"><strong>Выбрать почтамат на карте</strong></a><br /><br />
                                        <script type="text/javascript" src="https://qiwipost.ru/widget/dropdown.php?dropdown_name=machine&field_to_update=qiwiId&field_to_update2=qiwiAddr"></script><script type="text/javascript">inpost_machines_dropdown('machine_id');</script>
                                        <p>
                                            &nbsp;</p>
                                        <p>
                                            <input id="qiwiId" name="qiwiId" type="hidden" /> <input id="qiwiAddr" name="qiwiAddr" type="hidden" /></p>
                                    </div>
                                    <?
                                }
                                $deliveryPayment = DeliveryPaymentTable::getInstance()->createQuery()->where('delivery_id=' . $delivery->getId())->orderBy('position ASC')->execute();
                                //$payments = $delivery->getDeliveryPayments();
                                ?>

                                <div style="clear: both;margin-bottom: 20px;"></div>
                                <table style="width: 725px; margin: -11px;" cellspacing="0" cellpadding="0" bordercolor="#000000" border="0" align="center" class="cartContent">
                                    <thead><tr>
                                            <th style="color: #c3060e; width: 50%; font-size: 14px;">Способ оплаты:</th>
                                            <th style="color: #c3060e; font-size: 14px;">Выберете скидку:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                foreach ($deliveryPayment as $payment):
                                                    $payment = $payment->getPayment();
                                                    ?>
                                                    <div style="padding:0px 0px 15px 10px;text-align: left;"><input type="radio" value="<?= $payment->getId() ?>" onclick="checkDelivery($('input[type=radio][name=deliveryId]:checked').parent());" name="paymentId">&nbsp;<?= $payment->getName() ?><br><span  style="font-size: 11px;"><?= $payment->getContent() ?></span></div>
        <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <? If ($this->bonusCount > 0 and false) { ?><div style="padding:0px 0px 15px 10px;text-align: left;"><input type="radio" name="payBonus" onclick="checkBonus(this);
                                                                    $('.couponInput').each(function(i) {
                                                                        $(this).val('');
                                                                    });" value="1">&nbsp;Бонусы<br><span style="font-size: 11px;">Оплата бонусами <?= csSettings::get('PERSENT_BONUS_PAY') ?>% стоимости заказа</span></div><? } ?>
                                                <div style="padding:0px 0px 15px 10px;text-align: left;"><input type="radio" name="payBonus" onclick="checkBonus(this);" value="2">&nbsp;Акция <input type="text" id="coupon" class="couponInput" name="couponTxt" onkeyup="" onclick="$(this).keyup(function() {
                                                                    $(this).prev().attr('checked', 'checked');
                                                                });" onchange="$('#coupnumber2').val(this.value);
                                                                        ActionIsset(this);"><br><span style="font-size: 11px;">Введите номер купона или код для скидки</span></div>
        <? /* <div style="padding:0px 0px 15px 10px;text-align: left;"><input checked="checked" type="radio" name="payBonus" onclick="checkBonus(this);" value="3">&nbsp;Без скидки</div> */ ?>


                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

    <?php endforeach; ?>
                    </div>
                    <div style="clear: both;margin-bottom: 40px;"></div>
                    <div style="color: #c3060e;width: 934px;background-color: #f1f1f1;padding: 10px 10px 10px 15px; font-size: 14px;margin-bottom: 15px;">Заполните свои контактные данные</div>
                    <table style="width:100%;" class="tableRegister">

                        <?php
                        $form = new sfGuardRegisterNewForm();
                        if ($sf_user->isAuthenticated())
                            $form = new sfGuardRegisterNewForm($sf_user->getGuardUser());
                        foreach ($form as $key => $field):

                            if (!$field->isHidden()):
                                ?>

                                <tbody>
                                    <tr>
                                        <th style="width: 120px;"><label for="sf_guard_user_first_name" style="font-weight: normal;">
                                            <?php echo $field->renderLabel() ?></label></th>
                                        <td>
                                            <? if ($field->renderLabel() == "<label for=\"sf_guard_user_password\">Пароль*</label>") {
                                                ?><script>
                                                    function mtRand(min, max)
                                                    {
                                                        var range = max - min + 1;
                                                        var n = Math.floor(Math.random() * range) + min;
                                                        return n;
                                                    }


                                                    function showPass()
                                                    {
                                                        genPass = mkPass(mtRand(10, 14));
                                                        $('#sf_guard_user_password').val(genPass);
                                                        $('#sf_guard_user_password_again').val(genPass);
                                                    }

                                                    function mkPass(len)
                                                    {
                                                        var len = len ? len : 14;
                                                        var pass = '';
                                                        var rnd = 0;
                                                        var c = '';
                                                        for (i = 0; i < len; i++) {
                                                            rnd = mtRand(0, 2); // Латиница или цифры
                                                            if (rnd == 0) {
                                                                c = String.fromCharCode(mtRand(48, 57));
                                                            }
                                                            if (rnd == 1) {
                                                                c = String.fromCharCode(mtRand(65, 90));
                                                            }
                                                            if (rnd == 2) {
                                                                c = String.fromCharCode(mtRand(97, 122));
                                                            }
                                                            pass += c;
                                                        }
                                                        return pass;
                                                    }
                                                </script>

                                                <? /* <a href="javascript: showPass();">Сгенерировать надежный пароль</a> */ ?>
                                            <? }
                                            ?>
                                            <?php echo $field->renderError() ?>
                                            <?php echo $field ?>

                                            <?
                                            if ($field->renderLabel() == "<label for=\"sf_guard_user_birthday\">Дата рождения</label>") {
                                                echo "&nbsp;&nbsp;Получайте в этот день от нас приятные подарки!";
                                            }
                                            ?>

                                            <?
                                            if ($field->renderLabel() == "<label for=\"sf_guard_user_email_address\">*E-mail (логин)</label>") {
                                                echo "<span></span>
                                                    ";
                                                if ($sf_user->isAuthenticated())
                                                    echo "<div style='display: inline;'> Изменить свой e-mail вы можете в <a href=\"/customer/mydata\">личном кабинете</a></div>
                                                    ";
                                            }
                                            if ($field->renderLabel() == "<label for=\"sf_guard_user_phone\">*Телефон</label>") {
                                                echo "<span style=\"margin-left: 295px;float: left;\"></span>
                                                    ";
                                            }
                                            ?>

                                        </td>
                                    </tr>
                                </tbody>

                                <?php
                            else:
                                echo $field;
                                if ($field->renderLabel() == "<label for=\"sf_guard_user_birthday\">Дата рождения</label>") {
                                    echo "&nbsp;&nbsp;Получайте в этот день от нас приятные подарки!";
                                }
                            endif;
                        endforeach;
                        ?>
                    </table>

                    <div style="clear: both; "></div>
                    <div style=" margin-top: 20px;margin-bottom: 20px;;border-bottom: 2px dotted #c3060e; cursor: pointer; color:#c3060e; float: left;" onclick="$('#commentOrder2').toggle();
                                if ($(this).html() == 'Добавить комментарий к заказу')
                                    $(this).html('Скрыть комментарий к заказу');
                                else
                                    $(this).html('Добавить комментарий к заказу');">Добавить комментарий к заказу</div>
                    <div style="clear: both; "></div>
                    <div id="commentOrder2" style="margin-top: 20px;display: none;"><div>
                            Укажите удобное время звонка, когда наш менеджер свяжется с вами для подтверждения заказа. <br />
                            <input type="text" id="timeCall" name="timeCall" style="width:300px;margin-bottom: 20px;">
                        </div>       
                        <div>
                            Ваши пожелания по заказу и доставке (любая дополнительная информация, которую мы должны знать). <br />
                            <textarea style="width:300px; height:100px;" id="comments" name="comments"></textarea>
                        </div></div><div style="padding:10px;float: left;">
                        <span style="color: #c3060e;">*</span> - поля, обязательны для заполнения.<br />
                        Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить ваш заказ.<br />
                    </div><div style="clear: both; height: 10px;"></div><input type="checkbox" id="18form2">Подтверждаю, что мне 18 или более лет.<span></span>
                    <div style="clear: both; height: 20px;"></div>
                    <a class="red-btn colorWhite" onClick="$('#processOrder2').submit()"><span style="width: 250px;">Отправить заказ</span></a></form>
            </div>
        </div>
        <div style="clear: both; "></div>
    </div><div style="clear: both; "></div>
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
    (function() {
        var s = document.createElement("script");
        s.async = true;
        s.src = (document.location.protocol == "https:" ? "https:" : "http:") + "//cdn.admitad.com/static/js/retag.js";
        var a = document.getElementsByTagName("script")[0]
        a.parentNode.insertBefore(s, a);
    })()
</script>


