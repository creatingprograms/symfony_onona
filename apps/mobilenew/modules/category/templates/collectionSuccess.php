<?php
mb_internal_encoding('UTF-8'); /*
  slot('metaTitle', "Каталог товаров");
  slot('metaKeywords', "Каталог товаров");
  slot('metaDescription', "Каталог товаров"); */
slot('metaTitle', str_replace("{name}", $collection['name'], csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "") . " – Секс шоп Он и Она");
slot('metaKeywords', str_replace("{name}", $collection['name'], csSettings::get('titleCategory')));
slot('metaDescription', str_replace("{name}", $collection['name'], csSettings::get('titleCategory')));
?>


<div id="categoryShow">

    <div class="catalog breadcrumbs active">
        <div class="toggleCatalog arrowDown">

        </div>
        <div class="nameCatalog">
            <?= $collection['name'] ?>
        </div>
    </div>
</div>


<div id="productsShow">
    <?php
    include_partial("category/paginator", array("page" => $page, "pagesCount" => $pagesCount));
    ?>
    <ul class="product">
        <?php
        foreach ($products as $product['id'] => $product) {
            echo "<li>";


            include_partial("product/productBooklet", array(
                'sf_cache_key' => $product['id'],
                'product' => $product,
                'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
                'photo' => $photosAll[$product['id']]
                    )
            );

            echo "</li>";
        }
        ?>
    </ul>
    <div style="clear: both;"></div>
    <?php
    include_partial("category/paginator", array("page" => $page, "pagesCount" => $pagesCount));
    ?>

    <?php
    JSInPages("function toggleSpoiler(tag){
                $(tag).parent().children('.spoiler').fadeIn(0);
                $(tag).remove();
                }");
    ?>
    <?php
    if (@mb_strlen(strip_tags($collection['content'])) > 0) {
        ?>
        <div class="description">
            <div class="top">
                <?= $collection['name'] ?>
            </div>
            <?= mb_substr(strip_tags($collection['content']), 0, 150) ?>
            <div class="btnSpoiler" onclick="toggleSpoiler(this);">
                <div class="Icon">
                </div>
                <div class="Text">
                    Развернуть
                </div>
            </div>
            <div class="spoiler">
                <?= @mb_substr(strip_tags($collection['content']), 150, mb_strlen(strip_tags($collection['content']))) ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>



<div id="filtersShow">

    <form action="" id="categoryFilters" method="POST">
        <input type="hidden" name="page" value="<?= $page ?>" id="pageFromFilter">
    </form>
</div>