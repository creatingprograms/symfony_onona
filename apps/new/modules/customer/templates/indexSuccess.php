<?
  global $isTest;
  $h1='Личный кабинет';
  slot('breadcrumbs', [
    ['text' => $h1],
  ]);
  slot('h1', $h1);
?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <div class="block-greeting">
        <div class="wrap">Здравствуйте, <?=$user->getGuardUser()->getFirstName() ?>, добро пожаловать в ваш личный кабинет. <br>
          Вы зарегистрированы в системе под логином <b><?=$user->getGuardUser()->getEmailAddress() ?></b></div>
        <div class="wrap-btn">
          <a href="/guard/logout" class="btn-full btn-full_white btn-full_rad">Выйти</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="wrap-block wrap-user-info">
  <div class="container">
    <div class="block-content">
      <div class="block-user-info">
        <div class="user-info-item">
          <div class="wrap">
            <h4 class="user-info__title h4"><span>Мои заказы</span></h4>
            <div class="user-info__content">
              <? if(!sizeof($orders)) :?>

                <?/*если нет заказов, то отобразить блок .user-info_no-order. А таблицу .lk-orders скрыть*/?>

                <div class="user-info_no-order">
                  <p>У Вас пока нет заказов</p>
                  <div class="wrap-btn">
                    <a href="/catalog" class="btn-full btn-full_rad">За покупками</a>
                  </div>
                </div>
              <? else :?>
                <?
                  $prefix=csSettings::get('order_prefix');
                ?>
                <table class="lk-orders" >
                  <thead>
                    <tr>
                      <th>№ заказа</th>
                      <th>Дата заказа</th>
                      <th>Товаров</th>
                      <th>Стоимость</th>
                      <th>Статус</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($orders as $order): ?>
                      <? $orderInfo=ILTools::getOrderInfo($order); ?>
                      <tr class="<?=$orderInfo['status-executed'] ? 'status-executed' : ''?>">
                        <td><a href="/customer/orderdetails/<?= $order->getId() ?>"><?= $prefix.$order->getId() ?></a></td>
                        <td><?= date('d.m.Y', strtotime($order->getCreatedAt())) ?></td>
                        <td><?= $orderInfo['quant'] ?></td>
                        <td><?= ILTools::formatPrice($orderInfo['price'] - (!$orderInfo['new_version'])*$order->getBonuspay())?></td>
                        <td>
                          <div class="status-order">
                            <svg>
                              <use xlink:href="#status-<?=$orderInfo['icon']?>"></use>
                            </svg>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              <? endif ?>
            </div>
          </div>
        </div>

        <div class="user-info-item">
          <div class="wrap">
            <h4 class="user-info__title h4"><span>Мои данные</span></h4>
            <div class="user-info__content">
              <div class="col">
                <div class="user-info__name"><?=$user->getGuardUser()->getFirstName() ?></div>
              </div>
              <div class="col">
                <div><?=$user->getGuardUser()->getEmailAddress() ?></div>
                <div><span>тел.</span> <span><?=substr_replace($user->getGuardUser()->getPhone(), '*** ** ', 7, 7)?></span></div><?/*+7 928 *** ** 89*/?>
              </div>
              <div class="wrap-btn">
                <a href="/customer/mydata" class="btn-full btn-full_white btn-full_rad">Изменить</a>
              </div>
            </div>
            <div class="user-info__content">
              <p>В разделе <a href="/newprod">«Новинки»</a> Вы сможете увидеть новые товары, добавленные за время Вашего отсутствия, и всегда быть в курсе последних брендов. <br>
                <?/*В разделе <a href="#">«Лучшая цена»</a> Вы сможете приобрести товары по самым низким ценам в рунете. <br>*/?>
                Если у Вас есть вопросы о работе OnOna.ru - познакомьтесь с разделом <a href="/kak-sdelat-zakaz">«Как сделать заказ»</a>.</p>
              <p>Не забывайте, что компания «Он и Она» осуществляет БЕСПЛАТНУЮ ДОСТАВКУ, <a href="/dostavka">подробнее о доставке</a>.</p>
              <p>Мы осуществляем самую <a href="/dostavka">выгодную доставку</a> в Ваш город по наилучшей цене.</p>
              <div class="wrap-btn">
                <a href="/catalog" class="btn-full btn-full_rad">За покупками</a>
              </div>
            </div>
          </div>
        </div>

        <div class="user-info-item">
          <div class="wrap">
            <h4 class="user-info__title h4"><span>Мои бонусы</span> <a href="/programma-on-i-ona-bonus">Подробнее</a></h4>
            <div class="user-info__content">
              <div class="user-info__bonus h1"><?=ILTools::formatPrice($bonusCount)?> <?= ILTools::getWordForm($bonusCount, 'бонус', true)?></div>
              <div class="history-order">
                <div class="history-order__title">История бонусов:</div>
                <table class="history-order-table">
                  <tbody>
                    <?php foreach ($bonus as $bonusOne): ?>
                      <? if(!$bonusOne->getBonus()) continue;?>
                      <tr>
                        <td><?= date('d.m.Y', strtotime($bonusOne->getCreatedAt())) ?></td>
                        <td><?= ILTools::formatPrice($bonusOne->getBonus()) ?></td>
                        <td><?= str_replace("Пробление", "Продление", $bonusOne->getComment()) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="wrap-btn">
            <a href="#" class="btn-full btn-full_gray btn-bonus-js">Активировать бонусы</a>
          </div>
          <div class="wrap wrap-activation-bonuses">
            <div class="user-info__content">
              <p>Если вы совершили покупку в одном из наших оффлайн магазинов в Москве, то <span style="color: #EF182F;">через 24 часа после покупки</span>
                вы можете самостоятельно начислить себе бонусы
                в размере 50% от суммы оплаченного чека. Для этого
                в соответствующих полях укажите номер чека и сумму.</p>
              <div class="element element-picture">
                <a href="/images/checks2.png" class="js-image-popup">
                  <img src="/images/checks2.png">
                </a>
              </div>
              <form action="/customer/bonusshop" class="form form-bonus js-ajax-form">
                <div class="field field-default">
                  <label for="">Номер чека</label>
                  <input type="text" name="checknum" placeholder="Введите только цифры (верхний правый угол)">
                </div>
                <div class="field field-default">
                  <label for="">Сумма чека</label>
                  <input type="text" name="summ" placeholder="Введите итоговую сумму до точки (без копеек)">
                </div>
                <div class="wrap-btn">
                  <button class="btn-full btn-full_rad js-submit-button">Активировать</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="user-info-item user-info_no-border">

        </div>
      </div>
    </div>
  </div>
</div>
