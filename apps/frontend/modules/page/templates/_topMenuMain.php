<?php
  $isTest=false;
  // $isTest=true;

  if($isTest)
    echo('<div style="background: #fff; z-index: 10000;"><pre>'.print_r([
      'menu' => $menu,
    ], true).'</pre></div>');
?>
<?php if(sizeof($menu)) :?>
  <ul id="mainNav">
    <? foreach ($menu as $link): ?>
      <li>
        <? //= $link['is_noindex'] ? '<noindex>' : '' ?>
        <a <?=  $link['link']=='/category/skidki_do_60_percent' ? 'class="active"' : ''?> href="<?= $link['link']=='/catalog' ? 'javascript:void()' : $link['link'] ?>"<?= $link['is_noindex'] ? ' rel="nofollow"' : ''?><?= $link['is_target_blank'] ? ' target="_blank"' : ''?>><?=$link['name']?></a>
        <? //= $link['is_noindex'] ? '</noindex>' : '' ?>
        <? if(sizeof($link['submenu']) && $link['submenu']!==false) :?>
          <? if (isset($link['is_catalog'])) :?>
            <div class="main-nav-pop -full">
              <? foreach ($link['submenu'] as $subLink) :?>
                <div class="main-nav-pop-col">
                  <div class="main-nav-pop-h">
                    <a href="<?= $subLink['link'] ?>"<?= $subLink['is_noindex'] ? ' rel="nofollow"' : ''?><?= $subLink['is_target_blank'] ? ' target="_blank"' : ''?>><?=$subLink['name']?></a>
                  </div>
                    <? if(sizeof($subLink['submenu'])) :?>
                      <ul>
                        <? foreach ($subLink['submenu'] as $subSubLink) :?>
                          <li>
                            <? //= $subLink['is_noindex'] ? '<noindex>' : '' ?>
                            <a href="<?= $subSubLink['link'] ?>"<?= $subSubLink['is_noindex'] ? ' rel="nofollow"' : ''?><?= $subSubLink['is_target_blank'] ? ' target="_blank"' : ''?>><?=$subSubLink['name']?></a>
                            <? //= $subLink['is_noindex'] ? '</noindex>' : '' ?>
                          </li>
                        <? endforeach ?>
                      </ul>
                    <? endif ?>
                </div>
              <? endforeach ?>
            </div>
          <? else: ?>
            <div class="main-nav-pop">
              <ul>
                <? foreach ($link['submenu'] as $subLink) :?>
                  <li>
                    <? //= $subLink['is_noindex'] ? '<noindex>' : '' ?>
                    <a href="<?= $subLink['link'] ?>"<?= $subLink['is_noindex'] ? ' rel="nofollow"' : ''?><?= $subLink['is_target_blank'] ? ' target="_blank"' : ''?>><?=$subLink['name']?></a>
                    <? //= $subLink['is_noindex'] ? '</noindex>' : '' ?>
                  </li>
                <? endforeach ?>
              </ul>
            </div>
          <? endif ?>
        <? endif ?>
      </li>
    <?php endforeach; ?>
    </ul>
<? endif ?>
