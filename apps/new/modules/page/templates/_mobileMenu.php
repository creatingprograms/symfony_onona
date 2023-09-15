<div class="mob-nav">
  <div class="mob-nav-wrap">
    <nav>
      <? if (sizeof($menuNav)) : //Верхняя, белая часть. Управляется из меню типа main_frontend 
      ?>
        <ul class="mob-nav-top">
          <? foreach ($menuNav as $key => $link) : ?>

            <li class="<?/*= $link['link'] == '/catalog' ? '-red ' : '' */ ?><?= $link['link'] == '/akcii-i-bonusy' ? '-action ' : '' ?>">
              <?
              $hasSub = false;
              if ($link['link'] == '/catalog') {
                $link['name'] = 'Каталог';
                $link['link'] = '#';
              }
              if ($link['link'] == '/adresa-magazinov-on-i-ona-v-moskve-i-mo') {
                $link['link'] = '#';
              }
              if ($link['link'] == '/dostavka') {
                $link['link'] = '#';
              }
              if ($link['link'] == '/akcii-i-bonusy') $link['name'] = 'АКЦИИ';
              /* Ниже Будет выпадашка по ID-ку */
              if (sizeof($link['submenu']) && is_array($link['submenu'])) {
                // $link['link']='#';
                $subs['sub-' . $key] = [
                  'name' => $link['name'],
                  'elements' => $link['submenu']
                ];
                $hasSub = true;
              }
              ?>
              <?
              $isRealLink = true;
              if (!$link['link'] || $link['link'] == '#') $isRealLink = false;
              ?>
              <a <?= !empty($link['counter_target']) ? ' class="js-yandex-send" data-target="' . $link['counter_target'] . '"' : '' ?> href="<?= $link['link'] ?>" <?= (!$isRealLink) ? ' data-href="#sub-' . $key . '"' : '' ?><?= $link['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $link['is_target_blank'] ? ' target="_blank"' : '' ?>><?= $link['name'] ?></a>
              <? if (sizeof($link['submenu']) && is_array($link['submenu'])) : ?>
                <div class="mob-nav-arrow" data-href="#sub-<?= $key ?>">
                  <svg>
                    <use xlink:href="#backArrowIcon" />
                  </svg>
                </div>
              <? endif ?>
            </li>
          <? endforeach ?>
        </ul>
      <? endif ?>
      <? if (sizeof($menuBot)) : /*Нижняя, серая часть меню, управляемая из типа меню mobile_frontend*/ ?>
        <ul class="mob-nav-bot">
          <? foreach ($menuBot as $link) : ?>
            <li>
              <a href="<?= $link['link'] ?>" <?= $link['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $link['is_target_blank'] ? ' target="_blank"' : '' ?>><?= $link['name'] ?></a>
            </li>
          <? endforeach ?>
        </ul>
      <? endif ?>

      <? if (sizeof($subs)) foreach ($subs as $key => $lineBlock) : //Выпадашки для пунктов из первой итерации 
      ?>
        <div class="mob-nav-pop" id="<?= $key ?>">
          <div class="mob-nav-plate">
            <div class="mob-nav-plate-col -back">
              <svg>
                <use xlink:href="#backArrowIcon" />
              </svg>
            </div>
          </div>

          <ul class="mob-nav-top">
            <? foreach ($lineBlock['elements'] as $key => $link) : ?>
              <li>
                <?
                if ($link['class'] == '-certs') {
                  unset($lineBlock['elements'][$key]['submenu']);
                  unset($link['submenu']);
                  // die('<pre>'.print_r($link, true).'</pre>');
                }
                ?>
                <? if (sizeof($link['submenu']) && is_array($link['submenu'])) {
                  $subsSubs['subs-subs-' . $key] = [
                    'name' => $link['name'],
                    'elements' => $link['submenu'],
                    'class' => $link['class'],
                  ];
                ?>
                  <a class="mob-nav-link <?= $link['class'] ?><?= !empty($link['counter_target']) ? ' js-yandex-send' : '' ?>" <?= !empty($link['counter_target']) ? 'data-target="' . $link['counter_target'] . '"' : '' ?> href="<?= $link['link'] ?>">
                    <?php if ($link['class'] != '') : ?>
                      <svg class="cat-icon">
                        <use xlink:href="#cat<?= $link['class'] ?>" />
                      </svg>
                    <?php endif; ?>

                    <?= $link['name'] ?>

                    <div class="mob-nav-arrow" data-href="#<?= 'subs-subs-' . $key ?>">
                      <svg>
                        <use xlink:href="#backArrowIcon" />
                      </svg>
                    </div>
                  </a>
                <? } else { ?>
                  <a <?= $link['class'] != '' ? 'class="mob-nav-link ' . $link['class'] . '"' : '' ?> href="<?= $link['link'] ?>" <?= $link['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $link['is_target_blank'] ? ' target="_blank"' : '' ?>>
                    <?php if ($link['class'] != '') : ?>
                      <svg class="cat-icon">
                        <use xlink:href="#cat<?= $link['class'] ?>" />
                      </svg>
                    <?php endif; ?>
                    <?= $link['name'] ?>
                  </a>
                <?  }  ?>
              </li>
            <? endforeach ?>
          </ul>
        </div>
      <? endforeach ?>

      <? if (sizeof($subsSubs)) foreach ($subsSubs as $key => $lineBlock) : //Выпадашки для пунктов из второй итерации 
      ?>
        <div class="mob-nav-pop mob-nav-pop-lv-2" id="<?= $key ?>">
          <div class="mob-nav-plate">
            <div class="mob-nav-plate-col -back">
              <svg>
                <use xlink:href="#backArrowIcon" />
              </svg>
            </div>
          </div>
          <div class="mob-nav-head <?= $lineBlock['class'] ?>">
            <?php if ($lineBlock['class'] != '') : ?>
              <svg class="cat-icon">
                <use xlink:href="#cat<?= $lineBlock['class'] ?>" />
              </svg>
            <?php endif; ?>
            <?= $lineBlock['name'] ?>
          </div>
          <ul class="mob-nav-top">
            <? foreach ($lineBlock['elements'] as $key2 => $link) : ?>
              <li>
                <? if (sizeof($link['submenu']) && is_array($link['submenu'])) {
                  $subsSubsSubs['subs-subs-' . $key . $key2] = [
                    'name' => $link['name'],
                    'elements' => $link['submenu'],
                  ];
                ?>

                  <?/*<div class="mob-nav-link" data-href="#<?= 'subs-subs-' . $key ?>">*/ ?>
                  <a class="mob-nav-link" href="<?= $link['link'] ?>">
                    <? if (isset($link['name_part'])) : ?>
                      <span><?= $link['name_part'] ?></span> <?= $link['name_part_2'] ?>
                    <? else : ?>
                      <?= $link['name'] ?>
                    <? endif ?>
                    <div class="mob-nav-arrow" data-href="#<?= 'subs-subs-' . $key . $key2 ?>">
                      <svg>
                        <use xlink:href="#backArrowIcon" />
                      </svg>
                    </div>
                  </a>

                <? } else { ?>
                  <a href="<?= $link['link'] ?>" <?= $link['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $link['is_target_blank'] ? ' target="_blank"' : '' ?>><?= $link['name'] ?></a>
                <?  }  ?>
              </li>
            <? endforeach ?>
          </ul>
        </div>
      <? endforeach ?>


      <? if (sizeof($subsSubsSubs)) foreach ($subsSubsSubs as $key => $lineBlock) : //Выпадашки для пунктов из второй итерации 
      ?>
        <?php //die(var_dump($lineBlock))
        ?>
        <div class="mob-nav-pop mob-nav-pop mob-nav-pop-lv-2" id="<?= $key ?>">
          <div class="mob-nav-plate">
            <div class="mob-nav-plate-col -back">
              <svg>
                <use xlink:href="#backArrowIcon" />
              </svg>
            </div>
          </div>
          <div class="mob-nav-head">
            <?= $lineBlock['name'] ?>
          </div>
          <ul class="mob-nav-top">
            <? foreach ($lineBlock['elements'] as $key2 => $link) : ?>
              <li>
                <a href="<?= $link['link'] ?>" <?= $link['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $link['is_target_blank'] ? ' target="_blank"' : '' ?>><?= $link['name'] ?></a>
              </li>
            <? endforeach ?>
          </ul>
        </div>
      <? endforeach ?>
    </nav>
    <?/*<div class="wrap-login">
      <a class="btn-login" href="/lk">
        <svg>
          <use xlink:href="#login-svg"></use>
        </svg>
      </a>
      <a href="/lk" class="a">Личный кабинет</a>
    </div>*/ ?>
    <style>
      .wrap-smm {
        display: flex;
        justify-content: space-around;
        border-top: 1px solid #e1e0e9;
        border-bottom: 1px solid #e1e0e9;
        padding: 6px 0;
      }

      .wrap-smm a {
        line-height: 0;
      }

      .wrap-smm svg {
        width: 48px;
        height: 48px;
        fill: #000;
      }
    </style>
    <div class="wrap-smm">
      <a href="https://www.youtube.com/channel/UCrZ-3sU3RtG5g1YhKF8JJyg" target="_blank" class="" rel="nofollow">
        <svg>
          <use xlink:href="#youtube-small-svg"></use>
        </svg>
      </a>
      <?/*<a href="https://www.facebook.com/groups/537686253037357/?ref=share" target="_blank" class="" rel="nofollow">
        <svg>
          <use xlink:href="#fb-small-svg" />
        </svg>
      </a>*/ ?>
      <a href="https://vk.com/sex_shop_onona" target="_blank">
        <svg>
          <use xlink:href="#vk-small-svg" />
        </svg>
      </a>
      <?/*<a href="https://www.instagram.com/onona.ru/" target="_blank">
        <svg>
          <use xlink:href="#inst-small-svg" />
        </svg>
      </a>*/ ?>
      <a href="https://t.me/ononaaaa" target="_blank">
        <svg>
          <use xlink:href="#telegram-small-svg" />
        </svg>
      </a>
      <?/*<a href="https://dzen.ru/ononaa" target="_blank" class="js-yandex-send" data-target="perekhod-v-dcen-ikonka">
        <svg>
          <use xlink:href="#dzen-small-svg" />
        </svg>
      </a>*/?>
    </div>
    <ul class="mob-nav-tel">
      <li>
        <div class="header-tel__item">
          <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone1')) ?>"> <?= csSettings::get('phone1') ?></a>
          <span>по России бесплатно</span>
        </div>
      </li>
      <li>
        <div class="header-tel__item">
          <a href="tel:<?= preg_replace('/[^\d+]/', '', csSettings::get('phone2')) ?>"> <?= csSettings::get('phone2') ?></a>
          <span>круглосуточно по МСК</span>
        </div>
      </li>
    </ul>
  </div>
</div>