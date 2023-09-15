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
                                        $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

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
                                    $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {
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
                                        $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

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
                                            setTimeout(function () {
                                                $("#maxBonusCount_<?= $productInfo['productId'] ?>").fadeOut()
                                            }, 2000);
                                        } else {
                                            $("#maxBonusPercent_<?= $productInfo['productId'] ?>").fadeIn();
                                            1
                                            setTimeout(function () {
                                                $("#maxBonusPercent_<?= $productInfo['productId'] ?>").fadeOut()
                                            }, 2000);
                                        }
                                    }
                                 '><div style="position: absolute; color: #c3060e; width: 230px; left: -125px; top: -20px;display: none;" id="maxBonusPercent_<?= $productInfo['productId'] ?>">Максимальная Бонусная скидка</div>
                                <div style="position: absolute; color: #c3060e; width: 275px; left: -125px; top: -20px;display: none;" id="maxBonusCount_<?= $productInfo['productId'] ?>">На вашем счете недостаточно Бонусов</div> </div>

                            <div style="z-index: <?= (count($products_old) * 2) - $key * 2 + 3 ?>;width: 16px; height: 18px; background: url(/images/questionicon.png);margin: 20px auto; position: relative;" onClick="$('#qestionBonus_<?= $productInfo['productId'] ?>').toggle();" onMouseOver="$('#qestionBonus_<?= $productInfo['productId'] ?>').fadeIn()" onMouseOut="$('#qestionBonus_<?= $productInfo['productId'] ?>').fadeOut()">

                            </div> <div style=" position: relative;">
                                <div style="padding: 10px;z-index: <?= (count($products_old) * 2) - $key * 2 + 2 ?>;text-align: left;position: absolute; color: rgb(195, 6, 14); width: 400px; right: 42px; top: -41px;display: none;background: #FFF; border: 1px solid #c3060e;" id="qestionBonus_<?= $productInfo['productId'] ?>">
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
                                        function (data) {
                                            var totalCost = 0;
                                            $("#quantity_<?= $productInfo['productId'] ?>").html(data);
                                            $("#totalcost_<?= $productInfo['productId'] ?>").html(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()));
                            <? /* $("#cartTotalCost").html(eval($("#cartTotalCost").html())-(eval($("#price_<?= $productInfo['productId'] ?>").html()))); */ ?>
                                            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

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
                                            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

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
                                    function (data) {
                                        var totalCost = 0;
                                        $("#quantity_<?= $productInfo['productId'] ?>").html(data);
                                        $("#totalcost_<?= $productInfo['productId'] ?>").html(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()));
                                        $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

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
                                        $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {
                                            totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(":eq(1)").html()));

                                        });
                                        while (totalBonuspay > <?= $this->bonusCount ?>) {
                                            //console.log(totalBonuspay);

                                            $("#bonuspercent_<?= $productInfo['productId'] ?>").html(eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) - 5);
                                            $("#bonuspay_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));

                                            var totalBonuspay = 0;
                                            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {
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
                                        $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

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
