<h2>Дополнительные условия купона</h2>
<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_dop_info_products_list">
  <?/*<pre><?=print_r([$conditionsState], true)?></pre>*/?>
  <? if(sizeof($conditions['cats'])) :?>
    <div class="name">Категории</div>
    <? if(sizeof($conditionsState['cats'])) foreach($conditionsState['cats'] as $id) :?>
      <span class="element"><?= $conditions['catsNames'][$id] ?></span>
    <? endforeach ?>
    <div class="js-show-hide" data-target="#cats"></div>
    <div class="hide-block" id="cats">
      <select name="coupons[conditions][cats][]" multiple style="height: 600px;">
      <? $conditionsState['cats'][]=-1; ?>
      <? foreach ($conditions['cats'] as $cat) :?>
      <option value="<?=$cat['id']?>" <?= in_array($cat['id'], $conditionsState['cats']) ? 'selected' :''?>><?=$cat['name']?></option>
        <? if(sizeof($cat['CHILDS'])) foreach ($cat['CHILDS'] as $subCat):?>
          <option value="<?=$subCat['id']?>"  <?= in_array($subCat['id'], $conditionsState['cats']) ? 'selected' :''?>><?=$subCat['name']?></option>
        <? endforeach ?>
      <? endforeach ?>
    </select>
    </div>
  <? endif ?>
  <? if(sizeof($conditions['brands'])) :?>
    <div class="name">Бренды</div>
    <? if(sizeof($conditionsState['brands'])) foreach($conditionsState['brands'] as $id) :?>
      <span class="element"><?=$conditions['brands'][$id]['value']?></span>
    <? endforeach ?>
    <? $conditionsState['brands'][]=-1; ?>
    <div class="js-show-hide" data-target="#brands"></div>
    <div class="hide-block" id="brands">
      <? foreach ($conditions['brands'] as $cat) :?>
        <label>
          <input type="checkbox" name="coupons[conditions][brands][]" value="<?=$cat['id']?>" <?= in_array($cat['id'], $conditionsState['brands']) ? 'checked' :''?>>
          <?=$cat['value']?>
        </label>
      <? endforeach ?>
    </div>
  <? endif ?>
  <? if(sizeof($conditions['collections'])) :?>
    <div class="name">Коллекции</div>
    <? if(sizeof($conditionsState['collections'])) foreach($conditionsState['collections'] as $id) :?>
      <span class="element"><?=$conditions['collections'][$id]['value']?></span>
    <? endforeach ?>
    <? $conditionsState['collections'][]=-1; ?>
    <div class="js-show-hide" data-target="#collections"></div>
    <div class="hide-block" id="collections">
      <? foreach ($conditions['collections'] as $cat) :?>
        <label>
          <input type="checkbox" name="coupons[conditions][collections][]" value="<?=$cat['id']?>" <?= in_array($cat['id'], $conditionsState['collections']) ? ' checked' :''?>>
          <?=$cat['value']?>
        </label>
      <? endforeach ?>
    </div>
  <? endif ?>
  <? if(sizeof($conditions['suitable-for'])) :?>
    <div class="name">Для кого</div>
    <? if(sizeof($conditionsState['suitable-for'])) foreach($conditionsState['suitable-for'] as $id) :?>
      <span class="element"><?=$conditions['suitable-for'][$id]['value']?></span>
    <? endforeach ?>
    <? $conditionsState['suitable-for'][]=-1; ?>
    <div class="js-show-hide" data-target="#suitable-for"></div>
    <div class="hide-block" id="suitable-for">
      <? foreach ($conditions['suitable-for'] as $cat) :?>
        <label>
          <input type="checkbox" name="coupons[conditions][suitable-for][]" value="<?=$cat['id']?>" <?= in_array($cat['id'], $conditionsState['suitable-for']) ? ' checked' :''?>>
          <?=$cat['value']?>
        </label>
      <? endforeach ?>
    </div>
  <? endif ?>
</div>
<style>
  .name{
    font-weight: bold;
    margin-top: 15px;
  }
  .name:first-of-type{
    margin-top: 0;
  }
  .hide-block{
    display: none;
  }
  .hide-block.active{
    display: flex;
    flex-wrap: wrap;
  }
  #sf_admin_container label{
    display: block;
    max-width: 24%;
    width: 24%;
  }
  .js-show-hide{
    cursor: pointer;
    margin-bottom: 10px;
  }
  .js-show-hide:hover{
    color: red;
  }
  .js-show-hide::after{
    content: 'Показать все';
  }
  .js-show-hide.active::after{
    content: 'Свернуть';
  }
  select{
    max-height: 400px;
  }
  .element{
    border-radius: 10px;
    background: #ddd;
    padding: 0 10px;
    display: inline-block;
    margin-right: 5px;
    margin-bottom: 5px;
    cursor: no-drop;
  }
  input[type="checkbox"]{
    margin-right: 5px;
  }
</style>
<script>
$(document).on('ready', function (){
  $('.js-show-hide').on('click', function(e){
    e.preventDefault();
    $(this).toggleClass('active');
    $($(this).data('target')).toggleClass('active');
  })
});
</script>
