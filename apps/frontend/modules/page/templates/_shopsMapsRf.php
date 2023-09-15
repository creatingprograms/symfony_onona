<?php
slot('js-yandex', true);
?>
<select id="cities-list">
  <? foreach ($cities as $city) :?>
    <option value="<?= $city['id'] ?>" <?= $city['id']==3 ? ' selected="true"' : ''?>><?= $city['name']?></option>
  <? endforeach ?>
</select>
<div id="YMapsID" class="map-box"></div>
