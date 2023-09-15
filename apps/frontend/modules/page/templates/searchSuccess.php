<?
  slot('metaTitle', strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString")));
  slot('metaKeywords', strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString")));
  slot('metaDescription', strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString")));
  $h1='Результаты поиска';
  slot('breadcrumbs', [
    ['text' => $h1],
  ]);
  slot('h1', $h1);
?>
<main class="wrapper">
  <?php if ($collections->count() > 0): ?>
    <div class="v-block-plate -int">
      <h2>Коллекции, <?= $collections->count() ?> результата(ов)</h2>
    </div>
    <ul>
      <? foreach ($collections as $i => $collection): ?>
        <li><?=$i+1 ?>. <a href="/collection/<?= $collection->getSlug(); ?>"><?= $collection->getName(); ?></a></li>
      <?php endforeach; ?>
    </ul>
    <br>
  <? endif ?>
  <?php if ($manufacturer->count() > 0): ?>
    <div class="v-block-plate -int">
      <h2>Производители, <?= $manufacturer->count() ?> результата(ов)</h2>
    </div>
    <ul>
      <? foreach ($manufacturer as $i => $manufacture): ?>
        <li><?=$i+1 ?>. <a href="/manufacturer/<?= $manufacture->getSlug(); ?>"><?= $manufacture->getName(); ?></a></li>
      <?php endforeach; ?>
    </ul>
    <br>
  <? endif ?>

  <?php if ($categorys->count() > 0): ?>
    <div class="v-block-plate -int">
      <h2>Категории, <?= $categorys->count() ?> результата(ов)</h2>
    </div>
    <ul>
      <? foreach ($categorys as $i => $category): ?>
        <li><?=$i+1 ?>. <a href="/category/<?= $category->getSlug(); ?>"><?= $category->getName(); ?></a></li>
      <?php endforeach; ?>
    </ul>
    <br>
  <? endif ?>
  <? $products = $pager->getResults(); ?>
  <? if ($pager->getNbResults() > 0): ?>
    <div class="v-block-plate -int">
      <h2>Товары, <?= $pager->getNbResults() ?> результата(ов)</h2>
    </div>
    <?
      include_component('category', 'listItems',
        array(
          'sf_cache_key' => 'product-list'.strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString")),
          'products' => $products,
          'catName' => 'Поиск: '.strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString")),
          'catId' => -1,
        )
      );
    ?>
    <?php if ($pager->haveToPaginate()):?>
      <? include_component("noncache", "pagination", array(
        'pager' => $pager,
        'sortingUrl' => "&searchString=" . strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString")),
        'baselink' => '/search',
        'show_more' => true,
        'numbers' => true,
      )); ?>
    <?php endif; ?>
    <div data-retailrocket-markup-block="5ba3a5f597a52530d41bb240" data-search-phrase="<?=strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString"))?>"></div>
    <br>
  <? endif ?>
  <? if ($pages->count() > 0): ?>
    <div class="v-block-plate -int">
      <h2>Страницы, <?= $pages->count() ?> результата(ов)</h2>
    </div>
    <ul>
      <? foreach ($pages as $i => $page): ?>
        <li><?=$i+1 ?>. <a href="/<?= $page->getSlug(); ?>"><?= $page->getName(); ?></a></li>
      <?php endforeach; ?>
    </ul>
    <br>
  <? endif; ?>

  <? if ($articles->count() > 0): ?>
    <div class="v-block-plate -int">
      <h2>Статьи, <?= $articles->count() ?> результата(ов)</h2>
    </div>
    <ul>
      <? foreach ($articles as $i => $article): ?>
        <li><?=$i+1 ?>. <a href="/sexopedia/<?= $article->getSlug(); ?>"><?= $article->getName(); ?></a></li>
      <?php endforeach; ?>
    </ul>
    <br>
  <? endif; ?>

  <? if ($pages->count() == 0 and $products->count() == 0 and $categorys->count() == 0 and $manufacturer->count() == 0 and $articles->count() == 0):
      echo "По вашему запросу ничего не найдено.";?>
      <div data-retailrocket-markup-block="5ba3a60c97a52530d41bb246" data-search-phrase="<?=strip_tags(sfContext::getInstance()->getRequest()->getParameter("searchString"))?>"></div>
  <?else : ?>
  <? endif ?>
</main>
<?/*foreach ($products as $product){
  $advcakeItems[]=[
    'id' => $product->getId(),
    'name' => $product->getName(),
    'categoryId' => $product->getGeneralCategory()->getId(),
    'categoryName' => $product->getGeneralCategory()->getName(),
  ];
}*/?>
<?
  slot('advcake', 7);
  slot('advcake_list', [
    'products' => get_slot('advcakeItems'),
  ]);
?>
