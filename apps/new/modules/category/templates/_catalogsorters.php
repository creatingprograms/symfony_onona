<a href="/tinkoff" class="tinkoff_link_mobile">
  <img src="/frontend/images/tinkoff/mobile_aside.png">
</a>
<form action="<?= $link ?>" class="form form-sorting  js-sorter-form">
  <div class="form-sorting__title">Сортировать по:</div>

  <div class="sorting-block">
    <? if($set==2) ://Для каталога (id=3) меняем класс кнопок, т.к. они работают иначе?>
        <div class="sorting-type">
          <input type="radio" name="sort" id="sorting-type-3" class="js-tests-sort" data-sort="date" <?=$sortOrder != "rating" ? 'checked' : ''?>>
          <label for="sorting-type-3" class="sorting-type__label -novelty">по новизне</label>
        </div>
        <div class="sorting-type">
          <input type="radio" name="sort" id="sorting-type-4" class="js-tests-sort" data-sort="rating" <?=$sortOrder == "rating" ? 'checked' : ''?>>
          <label for="sorting-type-4" class="sorting-type__label -rating">по рейтингу</label>
        </div>
    <? endif ?>
    <? if($set==1 || $set==3) ://Для каталога (id=3) меняем класс кнопок, т.к. они работают иначе?>
      <div class="sidebar-mob-but">
        <svg>
          <use xlink:href="#filtrIconNew"></use>
        </svg>
        Фильтры
      </div>
      <div class="sorting-type">
        <input type="radio" name="cal-list-filtr" id="sorting-type-1" data-sort="rating" <?= $sortOrder == "rating" ? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>">
        <label for="sorting-type-1" class="sorting-type__label -popularity">по популярности</label>
      </div>
      <div class="sorting-type">
        <input type="radio" name="cal-list-filtr" id="sorting-type-2" data-sort="price" <?= ($sortOrder == "price" && $direction=="asc" )? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>" data-direction="asc">
        <label for="sorting-type-2" class="sorting-type__label -costs -up">по стоимости
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line y1="2.25" x2="4" y2="2.25" stroke-width="1.5" />
            <line y1="6.25" x2="8" y2="6.25" stroke-width="1.5" />
            <line y1="10.25" x2="12" y2="10.25" stroke-width="1.5" />
          </svg>
        </label>
      </div>
      <div class="sorting-type">
        <input type="radio" name="cal-list-filtr" id="sorting-type-5" data-sort="price" <?= ($sortOrder == "price" && $direction=="desc" )? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>" data-direction="desc">
        <label for="sorting-type-5" class="sorting-type__label -costs -down">по стоимости
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line y1="2.25" x2="4" y2="2.25" stroke-width="1.5" />
            <line y1="6.25" x2="8" y2="6.25" stroke-width="1.5" />
            <line y1="10.25" x2="12" y2="10.25" stroke-width="1.5" />
          </svg>
        </label>
      </div>
      <div class="sorting-type">
        <input type="radio" name="cal-list-filtr" id="sorting-type-3" data-sort="date"<?= $sortOrder == "date" ? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>">
        <label for="sorting-type-3" class="sorting-type__label -novelty">по новизне</label>
      </div>
      <?/*<div class="sorting-type">
        <input type="radio" name="cal-list-filtr" id="sorting-type-4" data-sort="rating"<?= $sortOrder == "rating" ? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>">
        <label for="sorting-type-4" class="sorting-type__label -rating">по рейтингу</label>
      </div>*/?>
      <?/*<div class="sorting-type">
        <input type="radio" name="cal-list-filtr" id="sorting-type-6" data-sort="name"<?= $sortOrder == "name" ? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>">
        <label for="sorting-type-6" class="sorting-type__label -rating">по названию</label>
      </div>*/?>
    <? endif ?>
  </div>
</form>
<?/*
<form action="<?= $link ?>" class="cat-list-filtr js-sorter-form">
  <span class="-sort"></span>
  <div class="cat-list-filtr-mob-col">
    <!-- div-ы после label нужны для мобильника, на мобильника все это превращается в селект, по другому не получится -->
    <? if($set==1 || $set==3) ://Для каталога (id=3) меняем класс кнопок, т.к. они работают иначе?>
      <input type="radio" name="cal-list-filtr" id="clf-1" data-sort="rating" <?= $sortOrder == "rating" ? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>">
      <label for="clf-1">популярности</label>
      <div class="cat-list-filtr-mob-text">популярности</div>

      <input type="radio" name="cal-list-filtr" id="clf-2" data-sort="date"<?= $sortOrder == "date" ? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>">
      <label for="clf-2">новизне</label>
      <div class="cat-list-filtr-mob-text">новизне</div>

      <input type="radio" name="cal-list-filtr" id="clf-3" data-sort="price" <?= ($sortOrder == "price" && $direction=="asc" )? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>" data-direction="asc">
      <label for="clf-3">цене ↓</label>
      <div class="cat-list-filtr-mob-text">цене ↓</div>

      <input type="radio" name="cal-list-filtr" id="clf-5" data-sort="price" <?= ($sortOrder == "price" && $direction=="desc" )? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>" data-direction="desc">
      <label for="clf-5">цене ↑</label>
      <div class="cat-list-filtr-mob-text">цене ↑</div>

      <!-- Попап для мобильника -->
      <div class="cat-list-filtr-mob-pop">
        <label for="clf-1">популярности</label>
        <label for="clf-2">новизне</label>
        <label for="clf-3">цене ↓</label>
        <label for="clf-5">цене ↑</label>
      </div>
    <? endif ?>

    <? if($set==2) :?>
      <input type="radio" name="cal-list-filtr" id="clf-1" <?=$sortOrder != "rating" ? 'checked' : ''?> class="js-tests-sort" data-sort="date">
      <label for="clf-1">новые</label>
      <div class="cat-list-filtr-mob-text">новые</div>
      <input type="radio" name="cal-list-filtr" id="clf-2" <?=$sortOrder == "rating" ? 'checked' : ''?> class="js-tests-sort" data-sort="rating">
      <label for="clf-2">популярные</label>
      <div class="cat-list-filtr-mob-text">популярные</div>
      <!-- Попап для мобильника -->
      <div class="cat-list-filtr-mob-pop">
        <label for="clf-1">новые</label>
        <label for="clf-2">популярные</label>
      </div>
    <? endif ?>

  </div>
</form>
*/?>
