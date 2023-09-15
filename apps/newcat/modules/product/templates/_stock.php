<?php
$stock = unserialize($product->getStock());
//print_r($stock);

if (( count($stock['storages']['storage']) > 0)) {
    ?>
    <div class="tab" style="    overflow: visible;">
        <div class="descr">Магазины, в которых этот товар есть в наличии:</div>
        <ul class="availability">
            <?
            foreach ($stock['storages']['storage'] as $storage):

                if ($storage['@attributes']['code1c'] or $storage['code1c']):
                    if ($storage['@attributes']['code1c']) {
                        $shop = ShopsTable::getInstance()->findOneById1c($storage['@attributes']['code1c']);
                        $codeShop1cIsStock[] = "'".$storage['@attributes']['code1c']."'";
                    } else {
                        $shop = ShopsTable::getInstance()->findOneById1c($storage['code1c']);
                        $codeShop1cIsStock[] = "'".$storage['code1c']."'";
                    }
                    if ($shop):
                        //echo $shop->getCity();
                        if(!$shop->getIsActive()) continue;
                        ?>
                        <li>
                            <?
                            if ($shop->getIconmetro() != "") {
                                ?>
                                <div style="float: left;margin-left: 10px;margin-right: 5px;"><img src="/uploads/metro/<?= $shop->getIconmetro() ?>" alt="metro"></div>
                            <? } ?>
                            <div class="address-holder">
                                <div class="name-holder">
                                    <? if ($shop->getSlug() != '1-ya-tverskaya-yamskaya') {} else { ?>
                                    <a target="_blank"  href="/<?
                                    // if ($shop->getPage()) {
                                        echo 'shops/'.$shop->getSlug();
                                    // }
                                    ?>">
                                        <span class="text"><?= $shop->get('Metro') ?></span>
                                    </a>
                                    <? } ?>
                                </div>
                                <div class="address"><?= $shop->get('Address') ?></div>
                            </div>
                            <div class="stockInfo">
                                <div style="color: #42b039;">В наличии</div>
                            </div>
                            <div class="work-time"><?
                              $strWorktime=$shop->getWorkTime();
                                /*$workTime = explode(",", $shop->getWorkTime());
                                if ($workTime[0] == $workTime[6]) {
                                    $strWorktime = "Пн-Вс " . $workTime[0] . "<br />";
                                } elseif ($workTime[0] == $workTime[4]) {
                                    $strWorktime = "Пн-Пт " . $workTime[0] . "<br />";
                                    if ($workTime[5] == $workTime[6]) {
                                        $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                    } else {
                                        $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                        $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                                    }
                                } else {
                                    $strWorktime = "Пн " . $workTime[0] . "<br />";
                                    $strWorktime.="Вт " . $workTime[1] . "<br />";
                                    $strWorktime.="Ср " . $workTime[2] . "<br />";
                                    $strWorktime.="Чт " . $workTime[3] . "<br />";
                                    $strWorktime.="Пн " . $workTime[4] . "<br />";
                                    if ($workTime[5] == $workTime[6]) {
                                        $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                    } else {
                                        $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                        $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                                    }
                                }*/
                                echo $strWorktime;
                                ?></div>
                        </li>
                        <?
                    endif;
                endif;
                unset($storage, $shop);
            endforeach;


            $shopsNotCount =
              ShopsTable::getInstance()
                ->createQuery()
                ->where("(id1c not in (" . implode(",", $codeShop1cIsStock) . ") ) and id1c is NOT NULL and id1c<>'' ")
                ->execute();
            $i = 100;
            foreach ($shopsNotCount as $shop) {
              if(!$shop->getIsActive()) continue;
                //echo $shop->getCity();
                ?>
                <li>
                    <?
                    if ($shop->getIconmetro() != "") {
                        ?>
                        <div style="float: left;margin-left: 10px;margin-right: 5px;"><img src="/uploads/metro/<?= $shop->getIconmetro() ?>" alt="metro"></div>
                    <? } ?>
                    <div class="address-holder">
                        <div class="name-holder">
                            <a target="_blank" href="/<?
                            // if ($shop->getPage()) {
                                echo 'shops/'.$shop->getSlug();
                            // }
                            ?>">
                                <span class="text"><?= $shop->get('Metro') ?></span>
                            </a>
                        </div>
                        <div class="address"><?= $shop->get('Address') ?></div>
                    </div>
                    <div class="stockInfo">
                        <div style="float: left;">Под заказ
                        </div>
                        <div style="float: left;">
                            <div onmouseout="$('#ShopNonCount<?= $shop->getId1c() ?>').fadeOut(0)" onmouseover="$('#ShopNonCount<?= $shop->getId1c() ?>').fadeIn(0)" onclick="$(
                                                    '#ShopNonCount<?= $shop->getId1c() ?>').toggle();" style="z-index: <?= $i ?>;width: 16px; height: 18px;    display: inline-block;    margin-bottom: -5px; background: url(/images/questionicon.png); position: relative;">

                                <div style=" position: relative;">
                                    <div id="ShopNonCount<?= $shop->getId1c() ?>" style="padding: 10px; z-index: 4; text-align: left; position: absolute; color: rgb(0, 0, 0); width: 215px; right: 0px; top: 0px; border: 1px solid rgb(195, 6, 14); display: none; background:  none 0% 0% repeat scroll rgb(255, 255, 255);">
                                        При оформлении заказа в корзине укажите Способ доставки: Самовывоз из магазина «Он и Она» в Москве, а в коментарии к заказу - адрес магазина доставки.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="work-time"><?
                    $strWorktime = $shop->getWorkTime();
                    /*
                        $workTime = explode(",", $shop->getWorkTime());
                        if ($workTime[0] == $workTime[6]) {
                            $strWorktime = "Пн-Вс " . $workTime[0] . "<br />";
                        } elseif ($workTime[0] == $workTime[4]) {
                            $strWorktime = "Пн-Пт " . $workTime[0] . "<br />";
                            if ($workTime[5] == $workTime[6]) {
                                $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                            } else {
                                $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                            }
                        } else {
                            $strWorktime = "Пн " . $workTime[0] . "<br />";
                            $strWorktime.="Вт " . $workTime[1] . "<br />";
                            $strWorktime.="Ср " . $workTime[2] . "<br />";
                            $strWorktime.="Чт " . $workTime[3] . "<br />";
                            $strWorktime.="Пн " . $workTime[4] . "<br />";
                            if ($workTime[5] == $workTime[6]) {
                                $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                            } else {
                                $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                                $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                            }
                        }
                        */
                        echo $strWorktime;
                        ?></div>
                </li>
                <?
                $i--;
            }
            ?>

        </ul>
    </div>
<? } else {
    ?>

    <div class="tab">
        Покупка возможна в интернет-магазине.</div>
<? } ?>
