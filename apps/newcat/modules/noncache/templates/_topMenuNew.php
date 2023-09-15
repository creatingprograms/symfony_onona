<?php
  function isNoIndex( $link ){
    if( mb_stristr ($link, 'http') === false) return false;
    else return true;
  }
?>
<div class="nav-holder">
    <div class="nav-deco"></div>
    <div class="nav-frame">
        <form action="/search" class="search">
            <fieldset>
                <input id="period" class="js-search" type="text" value="Поиск" name="searchString" autocomplete="off" onclick="if ($(this).val() == 'Поиск') {
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
                    $result = $q->execute("SELECT catalog.id AS catid, catalog.name AS catname, catalog.img AS catimg, catalog.description AS catdescription, catalog.slug AS catslug, category . *".
                    " FROM  `catalog`".
                    " LEFT JOIN category_catalog ON category_catalog.catalog_id = catalog.id".
                    " LEFT JOIN category ON category_catalog.category_id = category.id".
                    " WHERE catalog.is_public =  '1'".
                    " AND category.is_public =  '1'".
                    " ORDER BY catalog.position ASC , category.position ASC ");

                    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
                    $categorys = $result->fetchAll();

                    $categoryArray = array();
                    $CatalogWork = "";
                    $CategoryWork = "";
                    $parentIdRam = "";
                    $catalogNum = 0;
                    ?>

                    <li id="uit-catalog">
                      <a
                        <? if ($link->getUrl() != "") { ?>
                           href="<?= url_for($link->getUrl() == "/" ? url_for('@homepage') : trim($link->getUrl(), "/") . '/') ?>"
                        <? } else { ?>
                          class="cur-shops"
                        <? } ?>
                        <?= ($link->getTargetBlank()) ? ' target="_blank"' : '' ?>><span><?= $link->getText() ?></span>
                      </a>
                        <span class="cur"></span>
                        <div class="drop products">
                            <table>
                                <tr><? foreach ($categorys as $keyCategory => $category) :
                                        /* $categoryArray[$category['catid']]['catdescription']=$category['catdescription'];
                                          $categoryArray[$category['catid']]['catimg']=$category['catimg'];
                                          $categoryArray[$category['catid']]['category'][$category['id']]= */
                                        if ($CatalogWork != $category['catname']) :
                                            $CatalogWork = $category['catname'];
                                            $catalogNum = $catalogNum + 1;
                                            ?><td ><ul class="cat-menu">
                                                    <li style="padding-bottom: 0px; margin-bottom: 0px;">
                                                        <a id="catalog_top_<?= $category['catid'] ?>" href="<?= '/catalog/' . mb_strtolower($category['catslug'], 'utf-8') ?>"
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
                                                <ul class="cat-menuTop" style="padding-top: 0px;">
                                                  <li<?
                                                  if ($category['catslug'] == $sf_request->getParameter('slug') and sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show_newprod") {
                                                      echo "  class=\"active\"";
                                                  }
                                                  ?>><a href="<?= '/catalog/' . mb_strtolower($category['catslug'], 'utf-8') . "/newprod" ?>" class="newrel">Новые товары</a></li>
                                                  <li<?
                                                  if ($category['catslug'] == $sf_request->getParameter('slug') and sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show_relatecategory") {
                                                      echo "  class=\"active\"";
                                                  }
                                                  ?>><a href="<?= '/catalog/' . mb_strtolower($category['catslug'], 'utf-8') . "/relatecategory" ?>" class="newrel">Лидеры продаж</a></li>
                                                <?
                                                endif;
                                                ?>
                                                <li>
                                                    <a href="<?= '/category/' . mb_strtolower($category['slug'], 'utf-8') ?>"><?= $category['name'] ?></a></li>

                                                <?
                                                if (@$CatalogWork != @$categorys[$keyCategory + 1]['catname']) :
                                                    /*?><li<?
                                                    if ($category['catslug'] == $sf_request->getParameter('slug') and sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show_newprod") {
                                                        echo "  class=\"active\"";
                                                    }
                                                    ?>><a href="<?= '/catalog/' . $category['catslug'] . "/newprod" ?>" class="newrel">Новые товары</a></li>
                                                    <li<?
                                                    if ($category['catslug'] == $sf_request->getParameter('slug') and sfContext::getInstance()->getRouting()->getCurrentRouteName() == "catalog_show_relatecategory") {
                                                        echo "  class=\"active\"";
                                                    }
                                                    ?>><a href="<?= '/catalog/' . $category['catslug'] . "/relatecategory" ?>" class="newrel">Лидеры продаж</a></li>
                                                        <?*/
                                                        echo "</ul></td>";
                                                        if ($catalogNum == 4)
                                                            echo "</tr><tr>";
                                                    endif;
                                                endforeach;
                                                ?>
                                            <td class="menu-banner"><?php
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
                }
                else {
                    ?>
                    <li>
                      <?php
                        $subMenu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'Под шапкой(новый дизайн)'")->addWhere("parents_id = '" . $link->getId() . "'")->execute();
                        $isNoIndex= isNoIndex($link->getUrl());
                      ?>
                      <?= $isNoIndex ? '<noindex>' : ''?>
                      <a
                        <?= $isNoIndex ? 'rel="nofollow"' : ''?>
                        <? if ($link->getUrl() != "") { ?> href="<?= mb_strtolower($link->getUrl(), 'utf-8') ?>"<?
                            if ($link->getUrl() == '/category/skidki_do_60_percent') echo ' class="js-skidki"';
                          }
                          else {
                            if($subMenu->count()){ echo ' class="cur-shops"'; }
                          } ?><?= ($link->getTargetBlank()) ? ' target="_blank"' : '' ?>><span><?= $link->getText() ?></span>
                      </a>
                      <?= $isNoIndex ? '</noindex>' : ''?>
                      <?php
                        if ($subMenu->count() > 0) {
                          ?>
                            <span class="cur"></span>
                            <div class="drop">
                              <ul>
                                  <?php
                                  foreach ($subMenu as $subLink):
                                    $isNoIndexSub= isNoIndex($subLink->getUrl());
                                  ?>
                                    <li>
                                      <?= $isNoIndexSub ? '<noindex>' : ''?>
                                      <a <?= $isNoIndexSub ? 'rel="nofollow"' : ''?> href="<?= mb_strtolower($subLink->getUrl(), 'utf-8') ?>"<?= ($subLink->getTargetBlank()) ? ' target="_blank"' : '' ?>><?= $subLink->getText() ?></a>
                                      <?= $isNoIndexSub ? '</noindex>' : ''?>
                                    </li>
                                  <? endforeach; ?>
                              </ul>
                          </div><?
                        }
                        ?>
                <?php }
                if ($link->getId() == 27) : ?>
                  <li class="mobile-only">
                    <a href="/kak-sdelat-zakaz/">
                      <span>Как сделать заказ</span>
                    </a>
                  </li>
                  <li class="mobile-only">
                    <a href="/oplata/">
                      <span>Оплата</span>
                    </a>
                  </li>
                  <li class="mobile-only">
                    <a href="/dostavka/">
                      <span>Доставка</span>
                    </a>
                  </li>
              <? endif;
            endforeach; ?>
          <li class="mobile-only menu-cart">
            <a href="/cart/">
              <span class="my-cart">Моя корзина</span>
            </a>
          </li>
        </ul>
    </div>
</div>
