<div class="footer-top">
  <div class="wrapper">
    <div class="footer-wrap">
      <? if (sizeof($menu)) foreach ($menu as  $link) : ?>
        <div class="footer-nav-col">
          <div class="footer-h">
            <? //= $link['is_noindex'] ? '<noindex>' : '' ?>
            <a <?=$path == $link['link'] ? 'class="active"' : ''?> href="<?= $link['link'] ? $link['link'] : 'javascript:void(1);' ?>"<?= $link['is_noindex'] ? ' rel="nofollow"' : ''?><?= $link['is_target_blank'] ? ' target="_blank"' : ''?>><?=$link['name']?></a>
            <? //= $link['is_noindex'] ? '</noindex>' : '' ?>
          </div>
          <? if(sizeof($link['submenu']) && $link['submenu']!==false) :?>
            <ul>
              <? foreach ($link['submenu'] as $subLink) :?>
                <li>
                  <? //= $link['is_noindex'] ? '<noindex>' : '' ?>
                  <a <?=$path == $subLink['link'] ? 'class="active"' : ''?> href="<?= $subLink['link'] ? $subLink['link'] : 'javascript:void(1);' ?>"<?= $subLink['is_noindex'] ? ' rel="nofollow"' : ''?><?= $subLink['is_target_blank'] ? ' target="_blank"' : ''?>><?=$subLink['name']?></a>
                  <? //= $link['is_noindex'] ? '</noindex>' : '' ?>
                </li>
              <? endforeach ?>
            </ul>
          <? endif ?>
        </div>
      <? endforeach ?>


      <div class="footer-services">
        <div class="footer-h -active">
          будьте в курсе!
        </div>
        <div class="footer-intro">
          Новости и спецпредложения
        </div>
        <form action="/firstvisit/yes" class="footer-form js-submit-form">
          <input type="hidden" name="sf_guard_user[_csrf_token]" value="<?= $csrf ?>">
          <div class="footer-form-row">
            <input type="text" value="" name="user_name" placeholder="Ваше имя" class="js-rr-send-name">
          </div>
          <div class="footer-form-row">
            <input type="email" value="" name="user_email" placeholder="E-mail" class="js-rr-send-email">
          </div>
          <div class="footer-form-row -align">
            <input type="radio" name="user_sex" class="footer-form-chb js-rr-send-gender" id="footerForm1" value="m">
            <label for="footerForm1" class="-male">
              <svg>
                <use xlink:href="#maleIcon" />
              </svg>
            </label>
            <input type="radio" name="user_sex" class="footer-form-chb js-rr-send-gender" id="footerForm2" checked value="g">
            <label for="footerForm2" class="-female">
              <svg>
                <use xlink:href="#femeleIcon" />
              </svg>
            </label>
          </div>

          <div class="but js-submit-button js-rr-send-delay">Подписаться</div>
          <input type="checkbox" name="agreement" class="footer-form-conset" id="footerFormCH" value="1" checked>
          <label for="footerFormCH">
            Согласие на&nbsp;обработку персональных данных и получение рекламно-маркетинговых материалов
          </label>
        </form>
      </div>
    </div>

    <div class="footer-plate">
      <div class="footer-plate-wrap">
        <div class="footer-plate-w">
          <img src="/frontend/images/18.svg" width="82" height="82" alt="18+">
          <div class="footer-plate-w-text">
            <p>© 1992-<?= date('Y')?> «Секс шоп Он и Она»</p>
            <p>Сайт предназначен для лиц, достигших 18-ти летнего возраста.</p>
          </div>
        </div>
        <div class="footer-plate-smm">
          Мы в соцсетях:
          <div class="smm">
            <a href="https://vk.com/sex_shop_onona" target="_blank">
              <svg>
                <use xlink:href="#smmVcIcon" />
              </svg>
            </a>
            <a href="https://www.instagram.com/onona.ru/" target="_blank">
              <svg>
                <use xlink:href="#smmInstIcon" />
              </svg>
            </a>
            <a href="http://www.youtube.com/channel/UCrZ-3sU3RtG5g1YhKF8JJyg?sub_confirmation=1" target="_blank" class="-youtube">
              <svg>
                <use xlink:href="#smmYouIcon" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
