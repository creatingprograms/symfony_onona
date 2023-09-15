<?php
JSInPages("
    function changeProduct(slug) {
        document.location.href = slug;
    }");
?>
<div class="item-char"><fieldset>

        <?php
        $prevName = "";
        $tableSize = false;
        foreach ($params as $key => $property):

            if ($property['name'] == "Таблица размеров" and $property['value'] == 1):
                $tableSize = true;
            else:
                if ($prevName != $property['name']) {
                    if ($key != 0)
                        echo "</dd></dl>";
                    ?>
                    <dl>
                        <dt>
                            <input type="hidden" name="productOptions[]" value="<?= $property['dopinfo_id'] ?>" />
                            <?= $property['name'] ?>:</dt>
                        <dd><?php
                        }


                        if ($property['count_params'] == $countProductsParams):

                            if ($property['name'] == "Производитель") {
                                if ($property['man_slug']) {
                                    echo "<a href=\"/manufacturer/" . $property['man_slug'] . "\"><u>" . $property['value'] . "</u></a>";
                                } else {
                                    echo "<a href=\"/manufacturer/" . $property['dopinfo_id'] . "\"><u>" . $property['value'] . "</u></a>";
                                }
                            } elseif ($property['name'] == "Коллекция") {
                                if ($property['col_slug']) {
                                    echo "<a href=\"/collection/" . $property['col_slug'] . "\"><u>" . $property['value'] . "</u></a>";
                                } else {
                                    echo "<a href=\"/collection/" . $property['dopinfo_id'] . "\"><u>" . $property['value'] . "</u></a>";
                                }
                            } else {
                                echo "<div>" . $property['value'] . "</div>";
                            }
                            ?>
                            <?php
                        else:
                            ?>
                            <div class="paramsBottom<? if ($property['prod_id'] == $product['id']) echo " activeParamsBottom"; ?><? if ($property['prod_count'] == 0) echo " nonCountParamsBottom"; ?>" onClick="changeProduct('<?php echo url_for('@product_show?slug=' . $property['prod_slug']) ?>');"><?= $property['value'] ?></div>
                        <?php
                        endif;


                        $prevName = $property['name'];
                    endif;
                endforeach;
                ?>
            </dd></dl>
    </fieldset></div>

<?php if ($tableSize) { ?><dl>
        <a href="<?php echo url_for('@page?slug=size-table') ?>" target="_blank" class="pinkButton">Таблица размеров</a><? } ?>