<?php
mb_internal_encoding('UTF-8');
slot('metaTitle', $mainPage['title'] == '' ? $mainPage['name'] : $mainPage['title']);
slot('metaKeywords', $mainPage['keywords'] == '' ? $mainPage['name'] : $mainPage['keywords']);
slot('metaDescription', $mainPage['description'] == '' ? $mainPage['name'] : $mainPage['description']);


$matches = array(
    "{url_for('@catalog?slug=sex-igrushki-dlja-par')}" => url_for('@catalog?slug=sex-igrushki-dlja-par'),
    "{url_for('@catalog?slug=sex-igrushki-dlya-muzhchin')}" => url_for('@catalog?slug=sex-igrushki-dlya-muzhchin'),
    "{url_for('@catalog?slug=sex-igrushki-dlya-zhenschin')}" => url_for('@catalog?slug=sex-igrushki-dlya-zhenschin'),
    "{url_for('@catalogs')}" => url_for('@catalogs')
);

JSInPages("function togglePoint(tag){
                $(tag).parent().parent().children('ul').toggle();
                $(tag).parent().parent().children('.description').toggle();
                $(tag).parent().parent().children('.bottomPoint').toggle();
                if($(tag).hasClass('minus')){
                    $(tag).removeClass('minus').addClass('plus');
                }else{
                    $(tag).removeClass('plus').addClass('minus');
                }
                }");

JSInPages("function togglePointFromSubPoint(tag){
                if($(tag).hasClass('minus')){
                    $(tag).removeClass('minus').addClass('plus');

                    $(tag).parent().parent().children('.subPoint').each(function (i){
                        $(this).fadeOut(0);
                    });
                }else{
                    $(tag).removeClass('plus').addClass('minus');

                    $(tag).parent().parent().children('.subPoint').each(function (i){
                        $(this).fadeIn(0);
                    });
                }
                }");

JSInPages("function toggleSpoiler(tag){
                $(tag).parent().children('.spoiler').fadeIn(0);
                $(tag).remove();
                }");
ob_start();
?>
<div class="point bestsellerPoint">
    <div class="topPoint">
        <div class="togglePoint plus" onclick="togglePoint(this);">
        </div>
        <div class="namePointRed">
            Лидеры продаж
        </div>
    </div>
    <ul class="product" style="display: none;">
        <?php
        foreach ($productsBestSellers as $product['id'] => $product) {
            echo "<li>";


            include_partial("product/productBooklet", array(
                'sf_cache_key' => $product['id'],
                'product' => $product,
                'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
                'photo' => $photosAll[$product['id']]
                    )
            );

            echo "</li>";
        }
        ?>
    </ul>
    <a href="<?php echo url_for('@category_bestsellers') ?>">
        <div class="bottomPoint">

            <div class="arrowRight">
            </div>
            <div class="textPoint">
                Все товары - Лидеры продаж
            </div>
        </div>
    </a>
</div><?php
$bestsellerPoint = ob_get_contents();
ob_clean();
$matches['{bestsellerPoint}'] = $bestsellerPoint;
$slidersBlock=$slidersBlockMO='';
if (sizeof($sliderMain)>0){
  $slidersBlock =//print_r($sliderMain, true).
    '<div class="slide-gallery" id="gallery-mainPage">';
  foreach ($sliderMain as $key => $value) {
    $slidersBlock.=
      '<a href="'.($value['href']!='' ? $value['href'] : 'javascript:void(1)').'">'.
        '<img alt="'.$value['alt'].'"  src="/uploads/banners/'.$value['src'].'" style="width: 100%;" />'.
      '</a>';
  }
  $slidersBlock.='</div>';
}
if (sizeof($sliderMainMO)>0){
  $slidersBlockMO =
    '<div class="slide-gallery" id="gallery-mainPage">';
  foreach ($sliderMainMO as $key => $value) {
    $slidersBlockMO.=
      '<a href="'.($value['href']!='' ? $value['href'] : 'javascript:void(1)').'">'.
        '<img alt="'.$value['alt'].'"  src="/uploads/banners/'.$value['src'].'" style="width: 100%;" />'.
      '</a>';
  }
  $slidersBlockMO.='</div>';
}
$matches['{MainSlider}'] = $slidersBlock;
$matches['{MainSliderMO}'] = $slidersBlockMO;

if (count($productsNewProducts) > 0):
    ?>
    <div class="point newProductsPoint">
        <div class="topPoint">
            <div class="togglePoint plus" onclick="togglePoint(this);">
            </div>
            <div class="namePointRed">
                Новинки
            </div>
        </div>
        <ul class="product" style="display: none;">
            <?php
            foreach ($productsNewProducts as $product['id'] => $product) {
                echo "<li>";


                include_partial("product/productBooklet", array(
                    'sf_cache_key' => $product['id'],
                    'product' => $product,
                    'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
                    'photo' => $photosAll[$product['id']]
                        )
                );
                echo "</li>";
            }
            ?>
        </ul>
        <a href="<?php echo url_for('@category_newproduct') ?>">
            <div class="bottomPoint">

                <div class="arrowRight">
                </div>
                <div class="textPoint">
                    Все товары - Новинки
                </div>
            </div>
        </a>
    </div>
    <?php
endif;

$newProductsPoint = ob_get_contents();
ob_clean();
$matches['{newProductsPoint}'] = $newProductsPoint;
?>
<div class="point bonuspayPoint">
    <div class="topPoint">
        <div class="togglePoint plus" onclick="togglePoint(this);">
        </div>
        <div class="namePointRed">
            Управляй ценой!
        </div>
    </div>
    <div class="description" style="display: none;">Бонусные скидки от 50 до 100%</div>
    <ul class="product" style="display: none;">
        <?php
        foreach ($productsBonuspay as $product['id'] => $product) {
            echo "<li>";


            include_partial("product/productBooklet", array(
                'sf_cache_key' => $product['id'],
                'product' => $product,
                'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
                'photo' => $photosAll[$product['id']]
                    )
            );
            echo "</li>";
        }
        ?>
    </ul>
    <a href="<?php echo url_for('@category?slug=upravlyai-cenoi') ?>">
        <div class="bottomPoint">

            <div class="arrowRight">
            </div>
            <div class="textPoint">
                Все товары - Управляй ценой!
            </div>
        </div>
    </a>
</div>
<?php
$bonuspayPoint = ob_get_contents();
ob_clean();
$matches['{bonuspayPoint}'] = $bonuspayPoint;
?>
<div class="point bestpricePoint">
    <div class="topPoint">
        <div class="togglePoint plus" onclick="togglePoint(this);">
        </div>
        <div class="namePointRed">
            Лучшая цена
        </div>
    </div>
    <div class="description" style="display: none;">Самые низкие цены в Рунете</div>
    <ul class="product" style="display: none;">
        <?php
        foreach ($productsBestprice as $product['id'] => $product) {
            echo "<li>";

            include_partial("product/productBooklet", array(
                'sf_cache_key' => $product['id'],
                'product' => $product,
                'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
                'photo' => $photosAll[$product['id']]
                    )
            );
            echo "</li>";
        }
        ?>
    </ul>
    <a href="<?php echo url_for('@category_bestprice') ?>">
        <div class="bottomPoint">

            <div class="arrowRight">
            </div>
            <div class="textPoint">
                Все товары - Лучшая цена
            </div>
        </div>
    </a>
</div>
<?php
$bestpricePoint = ob_get_contents();
ob_clean();
$matches['{bestpricePoint}'] = $bestpricePoint;
?>
<div class="point videosPoint">
    <div class="topPoint">
        <div class="togglePoint minus" onclick="togglePoint(this);">
        </div>
        <div class="namePointRed">
            Он и Она | TUBE
        </div>
    </div>
    <ul class="videos">
        <?php
        foreach ($videos as $video['id'] => $video) {
            echo "<li>";

            include_partial("video/videoBooklet", array(
                'sf_cache_key' => $video['id'],
                'video' => $video
                    )
            );
            echo "</li>";
        }
        ?>
    </ul>
    <a href="<?php echo url_for('@videos') ?>">
        <div class="bottomPoint">

            <div class="arrowRight">
            </div>
            <div class="textPoint">
                Все видео
            </div>
        </div>
    </a>
</div>
<?php
$videosPoint = ob_get_contents();
ob_clean();
$matches['{videosPoint}'] = $videosPoint;



ob_end_clean();
?>


<div id="mainPage">

    <?php
    if (sfContext::getInstance()->getUser()->getAttribute('regMO')) {
        $resultPage = str_replace(array_keys($matches), array_values($matches), ($mainPage['content_mo_mobile']!=""?$mainPage['content_mo_mobile']:$mainPage['content_mobile']));
    } else {
        $resultPage = str_replace(array_keys($matches), array_values($matches), $mainPage['content_mobile']);
    }

    echo $resultPage;
    ?>
</div>
