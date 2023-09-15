<? slot('shops-in-stock', sizeof($shopsInStock) ? sizeof($shopsInStock) : -1 ) ?>
<div class="features-tab__content tab__content">
  <div class="h2">Наличие в магазинах</div>
  <br>
  <div class="table-shop">
    <div class="table-shop__th">
      <div class="col">Адрес магазина</div>
      <div class="col">Как забрать</div>
      <div class="col">Когда забрать</div>
      <div class="col">Время работы</div>
    </div>
    <div class="table-shop__body">
      <? if ($product->getCount() > 0) : ?>
        <div class="table-shop__row">
          <div class="col">
            <div class="table-shop__address">Интернет-магазин</div>
          </div>
          <div class="col">
            <div class="table-shop__status in-stock">В наличии</div>
          </div>
          <div class="col">
            <div class="table-shop__date">Сегодня</div>
          </div>
          <div class="col">
            <div class="table-shop__date">КРУГЛОСУТОЧНО</div>
            <?/*<a href="#" class="btn-full btn-full_white">Заберу отсюда</a>*/?>
          </div>
        </div>
      <? endif ?>
      <?php $i=0; if (sizeof($shopsInStock)) foreach ($shopsInStock as $shop): ?>
        <div class="table-shop__row">
          <div class="col">
            <div class="table-shop__st">
              <?if ($shop->getIconmetro() != "" && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/metro/" . $shop->getIconmetro())) :?>
                <img src="/uploads/metro/<?=$shop->getIconmetro()?>" alt="">
              <? endif ?>
              <?= $shop->get('Metro') ?>
            </div>
            <div class="table-shop__address"><?= $shop->get('Address') ?></div>
          </div>
          <div class="col">
            <div class="table-shop__status in-stock">В наличии</div>
          </div>
          <div class="col">
            <div class="table-shop__date">Сегодня</div>
          </div>
          <div class="col">
            <div class="table-shop__date"><?= $shop->getWorkTime() ?></div>
            <?/*<a href="#" class="btn-full btn-full_white">Заберу отсюда</a>*/?>
          </div>
        </div>
        <? $i++;?>
      <? endforeach ?>
      <? if($i<4 && sizeof($shopsNotCount)) foreach ($shopsNotCount as $shop): ?>
        <div class="table-shop__row">
          <div class="col">
            <div class="table-shop__st">
              <?if ($shop->getIconmetro() != "") :?>
                <img src="/uploads/metro/<?=$shop->getIconmetro()?>" alt="">
              <? endif ?>
              <?= $shop->get('Metro') ?>
            </div>
            <div class="table-shop__address"><?= $shop->get('Address') ?></div>
          </div>
          <div class="col">
            <div class="table-shop__status">Под заказ</div>
          </div>
          <div class="col">
            <div class="table-shop__date"></div>
          </div>
          <div class="col">
            <div class="table-shop__date"><?= $shop->getWorkTime() ?></div>
          </div>
        </div>
        <? if($i++>5) break;?>
      <? endforeach ?>
    </div>
  </div>
  <div class="wrap-btn">
    <a href="/adresa-magazinov-on-i-ona-v-moskve-i-mo" class="btn-full btn-full_gray">Показать все магазины</a>
  </div>
</div>
