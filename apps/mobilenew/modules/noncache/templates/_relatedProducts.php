<?php
$relatedProduct = ProductTable::getInstance()->getRelatedProduct();
                            //echo $relatedProduct->count();
                            if ($relatedProduct->count() > 0):
                                ?>
                                <div class="leaders-galery box">
                                    <div class="title-holder"><a href="/related" style="color: #C3060E">Лидеры продаж</a></div>
                                    <div class="galery-holder">
                                        <a href="#" class="prev"></a>
                                        <a href="#" class="next"></a>
                                        <div class="galery" style="height: 700px">
                                            <ul>
                                                <?php
                                                $q = Doctrine_Query::create()->select("name, slug, (select filename from photo where album_id=(select photoalbum_id from product_photoalbum where product_id=product.id) order by position asc limit 0,1) as filename")->from("product")->where("`is_related` = 1 and count>0")->orderBy("rand()")->execute();
                                                foreach ($q as $product):
                                                    ?>
                                                    <li><div style="text-align: center; width:186px;margin:0 auto 10px;"><?= $product->getName() ?></div><div class="prod"><?= $product->getDiscount() > 0 ? '<span class="sale' . $product->getDiscount() . '" style="top: 0px;"></span>' : ''; ?><a href="/product/<?= $product->getSlug() ?>" style="display: table-cell;vertical-align: middle;height: 268px;"><img src="/uploads/photo/thumbnails_250x250/<?= $product->getFilename() ?>" style="max-width: 188px; max-height: 260px;" alt="<?= str_replace(array("'", '"'), "", $product->getName()) ?>" /></a></div></li>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;