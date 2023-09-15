<?php

/**
 * category actions.
 *
 * @package    Magazin
 * @subpackage category
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class categoryActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->categorys = Doctrine_Core::getTable('Category')
                ->createQuery('a')
                ->execute();
    }

    public function executeCatalog(sfWebRequest $request) {

      $this->catalog = CatalogTable::getInstance()->findOneBySlug(array($request->getParameter('slug')));
      if (empty($this->catalog))
          $this->catalog = CatalogTable::getInstance()->findOneById(array($request->getParameter('slug')));

      if (empty($this->catalog)) {
          $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Catalog' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
          if ($oldSlug) {
              $this->catalog = Doctrine_Core::getTable('Catalog')->findOneById($oldSlug->getDopid());
              return $this->redirect('/catalog/' . $this->catalog->getSlug(), 301);
          }
      }
      $this->forward404Unless($this->catalog);


      $user = $this->getUser();
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $slug = 'service-'. $request->getParameter('slug');

      $timerCategory = sfTimerManager::getTimer('Action: Загрузка категорий');

      $query="SELECT children.id, children.name, children.slug, parent.id as parent_id, parent.name as parent_name, "
                ."parent.slug as parent_slug, cat.id as this_id, cat.name as this_name, cat.h1 as this_h1, "
                ."cat.content as this_content , cat.slug as this_slug, cat.filtersnew as filters, cat.minprice as minPrice, "
                ."cat.maxprice as maxPrice, cat.tags as tags  "
                .", cat.title, cat.keywords, cat.description  "
            . "FROM category as cat "
            . "LEFT JOIN category AS children ON children.parents_id = cat.id "
            . "LEFT JOIN category AS parent ON parent.id = cat.parents_id "
            . "WHERE cat.slug =  ? "
            . "ORDER BY cat.position ASC ";
      $this->categorys =
        $q->execute($query, array($slug))
        ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
      $this->query=$query;
      $this->slug=$request->getParameter('slug');

      $categoryId_root = end($this->categorys)['parent_id'] != "" ? end($this->categorys)['parent_id'] : end($this->categorys)['this_id'];
      $isCosmetic=false;
      if($categoryId_root){
        $catalogId=end($q->execute("SELECT * FROM category_catalog where category_id=".$categoryId_root.";")->fetchAll(Doctrine_Core::FETCH_ASSOC));
      }

      if (array_keys($this->categorys)[0] != "") {
          $categorysId = implode(",", array_merge(array_keys($this->categorys), array(end($this->categorys)['this_id'])));
      } else {
          $categorysId = end($this->categorys)['this_id'];
      }

      $timerCategory->addTime();

      $timerFilters = sfTimerManager::getTimer('Action: Загрузка параметров фильтра');
      $this->sortOrder=$request->getParameter('sortOrder');
      $this->direction = $request->getParameter('direction');
      $this->filters = $request->getParameter('filters');
      $this->price = $request->getParameter('Price');
      $this->page = $request->getParameter('page', 1);
      $this->isStock = $request->getParameter('isStock', 0);
      $this->isNovice = $request->getParameter('novice', '');
      if(!in_array($this->isNovice, $avaibleModifacators)) $this->isNovice='';
      // if(!$this->sortOrder) $this->sortOrder = 'actions';
      $timerFilters->addTime();

      $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
      $addleftJoin = "";
      $addOrderBy = "(count>0) DESC, ";
      // if($isCosmetic) $addOrderBy.="  LEFT(code, 2)='JO' desc,";
      if(!$this->sortOrder) {
        $this->sortOrder = 'actions';
        // $this->direction = "desc";
      }

      if ($this->sortOrder == "date") {
          $newsList=csSettings::get('optimization_newProductId');
          if ($newsList != "") {
            $addOrderBy.= " id IN(".$newsList.") DESC, ";
          }
          $addOrderBy.= "created_at desc";
      }
      elseif ($this->sortOrder == "price") {
          if ($this->direction == "asc") {
              $addOrderBy.= "price asc";
          } else {
              $addOrderBy.= "price desc";
          }
      }
      elseif ($this->sortOrder == "comments") {

          $addleftJoin = " LEFT JOIN comments as com on product.id = com.product_id and com.is_public = '1' ";
          $addOrderBy.= "count(com.id) desc";
      }
      elseif ($this->sortOrder == "actions") {
          $addOrderBy.= "(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC";
          $addOrderBy.= ", (step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC";
          if ($this->direction == "desc") {
              $addOrderBy.= ", position desc";
          } else {
              $this->direction = "asc";
              $addOrderBy.= ", position asc";
          }
      }
      elseif($this->sortOrder == "sortorder"){
          $addOrderBy.= "sortpriority desc,countsell desc,price desc";
      }
      $timerSort->addTime();
      if($addOrderBy=='') $addOrderBy='sortpriority desc ';
      $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');
      $addWhere = "";
      $addWhereParams = array();
      if ($this->price['from']) {
          $addWhere = $addWhere . ' and price>=? and price<=?';
          $addWhereParams[] = $this->price['from'];
          $addWhereParams[] = $this->price['to'];
      }
      if($this->isStock){
          $addWhere = $addWhere . ' and count>0';
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
                      . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1'))"
                      . $addWhere, $addWhereParams)
              ->fetch(Doctrine_Core::FETCH_ASSOC);
              // $this->addWhere='~!'.$addWhere;
      $this->productsCount = $this->productsCount['count'];
      $productsPerPageCount = 30;
      $this->pagesCount = ceil(($this->productsCount) / $productsPerPageCount);

      if ($this->page > $this->pagesCount or $this->page < 1) {
          $this->page = 1;
      }
      $pagesLimitStart = ($this->page * $productsPerPageCount - $productsPerPageCount) <= $this->productsCount ? ($this->page * $productsPerPageCount - $productsPerPageCount) : ($this->page * $productsPerPageCount - 2*$productsPerPageCount);
      $pagesLimitEnd = $productsPerPageCount;
      $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;
      $this->products =
        $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, product.created_at, video, videoenabled, endaction, step, count, product.is_public, parents_id, code, '".end($this->categorys)['name']."' as cat_name "
                    . "FROM product "
                    . "LEFT JOIN category_product as cp on product.id=product_id "
                    . $addleftJoin
                    . "WHERE  cp.category_id IN (" . $categorysId . ") "
                    . "and product.is_public='1' "
                    . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1')) "
                    . "" . $addWhere . " "
                    . "group by product.id "
                    . "ORDER BY "
                    . $addOrderBy
                    . $pagesLimit, $addWhereParams)
              ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
      $timerProducts->addTime();

      if (count($this->products) > 0) {

          $timerChildren = sfTimerManager::getTimer('Action: Загрузка дочерних товаров для всех товаров');
          $this->childrensAll = $q->execute("SELECT IFNULL( parents_id, p.id ),p.* FROM product as p "
                          . "WHERE parents_id in (" . implode(",", array_keys($this->products)) . ") or id in (" . implode(",", array_keys($this->products)) . ") "
                          . "ORDER BY parents_id ASC")
                  ->fetchAll(Doctrine_Core::FETCH_GROUP);
          $timerChildren->addTime();

          $timerProductsIdAll = sfTimerManager::getTimer('Action: Получение id всех товаров на странице, включая дочерних');
          $this->productsIdAll = $q->execute("SELECT p.id FROM product as p "
                          . "WHERE parents_id in (" . implode(",", array_keys($this->products)) . ") or id in (" . implode(",", array_keys($this->products)) . ") ")
                  ->fetchAll(Doctrine_Core::FETCH_COLUMN);
          $timerProductsIdAll->addTime();

          $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
          $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                          . "WHERE is_public='1' and product_id in (" . implode(',', $this->productsIdAll) . ") "
                          . "group by product_id")
                  ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerComments->addTime();

          $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
          $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                          . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                          . "WHERE ppa.product_id in (" . implode(",", $this->productsIdAll) . ") "
                          . "ORDER BY photo.position DESC")
                  ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerPhotos->addTime();
      }
      if ($request->getParameter("loadProducts")) {
          $this->setTemplate("loadProducts");
      }
    }

    public function executeShowNewby(sfWebRequest $request) {
        $avaibleModifacators =[//Доступные "галочки из админки"
          'for_pairs',
          'bdsm',
          'for_she',
          'for_her',
          'cosmetics',
          'belie',
          'other',
        ];
        $user = $this->getUser();
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $slug = 'dlya_novichkov';
        $request->setParameter('slug', $slug);

        $timerCategory = sfTimerManager::getTimer('Action: Загрузка категорий');
        $query="SELECT children.id, children.name, children.slug, parent.id as parent_id, parent.name as parent_name, "
                  ."parent.slug as parent_slug, cat.id as this_id, cat.name as this_name, cat.h1 as this_h1, "
                  ."cat.content as this_content , cat.slug as this_slug, cat.filtersnew as filters, cat.minprice as minPrice, "
                  ."cat.maxprice as maxPrice, cat.tags as tags  "
                  .", cat.title, cat.keywords, cat.description  "
              . "FROM category as cat "
              . "LEFT JOIN category AS children ON children.parents_id = cat.id "
              . "LEFT JOIN category AS parent ON parent.id = cat.parents_id "
              . "WHERE cat.slug =  ? "
              . "ORDER BY cat.position ASC ";
        $this->categorys =
          $q->execute($query, array($slug))
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          //DEBUG
        $this->query=$query;
        $this->slug=$request->getParameter('slug');

        $categoryId_root = end($this->categorys)['parent_id'] != "" ? end($this->categorys)['parent_id'] : end($this->categorys)['this_id'];
        $isCosmetic=false;
        if($categoryId_root){
          $catalogId=end($q->execute("SELECT * FROM category_catalog where category_id=".$categoryId_root.";")->fetchAll(Doctrine_Core::FETCH_ASSOC));
        }

        if (array_keys($this->categorys)[0] != "") {
            $categorysId = implode(",", array_merge(array_keys($this->categorys), array(end($this->categorys)['this_id'])));
        } else {
            $categorysId = end($this->categorys)['this_id'];
        }

        $timerCategory->addTime();

        $timerFilters = sfTimerManager::getTimer('Action: Загрузка параметров фильтра');
        $this->sortOrder=$request->getParameter('sortOrder');
        $this->direction = $request->getParameter('direction');
        $this->filters = $request->getParameter('filters');
        $this->price = $request->getParameter('Price');
        $this->page = $request->getParameter('page', 1);
        $this->isStock = $request->getParameter('isStock', 0);
        $this->isNovice = $request->getParameter('novice', '');
        // if($this->isNovice='' && isset($_GET['novice'])) $this->isNovice=$_GET['novice'];
        if(!in_array($this->isNovice, $avaibleModifacators)) $this->isNovice='';
        if(!$this->sortOrder) {
          $this->sortOrder = 'actions';
          // $this->direction = 'desc';
        }
        $timerFilters->addTime();

        $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
        $addleftJoin = "";
        $addOrderBy = "(count>0) DESC, ";
        // if($isCosmetic) $addOrderBy.="  LEFT(code, 2)='JO' desc,";

        if ($this->sortOrder == "date") {
            $newsList=csSettings::get('optimization_newProductId');
            if ($newsList != "") {
              $addOrderBy.= " id IN(".$newsList.") DESC, ";
            }
            $addOrderBy.= "created_at desc";
        }
        elseif ($this->sortOrder == "price") {
            if ($this->direction == "asc") {
                $addOrderBy.= "price asc";
            } else {
                $addOrderBy.= "price desc";
            }
        }
        elseif ($this->sortOrder == "comments") {

            $addleftJoin = " LEFT JOIN comments as com on product.id = com.product_id and com.is_public = '1' ";
            $addOrderBy.= "count(com.id) desc";
        }
        elseif ($this->sortOrder == "actions") {
            $addOrderBy.= "(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC";
            $addOrderBy.= ", (step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC";
            if ($this->direction == "desc") {
                $addOrderBy.= ", position desc";
            } else {
                $this->direction = "asc";
                $addOrderBy.= ", position asc";
            }
        }
        elseif($this->sortOrder == "sortorder"){
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
        if($this->isNovice){
          $addWhere = $addWhere . ' and '.$this->isNovice.'=?';
          $addWhereParams[] = 1;
        }
        if($this->isStock){
            $addWhere = $addWhere . ' and count>0';
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
                        . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1'))"
                        . $addWhere, $addWhereParams)
                ->fetch(Doctrine_Core::FETCH_ASSOC);
                // $this->addWhere='~!'.$addWhere;
        $this->productsCount = $this->productsCount['count'];
        $productsPerPageCount = 30;
        $this->pagesCount = ceil(($this->productsCount) / $productsPerPageCount);

        if ($this->page > $this->pagesCount or $this->page < 1) {
            $this->page = 1;
        }
        $pagesLimitStart = ($this->page * $productsPerPageCount - $productsPerPageCount) <= $this->productsCount ? ($this->page * $productsPerPageCount - $productsPerPageCount) : ($this->page * $productsPerPageCount - 2*$productsPerPageCount);
        $pagesLimitEnd = $productsPerPageCount;
        $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;
        if($addOrderBy=='') $addOrderBy='sortpriority desc ';
        $this->products =
          $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, product.created_at, video, videoenabled, endaction, step, count, product.is_public, parents_id, code, '".end($this->categorys)['name']."' as cat_name "
                      . "FROM product "
                      . "LEFT JOIN category_product as cp on product.id=product_id "
                      . $addleftJoin
                      . "WHERE  cp.category_id IN (" . $categorysId . ") "
                      . "and product.is_public='1' "
                      . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1')) "
                      . "" . $addWhere . " "
                      . "group by product.id "
                      . "ORDER BY "
                      . $addOrderBy
                      . $pagesLimit, $addWhereParams)
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerProducts->addTime();

        if (count($this->products) > 0) {

            $timerChildren = sfTimerManager::getTimer('Action: Загрузка дочерних товаров для всех товаров');
            $this->childrensAll = $q->execute("SELECT IFNULL( parents_id, p.id ),p.* FROM product as p "
                            . "WHERE parents_id in (" . implode(",", array_keys($this->products)) . ") or id in (" . implode(",", array_keys($this->products)) . ") "
                            . "ORDER BY parents_id ASC")
                    ->fetchAll(Doctrine_Core::FETCH_GROUP);
            $timerChildren->addTime();

            $timerProductsIdAll = sfTimerManager::getTimer('Action: Получение id всех товаров на странице, включая дочерних');
            $this->productsIdAll = $q->execute("SELECT p.id FROM product as p "
                            . "WHERE parents_id in (" . implode(",", array_keys($this->products)) . ") or id in (" . implode(",", array_keys($this->products)) . ") ")
                    ->fetchAll(Doctrine_Core::FETCH_COLUMN);
            $timerProductsIdAll->addTime();

            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', $this->productsIdAll) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                            . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", $this->productsIdAll) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
        if ($request->getParameter("loadProducts")) {
            $this->setTemplate("loadProducts");
        }
    }

    public function executeShowDiscount60(sfWebRequest $request) {
      $timer = sfTimerManager::getTimer('Action: Поиск категории');
      $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array("skidki_do_60_percent"));
      $this->forward404Unless($this->category);
      $timer->addTime();

      $page = $request->getParameter('page', 1);
      $this->page = $page;

      $timer = sfTimerManager::getTimer('Action: Получения товаров для категории лучшей цены');

      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $this->minPrice=(int) $_GET['min_price'];
      $this->maxPrice=(int) $_GET['max_price'];
      if(isset($_GET['filter_cat']) && $_GET['filter_cat']!=''){
        $filter=explode('|', $_GET['filter_cat']);
        // die(print_r($filter, true));
        $this->filterCat=$filter;
      }
      else
        $this->filterCat = [];
        // $this->filterCat = [1, 2, 3, 4, 5, 6, 7];

      $filters=[
        2=> ' for_pairs=1 ',
        3=> ' for_she=1 ',
        1=> ' for_her=1 ',
        4=> ' bdsm=1 ',
        5=> ' cosmetics=1 ',
        6=> ' belie=1 ',
        7=> ' other=1 ',
      ];
      $addWhereByFilter='';
      foreach ($this->filterCat as  $value) {
        $arrFilter[]=$filters[$value];
      }

      if (isset($arrFilter) && sizeof($arrFilter)){
        $addWhereByFilter=' AND ('.implode(' or ', $arrFilter).') ';
      }
      // $catalogIn=implode(', ', $this->filterCat);
      // if($catalogIn=='') $catalogIn= '1, 2, 3, 4, 5, 6, 7';
      $sqlBody=
        "SELECT p.id "
        ."FROM category_catalog cc "
        ."LEFT JOIN category c ON c.parents_id=cc.category_id "
        ."LEFT JOIN category_product cp ON cp.category_id=cc.category_id OR cp.category_id=c.id "
        ."LEFT JOIN product p ON p.id=cp.product_id/* OR p.generalcategory_id=cc.catalog_id*/ "
        ."WHERE p.id IN ( "
          ."SELECT p.id "
          ."FROM category c "
          ."LEFT JOIN category_product cp ON c.id=cp.category_id "
          ."LEFT JOIN product p ON cp.product_id=p.id "
          ."WHERE "
          ."c.slug='skidki_do_60_percent' "
          // ."AND (p.discount>0 OR p.old_price>0) "//129951 - onona.ru - распродажа
          // ."AND p.count>0 "
          .($this->maxPrice ? 'AND p.price <= '.$this->maxPrice .' ' : '')
          .($this->minPrice ? 'AND p.price >= '.$this->minPrice .' ' : '')
        .") "
        // .(strlen($catalogIn) ? "AND cc.catalog_id IN( ".$catalogIn." ) " : '')
        .$addWhereByFilter
        ."GROUP BY p.id "
        ."ORDER BY p.count>0 desc "
        ."";
        //SELECT p.id FROM category_catalog cc LEFT JOIN category c ON c.parents_id=cc.category_id LEFT JOIN category_product cp ON cp.category_id=cc.category_id OR cp.category_id=c.id LEFT JOIN product p ON p.id=cp.product_id WHERE p.id IN ( SELECT p.id FROM category c LEFT JOIN category_product cp ON c.id=cp.category_id LEFT JOIN product p ON cp.product_id=p.id WHERE c.slug='skidki_do_60_percent' AND (p.discount>0 OR p.old_price>0) AND p.count>0 ) and belie=1 GROUP BY p.id ;
        // die('<pre>'.print_r($sqlBody, true).'</pre>');

      $result = $q->execute($sqlBody);
      $this->sqlBody=$sqlBody;

      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $tmp = $result->fetchAll();

      if(sizeof($tmp)) foreach ($tmp as $key => $value) {
        $ids[]=$value['id'];
      }
      $sqlBody=
        "SELECT p.id as id, c.id as categoryId, p.name as name, c.name as categoryName ".
        // ", p.price as p_price".
        "FROM product p ".
        // "LEFT JOIN category_product cp ON cp.category_id=p.generalcategory_id ".
        "LEFT JOIN category c ON c.id=p.generalcategory_id  ".
        "WHERE p.id IN(".implode(', ',$ids).") ".
        // "ORDER BY p.count>0 desc".
        "";
        // die('foo');
      $result = $q->execute($sqlBody);
      $this->sqlBody=$sqlBody;

      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $tmp = $result->fetchAll();
      $this->$advcakeItems = $tmp;
      $this->ids=$ids;
      $this->catalogs=[
        2 => 'Для пар',
        3 => 'Для нее',
        1 => 'Для него',
        4 => 'БДСМ',
        5 => 'Косметика',
        6 => 'Бельё',
        7 => 'Разное',
      ];

    }

    public function executeShownew(sfWebRequest $request) {
      // die('<pre>'.print_r($_SERVER, true).'</pre>');
      if($request->getParameter('slug')!=strtolower($request->getParameter('slug')))
        return $this->redirect('/category/' . strtolower($request->getParameter('slug')), 301);

        $user = $this->getUser();
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerCategory = sfTimerManager::getTimer('Action: Загрузка категорий');
        $this->categorys = $q->execute("SELECT children.id, children.name, children.slug, parent.id as parent_id, parent.name as parent_name , parent.slug as parent_slug, cat.id as this_id, cat.name as this_name, cat.h1 as this_h1, cat.content as this_content , cat.slug as this_slug, cat.filtersnew as filters, cat.minprice as minPrice, cat.maxprice as maxPrice, cat.tags as tags  "
                        .", cat.title, cat.keywords, cat.description  "
                        . "FROM category as cat "
                        . "LEFT JOIN category AS children ON children.parents_id = cat.id "
                        . "LEFT JOIN category AS parent ON parent.id = cat.parents_id "
                        . "WHERE cat.slug =  ? "
                        . "ORDER BY cat.position ASC ", array($request->getParameter('slug')))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $categoryId_root = end($this->categorys)['parent_id'] != "" ? end($this->categorys)['parent_id'] : end($this->categorys)['this_id'];
        $isCosmetic=false;
        if($categoryId_root){
          $catalogId=end($q->execute("SELECT * FROM category_catalog where category_id=".$categoryId_root.";")->fetchAll(Doctrine_Core::FETCH_ASSOC));
          if($catalogId['catalog_id']==5) $isCosmetic=true;
          // die('<pre>'.print_r($catalogId, true).'</pre>');
        }
        $this->catalog=$catalogId;
        if (count($this->categorys) == 0) {
            $this->oldslug = $q->execute("SELECT * "
                            . "FROM  `oldslug` "
                            . "WHERE  `oldslug` LIKE  ? "
                            . "ORDER BY  `oldslug`.`id` DESC ", array($request->getParameter('slug')))->fetch(Doctrine_Core::FETCH_UNIQUE);
            if (count($this->oldslug) > 0) {
                if ($this->oldslug['dopid'] > 0) {
                    $this->category = Doctrine_Core::getTable('Category')->findOneById($this->oldslug['dopid']);
                    return $this->redirect('/category/' . $this->category->getSlug(), 301);
                }
            }

            $this->forward404();
        }
        if (array_keys($this->categorys)[0] != "") {
            $categorysId = implode(",", array_merge(array_keys($this->categorys), array(end($this->categorys)['this_id'])));
        } else {
            $categorysId = end($this->categorys)['this_id'];
        }

        $timerCategory->addTime();

        $timerCategory = sfTimerManager::getTimer('Action: Загрузка подписок');
        if ($user->isAuthenticated()) {
            $this->notificationCategory = $q->execute("SELECT id  "
                            . "FROM notification_category "
                            . "WHERE user_id =  ? "
                            . "AND category_id = ? "
                            . "AND is_enabled='1'", array($user->getGuardUser()->getId(), end($this->categorys)['this_id']))->fetch(Doctrine_Core::FETCH_ASSOC);
        }
        $timerCategory->addTime();
        $timerFilters = sfTimerManager::getTimer('Action: Загрузка параметров фильтра');
        $this->sortOrder=$request->getParameter('sortOrder');
        $this->direction = $request->getParameter('direction');
        $this->filters = $request->getParameter('filters');
        $this->price = $request->getParameter('Price');
        $this->page = $request->getParameter('page', 1);
        $this->isStock = $request->getParameter('isStock', 0);
        if(!$this->sortOrder) {
          $this->sortOrder = 'actions';
          // $this->direction = "desc";
        }
        $timerFilters->addTime();


        $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
        $addleftJoin = "";
        $addOrderBy = "(count>0) DESC, ";
        // if($isCosmetic) $addOrderBy.="  LEFT(code, 2)='JO' desc,";

        if ($this->sortOrder == "date") {
            $newsList=csSettings::get('optimization_newProductId');
            if ($newsList != "") {
              $addOrderBy.= " id IN(".$newsList.") DESC, ";
            }
            $addOrderBy.= "created_at desc";
        }
        elseif ($this->sortOrder == "price") {
            if ($this->direction == "asc") {
                $addOrderBy.= "price asc";
            } else {
                $addOrderBy.= "price desc";
            }
        }
        elseif ($this->sortOrder == "comments") {

            $addleftJoin = " LEFT JOIN comments as com on product.id = com.product_id and com.is_public = '1' ";
            $addOrderBy.= "count(com.id) desc";
        }
        elseif ($this->sortOrder == "actions") {
            $addOrderBy.= "(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC";
            $addOrderBy.= ", (step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC";
            if ($this->direction == "desc") {
                $addOrderBy.= ", position desc";
            } else {
                $this->direction = "asc";
                $addOrderBy.= ", position asc";
            }
        }
        elseif($this->sortOrder == "sortorder"){
          // die("!~".$this->sortOrder.'~!');
            // $this->sortOrder = "sortorder";
            $addOrderBy.= "sortpriority desc,countsell desc,price desc";
        }
        // else{}
        $timerSort->addTime();


        $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');
        $addWhere = "";
        $addWhereParams = array();
        if ($this->price['from']) {
            $addWhere = $addWhere . ' and price>=? and price<=?';
            $addWhereParams[] = $this->price['from'];
            $addWhereParams[] = $this->price['to'];
        }
        if($this->isStock){
            $addWhere = $addWhere . ' and count>0';
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
                        . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1'))"
                        . $addWhere, $addWhereParams)
                ->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->productsCount = $this->productsCount['count'];
        $productsPerPageCount = 30;
        //if ($request->getParameter('slug') == "upravlyai-cenoi") {
        $this->pagesCount = ceil(($this->productsCount) / $productsPerPageCount);

        if ($this->page > $this->pagesCount or $this->page < 1) {
            $this->page = 1;
        }
        $pagesLimitStart = ($this->page * $productsPerPageCount - $productsPerPageCount) <= $this->productsCount ? ($this->page * $productsPerPageCount - $productsPerPageCount) : ($this->page * $productsPerPageCount - 2*$productsPerPageCount);
        //$pagesLimitEnd = ($this->page * 10) <= $this->productsCount ? ($this->page * 10) : $this->productsCount;
        $pagesLimitEnd = $productsPerPageCount;
        /* } else {
          $this->pagesCount = ceil(($this->productsCount) / 4);

          if ($this->page > $this->pagesCount or $this->page < 1) {
          $this->page = 1;
          }
          $pagesLimitStart = ($this->page * 4 - 4) <= $this->productsCount ? ($this->page * 4 - 4) : ($this->page * 4 - 8);
          //$pagesLimitEnd = ($this->page * 4) <= $this->productsCount ? ($this->page * 4) : $this->productsCount;
          $pagesLimitEnd = 4;
          } */
        $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;
        // echo('line 1887<pre>'.print_r($addOrderBy, true).'</pre>');
        if($addOrderBy=='') $addOrderBy='sortpriority desc ';
        $this->products =
          $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, product.created_at, video, videoenabled, endaction, step, count, product.is_public, parents_id, code, '".end($this->categorys)['name']."' as cat_name "
                      . "FROM product "
                      . "LEFT JOIN category_product as cp on product.id=product_id "
                      . $addleftJoin
                      . "WHERE  cp.category_id IN (" . $categorysId . ") "
                      . "and product.is_public='1' "
                      . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1')) "
                      . "" . $addWhere . " "
                      . "group by product.id "
                      . "ORDER BY "
                      . $addOrderBy
                      . $pagesLimit, $addWhereParams)
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerProducts->addTime();

        if (count($this->products) > 0) {

            $timerChildren = sfTimerManager::getTimer('Action: Загрузка дочерних товаров для всех товаров');
            $this->childrensAll = $q->execute("SELECT IFNULL( parents_id, p.id ),p.* FROM product as p "
                            . "WHERE parents_id in (" . implode(",", array_keys($this->products)) . ") or id in (" . implode(",", array_keys($this->products)) . ") "
                            . "ORDER BY parents_id ASC")
                    ->fetchAll(Doctrine_Core::FETCH_GROUP);
            $timerChildren->addTime();

            $timerProductsIdAll = sfTimerManager::getTimer('Action: Получение id всех товаров на странице, включая дочерних');
            $this->productsIdAll = $q->execute("SELECT p.id FROM product as p "
                            . "WHERE parents_id in (" . implode(",", array_keys($this->products)) . ") or id in (" . implode(",", array_keys($this->products)) . ") ")
                    ->fetchAll(Doctrine_Core::FETCH_COLUMN);
            $timerProductsIdAll->addTime();

            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', $this->productsIdAll) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                            . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", $this->productsIdAll) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
        if ($request->getParameter("loadProducts")) {
            $this->setTemplate("loadProducts");
        }
    }

    public function executeManufacturer(sfWebRequest $request) {
        //Приводим к нижнему регистру
        if($request->getParameter('slug')!=strtolower($request->getParameter('slug')))
          return $this->redirect('/manufacturer/' . strtolower($request->getParameter('slug')), 301);

        $this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySlug($request->getParameter('slug'));
        if (empty($this->manufacturer)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Manufacturer' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneById($oldSlug->getDopid());
                return $this->redirect('/manufacturer/' . $this->manufacturer->getSlug(), 301);
            }
        }
        if ($this->manufacturer) {
            $idFind = $this->manufacturer->getSubid();
        } else {
            $this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySubid($request->getParameter('slug'));
            $idFind = $request->getParameter('slug');
        }

        if(!$idFind){
            $this->forward404();
        }

        $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);

        $this->dopinfo = Doctrine_Core::getTable('DopInfo')->findOneById($idFind);
        if (!$this->dopinfo)
            $this->forward404();

        if ($this->dopinfo->getDopInfoCategory()->getName() != "Производитель")
            $this->forward404();

        $prod = "";
        if(!count($this->dopinfoProduct)) $this->forward404();
        foreach ($this->dopinfoProduct as $key => $product) {
          $tableTmp[$key]=$product;
            $prod.=" p.id = '" . $product->get('product_id') . "'";
            if ($key < count($this->dopinfoProduct) - 1)
                $prod.=" or";
        }
        // if ($request->getParameter('slug')==1192) throw new Doctrine_Table_Exception('DEBUG <pre/>~|'.print_r(  ['count'=>count($this->dopinfoProduct),'tmp'=>$tableTmp, $this->dopinfoProduct], true).'|~</pre>');
        $this->pager = new sfDoctrinePager('Product', 30);

        if ($this->manufacturer) {
            $idProductsCount0Children = "";
            $productsCount0Children = ProductTable::getInstance()->createQuery()->leftJoin("Product.Parent par")->where("count>0")->addWhere("par.count=0")->addWhere("is_public=1")
                            /* ->addWhere("(generalcategory_id IN (" . $this->category->getId() . $idChildrenCategory . "))") */->addWhere("parents_id is not null")->groupBy("parents_id")->execute();
            foreach ($productsCount0Children as $productCount0Children)
                $idProductsCount0Children.="," . $productCount0Children->getParentsId();

            $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
            $query = Doctrine_Core::getTable('Product')
                    ->createQuery('p')->select("*")->addSelect($selectDate)
                    ->leftJoin("p.Comments com on p.id = com.product_id and com.is_public = '1'")
                    ->where($prod)
                    ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
                    ->addWhere('is_public = \'1\'')
                    ->addOrderBy("(count>0) DESC")
                    ->addOrderBy("sortpriority DESC")
                    /* ->addOrderBy("(step <> \"\" and Endaction <> \"\") DESC") */
                    ->addOrderBy("find_in_set(id, '" . $idProductsCount0Children . "') DESC");

            if ($request->getParameter('sortOrder') == "actions") {
                $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC");
                $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC");
            }
            /*
             * Сортировка
             */


            if (sfContext::getInstance()->getRequest()->getCookie('sortOrder') != "") {
                //$this->sortOrder = sfContext::getInstance()->getRequest()->getCookie('sortOrder');
            }
            if ($request->getParameter('sortOrder') != "") {
                sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
                $this->sortOrder = $request->getParameter('sortOrder');
            }
            if (sfContext::getInstance()->getRequest()->getCookie('direction') != "") {
                //$this->direction = sfContext::getInstance()->getRequest()->getCookie('direction');
            }
            if ($request->getParameter('direction') != "") {
                sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
                $this->direction = $request->getParameter('direction');
            }
            if(!$this->sortOrder) {
              $this->sortOrder = 'actions';
              // $this->direction = "asc";
            }

            if ($this->sortOrder == "date") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("created_at asc");
                } else {
                    $query->addOrderBy("created_at desc");
                }
            }

            if ($this->sortOrder == "price") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("price asc");
                } else {
                    $query->addOrderBy("price desc");
                }
            }

            if ($this->sortOrder == "views") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("views_count asc");
                } else {
                    $query->addOrderBy("views_count desc");
                }
            }

            if ($this->sortOrder == "rating") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("(p.rating/p.votes_count) asc");
                } else {
                    $query->addOrderBy("(p.rating/p.votes_count) desc");
                }
            }

            if ($this->sortOrder == "comments") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("count(com.id) asc")->groupBy("id");
                } else {
                    $query->addOrderBy("count(com.id) desc")->groupBy("id");
                }
            }


            if ($this->sortOrder == "actions") {
                $this->sortOrder = "actions";
                //$query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0) DESC");
                //$query->addOrderBy("(bonuspay>0) DESC");
                if (csSettings::get('logo_new') == "") {
                    $day_logo_new = 7;
                } else {
                    $day_logo_new = csSettings::get('logo_new');
                }
                $query->addOrderBy("new_product < " . $day_logo_new . " Desc");
                if ($this->direction == "desc") {
                    $query->addOrderBy("p.position desc");
                } else {
                    $this->direction = "asc";
                    $query->addOrderBy("p.position asc");
                }
            }
            if ($this->sortOrder == "sortorder" or $this->sortOrder == "") {
                /* $this->sortOrder = "sortorder";
                  //$query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0) DESC");
                  //$query->addOrderBy("(bonuspay>0) DESC");
                  if (csSettings::get('logo_new') == "") {
                  $day_logo_new = 7;
                  } else {
                  $day_logo_new = csSettings::get('logo_new');
                  }
                  $query->addOrderBy("new_product < " . $day_logo_new . " DESC");
                  if ($this->direction == "desc") {
                  $query->addOrderBy("p.position desc");
                  } else {
                  $this->direction = "asc";
                  $query->addOrderBy("p.position asc");
                  } */
                $this->sortOrder = "sortorder";
                $query->addOrderBy("p.sortpriority desc");
                $query->addOrderBy("p.countsell desc");
            }
            //echo $this->sortOrder;

            /*
             * Сортировка
             */
        } else
            $this->forward404();

        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));

        $this->pager->init();
    }

    public function executeManufacturerall(sfWebRequest $request) {
        $this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySlug($request->getParameter('slug'));
        if (empty($this->manufacturer)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Manufacturer' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneById($oldSlug->getDopid());
                return $this->redirect('/manufacturer/' . $this->manufacturer->getSlug(), 301);
            }
        }
        if ($this->manufacturer) {
            $idFind = $this->manufacturer->getSubid();
        } else {
            $this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySubid($request->getParameter('slug'));
            $idFind = $request->getParameter('slug');
        }

        if(!$idFind){
            $this->forward404();
        }

        $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);

        $this->dopinfo = Doctrine_Core::getTable('DopInfo')->findOneById($idFind);
        if (!$this->dopinfo)
            $this->forward404();

        if ($this->dopinfo->getDopInfoCategory()->getName() != "Производитель")
            $this->forward404();

        $prod = "";
        foreach ($this->dopinfoProduct as $key => $product) {
            $prod.=" p.id = '" . $product->get('product_id') . "'";
            if ($key < count($this->dopinfoProduct) - 1)
                $prod.=" or";
        }
        $this->pager = new sfDoctrinePager('Product', 3000);
        if ($this->manufacturer) {
            $idProductsCount0Children = "";
            $productsCount0Children = ProductTable::getInstance()->createQuery()->leftJoin("Product.Parent par")->where("count>0")->addWhere("par.count=0")->addWhere("is_public=1")
                            /* ->addWhere("(generalcategory_id IN (" . $this->category->getId() . $idChildrenCategory . "))") */->addWhere("parents_id is not null")->groupBy("parents_id")->execute();
            foreach ($productsCount0Children as $productCount0Children)
                $idProductsCount0Children.="," . $productCount0Children->getParentsId();

            $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
            $query = Doctrine_Core::getTable('Product')
                    ->createQuery('p')->select("*")->addSelect($selectDate)
                    ->leftJoin("p.Comments com on p.id = com.product_id and com.is_public = '1'")
                    ->where($prod)
                    ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
                    ->addWhere('is_public = \'1\'')
                    ->addOrderBy("(count>0) DESC")
                    /* ->addOrderBy("(step <> \"\" and Endaction <> \"\") DESC") */
                    ->addOrderBy("find_in_set(id, '" . $idProductsCount0Children . "') DESC");

            if ($request->getParameter('sortOrder') == "actions") {
                $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC");
                $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC");
            }
            /*
             * Сортировка
             */


            if (sfContext::getInstance()->getRequest()->getCookie('sortOrder') != "") {
                //$this->sortOrder = sfContext::getInstance()->getRequest()->getCookie('sortOrder');
            }
            if ($request->getParameter('sortOrder') != "") {
                sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
                $this->sortOrder = $request->getParameter('sortOrder');
            }
            if (sfContext::getInstance()->getRequest()->getCookie('direction') != "") {
                //$this->direction = sfContext::getInstance()->getRequest()->getCookie('direction');
            }
            if ($request->getParameter('direction') != "") {
                sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
                $this->direction = $request->getParameter('direction');
            }

            if ($this->sortOrder == "date") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("created_at asc");
                } else {
                    $query->addOrderBy("created_at desc");
                }
            }

            if ($this->sortOrder == "price") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("price asc");
                } else {
                    $query->addOrderBy("price desc");
                }
            }

            if ($this->sortOrder == "views") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("views_count asc");
                } else {
                    $query->addOrderBy("views_count desc");
                }
            }

            if ($this->sortOrder == "rating") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("(p.rating/p.votes_count) asc");
                } else {
                    $query->addOrderBy("(p.rating/p.votes_count) desc");
                }
            }

            if ($this->sortOrder == "comments") {
                if ($this->direction == "asc") {
                    $query->addOrderBy("count(com.id) asc")->groupBy("id");
                } else {
                    $query->addOrderBy("count(com.id) desc")->groupBy("id");
                }
            }


            if ($this->sortOrder == "actions") {
                $this->sortOrder = "actions";
                //$query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0) DESC");
                //$query->addOrderBy("(bonuspay>0) DESC");
                if (csSettings::get('logo_new') == "") {
                    $day_logo_new = 7;
                } else {
                    $day_logo_new = csSettings::get('logo_new');
                }
                $query->addOrderBy("new_product < " . $day_logo_new . " Desc");
                if ($this->direction == "desc") {
                    $query->addOrderBy("p.position desc");
                } else {
                    $this->direction = "asc";
                    $query->addOrderBy("p.position asc");
                }
            }
            if ($this->sortOrder == "sortorder" or $this->sortOrder == "") {
                /* $this->sortOrder = "sortorder";
                  //$query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0) DESC");
                  //$query->addOrderBy("(bonuspay>0) DESC");
                  if (csSettings::get('logo_new') == "") {
                  $day_logo_new = 7;
                  } else {
                  $day_logo_new = csSettings::get('logo_new');
                  }
                  $query->addOrderBy("new_product < " . $day_logo_new . " DESC");
                  if ($this->direction == "desc") {
                  $query->addOrderBy("p.position desc");
                  } else {
                  $this->direction = "asc";
                  $query->addOrderBy("p.position asc");
                  } */
                $this->sortOrder = "sortorder";
                $query->addOrderBy("p.sortpriority desc");
                $query->addOrderBy("p.countsell desc");
            }
            //echo $this->sortOrder;

            /*
             * Сортировка
             */
        } else
            $this->forward404();

        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    public function executeCollection(sfWebRequest $request) {
      if($request->getParameter('slug')!=strtolower($request->getParameter('slug')))
        return $this->redirect('/collection/' . strtolower($request->getParameter('slug')), 301);
        $this->Collection = Doctrine_Core::getTable('Collection')->findOneBySlug($request->getParameter('slug'));
        if (empty($this->Collection)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Collection' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->Collection = Doctrine_Core::getTable('Collection')->findOneById($oldSlug->getDopid());
                return $this->redirect('/collection/' . $this->Collection->getSlug(), 301);
            }
        }
        if ($this->Collection) {
            $idFind = $this->Collection->getSubid();
        } else {
            $this->Collection = Doctrine_Core::getTable('Collection')->findOneBySubid($request->getParameter('slug'));
            $idFind = $request->getParameter('slug');
        }
        $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);

        $this->dopinfo = Doctrine_Core::getTable('DopInfo')->findOneById($idFind);
        if ($this->dopinfo->getDopInfoCategory()->getName() != "Коллекция")
            $this->forward404();

        $prod = "";
        foreach ($this->dopinfoProduct as $key => $product) {
            $prod.=" p.id = '" . $product->get('product_id') . "'";
            if ($key < count($this->dopinfoProduct) - 1)
                $prod.=" or";
        }
        if ($prod == "") {
            $this->forward404();
        }
        $this->pager = new sfDoctrinePager('Product', 30);

        $this->collection = $this->Collection;
        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")->addSelect($selectDate)
                ->leftJoin("p.Comments com on p.id = com.product_id and com.is_public = '1'")
                ->where($prod)
                ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
                ->addWhere('is_public = \'1\'')
                ->addOrderBy("(count>0) DESC")
                ->addOrderBy("find_in_set(id, '" . $idProductsCount0Children . "') DESC")
        /* ->addOrderBy("(step <> \"\" and Endaction <> \"\") DESC") */;
        if ($request->getParameter('sortOrder') == "actions") {
            $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC");
            $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC");
        }
        /*
         * Сортировка
         */


        if (sfContext::getInstance()->getRequest()->getCookie('sortOrder') != "") {
            //$this->sortOrder = sfContext::getInstance()->getRequest()->getCookie('sortOrder');
        }

        if ($request->getParameter('sortOrder') != "") {
            sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
            $this->sortOrder = $request->getParameter('sortOrder');
        }
        if (sfContext::getInstance()->getRequest()->getCookie('direction') != "") {
            //$this->direction = sfContext::getInstance()->getRequest()->getCookie('direction');
        }
        if ($request->getParameter('direction') != "") {
            sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
            $this->direction = $request->getParameter('direction');
        }
        if(!$this->sortOrder) {
          $this->sortOrder = 'actions';
          // $this->direction = "asc";
        }

        if ($this->sortOrder == "date") {
            if ($this->direction == "asc") {
                $query->addOrderBy("created_at asc");
            } else {
                $query->addOrderBy("created_at desc");
            }
        }

        if ($this->sortOrder == "price") {
            if ($this->direction == "asc") {
                $query->addOrderBy("price asc");
            } else {
                $query->addOrderBy("price desc");
            }
        }

        if ($this->sortOrder == "views") {
            if ($this->direction == "asc") {
                $query->addOrderBy("views_count asc");
            } else {
                $query->addOrderBy("views_count desc");
            }
        }

        if ($this->sortOrder == "rating") {
            if ($this->direction == "asc") {
                $query->addOrderBy("(p.rating/p.votes_count) asc");
            } else {
                $query->addOrderBy("(p.rating/p.votes_count) desc");
            }
        }

        if ($this->sortOrder == "comments") {
            if ($this->direction == "asc") {
                $query->addOrderBy("count(com.id) asc")->groupBy("id");
            } else {
                $query->addOrderBy("count(com.id) desc")->groupBy("id");
            }
        }

        if ($this->sortOrder == "actions") {
            $this->sortOrder = "actions";
            //$query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0) DESC");
            //$query->addOrderBy("(bonuspay>0) DESC");
            if (csSettings::get('logo_new') == "") {
                $day_logo_new = 7;
            } else {
                $day_logo_new = csSettings::get('logo_new');
            }
            $query->addOrderBy("new_product < " . $day_logo_new . " Desc");
            if ($this->direction == "desc") {
                $query->addOrderBy("p.position desc");
            } else {
                $this->direction = "asc";
                $query->addOrderBy("p.position asc");
            }
        }

        if ($this->sortOrder == "sortorder" or $this->sortOrder == "") {
            /* $this->sortOrder = "sortorder";
              //$query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0) DESC");
              //$query->addOrderBy("(bonuspay>0) DESC");
              if (csSettings::get('logo_new') == "") {
              $day_logo_new = 7;
              } else {
              $day_logo_new = csSettings::get('logo_new');
              }
              $query->addOrderBy("new_product < " . $day_logo_new . " Desc");
              if ($this->direction == "desc") {
              $query->addOrderBy("p.position desc");
              } else {
              $this->direction = "asc";
              $query->addOrderBy("p.position asc");
              } */
            $this->sortOrder = "sortorder";
            $query->addOrderBy("p.sortpriority desc");
            $query->addOrderBy("p.countsell desc");
        }
        //echo $this->sortOrder;

        /*
         * Сортировка
         */
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    public function executeRelated(sfWebRequest $request) {

        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('a')->select("*")->addSelect($selectDate)
                ->where('parents_id IS NULL')
                ->addWhere('count>0');
        ;

        /*
         * Количество элементов на страницу
         */
        if (sfContext::getInstance()->getRequest()->getCookie('items2page') > 0) {
            $this->items2page = sfContext::getInstance()->getRequest()->getCookie('items2page');
        }
        if ($request->getParameter('items2page') > 0) {
            sfContext::getInstance()->getResponse()->setCookie('items2page', $request->getParameter('items2page'));
            $this->items2page = $request->getParameter('items2page');
        }

        if ($this->items2page > 0) {
            $this->pager = new sfDoctrinePager('Product', $this->items2page);
        } else {
            $this->pager = new sfDoctrinePager('Product', 15);
        }
        $this->pager = new sfDoctrinePager('Product', 30);
        /*
         * Количество элементов на страницу
         */
        /*
         * Сортировка
         */

        if (sfContext::getInstance()->getRequest()->getCookie('sortOrder') != "") {
            //$this->sortOrder = sfContext::getInstance()->getRequest()->getCookie('sortOrder');
        }
        if ($request->getParameter('sortOrder') != "") {
            sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
            $this->sortOrder = $request->getParameter('sortOrder');
        }
        if (sfContext::getInstance()->getRequest()->getCookie('direction') != "") {
            //$this->direction = sfContext::getInstance()->getRequest()->getCookie('direction');
        }
        if ($request->getParameter('direction') != "") {
            sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
            $this->direction = $request->getParameter('direction');
        }
        if(!$this->sortOrder) {
          $this->sortOrder = 'actions';
          // $this->direction = "asc";
        }

        if ($this->sortOrder == "date") {
            $query->addWhere('is_related = \'1\'');
            if ($this->direction == "asc") {
                $query->orderBy("Created_at asc");
            } else {
                $query->orderBy("Created_at desc");
            }
        }

        if ($this->sortOrder == "price") {
            $query->addWhere('is_related = \'1\'');
            if ($this->direction == "asc") {
                $query->orderBy("price asc");
            } else {
                $query->orderBy("price desc");
            }
        }

        if ($this->sortOrder == "views") {
            $query->addWhere('is_related = \'1\'');
            if ($this->direction == "asc") {
                $query->orderBy("views_count asc");
            } else {
                $query->orderBy("views_count desc");
            }
        }

        if ($this->sortOrder == "rating") {
            $query->addWhere('is_related = \'1\'');
            if ($this->direction == "asc") {
                $query->orderBy("(rating/votes_count) asc");
            } else {
                $query->orderBy("(rating/votes_count) desc");
            }
        }

        if ($this->sortOrder == "sortorder" or $this->sortOrder == "") {
            $this->sortOrder = "sortorder";
            $query->addOrderBy("sortpriority DESC");
            $query->addOrderBy("countsell DESC");
            $query->addOrderBy("(step>0) DESC");
            if (csSettings::get('logo_new') == "") {
                $logoNew = 7;
            } else {
                $logoNew = csSettings::get('logo_new');
            }
            $query->addOrderBy("new_product < " . $logoNew . " Desc");
            if ($this->direction == "desc") {
                $query->addOrderBy("positionrelated desc");
            } else {
                $query->addOrderBy("positionrelated asc");
            }
        }
        /*
         * Сортировка
         */
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

    }

    public function executeShowManagePrice(sfWebRequest $request) {

        $timer = sfTimerManager::getTimer('Action: Поиск категории');
        // $this->getUser()->setAttribute('deliveryId', "");
        $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array("upravlyai-cenoi"));

        $this->forward404Unless($this->category);

        $timer->addTime();

        $timer = sfTimerManager::getTimer('Action: Получения товаров у которых родителя нет в наличии');


        $idProductsCount0Children = $this->getComponent("noncache", "productParentNonCount", array('category' => $this->category, 'sf_cache_key' => $this->category->getId()));
        preg_match_all('#<div>(.+?)</div>#is', $idProductsCount0Children, $matches);
        if (count($matches[1]) == 0) {
            $matches[1] = explode("</div><div>", $idProductsCount0Children);
        }

        $idChildrenCategory = trim(str_replace(array("<div>", "</div>"), "", $matches[1][0]));
        $idProductsCount0Children = trim($matches[1][1]);


        $timer->addTime();


        $timer = sfTimerManager::getTimer('Action: Создание запроса');

        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")->addSelect($selectDate)
                ->leftJoin('p.CategoryProduct c')
                ->leftJoin("p.Comments com on p.id = com.product_id and com.is_public = '1'")
                //->where("(c.category_id IN (" . $this->category->getId() . $idChildrenCategory . ") or step <> \"\")")
                ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
                ->addWhere('com.is_public = \'1\' or com.is_public IS NULL')
                ->addWhere('is_public = \'1\'');

        $query->addOrderBy("(count>0) DESC");
        $query->addOrderBy("find_in_set(id, '" . $idProductsCount0Children . "') DESC");

        $this->pager = new sfDoctrinePager('Product', 30);

        $query->addwhere("bonuspay>0 and bonuspay is not null and count>0");
        if ($request->getParameter('sortOrder') == "sortorder" or $request->getParameter('sortOrder') == "") {
            $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC");
            $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC");
        }

        $timer->addTime();
        /*
         * Сортировка
         */


        if (sfContext::getInstance()->getRequest()->getCookie('sortOrder') != "") {
            //$this->sortOrder = sfContext::getInstance()->getRequest()->getCookie('sortOrder');
        }
        if ($request->getParameter('sortOrder') != "") {
            sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
            $this->sortOrder = $request->getParameter('sortOrder');
        }
        if (sfContext::getInstance()->getRequest()->getCookie('direction') != "") {
            //$this->direction = sfContext::getInstance()->getRequest()->getCookie('direction');
        }
        if ($request->getParameter('direction') != "") {
            sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
            $this->direction = $request->getParameter('direction');
        }

        if ($this->sortOrder == "date" or $this->sortOrder == "sortorder" or $this->sortOrder == "") {
            if ($this->direction == "asc") {
                $query->addOrderBy("created_at asc");
            } else {
                $query->addOrderBy("created_at desc");
            }
        }
        if(!$this->sortOrder) {
          $this->sortOrder = 'actions';
          // $this->direction = "asc";
        }

        if ($this->sortOrder == "price") {
            if ($this->direction == "asc") {
                $query->addOrderBy("price asc");
            } else {
                $query->addOrderBy("price desc");
            }
        }

        if ($this->sortOrder == "views") {
            if ($this->direction == "asc") {
                $query->addOrderBy("views_count asc");
            } else {
                $query->addOrderBy("views_count desc");
            }
        }

        if ($this->sortOrder == "rating") {
            if ($this->direction == "asc") {
                $query->addOrderBy("(p.rating/p.votes_count) asc");
            } else {
                $query->addOrderBy("(p.rating/p.votes_count) desc");
            }
        }

        if ($this->sortOrder == "comments") {
            if ($this->direction == "asc") {
                $query->addOrderBy("count(com.id) asc")->groupBy("id");
            } else {
                $query->addOrderBy("count(com.id) desc")->groupBy("id");
            }
        }

        /* if ($this->sortOrder == "sortorder" or $this->sortOrder == "") {
          $this->sortOrder = "sortorder";
          //$query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0) DESC");
          //$query->addOrderBy("(bonuspay>0) DESC");
          if (csSettings::get('logo_new') == "") {
          $day_logo_new = 7;
          } else {
          $day_logo_new = csSettings::get('logo_new');
          }
          $query->addOrderBy("new_product < " . $day_logo_new . " Desc");
          if ($this->direction == "desc") {
          $query->addOrderBy("p.position desc");
          } else {
          $this->direction = "asc";
          $query->addOrderBy("p.position asc");
          }
          } */
        //echo $this->sortOrder;

        /*
         * Сортировка
         */

        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));

        $this->pager->init();
    }

    public function executeShowBestPrice(sfWebRequest $request) {


        $timer = sfTimerManager::getTimer('Action: Поиск категории');
        $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array("Luchshaya_tsena"));
        $this->forward404Unless($this->category);
        $timer->addTime();

        $page = $request->getParameter('page', 1);
        $this->page = $page;

        $timer = sfTimerManager::getTimer('Action: Получения товаров для категории лучшей цены');

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute('select * from category where id in(select prod2.generalcategory_id from (select prod.generalcategory_id, count(*) as countProd from product prod where prod.is_public=1 and prod.step <> "" and prod.Endaction<>"" group by prod.generalcategory_id) prod2 where prod2.countProd>2)');

        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $categorys = $result->fetchAll();

        $productCount = 0;
        $prodTopAct = "";
        $idCatstr = "";
        foreach ($categorys as $key => $category) {
            $countAction = 0;
            $catProdCount = 0;
            $products = ProductTable::getInstance()->createQuery()->where('step <> "" and Endaction<>"" and discount>0')->addWhere('generalcategory_id=' . $category['id'])->execute();

            $countAction = $products->count();
            if ($countAction > 2) {
                $productCount = $productCount + 3 * floor($countAction / 3);
                $catProdCount = 3 * floor($countAction / 3);

                $idCatstr.=(($idCatstr == "") ? '' : ', ') . $category['id'];

                foreach ($products as $key => $product) {
                    if ($key < $catProdCount and empty($prodTopAct)) {
                        $prodTopAct = $product->getId();
                    } elseif ($key < $catProdCount) {

                        $prodTopAct.="," . $product->getId();
                    }
                }
            }
        }

        if ($idCatstr != "")
            $this->categorys = Doctrine_Core::getTable('Category')
                    ->createQuery('p')->select("*")
                    ->where("id IN (" . $idCatstr . ")")
                    ->addOrderBy('positionloveprice ASC')
                    ->execute();



        $timer->addTime();


        $timer = sfTimerManager::getTimer('Action: Получения товаров у которых родителя нет в наличии');


        $idProductsCount0Children = $this->getComponent("noncache", "productParentNonCount", array('category' => $this->category, 'sf_cache_key' => $this->category->getId()));
        preg_match_all('#<div>(.+?)</div>#is', $idProductsCount0Children, $matches);
        if (count($matches[1]) == 0) {
            $matches[1] = explode("</div><div>", $idProductsCount0Children);
        }

        $idChildrenCategory = trim(str_replace(array("<div>", "</div>"), "", $matches[1][0]));
        $idProductsCount0Children = trim($matches[1][1]);

        $timer->addTime();


        $timer = sfTimerManager::getTimer('Action: Создание запроса');

        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")->addSelect($selectDate)
                ->leftJoin('p.CategoryProduct c')
                ->leftJoin("p.Comments com on p.id = com.product_id and com.is_public = '1'")
                ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
                ->addWhere('com.is_public = \'1\' or com.is_public IS NULL')
                ->addWhere('is_public = \'1\'');

        $query->addOrderBy("(count>0) DESC");
        $query->addOrderBy("find_in_set(id, '" . $idProductsCount0Children . "') DESC");

        $query->addWhere('count>0');
        $query->addwhere("(c.category_id IN (" . $this->category->getId() . $idChildrenCategory . ") or (step <> \"\" and Endaction <> \"\" and discount>0))");
        if ($prodTopAct != "")
            $query->addWhere('id not in (' . $prodTopAct . ')');

        $query->addOrderBy("(step <> \"\" and Endaction <> \"\") DESC");

        $timer->addTime();



        $this->pager = new sfDoctrinePager('Product', 30);
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1) - 1);

        $this->pager->init();
    }

    public function executeBestprice(sfWebRequest $request) {

        $this->items2page = 15;
        $idCat = array();
        $categorys = Doctrine_Core::getTable('Category')
                        ->createQuery('p')->select("*")->addOrderBy('positionloveprice ASC')->execute();
        $productCount = 0;
        foreach ($categorys as $key => $category) {
            $countAction = 0;
            $products = ProductTable::getInstance()->createQuery()->where('step <> ""')->addWhere('generalcategory_id=' . $category->getId())->execute();

            $countAction = $products->count();
            if ($countAction > 2) {
                $idCat[] = $category->getId();
                $productCount = $productCount + 3;
            }
            if ($countAction > 5) {
                $idCat[] = $category->getId();
                $productCount = $productCount + 3;
            }
        }
        $page = $request->getParameter('page', 1);
        $pageCount = ceil($productCount / $productCount);
        //echo $pageCount;
        $this->pageCount = $pageCount;
        $this->page = $page;

        $catStart = $page * ($this->items2page / 3) - ($this->items2page / 3);
        $catEnd = $page * ($this->items2page / 3);
        $idCatstr = "";
        foreach ($idCat as $key => $idCatt) {
            if ($key >= $catStart and $key < $catEnd) {
                $idCatstr.=(($key == 0) ? '' : ', ') . $idCatt;
            }
        }

        $this->categorys = Doctrine_Core::getTable('Category')
                ->createQuery('p')->select("*")
                ->where("id IN (" . $idCatstr . ")")
                ->addOrderBy('positionloveprice ASC')
                ->execute();
    }



    public function executeFiltersblock(sfWebRequest $request) {
        $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array($request->getParameter('slug')));
        if (empty($this->category))
            $this->category = Doctrine_Core::getTable('Category')->findOneById(array($request->getParameter('slug')));
        if (empty($this->category)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Category' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->category = Doctrine_Core::getTable('Category')->findOneById($oldSlug->getDopid());
                return $this->redirect('/category/' . $this->category->getSlug(), 301);
            }
        }
        $this->forward404Unless($this->category);

        $categoryChildrens = $this->category->getChildren();
        $idChildrenCategory = implode(',', $categoryChildrens->getPrimaryKeys());

        if ($idChildrenCategory == "") {
            $categoryIdInQuery = $this->category->getId();
        } else {
            $categoryIdInQuery = $this->category->getId() . "," . $idChildrenCategory;
        }

        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product")
                ->leftJoin('p.CategoryProduct c')
                //->addWhere('parents_id IS NULL')
                ->addWhere("(c.category_id IN (" . $categoryIdInQuery . "))")
                ->addWhere('is_public = \'1\'');
        if (@$_POST['Price']['rangeChange']) {
            $query->addWhere('price>=?', $_POST['Price']['from'])
                    ->addWhere('price<=?', $_POST['Price']['to']);
        }
        $products = $query->execute();
        $productsIdCategory = $products->getPrimaryKeys();



        $arraysProdId = array();
        foreach ($_POST as $postkey => $postdata) {
            if ($postkey == "page") {
                continue;
            }
            $idParams = "";

            if (isset($postdata['rangeChange'])) {
                if ($postdata['rangeChange'] and $postkey != "Price") {
                    $dopInfos = DopInfoTable::getInstance()->createQuery()->where("dicategory_id = ?", $postkey)->addWhere("0 + REPLACE( value,  ',',  '.' ) >= ?", $postdata['from'])->addWhere("0 + REPLACE( value,  ',',  '.' ) <= ?", $postdata['to'])->execute();
                    if (count($idParams) > 0) {
                        if ($idParams != "")
                            $idParams.=",";
                        $idParams.= implode(',', $dopInfos->getPrimaryKeys());
                    }
                } else {
                    $prodIdArr = $productsIdCategory;
                }
            } else {
                foreach ($postdata as $paramsValue) {
                    $dopInfos = DopInfoTable::getInstance()->createQuery()->where("dicategory_id = ?", $postkey)->addWhere("value = ?", $paramsValue)->execute();
                    if (count($idParams) > 0) {
                        if ($idParams != "")
                            $idParams.=",";
                        $idParams.= implode(',', $dopInfos->getPrimaryKeys());
                    }
                    $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($postkey);
                    //$dopInfoCategoryFull=$dopInfoCategory->getCategory()->toArray();
                    if (count($dopInfoCategory->getCategory()->getPrimaryKeys()) > 0) {
                        $dopInfoCategoryFull = DopInfoCategoryFullTable::getInstance()->createQuery()->where("name=?", $paramsValue)->addWhere("id in (" . implode(',', $dopInfoCategory->getCategory()->getPrimaryKeys()) . ")")->execute();
                        foreach ($dopInfoCategoryFull as $dopsParams) {
                            //print_r($dopsParams->getDopInfoCategoryFullDopInfo()->toArray());
                            foreach ($dopsParams->getDopInfoCategoryFullDopInfo()->toArray() as $dopParams) {
                                if ($idParams != "")
                                    $idParams.=",";
                                $idParams.=$dopParams['dop_info_id'];
                            }
                        }
                    }
                }
            }
            //print_r($idParams);
            if ($idParams != "") {
                /*  $prodIdArr = array();
                  $dopInfoProducts = DopInfoProductTable::getInstance()->createQuery()->where("dop_info_id in (" . $idParams . ")")->execute();
                  foreach ($dopInfoProducts as $dopInfoProduct)
                  $prodIdArr[] = $dopInfoProduct->getProductId(); */
                if ($postkey != "58") {
                    $prodIdArr = array();
                    $dopInfoProducts = DopInfoProductTable::getInstance()->createQuery()->where("dop_info_id in (" . $idParams . ")")->execute();
                    foreach ($dopInfoProducts as $dopInfoProduct)
                        $prodIdArr[] = $dopInfoProduct->getProductId();
                } else {
                    $prodIdArr = array();
                    $idParamsArr = explode(",", $idParams);
                    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                    $result = $q->execute("SELECT *
FROM (

SELECT * , COUNT( dop_info_product.product_id ) AS countDP
FROM  `dop_info_product`
WHERE dop_info_product.dop_info_id
IN ( " . $idParams . " )
GROUP BY dop_info_product.product_id
) AS dp1
WHERE countDP =" . count($idParamsArr));

                    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
                    $result = $result->fetchAll();
                    foreach ($result as $dopInfoProduct)
                        $prodIdArr[] = $dopInfoProduct["product_id"];
                }
            }
            $arraysProdId[] = $prodIdArr;
        }
//print_r($arraysProdId);
        $textAddWhereQuery = "";
        foreach ($arraysProdId as $arrayProdId) {
            if (count($arrayProdId) == 0)
                $arrayProdId = array("0");
            $textAddWhereQuery.= " and product.id IN (" . implode(',', $arrayProdId) . ")";
        }

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        if (count($productsIdCategory) > 0) {
            $result = $q->execute("SELECT dop_info.*, count(product.id) as countProd FROM `dop_info`,dop_info_product, product where
                (
dop_info.name <>  'Таблица размеров'
OR dop_info.name IS NULL
)
and
              dop_info.id=dop_info_product.dop_info_id and dop_info_product.product_id=product.id and product.id IN (" . implode(',', $productsIdCategory) . ")" . $textAddWhereQuery . " GROUP BY dop_info.id");

            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $result = $result->fetchAll();
        }
        $filters = array();
        foreach ($result as $filter) {
            if ($filter['dicategory_id'] == 22) {

                $filterFull = DopInfoTable::getInstance()->findOneById($filter['id']);
                $filtersFull = $filterFull->getDopInfoCategoryFullDopInfo();

                if (count($filtersFull) > 0) {
                    foreach ($filtersFull as $keyFilterFull => $filterFull) {

                        // if (@array_search(trim($filterFull->getName()), $filters[$filter['dicategory_id']]) === FALSE) {
                        $filters[$filter['dicategory_id']][trim($filterFull->getName())] = trim($filter['countProd']);
                        // }
                    }
                }
            } else {
                $filters[$filter['dicategory_id']][trim($filter['value'])] = trim($filter['countProd']);
            }
        }

        foreach ($_POST as $postkey => $postdata) {
            if ($postkey != "58") {
                unset($filters[$postkey]);
            }
        }
        //print_r($filters);
        $this->filtersProbe = $filters;
    }

    public function executeCatalogOld(sfWebRequest $request) {
        $this->catalog = CatalogTable::getInstance()->findOneBySlug(array($request->getParameter('slug')));
        if (empty($this->catalog))
            $this->catalog = CatalogTable::getInstance()->findOneById(array($request->getParameter('slug')));

        if (empty($this->catalog)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Catalog' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->catalog = Doctrine_Core::getTable('Catalog')->findOneById($oldSlug->getDopid());
                return $this->redirect('/catalog/' . $this->catalog->getSlug(), 301);
            }
        }
        $this->forward404Unless($this->catalog);

        $categorysId = array();
        foreach ($this->catalog->CategoryCatalogs->getPrimaryKeys() as $id) {
            $categorysId[] = $id;
            $category = CategoryTable::getInstance()->findOneById($id);
            $categorysId = array_merge($categorysId, $category->Children->getPrimaryKeys());
        }
        $idCategorys = implode(",", $categorysId);
        $maxAction = $this->catalog->getMaxaction();
        if ($maxAction == "") {
            $maxAction = 90;
        }

        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        $this->ProductsBestPriceBlock = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")->addSelect($selectDate)
                ->leftJoin('p.CategoryProduct c')
                ->addWhere('parents_id IS NULL')
                ->addWhere('is_public = \'1\'')
                ->addWhere('count>0')
                //  ->addWhere("generalcategory_id IN (" . $idCategorys . ") and step <> \"\" and Endaction <> \"\"")
                ->addwhere("(c.category_id IN (" . $idCategorys . ") and (step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null))")
                ->addOrderBy("rand()")
                ->limit($maxAction)
                ->execute();
        if ($this->ProductsBestPriceBlock->count() != ($this->ProductsBestPriceBlock->count() - $this->ProductsBestPriceBlock->count() % 3)) {
            $this->ProductsBestPriceBlock = Doctrine_Core::getTable('Product')
                    ->createQuery('p')->select("*")->addSelect($selectDate)
                    ->leftJoin('p.CategoryProduct c')
                    ->addWhere('parents_id IS NULL')
                    ->addWhere('is_public = \'1\'')
                    ->addWhere('count>0')
                    //->addWhere("generalcategory_id IN (" . $idCategorys . ") and step <> \"\" and Endaction <> \"\"")
                    ->addwhere("(c.category_id IN (" . $idCategorys . ") and (step <> \"\" and Endaction <> \"\"))")
                    ->addOrderBy("rand()")
                    ->limit($this->ProductsBestPriceBlock->count() - $this->ProductsBestPriceBlock->count() % 2)
                    ->execute();
        }

        $this->ProductsBestsellersBlock = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")->addSelect($selectDate)
                ->addWhere('parents_id IS NULL')
                ->addWhere('is_public = \'1\'')
                ->addWhere('count>0')
                ->addWhere("generalcategory_id IN (" . $idCategorys . ") and (step = \"\" or step is null) and (Endaction = \"\" or Endaction is null)")
                ->addOrderBy("sortpriority DESC")
                ->addOrderBy("countsell DESC")
                ->limit(4)
                ->execute();


        //$this->catalog->setPage(str_replace(array("{blockAction}"),array($this->getBlockCatalogAction($this->ProductsBestPriceBlock)),$this->catalog->getPage()));
    }

    public function executeFilters(sfWebRequest $request) {
        /*
         * Для интервалов:
         *
          SELECT *
          FROM  `dop_info`
          WHERE 0 + REPLACE( value,  ',',  '.' ) > 31.6
          AND dicategory_id =24
          LIMIT 0 , 30
         *
         */

        // $this->getUser()->setAttribute('deliveryId', "");
        $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array($request->getParameter('slug')));
        if (empty($this->category))
            $this->category = Doctrine_Core::getTable('Category')->findOneById(array($request->getParameter('slug')));
        if (empty($this->category)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Category' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->category = Doctrine_Core::getTable('Category')->findOneById($oldSlug->getDopid());
                return $this->redirect('/category/' . $this->category->getSlug(), 301);
            }
        }
        $this->forward404Unless($this->category);

        $categoryChildrens = $this->category->getChildren();
        $idChildrenCategory = implode(',', $categoryChildrens->getPrimaryKeys());

        $idProductsCount0Children = "";
        $productsCount0Children = ProductTable::getInstance()->createQuery()->leftJoin("Product.Parent par")->where("count>0")->addWhere("par.count=0")->addWhere("is_public=1")
                        ->addWhere("parents_id is not null")->groupBy("parents_id")->execute();
        foreach ($productsCount0Children as $productCount0Children)
            $idProductsCount0Children.="," . $productCount0Children->getParentsId();

        if ($idChildrenCategory == "") {
            $categoryIdInQuery = $this->category->getId();
        } else {
            $categoryIdInQuery = $this->category->getId() . "," . $idChildrenCategory;
        }
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product")
                ->leftJoin('p.CategoryProduct c')
                // ->addWhere('parents_id IS NULL')
                ->addWhere("(c.category_id IN (" . $categoryIdInQuery . "))")
                ->addWhere('is_public = \'1\'');
        if ($_POST['Price']['rangeChange']) {
            $query->addWhere('price>=?', $_POST['Price']['from'])
                    ->addWhere('price<=?', $_POST['Price']['to']);
        }
        $products = $query->execute();
        $productsIdCategory = $products->getPrimaryKeys();
        //print_r($productsIdCategory);
        //print_r($_POST);

        $arraysProdId = array();
        foreach ($_POST as $postkey => $postdata) {
            if ($postkey == "page") {
                continue;
            }
            $idParams = "";

            if (isset($postdata['rangeChange'])) {
                if ($postdata['rangeChange'] and $postkey != "Price") {
                    $dopInfos = DopInfoTable::getInstance()->createQuery()->where("dicategory_id = ?", $postkey)->addWhere("0 + REPLACE( value,  ',',  '.' ) >= ?", $postdata['from'])->addWhere("0 + REPLACE( value,  ',',  '.' ) <= ?", $postdata['to'])->execute();
                    if (count($idParams) > 0) {
                        if ($idParams != "")
                            $idParams.=",";
                        $idParams.= implode(',', $dopInfos->getPrimaryKeys());
                    }
                } else {
                    $prodIdArr = $productsIdCategory;
                }
            } else {
                foreach ($postdata as $paramsValue) {
                    if ($postkey != "" and $paramsValue != "" and ! is_array($paramsValue)) {
                        $dopInfos = DopInfoTable::getInstance()->createQuery()
                                ->where("dicategory_id = ?", $postkey)
                                ->addWhere("value = ?", $paramsValue)
                                ->execute();
                        if (count($idParams) > 0) {
                            if ($idParams != "")
                                $idParams.=",";
                            $idParams.= implode(',', $dopInfos->getPrimaryKeys());
                        }
                        $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($postkey);
                        //$dopInfoCategoryFull=$dopInfoCategory->getCategory()->toArray();
                        if (count($dopInfoCategory->getCategory()->getPrimaryKeys()) > 0) {
                            $dopInfoCategoryFull = DopInfoCategoryFullTable::getInstance()->createQuery()->where("name=?", $paramsValue)->addWhere("id in (" . implode(',', $dopInfoCategory->getCategory()->getPrimaryKeys()) . ")")->execute();
                            foreach ($dopInfoCategoryFull as $dopsParams) {
                                //print_r($dopsParams->getDopInfoCategoryFullDopInfo()->toArray());
                                foreach ($dopsParams->getDopInfoCategoryFullDopInfo()->toArray() as $dopParams) {
                                    if ($idParams != "")
                                        $idParams.=",";
                                    $idParams.=$dopParams['dop_info_id'];
                                }
                            }
                        }
                    }
                }
            }
            //print_r($idParams);
            if ($idParams != "") {
                if ($postkey != "58") {
                    $prodIdArr = array();
                    $dopInfoProducts = DopInfoProductTable::getInstance()->createQuery()->where("dop_info_id in (" . $idParams . ")")->execute();
                    foreach ($dopInfoProducts as $dopInfoProduct)
                        $prodIdArr[] = $dopInfoProduct->getProductId();
                } else {
                    $prodIdArr = array();
                    $idParamsArr = explode(",", $idParams);
                    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                    $result = $q->execute("SELECT *
FROM (

SELECT * , COUNT( dop_info_product.product_id ) AS countDP
FROM  `dop_info_product`
WHERE dop_info_product.dop_info_id
IN ( " . $idParams . " )
GROUP BY dop_info_product.product_id
) AS dp1
WHERE countDP =" . count($idParamsArr));

                    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
                    $result = $result->fetchAll();
                    foreach ($result as $dopInfoProduct)
                        $prodIdArr[] = $dopInfoProduct["product_id"];
                }
            }
            $arraysProdId[] = $prodIdArr;
        }
        //print_r($arraysProdId);
        //exit;
        if (count($productsIdCategory) == 0) {
            $this->forward404();
        }
        $queryFilters = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product")
                ->addWhere("p.id IN (" . implode(',', $productsIdCategory) . ")");
        foreach ($arraysProdId as $arrayProdId) {
            if (count($arrayProdId) == 0)
                $arrayProdId = array("0");
            $queryFilters->addWhere("p.id IN (" . implode(',', $arrayProdId) . ")");
        }
        $queryFilters->addOrderBy("(count>0) DESC");
        $queryFilters->addOrderBy("find_in_set(id, '" . $idProductsCount0Children . "') DESC");
        $queryFilters->addOrderBy("(step <> \"\" and Endaction <> \"\") DESC");

        if (csSettings::get('logo_new') == "") {
            $day_logo_new = 7;
        } else {
            $day_logo_new = csSettings::get('logo_new');
        }
        $queryFilters->addOrderBy("new_product < " . $day_logo_new . " Desc");

        $queryFilters->addOrderBy("p.position asc");

        $this->pager = new sfDoctrinePager('Product', 30);

        $this->pager->setQuery($queryFilters);

        $this->pager->setPage($request->getParameter('page', 1));

        $this->pager->init();
    }

    public function executeShow(sfWebRequest $request) {
      // /*не используется*/
        $timer = sfTimerManager::getTimer('Action: Поиск категории');
        // $this->getUser()->setAttribute('deliveryId', "");
        $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array($request->getParameter('slug')));
        if (empty($this->category))
            $this->category = Doctrine_Core::getTable('Category')->findOneById(array($request->getParameter('slug')));
        if (empty($this->category)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Category' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->category = Doctrine_Core::getTable('Category')->findOneById($oldSlug->getDopid());
                return $this->redirect('/category/' . $this->category->getSlug(), 301);
            }
        }
        $this->forward404Unless($this->category);




        $timer->addTime();



        $timer = sfTimerManager::getTimer('Action: Получения товаров у которых родителя нет в наличии');


        $idProductsCount0Children = $this->getComponent("noncache", "productParentNonCount", array('category' => $this->category, 'sf_cache_key' => $this->category->getId()));
        preg_match_all('#<div>(.+?)</div>#is', $idProductsCount0Children, $matches);
        if (count($matches[1]) == 0) {
            $matches[1] = explode("</div><div>", $idProductsCount0Children);
        }

        $idChildrenCategory = trim(str_replace(array("<div>", "</div>"), "", $matches[1][0]));
        $idProductsCount0Children = trim($matches[1][1]);


        $timer->addTime();


        $timer = sfTimerManager::getTimer('Action: Создание запроса');

        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")->addSelect($selectDate)
                ->leftJoin('p.CategoryProduct c')
                ->leftJoin("p.Comments com on p.id = com.product_id and com.is_public = '1'")
                //->where("(c.category_id IN (" . $this->category->getId() . $idChildrenCategory . ") or step <> \"\")")
                ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
                ->addWhere('com.is_public = \'1\' or com.is_public IS NULL')
                ->addWhere('is_public = \'1\'');

        $query->addOrderBy("(count>0) DESC");
        $query->addOrderBy("find_in_set(id, '" . $idProductsCount0Children . "') DESC");

        $this->pager = new sfDoctrinePager('Product', 30);

        $query->addwhere("(c.category_id IN (" . $this->category->getId() . $idChildrenCategory . "))");
        if ($request->getParameter('sortOrder') == "actions") {
            $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC");
            $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC");
        }

        $timer->addTime();
        /*
         * Сортировка
         */


        if (sfContext::getInstance()->getRequest()->getCookie('sortOrder') != "") {
            // $this->sortOrder = sfContext::getInstance()->getRequest()->getCookie('sortOrder');
        }
        if ($request->getParameter('sortOrder') != "") {
            sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
            $this->sortOrder = $request->getParameter('sortOrder');
        }
        if (sfContext::getInstance()->getRequest()->getCookie('direction') != "") {
            // $this->direction = sfContext::getInstance()->getRequest()->getCookie('direction');
        }
        if ($request->getParameter('direction') != "") {
            sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
            $this->direction = $request->getParameter('direction');
        }


        if ($this->sortOrder == "date") {
            if ($this->direction == "asc") {
                $query->addOrderBy("created_at asc");
            } else {
                $query->addOrderBy("created_at desc");
            }
        }
        elseif ($this->sortOrder == "price") {
            if ($this->direction == "asc") {
                $query->addOrderBy("price asc");
            } else {
                $query->addOrderBy("price desc");
            }
        }
        elseif ($this->sortOrder == "views") {
            if ($this->direction == "asc") {
                $query->addOrderBy("views_count asc");
            } else {
                $query->addOrderBy("views_count desc");
            }
        }
        elseif ($this->sortOrder == "rating") {
            if ($this->direction == "asc") {
                $query->addOrderBy("(p.rating/p.votes_count) asc");
            } else {
                $query->addOrderBy("(p.rating/p.votes_count) desc");
            }
        }
        elseif ($this->sortOrder == "comments") {
            if ($this->direction == "asc") {
                $query->addOrderBy("count(com.id) asc")->groupBy("id");
            } else {
                $query->addOrderBy("count(com.id) desc")->groupBy("id");
            }
        }
        elseif ($this->sortOrder == "actions") {
            $this->sortOrder = "actions";
            //$query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0) DESC");
            //$query->addOrderBy("(bonuspay>0) DESC");
            if (csSettings::get('logo_new') == "") {
                $day_logo_new = 7;
            } else {
                $day_logo_new = csSettings::get('logo_new');
            }
            $query->addOrderBy("new_product < " . $day_logo_new . " Desc");
            if ($this->direction == "desc") {
                $query->addOrderBy("p.position desc");
            } else {
                $this->direction = "asc";
                $query->addOrderBy("p.position asc");
            }
        }
        else/* if ($this->sortOrder == "sortorder" or $this->sortOrder == "") */ {
            $this->sortOrder = "sortorder";
            //$query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0) DESC");
            //$query->addOrderBy("(bonuspay>0) DESC");
            /* if (csSettings::get('logo_new') == "") {
              $day_logo_new = 7;
              } else {
              $day_logo_new = csSettings::get('logo_new');
              }
              $query->addOrderBy("new_product < " . $day_logo_new . " Desc"); */
            $query->addOrderBy("p.sortpriority desc");
            $query->addOrderBy("p.countsell desc");
        }
        //echo $this->sortOrder;

        /*
         * Сортировка
         */

        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));

        $this->pager->init();
    }

    public function executeNoncount(sfWebRequest $request) {

        $this->pager = new sfDoctrinePager('Product', 30);
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')
                ->where("count <1");
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    public function executeNew(sfWebRequest $request) {
        $this->pager = new sfDoctrinePager('Product', 30);
        if (csSettings::get('logo_new') == "") {
            $day_logo_new = 7;
        } else {
            $day_logo_new = csSettings::get('logo_new');
        }
        $idProductsCount0Children = "";
        $productsCount0Children = ProductTable::getInstance()->createQuery()->leftJoin("Product.Parent par")->where("count>0")->addWhere("par.count=0")->addWhere("is_public=1")
                        /* ->addWhere("(generalcategory_id IN (" . $this->category->getId() . $idChildrenCategory . "))") */->addWhere("parents_id is not null")->groupBy("parents_id")->execute();
        foreach ($productsCount0Children as $productCount0Children)
            $idProductsCount0Children.="," . $productCount0Children->getParentsId();
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')
                /* ->addWhere('parents_id IS NULL') */
                ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
                ->addWhere('is_public = \'1\'')
                ->addWhere("DATEDIFF(NOW(),created_at) < " . $day_logo_new . "")
                ->addOrderBy("(count>0) DESC")
                ->addOrderBy("find_in_set(id, '" . $idProductsCount0Children . "') DESC")
                ->addOrderBy("created_at desc");
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    public function executeNewprod(sfWebRequest $request) {
        $this->catalog = CatalogTable::getInstance()->findOneBySlug(array($request->getParameter('slug')));
        if (empty($this->catalog))
            $this->catalog = CatalogTable::getInstance()->findOneById(array($request->getParameter('slug')));

        if (empty($this->catalog)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Catalog' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->catalog = Doctrine_Core::getTable('Catalog')->findOneById($oldSlug->getDopid());
                return $this->redirect('/catalog/' . $this->catalog->getSlug(), 301);
            }
        }
        $this->forward404Unless($this->catalog);

        $categorysId = array();
        foreach ($this->catalog->CategoryCatalogs->getPrimaryKeys() as $id) {
            $categorysId[] = $id;
            $category = CategoryTable::getInstance()->findOneById($id);
            $categorysId = array_merge($categorysId, $category->Children->getPrimaryKeys());
        }

        $idCategorys = implode(",", $categorysId);


        $this->pager = new sfDoctrinePager('Product', 30);
        if (csSettings::get('logo_new') == "") {
            $day_logo_new = 7;
        } else {
            $day_logo_new = csSettings::get('logo_new');
        }
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')
                ->leftJoin('p.CategoryProduct c')
                ->addWhere('parents_id IS NULL')
                ->addWhere('is_public = \'1\'')
                ->addWhere('count > \'0\'')
                // ->addWhere("DATEDIFF(NOW(),created_at) < " . $day_logo_new . "")
                ->addwhere("(c.category_id IN (" . $idCategorys . "))")
                ->addOrderBy("created_at desc");
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    public function executeRelatecategory(sfWebRequest $request) {
        $this->catalog = CatalogTable::getInstance()->findOneBySlug(array($request->getParameter('slug')));
        if (empty($this->catalog))
            $this->catalog = CatalogTable::getInstance()->findOneById(array($request->getParameter('slug')));

        if (empty($this->catalog)) {
            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Catalog' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
            if ($oldSlug) {
                $this->catalog = Doctrine_Core::getTable('Catalog')->findOneById($oldSlug->getDopid());
                return $this->redirect('/catalog/' . $this->catalog->getSlug(), 301);
            }
        }
        $this->forward404Unless($this->catalog);

        $categorysId = array();
        foreach ($this->catalog->CategoryCatalogs->getPrimaryKeys() as $id) {
            $categorysId[] = $id;
            $category = CategoryTable::getInstance()->findOneById($id);
            $categorysId = array_merge($categorysId, $category->Children->getPrimaryKeys());
        }

        $idCategorys = implode(",", $categorysId);


        $this->pager = new sfDoctrinePager('Product', 30);
        if (csSettings::get('logo_new') == "") {
            $day_logo_new = 7;
        } else {
            $day_logo_new = csSettings::get('logo_new');
        }
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')
                ->leftJoin('p.CategoryProduct c')
                ->addWhere('parents_id IS NULL')
                ->addWhere('is_public = \'1\'')
                ->addWhere('count > \'1\'')
                ->addwhere("(c.category_id IN (" . $idCategorys . "))")
                ->addOrderBy("sortpriority desc")
                ->addOrderBy("countsell desc");

        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    public function executeChangechildren(sfWebRequest $request) {

        /*  $product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('id')));

          $this->product = $product; */
        $this->id = $request->getParameter('id');
    }

}
