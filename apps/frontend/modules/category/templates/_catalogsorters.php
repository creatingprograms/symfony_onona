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
      <?/*
      <input type="radio" name="cal-list-filtr" id="clf-4" data-sort="name" <?= $sortOrder == "name" ? "checked" : "" ?> class="js-tests-sort<?=$set==3 ? '-cat' : ''?>" data-direction="asc">
      <label for="clf-4">названию</label>
      <div class="cat-list-filtr-mob-text">названию</div>*/?>

      <!-- Попап для мобильника -->
      <div class="cat-list-filtr-mob-pop">
        <label for="clf-1">популярности</label>
        <label for="clf-2">новизне</label>
        <label for="clf-3">цене ↓</label>
        <label for="clf-5">цене ↑</label>
        <?/*<label for="clf-4">названию</label>*/?>
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
