<?php
  $h1 = sfContext::getInstance()->getRequest()->getParameter('slug');
  slot('metaTitle', sfContext::getInstance()->getRequest()->getParameter('slug')." / Он и Она | TUBE/Видео ");
  slot('metaDescription', "Видео по теме ".sfContext::getInstance()->getRequest()->getParameter('slug').". Он и Она - сеть магазинов для взрослых");
  slot('metaKeywords', "Видео ролики по теме ".sfContext::getInstance()->getRequest()->getParameter('slug'));
  slot('h1', $h1);
  slot('breadcrumbs', [
    ['link' => '/video', 'text' => 'Он и Она | TUBE'],
    ['text' => $h1],
  ]);
?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <? include_component("video", "videosSideBar", array('sf_cahe_key' => '$videosSidebar', 'active' => $h1)); ?>
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
          'baselink' => url_for('video_list').'/'.$h1,
          'show_more' => false,
          'numbers' => true,
        )); ?>

      <?php endif; ?>
    </div>
  </div>
</div>
