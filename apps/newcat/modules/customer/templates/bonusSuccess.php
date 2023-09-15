<script type="text/javascript" src="/js/jquery.maskedinput-1.2.2.js"></script>
<section class="wellCome">
    <ul class="breadcrumbs">
        <li>
            <a href="/">Главная</a>
        </li>
        <li>
            <a href="/lk">Личный кабинет</a>
        </li>
        <li>
            Мои бонусы
        </li>
    </ul>
    <div class="wrapwer customer-bonus">
        <h1 class="title">Программа лояльности «Он и Она - Бонус»</h1>
        Компания «Он и Она» представляет специально разработанную программу для постоянных покупателей «Он и Она – Бонус», которая заключается в предоставлении вознаграждения за каждую покупку в интернет магазине OnOna.ru и любом магазине «Он и Она» в Москве.
        <br />1 рубль = 1 Бонус.<br />
        Это позволит вам совершать покупки в интернет магазине OnOna.ru со скидкой от 20 до 50% от суммы вашего заказа. Ведь каждая ваша покупка оплачивает следующую!
        <div style="clear: both;height: 20px;"></div>
        <div class="bonus-wrapper">
          <div class="current-count">
            <div class="header">На вашем Бонусном счете:</div>

            <div class="active-to">
              <span class="big-number"><?= $bonusCount ?></span>
              <br />Срок действия бонусов до
              <span style="color:#c3060e;"><?= date("d.m.Y", $lastDate + csSettings::get('BONUS_TIME') * 24 * 60 * 60) ?></span>
            </div>

            <div
              onclick="
                if($('#HystoryBonus').css('display')=='none'){
                    $('#buttonHystoryBonus span').html('Скрыть историю бонусов &gt;');
                    //$('#buttonHystoryBonus').removeClass( 'red-btn').addClass( 'green-btn');
                    $('#HystoryBonus').toggle();
                }else{
                    $('#buttonHystoryBonus span').html('Посмотреть историю бонусов &gt;');
                    //$('#buttonHystoryBonus').removeClass( 'green-btn').addClass( 'red-btn');
                    $('#HystoryBonus').toggle();
                }
                 "
              id="buttonHystoryBonus"
              style="right: 16px; top: -4px;"
              class="red-btn colorWhite">
                <span style="width: 290px;">Посмотреть историю бонусов ></span>
            </div>
          </div>
          <div class="spend-now">
            <div class="header">Перейти к покупкам:</div>
            <img src="/images/lkbonus/potrBon.png">
            <a href="/" style="right: 11px; top: -9px;" class="green-btn colorWhite">
              <span style="width: 290px;">Потратить бонусы сейчас ></span>
            </a>
          </div>
          <div class="program-description">
            <div class="header">Программа «Он и Она - Бонус»:</div>
            <img src="/images/lkbonus/progBonus.png">
            <a href="/programma-on-i-ona-bonus" style="right: 16px; top: -1px;" class="green-btn colorWhite">
              <span style="width: 290px;">Ознакомиться с условиями ></span>
            </a>
          </div>
        </div>
        <div style="clear: both;height: 20px;"></div>
        <? /* if ($bonusCount > 0) { ?>У вас <b><?= $bonusCount ?></b> бонусов <? } else { ?>Вам еще не начислены бонусные рубли.<? } ?><br /><br />
          Бонусные рубли начисляются за оплаченные заказы, мы возвращаем вам на счет от 30 до 100 % от стоимости оплаченного вами заказа.<br />
          Подробнее о программе <a href="http://www.onona.ru/programma-on-i-ona-bonus">«Он и Она - Бонус»</a><br /><br />
          <? if ($bonusCount > 0) { ?> Они сгорят <b><?= date("d.m.Y", $lastDate + csSettings::get('BONUS_TIME') * 24 * 60 * 60) ?></b><? } ?><br /><br /> */ ?>
        <table class="noBorder"<? // style="border-collapse: separate" ?>>
            <tr>
              <td id="tableActShopOffBon" style="border-right: 3px solid #FFFFFF;">
              <? /*<span style="cursor: pointer; color: #c3060e;border-bottom: 2px dotted #c3060e;" onClick="
                if($('#actBonusOffShop').css('display')=='none')
                    $('.dopBlockActBon').each(function (i) {
                        $(this).fadeOut(0);
                    });
                $('.buttonActBon').each(function (i) {
                    $(this).css('font-weight', 'normal');
                });
                $('#actBonusOffShop').toggle();
                $('#tableActShopOnBon').css('background-color','#FFF');
                if($('#actBonusOffShop').css('display')=='none')
                    $('#tableActShopOnBon').css('background-color','#F1F1F1');
                $('#tableActShopOffBon').css('background-color','#F1F1F1')">Активировать бонусы</span> <br /><br /> Для покупателей оффлайн магазинов Он и Она в Москве.*/?>
              </td>
              <td id="tableActShopOnBon">
                  <?
                  $ordersNoBon = OrdersTable::getInstance()->createQuery()->where('customer_id=' . $sf_user->getGuardUser()->getId() . ' and created_at<\'' . date("Y-m-d", time() - 604800) . '\' and status not like \'%отмен%\' and status not like \'%Оплачен%\' and status not like \'%Возврат%\'')->execute();

                  foreach ($ordersNoBon as $key => $orderNoBon) {
                      $productsForBonus = unserialize($orderNoBon->getText());

                      $bonusAddUser = 0;

                      $bonuslog = BonusTable::getInstance()->createQuery()->where('comment like \'%Зачисление за заказ #' . $orderNoBon->getPrefix() . $orderNoBon->getId() . '%\'')->fetchOne();
                      unset($product, $artInfoForBonus);
                      foreach ($productsForBonus as $artForBonus => $artInfoForBonus) {//echo $stockId;exit;
                          if (isset($artInfoForBonus['article'])) {
                              $product = ProductTable::getInstance()->findOneByCode($artInfoForBonus['article']);
                          }
                          if (isset($artInfoForBonus['productId']) and !$product) {
                              $product = ProductTable::getInstance()->findOneById($artInfoForBonus['productId']);
                          }
                          //$product = ProductTable::getInstance()->findOneById($artInfoForBonus['product_id']);
                          if ($product) {
                              if ($product->getBonus() != 0) {
                                  $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $artInfoForBonus['quantity'] * $product->getBonus()) / 100);
                              } else {
                                  $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $artInfoForBonus['quantity'] * csSettings::get("persent_bonus_add")) / 100);
                              }
                          }
                      }
                      if (!$bonuslog) {
                          echo "<span style=\"cursor: pointer; color: #c3060e;border-bottom: 2px dotted #c3060e;\" onClick=\"
                          $('.dopBlockActBon').each(function (i) {
                           $(this).fadeOut(0);
                          });
                          $('.buttonActBon').each(function (i) {
                           $(this).css('font-weight', 'normal');
                          });

                          $('#actBonusOnShop-" . $key . "').toggle();
                              $('#actBonusOnShop-" . $key . "').find('input[name=datePay]').mask('99.99.9999');
                              $('#actBonusOnShop-" . $key . "').find('input[name=datePay]').val('ДД.ММ.ГГГГ');
                                  $('#tableActShopOffBon').css('background-color','#FFF');
                                  $('#tableActShopOnBon').css('background-color','#F1F1F1')
                                  $(this).css('font-weight', 'bold');
                          \" class='buttonActBon'>Активировать " . $bonusAddUser . " Бонусов за заказ #" . $orderNoBon->getPrefix() . $orderNoBon->getId() . "</span><br>";
                      }
                  }
                  ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" <?/*style="display: none;"*/?> id="actBonusOffShop" class="dopBlockActBon">
                Если вы совершили покупку в одном из наших оффлайн магазинов в Москве, то
                <span style="color: #C3060E;">ЧЕРЕЗ 24 часа ПОСЛЕ ПОКУПКИ</span> вы можете самостоятельно начислить себе
                бонусы в размере 50% от суммы оплаченного чека. Для этого в соответствующих полях укажите номер чека и сумму.
                <br><br>
                <?/*По тех. причинам покупатели магазина «Он и Она» по адресу г. Москва, м. Партизанская, Измайловское шоссе, д. 69Д не могут самостоятельно начислить бонусы через форму ниже, для начисления бонусов необходимо отправить номер и сумму чека (или отсканированный чек) на e-mail: svs@onona.ru. После этого наш менеджер начислит Вам бонусы в размере 50% от суммы оплаченного чека.

                <br><br><?*/
                ?>
                <form action="/customer/bonusshop" method="post" id="support" class="bonus-offline">
                  <img src="/images/checks2.png" style="">
                  <table calss="noBorder" class="tableRegister">
                    <tbody>
                      <tr>
                        <th style="width: 110px;"><label for="name">Номер чека*:</label></th>
                        <td><input type="text" name="checknum" value="" id="checknum"></td>
                        <td>Введите только цифры (верхний правый угол)</td>
                      </tr>
                    </tbody>

                    <tbody>
                      <tr>
                        <th><label for="email">Сумма чека:*</label></th>
                        <td><input type="text" name="summ" value="" id="summ"></td>
                        <td>Введите итоговую сумму до точки (без копеек)</td>
                      </tr>
                    </tbody>

                    <tbody>
                      <tr>
                        <th><label for="code">Укажите код:*</label></th>
                        <td>
                          <img border="0" style="float: left; margin-right: 10px;" src="/captcha/supportcaptcha.php?<?=session_name() . '=' . session_id() ?>">
                          <input type="text" style="width: 120px" name="code" value="" id="code"><br>
                          <?=$errorCap ? '<span style="color: red;">Ошибка. Попробуйте ещё раз.</span>' : ''?>
                        </td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                  * - поля, отмеченные * обязательны для заполнения.<br>
                  <a  style="margin-top: 20px;" class = "red-btn colorWhite" href = "#" onclick = "$('#support').submit(); return false;"><span style = "width: 195px;">Активировать бонусы</span></a>
                </form>
                <div style="clear: both"></div><br/>
                Если у вас возникли вопросы по начислению бонусов, вы можете получить консультацию: <br/>
                по тел: 8 800 700 98 85<br/>
                или e-mail: info@onona.ru
                <br /><br />
                <span
                  class="hide-form"
                  onClick="$('#actBonusOffShop').toggle();
                    $('#tableActShopOnBon').css('background-color','#F1F1F1');$('#tableActShopOffBon').css('background-color','#F1F1F1');">
                      скрыть
                </span>
              </td>
            </tr>
            <?
              $ordersNoBon = OrdersTable::getInstance()->createQuery()->where('customer_id=' . $sf_user->getGuardUser()->getId() . ' and created_at<\'' . date("Y-m-d", time() - 604800) . '\' and status not like \'%отмен%\' and status not like \'%Оплачен%\'')->execute();

              foreach ($ordersNoBon as $key => $orderNoBon) {
                $bonuslog = BonusTable::getInstance()->createQuery()->where('comment like \'%Зачисление за заказ #' . $orderNoBon->getPrefix() . $orderNoBon->getId() . '%\'')->fetchOne();
                if (!$bonuslog) {?>

                  <tr>
                    <td colspan="2" style="display: none;" id="actBonusOnShop-<?= $key ?>" class="dopBlockActBon">
                      Мы ценим и доверяем своим покупателям, поэтому предлагаем вам не ждать автоматического зачисления, а самостоятельно активировать свои бонусы за оплаченный заказ, для этого достаточно указать дату оплаты заказа и нажать на кнопку активировать.

                      <form action="/customer/bonusadd/<?= $orderNoBon->getId()?>" method="post" width="100%" id="bonusadd-<?=$key?>">
                        <table class="tableRegister noBorder form-activate">
                          <tbody>
                            <tr>
                              <th><label for="name">Дата оплаты:</label></th>
                              <td><input type="text" class="pay-date" name="datePay" value="" id="datePay" value="ДД.ММ.ГГГГ"></td>
                            </tr>
                          </tbody>

                          <tbody>
                            <tr>
                              <th><label for="code">Укажите код:</label></th>
                              <td>
                                <img border="0" style="float: left; margin-right: 10px;" src="/captcha/supportcaptcha.php?<?= session_name() . '=' . session_id() ?>">
                                <input type="text" class="cap-input" name="code" value="" id="code"><br>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <a class = "red-btn colorWhite" href = "#" onclick = "$('#bonusadd-<?= $key ?>').submit(); return false;">
                          <span style = "width: 195px;">Активировать бонусы</span>
                        </a>
                      </form>
                      <div style="clear: both"></div><br/>
                      Если у вас возникли вопросы по начислению бонусов, вы можете получить консультацию: <br/>
                      по тел: 8 800 700 98 85<br/>
                      или e-mail: info@onona.ru
                      <br /><br />
                      <span
                        class="hide-form"
                        onClick="$('#actBonusOnShop-<?= $key ?>').toggle();
                          $('.buttonActBon').each(function (i) {
                              $(this).css('font-weight', 'normal');
                          });
                          $('#tableActShopOnBon').css('background-color','#F1F1F1');$('#tableActShopOffBon').css('background-color','#F1F1F1');">скрыть</span>
                    </td>
                  </tr>
                  <?
                    //echo "Активировать ".$bonusAddUser." Бонусов за заказ #" . $orderNoBon->getPrefix() . $orderNoBon->getId() . "<br>";
                }
              } ?>
        </table>
        <br />
        <div id="HystoryBonus" style="display: none">
            <div style="color: #C3060E; font: 17px/21px Tahoma,Geneva,sans-serif;margin-bottom: 10px;">История начисления и списания бонусов:</div>
            <table style="width: 100%;"><tbody>
                    <?php foreach ($bonus as $bonusOne): ?>
                        <tr>
                            <td>
                                <?= $bonusOne->getCreatedAt(); ?>
                            </td>
                            <td>
                                <?= $bonusOne->getBonus(); ?>
                            </td>
                            <td>
                                <?= str_replace("Пробление", "Продление", $bonusOne->getComment()) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="/lk">Личный кабинет</a><br />
        <a href="/customer/myorders">Мои заказы</a><br />
        <a href="/compare">Список желаний</a><br />
        <a href="/customer/mydata">Мои данные</a><br />
    </div>
</section>
