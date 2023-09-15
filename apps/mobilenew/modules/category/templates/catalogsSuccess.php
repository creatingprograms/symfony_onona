<?php
mb_internal_encoding('UTF-8');
slot('metaTitle', "Каталог товаров");
slot('metaKeywords', "Каталог товаров");
slot('metaDescription', "Каталог товаров");
?>


<div id="catalogs">
    <ul class="list">
        <?php foreach ($catalogs as $catalog['id'] => $catalog): ?>
            <li>
                <a href="<?php echo url_for('@catalog?slug=' . $catalog['slug']) ?>">
                    <div class="catalog">
                        <div class="toggleCatalog arrowRight">

                        </div>
                        <div class="nameCatalog">
                            <?= $catalog['name'] ?> <?= $catalog['description'] ?>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>