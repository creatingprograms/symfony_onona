<?php

class dopFuncPage {

    static function is_email($email) {
        return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
    }

    public function variableReplace() {
        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание списка переменных');

        $variable = array(
          "{blockMailRf}", "{shopsMapRf}", "{shopsMapMO}", "{shopsMap}", "{shopsList}", "{shopsListPickpoint}",
          "{shopsListOnona}", "{form-regvideo}", "{form-treningi}", "{form-vopros-seksologu}", "{form-creative_vacancy}",
          "{form-kons-seksologu}", "{commentsShopBlock5}", "{commentsShopBlock4}", "{commentsBlock5}",
          "{commentsBlock4}", "{bannersBlock}", "{action}", "{article}", "{newsBlock}", "{MainPageProduct}",
          "{MainPageTube}", "{MainPagePodpis}", "{buttonsLastNext}", "{russianPostList}","{pickpointList}",
          "{MainSlider}", "{MainSliderMO}", "{shopsMoscow}", "{shopsRostov}", "{shopsSpb}", "{shopsListMoscow}",
          "{shopsListPictureMoscow}", "{shopsListPictureSpb}", "{shopsListPictureKrym}", "{shopsListPictureRostov}",
          "{shopsListPictureKrasnodar}"
        );

        $timer->addTime();

        $timerVars = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных');

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $blockMailRf');
        if (substr_count($this->page->getContent(), "{blockMailRf}") > 0)
            $blockMailRf = PageTable::getInstance()->findOneById(245)->getContent();
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMoscow');
        if (substr_count($this->page->getContent(), "{shopsMoscow}") > 0)
          $shopsMoscow = $this->getComponent("page", "sliderShops", array(
            'sf_cache_key' => "5-" . rand(1, 3),
              'ul_class' => 'shop-list shops swiper-wrapper',
              'li_class' => 'swiper-slide',
              'city_id' => [3],
              'is_onmain' => true,
            )
          );
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsListMoscow');
        if (substr_count($this->page->getContent(), "{shopsListMoscow}") > 0)
          $shopsListMoscow = $this->getComponent("page", "sliderShops", array(
            'sf_cache_key' => "6-" . rand(1, 3),
              'ul_class' => 'address-list',
              'li_class' => '',
              'city_id' => [3, 5, 126, 144, 59],/*Москва, Химки, Балашиха, Солнечногорск, Лобня*/
              'is_list' => true,
            )
          );
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsListMoscow');
        if (substr_count($this->page->getContent(), "{shopsListPictureMoscow}") > 0)
          $shopsListPictureMoscow = $this->getComponent("page", "sliderShops", array(
            'sf_cache_key' => "6-" . rand(1, 3),
              'ul_class' => 'shop-list',
              'li_class' => '',
              'city_id' => [3, 5, 126, 144, 59],
            )
          );
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsListPictureKrym');
        if (substr_count($this->page->getContent(), "{shopsListPictureKrym}") > 0)
          $shopsListPictureKrym = $this->getComponent("page", "sliderShops", array(
            'sf_cache_key' => "shopsListPictureKrym",
              'ul_class' => 'shop-list',
              'li_class' => '',
              'city_id' => [192, 221, 218],
            )
          );
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: "{shopsListPictureRostov}"');
        if (substr_count($this->page->getContent(), "{shopsListPictureRostov}") > 0)
          $shopsListPictureRostov = $this->getComponent("page", "sliderShops", array(
            'sf_cache_key' => "shopsListPictureRostov",
              'ul_class' => 'shop-list',
              'li_class' => '',
              'city_id' => [17],
            )
          );
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: "{shopsListPictureKrasnodar}"');
        if (substr_count($this->page->getContent(), "{shopsListPictureKrasnodar}") > 0)
          $shopsListPictureKrasnodar = $this->getComponent("page", "sliderShops", array(
            'sf_cache_key' => "{shopsListPictureKrasnodar}",
              'ul_class' => 'shop-list krasnodar',
              'li_class' => '',
              'city_id' => [11],
            )
          );
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: shopsListPictureSpb');
        if (substr_count($this->page->getContent(), "{shopsListPictureSpb}") > 0)
          $shopsListPictureSpb = $this->getComponent("page", "sliderShops", array(
            'sf_cache_key' => "6-" . rand(1, 3),
              'ul_class' => 'shop-list',
              'li_class' => '',
              'city_id' => [4]
            )
          );
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsRostov');
        if (substr_count($this->page->getContent(), "{shopsRostov}") > 0)
          $shopsRostov = $this->getComponent("page", "sliderShops", array(
            'sf_cache_key' => "5-" . rand(1, 3),
              'ul_class' => 'shop-list shops swiper-wrapper',
              'li_class' => 'swiper-slide',
              'city_id' => [17],
              'is_onmain' => true,
            )
          );
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsSpb');
        if (substr_count($this->page->getContent(), "{shopsSpb}") > 0)
          $shopsSpb = $this->getComponent("page", "sliderShops", array(
            'sf_cache_key' => "5-" . rand(1, 3),
              'ul_class' => 'shop-list shops swiper-wrapper',
              'li_class' => 'swiper-slide',
              'city_id' => [4],
              'is_onmain' => true,
            )
          );
        $timer->addTime();


        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMapRf');

        //$shopsMapRf=shopsList::getShopsMapsRf();
        if (substr_count($this->page->getContent(), "{shopsMapRf}") > 0)
            $shopsMapRf = $this->getComponent("page", "shopsMapsRf");
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMapMO');
        //$shopsMapMO=shopsList::getShopsMapsMO();
        if (substr_count($this->page->getContent(), "{shopsMapsMO}") > 0)
            $shopsMapMO = $this->getComponent("page", "shopsMapsMO");
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMap');
        if (substr_count($this->page->getContent(), "{shopsMap}") > 0)
            $shopsMap = shopsList::getShopsMaps($this->page->getCity());
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsList');
        if (substr_count($this->page->getContent(), "{shopsList}") > 0)
            $shopsList = shopsList::getShopsList($this->page->getCity());
        $timer->addTime();


        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsListPickpoint');
        if (substr_count($this->page->getContent(), "{shopsListPickpoint}") > 0)
            $shopsListPickpoint = shopsList::getShopsListPickpoint($this->page->getCity());
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsListOnona');
        if (substr_count($this->page->getContent(), "{shopsListOnona}") > 0)
            $shopsListOnona = shopsList::getShopsListOnona($this->page->getCity());
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $formRegvideo');
        if (substr_count($this->page->getContent(), "{form-regvideo}") > 0)
            $formRegvideo = dopFormPage::getFormRegformvideo($this->errorCap);
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $formTreningi');
        if (substr_count($this->page->getContent(), "{form-treningi}") > 0)
            $formTreningi = dopFormPage::getFormTreningi($this->errorCap);
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $formVoprosSeksologu');
        $formVoprosSeksologu = dopFormPage::getFormSexolog($this->errorCap);
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $formCreativeVacancy');
        $formCreativeVacancy = dopFormPage::getFormVacancy($this->errorCap);
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $formKonsSeksologu');
        $formKonsSeksologu = dopFormPage::getFormKonsultSexolog($this->errorCap);
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $commentsShopBlock5');
        //$commentsShopBlock5 = dopBlockPage::getBlockCommentsShop(5);
        if (substr_count($this->page->getContent(), "{commentsShopBlock5}") > 0)
            $commentsShopBlock5 = $this->getComponent("page", "blockCommentsShop", array('sf_cache_key' => "5-" . rand(1, 3), 'num' => 5));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $commentsShopBlock4');
        //$commentsShopBlock5 = dopBlockPage::getBlockCommentsShop(5);
        if (substr_count($this->page->getContent(), "{commentsShopBlock4}") > 0)
            $commentsShopBlock4 = $this->getComponent("page", "blockCommentsShop", array('sf_cache_key' => "4-" . rand(1, 3), 'num' => 3));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $commentsBlock5');
        //$commentsBlock5=dopBlockPage::getBlockComments(5);
        if (substr_count($this->page->getContent(), "{commentsBlock5}") > 0)
            $commentsBlock5 = $this->getComponent("page", "blockComments", array('sf_cache_key' => "5-" . rand(1, 3), 'num' => 5));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $commentsBlock4');
        //$commentsBlock4 = dopBlockPage::getBlockComments(4);
        if (substr_count($this->page->getContent(), "{commentsBlock4}") > 0)
            $commentsBlock4 = $this->getComponent("page", "blockComments", array('sf_cache_key' => "4-" . rand(1, 3), 'num' => 3));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $bannersBlock');
        //$bannersBlock = dopBlockPage::getBlockBanners();
        if (substr_count($this->page->getContent(), "{bannersBlock}") > 0)
            $bannersBlock = $this->getComponent("page", "blockBanners", array('sf_cache_key' => rand(1, 3)));
        $timer->addTime();


        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $action');
        //$action=dopBlockPage::getBlockActionProduct();
        if (substr_count($this->page->getContent(), "{action}") > 0)
            $action = $this->getComponent("page", "blockActionProduct");
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $article');
        //$article = dopBlockPage::getBlockArticle(3);
        if (substr_count($this->page->getContent(), "{article}") > 0)
            $article = $this->getComponent("page", "blockArticle", array('sf_cache_key' => "3-" . rand(1, 3), 'num' => 3));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $newsBlock');
        if (substr_count($this->page->getContent(), "{newsBlock}") > 0)
            $newsBlock = dopBlockPage::getBlockNews($this->page);
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $MainPageProduct');
        if (substr_count($this->page->getContent(), "{MainPageProduct}") > 0)
            $MainPageProduct = $this->getComponent("page", "mainPageProduct", array('sf_cache_key' => "5-" . rand(1, 3)));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $MainPageTube');
        if (substr_count($this->page->getContent(), "{MainPageTube}") > 0)
            $MainPageTube = $this->getComponent("page", "mainPageTube", array('sf_cache_key' => "5-" . rand(1, 3)));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $buttonsLastNext');
        if (substr_count($this->page->getContent(), "{buttonsLastNext}") > 0)
            $buttonsLastNext = '
            <a href="#" class="prev"></a>
            <a href="#" class="next"></a>';
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $MainPagePodpis');
        if (substr_count($this->page->getContent(), "{MainPagePodpis}") > 0)
            $MainPagePodpis = $this->getComponent("page", "mainPagePodpis", array('sf_cache_key' => "5-" . rand(1, 3) . $this->getUser()->isAuthenticated()));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $russianPostList');
        if (substr_count($this->page->getContent(), "{russianPostList}") > 0)
            $russianPostList = $this->getComponent("page", "russianPostList");
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $pickpointList');
        if (substr_count($this->page->getContent(), "{pickpointList}") > 0)
            $pickpointList = $this->getComponent("page", "pickpointList");
        $timer->addTime();
        $timerVars->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $MainSlider');
        //$bannersBlock = dopBlockPage::getBlockBanners();
        if (substr_count($this->page->getContent(), "{MainSlider}") > 0)
        $mainSlider = $this->getComponent("page", "blockSliders", array('sf_cache_key' => rand(1, 3)));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $MainSliderMO');
        //$bannersBlock = dopBlockPage::getBlockBanners();
        if (substr_count($this->page->getContent().$this->page->getContentMo(), "{MainSliderMO}") > 0)
        $mainSliderMO = $this->getComponent("page", "blockSlidersMO", array('sf_cache_key' => rand(1, 3)));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Создание массива значений переменных');
        $resultVariable = array(
          $blockMailRf, $shopsMapRf, $shopsMapMO, $shopsMap, $shopsList, $shopsListPickpoint, $shopsListOnona,
          $formRegvideo, $formTreningi, $formVoprosSeksologu, $formCreativeVacancy, $formKonsSeksologu,
          $commentsShopBlock5, $commentsShopBlock4, $commentsBlock5, $commentsBlock4, $bannersBlock, $action,
          $article, $newsBlock, $MainPageProduct, $MainPageTube, $MainPagePodpis, $buttonsLastNext,
          $russianPostList,$pickpointList, $mainSlider, $mainSliderMO, $shopsMoscow, $shopsRostov, $shopsSpb,
          $shopsListMoscow, $shopsListPictureMoscow, $shopsListPictureSpb, $shopsListPictureKrym, $shopsListPictureRostov,
          $shopsListPictureKrasnodar
        );

        $timer->addTime();


        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Замена переменных');
        $this->page->setContent(str_replace($variable, $resultVariable, $this->page->getContent()));
        $this->page->setContentMo(str_replace($variable, $resultVariable, $this->page->getContentMo()));
        $timer->addTime();
    }

}

?>
