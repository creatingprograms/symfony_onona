
    <ul class="breadcrumbs">
        <li>
            <a href="/">Главная</a>
        </li>
        <li>
            Мои желания
        </li>
    </ul>
<?php if (is_array($products_jel) and count($products_jel)>0): ?>
    <div style="padding:5px">
        <table cellspacing="0" cellpadding="5" border="1" style="border:1px solid #999;width: 100%;">
            <tbody><tr>
                    <td width="50">Фото</td>
                    <td>Название</td>
                    <td>Цена</td>
                    <td width="20"><img width="1" height="1" src="/images/pixel.gif"></td>
                </tr>

                <?php
                foreach ($products_jel as $productInfo):
                    $product = ProductTable::getInstance()->findOneById($productInfo);
                    $photoalbums = $product->getPhotoalbums();
                    $photos = $photoalbums[0]->getPhotos();
                    ?>

                    <tr>
                        <td><a href="/product/<?= $product->getSlug() ?>"><img width="50" border="0" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>"></a></td>
                        <td><a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a></td>
                        <td><?= $product->getPrice() ?></td>

                        <td width="20" valign="middle" align="center"><a onClick='$("#main #content").load("/compare/delete/<?= $product->getId() ?>");'><img width="16" border="0" height="16" alt="Удалить из желаний" src="/images/icons/cross.png"></a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody></table>
    </div>
    <div align="center"><a href="javascript:history.back();">Продолжить покупки</a></div>
<?php else: ?>
        <?
                        $footer = PageTable::getInstance()->findOneBySlug("pustaya-stranica-spiska-zhelanii");
                        echo $footer->getContent();
                            ?>
<?php endif; ?>