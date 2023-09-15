<?php if(sizeof($menu)) :?>
  <nav class="top-nav -largeTabletHide">
    <ul>
      <? foreach ($menu as $link): ?>
        <li>
          <a href="<?= $link['link'] ?>" <?=$path == $link['link'] ? 'class="active"' : ''?>><?=$link['name']?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
<? endif ?>
