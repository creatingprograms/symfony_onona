<? if(sizeof($brands)) :?>
  <div class="sidebar-brend">
    <div class="sidebar-brend-h">
      бренды
    </div>
    <div class="sidebar-brend-link">
      <?php foreach ($brands as $brand): ?>
        <div class="sidebar-brend-link-row">
          <a href="/manufacturer/<?= mb_strtolower($brand->getSlug(), 'UTF-8') ?>" class="sidebar-brend-link-item">
            <img src="/uploads/manufacturer/<?= $brand->getImage() ?>" alt="<?= $brand->getName() ?>"> <?= $brand->getName() ?>
          </a>
        </div>
      <?php endforeach; ?>
      <a href="/manufacturers" class="sidebar-brend-link-all">Все бренды >></a>
    </div>
  </div>
<? endif ?>
