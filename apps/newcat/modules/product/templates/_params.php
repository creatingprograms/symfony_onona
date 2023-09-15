<? /*
 * SELECT dic.name as name, di.value as value, p.slug as prod_slug, p.id as prod_id, dic.position as sort, dic.id as category_id, di.id as dopinfo_id, count(dic.id) as count_params FROM `dop_info` as di left join dop_info_category as dic on dic.id=di.dicategory_id left join dop_info_product as dip on dip.dop_info_id=di.id left join product as p on p.id=dip.product_id where dip.product_id in (12382,12383) group by di.id ORDER BY `dic`.`position` ASC
 */
?>
<script>
    function changeProduct(slug) {
        document.location.href = "/product/" + slug;
    }
</script>
<dl>
    <dt></dt>
    <dd>
        <?php
        if ($product->getParent() != "")
            $productProp = $product->getParent();
        else
            $productProp = $product;

        $childrens = $productProp->getChildren()->getPrimaryKeys();
        $childrens[] = $productProp->getId();

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute("SELECT dic.name as name, "
                . "di.value as value, "
                . "p.slug as prod_slug, "
                . "p.id as prod_id, "
                . "p.count as prod_count, "
                . "dic.position as sort, "
                . "dic.id as category_id, "
                . "di.id as dopinfo_id, "
                . "count(dic.id) as count_params "
                . "FROM `dop_info` as di "
                . "left join dop_info_category as dic on dic.id = di.dicategory_id "
                . "left join dop_info_product as dip on dip.dop_info_id=di.id "
                . "left join product as p on p.id=dip.product_id "
                . "where dip.product_id in (" . implode(",", $childrens) . ") AND dic.is_public =1  "
                . "group by di.id "
                . "ORDER BY `dic`.`position` ASC, `di`.`position` ASC");
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $params = $result->fetchAll();
        $product_property='';
        foreach ($params as $key => $property):

            if ($property['name'] == "Таблица размеров" and $property['value'] == 1):
                $tableSize = true;
            else:
                if ($prevName != $property['name']) {
                    ?></dd></dl>
            <dl>
                <dt>
                <input type="hidden" name="productOptions[]" value="<?= $property['dopinfo_id'] ?>" />
                <?= $property['name'] ?>:</dt>
                <dd><?php
                }


                if ($property['count_params'] == count($childrens)):

                    if ($property['name'] == "Производитель") {
                        $manufacturer = ManufacturerTable::getInstance()->findOneBySubid($property['dopinfo_id']);
                        slot('product_manufacturer', $property['value']);
                        if ($manufacturer) {
                            if ($manufacturer->getIsPublic())
                                echo "<a href=\"/manufacturer/" . $manufacturer->getSlug() . "\"><u class='manufacturer-name'>" . $property['value'] . "</u></a>";
                            else
                                echo "<span class='manufacturer-name'>" . $property['value'] . "</span>";
                        } else {
                            echo "<span class='manufacturer-name'>" . $property['value'] . "</span>";
                            // echo "<a href=\"/manufacturer/" . $property['dopinfo_id'] . "\"><u class='manufacturer-name'>" . $property['value'] . "</u></a>";
                        }
                    } elseif ($property['name'] == "Коллекция") {
                        $collection = CollectionTable::getInstance()->findOneBySubid($property['dopinfo_id']);
                        if ($collection) {
                            echo "<a href=\"/collection/" . $collection->getSlug() . "\"><u>" . $property['value'] . "</u></a>";
                        } else {
                            echo "<a href=\"/collection/" . $property['dopinfo_id'] . "\"><u>" . $property['value'] . "</u></a>";
                        }
                    } else {
                        if ($property['name'] == 'Цвет') slot('product_color',  ' '.$property['value']);
                        if ($property['name'] == 'Материал') slot('product_material',  ' '.$property['value']);
                        if ($property['name'] == 'Свойство') $product_property.=' '.$property['value'];
                        echo "<div>" . $property['value'] . "</div>";
                    }
                    ?>
                    <?php
                else:
                    ?>
                    <div class="paramsBottom<? if ($property['prod_id'] == $product->getId()) echo " activeParamsBottom"; ?><? if ($property['prod_count'] == 0) echo " nonCountParamsBottom"; ?>" onClick="<? If ($productShow) { ?>changeProduct('<?= $property['prod_slug'] ?>')<? } else {
                        ?>loadPreShowLeft(<?= $property['prod_id'] ?>, '<?= implode(",", $productsKeys) ?>', <?= $product->getParentsId() > 0 ? $product->getParentsId() : 0 ?>);<? } ?>"><?= $property['value'] ?></div>
                     <?php
                     endif;


                     $prevName = $property['name'];
                 endif;
             endforeach;
             ?>
    </dd></dl>
<?php if ($tableSize) { ?><dl>
        <dt><a href="/size-table" target="_blank" style="color: #954649">Таблица размеров</a></dt>
        <dd></dd>
    </dl><? } ?>
    <?php
      slot('product_property',  $product_property);
    ?>
