<? $i=0; ?>
<?php if (sizeof($menu)): ?>
    <?//='<pre>'.print_r($menu, true).'</pre>' ?>
    <div class="sidebar-nav -collection <?= $isCat ? 'hide-mobile' : ''?>">
        <? if(!$isShowFull):?>
          <ul>
            <?php foreach ($menu as $menuLine): ?>
                <? if(!$isShowFull && $menuLine['isActive'] === false): ?>
                    <?php continue; ?>
                <? endif ?>

                <li class="<?php echo $menuLine['isActive'] ? '-isActive' : '-isUp' ?>">

                    <div class="sidebar-nav-toggle"></div>
                    <a href="<?= $menuLine['link'] ?>"<?/* class="<?= !$i++ ? ' active' : ''?>"*/?>><?= $menuLine['name_part'].' '.$menuLine['name_part_2']?></a>
                    <? if (sizeof($menuLine['submenu'])):?>
                    <ul style="<?php echo $menuLine['isActive'] ? 'display:block;' : '' ?>">
                        <? foreach ($menuLine['submenu'] as $submenu) :?>


                            <li class="<?php echo $submenu['isActive'] ? '-isActive' : '-isUp' ?>">
                                <? if (sizeof($submenu['submenu'])):?>
                                    <div class="sidebar-nav-toggle"></div>
                                <? endif ?>

                                <a href="<?= $submenu['link'] ?>" style="<?php echo !sizeof($submenu['submenu']) && $submenu['isActive'] ? 'color:blue;' : ''; ?>"><?= $submenu['name'] ?></a>

                                <? if (sizeof($submenu['submenu'])):?>
                                    <ul style="<?php/* echo $submenu['isActive'] ? 'display:block;' : '' */?>">
                                        <? foreach ($submenu['submenu'] as $submenu2) :?>
                                            <li>
                                                <a class="last" href="<?= $submenu2['link'] ?>" <?= $submenu2['isActive'] ? 'style="color:blue;"' : "" ?>><?= $submenu2['name'] ?></a>
                                            </li>

                                        <? endforeach ?>
                                    </ul>
                                <? endif ?>
                            </li>

                        <? endforeach ?>
                        <li>
                            <a href="<?= $menuLine['link'] ?>/newprod" class="-action">Новые товары</a>
                        </li>
                        <li>
                            <a href="<?= $menuLine['link'] ?>/relatecategory" class="-action">Лидеры продаж</a>
                        </li>
                        <? endif ?>
                    </ul>
                </li>
            <?php endforeach; ?>
          </ul>
        <? else :?>
          <div class="sidebar-f-accord -up">
            <div class="sidebar-f-accord-up-block">
              <div class="sidebar-f-accord-cont -isProps">
                <div class="sidebar-f-prop">
                  <?php foreach ($menu as $key => $menuLine): ?>
                    <div class="sidebar-f-prop-row">
                      <?
                        $value=str_ireplace('/catalog/', 'service-', $menuLine['link']);
                      ?>
                      <input type="checkbox" id="prop-category-<?=$key?>" name="filter_service_category" value="<?= $value ?>" class="styleCH js-radio" <?= $filter_service_category==$value ? 'checked' : ''?> >
                      <label for="prop-category-<?=$key?>"><?= $menuLine['name_part'].' '.$menuLine['name_part_2']?></label>
                    </div>
                  <? endforeach ?>
                </div>
              </div>
            </div>
          </div>

        <? endif ?>

    </div>
<?php endif; ?>
