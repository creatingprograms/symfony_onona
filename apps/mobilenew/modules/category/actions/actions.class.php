<?php

/**
 * category actions.
 *
 * @package    test
 * @subpackage category
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class categoryActions extends sfActions {

    public function executeCatalogs(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка каталогов');
        $this->catalogs = $q->execute("SELECT id, name, description, slug FROM catalog ORDER BY position asc")->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerPage->addTime();
    }

    public function executeCatalog(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка каталогов');
        $this->categorys = $q->execute("SELECT cat.id, cat.name, cat.slug, catalog.name AS catalog_name, catalog.description AS catalog_description, catalog.title AS catalog_title, catalog.metadescription AS catalog_metadescription, catalog.keywords AS catalog_keywords, catalog.prodid_random AS catalog_prodid_random, catalog.prodid_bestprice AS catalog_prodid_bestprice "
                        . "FROM catalog "
                        . "LEFT JOIN category_catalog AS cc ON cc.catalog_id = catalog.id "
                        . "LEFT JOIN category AS cat ON cat.id = cc.category_id "
                        . "WHERE catalog.slug =  ? "
                        . "ORDER BY cat.position ASC ", array($request->getParameter('slug')))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $this->forward404Unless($this->categorys);

        $prodIdBestprice = end($this->categorys)['catalog_prodid_bestprice'];
        $prodIdRandom = end($this->categorys)['catalog_prodid_random'];
        $timerPage->addTime();

        if ($prodIdBestprice != "") {
            $timerBestpriceProducts = sfTimerManager::getTimer('Action: Товары раздела Лучшая цена');
            $this->productsBestprice = $q->execute("SELECT id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
                            . "FROM product "
                            . "WHERE  id in (" . $prodIdBestprice . ") "
                            . "ORDER BY rand() "
                            . "limit 10")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            unset($arrayRandomKey);
            $timerBestpriceProducts->addTime();
        }

        $timerRandomProducts = sfTimerManager::getTimer('Action: Случайные товары ');
        $this->productsRandom = $q->execute("SELECT id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
                        . "FROM product "
                        . "WHERE id in (" . $prodIdRandom . ")"
                        . "ORDER BY rand() "
                        . "limit 10")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        unset($arrayRandomKey);
        $timerRandomProducts->addTime();



        $allProductsId = array_merge(
                array_keys($this->productsBestprice), array_keys($this->productsRandom)
        );
        if (count($allProductsId) > 0) {
            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', $allProductsId) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                            . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", $allProductsId) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
    }

    public function executeCategory(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerCategory = sfTimerManager::getTimer('Action: Загрузка категорий');
        $this->categorys = $q->execute("SELECT children.id, children.name, children.slug, parent.id as parent_id, parent.name as parent_name , parent.slug as parent_slug, cat.id as this_id, cat.name as this_name, cat.content as this_content , cat.slug as this_slug, cat.filtersnew as filters, cat.minprice as minPrice, cat.maxprice as maxPrice  "
                        . "FROM category as cat "
                        . "LEFT JOIN category AS children ON children.parents_id = cat.id "
                        . "LEFT JOIN category AS parent ON parent.id = cat.parents_id "
                        . "WHERE cat.slug =  ? "
                        . "ORDER BY cat.position ASC ", array($request->getParameter('slug')))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $categoryId_root = end($this->categorys)['parent_id'] != "" ? end($this->categorys)['parent_id'] : end($this->categorys)['this_id'];
        if (count($this->categorys) == 0) {
            $this->forward404();
        }

        if (array_keys($this->categorys)[0] != "") {
            $categorysId = implode(",", array_keys($this->categorys));
        } else {
            $categorysId = end($this->categorys)['this_id'];
        }
        $timerCategory->addTime();

        $timerCatalog = sfTimerManager::getTimer('Action: Загрузка каталогов');
        $this->catalog = $q->execute("SELECT catalog.id, catalog.name AS catalog_name, catalog.description AS catalog_description, catalog.slug AS catalog_slug "
                        . "FROM catalog "
                        . "LEFT JOIN category_catalog AS cc ON cc.catalog_id = catalog.id "
                        . "WHERE cc.category_id = ? ", array($categoryId_root))->fetch(Doctrine_Core::FETCH_UNIQUE);
        $timerCatalog->addTime();







        $timerFilters = sfTimerManager::getTimer('Action: Загрузка параметров фильтра');
        $this->sortOrder = $request->getParameter('sortOrder');
        $this->direction = $request->getParameter('direction');
        $this->filters = $request->getParameter('filters');
        $this->price = $request->getParameter('Price');
        $this->page = $request->getParameter('page', 1);
        $timerFilters->addTime();


        $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
        $addleftJoin = "";
        $addOrderBy = "(count>0) DESC,";
        if ($this->sortOrder == "date") {
            $addOrderBy.= "created_at desc";
        }elseif ($this->sortOrder == "price") {
            /* (
price - ( price * IFNULL( bonuspay, 0 ) * 0.01 )
)
             */
            if ($this->direction == "asc") {
                $addOrderBy.= "price asc";
            } else {
                $addOrderBy.= "price desc";
            }
        }elseif ($this->sortOrder == "comments") {

            $addleftJoin = " LEFT JOIN comments as com on product.id = com.product_id and com.is_public = '1' ";
            $addOrderBy.= "count(com.id) desc";
        }elseif ($this->sortOrder == "actions") {
            $addOrderBy.= "(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC";
            $addOrderBy.= ", (step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC";
            if ($this->direction == "desc") {
                $addOrderBy.= ", position desc";
            } else {
                $this->direction = "asc";
                $addOrderBy.= ", position asc";
            }
        }else/*if ($this->sortOrder == "sortorder" or $this->sortOrder == "")*/ {
            $this->sortOrder = "sortorder";
            $addOrderBy.= "sortpriority desc,countsell desc,price desc";
        }
        $timerSort->addTime();


        $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');
        $addWhere = "";
        $addWhereParams = array();
        if ($this->price['from']) {
            $addWhere = $addWhere . ' and price>=? and price<=?';
            $addWhereParams[] = $this->price['from'];
            $addWhereParams[] = $this->price['to'];
        }
        $filtersDB = unserialize(end($this->categorys)['filters']);
        if (is_array($this->filters)) {
            foreach ($this->filters as $dicId => $params) {
                if (isset($params['from']) and isset($params['to'])) {
                    if (($params['from'] != $filtersDB[$dicId]['range']['min']) or ( $params['to'] != $filtersDB[$dicId]['range']['max'])) {
                        $productsIDFiltersTemp = array();
                        foreach ($filtersDB[$dicId] as $keyParamDB => $paramDB) {
                            if ($keyParamDB != "range" and $keyParamDB != "nameCategory") {
                                if ((float) $paramDB['value'] >= $params['from'] and (float) $paramDB['value'] <= $params['to']) {
                                    $productsIDFiltersTemp = array_merge($productsIDFiltersTemp, explode(",", $paramDB['productsId']));
                                }
                            }
                        }
                        if (@is_array($productsIDFilters)) {
                            $productsIDFilters = array_intersect($productsIDFilters, $productsIDFiltersTemp);
                        } else {
                            $productsIDFilters = $productsIDFiltersTemp;
                        }
                    }
                } else {
                    if (isset($filtersDB[$dicId])) {
                        if (count($params) > 1) {
                            $productsIDFiltersTemp = array();
                            foreach ($params as $paramId) {
                                if (is_array($filtersDB[$dicId][$paramId])) {
                                    $productsIDFiltersTemp = array_merge($productsIDFiltersTemp, explode(",", $filtersDB[$dicId][$paramId]['productsId']));
                                }
                            }
                            if (@is_array($productsIDFilters)) {
                                $productsIDFilters = array_intersect($productsIDFilters, $productsIDFiltersTemp);
                            } else {
                                $productsIDFilters = $productsIDFiltersTemp;
                            }
                        } else {
                            if (is_array($filtersDB[$dicId][$params[0]])) {
                                if (@is_array($productsIDFilters)) {
                                    $productsIDFilters = array_intersect($productsIDFilters, explode(",", $filtersDB[$dicId][$params[0]]['productsId']));
                                } else {
                                    $productsIDFilters = explode(",", $filtersDB[$dicId][$params[0]]['productsId']);
                                }
                            }
                        }
                    }
                }
            }
        }
        if (@is_array($productsIDFilters)) {
            $productsIDFilters = array_diff($productsIDFilters, array(''));
            if (count($productsIDFilters) > 0) {
                $addWhere = $addWhere . " and product.id in (" . implode(",", $productsIDFilters) . ")";


                $this->filtersCountProducts = $q->execute("SELECT  dop_info_id, COUNT( product_id ) as count  "
                                . "FROM  dop_info_product "
                                . "WHERE  product_id in (" . implode(",", $productsIDFilters) . ") "
                                . "GROUP BY dop_info_id")
                        ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            } else {
                $addWhere = $addWhere . " and false";
            }
        }
        $this->productsCount = $q->execute("SELECT COUNT( DISTINCT product.id ) as count "
                        . "FROM product "
                        . "LEFT JOIN category_product as cp on product.id=product_id "
                        . $addleftJoin
                        . "WHERE  cp.category_id IN (" . $categorysId . ") "
                        . "and product.is_public='1' "
                        . $addWhere, $addWhereParams)
                ->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->productsCount = $this->productsCount['count'];
        //if ($request->getParameter('slug') == "upravlyai-cenoi") {
            $this->pagesCount = ceil(($this->productsCount) / 10);

            if ($this->page > $this->pagesCount or $this->page < 1) {
                $this->page = 1;
            }
            $pagesLimitStart = ($this->page * 10 - 10) <= $this->productsCount ? ($this->page * 10 - 10) : ($this->page * 10 - 20);
            //$pagesLimitEnd = ($this->page * 10) <= $this->productsCount ? ($this->page * 10) : $this->productsCount;
            $pagesLimitEnd = 10;
       /* } else {
            $this->pagesCount = ceil(($this->productsCount) / 4);

            if ($this->page > $this->pagesCount or $this->page < 1) {
                $this->page = 1;
            }
            $pagesLimitStart = ($this->page * 4 - 4) <= $this->productsCount ? ($this->page * 4 - 4) : ($this->page * 4 - 8);
            //$pagesLimitEnd = ($this->page * 4) <= $this->productsCount ? ($this->page * 4) : $this->productsCount;
            $pagesLimitEnd = 4;
        }*/
        $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;

        $this->products = $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, product.created_at, video, videoenabled, endaction, step, count "
                        . "FROM product "
                        . "LEFT JOIN category_product as cp on product.id=product_id "
                        . $addleftJoin
                        . "WHERE  cp.category_id IN (" . $categorysId . ") "
                        . "and product.is_public='1' "
                        . "" . $addWhere . " "
                        . "group by product.id "
                        . "ORDER BY "
                        . $addOrderBy
                        . $pagesLimit, $addWhereParams)
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerProducts->addTime();

        if (count($this->products) > 0) {
            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', array_keys($this->products)) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                            . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", array_keys($this->products)) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
        if($request->getParameter("loadProducts")){
            $this->setTemplate("loadProducts");
        }
    }

    public function executeBestsellers(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $this->setTemplate("category");
        $this->categoryName = "Лидеры продаж";

        $this->productsCount = 100;
        $this->pagesCount = 25;
        $this->page = $request->getParameter('page', 1);

        if ($this->page > $this->pagesCount or $this->page < 1) {
            $this->page = 1;
        }
        $pagesLimitStart = ($this->page * 4 - 4) <= $this->productsCount ? ($this->page * 4 - 4) : ($this->page * 4 - 8);
        //$pagesLimitEnd = ($this->page * 4) <= $this->productsCount ? ($this->page * 4) : $this->productsCount;
        $pagesLimitEnd = 4;
        $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;

        $this->products = $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
                        . "FROM product "
                        . "WHERE is_public='1' "
                        . "and count>0 "
                        . "ORDER BY sortpriority desc,countsell desc"
                        . $pagesLimit)
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);

        if (count($this->products) > 0) {
            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', array_keys($this->products)) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                            . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", array_keys($this->products)) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
    }

    public function executeNewproduct(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $this->setTemplate("category");
        $this->categoryName = "Новинки";

        $this->productsCount = 100;
        $this->pagesCount = 25;
        $this->page = $request->getParameter('page', 1);

        if ($this->page > $this->pagesCount or $this->page < 1) {
            $this->page = 1;
        }
        $pagesLimitStart = ($this->page * 10 - 10) <= $this->productsCount ? ($this->page * 10 - 10) : ($this->page * 10 - 20);
        //$pagesLimitEnd = ($this->page * 10) <= $this->productsCount ? ($this->page * 10) : $this->productsCount;
        $pagesLimitEnd = 10;
        $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;

        $this->products = $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
                        . "FROM product "
                        . "WHERE is_public='1' "
                        . "and count>0 "
                        . "ORDER BY created_at DESC"
                        . $pagesLimit)
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);

        if (count($this->products) > 0) {
            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', array_keys($this->products)) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                            . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", array_keys($this->products)) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
    }

    public function executeBestprice(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $this->category = $q->execute("SELECT * "
                        . "FROM category "
                        . "WHERE slug=? "
                        , array("Luchshaya_tsena"))
                ->fetch(Doctrine_Core::FETCH_ASSOC);


        $this->categorysCount = $q->execute("SELECT COUNT( * ) AS count "
                        . "FROM category "
                        . "WHERE is_public='1' "
                        . "AND countproductactions >  1")
                ->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->categorysCount = $this->categorysCount['count'];
        $this->pagesCount = ceil(($this->categorysCount) / 5);

        $this->page = $request->getParameter('page', 1);

        if ($this->page > $this->pagesCount or $this->page < 1) {
            $this->page = 1;
        }
        $pagesLimitStart = ($this->page * 5 - 5) <= $this->categorysCount ? ($this->page * 5 - 5) : ($this->page * 5 - 10);
        //$pagesLimitEnd = ($this->page * 5) <= $this->categorysCount ? ($this->page * 5) : $this->categorysCount;
        $pagesLimitEnd = 5;
        $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;

        $this->categorys = $q->execute("SELECT * "
                        . "FROM category "
                        . "WHERE is_public='1' "
                        . "AND countproductactions > 1 "
                        . "ORDER BY positionloveprice ASC"
                        . $pagesLimit)
                ->fetchAll(Doctrine_Core::FETCH_ASSOC);

        $this->products = array(0 => array(), 1 => array(), 2 => array(), 3 => array(), 4 => array());
        foreach ($this->categorys as $num => $category) {
            $this->products[$num] = $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
                            . "FROM product "
                            . "WHERE is_public='1' "
                            . "and count>0 "
                            . "and step <> '' "
                            . "and discount >0 "
                            . "and generalcategory_id=? "
                            . "limit 2", array($this->categorys[$num]['id']))
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            /* $products[1] = $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
              . "FROM product "
              . "WHERE is_public='1' "
              . "and moder = '0' "
              . "and count>0 "
              . "and step <> '' "
              . "and discount >0 "
              . "and generalcategory_id=? "
              . "limit 2", array($this->categorys[1]['id']))
              ->fetchAll(Doctrine_Core::FETCH_UNIQUE); */
        }

        if (count(array_merge(array_keys($this->products[0]), array_keys($this->products[1]), array_keys($this->products[2]), array_keys($this->products[3]), array_keys($this->products[4]))) > 0) {

            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', array_merge(array_keys($this->products[0]), array_keys($this->products[1]), array_keys($this->products[2]), array_keys($this->products[3]), array_keys($this->products[4]))) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                            . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(',', array_merge(array_keys($this->products[0]), array_keys($this->products[1]), array_keys($this->products[2]), array_keys($this->products[3]), array_keys($this->products[4]))) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
    }

    public function executeCollection(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerCollection = sfTimerManager::getTimer('Action: Загрузка коллекции');
        $this->collection = $q->execute("SELECT *  "
                        . "FROM collection as c "
                        . "WHERE c.slug =  ? ", array($request->getParameter('slug')))->fetch(Doctrine_Core::FETCH_ASSOC);

        if (!$this->collection) {
            $this->collection['subid'] = $request->getParameter('slug');
            $this->dopinfo = Doctrine_Core::getTable('DopInfo')->findOneById($this->collection['subid']);
            if ($this->dopinfo->getDopInfoCategory()->getName() != "Коллекция") {
                $this->forward404();
            } else {
                $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($this->collection['subid']);
                $this->collection['name'] = $this->dopinfoProduct[0]->getDopInfo()->getValue();
            }
        }
        //$this->forward404Unless($this->collection);
        $timerCollection->addTime();

        $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');
        $this->productsCount = $q->execute("SELECT count(*) as count  "
                        . "FROM product as p "
                        . "LEFT JOIN dop_info_product as di on p.id=di.product_id "
                        . "WHERE di.dop_info_id = '" . $this->collection['subid'] . "'")
                ->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->productsCount = $this->productsCount['count'];
        $this->pagesCount = ceil(($this->productsCount) / 4);
        $this->page = $request->getParameter('page', 1);

        if ($this->page > $this->pagesCount or $this->page < 1) {
            $this->page = 1;
        }
        $pagesLimitStart = ($this->page * 4 - 4) <= $this->productsCount ? ($this->page * 4 - 4) : ($this->page * 4 - 8);
        //$pagesLimitEnd = ($this->page * 4) <= $this->productsCount ? ($this->page * 4) : $this->productsCount;
        $pagesLimitEnd = 4;
        $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;

        $this->products = $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
                        . "FROM product "
                        . "LEFT JOIN dop_info_product as di on product.id=di.product_id "
                        . "WHERE di.dop_info_id = '" . $this->collection['subid'] . "' "
                        . "ORDER BY count>0 desc, sortpriority desc,countsell desc "
                        . $pagesLimit)
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerProducts->addTime();

        if (count($this->products) > 0) {
            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', array_keys($this->products)) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                            . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", array_keys($this->products)) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
    }

    public function executeManufacturer(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerManufacturer = sfTimerManager::getTimer('Action: Загрузка производителя');
        $this->manufacturer = $q->execute("SELECT *  "
                        . "FROM manufacturer as m "
                        . "WHERE m.slug =  ? ", array($request->getParameter('slug')))->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->forward404Unless($this->manufacturer);
        $timerManufacturer->addTime();

        $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');
        $this->productsCount = $q->execute("SELECT count(*) as count  "
                        . "FROM product as p "
                        . "LEFT JOIN dop_info_product as di on p.id=di.product_id "
                        . "WHERE di.dop_info_id = '" . $this->manufacturer['subid'] . "'")
                ->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->productsCount = $this->productsCount['count'];
        $this->pagesCount = ceil(($this->productsCount) / 4);
        $this->page = $request->getParameter('page', 1);

        if ($this->page > $this->pagesCount or $this->page < 1) {
            $this->page = 1;
        }
        $pagesLimitStart = ($this->page * 4 - 4) <= $this->productsCount ? ($this->page * 4 - 4) : ($this->page * 4 - 8);
        //$pagesLimitEnd = ($this->page * 4) <= $this->productsCount ? ($this->page * 4) : $this->productsCount;
        $pagesLimitEnd = 4;
        $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;

        $this->products = $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
                        . "FROM product "
                        . "LEFT JOIN dop_info_product as di on product.id=di.product_id "
                        . "WHERE di.dop_info_id = '" . $this->manufacturer['subid'] . "' "
                        . "ORDER BY count>0 desc, sortpriority desc,countsell desc "
                        . $pagesLimit)
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerProducts->addTime();

        if (count($this->products) > 0) {
            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', array_keys($this->products)) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                            . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", array_keys($this->products)) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
    }

}
