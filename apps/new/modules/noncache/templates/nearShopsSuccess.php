<?
  $h1='Магазины рядом с вами';
  slot('h1', $h1);
  slot('breadcrumbs', [
    ['link' =>'/adresa-magazinov-on-i-ona-v-moskve-i-mo', 'text' => 'Магазины в Москве'],
    ['text' => $h1],
  ]);
?>

<div class="wrap-block"><div class="container"><div class="block-content">
  <?php if (!empty($shops)): ?>
    <div class="table-shop">
      <div class="table-shop__th">
        <div class="col">Адрес магазина</div>
        <div class="col"></div>
        <div class="col"></div>
        <div class="col">Время работы</div>
      </div>
      <div class="table-shop__body">
        <?php foreach ($shops as $shop): ?>
          <div class="table-shop__row">
            <div class="col">
              <div class="table-shop__st">
                  <?if ($shop['iconmetro'] != "" && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/metro/" . $shop['iconmetro'])) :?>
                    <img src="/uploads/metro/<?= $shop['iconmetro'] ?>" alt="">
                  <? endif ?>
                  <?= $shop['metro']?>
              </div>
              <div class="table-shop__address"><a href="/shop-stocks/<?=$shop['slug']?>"><?= $shop['address'] ?></a>, <?= round($shop['dist'], 2) ?> км от вас</div>
            </div>
            <div class="col">
              <div class="table-shop__status in-stock"></div>
            </div>
            <div class="col">
              <div class="table-shop__date"></div>
            </div>
            <div class="col">
              <div class="table-shop__date"><?= $shop['worktime'] ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <? else :?>
  <p>Нам не удалось подобрать магазины в радиусе <?= $pres ?> км от вас. Попробуйте выбрать <a href="/adresa-magazinov-on-i-ona-v-moskve-i-mo">тут</a></p>
  <?php endif; ?>
</div></div></div>
