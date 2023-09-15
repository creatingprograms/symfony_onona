<?
global $isTest;
$menuCat[] = array_shift($menu);
if (false)
  // if(true)
  echo ('<div style="background: #fff; z-index: 10000;"><pre>' . print_r([
    'menu' => $menuCat,
  ], true) . '</pre></div>');
$i = 0;

?>
<?php if (sizeof($menuCat)) : ?>
  <div class="btn-catalog">
    <span class="btn">
      <svg>
        <use xlink:href="#menu-svg"></use>
      </svg>
    </span>
    <!-- <span>Каталог</span> -->
    <ul class="catalog-menu-new">
      <? foreach ($menuCat as $link) : ?>
        <? if ($link['link'] == '/catalog') : ?>
          <li class="first-level <? //='hover'
                                  ?>">
            <a class="first-level-name" href="/catalog">Каталог</a>
            <? if (sizeof($link['submenu']) && $link['submenu'] !== false) : ?>
              <ul class="second-level-wrapper">
                <? foreach ($link['submenu'] as $subLink) : ?>
                  <?
                  $linkName = $subLink['name'];
                  ?>
                  <li class="second-level-item colored <?= sizeof($subLink['submenu']) ? '-has-submenu' : '' ?> <?= $subLink['class'] ? $subLink['class']  : '' ?> <? //= (7==$i++) ? 'hover' : ''
                                                                                                                                                                  ?>">
                    <a class="name second-level-name <?= $path == $subLink['link'] ? ' active' : '' ?>" href="<?= $subLink['link'] ?>" <?= $subLink['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $subLink['is_target_blank'] ? ' target="_blank"' : '' ?>>
                      <?= $linkName ?>
                    </a>
                    <? if (sizeof($subLink['submenu'])) : ?>
                      <div class="third-level-wrapper <?= !empty($subLink['banner']) ? 'has-banner' : '' ?>">
                        <? if (!empty($subLink['banner'])) : ?>
                          <a href="<?= $subLink['banner']['link'] ? $subLink['banner']['link'] : 'javascript: void();' ?>" class="third-level-banner" style="background-image:url('<?= $subLink['banner']['img'] ?>');">
                          </a>
                        <? endif ?>
                        <div class="third-level-container">
                          <? foreach ($subLink['submenu'] as $subSubLink) : ?>
                            <div class="third-level-item colored <?= sizeof($subSubLink['submenu']) ? '-has-submenu' : '' ?>">
                              <a class="name third-level-name <?= $path == $subSubLink['link'] ? ' active' : '' ?>" href="<?= $subSubLink['link'] ?>" <?= $subSubLink['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $subSubLink['is_target_blank'] ? ' target="_blank"' : '' ?>>
                                <?= $subSubLink['name'] ?>
                              </a>
                              <?php if (sizeof($subSubLink['submenu'])) : ?>
                                <div class="fourth-level-wrapper">
                                  <div class="fourth-level-container">
                                    <? $counter = 0; ?>
                                    <?php foreach ($subSubLink['submenu'] as $subSubSubLink) : ?>
                                      <a class="fourth-level-item name colored <?= $path == $subSubSubLink['link'] ? ' active' : '' ?>" href="<?= $subSubSubLink['link'] ?>"><?= $subSubSubLink['name'] ?></a>
                                    <?php endforeach; ?>
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

                <? if (!empty($link['banner'])) : ?>
                  <li class="second-level-banner">
                    <a href="<?= $link['banner']['link'] ? $link['banner']['link'] : 'javascript: void();' ?>" class="second-level-banner-img" style="background-image:url('<?= $link['banner']['img'] ?>');">
                    </a>
                  </li>
                <? endif ?>
              </ul>
            <? endif ?>
          </li>
        <? endif ?>
      <? endforeach ?>
    </ul>
  </div>
<?php endif ?>

<? if (sizeof($menu)) : ?>
  <nav class="bottom-nav">
    <ul>
      <? foreach ($menu as $link) : ?>
        <li>
          <a href="<?= $link['link'] ?>" <?= !empty($link['counter_target']) ? ' class="js-yandex-send" data-target="' . $link['counter_target'] . '"' : '' ?>><?= $link['name'] ?></a>
          <? if (sizeof($link['submenu']) && $link['submenu'] !== false) : ?>
            <ul class="second-level-wrapper">
              <? foreach ($link['submenu'] as $subLink) : ?>
                <?
                $linkName = $subLink['name'];
                ?>
                <li class="second-level-item colored <?= sizeof($subLink['submenu']) ? '-has-submenu' : '' ?> <?= $subLink['class'] ? $subLink['class']  : '' ?>">
                  <a class="name second-level-name <?= $path == $subLink['link'] ? ' active' : '' ?><?= !empty($subLink['counter_target']) ? ' js-yandex-send' : '' ?>" <?= !empty($subLink['counter_target']) ? 'data-target="' . $subLink['counter_target'] . '"' : '' ?> href="<?= $subLink['link'] ?>" <?= $subLink['is_noindex'] ? ' rel="nofollow"' : '' ?><?= $subLink['is_target_blank'] ? ' target="_blank"' : '' ?>>
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