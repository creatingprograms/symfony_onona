<?php
 slot('metaTitle', "«Он и Она» | TUBE/Видео");
 slot('metaDescription', "TUBE/Видео. Круглосуточный интим-магазин, широкий ассортимент секс-товаров по доступным ценам. В нашем каталоге вся представленная продукция имеет подлинные сертификаты качества.");
 $h1 = 'Он и Она | TUBE';
 slot('h1', $h1);
 slot('breadcrumbs', [
   ['text' => $h1],
 ]);
?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <? include_component("video", "videosSideBar", array('sf_cache_key' => '$videosSidebar')); ?>
      <? $videos = $pager->getResults(); ?>
      <? include_component("video", "videosBlock", array('sf_cache_key' => '$videosNew', 'list'=> $videos)); ?>
      <?php if ($pager->haveToPaginate()):?>
        <?
          if($sortOrder!="")
            $sortingUrl="&sortOrder=".$sortOrder."&direction=".$direction;
          else
            $sortingUrl='';
        ?>

        <? include_component("noncache", "pagination", array(
          'pager' => $pager,
          'sortingUrl' => $sortingUrl,
          'baselink' => url_for('video_list'),
          'show_more' => false,
          'numbers' => true,
        )); ?>

      <?php endif; ?>
    </div>
  </div>
</div>
