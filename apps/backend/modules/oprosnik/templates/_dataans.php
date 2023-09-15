<div style="padding:10px; ">



    <?php
    if ($form['shop']->getValue() == "Магазин") {

        $data = unserialize($form['dataans']->getValue());
        ?>
            <table style="width: 100%; border: 0px;" class="noBorder">
            <tr>
                <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Оценка Продавца-консультанта</td><td style="padding-top: 50px;"></td>
            </tr>
            <tr>
                <td>Подошел ли к Вам Продавец-консультант сразу?</td><td><?= $data['managerComunication'][0] ?><br /><br />

                    Другое: <?= $data['managerComunication']['other'] ?></td>
                </td>
            </tr>
            <tr>
                <td>Рекомендовал ли Продавец-консультант товар, или Вы осуществили свой выбор самостоятельно?</td><td>
                    <?= $data['managerRecomendation'] ?>
                </td>
            </tr>
            <tr>
                <td>Предложил ли ознакомится, посмотреть и потрогать предлагаемый товар?</td><td><?= $data['managerViewProd'] ?>


                </td>
            </tr>
            <tr>
                <td>Предложил ли несколько вариантов товара?</td><td><?= $data['managerListProduct'] ?>

                </td>
            </tr>
            <tr>
                <td>Рассказал ли о особенностях эксплуатации материалов?</td><td><?= $data['managerMaterial'] ?>
                </td>
            </tr>
            <tr>
                <td>Предложил ли Продавец-консультант заказать товар специально для Вас?</td><td><?= $data['managerOrderProd'] ?>

                </td>
            </tr>
            <tr>
                <td>Поинтересовался ли наличием дисконтной карты?</td><td><?= $data['managerDiscountCard'] ?>

                </td>
            </tr>
            <tr>
                <td>Предложил ли воспользоваться скидками?</td><td><?= $data['managerDiscount'] ?>
                </td>
            </tr>
            <tr>
                <td>Записал ли для Продавец-консультант интересующий Вас товар (При отсутствии товара на данный момент)?</td><td><?= $data['managerWriteProduct'] ?>

                </td>
            </tr>
            <tr>
                <td>В целом Продавец-консультант был вежлив с Вами?</td><td><?= $data['managerPolite'] ?></td>
            </tr>
            <tr>
                <td>Оцените, пожалуйста, Ваше впечатление от Продавца-консультанта (внешний вид, подача и изложение описания товара и т.д.)</td><td><?= $data['managerBall'] ?></td>
            </tr>
            <tr>
                <td>Захотелось ли Вам вернуться за будущей покупкой именно к этому Продавцу-консультанту?</td><td><?= $data['managerReturn'] ?>

                </td>
            </tr>
            <tr><td>Здесь Вы можете оставить свои комментарии к оценке </td><td>
                    <?= $data['commentManager'] ?></td>
            </tr>
            <tr>
                <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Оценка Магазина</td><td style="padding-top: 50px;"></td>
            </tr>
            <tr>
                <td>Ваше впечатления от Магазина/Продавца-консультанта</td><td><?= $data['shopImpression'] ?>


                </td>
            </tr>
            <tr>
                <td>Будете ли Вы рекомендовать знакомым данный магазин?</td><td><?= $data['shopRecomendation'] ?></td>
            </tr>
            <tr>
                <td>Что, на Ваш взгляд, не хватает для идеальной работы Магазина?</td><td><?= $data['shopMissing'] ?>

                </td>
            </tr>
            <tr>
                <td>Какую услугу Вы рекомендовали бы добавить в Магазине?</td><td><?= $data['shopAddService'] ?> </td>
            </tr>
            <tr>
                <td>Укажите главное достоинство нашего Магазина для себя?</td><td>
                    <?
                    Foreach($data['shopQuality'] as $keyArray=>$value){
                        if($keyArray!=='other'){
                            echo $value."<br>";
                        }
                    }?>
                    Другое: <?= $data['shopQuality']['other'] ?></td>
                    </td>
            </tr>
            <tr>
                <td>Что влияет на ваш выбор Магазина?</td><td><?
                    Foreach($data['shopChoiceReason'] as $keyArray=>$value){
                        if($keyArray!=='other'){
                            echo $value."<br>";
                        }
                    }?>
                    Другое: <?= $data['shopChoiceReason']['other'] ?></td>
                    </td>
            </tr>

            <tr>
                <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Оценка полок в Магазине</td><td style="padding-top: 50px;"> </td>
            </tr>
            <tr>
                <td> Оцените, пожалуйста, ассортимент Магазина (на сколько полный ассортимент)</td><td><?= $data['shopProductChoice'] ?></td>
            </tr>
            <tr>
                <td>Вы приобрели, что хотели?</td><td><?= $data['payProd'] ?>     <br/><br/>
                    Если нет, то почему: <?= $data['payProdReason'] ?> </td>
                </td>
            </tr>
            <tr>
                <td>Как вы охарактеризуете цены в нашем Магазине?</td><td><?= $data['productPrice'] ?>                <br/><br/>
                    Иное: <?= $data['productPriceOther'] ?> </td>
                </td>
            </tr>
            <tr>
                <td>Если Вы частый Покупатель, скажите, какой товар у Вас пользуется большим спросом?</td><td><?= $data['prodGreaterDemand'] ?></td>
            </tr>
            <tr>
                <td>Что Вы хотели бы видеть на полках в нашем Магазине? (определенный производитель, определенную серию, определенный товар)</td><td><?= $data['addShop'] ?> </td>
            </tr>
            <tr>
                <td>Какой товар на Ваш взгляд необходимо добавить в ассортимент?</td><td><?= $data['prodAddShop'] ?> </td>
            </tr>
            <tr>
                <td>Какая дополнительная услуга с приобретением товара Вас может заинтересовать? </td><td><?
                    Foreach($data['shopAddServicePayProd'] as $keyArray=>$value){
                        if($keyArray!=='other'){
                            echo $value."<br>";
                        }
                    }?>
                    Если возможно, предложите, пожалуйста, свой вариант:<br/> <?= $data['shopAddServicePayProd']['other'] ?>
                             </td>
            </tr>
            <tr>
                <td>Что Вы могли бы порекомендовать нам?</td><td><?= $data['recomendation'] ?></td>
            </tr></table><?
    } else {
        ?>
        <?
        $data = unserialize($form['dataans']->getValue());
        $order = OrdersTable::getInstance()->findOneById($form['orderid']->getValue());
        //print_r($data);
        If ($order->getStatus() == "Оплачен"):
            ?>
            <form action="" method="POST" id="oprosnik">
                <table style="width: 100%; border: 0px;" class="noBorder">
                  <? if (isset($data['is_recommend']) || isset($data['manager_rate']) || isset($data['delivery_rate']) || isset($data['comment_short']) || isset($data['is_items_ok'])|| isset($data['is_easy']) ) :?>
                    <? if (isset($data['is_recommend'])) :?>
                      <tr>
                          <td>Обратитесь ли вы вновь в магазин onona.ru, порекомендуете ли его друзьям и знакомым?</td><td><?= $data['is_recommend'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['is_easy'])) :?>
                      <tr>
                          <td>Удобен ли сайт нашего Интернет-магазина?</td><td><?= $data['is_easy'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['manager_rate'])) :?>
                      <tr>
                          <td>Оцените работу менеджера, принявшего и оформившего ваш заказ.</td><td><?= $data['manager_rate'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['delivery_rate'])) :?>
                      <tr>
                          <td>Оцените работу курьера, доставившего ваш заказ</td><td><?= $data['delivery_rate'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['is_items_ok'])) :?>
                      <tr>
                          <td>Довольны ли Вы приобретенным товаром? </td><td><?= $data['is_items_ok'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['comment_short'])) :?>
                      <tr>
                          <td>Комментарий</td><td><?= $data['comment_short'] ?></td>
                      </tr>
                    <? endif ?>
                  <? else :?>
                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;">Работа менеджеров</td>
                        <td><? for ($i = 1; $i <= $data['managerWork']; $i++) {
                            echo '<img src="/images/star_select.png">';
                        } ?></td>
                    </tr>
                    <tr>
                        <td>Насколько корректно и вежливо с Вами общался менеджер?</td><td><?= $data['managerComunication'] ?></td>
                    </tr>
                    <tr>
                        <td>Смог ли менеджер проконсультировать Вас по всем Вашим вопросам?</td><td><?= $data['managerAdvise'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Как быстро перезвонил Вам менеджер для подтверждения оформленного Вами заказа?</td><td><?= $data['managerSpeedCalled'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Если были изменены комплектация или срок доставки заказа, вовремя ли менеджер сообщил Вам об этом?</td><td><?= $data['managerListProduct'] ?>
                        </td>
                    </tr>
                    <? /* <tr>
                      <td>Менеджер разговаривал по телефону вежливо?</td><td><?=$data['managerPolitely']?></td>
                      </tr>
                      <tr>
                      <td>Смог ли менеджер Вам помочь, когда Вы звонили нам по телефону?</td><td><?=$data['managerCalledHelp']?></td>
                      </tr> */ ?>
                    <tr>
                        <td>Если Вы отправляли нам вопросы через онлайн чат или через обратную связь на сайте, достаточно ли быстро Вы получили ответ?</td><td><?= $data['managerLiveText'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Если Вы подавали запрос на ремонт/замену товара, достаточно ли быстро наш сервис-менеджер обработал Вашу заявку и связался с Вами?</td><td><?= $data['managerReturn'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Оцените работу менеджеров по пятибальной шкале</td><td><?= $data['managerBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Комментарий</td><td><?= $data['commentManager'] ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Работа службы доставки</td><td style="padding-top: 50px;"> <? for ($i = 1; $i <= $data['deliveryWork']; $i++) {
                        echo '<img src="/images/star_select.png">';
                    } ?></td>
                    </tr>
                    <tr>
                        <td>Был ли Ваш заказ доставлен Вам в согласованное с нашим менеджером время? </td><td><?= $data['deliveryTime'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td> Корректно ли общался с Вами курьер при доставке Вашего заказа? </td><td><?= $data['kurerSleng'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Была ли сохранена целостность упаковки при доставке Вашего заказа?</td><td><?= $data['productPacket'] ?>

                        </td>
                    </tr>
                    <tr>
                        <td>Был ли Вам предоставлен кассовый чек?</td><td><?= $data['orderCheck'] ?></td>
                    </tr>
                    <tr>
                        <td>Оцените работу доставки по пятибалльной шкале</td><td><?= $data['deliveryBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Комментарий</td><td><?= $data['commentDelivery'] ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Качество товаров</td><td style="padding-top: 50px;"> <? for ($i = 1; $i <= $data['productWork']; $i++) {
                        echo '<img src="/images/star_select.png">';
                    } ?></td>
                    </tr>
                    <tr>
                        <td>Соответствует ли качество и характеристики товаров заявленным (описанию) на сайте? </td><td><?= $data['qualityProduct'] ?></td>
                    </tr>
                    <? /* <tr>
                      <td>Повреждены ли товары?</td><td><?=$data['damageProduct']?></td>
                      </tr> */ ?>
                    <tr>
                        <td>Оцените качество товаров по пятибалльной шкале</td><td><?= $data['productBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Комментарий</td><td><?= $data['commentProduct'] ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Сайт</td><td style="padding-top: 50px;"><? for ($i = 1; $i <= $data['wwwWork']; $i++) {
                            echo '<img src="/images/star_select.png">';
                          } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Удобно ли пользоваться сайтом?</td><td><?= $data['wwwEasy'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Были ли у Вас трудности с поиском нужного Вам товара на сайте?</td><td><?= $data['wwwSearchProduct'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Легко ли Вы нашли информацию по доставке и оплате?</td><td><?= $data['wwwSearchDelPay'] ?></td>
                    </tr>
                    <tr>
                        <td>Удобно ли пользоваться корзиной?</td><td><?= $data['wwwCart'] ?></td>
                    </tr>
                    <tr>
                        <td>Оцените работу сайта по пятибалльной шкале</td><td><?= $data['wwwBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Комментарий</td><td><?= $data['commentWWW'] ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Общие вопросы</td><td style="padding-top: 50px;"><? for ($i = 1; $i <= $data['otherWork']; $i++) {
                            echo '<img src="/images/star_select.png">';
                        } ?>
                      </td>
                    </tr>
                    <tr>
                        <td>Сколько раз (примерно) Вы заказывали в нашем интернет-магазине за последний год?</td><td><?= $data['wwwOrder'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Оцените по пятибальной шкале работу магазина</td><td><?= $data['shopBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Что Вы могли бы порекомендовать нам?</td><td><?= $data['recomendation'] ?></td>
                    </tr>
                  <? endif ?>
                  </table>
            </form>
            <?
             $comments=false;
            if (isset($data['comments'])) $comments = $data['comments'];
            if(!empty($comments)) foreach ($comments as $prid => $comment) {
                if ($comment != "") {
                    $product = ProductTable::getInstance()->findOneById($prid);
                    $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();

                    echo '  <table style="width: 100%"><tbody><tr><td style="width: 100px"><img height="100" border="0" src="http://onona.ru/uploads/photo/thumbnails_250x250/' . $photos[0]->getFilename() . '" alt="' . $product->getName() . '" class="item_picture">
                                        </td><td style="width: 200px;"><a target="_blank" href="http://onona.ru/product/' . $product->getSlug() . '">' . $product->getName() . '</a></td>
                                        <td style="border-left: 1px solid #DDDDDD;">' . $comment . '</td></tr></tbody></table>';
                }
            }
            ?>
    <? elseif ($order->getStatus() == "Отмена"): ?>


            <form action="" method="POST" id="oprosnik">
                <table style="width: 100%; border: 0px;" class="noBorder">
                  <? if (isset($data['is_recommend']) || isset($data['manager_rate']) || isset($data['delivery_rate']) || isset($data['comment_short']) ) :?>
                    <? if (isset($data['is_recommend'])) :?>
                      <tr>
                          <td>Обратитесь ли вы вновь в магазин onona.ru, порекомендуете ли его друзьям и знакомым?</td><td><?= $data['is_recommend'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['manager_rate'])) :?>
                      <tr>
                          <td>Оцените работу менеджера, принявшего и оформившего ваш заказ.</td><td><?= $data['manager_rate'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['delivery_rate'])) :?>
                      <tr>
                          <td>Оцените работу курьера, доставившего ваш заказ</td><td><?= $data['delivery_rate'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['comment_short'])) :?>
                      <tr>
                          <td>Комментарий</td><td><?= $data['comment_short'] ?></td>
                      </tr>
                    <? endif ?>
                  <? else :?>
                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;">Работа менеджеров</td><td><? for ($i = 1; $i <= $data['managerWork']; $i++) {
                            echo '<img src="/images/star_select.png">';
                        } ?></td>
                    </tr>
                    <tr>
                        <td>Насколько корректно и вежливо с Вами общался менеджер?</td><td><?= $data['managerComunication'] ?></td>
                    </tr>
                    <tr>
                        <td>Смог ли менеджер проконсультировать Вас по всем Вашим вопросам?</td><td><?= $data['managerAdvise'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Как быстро перезвонил Вам менеджер для подтверждения оформленного Вами заказа?</td><td><?= $data['managerSpeedCalled'] ?>
                        </td>
                    </tr>
                    <? /* <tr>
                      <td>Если были изменены комплектация или срок доставки заказа, вовремя ли менеджер сообщил Вам об этом?</td><td><?=$data['managerListProduct']?>
                      </td>
                      </tr>
                      <tr>
                      <td>Менеджер разговаривал по телефону вежливо?</td><td><?=$data['managerPolitely']?></td>
                      </tr>
                      <tr>
                      <td>Смог ли менеджер Вам помочь, когда Вы звонили нам по телефону?</td><td><?=$data['managerCalledHelp']?></td>
                      </tr> */ ?>
                    <tr>
                        <td>Если Вы отправляли нам вопросы через онлайн чат или через обратную связь на сайте, достаточно ли быстро Вы получили ответ?</td><td><?= $data['managerLiveText'] ?>
                        </td>
                    </tr>
                    <? /* <tr>
                      <td>Если Вы подавали запрос на ремонт/замену товара, достаточно ли быстро наш сервис-менеджер обработал Вашу заявку и связался с Вами?</td><td><?=$data['managerReturn']?>
                      </td>
                      </tr> */ ?>
                                <tr>
                        <td>Оцените работу менеджеров по пятибальной шкале</td><td><?= $data['managerBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Комментарий</td><td><?= $data['commentManager'] ?></td>
                    </tr>

                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Сайт</td><td style="padding-top: 50px;"><? for ($i = 1; $i <= $data['wwwWork']; $i++) {
                            echo '<img src="/images/star_select.png">';
                        } ?>
                      </td>
                    </tr>
                    <tr>
                        <td>Удобно ли пользоваться сайтом?</td><td><?= $data['wwwEasy'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Были ли у Вас трудности с поиском нужного Вам товара на сайте?</td><td><?= $data['wwwSearchProduct'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Легко ли Вы нашли информацию по доставке и оплате?</td><td><?= $data['wwwSearchDelPay'] ?></td>
                    </tr>
                    <tr>
                        <td>Удобно ли пользоваться корзиной?</td><td><?= $data['wwwCart'] ?></td>
                    </tr>
                    <tr>
                        <td>Оцените работу сайта по пятибалльной шкале</td><td><?= $data['wwwBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Комментарий</td><td><?= $data['commentWWW'] ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Общие вопросы</td><td style="padding-top: 50px;"><? for ($i = 1; $i <= $data['otherWork']; $i++) {
                            echo '<img src="/images/star_select.png">';
                        } ?>
                      </td>
                    </tr>
                    <tr>
                        <td>Сколько раз (примерно) Вы заказывали в нашем интернет-магазине за последний год?</td><td><?= $data['wwwOrder'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Оцените по пятибальной шкале работу магазина</td><td><?= $data['shopBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Сообщите, пожалуйста, причину отмены заказа:</td><td><?= $data['recomendation'] ?></td>
                    </tr>
                  <? endif ?>
                </table>
            </form>

    <? elseif ($order->getStatus() == "Возврат"): ?>


            <form action="" method="POST" id="oprosnik">
                <table style="width: 100%; border: 0px;" class="noBorder">
                  <? if (isset($data['is_recommend']) || isset($data['manager_rate']) || isset($data['delivery_rate']) || isset($data['comment_short']) ) :?>
                    <? if (isset($data['is_recommend'])) :?>
                      <tr>
                          <td>Обратитесь ли вы вновь в магазин onona.ru, порекомендуете ли его друзьям и знакомым?</td><td><?= $data['is_recommend'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['manager_rate'])) :?>
                      <tr>
                          <td>Оцените работу менеджера, принявшего и оформившего ваш заказ.</td><td><?= $data['manager_rate'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['delivery_rate'])) :?>
                      <tr>
                          <td>Оцените работу курьера, доставившего ваш заказ</td><td><?= $data['delivery_rate'] ?></td>
                      </tr>
                    <? endif ?>
                    <? if (isset($data['comment_short'])) :?>
                      <tr>
                          <td>Комментарий</td><td><?= $data['comment_short'] ?></td>
                      </tr>
                    <? endif ?>
                  <? else :?>
                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;">Работа менеджеров</td><td><? for ($i = 1; $i <= $data['managerWork']; $i++) {
                          echo '<img src="/images/star_select.png">';
                      } ?></td>
                    </tr>
                    <tr>
                        <td>Насколько корректно и вежливо с Вами общался менеджер?</td><td><?= $data['managerComunication'] ?></td>
                    </tr>
                    <tr>
                        <td>Смог ли менеджер проконсультировать Вас по всем Вашим вопросам?</td><td><?= $data['managerAdvise'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Как быстро перезвонил Вам менеджер для подтверждения оформленного Вами заказа?</td><td><?= $data['managerSpeedCalled'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Если были изменены комплектация или срок доставки заказа, вовремя ли менеджер сообщил Вам об этом?</td><td><?= $data['managerListProduct'] ?>
                        </td>
                    </tr>
                    <? /* <tr>
                      <td>Менеджер разговаривал по телефону вежливо?</td><td><?=$data['managerPolitely']?></td>
                      </tr>
                      <tr>
                      <td>Смог ли менеджер Вам помочь, когда Вы звонили нам по телефону?</td><td><?=$data['managerCalledHelp']?></td>
                      </tr> */ ?>
                    <tr>
                        <td>Если Вы отправляли нам вопросы через онлайн чат или через обратную связь на сайте, достаточно ли быстро Вы получили ответ?</td><td><?= $data['managerLiveText'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Оцените работу менеджеров по пятибальной шкале</td><td><?= $data['managerBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Комментарий</td><td><?= $data['commentManager'] ?></td>
                    </tr>

                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Сайт</td><td style="padding-top: 50px;"><? for ($i = 1; $i <= $data['wwwWork']; $i++) {
                            echo '<img src="/images/star_select.png">';
                        } ?>
                      </td>
                    </tr>
                    <tr>
                        <td>Удобно ли пользоваться сайтом?</td><td><?= $data['wwwEasy'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Были ли у Вас трудности с поиском нужного Вам товара на сайте?</td><td><?= $data['wwwSearchProduct'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Легко ли Вы нашли информацию по доставке и оплате?</td><td><?= $data['wwwSearchDelPay'] ?></td>
                    </tr>
                    <tr>
                        <td>Удобно ли пользоваться корзиной?</td><td><?= $data['wwwCart'] ?></td>
                    </tr>
                    <tr>
                        <td>Оцените работу сайта по пятибалльной шкале</td><td><?= $data['wwwBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Комментарий</td><td><?= $data['commentWWW'] ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Общие вопросы</td><td style="padding-top: 50px;"><? for ($i = 1; $i <= $data['otherWork']; $i++) {
                          echo '<img src="/images/star_select.png">';
                          } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Сколько раз (примерно) Вы заказывали в нашем интернет-магазине за последний год?</td><td><?= $data['wwwOrder'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Оцените по пятибальной шкале работу магазина</td><td><?= $data['shopBall'] ?></td>
                    </tr>
                    <tr>
                        <td>Сообщите, пожалуйста, причину почему вы не получили свой заказ:</td><td><?= $data['recomendation'] ?></td>
                    </tr>
                  <? endif ?>
              </table>
            </form>
    <? endif; ?>
<? } ?>
</div>
