<?
  if (
    (
      sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_show" or
      // sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_show" or
      sfContext::getInstance()->getRouting()->getCurrentRouteName() == "category_show_page"
    )
    and $sf_request->getParameter('slug') != "skidki-do-99"
    and $sf_request->getParameter('slug') != "Luchshaya_tsena"
    and $sf_request->getParameter('slug') != "vesennee-specpredlozhenie"
    and $sf_request->getParameter('slug') != "podarki"
    and $sf_request->getParameter('slug') != "upravlyai-cenoi"
  ){ ?>

    <?/*
    <div class="category-filter" id="filterSlider" onClick="changeFilterTab();">
      <div class="filter-element filter-element--menu<?= !count($_GET['filters']) ? ' active' : ''?>">Меню</div>
      <div class="filter-element filter-element--filter<?= count($_GET['filters']) ? ' active' : ''?>">Фильтры</div>*
    </div>
    */?>
<? } ?>
<ul class="cat-menu" <? If (count($_GET['filters']) > 0) { ?> style="display:none;"<?php } ?>><?
    slot('category_slug', $slot);
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
        if ($CatalogWork != $category['catname']) :
            $CatalogWork = $category['catname'];
            ?>
            <li<? if ($category['catslug'] == $selectCategory[0]['catslug']) echo ' class="active"'; ?>><a id="catalog_<?= $category['catid'] ?>" href="<?= '/catalog/' . mb_strtolower($category['catslug'], 'utf-8') ?>"
                                                                                                           onClick="location.replace('<?= '/catalog/' . $category['catslug'] ?>')">

                    <span class="arrow"></span>
                    <span class="text">
                        <span><?= $category['catname'] ?></span>
                        <span class="more_text"><?= $category['catdescription'] ?></span>
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

                        <a href="<?= '/category/' . mb_strtolower($category['slug'], 'utf-8') ?>"><?= $category['name'] ?></a>
                    <? endif; ?>
                    <?
                    if ($parentIdRam != $category['c__parents_id'] and $category['c__parents_id'] != 0) {
                        $parentIdRam = $category['c__parents_id'];
                        ?><ul<? if ($category['slug'] == $selectCategory[0]['slug'] and sfContext::getInstance()->getRouting()->getCurrentRouteName() != "catalog_show_newprod" and sfContext::getInstance()->getRouting()->getCurrentRouteName() != "catalog_show_relatecategory" and sfContext::getInstance()->getRouting()->getCurrentRouteName() != "catalog_show") echo ' style="display: block;"'; ?>><? } ?>
                        <? If ($category['c__parents_id'] != 0) { ?>

                            <li<? if ($category['c__slug'] == $slugCategory) echo ' class="active"'; ?>><a href="<?= '/category/' . mb_strtolower($category['c__slug'], 'utf-8') ?>"> <?= $category['c__name'] ?></a></li><? } ?>

                        <? if (@$parentIdRam != @$categorys[$keyCategory + 1]['c__parents_id'] and $category['c__parents_id'] != 0) {
                            ?></ul></li><?
                }
                if (@$CatalogWork != @$categorys[$keyCategory + 1]['catname']) :
                    ?><li<?
                            if ($category['catslug'] == $sf_request->getParameter('slug') and sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show_newprod") {
                                echo "  class=\"active\"";
                            }
                            ?>><a href="<?= '/catalog/' . mb_strtolower($category['catslug'], 'utf-8') . "/newprod" ?>" class="newrel">Новые товары</a></li>
                    <li<?
                    if ($category['catslug'] == $sf_request->getParameter('slug') and sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show_relatecategory") {
                        echo "  class=\"active\"";
                    }
                    ?>><a href="<?= '/catalog/' . mb_strtolower($category['catslug'], 'utf-8' ). "/relatecategory" ?>" class="newrel">Лидеры продаж</a></li>
                        <?
                        echo "</ul></li>";
                    endif;


                endforeach;
                ?></ul>
