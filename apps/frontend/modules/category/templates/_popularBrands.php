<? if (sizeof($manufacturerPopular)) :?>
  <div class="popular-brends -typeSlider">
    <div class="h2">популярные бренды</div>
    <div class="popular-brends-slider">
      <div class="swiper-container">
        <div class="swiper-wrapper">
          <? foreach ($manufacturerPopular as $manufacturer) :?>
            <div class="swiper-slide">
              <a href="/manufacturer/<?= $manufacturer->getSlug() ?>">
                <img src="<?= getImageSrc($manufacturer) ?>" alt="<?= $manufacturer->getName()?>">
              </a>
            </div>
          <? endforeach ?>
        </div>
      </div>
      <div class="swiper-pagination"></div>
      <div class="swiper-button-prev">
        <svg>
          <use xlink:href="#backArrowIcon" />
        </svg>
      </div>
      <div class="swiper-button-next">
        <svg>
          <use xlink:href="#backArrowIcon" />
        </svg>
      </div>
    </div>
  </div>
<? endif ?>
<?
if(!function_exists('getImageSrc')) {
  function getImageSrc($manufacturer){
    $image=false;
    if($manufacturer->getImage()) $image='/uploads/manufacturer/'.$manufacturer->getImage();
    if(!$image){
      $reg='/<img.+src="(.+\.png)".*>/mU';//Выдергивает из контента png
      $res=preg_match($reg, $manufacturer->getContent(), $matches);
      if($res) $image=$matches[1];
    }
    if(!$image) $image='/frontend/images/noimage-brand.png';
    return $image;
  }
}
?>
