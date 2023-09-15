<?php
global $isTest;

$h1='Каталог';
slot('metaTitle',  $h1 . " – Секс шоп Он и Она" );
slot('metaKeywords', $h1 );
slot('metaDescription',  $h1 );
if($category['canonical']) slot('canonical', '/catalog' );

// slot('catalog-class', '-forHer -full');
slot('breadcrumbs', [
  ['text' => $h1],
]);
slot('h1', $h1);
$i=0;
?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <div class="catalog-list">
        <? foreach ($catalog as $key => $cat) :?>
          <div class="catalog-item catalog-item_mod">
            <?/*<div class="catalog-item__img">
              <img src=<?= $cat['img_hard'] ? $cat['img_hard'] : '/uploads/photo/'.$cat['img'] ?> alt="<?=$cat['name']?>">
            </div>*/?>
            <div class="catalog-item__content">
              <a href="/<?=$cat['base']?>/<?=$cat['slug']?>" class="catalog-item__title lead-text" style="background-image: url(<?= $cat['img_hard'] ? $cat['img_hard'] : '/uploads/photo/'.$cat['img'] ?>)"><span><?=$cat['menu_name']?></span></a>
              <? foreach($cat['sub'] as $subcat):?>
                <div class="catalog-item__link"><a href="/category/<?= $subcat['slug']?>"><?= $subcat['name']?><span> <?=$subcat['count'] ? ILTools::GetWordForm($subcat['count'], 'категория')  : ''?></span></a></div>
              <? endforeach ?>
            </div>
          </div>
          <?
            unset($catalog[$key]);
            if($i++>4) break;
          ?>
        <? endforeach ?>
      </div>
    </div>
  </div>
</div>

<?= include_component("page", "sliderActions", array('sf_cache_key' => 'slider-actions')) ?>

<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <div class="catalog-list">
        <? foreach ($catalog as $cat) :?>
          <div class="catalog-item catalog-item_mod">
            <?/*<div class="catalog-item__img">
              <img src=<?= $cat['img_hard'] ? $cat['img_hard'] : '/uploads/photo/'.$cat['img'] ?> alt="<?=$cat['name']?>">
            </div>*/?>
            <div class="catalog-item__content">
              <a href="/<?=$cat['base']?>/<?=$cat['slug']?>" style="background-image: url(<?= $cat['img_hard'] ? $cat['img_hard'] : '/uploads/photo/'.$cat['img'] ?>)" class="catalog-item__title lead-text"><span><?=$cat['menu_name']?></span></a>
              <? foreach($cat['sub'] as $subcat):?>
                <div class="catalog-item__link"><a href="/category/<?= $subcat['slug']?>"><?= $subcat['name']?><span> <?=$subcat['count'] ? ILTools::GetWordForm($subcat['count'], 'категория')  : ''?></span></a></div>
              <? endforeach ?>
            </div>
          </div>
        <? endforeach ?>
      </div>
    </div>
  </div>
</div>
<?= include_component("category", "sliderItems", array('type'=>'sale')) ?>
