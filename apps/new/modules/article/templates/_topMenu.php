<div class="filter-article">
  <div class="filter-article__title">
    <span>Все</span>
    <div class="btn-arr">
      <svg>
        <use xlink:href="#single-arrow"></use>
      </svg>
    </div>
  </div>
  <div class="filter-article__list">
    <?php foreach ($ArticleCatalogs as $keyArt => $ArticleCattegory): ?>
      <?php //foreach ($ArticleCatalog->getCategory() as $ArticleCattegory): ?>
        <?php if ($active==$ArticleCattegory->getSlug()): ?>
          <span class="filter-article__link active"><?= $ArticleCattegory->getName() ?></span>
        <? else :?>
          <a href="<?= '/sexopedia/category/' . $ArticleCattegory->getSlug() ?>" class="filter-article__link"><?= $ArticleCattegory->getName() ?></a>
        <?php endif; ?>
      <?php //endforeach; ?>
    <?php endforeach; ?>
  </div>
</div>
