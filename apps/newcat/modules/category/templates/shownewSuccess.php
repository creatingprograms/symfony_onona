<?php
$timer = sfTimerManager::getTimer('Templates: Передача переменных в главный шаблон');
mb_internal_encoding('UTF-8');

  slot('metaTitle','NEW'.end($categorys)['parent_name']);

$keywordsTemplate='{name} купить цена отзывы доставка';
$descriptionTemplate='{name}. Продажа по доступным ценам с доставкой в онлайн интим-магазине Он и Она, 18+';
if (end($categorys)['title']!=""){
  slot('metaTitle', end($categorys)['title'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
else {
  slot('metaTitle', str_replace("{name}", end($categorys)['this_name'], csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
if (end($categorys)['keywords']!=""){
  slot('metaKeywords', end($categorys)['keywords'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
else {
  slot('metaKeywords', str_replace("{name}", end($categorys)['this_name'], $keywordsTemplate));
}
if (end($categorys)['description']!=""){
  slot('metaDescription', end($categorys)['description'] . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " | Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
}
else {
  slot('metaDescription', str_replace("{name}", end($categorys)['this_name'], $descriptionTemplate));
}


  end($categorys)['this_h1'] == '' ? $gen_cat_name = end($categorys)['this_name'] : $gen_cat_name = end($categorys)['this_h1'];

/*
  if (strtolower(end($categorys)['parent_slug']) == 'strapon') {
    slot('metaTitle',$gen_cat_name.': купить по приемлемым ценам с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Огромный ассортимент интимных игрушек, белья, косметики и аксессуаров от мировых производителей. Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'analnye_igrushki') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить анальные игрушки с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой ассортимент интимных игрушек, белья, косметики и аксессуаров от мировых производителей. Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'stimulyatory-iz-metalla-stekla-i-keramiki') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить стимуляторы из металла, керамики и стекла с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор интимных игрушек, белья, косметики и аксессуаров от мировых производителей. Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'sex_kukly') {
    slot('metaTitle',$gen_cat_name.' по разумным ценам | Купить секс-куклу с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор интимных игрушек, белья, косметики и аксессуаров от мировых производителей, быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'muzhskie-masturbatory') {
    slot('metaTitle',$gen_cat_name.' по разумным ценам | Купить мужской мастурбатор с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор интимных игрушек, белья, косметики и аксессуаров от мировых производителей, быстрая доставка, выгодные цены и конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'elitnye-vibratory') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить элитный вибратор с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Огромный ассортимент интимных игрушек, белья, косметики и аксессуаров от мировых производителей. Приемлемые цены, быстрая доставка и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'falloimitatory') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить реалистичные фаллоимитаторы с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор интимных игрушек, белья, косметики и аксессуаров от мировых производителей. Быстрая доставка, выгодные цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'vibratory-vibromassazhery') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить вибраторы и вибромассажеры с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой каталог интимных игрушек, белья, косметики и аксессуаров от мировых производителей. Быстрая доставка, низкие цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'kupit_vaginalnye_shariki') {
    slot('metaTitle',$gen_cat_name.' по доступным ценам | Купить вагинальные шарики и тренажеры с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор интимных игрушек, белья, косметики и аксессуаров от мировых производителей. Все игрушки проверены на безопасность и имеют сертификаты качества. Выгодные цены, быстрая доставка и абсолютная конфиденциальность. ');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'pletki-shlepalki') {
    slot('metaTitle',$gen_cat_name.': купить по доступной цене | Плетки, шлепалки с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Выгодные цены, быстрая доставка и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'maski-klyapy') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить маски и кляпы с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор интимных игрушек, белья, косметики и аксессуаров от мировых производителей с доставкой. Приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'intimnaya-kosmetika') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить интимную косметику с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Интимные игрушки, белье, косметика и аксессуары от мировых производителей. Быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'smazki-lubrikanty') {
    slot('metaTitle',$gen_cat_name.' по выгодным ценам | Купить смазку и лубриканты с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она» по выгодным ценам. Большой выбор интимных игрушек, белья, косметики и аксессуаров от мировых производителей, быстрая доставка и абсолютная конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'duhi_s_feromonami') {
    slot('metaTitle',$gen_cat_name.': купить по доступной цене | Духи с феромонами с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Быстрая и конфиденциальная доставка, разумные цены, огромный выбор. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'eroticheskoe-bele') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить эротическое женское белье с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Огромный ассортимент, быстрая доставка, приемлемые цены и абсолютная конфиденциальность. Все товары проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'zhenskoe-eroticheskoe-bele-bolshih-razmerov') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить женское эротическое белье больших размеров с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой ассортимент интимных игрушек, белья, косметики и аксессуаров от мировых производителей. Быстрая доставка, приемлемые цены и абсолютная конфиденциальность.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'eroticheskoe-bele-muzhskoe') {
    slot('metaTitle',$gen_cat_name.' по приемлемым ценам | Купить мужское эротическое белье с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор сертифицированных интимных игрушек, белья и аксессуаров от мировых производителей. Быстрая доставка, приемлемые цены и абсолютная конфиденциальность.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'muzhskoe-eroticheskoe-bele-bolshih-razmerov') {
    slot('metaTitle',$gen_cat_name.' по выгодным ценам | Купить мужское эротическое белье больших размеров с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор интимных игрушек, белья, косметики и аксессуаров от мировых производителей. Быстрая доставка, выгодные цены, конфиденциальность, безопасность и сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'eroticheskie-aksessuary') {
    slot('metaTitle',$gen_cat_name.' по разумным ценам | Купить эротические аксессуары с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор интимных товаров от мировых производителей, быстрая доставка, приемлемые цены и конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }

  if (strtolower(end($categorys)['parent_slug']) == 'prezervativy') {
    slot('metaTitle',$gen_cat_name.' по низким ценам | Купить презервативы с анонимной доставкой | Секс-шоп «Он и Она»');
    slot('metaDescription',$gen_cat_name.' в интернет-магазине «Он и Она». Большой выбор товаров, быстрая доставка, выгодные цены и конфиденциальность. Все игрушки проверены на безопасность и имеют сертификаты качества.');
  }
  */


slot('rightBlockCategory', true);
slot('filtersCategory', end($categorys));
slot('filtersParams', serialize($filters));
slot('filtersPrice', serialize($price));
slot('filtersPage', serialize($page));
slot('filtersSortOrder', serialize($sortOrder));
slot('filtersDirection', serialize($direction));
slot('filtersCountProducts', serialize($filtersCountProducts));
slot('filtersIsStock', serialize($isStock));
if(isset($_GET['page']))
  slot('canonicalSlugCategory', end($categorys)['this_slug'], true);



$timer->addTime();
$itemscope=1;
?>
<div id="categoryShow" class="gtm-category-show" data-list="<?= stripslashes(end($categorys)['this_name'])?>">
    <script type="text/javascript">
        (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function () {
            try {
                rrApi.categoryView(<?= end($categorys)['this_id'] ?>);
            } catch (e) {
            }
        })
    </script>
    <ul class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><div><a itemprop="item"  href="/"><span itemprop="name"><meta itemprop="position" content="<?=$itemscope++?>" />Секс-шоп главная</span></a></div></li>
        <?php
        if (end($categorys)['parent_name'] != "") {
            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><div><a itemprop="item" href="' . url_for('@category_show?slug=' . end($categorys)['parent_slug']) . '"><span itemprop="name"><meta itemprop="position" content="'.$itemscope++.'" />' . end($categorys)['parent_name'] . '</span></a></div></li>';
        }
        ?>
        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><div><a itemprop="item" href="<?= explode("?", $_SERVER['REQUEST_URI'])[0] ?>"></a><span itemprop="name"><meta itemprop="position" content="<?=$itemscope++?>" /><?php echo end($categorys)['this_name'] ?></span></div></li>
    </ul>
    <?/*
    <ul class="breadcrumbs">
        <li><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/" itemprop="url"><span itemprop="title">Секс-шоп главная</span></a></div></li>
        <?php
        if (end($categorys)['parent_name'] != "") {
            echo '<li><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . url_for('@category_show?slug=' . end($categorys)['parent_slug']) . '" itemprop="url"><span itemprop="title">' . end($categorys)['parent_name'] . '</span></a></div></li>';
        }
        ?>
        <li><div itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="<?= explode("?", $_SERVER['REQUEST_URI'])[0] ?>" itemprop="url"><span itemprop="title"><?php echo end($categorys)['this_name'] ?></span></a></div></li>
    </ul>*/?>


    <h1 class="title"><?= end($categorys)['this_h1'] != "" ? stripslashes(end($categorys)['this_h1']) : stripslashes(end($categorys)['this_name']) ?></h1>
    <?/*<pre><?=print_r($catalog, true)?></pre>*/?>
    <?php
      include_component(
        'category',
        'catalogicons',
        array(
          'slug' => $sf_request->getParameter('slug'),
          'id' => $catalog['catalog_id'],
          // 'slug' => $sf_request->getParameter('slug'),
          'sf_cache_key' => $sf_request->getParameter('slug') . "-icons-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
        )
      );
    ?>
    <?
    $timer = sfTimerManager::getTimer('Templates: Вывод блока подписок');


    if ($sf_user->isAuthenticated()):
        if ($notificationCategory) {
            ?>
            <div class="NotificationCategory">
                <div style='color: #c3060e;margin-bottom: 10px;'>Вы подписаны на уведомления о новых поступлениях товаров в этом разделе. <a href="/delnotification/<?= end($categorys)['this_id'] ?>" onclick=" $('.NotificationCategory').load($(this).attr('href'));
                                return false;">Отписаться</a></div>
            </div><?
        } else {
            ?><div class="NotificationCategory">
                <div class="notification-text"><input type="checkbox" id="checkboxNotify">Я хочу подписаться на уведомления о новых поступлениях товаров в этом разделе:</div>
                <div style="float: left;" class="silverButtonAddToNotification" onClick='if (!$("#checkboxNotify").prop("checked")) {
                            return false;
                        }
                        $(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= end($categorys)['this_id'] ?>});'>Подписаться</div>

            </div><?
        }
    else:
        ?><div class="NotificationCategory">
            <div class="notification-text-not-autorized">Подпишитесь на уведомления о новых поступлениях товаров в этом разделе:</div>
            <div class="fl-right">
                <div style="float: left;"><input id="notificationMail" value="Введите свой e-mail" onClick="if ($(this).val() == 'Введите свой e-mail')
                            $(this).val('');" /></div>
                <div style="float: left;" class="silverButtonAddToNotification" onClick='$(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= end($categorys)['this_id'] ?>, emailAddNotification: $("#notificationMail").val()});'>Подписаться</div>
            </div>
        </div><?
    endif;

    $timer->addTime();
    ?>

    <div style="clear: both;"></div>
    <?
    $timer = sfTimerManager::getTimer('Templates: Вывод таблицы с набором сортировок');
    if ($sf_request->getParameter('page', 1) == 1 and $sortOrder == "actions" and sizeof($sf_request->getParameter('filters')) == 0) {
      if (substr_count(end($categorys)['this_content'], "{replaceSpoiler}")) {

            echo "<div class=\"descriptionCategory\">" . str_replace("{replaceSpoiler}", '<div class="btnSpoiler" onclick="toggleSpoiler(this);">
			<div class="Text">
				Развернуть</div>
                                </div><div class="spoiler">', end($categorys)['this_content']);
            ?></div></div>

        <script type="text/javascript">
            function toggleSpoiler(tag) {
                $(tag).parents('.descriptionCategory').find('.spoiler').fadeIn(0);
                $(tag).remove();
            }
        </script>
        <?
      }
      else {
        if(mb_strlen(strip_tags(end($categorys)['this_content']))>330){
          echo '<div class="spoiler-category">'.end($categorys)['this_content'].'</div>'.'<div class="show-more"></div>';
          ?>
          <script>
            $(document).ready(function(){
              $('.show-more').on('click', function(e){
                e.preventDefault();
                $(this).toggleClass('active');
                $(this).prev().toggleClass('active');
                console.log('.show-more click');
              });
            });
          </script>
          <?
        }
        else echo end($categorys)['this_content'];
          ?>
          <br />
          <?
      }
    }?>
    <div data-retailrocket-markup-block="5ba3a57697a52530d41bb22d" data-category-id="<?= end($categorys)['this_id'] ?>"></div>
    <?
    // echo '<pre>'.print_r(['slug' => $sf_request->getParameter('slug'), 'slot' => get_slot('category_slug')],true).'</pre>';
    include_component(
      'category',
      'catalogfilters',
      array(
        'slug' => $sf_request->getParameter('slug'),
        'slot' => get_slot('category_slug'),
        'filtersCategory'=>end($categorys),
        'filters'=>$filters,
        'isStock'=>$isStock,
        'sortOrder'=>$sortOrder,
        'direction'=>$direction,
        'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
      )
    );
?>
<?php if (is_array($filters)) { ?>
    <div class="blockCountProducts">
        Товаров найдено: <b><?= $productsCount ?></b>
    </div>
<?php } ?>
<div style="clear: both;"></div>
<?
$timer->addTime();
?>

<div id="productsShow">
    <?php
    if (count($products) > 0):
        ?>
    <ul class="product item-list">
            <?php
            $index=0;
            foreach ($products as $product['id'] => $product) {
              $gdeslonCodes[]='{ id : "'.$product['id'].'", quantity: 1, price: '.$product['price'].'}';
              $advcakeItems[]=[
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
              ];
              // '{ id : "'.$productInfo['productId'].'", quantity: '.$productInfo['quantity'].', price: '.($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']).'}';
                echo
                  '<li'.
                    ' class="gtm-list-item prodTable-' . $product['id'] . (($product['count'] == 0) ? " liProdNonCount" : ""). '"'.
                    ' data-id="'.$product['id'].'"'.
                    ' data-name="'.$product['name'].'"'.
                    ' data-price="'.$product['price'].'"'.
                    ' data-category="'.$product['cat_name'].'"'.
                    ' data-position="'.$index++.'"'.
                    // ' data-debug="'.print_r($product, true).'"'.
                  '>';
                if (!isset($childrensAll[$product['id']])) {
                    $childrensAll[$product['id']] = array();
                }

                include_partial("product/productBooklet", array(
                    'sf_cache_key' => $product['id'],
                    'products' => $products,
                    'product' => $product,
                    'childrens' => $childrensAll[$product['id']],
                    'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
                    'commentsAll' => $commentsAll,
                    'photo' => $photosAll[$product['id']],
                    'photosAll' => $photosAll,
                    'autoLoadPhoto' => false
                        )
                );

                echo "</li>";
            }
            ?>
        </ul>
        <?php
    else:
        ?><div style="padding: 20px;
             ">Нет подходящих результатов</div><?php
         endif;
         ?>
    <div style="clear: both;
         "></div>

</div>
<div id="paginationBoxPage">
    <?
    include_component('category', 'paginatorNew', array('category' => end($categorys), 'sortOrder' => $sortOrder, 'direction' => $direction, "page" => $page, "pagesCount" => $pagesCount));
    ?>
</div>
<?php if (@is_array($_POST['filters']) and @ is_array($filtersCountProducts)): ?>
    <div id="filtersCountProducts" style="display: none;"><?= json_encode($filtersCountProducts) ?></div>
<?php endif; ?>
</div>
<div class="popup-15-need"></div>
<? slot('gdeSlonCatId', 'category_id: "'.end($categorys)['this_id'].'",'); ?>
<? slot('gdeSlonCodes', 'products: [ '.implode(', '."\n", $gdeslonCodes).' ],'); ?>
<? //slot('gdeSlonCodes', 'codes='.implode(',', $gdeslonCodes)); ?>
<? slot('gdeSlonMode', 'list'); ?>
<? slot('advcake', 3); ?>
<? slot('advcake_list', [
  'products' => $advcakeItems,
  'category' => [
    'id' => end($categorys)['this_id'],
    // 'dump' => print_r(end($categorys), true),
    'name' =>end($categorys)['this_name'],
  ],
]);?>
