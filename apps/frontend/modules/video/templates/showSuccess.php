<?php
  $h1 = sfContext::getInstance()->getRequest()->getParameter('slug');
  slot('metaTitle', sfContext::getInstance()->getRequest()->getParameter('slug')." / Он и Она | TUBE/Видео ");
  slot('metaDescription', "Видео по теме ".sfContext::getInstance()->getRequest()->getParameter('slug').". Он и Она - сеть магазинов для взрослых");
  slot('metaKeywords', "Видео ролики по теме ".sfContext::getInstance()->getRequest()->getParameter('slug'));
  slot('breadcrumbs', [
    ['link' => '/video', 'text' => 'Он и Она | TUBE'],
    ['text' => $h1],
  ]);
?>
<div class="wrapper wrap-cont -video -clf">
  <main class="cont-right -video">
    <h1><?= $h1?></h1>
    <div class="video-page">
      <? if(sizeof($videos)) :?>

        <? include_component("video", "videosBlock", array('sf_cache_key' => '$videosRelated'.$h1, 'list'=> $videos)); ?>

      <? endif ?>
    </div>
  </main>
  <? include_component("video", "videosSideBar", array('sf_cache_key' => '$videosSidebar', 'active' => $h1)); ?>
</div>
