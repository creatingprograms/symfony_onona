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
  const PRODUCTS_PER_PAGE=24;

  public function executeFeb14(sfWebRequest $request) { //14 февраля
    $this->isSale = false;
    global $isTest;
    if ($isTest) $slug = 'analnye_igrushki';
    else $slug = 'feb_14';

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();

    // $timerCategory = sfTimerManager::getTimer('Action: Загрузка категорий');
    $this->categorys = $q->execute("SELECT children.id, children.name, children.slug, parent.id as parent_id, parent.name as parent_name , parent.slug as parent_slug, cat.id as this_id, cat.name as this_name, cat.h1 as this_h1, cat.content as this_content , cat.slug as this_slug, cat.filtersnew as filters, cat.minprice as minPrice, cat.maxprice as maxPrice, cat.tags as tags "
    . ", cat.prodid_priority as prodid_priority, cat.title, cat.keywords, cat.description, cat.canonical  "
    . "FROM category as cat "
    . "LEFT JOIN category AS children ON children.parents_id = cat.id "
    . "LEFT JOIN category AS parent ON parent.id = cat.parents_id "
    . "WHERE cat.slug =  ? AND cat.is_public=1 "
    . "ORDER BY cat.position ASC ", array($slug))->fetchAll(Doctrine_Core::FETCH_UNIQUE);


    if (count($this->categorys) == 0) $this->forward404();

    $tmp = explode(',', end($this->categorys)['prodid_priority']); //Получаем приоритетные позиции

    foreach ($tmp as $value) //
      if (intval($value) > 0)
        $productsPrior[] = intval($value);
    $this->productsPrior = $productsPrior;

    // $categoryId_root = end($this->categorys)['parent_id'] != "" ? end($this->categorys)['parent_id'] : end($this->categorys)['this_id'];
    // if ($categoryId_root) {
    //   $catalogId = end($q->execute("SELECT * FROM category_catalog where category_id=" . $categoryId_root . ";")->fetchAll(Doctrine_Core::FETCH_ASSOC));
    // }
    // $this->catalog = $catalogId;
    // if (isset($catalogId['catalog_id']) && $catalogId['catalog_id']) $this->catalog = CatalogTable::getInstance()->findOneById($catalogId['catalog_id']);
    if (array_keys($this->categorys)[0] != "") {
      $categorysId = implode(",", array_merge(array_keys($this->categorys), array(end($this->categorys)['this_id'])));
    } else {
      $categorysId = end($this->categorys)['this_id'];
    }


    // if (is_object($this->catalog)) $breadcrumbs[] = ['link' => '/catalog/' . $this->catalog->getSlug(), 'text' => $this->catalog->getMenuName()];

    // if (end($this->categorys)['parent_name'] != "") {
    //   $breadcrumbs[] = ['link' => '/category/' . end($this->categorys)['parent_slug'], 'text' => end($this->categorys)['parent_name']];
    // }
    // $this->breadcrumbs = $breadcrumbs;

    //далее формируем массив как в каталоге
    $this->sortOrder = $request->getParameter('sortOrder');
    $this->direction = $request->getParameter('direction');
    $this->filters = $request->getParameter('filters');
    $this->price = str_replace(' ', '', $request->getParameter('Price'));
    $this->filter_service_category = $request->getParameter('filter_service_category', false);
    $this->page = $request->getParameter('page', 1);

    $this->shops = $request->getParameter('shops');

    $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
    $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');

    $addleftJoin = "";
    $addOrderBy = $this->applySortCatalog(true);

    $timerSort->addTime();

    $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');
    $addWhere = "";
    $addWhereParams = array();
    if ($this->price['from']) {
      $addWhere = $addWhere . ' and price>=? and price<=?';
      $addWhereParams[] = $this->price['from'];
      $addWhereParams[] = $this->price['to'];
    }
    if ($this->isStock) {
      $addWhere = $addWhere . ' and count>0';
    }

    $filtersDB = unserialize(end($this->categorys)['filters']);

    $productsIDFilters = $this->getFilters($filtersDB);
    if (!empty($this->shops)) { //если есть фильтр по магазину
      $productsIDFilters = $this->applyShopsFilter($productsIDFilters);

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
    if ($this->filter_service_category) {
      $sqlBody = "SELECT `id` FROM `category` WHERE `slug`='" . $this->filter_service_category . "'";
      $catId = $q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
      if (isset($catId[0]['id'])) {
        $catId = $catId[0]['id'];
        $this->productsCount = $q->execute("SELECT product.id  "
        . "FROM product "
        . "LEFT JOIN category_product as cp on product.id=product_id "
        . $addleftJoin
          . "WHERE  cp.category_id IN (" . $categorysId . ") "
          . "and product.is_public='1' "
          . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1'))"
          . $addWhere, $addWhereParams)
          ->fetchAll(Doctrine_Core::FETCH_ASSOC);
        $this->productsCount = sizeof($this->productsCount);
      } else {
        $catId = false;
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
      }
    } else {
      $catId = false;
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
    }

    //Пагинация

    $this->pagesCount = ceil(($this->productsCount) / self::PRODUCTS_PER_PAGE);
    if (!$this->pagesCount && (!empty($this->shops) || !empty($this->filters))) $this->pagesCount = 1;

    if ($this->page > $this->pagesCount or $this->page < 1) {
      $this->page = 1;
      $this->forward404Unless(false);
    }
    $pagesLimitStart = ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) <= $this->productsCount ? ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) : ($this->page * self::PRODUCTS_PER_PAGE - 2 * self::PRODUCTS_PER_PAGE);
    $pagesLimitEnd = self::PRODUCTS_PER_PAGE;
    $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;
    //Конец пагинации


    $this->ids = array_keys(
      $q->execute("SELECT product.id "
      . "FROM product "
      . "LEFT JOIN category_product as cp on product.id=product_id "
      . $addleftJoin
        . "WHERE  cp.category_id IN (" . $categorysId . ") "
        . "and product.is_public='1' "
        . "" . $addWhere . " "
        . "group by product.id "
        . "ORDER BY "
        . $addOrderBy
        . (!$catId ? $pagesLimit : ''), $addWhereParams)
        ->fetchAll(Doctrine_Core::FETCH_UNIQUE)
    );
    if ($catId) {
      $this->ids[] = -1; //чтобы не был пустой IN
      $this->ids = array_keys(
        $q->execute("SELECT product.id "
        . "FROM product "
        . "LEFT JOIN category_product as cp on product.id=product_id "
        . "WHERE  cp.category_id =" . $catId . " "
        . "AND product.id IN(" . implode(', ', $this->ids) . ") "
        . $pagesLimit)
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE)
      );
    };

    $timerProducts->addTime();
  }

  public function executeForHim(sfWebRequest $request) {
    $this->slug = trim($request->getPathInfo(), '/');
    $this->lowerSlug = strtolower($this->slug);
    $this->pageForHim = PageTable::getInstance()->createQuery()->where('slug = ?', $this->slug)->addWhere('is_public=1')->execute()->get(0);
    $this->forward404Unless($this->pageForHim->exists());

    /* $category = CategoryTable::getInstance()->createQuery()->where('slug = ?', 'preparaty-dlya-nego')->execute()->get(0)->toArray();
    $this->categoriesIds = [$category['id']]; */

    $catalogForHim = CatalogTable::getInstance()->createQuery()->where('slug = ?', 'sex-igrushki-dlya-muzhchin')->addWhere('is_public=1')->execute()->get(0)->toArray();
    $this->forward404Unless($catalogForHim);
    $this->categoriesIds = array_map(function ($item) {
      return $item['category_id'];
    }, CategoryCatalogTable::getInstance()->createQuery()->where('catalog_id = ?', $catalogForHim['id'])->execute()->toArray());

    $this->categoriesIdsStr = implode(',', $this->categoriesIds);

    $this->isSale = false;
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();

    $timerCategory = sfTimerManager::getTimer('Action: Загрузка категорий');
    $this->categorys = $q->execute("SELECT children.id, children.name, children.slug, parent.id as parent_id, parent.name as parent_name , parent.slug as parent_slug, cat.id as this_id, cat.name as this_name, cat.h1 as this_h1, cat.content as this_content , cat.slug as this_slug, cat.filtersnew as filters, cat.minprice as minPrice, cat.maxprice as maxPrice, cat.tags as tags "
                    .", cat.prodid_priority as prodid_priority, cat.title, cat.keywords, cat.description, cat.canonical  "
                    . "FROM category as cat "
                    . "LEFT JOIN category AS children ON children.parents_id = cat.id "
                    . "LEFT JOIN category AS parent ON parent.id = cat.parents_id "
                    . "WHERE cat.id IN (?) AND cat.is_public=1 "
                    . "ORDER BY cat.position ASC ", array($this->categoriesIdsStr))->fetchAll(Doctrine_Core::FETCH_UNIQUE);

    $categoryId_root = end($this->categorys)['parent_id'] != "" ? end($this->categorys)['parent_id'] : end($this->categorys)['this_id'];
    if($categoryId_root){
      $catalogId=end($q->execute("SELECT * FROM category_catalog where category_id=".$categoryId_root.";")->fetchAll(Doctrine_Core::FETCH_ASSOC));
    }
    $this->catalog=$catalogId;
    if(isset($catalogId['catalog_id']) && $catalogId['catalog_id']) $this->catalog=CatalogTable::getInstance()->findOneById($catalogId['catalog_id']);
    if (array_keys($this->categorys)[0] != "") {
      $categorysId = implode(",", array_merge(array_keys($this->categorys), array(end($this->categorys)['this_id'])));
    } else {
      $categorysId = end($this->categorys)['this_id'];
    }


    if(is_object($this->catalog)) $breadcrumbs[]= ['link' => '/catalog/'.$this->catalog->getSlug(), 'text' => $this->catalog->getMenuName()];

    /* if (end($this->categorys)['parent_name'] != "") {
      $breadcrumbs[]= ['link' => '/category/'.end($this->categorys)['parent_slug'], 'text' => end($this->categorys)['parent_name']] ;
    }
    $this->breadcrumbs=$breadcrumbs; */

    //далее формируем массив как в каталоге
    $this->sortOrder=$request->getParameter('sortOrder');
    $this->direction = $request->getParameter('direction');
    $this->filters = $request->getParameter('filters');
    $this->price = str_replace(' ', '', $request->getParameter('Price'));
    $this->filter_service_category = $request->getParameter('filter_service_category', false);
    $this->page = $request->getParameter('page', 1);

    $this->shops = $request->getParameter('shops');

    $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
    $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');

    $addleftJoin = "";
    $addOrderBy = $this->applySortCatalog(true);

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

    $productsIDFilters = $this->getFilters($filtersDB);
    if(!empty($this->shops)){//если есть фильтр по магазину
      $productsIDFilters=$this->applyShopsFilter($productsIDFilters);
      // die(__DIR__.__FILE__.'|'.__LINE__.'<pre>'.print_r([$productsIDFilters,$addleftJoin, $addWhere,  $addWhereParams], true).'</pre>');

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
                    . "WHERE  cp.category_id IN (" . $this->categoriesIdsStr . ") "
                    . "and product.is_public='1' "
                    . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1'))"
                    . $addWhere, $addWhereParams)
      ->fetch(Doctrine_Core::FETCH_ASSOC);
    $this->productsCount = $this->productsCount['count'];

    //Пагинация

    $this->pagesCount = ceil(($this->productsCount) / self::PRODUCTS_PER_PAGE);
    if(!$this->pagesCount && (!empty($this->shops) || !empty($this->filters))) $this->pagesCount=1;

    if ($this->page > $this->pagesCount or $this->page < 1) {
        $this->page = 1;
        $this->forward404Unless(false);
    }
    $pagesLimitStart = ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) <= $this->productsCount ? ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) : ($this->page * self::PRODUCTS_PER_PAGE - 2*self::PRODUCTS_PER_PAGE);
    $pagesLimitEnd = self::PRODUCTS_PER_PAGE;
    $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;
    //Конец пагинации

    $this->ids =array_keys(
        $q->execute("SELECT product.id "
                . "FROM product "
                . "LEFT JOIN category_product as cp on product.id=product_id "
                . $addleftJoin
                . "WHERE  cp.category_id IN (" . $this->categoriesIdsStr . ") "
                . "and product.is_public='1' "
                // . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1')) "
                . "" . $addWhere . " "
                . "group by product.id "
                . "ORDER BY "
                . $addOrderBy
                // . ($catId ? $pagesLimit : ''), $addWhereParams)
                . (!$catId ? $pagesLimit : ''), $addWhereParams)
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE)
    );
    // die('<pre>' . print_r($addOrderBy, true));
    // if(isset($_GET['ildebug'])) die('!<pre>'.print_r([$catId, $pagesLimit, $this->ids], true).'</pre>!');
    if($catId )  {
      $this->ids[]=-1; //чтобы не был пустой IN
      $this->ids =array_keys(
          $q->execute("SELECT product.id "
                  . "FROM product "
                  . "LEFT JOIN category_product as cp on product.id=product_id "
                  . "WHERE  cp.category_id =" . $catId . " "
                  . "AND product.id IN(".implode(', ', $this->ids).") "
                  . $pagesLimit)
            ->fetchAll(Doctrine_Core::FETCH_UNIQUE)
      );
      // die('<pre>'.print_r($this->ids, true));
    };

    $timerProducts->addTime();
  }

  public function executeStock(sfWebRequest $request) {//Остатки для маркета
    // die ('----------------');
    if($request->getParameter('debug')=='Y'){
      if($request->getParameter('warehouseId')) $warehouseId=$request->getParameter('warehouseId');
      if(sizeof($_GET['skus'])) $skus=$_GET['skus'];
    }
    else{//Берем данные из php_input
      $postData = file_get_contents('php://input');
      $jsonData = json_decode($postData, true);
      if(isset($jsonData->warehouseId)) $warehouseId=$jsonData->warehouseId;
      if(isset($jsonData->skus)) $skus=$jsonData->skus;
      if(isset($jsonData['warehouseId'])) $warehouseId=$jsonData['warehouseId'];
      if(isset($jsonData['skus'])) $skus=$jsonData['skus'];
    }

    file_put_contents(
      $_SERVER['DOCUMENT_ROOT'].'/../stock.log',
      "\n\n----executeStock----------- ".date('d.m.Y H:i:s')." ---------------\n".
      // print_r(['warehouseId'=> $warehouseId, 'skus'=> $skus, 'postData'=> $postData, 'jsonData' => $jsonData], true).
      "",
      FILE_APPEND
    );

    if(empty($warehouseId) || empty($skus)) {
        header('400 Bad Request', false, 400);
        die(
          json_encode([
            'error' => (empty($warehouseId) ? 'warehouseId required parameter. ' : '').(empty($skus) ? 'skus required parameter. ' : '')
          ])
        );
    }
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $skus[]=-1;
    $sqlIn="'".implode("', '", $skus)."'";
    $sqlBody="SELECT `id`, `code`, `count`, `updated_at` FROM `product` WHERE `code` IN($sqlIn)";
    $products=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    foreach ($products as $product) {
      $data['skus'][]=[
        'sku'=> $product['code'],
        'warehouseId' => $warehouseId,
        'items' => [[
          'type' => 'FIT',
          'count' => $product['count'] > 1 ? $product['count'] - 1 : 0,
          'updatedAt' => date('c', time()-4*60*60),
          // 'updatedAt' => date('c', strtotime($product['updated_at'])),
        ]],
      ];
    }
    // die('<pre>'.print_r($data, true));
    if(!sizeof($data) ){
      file_put_contents(
        $_SERVER['DOCUMENT_ROOT'].'/../stock.log',
        "\n\n----empty----------- ".date('d.m.Y H:i:s')." ---------------\n".
         print_r(['json' => $data, 'skus' => $skus], true),
        // print_r(['data' => $data, 'json' => json_encode($data)], true),
        FILE_APPEND
      );
      $data['skus']=[];
    }
    header('Content-Type: application/json');
    die(json_encode($data));
  }

  private function getPriorBrandsOrderClause($fromCatalog = false){
    // select * from dop_info where (dicategory_id = 10 or dicategory_id =19) and value like '%kanik%'  limit 30;
    /*if ($fromCatalog)
      $brandsList = [
        1406 => 'REAL',
        844 => 'JO system, США',
        44 => 'We Vibe, Канада',
        1240 => 'Satisfyer, Германия',
        1296 => 'Shots toys, Нидерланды',
        1797 => 'Shots Toys', //Коллекция, но пофиг
        841 => 'California Exotic Novelties, США',
        1828 => 'Amor El',
      ];
    else */
    $brandsList = [
      1406 => 'REAL',
      1838 => 'EROZON',
      827 =>  'Anasteisha',
      1938 => 'Anasteisha',
      844 =>  'JO system, США',
      1296 => 'Shots toys, Нидерланды',
      1797 => 'Shots Toys', //Коллекция, но пофиг
      1779 => 'EDC',
      841 =>  'California Exotic Novelties, США',
      44 =>   'We Vibe, Канада',
      1085 => 'Womanizer',
      861 =>  'Funfactory',
      933 =>  'Kanikule', //коллекции
      1231 => 'Kanikule', //коллекции
      1290 => 'Kanikule', //коллекции
      1605 => 'Kanikule', //коллекции
      1942 => 'Quanli Kong',
      1828 => 'Amor El',
      1930 => 'Arcwave',
      1921 => 'Orctan',
      1685 => 'Snailvibe',
      1854 => 'Dreamlove',
    ];
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sqlBody = "SELECT `product_id` FROM `dop_info_product` WHERE `dop_info_id` IN(" . implode(', ', array_keys($brandsList)) . ")";
    $result = $q->execute($sqlBody);
    $prIds = array_keys($result->fetchAll(Doctrine_Core::FETCH_UNIQUE));

    if(is_array($prIds) && !empty($prIds)){
      $orderByClause = ($fromCatalog ? "product." : '') . "id IN (" . implode(', ', $prIds) . ") DESC";
      if($fromCatalog) $orderByClause.= ", ";
    }


    return $orderByClause;
  }

  private function applySortCatalog($addBrandsSort=false, $artSort=false){
    // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([], true) . '</pre>');
    $addOrderBy = "(product.count>0) DESC, ";

    if(!$this->sortOrder || $this->sortOrder=='rating') {
      $this->sortOrder = 'rating';
      $this->direction = "desc";
      if(sizeof($this->productsPrior)) $addOrderBy.= "FIELD(product.id, ".implode(', ', array_reverse($this->productsPrior)).") DESC, ";

      if($addBrandsSort){//Выводим сначала товары определенных брендов
        $addOrderBy.= $this->getPriorBrandsOrderClause(true);
      }
      $addOrderBy .= " is_new_on_market desc, is_new_on_site desc";
    }

    if ($this->sortOrder == "date") {
        $newsList=csSettings::get('optimization_newProductId');
        if ($newsList != "") {
          $addOrderBy.= " product.id IN(".$newsList.") DESC, ";
        }
        $addOrderBy.= " product.created_at desc";
    }
    if ($this->sortOrder == "name") {
        if ($this->direction == "asc") {
            $addOrderBy.= "product.name asc";
        } else {
            $addOrderBy.= "product.name desc";
        }
    }
    if ($this->sortOrder == "price") {
        if ($this->direction == "asc") {
            $addOrderBy.= "product.price asc";
        } else {
            $addOrderBy.= "product.price desc";
        }
    }
    /*
    if ($this->sortOrder == "comments") {

        $addleftJoin = " LEFT JOIN comments as com on product.id = com.product_id and com.is_public = '1' ";
        $addOrderBy.= "count(com.id) desc";
    }
    */
    if ($this->sortOrder == "actions") {
        $addOrderBy.= "(product.step <> \"\" and product.Endaction <> \"\" and product.discount>0 and product.discount is not null) DESC";
        $addOrderBy.= ", (product.step <> \"\" and product.Endaction <> \"\" and product.bonuspay>0 and product.bonuspay is not null) DESC";
        if ($this->direction == "desc") {
            $addOrderBy.= ", product.position desc";
        } else {
            $this->direction = "asc";
            $addOrderBy.= ", product.position asc";
        }
    }
    if($this->sortOrder == "sortorder"){
        $addOrderBy.= "product.sortpriority desc, product.countsell desc, product.price desc";
    }

    if($addOrderBy=='') $addOrderBy='product.sortpriority desc ';
    // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$addOrderBy], true) . '</pre>');
    return $addOrderBy;
  }

  private function applySort(&$query, $addBrandsSort = false ){
    $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
    $addSort = "(p.count>0) DESC ";
    $query->addOrderBy("(p.count>0) DESC");

    /* начало сортировки */

    if(!$this->sortOrder) {
      $this->sortOrder = 'rating';
      $this->direction = "desc";
    }

    if ($this->sortOrder == "date") {
      if (sizeof($this->productsPrior)) {
        $query->addOrderBy("FIELD(p.id, " . implode(', ', array_reverse($this->productsPrior)) . ") DESC");
        $addSort .= ", sort by ";
      }
      if ($addBrandsSort) {
        $addSort .= ", sort by brands";
        $byBrands = $this->getPriorBrandsOrderClause();

        $query->addOrderBy($byBrands);

      }
      if ($this->direction == "asc") {
        $query->addOrderBy("p.created_at asc");
      } else {
        $query->addOrderBy("p.created_at desc");
        $addSort .= " p.created_at desc";
      }

    }
    if ($this->sortOrder == "price") {
        if ($this->direction == "asc") {
            $query->addOrderBy("price asc");
        } else {
          // die('foo bar');
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
      if (sizeof($this->productsPrior)) {
        $query->addOrderBy("FIELD(p.id, " . implode(', ', array_reverse($this->productsPrior)) . ") DESC");
        $addSort .= ", " . "FIELD(p.id, " . implode(', ', array_reverse($this->productsPrior)) . "), ";
      }
      if($addBrandsSort) {
        $query->addOrderBy($this->getPriorBrandsOrderClause());
        $addSort .= $this->getPriorBrandsOrderClause();
      }
      $query->addOrderBy("is_new_on_market desc");
      $query->addOrderBy("is_new_on_site desc");
      $addSort .= "is_new_on_market desc";
      $addSort .= "is_new_on_site desc";
      // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$addSort], true) . '</pre>');
    }
    if ($this->sortOrder == "comments") {
      //Возможно так не работает, но сортировки по комментариям пока нет ;)
        if ($this->direction == "asc") {
            $query->leftJoin("p.Comments com on p.id = com.product_id and com.is_public = '1'")->addOrderBy("count(com.id) asc")->groupBy("id");
        } else {
            $query->leftJoin("p.Comments com on p.id = com.product_id and com.is_public = '1'")->addOrderBy("count(com.id) desc")->groupBy("id");
        }
    }
    if ($this->sortOrder == "name") {
        if ($this->direction == "asc") {
            $query->addOrderBy("name asc");
        } else {
            $query->addOrderBy("name desc");
        }
    }
    if ($this->sortOrder == "actions") {
        $this->sortOrder = "actions";
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
        $this->sortOrder = "sortorder";
        $query->addOrderBy("p.sortpriority desc");
        $query->addOrderBy("p.countsell desc");
    }


    $timerSort->addTime();
    /* конец сортировки */
    // return $query;
    // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$addSort], true) . '</pre>');
  }

  private function getFilters($filtersDB){
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
    else $productsIDFilters=false;

    return $productsIDFilters;
  }

  public function executeCatalogFull(sfWebRequest $request) {//Каталог разводящая
    $page=$request->getParameter('page', 1);
    if($page<>1) $this->forward404();
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sqlBody="SELECT `id`, `name`, `img`, `description`, `slug`, `menu_name`, `img_top`, `img_bottom`, 'catalog' as `base` FROM `catalog` WHERE `is_public`=1 ORDER BY `position`";
    $res=$q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_ASSOC);
    foreach ($res as $key => $cat) {
      $sqlBody=
        "SELECT c.`id`, c.`name`, c.`slug` FROM `category_catalog` cca ".
        "LEFT JOIN `category` c ON c.`id`=cca.`category_id` ".
        // "JOIN `category` cc ON c.`parents_id`=cc.`id` ".
        "WHERE cca.`catalog_id`=".$cat['id']." AND c.`is_public`=1 ".
        "ORDER BY c.`position` ".
        "";


      $category=$q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_ASSOC);
      foreach ($category as $key2 => $value) {
        $sqlBody="SELECT count(id) as `count_id` FROM `category` WHERE `parents_id`=".$value['id']." AND `is_public`=1 ORDER BY `position` ";
        $sub=$q->execute($sqlBody)->Fetch(Doctrine_Core::FETCH_ASSOC);
        $category[$key2]['count']=$sub['count_id'];
        $res[$key]['sub']=$category;
      }
    }
    $res[]=[
      'slug'=>'dlya_novichkov',
      'base' => 'category',
      'img_hard'=> '/frontend/images_new/cat_trans/9.png',
      'menu_name' => 'Новичкам',
      'sub' => [
        [
          'name' => 'Для мужчин',
          'slug' => 'dlya_novichkov?novice=for_her',
          'count'=> 0,
        ],
        [
          'name' => 'Для женщин',
          'slug' => 'dlya_novichkov?novice=for_she',
          'count'=> 0,
        ],
        [
          'name' => 'Для двоих',
          'slug' => 'dlya_novichkov?novice=for_pairs',
          'count'=> 0,
        ],
      ],
    ];
    $this->catalog=$res;
    // die("<hr><pre>".print_r([$res], true)."</pre><hr>");
  }

  public function executeShowShopStocks(sfWebRequest $request) {//Товар в магазине
    if($request->getParameter('slug')!=trim(strtolower($request->getParameter('slug'))))
      return $this->redirect('/shop-stocks/' . trim(strtolower($request->getParameter('slug'))), 301);
    $this->shop = Doctrine_Core::getTable('Shops')->findOneBySlug(array($request->getParameter('slug')));
    $this->forward404Unless($this->shop);
    if(!$this->shop->getIsActive()) unset($this->shop);
    $this->forward404Unless($this->shop);
    if(empty($this->shop->getId1c())) unset($this->shop);
    $this->forward404Unless($this->shop);

    $this->sortOrder=$request->getParameter('sortOrder');
    $this->direction = $request->getParameter('direction');

    $addOrderBy = $this->applySortCatalog(true);

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sqlBody = "SELECT COUNT(*) as `shopstock` FROM `shop_stocks` WHERE `shop_id`='".$this->shop->getId1c()."'";
    $result=$q->execute($sqlBody);
    $table=$result->fetch(Doctrine_Core::FETCH_ASSOC);
    // if(!empty($table['shopstock']) && $table['shopstock']>0)
    $this->productsCount = $table['shopstock'];

    $this->page = $request->getParameter('page', 1);
    $this->pagesCount = ceil(($this->productsCount) / self::PRODUCTS_PER_PAGE);
    $this->baseLink='/shop-stocks/'.$this->shop->getSlug();

    if ($this->page > $this->pagesCount or $this->page < 1) {
        $this->page = 1;
        $this->forward404Unless(false);
    }
    $pagesLimitStart = ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) <= $this->productsCount ? ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) : ($this->page * self::PRODUCTS_PER_PAGE - 2*self::PRODUCTS_PER_PAGE);
    $pagesLimitEnd = self::PRODUCTS_PER_PAGE;
    $pagesLimit = " LIMIT " . $pagesLimitStart . "," . $pagesLimitEnd;

    $sqlBody="SELECT product.`id` FROM `product` LEFT JOIN `shop_stocks` ss ON product.`id`=ss.`product_id` WHERE ss.`shop_id`='".$this->shop->getId1c()."' AND product.`is_public`=1 AND product.`count` > 0 ORDER BY ".$addOrderBy. $pagesLimit;
    $result=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    $this->ids = array_keys($result);
    // die(__DIR__.__FILE__.'|'.__LINE__.'<pre>'.print_r([$result, $this->ids, $sqlBody], true).'</pre>');

  }

  public function executeCategory(sfWebRequest $request) {//Категория
    if(strtolower($request->getParameter('slug'))=='service_all') return $this->redirect('/catalog');
    if(strtolower($request->getParameter('slug'))=='feb_14') return $this->redirect('/feb_14');
    if($request->getParameter('slug')!=trim(strtolower($request->getParameter('slug'))))
      return $this->redirect('/category/' . trim(strtolower($request->getParameter('slug'))), 301);

    if($request->getParameter('slug')=='skidki_do_60_percent') $this->isSale=true;
    else $this->isSale=false;
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();

    $timerCategory = sfTimerManager::getTimer('Action: Загрузка категорий');
    $this->categorys = $q->execute("SELECT children.id, children.name, children.slug, parent.id as parent_id, parent.name as parent_name , parent.slug as parent_slug, cat.id as this_id, cat.name as this_name, cat.h1 as this_h1, cat.content as this_content , cat.slug as this_slug, cat.filtersnew as filters, cat.minprice as minPrice, cat.maxprice as maxPrice, cat.tags as tags "
                    .", cat.prodid_priority as prodid_priority, cat.title, cat.keywords, cat.description, cat.canonical  "
                    . "FROM category as cat "
                    . "LEFT JOIN category AS children ON children.parents_id = cat.id "
                    . "LEFT JOIN category AS parent ON parent.id = cat.parents_id "
                    . "WHERE cat.slug =  ? AND cat.is_public=1 "
                    . "ORDER BY cat.position ASC ", array($request->getParameter('slug')))->fetchAll(Doctrine_Core::FETCH_UNIQUE);


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

    $tmp=explode(',', end($this->categorys)['prodid_priority']);//Получаем приоритетные позиции

    foreach ($tmp as $key => $value) //
      if(intval($value)>0)
        $productsPrior[]=intval($value);
    $this->productsPrior=$productsPrior;

    $categoryId_root = end($this->categorys)['parent_id'] != "" ? end($this->categorys)['parent_id'] : end($this->categorys)['this_id'];
    if($categoryId_root){
      $catalogId=end($q->execute("SELECT * FROM category_catalog where category_id=".$categoryId_root.";")->fetchAll(Doctrine_Core::FETCH_ASSOC));
    }
    $this->catalog=$catalogId;
    if(isset($catalogId['catalog_id']) && $catalogId['catalog_id']) $this->catalog=CatalogTable::getInstance()->findOneById($catalogId['catalog_id']);
    if (array_keys($this->categorys)[0] != "") {
        $categorysId = implode(",", array_merge(array_keys($this->categorys), array(end($this->categorys)['this_id'])));
    } else {
        $categorysId = end($this->categorys)['this_id'];
    }


    if(is_object($this->catalog)) $breadcrumbs[]= ['link' => '/catalog/'.$this->catalog->getSlug(), 'text' => $this->catalog->getMenuName()];

    if (end($this->categorys)['parent_name'] != "") {
      $breadcrumbs[]= ['link' => '/category/'.end($this->categorys)['parent_slug'], 'text' => end($this->categorys)['parent_name']] ;
    }
    $this->breadcrumbs=$breadcrumbs;

    //далее формируем массив как в каталоге
    $this->sortOrder=$request->getParameter('sortOrder');
    $this->direction = $request->getParameter('direction');
    $this->filters = $request->getParameter('filters');
    $this->price = str_replace(' ', '', $request->getParameter('Price'));
    $this->filter_service_category = $request->getParameter('filter_service_category', false);
    $this->page = $request->getParameter('page', 1);

    $this->shops = $request->getParameter('shops');

    $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
    $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');

    $addleftJoin = "";
    $addOrderBy = $this->applySortCatalog(true);

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

    $productsIDFilters = $this->getFilters($filtersDB);
    if(!empty($this->shops)){//если есть фильтр по магазину
      $productsIDFilters=$this->applyShopsFilter($productsIDFilters);
      // die(__DIR__.__FILE__.'|'.__LINE__.'<pre>'.print_r([$productsIDFilters,$addleftJoin, $addWhere,  $addWhereParams], true).'</pre>');

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
    if($this->filter_service_category){
      $sqlBody="SELECT `id` FROM `category` WHERE `slug`='".$this->filter_service_category."'";
      $catId=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
      if(isset($catId[0]['id'])) {
        $catId=$catId[0]['id'];
        $this->productsCount = $q->execute("SELECT product.id  "
            . "FROM product "
            . "LEFT JOIN category_product as cp on product.id=product_id "
            . $addleftJoin
            . "WHERE  cp.category_id IN (" . $categorysId . ") "
            . "and product.is_public='1' "
            . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1'))"
            . $addWhere, $addWhereParams)
        ->fetchAll(Doctrine_Core::FETCH_ASSOC);
      $this->productsCount = sizeof($this->productsCount);
      }
      else {
        $catId=false;
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
      }
    }
    else {
      $catId=false;
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
    }

    //Пагинация

    $this->pagesCount = ceil(($this->productsCount) / self::PRODUCTS_PER_PAGE);
    if(!$this->pagesCount && (!empty($this->shops) || !empty($this->filters))) $this->pagesCount=1;

    if ($this->page > $this->pagesCount or $this->page < 1) {
        $this->page = 1;
        $this->forward404Unless(false);
    }
    $pagesLimitStart = ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) <= $this->productsCount ? ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) : ($this->page * self::PRODUCTS_PER_PAGE - 2*self::PRODUCTS_PER_PAGE);
    $pagesLimitEnd = self::PRODUCTS_PER_PAGE;
    $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;
    //Конец пагинации


    $this->ids =array_keys(
        $q->execute("SELECT product.id "
                . "FROM product "
                . "LEFT JOIN category_product as cp on product.id=product_id "
                . $addleftJoin
                . "WHERE  cp.category_id IN (" . $categorysId . ") "
                . "and product.is_public='1' "
                // . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1')) "
                . "" . $addWhere . " "
                . "group by product.id "
                . "ORDER BY "
                . $addOrderBy
                // . ($catId ? $pagesLimit : ''), $addWhereParams)
                . (!$catId ? $pagesLimit : ''), $addWhereParams)
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE)
    );
    // die('<pre>' . print_r($addOrderBy, true));
    // if(isset($_GET['ildebug'])) die('!<pre>'.print_r([$catId, $pagesLimit, $this->ids], true).'</pre>!');
    if($catId )  {
      $this->ids[]=-1; //чтобы не был пустой IN
      $this->ids =array_keys(
          $q->execute("SELECT product.id "
                  . "FROM product "
                  . "LEFT JOIN category_product as cp on product.id=product_id "
                  . "WHERE  cp.category_id =" . $catId . " "
                  . "AND product.id IN(".implode(', ', $this->ids).") "
                  . $pagesLimit)
            ->fetchAll(Doctrine_Core::FETCH_UNIQUE)
      );
      // die('<pre>'.print_r($this->ids, true));
    };

    $timerProducts->addTime();


    // Видимо AJAX
    /*
    if ($request->getParameter("loadProducts")) {
        $this->setTemplate("loadProducts");
    }*/


  }

  public function executeCalalogTop20(sfWebRequest $request) {//Каталог, клон главной
    if($request->getParameter('slug')!=trim(strtolower($request->getParameter('slug'))))
      return $this->redirect('/catalog/' . trim(strtolower($request->getParameter('slug'))) . '/top20', 301);

    $this->catalog = CatalogTable::getInstance()->findOneBySlug(array($request->getParameter('slug')));
    if (empty($this->catalog)){
      $this->catalog = CatalogTable::getInstance()->findOneById(array($request->getParameter('slug')));
      if(is_object($this->catalog))
        return $this->redirect('/catalog/' . trim(strtolower($this->catalog->getSlug())) . '/top20', 301);
    }

    if (empty($this->catalog)) {
        $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Catalog' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
        if ($oldSlug) {
            $this->catalog = Doctrine_Core::getTable('Catalog')->findOneById($oldSlug->getDopid());
            return $this->redirect('/catalog/' . $this->catalog->getSlug() . '/top20', 301);
        }
    }
    $this->forward404Unless($this->catalog);
    // $this->catalog->setTop20List(null);

    if (empty($this->catalog->getTop20List())) $this->forward404Unless(false);
  }
  public function executeCatalog(sfWebRequest $request) {//Каталог, клон главной
    if($request->getParameter('slug')!=trim(strtolower($request->getParameter('slug'))))
      return $this->redirect('/catalog/' . trim(strtolower($request->getParameter('slug'))), 301);

    $this->catalog = CatalogTable::getInstance()->findOneBySlug(array($request->getParameter('slug')));
    if (empty($this->catalog)){
      $this->catalog = CatalogTable::getInstance()->findOneById(array($request->getParameter('slug')));
      if(is_object($this->catalog))
        return $this->redirect('/catalog/' . trim(strtolower($this->catalog->getSlug())), 301);
    }

    if (empty($this->catalog)) {
        $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Catalog' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
        if ($oldSlug) {
            $this->catalog = Doctrine_Core::getTable('Catalog')->findOneById($oldSlug->getDopid());
            return $this->redirect('/catalog/' . $this->catalog->getSlug(), 301);
        }
    }
    $this->forward404Unless($this->catalog);

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $slug = 'service-'. $request->getParameter('slug');

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

    // die('----------');
  }
  public function executeCatalogOld(sfWebRequest $request) {//Каталог, на основе сервисной категории

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
    $tmp=explode(',', $this->catalog->get('prodid_priority'));//Получаем приоритетные позиции
    foreach ($tmp as $key => $value) //
      if(intval($value)>0)
        $productsPrior[]=intval($value);
    $this->productsPrior=$productsPrior;

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();

    // die('sdfsdfsdfsdf');

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

    $categoryId_root = end($this->categorys)['parent_id'] != "" ? end($this->categorys)['parent_id'] : end($this->categorys)['this_id'];

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
    $this->price = str_replace(' ', '', $request->getParameter('Price'));
    // $this->price = $request->getParameter('Price');
    $this->page = $request->getParameter('page', 1);
    // $this->isStock = $request->getParameter('isStock', 0);
    // $this->isNovice = $request->getParameter('novice', '');
    // if(!in_array($this->isNovice, $avaibleModifacators)) $this->isNovice='';
    $this->shops = $request->getParameter('shops');

    $timerFilters->addTime();

    $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
    $addleftJoin = "";
    $addOrderBy = $this->applySortCatalog(true);

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

    $productsIDFilters = $this->getFilters($filtersDB);
    if(!empty($this->shops)){//если есть фильтр по магазину
      $productsIDFilters=$this->applyShopsFilter($productsIDFilters);
      // die(__DIR__.__FILE__.'|'.__LINE__.'<pre>'.print_r([$productsIDFilters,$addleftJoin, $addWhere,  $addWhereParams], true).'</pre>');

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
    //Пагинация
    $this->productsCount = $this->productsCount['count'];

    $this->pagesCount = ceil(($this->productsCount) / self::PRODUCTS_PER_PAGE);
    if(!$this->pagesCount && (!empty($this->shops) || !empty($this->filters))) $this->pagesCount=1;

    if ($this->page > $this->pagesCount or $this->page < 1) {
        $this->page = 1;
        $this->forward404Unless(false);
    }
    $pagesLimitStart = ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) <= $this->productsCount ? ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) : ($this->page * self::PRODUCTS_PER_PAGE - 2*self::PRODUCTS_PER_PAGE);
    $pagesLimitEnd = self::PRODUCTS_PER_PAGE;
    $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;
    //Конец пагинации
    $this->ids =array_keys(
      $q->execute("SELECT product.id "
                  . "FROM product "
                  . "LEFT JOIN category_product as cp on product.id=product_id "
                  . $addleftJoin
                  . "WHERE  cp.category_id IN (" . $categorysId . ") "
                  . "and product.is_public='1' "
                  // . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1')) "
                  . "" . $addWhere . " "
                  . "group by product.id "
                  . "ORDER BY "
                  . $addOrderBy
                  . $pagesLimit, $addWhereParams)
            ->fetchAll(Doctrine_Core::FETCH_UNIQUE));
    $timerProducts->addTime();

    /*if ($request->getParameter("loadProducts")) {
        $this->setTemplate("loadProducts");
    }*/
  }

  public function executeManufacturerindex(sfWebRequest $request) {
    $view=$request->getParameter('view', 'card');

    //Готовим массив
    $query =  Doctrine_Core::getTable('Manufacturer')->createQuery('a')
      ->where("is_public='1'")
      // ->orderBy('`image` IS NOT NULL DESC')
      ;
    if($view=='card'){
      $this->pagesize=25;
      $query->orderBy("`image` IS NOT NULL DESC,  position DESC");
    }
    else{
      $this->pagesize=10000;
      $query->orderBy("name ASC");
    }

    $this->pager = new sfDoctrinePager('manufacturer', $this->pagesize);
    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->view=$view;

  }

  public function executeCollectionindex(sfWebRequest $request) {
    $view=$request->getParameter('view', 'card');


    $query =  Doctrine_Core::getTable('Collection')->createQuery('c')
      ->where("is_public='1'");

    if($view=='card'){
      $this->pagesize=25;
      $query->orderBy("position DESC");
    }
    else{
      $this->pagesize=10000;
      $query->orderBy("name ASC");
    }


    $this->pager = new sfDoctrinePager('collection', $this->pagesize);
    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->view=$view;

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
      if(!$idFind) $this->forward404();
      $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);

      $this->dopinfo = Doctrine_Core::getTable('DopInfo')->findOneById($idFind);
      if ($this->dopinfo->getDopInfoCategory()->getName() != "Коллекция")
          $this->forward404();


      $this->filters=$request->getParameter('cat');

      $prod = "";
      if(!count($this->dopinfoProduct)) $this->forward404();

      foreach ($this->dopinfoProduct as $key => $product) {
        $tableTmp[$key]=$product->get('product_id');
      }
      $prod= 'p.id IN('.implode(', ', $tableTmp).')';

      if(sizeof($this->filters)){//Выставлен фильтр по категории
        $sqlBody="SELECT product_id FROM category_product WHERE category_id IN(".implode(', ', $this->filters).") AND product_id IN(".implode(', ', $tableTmp).")";
        $q=Doctrine_Manager::getInstance()->getCurrentConnection();
        $res=$q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_GROUP);
        $newIds=array_keys($res);
        $newIds[]=-1;
        $prod= 'p.id IN('.implode(', ', $newIds).')';
      }

      /*

      foreach ($this->dopinfoProduct as $key => $product) {
          $prod.=" p.id = '" . $product->get('product_id') . "'";
          if ($key < count($this->dopinfoProduct) - 1)
              $prod.=" or";
      }*/
      if ($prod == "") {
          $this->forward404();
      }

      $this->pager = new sfDoctrinePager('Product', self::PRODUCTS_PER_PAGE);

      $this->collection = $this->Collection;
      // $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
      $query = Doctrine_Core::getTable('Product')
              ->createQuery('p')->select("*")//->addSelect($selectDate)
              ->where($prod)
              // ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
              ->addWhere('is_public = \'1\'')
      ;

      if ($request->getParameter('sortOrder') != "") {
          sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
          $this->sortOrder = $request->getParameter('sortOrder');
      }
      if ($request->getParameter('direction') != "") {
          sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
          $this->direction = $request->getParameter('direction');
      }

      if (empty($this->sortOrder) || $this->sortOrder == 'rating') {
        $ids = explode(',', $this->collection->getPrioritySortList());

        foreach ($ids as $key => $value) //
          if (intval($value) > 0)
            $productsPrior[] = intval($value);

        if (empty($productsPrior)) $productsPrior[] = -1;
        $this->productsPrior = $productsPrior;
      }

      $this->applySort($query, false); //Сортировка всегда одинаковая, нехай будет в одном месте

      $this->pager->setQuery($query);
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
      if(count($this->pager->getLinks(2000))<$request->getParameter('page', 1)) $this->forward404();

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
    $this->filters=$request->getParameter('cat');

    $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);

    $this->dopinfo = Doctrine_Core::getTable('DopInfo')->findOneById($idFind);
    if (!$this->dopinfo)
        $this->forward404();

    if ($this->dopinfo->getDopInfoCategory()->getName() != "Бренд(ы)" && $this->dopinfo->getDopInfoCategory()->getName() != "Бренд"&& $this->dopinfo->getDopInfoCategory()->getName() != "Производитель")
        $this->forward404();

    $prod = "";
    if(!count($this->dopinfoProduct)) $this->forward404();

    foreach ($this->dopinfoProduct as $key => $product) {
      $tableTmp[$key]=$product->get('product_id');
    }
    $prod= 'p.id IN('.implode(', ', $tableTmp).')';
    if(sizeof($this->filters)){//Выставлен фильтр по категории
      $sqlBody="SELECT product_id FROM category_product WHERE category_id IN(".implode(', ', $this->filters).") AND product_id IN(".implode(', ', $tableTmp).")";
      $q=Doctrine_Manager::getInstance()->getCurrentConnection();
      $res=$q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_GROUP);
      $newIds=array_keys($res);
      $newIds[]=-1;
      $prod= 'p.id IN('.implode(', ', $newIds).')';
    }

    /*foreach ($this->dopinfoProduct as $key => $product) {
      $tableTmp[$key]=$product;
        $prod.=" p.id = '" . $product->get('product_id') . "'";
        if ($key < count($this->dopinfoProduct) - 1)
            $prod.=" or";
    }*/

    $this->pager = new sfDoctrinePager('Product', self::PRODUCTS_PER_PAGE);

    if ($this->manufacturer) {
      /*
        $idProductsCount0Children = "";
        $productsCount0Children = ProductTable::getInstance()->createQuery()->leftJoin("Product.Parent par")->where("count>0")->addWhere("par.count=0")->addWhere("is_public=1")
                      ->addWhere("parents_id is not null")->groupBy("parents_id")->execute();
        foreach ($productsCount0Children as $productCount0Children)
            $idProductsCount0Children.="," . $productCount0Children->getParentsId();
            */
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')->select("*")
                ->where($prod)
                ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
                ->addWhere('is_public = \'1\'')
                ;

        if ($request->getParameter('sortOrder') != "") {
            sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
            $this->sortOrder = $request->getParameter('sortOrder');
        }
        if ($request->getParameter('direction') != "") {
            sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
            $this->direction = $request->getParameter('direction');
        }
        if(empty($this->sortOrder) || $this->sortOrder == 'rating'){
          $ids = explode(',', $this->manufacturer->getPrioritySortList());

          foreach ($ids as $key => $value) //
          if (intval($value) > 0)
          $productsPrior[] = intval($value);

          if (empty($productsPrior)) $productsPrior[] = -1;
          $this->productsPrior = $productsPrior;
        }
        $this->applySort($query, false); //Сортировка всегда одинаковая, нехай будет в одном месте

    } else
        $this->forward404();

    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));

    $this->pager->init();
    if(count($this->pager->getLinks(2000))<$request->getParameter('page', 1)) $this->forward404();
  }

  public function executeShowSets(sfWebRequest $request) {//Наборы
    $slug=$request->getParameter('slug', 'sets');
    $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array($slug));
    $this->forward404Unless($this->category);

    $breadcrumbs[]=
      ['link'=>'/catalog', 'text' => 'Каталог'];
    $this->category->setViewsCount($this->category->getViewsCount()+1);
    $this->category->save();
    $this->breadcrumbs=$breadcrumbs;
    $this->pager = new sfDoctrinePager('Product', 240);
    $sqlBody="SELECT `product_id` FROM `category_product` where `category_id`=".$this->category->getId();
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $tableIds=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    $ids=array_keys($tableIds);

    $ids[]=-1;//Чтобы IN не оказался пустым

    $query = Doctrine_Core::getTable('Product')
            ->createQuery('p')
            ->select("*")
            ->where('count > 0')
            ->addWhere('id IN('.implode(', ', $ids).')')
            ->addWhere('is_public=1')
    ;
    if(in_array($_POST['sets-list-filtr'], ['her', 'him', 'pairs'])){
      $query->addWhere('set_'.$_POST['sets-list-filtr'].'=1');
    }
    // $this->applySort($query); //Сортировка всегда одинаковая, нехай будет в одном месте

    $this->pager->setQuery($query);
    $this->pager->setPage($page);

    $this->pager->init();
    // die('<pre>'.print_r(array_keys($tableIds), true));

  }

  public function executeShowExpress(sfWebRequest $request) {//Экспресс
    $slug=$request->getParameter('slug', 'express');
    $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array($slug));
    $this->forward404Unless($this->category);
    // $productsPerPageCount = 24;
    $page = $request->getParameter('page', 1);
    $this->page = $page;
    if ($request->getParameter('sortOrder') != "") {
        sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
        $this->sortOrder = $request->getParameter('sortOrder');
    }
    if ($request->getParameter('direction') != "") {
        sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
        $this->direction = $request->getParameter('direction');
    }
    $breadcrumbs[]=
      ['link'=>'/catalog', 'text' => 'Каталог'];
    if($slug!='express') $breadcrumbs[]=
      ['link'=>'/category/express', 'text' => 'Экспресс доставка'];
    $breadcrumbs[]=['text' => $this->category->getName()];
    $this->category->setViewsCount($this->category->getViewsCount()+1);
    $this->category->save();
    $this->breadcrumbs=$breadcrumbs;
    $this->pager = new sfDoctrinePager('Product', 12);
    $sqlBody="SELECT `product_id` FROM `category_product` where `category_id`=".$this->category->getId();
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $tableIds=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    $ids=array_keys($tableIds);

    $ids[]=-1;//Чтобы IN не оказался пустым
    // die('<pre>'.print_r(array_keys($tableIds), true));


    $query = Doctrine_Core::getTable('Product')
            ->createQuery('p')
            ->select("*")
            ->where('count > 0')
            ->addWhere('id IN('.implode(', ', $ids).')')
            ->addWhere('is_public=1')
    ;
    $this->applySort($query); //Сортировка всегда одинаковая, нехай будет в одном месте

    $this->pager->setQuery($query);
    $this->pager->setPage($page);

    $this->pager->init();

  }

  public function executeShowQrshops(sfWebRequest $request) {//QR магазины

  }

  public function executeShowQrshops_not_in_use(sfWebRequest $request) {//QR магазины
    $slug=$request->getParameter('slug', 'qrshops');
    $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array($slug));
    $this->forward404Unless($this->category);
    // $productsPerPageCount = 24;
    $page = $request->getParameter('page', 1);
    $this->page = $page;
    if ($request->getParameter('sortOrder') != "") {
        sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
        $this->sortOrder = $request->getParameter('sortOrder');
    }
    else{
      $this->sortOrder = "date";
      $this->direction = "desc";
    }
    if ($request->getParameter('direction') != "") {
        sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
        $this->direction = $request->getParameter('direction');
    }
    if($slug!='qrshops') $breadcrumbs[]=['link'=>'/category/qrshops', 'text' => 'Qr магазины'];
    $breadcrumbs[]=['text' => $this->category->getName()];
    $this->breadcrumbs=$breadcrumbs;
    $this->category->setViewsCount($this->category->getViewsCount()+1);
    $this->category->save();
    $this->pager = new sfDoctrinePager('Product', self::PRODUCTS_PER_PAGE);
    $sqlBody="SELECT `product_id` FROM `category_product` where `category_id`=".$this->category->getId();
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $tableIds=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    $ids=array_keys($tableIds);
    $ids[]=-1;//Чтобы IN не оказался пустым
    // die('<pre>'.print_r(array_keys($tableIds), true));


    $query = Doctrine_Core::getTable('Product')
            ->createQuery('p')
            ->select("*")
            ->where('id IN('.implode(', ', $ids).')')
            ->addWhere('is_public=1')
    ;

    $this->applySort($query); //Сортировка всегда одинаковая, нехай будет в одном месте

    $this->pager->setQuery($query);
    $this->pager->setPage($page);

    $this->pager->init();

  }

  public function executeShowFixprice(sfWebRequest $request) {//Все по
    $slug=$request->getParameter('slug', 'vse-po');
    $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array($slug));
    $this->forward404Unless($this->category);
    // $productsPerPageCount = 24;
    $page = $request->getParameter('page', 1);
    $this->page = $page;
    if ($request->getParameter('sortOrder') != "") {
        sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
        $this->sortOrder = $request->getParameter('sortOrder');
    }
    if ($request->getParameter('direction') != "") {
        sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
        $this->direction = $request->getParameter('direction');
    }
    if($slug!='vse-po') $breadcrumbs[]=['link'=>'/category/vse-po', 'text' => ' Все по'];
    $breadcrumbs[]=['text' => $this->category->getName()];
    $this->breadcrumbs=$breadcrumbs;
    $this->category->setViewsCount($this->category->getViewsCount()+1);
    $this->category->save();
    $this->pager = new sfDoctrinePager('Product', self::PRODUCTS_PER_PAGE);
    $sqlBody="SELECT `product_id` FROM `category_product` where `category_id`=".$this->category->getId();
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $tableIds=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    $ids=array_keys($tableIds);
    $ids[]=-1;//Чтобы IN не оказался пустым
    // die('<pre>'.print_r(array_keys($tableIds), true));


    $query = Doctrine_Core::getTable('Product')
            ->createQuery('p')
            ->select("*")
            ->where('id IN('.implode(', ', $ids).')')
            ->addWhere('is_public=1')
    ;
    $this->applySort($query); //Сортировка всегда одинаковая, нехай будет в одном месте

    $this->pager->setQuery($query);
    $this->pager->setPage($page);

    $this->pager->init();

  }

  public function executeShowDiscount60(sfWebRequest $request) {//Распродажа. Не используется
    $timer = sfTimerManager::getTimer('Action: Поиск категории');
    $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array("skidki_do_60_percent"));
    $this->forward404Unless($this->category);
    $this->category->setViewsCount($this->category->getViewsCount()+1);
    $this->category->save();
    $timer->addTime();

    $productsPerPageCount = self::PRODUCTS_PER_PAGE;
    $page = $request->getParameter('page', 1);
    $this->page = $page;


    $timer = sfTimerManager::getTimer('Action: Получения товаров для категории лучшей цены');

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $this->minPrice=(int) $_GET['min_price'];
    $this->maxPrice=(int) $_GET['max_price'];
    if(isset($_GET['filter_cat']) && $_GET['filter_cat']!=''){
      $filter=explode('|', $_GET['filter_cat']);
      $this->filterCat=$filter;
    }
    else
      // $this->filterCat = [];
    $priceWhere='';
    if($this->maxPrice) $arPriceFilter[]='p.price <= '.$this->maxPrice;
    if($this->minPrice) $arPriceFilter[]='p.price >= '.$this->minPrice;
    if(is_array($arPriceFilter)) $priceWhere='AND ('.implode(' AND ', $arPriceFilter).') ';
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
    $sqlBody=
      "SELECT count(p.id) as n ".
      "FROM product p ".
      "LEFT JOIN category_product cp on cp.product_id=p.id ".
      "LEFT JOIN category c ON c.id=cp.category_id ".
      "WHERE c.slug='skidki_do_60_percent' AND p.is_public=1 ".
      $priceWhere.
      $addWhereByFilter.
      "";

    $result = $q->execute($sqlBody);

    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $tmp = $result->fetchAll();

    $this->productsCount = $tmp[0]['n'];

    $this->pagesCount = ceil(($this->productsCount) / self::PRODUCTS_PER_PAGE);

    if ($this->page > $this->pagesCount or $this->page < 1) {
        $this->page = 1;
    }
    $pagesLimitStart = ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) <= $this->productsCount ? ($this->page * self::PRODUCTS_PER_PAGE - self::PRODUCTS_PER_PAGE) : ($this->page * self::PRODUCTS_PER_PAGE - 2*self::PRODUCTS_PER_PAGE);
    $pagesLimitEnd = self::PRODUCTS_PER_PAGE;
    $pagesLimit = "LIMIT " . $pagesLimitStart . "," . $pagesLimitEnd .' ';

    $sqlBody=
      "SELECT p.id ".
      "FROM product p ".
      "LEFT JOIN category_product cp on cp.product_id=p.id ".
      "LEFT JOIN category c ON c.id=cp.category_id ".
      "WHERE c.slug='skidki_do_60_percent' AND p.is_public=1 ".
      $priceWhere.
      $addWhereByFilter.
      "ORDER BY p.count>0 DESC, id ASC ".
      $pagesLimit.
      "";
    // die('<pre>'.print_r($sqlBody, true));
    $result = $q->execute($sqlBody);

    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $tmp = $result->fetchAll();

    if(sizeof($tmp)) foreach ($tmp as $key => $value) {
      $ids[]=$value['id'];
    }
    $this->ids=$ids;
    // die('<pre>'.print_r($ids, true));
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

  public function executeShowNewby(sfWebRequest $request) {//Для новичков

    $avaibleModifacators =[//Доступные "галочки из админки"
      'for_pairs',
      'bdsm',
      'for_she',
      'for_her',
      'cosmetics',
      'belie',
      'other',
    ];
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $slug = 'dlya_novichkov';
    $this->categorys = $q->execute("SELECT children.id, children.name, children.slug, parent.id as parent_id, parent.name as parent_name , parent.slug as parent_slug, cat.id as this_id, cat.name as this_name, cat.h1 as this_h1, cat.content as this_content , cat.slug as this_slug, cat.filtersnew as filters, cat.minprice as minPrice, cat.maxprice as maxPrice, cat.tags as tags  "
                    .", cat.title, cat.keywords, cat.description  "
                    . "FROM category as cat "
                    . "LEFT JOIN category AS children ON children.parents_id = cat.id "
                    . "LEFT JOIN category AS parent ON parent.id = cat.parents_id "
                    . "WHERE cat.slug =  ? "
                    . "ORDER BY cat.position ASC ", array($slug))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    if (count($this->categorys) == 0) $this->forward404();
    $categoryId_root = end($this->categorys)['parent_id'] != "" ? end($this->categorys)['parent_id'] : end($this->categorys)['this_id'];
    if($categoryId_root){
      $catalogId=end($q->execute("SELECT * FROM category_catalog where category_id=".$categoryId_root.";")->fetchAll(Doctrine_Core::FETCH_ASSOC));
    }
    $this->catalog=CatalogTable::getInstance()->findOneById($categoryId_root);

    if (array_keys($this->categorys)[0] != "") {
        $categorysId = implode(",", array_merge(array_keys($this->categorys), array(end($this->categorys)['this_id'])));
    } else {
        $categorysId = end($this->categorys)['this_id'];
    }

    $request->setParameter('slug', $slug);
    //далее формируем массив как в каталоге
    $this->sortOrder=$request->getParameter('sortOrder');
    $this->direction = $request->getParameter('direction');
    $this->filters = $request->getParameter('filters');
    $this->price = str_replace(' ', '', $request->getParameter('Price'));
    $this->page = $request->getParameter('page', 1);
    $this->isNovice = $request->getParameter('novice', '');

    if(!in_array($this->isNovice, $avaibleModifacators)) $this->isNovice='';

    $timerSort = sfTimerManager::getTimer('Action: Настройка сортировки');
    $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров');

    $addleftJoin = "";
    $addOrderBy = $this->applySortCatalog(true);


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

    $productsIDFilters = $this->getFilters($filtersDB);

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
    // die('<pre>7ds-95-'.print_r($addOrderBy, true));
    if($this->isNovice){
      $addWhere = $addWhere . ' and '.$this->isNovice.'=?';
      $addWhereParams[] = 1;
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
    //Пагинация

    $this->productsCount = $this->productsCount['count'];
    $productsPerPageCount = self::PRODUCTS_PER_PAGE/2;
    $productsPerPageCount = 40;
    $this->pagesCount = ceil(($this->productsCount) / $productsPerPageCount);

    if ($this->page > $this->pagesCount or $this->page < 1) {
        $this->page = 1;
    }
    $pagesLimitStart = ($this->page * $productsPerPageCount - $productsPerPageCount) <= $this->productsCount ? ($this->page * $productsPerPageCount - $productsPerPageCount) : ($this->page * $productsPerPageCount - 2*$productsPerPageCount);
    $pagesLimitEnd = $productsPerPageCount;
    $pagesLimit = " limit " . $pagesLimitStart . "," . $pagesLimitEnd;
    //Конец пагинации

    $this->ids =array_keys(
      $q->execute("SELECT product.id "
                  . "FROM product "
                  . "LEFT JOIN category_product as cp on product.id=product_id "
                  . $addleftJoin
                  . "WHERE  cp.category_id IN (" . $categorysId . ") "
                  . "and product.is_public='1' "
                  // . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1')) "
                  . "" . $addWhere . " "
                  . "group by product.id "
                  . "ORDER BY "
                  . $addOrderBy
                  . $pagesLimit, $addWhereParams)
            ->fetchAll(Doctrine_Core::FETCH_UNIQUE));
    $timerProducts->addTime();

  }

  public function executeShowManagePrice(sfWebRequest $request) {//Управляй ценой
      $timer = sfTimerManager::getTimer('Action: Поиск категории');
      // $this->getUser()->setAttribute('deliveryId', "");
      $this->category = Doctrine_Core::getTable('Category')->findOneBySlug(array("upravlyai-cenoi"));

      $this->forward404Unless($this->category);

      $timer->addTime();

      $timer = sfTimerManager::getTimer('Action: Получения товаров у которых родителя нет в наличии');

      $timer->addTime();

      $timer = sfTimerManager::getTimer('Action: Создание запроса');

      // $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
      $query = Doctrine_Core::getTable('Product')
              ->createQuery('p')->select("*")//->addSelect($selectDate)
              ->leftJoin('p.CategoryProduct c')
              ->where("(c.category_id IN (" . $this->category->getId() . ") or step <> '')")
              ->addWhere('is_public = 1');

      $query->addOrderBy("(count>0) DESC");
      // $query->addOrderBy("find_in_set(id, '" . $idProductsCount0Children . "') DESC");

      $this->pager = new sfDoctrinePager('Product', self::PRODUCTS_PER_PAGE);

      $query->addwhere("bonuspay>0 and bonuspay is not null and count>0");
      /*
      if ($request->getParameter('sortOrder') == "sortorder" or $request->getParameter('sortOrder') == "") {
          $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and discount>0 and discount is not null) DESC");
          $query->addOrderBy("(step <> \"\" and Endaction <> \"\" and bonuspay>0 and bonuspay is not null) DESC");
      }*/

      $timer->addTime();

      if ($request->getParameter('sortOrder') != "") {
          sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
          $this->sortOrder = $request->getParameter('sortOrder');
      }
      if ($request->getParameter('direction') != "") {
          sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
          $this->direction = $request->getParameter('direction');
      }
      $this->applySort($query); //Сортировка всегда одинаковая, нехай будет в одном месте

      $this->pager->setQuery($query);
      $this->pager->setPage($request->getParameter('page', 1));

      $this->pager->init();
  }

  public function executeNew(sfWebRequest $request) {//Новые товары
      $this->pager = new sfDoctrinePager('Product', self::PRODUCTS_PER_PAGE);

      $day_logo_new = csSettings::get('logo_new');
      if(!$day_logo_new) $day_logo_new = 90;

      $ids=explode(',', csSettings::get('optimization_newProductId'));

      foreach ($ids as $key => $value) //
        if(intval($value)>0)
          $productsPrior[]=intval($value);

      if(empty($productsPrior)) $productsPrior[]=-1;
      // $orderBy = "FIELD(id, ".implode(', ', array_reverse($productsPrior)).") DESC";
      $this->productsPrior = $productsPrior;

      $query = Doctrine_Core::getTable('Product')
              ->createQuery('p')
              ->where('is_public = 1')
              // ->addWhere('parents_id IS NULL')
              // ->addWhere('parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = \'1\')')
              ->addWhere('id IN('.implode(', ', $productsPrior). ') OR DATEDIFF(NOW(),created_at) < ' . $day_logo_new );

              // ->addOrderBy("(count>0) DESC")
              // ->addOrderBy($orderBy);
      $this->sortOrder = 'date';
      $this->direction = "desc";
      $this->applySort($query, true); //Сортировка всегда одинаковая, нехай будет в одном месте
      // var_dump($query);

      $this->pager->setQuery($query);
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
  }

  public function executeRelated(sfWebRequest $request) {//Популярное
    $this->pager = new sfDoctrinePager('Product', self::PRODUCTS_PER_PAGE);
    $ids=explode(',', csSettings::get('bestsellersProducts'));
    foreach ($ids as $key => $value) //
      if(intval($value)>0)
        $productsPrior[]=intval($value);
      $productsPrior[]=-1;
    /*
    if (csSettings::get('logo_new') == "") {
        $day_logo_new = 7;
    } else {
        $day_logo_new = csSettings::get('logo_new');
    }*/
    $query = Doctrine_Core::getTable('Product')
            ->createQuery('p')
            ->addWhere('is_public = \'1\'')
            ->addWhere("count>0")
            ->addWhere('id IN('.implode(', ', $productsPrior).')')
            ->orderBy('countsell DESC');
    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    if(count($this->pager->getLinks(2000)) < $request->getParameter('page', 1)) $this->forward404();;
  }

  public function executeNewprod(sfWebRequest $request) {//Новые товары в категории
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


      $this->pager = new sfDoctrinePager('Product', self::PRODUCTS_PER_PAGE);
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

  public function executeRelatecategory(sfWebRequest $request) {//Лидеры продаж категории
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


      $this->pager = new sfDoctrinePager('Product', self::PRODUCTS_PER_PAGE);
      /*
      if (csSettings::get('logo_new') == "") {
          $day_logo_new = 7;
      } else {
          $day_logo_new = csSettings::get('logo_new');
      }*/
      $query = Doctrine_Core::getTable('Product')
              ->createQuery('p')
              ->leftJoin('p.CategoryProduct c')
              ->addWhere('parents_id IS NULL')
              ->addWhere('is_public = \'1\'')
              ->addWhere('count > \'1\'')
              ->addwhere("(c.category_id IN (" . $idCategorys . "))")
              // ->addOrderBy("sortpriority desc")
              ->addOrderBy("countsell desc");

      $this->pager->setQuery($query);
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
  }

  private function applyShopsFilter($productsIDFilters){//Получает список id товаров и возвращает список id в запрошенных магазинах
    // die(__DIR__.__FILE__.'|'.__LINE__.'<pre>'.print_r([$tmp, $productsIDFilters, $this->shops,$sqlBody], true).'</pre>');

    if(empty($this->shops)) return $productsIDFilters;
    if(is_array($productsIDFilters)) $prodidWhere = " AND `product_id` IN(".implode(', ', $productsIDFilters).")";
    else $prodidWhere = '';
    $sqlBody = "SELECT `product_id`, COUNT(`product_id`) as `s_count` FROM `shop_stocks` WHERE `shop_id` IN ('".implode("', '", $this->shops)."') ".$prodidWhere." GROUP BY `product_id`";
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $tmp = array_keys($q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE));
    return $tmp;

  }
  /* ********************************************** old **************************************************** */


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

    public function executeNoncount(sfWebRequest $request) {

        $this->pager = new sfDoctrinePager('Product', 30);
        $query = Doctrine_Core::getTable('Product')
                ->createQuery('p')
                ->where("count <1");
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

}
