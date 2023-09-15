<? if(sizeof($manufacturers)) : ?>
<aside class="sidebar">
  <div class="sidebar-nav">
    <ul>
      <? foreach ($manufacturers as $manufacturer) :?>
        <li>
          <a href="/manufacturer/<?= $manufacturer->getSlug() ?>" <?= $active==$manufacturer->getSlug() ? 'class="active"' : ''?>><?= $manufacturer->getName()?></a>
        </li>
      <? endforeach ?>
    </ul>
  </div>
</aside>
<? endif ?>
