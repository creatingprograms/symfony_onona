<?php
  $isTest=false;
  // $isTest=true;

  if($isTest)
    echo('<div style="background: #fff; z-index: 10000;"><pre>'.print_r([
      'menu' => $menu,
    ], true).'</pre></div>');
?>
<?php if(sizeof($menu)) :?>
  <nav class="new-menu">
    <? foreach ($menu as $link): ?>
      <div class="new-menu-item">
        <?= $link['link'] ? '<a href="'.$link['link'].'">' : ''?>
          <span class="<?=sizeof($link['submenu']) ? 'js-top-menu-new header' : '' ?>"><?=$link['name']?></span>
        <?= $link['link'] ? '</a>' : '' ?>
        <? if (sizeof($link['submenu'])) : ?>
          <div class="submenu-first">
            <? foreach ($link['submenu'] as $subline): ?>
              <? if (sizeof($subline['submenu'])) : ?>
                <div class="submenu-second">
                  <div class="header">
                    <?= $subline['link'] ? '<a href="'.mb_strtolower($subline['link'], 'utf-8').'">' : ''?>
                      <?=$subline['name']?>
                    <?= $subline['link'] ? '</a>' : '' ?>
                  </div>
                  <? foreach ($subline['submenu'] as $subline2): ?>
                    <a href="<?=mb_strtolower($subline2['link'], 'utf-8')?>"><span class="deco"></span><?=$subline2['name']?></a>
                  <?php endforeach; ?>
                </div>
              <? else : ?>
                <?= $subline['link'] ? '<a href="'.mb_strtolower($subline['link'], 'utf-8').'">' : ''?>
                  <?=$subline['name']?>
                <?= $subline['link'] ? '</a>' : '' ?>
              <? endif ?>
            <?php endforeach; ?>
          </div>
        <? endif ?>
      </div>
    <?php endforeach; ?>
  </nav>
<? endif ?>
