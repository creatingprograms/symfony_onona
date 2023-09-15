<?php
slot('js-yandex', true);
?>
<div class="wrap-custom-select">
  <select id="cities-list" class="custom-select cities-list">
    <? foreach ($cities as $city) :?>
      <option value="<?= $city['id'] ?>" <?= $city['id']==3 ? ' selected="true"' : ''?>><?= $city['name']?></option>
    <? endforeach ?>
  </select>
</div>
<div class="map-delivery">
  <div id="YMapsID" class="map-box delivery-ymaps"></div>
</div>
