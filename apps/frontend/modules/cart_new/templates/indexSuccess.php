<?
  $h1='Корзина';
  slot('caltat', 2);
  slot('breadcrumbs', [
    ['text' => $h1],
  ]);
  $isHaveEvroset = false;
  global $isTest;
  slot('hide_mess', true);
  // slot('h1', $h1);
// echo '<pre>'.print_r([$bonusCount,/*reset(*/ $products_old/*)*/], true).'</pre>';
$commonPercent=$percentBase;
$screenPosition=0;
$isAndroid=(false!==mb_stripos($_SERVER['HTTP_USER_AGENT'], 'android', 0, 'UTF-8'));
if($isTest) $isAndroid=true;
if($isAndroid) $exchangeRate=csSettings::get('change_rate_ime');
?>
<?/* if($isTest):?>
<style>
.basket-services-form.form.mfp-hide{display: block!important;}
</style>
<? endif */?>
<main class="wrapper">
  <div class="basket-page">
    <div class="v-block-plate">
      <h1>корзина</h1>
      <?/*<noindex><div style="display: none;"><?= print_r($discountNotApply, true) ?></div></noindex>*/?>
    </div>
    <div class="basket-for-js<?=(!is_array($products_old) || count($products_old) == 0) ? ' -deleted' : ''?>" id="basketForJs">
      <? if(is_array($products_old) && count($products_old) > 0) :?>
        <form action="/cart/confirmed_check" class="basket-form js-ajax-form gtm-checkout-one" id="basketForm" method="post">
          <input type="hidden" name="region-fias-id" value="<?=!$user ? '' :$user->getRegionFiasId()?>" id="region-fias-id">
          <input type="hidden" name="coupon-sum-discount" value="0" id="coupon-sum-discount">
          <input type="hidden" name="sf_guard_user[_csrf_token]" value="<?php echo $csrf ?>">
          <table class="basket" id="basket"
              data-bonus="<?= $bonusCount ?>"
              data-freedelivery="<?= csSettings::get('free_deliver') ?>"
              data-freedelivery-online-moscow="<?=csSettings::get('online_delivery_free_moscow')?>"
              data-freedelivery-online-russia="<?=csSettings::get('online_delivery_free_russia')?>"
              data-bonusTopLimit="<?=$bonusTopLimit?>"
              data-bonusMinLimit="<?=$bonusMinLimit?>"
          >
            <thead>
              <th class="-size-1">&nbsp;</th>
              <th class="-leftAlign -tHeadName">Наименование</th>
              <th class="-size-3">Цена, руб.</th>
              <th class="-size-4">Бонусы</th>
              <th class="-size-5">Кол-во</th>
              <th class="-size-6">Сумма</th>
              <th class="-size-7">&nbsp;</th>
            </thead>
            <tbody>
              <?php foreach ($products_old as $key => $productInfo): ?>
                <?
                  $product = $products[ $key ];
                  $productKeys[]=$product->getId();
                  $photoalbum = $product->getPhotoalbums();
                  $image=false;
                  if(is_object($photoalbum[0])) {
                    $photos=$photoalbum[0]->getPhotos();
                    if(isset($photos[0])) $image='/uploads/photo/thumbnails_250x250/'.$photos[0]->getFilename();
                  }
                  if(!$image) $image = '/frontend/images/no-photo_250.jpg';
                ?>
                <tr id="product-line<?=$key?>" class="js-product-row gtm-checkout-item" data-id="<?=$key?>" data-discount-not-apply="<?= in_array($key, $discountNotApply) ? 1 : 0?>" data-weight="<?=$product->getWeight()?>" data-discount="<?= $discountProd ?>" data-price="<?= $productInfo['price'] ?>" data-name="<?= $product->getName() ?>" data-position="<?= $screenPosition++ ?>" data-category="<?=$product->getGeneralCategory()?>">
                  <td class="basket-pic">
                    <a href="/product/<?= $product->getSlug() ?>">
                      <? if($productInfo['discount']) :?>
                        <span class="cat-list-action"> <?=$productInfo['discount']?>% </span>
                      <? endif ?>
                      <img src="<?=$image?>" alt="">
                    </a>
                  </td>
                  <td class="basket-name -leftAlign">
                    <a href="/product/<?= $product->getSlug() ?>"> <?=$product->getName() ?> </a>
                  </td>
                  <td class="basket-price" data-mobtext="Цена, руб.">
                    <?= $productInfo['price'] ?>
                  </td>
                  <td class="basket-bonus" data-mobtext="Бонусы">
                    <div class="basket-calc">
                      <div class="basket-calc-min<?=$productInfo['discount'] ? ' disabled' : ''?>"></div>
                      <input type="number"
                             name="bonusPercentForm[<?= $key ?>]"
                             value="<?=$productInfo['percentbonuspay']?>"
                             data-bonus="<?=$productInfo['bonuspay']?>"
                             readonly
                             data-step="<?= $productInfo['priceonus5persent'] ?>"
                             data-maxbonus="<?=  $product->getIsBonusEnabled() ? ($product->getBonus() != 0 ? $product->getBonus() :  $commonPercent) : 0?>"
                      >
                      <div class="basket-calc-plus<?=$productInfo['discount'] ? ' disabled' : ''?>"></div>
                    </div>
                  </td>
                  <td class="basket-numb" data-mobtext="Кол-во">
                    <div class="basket-calc">
                      <div class="basket-calc-min gtm-decrement-in-cart"></div>
                      <input class="gtm-basket-item-count" type="number" name="" value="<?=$productInfo['quantity']?>" data-price="<?= $productInfo['price_w_discount'] ?>" data-bonus="<?=$productInfo['bonuspay']?>" data-sum="<?= $productInfo['price_w_discount'] * $productInfo['quantity']?>">
                      <div class="basket-calc-plus gtm-increment-in-cart"></div>
                    </div>
                  </td>
                  <td class="basket-sum" data-mobtext="Сумма">
                    <span><?= $productInfo['price_w_discount'] * $productInfo['quantity'] - $productInfo['bonuspay']?></span>
                  </td>
                  <td class="basket-delete js-basket-delete">
                    <div class="gtm-delete-from-chart">
                      <svg>
                        <use xlink:href="#closesIcon" />
                      </svg>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="basket-services-wrap">
            <div class="basket-services">

              <?php if (!$sf_user->isAuthenticated()): ?>
                <div class="basket-user-param basket-bg">
                  <div class="basket-user-param-wrap">
                    <div class="basket-user-param-item">
                      <div class="basket-user-param-h">
                        Зарегистрированный пользователь
                      </div>
                      <div class="basket-user-param-title">
                        Авторизуйтесь для быстрого оформления заказа. Возможно, на вашем счете уже есть Бонусы на оплату от 20 до 50% стоимости заказа.
                      </div>
                      <a href="/guard/login" class="but"> Войти </a>
                    </div>
                    <div class="basket-user-param-item">
                      <div class="basket-user-param-h">
                        Новый покупатель
                      </div>
                      <div class="basket-user-param-title">
                        Зарегистрируйтесь и получите 300 приветственных бонусов, которыми вы сможете оплатить свой заказ прямо сейчас.
                      </div>
                      <a href="/register" class="but"> Зарегистрироваться </a>
                    </div>
                  </div>
                </div>
              <?php else :?>
                <div class="basket-user-param-crutch"><?php/* костыль, чтобы убрать дыру в верстке */?></div>
              <?php endif; ?>
              <div class="v-block-plate">
                шаг 1. Выберите способ доставки
              </div>
              <? if(sizeof($delivers)) ://Выводим доступные доставки?>
                <div class="basket-delivery basket-bg">
                  <?php foreach ($delivers as $key => $delivery): ?>
                    <? if(!$isYandexEnable && $delivery->getId()==15) continue; ?>
                    <div class="basket-delivery-item">
                      <input
                        type="radio"
                        name="deliveryId"
                        value="<?= $delivery->getId() ?>" id="delivery-<?= $key ?>"
                        class="js-delivery"
                        data-free-from="<?= $delivery->getFreeFrom() ?>"
                        data-free-from-online="<?= $delivery->getFreeFromOnline() ?>"
                        data-free-from-online-moscow="<?= $delivery->getFreeFromOnlineMoscow() ?>"
                      >
                      <label for="delivery-<?= $key ?>" <?=$delivery->getId()==10 ? 'class="js-shop-init" data-dont-stop="1"' : ''?>>
                        <span class="basket-delivery-img">
                          <img src="/uploads/delivery/<?= $delivery->getPicture() ?>" width="34px">
                          <img src="/uploads/delivery/<?= $delivery->getPicturehover() ?>" class="-hoveredImg" width="34px">
                        </span>
                        <span class="basket-delivery-h"> <?= $delivery->getName() ?> </span>
                        <span class="basket-delivery-title"> <?= $delivery->getDescription() ?> </span>
                      </label>
                      <div style="display: none;" id="description-<?= $delivery->getId() ?>">
                        <?/* Этот блок вставляется в блок ниже с помощью JS */?>
                        <div class="address"></div>
                        <? if($delivery->getId()==11) : // PickPoint?>
                        <? slot('js-pickpoint', true); ?>
                          <div class="drh-shutter but js-pickpoint-init">Выбрать точку доставки</div><br>
                        <? endif ?>
                        <? if($delivery->getId()==14) : // Евросеть?>
                          <? slot('js-euroset', true); $isHaveEvroset = true; ?>
                          <div class="drh-shutter but js-dlh-init">Выбрать точку доставки</div><br>
                        <? endif ?>
                        <? if($delivery->getId()==10) : // Самовывоз?>
                          <div class="drh-shutter but js-shop-init">Выбрать пункт самовывоза</div><br>
                        <? endif ?>
                        <?= $delivery->getContent() ?>
                      </div>
                      <? if($delivery->getId()==11) : // PickPoint?>
                        <input type="hidden" name="pickpoint_id" id="pickpoint_id">
                        <input type="hidden" name="pickpoint_address" id="pickpoint_address" value="" />
                      <? endif ?>
                      <? if($delivery->getId()==14) : // Евросеть?>
                        <input type="hidden" name="dlh_shop_id" id="dlh_shop_id" value="" />
                        <input type="hidden" name="dlh_address_line" id="dlh_address_line" value="" />

                      <? endif ?>
                      <? if($delivery->getId()==10) : // Самовывоз?>
                        <input type="hidden" name="shop_id" id="shop_id" value="" />
                        <input type="hidden" name="shop_address_line" id="shop_address_line" value="" />
                        <? include_component('page', 'shopsDelivery', array('sf_cache_key' => "shopsDelivery")); ?>
                      <? endif ?>
                    </div>
                  <?php endforeach; ?>
                  <div class="basket-all js-hide-show-next">
                    <a href="#">
                      <span>Информация о доставке</span>
                      <svg>
                        <use xlink:href="#arrowMoreIcon" />
                      </svg>
                    </a>
                  </div>
                  <div id="delivery-info" class="mfp-hide">Пожалуйста, выберите вид доставки!</div>
                  <?= $isHaveEvroset ? '<div id="drh-container" class="drh-container"> <div class="drh-container-back"></div> <div id="map" class="drh-map-container"></div></div>' : '' ?>
                </div>
              <? endif ?>
              <div class="v-block-plate mfp-hide" id="payment-header">
                шаг 2. Выберите способ оплаты
              </div>
              <div class="basket-payment basket-bg mfp-hide" id="payment-body"><?/* придут из ajax шаблоном _payments.php*/?></div>
              <div class="v-block-plate mfp-hide" id="services-header">
                шаг 3. ЗАПОЛНИТЕ ФОРМУ
              </div>
              <div class="basket-bg basket-services-form form mfp-hide" id="services-body">
                <div class="basket-services-form-group -align -botBordered">
                  <div class="basket-services-form-col -col-4-of-12">
                    <div class="form-label">
                      ФИО: *
                    </div>
                    <input type="text" name="first_name" value="<?= !$user ? $_GET['username'] : $user->getFirstName()?>" placeholder="Как к Вам обращаться?">
                  </div>
                  <div class="basket-services-form-col -col-4-of-12">
                    <div class="form-label">
                      Пол:
                    </div>
                    <div class="male-female">
                      <? $sex=!$user ? '' : $user->getSex(); ?>
                      <input type="radio" name="sex" class="styleCH" value="m" id="sex-m" <?=$sex=='m' ? ' selected' : ''?>>
                      <label for="sex-m"> М </label>
                      <input type="radio" name="sex" class="styleCH" value="g" id="sex-f" <?=$sex=='g' ? ' selected' : ''?>>
                      <label for="sex-f"> Ж </label>
                    </div>
                    <?/*<input type="text" name="sex" value="<?= !$user ? '' : $user->getSex()?>" placeholder="Выберите пол">*/?>
                  </div>
                  <div class="basket-services-form-col -col-4-of-12">
                    <div class="form-label">
                      День рождения:
                    </div>
                    <?
                      if( !$user)
                        $birthdate='';
                      else{
                         $birthdate=implode('.', array_reverse(explode('-',$user->getBirthday())));
                         if($birthdate=='00.00.0000') $birthdate='';
                      }
                    ?>
                    <input type="text" name="birthday" value="<?=$birthdate?>" placeholder="01.01.1980">
                  </div>
                  <div class="basket-services-form-col -col-4-of-12">
                    <div class="form-label">
                      Телефон: *
                    </div>
                    <input type="tel" class="js-phone-mask" name="phone" value="<?= !$user ? $_GET['phone']  : $user->getPhone()?>" placeholder="+7 (___) ___-__-__">
                  </div>
                  <div class="basket-services-form-col -col-4-of-12">
                    <div class="form-label">
                      E-mail: *
                    </div>
                    <input class="js-rr-send" type="email" name="user_mail" value="<?= !$user ? '' : $user->getEmailAddress()?>" placeholder="Укажите Ваш e-mail" <?= !$user ? '' : ' readonly'?>>
                  </div>
                  <div class="basket-services-form-col basket-services-form-add-comment -col-4-of-12 js-hide-show-next">
                    <a href="#">Добавить комментарий к заказу</a>
                  </div>
                  <div class="basket-services-form-col basket-services-form-add-comment -col-12-of-12 mfp-hide">
                    <div class="form-label">
                      Ваши пожелания по заказу и доставке:
                    </div>
                    <textarea name="comments"></textarea>
                  </div>
                </div>
                <div class="basket-services-form-group -align -middleBordered">
                  <div class="basket-services-form-col -col-4-of-12 -imitationRow js-no-address-need js-delivery-calc">
                    <div class="form-label">
                      Город: *
                    </div>
                    <?
                      if($user && $user->getUpdatedAt()<'2020-10-05 12:00:12') $notShowCity=true;
                      else $notShowCity=false;
                    ?>
                    <input type="text" name="city" value="<?= (!$user || $notShowCity) ? '' : $user->getCity()?>" placeholder="" id="city" class="js-dadata">
                  </div>
                  <div class="basket-services-form-col -col-4-of-12 -imitationRow js-no-address-need js-delivery-calc">
                    <div class="form-label">
                      Улица: *
                    </div>
                    <input type="text" name="street" value="<?= !$user ? '' : $user->getStreet()?>" placeholder="" id="street" class="js-dadata">
                  </div>
                  <div class="basket-services-form-col -col-4-of-12 -imitationRow js-no-address-need js-delivery-calc">
                    <div class="form-label">
                      Дом: *
                    </div>
                    <input type="text" name="house" value="<?= !$user ? '' : $user->getHouse()?>" placeholder="" id="house" class="js-dadata">
                  </div>
                  <? if($isCouponsEnabled) :?>
                    <div class="basket-services-form-col -col-8-of-12">
                      <div class="form-label">
                        Промокод:
                      </div>
                      <input type="text" name="coupon" value="" placeholder="" class="coupon">
                    </div>
                    <div class="basket-services-form-col -col-4-of-12 ">
                      <div class="apply-button js-coupon-apply">Применить</div>
                    </div>
                  <? endif ?>
                  <?/*
                  <div class="basket-services-form-col form-capcha -col-4-of-12">
                    <div class="form-capcha-label">
                      4534535
                    </div>
                    <input type="text" name="" value="" placeholder="Введите символы">
                  </div>
                  <?*/?>
                </div>
                <div class="basket-services-form-foot">
                  <div class="basket-services-form-confirm">
                    <div class="basket-services-form-confirm-row">
                      <input type="checkbox" name="agreement-18" class="styleCH" value="1" id="basketConfirm1" checked>
                      <label for="basketConfirm1">
                        Подтверждаю, что мне 18 или более лет.
                      </label>
                    </div>
                    <div class="basket-services-form-confirm-row">
                      <input type="checkbox" name="agreement" class="styleCH" value="1" id="basketConfirm2"  checked>
                      <label for="basketConfirm2">
                        Я принимаю условия <a href="/personal_accept" target="_blank">Пользовательского соглашения</a>
                      </label>
                    </div>
                  </div>
                  <div class="but js-submit-button">оформить заказ</div>
                  <?/*<input type="submit" name="" value="оформить заказ" class="but">*/?>
                </div>
              </div>
            </div>
            <div class="basket-all-price basket-bg" id="basketAllPriceVlock">
              <div class="basket-all-price-row -align">
                <div class="basket-all-price-col">
                  Сумма:
                </div>
                <div class="basket-all-price-sum" id="basketAllPrice">
                  <span>0</span> руб.
                </div>
              </div>
              <div class="basket-all-price-row -align mfp-hide" id="delivery-sum-block">
                <input type="hidden" value="0" name="deliveryPriceSend" id="deliveryPriceSend">
                <div class="basket-all-price-col">
                  Доставка:
                </div>
                <div class="basket-all-price-sum">
                  <span id="deliveryPriceSendSpan">0</span> руб.
                </div>
              </div>
              <div class="basket-all-price-desc">
                <div class="basket-all-price-row">
                  <div class="basket-all-price-col">
                    <input type="radio" name="basketRadio" class="styleCH" value="1" id="basketRadio-1" data-discount="7" disabled>
                    <label for="basketRadio-1">
                      Оплата онлайн:
                      <span class="-detailed">
                        (скидка 7%)
                      </span>
                    </label>
                  </div>
                </div>
                <div class="basket-all-price-row">
                  <div class="basket-all-price-col">
                    <input type="radio" name="basketRadio" class="styleCH" value="0" id="basketRadio-" disabled>
                    <label for="basketRadio-" class="-botCH">
                      Оплата при получениии:
                    </label>
                  </div>
                </div>
              </div>
              <div class="basket-all-price-foot ">
                <div class="basket-all-price-col basket-all-price-total-text">
                  Итог:
                </div>
                <div class="basket-all-price-total" id="basketAllPriceTotal">
                  <span>0</span> руб.
                </div>
                <?/*
                <div class="basket-all-price-foot-subm">
                  <input type="submit" name="" class="but" value="Быстрый заказ">
                </div>*/?>
              </div>
              <? if($isAndroid) :?>
                <div class="basket-all-price-row -align mfp-hide js-ime-only">
                  <div class="basket-all-price-col basket-all-price-total-text">

                  </div>
                  <div class="basket-all-price-total" id="basketAllPriceTotalAicoin" data-change-rate="<?= $exchangeRate ?>">
                    <span>0</span> AiCoin.
                  </div>
                </div>
            <? endif ?>
            </div>
          </div>
        </form>
        <div data-retailrocket-markup-block="5ba3a64397a52530d41bb248" data-products="<?= implode(', ', $productKeys) ?>"></div>

      <? else :?>
        <div class="basket-is-deleted-block basket-bg">
          <p>Корзина пуста.</p>
          <p>Для добавления товара в&nbsp;корзину перейдите в&nbsp;раздел <a href="/">Интернет&nbsp;секс&nbsp;шоп</a>, выберете нужный Вам товар и&nbsp;нажмите на кнопку «В&nbsp;корзину». В&nbsp;корзине Вы можете изменить количество товара
            или удалить его из корзины.</p>
          <p>Кнопка «Сообщить о&nbsp;поступлении» вместо кнопки «В&nbsp;корзину» означает, что в&nbsp;данный момент товара нет на складе. При подписке на&nbsp;уведомление о&nbsp;поступлении товара, укажите свой e-mail, на&nbsp;который Вы хотели бы получить
            уведомление, и, когда товар поступит в&nbsp;продажу, Вы своевременно узнаете об этом.</p>
          <p>Приятных Вам покупок!</p>
          <div data-retailrocket-markup-block="5ba3a65797a52530d41bb249"></div>
        </div>
      <? endif ?>
    </div>
  </div>
</main>
<? //include_component("category", "sliderItems", array('sf_cache_key' => 'recommended-cart', 'type'=>'recommended'));?>
<?/*<div data-retailrocket-markup-block="5ba3a64397a52530d41bb248" data-products="<?=implode (', ', $itemsInChart)?>"></div>*/?>
<article class="intro-block -int wrapper">
  <div class="intro-block-wrap">
    <figure class="intro-block-img">
      <img src="/frontend/images/about-int.png" width="388px">
    </figure>
    <div class="intro-block-desc">
      <div class="h1">
        <span class="-smallTabletDefis">магазин для взрослых “он и она” </span>
        КАЧЕСТВЕННЫЕ СЕКС-ИГРУШКИ, СЕКСУАЛЬНОЕ БЕЛЬЕ, ВЫГОДНЫЕ ПРЕДЛОЖЕНИЯ И ЛУЧШИЕ БРЕНДЫ
      </div>
      <p>Первый специализированный магазин для взрослых был открыт в Москве при отделении семейной консультации на базе медицинского центра «Медицина и репродукция» в 1992 году. Уникальность этого проекта состояла в том, что из массы зарубежных товаров
        отбирались исключительно те, медицинское действие которых было доказано.</p>
    </div>
  </div>
</article>

<?php
slot('advcake', 4);
slot('is_dadata_need', true);
?>
