<?php

class dopFuncPage {

  public function variableReplace() {
    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание списка переменных');
    $variable = array(
      "{MainSlider}", "{MainSliderMO}", "{PopularItems}",  "{NewItems}",  "{RecommendItems}", "{shopsTextMoscow}",
      "{shopsImagesMoscow}", "{bonusAddAll}", "{shopsListPictureSpb}", "{shopsListPictureKrym}", "{shopsListPictureRostov}",
      "{shopsListPictureKrasnodar}", "{shopsMapRf}", "{pickpointList}", "{shopsListPickpoint}", "{shopsMap}",
      "{russianPostList}", "{dlhLogistic}", "{form-vopros-seksologu}", "{ShopsSlider}", "{HitsItems}", '{main-icons}',
      '{videoOrder}', "{shopsListPictureKazan}", "{DopinfoActionSlider}", "{Advantages}", '{MainCatalog}', '{banner-main-1}',
      '{banner-main-2}', "{SaleSlider}", "{SliderActions}", "{LastReviews}", "{ProductsReviews}", "{FormServiceGuarant}", "{rrMainBlock}",
      "{PopularBrands}", "{MainSlider-1}", "{MainSlider-2}", "{MainSlider-3}", "{MainBanner-1}", "{MainBanner-2}",
      "{TopNews}", "{TopArticles}", "{TopReviews}", "{shopsTextMoscowSlider}", "{shopsImagesMoscowSlider}", "{shopsImagesMoscowMobile}",
      "{MainSlider-1-mo}", "{IP-adress}", "{sdekMap}", "{postMap}", "{sberMap}", "{viaMap}", "{bonusForStart}"
    );

    $unionContent = $this->page->getContent().$this->page->getContentMo();

    if (substr_count($unionContent, "{TopNews}") > 0)
      $TopNews = $this->getComponent("page", "topNews",
        array(
          'sf_cache_key' => 'main-news',
          'catalog' =>  'main',
        )
      );

    if (substr_count($unionContent, "{IP-adress}") > 0) $IPAddress = '<strong>'.$_SERVER['REMOTE_ADDR'].'</strong>';

    if (substr_count($unionContent, "{bonusForStart}") > 0)
      $bonusForStart = csSettings::get('register_bonus_add');

    if (substr_count($unionContent, "{TopArticles}") > 0)
      $TopArticles = $this->getComponent("article", "top",
        array(
          'sf_cache_key' => 'main-sexopedia',
          'catalog' =>  'main',
        )
      );

    if (substr_count($unionContent, "{TopReviews}") > 0)
      $TopReviews = $this->getComponent("page", "itemsReviews",
        array(
          'sf_cache_key' =>'main-itemsReviews',
          'catalog' => 'main',
        )
      );

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: ${PopularBrands}');
    if (substr_count($unionContent, "{PopularBrands}") > 0){
      // if(isset($_GET['ildebug']))
        $popularBrands = $this->getComponent("category", "popularBrands", array('sf_cache_key' => '{PopularBrands}', 'type'=>'main'));
      // else
      //   $popularBrands = '';
    }
    $timer->addTime();
    if (substr_count($unionContent, "{MainBanner-1}") > 0)
      $MainBanner1 = $this->getComponent("page", "bannerNew", array(
        'type' => 'Двойной Главная',
        'variant' => "double",
        'sf_cache_key' => 'MainBanner-1',
      ));
    if (substr_count($unionContent, "{MainBanner-2}") > 0)
      $MainBanner2 = $this->getComponent("page", "bannerNew", array(
        'type' => 'Главная новый 2',
        'variant' => "double",
        'sf_cache_key' => 'MainBanner-2',
      ));

    if (substr_count($unionContent, "{bonusAddAll}") > 0){
      $bonusAddAll = number_format(csSettings::get('all_bonus_add') + (time() - mktime(0, 0, 0)) * 2, 0, ',', ' ');
    }
    if (substr_count($unionContent, "{FormServiceGuarant}") > 0){
      $FormServiceGuarant = $this->getComponent("page", "subpage", array(
        'page'=>'form-service-guarant',
      ));
    }
    if (substr_count($unionContent, "{main-icons}") > 0){
      $mainIcons = $this->getComponent("page", "subpage", array(
        'page'=>'main-icons',
      ));
    }
    if (substr_count($unionContent, "{MainCatalog}") > 0){
      $MainCatalog = $this->getComponent("category", "maincatalog", array(
        'type' => 'new'
        // 'page'=>'main-icons',
      ));
    }
    if (substr_count($unionContent, "{videoOrder}") > 0){
      $videoOrder = $this->getComponent("page", "subpage", array(
        'page'=>'videoorder-form',
      ));
    }
    if (substr_count($unionContent, "{Advantages}") > 0){
      $Advantages = $this->getComponent("page", "subpage", array(
        'page'=>'advantages',
      ));
    }

    $timer->addTime();
    $timer = sfTimerManager::getTimer('Action: Обработка переменных: {rrMainBlock}');
    if (substr_count($unionContent, "{rrMainBlock}") > 0)
      $rrMainBlock = $this->getComponent("page", "rrMainBlock", array(
        'sf_cache_key' => 'rrMainBlock',
      ));
    $timer->addTime();
    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Форма вопрос сексологу');
    if (substr_count($unionContent, "{form-vopros-seksologu}") > 0)
      $formVoprosSeksologu = $this->getComponent("page", "formVoprosSeksologu", array(
        'sf_cache_key' => 'formVoprosSeksologu',
      ));
    $timer->addTime();

    if (substr_count($unionContent, "{banner-main-1}") > 0)
      $bannerMain1 = $this->getComponent("page", "banner", array(
        'type' => 'Главная 1',
        'sf_cache_key' => 'banner-main-1',
      ));
    if (substr_count($unionContent, "{banner-main-2}") > 0)
      $bannerMain2 = $this->getComponent("page", "banner", array(
        'type' => 'Главная 2',
        'sf_cache_key' => 'banner-main-2',
      ));
    if (substr_count($unionContent, "{ProductsReviews}") > 0)
      $ProductsReviews = $this->getComponent("page", "productsReviews", array(
        'sf_cache_key' => 'banner-main-2',
      ));

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: {LastReviews}');
    if (substr_count($unionContent, "{LastReviews}") > 0)
      $LastReviews = $this->getComponent("page", "lastReviews", array(
        'sf_cache_key' => 'lastReviews'.date('d.m.Y'),
      ));
    $timer->addTime();
    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsTextMoscow');
    if (substr_count($unionContent, "{shopsTextMoscow}") > 0)
      $shopsTextMoscow = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => '$shopsTextMoscow',
        'city_id' => ILTools::getMoscowCodes(),/*Москва, Химки, Балашиха, Солнечногорск, Лобня*/
        'limit' => false,
        'type' => 'text',
        'is_krasnodar' => false,
      ));
    $timer->addTime();
    if (substr_count($unionContent, "{shopsTextMoscowSlider}") > 0)
      $shopsTextMoscowSlider = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => '$shopsTextMoscow',
        'city_id' => ILTools::getMoscowCodes(),/*Москва, Химки, Балашиха, Солнечногорск, Лобня*/
        'limit' => false,
        'type' => 'side',
        'is_krasnodar' => false,
      ));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsImagesMoscow');
    if (substr_count($unionContent, "{shopsImagesMoscow}") > 0)
      $shopsImagesMoscow = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => '$shopsImagesMoscow',
        'city_id' => ILTools::getMoscowCodes(),/*Москва, Химки, Балашиха, Солнечногорск, Лобня*/
        'limit' => 9,
        'page' => 0,
        'type' => 'images',
        'is_krasnodar' => false,
      ));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsImagesMoscowSlider');
    if (substr_count($unionContent, "{shopsImagesMoscowSlider}") > 0)
      $shopsImagesMoscowSlider = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => '$shopsImagesMoscowSlider',
        'city_id' => ILTools::getMoscowCodes(),/*Москва, Химки, Балашиха, Солнечногорск, Лобня*/
        // 'limit' => 9,
        'page' => 0,
        'type' => 'images_slider',
        'is_krasnodar' => false,
      ));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsImagesMoscowMobile');
    if (substr_count($unionContent, "{shopsImagesMoscowMobile}") > 0)
      $shopsImagesMoscowMobile = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => '$shopsImagesMoscowSlider',
        'city_id' => ILTools::getMoscowCodes(),/*Москва, Химки, Балашиха, Солнечногорск, Лобня*/
        // 'limit' => 9,
        'page' => 0,
        'type' => 'mobile',
        'is_krasnodar' => false,
      ));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: shopsListPictureKazan');
    if (substr_count($unionContent, "{shopsListPictureKazan}") > 0)
      $shopsListPictureKaz = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => 'shopsListPictureKazan',
        'city_id' => [10, 29, 521],/*Казань, Чебоксары, Нижнекамск*/
        'limit' => 9,
        'page' => 0,
        'type' => 'images',
        'is_krasnodar' => false,
      ));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: shopsListPictureSpb');
    if (substr_count($unionContent, "{shopsListPictureSpb}") > 0)
      $shopsListPictureSpb = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => '$shopsListPictureSpb',
        'city_id' => [4],/*СПБ*/
        'limit' => 9,
        'page' => 0,
        'type' => 'images',
        'is_krasnodar' => false,
      ));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: {shopsListPictureKrasnodar}');
    if (substr_count($unionContent, "{shopsListPictureKrasnodar}") > 0)
      $shopsListPictureKrasnodar = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => 'shopsListPictureKrasnodar',
        'city_id' => [11], /*Краснодар*/
        'limit' => 9,
        'page' => 0,
        'type' => 'images',
        'is_krasnodar' => false,
      ));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: shopsListPictureKrym');
    if (substr_count($unionContent, "{shopsListPictureKrym}") > 0)
      $shopsListPictureKrym = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => 'shopsListPictureKrym',
        'city_id' => [192, 221, 218], /*Крым*/
        'limit' => 9,
        'page' => 0,
        'type' => 'images',
        'is_krasnodar' => false,
      ));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: {shopsListPictureRostov}');
    if (substr_count($unionContent, "{shopsListPictureRostov}") > 0)
      $shopsListPictureRostov = $this->getComponent("page", "shopsList", array(
        'sf_cache_key' => '{shopsListPictureRostov}',
        'city_id' => [17], /*Ростов*/
        'limit' => 9,
        'page' => 0,
        'type' => 'images',
        'is_krasnodar' => false,
      ));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $SliderActions');
    if (substr_count($unionContent, "{SliderActions}") > 0)
      $sliderActions = $this->getComponent("page", "sliderActions", array('sf_cache_key' => 'slider-actions'));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $MainSlider');
    if (substr_count($unionContent, "{MainSlider}") > 0)
      $mainSlider = $this->getComponent("page", "blockSliders", array('sf_cache_key' => 'slider', 'is_onlymoscow' => false, 'positionpage'=>'main_page_new', 'half_size' => true));
    if (substr_count($unionContent, "{MainSlider-1}") > 0)
      $mainSlider1 = $this->getComponent("page", "blockSliders", array('sf_cache_key' => 'slider-main-1', 'positionpage'=>'main_new_1', 'isNew' => true));
    if (substr_count($unionContent, "{MainSlider-1-mo}") > 0)
      $mainSlider1MO = $this->getComponent("page", "blockSliders", array('sf_cache_key' => 'slider-main-1-mo', 'positionpage'=>'main_new_1', 'isNew' => true, 'moscow' => true));
    if (substr_count($unionContent, "{MainSlider-2}") > 0)
      $mainSlider2 = $this->getComponent("page", "blockSliders", array('sf_cache_key' => 'slider-main-2', 'positionpage'=>'main_new_2', 'isNew' => true));
    if (substr_count($unionContent, "{MainSlider-3}") > 0)
      $mainSlider3 = $this->getComponent("page", "blockSliders", array('sf_cache_key' => 'slider-main-3', 'positionpage'=>'main_new_3', 'isNew' => true));
    $timer->addTime();
    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $ShopsSlider');
    if (substr_count($unionContent, "{ShopsSlider}") > 0)
      $ShopsSlider = $this->getComponent("page", "blockSliders", array('sf_cache_key' => 'slider-shops', 'is_onlymoscow' => false, 'positionpage'=>'magazins_in_moscow'));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $MainSliderMO');
    if (substr_count($unionContent, "{MainSliderMO}") > 0)
      $mainSliderMO = $this->getComponent("page", "blockSliders", array('sf_cache_key' => 'slider-mo', 'is_onlymoscow' => true, 'positionpage'=>'main_page', 'half_size' => true));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $popularItems');
    if (substr_count($unionContent, "{HitsItems}") > 0)
      $HitsItems = $this->getComponent("category", "sliderItems", array('sf_cache_key' => 'HitsItems', 'type'=>'hits'));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $popularItems');
    if (substr_count($unionContent, "{PopularItems}") > 0)
      $popularItems = $this->getComponent("category", "sliderItems", array('sf_cache_key' => 'PopularItems', 'type'=>'popular'));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $dopinfoItems');
    if (substr_count($unionContent, "{DopinfoActionSlider}") > 0)
      $dopinfoItems = $this->getComponent("category", "sliderItems", array('type'=>'dopinfoaction'));

    $timer->addTime();
    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $"{SaleSlider}"');
    if (substr_count($unionContent, "{SaleSlider}") > 0)
      $saleSlider = $this->getComponent("category", "sliderItems", array('type'=>'sale'));

    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $newItems');
    if (substr_count($unionContent, "{NewItems}") > 0)
      $newItems = $this->getComponent("category", "sliderItems", array('sf_cache_key' => 'NewItems', 'type'=>'new'));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $recommendItems');
    if (substr_count($unionContent, "{RecommendItems}") > 0)
      $recommendItems = $this->getComponent("category", "sliderItems", array('sf_cache_key' => 'RecommendItems', 'type'=>'recommend'));
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsListPickpoint');
    if (substr_count($unionContent, "{shopsListPickpoint}") > 0)
        $shopsListPickpoint = shopsList::getShopsListPickpoint($this->page->getCity());
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMap');
    if (substr_count($unionContent, "{shopsMap}") > 0)
        $shopsMap = shopsList::getShopsMaps($this->page->getCity());
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $pickpointList');
    if (substr_count($unionContent, "{pickpointList}") > 0)
        $pickpointList = $this->getComponent("page", "pickpointList");
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $dlhLogistic');
    if (substr_count($unionContent, "{dlhLogistic}") > 0)
        $dlhLogistic = $this->getComponent("page", "dlhLogistic");
    $timer->addTime();
    
    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $sdekMap');
    if (substr_count($unionContent, "{sdekMap}") > 0)
        $sdekMap = $this->getComponent("page", "sdek");
    $timer->addTime();
    
    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $postMap');
    if (substr_count($unionContent, "{postMap}") > 0)
        $postMap = $this->getComponent("page", "postMap");
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $sberMap');
    if (substr_count($unionContent, "{sberMap}") > 0)
        $sberMap = $this->getComponent("page", "sberMap");
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $viaMap');
    if (substr_count($unionContent, "{viaMap}") > 0)
        $viaMap = $this->getComponent("page", "viaMap");
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $russianPostList');
    if (substr_count($unionContent, "{russianPostList}") > 0)
        $russianPostList = $this->getComponent("page", "russianPostList");
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMapRf');

    //$shopsMapRf=shopsList::getShopsMapsRf();
    if (substr_count($unionContent, "{shopsMapRf}") > 0)
    $shopsMapRf = $this->getComponent("page", "shopsMapsRf");
    $timer->addTime();

    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Создание массива значений переменных');
    $resultVariable = array(
      $mainSlider, $mainSliderMO, $popularItems, $newItems, $recommendItems, $shopsTextMoscow,
      $shopsImagesMoscow, $bonusAddAll, $shopsListPictureSpb, $shopsListPictureKrym, $shopsListPictureRostov,
      $shopsListPictureKrasnodar, $shopsMapRf, $pickpointList, $shopsListPickpoint, $shopsMap,
      $russianPostList, $dlhLogistic, $formVoprosSeksologu, $ShopsSlider, $HitsItems, $mainIcons,
      $videoOrder, $shopsListPictureKaz, $dopinfoItems, $Advantages, $MainCatalog, $bannerMain1,
      $bannerMain2, $saleSlider, $sliderActions, $LastReviews, $ProductsReviews, $FormServiceGuarant,
      $rrMainBlock, $popularBrands, $mainSlider1, $mainSlider2, $mainSlider3, $MainBanner1, $MainBanner2,
      $TopNews, $TopArticles, $TopReviews, $shopsTextMoscowSlider, $shopsImagesMoscowSlider, $shopsImagesMoscowMobile,
      $mainSlider1MO, $IPAddress, $sdekMap, $postMap, $sberMap, $viaMap, $bonusForStart
    );

    $timer->addTime();



    $timer = sfTimerManager::getTimer('Action: Обработка переменных: Замена переменных');
    $this->page->setContent(str_replace($variable, $resultVariable, $this->page->getContent()));
    $this->page->setContentMo(str_replace($variable, $resultVariable, $this->page->getContentMo()));
    $timer->addTime();


    $mask='/\{products:([0-9,]+)\}/';
    $text = $this->page->getContent();
    $textMO = $this->page->getContentMo();

    preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
    if (sizeof($matches)) foreach ($matches as $group) {
      $productBlock = $this->getComponent('category', 'sliderItems',
        array(
          'sf_cache_key' => 'product-list'.$group[1],
          'type' => 'by-ids',
          'out_of_stock' => true,
          // 'blockName' => 'Рекомендованные товары',
          'strIds' => $group[1],
          // 'limit' => 3,
        )
      );
      $text=str_replace($group[0], $productBlock, $text);
    }

    preg_match_all($mask, $textMO, $matches, PREG_SET_ORDER, 0);
    if (sizeof($matches)) foreach ($matches as $group) {
      $productBlock = $this->getComponent('category', 'sliderItems',
        array(
          'sf_cache_key' => 'product-list'.$group[1],
          'type' => 'by-ids',
          'out_of_stock' => true,
          // 'blockName' => 'Рекомендованные товары',
          'strIds' => $group[1],
          // 'limit' => 3,
        )
      );
      $textMO=str_replace($group[0], $productBlock, $textMO);
    }
    $this->page->setContent($text);
    // die($this->page->getContent());
    $this->page->setContentMo($textMO);
  }

}

?>
