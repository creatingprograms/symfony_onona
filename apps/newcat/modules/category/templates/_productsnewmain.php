<? if ($product->getId() != ""):

    $prodName=str_replace(array("'", '"', '\\'), "", $product->getName());
    $price=$product->getPrice();
    $priceText=number_format ($price, 0, '', ' ');
    $oldPrice = $product->getOldPrice();
    if ($oldPrice && $oldPrice > $price){
      $oldPriceText=number_format ($oldPrice, 0, '', ' ');
      $attraction = number_format ($oldPrice-$price, 0, '', ' ');
    }
    else $oldPriceText='';
    // $timerGetPhotosCommentsChildren->addTime();
    ?>
    <div class="new-item swiper-slide">
      <div class="new-item-container<?=$product->getCount() ? '' : ' notcount' ?>">
        <div class="new-item-labels">
          <span class="new-item-label-faved <?= $prodInDesire ? 'active' : ''?>"></span>
          <span class="new-item-label-compared <?= $prodInCompare ? 'active' : ''?>"></span>
          <span class="new-item-label-bought <?= $prodInCart ? 'active' : ''?>"></span>
        </div>
        <div class="new-item-title">
          <a href="/product/<?=$product->getSlug()?>"><?= $prodName  ?></a>
        </div>
        <div class="new-item-image">
          <a href="/product/<?=$product->getSlug()?>">
            <img src="/uploads/photo/thumbnails_250x250/<?=$photoFilename?>" alt="<?= $prodName  ?>">
          </a>
        </div>
        <div class="new-item-price-wrapper">
          <div class="new-item-price">
            <span class="new-item-price-value"><?=$priceText?></span>
            <span class="new-item-price-currency"></span>
          </div>
          <? if($oldPriceText) :?>
            <div class="old-price">
              <span><?= $oldPriceText ?></span>
              <span class="new-item-price-currency"></span>
            </div>
            <div class="attraction">
              <span>Ваша выгода <?=$attraction?> </span><span class="new-item-price-currency"></span>
            </div>
          <? endif?>
        </div>
        <nav class="new-item-actions">
          <a class="new-item-actions-more" href="/product/<?=$product->getSlug()?>"></a>
          <div
            class="new-item-actions-buy gtm-list-item js-mobile-button-buy-new <?= $prodInCart ? 'added' : 'gtm-list-add-to-basket'?>"
            <?=$listname ? ' data-list="'.$listname.'"' : ''?>
            data-id="<?=$product->getId()?>"
            data-name="<?= $prodName  ?>"
            data-image="/uploads/photo/thumbnails_250x250/<?=$photoFilename?>"
            data-price="<?=$product->getPrice()?>"
            data-bonus-add="<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>"
          ></div>
          <a class="new-item-actions-fav <?= $prodInDesire ? '' : 'js-button-add-desire'?>" data-id="<?=$product->getId()?>" data-title="Добавить в&nbsp;список желаний" href="/desire">Добавить в&nbsp;список желаний</a>
          <a class="new-item-actions-compare <?= $prodInCompare ? '' : 'js-button-add-compare'?>" data-id="<?=$product->getId()?>" data-title="Добавить в&nbsp;сравнение" href="/compare">Добавить в&nbsp;сравнение</a>
        </nav>
      </div>
    </div>

<? endif; ?>
<?/*
<pre><?=print_r([
  'inChart'=>$prodInCart,
  'inDesire'=>$prodInDesire,
  'inCompare'=>$prodInCompare,
], true)?></pre>
*/?>
