<?php
$h1='Коллекции товаров от известных мировых производителей';
slot('metaTitle', "$h1");
slot('metaKeywords', "Коллекции товаров от известных мировых производителей");
slot('metaDescription', "Коллекции товаров от известных мировых производителей");
slot('breadcrumbs', [
  ['text' => $h1],
]);
if(!function_exists('getImageSrc')) {
  function getImageSrc($collection){
    $image=false;
    if($collection->getImage()) $image='/uploads/collection/'.$collection->getImage();
    if(!$image){
      $reg='/<img.+src="(.+\.png)".*>/mU';//Выдергивает из контента png
      $res=preg_match($reg, $collection->getContent(), $matches);
      if($res) $image=$matches[1];
    }
    if(!$image) $image='/frontend/images/noimage-brand.png';
    return $image;
  }
}
?>
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right">
    <h1><?=$h1?></h1>
    <?/*<div class="intro">
      <p>
        Мы «Он и Она» являемся крупнейшим поставщиком товаров для счастливой сексуальной жизни от ведущих мировых производителей.
      </p>
      <p>
        Наша миссия заключается в том, чтобы предоставить вам интересные, уникальные и&nbsp;современные игрушки для удовольствия.
      </p>
      <p>
        Мы хотим, чтобы вы чувствовали себя сексуальными и&nbsp;удовлетворенными при использовании высококачественной продукции
      </p>
    </div>*/?>
    <? include_component("category", "popularCollections", array('sf_cache_key' => 'popularCollections')); ?>

    <? $collections = $pager->getResults(); ?>

    <? if (sizeof($collections)) :?>
      <div class="brands">
        <h2>все коллекции</h2>
        <!------------------------- page data ----------------------------->
        <? foreach ($collections as $collection) :?>
          <a href="/collection/<?= $collection->getSlug() ?>" class="brands-item">
            <img src="<?= getImageSrc($collection) ?>" alt="<?= $collection->getName()?>">
          </a>
        <? endforeach ?>
        <? for ($i=0; $i<4-(sizeof($collections)%4); $i++) :?>
          <div class="brands-polyfill"></div>
        <? endfor ?>
        <!------------------------- page data ----------------------------->
      </div>

      <?php if ($pager->haveToPaginate()):?>
        <? include_component("noncache", "pagination", array(
          'pager' => $pager,
          'sortingUrl' => '',
          'baselink' => '/collections',
          'show_more' => true,
          'numbers' => true,
        )); ?>
      <?php endif; ?>
    <? endif ?>
  </main>
  <? include_component("category", "leftMenuCollections", array('sf_cache_key' => 'leftMenuCollections')); ?>
</div>
