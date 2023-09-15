<?php
mb_internal_encoding('UTF-8'); /*
  slot('metaTitle', "Каталог товаров");
  slot('metaKeywords', "Каталог товаров");
  slot('metaDescription', "Каталог товаров"); */
slot('metaTitle', str_replace("{name}", end($categorys)['this_name'], csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
slot('metaKeywords', str_replace("{name}", end($categorys)['this_name'], csSettings::get('titleCategory')));
slot('metaDescription', str_replace("{name}", end($categorys)['this_name'], csSettings::get('titleCategory')));
?>


<div id="categoryShow">
    <?php
    if (@is_array($categorys)):
        if ($catalog['catalog_slug'] != "") {
            ?>
            <a href="<?php echo url_for('@catalog?slug=' . $catalog['catalog_slug']) ?>" class="noDecoration">
                <div class="catalog breadcrumbs">
                    <div class="toggleCatalog arrowDown">

                    </div>
                    <div class="nameCatalog">
                        <?= $catalog['catalog_name'] ?> <?= $catalog['catalog_description'] ?>
                    </div>
                </div>
            </a>
            <?php
        }
        if (end($categorys)['parent_name'] != ""):
            ?>
            <a href="<?php echo url_for('@category?slug=' . end($categorys)['parent_slug']) ?>" class="noDecoration">
                <div class="catalog breadcrumbs">
                    <div class="toggleCatalog arrowDown">

                    </div>
                    <div class="nameCatalog">
                        <?= end($categorys)['parent_name'] ?>
                    </div>
                </div>
            </a>
        <?php endif; ?>
        <div class="catalog breadcrumbs active">
            <div class="toggleCatalog arrowDown">

            </div>
            <div class="nameCatalog">
                <?= end($categorys)['this_name'] ?>
            </div>
        </div>
        <ul class="list">
            <?php
            if (end($categorys)['name'] != ""):
                foreach ($categorys as $category['id'] => $category):
                    ?>
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
                    <?php
                endforeach;
            endif;
            ?>
        </ul>
        <?php
    else:
        ?>

        <div class="catalog breadcrumbs active">
            <div class="toggleCatalog arrowDown">

            </div>
            <div class="nameCatalog">
                <?= $categoryName ?>
            </div>
        </div>
    <?php
    endif;
    ?>
</div>


<?php
if (@is_array($categorys)):
    ?>
    <?php
    JSInPages("function sortFilterButton(type){
    if(type=='sort'){
    $('#categoryShow').fadeOut(0);
    $('#productsShow').fadeOut(0);
    $('#filtersShow').fadeOut(0);
    $('#sortFilterButton').fadeOut(0);
    $('#sortShow').fadeIn(0);
    }else{
    $('#categoryShow').fadeOut(0);
    $('#productsShow').fadeOut(0);
    $('#sortShow').fadeOut(0);
    $('#sortFilterButton').fadeOut(0);
    $('#filtersShow').fadeIn(0);
    }
    
                }");
    ?>
    <div id="sortFilterButton">
        <div>
            <div class="button" onclick="sortFilterButton('sort')">
                Сортировка
            </div>
        </div>
        <div>
            <div class="button" onclick="sortFilterButton('filters')">
                Фильтр товаров
            </div>
        </div>
    </div>


    <?php
endif;
?>
<div id="productsShow">
    <?php
    include_partial("category/paginator", array("page" => $page, "pagesCount" => $pagesCount));
    if (count($products) > 0):
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
        <?php
    else:
        ?><div style="padding: 20px;">Нет подходящих результатов</div><?php
    endif;
    ?>
    <div style="clear: both;"></div>

    <?php
    JSInPages("function LoadProducts(button){
                    $('.pageId-'+$('#pageFromFilter').val()).removeClass('active');
                    $('#pageFromFilter').val(parseInt($('#pageFromFilter').val(),10)+1);
                    var queryString = $('#categoryFilters').formSerialize();
                    var redirect = '" . explode('?', $_SERVER['REQUEST_URI'])[0] . "?' + queryString;
                    history.pushState('', '', redirect);
    
    
                    $('input[name=loadProducts]').val('1');
                    var queryString = $('#categoryFilters').formSerialize();
                    var redirect = '" . explode('?', $_SERVER['REQUEST_URI'])[0] . "?' + queryString;
                        
                    $.post( redirect, queryString, function( data ) {
                        $('#productsShow ul.product').append(data);
                    } ).always(function() {
                        $('#productsShow ul.product').find('img.thumbnails').each(function () {
                            $(this).attr('src', $(this).attr('data-original'));
                        });
                    });


                    
                    $('input[name=loadProducts]').val('0');
                    
                    $('.pageId-'+$('#pageFromFilter').val()).addClass('active');
                    if(parseInt($('#pageFromFilter').val(),10)==" . $pagesCount . "){
                        $(button).remove();
                    }
                    
                    return false;
                }");
    ?>
    <?php if ($page != ($pagesCount)) {
        ?>
        <div class="silverButton" onclick="LoadProducts(this);">
            <div class="Text">
                Показать еще</div>
        </div>
    <?php } ?>
    <?php
    include_partial("category/paginator", array("page" => $page, "pagesCount" => $pagesCount));
    ?>

    <?php
    JSInPages("function toggleSpoiler(tag){
                $(tag).parent().children('.spoiler').fadeIn(0);
                $(tag).remove();
                }");



    if (@mb_strlen(end($categorys)['this_content']) > 0) {
        ?>
        <div class="description">
            <div class="top">
                <?= end($categorys)['this_name'] ?>
            </div>
            <?php
            
            $dom = new DOMDocument;
            $dom->loadHTML(mb_convert_encoding(end($categorys)['this_content'], 'HTML-ENTITIES', 'UTF-8'));
            foreach ($dom->getElementsByTagName('iframe') as $node) {
                if ($node->hasAttribute('src')) {
                    echo '<div style="display: block;position: relative;padding-top: 56.2857%;">
<iframe allowfullscreen="" frameborder="0" height="425" src="' . $node->getAttribute('src') . '" style="position: absolute;top: 0;left: 0;width: 100%;height: 100%;"></iframe></div>';

                    $node->parentNode->removeChild($node);
                }
            }
            $categoryContent=mb_convert_encoding($dom->saveHTML(), 'UTF-8', 'HTML-ENTITIES');;
            if (substr_count($categoryContent, "{replaceSpoiler}")) {

                echo str_replace("{replaceSpoiler}", '<div class="btnSpoiler" onclick="toggleSpoiler(this);">
                <div class="Icon">
                </div>
                <div class="Text">
                    Развернуть
                </div>
            </div>
            <div class="spoiler">', $categoryContent);
            } else {
                echo "<div>" . $categoryContent;
            }
            ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>


<?php
JSInPages("function setSortOrder(sortOrder,direction){
                    $('#sortOrderFilter').val(sortOrder);
                    $('#directionFilter').val(direction);
                    
                    
                    var queryString = $('#categoryFilters').formSerialize();
                    var redirect = '" . explode("?", $_SERVER['REQUEST_URI'])[0] . "?' + queryString;
                    history.pushState('', '', redirect);
                    
                    jQuery('#categoryFilters').submit();
                }");
?>
<div id="sortShow">

    <ul class="list">
        <li>
            <div onclick="setSortOrder('', '');"<?= $sortOrder == "" ? ' style="background:#d0d0d0"' : "" ?>>
                <div class="arrowRight">
                </div>
                <div>По популярности</div>
            </div>
        </li>
        <li>
            <div onclick="setSortOrder('date', '');"<?= $sortOrder == "date" ? ' style="background:#d0d0d0"' : "" ?>>

                <div class="arrowRight">
                </div>
                <div>По новинкам</div>
            </div>
        </li>
        <li>
            <div onclick="setSortOrder('actions', '');"<?= $sortOrder == "actions" ? ' style="background:#d0d0d0"' : "" ?>>

                <div class="arrowRight">
                </div>
                <div>По акциям и скидкам</div>
            </div>
        </li>
        <li>
            <div onclick="setSortOrder('price', '<?= ($direction == "asc" and $sortOrder == "price") ? 'desc' : 'asc' ?>');"<?= $sortOrder == "price" ? ' style="background:#d0d0d0"' : "" ?>>

                <div class="arrowRight">
                </div>
                <div>По цене<?= $sortOrder == "price" ? ($direction == "asc" ? ' - по возрастанию' : ' - по убыванию') : "" ?></div>
            </div>
        </li>
        <li>
            <div onclick="setSortOrder('comments', '');"<?= $sortOrder == "comments" ? ' style="background:#d0d0d0"' : "" ?>>

                <div class="arrowRight">
                </div>
                <div>По отзывам</div>
            </div>
        </li>
    </ul>
</div>


<div id="filtersShow">


    <?php
    JSInPages("function toggleFilter(tag){
                $(tag).parent().children('.valueParams').toggle();
                if($(tag).children('.toggleCatalog').hasClass('minus')){
                    $(tag).children('.toggleCatalog').removeClass('minus').addClass('plus');
                }else{
                    $(tag).children('.toggleCatalog').removeClass('plus').addClass('minus');
                }
                }");
    
    JSInPages("$('#categoryFilters input, #categoryFilters img').click(function() {
                    var queryString = $('#categoryFilters').formSerialize();
                    var redirect = '" . explode('?', $_SERVER['REQUEST_URI'])[0] . "?' + queryString;
                    IsNewDataCountFilter=false;
                    $.post( redirect, queryString, function( data ) {
                        jQuery.each($(data), function(i, val) {
                            if($(val).attr('id')=='filtersCountProducts'){
                                filtersCountProducts = JSON.parse($(val).html());
                                $('input.filteParams').each(function (index) {
                                    if (filtersCountProducts[$(this).val()] !== undefined) {
                                        $(this).parent().find('div.countProd').html(filtersCountProducts[$(this).val()]['count']);
                                    } else {
                                        $(this).parent().find('div.countProd').html(0);
                                    }
                                });
                                IsNewDataCountFilter=true;
                            }
                        });
                    } );
                    if(!IsNewDataCountFilter){
                    
                        $('input.filteParams').each(function (index) {
                            $(this).parent().find('div.countProd').html($(this).parent().find('div.countProd').data('default'));
                                                                                                                               
                        });
                    }
                });");
    ?>
    <form action="" id="categoryFilters" method="get">
        <input type="hidden" name="page" value="<?= $page ?>" id="pageFromFilter">
        <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>" id="sortOrderFilter">
        <input type="hidden" name="direction" value="<?= $direction ?>" id="directionFilter">
        <input type="hidden" name="loadProducts" value="0" id="pageFromFilter">
        <?php
        $filtersDB = unserialize(end($categorys)['filters']);
        ?>
        <ul class="list filtersLi">
            <li>
                <div id="paramsPrice" class="params">
                    <div class="name">Цена</div>
                    <div class="value">
                        от <input type="text" class="textParam" id="minCostPrice" value="<?= $price['from'] != "" ? $price['from'] : end($categorys)['minPrice'] ?>" name="Price[from]" data-value="<?= end($categorys)['minPrice'] ?>" /> 
                        до <input type="text" class="textParam" id="maxCostPrice" value="<?= $price['to'] != "" ? $price['to'] : end($categorys)['maxPrice'] ?>" name="Price[to]" data-value="<?= end($categorys)['maxPrice'] ?>" />
                    </div>
                </div>
            </li>
            <?php
            if (is_array($filtersDB))
                foreach ($filtersDB as $diId => $filter) :
                    ?><li>
                        <?php
                        if (@is_array($filter['range'])) {
                            ?>
                            <div class="params">
                                <div class="name"><?= $filter['nameCategory'] ?></div>
                                <div class="value">
                                    от <input type="text" class="textParam" id="minCost<?= $diId ?>" value="<?= $filters[$diId]['from'] != "" ? $filters[$diId]['from'] : $filter['range']['min'] ?>" name="filters[<?= $diId ?>][from]" data-value="<?= $filter['range']['min'] ?>"/>
                                    до <input type="text" class="textParam" id="maxCost<?= $diId ?>"  value="<?= $filters[$diId]['to'] != "" ? $filters[$diId]['to'] : $filter['range']['max'] ?>" name="filters[<?= $diId ?>][to]" data-value="<?= $filter['range']['max'] ?>" />
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="params" onclick="toggleFilter(this)">
                                <div class="toggleCatalog plus">

                                </div>
                                <div class="name"><?= $filter['nameCategory'] ?></div>
                            </div>
                            <div class="valueParams" style="display: none;">
                                <?php
                                if (is_array($filter))
                                    foreach ($filter as $paramKey => $filterParam) {
                                        if ($paramKey !== "nameCategory") {
                                            $checked = "";
                                            if (@is_array($filters[$diId])) {
                                                if (array_search($paramKey, $filters[$diId]) !== false) {
                                                    $checked = "checked=\"checked\"";
                                                    $imgClass = "class=\"colorFilterButton-select\"";
                                                } else {
                                                    $imgClass = "class=\"colorFilterButton\"";
                                                }
                                            }
                                            if (isset($filterParam['filename'])) {
                                                echo '<div style="display: inline-block;margin: 5px;"><input type="checkbox" ' . $checked . ' name="filters[' . $diId . '][]" value="' . $paramKey . '" class="filteParams" style="opacity:0; position:absolute; left:9999px;"><img src="/uploads/dopinfo/thumbnails/' . $filterParam['filename'] . '" alt="' . $filterParam['value'] . '" title="' . $filterParam['value'] . '" ' . $imgClass . ' width="28" onClick=" if ( $(this).hasClass(\'colorFilterButton\') ) {$(this).removeClass(\'colorFilterButton\').addClass(\'colorFilterButton-select\');$(this).prev(\'input\').attr(\'checked\', \'checked\');}else{$(this).removeClass(\'colorFilterButton-select\').addClass(\'colorFilterButton\');$(this).prev(\'input\').removeAttr(\'checked\');} " style="cursor: pointer;"></div>';
                                            } else {
                                                echo '<div><input type="checkbox" ' . $checked . ' name="filters[' . $diId . '][]" value="' . $paramKey . '" class="filteParams"><div style="display: inline;"> - ' . $filterParam['value'] . ' (<div style="display: inline;" class="countProd" data-default="' . $filterParam['countProducts'] . '">' . (@is_array($filtersCountProducts) ? (@$filtersCountProducts[$paramKey]['count'] != "" ? @$filtersCountProducts[$paramKey]['count'] : '0') : $filterParam['countProducts']) . '</div>)</div></div>';
                                            }
                                        }
                                    }
                                ?> </div>
                            <?php
                        }
                        ?>
                    </li>
                    <?php
                endforeach;
            ?>
        </ul>

        <?php
        JSInPages("
                function clearFilters(){
                $('.colorFilterButton-select').each(function (index) {
                $(this).removeClass('colorFilterButton-select').addClass('colorFilterButton');
                });
                $('#categoryFilters input').each(function (index) {
                if($(this).attr('type')=='text'){
                $(this).val($(this).attr('data-value'));
                }else{
                $(this).removeAttr('checked');

                }
                });
                }
                ");
        ?>
        <div id="clearFilters" onClick="clearFilters()">
            <div class="clearFiltersIcon">
            </div>
            <div class="text">
                Очистить все фильтры
            </div>
        </div>
        <div id="enableFilters" onClick="jQuery('#categoryFilters').submit();">
            <div class="button">
                Применить
            </div>
        </div>

    </form>
</div>
    
<?php if (@is_array($_POST['filters']) and @ is_array($filtersCountProducts)): ?>
    <div id="filtersCountProducts" style="display: none;"><?= json_encode($filtersCountProducts) ?></div>
<?php endif; ?>