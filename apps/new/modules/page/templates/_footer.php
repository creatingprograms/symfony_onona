<div class="footer-top">
  <div class="container">
    <div class="footer-wrap">
      <div class="footer-menu">
        <? if (sizeof($menu)) foreach ($menu as  $link) : ?>
          <div class="footer-nav-col">
            <div class="footer-h">
              <? //= $link['is_noindex'] ? '<noindex>' : '' 
              ?>
              <a <?= $path == $link['link'] ? 'class="active"' : '' ?> href="<?= $link['link'] ? $link['link'] : 'javascript:void(1);' ?>" <?= $link['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $link['is_target_blank'] ? ' target="_blank"' : '' ?>><?= $link['name'] ?></a>
              <span class="btn-footer-menu"></span>
              <? //= $link['is_noindex'] ? '</noindex>' : '' 
              ?>
            </div>
            <? if (sizeof($link['submenu']) && $link['submenu'] !== false) : ?>
              <ul>
                <? foreach ($link['submenu'] as $subLink) : ?>
                  <li>
                    <? //= $link['is_noindex'] ? '<noindex>' : '' 
                    ?>
                    <a <?= $path == $subLink['link'] ? 'class="active"' : '' ?> href="<?= $subLink['link'] ? $subLink['link'] : 'javascript:void(1);' ?>" <?= $subLink['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $subLink['is_target_blank'] ? ' target="_blank"' : '' ?>><?= $subLink['name'] ?></a>
                    <? //= $link['is_noindex'] ? '</noindex>' : '' 
                    ?>
                  </li>
                <? endforeach ?>
              </ul>
            <? endif ?>
          </div>
        <? endforeach ?>
      </div>
      <div class="footer-contact">
        <div class="footer-h">Контактные данные</div>
        <div class="footer-tel">
          <div class="footer-tel__item">
            <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone1')) ?>"> <?= csSettings::get('phone1') ?></a>
            <span>по России бесплатно</span>
          </div>
          <div class="footer-tel__item">
            <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone2')) ?>"> <?= csSettings::get('phone2') ?></a>
            <span>круглосуточно по МСК</span>
          </div>
        </div>
        <div class="footer-plate-smm">
          <div class="smm">
            <a href="https://www.youtube.com/channel/UCrZ-3sU3RtG5g1YhKF8JJyg" target="_blank" class="" rel="nofollow">
              <svg>
                <use xlink:href="#youtube-svg" />
              </svg>
            </a>
            <?/*<a href="https://www.facebook.com/groups/537686253037357/?ref=share" target="_blank" class="" rel="nofollow">
              <svg>
                <use xlink:href="#fb-svg" />
              </svg>
            </a>*/ ?>
            <a href="https://vk.com/sex_shop_onona" target="_blank">
              <svg>
                <use xlink:href="#vk-svg" />
              </svg>
            </a>
            <?/*<a href="https://www.instagram.com/onona.ru/" target="_blank">
              <svg>
                <use xlink:href="#inst-svg" />
              </svg>
            </a>*/ ?>
            <a href="https://t.me/ononaaaa" target="_blank">
              <svg>
                <use xlink:href="#telegram-svg" />
              </svg>
            </a>
            <?/*<a href="https://dzen.ru/ononaa" target="_blank" class="js-yandex-send" data-target="perekhod-v-dcen-ikonka">
              <svg>
                <use xlink:href="#dzen-svg" />
              </svg>
            </a>*/?>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-plate">
      <div class="footer-plate-wrap">
        <noindex>
          <div class="footer-plate-w">
            <div class="footer-plate-w-text">
              <p>© 1992-<?= date('Y') ?> «Секс шоп Он и Она»</p>
              <p class="text-warning">Сайт предназначен для лиц, достигших 18-ти летнего возраста.</p>
              <div class="wrap-note">
                <a href="/personal_accept">Политика конфиденциальности</a>
              </div>
            </div>
          </div>
        </noindex>

      </div>
    </div>
  </div>
</div>
<div class="mobile-bottom-menu">
  <a href="/" class="mobile-bottom-menu_item">
    <svg>
      <use xlink:href="#home-svg"></use>
    </svg>
    <span>Главная</span>
  </a>
  <a href="/catalog" class="mobile-bottom-menu_item">
    <svg>
      <use xlink:href="#catalog-svg"></use>
    </svg>
    <span>Каталог</span>
  </a>
  <? if (!$sf_user->isAuthenticated()) : ?>
    <a href="#popup-login" class="mobile-bottom-menu_item js-popup-form">
    <? else : ?>
      <a href="/lk" class="mobile-bottom-menu_item">
      <? endif ?>
      <svg>
        <use xlink:href="#login-svg"></use>
      </svg>
      <span>Кабинет</span>
      </a>
      <? if (!$sf_user->isAuthenticated()) : ?>
        <a href="#popup-login" class="mobile-bottom-menu_item js-popup-form">
        <? else : ?>
          <? $favCount = ILTools::getFavCount(sfContext::getInstance()->getUser()->getGuardUser()); ?>
          <a href="/customer/favorites" class="mobile-bottom-menu_item fav-wrapper" <?= $favCount ? 'count="' . $favCount . '"' : '' ?>>
          <? endif ?>
          <svg>
            <use xlink:href="#chosen-svg"></use>
          </svg>
          <span>Избранное</span>
          </a>
          <? include_component('cart_new', 'topCart', array('type' => 'fixed')); ?>
          <?/*<a href="/">
    <svg>
      <use xlink:href="#login-svg"></use>
    </svg>
    <span>Корзина</span>
  </a>*/ ?>
</div>