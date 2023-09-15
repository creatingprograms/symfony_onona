<?
  $needShowClear = false;
  $i=1;
  $all="Все";
  // $all="Выбрать";
?>
<div class="reviews-list__filter">
  <form method="POST" action="/reviews" class="js-pagination-form">
    <input type="hidden" name="page" value="<?= $page ?>">
    <div class="reviews-list__filter_wrap">
      <?/*<div class="reviews-list__filter_el">
        <div class="label">Для кого</div>
        <div class="field">
          <div class="my-custom-select">
            <select class="js-review-select" name="from-who">
              <option value="0" data-id="0"><?=$all?></option>
              <?php foreach ($fromWho as $value => $option): ?>
                <option value="<?=$value?>" <?= $filter['from-who']==$value ? 'selected' : ''?>><?= $option ?></option>
                <? if($filter['from-who']==$option['id']) $needShowClear = true;?>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <? $i=1 ?>
      <div class="reviews-list__filter_el">
        <div class="label">От кого</div>
        <div class="field">
          <div class="my-custom-select">
            <select class="js-review-select" name="for-who">
              <option value="0" data-id="0"><?=$all?></option>
              <?php foreach ($forWho as $value => $option): ?>
                <option value="<?=$value?>" <?= $filter['for-who']==$value ? 'selected' : ''?>><?= $option ?></option>
                <? if($filter['for-who']==$option['id']) $needShowClear = true;?>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <? $i=1 */?>
      <div class="reviews-list__filter_el">
        <div class="label">Бренды</div>
        <div class="field">
          <div class="my-custom-select">
            <select class="js-review-select" name="brand">
              <option value="0" data-id="0"><?=$all?></option>
              <?php foreach ($brands as $option): ?>
                <option data-id="<?= $i++?>" value="<?=$option['id']?>" <?= $filter['brand']==$option['id'] ? 'selected' : ''?>><?= $option['name'] ?></option>
                <? if($filter['brand']==$option['id']) $needShowClear = true;?>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <? $i=1 ?>
      <div class="reviews-list__filter_el">
        <div class="label">Разделы каталога</div>
        <div class="field">
          <div class="my-custom-select">
            <select class="js-review-select" name="catalog">
              <option value="0" data-id="0"><?=$all?></option>
              <?php foreach ($catalog as $option): ?>
                <option data-id="<?= $i++?>" value="<?=$option['id']?>" <?= $filter['catalog']==$option['id'] ? 'selected' : ''?>><?= $option['name'] ?></option>
                <? if($filter['catalog']==$option['id']) $needShowClear = true;?>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
    </div>
    <?php if ($needShowClear): ?>
      <div class="js-form-clear reviews-list__clear_button">Очистить фильтр</div>
    <?php endif; ?>
  </form>
</div>
