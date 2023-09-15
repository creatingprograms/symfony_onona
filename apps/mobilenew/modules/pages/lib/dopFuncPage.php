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
          "{form-kons-seksologu}", "{commentsShopBlock5}", "{commentsShopBlock4}", "{commentsBlock5}", "{commentsBlock4}",
          "{bannersBlock}", "{action}", "{article}", "{newsBlock}", "{MainPageProduct}", "{MainPageTube}",
          "{MainPagePodpis}", "{buttonsLastNext}", "{MainSlider}"
        );

        $timer->addTime();

        $timerVars = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных');

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $blockMailRf');
        if (substr_count($this->page->getContent(), "{blockMailRf}") > 0)
            $blockMailRf = PageTable::getInstance()->findOneById(245)->getContent();
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
            $commentsShopBlock4 = $this->getComponent("page", "blockCommentsShop", array('sf_cache_key' => "4-" . rand(1, 3), 'num' => 4));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $commentsBlock5');
        //$commentsBlock5=dopBlockPage::getBlockComments(5);
        if (substr_count($this->page->getContent(), "{commentsBlock5}") > 0)
            $commentsBlock5 = $this->getComponent("page", "blockComments", array('sf_cache_key' => "5-" . rand(1, 3), 'num' => 5));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $commentsBlock4');
        //$commentsBlock4 = dopBlockPage::getBlockComments(4);
        if (substr_count($this->page->getContent(), "{commentsBlock4}") > 0)
            $commentsBlock4 = $this->getComponent("page", "blockComments", array('sf_cache_key' => "4-" . rand(1, 3), 'num' => 4));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $bannersBlock');
        //$bannersBlock = dopBlockPage::getBlockBanners();
        if (substr_count($this->page->getContent(), "{bannersBlock}") > 0)
            $bannersBlock = $this->getComponent("page", "blockBanners", array('sf_cache_key' => rand(1, 3)));
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $MainSlider');
        //$bannersBlock = dopBlockPage::getBlockBanners();
        if (substr_count($this->page->getContent(), "{MainSlider}") > 0)
            $mainSlider = $this->getComponent("page", "blockSliders", array('sf_cache_key' => rand(1, 3)));
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
        $timerVars->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Создание массива значений переменных');
        $resultVariable = array(
          $blockMailRf, $shopsMapRf, $shopsMapMO, $shopsMap, $shopsList, $shopsListPickpoint, $shopsListOnona,
          $formRegvideo, $formTreningi, $formVoprosSeksologu, $formCreativeVacancy, $formKonsSeksologu,
          $commentsShopBlock5, $commentsShopBlock4, $commentsBlock5, $commentsBlock4, $bannersBlock, $action,
          $article, $newsBlock, $MainPageProduct, $MainPageTube, $MainPagePodpis, $buttonsLastNext, $mainSlider
        );

        $timer->addTime();


        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Замена переменных');
        $this->page->setContent(str_replace($variable, $resultVariable, $this->page->getContent()));
        $timer->addTime();
    }

}

?>
