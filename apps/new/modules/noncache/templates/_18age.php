<?php
  if (sfContext::getInstance()->getRequest()->getCookie('age18') != true and ! get_slot('18ageNo')) {
    ?>
    <noindex>
      <div id="age-18-BG"></div>
      <div class="eithteen-age__new-popup" id="age-18">
        <a href="/support" class="eithteen-age-image"></a>
        <div class="eithteen-age__new-text">Просмотр материалов данного сайта предназначен исключительно для совершеннолетних лиц.</div>
        <div class="eithteen-age__new-text">Если вам не исполнилось 18 лет, просим немедленно покинуть сайт!</div>
        <div class="notification-buttons">
          <a class="btn-full btn-full_rad" href="/18age/yes">
            Мне 18 или более лет
          </a>
          <a class="btn-full btn-full_white btn-full_rad" href="/18age/no">
            Мне меньше 18 лет
          </a>
        </div>
      </div>
    </noindex>
<? }
?>
