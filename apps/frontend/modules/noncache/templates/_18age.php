<?php
  if (sfContext::getInstance()->getRequest()->getCookie('age18') != true and ! get_slot('18ageNo')) {
    ?>
    <!--noindex-->
    <div id="age-18-BG"></div>
    <div class="eithteen-age-popup" id="age-18">
      <div class="eithteen-age-image"></div>
      <div class="eithteen-age-text-1">Просмотр материалов данного сайта предназначен исключительно для совершеннолетних лиц.</div>
      <div class="eithteen-age-text-2"><strong>Если вам не исполнилось 18 лет, просим немедленно покинуть сайт!</strong></div>
      <div class="notification-buttons">
        <a class="eithteen-age-button-1" href="/18age/yes">
          Мне 18 или более лет
        </a>
        <a class="eithteen-age-button-2" href="/18age/no">
          Мне меньше 18 лет
        </a>
      </div>
    </div>
    <!--/noindex-->
<? }
?>
