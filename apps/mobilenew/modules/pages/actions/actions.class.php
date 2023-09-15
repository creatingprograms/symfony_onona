<?php

/**
 * pages actions.
 *
 * @package    test
 * @subpackage pages
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pagesActions extends sfActions {
    public function executeSitemap(sfWebRequest $request){
    /*
        $this->catalog = CatalogTable::getInstance()
        ->createQuery()
        ->where("is_public='1'")
        ->orderBy('position')
        ->execute();
    */
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute(
          "SELECT catalog.id AS catid, catalog.name AS catname, catalog.description AS catdescription, catalog.slug AS catslug, category.name, category.slug
    FROM  `catalog`
    LEFT JOIN category_catalog ON category_catalog.catalog_id = catalog.id
    LEFT JOIN category ON category_catalog.category_id = category.id
    WHERE catalog.is_public =  '1'
    AND category.is_public =  '1'
    ORDER BY catalog.position ASC , category.position ASC ");

        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $this->categorys = $result->fetchAll();
        // $this->catalog = Doctrine_Core::getTable('Catalog')->createQuery()->execute();
    }
    public function executeShow(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка страницы');
        $this->page = $q->execute("SELECT * from page as p LEFT JOIN categorypage_page cp ON cp.page_id=p.id WHERE slug=?", array($request->getParameter('slug')))->fetch(Doctrine_Core::FETCH_ASSOC);

        if (empty($this->page)) {
            $timer = sfTimerManager::getTimer('Action: find old slug page');

            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Page' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();

            $timer->addTime();
            if ($oldSlug) {
                $this->page = Doctrine_Core::getTable('Page')->findOneById($oldSlug->getDopid());
                return $this->redirect('/' . $this->page->getSlug());
            }
        }
        $this->forward404Unless($this->page);
        $timerPage->addTime();


        $this->page['content'] = $this->page['content_mobile'] != "" ? $this->page['content_mobile'] : $this->page['content'];
        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMapRf');
        if (substr_count($this->page['content'], "{shopsMapRf}") > 0) {
            $this->page['content'] = str_replace('{shopsMapRf}', $this->getComponent("pages", "shopsMapsRf"), $this->page['content']);
        }
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMapMO');
        if (substr_count($this->page['content'], "{shopsMapsMO}") > 0) {
            $this->page['content'] = str_replace('{shopsMapsMO}', $this->getComponent("pages", "shopsMapsMO"), $this->page['content']);
        }
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMap');
        if (substr_count($this->page['content'], "{shopsMap}") > 0) {
            $this->page['content'] = str_replace('{shopsMap}', shopsList::getShopsMaps($this->page['city_id']), $this->page['content']);
        }
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMap');
        if (substr_count($this->page['content'], "{russianPostList}") > 0) {
            $this->page['content'] = str_replace('{russianPostList}', $this->getComponent("pages", "russianPostList"), $this->page['content']);
        }
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка форм комментариев');
        @dopBlockPage::blockAddComment();

        $timer->addTime();

        $this->page['content_mobile'] = $this->page['content'];


        $variable = array("{blockMailRf}", "{shopsMapRf}", "{shopsMapMO}", "{shopsMap}", "{shopsList}", "{shopsListPickpoint}", "{shopsListOnona}", "{form-regvideo}", "{form-treningi}",
            "{form-vopros-seksologu}", "{form-creative_vacancy}", "{form-kons-seksologu}", "{commentsShopBlock5}", "{commentsShopBlock4}", "{commentsBlock5}", "{commentsBlock4}", "{bannersBlock}", "{action}", "{article}", "{newsBlock}", "{MainPageProduct}", "{MainPageTube}", "{MainPagePodpis}", "{buttonsLastNext}");

        $this->page['content'] = str_replace($variable, '', $this->page['content']);
    }

    public function executeAddcomm(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $this->page = $q->execute("SELECT * from page as p LEFT JOIN categorypage_page cp ON cp.page_id=p.id WHERE slug=?", array($request->getParameter('slug')))->fetch(Doctrine_Core::FETCH_ASSOC);

        $this->page['content'] = $this->page['content_mobile'] != "" ? $this->page['content_mobile'] : $this->page['content'];
        @dopBlockPage::blockAddComment();
        $this->page['content_mobile'] = $this->page['content'];
        $this->setTemplate('show');
    }

    public function executeMain(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка страницы');
        $this->mainPage = $q->execute("SELECT * from page WHERE id='385'")->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->forward404Unless($this->mainPage);
        $timerPage->addTime();

        $timerBestSellers = sfTimerManager::getTimer('Action: Товары раздела Лидеры продаж');
        if(csSettings::get("bestsellersProducts")==""){
             exec('/var/www/ononaru/data/www/symfony cc');
        }
        $this->productsBestSellers = $q->execute("SELECT id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count from product "
                        . "WHERE id in (" . csSettings::get("bestsellersProducts") . ") and is_public='1' and count>0 "
                        . "ORDER BY rand() "
                        . "LIMIT 2")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        /*
          if (implode(",", array_keys($this->productsBestSellers)) != ""):
          $timerComment = sfTimerManager::getTimer('Action: Товары раздела Лучшая цена: Загрузка комментариев');
          $this->commentsBestSellers = $q->execute("SELECT product_id,count(product_id) as countcomm from comments "
          . "WHERE is_public='1' and product_id in (" . implode(',', array_keys($this->productsBestSellers)) . ") "
          . "group by product_id")
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerComment->addTime();

          $timerPhoto = sfTimerManager::getTimer('Action: Товары раздела Лучшая цена: Загрузка фото');
          $this->photosBestSellers = $q->execute("SELECT ppa.product_id,photo.filename as filename from photo "
          . "left join product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
          . "WHERE ppa.product_id in (" . implode(",", array_keys($this->productsBestSellers)) . ") "
          . "ORDER BY photo.position DESC")
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerPhoto->addTime();
          endif; */
        $timerBestSellers->addTime();

        $this->sliderMain = $q->execute(
          "SELECT id, href, alt, src FROM sliders ".
          "WHERE is_active=1 AND is_onlymoscow=0 ".
          "ORDER BY position"
        )->fetchAll(Doctrine_Core::FETCH_UNIQUE);

        $this->sliderMainMO = $q->execute(
          "SELECT id, href, alt, src FROM sliders ".
          "WHERE is_active=1 ".
          "ORDER BY position"
        )->fetchAll(Doctrine_Core::FETCH_UNIQUE);

        $timerNewProducts = sfTimerManager::getTimer('Action: Товары раздела Новинки');
        $arrayNewProductId = explode(",", csSettings::get("optimization_newProductId"));
        if (count($arrayNewProductId) > 1) {
            $arrayRandomKey = array_rand(explode(",", csSettings::get("optimization_newProductId")), 2);
            $this->productsNewProducts = $q->execute("SELECT id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count from product "
                            . "WHERE id in (" . $arrayNewProductId[$arrayRandomKey[0]] . ", " . $arrayNewProductId[$arrayRandomKey[1]] . ") and is_public='1' and count>0 "
                            . "LIMIT 2")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            unset($arrayRandomKey);
        } else {
            $this->productsNewProducts = array();
        }
        /*
          if (implode(",", array_keys($this->productsNewProducts)) != ""):
          $timerComment = sfTimerManager::getTimer('Action: Товары раздела Новинки: Загрузка комментариев');
          $this->commentsNewProducts = $q->execute("SELECT product_id,count(product_id) as countcomm from comments "
          . "WHERE is_public='1' and product_id in (" . implode(',', array_keys($this->productsNewProducts)) . ") "
          . "group by product_id")
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerComment->addTime();

          $timerPhoto = sfTimerManager::getTimer('Action: Товары раздела Новинки: Загрузка фото');
          $this->photosNewProducts = $q->execute("SELECT ppa.product_id,photo.filename as filename from photo "
          . "left join product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
          . "WHERE ppa.product_id in (" . implode(",", array_keys($this->productsNewProducts)) . ") "
          . "ORDER BY photo.position DESC")
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerPhoto->addTime();
          endif; */
        $timerNewProducts->addTime();



        $timerBonuspayProducts = sfTimerManager::getTimer('Action: Товары раздела Управляй ценой');
        $arrayBonuspayProductId = explode(",", csSettings::get("optimization_BonuspayProductId"));
        if (count($arrayBonuspayProductId) > 1) {
            $arrayRandomKey = array_rand(explode(",", csSettings::get("optimization_BonuspayProductId")), 2);
            $this->productsBonuspay = $q->execute("SELECT id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count from product "
                            . "WHERE  id in (" . $arrayBonuspayProductId[$arrayRandomKey[0]] . ", " . $arrayBonuspayProductId[$arrayRandomKey[1]] . ") and is_public='1' and count>0 "
                            . "LIMIT 2")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            unset($arrayRandomKey);
        } else {
            $this->productsBonuspay = array();
        }
        /*
          if (implode(",", array_keys($this->productsBonuspay)) != ""):
          $timerComment = sfTimerManager::getTimer('Action: Товары раздела Управляй ценой: Загрузка комментариев');
          $this->commentsBonuspay = $q->execute("SELECT product_id,count(product_id) as countcomm from comments "
          . "WHERE is_public='1' and product_id in (" . implode(',', array_keys($this->productsBonuspay)) . ") "
          . "group by product_id")
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerComment->addTime();

          $timerPhoto = sfTimerManager::getTimer('Action: Товары раздела Управляй ценой: Загрузка фото');
          $this->photosBonuspay = $q->execute("SELECT ppa.product_id,photo.filename as filename from photo "
          . "left join product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
          . "WHERE ppa.product_id in (" . implode(",", array_keys($this->productsBonuspay)) . ") "
          . "ORDER BY photo.position DESC")
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerPhoto->addTime();
          endif; */
        $timerBonuspayProducts->addTime();



        $timerBestpriceProducts = sfTimerManager::getTimer('Action: Товары раздела Лучшая цена');
        $arrayBestpriceProductId = explode(",", csSettings::get("optimization_bestpriceProductId"));
        if (count($arrayBestpriceProductId) > 1) {
            $arrayRandomKey = array_rand(explode(",", csSettings::get("optimization_bestpriceProductId")), 2);
            $this->productsBestprice = $q->execute("SELECT id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count from product "
                            . "WHERE  id in (" . $arrayBestpriceProductId[$arrayRandomKey[0]] . ", " . $arrayBestpriceProductId[$arrayRandomKey[1]] . ") and is_public='1' and count>0 "
                            . "LIMIT 2")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            unset($arrayRandomKey);
        } else {
            $this->productsBestprice = array();
        }
        $timerBestpriceProducts->addTime();




        $allProductsId = array_merge(
                array_keys($this->productsBestSellers), array_keys($this->productsNewProducts), array_keys($this->productsBonuspay), array_keys($this->productsBestprice)
        );
        if (count($allProductsId) > 0) {
            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm from comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', $allProductsId) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename from photo "
                            . "left join product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", $allProductsId) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }


        $timerVideos = sfTimerManager::getTimer('Action: Загрузка видео');

        $this->videos = $q->execute("SELECT id, name, slug, photo, youtubelink from video "
                . "WHERE  is_publicmainpage='1' and is_public='1' and youtubelink!='' "
                . "ORDER BY rand() "
                . "LIMIT 2");
        $timerVideos->addTime();
    }

    public function fillWord(&$arr, $idx = 0) {
        static $line = array();
        static $keys;
        static $max;
        static $results;
        if ($idx == 0) {
            $keys = array_keys($arr);
            $max = count($arr);
            $results = array();
        }
        if ($idx < $max) {
            $values = $arr[$keys[$idx]];
            foreach ($values as $value) {
                array_push($line, $value);
                $this->fillWord($arr, $idx + 1);
                array_pop($line);
            }
        } else {
            $results[] = $line;
        }
        if ($idx == 0)
            return $results;
    }

    public function executeSearch(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $searchLog = new SearchLog;
        $searchLog->setText(addslashes($request->getParameter('searchString')));
        $searchLog->save();

        mb_internal_encoding('UTF-8');
        $testQuest = addslashes($request->getParameter('searchString'));
        preg_match_all('/([-a-z-A-Z-а-я-А-Я-0-9]+)/u', $testQuest, $parseText);
        foreach ($parseText[1] as $key => $word) {
            $synonyms = SynonymsTable::getInstance()->createQuery()->where('text like "%' . $word . '%"')->fetchArray();
            if ($synonyms) {
                $allSynWords[$key] = explode(",", $synonyms[0]['text']);
            } else {
                $allSynWords[$key][0] = $word;
            }
        }
        foreach ($this->fillWord($allSynWords) as $queryOption) {
            $queryOptions[] = implode(" ", $queryOption);
        }
        $queryOptions[] = $request->getParameter('searchString');

        //print_r($queryOptions);


        foreach ($queryOptions as $keyOptions => $queryOption) {
            if ($keyOptions == 0) {
                $nameOption = "(name like ?";
                $nameArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndContentOption = "(name like ? or content like ?";
                $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cOption = "(name like ? or code like ? or content like ? or id like ?";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
            } elseif ($keyOptions == (count($queryOptions) - 1)) {
                $nameOption.=" or name like ?)";
                $nameArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndContentOption.= " or name like ? or content like ?)";
                $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cOption .= " or name like ? or code like ? or content like ? or id like ?)";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
            } else {
                $nameOption.=" or name like ?";
                $nameArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndContentOption.=" or name like ? or content like ?";
                $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cOption .= " or name like ? or code like ? or content like ? or id like ?";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
                $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
            }
            if (count($queryOptions) == 1) {
                $nameOption.=")";
                $nameAndContentOption.= ")";
                $nameAndCodeAndContentAndid1cOption.=")";
            }
        }
        $this->categorys = CategoryTable::getInstance()->createQuery()->where($nameOption, $nameArrOptions)->addWhere("is_public=1")->execute();
        $this->articles = ArticleTable::getInstance()->createQuery()->where($nameAndContentOption, $nameAndContentArrOptions)->addWhere("is_public=1")->execute();



//$this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySlug(addslashes($request->getParameter('searchString')));
        $this->manufacturer = ManufacturerTable::getInstance()->createQuery()->where("slug like '%" . addslashes($request->getParameter('searchString')) . "%'")->orWhere("name like '%" . addslashes($request->getParameter('searchString')) . "%'")->fetchOne();
        if ($this->manufacturer) {
            $idFind = $this->manufacturer->getSubid();
            $manIsset = true;
        } else {
            $this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySubid(addslashes($request->getParameter('searchString')));

            if (preg_match("/^[\d]+$/", $request->getParameter('searchString'))) {
                $idFind = addslashes($request->getParameter('searchString'));
                $manIsset = true;
            }
        }
        if ($manIsset and $idFind!="") {
            $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);
            $prod = "";
            foreach ($this->dopinfoProduct as $key => $product) {
                $prod.=" id = '" . $product->get('product_id') . "'";
                if ($key < count($this->dopinfoProduct) - 1)
                    $prod.=" or";
            }
        }

        $this->collection = CollectionTable::getInstance()->createQuery()->where("slug like '%" . addslashes($request->getParameter('searchString')) . "%'")->orWhere("name like '%" . addslashes($request->getParameter('searchString')) . "%'")->fetchOne();

// $this->collection = Doctrine_Core::getTable('Collection')->findOneBySlug(addslashes($request->getParameter('searchString')));
        if ($this->collection) {
            $idFind = $this->collection->getSubid();
            $collIsset = true;
        } else {
            $this->collection = Doctrine_Core::getTable('Collection')->findOneBySubid(addslashes($request->getParameter('searchString')));
            // $idFind = addslashes($request->getParameter('searchString'));

            if (preg_match("/^[\d]+$/", $request->getParameter('searchString'))) {
                $idFind = addslashes($request->getParameter('searchString'));
                $collIsset = true;
            }
        }
        if ($collIsset and $idFind!="") {
            $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);
            foreach ($this->dopinfoProduct as $key => $product) {
                if ($prod != "")
                    $prod.=" or";
                $prod.=" id = '" . $product->get('product_id') . "'";
            }
        }
        $this->pager = new sfDoctrinePager('Product', 60);

        //$this->pager->setQuery(ProductTable::getInstance()->createQuery()->where("name like '%" . addslashes($request->getParameter('searchString')) . "%' or code like '%" . addslashes($request->getParameter('searchString')) . "%' or content like '%" . addslashes($request->getParameter('searchString')) . "%' or id1c like '%" . addslashes($request->getParameter('searchString')) . "%' " . ($prod != "" ? "or " . $prod : ""))->addWhere("is_public=1")->addWhere("generalcategory_id<>135")->orderBy("(count>0) DESC"));
        $this->pager->setQuery(ProductTable::getInstance()->createQuery()->where($nameAndCodeAndContentAndid1cOption . ($prod != "" ? " or " . $prod : ""), $nameAndCodeAndContentAndid1cArrOptions)->addWhere("is_public=1")->addWhere("generalcategory_id<>135")->orderBy("(count>0) DESC"));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        //$this->products = ProductTable::getInstance()->createQuery()->where("name like '%" . addslashes($request->getParameter('searchString')) . "%' or code like '%" . addslashes($request->getParameter('searchString')) . "%' or content like '%" . addslashes($request->getParameter('searchString')) . "%' or id1c like '%" . addslashes($request->getParameter('searchString')) . "%' " . ($prod != "" ? "or " . $prod : ""))->addWhere("is_public=1")->addWhere("generalcategory_id<>135")->orderBy("(count>0) DESC")->execute();

        $this->products = ProductTable::getInstance()->createQuery()->where($nameAndCodeAndContentAndid1cOption . ($prod != "" ? " or " . $prod : ""), $nameAndCodeAndContentAndid1cArrOptions)->addWhere("is_public=1")->addWhere("generalcategory_id<>135")->orderBy("(count>0) DESC")->execute();
        $this->pages = PageTable::getInstance()->createQuery()->where("name like ?", "%" . addslashes($request->getParameter('searchString')) . "%")->addWhere("is_public=1")->execute();
        $this->manufacturer = ManufacturerTable::getInstance()->createQuery()->where("name like ?", "%" . addslashes($request->getParameter('searchString')) . "%")->addWhere("is_public=1")->execute();


        $allProductsId = array_merge(
                $this->products->getPrimaryKeys()
        );
        if (count($allProductsId) > 0) {
            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm from comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', $allProductsId) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename from photo "
                            . "left join product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", $allProductsId) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
    }

    public function executeError404(sfWebRequest $request) {

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $e404 = new Logs404();
        $e404->setUrl('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
// print_r($_SERVER);exit;
        $e404->setUrlFrom($_SERVER["HTTP_REFERER"]);
        $e404->setIp($_SERVER['REMOTE_ADDR']);
        $e404->save();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка страницы');
        $this->page = $q->execute("SELECT * from page WHERE slug='error404'")->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->forward404Unless($this->page);
        $timerPage->addTime();
        $this->setTemplate('show');
    }

    public function executeShowRussianPost(sfWebRequest $request) {

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка страницы');
        $this->page = $q->execute("SELECT * from russian_post_city as p LEFT JOIN categorypage_page cp ON cp.page_id=p.id WHERE slug=?", array($request->getParameter('slug')))->fetch(Doctrine_Core::FETCH_ASSOC);

        if (empty($this->page)) {
            $timer = sfTimerManager::getTimer('Action: find old slug page');

            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='RussianPostCity' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();

            $timer->addTime();
            if ($oldSlug) {
                $this->page = Doctrine_Core::getTable('RussianPostCity')->findOneById($oldSlug->getDopid());
                return $this->redirect('/' . $this->page->getSlug());
            }
        }
        $this->forward404Unless($this->page);
        $timerPage->addTime();


        $this->page['content'] = $this->page['content_mobile'] != "" ? $this->page['content_mobile'] : $this->page['content'];
        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMapRf');
        if (substr_count($this->page['content'], "{shopsMapRf}") > 0) {
            $this->page['content'] = str_replace('{shopsMapRf}', $this->getComponent("pages", "shopsMapsRf"), $this->page['content']);
        }
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMapMO');
        if (substr_count($this->page['content'], "{shopsMapsMO}") > 0) {
            $this->page['content'] = str_replace('{shopsMapsMO}', $this->getComponent("pages", "shopsMapsMO"), $this->page['content']);
        }
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMap');
        if (substr_count($this->page['content'], "{shopsMap}") > 0) {
            $this->page['content'] = str_replace('{shopsMap}', shopsList::getShopsMaps($this->page['city_id']), $this->page['content']);
        }
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Обработка переменных: Задание значений переменных: $shopsMap');
        if (substr_count($this->page['content'], "{russianPostList}") > 0) {
            $this->page['content'] = str_replace('{russianPostList}', $this->getComponent("pages", "russianPostList"), $this->page['content']);
        }
        $timer->addTime();


        $this->page['content_mobile'] = $this->page['content'];


        $variable = array("{blockMailRf}", "{shopsMapRf}", "{shopsMapMO}", "{shopsMap}", "{shopsList}", "{shopsListPickpoint}", "{shopsListOnona}", "{form-regvideo}", "{form-treningi}",
            "{form-vopros-seksologu}", "{form-creative_vacancy}", "{form-kons-seksologu}", "{commentsShopBlock5}", "{commentsShopBlock4}", "{commentsBlock5}", "{commentsBlock4}", "{bannersBlock}", "{action}", "{article}", "{newsBlock}", "{MainPageProduct}", "{MainPageTube}", "{MainPagePodpis}", "{buttonsLastNext}");

        $this->page['content'] = str_replace($variable, '', $this->page['content']);
        $this->setTemplate("show");
    }

}
