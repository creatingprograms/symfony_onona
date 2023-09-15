<?/*<pre><?=print_r(['style'=> $style], true) ?></pre>*/ ?>
<?
$i = 0;
if (!$break) $break = 11;
// die($catId);
?>
<? if (sizeof($products)) : ?>
  <div class="catalog-result gtm-items-list <?= $class ? $class : '' ?>" data-cat-name="<?= $catName ?>" data-cat-id="<?= $catId ?>">
    <!------------------------- page data ----------------------------->
    <? foreach ($products as $key => $product) {
      $advcakeItems[] = [
        'id' => $product->getId(),
        'name' => $product->getName(),
        'categoryId' => $product->getGeneralCategory()->getId(),
        'categoryName' => $product->getGeneralCategory()->getName(),
        'price' => $product->getPrice(),
      ];
      if (!isset($style))
        $style == 'short';

      include_component("product", "productInList", array(
        'sf_cache_key' => 'product-' . $style . $product->getId() . $product->getEndaction() . '|' . $sf_user->isAuthenticated(),
        'product' => $product,
        'showChoosen' => true,
        'isSets' => $isSets,
        'catId' => $catId,
        'style' => $style
      ));

      unset($products[$key]);
      if (++$i > $break) break;
    }
    ?>
    <!------------------------- page data ----------------------------->
  </div>
<? else : ?>
  <p>Товаров подходящих под условия фильтра не найдено. Попробуйте изменить условия</p>
<? endif ?>

<? if ($bannerType && $i) : ?>
  <? include_component("page", "banner", array('type' => $bannerType, 'sf_cache_key' => 'banner-main-1', 'not_wrap' => true, 'add_class' => 'block-info_catalog')); ?>
<? endif ?>
<div data-retailrocket-markup-block="63ef8d28e801b5b9248bd926" data-category-id="<?= $catId ?>"></div>
<? if (sizeof($products)) : ?>
  <div class="catalog-result gtm-items-list <?= $class ? $class : '' ?>" data-cat-name="<?= $catName ?>" data-cat-id="<?= $catId ?>">
    <? foreach ($products as $key => $product) {
      $advcakeItems[] = [
        'id' => $product->getId(),
        'name' => $product->getName(),
        'categoryId' => $product->getGeneralCategory()->getId(),
        'categoryName' => $product->getGeneralCategory()->getName(),
        'price' => $product->getPrice(),
      ];
      if (!isset($style))
        $style == 'short';

      include_component("product", "productInList", array(
        'sf_cache_key' => 'product-' . $style . $product->getId() . $product->getEndaction() . '|' . $sf_user->isAuthenticated(),
        'product' => $product,
        'showChoosen' => true,
        'style' => $style
      ));
    }
    ?>
  </div>
<? endif ?>

<? if (sizeof($advcakeItems)) slot('advcakeItems', $advcakeItems); ?>