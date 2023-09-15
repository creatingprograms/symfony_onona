<div id="cartPage">
    <div class="cartPageTitle">Моя корзина </div>
    <?php if (is_array($productsToCart) and count($productsToCart) > 0):
        ?>
        <?php if (!$sf_user->isAuthenticated()): ?>
            <div class="blockAuth">
                <div>
                    <span style="color: #c3060e; font-size: 14px;">Зарегистрированный покупатель</span> <br />
                    Авторизуйтесь для быстрого оформления заказа. Возможно, на вашем
                    счете уже есть Бонусы на оплату от 20 до 50% стоимости заказа.<br /><br />
                    <span style="color: #c3060e; font-size: 14px;">Новый покупатель</span><br />
                    Зарегистрируйтесь и получите 300 приветственных бонусов, которыми вы
                    сможете оплатить свой заказ прямо сейчас.<br /><br />
                    <a href="<?php echo url_for('@sf_guard_signin') ?>" class="silverButton">Войти/Зарегистрироваться</a>
                </div>
            </div>
        <?php endif; ?>
        <div style="clear: both;margin-bottom: 20px;"></div>
        <ul class="productsList">
            <?php
            $TotalSumm = 0;
            $totalBonusPay = 0;
            foreach ($productsToCart as $key => $productInfo):
                $TotalSumm = $TotalSumm + ((($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? ($productInfo['quantity'] * $productInfo['price_w_discount']) : round($productInfo['quantity'] * $productInfo['price'] - $productInfo['bonuspay'])));
                $totalBonusPay = $totalBonusPay + $productInfo['bonuspay'];
                $discountProd = ($productInfo['price_w_discount'] > 0 ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0');
                ?>
                <li>
                    <div class="productPhoto">
                        <a href="<?php echo url_for('@product_show?slug=' . $productsAll[$productInfo['productId']]['slug']) ?>">
                            <img width="150" src="/uploads/photo/thumbnails_250x250/<?= $photosAll[$productInfo['productId']]['filename'] ?>">
                        </a>
                    </div>

                    <div class="productParams">
                        <div class="name">
                            <a href="<?php echo url_for('@product_show?slug=' . $productsAll[$productInfo['productId']]['slug']) ?>">
                                <?= $productsAll[$productInfo['productId']]['name'] ?>
                            </a>
                        </div>
                        <div class="price">Цена: <div style="display: inline-block" id="price_<?= $productInfo['productId'] ?>"><?= $productInfo['price'] ?></div> руб.</div>
                        <div class="discount">Скидка: <div id="discount_<?= $productInfo['productId'] ?>"><?= $discountProd ?></div> %</div>
                        <div class="bonus">
                            Бонусы:
                            <div style="background-image: url('/newdis/images/cart/min_count.png'); display: inline-block; position: relative;left: 4px; height: 23px; width: 18px; top: 9px;"
                                 onClick='
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
                                                     $("div.productParams").each(function (i) {

                                                         totalCost = eval(totalCost) + eval(parseInt($(this).children(".cost").children(".totalcost").html()));
                                                         totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(".bonus").children(".bonuspaycount").html()));
                                                         $("#PriceBonusTable").html(totalBonuspay);
                                                     });
                                                     $("#cartTotalCost").html(eval(totalCost));
                                                     $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));

                                                 }
                                 ' > </div>
                            <div id="bonuspay_<?= $productInfo['productId'] ?>" class="bonuspaycount" style="display: none"><?= $productInfo['bonuspay'] ?></div>
                            <div id="bonuspercent_<?= $productInfo['productId'] ?>" style="" class="cartCount"><?= $productInfo['percentbonuspay'] ?></div>
                            <div style="background-image: url('/newdis/images/cart/pl_count.png'); display: inline-block; position: relative;left: -4px; top: 9px; height: 23px; width: 18px;" onClick='

                                            var totalBonuspay = 0;
                                            $("div.productParams").children(".bonus").each(function (i) {
                                                totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(".bonuspaycount").html()));
                                            });
                                            if ((eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) + 5) <=<?= $productsAll[$productInfo['productId']]['bonuspay'] != '' ? $productsAll[$productInfo['productId']]['bonuspay'] : csSettings::get('PERSENT_BONUS_PAY') ?> && totalBonuspay + Math.round((eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * 5) / 100) < <?= $bonusCount ?>) {
                                                $("#bonuspercent_<?= $productInfo['productId'] ?>").html(eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) + 5);
                                                $("#discount_<?= $productInfo['productId'] ?>").html("0");
                                                $("#bonuspay_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));
                                                $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));

                                                $("#bonusPercet1Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                $("#bonusPercet2Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                var totalBonuspay = 0;
                                                var totalCost = 0;
                                                $("div.productParams").each(function (i) {

                                                    totalCost = eval(totalCost) + eval(parseInt($(this).children(".cost").children(".totalcost").html()));
                                                    totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(".bonus").children(".bonuspaycount").html()));
                                                    $("#PriceBonusTable").html(totalBonuspay);
                                                });
                                                $("#cartTotalCost").html(eval(totalCost));
                                                $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                                            } else {
                                                if ((eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) + 5) <=<?= $productsAll[$productInfo['productId']]['bonuspay'] != '' ? $productsAll[$productInfo['productId']]['bonuspay'] : csSettings::get('PERSENT_BONUS_PAY') ?>) {
                                                    $("#maxBonusCount_<?= $productInfo['productId'] ?>").fadeIn();
                                                    setTimeout(function () {
                                                        $("#maxBonusCount_<?= $productInfo['productId'] ?>").fadeOut()
                                                    }, 2000);
                                                } else {
                                                    $("#maxBonusPercent_<?= $productInfo['productId'] ?>").fadeIn();
                                                    setTimeout(function () {
                                                        $("#maxBonusPercent_<?= $productInfo['productId'] ?>").fadeOut()
                                                    }, 2000);
                                                }
                                            }
                                 '><div style="position: absolute; color: #c3060e; width: 210px; left: -200px; top: -20px;display: none;    background: #fff;border: 1px solid #c3060e; padding: 5px;" id="maxBonusPercent_<?= $productInfo['productId'] ?>">Максимальная Бонусная скидка</div>
                                <div style="position: absolute; color: #c3060e; width: 250px; left: -200px; top: -20px;display: none;   background: #fff;border: 1px solid #c3060e; padding: 5px;" id="maxBonusCount_<?= $productInfo['productId'] ?>">На вашем счете недостаточно Бонусов</div> </div>

                            %
                        </div>
                        <div class="quantity">
                            Количество:
                            <div style="background-image: url('/newdis/images/cart/min_count.png'); display: inline-block; position: relative;left: 4px; height: 23px; width: 18px; top: 9px;" onClick='
                                            if ($("#quantity_<?= $productInfo['productId'] ?>").html() - 1 > 0) {
                                                $.post("<?php echo url_for('@cart_addtocartcount?id=' . $productInfo['productId']) ?>", {count: $("#quantity_<?= $productInfo['productId'] ?>").html() - 1},
                                                function (data) {
                                                    var totalCost = 0;
                                                    $("#quantity_<?= $productInfo['productId'] ?>").html(data);
                                                    $("#totalcost_<?= $productInfo['productId'] ?>").html(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()));
                            <? /* $("#cartTotalCost").html(eval($("#cartTotalCost").html())-(eval($("#price_<?= $productInfo['productId'] ?>").html()))); */ ?>

                                                    $("div.productParams").each(function (i) {

                                                        totalCost = eval(totalCost) + eval(parseInt($(this).children(".cost").children(".totalcost").html()));

                                                    });
                                                    $("#cartTotalCost").html(eval(totalCost));
                                                    $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));


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

                                                    $("div.productParams").each(function (i) {

                                                        totalCost = eval(totalCost) + eval(parseInt($(this).children(".cost").children(".totalcost").html()));
                                                        totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(".bonus").children(".bonuspaycount").html()));
                                                        $("#PriceBonusTable").html(totalBonuspay);
                                                        //console.log(totalCost);
                                                    });
                                                    $("#cartTotalCost").html(eval(totalCost));
                                                    if ($("#cartTotalCost").html() <<?= csSettings::get('free_deliver') ?>) {
                                                        $("#PriceDeliverTable").html("290");
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
                                                    $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                                                });
                                            }
                                 ' > </div>
                            <div id="price_<?= $productInfo['productId'] ?>" style="display: none"><?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?></div>
                            <div id="quantity_<?= $productInfo['productId'] ?>" style="" class="cartCount"><?= $productInfo['quantity'] ?></div>
                            <div style="background-image: url('/newdis/images/cart/pl_count.png'); display: inline-block; position: relative;left: -4px; top: 9px; height: 23px; width: 18px;" onClick='

                                            $.post("<?php echo url_for('@cart_addtocartcount?id=' . $productInfo['productId']) ?>", {count: eval($("#quantity_<?= $productInfo['productId'] ?>").html()) + 1},
                                            function (data) {
                                                var totalCost = 0;
                                                $("#quantity_<?= $productInfo['productId'] ?>").html(data);
                                                $("#totalcost_<?= $productInfo['productId'] ?>").html(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()));

                                                $("div.productParams").each(function (i) {

                                                    totalCost = eval(totalCost) + eval(parseInt($(this).children(".cost").children(".totalcost").html()));

                                                });
                                                $("#cartTotalCost").html(eval(totalCost));
                                                $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));


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
                                                $("div.productParams").children("bonus").each(function (i) {
                                                    totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(".bonuspaycount").html()));
                                                });
                                                while (totalBonuspay > <?= $bonusCount ?>) {
                                                    //console.log(totalBonuspay);

                                                    $("#bonuspercent_<?= $productInfo['productId'] ?>").html(eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) - 5);
                                                    $("#bonuspay_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));

                                                    var totalBonuspay = 0;

                                                    $("div.productParams").each(function (i) {

                                                        totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(".bonus").children(".bonuspaycount").html()));

                                                    });
                                                }
                                                if ($("#bonuspercent_<?= $productInfo['productId'] ?>").html() != 0) {
                                                    $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));
                                                }
                                                $("#bonusPercet1Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                $("#bonusPercet2Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                                var totalBonuspay = 0;
                                                var totalCost = 0;
                                                $("div.productParams").each(function (i) {

                                                    totalCost = eval(totalCost) + eval(parseInt($(this).children(".cost").children(".totalcost").html()));
                                                    totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(".bonus").children(".bonuspaycount").html()));
                                                    $("#PriceBonusTable").html(totalBonuspay);
                                                    //console.log(totalCost);
                                                });
                                                $("#cartTotalCost").html(eval(totalCost));
                                                if ($("#cartTotalCost").html() <<?= csSettings::get('free_deliver') ?>) {
                                                    $("#PriceDeliverTable").html("290");
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

                                                $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                                            });

                                 '>
                            </div>
                        </div>

                        <div class="cost">
                            Сумма:
                            <div style="display: inline-block;" id="totalcost_<?= $productInfo['productId'] ?>" class="totalcost"><?= (($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? ($productInfo['quantity'] * $productInfo['price_w_discount']) : round($productInfo['quantity'] * $productInfo['price'] - $productInfo['bonuspay'])) ?></div>
                            руб.
                        </div>
                        <div class="removeProduct">
                            <a onClick='$("div#contentWrapper").load("<?php echo url_for('@cart_deletefromcart?id=' . ($key + 1)) ?>");'><img width="16" border="0" height="16" align="absmiddle" title="Удалить товар из корзины" alt="Удалить" src="/images/icons/cross.png"></a>
                        </div>

                    </div>
                </li>
                <?php
            endforeach;
            ?>
        </ul>


        <div class="totalInfo">
            <div>
                Сумма заказа: <span id="cartTotalCost" style="font-weight: normal;"><?= $TotalSumm ?></span>
            </div>
            <div>
                Доставка: <span id="PriceDeliverTable"><?= ($TotalSumm < csSettings::get('free_deliver') ? '290' : '0') ?></span>
            </div>
            <div>
                Бонусы: <span id="PriceBonusTable"><?= $totalBonusPay ?></span>
            </div>
            <div>
                Итого к оплате: <span style="font-weight: bold;color: #c3060e;" id="PriceAllTable"><?= $TotalSumm + ($TotalSumm < csSettings::get('free_deliver') ? 290 : 0) ?></span>
            </div>
        </div>
        <div style="padding:10px;margin-top: 20px;"> <img src="/images/attention.png" style="    float: left;
                                                          margin-right: 6px;" /> Итоговая стоимость заказа с учетом всех скидок и бонусов рассчитывается менеджером при подтверждении заказа.
        </div>

        <form id="processOrder1" name="confirm_order" method="post" action="<?php echo url_for('@cart_confirmed') ?>">
            <input name="deliveryPriceSend" type="hidden" value ="<?= ($TotalSumm < csSettings::get('free_deliver') ? '290' : '0') ?>">
            <?php
            foreach ($productsToCart as $key => $productInfo) {
                ?>
                <input name="bonusPercet1Form[<?= $key ?>]" type="hidden" id="bonusPercet1Form-<?= $key ?>" value="<?= $productInfo['percentbonuspay'] ?>"><? }
            ?>
            <input name="formType" type="hidden" value="1">

            <?php
            JSInPages("$(document).ready(function () {

                    $('form#processOrder1').validate({
                        onKeyup: true,
                        sendForm: true,
                        eachValidField: function () {

                            $(this).closest('div').removeClass('error').addClass('success');
                        },
                        eachInvalidField: function () {

                            $(this).closest('div').removeClass('success').addClass('error');
                        },
                        description: {
                            allFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Pattern</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            },
                            mailFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Неправильный формат e-mail</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            },
                            phoneFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Неправильный формат телефона. Пример: +7(777)777-7777</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            },
                            nameFields: {
                                required: '<div class=\"alert alert-error\">Обязательное поле</div>',
                                pattern: '<div class=\"alert alert-error\">Можно использовать только буквы кирилицы</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            },
                            agreementFields: {
                                required: '<div class=\"alert alert-error\">Необходимо принять пользовательское соглашение!</div>',
                                // pattern: '<div class=\"alert alert-error\">Необходимо принять пользовательское соглашение!</div>',
                                conditional: '<div class=\"alert alert-error\">Conditional</div>',
                                // valid: '<div class=\"alert alert-success\">Спасибо</div>'
                            }
                        }
                    });
                    });");
            ?>
            <div class="form">
                <div class="row">
                    <div class="label-holder">
                        <label>Имя*</label>
                    </div>
                    <div id="description-name" class="requeredDescription"></div>
                    <div class="input-holder" style="border:0;padding: 0;">
                        <div id="rate_div_comment"></div>
                        <input type="text" name="user_name"<?php if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getFirstName() . "'"; ?>
                               data-describedby="description-name" data-required="true" data-pattern="^[а-яА-ЯёЁ]+$" data-description="nameFields" class="required">

                    </div>
                </div>
                <div class="row">
                    <div class="label-holder">
                        <label>E-Mai*</label>
                    </div>
                    <div id="description-mail" class="requeredDescription"></div>
                    <div class="input-holder" style="border:0;padding: 0;">
                        <div id="rate_div_comment"></div>
                        <input type="text" name="user_mail"<? if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getEmailAddress() . "' readonly"; ?>
                               data-describedby="description-mail" data-required="true" data-pattern="^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$" data-description="mailFields" class="required">

                    </div>
                </div>
                <div class="row">
                    <div class="label-holder">
                        <label>Мобильный телефон*</label>
                    </div>
                    <div id="description-phone" class="requeredDescription"></div>
                    <div class="input-holder" style="border:0;padding: 0;">
                        <div id="rate_div_comment"></div>
                        <input type="text" name="user_phone"<? if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getPhone() . "'"; ?> placeholder="+7(777)777-7777"
                               data-describedby="description-phone" data-required="true" data-pattern="^\+\d{1}\(\d{3}\)\d{3}-\d{4}$" data-description="phoneFields" class="required">

                    </div>
                </div>
                <div class="row">
                    <div class="label-holder">
                        <label>Акция (Введите номер купона или код для скидки)</label>
                    </div>
                    <div class="input-holder" style="border:0;padding: 0;">
                        <div id="rate_div_comment"></div>
                        <input type="text" name="coupon">

                    </div>
                </div>
                <div class="row">
                    <div class="label-holder">
                        <label>Ваши пожелания по заказу и доставке (любая дополнительная информация, которую мы должны знать). </label>
                    </div>
                    <div class="input-holder" style="border:0;padding: 0;">
                        <textarea style="width:100%; height:100px;padding: 0;" id="comments" name="comments"></textarea>

                    </div>
                </div>
                <div class="row">
                    <div class="label-holder agreement">
                        <input type="checkbox" id="personal-agreement"
                          data-describedby="description-agreement"
                          data-required="true"
                          <?php/*data-pattern="^[а-яА-ЯёЁ]+$"*/?>
                          data-description="agreementFields"
                          class="required"
                        ><label for="personal-agreement">Я принимаю условия <a href='/personal_accept' target='_blank'>Пользовательского соглашения</a> </label>
                    </div>
                    <div id="description-agreement" class="requeredDescription"></div>
                </div>

                <div class="redButton" onclick="$('#processOrder1').find('[type=\'submit\']').trigger('click');">Отправить заказ</div>

                <input type="submit" class="submitButton" style="display: none;" />
            </div>
        </form>

    <?php else:
        ?>
        <div id="pageCartNonProduct"><?= $page['content_mobile'] != "" ? $page['content_mobile'] : $page['content'] ?></div>
    <?php
    endif;
    ?>
</div>
