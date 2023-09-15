<? $i=0; ?>
<?php if (sizeof($menu)): ?>
  <div class="sidebar-f sidebar-f--full">
    <div class="sidebar-nav -collection <?= $isCat ? 'hide-mobile' : ''?>">
      <ul>
        <li class="-isActive">
          <div class="sidebar-nav-toggle"></div>
          <a href="<?= $menuLine['link'] ?>">
            <?= 'ИНТЕРНЕТ СЕКС ШОП'?>
          </a>
          <?php foreach ($menu as $menuLine): ?>
            <ul style="display: block;">
              <li class="-isUp">
                <div class="sidebar-nav-toggle"></div>
                <a href="<?= $menuLine['link'] ?>"<?/* class="<?= !$i++ ? ' active' : ''?>"*/?>>
                  <?= $menuLine['name']?>
                </a>
                <? if (sizeof($menuLine['submenu'])):?>
                  <ul>
                    <? foreach ($menuLine['submenu'] as $submenu) :?>
                      <li class="-isActive">
                        <a href="<?= $submenu['link'] ?>"><?= $submenu['name'] ?></a>
                      </li>
                    <? endforeach ?>
                  </ul>
                <? endif ?>
              </li>
            </ul>
          <? endforeach ?>
        </li>
      </ul>
    </div>
  </div>
<? endif ?>
