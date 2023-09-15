<form action="" class="form form-sorting form-sorting_view">
  <div class="form-sorting__title">Показать:</div>
  <div class="sorting-block">
    <div class="sorting-type">
      <input type="radio" name="view" id="sorting-type-3" value="list" <?=$view=='list' ? 'checked' : ''?> class="js-change-view">
      <label for="sorting-type-3" class="sorting-type__label -list">
      <svg>
                  <use xlink:href="#list-i"></use>
                </svg>Списком</label>
    </div>
    <div class="sorting-type">
      <input type="radio" name="view" id="sorting-type-4" value="card" <?=$view!='list' ? 'checked' : ''?> class="js-change-view">
      <label for="sorting-type-4" class="sorting-type__label -cart">
      <svg>
                  <use xlink:href="#cart-list-i"></use>
                </svg>Карточками</label>
    </div>
  </div>
</form>
