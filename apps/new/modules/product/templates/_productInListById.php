<? if ($style == 'newbie') : ?>
  <div class="novice-item">
    <a href="/product/<?= $product->getSlug() ?>" class="novice-itme-img box-ratio">
      <div class="-img ">
        <img src="<?= $photo ?>" alt="<?= $product->getName() ?>">
      </div>
    </a>
    <div class="novice-item-content">
      <div class="-title h4"><?= $product->getName() ?></div>
      <div class="-text zero-m">
        <?= $product->getNewbieDescription() ?>
      </div>
      <a href="/product/<?= $product->getSlug() ?>" class="btn-full">Подробнее</a>
    </div>
  </div>
<? endif ?>