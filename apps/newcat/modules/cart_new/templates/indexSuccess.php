<?php
slot("cartFirstPage", $cartFirstPage);
slot('topMenu', true);
slot('advcake', 4);
?>

<script type="text/javascript" src="/js/jquery.maskedinput-1.2.2.js"></script>
<script type="text/javascript" src="/js/validation.js?v=4"></script>

<?php if (is_array($products_old) and count($products_old) > 0): ?>
    <div class="borderCart" id="gtm-checkout-one">
        <div align="center" class="pink bold" style="padding:5px;color: #ba272d;">Моя корзина </div>

        <?php if (!$sf_user->isAuthenticated()): ?>
          <div class="cart-header">
            <div class="cart-header-element"><span>Зарегистрированный покупатель</span> <br />
                Авторизуйтесь для быстрого оформления заказа. Возможно, на вашем
                счете уже есть Бонусы на оплату от 20 до 50% стоимости заказа.<br /><br />
                <a href="#" class="login"><img src="/newdis/images/cart/enterButtom.png"></a>
            </div>

            <div class="cart-header-element"><span>Новый покупатель</span><br />
                Зарегистрируйтесь и получите 300 приветственных бонусов, которыми вы
                сможете оплатить свой заказ прямо сейчас.<br /><br />
                <a href="/register"><img src="/newdis/images/cart/registerButtom.png"></a>
            </div>
          </div>
        <?php endif; ?>
        <div style="clear: both;margin-bottom: 20px;"></div>

        <table width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000" border="0" align="center" class="cartContent">
            <thead>
              <tr>
                <th>Наименование</th>
                <th>Цена, руб.</th>
                <th>Скидка, %</th>
                <th>Бонусы, %</th>
                <th>Кол-во</th>
                <th>Сумма, руб.</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
                <?php
                $TotalSumm = 0;
                $TotalCostFotterCart = 0;
                $bonusAddUser = 0;
                $reTagProd = '';
                $bonusPay = 0;
                $totalBonusPay = 0;
                $this->bonusCount = 0;
                $screenPosition=0;

                if ($sf_context->getUser()->getGuardUser()) {
                    $this->bonus = BonusTable::getInstance()->findBy('user_id', $sf_context->getUser()->getGuardUser()->getId());
                    foreach ($this->bonus as $bonus) {
                        $this->bonusCount = $this->bonusCount + $bonus->getBonus();
                    }
                }

                //$countProdFromBuyWithItem = ceil(15 / count($products_old));
                $isStock = true;
                $numProduct = 0;
                foreach ($products_old as $key => $productInfo):
                    $itemsInChart[]=$productInfo['productId'];
                    $numProduct++;
                    $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    if(!is_object($product)){
                      // die('sdfsdfsdf-4');
                      unset($products_old[$key]);
                      continue;
                    }
                    $BuyWithItems = unserialize($product->getBuyWithItem());
                    $i = 0;
                    foreach ($BuyWithItems as $idBuyWithItem => $BuyWithItem) {
                        if ($i < 10) {
                            $arrayBuyWithItem[] = $idBuyWithItem;
                        } else {
                            continue;
                        }
                        $i++;
                    }
                    /* for ($i = 0; $i < $countProdFromBuyWithItem; $i++) {
                      $arrayBuyWithItem[] = $BuyWithItem[$i];
                      } */
                    $reTagProd.='{
                        "id": "' . ($product->getId()) . '",   // required
                        "number": "' . ($productInfo['quantity']) . '",
                    },';
                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * (($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? $productInfo['price_w_discount'] : round($productInfo['price'] - $productInfo['bonuspay'])));
                    $TotalCostFotterCart = $TotalCostFotterCart + ($productInfo['quantity'] * round($productInfo['price'] - $productInfo['bonuspay']));
                    //print_r($productInfo);
                    $photoalbum = $product->getPhotoalbums();
                    $photos = $photoalbum[0]->getPhotos();
                    if ($product->getBonus() != 0) {
                        //$productInfo['quantity'] * (($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? $productInfo['price_w_discount'] : round($productInfo['price'] - $productInfo['bonuspay']));
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
                    /*
                    if ($product->getCount() == 0) {
                        $isStock = false;
                    }*/
                    ?>
                    <tr class="gtm-checkout-item">
                        <td style="text-align: left;">
                          <a
                            class="cart-item-link<?= $product->getCount() == 0 ? ' out-of-stock' : ''?>"
                            href="/product/<?= $product->getSlug() ?>" style="<?=isset($photos[0]) ? 'background-image: url(/uploads/photo/thumbnails_250x250/'. $photos[0]->getFilename().')': ''?><?//= $product->getCount() == 0 ? ' opacity: 0.2;' : '' ?>">
                              <?= $product->getName() ?>
                          </a>
                          <? //= $product->getCount() == 0 ? ' <div style="color: #c3060e;font-weight: bold;opacity: 1;">Товара нет в наличии</div>' : '' ?>
                        </td>
                        <td id="price_<?= $productInfo['productId'] ?>"<?//= $product->getCount() == 0 ? ' style="opacity: 0.2;"' : '' ?>>
                          <?= $productInfo['price'] ?>
                        </td>
                        <td id="discount_<?= $productInfo['productId'] ?>"<?//= $product->getCount() == 0 ? ' style="opacity: 0.2;"' : '' ?>>
                          <?= $discountProd; ?>
                        </td>
                        <td class="bonus"<?//= $product->getCount() == 0 ? ' style="opacity: 0.2;"' : '' ?>>
                            <div class="bonus-left" onClick='

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
                                        var totalCostFotterCart = 0;
                                        var totalBonuspay = 0;
                                        $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {
                                            totalBonusadd = (eval(parseInt($(this).children(":eq(5)").find("div").html())) * eval(parseInt($(this).children(":eq(3)").find("div.bonusadd").html()))) / 100;
                                            totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                                            totalCostFotterCart = eval(totalCostFotterCart) + eval(parseInt($(this).children(":eq(1)").html())) * eval(parseInt($(this).children(":eq(4)").find("div.cartCount").html()));
                                            totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(".bonuspay").html()));
                                            $("#PriceBonusTable").html(totalBonuspay);
                                            $("#PriceBonusTable").html(totalCostFotterCart - totalCost);
                                            // console.log(totalBonuspay);
                                        });

                                        $("#totalBonusadd").html(Math.round(eval(totalBonusadd)));
                                        $("#cartTotalCost").html(eval(totalCost));
                                        $("#cartTotalCostFotterCart").html(eval(totalCostFotterCart));
                                        $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));

                                    }
                                 ' > </div>
                            <div id="bonusadd_<?= $productInfo['productId'] ?>" class="bonusadd" style="display: none"><?
                                if ($product->getBonus() != 0) {
                                    echo $product->getBonus();
                                } else {
                                    echo csSettings::get("persent_bonus_add");
                                }
                                ?></div>
                            <div id="bonuspay_<?= $productInfo['productId'] ?>" class="bonuspay" style="display: none"><?= $productInfo['bonuspay'] ?></div>
                            <div id="bonuspercent_<?= $productInfo['productId'] ?>" style="" class="cartCount"><?= $productInfo['percentbonuspay'] ?></div>
                            <div class="bonus-right" onClick='

                                    var totalBonuspay = 0;
                                    $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {
                                        totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(".bonuspay").html()));
                                        //console.log(totalBonuspay);
                                    });
                                    console.log(totalBonuspay + Math.round((eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * 5) / 100));
                                    if ((eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) + 5) <=<?= $product->getBonuspay() != '' ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?> && totalBonuspay + Math.round((eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * 5) / 100) < <?= $this->bonusCount ?>) {
                                        $("#bonuspercent_<?= $productInfo['productId'] ?>").html(eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) + 5);
                                        $("#discount_<?= $productInfo['productId'] ?>").html("0");
                                        $("#bonuspay_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));
                                        $("#totalcost_<?= $productInfo['productId'] ?>").html(Math.round(eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) - eval($("#quantity_<?= $productInfo['productId'] ?>").html()) * eval($("#price_<?= $productInfo['productId'] ?>").html()) * eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) / 100));

                                        $("#bonusPercet1Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                        $("#bonusPercet2Form-<?= $key ?>").val($("#bonuspercent_<?= $productInfo['productId'] ?>").html());
                                        var totalBonuspay = 0;
                                        var totalCost = 0;
                                        var totalCostFotterCart = 0;
                                        $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

                                            totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                                            totalCostFotterCart = eval(totalCostFotterCart) + eval(parseInt($(this).children(":eq(1)").html())) * eval(parseInt($(this).children(":eq(4)").find("div.cartCount").html()));
                                            totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(".bonuspay").html()));
                                            $("#PriceBonusTable").html(totalBonuspay);
                                            $("#PriceBonusTable").html(totalCostFotterCart - totalCost);
                                            //console.log(totalCost);
                                        });
                                        $("#cartTotalCost").html(eval(totalCost));
                                        $("#cartTotalCostFotterCart").html(eval(totalCostFotterCart));
                                        $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                                    } else {
                                        if ((eval($("#bonuspercent_<?= $productInfo['productId'] ?>").html()) + 5) <=<?= $product->getBonuspay() != '' ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?>) {
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
                                    $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {
                                        totalBonusadd = (eval(parseInt($(this).children(":eq(5)").find("div").html())) * eval(parseInt($(this).children(":eq(3)").find("div.bonusadd").html()))) / 100;
                                    });

                                    $("#totalBonusadd").html(Math.round(eval(totalBonusadd)));
                                 '><div style="position: absolute; color: #c3060e; width: 230px; left: -125px; top: -20px;display: none;" id="maxBonusPercent_<?= $productInfo['productId'] ?>">Максимальная Бонусная скидка</div>
                                <div style="position: absolute; color: #c3060e; width: 275px; left: -125px; top: -20px;display: none;" id="maxBonusCount_<?= $productInfo['productId'] ?>">На вашем счете недостаточно Бонусов</div> </div>

                            <div style="z-index: <?= (count($products_old) * 2) - $numProduct * 2 + 3 ?>;"
                              class="bonus-question"
                              onClick="$('#qestionBonus_<?= $productInfo['productId'] ?>').toggle();"
                              onMouseOver="$('#qestionBonus_<?= $productInfo['productId'] ?>').fadeIn()"
                              onMouseOut="$('#qestionBonus_<?= $productInfo['productId'] ?>').fadeOut()">

                            </div> <div style=" position: relative;">
                                <div class="cart-bouus-info" style="z-index: <?= (count($products_old) * 2) - $numProduct * 2 + 2 ?>;" id="qestionBonus_<?= $productInfo['productId'] ?>">
                                    <?
                                    $block = PageTable::getInstance()->findOneBySlug("vazhnaya-informaciya-blok-bonusov-na-stranice-korziny");
                                    echo $block->getContent();
                                    ?>
                                </div>
                            </div>
                        </td>
                        <td
                          class="count"
                          data-weight="<?=$product->getWeight()?>"
                          data-id="<?=$product->getId()?>"
                          data-key="<?=$key?>"
                          data-discount="<?= $discountProd ?>"
                          data-price="<?= $productInfo['price'] ?>"
                          data-name="<?= $product->getName() ?>"
                          data-position="<?= $screenPosition++ ?>"
                          data-category="<?=$product->getGeneralCategory()?>"
                        >
                            <div class="bonus-left js-add-to-cart gtm-decrement-in-cart" data-direction="-1"> </div>
                            <div id="price_<?= $productInfo['productId'] ?>" style="display: none"><?= ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) ?></div>
                            <div id="quantity_<?= $productInfo['productId'] ?>" style="" class="cartCount"><?= $productInfo['quantity'] ?></div>
                            <div class="bonus-right js-add-to-cart gtm-increment-in-cart" data-direction="1"> </div>
                            <?
                              // for($gdeI=0; $gdeI<$productInfo['quantity']; $gdeI++)
                                $gdeslonCodes[]='{ id : "'.$productInfo['productId'].'", quantity: '.$productInfo['quantity'].', price: '.($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']).'}';
                            ?>
                        </td>
                        <td<? //= $product->getCount() == 0 ? ' style="opacity: 0.2;"' : '' ?>>
                          <div style="display: inline-block;" id="totalcost_<?= $productInfo['productId'] ?>"><?= $productInfo['quantity'] * (($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? $productInfo['price_w_discount'] : round($productInfo['price'] - $productInfo['bonuspay'])) ?></div>
                        </td>
                        <td>
                          <a class="gtm-delete-from-chart" onClick='$("div#content").load("/deletefromcart/<?= $key + 1 ?>");'><img width="16" border="0" height="16" align="absmiddle" title="Удалить товар из корзины" alt="Удалить" src="/images/icons/cross.png"></a>
                          <span class="column-name">Цена, руб.</span>
                          <span class="column-name">Скидка, %</span>
                          <span class="column-name">Бонусы, %</span>
                          <span class="column-name">Кол-во</span>
                          <span class="column-name">Сумма</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <div class="cart-summary-stars">
                <span style="color: #c3060e;">*</span> После оплаты заказа на ваш счет будут зачислены <span style="color: #c3060e;" id="totalBonusadd"><?= $bonusAddUser ?></span> <a href="/programma-on-i-ona-bonus" target="_blank"><u>бонусных рублей</u></a><br />
                <span style="color: #c3060e;">**</span> Товары со скидкой (Акционные товары) нельзя оплатить бонусами.
            </div>
            <? if ($sf_user->isAuthenticated()): ?>
              <div class="current-bonus-points">
                <div class="bonus-details">
                    <img src="/newdis/images/cart/surprice.png">
                    <div class="bonus-details-text">Сейчас на вашем Бонусном счете <span style="color: #c3060e;"><?
                            echo $this->bonusCount;
                            $bonusCount = $this->bonusCount;
                            ?></span> рублей.</div>
                </div>
              </div>
                  <?
            endif;

            if ($bonusCount == "")
                $bonusCount = 0;
            ?>
            <div class="summary-total">
                <div style="clear: both; margin-bottom: 5px;">Сумма заказа без скидки: <div style="float:right;"><span id="cartTotalCostFotterCart" style="font-weight: normal;"><?= $TotalCostFotterCart ?></span><span id="cartTotalCost" style="font-weight: normal;display: none;"><?= $TotalSumm ?></span></div>
                </div>
                <div style="clear: both; margin-bottom: 5px;">Доставка: <div style="float:right;"><span id="PriceDeliverTable"><?= ($TotalSumm < csSettings::get('free_deliver') ? '290' : '0') ?></span></div>
                </div>
                <div style="clear: both; margin-bottom: 5px;">Бонусы/Скидки: <div style="float:right;"><span id="PriceBonusTable"><?= $TotalCostFotterCart - $TotalSumm/* $totalBonusPay */ ?></span></div>
                </div>
                <div style="clear: both; margin-bottom: 5px;"><div style="float:left;">Итого к оплате:</div>
                    <div style="z-index: 101;width: 16px; height: 18px; background: url(/images/questionicon.png);float: left; position: relative;" onClick="$('#qestionTotalSumm').toggle();" onMouseOver="$('#qestionTotalSumm').fadeIn()" onMouseOut="$('#qestionTotalSumm').fadeOut()">

                    </div> <div style=" position: relative;">
                        <div style="padding: 10px;z-index: 100;text-align: left;position: absolute; color: #414141; width: 290px; right: 80px; top: -1px;display: none;background: #FFF; border: 1px solid #c3060e;" id="qestionTotalSumm">
                            Итоговая стоимость заказа с учетом всех скидок и бонусов рассчитывается менеджером при подтверждении заказа.
                        </div>
                    </div>
                    <div style="float:right;"><span style="font-weight: bold;color: #c3060e;" id="PriceAllTable"><?= $TotalSumm + ($TotalSumm < csSettings::get('free_deliver') ? 290 : 0) ?></span></div>
                </div>
            </div>

        </div>
        <div class="attention"> <img src="/images/attention.png" style="float: left;  margin-right: 6px;" /> Итоговая стоимость заказа с учетом всех скидок и бонусов рассчитывается менеджером при подтверждении заказа.</div>

        <div style="clear: both;margin-bottom: 20px;"></div>
        <?php
          JSInPages(
            "function toggleBlock(tag, tagToggle){
                  $(tagToggle).toggle();
                  if($(tag).hasClass('minus')){
                      $(tag).removeClass('minus').addClass('plus');
                  }
                  else{
                      $(tag).removeClass('plus').addClass('minus');
                  }
            }"
          );
        ?>
        <script>
            function ActionIsset(input) {
                $.post("/cart/actioninfo", {text: $(input).val()},
                        function (data) {
                            $(".cartContent:first").html(data);
                            var totalBonuspay = 0;
                            var totalCost = 0;
                            var TotalCostFotterCart = 0;
                            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

                                totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                                TotalCostFotterCart = eval(TotalCostFotterCart) + eval(parseInt($(this).children(":eq(1)").html())) * eval(parseInt($(this).children(":eq(4)").find("div.cartCount").html()));
                                totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(".bonuspay").html()));
                                $("#PriceBonusTable").html(totalBonuspay);
                                $("#PriceBonusTable").html(TotalCostFotterCart - totalCost);
                                //console.log(totalCost);
                            });
                            $("#cartTotalCost").html(eval(totalCost));
                            $("#cartTotalCostFotterCart").html(eval(TotalCostFotterCart));
                            $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
                        });
            }
        </script>
        <div class="tabset tabset--cart" style="margin-top: 20px;width: 100%">
            <ul class="tab-control">
                <li class="active"><a href="#"><span>Быстрый заказ в один клик</span></a></li>
                <li><a href="#"><span>Полная форма заказа</span></a></li>
            </ul>
            <div class="tab" style="display:block;">
                <form id="processOrder1" class="processOrder" name="confirm_order" method="post" action="/cart/confirmed">
                  <div class="discount-5"></div>
                  <input name="deliveryPriceSend" type="hidden" value ="<?= ($TotalSumm < csSettings::get('free_deliver') ? '290' : '0') ?>">
                    <? foreach ($products_old as $key => $productInfo) { ?>
                        <input name="bonusPercet1Form[<?= $key ?>]" type="hidden" id="bonusPercet1Form-<?= $key ?>" value="<?= $productInfo['percentbonuspay'] ?>">
                    <? } ?>
                    <input name="formType" type="hidden" value="1">
                    <table class="tableRegister" style="margin-bottom: 10px;">
                        <tr>
                            <th class="first">*Имя</th>
                            <td><input name="user_name" type="text"<? if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getFirstName() . "'"; ?>><span></span></td>
                            <td class="last"></td>
                        </tr>
                        <tr>
                            <th class="first">Фамилия</th>
                            <td><input name="last_name" type="text"<? if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getLastName() . "'"; ?>><span></span></td>
                            <td class="last"></td>
                        </tr>
                        <tr>
                            <th>*E-Mail</th>
                            <td><input name="user_mail" type="text"<? if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getEmailAddress() . "' readonly"; ?>
                                       onblur="var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;if (regex.test(this.value)) {
                                                   try {
                                                       rrApi.setEmail(this.value);
                                                       sendRRWelcome(this.value);
                                                   } catch (e) {
                                                   }
                                               }"><span></span>
                                <? if ($sf_user->isAuthenticated()) echo "<div class='email-disclaimer'> Изменить e-mail вы можете в <a href=\"/customer/mydata\">личном кабинете</a></div>"; ?>
                            </td>
                            <td class="last">

                            </td>
                        </tr>
                        <tr>
                            <th>*Мобильный телефон</th>
                            <td id="comm1"><input name="user_phone" type="text" id="sf_guard_user_phone2"<? if ($sf_user->isAuthenticated()) echo " value='" . $sf_user->getGuardUser()->getPhone() . "'"; ?>><?/*<span style="margin-left: 295px;float: left;"></span>*/?></td>
                        </tr>
                    </table>
                    <input type="hidden" name="coupon" id="coupnumber1">
                    <div class="cart-action"><div><? /* <input name="payBonus" onclick="checkBonus(this);" value="2" type="radio" style="margin-top: -3px;"> */ ?>&nbsp;Акция </div>
                        <input id="coupon" class="couponInput" name="couponTxt" onclick="$(this).keyup(function () {
                                    /*$(this).prev().attr('checked', 'checked');*/
                                    $('#coupnumber1').val(this.value);
                                    ActionIsset(this);
                                });" onchange="$('#coupnumber1').val(this.value);
                                        ActionIsset(this);" onkeyup="" type="text" value="<?= $sf_user->getAttribute('actionCartCode') ?>"><br><span style="font-size: 11px;">Введите номер купона или код для скидки</span></div>

                    <? if ($this->bonusCount > 0 and false) { ?><div style="padding:2px 0px 15px 40px;text-align: left; float: left;"><input type="radio" name="payBonus" onclick="checkBonus(this);
                                    $('.couponInput').each(function (i) {
                                        $(this).val('');
                                    });" value="1" style="margin-top: -3px;">&nbsp;<div style="display: inline-block; height: 21px;">Бонусы</div><br><div style="font-size: 11px; padding-left: 27px;">Оплата бонусами <?= csSettings::get('PERSENT_BONUS_PAY') ?>% стоимости заказа</div></div><? } ?>

                    <? /* <div style="padding:0px 0px 15px 10px;text-align: left; "><input checked="checked" name="payBonus" onclick="checkBonus(this);" value="3" type="radio">&nbsp;Без скидки</div> */ ?>
                    <div style="clear: both; "></div>
                    <? if (!$sf_user->isAuthenticated()): ?>
                      <div class="cart-action captcha">
                        <div>*Код</div>
                        <input name="captcha" class="js-captcha-1">
                        <img  class="captchasu"  src="/images/pixel.gif" data-original="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />
                        <span>Введите проверочный код</span>
                      </div>
                      <div style="clear: both; "></div>
                    <? endif ?>
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
                    </div>
                    <div style="clear: both; height: 10px;"></div><input type="checkbox" id="18form1">Подтверждаю, что мне 18 или более лет.<span></span>
                    <div style="clear: both; height: 10px;"></div><input type="checkbox" id="personal-accept-1">Я принимаю условия Пользовательского соглашения<span></span>
                    <br><a href='/personal_accept' target='_blank'>Пользовательское соглашение</a>
                    <div style="clear: both; height: 20px;"></div>

                    <?php
                    if ($isStock) {
                        ?><a class="red-btn colorWhite" onClick="$('#processOrder1').submit()"><span style="width: 250px;">Отправить заказ</span></a><?php
                    }
                    else { ?>
                        <div style="color: #c3060e;font-weight: bold;">В корзине есть отсутствующие товары</div><?php
                    } ?>

                </form>
            </div>
            <div class="tab">
                <div class ="hor-line">Выберите способ доставки и оплаты</div>

                <form id="processOrder2" class="processOrder" name="confirm_order" method="post" action="/cart/confirmed">
                  <input name="formType" type="hidden" value="2">
                  <input id="deliveryPriceSend" name="deliveryPriceSend" type="hidden" value ="<?= ($TotalSumm < csSettings::get('free_deliver') ? '290' : '0') ?>">
                  <? foreach ($products_old as $key => $productInfo) { ?>
                    <input name="bonusPercet2Form[<?= $key ?>]" type="hidden" id="bonusPercet2Form-<?= $key ?>" value="<?= $productInfo['percentbonuspay'] ?>">
                  <? } ?>
                  <script type='text/javascript'>
                    var nextStep;
                    function checkPayment(data) {
                        $valDeliv = $(":radio[name=deliveryId]").filter(":checked").val();
                        if ($("#TotalSumm").html() < 2990) {
                            $("#deliverPrice").html('290 р.');
                            $("#deliverPriceTotal").html(290 + eval($("#cartTotalCost").html()) + ' р.');
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

                  </script>

                  <input type="hidden" name="coupon" id="coupnumber2">
                    <div class="tabset_old tabsetDeliver tabsetDelivery">
                      <ul class="tab-controlDelivery" style="margin: 0px; padding: 0px;">
                        <?php
                        $delivers = DeliveryTable::getInstance()->createQuery()->where('is_public = \'1\'')->orderBy('position ASC')->execute();

                        foreach ($delivers as $delivery): ?>
                              <li>
                                  <a href="#" onclick="SelectDelivery(this)">
                                      <div class="center"><input style="float: left;" type="radio" value="<?= $delivery->getId() ?>" id="delivMod" name="deliveryId" onClick=" return false;"><span style=" width: 225px; padding: 0;"> <?= $delivery->getName() ?></span></div>
                                      <div class="helper"></div>
                                  </a>

                              </li>

                        <?php endforeach; ?>
                      </ul>
                      <?php foreach ($delivers as $delivery): ?>
                          <div class="tabDelivery">
                            <?= $delivery->getContent(); ?>
                            <? if ($delivery->getId() == 11) { ?>

                              <script type="text/javascript" src="https://pickpoint.ru/select/postamat.js" charset="utf-8"></script>
                              <div id="pickpoint" style=""><br />
                                <div style="text-align: center; width: 100%;">
                                <a href="#" onclick="PickPoint.open(my_function); return false;" style="font: 17px/18px Tahoma,Geneva,sans-serif;color: #C3060E;"><b>Выбрать постамат или пункт выдачи на карте</b></a></div>
                                <div id="address"></div>
                                <input type="hidden" name="pickpoint_id" id="pickpoint_id">
                                <input type="hidden" name="pickpoint_price" id="pickpoint_price">
                                <input type="hidden" name="pickpoint_address" id="pickpoint_address" value="" /><br /><br />
                                <script type="text/javascript">
                                    function my_function(result) {
                                        // устанавливаем в скрытое поле ID терминала
                                        document.getElementById('pickpoint_id').value = result['id'];
                                        document.getElementById('pickpoint_address').value = result['name'] + '<br />' + result['address'];

                                        // показываем пользователю название точки и адрес доствки
                                        document.getElementById('address').innerHTML = result['name'] + '<br />' + result['address'];
                                        calcDeliveryPrice();
                                    }
                                </script>
                              </div>
                            <? }
                            if ($delivery->getId() == 14) { //DRH LOGISTIC?>
                              <script src="//api-maps.yandex.ru/2.1/?lang=ru-RU" type="text/javascript"></script>
                              <script src="https://api.drhl.ru/api/deliverypoints" type="text/javascript"></script>
                              <div class="drh-shutter" onclick="showDrhLogistic()">Выбрать точку доставки</div>
                              <div id="drh-container" class="drh-container">
                                <div class="drh-container-back"></div>
                                <div id="map" class="drh-map-container"></div>
                              </div>
                              <div id="dlh_address"></div>
                              <input type="hidden" name="dlh_price" id="dlh_price">
                              <input type="hidden" name="dlh_shop_id" id="dlh_shop_id" value="" />
                              <input type="hidden" name="dlh_address_line" id="dlh_address_line" value="" /><br /><br />
                              <script>
                              var deliveryPointSelected=false;

                              </script>
                            <? }
                            if ($delivery->getId() == 3) { //Почта России
                              $payedDeliver = $TotalSumm < csSettings::get('free_deliver');?>
                              <div id="js-rp-message" data-need-calc="<?=$TotalSumm?>">
                                <?=$payedDeliver ?
                                'Недостаточно данных для расчета. Выберите форму оплаты и заполните адрес.' :
                                'Бесплатная доставка'
                                ?>
                              </div>
                              <?/*<div class="js-rp-shutter">Нажмите для обновления</div>*/?>
                            <? }
                            if ($delivery->getId() == 13) { ?>
                                  <link type="text/css" href="http://wt.qiwipost.ru/css/gray/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
                                  <script type="text/javascript" src="http://wt.qiwipost.ru/selectterminal?div=qiwipost_widget&dropdown=1&combobox=1&emptydropvalue=1"></script>
                                  <script type="text/javascript">
                                          function myQiwipostCallback(t) {
                                              //alert('Выбран терминал ' + t.name + ' ' + t.addr);
                                              $("#qiwiId").val(t.name);
                                              $("#qiwiAddr").val(t.addr);
                                          }
                                  </script>
                                  <span id="qiwipost_widget"></span><input type="button" value="Выбрать на карте" id="qiwipost_map_link" onclick="QiwipostWidget.mapClick({onlytown: 1, towntype: 1, mobile: '', mobileinput: 0, mobileconfirm: 0, calc: 0, callback: myQiwipostCallback});">

                                  <input id="qiwiId" name="qiwiId" type="hidden" /> <input id="qiwiAddr" name="qiwiAddr" type="hidden" />

                                  <?
                            }
                            $deliveryPayment = DeliveryPaymentTable ::getInstance()->createQuery()->where('delivery_id=' . $delivery->getId())->orderBy('position ASC')->execute();
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
                                                <? If ($this->bonusCount > 0 and false) { ?><div style="padding:0px 0px 15px 10px;text-align: left;"><input type="radio" name="payBonus" onclick="checkBonus(this); $('.couponInput').each(function (i) { $(this).val(''); });" value="1">&nbsp;Бонусы<br><span style="font-size: 11px;">Оплата бонусами <?= csSettings::get('PERSENT_BONUS_PAY') ?>% стоимости заказа</span></div><? } ?>
                                            <div style="padding:0px 0px 15px 10px;text-align: left;"><input type="radio" name="payBonus" onclick="checkBonus(this);" value="2">&nbsp;Акция <input type="text" id="coupon" class="couponInput" name="couponTxt" onkeyup="" onclick="$(this).keyup(function () { $(this).prev().attr('checked', 'checked'); });" onchange="$('#coupnumber2').val(this.value); ActionIsset(this);" value="<?= $sf_user->getAttribute('actionCartCode') ?>"><br>
                                            <span style="font-size: 11px;">Введите номер купона или код для скидки</span></div>
                                            <? /* <div style="padding:0px 0px 15px 10px;text-align: left;"><input checked="checked" type="radio" name="payBonus" onclick="checkBonus(this);" value="3">&nbsp;Без скидки</div> */ ?>


                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>
                      <?php endforeach; ?>
                    </div>
                    <div style="clear: both;margin-bottom: 40px;"></div>
                    <div class="hor-line relative">Заполните свои контактные данные<div class="discount-5"></div></div>
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
                                        <th class="first">
                                          <label for="sf_guard_user_first_name" style="font-weight: normal;"><?php echo $field->renderLabel() ?></label>
                                        </th>
                                        <td>
                                          <? if ($field->renderLabel() == "<label for=\"sf_guard_user_password\">Пароль*</label>") { ?>
                                              <script>
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
                                          <? } ?>
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
                                            if ($field->renderLabel() == "<label for=\"sf_guard_user_last_name\">Фамилия</label>") {
                                                echo "<span></span>
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
                    <? if (!$sf_user->isAuthenticated()): ?>
                      <div class="cart-action captcha">
                        <div>*Код</div>
                        <input name="captcha" class="js-captcha-2">
                        <img  class="captchasu" src="/images/pixel.gif" data-original="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />
                        <span>Введите проверочный код</span>
                      </div>
                      <div style="clear: both; "></div>
                    <? endif ?>

                    <div style="clear: both; "></div>
                    <div style=" margin-top: 20px;margin-bottom: 20px;;border-bottom: 2px dotted #c3060e; cursor: pointer; color:#c3060e; float: left;" onclick="$('#commentOrder2').toggle(); if ($(this).html() == 'Добавить комментарий к заказу') $(this).html('Скрыть комментарий к заказу'); else $(this).html('Добавить комментарий к заказу');">Добавить комментарий к заказу</div>
                    <div style="clear: both; "></div>
                    <div id="commentOrder2" style="margin-top: 20px;display: none;">
                      <div>
                          Укажите удобное время звонка, когда наш менеджер свяжется с вами для подтверждения заказа. <br />
                          <input type="text" id="timeCall" name="timeCall" style="width:300px;margin-bottom: 20px;">
                      </div>
                      <div>
                          Ваши пожелания по заказу и доставке (любая дополнительная информация, которую мы должны знать). <br />
                          <textarea style="width:300px; height:100px;" id="comments" name="comments"></textarea>
                      </div>
                    </div>
                    <div style="padding:10px;float: left;">
                        <span style="color: #c3060e;">*</span> - поля, обязательны для заполнения.<br />
                        Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить ваш заказ.<br />
                    </div>
                    <div style="clear: both; height: 10px;"></div><input type="checkbox" id="18form2">Подтверждаю, что мне 18 или более лет.<span></span>
                    <div style="clear: both; height: 10px;"></div><input type="checkbox" id="personal-accept-2">Я принимаю условия Пользовательского соглашения<span></span>
                    <br><a href='/personal_accept' target='_blank'>Пользовательское соглашение</a>
                    <div style="clear: both; height: 20px;"></div>
                    <?php if (true /*$isStock*/) { ?>
                        <a class="red-btn colorWhite" onClick="$('#processOrder2').submit()"><span style="width: 250px;">Отправить заказ</span></a>
                    <?php }
                    else { ?>
                        <div style="color: #c3060e;font-weight: bold;">В корзине есть отсутствующие товары</div><?php
                    } ?>
                </form>
            </div>
        </div>
        <div style="clear: both; "></div>
        <?/*
        <div class="cart-buy-also" style="" onclick="toggleBlock($(this).find('.toggleBlock'), $('#productsBlockBuyWithItem'))">
          <div class="toggleBlock minus"></div>С товаром/товарами из вашей Корзины также покупают:
        </div>

        <div style='display: block; position: relative;' id="productsBlockBuyWithItem">

            <div class="blockBuyWithItemContentNew">
                <ul class="item-list  item-list-mainpage-prod gtm-category-show" data-list="Корзина. С этим товаром также покупают" id="more_products">
                    <?php

                    $items2cart = implode(",", $arrayBuyWithItem);

                    if ($items2cart != "")
                        $productsBuyWithItems = ProductTable::getInstance()->createQuery()->where("id in (" . $items2cart . ") and count>0")->orderBy("rand()")->execute();
                    else {
                        if (csSettings::get("bestsellersProducts") != "") {
                            $productsBuyWithItems = ProductTable::getInstance()->createQuery()->where("id in (" . csSettings::get("bestsellersProducts") . ")")->addWhere("is_public='1'")->addWhere("count>0")->orderBy("(count>0) DESC")->limit(10)->execute();
                        }
                    }
                    foreach ($productsBuyWithItems as $prodNum => $product):

                        if ($product->getId() != ""):

                            if ($product->getCount() > 0) {
                                $prodCount = 1;
                            } else {
                                $prodCount = 0;
                            }
                            include_component('category', 'products', array('slug' => $sf_request->getParameter('slug'), 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-line4-last-prodnum0", 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => 0, 'line4' => false, "productShowItems" => true, 'last' => true));

                        endif;
                    endforeach;
                    ?>

                </ul>
            </div>
            <a href="#" class="BuyWithItemCart sa-left" style="left: 15px; top: 170px"></a>
            <a href="#" class="BuyWithItemCart sa-right" style="right: 15px; top: 170px"></a>
        </div>*/?>
        <div data-retailrocket-markup-block="5ba3a64397a52530d41bb248" data-products="<?=implode (', ', $itemsInChart)?>"></div>
    </div>
    <div style="clear: both; "></div>
  <?php else: ?>
    <div class="borderCart">
      <div align="center" class="pink bold" style="padding:5px;color: #ba272d;">Моя корзина </div>
      <?php
        $cartEmpty = PageTable::getInstance()->findOneBySlug("stranica-pustoi-korziny");
        echo $cartEmpty->getContent();
      ?>

      <div data-retailrocket-markup-block="5ba3a65797a52530d41bb249"></div>
    </div>
  <?php endif; ?>
<? /*114338 - onona.ru - Обновить трекинг-код
<script type="text/javascript">
    var ad_products = [<?= $reTagProd ?>
    ];

    window._retag = window._retag || [];
    window._retag.push({code: "9ce8886733", level: 3});
    (function () {
        var s = document.createElement("script");
        s.async = true;
        s.src = (document.location.protocol == "https:" ? "https:" : "http:") + "//cdn.admitad.com/static/js/retag.js";
        var a = document.getElementsByTagName("script")[0]
        a.parentNode.insertBefore(s, a);
    })()
</script>
*/?>
<script type="text/javascript" src="/js/jquery.lbslider.js"></script>
<script>

  function checkBonus(data) {
      if (data.value == 1) {
          var $bonusPay = 0;
          $(".cartContent:first tbody tr").each(function (i) {
              $children = $(this).children();
              if ($($children[2]).text() == 0) {
                  $bonusPay = $bonusPay + Math.round(eval($($children[4]).text() * <?= csSettings::get('PERSENT_BONUS_PAY') / 100 ?>));
              }
          });

          if ($bonusPay > <?= $bonusCount ?>) {
              $("#PriceBonusTable").html("<?= $bonusCount ?>");
          } else {
              $("#PriceBonusTable").html("" + $bonusPay);
          }
      }
      if (data.value == 2) {
          $("#PriceBonusTable").html(0);
      }
      if (data.value == 3) {
          $("#PriceBonusTable").html(0);
      }
      $("#PriceAllTable").html(eval($("#cartTotalCost").html()) + eval($("#PriceDeliverTable").html()));

  }

  function checkBonusMenu(data) {
      if (data.val() == 1) {
          $("#PriceBonusTable").html(
            <?
              if ((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm > $bonusCount) {
                  echo $bonusCount;
              } else {
                  echo round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm);
              }
              ?>
          );
      }
      if (data.val() == 2) {
          $("#PriceBonusTable").html(0);
      }
      if (data.val() == 3) {
          $("#PriceBonusTable").html(0);
      }
      $("#PriceAllTable").html(
        eval($("#cartTotalCost").html()) + eval($("#PriceDeliverTable").html())
      );
  }

  function CallPrint(strid) {
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

  $(document).ready(function () {
    $('#sf_guard_user_city').on('change', function(){if($('input[name="deliveryId"]:checked').val()==3) calcDeliveryPrice()});
    $('#sf_guard_user_street').on('change', function(){if($('input[name="deliveryId"]:checked').val()==3) calcDeliveryPrice()});
    $('#sf_guard_user_house').on('change', function(){if($('input[name="deliveryId"]:checked').val()==3) calcDeliveryPrice()});
    $("#sf_guard_user_phone").after("<div id='commPhone' style='display: inline-block;margin-left: 5px;'>Введите, пожалуйста, номер в формате +7 (и 10 цифр вашего телефона) <br />Пример +74957879886</div>");
    $("#comm1").after("<td class='last'><div id='commPhone' class='email-disclaimer'>Введите, пожалуйста, номер в формате +7 (и 10 цифр вашего телефона) <br />Пример +74957879886</div></td>");
    <?php if ($sf_user->isAuthenticated()) {
          echo "$(\"#sf_guard_user_email_address\").attr('readonly','readonly');";
      }
    ?>
    <?php $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
      //print_r($products_old);
      foreach ($products_old as $key => $product) {
          echo "changeButtonToGreen(" . $product["productId"] . ");";
      }
      //changeButtonToGreen(id)
    ?>
    var visible = 3;
    if (window.screen.width<768) visible = 1;
      $('.blockBuyWithItemContentNew').lbSlider({
          leftBtn: '.BuyWithItemCart.sa-left',
          rightBtn: '.BuyWithItemCart.sa-right',
          visible: visible,
          autoPlay: false,
          autoPlayDelay: 5,
          cyclically: true
      });
  });

  $('.js-add-to-cart').on('click', function(e){
    var
      $this=$(this),
      id=$this.parent('.count').data('id'),
      direction=parseInt($this.data('direction')),
      discountProd=$this.parent('.count').data('discount'),
      bonusCount=<?= $this->bonusCount ? $this->bonusCount : 0 ?>,
      key=$this.parent('.count').data('key')
    ;
    // console.log([id, direction, discountProd, bonusCount, key]);
    // return;
    if(eval($("#quantity_"+id).html()) + direction >0){//Не удаляем здесь, только до 1
      $.post(
        "/cart/addtocartcount/"+id,
        {
          count: eval($("#quantity_"+id).html()) + direction
        },
        function (data) {
            var totalCost = 0;
            var totalCostFotterCart = 0;
            $("#quantity_"+id).html(data);
            $("#totalcost_"+id).html(eval($("#quantity_"+id).html()) * eval($("#price_"+id).html()));


            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

                totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                totalCostFotterCart = eval(totalCostFotterCart) + eval(parseInt($(this).children(":eq(1)").html())) * eval(parseInt($(this).children(":eq(4)").find("div.cartCount").html()));
            });


            // console.log(totalCost);

            $("#cartTotalCost").html(totalCost);
            $("#cartTotalCostFotterCart").html(eval(totalCostFotterCart));
            $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));
            $valDeliv = $(":radio[name=deliveryId]").filter(":checked").val();
            if ($("#cartTotalCost").html() < <?= csSettings::get('free_deliver') ?>) {
              if($valDeliv != "11" && $valDeliv != "14")
                $("#PriceDeliverTable").html("290");
            } else {
                $("#PriceDeliverTable").html("0");
            }


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


            if ($("#bonuspercent_"+id).html() == 0) {
                $("#discount_"+id).html(discountProd);
                $("#bonuspay_"+id).html(0);

                $("#totalcost_"+id).html(Math.round(eval($("#quantity_"+id).html()) * eval($("#price_"+id).html()) - eval($("#quantity_"+id).html()) * eval($("#price_"+id).html()) * discountProd / 100));
                // console.log('bnous percent');
                // console.log([id, data, $("#totalcost_"+id).html()]);

            } else {
                $("#totalcost_"+id).html(Math.round(eval($("#quantity_"+id).html()) * eval($("#price_"+id).html()) - eval($("#quantity_"+id).html()) * eval($("#price_"+id).html()) * eval($("#bonuspercent_"+id).html()) / 100));

                $("#bonuspay_"+id).html(Math.round(eval($("#quantity_"+id).html()) * eval($("#price_"+id).html()) * eval($("#bonuspercent_"+id).html()) / 100));
            }
            var totalBonuspay = 0;
            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {
                totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(".bonuspay").html()));

            });
            while (totalBonuspay > bonusCount) {
                //console.log(totalBonuspay);

                $("#bonuspercent_"+id).html(eval($("#bonuspercent_"+id).html()) - 5);
                $("#bonuspay_"+id).html(Math.round(eval($("#quantity_"+id).html()) * eval($("#price_"+id).html()) * eval($("#bonuspercent_"+id).html()) / 100));

                var totalBonuspay = 0;
                $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {
                    totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(".bonuspay").html()));

                });
            }
            if ($("#bonuspercent_"+id).html() != 0) {
                $("#totalcost_"+id).html(Math.round(eval($("#quantity_"+id).html()) * eval($("#price_"+id).html()) - eval($("#quantity_"+id).html()) * eval($("#price_"+id).html()) * eval($("#bonuspercent_"+id).html()) / 100));
            }
            $("#bonusPercet1Form-"+key).val($("#bonuspercent_"+id).html());
            $("#bonusPercet2Form-"+key).val($("#bonuspercent_"+id).html());
            var totalBonuspay = 0;
            var totalCost = 0;
            var totalCostFotterCart = 0;
            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {

                totalCost = eval(totalCost) + eval(parseInt($(this).children(":eq(5)").find("div").html()));
                totalCostFotterCart = eval(totalCostFotterCart) + eval(parseInt($(this).children(":eq(1)").html())) * eval(parseInt($(this).children(":eq(4)").find("div.cartCount").html()));
                totalBonuspay = eval(totalBonuspay) + eval(parseInt($(this).children(":eq(3)").children(".bonuspay").html()));
                $("#PriceBonusTable").html(totalBonuspay);
                $("#PriceBonusTable").html(totalCostFotterCart - totalCost);
                // console.log(totalCost);
            });
            $("#cartTotalCost").html(eval(totalCost));
            $("#cartTotalCostFotterCart").html(eval(totalCostFotterCart));
            $("#PriceAllTable").html(eval(totalCost) + eval($("#PriceDeliverTable").html()));

            $("div.borderCart").children("table:first").children("tbody").children("tr").each(function (i) {
                totalBonusadd = (eval(parseInt($(this).children(":eq(5)").find("div").html())) * eval(parseInt($(this).children(":eq(3)").find("div.bonusadd").html()))) / 100;
            });

            $("#totalBonusadd").html(Math.round(eval(totalBonusadd)));
            calcDeliveryPrice();
        });
    }
  });
  var mapWidget=false;
  function showDrhLogistic(){
    // console.log('map open');
    $('#drh-container').addClass('active');
    window.onPointSelected = function(deliveryPoint) {
      deliveryPointSelected=deliveryPoint;
        // описание объекта deliveryPoint
          // {
          //   city: "Москва" //город доставки
          //   region: "Москва г." // регион доставки, необходим для городов, находящихся в нескольких регионах
          //   address: "Кутузовский пр-кт 22", // Адрес точки
          //   deliveryCost1: 1000, // Стоимость доставки до 1кг
          //   deliveryCost2: 1100, // Стоимость доставки до 2кг
          //   deliveryCost3: 1200, // Стоимость доставки до 3кг
          //   deliveryCost4: 1300, // Стоимость доставки до 4кг
          //   deliveryCost5: 1400, // Стоимость доставки до 5кг
          //   deliveryCost6: 1500, // Стоимость доставки до 6кг
          //   deliveryCost7: 1600, // Стоимость доставки до 7кг
          //   deliveryCost8: 1700, // Стоимость доставки до 8кг
          //   hash: "D9B04D555562C41955612352F046E6CE", // Хеш сумма, для валидации подлинности данных для устаревшей тарифной сетки до 4 кг (не использовать, оставлено для совместимости с ранее интегрировавшимися партнерами)
          //   hash8: "31AE97914586F6AA97F52E0AE4B41FD5", // Хеш сумма, для валидации подлинности данных для тарифной сетки до 8 кг на доставку (не использовать, оставлено для совместимости с ранее интегрировавшимися партнерами)
          //   hash16: "5E0680EBB4BC2607EE4875F3C2825C98", // Хеш сумма, для валидации подлинности данных для тарифных сеток до 8 кг на доставку и на возврат
          //   name: "Кутузовский пр-т, 22", // Имя точки
          //   returnCost: 500, // Стоимость возврата (не использовать, оставлено для совместимости с ранее интегрировавшимися партнерами)
          //   returnCost1: 500, // Стоимость возврата до 1кг
          //   returnCost2: 550, // Стоимость возврата до 2кг
          //   returnCost3: 600, // Стоимость возврата до 3кг
          //   returnCost4: 650, // Стоимость возврата до 4кг
          //   returnCost5: 700, // Стоимость возврата до 5кг
          //   returnCost6: 750, // Стоимость возврата до 6кг
          //   returnCost7: 800, // Стоимость возврата до 7кг
          //   returnCost8: 850, // Стоимость возврата до 8кг
          //   shopId: "M354" // Идентификатор точки
          //   deliveryOperatorId: 1 // Идентификатор торговой сети
          //   deliveryOperator: "Евросеть" // Наименование торговой сетиы
        // }
        $('#drh-container').removeClass('active');
        // console.log('map close');
        calcDeliveryPrice();
    }
    // partnerId - Идентификатор партнера 147
    // map - id элемента страницы, куда необходимо вставить виджет. Элемент должен быть не менее 900px в ширину и 720px в высоту
    // для адаптивных интерфейсов ширина 900px должна соблюдаться при ширине экрана более 992px. Высота должна быть 720px всегда
    // onPointSelected - функция, которая будет вызвана после выбора точки пользователем
    if (!mapWidget) mapWidget = new MapWidget(151, "map", onPointSelected, {'size':'xs'});
    // запустить инициализацию виджета
    mapWidget.init();
  }

  function calcDeliveryPrice(){
    // console.log('------------------------------------ calculate delivery price here ------------------------------------');
    var freeDeliveryLimit=<?= csSettings::get('free_deliver') ?>;
    var deliveryId=$('input[name="deliveryId"]:checked').val();
    var weight=getOrderWeigth();
    var price=290;
    // console.log('  deliveryId='+deliveryId);
    // console.log('  weight='+weight);
    var cartTotalCost=parseInt($('#cartTotalCost').html());
    if(deliveryId==3){//почта России
      weight=1000*weight;
      // weight=1000*String(weight).replace('.','');
      freeDeliveryLimit=2990;
      if(cartTotalCost>freeDeliveryLimit) {
        $('#js-rp-message').text('Бесплатная доставка');
        $('#js-rp-message').data('need-calc', 'yes');
        // $('#js-rp-message').data('total-sum', cartTotalCost);
        price=0;
        // console.log('russian post free shipping fired');

      }
      else{//Необходим расчет
        var $jsRPMessage = $('#js-rp-message');//Объект хранящий свойства заказа
        var paymentId = $jsRPMessage.parent().find('input[name="paymentId"]:checked').val();
        if (!!!paymentId){
          paymentId=false;
        }
        if(paymentId && paymentId!=4 && paymentId!=5) var onlinePayment = 1;
        else var onlinePayment = 0;

        var city =      $('#sf_guard_user_city').val(),
            street =    $('#sf_guard_user_street').val(),
            house =     $('#sf_guard_user_house').val(),
            needCalc = weight + '|' + city + '|' + street+ '|'  + house+ '|' +  onlinePayment+ '|' + cartTotalCost
        ;


        if(//Проверяем изменилось ли что-то  (могла ли измениться цена доставки)
          needCalc != $jsRPMessage.data('need-calc')
        ){
          $jsRPMessage.data('need-calc', needCalc)
          // console.log('---------------------------------------------');
          // console.log('paymentId:' + paymentId)
          // console.log('onlinePayment:' + onlinePayment)
          // console.log('need-calc:' + needCalc);
          // console.log('weight:' + weight);
          // console.log('russian post calc fired');

          $.ajax({
            url: '/russianpost_get_delivery_price',
            data: {
              'weight': weight,
              'city': city,
              'street': street,
              'house': house,
              'pay-online': onlinePayment,
              'order-price': cartTotalCost
            },
            type: 'post',
            success: function(response){
              // console.log(response);
              $jsRPMessage.text(response.comment);
              if(response.status) {
                price=1*response.price;
                $('#PriceDeliverTable').html(price);
                $('#PriceAllTable').html(price+cartTotalCost);
                $('#deliveryPriceSend').val(price);
              }
            }
          });

        }
        else{
          // console.log('russian post calc not needed');
        }
      }
    }
    if(deliveryId==9){//Курьер по России
      freeDeliveryLimit=4990;
      price=300;
    }
    if(deliveryId==11){//PickPoint
      freeDeliveryLimit=3590;
      if(cartTotalCost>freeDeliveryLimit) price=0;
      else{
        price=290;
        if(!$('#pickpoint_id').val().length){
          // alert('Выберите точку доставки');
          // console.log('точка неясна');
          // return;
        }
        else{
          weight=String(weight).replace('.',',');
          $.ajax({
            url: '/pickpoint_get_delivery_price/'+$('#pickpoint_id').val()+'/'+weight,
            type: 'post',
            success: function(response){
              if(!response[0]) alert(response[1]);
              else {
                price=response[1];
                $('#PriceDeliverTable').html(price);
                $('#PriceAllTable').html(price+cartTotalCost);
                $('#deliveryPriceSend').val(price);
                // console.log('  service=PickPoint');
              }
            }
          });
        }
      }

    }
    if(deliveryId==14){//DLH Logistic
      if(!deliveryPointSelected) {
        return;
      }
      var weight=getOrderWeigth();
      price=deliveryPointSelected.deliveryCost1;
      var message=deliveryPointSelected.region+', '+deliveryPointSelected.city+', '+deliveryPointSelected.address+'. Работает с '+deliveryPointSelected.openTime+' до '+deliveryPointSelected.closeTime;
      $('#dlh_address_line').val(message);
      if(weight>1) price=deliveryPointSelected.deliveryCost2;
      if(weight>2) price=deliveryPointSelected.deliveryCost3;
      if(weight>3) price=deliveryPointSelected.deliveryCost4;
      if(weight>4) price=deliveryPointSelected.deliveryCost5;
      if(weight>5) price=deliveryPointSelected.deliveryCost6;
      if(weight>6) price=deliveryPointSelected.deliveryCost7;
      if(weight>7) price=deliveryPointSelected.deliveryCost8;
      if(weight>=8){
        price=0;
        message='Доставка данным оператором невозможна. Пожалуйста, выберите другой вид доставки';
      }
      if(cartTotalCost>freeDeliveryLimit) price=0;
      price=Math.ceil(price);
      $('#dlh_address').html(message);
      $('#dlh_price').val(price);
      $('#dlh_shop_id').val(deliveryPointSelected.shopId);
      $('#PriceDeliverTable').html(price);
      $('#PriceAllTable').html(price+cartTotalCost);
      $('#deliveryPriceSend').val(price);

    }
    if(cartTotalCost>freeDeliveryLimit) {
      price=0;
    }
      $('#PriceDeliverTable').html(price);
      $('#PriceAllTable').html(price+cartTotalCost);
      $('#deliveryPriceSend').val(price);

  }

  function getOrderWeigth(){
    var weight=0;
    $('td.count').each(function(i, el){
      weight+=parseFloat($(el).data('weight'))*parseInt($(el).find('.cartCount').html());
    })
    return weight;
  }

  function SelectDelivery(delivery) {
      checkDelivery($(delivery));

      checkBonusMenu($('input[type=radio][name=payBonus]:checked'));
      window.setTimeout(calcDeliveryPrice(), 150);
      window.setTimeout('$(\'.tabDelivery:visible input[type=radio][name=paymentId]\').first().attr(\'checked\', \'checked\');', 100);
      window.setTimeout('$("input[type=radio][value=' + $(delivery).find('input').val() + '][name=deliveryId]").attr(\'checked\',\'checked\');', 50);
      window.setTimeout('$("input[type=radio][value=3][name=payBonus]:visible").attr(\'checked\',\'checked\');', 50);

      return false;
  }
  function checkDelivery(data) {
      //console.log(nextStep);
      data = data.find('input');

      $("#sf_guard_user_last_name").removeAttr('required');
      $("label[for=sf_guard_user_last_name]").text("Фамилия");
      if (data.val() == '3') {
          $("#sf_guard_user_last_name").attr('required', 'required');
          $("label[for=sf_guard_user_last_name]").text("*Фамилия");
      }
      calcDeliveryPrice();
      $('#deliveryPriceSend').val($('#PriceDeliverTable').html());
      $("#PriceAllTable").html(eval($("#cartTotalCost").html()) + eval($("#PriceDeliverTable").html()));

      nextStep = true;
      $('.red-btn').fadeIn();

  }

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
<? slot('gdeSlonCodes', 'products: [ '.implode(', '."\n", $gdeslonCodes).' ],'); ?>
<? //slot('gdeSlonCodes', '&codes='.implode(',', $gdeslonCodes)); ?>
<? slot('gdeSlonMode', 'basket'); ?>
