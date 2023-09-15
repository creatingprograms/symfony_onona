<?php
 slot('metaTitle', "«Он и Она» | TUBE/Видео");
 slot('metaDescription', "TUBE/Видео. Круглосуточный интим-магазин, широкий ассортимент секс-товаров по доступным ценам. В нашем каталоге вся представленная продукция имеет подлинные сертификаты качества.");
 $h1 = 'Он и Она | TUBE';
 slot('breadcrumbs', [
   ['text' => $h1],
 ]);
?>

<div class="wrapper wrap-cont -video -clf">
  <main class="cont-right -video">
    <h1>Он и Она | TUBE</h1>
    <div class="video-page">
      <? if(sizeof($videosRelated)) :?>
        <div class="v-block-plate -int">
          рекомедуемые видео
        </div>
        <? include_component("video", "videosBlock", array('sf_cache_key' => '$videosRelated', 'list'=> $videosRelated)); ?>
        <div class="v-block-link">
          <a href="/video/Рекомендованное">Все рекомендуемые видео &gt;&gt;</a>
        </div>
      <? endif ?>
      <? if(sizeof($videosNew)) :?>
        <div class="v-block-plate -int">
          новые видео
        </div>
        <? include_component("video", "videosBlock", array('sf_cache_key' => '$videosNew', 'list'=> $videosNew)); ?>
        <div class="v-block-link">
          <a href="/video/Новое">Все новые видео &gt;&gt;</a>
        </div>
      <? endif ?>
    </div>
  </main>
  <? include_component("video", "videosSideBar", array('sf_cache_key' => '$videosSidebar')); ?>
</div>
