

<ul class="breadcrumbs"<? /* style="float: left;width: 750px;" */ ?>>
    <li>
        <a href="/">Главная</a>
    </li>
    <li>
        Сравнение товаров
    </li>
</ul>
<h1 class="title">Сравнение товаров</h1>
<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
<div class="yashare-wrapper" style="background: none;  width: 200px; height: 22px; margin-left:-6px;margin-top:-6px; margin-bottom: 10px;">
    <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
         data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

         ></div>
</div>
<?php
if (is_array($products_compare) and count($products_compare) > 0):
    ?>
    <div><div style="float: left;">Всего товаров для сравнения: <?= count($products_compare) ?></div>
        <div style="float: right;"><input type="checkbox" id="isDelete" style="float: left;margin-top: 9px;margin-right: 5px;" /><div onclick="if ($('#isDelete').prop('checked')) {
                        $('.ScroolBlock').load('/compare/deleteall');
                    } else {
                        $('.DellAllBlock').show();
                    }" class="silverButtonDelAllProducts"></div></div>
        <div class="DellAllBlock" style="display: none;position: absolute;right: 10px;top: 22px;">
            <div style="
                 position: relative;
                 width: 210px;
                 ">
                <div style="
                     background-color: rgba(255, 255, 255, 1);
                     border:1px solid #c3060e; padding: 20px; ">
                    Для удаления товаров установите галочку.
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
    </div>
    <div style="clear: both;height: 10px;"></div>
    <?
    //$whereDopInfo = "product_id = " . $product->getId();
    foreach ($products_compare as $productId) {
        $whereDopInfo.=" OR product_id = " . $productId;
    }
    $whereDopInfo = trim($whereDopInfo, " OR ");
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    //$result = $q->execute("select * from (SELECT*,count(dop_info_id) as count FROM dop_info_product where " . $whereDopInfo . " group by dop_info_id) as params left join dop_info as dp on dp.id=params.dop_info_id");


    $result = $q->execute("select * from (SELECT * FROM dop_info_product where " . $whereDopInfo . ") as params left join dop_info as dp on dp.id=params.dop_info_id left join dop_info_category as dpc on dpc.id=dp.dicategory_id where dpc.name!='Таблица размеров' and dpc.is_compare='1' order by dpc.name");

    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $productsDopInfo = $result->fetchAll();
    //print_r($productsDopInfo);
    foreach ($productsDopInfo as $dopinfo) {
        $arrayDopInfo[$dopinfo['product_id']][$dopinfo['name']][] = $dopinfo['value'];
    }
    //print_r($arrayDopInfo);


    $result = $q->execute("select dpc.name, dpc.namecompare from (SELECT * FROM dop_info_product where " . $whereDopInfo . ") as params left join dop_info as dp on dp.id=params.dop_info_id left join dop_info_category as dpc on dpc.id=dp.dicategory_id where dpc.name!='Таблица размеров' and dpc.is_compare='1' group by dpc.name order by dpc.namecompare");

    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $DopInfoCategory = $result->fetchAll();
    //print_r($DopInfoCategory);
    ?><link href="/css/jquery.mCustomScrollbar.css" rel="stylesheet">
    <script src="/js/jquery.mCustomScrollbar.js"></script>

    <script>
      (function ($) {
        $(window).load(function () {
          $(".ScroolBlock").mCustomScrollbar({
            axis: "x",
            scrollButtons: {enable: true},
            theme: "3d",
            scrollbarPosition: "outside",
            autoExpandScrollbar: true,
            autoDraggerLength: false,
            advanced: {autoExpandHorizontalScroll: true}
          });
        });
        $(".ScroolBlock").mCustomScrollbar({
          axis: "x",
          scrollButtons: {enable: true},
          theme: "3d",
          scrollbarPosition: "outside",
          autoExpandScrollbar: true,
          autoDraggerLength: false,
          advanced: {autoExpandHorizontalScroll: true}
        });
      })(jQuery);
      function CollapseExpand(button, num) {
        if ($(button).find(".blockDescriptionContentProductTurnButtom").text() == "") {
          $(button).find(".blockDescriptionContentProductTurnButtom").text('-');
          $(button).find(".blockDescriptionContentProductTurnButtom").attr('data-minus', '1');
          $(button).find(".blockDescriptionContentProductTurnButtom").css('background', "none repeat scroll 0 0 #c3060e");
          $("#tableCompare" + num).fadeIn(1);
        }
        else {
          $(button).find(".blockDescriptionContentProductTurnButtom").text('');
          $(button).find(".blockDescriptionContentProductTurnButtom").removeAttr('data-minus');
          $(button).find(".blockDescriptionContentProductTurnButtom").css('background', "url('/images/newcat/+.png')");
          $("#tableCompare" + num).fadeOut(0);
        }
      }
    </script>
    <div style="<? /* padding:5px; */ ?>overflow-x: auto;margin-bottom: 20px;" class="ScroolBlock">

        <table cellspacing="0" cellpadding="5" border="1" style="border:1px solid #999;width: 0;table-layout: fixed;" class="tableCompare">
            <tbody><tr>
                    <td width="120" style=" color: #c4040d;padding:10px;letter-spacing: -0.7px;">Общая информация</td>

                    <?php
                    foreach ($products_compare as $productInfo):
                        $product = ProductTable::getInstance()->findOneById($productInfo);
                        $photoalbums = $product->getPhotoalbums();
                        $photos = $photoalbums[0]->getPhotos();
                        ?>
                        <td width="188" style="height: 125px; position: relative;">
                            <div class="close" onClick='$("#main #content").load("/compare/delete/<?= $product->getId() ?>");'></div>
                            <div style="width: 170px; text-align: center;">
                                <a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a>
                            </div>
                            <div style="float: left; position: absolute; bottom: 10px;">
                                <span style=" border:1px solid #e0e0e0; width: 60px; height: 60px;cursor: pointer; text-align: center;display: inline-block;vertical-align: middle;">
                                    <img style="max-width: 60px; max-height: 60px;" border="0" src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>">
                                </span>
                            </div>
                            <div style="float: right;margin-top: 30px; position: absolute; bottom: 27px;right: 10px;"><?php if ($product->getCount() > 0 and $product->get("is_public")): ?>
                                    <?
                                    $products_old = unserialize($sf_user->getAttribute('products_to_cart'));
                                    if (is_array($products_old))
                                        foreach ($products_old as $key => $productCart) {
                                            $arrayProdCart[] = $productCart['productId'];
                                        }
                                    if (in_array($product->getId(), $arrayProdCart) === true)
                                        $prodInCart = true;
                                    else
                                        $prodInCart = false;
                                    if ($prodInCart) {
                                        ?>
                                        <a href="/cart" class="greenButtonAddToCartIco" style='margin-right: 10px;margin-top: 5px;'></a>
                                    <? } else { ?>
                                        <div class="redButtonAddToCartIco" style='margin-right: 10px;margin-top: 5px;' onClick="
                                                                tagDiv = this;
                                                                $.ajax({
                                                                    url: '/cart/addtocart/<?= $product->getId() ?>',
                                                                    cache: false
                                                                }).done(function (html) {
                                                                    addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');
                                                                    $(tagDiv).after($('<a class=\'greenButtonAddToCartIco\'>').attr('href', '/cart')).remove();

                                                                });
                                             "></div>
                                         <? } ?>
                                         <?php
                                     endif;

                                     $products_desire = $sf_user->getAttribute('products_to_desire');
                                     $products_desire = $products_desire != '' ? unserialize($products_desire) : '';

                                     if (in_array($product->getId(), $products_desire) === true)
                                         $prodInDesire = true;
                                     else
                                         $prodInDesire = false;
                                     ?>
                                     <? if ($prodInDesire === true) {
                                         ?>
                                    <a class="greenButtonAddToDesire" href='/desire' style='margin-right: 10px;margin-top: 5px;'></a>
                                    <?
                                } else {
                                    ?>
                                    <div class="redButtonAddToDesire" style='margin-right: 10px;margin-top: 5px;' onClick='javascript: $(this).after($("<a class=\"greenButtonAddToDesire\">").attr("href", "/desire")).remove();
                                                        addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                                                        $("#JelHeader").load("/cart/addtodesire/<?= $product->getId() ?>");'></div>
                                     <? } ?>

                                <div class="greenButtonDelToCompare" style='margin-right: 10px;margin-top: 5px;' onClick='$("#main #content").load("/compare/delete/<?= $product->getId() ?>");'></div>


                            </div>
                        </td>

                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td style="text-align: right"><b>Фото:</b></td>
                    <?
                    foreach ($products_compare as $productInfo):
                        $product = ProductTable::getInstance()->findOneById($productInfo);
                        $photoalbums = $product->getPhotoalbums();
                        $photos = $photoalbums[0]->getPhotos();
                        ?>
                        <td style="text-align: center">

                            <a href="" onclick="loadPreShowPhoto(<?= $product->getId() ?>);
                              return false;"><?= $photos->count() ?> - посмотреть</a>
                            <script>
                              function loadPreShowPhoto($id) {
                                $.post("/product/preshowphoto/" + $id,
                                  function (data) {
                                    $('<div/>').click(function (e) {
                                      if (e.target != this)
                                          return;
                                      $(this).remove();
                                    }).attr('id', 'preShow-' + $id).css("padding-top", $(window).scrollTop() + 40).addClass("blockPreShow").appendTo('body');
                                    $('.blockPreShow').html(data);

                                    $(document).keyup(function (e) {
                                      if (e.keyCode == 27) {
                                          $('.blockPreShow').remove();
                                      }   // esc
                                    });
                                  });
                              }
                            </script>

                        </td>

                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td style="text-align: right"><b>Цена:</b></td>
                    <?
                    foreach ($products_compare as $productInfo):
                        $product = ProductTable::getInstance()->findOneById($productInfo);
                        ?>
                        <td style="text-align: center">
                            <? if ($product->getBonuspay() > 0) { ?>
                                <span style="font-size: 13px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= number_format($product->getPrice(), 0, '', ' ') ?> р.</span><span style="font-size: 18px; color: #c3060e; margin-right: 10px;"><?= number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> р.</span>
                            <? } elseif ($product->getDiscount() > 0) { ?>
                                <span style="font-size: 13px; color: #414141;text-decoration: line-through; margin-right: 10px;"><?= $product->getOldPrice() ?> р.</span><span style="font-size: 18px; color: #c3060e; margin-right: 10px;"><?= $product->getPrice() ?> р.</span>
                            <? } else { ?>
                                <span style="font-size: 18px; color: #c3060e; margin-right: 10px;"><?= $product->getPrice() ?> р.</span>

                            <? } ?>

                        </td>

                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td style="text-align: right"><b>Акция:</b></td>
                    <?
                    foreach ($products_compare as $productInfo):
                        $product = ProductTable::getInstance()->findOneById($productInfo);
                        ?>
                        <td style="text-align: center">
                            <?php if ($product->getDiscount() > 0) { ?>
                                Лучшая цена
                            <? } elseif ($product->getBonuspay() > 0) { ?>
                                Управляй ценой!
                            <? } ?></td>

                    <?php endforeach; ?>
                </tr>
            <td style="text-align: right"><b>Категория:</b></td>
            <?
            foreach ($products_compare as $productInfo):
                $product = ProductTable::getInstance()->findOneById($productInfo);
                $generalCategory = $product->getGeneralCategory();
                if ($generalCategory->getParent()) {
                    $categoryParents = $generalCategory->getParent();
                }
                ?>
                <td style="text-align: center">
                    <?php
                    if ($categoryParents) {
                        echo $categoryParents->getName() . "<br />";
                    }
                    if ($generalCategory) {
                        echo $generalCategory->getName();
                    }
                    ?></td>

            <?php endforeach; ?>
            </tr><?
            foreach ($DopInfoCategory as $keyNumCategory => $category):
                if ($category['namecompare'] != $oldCategoryPrint and $category['namecompare'] != "") {
                    $oldCategoryPrint = $category['namecompare'];
                    ?></tbody></table>
                <div style="margin-top:30px;border:1px solid #dddddd;height: 20px;" onclick="CollapseExpand(this,<?= $keyNumCategory ?>)">
                    <div class="blockDescriptionContentProductTurnButtom" data-minus="1">-</div>
                    <div class="blockDescriptionContentProductTurnText" style="text-decoration: none; color: #c4040d;padding-top:2px;"><?= $category['namecompare'] ?></div>

                </div>
                <table cellspacing="0" cellpadding="5" border="1" style="border:1px solid #dddddd;width: 0;table-layout: fixed; border-top: 1px solid #FFF;" class="tableCompare" id="tableCompare<?= $keyNumCategory ?>">
                    <tbody>
                        <?
                    }
                    ?>
                    <tr>
                        <td style="text-align: right;font-weight: bold;padding:10px; width: 120px;"><b><?= $category['name'] ?>:</b></td>
                        <?
                        foreach ($products_compare as $productInfo):
                            ?>
                            <td style="text-align: center; width:188px;"><?
                                foreach ($arrayDopInfo[$productInfo][$category['name']] as $param) {
                                    echo $param . "<br />";
                                }
                                ?>
                                <? /* = $arrayDopInfo[$productInfo][$category['name']] */ ?>
                            </td>

                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody></table>
        <div style="margin-top:10px;border:1px solid #dddddd;height: 20px;" onclick="CollapseExpand(this, 'rate')">
            <div class="blockDescriptionContentProductTurnButtom" data-minus="1">-</div>
            <div class="blockDescriptionContentProductTurnText" style="text-decoration: none; color: #c4040d;padding-top:2px;">Рейтинг и отзывы</div>

        </div>
        <table cellspacing="0" cellpadding="5" border="1" style="border:1px solid #dddddd;width: 0;table-layout: fixed; border-top: 1px solid #FFF;" class="tableCompare"id="tableComparerate">
            <tbody>
                <tr>
                    <td style="text-align: right;font-weight: bold;padding:10px; width: 120px;"><b>Рейтинг:</b></td>
                    <?
                    foreach ($products_compare as $productInfo):
                        $product = ProductTable::getInstance()->findOneById($productInfo);
                        ?>
                        <td style="text-align: center; width:188px;">
                            <div class="stars">
                                <span style="left: 0;width:<?= $product->getRating() > 0 ? (@round($product->getRating() / $product->getVotesCount())) * 10 : 0 ?>%;"></span>
                            </div>

                        <?php endforeach; ?>
                </tr>

                <tr>
                    <td style="text-align: right"><b>Отзывы:</b></td>
                    <?
                    foreach ($products_compare as $productInfo):
                        $comments = Doctrine_Core::getTable('Comments')
                                ->createQuery('c')
                                ->where("is_public = '1'")
                                ->addWhere('product_id = ?', $productInfo)
                                ->orderBy('id ASC')
                                ->execute();
                        ?>
                        <td style="text-align: center">
                            <? if ($comments->count() > 0) { ?><a href="/product/<?= $product->getSlug() ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $comments->count() ?></a><? } ?>
                        </td>

                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>

        <? /*
          <table cellspacing="0" cellpadding="5" border="1" style="border:1px solid #999;width: 100%;">
          <tbody><tr>
          <td width="50">Фото</td>
          <td>Название</td>
          <td>Цена</td>
          <td width="20"><img width="1" height="1" src="/images/pixel.gif"></td>
          </tr>

          <?php
          foreach ($products_compare as $productInfo):
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
          <div align="center"><a href="javascript:history.back();">Продолжить покупки</a></div> */ ?>
    </div>
<?php else: ?>
    У вас нет товаров для сравнения
<?php endif; ?>
