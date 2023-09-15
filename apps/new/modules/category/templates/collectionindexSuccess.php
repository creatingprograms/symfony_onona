<?php
$h1='Коллекции товаров от известных мировых производителей';
slot('metaTitle', "$h1");
slot('metaKeywords', "Коллекции товаров от известных мировых производителей");
slot('metaDescription', "Коллекции товаров от известных мировых производителей");
slot('breadcrumbs', [
  ['text' => $h1],
]);
slot('h1', $h1);
slot('catalog-class', 'page-brands');
?>
<?/*
<div class="wrap-block wrap-brand-info">
  <div class="container">
    <div class="block-content">
      <p>Мы «Он и Она» являемся крупнейшим поставщиком товаров для счастливой сексуальной жизни от ведущих мировых производителей.</p>
      <p>Наша миссия заключается в том, чтобы предоставить вам интересные, уникальные и современные игрушки для удовольствия.</p>
      <p>Мы хотим, чтобы вы чувствовали себя сексуальными и удовлетворенными при использовании высококачественной продукции.</p>
    </div>
  </div>
</div>
*/?>
<div class="wrap-block">
  <div class="container">
    <div class="block-content">
      <? include_component("category", "setViewMode", array('sf_cache_key' => $view, 'view' => $view)); ?>

      <? $manufacturers = $pager->getResults(); ?>
      <? if(sizeof($manufacturers)) :?>
        <? if($view=='card') :?>
          <div class="brands-list-cart">
            <? foreach ($manufacturers as $manufacturer) :?>
              <a
                href="/collection/<?= $manufacturer->getSlug() ?>"
                class="brands-item"
                style="background-image: url('<?= ILTools::getImageSrc($manufacturer, 'collection') ?>')"
              >
              </a>
            <? endforeach ?>
          </div>
        <? else :?>
        <?
          $list=ILTools::GetListByLetter($manufacturers);
        ?>
          <? if(sizeof($list)) :?>
            <div class="brands-list-word">
              <?php foreach ($list as $key => $objList): ?>
                <div class="brands-item-word">
                  <div class="brands-item-word__title"><?=$key?></div>
                  <div class="brands-itemword__list">
                  <?php foreach ($objList as $key => $obj): ?>
                    <a href="/collection/<?=$obj->getSlug()?>"><?=$obj->getName()?></a>
                  <?php endforeach; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <? endif ?>
        <? endif ?>
      <? endif ?>

      <? //include_component("category", "popularBrands", array('sf_cache_key' => 'popularBrands')); ?>

      <?php if ($pager->haveToPaginate()):?>
        <? include_component("noncache", "pagination", array(
          'pager' => $pager,
          'sortingUrl' => '',
          'baselink' => '/collections',
          // 'show_more' => true,
          'numbers' => true,
        )); ?>
      <?php endif; ?>
    </div>
  </div>
</div>
