<? if(sizeof($brands)) :?>
    <aside class="sidebar-filter brands">
      <div class="h3">Производители</div>
      <noindex>
      <?php foreach ($brands as $brand): ?>
        <div class="sidebar-brend-link-row">
          <a href="/manufacturer/<?= mb_strtolower($brand->getSlug(), 'UTF-8') ?>" class="sidebar-brend-link-item <?=$current==$brand->getSlug() ? 'strong' : ''?>">
            <?= $brand->getName() ?>
          </a>
        </div>
      <?php endforeach; ?>
      <!-- <a href="/manufacturers" class="sidebar-brend-link-all">Все бренды >></a> -->
    </noindex>
    </aside>
<? endif ?>
