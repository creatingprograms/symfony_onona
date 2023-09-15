<? $shops = $pager->getResults(); ?>
<? if(sizeof($shops)) :?>
  <!------------------------- page data ----------------------------->
  <? foreach($shops as $shop) :?>
    <div class="shops-showcase-item">
      <a href="<?= url_for('shops/'.$shop->getSlug()) ?>" class="shops-showcase-img">
        <img alt="Магазин для взрослых Он и Она,<?=$shop->getAddress()?>"
        src="/uploads/assets/images/<?=$shop->getPreviewImage() ? $shop->getPreviewImage() : '001.jpg'?>">
      </a>
      <div class="shops-showcase-desc">
        <div class="shops-page-adres-h -icon-1">
          <a href="<?= url_for('shops/'.$shop->getSlug()) ?>">
            <img src="<?=$shop->getIconmetro() ? '/uploads/metro/'.$shop->getIconmetro() : '/frontend/images/metro/metro-8.png'?>" alt="">
            <?= $shop->getMetro() ?>
          </a>
        </div>
        <div class="shops-page-adres-inf">
          <?=$shop->getStreet() ? $shop->getStreet().', ' : ''?>
          <?=$shop->getHouse() ? 'д. '.$shop->getHouse() : ''?>
        </div>
        <div class="shops-showcase-week-wrap">
          <div class="shops-showcase-week">
            <div class="shops-showcase-week-item">
              пн
            </div>
            <div class="shops-showcase-week-item">
              вт
            </div>
            <div class="shops-showcase-week-item">
              ср
            </div>
            <div class="shops-showcase-week-item">
              чт
            </div>
            <div class="shops-showcase-week-item">
              пт
            </div>
            <div class="shops-showcase-week-item -off">
              сб
            </div>
            <div class="shops-showcase-week-item -off">
              вс
            </div>
          </div>
          <div class="shops-showcase-week-period">
            <div><?=$shop->getWorktime()?></div>
          </div>
        </div>
      </div>
    </div>
  <? endforeach ?>
  <!------------------------- page data ----------------------------->
  <?php if ($pager->haveToPaginate()):?>
    <? include_component("noncache", "pagination", array(
      'pager' => $pager,
      'sortingUrl' => '&ar_cities='.$showMore['cities'].'&page-size='.$showMore['limit'],
      'baselink' => '/shops/get-more',
      'show_more' => true,
      'numbers' => false,
    )); ?>
  <?php endif; ?>
<? endif ?>
