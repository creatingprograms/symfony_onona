<? $levelIn=$i=0; ?>
<?php if (sizeof($menu)): ?>
    <?//='<pre>'.print_r($menu, true).'</pre>' ?>
    <? foreach ($menu as $menuLine) :?>
      <?/*<a href="<?=$menuLine['link']?>" class="form-catalog__title text-lead"><?= $menuLine['name']?></a>*/?>
      <? if($menuLine['isActive']) $i++; ?>
      <div class="form-catalog__title text-lead"><?= $menuLine['name']?></div>
      <? if (sizeof($menuLine['submenu'])):?>
        <? foreach($menuLine['submenu'] as $submenu) :?>
          <? if (sizeof($submenu['submenu'])):?>
            <? if($submenu['isActive']) $i++; ?>
            <? if($submenu['isActive']) $levelIn++; ?>
              <div class="form-group-block accordion">
                <div class="form-group__title<?= $submenu['isActive'] ? ' active' : ''?>">
                  <span><?= $submenu['name'] ?></span>
                  <span class="btn-form-group">
                    <svg>
                      <use xlink:href="#single-arrow"></use>
                    </svg>
                  </span>
                </div>

                <div class="form-group__content"<?= $submenu['isActive'] ? ' style="display: block;"' : ''?>>
                  <? foreach($submenu['submenu'] as $subsubmenu) :?>
                    <? if($subsubmenu['isActive']) $levelIn++; ?>
                    <div class="custom-check custom-check_color">
                      <a href="<?=$subsubmenu['link']?>" class="custom-check_label<?= $subsubmenu['isActive'] ? ' active' : ''?>"><?=$subsubmenu['name']?></a>
                      <?/*<div class="check-check_block">
                        <input type="checkbox" name="" id="" class="check-check_input">
                        <div class="custom-check_shadow"></div>
                      </div>
                      <label for="" class="custom-check_label"><?=$subsubmenu['name']?></label>*/?>
                    </div>
                  <? endforeach ?>
                </div>
              </div>
          <? else : ?>
            <? if($submenu['isActive']) $levelIn++; ?>
            <div class="form-group-block">
              <a class="form-group__title" href="<?= $submenu['link']?>"><?= $submenu['name'] ?></a>
            </div>
          <? endif ?>
        <? endforeach ?>
      <? endif ?>
    <? endforeach ?>


<?php endif; ?>
<?/*<h1><?= 'wr = '.((!($levelIn < $i)) ? 'true': 'false'). '<br>i='.$i.'|level='.$levelIn ?></h1>*/?>
<? slot('wrap_aside', !($levelIn < $i)) ?>
