<?
  global $isTest;
  $menuCat[]=array_shift($menu);
  if(false)
  // if(true)
    echo('<div style="background: #fff; z-index: 10000;"><pre>'.print_r([
      'menu' => $menuCat,
    ], true).'</pre></div>');

?>
<?php if(sizeof($menuCat)) :?>
  <div class="btn-catalog">
    <span class="btn">
      <svg>
        <use xlink:href="#menu-svg"></use>
      </svg>
    </span>
    <!-- <span>Каталог</span> -->
    <ul class="catalog-menu">
      <? foreach ($menuCat as $link): ?>
        <? if($link['link'] == '/catalog') :?>
        <? //if(true) :?>
          <li class="first-level">
            <a class="first-level-name" href="/catalog">Каталог</a>
            <? if(sizeof($link['submenu']) && $link['submenu']!==false) :?>
              <ul class="second-level-wrapper">
                <? foreach ($link['submenu'] as $subLink) :?>
                  <?
                    $linkName=$subLink['name'];
                    // if(isset($subLink['name_part']) && $subLink['name_part']) $linkName=$subLink['name_part'];
                    // if(isset($subLink['name_part_2']) && $subLink['name_part_2']) $linkName.=' '.$subLink['name_part_2'];
                  ?>
                  <?/*<pre><?=sizeof($subLink) ? print_r(sizeof($subLink['submenu']), true) : ''?></pre>*/?>
                  <li class="second-level-item colored <?= sizeof($subLink['submenu']) ? '-has-submenu' : ''?> <?= $subLink['class'] ? $subLink['class']  : ''?>">
                    <a class="name second-level-name <?=$path == $subLink['link'] ? ' active' : ''?>" href="<?= $subLink['link'] ?>"<?= $subLink['is_noindex'] ? ' rel="nofollow"' : ''?><?= $subLink['is_target_blank'] ? ' target="_blank"' : ''?>>
                      <?= $linkName ?>
                    </a>
                    <? if(sizeof($subLink['submenu'])) :?>
                      <div class="third-level-wrapper">
                        <div class="third-level-container">
                          <? foreach ($subLink['submenu'] as $subSubLink) :?>
                            <div class="third-level-item colored <?= sizeof($subSubLink['submenu']) ? '-has-submenu' : ''?>">
                              <a class="name third-level-name <?=$path == $subSubLink['link'] ? ' active' : ''?>" href="<?= $subSubLink['link'] ?>" <?= $subSubLink['is_noindex'] ? ' rel="nofollow"' : ''?><?= $subSubLink['is_target_blank'] ? ' target="_blank"' : ''?>>
                                <?=$subSubLink['name']?>
                              </a>
                              <?php if (sizeof($subSubLink['submenu'])): ?>
                                <div class="fourth-level-wrapper">
                                  <div class="fourth-level-container">
                                    <?$counter=0;?>
                                    <?php foreach ($subSubLink['submenu'] as $subSubSubLink): ?>
                                      <a class="fourth-level-item name colored <?=$path == $subSubSubLink['link'] ? ' active' : ''?>" href="<?=$subSubSubLink['link']?>"><?=$subSubSubLink['name']?></a>
                                      <?/* if($counter++ == 2 && sizeof($subSubLink['submenu'])>3) :?>
                                        <div class="js-hide-show-next show-more">Еще</div>
                                        <div class="mfp-hide">
                                      <? endif */?>
                                    <?php endforeach; ?>
                                    <?/* if($counter>2 && sizeof($subSubLink['submenu'])>3) :?>
                                      </div>
                                    <? endif */?>
                                  </div>
                                </div>
                              <?php endif; ?>
                            </div>
                          <? endforeach ?>
                        </div>
                      </div>
                    <? endif ?>
                  </li>
                <? endforeach ?>
              </ul>
            <? endif ?>
          </li>
        <? endif ?>
      <? endforeach?>
    </ul>
  </div>
<?php endif ?>
<? if(sizeof($menu)) :?>
  <nav class="bottom-nav">
    <ul>
      <? foreach ($menu as $link): ?>
        <li>
        <a href="<?=$link['link']?>"><?=$link['name']?></a>
        <? if(sizeof($link['submenu']) && $link['submenu']!==false) :?>
              <ul class="second-level-wrapper">
                <? foreach ($link['submenu'] as $subLink) :?>
                  <?
                    $linkName=$subLink['name'];
                    // if(isset($subLink['name_part']) && $subLink['name_part']) $linkName=$subLink['name_part'];
                    // if(isset($subLink['name_part_2']) && $subLink['name_part_2']) $linkName.=' '.$subLink['name_part_2'];
                  ?>
                  <?/*<pre><?=sizeof($subLink) ? print_r(sizeof($subLink['submenu']), true) : ''?></pre>*/?>
                  <li class="second-level-item colored <?= sizeof($subLink['submenu']) ? '-has-submenu' : ''?> <?= $subLink['class'] ? $subLink['class']  : ''?>">
                    <a class="name second-level-name <?=$path == $subLink['link'] ? ' active' : ''?>" href="<?= $subLink['link'] ?>"<?= $subLink['is_noindex'] ? ' rel="nofollow"' : ''?><?= $subLink['is_target_blank'] ? ' target="_blank"' : ''?>>
                      <?= $linkName ?>
                    </a>
                  </li>
                <? endforeach ?>
              </ul>
            <? endif ?>
        </li>
      <? endforeach ?>
    </ul>
  </nav>
<? endif ?>
