<aside class="sidebar -sexopedia">
  <div class="sidebar-up-but">
    <a href="#pop-1" class="but">
      все статьи
    </a>
  </div>
  <div class="sidebar-sexopedia" data-upblock="#pop-1">
    <?php foreach ($ArticleCatalogs as $keyArt => $ArticleCatalog): ?>
      <a href="<?= $keyArt == 0 ? "/sexopedia" : "/sexopedia/catalog/" . $ArticleCatalog->getSlug() ?>" class="sidebar-sexopedia-h">
        <?= $ArticleCatalog->getName() ?>
      </a>
      <ul class="sidebar-sexopedia-nav">
        <? foreach ($ArticleCatalog->getCategory() as $ArticleCattegory):?>
        <li>
          <a <?= $active==$ArticleCattegory->getSlug() ? 'class="active" ' : ''?>href="<?= '/sexopedia/category/' . $ArticleCattegory->getSlug() ?>"><?= $ArticleCattegory->getName() ?></a>
        </li>
        <? endforeach ?>
      </ul>
    <?php endforeach; ?>
  </div>
</aside>
