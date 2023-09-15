<div class="product-card-row product-card-enable-block">
  <? if (sizeof($shopsInStock) + sizeof($shopsNotCount)):?>
    <div class="product-card-desc-block-head">
      <div class="h1">Наличие в магазинах</div>
      <a href="/dostavka" class="product-card-table-icon">
        Доставка и самовывоз
      </a>
    </div>

    <div class="product-card-enable" id="productCardEnable">
      <?php $i=0; if (sizeof($shopsInStock)) foreach ($shopsInStock as $shop): ?>
        <div class="product-card-enable-row">
          <div class="product-card-enable-name">
            <div class="shops-page-adres-h ">
              <a href="/shops/<?= $shop->getSlug() ?>">
                <?if ($shop->getIconmetro() != "") :?>
                  <img src="/uploads/metro/<?=$shop->getIconmetro()?>" alt="">
                <? endif ?>
                 <?= $shop->get('Metro') ?>
              </a>
            </div>
          </div>
          <div class="product-card-enable-adres">
            <?= $shop->get('Address') ?>
          </div>
          <div class="product-card-enable-presence">
            В&nbsp;наличии
          </div>
          <div class="product-card-enable-time">
            <?= $shop->getWorkTime() ?>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (sizeof($shopsNotCount))  foreach ($shopsNotCount as $shop): ?>
        <div class="product-card-enable-row">
          <div class="product-card-enable-name">
            <div class="shops-page-adres-h ">
              <a href="/shops/<?= $shop->getSlug() ?>">
                <?if ($shop->getIconmetro() != "") :?>
                  <img src="/uploads/metro/<?=$shop->getIconmetro()?>" alt="">
                  <? endif ?>
                 <?= $shop->get('Metro') ?>
              </a>
            </div>
          </div>
          <div class="product-card-enable-adres">
            <?= $shop->get('Address') ?>
          </div>
          <div class="product-card-enable-presence">
            <a href="#" class="product-card-table-icon">
              Под заказ
            </a>
          </div>
          <div class="product-card-enable-time">
            <?= $shop->getWorkTime() ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <? if (sizeof($shopsInStock) + sizeof($shopsNotCount) > 6):?>
      <div class="more">
        <a href="#productCardEnable" class="allButJS" data-all="Свернуть список">
          <span>Весь список магазинов </span>
          <svg>
            <use xlink:href="#arrowMoreIcon"></use>
          </svg>
        </a>
      </div>
    <? endif ?>
  <? else :?>
  <div class="product-card-desc-block-head">
    <div class="h1">Наличие в магазинах</div>
    <a href="/dostavka" class="product-card-table-icon">
      Доставка и самовывоз
    </a>
  </div>
  <? endif ?>
</div>
