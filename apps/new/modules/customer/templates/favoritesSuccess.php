<?
  global $isTest;
  $h1='Избранное';
  slot('breadcrumbs', [
    ['text' => 'Личный кабинет', 'link' => '/lk'],
    ['text' => $h1],
  ]);
  slot('h1', $h1);
?>
<?
  include_component( 'category', 'favoriteItems',
    array(
      'ids' => $itemIds,
      'type' => 'favorites',
      'no_limit' => true,
    )
  );
?>
<?
  include_component("category", "sliderItems", array('sf_cache_key' => 'RecommendItems', 'type'=>'recommend'));
?>
<div id="bonus-info" class="mfp-hide white-popup-block block-popup popup-login">
  <? include_component("page", "subpage", array('sf_cache_key' => '_bonus_info', 'page'=>'_bonus_info')); ?>
</div>
