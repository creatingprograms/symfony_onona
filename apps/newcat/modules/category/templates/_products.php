<?
$newsList=csSettings::get('optimization_newProductId');
$newsListAr=explode(',', $newsList);

if ((strtotime($product->getEndaction()) + 86399) < time() and $product->getEndaction() != "") {
    $product->setEndaction(NULL);
    if ($product->getDiscount() > 0) {
        $product->setPrice($product->getOldPrice());
        $product->setOldPrice(Null);
    }
    $product->setDiscount(0);
    $product->setBonuspay(NULL);
    $product->setStep(NULL);
    $product->save();
}

if ($product->getId() != ""):

    $timerBeforeChildren = sfTimerManager::getTimer('ComponentsInclude: Before Children as _products');
    $timerGetPhotosCommentsChildren = sfTimerManager::getTimer('ComponentsInclude: GetPhotosCommentsChildren as _products');

    $timerGetChildren = sfTimerManager::getTimer('ComponentsInclude: GetPhotosCommentsChildren as _products: Get Children');
    if ($product->getParentsId() > 0)
        $productChildrens = ProductTable::getInstance()->createQuery()->where("is_public = '1'")->addWhere("(parents_id=" . $product->getId() . " or parents_id=" . $product->getParentsId() . " or id=" . $product->getParentsId() . ")")->addWhere("id!=" . $product->getId())->execute();
    else
        $productChildrens = ProductTable::getInstance()->createQuery()->where("is_public = '1'")->addWhere("parents_id=" . $product->getId() . "")->addWhere("id!=" . $product->getId())->execute();
    $timerGetChildren->addTime();

    $childrensId = $productChildrens->getPrimaryKeys();
    $childrensId[] = $product->getId();
    if ($photoFilename == "") {
        $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
        $photoFilename = $photos[0]['filename'];
    }
    if ($commentsCount == "") {
        $comments = Doctrine_Core::getTable('Comments')
                ->createQuery('c')
                ->where("is_public = '1'")
                ->addWhere('product_id in (' . implode(',', $childrensId) . ')')
                ->orderBy('id ASC')
                ->execute();
        $commentsCount = $comments->count();
    }
    $timerGetPhotosCommentsChildren->addTime();
    ?><li <?
    if (!$last) {
        echo "style=\"white-space: normal;\"";
    }
    ?> class="prodTable-<?= $product->getId() ?> <?php if ($product->getCount() == 0): ?> liProdNonCount<?php endif; ?>">
        <?
        if (isset($mainpage)) {
            if ($mainpage) {
                echo "<div>";
            }
        }
        ?><?
        // var_dump($productShowItems);
        if (isset($productShowItems)) {
            if ($productShowItems) {
                $posY = "productShowItems";
            }
        } elseif ($product->getEndaction() != "") {
            $posY = "PreShowProdAction";
        } else {
            $posY = "PreShowProd";
        }
        ?>

        <div class="c">
            <div class="content<?php if ($product->getCount() == 0): ?> notcount<?php endif; ?>">
              <?php //if ($product->getCount() > 0): ?>
                <div
                  class="js-mobile-button-buy mobile-button-buy gtm-list-add-to-basket"
                  <?=$listname ? ' data-list="'.$listname.'"' : ''?>
                  data-id="<?=$product->getId()?>"
                  data-name="<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>"
                  data-image="<?=$photoFilename?>"
                  data-price="<?=$product->getPrice()?>"
                  data-bonus-add="<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>"
                ><?=$product->getCount() > 0 ? 'В корзину' : 'Заказать'?></div>
              <?php //endif ?>


                <? if ($product->getBonuspay() > 0) { ?>
                    <div style="position: absolute; left: 0px; top: 60px;z-index: 10; cursor:pointer;" onClick="$('.liTipContent .bonusDiscountText_<?= $product->getId() ?>').each(function (index) {
                                $(this).toggle();
                            });">
                        <img src="/images/bonDisc/podarok.png" alt="Управляй ценой">
                        <div style='    position: absolute; /* Абсолютное позиционирование */
                             left: 35px; top: 2px; /* Положение подсказки */
                             z-index: 1; /* Отображаем подсказку поверх других элементов */
                             background: #fff; /* Полупрозрачный цвет фона */
                             font-family: Arial, sans-serif; /* Гарнитура шрифта */
                             font-size: 13px; /* Размер текста подсказки */
                             padding: 5px 10px; /* Поля */
                             border: 1px solid #c4040d; /* Параметры рамки */
                             width: 150px;
                             -webkit-border-radius: 5px;
                             -moz-border-radius: 5px;
                             color: #000;
                             border-color: #c4040d;display: none;' class='bonusDiscountText_<?= $product->getId() ?>'>
                            <div style="text-align: center;margin-bottom: 10px;"><span style="color: #c4040d;
                                                                                       font-weight: bold;">УПРАВЛЯЙ ЦЕНОЙ!</span></div>
                            <div style="margin-bottom: 10px;">Оплата накопленными<br />
                                Бонусами до <?= $product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY') ?>% от<br />
                                стоимости товара.</div>
                            <a href="/programma-on-i-ona-bonus" target="_blank">Условия программы ></a>
                        </div>

                    </div>

                    <?
                    echo '<span class="sale' . /*$product->getBonuspay() .*/ '">-' . $product->getBonuspay() . '%</span>';
                } elseif ($product->getDiscount() > 0) {
                    ?>
                    <div style="position: absolute; left: 0px; top: 60px;z-index: 10;" class="loveprice" data-title="Лучшая цена в Рунете!"><a href="/category/Luchshaya_tsena"><img src="/newdis/images/loveprice.png" alt="Лучшая цена"></a></div>
                    <?
                } else {
                  $isNew=false;
                  if (strtotime($product->getCreatedAt()) > time() - (csSettings::get('logo_new') * 24 * 60 * 60)) $isNew= true;
                  if (in_array($product->getId(), $newsListAr)) $isNew= true;
                  echo $isNew ? '<span class="newProduct"></span>' : '';
                  // if ($product->getId()==21318) die('~!!~'.print_r(['arr'=> $newsListAr, 'id'=>$product->getId(), 'isNew'=>$isNew], true));
                }
                ?>
                <span class="productInDesire-<?= $product->getId() ?> productInDesire"<?= $prodInDesire ? ' style="display: block;"' : '' ?>></span>
                <span class="productInCompare-<?= $product->getId() ?> productInCompare"<?= $prodInCompare ? ' style="display: block;"' : '' ?>></span>
                <span class="productInCart-<?= $product->getId() ?> productInCart"<?= $prodInCart ? ' style="display: block;"' : '' ?>></span>
                <?= $product->getDiscount() > 0 ? '<span class="sale' . /*$product->getDiscount() . */'">-' . $product->getDiscount() . '%</span>' : ''; ?>

                <?= ($product->getVideo() != '' or $product->getVideoenabled() ) ? '<span class="videoProduct" title="К этому товару есть видео-презентация"></span>' : ''; ?>

                <div class="title"><a class="gtm-link-item" href="/product/<?= $product->getSlug() ?>"><? mb_internal_encoding('UTF-8'); ?><?= $product->getName() ?></a></div><?php /* mb_substr($product->getName(), 0, 55) */?>
                <div class="img-holder">
                    <a class="gtm-link-item" href="/product/<?= $product->getSlug() ?>">
                        <? If ($prodNum < 6) { ?>
                            <img <? /* id="photoimg_<?= $product->getId() ?>" */ ?>src="/uploads/photo/thumbnails_250x250/<?= $photoFilename ?>" alt='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' title='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' class="thumbnails" />
                        <? } else { ?>
                            <img <? /* id="photoimg_<?= $product->getId() ?>" */ ?>src="/images/pixel.gif" data-original="/uploads/photo/thumbnails_250x250/<?= $photoFilename ?>" alt='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' title='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' class="thumbnails" />
                        <? } ?>
                    </a>
                    <? if ($product->getEndaction() != ""): ?>
                        <div class="countdown">
                            <script>
                                if (typeof ($.fn.countdown) === 'undefined' && !$("script").is("#countdownSRC")) {

                                    (function () {
                                        var lt = document.createElement('script');
                                        lt.type = 'text/javascript';
                                        lt.async = true;
                                        lt.src = '/newdis/js/jquery.countdown.js';
                                        lt.id = 'countdownSRC';
                                        var s = document.getElementsByTagName('script')[0];
                                        s.parentNode.insertBefore(lt, s);
                                    })();
                                }
                            </script>
                            <a class="buy">Успейте купить!</a>
                            <span class="time actionProduct_<?= $product->getId() ?>"></span>
                            <?php
                            $step = array("1 сутки" => "1", "2 суток" => "2", "3 суток" => "3", "4 суток" => "4", "5 суток" => "5");
                            if ($product->getStep() != "") {
                                //echo $product->getEndaction() - time() + 24 * 60 * 60;
                                if ((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) > $step[$product->getStep()] * 24 * 60 * 60) {
                                    $dateEnd = date("Y, m-1, d", (strtotime($product->getEndaction()) - floor((strtotime($product->getEndaction()) - time() + 24 * 60 * 60) / ($step[$product->getStep()] * 24 * 60 * 60)) * $step[$product->getStep()] * 24 * 60 * 60));
                                } else {
                                    $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()));
                                }
                            } else {
                                $dateEnd = date("Y, m-1, d", strtotime($product->getEndaction()));
                            }
                            ?>
                            <script type="text/javascript">
                                $("#countdownSRC").load(function () {
                                    $('.countdown .actionProduct_<?= $product->getId() ?>').countdown({
                                        //format: 'd hh:mm:ss',
                                        format: 'hh:mm:ss',
                                        compact: true,
                                        description: '',
                                        until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                                //timezone: +6
                                    });
                                });
                                if (typeof ($.fn.countdown) !== 'undefined') {
                                    $('.countdown .actionProduct_<?= $product->getId() ?>').countdown({
                                        //format: 'd hh:mm:ss',
                                        format: 'hh:mm:ss',
                                        compact: true,
                                        description: '',
                                        until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                                //timezone: +6
                                    });
                                }/*
                                 $(document).ready(function () {
                                 $('.countdown .actionProduct_<?= $product->getId() ?>').countdown({
                                 //format: 'd hh:mm:ss',
                                 format: 'hh:mm:ss',
                                 compact: true,
                                 description: '',
                                 until: new Date(<?= $dateEnd ?>, 23, 59, 59)
                                 //timezone: +6
                                 });
                                 });*/</script>
                        </div>
                        <? Endif; ?>
                </div>
                <div class="bottom-box">
                    <div class="row">
                        <? if ($commentsCount > 0) { ?>
                          <a class="gtm-link-item" href="/product/<?= $product->getSlug() ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $commentsCount ?></a>
                        <? } ?>
                        <? if (!$commentsCount) {
                          echo '&nbsp;';
                        } ?>
                        <div class="stars">
                            <span style="width:<?= $product->getRating() > 0 ? (@round($product->getRating() / $product->getVotesCount())) * 10 : 0 ?>%;"></span>
                        </div>
                    </div>
                    <div class="price-box">
                        <?php if ($product->getOldPrice() > 0) { ?>
                            <span class="old-price"><?= number_format($product->getOldPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                            <span class="new-price"><?= number_format($product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                        <? } elseif ($product->getBonuspay() > 0) { ?>
                            <span class="old-price"><?= number_format($product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                            <span class="new-price"><?= number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                        <? } else { ?>
                            <span class="price"><?= number_format($product->getPrice(), 0, '', ' ') ?>  <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
                        <? } ?>
                    </div>

                    <div class="bonus-box-price"><?php
                        // if ($product->getBonuspay() > 0) {
                        if (true) {
                            $bonusAddUser = round(($product['price'] - $product['price'] * ($product['bonuspay'] > 0 ? $product['bonuspay'] : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($product['bonus'] > 0 ? $product['bonus'] : csSettings::get('persent_bonus_add')) / 100));
                            // $bonusAddUser = round(($product->getPrice() - $product->getPrice() * ($product->getBonuspay() / 100)) * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
                            if ($bonusAddUser > 0) {
                                ?>+<?= $bonusAddUser ?> бонусов<?
                            }
                        } else {
                            ?>+<?= round($product->getPrice() * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100)) ?> бонусов<? } ?>
                    </div><? /*
                      <div class="tools">
                      <a href="#" class="att" title="Быстрый просмотр товара"></a><?php if ($product->getCount() > 0): ?>
                      <a href="#" id="buttonId_<?= $product->getId() ?>" class="red-btn small to-card  colorWhite" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', {quantity: 1}, function (data) {
                      $.post('/cart/addtocart/<?= $product->getId() ?>', {quantity: 1}, function (data) {
                      });
                      });

                      addToCartNew('/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>', '<?= str_replace(array("'", '"', '\\'), "", $product->getName()) ?>', '<?= $product->getPrice() ?>', '<?= round(($product->getPrice() * csSettings::get("persent_bonus_add")) / 100) ?>');
                      changeButtonToGreen(<?= $product->getId() ?>);
                      return false;
                      " title="Купить">

                      <span>В корзину</span>
                      </a>
                      <a href="#" class="to-desire" title="Добавьте товар в список своих желаний и вы сможете легко вернуться к просмотру данного товара, удалить из списка желаний или заказать его" onClick='javascript: addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);
                      $("#JelHeader").load("/cart/addtojel/<?= $product->getId() ?>");'></a>

                      <?php else: ?>

                      <a id="addToBasket3-<?= $product->getId() ?>" class="red-btn to-card small  colorWhite highslide" onclick="return hs.htmlExpand(this, {contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar red-header',
                      headingText: '', width: 553, height: 400, slideshowGroup: 'groupToSend', left: -9});"
                      style=""><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;" onclick="$('#ContentToSend_<?= $product->getId() ?> .captchasu').attr('src', $('#ContentToSend_<?= $product->getId() ?> .captchasu').attr('data-original'));
                      setTimeout('enableAjaxFormSendUser_<?= $product->getId() ?>()', 500)">Сообщить о поступлении</span></a>

                      <?php endif; ?>
                      </div> */ ?>
                </div>
            </div>
        </div>
        <div class="b"></div>
        <?
        $timerBeforeChildren->addTime();
        $timer = sfTimerManager::getTimer('ComponentsInclude: Children as _products');
        $productIdClildrenAndThis = $productChildrens->getPrimaryKeys();
        $productIdClildrenAndThis[] = $product->getId();
        $whereDopInfo = implode(",", $productIdClildrenAndThis);
        /* $whereDopInfo = "product_id = " . $product->getId();
          foreach ($productChildrens as $children) {
          $whereDopInfo.=" OR product_id = " . $children->getId();
          } */
        $timerDopInfo = sfTimerManager::getTimer('ComponentsInclude: Children as _products Foreach Dop Info');
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute("select *, dpс.name as dpc_name from (SELECT*,count(dop_info_id) as count FROM dop_info_product where product_id in (" . $whereDopInfo . ") group by dop_info_id) as params left join dop_info as dp on dp.id=params.dop_info_id left join dop_info_category as dpс on dpс.id=dp.dicategory_id where params.count <" . ($productChildrens->count() + 1));
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $childrensDopInfo = $result->fetchAll();

        foreach ($childrensDopInfo as $dopInfo) {

            $tempArray['name'] = $dopInfo['name'] != "" ? $dopInfo['name'] : $dopInfo['dpc_name'];
            $tempArray['value'] = $dopInfo['value'];
            $childrenDopInfo[$dopInfo['product_id']][] = $tempArray;
            unset($tempArray);
        }
        $timerDopInfo->addTime();
        ?>
        <div class="preShowProduct-<?= $product->getId() ?>">
            <div  class="preShowProduct">
                <ul class="item-list" style="padding: 0px;"><li style="border:0px;padding: 0px;">
                        <div class="c">
                            <div <?=$listname ? ' data-list="'.$listname.'"' : ''?>  class="content contentPreShowProduct-<?= $product->getId() ?><?php if ($product->getCount() == 0): ?> notcount<?php endif; ?>">
                                <?
                                include_component("category", "changechildren", [
                                    'sf_cache_key' => $product->getId() . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-" . md5($productsKeys) . "-showarticle-" . $showarticle . "-rrMETHOD_NAME-" . (isset($rrMETHOD_NAME) ? $rrMETHOD_NAME : ''),
                                    'rrMETHOD_NAME' => isset($rrMETHOD_NAME) ? ($rrMETHOD_NAME) : '',
                                    'id' => $product->getId(),
                                    "productsKeys" => $productsKeys,
                                    "prodInCart" => $prodInCart,
                                    "prodInDesire" => $prodInDesire,
                                    "prodInCompare" => $prodInCompare,
                                    "showarticle" => $showarticle,
                                    "product" => $product,
                                    "commentsCount" => $commentsCount,
                                    "photoFilename" => $photoFilename
                                        ]
                                );
                                ?>
                            </div>
                            <div style="float: left; position: relative; margin-left: 250px; top: -210px;  max-height: 420px;<? If ($productChildrens->count() > 6) { ?> overflow-y: scroll<? } ?>">
                                <div class="childrenBottom activeChildrenBottom" onclick='$(".childrenBottom").each(function (i) {
                                            $(this).removeClass("activeChildrenBottom");
                                        });
                                        $(this).addClass("activeChildrenBottom");
                                        $(".contentPreShowProduct-<?= $product->getId() ?>").each(function (i) {
                                            $(this).load("/category/changechildren/<?= $product->getId() ?>", {productsKeys: "<?= $productsKeys ?>", prodInCart: <?= $prodInCart === false ? 0 : 1 ?>},
                                            function () {
                                                $(this).find("img.thumbnails").each(function () {
                                                    $(this).attr("src", $(this).attr("data-original"));
                                                });
                                            }
                                            );
                                        });
                                     '><img src="/images/pixel.gif" data-original="/uploads/photo/thumbnails_60x60/<?= $photoFilename ?>" class="thumbnails" style="max-width: 60px; max-height: 60px;" alt="<?= str_replace(array("'", '"'), "", $product->getName()) ?>-0" />
                                         <?
                                         if (isset($childrenDopInfo)) {
                                             if (count($childrenDopInfo[$product->getId()]) > 0) {
                                                 ?><span><?
                                                foreach ($childrenDopInfo[$product->getId()] as $dopinfo) {
                                                    echo $dopinfo['name'] . ": " . $dopinfo['value'] . "";
                                                }
                                                ?></span>
                                            <?
                                        }
                                    }
                                    ?></div>
                                    <?
                                    foreach ($productChildrens as $children) {
                                        $timerDBPhotosChildren = sfTimerManager::getTimer('ComponentsInclude: Children as _products DB photos children');
                                        $photosChildren = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $children->getId() . " limit 0,1)")->orderBy("position")->execute();

                                        $timerDBPhotosChildren->addTime();
                                        ?><div class="childrenBottom" onclick='$(".childrenBottom").each(function (i) {
                                                            $(this).removeClass("activeChildrenBottom")
                                                        });
                                                        $(this).addClass("activeChildrenBottom");
                                                        $(".contentPreShowProduct-<?= $product->getId() ?>").each(function (i) {
                                                            $(this).load("/category/changechildren/<?= $children->getId() ?>", {productsKeys: "<?= $productsKeys ?>", prodInCart: <?= $prodInCart === false ? 0 : 1 ?>},
                                                            function () {
                                                                $(this).find("img.thumbnails").each(function () {
                                                                    $(this).attr("src", $(this).attr("data-original"));
                                                                });
                                                            });
                                                        });'><img src="/uploads/photo/thumbnails_60x60/<?= $photosChildren[0]['filename'] ?>" style="max-width: 60px; max-height: 60px;" alt="<?= str_replace(array("'", '"'), "", $children->getName()) ?>" />
                                        <? if (count($childrenDopInfo[$children->getId()]) > 0) { ?><span><?
                                            foreach ($childrenDopInfo[$children->getId()] as $dopinfo) {
                                                echo $dopinfo['name'] . ": " . $dopinfo['value'] . "";
                                            }
                                            ?></span><? } ?></div>
                                    <?
                                }
                                ?>
                            </div>
                        </div></li></ul>
            </div>
        </div>

        <?
        $timer->addTime();
        If ($showarticle) {
            echo $product->getCode();
        }
        ?>
        <?
        if (isset($mainpage)) {
            if ($mainpage) {
                echo "</div>";
            }
        }

        if (!$agentIsMobile) {
            ?>
            <script>
                if (typeof ($.fn.liTip) === 'undefined' && !$("script").is("#liTipSRC")) {

                    (function () {
                        var lt = document.createElement('script');
                        lt.type = 'text/javascript';
                        lt.async = true;
                        lt.src = '/js/liTip_newcat.js';
                        lt.id = 'liTipSRC';
                        var s = document.getElementsByTagName('script')[0];
                        s.parentNode.insertBefore(lt, s);
                    })();
                }
                $("#liTipSRC").load(function () {
                    $('li.prodTable-<?= $product->getId() ?>').each(function (index) {
                        $(this).liTip({
                            timehide: 0/*1000*/,
                            posY: '<?= $posY ?>',
                            radius: '0px',
                            maxWidth: '320px',
                            content: $(".preShowProduct-<?= $product->getId() ?> .preShowProduct").html()
                        });
                    });
                });

                if (typeof ($.fn.liTip) !== 'undefined') {

                    $('li.prodTable-<?= $product->getId() ?>').each(function (index) {
                        $(this).liTip({
                            timehide: 0/*1000*/,
                            posY: '<?= $posY ?>',
                            radius: '0px',
                            maxWidth: '320px',
                            content: $(".preShowProduct-<?= $product->getId() ?> .preShowProduct").html()
                        });
                    });
                }
            </script>
        <? }
        ?>
    </li>
    <? Endif; ?>
