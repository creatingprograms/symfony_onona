<?php
global $isTest;
$keywordsTemplate = '{name} купить цена отзывы доставка';
$descriptionTemplate = '{name}. Продажа по доступным ценам с доставкой в онлайн интим-магазине Он и Она, 18+';
$h1 = 'Выбираем первую игрушку с <span>Он и Она</span>';

$cat = end($categorys);
// die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r($ids, true) . '</pre>');
slot('metaTitle', str_replace("{name}", $h1, csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
slot('metaKeywords', str_replace("{name}",  $h1, $keywordsTemplate));
slot('metaDescription', str_replace("{name}", $h1, $descriptionTemplate));
slot('canonical', '/category/dlya_novichkov');
$breadcrumbs[] = ['text' => $h1];
slot('breadcrumbs', $breadcrumbs);
slot('h1', $h1);
slot('catalog-class', '-forHer -forNoviceNew');
$style = 'newbie';
?>

<div class="wrap-block">
  <div class="container">
    <div class="novice-block-text">
      <div class="novice-text zreo-m">
        <?= $cat['this_content'] ?>
      </div>
      <div class="novice-info">
        <div class="novice-content">
          Дарим скидку 15% по&nbsp;промо <b>NEW</b> <br>для первой покупки
        </div>
        <span>15%</span>
      </div>
      <div></div>
      <div style="padding-top: 8px; font-size: 90%;">Скидка 15% действует только на ограниченное количество товаров, указанных на данной странице.</div>
    </div>
  </div>
</div>
<? if (!empty($ids)) : ?>
  <div class="wrap-block">
    <div class="container">
      <div class="novice-list">
        <? foreach ($ids as $id) : ?>
          <? include_component("product", "productInListById", array(
            'sf_cache_key' => 'product-' . $style . $id . '|' . $sf_user->isAuthenticated(),
            'id' => $id,
            'style' => $style
          )); ?>
        <? endforeach ?>
      </div>
    </div>
  </div>
<? endif ?>
<? include_component("page", "lastReviews", array(
  'cat_id' => end($categorys)['this_id'],
  'is_novice' => true,
  'sf_cache_key' => 'lastReviews' . date('d.m.Y'),
)); ?>