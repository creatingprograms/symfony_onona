<? switch($type){
  case 'text':
    if (sizeof($shops)) :?>
    <div class="shops-page-adres">
      <? $colLimit = ceil(sizeof($shops)/3); $i=0;
      foreach($shops as $shop) :?>
        <?= $i++ ? '' : '<div class="shops-page-adres-col">' ?>
          <div class="shops-page-adres-item">
            <div class="shops-page-adres-h ">
              <a href="<?= url_for('shops/'.$shop->getSlug()) ?>">
                <img src="<?=$shop->getIconmetro() ? '/uploads/metro/'.$shop->getIconmetro() : '/frontend/images/metro/metro-8.png'?>" alt="">
                <?= $shop->getMetro() ?>
              </a>
            </div>
            <div class="shops-page-adres-inf">
              <?=$shop->getStreet() ? $shop->getStreet().', ' : ''?>
              <?=$shop->getHouse() ? 'д. '.$shop->getHouse() : ''?>
            </div>
          </div>
        <?= ($i==($colLimit)) ? '</div>' : ''?>
        <? if($i==($colLimit)) $i=0; ?>

      <? endforeach ?>
      </div>
    </div>
  <? endif;
    break;
  case 'images':
    if (sizeof($shops)) : ?>
      <div class="shops-showcase">
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
      </div>
      <?php if(isset($showMore)) :?>
        <div class="js-show-more more" data-url="/shops/get-more" data-options="&ar_cities=<?= $showMore['cities'] ?>&page-size=<?= $limit ?>" data-page="<?= $showMore['page']+1 ?>">
          <span>Больше магазинов >></span>
        </div>
      <? endif ?>

  <? endif;
    break;
  default:
    break;
}
?>
