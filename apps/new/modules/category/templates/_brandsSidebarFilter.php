<aside class="sidebar-filter">
  <?//die('<pre>!~'.print_r($filters, true));?>
  <form action=""  method="POST" class="form form-catalog js-filters-form" id="js-filters-form">
    <input type="hidden" name="page" value="<?= $page ? $page : 1 ?>" id="form-filter-page">
    <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>" id="form-filter-sort">
    <input type="hidden" name="direction" value="<?= $direction ?>" id="form-filter-direction">
    <?/*<input type="hidden" name="isStock" value="<?= $isStock ?>" id="isStockFilter">*/?>
    <input type="hidden" name="novice" value="<?= isset($isNovice) ? $isNovice : '' ?>" id="form-filter-is-novice">
    <div class="wrap-form">
      <div class="wrap-form-catalog__mob">
        <div class="form-catalog__title-mob">Фильтр</div>
        <div class="btn-close">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.29301 16.2929C-0.0975142 16.6834 -0.0975142 17.3166 0.29301 17.7071C0.683534 18.0976 1.3167 18.0976 1.70722 17.7071L0.29301 16.2929ZM17.7072 1.70711C18.0977 1.31658 18.0977 0.683418 17.7072 0.292894C17.3167 -0.0976307 16.6835 -0.0976307 16.293 0.292894L17.7072 1.70711ZM1.70711 0.292893C1.31658 -0.0976311 0.683417 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L1.70711 0.292893ZM16.2929 17.7071C16.6834 18.0976 17.3166 18.0976 17.7071 17.7071C18.0976 17.3166 18.0976 16.6834 17.7071 16.2929L16.2929 17.7071ZM1.70722 17.7071L17.7072 1.70711L16.293 0.292894L0.29301 16.2929L1.70722 17.7071ZM0.292893 1.70711L16.2929 17.7071L17.7071 16.2929L1.70711 0.292893L0.292893 1.70711Z" fill="#1A1A1A"></path>
          </svg>
        </div>
      </div>
      <? $i=0; ?>
      <?php if (sizeof($menu)): ?>
          <?//='<pre>'.print_r($menu, true).'</pre>' ?>
          <? foreach ($menu as $menuLine) :?>
            <?/*<a href="<?=$menuLine['link']?>" class="form-catalog__title text-lead"><?= $menuLine['name']?></a>*/?>
            <?/*<div class="form-catalog__title text-lead"><?= $menuLine['name']?></div>*/?>
            <? if (sizeof($menuLine['submenu'])):?>
              <? foreach($menuLine['submenu'] as $submenu) :?>
                <? //if($submenu['id']!=47 && $submenu['id']!=68) die(__LINE__."|".__FILE__.'<pre>!'.print_r([$submenu, $filters], true))?>
                <? if (sizeof($submenu['submenu'])):?>
                    <? $isOpen=ILTools::array_in(array_keys($submenu['submenu']), $filters)?>
                    <div class="form-group-block accordion">
                      <div class="form-group__title <?=$isOpen ? 'active' : ''?>">
                        <span><?= $submenu['name'] ?></span>
                        <span class="btn-form-group">
                          <svg>
                            <use xlink:href="#single-arrow"></use>
                          </svg>
                        </span>
                      </div>

                      <div class="form-group__content" <?=$isOpen ? 'style="display: block;"' : ''?>>
                        <? foreach($submenu['submenu'] as $subsubmenu) :?>
                          <div class="custom-check custom-check_color">
                            <?/*<a href="<?=$subsubmenu['link']?>" class="custom-check_label"><?=$subsubmenu['name']?></a>*/?>
                            <div class="check-check_block">
                              <input type="checkbox" name="cat[]" id="cat-<?=$subsubmenu['id']?>" class="check-check_input js-filter-shutter" value="<?=$subsubmenu['id']?>" <?=in_array($subsubmenu['id'], $filters) ? 'checked' : ''?>>
                              <div class="custom-check_shadow"></div>
                            </div>
                            <label for="cat-<?=$subsubmenu['id']?>" class="custom-check_label"><?=$subsubmenu['name']?></label>
                          </div>
                        <? endforeach ?>
                      </div>
                    </div>
                <? else : ?>
                  <div class="form-group-block">
                    <a class="form-group__title" href="<?= $submenu['link']?>"><?= $submenu['name'] ?></a>
                  </div>
                <? endif ?>
              <? endforeach ?>
            <? endif ?>
          <? endforeach ?>
        <? endif ?>
        <br>
      <?php if ($type=='brands'): ?>
        <a href="/manufacturers" class="btn-full btn-full_white btn-full_rad">Все бренды</a>
      <? else :?>
        <a href="/collections" class="btn-full btn-full_white btn-full_rad">Все коллекции</a>
      <?php endif ?>
    </div>
  </form>
</aside>
