<?php
$slug='';
if ($category) {
    $startTitle = $category->getName();
    slot('canonical', '/sexopedia/category/'.$category->getSlug());
    $h1=$category->getName();
    $slug=$category->getSlug();
}else{
  $h1 = $startTitle = $categoryName;
}
slot('metaTitle', $startTitle . " * Энциклопедия секса * Главная");
slot('metaKeywords', $startTitle . " * Энциклопедия секса * Главная");
slot('metaDescription', $startTitle . " * Энциклопедия секса * Главная");
slot('breadcrumbs', [
  ['link'=>'/sexopedia', 'text' => 'Энциклопедия секса'],
  ['text' => $h1],
]);
slot('h1', $h1);
if(sizeof($pagerArticles)):
?>
  <div class="wrapper wrap-cont -clf -sidebar">
    <main class="cont-right -sexopedia">
      <div class="art-list-group">
        <? include_component("article", "articlesBlock", array('sf_cache_key' => '$articlesNew'.$h1, 'list'=> $pagerArticles)); ?>
      </div>
    </main>
    <? include_component("article", "leftMenu", array('sf_cache_key' => 'articlesLeftMenu', 'active' => $slug)); ?>
  </div>
<? endif ?>
