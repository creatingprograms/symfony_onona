<? if(sizeof($collections)) : ?>
<aside class="sidebar">
  <div class="sidebar-nav">
    <ul>
      <? foreach ($collections as $collection) :?>
        <li>
          <a href="/collection/<?= $collection->getSlug() ?>" <?= $active==$collection->getSlug() ? 'class="active"' : ''?>><?= $collection->getName()?></a>
        </li>
      <? endforeach ?>
    </ul>
  </div>
</aside>
<? endif ?>
