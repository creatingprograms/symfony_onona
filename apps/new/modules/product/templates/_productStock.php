<div class="stock">
  <span>
    <?php if ($product->getCount()>0): ?>
      В наличии
      <?php if (!empty($shopsInStock)) :?>
        в</span>
        <a class="link js-popup-form" href="#popup-shops-<?= $product->getId()?>"><?//= sizeof($shopsInStock)?> <?=ILTools::getWordForm(sizeof($shopsInStock), 'магазин')?></a>
        <div class="mfp-hide white-popup-block block-popup" id="popup-shops-<?= $product->getId()?>">
          <div class="h2 block-popup__title" style="font-size: 150%;"><?= $product->getName()?> в магазинах:</div><br/>
          <?php foreach ($shopsInStock as $shop): ?>
            <a class="shops-link" href="/shops/<?=mb_strtolower($shop->getSlug())?>"><?=$shop->getName()?></a>
          <?php endforeach; ?>
        </div>
      <?php endif ?>
    <?php else : ?>
      Под заказ</span>
    <?php endif; ?>
</div>
