<?php
$h1='Мировые производители товаров для взрослых';
slot('metaTitle', "$h1");
slot('metaKeywords', "Мировые производители товаров для взрослых");
slot('metaDescription', "Мировые производители товаров для взрослых. Огромный ассортимент интимных игрушек, белья, косметики и аксессуаров. Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества. ");
slot('breadcrumbs', [
  ['text' => $h1],
]);
// if(isset($_GET['page']) && $_GET['page']>1) slot('canonical', '/manufacturers' );
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
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right">
    <h1>Мировые производители товаров для взрослых</h1>
    <div class="intro">
      <p>
        Мы «Он и Она» являемся крупнейшим поставщиком товаров для счастливой сексуальной жизни от ведущих мировых производителей.
      </p>
      <p>
        Наша миссия заключается в том, чтобы предоставить вам интересные, уникальные и&nbsp;современные игрушки для удовольствия.
      </p>
      <p>
        Мы хотим, чтобы вы чувствовали себя сексуальными и&nbsp;удовлетворенными при использовании высококачественной продукции
      </p>
    </div>
    <? include_component("category", "popularBrands", array('sf_cache_key' => 'popularBrands')); ?>

    <? $manufacturers = $pager->getResults(); ?>

    <? if (sizeof($manufacturers)) :?>
      <div class="brands">
        <h2>все бренды</h2>
        <!------------------------- page data ----------------------------->
        <? foreach ($manufacturers as $manufacturer) :?>
          <a href="/manufacturer/<?= $manufacturer->getSlug() ?>" class="brands-item">
            <img src="<?= getImageSrc($manufacturer) ?>" alt="<?= $manufacturer->getName()?>">
          </a>
        <? endforeach ?>
        <? for ($i=0; $i<4-(sizeof($manufacturers)%4); $i++) :?>
          <div class="brands-polyfill"></div>
        <? endfor ?>
        <!------------------------- page data ----------------------------->
      </div>
      <?php if ($pager->haveToPaginate()):?>
        <? include_component("noncache", "pagination", array(
          'pager' => $pager,
          'sortingUrl' => '',
          'baselink' => '/manufacturers',
          'show_more' => true,
          'numbers' => true,
        )); ?>
      <?php endif; ?>
    <? endif ?>
  </main>
  <? include_component("category", "leftMenuBrands", array('sf_cache_key' => 'leftMenuBrands')); ?>
</div>
