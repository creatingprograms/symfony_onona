
<td class="sf_admin_text sf_admin_list_td_id">
    <?php echo $category->getId() ?>
</td><td class="sf_admin_text sf_admin_list_td_name">
    <?php echo ($category->getParentsId() != "") ? ' -- ' : '' ?><a href="/backend.php/product/setFilterTag/<?php echo $category->getId() ?>"><?php echo $category->getName() ?></a>
</td>
<td class="sf_admin_text sf_admin_list_td_slug">
    <?php echo $category->getSlug() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_slug">
    <?php
    if (!sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")):

/*        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        if (count($category->getCategoryProducts()->getPrimaryKeys()) > 0) {
            $countProductsIsset = $q->execute("SELECT COUNT( * ) AS count "
                            . "FROM product WHERE  `count` >0 
                                        AND  `is_public` =1 
                                        AND  `moder` =0 and id in(" . implode(",", $category->getCategoryProducts()->getPrimaryKeys()) . ")")
                    ->fetch(Doctrine_Core::FETCH_COLUMN);
        } else {
            $countProductsIsset = 0;
        }
        echo $countProductsIsset . " / " . $category->getCategoryProducts()->count();*/
        echo $stats[$category->getId()][0] . " / " . $stats[$category->getId()][1];

    endif;
    ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_public">
    <?php echo get_partial('category/list_field_boolean', array('value' => $category->getIsPublic())) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_adult_<?php echo $category->getId() ?>">
    <?php
    if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")):
        ?>
        <?php echo get_partial('category/list_field_boolean', array('value' => $category->getAdult())) ?>
        <?php
    else:
        ?>
        <a href="#" onClick="changeAdult(<?php echo $category->getId() ?>); return false;"><?php echo get_partial('category/list_field_boolean', array('value' => $category->getAdult())) ?></a>
    <?php
    endif;
    ?>
</td>
