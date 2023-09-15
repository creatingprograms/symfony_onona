<? switch($type){
  case 'images_slider':
    if (sizeof($shops)) :?>
      <div class="swiper-container shop-image-slider">
        <div class="swiper-wrapper">
          <!-- <div class="swiper-slide"> -->
            <? foreach($shops as $shop) :?>
              <div class="swiper-slide shops-showcase-item">
                <a href="<?= '/shops/'.mb_strtolower($shop->getSlug()) ?>" class="shops-showcase-img">
                  <img alt="Магазин для взрослых Он и Она,<?=htmlspecialchars($shop->getAddress())?>"
                    src="/uploads/assets/images/<?=$shop->getPreviewImage()  ? $shop->getPreviewImage() : '001.jpg'?>">
                </a>
                <div class="shops-showcase-desc">
                  <div class="shops-page-adres-h -icon-1">
                    <?
                      $img=($shop->getIconmetro() && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/metro/" . $shop->getIconmetro())) ? '/uploads/metro/'.$shop->getIconmetro() : '/frontend/images/metro/metro-8.png';
                    ?>
                    <a href="<?= '/shops/'.mb_strtolower($shop->getSlug()) ?>"
                      style="background-image:url('<?=$img?>')"
                      >
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
                      <div class="shops-showcase-week-item">
                        сб
                      </div>
                      <div class="shops-showcase-week-item<?/* -off*/?>">
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
          <!-- </div> -->
        </div>
        <div class="swiper-pagination"></div>
      </div>
    <? endif;
    break;
  case 'side':
    if (sizeof($shops)) :?>
      <div class="swiper-container table-shop-side">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <? foreach($shops as $shop) :?>
              <div class="table-shop__element">
                  <div class="table-shop__st">
                    <?if ($shop->getIconmetro() != "" && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/metro/" . $shop->getIconmetro())) :?>
                      <img src="/uploads/metro/<?=$shop->getIconmetro()?>" alt="">
                    <? endif ?>
                    <?= $shop->get('Metro') ?>
                  </div>
                  <div class="table-shop__address"><a href="<?= '/shops/'.mb_strtolower($shop->getSlug()) ?>"><?= $shop->get('Address') ?></a></div>
                  <div class="table-shop__date"><?= $shop->getWorkTime() ?></div>
                </div>
            <? endforeach ?>
          </div>
        </div>
        <div class="swiper-scrollbar"></div>
      </div>
    <? endif;
    break;
  case 'text':
    if (sizeof($shops)) :?>
    <div class="table-shop">
      <div class="table-shop__th">
        <div class="col">Адрес магазина</div>
        <div class="col"></div>
        <div class="col"></div>
        <div class="col">Время работы</div>
      </div>
      <div class="table-shop__body">
        <? foreach($shops as $shop) :?>
          <div class="table-shop__row">
            <div class="col">
              <div class="table-shop__st">
                <?if ($shop->getIconmetro() != "" && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/metro/" . $shop->getIconmetro())) :?>
                  <img src="/uploads/metro/<?=$shop->getIconmetro()?>" alt="">
                <? endif ?>
                <?= $shop->get('Metro') ?>
              </div>
              <div class="table-shop__address"><a href="<?= '/shops/'.mb_strtolower($shop->getSlug()) ?>"><?= $shop->get('Address') ?></a></div>
            </div>
            <div class="col">
              <div class="table-shop__status in-stock"></div>
            </div>
            <div class="col">
              <div class="table-shop__date"></div>
            </div>
            <div class="col">
              <div class="table-shop__date"><?= $shop->getWorkTime() ?></div>
              <?/*<a href="#" class="btn-full btn-full_white">Заберу отсюда</a>*/?>
            </div>
          </div>
        <? endforeach ?>
      </div>
    </div>
  <? endif;
    break;
  case 'text_old':
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
            <a href="<?= '/shops/'.mb_strtolower($shop->getSlug()) ?>" class="shops-showcase-img">
              <img alt="Магазин для взрослых Он и Она,<?=htmlspecialchars($shop->getAddress())?>"
                src="/uploads/assets/images/<?=$shop->getPreviewImage()  ? $shop->getPreviewImage() : '001.jpg'?>">
            </a>
            <div class="shops-showcase-desc">
              <div class="shops-page-adres-h -icon-1">
                <?
                  $img=($shop->getIconmetro() && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/metro/" . $shop->getIconmetro())) ? '/uploads/metro/'.$shop->getIconmetro() : '/frontend/images/metro/metro-8.png';
                ?>
                <a href="<?= '/shops/'.mb_strtolower($shop->getSlug()) ?>"
                  style="background-image:url('<?=$img?>')"
                  >
                  <?= $shop->getMetro() ?>
                </a>
              </div>
              <div class="shops-page-adres-inf">
                <?=$shop->getStreet() ? $shop->getStreet().', ' : ''?>
                <?=$shop->getHouse() ? 'д. '.$shop->getHouse() : ''?>
              </div>
              <?php if ($shop->getLatitude()*$shop->getLongitude()): ?>
                <noindex>
                  <a href="yandexnavi://build_route_on_map?lat_to=<?=$shop->getLatitude()?>&lon_to=<?=$shop->getLongitude()?>" target="_blank" class="navy js-navy">Ехать с Яндекс.Навигатор</a>
                </noindex>
              <?php endif; ?>
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
                  <div class="shops-showcase-week-item">
                    сб
                  </div>
                  <div class="shops-showcase-week-item<?/* -off*/?>">
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
        <div class="shops-showcase-item empty"></div>
        <div class="shops-showcase-item empty"></div>
        <div class="shops-showcase-item empty"></div>
      </div>
      <?php /*if(isset($showMore)) :?>
        <div class="js-show-more more" data-url="/shops/get-more" data-options="&ar_cities=<?= $showMore['cities'] ?>&page-size=<?= $limit ?>" data-page="<?= $showMore['page']+1 ?>">
          <span>Больше магазинов >></span>
        </div>
      <? endif */?>

  <? endif;
    break;
  case 'images_old':
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
  case 'mobile':
    $i=1;

    if(!empty($metroList)): ?>
      <div class="my-custom-select select-metro">
        <select class="js-show-shops">
          <option value="" data-station="0">Все</option>
          <?php foreach ($metroList as $key => $station): ?>
            <option value="<?=$key?>" data-image="<?=$station['image']?>" data-id="<?= $i++?>"><?=$key?></option>
          <?php endforeach; ?>
        </select>
      </div>
    <? endif;
    if (sizeof($shops)) :?>
      <div class="swiper-container shop-image-slider-mobile">
        <div class="swiper-wrapper">
          <!-- <div class="swiper-slide"> -->
            <? foreach($shops as $shop) :?>
              <div class="swiper-slide shops-showcase-item">
                <a href="<?= '/shops/'.mb_strtolower($shop->getSlug()) ?>" class="shops-showcase-img">
                  <img alt="Магазин для взрослых Он и Она,<?=htmlspecialchars($shop->getAddress())?>"
                    src="/uploads/assets/images/<?=$shop->getPreviewImage()  ? $shop->getPreviewImage() : '001.jpg'?>">
                </a>
                <div class="shops-showcase-desc">
                  <div class="shops-page-adres-h -icon-1">
                    <?
                      $img=($shop->getIconmetro() && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/metro/" . $shop->getIconmetro())) ? '/uploads/metro/'.$shop->getIconmetro() : '/frontend/images/metro/metro-8.png';
                    ?>
                    <a href="<?= '/shops/'.mb_strtolower($shop->getSlug()) ?>"
                      style="background-image:url('<?=$img?>')"
                      >
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
                      <div class="shops-showcase-week-item">
                        сб
                      </div>
                      <div class="shops-showcase-week-item<?/* -off*/?>">
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
        <div class="swiper-pagination"></div>
      </div>
    <? endif;

    break;
  case 'mobile_mod':
    if(!empty($metroList)): ?>
    <? $i=1; ?>
      <div class="my-custom-select select-metro">
        <select class="js-show-shops_mod">
          <option value="" data-station="0">Все</option>
          <?php foreach ($metroList as $key => $station): ?>
            <option value="<?=$key?>" data-image="<?=$station['image']?>" data-id="<?= $i++?>" data-href="/shops/<?=$station['slug']?>"><?=$key?></option>
          <?php endforeach; ?>
        </select>
      </div>
    <? endif;

    break;
  default:
    break;
}
?>
