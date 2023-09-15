<?php
slot('topMenu', true);
if (is_array($products_old) and count($products_old) > 0):
    ?><div class="borderCart">
        <form id="processOrder" action="/cart/processorder/confirmed" method="post" name="confirm_order">
            <div align="center" class="pink bold" style="padding:5px; color: #ba272d;">Моя корзина </div>
            <div style="display: block;">
                <a href="/cart"><div style="position: relative; z-index: 10; float: left; background: url('/newdis/images/cart/top1_act.png') repeat scroll 0pt 0pt transparent; height: 32px; width: 186px;"></div></a>
                <div style="position: relative; float: left; height: 32px; width: 186px; left: -13px; z-index: 9; background: url('/newdis/images/cart/top2_act.png') repeat scroll 0pt 0pt transparent;"></div>
                <div style="position: relative; float: left; height: 32px; width: 186px; left: -26px; z-index: 8; background: url('/newdis/images/cart/top3_noact.png') repeat scroll 0pt 0pt transparent;"></div>
            </div>
            <div style="clear: both;margin-bottom: 20px;"></div>


            <table width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000" border="0" align="center" class="cartContent">
                <thead><tr>
                        <th>Наименование</th>
                        <th style=" width: 111px;">Цена, руб.</th>
                        <th style=" width: 88px;">Скидка, %</th>
                        <th style=" width: 110px;">Кол-во</th>
                        <th style=" width: 108px;">Сумма, руб.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $TotalSumm = 0;
                    $bonusAddUser = 0;
                    foreach ($products_old as $key => $productInfo):
                        $product = ProductTable::getInstance()->findOneById($productInfo['productId']);

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
                            <td><?= $productInfo['price'] ?></td>
                            <td><?= $product->getDiscount() > 0 ? $product->getDiscount() : '0'; ?></td>
                            <td><?= $productInfo['quantity'] ?>                    </td>
                            <td><div style="display: inline-block;" id="totalcost_<?= $productInfo['productId'] ?>"><?= $productInfo['quantity'] * $productInfo['price'] ?></div></td>

                        </tr>
    <?php endforeach; ?>
                </tbody>
            </table>

            <div style=" color: #414141;">
                <div style="padding:10px;float: left;">
                    <span style="color: #c3060e;">*</span> После оплаты заказа на ваш счет будут зачислены <span style="color: #c3060e;"><?= $bonusAddUser ?></span> <u>бонусных рублей</u>
                </div>
    <? if ($sf_user->isAuthenticated()): ?> <div style="padding:10px;float: left;position: absolute;margin-top: 30px;">
                        <div style="border: 1px solid #c3060e; max-width: 390px; height: 56px; margin-top: 10px; border-radius: 4px 4px 4px 4px;">
                            <img src="/newdis/images/cart/surprice.png" style="right: -5px; position: relative; float: left;">
                            <div style="float: left; margin: 18px 5px 0pt 10px;">Сейчас на вашем Бонусном счете <?
        $this->bonus = BonusTable::getInstance()->findBy('user_id', $sf_context->getUser()->getGuardUser()->getId());
        $this->bonusCount = 0;
        foreach ($this->bonus as $bonus) {
            $this->bonusCount = $this->bonusCount + $bonus->getBonus();
        }
        echo $this->bonusCount;
        ?> рублей.</div>
                        </div></div><? endif; ?>

                <div style="text-align: left; position: absolute; right: 21px; width: 191px; background-color: #f1f1f1;padding:15px;">
                    <div style="clear: both; margin-bottom: 5px;">Сумма заказа: <div style="float:right;"><span id="cartTotalCost" style="font-weight: normal;"><?= $TotalSumm ?></span></div>
                    </div>
                    <div style="clear: both; margin-bottom: 5px;">Доставка: <div style="float:right;"><span id="PriceDeliverTable">0</span></div>
                    </div>
                    <div style="clear: both; margin-bottom: 5px;">Бонусы: <div style="float:right;"><span id="PriceBonusTable">0</span></div>
                    </div>
                    <div style="clear: both; margin-bottom: 5px;">Итого к оплате: <div style="float:right;"><span style="font-weight: bold;color: #c3060e;" id="PriceAllTable"><?= $TotalSumm ?></span></div>
                    </div>
                </div>


            </div>
            <div style="clear: both;<? /* margin-bottom: 120px; */ ?> height: 120px;"></div>
            <div style="color: #c3060e;width: 934px;background-color: #f1f1f1;padding: 10px 10px 10px 15px; font-size: 14px;">Выберите способ доставки и оплаты</div>


            <div style="clear: both;margin-bottom: 20px;"></div>



            <script>
                function SelectDelivery(delivery){
                    // $(this).find('input').attr('checked', 'checked');
                    checkDelivery($(delivery));
                    checkBonusMenu($('input[type=radio][name=payBonus]:checked'));
                    window.setTimeout('$(\'.tab:visible input[type=radio][name=paymentId]\').first().attr(\'checked\', \'checked\');', 100);
                    window.setTimeout('$("input[type=radio][value='+$(delivery).find('input').val()+'][name=deliveryId]").attr(\'checked\',\'checked\');', 50);
                    window.setTimeout('$("input[type=radio][value=3][name=payBonus]:visible").attr(\'checked\',\'checked\');', 50);

                    window.setTimeout("$('#content').css('height',$('.borderCart').height())", 50);
                    return false;
                }
            </script>
            <div class="tabset tabsetDeliver">
                <ul class="tab-control">
                    <?php
                    $delivers = DeliveryTable::getInstance()->createQuery()->where('is_public = \'1\'')->orderBy('position ASC')->execute();

                    foreach ($delivers as $delivery):
                        ?>
                        <li>
                            <a href="#" onclick="SelectDelivery(this)">
                                <div class="center"><input style="float: left;" type="radio" value="<?= $delivery->getId() ?>" id="delivMod" name="deliveryId" onClick=" return false;  "><span style=" width: 225px; padding: 0;"> <?= $delivery->getName() ?></span></div>
                                <div class="helper"></div>
                            </a>

                        </li>

                <?php endforeach; ?>
                </ul>
                <?php
                foreach ($delivers as $delivery):
                    ?>
                    <div class="tab">
                        <?= $delivery->getContent(); ?>
        <? if ($delivery->getId() == 11) { ?>

                            <script type="text/javascript" src="http://pickpoint.ru/select-test/widget/postamat.js" /></script>
                            <div id="pickpoint" style=""><br />
                                <div style="text-align: center; width: 100%;"><a href="#" onclick="PickPoint.open(my_function);return false" style="font: 17px/18px Tahoma,Geneva,sans-serif;color: #C3060E;"><b>Выбрать постамат или пункт выдачи на карте</b></a></div>
                                <div id="address"></div>
                                <input type="hidden" name="pickpoint_id" id="pickpoint_id">
                                <input type="hidden" name="pickpoint_address" id="pickpoint_address" value="" /><br /><br />
                                <script type="text/javascript">
                                    function my_function(result){
                                        // устанавливаем в скрытое поле ID терминала
                                        document.getElementById('pickpoint_id').value=result['id'];
                                        document.getElementById('pickpoint_address').value=result['name']+'<br />'+result['address'];

                                        // показываем пользователю название точки и адрес доствки
                                        document.getElementById('address').innerHTML=result['name']+'<br />'+result['address'];
                                    }
                                </script></div>
                            <?
                        }
                        if ($delivery->getId() == 13) {
                            ?>
                            <div style="text-align: center; width: 575px;"><a href="#" onclick="openMap(); return false;" style="font: 17px/18px Tahoma,Geneva,sans-serif;color: #C3060E;"><strong>Выбрать почтамат на карте</strong></a><br /><br />
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
        <? If ($this->bonusCount > 0) { ?><div style="padding:0px 0px 15px 10px;text-align: left;"><input type="radio" name="payBonus" onclick="checkBonus(this);$('.couponInput').each(function(i){$(this).val('');});" value="1">&nbsp;Бонусы<br><span style="font-size: 11px;">Оплата бонусами <?= csSettings::get('PERSENT_BONUS_PAY') ?>% стоимости заказа</span></div><? } ?>
                                        <div style="padding:0px 0px 15px 10px;text-align: left;"><input type="radio" name="payBonus" onclick="checkBonus(this);" value="2">&nbsp;Акция <input type="text" id="coupon" class="couponInput" name="couponTxt" onclick="$(this).keyup(function() {$(this).prev().attr('checked', 'checked');});" onchange="$('#coupnumber').val(this.value)"><br><span style="font-size: 11px;">Введите номер купона или код для скидки</span></div>
                                        <div style="padding:0px 0px 15px 10px;text-align: left;"><input checked="checked" type="radio" name="payBonus" onclick="checkBonus(this);" value="3">&nbsp;Без скидки</div>


                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

    <?php endforeach; ?>
            </div>
            <div style="clear: both;margin-bottom: 40px;"></div>

            <div style="color: #c3060e;width: 934px;background-color: #f1f1f1;padding: 10px 10px 10px 15px; font-size: 14px;">Оставьте свои комментарии к заказу</div>


            <div style="clear: both;margin-bottom: 20px;"></div>

            <div>
                Укажите удобное время звонка, когда наш менеджер свяжется с вами для подтверждения заказа. <br />
                <input type="text" id="timeCall" name="timeCall" style="width:300px;margin-bottom: 20px;">
            </div>
            <div>
                Ваши пожелания по заказу и доставке (любая дополнительная информация, которую мы должны знать). <br />
                <textarea style="width:300px; height:100px;" id="comments" name="comments"></textarea>
            </div>


            <div style="clear: both;margin-bottom: 20px;"></div>

            <div>
                <input type="hidden" id="deliveryPriceSend" name="deliveryPrice">
                <a class="red-btn colorWhite" href="#" onClick="$('#processOrder').submit()" style="display: none;"><span style="width: 250px;">Сохранить и продолжить</span></a>
            </div>
            <div style="clear: both;margin-bottom: 20px;"></div>
            <script language="javascript">
                var nextStep;
                function checkPayment(data)
                {
                    $valDeliv=$(":radio[name=deliveryId]").filter(":checked").val();

                    if($("#TotalSumm").html()<2000){
                        $("#deliverPrice").html('290 р.');
                        $("#deliverPriceTotal").html(290+eval($("#cartTotalCost").html())+' р.');
                    }else if($("#TotalSumm").html()>=2000){
                        $("#deliverPrice").html('0 р.');
                        $("#deliverPriceTotal").html(eval($("#cartTotalCost").html())+' р.');
                    }

                    if($valDeliv=='3' &&  data.value=='5') {
                        if($("#cartTotalCost").html()<2000){
                            $("#deliverPrice").html(220+' р.');
                            $("#deliverPriceTotal").html(220+eval($("#cartTotalCost").html())+' р.');
                        }else{
                            $("#deliverPrice").html('0 р.');
                            $("#deliverPriceTotal").html(eval($("#cartTotalCost").html())+' р.');
                        }
                    }else if($valDeliv=='3'){
                        $("#deliverPrice").html(Math.round(220+eval($("#cartTotalCost").html())*0.05)+' р.');
                        $("#deliverPriceTotal").html(Math.round(220+eval($("#cartTotalCost").html())*1.05)+' р.');
                    }

                    $('#deliveryPriceSend').val($('#deliverPrice').html());
                    $('#ButtonNext').css('display', 'block');
                }


                function checkDelivery(data)
                {
                    //console.log(nextStep);
                    data=data.find('input');

                    if($("#cartTotalCost").html()<<?= csSettings::get('free_deliver') ?>){
                        $("#PriceDeliverTable").html('290');
                    }else{
                        $("#PriceDeliverTable").html('0');
                    }

                    if(data.val()=='3' &&  $('input[type=radio][name=paymentId]:checked').val()=='5') {
                        if($("#cartTotalCost").html()<<?= csSettings::get('free_deliver') ?>){
                            $("#PriceDeliverTable").html(220);
                        }else{
                            $("#PriceDeliverTable").html('0');
                        }
                    }else if(data.val()=='3'){
                        $("#PriceDeliverTable").html(220+Math.round(eval($("#cartTotalCost").html())*0.05));
                    }
                    $('#deliveryPriceSend').val($('#PriceDeliverTable').html());
                    $("#PriceAllTable").html(eval($("#cartTotalCost").html())+eval($("#PriceDeliverTable").html())+eval($("#PriceBonusTable").html()));

                    nextStep=true;
                    $('.red-btn').fadeIn();

                }


                function checkBonus(data)
                {
                    if(data.value==1){
                        $("#PriceBonusTable").html(<?
    if ((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm > $bonusCount) {
        echo "-" . $bonusCount;
    } else {
        echo "-" . round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm);
    }
    ?>);
            }
            if(data.value==2){
                $("#PriceBonusTable").html(0);
            }
            if(data.value==3){
                $("#PriceBonusTable").html(0);
            }
            $("#PriceAllTable").html(eval($("#cartTotalCost").html())+eval($("#PriceDeliverTable").html())+eval($("#PriceBonusTable").html()));

        }


        function checkBonusMenu(data)
        {
            if(data.val()==1){
                $("#PriceBonusTable").html(<?
    if ((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm > $bonusCount) {
        echo "-" . $bonusCount;
    } else {
        echo "-" . round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm);
    }
    ?>);
            }
            if(data.val()==2){
                $("#PriceBonusTable").html(0);
            }
            if(data.val()==3){
                $("#PriceBonusTable").html(0);
            }
            $("#PriceAllTable").html(eval($("#cartTotalCost").html())+eval($("#PriceDeliverTable").html())+eval($("#PriceBonusTable").html()));

        }
            </script>

            <input type="hidden" name="couponTxt" id="coupnumber">

        </form>




    </div>


<?php else: ?>
    <?
    $cartEmpty = PageTable::getInstance()->findOneBySlug("stranica-pustoi-korziny");
    echo $cartEmpty->getContent();
    ?>
<?php endif; ?>
