<?
global $isTest;
$h1='Мои бонусы';
slot('breadcrumbs', [
  ['text' => 'Личный кабинет', 'link'=>'/lk'],
  ['text' => $h1],
]);
slot('h1', 'Программа лояльности "Он и Она Бонус"');
?>
<?/*<script type="text/javascript" src="/js/jquery.maskedinput-1.2.2.js"></script>*/?>
<main class="wrapper">
  <div class="lk-bonus-detail">
    <p>Компания «Он и Она» представляет специально разработанную программу для постоянных покупателей «Он и Она – Бонус», которая заключается в предоставлении вознаграждения за каждую покупку в интернет магазине OnOna.ru и любом магазине «Он и Она» в Москве.</p>
    <p>&nbsp;</p><div class="red"><strong>1 рубль = 1 Бонус.</strong></div><p>&nbsp;</p>
    <p>Это позволит вам совершать покупки в интернет магазине OnOna.ru <strong>со скидкой от 20 до 50%</strong> от суммы вашего заказа. Ведь каждая ваша покупка оплачивает следующую!</p>
    <div class="lk-bonus-detail-block">
      <div class="lk-bonus-detail-block-element">
        <div class="header">На вашем Бонусном счете:</div>

          <div class="big-number"><?= $bonusCount ?><span class="bonus-icon"></span></div>
          <div class="active-to">
            Срок действия бонусов до
            <span class="red"><?= date("d.m.Y", $lastDate + csSettings::get('BONUS_TIME') * 24 * 60 * 60) ?></span>
          </div>
        <div class="bottom-pink js-toggle-target" data-href="#history-bonus">Посмотреть историю бонусов >></div>
      </div>
      <div class="lk-bonus-detail-block-element spend">
        <div class="header">Перейти к покупкам:</div>
        <a href="/"  class="bottom-pink">
          Потратить бонусы сейчас >>
        </a>
      </div>
      <div class="lk-bonus-detail-block-element desc">
        <div class="header">Программа «Он и Она - Бонус»:</div>
        <a href="/programma-on-i-ona-bonus" class="bottom-pink">
          Ознакомиться с условиями >>
        </a>
      </div>

    </div>
    <div id="history-bonus" class="mfp-hide lk-bonus-detail-history">
        <div>История начисления и списания бонусов:</div>
        <table><tbody>
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
    <div class="lk-bonus-detail-forms">
      <p>Если вы совершили покупку в одном из наших оффлайн магазинов в Москве, то <span class="red">ЧЕРЕЗ 24 часа ПОСЛЕ ПОКУПКИ</span> вы можете самостоятельно начислить себе
      бонусы в размере 50% от суммы оплаченного чека. Для этого в соответствующих полях укажите номер чека и сумму.</p>
      <form action="/customer/bonusshop" method="post" class="lk-bonus-detail-forms-offline form js-ajax-form">
        <div class="element element-form">
          <div class="form-row">
            <div class="form-label">
              Номер чека*
            </div>
            <div class="input-row">
              <input type="text" name="checknum" value="">
              <div class="description">Введите только цифры (верхний правый угол)</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-label">
              Сумма чека:*
            </div>
            <div class="input-row">
              <input type="text" name="summ" value="">
              <div class="description">Введите итоговую сумму до точки (без копеек)</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-label">
              * - поля, обязательны для заполнения.
            </div>
            <div class = "but js-submit-button">Активировать бонусы</div>
          </div>
        </div>
        <div class="element element-picture">
          <img src="/images/checks2.png" >
        </div>
      </form>
    </div>
  </div>

  <?/*
    <div class="wrapwer customer-bonus">

                <table class="noBorder">
            <tr>
              <td id="tableActShopOffBon">

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
                          echo "<span onClick=\"
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

            </tr>
            <?
              $ordersNoBon = OrdersTable::getInstance()->createQuery()->where('customer_id=' . $sf_user->getGuardUser()->getId() . ' and created_at<\'' . date("Y-m-d", time() - 604800) . '\' and status not like \'%отмен%\' and status not like \'%Оплачен%\'')->execute();

              foreach ($ordersNoBon as $key => $orderNoBon) {
                $bonuslog = BonusTable::getInstance()->createQuery()->where('comment like \'%Зачисление за заказ #' . $orderNoBon->getPrefix() . $orderNoBon->getId() . '%\'')->fetchOne();
                if (!$bonuslog) {?>

                  <tr>
                    <td colspan="2" id="actBonusOnShop-<?= $key ?>" class="dopBlockActBon">
                      Мы ценим и доверяем своим покупателям, поэтому предлагаем вам не ждать автоматического зачисления, а самостоятельно активировать свои бонусы за оплаченный заказ, для этого достаточно указать дату оплаты заказа и нажать на кнопку активировать.

                      <form action="/customer/bonusadd/<?= $orderNoBon->getId()?>" method="post" id="bonusadd-<?=$key?>">
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
                                <img src="/captcha/supportcaptcha.php?<?= session_name() . '=' . session_id() ?>">
                                <input type="text" class="cap-input" name="code" value="" id="code"><br>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <a class = "red-btn colorWhite" href = "#" onclick = "$('#bonusadd-<?= $key ?>').submit(); return false;">
                          <span>Активировать бонусы</span>
                        </a>
                      </form>
                      <div></div><br/>
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

    </div>
  */?>
</main>
