<?php if ($field->isPartial()): ?>
    <?php include_partial('orders/' . $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php elseif ($field->isComponent()): ?>
    <?php include_component('orders', $name, array('form' => $form, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?>
<?php else: ?>
    <div class="<?php echo $class ?><?php $form[$name]->hasError() and print ' errors' ?>">
        <?php echo $form[$name]->renderError() ?>
        <div><?php
            if ($name == "text"):
                $products_old = $form[$name]->getValue();
                $products_old = $products_old != '' ? unserialize($products_old) : '';
                ?>
                <table width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000" border="1" align="center">
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">Фото</th>
                            <th style="border: 1px solid #666; width: 1000px;">Название</th>
                            <!--<th width="80" style="text-align: center;border: 1px solid #666;">Артикул</th>-->
                            <th width="100" style="text-align: center;border: 1px solid #666;">Артикул</th>
                            <th width="100" style="text-align: center;border: 1px solid #666;">Кол.</th>
                            <th width="50" style="text-align: center;border: 1px solid #666;">Цена</th>
                            <th width="50" style="text-align: center;border: 1px solid #666;">Скидка</th>
                            <th width="50" style="text-align: center;border: 1px solid #666;">Бонусы</th>
                            <th width="50" style="text-align: center;border: 1px solid #666;">Сумма</th>
                        </tr>
                        <?php
                        $TotalSumm = 0;
                        //print_r($products_old);//exit;
                        foreach ($products_old as $key => $productInfo):

                            if (isset($productInfo['article'])) {
                                $product = ProductTable::getInstance()->findOneByCode($productInfo['article']);
                            }
                            if (isset($productInfo['productId']) and ! $product) {
                                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                            }
                            $TotalSumm = $TotalSumm + ($productInfo['quantity'] * (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']));

                            if ($product) {
                                $photoalbum = $product->getPhotoalbums();
                                $photos = $photoalbum[0]->getPhotos();
                                ?>

                                <tr style="border-top: 1px dotted #AAA">
                                    <td width="70" style="text-align: center;border: 1px solid #666;">
                                        <?php if (isset($photos[0])): ?>
                                            <a href="/product/<?= $product->getSlug() ?>"><img width="70" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>"></a>
                                        <?php endif; ?>
                                    </td>
                                    <td style="border: 1px solid #666;">
                                        <div style="padding:0px 0px 0px 2px">
                                            <a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a>
                                            <!--  <br />
                                             <span style="font-size:10px;">
                                                 Производитель: Internetmarketing Bielefeld GmbH, Германия
                                                 <br />
                                                     Аромат: Персик
                                                 <br />
                                                     Объем: 200 мл
                                                     </span>  -->
                                        </div>
                                    </td>
                                    <td width="50" align="center" style="text-align: center;border: 1px solid #666;"><?= $product->getCode() ?></td>
                                    <!--<td width="80" align="center" style="text-align: center;border: 1px solid #666;">211227DR</td>-->
                                    <td width="100" align="center" style="text-align: center;border: 1px solid #666;">

                                        <div style="display: none" id="price_<?= $productInfo['productId'] ?>"><?= $productInfo['price'] ?></div>
                                        <div id="quantity_<?= $productInfo['productId'] ?>" style="display: inline-block;"><?= $productInfo['quantity'] ?></div>

                                    </td>
                                    <td width="50" align="center" style="text-align: center;border: 1px solid #666;"><?= $productInfo['price'] ?> р.</td>
                                    <td width="50" align="center" style="text-align: center;border: 1px solid #666;"><?= (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0') ?></td>
                                    <td width="50" align="center" style="text-align: center;border: 1px solid #666;">
                                        <?
                                        foreach (unserialize($form['firsttext']->getValue()) as $key2 => $productInfo2):
                                            if (@$productInfo['article'] == @$productInfo2['article'] or @ $productInfo['productId'] == @$productInfo2['productId']) {
                                                if (isset($productInfo2['bonus_percent'])) {
                                                    echo round(($productInfo2['bonus_percent'] / 100) * $productInfo2['price'] * $productInfo2['quantity']);
                                                    echo " / " . $productInfo2['bonus_percent']."%";
                                                }
                                            }
                                        endforeach;
                                        ?></td>
                                    <td width="50" align="center" style="text-align: center;border: 1px solid #666;"><div style="display: inline-block;" id="totalcost_<?= $productInfo['productId'] ?>"><?= $productInfo['quantity'] * (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']) ?></div> р.</td>
                                </tr>
                                <?php
                                unset($product);
                            } else {
                                ?>

                                <tr style="border-top: 1px dotted #AAA">
                                    <td width="70" style="text-align: center;border: 1px solid #666;">
                                    </td>
                                    <td style="border: 1px solid #666;">
                                        <div style="padding:0px 0px 0px 2px">
                                            <?= $productInfo['title'] ?>
                                            <!--  <br />
                                             <span style="font-size:10px;">
                                                 Производитель: Internetmarketing Bielefeld GmbH, Германия
                                                 <br />
                                                     Аромат: Персик
                                                 <br />
                                                     Объем: 200 мл
                                                     </span>  -->
                                        </div>
                                    </td>
                                    <td width="50" align="center" style="text-align: center;border: 1px solid #666;"><?= $productInfo['article'] ?></td>
                                    <!--<td width="80" align="center" style="text-align: center;border: 1px solid #666;">211227DR</td>-->
                                    <td width="100" align="center" style="text-align: center;border: 1px solid #666;">

                                        <div style="display: none" id="price_<?= $productInfo['productId'] ?>"><?= $productInfo['price'] ?></div>
                                        <div id="quantity_<?= $productInfo['productId'] ?>" style="display: inline-block;"><?= $productInfo['quantity'] ?></div>

                                    </td>
                                    <td width="50" align="center" style="text-align: center;border: 1px solid #666;"><?= $productInfo['price'] ?> р.</td>
                                    <td width="50" align="center" style="text-align: center;border: 1px solid #666;"><?= (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0') ?></td>
                                    <td width="50" align="center" style="text-align: center;border: 1px solid #666;"><div style="display: inline-block;" id="totalcost_<?= $productInfo['productId'] ?>"><?= $productInfo['quantity'] * (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']) ?></div> р.</td>
                                </tr>
                                <?php
                            }

                        endforeach;
                        ?>
                        <tr id="deliverPriceTr">
                            <td width="70" align="center" style="border: 1px solid #666"></td>
                            <td style="border: 1px solid #666" id="deliverPriceTotalName"><b>Итого</b></td>
                            <td width="70" align="center" style="border: 1px solid #666"></td>
                            <td width="40" align="center" style="border: 1px solid #666"></td>
                            <td width="40" align="center" style="border: 1px solid #666"></td>
                            <td width="40" align="center" style="border: 1px solid #666"></td>
                            <td width="80" align="center" style="border: 1px solid #666" id="deliverPriceTotal"><?= $TotalSumm ?> р.</td>
                        </tr>

                    </tbody></table>
            <?php elseif ($name == "customer_id"):
              // if(!$form[$name]->getValue()) $form[$name]->setValue('5');
              try{
                $customer = sfGuardUserTable::getInstance()->findOneById($form[$name]->getValue());
              }
              catch(Exception $e){
                $customer = sfGuardUserTable::getInstance()->findOneById(5);
              }
                ?>
                <table width="400" cellspacing="0" cellpadding="0" bordercolor="#000000" border="1" align="center">
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">ФИО:</th>
                            <td><?= $customer->getName() ?></td>
                        </tr>
                    </tbody>
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">Индекс:</th>
                            <td><?= $customer->getIndexHouse() ?></td>
                        </tr>
                    </tbody>
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">Город:</th>
                            <td><?= $customer->getCity() ?></td>
                        </tr>
                    </tbody>
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">Улица:</th>
                            <td><?= $customer->getStreet() ?></td>
                        </tr>
                    </tbody>
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">Дом:</th>
                            <td><?= $customer->getHouse() ?></td>
                        </tr>
                    </tbody>
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">Корпус:</th>
                            <td><?= $customer->getKorpus() ?></td>
                        </tr>
                    </tbody>
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">Квартира/офис:</th>
                            <td><?= $customer->getApartament() ?></td>
                        </tr>
                    </tbody>
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">Телефон:</th>
                            <td><?= $customer->getPhone() ?></td>
                        </tr>
                    </tbody>
                    <tbody><tr>
                            <th style="border: 1px solid #666; width: 70px;">Электропочта:</th>
                            <td><?= $customer->getEmailAddress() ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php
            elseif ($name == "status"):
                echo $form[$name]->renderLabel($label);
                echo $products_old = $form[$name]->getValue();

            elseif ($name == "firsttotalcost"):
                echo $form[$name]->renderLabel($label);
                echo $products_old = $form[$name]->getValue() . " руб.";

            elseif ($name == "manager"):
                echo $form[$name]->renderLabel($label);
                echo $products_old = $form[$name]->getValue();
            else:
                echo $form[$name]->renderLabel($label)
                ?>

                <div class="content">
                    <?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes); ?>
                </div>
            <?php endif; ?>
            <?php if ($help): ?>
                <div class="help"><?php echo __($help, array(), 'messages') ?></div>
            <?php elseif ($help = $form[$name]->renderHelp()): ?>
                <div class="help"><?php echo $help ?></div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
