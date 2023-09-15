<?/*
<div style="position:fixed; width: 100%; height: 100%; left: 0; top: 0; background: #fff;">
  <pre><?die(print_r($shops, true))?></pre>
</div>
*/?>
<?php
$hideMobile = '';
if (sfContext::getInstance()->getRouting()->getCurrentRouteName() == 'product_show') $hideMobile = ' mobile-hide';
if (preg_match('/^(category|catalog)_/', sfContext::getInstance()->getRouting()->getCurrentRouteName())) $hideMobile = ' mobile-hide';
?>
<?php $i=0 ;?>
<?php foreach ($shops as $key => $shop) :?>
  <?php if(11 == $i++) break;?>
  <div class="shop-item<?php echo $hideMobile; ?>" style="background-image:url('/uploads/metro/<?=$shop['iconmetro']?>');">
    <?php if(!isset($shop['shops'])):?>
      <a href="/shops/<?=$shop['slug']?>">
        <?=$shop['metro']?>
      </a>
    <?php else :?>
      <div class="sub-shops js-subshops">
        <?=$shop['metro']?>
        <div class="sub-shops-wrapper">
          <?php foreach ($shop['shops'] as $key => $value) : ?>
            <a href="/shops/<?=$value['slug']?>">
              <?=$value['street'].', д.'.$value['house']?>
            </a>
          <?php endforeach ?>
        </div>
      </div>
    <?php endif ?>
  </div>
<? endforeach ?>
<div class="button-all js-button-all<?php echo $hideMobile; ?>">Все магазины</div>
<div class="top-shops-popup<?php echo $hideMobile; ?>">
  <div class="black-back"></div>
  <div class="top-shops-wrapper">
    <?php foreach ($shops as $key => $shop) :?>
      <div class="top-shop-item" style="background-image:url('/uploads/metro/<?=$shop['iconmetro']?>');">
        <?php if(!isset($shop['shops'])):?>
          <a href="/shops/<?=$shop['slug']?>">
            <?=$shop['metro']?>
          </a>
        <?php else :?>
          <div class="sub-shops js-subshops">
            <?=$shop['metro']?>
            <div class="sub-shops-wrapper">
              <?php foreach ($shop['shops'] as $key => $value) : ?>
                <a href="/shops/<?=$value['slug']?>">
                  <?=$value['street'].', д.'.$value['house']?>
                </a>
              <?php endforeach ?>
            </div>
          </div>
        <?php endif ?>
      </div>
    <? endforeach ?>
    <div class="header">Магазины в Москве</div>
    <div class="close js-button-all"></div>
  </div>
</div>
