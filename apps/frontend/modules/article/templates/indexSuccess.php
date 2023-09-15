<?php
slot('metaTitle', "Сексопедия. Энциклопедия секса | Секс-шоп «Он и Она»");
slot('metaKeywords', "сексопедия , все о сексе энциклопедия");
slot('metaDescription', "Сексопедия. Статьи о сексуальном здоровье, о взаимоотношениях в семье, о сексе и любви ");
$h1 = 'Энциклопедия секса - Sexopedia 18+';
slot('breadcrumbs', [
  ['text' => $h1],
]);
slot('h1', $h1);

?>
<? include_component("page", "blockSliders", array('sf_cache_key' => 'slider_sl_sexopedia', 'is_onlymoscow' => false, 'positionpage'=>'sl_sexopedia')); ?>

<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right -sexopedia">
    <? if(sizeof($articlesNew)):?>
      <div class="art-list-group">
        <div class="v-block-plate -int">
          <h2>новые статьи</h2>
        </div>
        <? include_component("article", "articlesBlock", array('sf_cache_key' => '$articlesNew', 'list'=> $articlesNew)); ?>
        <div class="v-block-link">
          <a href="/sexopedia/catalog/new">Все новые статьи >></a>
        </div>
      </div>
    <? endif ?>
    <? if(sizeof($articlesRelated)):?>
      <div class="art-list-group">
        <div class="v-block-plate -int">
          <h2>рекомендуемые статьи</h2>
        </div>
        <? include_component("article", "articlesBlock", array('sf_cache_key' => '$articlesRelated', 'list'=> $articlesRelated)); ?>
        <div class="v-block-link">
          <a href="/sexopedia/catalog/recommend">Все рекомендуемые статьи >></a>
        </div>
      </div>
    <? endif ?>
    <? if(sizeof($articlesPopular)):?>
      <div class="art-list-group">
        <div class="v-block-plate -int">
          <h2>популярные статьи</h2>
        </div>
        <? include_component("article", "articlesBlock", array('sf_cache_key' => '$articlesPopular', 'list'=> $articlesPopular)); ?>
        <div class="v-block-link">
          <a href="/sexopedia/catalog/pop">Все популярные статьи >></a>
        </div>
      </div>
    <? endif ?>
  </main>
  <? include_component("article", "leftMenu", array('sf_cache_key' => 'articlesLeftMenu')); ?>
</div>
