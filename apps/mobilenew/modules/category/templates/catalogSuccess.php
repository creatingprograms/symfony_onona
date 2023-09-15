<?php
mb_internal_encoding('UTF-8');
slot('metaTitle', current($categorys)['catalog_title'] == '' ? current($categorys)['catalog_name'] . " " . current($categorys)['catalog_description'] . " – Секс шоп Он и Она" : current($categorys)['catalog_title'] );
slot('metaKeywords', current($categorys)['catalog_keywords'] == '' ? current($categorys)['catalog_name'] . " " . current($categorys)['catalog_description'] : current($categorys)['catalog_keywords']);
slot('metaDescription', current($categorys)['catalog_metadescription'] == '' ? current($categorys)['catalog_name'] . " " . current($categorys)['catalog_description'] : current($categorys)['catalog_metadescription']);
/*
slot('metaTitle', "Каталог товаров");
slot('metaKeywords', "Каталог товаров");
slot('metaDescription', "Каталог товаров");*/
?>


<div id="catalogShow">
    <div class="catalog active">
        <div class="toggleCatalog arrowDown">

        </div>
        <div class="nameCatalog">
            <?= current($categorys)['catalog_name'] ?> <?= current($categorys)['catalog_description'] ?>
        </div>
    </div>
    <ul class="list">
        <?php foreach ($categorys as $category['id'] => $category): ?>
            <li>
                <a href="<?php echo url_for('@category?slug=' . $category['slug']) ?>">
                    <div class="catalog">
                        <div class="toggleCatalog arrowRight">

                        </div>
                        <div class="nameCatalog">
                            <?= $category['name'] ?>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>


    <?php
    JSInPages("function togglePoint(tag){
                $(tag).parent().parent().children('ul').toggle();
                $(tag).parent().parent().children('.bottomPoint').toggle();
                if($(tag).hasClass('minus')){
                    $(tag).removeClass('minus').addClass('plus');
                }else{
                    $(tag).removeClass('plus').addClass('minus');
                }
                }");
    ?>

    <div class="point bestsellerPoint">
        <div class="topPoint">
            <div class="togglePoint minus" onclick="togglePoint(this);">
            </div>
            <div class="namePointRed">
                Товары по лучшей цене в Рунете!
            </div>
        </div>
        <ul class="product">
            <?php
            foreach ($productsBestprice as $product['id'] => $product) {
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
    </div>

    <div class="point bestsellerPoint">
        <div class="topPoint">
            <div class="togglePoint minus" onclick="togglePoint(this);">
            </div>
            <div class="namePointRed">
                Случайные товары этого раздела
            </div>
        </div>
        <ul class="product">
            <?php
            foreach ($productsRandom as $product['id'] => $product) {
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
    </div>


</div>