<? If ((sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_show" or sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_show_page") and $sf_request->getParameter('slug') != "skidki-do-99" and $sf_request->getParameter('slug') != "Luchshaya_tsena" and $sf_request->getParameter('slug') != "vesennee-specpredlozhenie" and $sf_request->getParameter('slug') != "podarki" and $sf_request->getParameter('slug') != "upravlyai-cenoi") { ?><div style="margin: auto auto -4px; width: 150px; text-align: center; border: 1px solid rgb(238, 238, 238); padding: 3px 0;cursor: pointer;" id="filterSlider" onClick="
            if ($('#filters').css('display') == 'none') {
                $('#filterSlider').html('<u style=\'color:#04590e\'>Меню</u> / Фильтры');
                $('#filters').css('display', 'block');
                $('ul.cat-menu').css('display', 'none');
                $('#buttonFilter span').html('Главное меню <img src=\'/images/filters/silver-str-right.png\'  style=\'position: relative; top: 2px;\' alt=\'silverbutton\'  />');
                $('#buttonFilter').removeClass('filtersButton').addClass('categoryButton');
            } else {
                $('#filterSlider').html('Меню / <u style=\'color:#04590e\'>Фильтры</u>');
                $('#filters').css('display', 'none');
                $('ul.cat-menu').css('display', 'block');
                $('#buttonFilter span').html('Фильтры товаров <img src=\'/images/filters/green-str-right.png\'  style=\'position: relative; top: 2px;\' alt=\'greenbutton\'  />');
                $('#buttonFilter').removeClass('categoryButton').addClass('filtersButton');

                $('span.clearparams').each(function (i, elem) {
                    $(elem).click();
                });
            }
         ">Меню / <u style='color:#04590e'>Фильтры</u></div>

    <div class="filtersButton" style="position: absolute; right: 240px; top: 25px;" id="buttonFilter" onClick="
            if ($('#filters').css('display') == 'none') {
                $('#filterSlider').html('<u style=\'color:#04590e\'>Меню</u> / Фильтры');
                $('#filters').css('display', 'block');
                $('ul.cat-menu').css('display', 'none');
                $('#buttonFilter span').html('Главное меню <img src=\'/images/filters/silver-str-right.png\'  style=\'position: relative; top: 2px;\' alt=\'silverbutton\' />');
                $('#buttonFilter').removeClass('filtersButton').addClass('categoryButton');
            } else {
                $('#filterSlider').html('Меню / <u style=\'color:#04590e\'>Фильтры</u>');
                $('#filters').css('display', 'none');
                $('ul.cat-menu').css('display', 'block');
                $('#buttonFilter span').html('Фильтры товаров <img src=\'/images/filters/green-str-right.png\'  style=\'position: relative; top: 2px;\' alt=\'greenbutton\' />');
                $('#buttonFilter').removeClass('categoryButton').addClass('filtersButton');

                $('span.clearparams').each(function (i, elem) {
                    $(elem).click();
                });
            }
         "></div>
     <? } ?>
<ul class="cat-menu"><?
    /* // Считываем текущее время
      $current_time = microtime();
      // Отделяем секунды от миллисекунд
      $current_time = explode(" ", $current_time);
      // Складываем секунды и миллисекунды
      $start_time = $current_time[1] + $current_time[0]; */
    slot('category_slug', $slot);
//get_slot('category_slug')
//SELECT catalog.*,category.* FROM `catalog` left join category_catalog on category_catalog.catalog_id=catalog.id left join category on category_catalog.category_id=category.id where catalog.is_public='1' and category.is_public='1' order by catalog.position ASC ,category.position ASC
//SELECT catalog.id as catid,catalog.name as catname,catalog.img as catimg,catalog.description as catdescription,catalog.slug as catslug,category.*,categoryChild.*,c.id AS c__id, c.name AS c__name, c.content AS c__content, c.is_open AS c__is_open, c.is_public AS c__is_public, c.adult AS c__adult, c.title AS c__title, c.keywords AS c__keywords, c.description AS c__description, c.parents_id AS c__parents_id, c.lovepricename AS c__lovepricename, c.positionloveprice AS c__positionloveprice, c.filters AS c__filters, c.tags AS c__tags, c.created_at AS c__created_at, c.updated_at AS c__updated_at, c.position AS c__position, c.slug AS c__slug  FROM `catalog` left join category_catalog on category_catalog.catalog_id=catalog.id left join category on category_catalog.category_id=category.id left join category as categoryChild on category.id=categoryChild.parents_id where catalog.is_public='1' and category.is_public='1' order by catalog.position ASC ,category.position ASC
//SELECT catalog.id as catid,catalog.name as catname,catalog.img as catimg,catalog.description as catdescription,catalog.slug as catslug,category.*,categoryChild.id AS c__id, categoryChild.name AS c__name, categoryChild.parents_id AS c__parents_id, categoryChild.slug AS c__slug  FROM `catalog` left join category_catalog on category_catalog.catalog_id=catalog.id left join category on category_catalog.category_id=category.id left join category as categoryChild on category.id=categoryChild.parents_id where catalog.is_public='1' and category.is_public='1' and categoryChild.is_public='1' order by catalog.position ASC ,category.position ASC
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $result = $q->execute("SELECT catalog.id as catid,catalog.name as catname,catalog.img as catimg,catalog.description as catdescription,catalog.slug as catslug,category.*,categoryChild.id AS c__id, categoryChild.name AS c__name, categoryChild.parents_id AS c__parents_id, categoryChild.slug AS c__slug  FROM `catalog` left join category_catalog on category_catalog.catalog_id=catalog.id left join category on category_catalog.category_id=category.id left join category as categoryChild on category.id=categoryChild.parents_id and categoryChild.is_public='1' where catalog.is_public='1' and category.is_public='1' and (categoryChild.is_public!='0' or categoryChild.is_public is null) order by catalog.position ASC ,category.position ASC");

    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $categorys = $result->fetchAll();

    if (get_slot('category_slug') != "") {
        $slugCategory = get_slot('category_slug');
    } else {
        $slugCategory = $sf_request->getParameter('slug');
    }

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $selectCategory = $q->execute("SELECT catalog.id AS catid, catalog.name AS catname, catalog.img AS catimg, catalog.description AS catdescription, catalog.slug AS catslug, category . * , categoryChild.id AS c__id, categoryChild.name AS c__name, categoryChild.parents_id AS c__parents_id, categoryChild.slug AS c__slug
FROM  `catalog` 
LEFT JOIN category_catalog ON category_catalog.catalog_id = catalog.id
LEFT JOIN category ON category_catalog.category_id = category.id
LEFT JOIN category AS categoryChild ON category.id = categoryChild.parents_id
WHERE catalog.slug = ? 
OR category.slug = ? 
OR categoryChild.slug  = ? 
OR catalog.id  = ? 
OR category.id  = ? 
OR categoryChild.id  = ? ", array($slugCategory, $slugCategory, $slugCategory, $slugCategory, $slugCategory, $slugCategory))->fetchAll(Doctrine_Core::FETCH_ASSOC);
    $categoryArray = array();
    $CatalogWork = "";
    $CategoryWork = "";
    $parentIdRam = "";
    foreach ($categorys as $keyCategory => $category) :
        /* $categoryArray[$category['catid']]['catdescription']=$category['catdescription'];
          $categoryArray[$category['catid']]['catimg']=$category['catimg'];
          $categoryArray[$category['catid']]['category'][$category['id']]= */
        if ($CatalogWork != $category['catname']) :
            $CatalogWork = $category['catname'];
            //print_r($categorys);
            ?> 
            <li<? if ($category['catslug'] == $selectCategory[0]['catslug']) echo ' class="active"'; ?>><a id="catalog_<?= $category['catid'] ?>" href="<?= '/catalog/' . $category['catslug'] ?>"
                                                                                                           onClick="location.replace('<?= '/catalog/' . $category['catslug'] ?>')">

                    <span class="arrow"></span>
                    <span class="text">
                        <strong><?= $category['catname'] ?></strong>
                        <em><?= $category['catdescription'] ?></em>
                    </span><span class="img-holder">
                        <img width="90" height="118" alt="image description" src="/uploads/photo/<?= $category['catimg'] ?>">
                    </span> 
                    <span class="deco-left"></span>
                    <span class="deco-right"></span>
                </a> 
                <ul <?
                if ($category['catslug'] == $selectCategory[0]['catslug']) {
                    echo "class=\"drop\"  style=\"display: block;\"";
                } else {
                    echo "class=\"dropLeft drop\"";
                }
                ?>>
                        <?
                    endif;
                    ?>    
                    <?
                    if ($CategoryWork != $category['name']) :
                        $CategoryWork = $category['name'];
                        ?><li<? if ($category['slug'] == $selectCategory[0]['slug'] and sfContext::getInstance()->getRouting()->getCurrentRouteName() != "catalog_show_newprod" and sfContext::getInstance()->getRouting()->getCurrentRouteName() != "catalog_show_relatecategory" and sfContext::getInstance()->getRouting()->getCurrentRouteName() != "catalog_show") echo ' class="active"'; ?>>

                        <a href="<?= '/category/' . $category['slug'] ?>"><?= $category['name'] ?></a>
                    <? endif; ?>
                    <?
                    //print_r($category);
                    If ($parentIdRam != $category['c__parents_id'] and $category['c__parents_id'] != 0) {
                        $parentIdRam = $category['c__parents_id'];
                        ?><ul<? if ($category['slug'] == $selectCategory[0]['slug'] and sfContext::getInstance()->getRouting()->getCurrentRouteName() != "catalog_show_newprod" and sfContext::getInstance()->getRouting()->getCurrentRouteName() != "catalog_show_relatecategory" and sfContext::getInstance()->getRouting()->getCurrentRouteName() != "catalog_show") echo ' style="display: block;"'; ?>><? } ?>
                        <? If ($category['c__parents_id'] != 0) { ?>

                            <li<? if ($category['c__slug'] == $slugCategory) echo ' class="active"'; ?>><a href="<?= '/category/' . $category['c__slug'] ?>"> <?= $category['c__name'] ?></a></li><? } ?>

                        <? if (@$parentIdRam != @$categorys[$keyCategory + 1]['c__parents_id'] and $category['c__parents_id'] != 0) {
                            ?></ul></li><?
                }
                if (@$CatalogWork != @$categorys[$keyCategory + 1]['catname']) :
                    ?><li<?
                            if ($category['catslug'] == $sf_request->getParameter('slug') and sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show_newprod") {
                                echo "  class=\"active\"";
                            }
                            ?>><a href="<?= '/catalog/' . $category['catslug'] . "/newprod" ?>" class="newrel">Новые товары</a></li>
                    <li<?
                    if ($category['catslug'] == $sf_request->getParameter('slug') and sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show_relatecategory") {
                        echo "  class=\"active\"";
                    }
                    ?>><a href="<?= '/catalog/' . $category['catslug'] . "/relatecategory" ?>" class="newrel">Лидеры продаж</a></li>
                        <?
                        echo "</ul></li>";
                    endif;


                endforeach;
                ?></ul>
            <?
            /*
              // То же, что и в 1 части
              $current_time = microtime();
              $current_time = explode(" ", $current_time);
              $current_time = $current_time[1] + $current_time[0];

              // Вычисляем время выполнения скрипта
              $result_time = ($current_time - $start_time);

              printf("<p><b>Время выполнения скрипта - %f секунд.</b></p>", $result_time);















              // Считываем текущее время
              $current_time = microtime();
              // Отделяем секунды от миллисекунд
              $current_time = explode(" ", $current_time);
              // Складываем секунды и миллисекунды
              $start_time = $current_time[1] + $current_time[0];
              ?>
              <ul class="cat-menu">
              <?php
              //$categorysCat = CategoryTable::getInstance()->createQuery('c')->select("*") ->addSelect("c2.id as childrenId") ->leftJoin("c.Children as c2")->addWhere('parents_id IS NULL')->addWhere('is_public = \'1\'')->groupBy("c.id")->orderBy('position ASC')->execute();
              $catalogs = CatalogTable::getInstance()->createQuery()->where("is_public='1'")->orderBy('position ASC')->execute();

              foreach ($catalogs as $catalog):

              slot('categoryEnable', false);
              //$categorys = $catalog->getCategoryCatalogs();

              $categorys = CategoryTable::getInstance()->createQuery('c')->select("*")->addSelect("c2.id as childrenId")->leftJoin("c.Children as c2")->leftJoin("Catalog as cc")->addWhere('parents_id IS NULL')->addWhere('cc.id =' . $catalog->getId())->addWhere('is_public = \'1\'')->groupBy("c.id")->orderBy('position ASC')->execute();
              //$categorys=$catalog->getCategoryCatalog()->getCategory();
              foreach ($categorys as $category):
              // echo $category->getName();
              foreach ($category->getCategoryCatalogs() as $catthiscat) {
              $catalogsThisCategory = $catthiscat->getId();
              }//exit;
              if ($category->getParentsId() == "" and $catalog->getId() == $catalogsThisCategory) {

              //echo $category."<br>";
              foreach ($category->getCategoryCatalogs() as $catthiscat) {
              $catalogsThisCategory = $catthiscat->getId();
              }
              if (($category->getSlug() == $sf_request->getParameter('slug') or get_slot('category_slug') == $category->getSlug()) and $catalog->getId() == $catalogsThisCategory) {
              slot('categoryEnable', true);
              }
              //echo $category->getId();
              $categorysSub = CategoryTable::getInstance()->createQuery('cs')->addWhere('parents_id = ?', $category->getId())->addWhere('is_public = 1')->orderBy('Position  Asc')->execute();
              $categorySubPerenos[$category->getId()] = $categorysSub;
              foreach ($categorysSub as $categorySub):
              //echo $categorySub->getSlug()."<br>";
              if ($categorySub->getSlug() == $sf_request->getParameter('slug') or get_slot('category_slug') == $categorySub->getSlug()) {
              slot('categoryEnable', true); // echo $categorySub->getSlug() ."==". get_slot('category_slug');
              }
              endforeach;
              }
              endforeach;
              ?>
              <li<?php
              if (get_slot('categoryEnable') or (sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show" and ($sf_request->getParameter('slug') == $catalog->getId() or $sf_request->getParameter('slug') == $catalog->getSlug()))) {
              echo " class=\"active\"";
              slot('categoryEnable', true);
              }
              ?>>
              <a id="catalog_<?= $catalog->getId() ?>" href="<?
              $i = 0;
              ?>/catalog/<?= $catalog->getSlug() ?><?
              $hrefCatalog = "/catalog/" . $catalog->getSlug();
              ?>"
              onClick="location.replace('<?= $hrefCatalog ?>')">
              <span class="img-holder">
              <img width="90" height="118" alt="image description" src="/uploads/photo/<?= $catalog->getImg() ?>">
              </span>
              <span class="text">
              <strong><?= $catalog->getName() ?></strong>
              <em><?= $catalog->getDescription() ?></em>
              </span>
              <span class="arrow"></span>
              <span class="deco-left"></span>
              <span class="deco-right"></span>
              </a>
              <?php
              if ($categorys->count() > 0):
              ?>
              <ul <?
              if (get_slot('categoryEnable') or (sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show" and ($sf_request->getParameter('slug') == $catalog->getId() or $sf_request->getParameter('slug') == $catalog->getSlug()))) {
              echo "class=\"drop\"  style=\"display: block;\"";
              slot('categoryEnable', false);
              } else {
              echo "class=\"dropLeft drop\"";
              }
              ?>>
              <?php
              foreach ($categorys as $category):
              $catalogsThisCategory = 0;
              foreach ($category->getCategoryCatalogs() as $catthiscat) {
              $catalogsThisCategory = $catthiscat->getId();
              }//exit;
              if ($category->getParentsId() == "" and $catalog->getId() == $catalogsThisCategory) {
              //$categorysSub = CategoryTable::getInstance()->createQuery('cs')->addWhere('parents_id = ?', $category->getId())->addWhere('is_public = 1')->execute();
              $categorysSub = $categorySubPerenos[$category->getId()];
              foreach ($categorysSub as $categorySub):
              if ($categorySub->getSlug() == $sf_request->getParameter('slug') or get_slot('category_slug') == $categorySub->getSlug()) {
              slot('categoryEnable', true);
              }
              endforeach;
              ?>
              <li<?php
              if (get_slot('categoryEnable') or $category->getSlug() == $sf_request->getParameter('slug') or get_slot('category_slug') == $category->getSlug()) {
              echo " class=\"active\"";
              slot('categoryEnable', true);
              }
              ?>><a href="/category/<?= $category->getSlug() ?>"><?= $category->getName() ?></a>
              <?php
              if ($categorysSub->count() > 0):
              ?><ul<?
              if (get_slot('categoryEnable') or $category->getSlug() == $sf_request->getParameter('slug')) {
              echo "  style=\"display: block;\"";
              slot('categoryEnable', false);
              }
              ?>><?php
              foreach ($categorysSub as $categorySub):
              ?><li<?
              if ($categorySub->getSlug() == $sf_request->getParameter('slug') or $categorySub->getSlug() == get_slot('category_slug')) {
              echo "  class=\"active\"";
              }
              ?>><a href="/category/<?= $categorySub->getSlug() ?>"><?= $categorySub->getName() ?></a></li><?php
              endforeach;
              ?></ul><?php
              endif;

              slot('categoryEnable', false);
              ?>
              </li>
              <?php
              }
              endforeach;
              ?>
              </ul>
              <?php
              endif;
              ?></li><?php
              endforeach;
              ?>

              </ul>
              <?
              // То же, что и в 1 части
              $current_time = microtime();
              $current_time = explode(" ", $current_time);
              $current_time = $current_time[1] + $current_time[0];

              // Вычисляем время выполнения скрипта
              $result_time = ($current_time - $start_time);

              printf("<p><b>Время выполнения скрипта - %f секунд.</b></p>", $result_time);


             */
            ?>

        <?
        if ($this->getModuleName() == "category" and ( sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_show" or sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_show_page" )) {
            ?>


            <div id="filters" style="display:none;">
                <script src="/js/jquery.ui-slider.js"></script>
                <script>
                                                                                                           // wait for the DOM to be loaded 
                                                                                                           $(document).ready(function () {

                                                                                                               $.post("<?= url_for('@category_filters_block?slug=' . $sf_request->getParameter('slug')) ?>", {},
                                                                                                                       function (data) {
                                                                                                                           var filter = data;

                                                                                                                           for (did in filter) {
                                                                                                                               for (valuefilter in filter[did]) {
                                                                                                                                   /*countprod=filter[did][valuefilter];
                                                                                                                                    $("#params"+did+" input[value=\""+valuefilter+"\"]").parent().children("div").text(" - "+valuefilter+" ("+countprod+")");
                                                                                                                                    //console.log($("#params"+did+" input[value=\""+valuefilter+"\"]").next());
                                                                                                                                    $("#params"+did+" input[value=\""+valuefilter+"\"]").next('img').attr("title", valuefilter+" ("+countprod+")");*/
                                                                                                                                   countprod = filter[did][valuefilter];
                                                                                                                                   $("#params" + did + " input[value=\"" + valuefilter + "\"]").parent().children("div").text(" - " + valuefilter + " (" + countprod + ")");
                                                                                                                                   if (countprod == 0) {
                                                                                                                                       $("#params" + did + " input[value=\"" + valuefilter + "\"]").attr("disabled", true);
                                                                                                                                   } else {
                                                                                                                                       $("#params" + did + " input[value=\"" + valuefilter + "\"]").removeAttr("disabled");
                                                                                                                                   }
                                                                                                                                   //console.log($("#params"+did+" input[value=\""+valuefilter+"\"]").next('img'));
                                                                                                                                   $("#params" + did + " input[value=\"" + valuefilter + "\"]").next('img').attr("title", valuefilter + " (" + countprod + ")");

                                                                                                                                   if (countprod == 0 && $("#params" + did + " input[value=\"" + valuefilter + "\"]").next('img').length) {
                                                                                                                                       $("#params" + did + " input[value=\"" + valuefilter + "\"]").parent().fadeOut();
                                                                                                                                   } else {
                                                                                                                                       $("#params" + did + " input[value=\"" + valuefilter + "\"]").parent().fadeIn();
                                                                                                                                   }
                                                                                                                               }
                                                                                                                           }
                                                                                                                       }, "json");

                                                                                                               // bind 'myForm' and provide a simple callback function 
                                                                                                               $('#categoryFilters').ajaxForm(function (result) {
                                                                                                                   var qString = $("#categoryFilters").formSerialize();
                                                                                                                   //console.log(qString);
                                                                                                                   //$.post("myscript.php", qString);
                                                                                                                   $.post("<?= url_for('@category_filters_block?slug=' . $sf_request->getParameter('slug')) ?>", qString,
                                                                                                                           function (data) {
                                                                                                                               var filter = data;

                                                                                                                               for (did in filter) {
                                                                                                                                   for (valuefilter in filter[did]) {
                                                                                                                                       countprod = filter[did][valuefilter];
                                                                                                                                       $("#params" + did + " input[value=\"" + valuefilter + "\"]").parent().children("div").text(" - " + valuefilter + " (" + countprod + ")");
                                                                                                                                       if (countprod == 0) {
                                                                                                                                           $("#params" + did + " input[value=\"" + valuefilter + "\"]").attr("disabled", true);
                                                                                                                                       } else {
                                                                                                                                           $("#params" + did + " input[value=\"" + valuefilter + "\"]").removeAttr("disabled");
                                                                                                                                       }
                                                                                                                                       //console.log($("#params"+did+" input[value=\""+valuefilter+"\"]").next('img'));
                                                                                                                                       $("#params" + did + " input[value=\"" + valuefilter + "\"]").next('img').attr("title", valuefilter + " (" + countprod + ")");

                                                                                                                                       if (countprod == 0 && $("#params" + did + " input[value=\"" + valuefilter + "\"]").next('img').length) {
                                                                                                                                           $("#params" + did + " input[value=\"" + valuefilter + "\"]").parent().fadeOut();
                                                                                                                                       } else {
                                                                                                                                           $("#params" + did + " input[value=\"" + valuefilter + "\"]").parent().fadeIn();
                                                                                                                                       }
                                                                                                                                   }
                                                                                                                               }
                                                                                                                           }, "json");
                                                                                                                   $("#content").html(result);
                                                                                                                   initPopup();
                                                                                                                   viravnivanie()
                                                                                                                   /*$("div.c div.notcount").hover(function () {
                                                                                                                       $(this).stop().animate({"opacity": 1});
                                                                                                                   }, function () {
                                                                                                                       $(this).stop().animate({"opacity": 0.3});
                                                                                                                   });*/
    <?
    /* if ($this->getModuleName() == "category" or get_slot('sexshop') or true) {
      $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
      //print_r($products_old);
      if (is_array($products_old))
      foreach ($products_old as $key => $product) {
      echo "changeButtonToGreen(" . $product["productId"] . ");";
      }
      //changeButtonToGreen(id)
      } */
    ?>
                                                                                                                   $("img.thumbnails").lazyload();
                                                                                                                   //console.log(result);
                                                                                                               });
                                                                                                           });
                </script> 
                <form action="<?= url_for('@category_filters_show?slug=' . $sf_request->getParameter('slug')) ?>" id="categoryFilters" method="POST">
                    <input type="hidden" name="page" value="1" id="pageFromFilter"><?php
                    $category = CategoryTable::getInstance()->findOneBySlug($sf_request->getParameter('slug'));
                    if (!$category) {
                        $category = CategoryTable::getInstance()->findOneById($sf_request->getParameter('slug'));
                    }
                    $filters = unserialize($category->get('filters'));
                    //print_r($filters);
                    if (isset($filters[24]))
                        @$newFilters[24] = $filters[24];
                    if (isset($filters[56]))
                        @$newFilters[56] = $filters[56];
                    if (isset($filters[58]))
                        @$newFilters[58] = $filters[58];
                    if (isset($filters[23]))
                        @$newFilters[23] = $filters[23];
                    if (isset($filters[22]))
                        @$newFilters[22] = $filters[22];
                    foreach ($filters as $key => $filter) {

                        $newFilters[$key] = $filter;
                    }
                    if (isset($newFilters))
                        $filters = $newFilters;
                    /* Фильтр по цене */
                    $categoryChildrens = $category->getChildren();
                    $idChildrenCategory = implode(',', $categoryChildrens->getPrimaryKeys());
                    if ($idChildrenCategory != "")
                        $idCategorysForQuery = $category->getId() . "," . $idChildrenCategory;
                    else
                        $idCategorysForQuery = $category->getId();
                    $rangePrice = Doctrine_Core::getTable('Product')
                                    ->createQuery('p')->select("max(price) as maxPrice")->addSelect("min(price) as minPrice")
                                    ->leftJoin('p.CategoryProduct c')
                                    ->addWhere('parents_id IS NULL')
                                    ->addWhere("(c.category_id IN (" . $idCategorysForQuery . "))")
                                    ->addWhere('is_public = \'1\'')
                                    ->fetchOne()->toArray();
//print_r($rangePrice);
                    $minPrice = $rangePrice['minPrice'];
                    $maxPrice = $rangePrice['maxPrice'];
//echo "<b>Цена</b>:<br/>";

                    echo "<div class=\"paramFilter\" style=\"margin-top: 3px;position: relative;\"><div class=\"headerFilter\">Цена<img src=\"/images/filters/silver-str-bottom.png\" style=\"position: absolute; right: 10px; top: 10px;\" onclick=\"$('#paramsPrice').toggle();if($(this).attr('src')=='/images/filters/silver-str-bottom.png'){ $(this).attr('src','/images/filters/red-str-right.png');}else{ $(this).attr('src','/images/filters/silver-str-bottom.png');}\" alt='silverbutton'></div>";
                    echo '<div id="paramsPrice" class="params">
                            от<input type="text" class="textParam" id="minCostPrice" value="' . ((float) $minPrice) . '" name="Price[from]" style="width: 57px;margin-right: 0px;"/> до<input type="text" class="textParam" id="maxCostPrice" value="' . ((float) $maxPrice) . '" name="Price[to]" style="width: 57px;margin-right: 0px;"/> <span style="font-family: \'PT Sans Narrow\', sans-serif;">&#8381;</span>
<input type="hidden" id="rangeChangePrice" value="0" name="Price[rangeChange]"/>
<div id="sliderPrice" style="width: 185px; margin-left: 5px;margin-bottom: 20px;"></div>
                                <script type="text/javascript">
jQuery("#sliderPrice").slider({
	min: ' . ((float) $minPrice) . ',
        animate: true,
	max: ' . ((float) $maxPrice) . ',
            step: 0.1,
	values: [' . ((float) $minPrice) . ',' . ((float) $maxPrice) . '],
	range: true,
	stop: function(event, ui) {
		jQuery("input#minCostPrice").val(jQuery("#sliderPrice").slider("values",0));
		jQuery("input#maxCostPrice").val(jQuery("#sliderPrice").slider("values",1));
                                        if(parseFloat(jQuery("input#minCostPrice").val())==parseFloat(' . ((float) $minPrice) . ') && parseFloat(jQuery("input#maxCostPrice").val())==parseFloat(' . ((float) $maxPrice) . ')){
                        jQuery("input#rangeChangePrice").val(0);
}else{
  jQuery("input#rangeChangePrice").val(1);
}
 jQuery("#categoryFilters").submit();
    },
    slide: function(event, ui){
		jQuery("input#minCostPrice").val(jQuery("#sliderPrice").slider("values",0));
		jQuery("input#maxCostPrice").val(jQuery("#sliderPrice").slider("values",1));
                    if(parseFloat(jQuery("input#minCostPrice").val())==parseFloat(' . ((float) $minPrice) . ') && parseFloat(jQuery("input#maxCostPrice").val())==parseFloat(' . ((float) $maxPrice) . ')){
                        jQuery("input#rangeChangePrice").val(0);
}else{
  jQuery("input#rangeChangePrice").val(1);
}

    }

});
jQuery("input#minCostPrice").change(function(){
	var value1=jQuery("input#minCostPrice").val();
	var value2=jQuery("input#maxCostPrice").val();

    if(parseFloat(value1) > parseFloat(value2)){
		value1 = value2;
		jQuery("input#minCostPrice").val(value1);
	}
    if(parseFloat(value1) < parseFloat(' . ((float) $minPrice) . ')){
		value1 = ' . ((float) $minPrice) . ';
		jQuery("input#minCostPrice").val(value1);
	}
	jQuery("#sliderPrice").slider("values",0,value1);	
             if(parseFloat(jQuery("input#minCostPrice").val())==parseFloat(' . ((float) $minPrice) . ') && parseFloat(jQuery("input#maxCostPrice").val())==parseFloat(' . ((float) $maxPrice) . ')){
                        jQuery("input#rangeChangePrice").val(0);
}else{
  jQuery("input#rangeChangePrice").val(1);
}
 jQuery("#categoryFilters").submit();
});

	
jQuery("input#maxCostPrice").change(function(){
	var value1=jQuery("input#minCostPrice").val();
	var value2=jQuery("input#maxCostPrice").val();
	
	if (parseFloat(value2) > parseFloat(' . ((float) $maxPrice) . ')) { value2 = ' . ((float) $maxPrice) . '; jQuery("input#maxCostPrice").val(' . ((float) $maxPrice) . ')}

	if(parseFloat(value1) > parseFloat(value2)){
		value2 = value1;
		jQuery("input#maxCostPrice").val(value2);
	}
	jQuery("#sliderPrice").slider("values",1,value2);
             if(parseFloat(jQuery("input#minCostPrice").val())==parseFloat(' . ((float) $minPrice) . ') && parseFloat(jQuery("input#maxCostPrice").val())==parseFloat(' . ((float) $maxPrice) . ')){
                        jQuery("input#rangeChangePrice").val(0);
}else{
  jQuery("input#rangeChangePrice").val(1);
}
 jQuery("#categoryFilters").submit();
});

jQuery("input#minCostPrice").keypress(function(event){
		var key, keyChar;
		if(!event) var event = window.event;
		
		if (event.keyCode) key = event.keyCode;
		else if(event.which) key = event.which;
	
		if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
		keyChar=String.fromCharCode(key);
		
		if(!/\d/.test(keyChar))	return false;
	
	});

jQuery("input#maxCostPrice").keypress(function(event){
		var key, keyChar;
		if(!event) var event = window.event;
		
		if (event.keyCode) key = event.keyCode;
		else if(event.which) key = event.which;
	
		if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
		keyChar=String.fromCharCode(key);
		
		if(!/\d/.test(keyChar))	return false;
	
	});

</script></div></div>
';
                    foreach ($filters as $diId => $filter) {
                        $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($diId);
                        echo "<div class=\"paramFilter\"  style=\"position: relative;\"><div class=\"headerFilter\">" . $dopInfoCategory . "<span id=\"clearparams" . $diId . "\" class=\"clearparams\" style=\"display: none;   top: 7px;color: #C3060E; position: absolute; right: 30px; font-size: 10px; cursor: pointer; \" onClick=\"clearParams" . $diId . "()\"><u>Сбросить</u></span> <img src=\"/images/filters/silver-str-bottom.png\" alt=\"Сбросить\" style=\"position: absolute; right: 10px; top: 10px;\" onclick=\"$('#paramsForHide" . $diId . "').toggle();if($(this).attr('src')=='/images/filters/silver-str-bottom.png'){ $(this).attr('src','/images/filters/red-str-right.png');}else{ $(this).attr('src','/images/filters/silver-str-bottom.png');}\"></div><div  id=\"paramsForHide" . $diId . "\">";
                        if (is_array($filter))
                            natsort($filter);
                        if ((float) str_replace(",", ".", $filter[0]) > 0 and (float) end($filter) > 0) {
                            $i = 0;
                            $filterNew = array();
                            foreach ($filter as $filtersNew) {
                                if (substr_count($filtersNew, 'x') == 0 and substr_count($filtersNew, 'х') == 0) {
                                    $filterNew[$i] = $filtersNew;
                                    $i++;
                                }
                            }
                            $filter = $filterNew;
                        }
                        /*   if ($dopInfoCategory->getName() == "Цвет") {
                          foreach ($filter as $filterParam) {
                          echo '<input type="checkbox" name="' . $diId . '[]" value="' . $filterParam . '" class="filteParams"> - ' . $filterParam . '<br />';

                          }
                          } else if ($dopInfoCategory->getName() == "Длина") { */
                        if ((float) str_replace(",", ".", $filter[0]) > 0 and (float) end($filter) > 0 and $dopInfoCategory->getName() != "Размер") {


                            echo '<div id="params' . $diId . '" class="params">
                <script>
                 //
                 // Функция кнопки сбросить
                 //
             function clearParams' . $diId . '(){
jQuery("#slider' . $diId . '").slider({
	min: ' . ((float) str_replace(",", ".", $filter[0])) . ',
        animate: true,
	max: ' . ((float) str_replace(",", ".", end($filter))) . ',
            step: 0.1,
	values: [' . ((float) str_replace(",", ".", $filter[0])) . ',' . ((float) str_replace(",", ".", end($filter))) . '],
	range: true,
	stop: function(event, ui) {
		jQuery("input#minCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",0));
		jQuery("input#maxCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",1));
                                        if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
      
}
 jQuery("#categoryFilters").submit();
    },
    slide: function(event, ui){
		jQuery("input#minCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",0));
		jQuery("input#maxCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",1));
                    if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
}

    }

});
		jQuery("input#minCost' . $diId . '").val(' . ((float) str_replace(",", ".", $filter[0])) . ');
		jQuery("input#maxCost' . $diId . '").val(' . ((float) str_replace(",", ".", end($filter))) . ');
$("#clearparams' . $diId . '").fadeOut();
  jQuery("input#rangeChange' . $diId . '").val(0);


 jQuery("#categoryFilters").submit();
}

</script>
                            от<input type="text" class="textParam" id="minCost' . $diId . '" value="' . ((float) str_replace(",", ".", $filter[0])) . '" name="' . $diId . '[from]" style="width: 57px;margin-right: 0px;"/> до<input type="text" class="textParam" id="maxCost' . $diId . '" value="' . ((float) str_replace(",", ".", end($filter))) . '" name="' . $diId . '[to]" style="width: 57px;margin-right: 0px;"/>' . (preg_replace("/[^a-zA-ZА-Яа-я\s]/u", "", $filter[0])) . '
<input type="hidden" id="rangeChange' . $diId . '" value="0" name="' . $diId . '[rangeChange]"/>
<div id="slider' . $diId . '" style="width: 185px; margin-left: 5px;margin-bottom: 20px;"></div>
                                <script type="text/javascript">
                                

                 //
                 // Создание слайдера
                 //
jQuery("#slider' . $diId . '").slider({
	min: ' . ((float) str_replace(",", ".", $filter[0])) . ',
        animate: true,
	max: ' . ((float) str_replace(",", ".", end($filter))) . ',
            step: 0.1,
	values: [' . ((float) str_replace(",", ".", $filter[0])) . ',' . ((float) str_replace(",", ".", end($filter))) . '],
	range: true,
	stop: function(event, ui) {
		jQuery("input#minCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",0));
		jQuery("input#maxCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",1));
                                        if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
      
}
 jQuery("#categoryFilters").submit();
    },
    slide: function(event, ui){
		jQuery("input#minCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",0));
		jQuery("input#maxCost' . $diId . '").val(jQuery("#slider' . $diId . '").slider("values",1));
                    if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
}

    }

});


                 //
                 // Изменение в форме
                 //
jQuery("input#minCost' . $diId . '").change(function(){
	var value1=jQuery("input#minCost' . $diId . '").val();
	var value2=jQuery("input#maxCost' . $diId . '").val();

    if(parseFloat(value1) > parseFloat(value2)){
		value1 = value2;
		jQuery("input#minCost' . $diId . '").val(value1);
	}
    if(parseFloat(value1) < parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ')){
		value1 = ' . ((float) str_replace(",", ".", $filter[0])) . ';
		jQuery("input#minCost' . $diId . '").val(value1);
	}
	jQuery("#slider' . $diId . '").slider("values",0,value1);	
             if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
}
 jQuery("#categoryFilters").submit();
});

	
jQuery("input#maxCost' . $diId . '").change(function(){
	var value1=jQuery("input#minCost' . $diId . '").val();
	var value2=jQuery("input#maxCost' . $diId . '").val();
	
	if (parseFloat(value2) > parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')) { value2 = ' . ((float) str_replace(",", ".", end($filter))) . '; jQuery("input#maxCost' . $diId . '").val(' . ((float) str_replace(",", ".", end($filter))) . ')}

	if(parseFloat(value1) > parseFloat(value2)){
		value2 = value1;
		jQuery("input#maxCost' . $diId . '").val(value2);
	}
	jQuery("#slider' . $diId . '").slider("values",1,value2);
             if(parseFloat(jQuery("input#minCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", $filter[0])) . ') && parseFloat(jQuery("input#maxCost' . $diId . '").val())==parseFloat(' . ((float) str_replace(",", ".", end($filter))) . ')){
                        jQuery("input#rangeChange' . $diId . '").val(0);
  $("#clearparams' . $diId . '").fadeOut();
}else{
  jQuery("input#rangeChange' . $diId . '").val(1);
  $("#clearparams' . $diId . '").fadeIn();
}
 jQuery("#categoryFilters").submit();
});


                 //
                 // Проверка формы
                 //
jQuery("input#minCost' . $diId . '").keypress(function(event){
		var key, keyChar;
		if(!event) var event = window.event;
		
		if (event.keyCode) key = event.keyCode;
		else if(event.which) key = event.which;
	
		if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
		keyChar=String.fromCharCode(key);
		
		if(!/\d/.test(keyChar))	return false;
	
	});

jQuery("input#maxCost' . $diId . '").keypress(function(event){
		var key, keyChar;
		if(!event) var event = window.event;
		
		if (event.keyCode) key = event.keyCode;
		else if(event.which) key = event.which;
	
		if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
		keyChar=String.fromCharCode(key);
		
		if(!/\d/.test(keyChar))	return false;
	
	});

</script>
';
                        } else {
                            echo "
             <script>
             function issetParams" . $diId . "(){
                 var checkedParams=false;
$('div#params" . $diId . " div input').each(function(i,elem) {
if($(elem).parent('div').children('input').prop(\"checked\")){
checkedParams=true;
$(elem).parent('div').css('color', '#C3060E');
}else{
$(elem).parent('div').css('color', '#424141');
}
}); 
if(checkedParams){
$('#clearparams" . $diId . "').fadeIn();
    }else{
$('#clearparams" . $diId . "').fadeOut();
    }
    
                 }
                 
             function clearParams" . $diId . "(){
$('div#params" . $diId . " div input').each(function(i,elem) {
    $(elem).parent('div').children('input').removeAttr(\"checked\");
$(elem).parent('div').css('color', '#424141');
$(elem).parent('div').children('img').removeClass('colorFilterButton-select').addClass('colorFilterButton');
}); 
$('#clearparams" . $diId . "').fadeOut();
 jQuery('#categoryFilters').submit();
    
                 }
             </script>";


                            if ($dopInfoCategory->getName() != "Цвет") {
                                echo "<input type=\"text\" id=\"textParam" . $diId . "\" class=\"textParam\" onkeyup=\"

$('div#params" . $diId . " > div').each(function(i,elem) {
     if ($(elem).text().toLowerCase().replace('ё', 'е').search($('#textParam" . $diId . "').val().toLowerCase().replace('ё', 'е'))!=-1) { 

$(elem).css('display', 'block')	;
 } else { 	
$(elem).css('display', 'none')	;
 }
 

});                            
                                \">";
                            }
                            echo "<div id=\"params" . $diId . "\" class=\"params\" " . ($dopInfoCategory->getName() == "Цвет" ? 'style="padding: 3px;"' : '') . ">";
                            if (is_array($filter))
                                foreach ($filter as $filterParam) {
                                    if ($dopInfoCategory->getName() == "Цвет") {
                                        $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($diId);
                                        $dopInfoCategoryFull = DopInfoCategoryFullTable::getInstance()->createQuery()->where("name=?", $filterParam)->addWhere("id in (" . implode(',', $dopInfoCategory->getCategory()->getPrimaryKeys()) . ")")->fetchOne();

                                        if ($dopInfoCategoryFull)
                                            echo '<div style="display: inline-block;margin: 5px;"><input type="checkbox" name="' . $diId . '[]" value="' . $filterParam . '" class="filteParams" onChange="jQuery(\'#categoryFilters\').submit();issetParams' . $diId . '();" style="opacity:0; position:absolute; left:9999px;"' . /* id="' . $diId . $filterParam . '" */'><img src="/uploads/dopinfo/thumbnails/' . ($dopInfoCategoryFull->getFilename()) . '" alt="' . $filterParam . '" title="' . $filterParam . '" class="colorFilterButton" width="28" onClick=" if ( $(this).hasClass(\'colorFilterButton\') ) {$(this).removeClass(\'colorFilterButton\').addClass(\'colorFilterButton-select\');$(this).prev(\'input\').attr(\'checked\', \'checked\');}else{$(this).removeClass(\'colorFilterButton-select\').addClass(\'colorFilterButton\');$(this).prev(\'input\').removeAttr(\'checked\');} jQuery(\'#categoryFilters\').submit();issetParams' . $diId . '();" style="cursor: pointer;"></div>';
                                    } else {
                                        echo '<div><input type="checkbox" name="' . $diId . '[]" value="' . $filterParam . '" class="filteParams" onChange="jQuery(\'#categoryFilters\').submit();issetParams' . $diId . '();"><div style="display: inline;"> - ' . $filterParam . '</div></div>';
                                    }
                                }
                        }
                        /*  }  else {
                          foreach ($filter as $filterParam) {

                          echo '<input type="checkbox" name="' . $diId . '[]" value="' . $filterParam . '" class="filteParams"> - ' . $filterParam . '<br />';
                          }
                          } */
                        echo "</div></div></div>";
                    }
//print_r($filters);
//echo phpinfo();
// include_component('category', 'catalogDev', array('slug' => $sf_request->getParameter('slug'), 'slot' => get_slot('category_slug')));
                    ?></form>
            </div>
        <? } ?>

