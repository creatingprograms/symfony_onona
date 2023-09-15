<? if(is_object($banner)) : ?>
  <!-- блок Экспресс доставки -->
  <? if (!$not_wrap) :?>
  <div class="wrap-block">
    <div class="container">
    <? endif?>
      <noindex>
        <div class="block-info block-info_mob <?=$banner->getClass()?> <?=$add_class?>">
          <div class="block-info__img">
            <img src="/uploads/media/<?=$banner->getSrc()?>" alt="<?=$banner->getAlt()?>">
          </div>
          <div class="block-info__title"><?=$banner->getAlt()?></div>
          <div class="block-info__text"><?=$banner->getDescription()?></div>
          <? if($banner->getHref()):?>
            <a href="<?=$banner->getHref()?>" class="link-fill"><?=$banner->getButton()?></a>
          <? endif ?>
        </div>
      </noindex>
      <? if (!$not_wrap) :?>
    </div>
  </div>
  <? endif?>
  <!--END - блок Экспресс доставки -->
<? endif ?>
