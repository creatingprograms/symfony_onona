<?php use_helper('I18N') ?>
<div id="customerPage">
    <div class="headerPage">
        Рады приветствовать Вас!<br />
        Вы зарегистрированы в системе под логином <?= $sf_user->getGuardUser()->getEmailAddress() ?>
    </div>

    <?php
    JSInPages("function togglePoint(tag){
                $(tag).parent().children('div.content').toggle();
                if($(tag).children('div.togglePoint').hasClass('minus')){
                    $(tag).children('div.togglePoint').removeClass('minus').addClass('plus');
                }else{
                    $(tag).children('div.togglePoint').removeClass('plus').addClass('minus');
                }
                }");
    ?>

    <div class="point ordersPoint">
        <div class="topPoint" onclick="togglePoint(this);">
            <div class="togglePoint plus">
            </div>
            <div class="iconPoint">
                <img src="/images/mobile/icons/green/cart.svg" width="24" />
            </div>
            <div class="namePoint">
                Мои заказы
            </div>
        </div>
        <div class="content" style="display: none;">
            <table  cellspacing="0" cellpadding="5" border="1" align="center" style="border-collapse:collapse; width: 100%;">
                <thead><tr>
                        <th>№ заказа</th>
                        <th>Дата заказа</th>
                        <?/*<th>Товаров</th>*/?>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr></thead><tbody>
                    <?php
                    foreach ($orders as $order['id'] => $order):

                        $products_old = $order['text'] != '' ? unserialize($order['text']) : '';
                        $TotalSumm = 0;
                        $quantity = 0;
                        foreach ($products_old as $key => $productInfo):
                            $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
                            $quantity = $quantity + $productInfo['quantity'];
                        endforeach;
                        ?>
                        <tr>
                            <td align="center"><a href="<?= url_for('@customer_orderdetails?id=' . $order['id']) ?>"><?= $order['prefix'] ?><?= $order['id'] ?></a></td>
                            <td align="center"><?= $order['created_at'] ?></td>
                            <?/*<td align="center"><?= $quantity ?></td>*/?>
                            <td align="center"><?= $TotalSumm ?> руб.</td>
                            <td align="center" style="text-transform:capitalize;"><?= $order['status'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody></table>
        </div>
    </div>

    <div class="point dataPoint">
        <div class="topPoint" onclick="togglePoint(this);">
            <div class="togglePoint plus">
            </div>
            <div class="iconPoint">
                <img src="/images/mobile/icons/green/mydata.svg" width="24" />
            </div>
            <div class="namePoint">
                Мои данные
            </div>
        </div>
        <div class="content" style="display: none;">

            <form action="<?php echo url_for('@customer_mydata') ?>" method="post" id="mydata">
                <?php echo get_partial('sfGuardRegister/form', array('form' => $form)) ?>
                <div class="rulesAndButton">
                    <span style="color: #c3060e;">*</span> - Поля, обязательны для заполнения.<br /> 
                    <span style="color: #c3060e;">**</span> - Пожалуйста, указывайте достоверные данные, чтобы мы могли доставить Ваш заказ.<br /> <br /> 

                    <div style="margin: 20px;">
                        <a class="redButton" href="#" onClick="$('#mydata').find('[type=\'submit\']').trigger('click');"><span>Сохранить</span></a>
                    </div>   
                    <input type="submit" class="submitButton" style="display: none;" />
                </div>
            </form>
        </div>
    </div>

    <div class="point bonusPoint">
        <div class="topPoint" onclick="togglePoint(this);">
            <div class="togglePoint plus">
            </div>
            <div class="iconPoint">
                <img src="/images/mobile/icons/green/bonus.svg" width="24" />
            </div>
            <div class="namePoint">
                Мои бонусы
            </div>
        </div>
        <div class="content" style="display: none;padding: 10px;">
            <b>На вашем Бонусном счете: <?= $bonusCount[0]['bonusCount'] ?></b>
            <div id="HystoryBonus">
                <table style="width: 100%;"><tbody>
                        <?php
                        foreach ($bonus as $bonusOne):
                            ?>    <tr>
                                <td>
                                    <?= $bonusOne['created_at']; ?>
                                </td>
                                <td>
                                    <?= $bonusOne['bonus']; ?>
                                </td>
                                <td>
                                    <?= str_replace("Пробление", "Продление", $bonusOne['comment']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="point photosPoint">
        <div class="topPoint" onclick="togglePoint(this);">
            <div class="togglePoint plus">
            </div>
            <div class="iconPoint">
                <img src="/images/mobile/icons/green/camera.svg" width="24" />
            </div>
            <div class="namePoint">
                Мои фото
            </div>
        </div>
        <div class="content" style="display: none;padding: 10px;">

            <? If (count($userPhotos) > 0) { ?>
                Вы добавили фото всего: <?= count($userPhotos) ?><br />
                <ul class="photosUserLK">
                    <? foreach ($userPhotos as $photo['id'] => $photo) {
                        ?><li><span>
                                <table class="noBorder" style="padding: 0;"><tbody><tr><td style="width: 180px;height: 178px;text-align: center;vertical-align: middle;padding: 0;line-height: 0;font-size: 0;">

                                                <img src="/uploads/photouser/thumbnails/<?= $photo['filename'] ?>" style="max-width: 180px; max-height: 180px;">
                                            </td></tr></tbody></table>

                            </span>
                            Дата: <?= date("d.m.Y H:i", strtotime($photo['created_at'])) ?><br/>
                            <?php /* Товар: <a href="/product/<?= $photo->getProduct()->getSlug() ?>"><?= $photo->getProduct()->getName() ?></a> */ ?></li><?
                    }
                    ?>
                </ul>
            <? } else { ?>
                Вы еще не добавили ни одной фотографии к товарам. <br/>

            <? } ?>
        </div>
    </div>
    <? /*
      <div class="point comparePoint">
      <div class="topPoint" onclick="togglePoint(this);">
      <div class="togglePoint plus">
      </div>
      <div class="iconPoint">
      <img src="/images/mobile/icons/green/compare.svg" width="24" />
      </div>
      <div class="namePoint">
      Мои сравнения
      </div>
      </div>
      <div class="content" style="display: none;padding: 10px;">
      Null
      </div>
      </div>

      <div class="point desirePoint">
      <div class="topPoint" onclick="togglePoint(this);">
      <div class="togglePoint plus">
      </div>
      <div class="iconPoint">
      <img src="/images/mobile/icons/green/desire.svg" width="24" />
      </div>
      <div class="namePoint">
      Мои желания
      </div>
      </div>
      <div class="content" style="display: none;padding: 10px;">
      Null
      </div>
      </div>

      <div class="point notificationPoint">
      <div class="topPoint" onclick="togglePoint(this);">
      <div class="togglePoint plus">
      </div>
      <div class="iconPoint">
      <img src="/images/mobile/icons/green/notification.svg" width="24" />
      </div>
      <div class="namePoint">
      Мои оповещения
      </div>
      </div>
      <div class="content" style="display: none;padding: 10px;">
      Null
      </div>
      </div>
     */ ?>
    <div class="point notificationPoint">
        <a href="<?php echo url_for('@sf_guard_signout') ?>">
            <div class="topPoint">
                <div class="togglePoint plus">
                </div>
                <div class="iconPoint">
                    <img src="/images/mobile/icons/green/logout.svg" width="24" />
                </div>
                <div class="namePoint">
                    Выход из аккаунта
                </div>
            </div>
        </a>
    </div>


    <div style="padding: 10px;">
        В разделе <a href="<?php echo url_for('@category_newproduct') ?>">«Новинки»</a> Вы сможете увидеть новые товары, добавленные за время Вашего отсутствия, и всегда быть в курсе последних брендов.<br />
        В разделе <a href="<?php echo url_for('@category_bestprice') ?>">«Лучшая цена»</a> Вы сможете приобрести товары по самым низким ценам в рунете.<br />
        Если у Вас есть вопросы о работе OnOna.ru - познакомьтесь с разделом <a href="<?php echo url_for('@page?slug=kak-sdelat-zakaz') ?>">«Как сделать заказ»</a>.<br />
        <br />
        <br />
        Не забывайте, что компания «Он и Она» осуществляет БЕСПЛАТНУЮ ДОСТАВКУ, <a href="<?php echo url_for('@page?slug=dostavka') ?>">подробнее о доставке</a>.<br />
        <br />
        Мы осуществляем самую <a href="<?php echo url_for('@page?slug=dostavka') ?>">выгодную доставку</a> в Ваш город по наилучшей цене.
    </div>
</div>