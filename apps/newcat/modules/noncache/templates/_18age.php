<?php
  if (sfContext::getInstance()->getRequest()->getCookie('age18') != true and ! get_slot('18ageNo')) {
    ?>
    <!--noindex-->
    <div id="age-18-BG"></div>
    <div class="eithteen-age-popup" id="age-18">
      <div class="eithteen-age-image"></div>
      <div class="eithteen-age-text-1">Просмотр материалов данного сайта предназначен исключительно для совершеннолетних лиц.</div>
      <div class="eithteen-age-text-2">Если вам не исполнилось <strong>18 лет</strong>, просим немедленно покинуть сайт!</div>
      <div class="notification-buttons">
        <a class="eithteen-age-button-1" href="javascript:setCookie('age18', true, 'Mon, 01-Jan-2101 00:00:00 GMT', '/'); $('#age-18').hide(); $('#age-18-BG').hide(); void(0);">
          Мне 18 или более лет
        </a>
        <a class="eithteen-age-button-2" href="javascript:$('#age-18').hide(); window.location.href='/no18';">
          Мне меньше 18 лет
        </a>
      </div>
    </div>
    <!--/noindex-->
<? }
?>
