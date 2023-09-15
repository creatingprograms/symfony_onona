<?/*
 * SELECT dic.name as name, di.value as value, p.slug as prod_slug, p.id as prod_id, dic.position as sort, dic.id as category_id, di.id as dopinfo_id, count(dic.id) as count_params FROM `dop_info` as di left join dop_info_category as dic on dic.id=di.dicategory_id left join dop_info_product as dip on dip.dop_info_id=di.id left join product as p on p.id=dip.product_id where dip.product_id in (12382,12383) group by di.id ORDER BY `dic`.`position` ASC
 */
?>
<script>
    function changeArtCode(slug) {
        document.location.href = "/product/" + slug;
    }
</script>
<?php
$arrayDopInfo = array();
$dopInfos = $product->getDopInfoProducts();
/* foreach ($dopInfos as $info):
  $dopParam = $info->getDopInfoProduct()->getData();
  $arraySubInfo['value'] = $info->getValue();
  $arraySubInfo['code'] = $dopParam[0]->getCode();
  $arraySubInfo['dopInfoID'] = $info->getId();
  $arrayInfo[$info->getName()][] = $arraySubInfo;
  endforeach; */
if ($product->getParent() != "")
    $productProp = $product->getParent();
else
    $productProp = $product;
$i = 0;
$childrens = $productProp->getChildren();
$childrens[] = $productProp;

foreach ($dopInfos as $key => $property):
    $doparray['value'] = $property['value'];
    $doparray['product_id'] = $product->getSlug() != "" ? $product->getSlug() : $product->getId();
    $doparray['product_id_int'] = $product->getId();

    $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($property['dicategory_id']);
    $doparray['sort'] = $dopInfoCategory->getPosition();


    $doparray['dopInfoID'] = $property->getId();
    $arrayDopInfo[$property['dicategory_id']][] = $doparray;
endforeach;
foreach ($dopInfos as $key => $property):
    /* $doparray['value'] = $property['value'];
      $doparray['product_id'] = $product->getSlug() != "" ? $product->getSlug() : $product->getId();

      $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($property['dicategory_id']);
      $doparray['sort'] = $dopInfoCategory->getPosition();


      $doparray['dopInfoID'] = $property->getId();
      $arrayDopInfo[$property['dicategory_id']][] = $doparray; */
    foreach ($childrens as $children) {
        foreach ($children->getDopInfoProducts() as $dopInfoChildren) {
            //echo $dopInfoChildren['value'];
            //echo $dopInfoChildren['dicategory_id'].' '.$property['dicategory_id']." упымап ";

            if ($dopInfoChildren['dicategory_id'] == $property['dicategory_id'] and $children->getId() != $product->getId() and $dopInfoChildren['value'] != $property['value']) {
                /* if (/$dopInfoChildren['dicategory_id'] == 62) {
                  //echo $dopInfoChildren['value'];
                  } */
                $in_array_di = false;

                foreach ($arrayDopInfo[$property['dicategory_id']] as $value) {
                    //print_r($value);
                    if (in_array($dopInfoChildren['value'], $value)) {
                        //print_r($dopInfoChildren['value']);
                        $in_array_di = true;
                    }
                }

                if ($in_array_di === false) {
                    //echo "<pre>".$dopInfoChildren['value'];
                    $doparray['value'] = $dopInfoChildren['value'];
                    $doparray['product_id'] = $children->getSlug() != "" ? $children->getSlug() : $children->getId();
                    $doparray['product_id_int'] = $children->getId();
                    $doparray['product_count'] = $children->getCount();
                    $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($property['dicategory_id']);
                    $doparray['sort'] = $dopInfoCategory->getPosition();
                    $arrayDopInfo[$property['dicategory_id']][] = $doparray;
                }



                //print_r($arrayDopInfo);
            }
        }
    }
    $i++;

endforeach; //print_r($arrayDopInfo);
//print_r($arrayDopInfo);

foreach ($arrayDopInfo as $c => $key) {
    $sort_numcie[$c] = $key['0']['sort'];
}
$sort_numcie2 = $sort_numcie;
array_multisort($sort_numcie, SORT_ASC, $arrayDopInfo);
asort($sort_numcie2);

foreach ($sort_numcie2 as $c => $null) {
    $arrayDopInfoKeys[] = $c;
}
//print_r($arrayDopInfo);
$arrayDopInfo = array_combine($arrayDopInfoKeys, $arrayDopInfo);
if ($arrayDopInfo):

    foreach ($arrayDopInfo as $key => $property):
        $key_old = $key;
        $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($key);
        $key = $dopInfoCategory->getName();

        if ($dopInfoCategory->getIsPublic()):
            ?>

            <?php
            if (count($property) == 1):

                if ($key == "Таблица размеров" and $property[0]['value'] == 1):
                    $tableSize = true;
                else:
                    ?>
                    <dl>
                        <dt>
                        <input type="hidden" name="productOptions[]" value="<?= $property[0]['dopInfoID'] ?>" />
                        <?= $key ?>:</dt>
                        <dd><?php
                            if ($key == "Производитель") {
                                $manufacturer = ManufacturerTable::getInstance()->findOneBySubid($property[0]['dopInfoID']);
                                if ($manufacturer) {
                                    if ($manufacturer->getIsPublic())
                                        echo "<a href=\"/manufacturer/" . $manufacturer->getSlug() . "\"><u>" . $property[0]['value'] . "</u></a>";
                                    else
                                        echo "" . $property[0]['value'] . "";
                                } else {
                                    echo "<a href=\"/manufacturer/" . $property[0]['dopInfoID'] . "\"><u>" . $property[0]['value'] . "</u></a>";
                                }
                            } elseif ($key == "Коллекция") {
                                $collection = CollectionTable::getInstance()->findOneBySubid($property[0]['dopInfoID']);
                                if ($collection) {
                                    echo "<a href=\"/collection/" . $collection->getSlug() . "\"><u>" . $property[0]['value'] . "</u></a>";
                                } else {
                                    echo "<a href=\"/collection/" . $property[0]['dopInfoID'] . "\"><u>" . $property[0]['value'] . "</u></a>";
                                }
                            } else {
                                echo $property[0]['value'];
                            }
                            ?></dd></dl>
                <?php
                endif;
            else:
                ?><dl>
                    <dt><?= $key ?>:</dt>
                    <dd>
                        <?php
                        $valueOther = false;
                        $firstValue = "";

                        foreach ($property as $keySub => $sub):
                            if ($firstValue == "")
                                $firstValue = $sub['product_id'];
                            elseif ($firstValue != $sub['product_id']) {
                                $valueOther = true;
                                //echo $firstValue." ".$sub['value'];
                            }
                        endforeach;
                        ?><?
                        If ($valueOther) {/* ?>
                          <select name="productOptions[]" onchange="changeArtCode(this)" style="width: 130px;" id="select_<?= $i ?>">
                          <?php foreach ($property as $keySub => $sub): ?>
                          <option value="<?= $sub['product_id'] ?>"><?= $sub['value'] ?></option>
                          <?php endforeach; ?>
                          </select><? */


                            foreach ($property as $keySub => $sub):
                                if ($keySub == 0) {
                                    ?>
                                    <div><?= $sub['value'] ?></div>
                                    <?php
                                }
                            endforeach;
                            //if ($product->getIsVisiblechildren()) {
                            foreach ($property as $keySub => $sub):
                                ?>
                                <div class="paramsBottom<? if ($keySub == 0) echo " activeParamsBottom"; ?><? if ($sub['product_count'] == 0 and $keySub != 0) echo " nonCountParamsBottom"; ?>" onClick="<? If ($productShow) { ?>changeArtCode('<?= $sub['product_id'] ?>')<? } else {
                                    ?>loadPreShowLeft(<?= $sub['product_id_int'] ?>, '<?= implode(",", $productsKeys) ?>', <?= $product->getParentsId() > 0 ? $product->getParentsId() : 0 ?>);<? } ?>"><?= $sub['value'] ?></div>
                                     <?php
                                 endforeach;
                             }else {
                               ?>
                               <?php foreach ($property as $keySub => $sub): ?>
                               <?= $sub['value'] ?><br />
                               <?php endforeach; ?>
                               <? } 
                             ?>
                    </dd></dl>
            <?php endif; ?>

            <?php
        endif;
    endforeach;
endif;
?>
<? /*
  <dl>
  <dt>Количество:</dt>
  <dd><?php if ($product->getCount() > 0): ?><input type="number" required="required" id="count" value="1" min="1" max="<?= $product->getCount() ?>" name="quantity" style="width: 30px;" onchange="$(this).val(Math.abs($(this).val()))" /><?php else: ?>
  Нет в наличии

  <?php endif; ?></dd>
  </dl>
  <dl style="display: none;" id="productCode">
  <dt>Артикул:</dt>
  <dd class="artCode"><?= $product->getCode() ?></dd>
  </dl> */ ?>
<?php if ($tableSize) { ?><dl>
        <dt><a href="/size-table" target="_blank" style="color: #954649">Таблица размеров</a></dt>
        <dd></dd>
    </dl><? } ?>