<?
$h1 = 'Корзина';
slot('caltat', 2);
slot('breadcrumbs', [
  ['text' => $h1],
]);
$isHaveEvroset = false;
global $isTest;
slot('hide_mess', true);
slot('catalog-class', 'page-basket');
slot('h1', $h1);
$notAddDelivery = true;
// echo '<pre>'.print_r([$bonusCount,/*reset(*/ $products_old/*)*/], true).'</pre>';
slot('tinkoff', true);
$commonPercent = $percentBase;
$totalBonus = $totalCost = $totalCostWODiscount = $screenPosition = $bonusAllowed = 0;
$screenPosition = 0;
$deliveryLimit = csSettings::get('free_deliver');
$percentToAdd = csSettings::get("persent_bonus_add");
$viaShopId = "7895165b-ac0b-476a-bec7-ebb1d3ad1814";
?>

<? if (is_array($products_old) && count($products_old) > 0) : ?>
  <div class="wrap-block wrap-block-features" id="basketForJs" data-via-shop="<?= $viaShopId ?>">
    <div class="container">
      <form action="/cart/confirmed_check" class="js-ajax-form gtm-checkout-one" id="basketForm" method="post" data-freedelivery="<?= $deliveryLimit ?>" data-freedelivery-online-moscow="<?= csSettings::get('online_delivery_free_moscow') ?>" data-freedelivery-online-russia="<?= csSettings::get('online_delivery_free_russia') ?>" data-bonusTopLimit="<?= $bonusTopLimit ?>" data-bonusMinLimit="<?= $bonusMinLimit ?>">
        <input type="hidden" name="region-fias-id" value="<?= !$user ? '' : $user->getRegionFiasId() ?>" id="region-fias-id">
        <input type="hidden" name="lat" value="0" id="lat">
        <input type="hidden" name="lon" value="0" id="lon">
        <input type="hidden" name="coupon-sum-discount" value="0" id="coupon-sum-discount">
        <input type="hidden" name="sf_guard_user[_csrf_token]" value="<?php echo $csrf ?>">
        <div class="block-content">
          <div class="wrap-product-features width_min">
            <div class="wrap-basket-item">
              <?php foreach ($products_old as $key => $productInfo) : ?>
                <?
                $product = $products[$key];
                $productKeys[] = $product->getId();
                $photoalbum = $product->getPhotoalbums();
                $isExpress = $product->getIsExpress();
                $isExpressPropStr = 'data-is-express="'.($isExpress ? 'true' : 'false').'"';
                $image = false;
                if (is_object($photoalbum[0])) {
                  $photos = $photoalbum[0]->getPhotos();
                  if (isset($photos[0])) $image = '/uploads/photo/thumbnails_250x250/' . $photos[0]->getFilename();
                }
                if (!$image) $image = '/frontend/images/no-photo_250.jpg';
                $productBonus = $productInfo['quantity'] * round(0.01 * $product->getPrice() * ($product->getIsBonusEnabled() ? ($product->getBonus() != 0 ? $product->getBonus() :  $percentToAdd) : 0));
                $totalBonus += $productBonus;
                //добавляем к разрешенным для списания бонусам только товары без скидки
                $bonusAllowed += ($productInfo['price_w_discount'] == $productInfo['price'] && $productInfo['percentbonuspay']) * $productInfo['quantity'] * $productInfo['price'];
                // echo $productInfo['price_w_discount'].'|'.$productInfo['price'].'|'.$productInfo['quantity'].'|'. $productInfo['price'].'|'.$bonusAllowed."<br>";
                $totalCost += $productInfo['price_w_discount'] * $productInfo['quantity'];
                $totalCostWODiscount += $productInfo['price'] * $productInfo['quantity'];
                if (!$product->getIsNotadddelivery()) $notAddDelivery = false;
                ?>
                <div class="basket-item" data-id="<?= $key ?>" id="basket-item-<?= $key ?>" data-init-price="<?= $productInfo['price_w_discount'] ?>">
                  <div class="col">
                    <div class="basket-item__img"><img src="<?= $image ?>" alt=""></div>
                  </div>
                  <div class="col">
                    <div class="row-col">
                      <div class="basket-item__info">
                        <div class="basket-item__article">
                          <span>Артикул: </span><span>№ <?= $product->getCode() ?></span>
                        </div>
                        <div class="basket-item__title"><?= $product->getName() ?></div>
                        <?/*<a href="/product/<?= $product->getSlug()?>" target="_blank" class="basket-item__title"><?= $product->getName()?></a>*/ ?>
                        <div class="purchase-card__item -status"><span><?= $product->getCount() > 0 ? 'В наличии' : 'Под заказ' ?></span>
                          <? if ($stock[$key]) : ?>
                            <a href="/adresa-magazinov-on-i-ona-v-moskve-i-mo">в <?= ILTools::getWordForm($stock[$key], 'магазин') ?></a>
                          <? endif ?>
                        </div>
                      </div>
                      <div class="field-number">
                        <span class="number-min gtm-decrement-in-cart">
                          <svg>
                            <use xlink:href="#minus-i"></use>
                          </svg>
                        </span>
                        <input type="number" name="" min="1" <?/*id=""*/ ?> value="<?= $productInfo['quantity'] ?>" class="gtm-basket-item-count js-product-row gtm-checkout-item" data-bonus-max-percent="<?= $product->getIsBonusEnabled() ? ($product->getBonus() != 0 ? $product->getBonus() :  $percentToAdd) : 0 ?>" data-id="<?= $key ?>" data-name="<?= $product->getName() ?>" data-position="<?= $screenPosition++ ?>" data-category="<?= $product->getGeneralCategory() ?>" data-discount-not-apply="<?= in_array($key, $discountNotApply) ? 1 : 0 ?>" data-weight="<?= $product->getWeight() ?>" data-price="<?= $productInfo['price_w_discount'] ?>" data-price-wo="<?= $productInfo['price'] ?>" data-sum="<?= $productInfo['price_w_discount'] * $productInfo['quantity'] ?>" <?php echo $isExpressPropStr; ?> >
                        <span class="number-plus gtm-increment-in-cart">
                          <svg>
                            <use xlink:href="#plus-i"></use>
                          </svg></span>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="product-item__price" <?= $product->getDiscount() ? 'old-price="' . ILTools::formatPrice($product->getOldPrice()) . ' ₽"' : '' ?>><?= ILTools::formatPrice($product->getPrice()) ?> ₽</div>
                    <? if ($productBonus) : ?>
                      <div class="purchase-card__bonus">+<span><?= ILTools::formatPrice($productBonus) ?></span> <?= ILTools::getWordForm($productBonus, 'бонус', true) ?> на счет</div>
                    <? endif ?>
                    <div class="btn-basket-del js-basket-delete gtm-delete-from-chart">
                      <svg>
                        <use xlink:href="#basket-del-i"></use>
                      </svg>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
              <? //die('----------------');
              ?>
            </div>
            <div data-retailrocket-markup-block="63ef8d7fe801b5b9248bd93f" data-products="<?= implode(',', array_keys($products_old)) ?>"></div>
            <? $deliveryCost = !$notAddDelivery * ($deliveryLimit > $totalCost ? 290 : 0); //0 если не добавляем доставку
            ?>

            <input type="hidden" value="<?= $deliveryCost ?>" name="deliveryPriceSend" id="deliveryPriceSend">
            <input type="hidden" value="<?= $notAddDelivery ?>" name="not-add-delivery" id="not-add-delivery">

            <div class="purchase-card purchase-card_basket purchase-card_fixed ">
              <div class="wrap">

                <div class="purchase-card__info">
                  <span>Доставка</span>
                  <span>
                    <span style="display:inline;" class="js-delivery-price"><?= $deliveryCost ?></span>
                    ₽
                  </span>
                </div>
                <div class="purchase-card__info">
                  <span>Итоговая стоимость</span>
                  <div class="product-item__price" <?= $totalCost != $totalCostWODiscount ? 'old-price="' . ILTools::formatPrice($totalCostWODiscount) . ' ₽"' : '' ?>>
                    <span class="js-total-cost" style="display: inline;"><?= ILTools::formatPrice($totalCost + $deliveryCost) ?></span>
                    ₽
                  </div>
                  <? if ($totalBonus) : ?>
                    <div class="purchase-card__bonus">+
                      <span style="display:inline;" class="js-bonus-add"><?= ILTools::formatPrice($totalBonus) ?></span>
                      <?= ILTools::getWordForm($productBonus, 'бонус', true) ?> на счет
                    </div>
                  <? endif ?>
                </div>
                <div class="purchase-card__info mfp-hide js-online-discount-block">
                  <span>Скидка за выбор Pickpoint</span>
                  <span>
                    <div style="display: inline;" class="js-online-discount">0</div> ₽ (2%)
                  </span>
                </div>
                <div class="purchase-card__info <?= $totalCost == $totalCostWODiscount ? 'mfp-hide' : '' ?> js-discount-block">
                  <span>Ваша выгода:</span>
                  <span>
                    <div style="display: inline;" class="js-discount"><?= $totalCostWODiscount - $totalCost ?></div> ₽
                  </span>
                </div>
              </div>
              <div class="wrap">
                <div class="wrap-btn">
                  <a href="#" class="btn-full btn-full_rad js-target-to" data-target="#delivery-block">Перейти к оформлению</a>
                </div>
              </div>
            </div>
            <?php if (!$sf_user->isAuthenticated()) : //Блок авторизации/регистрации
            ?>
              <div class="wrap-sidebar-info">
                <div class="sidebar-info">
                  <div class="sidebar-info__title">Хотите дешевле?</div>
                  <div class="sidebar-info__text">Зарегистрируйтесь и получите <?= csSettings::get('register_bonus_add') ?> приветственных бонусов, которыми вы сможете оплатить свой заказ прямо сейчас.</div>
                  <div class="wrap-btn">
                    <a href="#popup-reg" class="btn-full btn-full_white btn-full_rad js-popup-form">Регистрация</a>
                  </div>
                </div>
                <div class="sidebar-info">
                  <div class="sidebar-info__title">Постоянный покупатель?</div>
                  <div class="sidebar-info__text">Возможно, на вашем счете уже есть Бонусы на оплату <?= csSettings::get('PERSENT_BONUS_PAY') ?>% от стоимости заказа.</div>
                  <div class="wrap-btn">
                    <a href="#popup-login" class="btn-full btn-full_white btn-full_rad js-popup-form">Войти в кабинет</a>
                  </div>
                </div>
              </div>
            <? endif ?>

            <? include_component("page", "banner", array('type' => 'Корзина', 'sf_cache_key' => 'banner-basket', 'not_wrap' => true, 'add_class' => 'block-info_catalog')); ?>

            <div class="purchase-steps">
              <div class="purchase-step">
                <h4 class=" h4 purchase-step__title" id="delivery-block">Шаг 1. Выберите способ получения</h4>
                <div class="purchase-step__content">
                  <?/* <p>Выберите способ доставки <strong>PickPoint</strong>, получите скидку в размере <strong class="red">2%</strong></p> */?>
                  <p>&nbsp;</p>
                  <div class="features-tabs tabs">
                    <? if (sizeof($delivers)) : ?>
                      <div class="features-tabs__title tabs__title tabs__title-delivery">
                        <?
                        $i = 1;
                        $needShowPvz = true;
                        $strPVZ = '';
                        foreach ($delivers as $delivery) {
                          if (!$delivery->getIsPvz()) continue;
                          $strPVZ .=
                            '<label for="delivery-' . $delivery->getId() . 'p">' .
                            '<input type="radio" name="deliveryId" value="' 
                              . $delivery->getId() . '" id="delivery-' . $delivery->getId() 
                              . 'p" class="js-delivery '
                              . ($delivery->getId() == 21 ? 'js-yandex-send' : '')
                              . '" data-free-from="' . $delivery->getFreeFrom() 
                              . '" data-free-from-online="' . $delivery->getFreeFromOnline() 
                              . '" data-free-from-online-moscow="' . $delivery->getFreeFromOnlineMoscow() . '"'
                              . ($delivery->getId() == 21 ? 'data-target="cart-pyaterochka-yandeks" ' : '')
                              . '>' .
                            '' . $delivery->getName() . '<br>' .
                            '</label>' .
                            '';
                        }
                        ?>
                        <?php foreach ($delivers as $key => $delivery) : ?>
                          <? if (!$isYandexEnable && $delivery->getId() == 15) continue; ?>
                          <? if (!$isYandexNewEnabled && $delivery->getId() == 16) continue; ?>
                          <? if (!$needShowPvz && $delivery->getIsPvz()) continue; ?>
                          <? if ($needShowPvz && $delivery->getIsPvz()) : ?>
                            <? $needShowPvz = false; ?>
                            <div class="features-tab__title delivery-icon-<?= $delivery->getId() ?> features-tab__title_pvz">
                              Постаматы&nbsp;и&nbsp;ПВЗ<br>
                              <?= $strPVZ ?>
                            </div>
                          <? else : ?>
                            <label class="features-tab__title <?= !$i ? 'active' : '' ?><?= $delivery->getId() == 10 ? ' js-shop-init' : '' ?> delivery-icon-<?= $delivery->getId() ?>" for="delivery-<?= $key ?>" <?= $delivery->getId() == 10 ? ' data-dont-stop="1"' : '' ?>>
                              <input type="radio" name="deliveryId" value="<?= $delivery->getId() ?>" id="delivery-<?= $key ?>" class="js-delivery" <?= !$i++ ? 'checked' : '' ?> data-free-from="<?= $delivery->getFreeFrom() ?>" data-free-from-online="<?= $delivery->getFreeFromOnline() ?>" data-free-from-online-moscow="<?= $delivery->getFreeFromOnlineMoscow() ?>">
                              <?php if ($delivery->getId() <> 16) : ?>
                                <?= $delivery->getName() ?>
                              <?php endif; ?>
                              <span><?= $delivery->getDescription() ?></span>
                            </label>
                          <? endif ?>

                        <?php endforeach; ?>
                      </div>
                      <div class="features-tabs__content tabs__content">
                        <? $i = 1; ?>
                        <?php foreach ($delivers as $key => $delivery) : ?>
                          <? if (!$isYandexEnable && $delivery->getId() == 15) continue; ?>
                          <? if (!$isYandexNewEnabled && $delivery->getId() == 16) continue; ?>
                          <div class="features-tab__content tab__content <?= !$i++ ? 'active' : '' ?>" id="tab_content-<?= $delivery->getId() ?>">
                            <div class="address" id="address-<?= $delivery->getId() ?>"></div><br>
                            <? if ($delivery->getId() == 11) : // PickPoint
                            ?>
                              <? slot('js-pickpoint', true); ?>
                              <div class="drh-shutter btn-full btn-full_rad js-pickpoint-init">Выбрать точку доставки</div><br>
                              <input type="hidden" name="pickpoint_id" id="pickpoint_id">
                              <input type="hidden" name="pickpoint_address" id="pickpoint_address" value="" />
                            <? endif ?>
                            <? if ($delivery->getId() == 14) : // Евросеть
                            ?>
                              <? slot('js-euroset', true);
                              $isHaveEvroset = true; ?>
                              <? slot('js-yandex', true); ?>
                              <div class="drh-shutter btn-full btn-full_rad js-dlh-init">Выбрать точку доставки</div><br>
                              <input type="hidden" name="dlh_shop_id" id="dlh_shop_id" value="" />
                              <input type="hidden" name="dlh_address_line" id="dlh_address_line" value="" />
                            <? endif ?>
                            <? if ($delivery->getId() == 10) : // Самовывоз
                            ?>
                              <div class="drh-shutter btn-full btn-full_rad js-shop-init">Выбрать пункт самовывоза</div><br>
                            <? endif ?>
                            <? if ($delivery->getId() == 17) : // Ozon
                            ?>
                              <div class="drh-shutter btn-full btn-full_rad js-ozon-init">Выбрать точку доставки</div><br>
                              <input type="hidden" name="ozon_id" id="ozon_id" value="" />
                              <input type="hidden" name="ozon_address" id="ozon_address" value="" />
                              <? $isHaveOzon = true;  ?>
                            <? endif ?>
                            <? if ($delivery->getId() == 18) : // Почта России постаматы
                            ?>
                              <div class="drh-shutter btn-full btn-full_rad js-post-postamat">Выбрать точку доставки</div><br>
                              <input type="hidden" name="post_id" id="post_id" value="" />
                              <input type="hidden" name="post_address" id="post_address" value="" />
                              <? $isHavePost = true;  ?>
                            <? endif ?>
                            <? if ($delivery->getId() == 19) : // СДЭК
                            ?>
                              <div class="drh-shutter btn-full btn-full_rad js-sdek">Выбрать точку доставки</div><br>
                              <input type="hidden" name="sdek_id" id="sdek_id" value="" />
                              <input type="hidden" name="sdek_city_id" id="sdek_city_id" value="" />
                              <input type="hidden" name="sdek_tariff" id="sdek_tariff" value="" />
                              <input type="hidden" name="sdek_address" id="sdek_address" value="" />
                              <? $isHaveSdek = true;  ?>
                            <? endif ?>
                            <? if ($delivery->getId() == 20) : // Сбер
                            ?>
                              <p><strong>Внимание!</strong> Для корректного отображения карты необходимо сначала выбрать город <a href="#city_selector">ниже</a></p><br>
                              <a href="#" data-role="shiptor_widget_show" class="drh-shutter btn-full btn-full_rad js-sber">Выбрать точку доставки</a>
                              <!-- <br> -->
                              <input type="hidden" name="sber_id" id="sber_id" value="" />
                              <input type="hidden" name="sber_address" id="sber_address" value="" />
                              <? $isHaveSber = true;  ?>
                            <? endif ?>
                            <? if ($delivery->getId() == 21) : // VIA
                            ?>

                              <input type="hidden" name="via_id" id="via_id" value="" />
                              <input type="hidden" name="via_address" id="via_address" value="" />
                              <?
                                $zoom = 10;
                                $strCoords = '&lat=55.7558&lng=37.6173';
                                $strCity = (is_object($user) && $user->getCity()) ? '&address='.$user->getCity(). ' ' . $user->getStreet() : '';
                                if(!empty($strCity)) $strCoords = '';

                                $viaSrc =
                                  '<iframe id="map-via" class="via-delivery" src="https://widget.viadelivery.pro/via.maps/'.
                                    '?dealerId=' . $viaShopId.
                                    '&orderCost=' . $totalCost.
                                    '&zoom=' . $zoom .
                                    $strCity.
                                    $strCoords.
                                    '&action=true&lang=ru&orderWeight=1" width="100%" frameborder="0">'.
                                  '</iframe>';
                              ?>

                              <?= $viaSrc ?>

                            <? endif ?>

                            <?= $delivery->getContent() ?>

                            <? if ($delivery->getId() == 10) : // Самовывоз
                            ?>
                              <input type="hidden" name="shop_id" id="shop_id" value="" />
                              <input type="hidden" name="shop_address_line" id="shop_address_line" value="" />
                              <? include_component('page', 'shopsDelivery', array('sf_cache_key' => "shopsDelivery")); ?>
                            <? endif ?>
                          </div>
                        <?php endforeach; ?>
                        <? if ($isHaveEvroset) : ?>
                          <div id="drh-container" class="drh-container">
                            <div class="drh-container-back"></div>
                            <div id="map" class="drh-map-container"></div>
                          </div>
                        <? endif ?>
                        <? if ($isHaveOzon) : ?>
                          <div id="ozon-container" class="mfp-hide">
                          </div>
                        <? endif ?>
                        <? if ($isHavePost) : ?>
                          <? slot('js-russian-post', true); ?>
                          <div class="js-post-wrapper mfp-hide">
                            <div id="ecom-widget" class="russian-post-widget">
                            </div>
                          </div>
                        <? endif ?>
                        <? if ($isHaveSdek) : ?>
                          <? slot('js-sdek', true); ?>
                          <div class="js-sdek-wrapper mfp-hide">
                            <div id="sdek-widget" class="russian-post-widget">
                            </div>
                          </div>
                        <? endif ?>
                        <? if ($isHaveSber) : ?>
                          <script type="text/javascript" src="https://widget.shiptor.ru/embed/widget-pvz.js"></script>

                          <div id="shiptor_widget_pvz" class="_shiptor_widget _sbl_widget" data-cod="0" data-declaredCost="<?= $totalCost ?>" data-mode="inline" data-link-maps="1"></div>
                        <? endif ?>
                      </div>
                    <? endif ?>
                  </div>
                </div>
              </div>

              <div class="purchase-step">
                <h4 class=" h4 purchase-step__title" id="payment-block">Шаг 2. Выберите способ оплаты</h4>
                <div class="purchase-step__content" id="payment-body">
                  <div class="block-payment">
                    <p>&nbsp;&nbsp;&nbsp;&nbsp;Выберите способ доставки</p>
                  </div>
                </div>
              </div>

              <div class="purchase-step">
                <h4 class=" h4 purchase-step__title">Шаг 3. Данные получателя</h4>
                <span class="purchase-step__title-sub">Введите контактные данные</span>
                <div class="purchase-step__content">
                  <div class="wrap-info-user">
                    <div class="info-user">
                      <div class="field field-default field-name">
                        <label for="">Как вас зовут</label>
                        <input type="text" name="first_name" placeholder="Полное ФИО" value="<?= !$user ? $_GET['username'] : $user->getFirstName() ?>">
                      </div>

                      <div class="field field-default field-date">
                        <label for="">Дата рождения</label>
                        <?
                        if (!$user)
                          $birthdate = '';
                        else {
                          $birthdate = implode('.', array_reverse(explode('-', $user->getBirthday())));
                          if ($birthdate == '00.00.0000') $birthdate = '';
                          $sex = !$user ? '' : $user->getSex();
                        }
                        ?>
                        <input type="date" placeholder="01.01.1980" value="<?= $birthdate ?>">
                      </div>

                      <div class="group-field group-field_gender">
                        <label for="">Пол</label>
                        <div class="custom-check custom-check_circle ">
                          <div class="check-check_block">
                            <input type="radio" name="sex" id="gender-g" value="g" class="check-check_input" <?= $sex == 'g' ? ' selected' : '' ?>>
                            <div class="custom-check_shadow"></div>
                          </div>
                          <label for="gender-g" class="custom-check_label">Ж</label>
                        </div>
                        <div class="custom-check custom-check_circle">
                          <div class="check-check_block">
                            <input type="radio" value="m" name="sex" id="gender-m" class="check-check_input" <?= $sex == 'm' ? ' selected' : '' ?>>
                            <div class="custom-check_shadow"></div>
                          </div>
                          <label for="gender-m" class="custom-check_label">М</label>
                        </div>
                      </div>

                      <div class="field field-default field-tel">
                        <label for="">Телефон для связи</label>
                        <input class="js-phone-mask" name="phone" type="tel" placeholder="+7 (___) ___-__-__" value="<?= !$user ? $_GET['phone']  : $user->getPhone() ?>">
                      </div>

                      <div class="field field-default field-email">
                        <label for="">Электронная почта</label>
                        <input class="js-rr-send" type="email" name="user_mail" value="<?= !$user ? '' : $user->getEmailAddress() ?>" placeholder="ivanov@mail.com" <?= !$user ? '' : ' readonly' ?>>
                      </div>

                      <div class="field field-default field-cooment hide-textarea">
                        <div class="btn-add-comment">
                          <svg>
                            <use xlink:href="#plus-i"></use>
                          </svg>
                          <span>Комментарий к заказу</span>
                        </div>
                        <div class="block-textarea">
                          <label>Комментарий к заказу</label>
                          <textarea name="comments" id="" cols="30" rows="4"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="info-user js-no-address-need js-delivery-calc" id="city_selector">
                      <div class="field field-default field-city">
                        <?
                        if ($user && $user->getUpdatedAt() < '2020-10-05 12:00:12') $notShowCity = true;
                        else $notShowCity = false;
                        ?>
                        <label for="">Город</label>
                        <input type="text" placeholder="" name="city" value="<?= (!$user || $notShowCity) ? '' : $user->getCity() ?>" placeholder="" id="city" class="js-dadata">
                      </div>
                      <div class="field field-default field-street">
                        <label for="">Улица</label>
                        <input type="text" placeholder="" name="street" value="<?= !$user ? '' : $user->getStreet() ?>" placeholder="" id="street" class="js-dadata">
                      </div>
                      <div class="field field-default field_house">
                        <label for="">Дом</label>
                        <input type="text" placeholder="" name="house" value="<?= !$user ? '' : $user->getHouse() ?>" id="house" class="js-dadata">
                      </div>
                    </div>
                    <div class="info-user-bonus" id="info-user-bonus">
                      <? if ($isCouponsEnabled) : ?>
                        <div class="wrap-field-input">
                          <label>У меня есть промокод</label>
                          <div class="field">
                            <input type="text" placeholder="Например, SALE2021" name="coupon">
                            <button class="btn link-fill -red js-coupon-apply">Применить</button>
                          </div>
                        </div>
                      <? endif ?>
                      <div class="wrap-field-range <?= $bonusCount && $bonusAllowed && $sf_user->isAuthenticated() ? '' : 'mfp-hide not-show' ?>">
                        <label>Списать баллы с бонусного счета</label>
                        <div class="wrap-slider-range">
                          <div class="custom-slide-range">
                            <input type="text" class="slide-range-input js-bonus-count" value="0" name="bonus">
                            <div class="max-count-range">из <span></span></div>
                          </div>
                          <div class="slider-range-max" max="<?= $bonusCount ? ($bonusCount > $percentBase * $bonusAllowed / 100 ? floor($percentBase * $bonusAllowed / 100) : $bonusCount) : 0 ?>" min="0" percentBase="<?= $percentBase ?>"></div>
                        </div>
                      </div>
                    </div>

                    <div class="info-user__note">
                      <div class="custom-check">
                        <div class="check-check_block">
                          <input type="checkbox" name="agreement-18" value="1" id="basketConfirm1" checked class="check-check_input">
                          <div class="custom-check_shadow"></div>
                        </div>
                        <label for="basketConfirm1" class="custom-check_label">Подтверждаю, что мне более 18 лет</label>
                      </div>
                      <div class="custom-check">
                        <div class="check-check_block">
                          <input type="checkbox" name="agreement" class="check-check_input" value="1" id="basketConfirm2" checked>
                          <div class="custom-check_shadow"></div>
                        </div>
                        <label for="basketConfirm2" class="custom-check_label">Я принимаю условия <a href="/personal_accept" target="_blank">Пользовательского соглашения</a></label>
                      </div>
                    </div>

                    <div class="wrap-btn wrap-btn-mob">
                      <button class="btn-full btn-full_rad js-submit-button js-no-tinkoff">Оформить</button>
                      <a href="#" class="btn-full btn-full_rad btn-full_yellow js-tinkoff js-submit-button mfp-hide">Купить в кредит</a>
                    </div>


                  </div>
                </div>
              </div>
            </div>

          </div>
          <aside class="product-sidebar">
            <div class="purchase-card purchase-card_basket">
              <div class="wrap">
                <div class="purchase-card__info">
                  <span>Стоимость товаров в корзине</span>
                  <div class="product-item__price js-items-in-cart" data-bonus-add-percent="<?= $percentToAdd ?>" data-price-for-delivery="<?= $totalCost ?>" data-coupon="0" data-sum="<?= $totalCost ?>" <?= $totalCost != $totalCostWODiscount ? 'old-price="' . ILTools::formatPrice($totalCostWODiscount) . ' ₽"' : '' ?>>
                    <div style="display: inline;" class="js-total"><?= ILTools::formatPrice($totalCost) ?></div>
                    ₽
                  </div>
                  <? if ($totalBonus) : ?>
                    <div class="purchase-card__bonus">+<div style="display: inline;" class="js-bonus-add"><?= ILTools::formatPrice($totalBonus) ?></div> <?= ILTools::getWordForm($productBonus, 'бонус', true) ?> на счет</div>
                  <? endif ?>
                </div>
                <div class="purchase-card__info">
                  <span>Доставка</span>
                  <span>
                    <div style="display: inline;" class="js-delivery-price"><?= $deliveryCost ?></div> ₽
                  </span>
                </div>
                <div class="purchase-card__info mfp-hide js-online-discount-block">
                  <span>Скидка за выбор Pickpoint</span>
                  <span>
                    <div style="display: inline;" class="js-online-discount">0</div> ₽ (2%)
                  </span>
                </div>
                <div class="purchase-card__info <?= $totalCost == $totalCostWODiscount ? 'mfp-hide' : '' ?> js-discount-block">
                  <span>Ваша выгода:</span>
                  <span>
                    <div style="display: inline;" class="js-discount"><?= $totalCostWODiscount - $totalCost ?></div> ₽
                  </span>
                </div>
                <div class="purchase-card__info js-bonus-container mfp-hide">
                  <span>Списано бонусов</span>
                  <span class="js-bonus-view">0</span>
                </div>
              </div>
              <div class="wrap">
                <div class="purchase-card__info -result">
                  <span>Итоговая стоимость</span>
                  <span><span style="display: inline;" class="js-total-cost"><?= ILTools::formatPrice($totalCost + $deliveryCost) ?></span> ₽ </span>
                </div>
                <div class="wrap-btn">
                  <a href="#" class="btn-full btn-full_rad js-submit-button js-no-tinkoff">Оформить заказ</a>
                  <a href="#" class="btn-full btn-full_rad btn-full_yellow js-tinkoff js-submit-button mfp-hide">Купить в кредит</a>
                </div>
              </div>
            </div>
            <?php if (!$sf_user->isAuthenticated()) : //Блок авторизации/регистрации
            ?>
              <div class="sidebar-info">
                <div class="sidebar-info__title">Хотите дешевле?</div>
                <div class="sidebar-info__text">Зарегистрируйтесь и получите <?= csSettings::get('register_bonus_add') ?> приветственных бонусов, которыми вы сможете оплатить свой заказ прямо сейчас.</div>
                <div class="wrap-btn">
                  <a href="#popup-reg" class="btn-full btn-full_white btn-full_rad js-popup-form">Регистрация</a>
                </div>
              </div>
              <div class="sidebar-info">
                <div class="sidebar-info__title">Постоянный покупатель?</div>
                <div class="sidebar-info__text">Возможно, на вашем счете уже есть Бонусы на оплату <?= csSettings::get('PERSENT_BONUS_PAY') ?>% от стоимости заказа.</div>
                <div class="wrap-btn">
                  <a href="#popup-login" class="btn-full btn-full_white btn-full_rad js-popup-form">Войти в кабинет</a>
                </div>
              </div>
            <? endif ?>
          </aside>
        </div>
      </form>
      <!-- rr -->

    </div>
  </div>
  <div class="wrap-block wrap-block-features">
    <!-- <div class="container"> -->
    <div data-retailrocket-markup-block="5ba3a64397a52530d41bb248" data-products="<?= implode(',', array_keys($products_old)) ?>"></div>
    <!-- </div> -->
  </div>
<? else : ?>
  <div class="wrap-block">
    <div class="container">
      <p class="lead-text">Ваша корзина пуста<br>Нажмите <a style="color: red;" href="/catalog">здесь</a>, чтобы продолжить покупки</p>
    </div>
    <div data-retailrocket-markup-block="5ba3a65797a52530d41bb249"></div>
  </div>

<? endif ?>
<? //include_component("category", "sliderItems", array('sf_cache_key' => 'RecommendItems', 'type'=>'recommend'))
?>

<? include_component("page", "subpage", array('page' => 'advantages-item')); ?>

<?php
slot('advcake', 4);
slot('is_dadata_need', true);
?>
