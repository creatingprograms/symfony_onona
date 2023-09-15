<?php
mb_internal_encoding('UTF-8');
/* slot('metaTitle', "Лучшая цена - Каталог товаров");
  slot('metaKeywords', "Лучшая цена - Каталог товаров");
  slot('metaDescription', "Лучшая цена - Каталог товаров"); */

slot('metaTitle', $category['title'] == '' ? str_replace("{name}", $category['name'], csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "") : $category['title'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "") );
slot('metaKeywords', $category['keywords'] == '' ? str_replace("{name}", $category['name'], csSettings::get('titleCategory')) : $category['keywords']);
slot('metaDescription', $category['description'] == '' ? str_replace("{name}", $category['name'], csSettings::get('titleCategory')) : $category['description']);
?>


<div id="categoryShow">

    <div class="catalog breadcrumbs active">
        <div class="toggleCatalog arrowDown">

        </div>
        <div class="nameCatalog">
            Лучшая цена
        </div>
    </div>
</div>


<div id="productsShow">
    <?php
    include_partial("category/paginator", array("page" => $page, "pagesCount" => $pagesCount));
    foreach ($categorys as $num => $category):
        ?>
        <div class="point">
            <div class="topPoint">
                <div class="togglePoint minus" onclick="togglePoint(this);">
                </div>
                <div class="namePointRed">
                    <?= $categorys[$num]['name'] ?>
                </div>
            </div>
            <ul class="product">
                <?php
                foreach ($products[$num] as $product['id'] => $product) {
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
            <a href="<?php echo url_for('@category?slug=' . $categorys[0]['slug']) ?>">
                <div class="bottomPoint">

                    <div class="arrowRight">
                    </div>
                    <div class="textPoint">
                        Все товары - <?= $categorys[0]['name'] ?>
                    </div>
                </div>
            </a>
        </div>
        <?php
    endforeach;
    ?>
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
    if (@mb_strlen(strip_tags($category['content'])) > 0) {
        ?>
        <div class="description">
            <div class="top">
                <?= $category['name'] ?>
            </div>
            <?= mb_substr(strip_tags($category['content']), 0, 150) ?>
            <div class="btnSpoiler" onclick="toggleSpoiler(this);">
                <div class="Icon">
                </div>
                <div class="Text">
                    Развернуть
                </div>
            </div>
            <div class="spoiler">
                <?= @mb_substr(strip_tags($category['content']), 150, mb_strlen(strip_tags($category['content']))) ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<div id="filtersShow">
    <form action="" id="categoryFilters" method="POST">
        <input type="hidden" name="page" value="<?= $page ?>" id="pageFromFilter">
        <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>" id="sortOrderFilter">
        <input type="hidden" name="direction" value="<?= $direction ?>" id="directionFilter">
    </form>
</div>