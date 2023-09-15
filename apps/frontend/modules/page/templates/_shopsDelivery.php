<div id="shops-delivery" class="mfp-hide shops-delivery">
  <div class="shops-wrapper form">
    <?php foreach ($shops as $shop): ?>
      <input type="radio" name="self_delivery_id" class="styleCH js-shop-select-list" id="delivery-point-<?= $shop->getId() ?>" value="<?= $shop->getId() ?>">
      <label for="delivery-point-<?= $shop->getId() ?>">
        <?= $shop->getAddress() ?>
      </label>

    <? endforeach ?>
    </div>
  <?/*<div id="shops-map" class="shops-map"></div>*/?>
  <?php/* foreach ($shops as $shop): ?>
    <div class="js-shop-item mfp-hide" data-lng="<?= $shop->getLongitude() ?>" data-lat="<?= $shop->getLatitude() ?>" data-id="<?= $shop->getId() ?>">
      <div class="shop-baloon" data-id="<?= $shop->getId() ?>">
        <div class="name"><?= $shop->getName() ?></div>
        <div class="shop-address"><?= $shop->getAddress() ?></div>
        <div class="proezd"><?=$shop->getMetro()?> <?=$shop->getOutdescription()?></div>
      </div>
      <div class="js-shop-select shop-select">Выбрать</div>
    </div>
  <?php endforeach; */?>
  <p class="js-current-shop-point">Пожалуйста, выберите точку самовывоза</p>
</div>
