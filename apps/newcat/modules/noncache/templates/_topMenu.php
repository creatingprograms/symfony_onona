<div class="nav-holder">
    <div class="nav-deco"></div>
    <div class="nav-frame">
        <form action="/search" class="search">
            <fieldset>
                <input id="period" type="text" value="Поиск" name="searchString" onclick="if ($(this).val() == 'Поиск') {
                            $(this).val('');
                        }
                        $(this).css('color', '#000');" />
                <input class="srch-btn" type="submit" value="" />
            </fieldset>
        </form>
        <ul id="nav">
            <?php
            $menu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'Под шапкой(новый дизайн)'")->addWhere("parents_id is NULL")->execute();
            foreach ($menu as $link):
                if ($link->getId() == 32) {
                    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                    $result = $q->execute("SELECT catalog.id AS catid, catalog.name AS catname, catalog.img AS catimg, catalog.description AS catdescription, catalog.slug AS catslug, category . * 
FROM  `catalog` 
LEFT JOIN category_catalog ON category_catalog.catalog_id = catalog.id
LEFT JOIN category ON category_catalog.category_id = category.id
WHERE catalog.is_public =  '1'
AND category.is_public =  '1'
ORDER BY catalog.position ASC , category.position ASC ");

                    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
                    $categorys = $result->fetchAll();

                    $categoryArray = array();
                    $CatalogWork = "";
                    $CategoryWork = "";
                    $parentIdRam = "";
                    $catalogNum = 0;
                    ?>

                    <li><a <? if ($link->getUrl() != "") { ?> href="<?= url_for($link->getUrl() == "/" ? url_for('@homepage') : trim($link->getUrl(), "/") . '/') ?>"<? } ?><?= ($link->getTargetBlank()) ? ' target="_blank"' : '' ?>><span><?= $link->getText() ?></span></a>
                        <div class="drop" style="width: 990px;">
                            <span class="cur"></span>
                            <table style="width: 100%;">
                                <tr><?                                     foreach ($categorys as $keyCategory => $category) :
                                        /* $categoryArray[$category['catid']]['catdescription']=$category['catdescription'];
                                          $categoryArray[$category['catid']]['catimg']=$category['catimg'];
                                          $categoryArray[$category['catid']]['category'][$category['id']]= */
                                        if ($CatalogWork != $category['catname']) :
                                            $CatalogWork = $category['catname'];
                                            $catalogNum = $catalogNum + 1;
                                            ?><td style="width:25%;"><ul class="cat-menu">
                                                    <li style="padding-bottom: 0px; margin-bottom: 0px;">
                                                        <a id="catalog_top_<?= $category['catid'] ?>" href="<?= '/catalog/' . $category['catslug'] ?>"
                                                           onClick="location.replace('<?= '/catalog/' . $category['catslug'] ?>')">
                                                            <span class="img-holder">
                                                                <img width="90" height="118" alt="image description" src="/uploads/photo/<?= $category['catimg'] ?>">
                                                            </span> 
                                                            <span class="text" style="  margin-left: -5px;">
                                                                <strong><?= $category['catname'] ?></strong>
                                                                <em><?= $category['catdescription'] ?></em>
                                                            </span>
                                                            <span class="arrow"></span>
                                                            <span class="deco-left"></span>
                                                            <span class="deco-right"></span>
                                                        </a> 
                                                    </li></ul>
                                                <ul class="cat-menuTop" style="padding-top: 0px;"><?
                                                endif;
                                                ?>
                                                <li>
                                                    <a href="<?= '/category/' . $category['slug'] ?>"><?= $category['name'] ?></a></li>

                                                <?
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
                                                        echo "</ul></td>";
                                                        if ($catalogNum == 4)
                                                            echo "</tr><tr>";
                                                    endif;
                                                endforeach;
                                                ?>
                                            <td style="  padding-top: 25px;"><?php
                                                $banner = BannersTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("rand()")->limit(1)->fetchOne();

                                                $target = ((substr_count($banner->getHref(), "http://") > 0) ? "target=\"_blank\"" : "");
                                                echo "<a href=\"" . $banner->getHref() . "\" " . $target . " style=\"border: 0px;\"><img src=\"/uploads/banners/" . $banner->getSrc() . "\" width=\"188\" alt=\"" . $banner->getAlt() . "\" /></a>";
                                                ;
                                                ?></td>
                                </tr>
                            </table>
                        </div>
                    </li>    
                    <?
                } else {
                    ?>
                    <li><a <? if ($link->getUrl() != "") { ?> href="<?= $link->getUrl() ?>"<? } ?><?= ($link->getTargetBlank()) ? ' target="_blank"' : '' ?>><span><?= $link->getText() ?></span></a>
                        <?php
                        $subMenu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'Под шапкой(новый дизайн)'")->addWhere("parents_id = '" . $link->getId() . "'")->execute();
                        if ($subMenu->count() > 0) {
                            ?><div class="drop">
                                <span class="cur"></span>
                                <ul>
                                    <?php
                                    foreach ($subMenu as $subLink):
                                        ?>
                                        <li><a href="<?= $subLink->getUrl() ?>"<?= ($subLink->getTargetBlank()) ? ' target="_blank"' : '' ?>><?= $subLink->getText() ?></a></li>
                                    <? endforeach; ?>
                                </ul>
                            </div><?
                        }
                        ?>
                        <?php
                    }
                endforeach;
                ?>

        </ul>
    </div>
</div>