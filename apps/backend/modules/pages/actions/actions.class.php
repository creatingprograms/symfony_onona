<?php

require_once dirname(__FILE__) . '/../lib/pagesGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/pagesGeneratorHelper.class.php';

/**
 * pages actions.
 *
 * @package    Magazin
 * @subpackage pages
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pagesActions extends autoPagesActions {

    public function __construct($context, $moduleName, $controllerName) {
        parent::__construct($context, $moduleName, $controllerName);
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры"))
            if ($controllerName != "index" and $controllerName != "editseo" and $controllerName != "filter" and $controllerName != "update") {
                $this->redirect("@page");
            }
    }

    public function executeImportPromo(sfWebRequest $request) {
      if (isset($_FILES['file'])){//Если файл передан - обрабатываем
        $this->file = $_FILES['file'];
        $this->productName = array();
        if (is_uploaded_file($this->file['tmp_name'])) {
          $str=file_get_contents ($this->file['tmp_name']);
          // $str=iconv('CP1251', 'UTF-8', $str);
          $lines=explode("\n", $str);
          $i=0;
          foreach ($lines as $key => $line) {
            if (!$key) continue;
            $arLine=explode(';', $line);
            // $text=iconv('cp1251', 'utf-8', trim($arLine[0]));
            $text= trim($arLine[0]);
            // if($i++==3) die('<pre>'.print_r($text, true));
            if (!$arLine[0]) continue;

            $coupon = CouponsTable::getInstance()->findOneByText($text);
            $this->productName[]=$text;
            if(is_object($coupon)){

            }
            else{
              $coupon= new Coupons();
              $coupon->setText($text);
              $coupon->setIsActive(true);
              $artNotFound[]=$text;
            }
            $coupon->setDiscount($arLine[1]);
            $date=strtotime($arLine[2]);
            if(!$date) $date=time()-24*60*60;
            $coupon->setStartaction(date('Y-m-d 00:00', $date));
            $date=strtotime($arLine[3]);
            if(!$date) $date=time()+30*24*60*60;
            $coupon->setEndaction(date('Y-m-d 23:59', $date));
            $coupon->save();


          }
          if (sfContext::getInstance()->getUser()->hasPermission("Enter backend")) {
              exec("../symfony cc", $test);
          }
          if (sizeof($artNotFound)) $this->artNotFound=$artNotFound;
          else $this->artNotFound=[];
        }
      }
      else{//иначе строим форму для обработки

      }
    }

    public function executeViewcount(sfWebRequest $request) {//показ счетчика просмотров
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $sqlBody="SELECT slug, name, views_count FROM page WHERE views_count >0 AND is_public=1 ORDER BY views_count DESC";
      $result = $q->execute($sqlBody);
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $this->pages = $result->fetchAll();
      $sqlBody="SELECT slug, name, views_count FROM category WHERE views_count >0 AND is_public=1 ORDER BY views_count DESC";
      $result = $q->execute($sqlBody);
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $this->cats = $result->fetchAll();
    }

    public function executeSetCategoryToArt(sfWebRequest $request) {
      if (isset($_FILES['file'])){//Если файл передан - обрабатываем
        $this->file = $_FILES['file'];
        $this->productId = array();
        $this->productName = array();
        if (is_array($this->file)) {
          if (is_uploaded_file($this->file['tmp_name'])) {
            $str=file_get_contents ($this->file['tmp_name']);
            $str=iconv('CP1251', 'UTF-8', $str);
            $lines=explode("\n", $str);
            foreach ($lines as $key => $line) {
              if (!$key) continue;
              $arLine=explode(';', $line);
              if (!$arLine[0]) continue;
              $product = ProductTable::getInstance()->findOneByCode(trim($arLine[0]));

              //  echo $buffer.$product2."<br/>";
              if ($product) {
                  $productPairs[] = '('.$_POST['cat'].', '.$product->getId().')';
                  $this->productName[] = $product->getId().'|'.$product->getName();
                  if(isset($_POST['with_stock']) && $_POST['with_stock']){
                    if (isset($arLine[10]) ) $product->setOther($arLine[10]==1 ? true : false);
                    if (isset($arLine[9]) ) $product->setBelie($arLine[9]==1 ? true : false);
                    if (isset($arLine[8]) ) $product->setCosmetics($arLine[8]==1 ? true : false);
                    if (isset($arLine[7]) ) $product->setBdsm($arLine[7]==1 ? true : false);
                    if (isset($arLine[6]) ) $product->setForHer($arLine[6]==1 ? true : false);
                    if (isset($arLine[5]) ) $product->setForShe($arLine[5]==1 ? true : false);
                    if (isset($arLine[4]) ) $product->setForPairs($arLine[4]==1 ? true : false);
                    if (isset($arLine[3]) && $arLine[3]) $product->setPrice($arLine[3]);
                    if (isset($arLine[2]) && $arLine[2]) {
                      $discount=ceil(($arLine[2]-$arLine[3])/$arLine[2]*100);
                      $product->setOldPrice($arLine[2]);
                      $product->setDiscount($discount);
                    }
                    if (isset($arLine[1])) {
                      $product->setCount($arLine[1]);
                      $product->save();
                    }
                  }
              }
              else $artNotFound[]=trim($arLine[0]);
              $product = null;
            }
            if (sizeof($artNotFound)) $this->artNotFound=$artNotFound;
            else $this->artNotFound=[];
            if(sizeof($productPairs)) {//Есть что добавить
              $q = Doctrine_Manager::getInstance()->getCurrentConnection();
              $sqlBody="INSERT IGNORE INTO `category_product` (category_id, product_id) VALUES ".implode(', ', $productPairs);
              $result = $q->execute($sqlBody);
              if (sfContext::getInstance()->getUser()->hasPermission("Enter backend")) {
                  exec("../symfony cc", $test);
              }
            }

            // $this->str=$sqlBody;
          }
        }
      }
      else{//иначе строим форму для обработки
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sqlBody="SELECT id, name from category WHERE parents_id IS NULL";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $cats = $result->fetchAll();
        if (sizeof($cats)) foreach ($cats as $key => $cat) {
          $sqlBody="SELECT id, name FROM category WHERE parents_id=".$cat['id'];
          $result=$q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $cats[$key]['sub'] = $result->fetchAll();
        }
        $this->cats=$cats;
      }


    }

    public function executeEditseo(sfWebRequest $request) {
        $this->page = $this->getRoute()->getObject();
        $this->form = new PageSEOForm($this->page);
    }

    public function executeSetNewArrivals(sfWebRequest $request) {

      $this->file = isset( $_FILES['file']) ? $_FILES['file'] : false;
      $this->productId = array();
      $this->productName = array();
      /* $setting = csSettingTable::getInstance()->findOneBySlug("bestsellersProducts");
        $setting->setValue(implode(",", array_keys(array_slice($bestsellersArray, 0, 20, true))));
        $setting->save(); */

      if (is_array($this->file)) {
        if (is_uploaded_file($this->file['tmp_name'])) {
          $handle = fopen($this->file['tmp_name'], "r");
          while (!feof($handle)) {
            $buffer = fgets($handle, 4096);
            $product = ProductTable::getInstance()->findOneByCode(trim($buffer));

            //  echo $buffer.'|'.$product."<br/>";die();
            if ($product) {
                $this->productId[] = $product->getId();
                $this->productName[] = $product->getName();
            }
            $product = null;
            //echo $buffer;
          }
          fclose($handle);
          $newList = csSettings::get('optimization_newProductId');
          // echo '<pre>!~'.print_r($newList, true).'~!</pre>';die();
          // echo '<pre>!~'.print_r($this->productId, true).'~!</pre>';die();
          if (count($this->productId) > 0) {
            $setting = csSettingTable::getInstance()->findOneBySlug("optimization_newProductId");
            $setting->setValue(implode(",", $this->productId));
            $setting->save();
          }

          if (sfContext::getInstance()->getUser()->hasPermission("Enter backend")) {
            exec("../symfony cc", $test);
              //print_r($test);exit;
          }
        }
      }
      $this->setTemplate('setOptimizationIdProducts');
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->page = $this->getRoute()->getObject();
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")) {
            $this->form = new PageSEOForm($this->page);
            $this->processForm($request, $this->form);

            $this->setTemplate('editseo');
        } else {
            $this->form = $this->configuration->getForm($this->page);
            $this->processForm($request, $this->form);

            $this->setTemplate('edit');
        };
    }

    public function executeTest(sfWebRequest $request) {
        sfCacheManager::clearCache('@sf_cache_partial?module=category&action=_products&sf_cache_key=14539*');
        sfCacheManager::clearCache('@sf_cache_partial?module=category&action=_changechildren&sf_cache_key=14539*');
        sfCacheManager::clearCache('@sf_cache_partial?module=product&action=_params&sf_cache_key=14539*');
        sfCacheManager::clearCache('@sf_cache_partial?module=product&action=_stock&sf_cache_key=14539*');
        /* sfContext::switchTo('newcat');
          $cacheManager = sfContext::getInstance()->getViewCacheManager();
          if ($cacheManager) {
          $cacheManager->remove('@sf_cache_partial?module=category&action=_products&sf_cache_key=14539*');
          $cacheManager->remove('@sf_cache_partial?module=category&action=_changechildren&sf_cache_key=14539*');
          $cacheManager->remove('@sf_cache_partial?module=product&action=_params&sf_cache_key=14539*');
          $cacheManager->remove('@sf_cache_partial?module=product&action=_stock&sf_cache_key=14539*');
          }
          sfContext::switchTo('backend'); */
    }

    public function executeSearch(sfWebRequest $request) {
        $this->categorys = CategoryTable::getInstance()->createQuery()->where("name like ?", "%" . strip_tags($request->getParameter('searchString')) . "%")->execute();
        $this->products = ProductTable::getInstance()->createQuery()->where("name like ?", "%" . strip_tags($request->getParameter('searchString')) . "%")->orWhere("code like ?", "%" . $request->getParameter('searchString') . "%")->execute();
        $this->pages = PageTable::getInstance()->createQuery()->where("name like ?", "%" . strip_tags($request->getParameter('searchString')) . "%")->execute();
    }

    public function executeStatsProduct(sfWebRequest $request) {

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        if (count($_POST) > 0) {
            if ($_POST['query'] == "Все отсутствующие товары") {
                if ($_POST['category'] != "") {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where is_public=1 and count=0 and generalcategory_id=?", array($_POST['category']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                } elseif ($_POST['manufacturer'] != "") {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where is_public=1 and count=0 and id in (select product_id from `dop_info_product` WHERE `dop_info_id` = ?)", array($_POST['manufacturer']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                } elseif ($_POST['collection'] != "") {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where is_public=1 and count=0 and id in (select product_id from `dop_info_product` WHERE `dop_info_id` = ?)", array($_POST['collection']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                } else {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where is_public=1 and count=0")
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                }
            } elseif ($_POST['query'] == "Все скрытые товары") {
                if ($_POST['category'] != "") {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction from product where (is_public=0 or moder =1) and generalcategory_id=?", array($_POST['category']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                } elseif ($_POST['manufacturer'] != "") {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where (is_public=0 or moder =1) and id in (select product_id from `dop_info_product` WHERE `dop_info_id` = ?)", array($_POST['manufacturer']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                } elseif ($_POST['collection'] != "") {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where (is_public=0 or moder =1) and id in (select product_id from `dop_info_product` WHERE `dop_info_id` = ?)", array($_POST['collection']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                } else {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where (is_public=0 or moder =1)")
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                }
            } elseif ($_POST['query'] == "Все товары которые в наличии") {
                if ($_POST['category'] != "") {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction from product where is_public=1 and count>0 and generalcategory_id=?", array($_POST['category']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                } elseif ($_POST['manufacturer'] != "") {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where is_public=1 and count>0 and id in (select product_id from `dop_info_product` WHERE `dop_info_id` = ?)", array($_POST['manufacturer']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                } elseif ($_POST['collection'] != "") {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where is_public=1 and count>0 and id in (select product_id from `dop_info_product` WHERE `dop_info_id` = ?)", array($_POST['collection']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                } else {
                    $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where is_public=1 and count>0", array($_POST['collection']))
                            ->fetchAll(Doctrine_Core::FETCH_ASSOC);
                }
            } elseif ($_POST['query'] == "Все товары Управляй ценой") {
                $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where bonuspay>0")
                        ->fetchAll(Doctrine_Core::FETCH_ASSOC);
            } elseif ($_POST['query'] == "Все товары Лучшей цены") {
                $this->products = $q->execute("SELECT code, price,old_price,discount,bonuspay,endaction FROM product where discount>0")
                        ->fetchAll(Doctrine_Core::FETCH_ASSOC);
            }
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize ' => '256MB');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("OnOna");
            $objPHPExcel->getProperties()->setLastModifiedBy("OnOna");
            $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
            $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
            $objPHPExcel->getProperties()->setDescription("OnOna document for Office 2007 XLSX.");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Артикул');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Цена');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Старая цена');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Скидка');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Процент оплаты бонусами');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Окончание акции');
            ini_set("max_execution_time", 240);
            foreach ($this->products as $num => $product) {
                @$objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $product['code']);
                @$objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $product['price']);
                @$objPHPExcel->getActiveSheet()->SetCellValue('C' . ($num + 2), $product['old_price']);
                @$objPHPExcel->getActiveSheet()->SetCellValue('D' . ($num + 2), $product['discount']);
                @$objPHPExcel->getActiveSheet()->SetCellValue('E' . ($num + 2), $product['bonuspay']);
                @$objPHPExcel->getActiveSheet()->SetCellValue('F' . ($num + 2), $product['endaction']);
            }


            $objPHPExcel->getActiveSheet()->setTitle("Товары");

            header("Content-Type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=\"Товары.xlsx\"");
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->save('php://output');

            exit;
        }

        $this->categorys = $q->execute("SELECT * from category")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $this->countProductOnCategory = $q->execute("SELECT generalcategory_id, COUNT( generalcategory_id ) as count ".
          "FROM  `product` ".
          "GROUP BY generalcategory_id")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $this->manufacturers = $q->execute("SELECT * from manufacturer")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $this->collections = $q->execute("SELECT * from collection")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    }

    public function executeOrdersReports2(sfWebRequest $request){
      if(isset($_POST['start_mon']) && isset($_POST['start_year'])){
        $startDate=$_POST['start_year'].'-'.$_POST['start_mon'].'-01 00:00';
        
        $endDate=$_POST['start_year'].'-'.$_POST['start_mon'].'-01 00:00';
        $iDate=strtotime($endDate);
        $endDate=$_POST['start_year'].'-'.$_POST['start_mon'].'-'.date('t', $iDate).' 23:59:59';
      }
      if (isset($startDate) && isset($endDate)) $this->generateOrdersXLS2($startDate, $endDate);
      
    }

    public function executeOrdersReports2YM(sfWebRequest $request){
      if(isset($_POST['start_mon']) && isset($_POST['start_year'])){
        $startDate=$_POST['start_year'].'-'.$_POST['start_mon'].'-01 00:00';
        
        $endDate=$_POST['start_year'].'-'.$_POST['start_mon'].'-01 00:00';
        $iDate=strtotime($endDate);
        $endDate=$_POST['start_year'].'-'.$_POST['start_mon'].'-'.date('t', $iDate).' 23:59:59';
      }
      if (isset($startDate) && isset($endDate)) $this->generateOrdersXLS2_YM($startDate, $endDate);
      
    }

    public function executeOrdersReports(sfWebRequest $request){
      $query = false;
      if (isset($_POST['query'])) $query = $_POST['query'];
       switch($query){
        case 'За прошлый месяц':
          $startDate=strtotime('first day of previous month');
          $endDate=strtotime('last day of previous month');
        break;

        case 'За прошлую неделю':
          $startDate=strtotime('mon previous week');
          $endDate=strtotime('sun previous week');
        break;

        case 'За вчера':
          $startDate=time()-24*60*60;
          $endDate=$startDate;
          break;
        case 'Диапазон':
          if(isset($_POST['start_mon']) && isset($_POST['start_year'])){
            $startDate=strtotime($_POST['start_year'].'-'.$_POST['start_mon'].'-01 00:00');
          }
          if(isset($_POST['end_mon']) && isset($_POST['end_year'])){
            $endDate=$_POST['end_year'].'-'.$_POST['end_mon'].'-01 00:00';
            $iDate=strtotime($endDate);
            $endDate=strtotime($_POST['end_year'].'-'.$_POST['end_mon'].'-'.date('t').' 23:59:59');
          }
          break;

        default:

      }
      if (isset($startDate) && isset($endDate)) $this->generateOrdersXLS($startDate, $endDate);

    }

    public function executeOrdersReportsYM(sfWebRequest $request){
      $query = false;
      if (isset($_POST['query'])) $query = $_POST['query'];
       switch($query){
        case 'За прошлый месяц':
          $startDate=strtotime('first day of previous month');
          $endDate=strtotime('last day of previous month');
        break;

        case 'За прошлую неделю':
          $startDate=strtotime('mon previous week');
          $endDate=strtotime('sun previous week');
        break;

        case 'За вчера':
          $startDate=time()-24*60*60;
          $endDate=$startDate;
          break;
        case 'Диапазон':
          if(isset($_POST['start_mon']) && isset($_POST['start_year'])){
            $startDate=strtotime($_POST['start_year'].'-'.$_POST['start_mon'].'-01 00:00');
          }
          if(isset($_POST['end_mon']) && isset($_POST['end_year'])){
            $endDate=$_POST['end_year'].'-'.$_POST['end_mon'].'-01 00:00';
            $iDate=strtotime($endDate);
            $endDate=strtotime($_POST['end_year'].'-'.$_POST['end_mon'].'-'.date('t').' 23:59:59');
          }
          break;

        default:

      }
      if (isset($startDate) && isset($endDate)) $this->generateOrdersXLS_YM($startDate, $endDate);

    }

    public function executeCouponsReport(sfWebRequest $request){
      if(isset($_POST['from']) && isset($_POST['to']) && ($from=strtotime($_POST['from'])) && ($to=strtotime($_POST['to'])) && $from<=$to){
        $filename='coupons_report';
        $sqlBody="SELECT c.text AS c_text, count(o.id) as o_count, SUM(firsttotalcost) AS o_sum ".
                  " FROM coupons c ".
                  " LEFT JOIN orders o ON c.text=o.coupon ".
                  " WHERE o.`created_at` BETWEEN '".date('Y-m-d 00:00:00', $from)."' AND '".date('Y-m-d 23:59:59', $to)."' ".
                  " GROUP BY c.id, c.text ".
                  " ORDER BY o_count DESC".
                  "";
        // die('<pre>'.print_r([$_POST, $from, $to], true).$sqlBody);
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $data=$q->execute($sqlBody)
          ->fetchAll(Doctrine_Core::FETCH_ASSOC);
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize ' => '256MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("OnOna");
        $objPHPExcel->getProperties()->setLastModifiedBy("OnOna");
        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $objPHPExcel->getProperties()->setDescription("OnOna document for Office 2007 XLSX.");
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Промокод');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Количество заказов');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Сумма заказов без стоимости доставки');
        ini_set("max_execution_time", 240);
        foreach ($data as $num => $line) {
            @$objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $line['c_text']);
            @$objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $line['o_count']);

            // die('<pre>'.print_r($line, true));
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($num + 2), $line['o_sum']);
        }
        $objPHPExcel->getActiveSheet()->setTitle("Отчет по купонам");

        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=\"".$filename/*.date('d.y.Y-H.m.i')*/.".xlsx\"");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');

        exit;
      }

    }

    private function generateOrdersXLS2($start, $end, $filename='onona_orders_by_source'){
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      //   $sqlBody = "SELECT CONCAT(`source`,' ', `medium`) AS `ist`, COUNT(`id`) AS `count_id`, SUM(`firsttotalcost`) AS `first_sum` FROM `orders` WHERE `created_at` > '$start' AND `created_at`<'$end' GROUP BY `ist`";
      //   $this->data=$q->execute($sqlBody) ->fetchAll(Doctrine_Core::FETCH_ASSOC);

      $sqlBody = "SELECT COUNT(`id`) as countid, `status`, SUM(`firsttotalcost`) AS `first_sum` FROM `orders` WHERE `created_at` > '$start' AND `created_at`<'$end' GROUP BY `status` ORDER BY `status` IN ('Отмена') DESC";
      $statuses = $q->execute($sqlBody) ->fetchAll(Doctrine_Core::FETCH_ASSOC);
      
      $sqlBody = "SELECT CONCAT(`source`,' ', `medium`) AS `ist`, `firsttotalcost`, `status` FROM `orders` WHERE `created_at` > '$start' AND `created_at`<'$end'";
      $data = $q->execute($sqlBody) ->fetchAll(Doctrine_Core::FETCH_ASSOC);
      
      $report[0][0] = '';
      $report[1][0] = 'Всего';
      $countTotal = $sumTotal = $i = 0;
      $templateLine[0] = '';

      $templateLine[1 + 2 * $i] = 0;
      $templateLine[2 + 2 * $i] = 0;
      $report[0][1 + 2 * $i] = 'Итого | Сумма';
      $report[0][2 + 2 * $i] = 'Итого | Количество';
      $statsMap['total'] = 1 + 2 * $i++;
      

      foreach($statuses as $status) {
        $statsMap[$status['status']] = 1+2*$i;
        $report[0][1+2*$i] = $status['status']. ' | Сумма';
        $report[0][2+2*$i] = $status['status'] . ' | Количество';
        $templateLine[1+2*$i] = 0;
        $templateLine[2+2*$i] = 0;
        $report[1][1+2*$i] = $status['first_sum'];
        $report[1][2+2*$i++] = $status['countid'];
        $sumTotal += $status['first_sum'];
        $countTotal += $status['countid'];
      }

      $report[1][1] = $sumTotal;
      $report[1][2] = $countTotal;
      
      
      
      $i = 2;
      $sourceMap = [];

      foreach($data as $line) {
        $ist = $line['ist'];
        if(empty($ist)) $ist = ' ';
        if(empty($sourceMap[$ist])) {
            $sourceMap[$ist] = $i++;
            $report[$sourceMap[$ist]] = $templateLine;
            $report[$sourceMap[$ist]][0] = $ist;
        }
        $report[$sourceMap[$ist]][$statsMap[$line['status']]]   += $line['firsttotalcost'];//Увеличиваем сумму по источнику по статусу
        $report[$sourceMap[$ist]][$statsMap['total']]           += $line['firsttotalcost'];//Увеличиваем сумму по источнику итог
        $report[$sourceMap[$ist]][$statsMap[$line['status']]+1]++;//Увеличиваем количество по источнику по статусу
        $report[$sourceMap[$ist]][$statsMap['total']+1]++;//Увеличиваем количество по источнику итог
      }

      //   die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$report], true) . '</pre>');
      ILTools::saveXLS($report, $filename . '_' . $start . '_' . $end);
      exit();
        

    }
    private function generateOrdersXLS2_YM($start, $end, $filename='onona_orders_by_source_ym'){
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      //   $sqlBody = "SELECT CONCAT(`source`,' ', `medium`) AS `ist`, COUNT(`id`) AS `count_id`, SUM(`firsttotalcost`) AS `first_sum` FROM `orders` WHERE `created_at` > '$start' AND `created_at`<'$end' GROUP BY `ist`";
      //   $this->data=$q->execute($sqlBody) ->fetchAll(Doctrine_Core::FETCH_ASSOC);

      $sqlBody = "SELECT COUNT(`id`) as countid, `status`, SUM(`firsttotalcost`) AS `first_sum` FROM `orders` WHERE `created_at` > '$start' AND `created_at`<'$end' GROUP BY `status` ORDER BY `status` IN ('Отмена') DESC";
      $statuses = $q->execute($sqlBody) ->fetchAll(Doctrine_Core::FETCH_ASSOC);
      
      $sqlBody = "SELECT CONCAT(`source_ym`,' ', `medium_ym`) AS `ist`, `firsttotalcost`, `status` FROM `orders` WHERE `created_at` > '$start' AND `created_at`<'$end'";
      $data = $q->execute($sqlBody) ->fetchAll(Doctrine_Core::FETCH_ASSOC);
      
      $report[0][0] = '';
      $report[1][0] = 'Всего';
      $countTotal = $sumTotal = $i = 0;
      $templateLine[0] = '';

      $templateLine[1 + 2 * $i] = 0;
      $templateLine[2 + 2 * $i] = 0;
      $report[0][1 + 2 * $i] = 'Итого | Сумма';
      $report[0][2 + 2 * $i] = 'Итого | Количество';
      $statsMap['total'] = 1 + 2 * $i++;
      

      foreach($statuses as $status) {
        $statsMap[$status['status']] = 1+2*$i;
        $report[0][1+2*$i] = $status['status']. ' | Сумма';
        $report[0][2+2*$i] = $status['status'] . ' | Количество';
        $templateLine[1+2*$i] = 0;
        $templateLine[2+2*$i] = 0;
        $report[1][1+2*$i] = $status['first_sum'];
        $report[1][2+2*$i++] = $status['countid'];
        $sumTotal += $status['first_sum'];
        $countTotal += $status['countid'];
      }

      $report[1][1] = $sumTotal;
      $report[1][2] = $countTotal;
      
      
      
      $i = 2;
      $sourceMap = [];

      foreach($data as $line) {
        $ist = $line['ist'];
        if(empty($ist)) $ist = ' ';
        if(empty($sourceMap[$ist])) {
            $sourceMap[$ist] = $i++;
            $report[$sourceMap[$ist]] = $templateLine;
            $report[$sourceMap[$ist]][0] = $ist;
        }
        $report[$sourceMap[$ist]][$statsMap[$line['status']]]   += $line['firsttotalcost'];//Увеличиваем сумму по источнику по статусу
        $report[$sourceMap[$ist]][$statsMap['total']]           += $line['firsttotalcost'];//Увеличиваем сумму по источнику итог
        $report[$sourceMap[$ist]][$statsMap[$line['status']]+1]++;//Увеличиваем количество по источнику по статусу
        $report[$sourceMap[$ist]][$statsMap['total']+1]++;//Увеличиваем количество по источнику итог
      }

      //   die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$report], true) . '</pre>');
      ILTools::saveXLS($report, $filename . '_' . $start . '_' . $end);
      exit();
        

    }

    private function generateOrdersXLSold($startDate, $endDate, $filename='onona_orders'){
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      // $orderPrefix=csSettings::get("order_prefix"); //Настройка в СУ, префикс заказа
      $sqlBody=
        'SELECT o.id as oid, comment_1c, status_detail, manager, name, delivery_price, comments, status, medium, region, o.created_at as oca, prefix, firsttotalcost, source '.
        'FROM orders o '.
        'LEFT JOIN delivery d ON o.delivery_id=d.id '.
        "WHERE o.created_at BETWEEN '".date('Y-m-d 00:00', $startDate)."' AND '".date('Y-m-d 23:59:59', $endDate)."' ".
        'ORDER BY oid desc '.
        '';

      $this->products = $q->execute($sqlBody)
              ->fetchAll(Doctrine_Core::FETCH_ASSOC);
              $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
              $cacheSettings = array('memoryCacheSize ' => '512MB');
              PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
              $objPHPExcel = new PHPExcel();
              $objPHPExcel->getProperties()->setCreator("OnOna");
              $objPHPExcel->getProperties()->setLastModifiedBy("OnOna");
              $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
              $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
              $objPHPExcel->getProperties()->setDescription("OnOna document for Office 2007 XLSX.");
              $objPHPExcel->setActiveSheetIndex(0);
              $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Номер');
              $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Дата заказа');
              $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Сумма доставки');
              $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Тип доставки');
              $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Статус');
              $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Первоначальная сумма');
              $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Комментарий');
              $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Менеджер');
              $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Комментарий 1С');
              $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Источник перехода');
              $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Канал перехода');
              $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Регион');
              ini_set("max_execution_time", 240);
              foreach ($this->products as $num => $product) {
                  @$objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $product['prefix'].$product['oid']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), date('d.m.Y', strtotime($product['oca'])));
                  @$objPHPExcel->getActiveSheet()->SetCellValue('C' . ($num + 2), $product['delivery_price']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('D' . ($num + 2), $product['name']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('E' . ($num + 2), $product['status']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('F' . ($num + 2), $product['firsttotalcost']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('G' . ($num + 2), $product['comments']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('H' . ($num + 2), $product['manager']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('I' . ($num + 2), $product['comment_1c']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('J' . ($num + 2), $product['source']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('K' . ($num + 2), $product['medium']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('L' . ($num + 2), $product['region']);
              }


              $objPHPExcel->getActiveSheet()->setTitle("Заказы");

              header("Content-Type:application/vnd.ms-excel");
              header("Content-Disposition:attachment;filename=\"".$filename.'_'.date('Y_m_d', $startDate).'_'.date('Y_m_d', $endDate).".xlsx\"");
              $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
              $objWriter->save('php://output');

              exit;
    }

    private function generateOrdersXLS($startDate, $endDate, $filename='onona_orders'){
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        // $orderPrefix=csSettings::get("order_prefix"); //Настройка в СУ, префикс заказа
        $sqlBody=
            'SELECT o.id as oid, comment_1c, status_detail, manager, name, delivery_price, comments, status, medium, region, o.created_at as oca, prefix, firsttotalcost, source, '.
                ' o.text '.
            'FROM orders o '.
            'LEFT JOIN delivery d ON o.delivery_id=d.id '.
            "WHERE o.created_at BETWEEN '".date('Y-m-d 00:00', $startDate)."' AND '".date('Y-m-d 23:59:59', $endDate)."' ".
            'ORDER BY oid desc '.
            '';

        $data = $q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);

        $report[] = [
            'Номер',
            'Дата заказа',
            'Сумма доставки',
            'Тип доставки',
            'Статус',
            'Первоначальная сумма',
            'Сумма из 1С',
            'Комментарий',
            'Менеджер',
            'Комментарий 1С',
            'Источник перехода',
            'Канал перехода',
            'Регион', 
            'Артикулы',
            // 'TEXT',
            // 'FIRSTTEXT'
        ];

        foreach ($data as $order) {
            $products = unserialize($order['text']);
            $total = 0;
            $articles = [];
            foreach($products as $product) {
                if($product['article'] == 'д1') continue;
                $articles[] = $product['article'];
                $total += $product['quantity'] * ($product['price_w_discount'] ? $product['price_w_discount'] : $product['price']);
            }
            $report[] = [
                $order['prefix'].$order['oid'],
                date('d.m.Y', strtotime($order['oca'])),
                $order['delivery_price'],
                $order['name'],
                $order['status'],
                $order['firsttotalcost'],
                $total,
                $order['comments'],
                $order['manager'],
                $order['comment_1c'],
                $order['source'],
                $order['medium'],
                $order['region'],
                implode(",\n", $articles),
            ];
        }

        // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$report], true) . '</pre>');
        ILTools::saveXLS($report, $filename . '_' . $startDate . '_' . $endDate);
        exit();

    }
    private function generateOrdersXLS_YM($startDate, $endDate, $filename='onona_orders_ym'){
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        // $orderPrefix=csSettings::get("order_prefix"); //Настройка в СУ, префикс заказа
        $sqlBody=
            'SELECT o.id as oid, comment_1c, status_detail, manager, name, delivery_price, comments, status, medium_ym as medium, region, o.created_at as oca, prefix, firsttotalcost, source_ym as source, '.
                ' o.text '.
            'FROM orders o '.
            'LEFT JOIN delivery d ON o.delivery_id=d.id '.
            "WHERE o.created_at BETWEEN '".date('Y-m-d 00:00', $startDate)."' AND '".date('Y-m-d 23:59:59', $endDate)."' ".
            'ORDER BY oid desc '.
            '';

        $data = $q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);

        $report[] = [
            'Номер',
            'Дата заказа',
            'Сумма доставки',
            'Тип доставки',
            'Статус',
            'Первоначальная сумма',
            'Сумма из 1С',
            'Комментарий',
            'Менеджер',
            'Комментарий 1С',
            'Источник перехода',
            'Канал перехода',
            'Регион', 
            'Артикулы',
            // 'TEXT',
            // 'FIRSTTEXT'
        ];

        foreach ($data as $order) {
            $products = unserialize($order['text']);
            $total = 0;
            $articles = [];
            foreach($products as $product) {
                if($product['article'] == 'д1') continue;
                $articles[] = $product['article'];
                $total += $product['quantity'] * ($product['price_w_discount'] ? $product['price_w_discount'] : $product['price']);
            }
            $report[] = [
                $order['prefix'].$order['oid'],
                date('d.m.Y', strtotime($order['oca'])),
                $order['delivery_price'],
                $order['name'],
                $order['status'],
                $order['firsttotalcost'],
                $total,
                $order['comments'],
                $order['manager'],
                $order['comment_1c'],
                $order['source'],
                $order['medium'],
                $order['region'],
                implode(",\n", $articles),
            ];
        }

        // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$report], true) . '</pre>');
        ILTools::saveXLS($report, $filename . '_' . $startDate . '_' . $endDate);
        exit();

    }

    public function executeSetDiscount(sfWebRequest $request) {
        $this->file = $_FILES['file'];
        $this->productId = array();
        $this->productName = array();
        /* $setting = csSettingTable::getInstance()->findOneBySlug("bestsellersProducts");
          $setting->setValue(implode(",", array_keys(array_slice($bestsellersArray, 0, 20, true))));
          $setting->save(); */
        if (is_array($this->file)) {
            if (is_uploaded_file($this->file['tmp_name'])) {

                $ar = array();                // инициализируем массив
                $inputFileType = PHPExcel_IOFactory::identify($this->file['tmp_name']);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
                $objReader = PHPExcel_IOFactory::createReader($inputFileType); // создаем объект для чтения файла
                $objPHPExcel = $objReader->load($this->file['tmp_name']); // загружаем данные файла в объект
                $ar = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные из объекта в массив
                foreach ($ar as $prod) {
                    if ($prod[2] != "") {
                        $endaction = explode("/", $prod[2]);
                        $endaction = strtotime($endaction[2] . "-" . $endaction[1] . "-" . $endaction[0]);
                    } else {
                        $endaction = 0;
                    }
                    /* $endaction=strtotime($prod[2]); */
                    $product = ProductTable::getInstance()->findOneByCode(trim($prod[0]));
                    if ($product) {

                        //echo $prod[0] . " " . $prod[1] . " " . $prod[2] . " " . $endaction . " <br />";
                        $this->productId[] = $product->getId();
                        $this->productName[$prod[0]]['name'] = $product->getName();
                        $this->productName[$prod[0]]['discount'] = $prod[1];

                        if ($request->getParameter('form') == "discount") {
                            if ($product->get('old_price') == "" or $product->get('old_price') == 0) {
                                $product->set('discount', $prod[1]);
                                $product->set('step', "3 суток");
                                $product->set('old_price', $product->get('price'));
                                $product->set('price', round($product->get('price') - ($product->get('price') * $product->get('discount') / 100)));
                                $product->set('endaction', null);
                                if ($endaction > 0)
                                    $product->set('endaction', date("Y-m-d", $endaction));
                                $product->set('bonuspay', null);
                            }elseif (( $product->get('old_price') > 0)) {

                                $product->set('discount', $prod[1]);
                                $product->set('price', round($product->get('old_price') - ($product->get('old_price') * $product->get('discount') / 100)));
                                $product->set('endaction', null);
                                if ($endaction > 0)
                                    $product->set('endaction', date("Y-m-d", $endaction));
                                $product->set('bonuspay', null);
                            }
                            $product->save();
                        } else {

                            $product->set('price', $product->get('old_price'));
                            $product->set('old_price', null);
                            $product->set('discount', null);
                            $product->set('step', null);
                            $product->set('endaction', null);
                            $product->set('bonuspay', $prod[1]);
                            $product->set('step', "3 суток");
                            if ($endaction > 0)
                                $product->set('endaction', date("Y-m-d", $endaction));

                            $product->save();
                        }
                    }

                    unset($endaction, $product);
                }

                if (sfContext::getInstance()->getUser()->hasPermission("Enter backend")) {
                    //exec("../symfony cc", $test);
                    //print_r($test);exit;
                }
            }
        }
    }

    public function executeChangeCode(sfWebRequest $request) {
        $this->file = $_FILES['file'];
        $this->productId = array();
        $this->productName = array();
        /* $setting = csSettingTable::getInstance()->findOneBySlug("bestsellersProducts");
          $setting->setValue(implode(",", array_keys(array_slice($bestsellersArray, 0, 20, true))));
          $setting->save(); */
        if (is_array($this->file)) {
            if (is_uploaded_file($this->file['tmp_name'])) {

                $ar = array();                // инициализируем массив
                $inputFileType = PHPExcel_IOFactory::identify($this->file['tmp_name']);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
                $objReader = PHPExcel_IOFactory::createReader($inputFileType); // создаем объект для чтения файла
                $objPHPExcel = $objReader->load($this->file['tmp_name']); // загружаем данные файла в объект
                $ar = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные из объекта в массив

                $this->productNoneNewCode = array();
                $this->productNoneCode = array();
                $this->productChangeCode = array();
                foreach ($ar as $prod) {
                    if ($prod[1] == "") {
                        $this->productNoneNewCode[] = trim($prod[0]);
                    } else {
                        $product = ProductTable::getInstance()->findOneByCode(trim($prod[0]));
                        if ($product) {
                            $this->productChangeCode[] = array($prod[0], $prod[1]);
                            $product->set('code', $prod[1]);

                            $product->save();
                        } else {
                            $this->productNoneCode[] = trim($prod[0]);
                        }
                    }
                }
            }
        }
    }

    public function executeSetNotPublic(sfWebRequest $request) {
        $this->file = $_FILES['file'];
        $this->productId = array();
        $this->productName = array();
        /* $setting = csSettingTable::getInstance()->findOneBySlug("bestsellersProducts");
          $setting->setValue(implode(",", array_keys(array_slice($bestsellersArray, 0, 20, true))));
          $setting->save(); */
        if (is_array($this->file)) {
            if (is_uploaded_file($this->file['tmp_name'])) {

                $ar = array();                // инициализируем массив
                $inputFileType = PHPExcel_IOFactory::identify($this->file['tmp_name']);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
                $objReader = PHPExcel_IOFactory::createReader($inputFileType); // создаем объект для чтения файла
                $objPHPExcel = $objReader->load($this->file['tmp_name']); // загружаем данные файла в объект
                $ar = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные из объекта в массив

                $this->productNoneCode = array();
                $this->productChangeCode = array();
                foreach ($ar as $prod) {
                    $product = ProductTable::getInstance()->findOneByCode(trim($prod[0]));
                    if ($product) {
                        $this->productChangeCode[] = array($prod[0]);
                        $product->set('is_public', false);

                        $product->save();
                    } else {
                        $this->productNoneCode[] = trim($prod[0]);
                    }
                }
            }
        }
    }

    public function executeGetMailDB2(sfWebRequest $request){

      if (!(count($_POST)==0 ||
        ($_POST['mail_db_citys']=='' && $_POST['mail_db_login']['from']=='' && $_POST['mail_db_login']['to']=='' &&
        $_POST['mail_db_order']['from']=='' && $_POST['mail_db_order']['to']=='' &&
        $_POST['mail_db_newuser']['from']=='' && $_POST['mail_db_newuser']['to']=='' &&
        $_POST['mail_db_prod_id']==''))
      ) {
        // throw new Doctrine_Table_Exception('let`s show!  <pre/>~|'.print_r($_POST, true).'|~</pre>');
        $filename='Пользователи';
        $sqlFrom=
          'SELECT u.`first_name`, u.`last_name`, u.`email_address`, u.`last_login`, u.`city`, u.`created_at`, '.
            'u.`birthday`, u.`phone`, u.`sex` '.
            'FROM `sf_guard_user` AS `u` ';
        $sqlJoin='';
        $sqlWhere=' WHERE `email_address` <>"" and `email_address` like "%@%" ';
        $sqlGroup='';

        if (@$_POST['mail_db_citys'] != "") {//Город
          $filename.="_Город";

            $citys = explode(",", $_POST['mail_db_citys']);

            $cityWhere = "";
            foreach ($citys as $numCity => $city) {
                if ($numCity == 0) {
                    $cityWhere.='city like "%' . $city . '%"';
                } else {
                    $cityWhere.=' or city like "%' . $city . '%"';
                }
            }
            $sqlWhere.=" AND "."(" . $cityWhere . ")";
        }
        if (@$_POST['mail_db_login']['from'] != "") {//Последний вход
          $filename.="_Последний вход";
            if (@$_POST['mail_db_login']['from'] != "") {
                $from = explode('.', $_POST['mail_db_login']['from']);
                unset($_POST['mail_db_login']['from']);
                $_POST['mail_db_login']['from']['year'] = $from[2];
                $_POST['mail_db_login']['from']['month'] = $from[1];
                $_POST['mail_db_login']['from']['day'] = $from[0];
            }
            if (@$_POST['mail_db_login']['to'] != "") {
                $from = explode('.', $_POST['mail_db_login']['to']);
                unset($_POST['mail_db_login']['to']);
                $_POST['mail_db_login']['to']['year'] = $from[2];
                $_POST['mail_db_login']['to']['month'] = $from[1];
                $_POST['mail_db_login']['to']['day'] = $from[0];
            }
            else {
              $showForm=true;
              $_POST['mail_db_login']['to']['year']=$_POST['mail_db_login']['to']['month'] =$_POST['mail_db_login']['to']['day'] ='';
            }
            $sqlWhere.= ' AND (`last_login` >="'.$_POST['mail_db_login']['from']['year'] . '-' . $_POST['mail_db_login']['from']['month'] . '-' . $_POST['mail_db_login']['from']['day'] . ' 00:00:00" '.
              'AND  `last_login`<="' . $_POST['mail_db_login']['to']['year'] . '-' . $_POST['mail_db_login']['to']['month'] . '-' . $_POST['mail_db_login']['to']['day'] . ' 23:59:59"'.') ';
        }
        if (@$_POST['mail_db_order']['from'] != "") {//Заказы дата
          $filename.="_СделалиЗаказ";
            if (@$_POST['mail_db_order']['from'] != "") {
                $from = explode('.', $_POST['mail_db_order']['from']);
                unset($_POST['mail_db_order']['from']);
                $_POST['mail_db_order']['from']['year'] = $from[2];
                $_POST['mail_db_order']['from']['month'] = $from[1];
                $_POST['mail_db_order']['from']['day'] = $from[0];
            }
            if (@$_POST['mail_db_order']['to'] != "") {
                $from = explode('.', $_POST['mail_db_order']['to']);
                unset($_POST['mail_db_order']['to']);
                $_POST['mail_db_order']['to']['year'] = $from[2];
                $_POST['mail_db_order']['to']['month'] = $from[1];
                $_POST['mail_db_order']['to']['day'] = $from[0];
            }
            else {
              $showForm=true;
              $_POST['mail_db_order']['to']['year']=$_POST['mail_db_order']['to']['month'] =$_POST['mail_db_order']['to']['day'] ='';
            }

            $sqlWhere.=
              " AND o.id IS NOT NULL ".
              " AND o.`created_at`>=\"" . $_POST['mail_db_order']['from']['year'] . "-" . $_POST['mail_db_order']['from']['month'] . "-" . $_POST['mail_db_order']['from']['day'] . " 00:00:00\" ".
              " AND o.`created_at`<=\"" . $_POST['mail_db_order']['to']['year'] . "-" . $_POST['mail_db_order']['to']['month'] . "-" . $_POST['mail_db_order']['to']['day'] . " 23:59:59\""
              ;
            $sqlGroup=" GROUP BY u.`email_address`,  u.`first_name`, u.`last_login`, u.`city` ";
            $sqlJoin= "LEFT JOIN `orders` AS o ON o.`customer_id` = u.`id`";
        }
        if (@$_POST['mail_db_prod_id'] != "") {//Заказавшие продукт
          $filename.="_ЗаказалПродукт";
            $prodId = explode(",", $_POST['mail_db_prod_id']);
            $prodidWhere = "";
            foreach ($prodId as $numProd => $id) {
                if ($numProd == 0) {
                    $prodidWhere.="`text` LIKE  '%\"product_id\";s:5:\"" . $id . "\"%'".
                    " OR  `text` LIKE  '\"productId\";i:" . $id . "'";
                } else {
                    $prodidWhere.=" or `text` LIKE  '%\"product_id\";s:5:\"" . $id . "\"%'".
                    " OR  `text` LIKE  '\"productId\";i:" . $id . "'";
                }
            }
            $sqlJoin= "LEFT JOIN `orders` AS o ON o.`customer_id` = u.`id`";
            $sqlWhere.=
              " AND (" . $prodidWhere . ")".
              " AND o.id IS NOT NULL"
            ;
            $sqlGroup="  GROUP BY u.`email_address`,  u.`first_name`, u.`last_login`, u.`city` ";

        }
        if (@$_POST['mail_db_newuser']['from'] != "") {//Новые пользователи
          $filename.="_ДатаРегистрации";
            if (@$_POST['mail_db_newuser']['from'] != "") {
                $from = explode('.', $_POST['mail_db_newuser']['from']);
                unset($_POST['mail_db_newuser']['from']);
                $_POST['mail_db_newuser']['from']['year'] = $from[2];
                $_POST['mail_db_newuser']['from']['month'] = $from[1];
                $_POST['mail_db_newuser']['from']['day'] = $from[0];
            }
            if (@$_POST['mail_db_newuser']['to'] != "") {
                $from = explode('.', $_POST['mail_db_newuser']['to']);
                unset($_POST['mail_db_newuser']['to']);
                $_POST['mail_db_newuser']['to']['year'] = $from[2];
                $_POST['mail_db_newuser']['to']['month'] = $from[1];
                $_POST['mail_db_newuser']['to']['day'] = $from[0];
            }
            else {
              $showForm=true;
              $_POST['mail_db_newuser']['to']['year']=$_POST['mail_db_newuser']['to']['month'] =$_POST['mail_db_newuser']['to']['day'] ='';
            }
            $sqlWhere.=" AND ".
              " u.`created_at`>=\"" . $_POST['mail_db_newuser']['from']['year'] . "-"
              . $_POST['mail_db_newuser']['from']['month'] . "-"
              . $_POST['mail_db_newuser']['from']['day'] . " 00:00:00\" and  u.`created_at`<=\""
              . $_POST['mail_db_newuser']['to']['year'] . "-" . $_POST['mail_db_newuser']['to']['month'] . "-"
              . $_POST['mail_db_newuser']['to']['day'] . " 23:59:59\"";
        }

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute($sqlFrom.  $sqlJoin. $sqlWhere. $sqlGroup);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $this->citys=$result->fetchAll();
        if (sizeof($this->citys) < 41348){  //иначе получаем переполнение памяти
          $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
          $cacheSettings = array('memoryCacheSize ' => '256MB');
          PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setCreator("OnOna");
          $objPHPExcel->getProperties()->setLastModifiedBy("OnOna");
          $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
          $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
          $objPHPExcel->getProperties()->setDescription("OnOna document for Office 2007 XLSX.");
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Имя');
          $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Фамилия');
          $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'E-mail');
          $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Зарегистрирован');
          $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Последний вход');
          $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Город');
          $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Дата рождения');
          $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Телефон');
          $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Пол');

          ini_set("max_execution_time", 240);

          foreach ($this->citys as $num => $city) {
              $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $city['first_name']);
              $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $city['last_name']);
              $objPHPExcel->getActiveSheet()->SetCellValue('C' . ($num + 2), $city['email_address']);
              $objPHPExcel->getActiveSheet()->SetCellValue('D' . ($num + 2), $city['created_at']);
              $objPHPExcel->getActiveSheet()->SetCellValue('E' . ($num + 2), $city['last_login']);
              $objPHPExcel->getActiveSheet()->SetCellValue('F' . ($num + 2), $city['city']);
              $objPHPExcel->getActiveSheet()->SetCellValue('G' . ($num + 2), $city['birthday']);
              $objPHPExcel->getActiveSheet()->SetCellValue('H' . ($num + 2), $city['phone']);
              $objPHPExcel->getActiveSheet()->SetCellValue('I' . ($num + 2), $city['sex']);

          }
          // $objPHPExcel->getActiveSheet()->setTitle("города");

          header("Content-Type:application/vnd.ms-excel");
          header("Content-Disposition:attachment;filename=\"" . $filename . ".xlsx\"");
          $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
          // $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
          $objWriter->save('php://output');

          exit;
        }
        else {
          // throw new Doctrine_Table_Exception('DEBUG memory overflow ~|'.print_r($_POST, true).'|~');
          unset($_POST);
          $this->error='Превышено ограничение на количество выводимых строк. Попробуйте изменить параметры запроса';
        }
      }

      /**/
      if(false){//Не работает. Должен выдавать список всех почт из всех таблиц. Непонятно как прикрутить
         if (@$_POST['mail_db_all'] == 1) {

             $q = Doctrine_Manager::getInstance()->getCurrentConnection();
             $result = $q->execute(
               '(select sf_guard_user.email_address,sf_guard_user.first_name from sf_guard_user where sf_guard_user.email_address <>"" and sf_guard_user.email_address like \'%@%\')'.
               ' UNION  ('.
               ' select senduser.mail,senduser.name from senduser where senduser.mail <>"" and senduser.mail like \'%@%\''.
               ' )UNION  ('.
               ' select comments.mail,comments.username from comments where comments.mail <>"" and comments.mail like \'%@%\''.
               ' )UNION  ('.
               ' select fast_order_log.mail,fast_order_log.username from fast_order_log where fast_order_log.mail <>"" and fast_order_log.mail like \'%@%\''.
               ' )'
             );
             $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
             $users = $result->fetchAll();


             foreach ($users as $num => $user) {
                 $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $user['first_name']);
                 $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $user['email_address']);
             }


             $objPHPExcel->getActiveSheet()->setTitle("Все пользователи");

             header("Content-Type:application/vnd.ms-excel");
             header("Content-Disposition:attachment;filename=\"Все пользователи.csv\"");
             $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
             $objWriter->save('php://output');
             exit;
         }
     }
 }

    public function executeGetMailDB(sfWebRequest $request) {
        if (count($_POST) > 0) {
            $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array('memoryCacheSize ' => '256MB');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("OnOna");
            $objPHPExcel->getProperties()->setLastModifiedBy("OnOna");
            $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
            $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
            $objPHPExcel->getProperties()->setDescription("OnOna document for Office 2007 XLSX.");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'ФИО');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Адрес');
            ini_set("max_execution_time", 240);
        }
        if (@$_POST['mail_db_citys'] != "") {


            $citys = explode(",", $_POST['mail_db_citys']);
            $this->citys = sfGuardUserTable::getInstance()->createQuery()->where('email_address <>"" and email_address like \'%@%\'  and email_address like \'%.%\' ');
            $cityWhere = "";
            foreach ($citys as $numCity => $city) {
                if ($numCity == 0) {
                    $cityWhere.='city like "%' . $city . '%"';
                } else {
                    $cityWhere.=' or city like "%' . $city . '%"';
                }
            }
            $this->citys = $this->citys->addwhere("(" . $cityWhere . ")")->fetchArray();
            /* header("Content-Type: application/force-download");
              header("Content-Disposition: attachment; filename=\"" . $_POST['mail_db_citys'] . ".txt\"");
              foreach ($this->citys as $city) {
              echo $city['email_address'] . "\n";
              } */
            /* header('Content-Type: text/html; charset=utf-8');
              header("Content-type: application/vnd.ms-excel");
              header("Content-Disposition: attachment; filename=\"" . $_POST['mail_db_citys'] . ".xls\"");
              echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><meta name="author" content="zabey" /><title>Demo</title></head><body><table border="1"><tr><td>ФИО</td><td>Адрес</td></tr>';
              foreach ($this->citys as $city) {
              echo "<tr><td>" . $city['first_name'] . "</td><td>" . $city['email_address'] . "</td></tr>";
              }
              echo "</table>"; */
            foreach ($this->citys as $num => $city) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $city['first_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $city['email_address']);
            }


            $objPHPExcel->getActiveSheet()->setTitle("города");

            header("Content-Type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=\"" . $_POST['mail_db_citys'] . ".csv\"");
            $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
            $objWriter->save('php://output');

            exit;
            //print_r($citys);
        } elseif (@$_POST['mail_db_login']['from'] != "") {
            if (@$_POST['mail_db_login']['from'] != "") {
                $from = explode('.', $_POST['mail_db_login']['from']);
                unset($_POST['mail_db_login']['from']);
                $_POST['mail_db_login']['from']['year'] = $from[2];
                $_POST['mail_db_login']['from']['month'] = $from[1];
                $_POST['mail_db_login']['from']['day'] = $from[0];
            }
            if (@$_POST['mail_db_login']['to'] != "") {
                $from = explode('.', $_POST['mail_db_login']['to']);
                unset($_POST['mail_db_login']['to']);
                $_POST['mail_db_login']['to']['year'] = $from[2];
                $_POST['mail_db_login']['to']['month'] = $from[1];
                $_POST['mail_db_login']['to']['day'] = $from[0];
            }
            $this->citys = sfGuardUserTable::getInstance()->createQuery()->where('email_address <>"" and email_address like \'%@%\'  and email_address like \'%.%\' and  last_login>="' . $_POST['mail_db_login']['from']['year'] . '-' . $_POST['mail_db_login']['from']['month'] . '-' . $_POST['mail_db_login']['from']['day'] . ' 00:00:00" and  last_login<="' . $_POST['mail_db_login']['to']['year'] . '-' . $_POST['mail_db_login']['to']['month'] . '-' . $_POST['mail_db_login']['to']['day'] . ' 23:59:59"')->fetchArray();
            /* header("Content-Type: application/force-download");
              header("Content-Disposition: attachment; filename=\"Последний вход " . $_POST['mail_db_login']['from']['year'] . ',' . $_POST['mail_db_login']['from']['month'] . ',' . $_POST['mail_db_login']['from']['day'] . '-' . $_POST['mail_db_login']['to']['year'] . ',' . $_POST['mail_db_login']['to']['month'] . ',' . $_POST['mail_db_login']['to']['day'] . ".txt\"");
              foreach ($this->citys as $city) {
              echo $city['email_address'] . "\n";
              } */
            /* header('Content-Type: text/html; charset=utf-8');
              header("Content-type: application/vnd.ms-excel");
              header("Content-Disposition: attachment; filename=\"Последний вход " . $_POST['mail_db_login']['from']['year'] . ',' . $_POST['mail_db_login']['from']['month'] . ',' . $_POST['mail_db_login']['from']['day'] . '-' . $_POST['mail_db_login']['to']['year'] . ',' . $_POST['mail_db_login']['to']['month'] . ',' . $_POST['mail_db_login']['to']['day'] . ".xls\"");
              echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><meta name="author" content="zabey" /><title>Demo</title></head><body><table border="1"><tr><td>ФИО</td><td>Адрес</td></tr>';
              foreach ($this->citys as $city) {
              echo "<tr><td>" . $city['first_name'] . "</td><td>" . $city['email_address'] . "</td></tr>";
              }
              echo "</table>"; */

            foreach ($this->citys as $num => $city) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $city['first_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $city['email_address']);
            }


            $objPHPExcel->getActiveSheet()->setTitle("Последний вход");

            header("Content-Type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=\"Последний вход " . $_POST['mail_db_login']['from']['year'] . ',' . $_POST['mail_db_login']['from']['month'] . ',' . $_POST['mail_db_login']['from']['day'] . '-' . $_POST['mail_db_login']['to']['year'] . ',' . $_POST['mail_db_login']['to']['month'] . ',' . $_POST['mail_db_login']['to']['day'] . ".csv\"");
            $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
            $objWriter->save('php://output');
            exit;
        } elseif (@$_POST['mail_db_order']['from'] != "") {
            if (@$_POST['mail_db_order']['from'] != "") {
                $from = explode('.', $_POST['mail_db_order']['from']);
                unset($_POST['mail_db_order']['from']);
                $_POST['mail_db_order']['from']['year'] = $from[2];
                $_POST['mail_db_order']['from']['month'] = $from[1];
                $_POST['mail_db_order']['from']['day'] = $from[0];
            }
            if (@$_POST['mail_db_order']['to'] != "") {
                $from = explode('.', $_POST['mail_db_order']['to']);
                unset($_POST['mail_db_order']['to']);
                $_POST['mail_db_order']['to']['year'] = $from[2];
                $_POST['mail_db_order']['to']['month'] = $from[1];
                $_POST['mail_db_order']['to']['day'] = $from[0];
            }
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            /*  echo "select * from sf_guard_user AS u
              LEFT JOIN orders AS o ON (o.customer_id = u.id and o.created_at>=\"".$_POST['mail_db_order']['from']['year']."-".$_POST['mail_db_order']['from']['month']."-".$_POST['mail_db_order']['from']['day']." 00:00:00\" and  o.created_at<=\"".$_POST['mail_db_order']['to']['year']."-".$_POST['mail_db_order']['to']['month']."-".$_POST['mail_db_order']['to']['day']." 23:59:59\")
              WHERE u.email_address <>  \"\"
              AND u.email_address LIKE  '%@%'
              AND u.email_address LIKE  '%.%'
              AND o.id IS NOT NULL
              " ;exit; */
            $result = $q->execute("select u.email_address,u.first_name from sf_guard_user AS u
LEFT JOIN orders AS o ON (o.customer_id = u.id and o.created_at>=\"" . $_POST['mail_db_order']['from']['year'] . "-" . $_POST['mail_db_order']['from']['month'] . "-" . $_POST['mail_db_order']['from']['day'] . " 00:00:00\" and  o.created_at<=\"" . $_POST['mail_db_order']['to']['year'] . "-" . $_POST['mail_db_order']['to']['month'] . "-" . $_POST['mail_db_order']['to']['day'] . " 23:59:59\")
WHERE u.email_address <>  \"\"
AND u.email_address LIKE  '%@%'
AND u.email_address LIKE  '%.%'
AND o.id IS NOT NULL
group by u.email_address ");
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $users = $result->fetchAll();
            // $this->citys = sfGuardUserTable::getInstance()->createQuery('u')->leftJoin("u.Orders o")->where('u.email_address <>"" and u.email_address like \'%@%\'  and u.email_address like \'%.%\' and  o.created_at>="'.$_POST['mail_db_order']['from']['year'].'-'.$_POST['mail_db_order']['from']['month'].'-'.$_POST['mail_db_order']['from']['day'].' 00:00:00" and  o.created_at<="'.$_POST['mail_db_order']['to']['year'].'-'.$_POST['mail_db_order']['to']['month'].'-'.$_POST['mail_db_order']['to']['day'].' 23:59:59"')->fetchArray();
            /* header("Content-Type: application/force-download");
              header("Content-Disposition: attachment; filename=\"Заказы в период " . $_POST['mail_db_order']['from']['year'] . ',' . $_POST['mail_db_order']['from']['month'] . ',' . $_POST['mail_db_order']['from']['day'] . '-' . $_POST['mail_db_order']['to']['year'] . ',' . $_POST['mail_db_order']['to']['month'] . ',' . $_POST['mail_db_order']['to']['day'] . ".txt\"");
              foreach ($users as $user) {
              echo $user['email_address'] . "\n";
              } */
            /* header('Content-Type: text/html; charset=utf-8');
              header("Content-type: application/vnd.ms-excel");
              header("Content-Disposition: attachment; filename=\"Заказы в период " . $_POST['mail_db_order']['from']['year'] . ',' . $_POST['mail_db_order']['from']['month'] . ',' . $_POST['mail_db_order']['from']['day'] . '-' . $_POST['mail_db_order']['to']['year'] . ',' . $_POST['mail_db_order']['to']['month'] . ',' . $_POST['mail_db_order']['to']['day'] . ".xls\"");
              echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><meta name="author" content="zabey" /><title>Demo</title></head><body><table border="1"><tr><td>ФИО</td><td>Адрес</td></tr>';
              foreach ($users as $user) {
              echo "<tr><td>" . $user['first_name'] . "</td><td>" . $user['email_address'] . "</td></tr>";
              }
              echo "</table>"; */

            foreach ($users as $num => $user) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $user['first_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $user['email_address']);
            }


            $objPHPExcel->getActiveSheet()->setTitle("Заказы в период");

            header("Content-Type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=\"Заказы в период " . $_POST['mail_db_order']['from']['year'] . ',' . $_POST['mail_db_order']['from']['month'] . ',' . $_POST['mail_db_order']['from']['day'] . '-' . $_POST['mail_db_order']['to']['year'] . ',' . $_POST['mail_db_order']['to']['month'] . ',' . $_POST['mail_db_order']['to']['day'] . ".csv\"");
            $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
            $objWriter->save('php://output');
            exit;
        } elseif (@$_POST['mail_db_prod_id'] != "") {
            $prodId = explode(",", $_POST['mail_db_prod_id']);
            $prodidWhere = "";
            foreach ($prodId as $numProd => $id) {
                if ($numProd == 0) {
                    $prodidWhere.="`text` LIKE  '%\"product_id\";s:5:\"" . $id . "\"%'
OR  `text` LIKE  '\"productId\";i:" . $id . "'";
                } else {
                    $prodidWhere.=" or `text` LIKE  '%\"product_id\";s:5:\"" . $id . "\"%'
OR  `text` LIKE  '\"productId\";i:" . $id . "'";
                }
            }
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("select u.email_address,u.first_name from sf_guard_user AS u
LEFT JOIN orders AS o ON (o.customer_id = u.id and (" . $prodidWhere . "))
WHERE u.email_address <>  \"\"
AND u.email_address LIKE  '%@%'
AND u.email_address LIKE  '%.%'
AND o.id IS NOT NULL
group by u.email_address ");
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $users = $result->fetchAll();
            // $this->citys = sfGuardUserTable::getInstance()->createQuery('u')->leftJoin("u.Orders o")->where('u.email_address <>"" and u.email_address like \'%@%\'  and u.email_address like \'%.%\' and  o.created_at>="'.$_POST['mail_db_order']['from']['year'].'-'.$_POST['mail_db_order']['from']['month'].'-'.$_POST['mail_db_order']['from']['day'].' 00:00:00" and  o.created_at<="'.$_POST['mail_db_order']['to']['year'].'-'.$_POST['mail_db_order']['to']['month'].'-'.$_POST['mail_db_order']['to']['day'].' 23:59:59"')->fetchArray();
            /* header("Content-Type: application/force-download");
              header("Content-Disposition: attachment; filename=\"Заказы c id товаров " . $_POST['mail_db_prod_id'] . '.txt"');
              foreach ($users as $user) {
              echo $user['email_address'] . "\n";
              } */

            /* header('Content-Type: text/html; charset=utf-8');
              header("Content-type: application/vnd.ms-excel");
              header("Content-Disposition: attachment; filename=\"Заказы c id товаров " . $_POST['mail_db_prod_id'] . ".xls\"");
              echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><meta name="author" content="zabey" /><title>Demo</title></head><body><table border="1"><tr><td>ФИО</td><td>Адрес</td></tr>';
              foreach ($users as $user) {
              echo "<tr><td>" . $user['first_name'] . "</td><td>" . $user['email_address'] . "</td></tr>";
              }
              echo "</table>"; */

            foreach ($users as $num => $user) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $user['first_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $user['email_address']);
            }


            $objPHPExcel->getActiveSheet()->setTitle("Заказы c id товаров");

            header("Content-Type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=\"Заказы c id товаров " . $_POST['mail_db_prod_id'] . ".csv\"");
            $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
            $objWriter->save('php://output');
            exit;
        } elseif (@$_POST['mail_db_all'] == 1) {

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute('(select sf_guard_user.email_address,sf_guard_user.first_name from sf_guard_user where sf_guard_user.email_address <>"" and sf_guard_user.email_address like \'%@%\')
        UNION  (
        select senduser.mail,senduser.name from senduser where senduser.mail <>"" and senduser.mail like \'%@%\'
        )UNION  (
        select comments.mail,comments.username from comments where comments.mail <>"" and comments.mail like \'%@%\'
        )UNION  (
        select fast_order_log.mail,fast_order_log.username from fast_order_log where fast_order_log.mail <>"" and fast_order_log.mail like \'%@%\'
        )');
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $users = $result->fetchAll();
            /* header('Content-Type: text/html; charset=utf-8');
              header("Content-type: application/vnd.ms-excel");
              header("Content-Disposition: attachment; filename=\"Все пользователи.xls\"");
              echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><meta name="author" content="zabey" /><title>Demo</title></head><body><table border="1"><tr><td>ФИО</td><td>Адрес</td></tr>';
              foreach ($users as $user) {
              echo "<tr><td>" . $user['first_name'] . "</td><td>" . $user['email_address'] . "</td></tr>";
              }
              echo "</table>"; */
            foreach ($users as $num => $user) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $user['first_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $user['email_address']);
            }


            $objPHPExcel->getActiveSheet()->setTitle("Все пользователи");

            header("Content-Type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=\"Все пользователи.csv\"");
            $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
            $objWriter->save('php://output');
            exit;
        } elseif (@$_POST['mail_db_newuser']['from'] != "") {
            if (@$_POST['mail_db_newuser']['from'] != "") {
                $from = explode('.', $_POST['mail_db_newuser']['from']);
                unset($_POST['mail_db_newuser']['from']);
                $_POST['mail_db_newuser']['from']['year'] = $from[2];
                $_POST['mail_db_newuser']['from']['month'] = $from[1];
                $_POST['mail_db_newuser']['from']['day'] = $from[0];
            }
            if (@$_POST['mail_db_newuser']['to'] != "") {
                $from = explode('.', $_POST['mail_db_newuser']['to']);
                unset($_POST['mail_db_newuser']['to']);
                $_POST['mail_db_newuser']['to']['year'] = $from[2];
                $_POST['mail_db_newuser']['to']['month'] = $from[1];
                $_POST['mail_db_newuser']['to']['day'] = $from[0];
            }
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("SELECT *
FROM  `sf_guard_user`
WHERE  created_at>=\"" . $_POST['mail_db_newuser']['from']['year'] . "-" . $_POST['mail_db_newuser']['from']['month'] . "-" . $_POST['mail_db_newuser']['from']['day'] . " 00:00:00\" and  created_at<=\"" . $_POST['mail_db_newuser']['to']['year'] . "-" . $_POST['mail_db_newuser']['to']['month'] . "-" . $_POST['mail_db_newuser']['to']['day'] . " 23:59:59\"");
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $users = $result->fetchAll();
            /* header('Content-Type: text/html; charset=utf-8');
              header("Content-type: application/vnd.ms-excel");
              header("Content-Disposition: attachment; filename=\"Все пользователи.xls\"");
              echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><meta name="author" content="zabey" /><title>Demo</title></head><body><table border="1"><tr><td>ФИО</td><td>Адрес</td></tr>';
              foreach ($users as $user) {
              echo "<tr><td>" . $user['first_name'] . "</td><td>" . $user['email_address'] . "</td></tr>";
              }
              echo "</table>"; */
            foreach ($users as $num => $user) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $user['first_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $user['email_address']);
            }


            $objPHPExcel->getActiveSheet()->setTitle("Новые пользователи");

            header("Content-Type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=\"Новые пользователи.csv\"");
            $objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
            $objWriter->save('php://output');
            exit;
        }
    }

    public function executeCityUndraw(sfWebRequest $request) {
        $city = CityTable::getInstance()->findAll();
        $article = "";
        foreach ($city as $town) {
            $page = PageTable::getInstance()->findOneByCityId($town->getId());
            if (!$page)
                $citys.=$town->getName() . "<br>";
        }
        echo $citys;
        exit;
    }

    public function executeStatsCard(sfWebRequest $request) {
        $arrayStats = unserialize(csSettings::get('stats_card_and_phones'));

        $this->phoneMail = $arrayStats['phoneMail'];
        $this->phoneMailNotReg = $arrayStats['phoneMailNotReg'];
        $this->phoneMailNotRegNotValid = $arrayStats['phoneMailNotRegNotValid'];
        $this->ordersCountPhone = $arrayStats['ordersCountPhone'];
        $this->cardMail = $arrayStats['cardMail'];
        $this->cardMailNotReg = $arrayStats['cardMailNotReg'];
        $this->cardMailNotRegNotValid = $arrayStats['cardMailNotRegNotValid'];
        $this->ordersCountCard = $arrayStats['ordersCountCard'];
    }

    private function formatDate($datestr){
      $dateArr=explode('.', $datestr);
      return $dateArr[2]."-".$dateArr[1]."-".$dateArr[0];
    }

    protected function getDBConfig(){
      foreach(Doctrine_Manager::getInstance()->getConnections() as $connection){
        $conn = $connection->getOptions();
        preg_match('/host=(.*);/', $conn['dsn'], $host);
        preg_match('/dbname=(.*)/', $conn['dsn'], $dbname);
        return [
          'all'=>$conn,
          'host'     =>  $host[1],
          'db_name'  =>  $dbname[1],
          'user'     =>  $conn['username'],
          'pass'     =>  $conn['password'],
        ];
      }
    }

    public function executeSearchLog(sfWebRequest $request) {
        if(isset($_POST['download'])){  //задача 106447. Скачать результаты

          $csvDelimiter=';';
          $query='SELECT `text`, count(`text`) as textCount FROM search_log';
          $and=' WHERE ';
          $where='';

          if (isset($_POST['fromDate']) && $_POST['fromDate'] != "") {
            $where.=$and.'created_at >='."'".$this->formatDate($_POST['fromDate'])." 00:00:00'";
            $and= ' AND ';
          }
          if (isset($_POST['to']) && $_POST['to'] != "") {
            $where.=$and.'created_at <='."'".$this->formatDate($_POST['to'])." 23:59:59'";
            $and= ' AND ';
          }
          if (isset($_POST['search']) && $_POST['search'] != "") {
            $where.=$and.'`text` LIKE "%'.$_POST['search'].'%"';
            $and= ' AND ';
          }
          $groupBy=' GROUP BY TRIM(`text`)';

          // echo'<pre>'.print_r($query.$where.$groupBy, true).'</pre>';

          $config=$this->getDBConfig();

          $dbId=@mysql_connect($config['host'], $config['user'], $config['pass']);
          @mysql_query('SET NAMES UTF8');
          @mysql_select_db($config['db_name']);
          $resourseId = @mysql_query($query.$where.$groupBy, $dbId);
          if ($error=@mysql_error()) die($error)."\n";
          $i=0;
          $dataArray[]=implode($csvDelimiter, [
            'Строка',
            'Количество',
          ]);
          while ($rowOne = mysql_fetch_assoc($resourseId)) {
              // $email=trim($rowOne['email_address']);
              // if(!$this->testForEmail($email)) continue;
              // $i++;
              $row=implode($csvDelimiter, [
                // $email,
                // trim($rowOne['email_address']),
                // 'true',
                iconv("UTF-8", "CP1251", str_replace($csvDelimiter, '|', $rowOne['text'])),
                $rowOne['textCount'],

              ]);
              $dataArray[]=$row;
            }
          @mysql_free_result($resourseId);
          header("Content-Type:application/vnd.ms-excel");
          header("Content-Disposition:attachment;filename=\"" . 'searchlog' . ".csv\"");
          // die('<pre>'.print_r($dataArray, true).'</pre>');
          echo implode("\n",$dataArray);

          // echo'<pre>'.print_r($dataArray , true).'</pre>';

            exit();
        }
        if ($request->getParameter('sort')) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        } else {

            $this->setSort(array('id', 'asc'));
        }
        //$this->setSort(array("",""));
        $this->sort = $this->getSort();
        $searchLog = $request->getParameter('search_log');
        if (count($searchLog) > 0) {
            $this->getUser()->setAttribute('searchLog', serialize($searchLog));
        }
        if ($this->getUser()->getAttribute('searchLog') != "") {
            $searchLog = unserialize($this->getUser()->getAttribute('searchLog'));
        }
        //print_r($_POST);
        if (isset($_POST['fromDate']) && $_POST['fromDate'] != "") {

            $this->getUser()->setAttribute('searchLogFrom', $_POST['fromDate']);
            $from = explode('.', $_POST['fromDate']);
            $searchLog['created_at']['from']['year'] = $from[2];
            $searchLog['created_at']['from']['month'] = $from[1];
            $searchLog['created_at']['from']['day'] = $from[0];
        }
        if (isset($_POST['to']) && $_POST['to']!= "") {
            $this->getUser()->setAttribute('searchLogTo', $_POST['to']);
            $from = explode('.', $_POST['to']);
            $searchLog['created_at']['to']['year'] = $from[2];
            $searchLog['created_at']['to']['month'] = $from[1];
            $searchLog['created_at']['to']['day'] = $from[0];
        }

        //$this->getUser()->setAttribute('searchLogTo', '');
        if ($this->getUser()->getAttribute('searchLogFrom') != "") {

            $searchLog = $this->getUser()->getAttribute('searchLogFrom');
            $_POST['fromDate'] = $searchLog;
            $from = explode('.', $searchLog);
            unset($searchLog);
            $searchLog['created_at']['from']['year'] = $from[2];
            $searchLog['created_at']['from']['month'] = $from[1];
            $searchLog['created_at']['from']['day'] = $from[0];
        }

        if ($this->getUser()->getAttribute('searchLogTo') != "") {
            $searchLog2 = $this->getUser()->getAttribute('searchLogTo');
            $_POST['to'] = $searchLog2;
            $from = explode('.', $searchLog2);
            //unset($searchLog);
            $searchLog['created_at']['to']['year'] = $from[2];
            $searchLog['created_at']['to']['month'] = $from[1];
            $searchLog['created_at']['to']['day'] = $from[0];
        }
        $this->searchLog = $searchLog;
        //print_r($searchLog);
        if (count($searchLog) > 0) {
            $this->result = SearchLogTable::getInstance()->createQuery()->select("text")->addSelect("COUNT(text) as countquery")->where("created_at > ?", $searchLog['created_at']['from']['year'] . "-" . $searchLog['created_at']['from']['month'] . "-" . $searchLog['created_at']['from']['day'])->addWhere("created_at < ?", $searchLog['created_at']['to']['year'] . "-" . $searchLog['created_at']['to']['month'] . "-" . $searchLog['created_at']['to']['day'])->groupBy("text");
        } else {
            $this->result = SearchLogTable::getInstance()->createQuery()->select("text")->addSelect("COUNT(text) as countquery")->groupBy("text");
        }
        if (isset ($_POST['search']) && $_POST['search']!= "")
            $this->result->addWhere("text like '%" . $_POST['search'] . "%'");

        $this->addSortQuery($this->result/* ->getSqlQuery() */);


        //$this->result->limit(5000);
        // $query = $this->result->getDql();
        $this->pager = new sfDoctrinePager('SearchLog', 100);

        $this->pager->setQuery($this->result);

        $this->pager->setPage($request->getParameter('page', 1));

        $this->pager->init();

        /* $this->pager = new sfDoctrinePager('SearchLog', 100);
          $this->pager->setQuery($query);
          $this->pager->setPage($request->getParameter('page', 1));
          $this->pager->init();
          $this->result = $this->result->execute(); */
    }

    public function executeCc(sfWebRequest $request) {

        /* $dispatcher = new sfEventDispatcher();
          $formatter = new sfFormatter();

          $task = new sfGenerateProjectTask($dispatcher, $formatter);
          $task->run(array('test'));
          $task = new sfGenerateAppTask($dispatcher, $formatter);
          $task->run(array('frontend'));

          require_once sfConfig::get('sf_root_dir') . '/config/ProjectConfiguration.class.php';
          $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', true);

          // Put something in the cache
          $file = sfConfig::get('sf_config_cache_dir') . DIRECTORY_SEPARATOR . 'test';
          touch($file);

          $task = new sfCacheClearTask($dispatcher, $formatter);
          $task->run(); */
        //$cache = sfCacheClearTask::clearAllCache("sfCache::ALL");
        //new sfCacheClearTask();
        if (sfContext::getInstance()->getUser()->hasPermission("Enter backend")) {
            exec("../symfony cc", $test);
            //print_r($test);exit;
        }
    }

    public function executeProductnotenabled(sfWebRequest $request) {
        $bufferOutput = "";
        /* $handle = fopen("/var/www/ononaru/data/www/prodnotenabled/code1.txt", "r");
          while (!feof($handle)) {
          $buffer = fgets($handle, 4096);
          if (trim($buffer) != "") {
          $buffer = trim($buffer);
          $bufferOutput.=$buffer;
          if ($product = ProductTable::getInstance()->findOneByCode($buffer)) {
          $product->setIsPublic(0);
          $product->save();
          $bufferOutput.=" - https://onona.ru/product/" . $product->getSlug();
          } else {
          $bufferOutput.=" - Не найден";
          }

          $bufferOutput.="\r\n";
          }
          }
          $handle = fopen("/var/www/ononaru/data/www/prodnotenabled/code2.txt", "r");
          while (!feof($handle)) {
          $buffer = fgets($handle, 4096);
          if (trim($buffer) != "") {
          $buffer = trim($buffer);
          $bufferOutput.=$buffer;
          if ($product = ProductTable::getInstance()->findOneByCode($buffer)) {
          $product->setIsPublic(0);
          $product->save();
          $bufferOutput.=" - https://onona.ru/product/" . $product->getSlug();
          } else {
          $bufferOutput.=" - Не найден";
          }

          $bufferOutput.="\r\n";
          }
          } */
        /* $handle = fopen("/var/www/ononaru/data/www/prodnotenabled/code3.txt", "r");
          while (!feof($handle)) {
          $buffer = fgets($handle, 4096);
          if (trim($buffer) != "") {
          $buffer = trim($buffer);
          $bufferOutput.=$buffer;
          if ($product = ProductTable::getInstance()->findOneByCode($buffer)) {
          $product->setIsPublic(1);
          $product->save();
          $bufferOutput.=" - https://onona.ru/product/" . $product->getSlug();
          } else {
          $bufferOutput.=" - Не найден";
          }

          $bufferOutput.="\r\n";
          }
          } */
        $handle = fopen("/var/www/ononaru/data/www/prodnotenabled/code_160715.txt", "r");
        while (!feof($handle)) {
            $buffer = fgets($handle, 4096);
            if (trim($buffer) != "") {
                $buffer = trim($buffer);
                $bufferOutput.=$buffer;
                if ($product = ProductTable::getInstance()->findOneByCode($buffer)) {
                    $product->setIsPublic(0);
                    $product->save();
                    $bufferOutput.=" - https://onona.ru/product/" . $product->getSlug();
                } else {
                    $bufferOutput.=" - Не найден";
                }

                $bufferOutput.="\r\n";
            }
        }
        $this->bufferOutput = $bufferOutput;
    }

    public function executeArticlesshow(sfWebRequest $request) {

        $products = ProductTable::getInstance()->findAll();
        $article = "";
        foreach ($products as $product) {
            $article.=$product->getCode() . "<br>";
        }
        echo $article;
        exit;
    }

    public function executeBonusstats(sfWebRequest $request) {
        $this->bonusAllSum = BonusTable::getInstance()->createQuery("b")->select("Sum(bonus) as sum")->fetchArray();
        $this->bonusAll = BonusTable::getInstance()->createQuery("b")->select("Year(created_at) as year, Month(created_at) as Month, Sum(bonus) as sum")->groupBy("Year(created_at)")->addGroupBy("Month(created_at)")->orderBy("created_at desc")->fetchArray();
        $this->bonusPay = BonusTable::getInstance()->createQuery("b")->select("Year(created_at) as year, Month(created_at) as Month, Sum(bonus) as sum")->where("comment like '%Снятие бонусов в счет оплаты заказа %'")->groupBy("Year(created_at)")->addGroupBy("Month(created_at)")->orderBy("created_at desc")->fetchArray();
        $this->bonusRegister = BonusTable::getInstance()->createQuery("b")->select("Year(created_at) as year, Month(created_at) as Month, Sum(bonus) as sum")->where("comment like '%Зачисление за регистрацию%'")->groupBy("Year(created_at)")->addGroupBy("Month(created_at)")->orderBy("created_at desc")->fetchArray();
        $this->bonusBirthday = BonusTable::getInstance()->createQuery("b")->select("Year(created_at) as year, Month(created_at) as Month, Sum(bonus) as sum")->where("comment like '%Бонус в День Рождения%'")->groupBy("Year(created_at)")->addGroupBy("Month(created_at)")->orderBy("created_at desc")->fetchArray();
        $this->bonusLifetime = BonusTable::getInstance()->createQuery("b")->select("Year(created_at) as year, Month(created_at) as Month, Sum(bonus) as sum")->where("comment like '%Истекло время жизни. %'")->groupBy("Year(created_at)")->addGroupBy("Month(created_at)")->orderBy("created_at desc")->fetchArray();
        $this->bonusOrder = BonusTable::getInstance()->createQuery("b")->select("Year(created_at) as year, Month(created_at) as Month, Sum(bonus) as sum")->where("comment like '%Зачисление за заказ %'")->groupBy("Year(created_at)")->addGroupBy("Month(created_at)")->orderBy("created_at desc")->fetchArray();
        $this->bonusShop = BonusTable::getInstance()->createQuery("b")->select("Year(created_at) as year, Month(created_at) as Month, Sum(bonus) as sum")->where("comment like '%За покупку в магазине%'")->groupBy("Year(created_at)")->addGroupBy("Month(created_at)")->orderBy("created_at desc")->fetchArray();
        $this->otherPlus = BonusTable::getInstance()->createQuery("b")->select("Year(created_at) as year, Month(created_at) as Month, Sum(bonus) as sum")->where("comment not like '%За покупку в магазине%'")
                        ->addwhere("comment not like '%Зачисление за заказ %'")
                        ->addwhere("comment not like '%Истекло время жизни. %'")
                        ->addwhere("comment not like '%Бонус в День Рождения%'")
                        ->addwhere("comment not like '%Зачисление за регистрацию%'")
                        ->addwhere("comment not like '%Снятие бонусов в счет оплаты заказа %'")
                        ->addwhere("bonus >0")
                        ->groupBy("Year(created_at)")->addGroupBy("Month(created_at)")->orderBy("created_at desc")->fetchArray();
        $this->otherMinus = BonusTable::getInstance()->createQuery("b")->select("Year(created_at) as year, Month(created_at) as Month, Sum(bonus) as sum")->where("comment not like '%За покупку в магазине%'")
                        ->addwhere("comment not like '%Зачисление за заказ %'")
                        ->addwhere("comment not like '%Истекло время жизни. %'")
                        ->addwhere("comment not like '%Бонус в День Рождения%'")
                        ->addwhere("comment not like '%Зачисление за регистрацию%'")
                        ->addwhere("comment not like '%Снятие бонусов в счет оплаты заказа %'")
                        ->addwhere("bonus <0")
                        ->groupBy("Year(created_at)")->addGroupBy("Month(created_at)")->orderBy("created_at desc")->fetchArray();
    }

    public function executeUsersendstats(sfWebRequest $request) {
        if (@$_POST['fromDate'] != "") {

            $this->getUser()->setAttribute('managerstatsFrom', $_POST['fromDate']);
            $from = explode('.', $_POST['fromDate']);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }
        if (@$_POST['to'] != "") {
            $this->getUser()->setAttribute('managerstatsTo', $_POST['to']);
            $from = explode('.', $_POST['to']);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }
        //$this->getUser()->setAttribute('searchLogTo', '');
        if ($this->getUser()->getAttribute('managerstatsFrom') != "") {

            $managerstats = $this->getUser()->getAttribute('managerstatsFrom');
            $_POST['fromDate'] = $managerstats;
            $from = explode('.', $managerstats);
            unset($managerstats);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }

        if ($this->getUser()->getAttribute('managerstatsTo') != "") {
            $searchLog2 = $this->getUser()->getAttribute('managerstatsTo');
            $_POST['to'] = $searchLog2;
            $from = explode('.', $searchLog2);
            //unset($searchLog);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        if (count($managerstats) > 0) {

            $result = $q->execute("SELECT product_id, code , COUNT( product_id ) as countPod, p.name as prodname
FROM  `senduser`
left join product p on p.id= senduser.product_id
WHERE  senduser.created_at > '" . $managerstats['created_at']['from']['year'] . "-" . $managerstats['created_at']['from']['month'] . "-" . $managerstats['created_at']['from']['day'] . "' and senduser.created_at < '" . $managerstats['created_at']['to']['year'] . "-" . $managerstats['created_at']['to']['month'] . "-" . $managerstats['created_at']['to']['day'] . "'
GROUP BY product_id
ORDER BY COUNT( product_id ) DESC ");
        } else {

            $result = $q->execute("SELECT * , COUNT( product_id ) as countPod, p.name as prodname
FROM  `senduser`
left join product p on p.id= senduser.product_id
GROUP BY product_id
ORDER BY COUNT( product_id ) DESC ");
        }
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $this->productsUsersend = $result->fetchAll();
        $this->managerstats = $managerstats;
        //print_r($this->productsUsersend);
    }

    public function executeUsersendsetsend(sfWebRequest $request) {

        $usersend = SenduserTable::getInstance()->findOneById($request->getParameter('user'));

        $usersend->setIsManager($usersend->getIsManager() ? 0 : 1);
        $usersend->save();
        return $this->renderText($usersend->getIsManager());
    }

    public function executeUsersendstatsmail(sfWebRequest $request) {

        $usersend = SenduserTable::getInstance()->findOneById($request->getParameter('user'));
        return $this->renderText($usersend->getMail().($usersend->getPhone() ? '; '.$usersend->getPhone() : ''));
        //print_r($this->productsUsersend);
    }

    public function executeManagersstats(sfWebRequest $request) {

        ini_set("max_execution_time", 240);
        sfProjectConfiguration::getActive()->loadHelpers('Date');
        if (@$_GET['period'] == "week") {
            $_POST['fromDate'] = format_date(time() - 604800, "p");
            $_POST['to'] = format_date(time(), "p");
        }

        if (@$_GET['period'] == "month") {
            $_POST['fromDate'] = format_date(time() - 2592000, "p");
            $_POST['to'] = format_date(time(), "p");
        }
        if (@$_POST['fromDate'] != "") {

            $this->getUser()->setAttribute('managerstatsFrom', $_POST['fromDate']);
            $from = explode('.', $_POST['fromDate']);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }
        if (@$_POST['to'] != "") {
            $this->getUser()->setAttribute('managerstatsTo', $_POST['to']);
            $from = explode('.', $_POST['to']);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }
        //$this->getUser()->setAttribute('searchLogTo', '');
        if ($this->getUser()->getAttribute('managerstatsFrom') != "") {

            $managerstats = $this->getUser()->getAttribute('managerstatsFrom');
            $_POST['fromDate'] = $managerstats;
            $from = explode('.', $managerstats);
            unset($managerstats);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }

        if ($this->getUser()->getAttribute('managerstatsTo') != "") {
            $searchLog2 = $this->getUser()->getAttribute('managerstatsTo');
            $_POST['to'] = $searchLog2;
            $from = explode('.', $searchLog2);
            //unset($searchLog);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        if (@count($managerstats) > 0) {
            $this->oprosniki = $q->execute("SELECT * from oprosnik "
                            . "WHERE created_at > '" . $managerstats['created_at']['from']['year'] . "-" . $managerstats['created_at']['from']['month'] . "-" . $managerstats['created_at']['from']['day'] . "' "
                            . "AND created_at < '" . $managerstats['created_at']['to']['year'] . "-" . $managerstats['created_at']['to']['month'] . "-" . $managerstats['created_at']['to']['day'] . "' ")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            //$this->oprosniki = OprosnikTable::getInstance()->createQuery()->where("created_at > ?", $managerstats['created_at']['from']['year'] . "-" . $managerstats['created_at']['from']['month'] . "-" . $managerstats['created_at']['from']['day'])->addWhere("created_at < ?", $managerstats['created_at']['to']['year'] . "-" . $managerstats['created_at']['to']['month'] . "-" . $managerstats['created_at']['to']['day'])->execute();
        } else {
            //$this->oprosniki = OprosnikTable::getInstance()->findAll();
            $this->oprosniki = $q->execute("SELECT * from oprosnik")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        }
        //$this->oprosniki = OprosnikTable::getInstance()->findAll();
        if (count($this->oprosniki) > 0)
            $idx = 0;
            foreach ($this->oprosniki as $oprosnik) if ($oprosnik['orderid']) {
                $order = OrdersTable::getInstance()->findOneById($oprosnik['orderid']);
                if (!$order) continue;
                @$data = unserialize($oprosnik['dataans']);
                if ($data['managerBall'] == "Отлично") {
                    $ratingShop = 5;
                } else if ($data['managerBall'] == "Хорошо") {
                    $ratingShop = 4;
                } else if ($data['managerBall'] == "Так себе") {
                    $ratingShop = 3;
                } else if ($data['managerBall'] == "Плохо") {
                    $ratingShop = 2;
                } else {
                    $ratingShop = 1;
                }
                if ($data['managerBall'] == "")
                    $ratingShop = $oprosnik['rating'];


                if ($data['productBall'] == "Отлично") {
                    $productBall = 5;
                } else if ($data['productBall'] == "Хорошо") {
                    $productBall = 4;
                } else if ($data['productBall'] == "Так себе") {
                    $productBall = 3;
                } else if ($data['productBall'] == "Плохо") {
                    $productBall = 2;
                } else {
                    $productBall = 1;
                }
                if ($data['productBall'] == "")
                    $productBall = $oprosnik['rating'];


                if ($data['deliveryBall'] == "Отлично") {
                    $deliveryBall = 5;
                } else if ($data['deliveryBall'] == "Хорошо") {
                    $deliveryBall = 4;
                } else if ($data['deliveryBall'] == "Так себе") {
                    $deliveryBall = 3;
                } else if ($data['deliveryBall'] == "Плохо") {
                    $deliveryBall = 2;
                } else {
                    $deliveryBall = 1;
                }
                if ($data['deliveryBall'] == "")
                    $deliveryBall = $oprosnik['rating'];


                if ($data['wwwBall'] == "Отлично") {
                    $wwwBall = 5;
                } else if ($data['wwwBall'] == "Хорошо") {
                    $wwwBall = 4;
                } else if ($data['wwwBall'] == "Так себе") {
                    $wwwBall = 3;
                } else if ($data['wwwBall'] == "Плохо") {
                    $wwwBall = 2;
                } else {
                    $wwwBall = 1;
                }
                if ($data['wwwBall'] == "")
                    $wwwBall = $oprosnik['rating'];


                if ($data['shopBall'] == "Отлично") {
                    $shopBall = 5;
                } else if ($data['shopBall'] == "Хорошо") {
                    $shopBall = 4;
                } else if ($data['shopBall'] == "Так себе") {
                    $shopBall = 3;
                } else if ($data['shopBall'] == "Плохо") {
                    $shopBall = 2;
                } else {
                    $shopBall = 1;
                }
                if ($data['shopBall'] == "")
                    $shopBall = $oprosnik['rating'];

                $manager = $order->getManager();
                if ($manager) {
                  @$stats[$manager]['orders'][$oprosnik['orderid']]['managerBall'] = $ratingShop;
                  @$stats[$manager]['orders'][$oprosnik['orderid']]['productBall'] = $productBall;
                  @$stats[$manager]['orders'][$oprosnik['orderid']]['deliveryBall'] = $deliveryBall;
                  @$stats[$manager]['orders'][$oprosnik['orderid']]['wwwBall'] = $wwwBall;
                  @$stats[$manager]['orders'][$oprosnik['orderid']]['shopBall'] = $shopBall;
                  @$stats[$manager]['orders'][$oprosnik['orderid']]['rating'] = $oprosnik['rating'];
                  @$stats[$manager]['rating'] = $stats[$manager]['rating'] + $oprosnik['rating'];
                  @$stats[$manager]['managerBall'] = $stats[$manager]['managerBall'] + $ratingShop;
                  @$stats[$manager]['productBall'] = $stats[$manager]['productBall'] + $productBall;
                  @$stats[$manager]['deliveryBall'] = $stats[$manager]['deliveryBall'] + $deliveryBall;
                  @$stats[$manager]['wwwBall'] = $stats[$manager]['wwwBall'] + $wwwBall;
                  @$stats[$manager]['shopBall'] = $stats[$manager]['shopBall'] + $shopBall;
                  @$stats[$manager]['count'] = $stats[$manager]['count'] + 1;
                }

                //echo $order->getManager();
                $idx++;
            }

        $this->stats = $stats;
    }

    public function executeIndex(sfWebRequest $request) {
        // sorting
        if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort'))) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        }

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }
        $sortData = $this->getSort();
        if ($sortData[0] == "countquery") {
            $this->setSort(array('id', 'asc'));
        }
        $this->pager = $this->getPager();
        $this->sort = $this->getSort();
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {

            /*
              sfContext::switchTo('newcat');
              $cacheManager = sfContext::getInstance()->getViewCacheManager();
              if ($cacheManager) {
              $cacheManager->remove('@sf_cache_partial?module=category&action=_products&sf_cache_key='.($form->getObject()->getId()).'*');
              }
              sfContext::switchTo('backend'); */



            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {
                $page = $form->save();
            } catch (Doctrine_Validator_Exception $e) {

                $errorStack = $form->getObject()->getErrorStack();

                $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
                foreach ($errorStack as $field => $errors) {
                    $message .= "$field (" . implode(", ", $errors) . "), ";
                }
                $message = trim($message, ', ');

                $this->getUser()->setFlash('error', $message);
                return sfView::SUCCESS;
            }

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $page)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@page_new');
            } else {
                $this->getUser()->setFlash('notice', $notice);

                $this->redirect(array('sf_route' => 'page_edit', 'sf_subject' => $page));
            }
        } else {
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
    }

    public function executeSetBestSellers(sfWebRequest $request) {
        $this->file = $_FILES['file'];
        $this->productId = array();
        $this->productName = array();
        /* $setting = csSettingTable::getInstance()->findOneBySlug("bestsellersProducts");
          $setting->setValue(implode(",", array_keys(array_slice($bestsellersArray, 0, 20, true))));
          $setting->save(); */
        if (is_array($this->file)) {
            if (is_uploaded_file($this->file['tmp_name'])) {
                $handle = fopen($this->file['tmp_name'], "r");
                while (!feof($handle)) {
                    $buffer = fgets($handle, 4096);
                    $product = ProductTable::getInstance()->findOneByCode(trim($buffer));

                    //  echo $buffer.$product2."<br/>";
                    if ($product) {
                        $this->productId[] = $product->getId();
                        $this->productName[] = $product->getName();
                    }
                    $product = null;
                    //echo $buffer;
                }
                fclose($handle);
                if (count($this->productId) > 0) {
                    $setting = csSettingTable::getInstance()->findOneBySlug("bestsellersProducts");
                    $setting->setValue(implode(",", $this->productId));
                    $setting->save();
                }

                if (sfContext::getInstance()->getUser()->hasPermission("Enter backend")) {
                    exec("../symfony cc", $test);
                    //print_r($test);exit;
                }
            }
        }
        $this->setTemplate('setOptimizationIdProducts');
    }

    public function executeSetNoPersonalRecomendation(sfWebRequest $request) {
        $this->file = $_FILES['file'];
        $this->productId = array();
        $this->productName = array();
        /* $setting = csSettingTable::getInstance()->findOneBySlug("bestsellersProducts");
          $setting->setValue(implode(",", array_keys(array_slice($bestsellersArray, 0, 20, true))));
          $setting->save(); */
        if (is_array($this->file)) {
            if (is_uploaded_file($this->file['tmp_name'])) {
                $handle = fopen($this->file['tmp_name'], "r");
                while (!feof($handle)) {
                    $buffer = fgets($handle, 4096);
                    $product = ProductTable::getInstance()->findOneByCode(trim($buffer));

                    //  echo $buffer.$product2."<br/>";
                    if ($product) {
                        $this->productId[] = $product->getId();
                        $this->productName[] = $product->getName();
                    }
                    $product = null;
                    //echo $buffer;
                }
                fclose($handle);
                $setting = csSettingTable::getInstance()->findOneBySlug("notRecomendationProducts");
                $setting->setValue(implode(",", $this->productId));
                $setting->save();

                if (sfContext::getInstance()->getUser()->hasPermission("Enter backend")) {
                    exec("../symfony cc", $test);
                    //print_r($test);exit;
                }
            }
        }
        $this->setTemplate('setOptimizationIdProducts');
    }

    public function executeBestSalesProducts(sfWebRequest $request) {
        if (@$_POST['fromDate'] != "") {

            $this->getUser()->setAttribute('managerstatsFrom', $_POST['fromDate']);
            $from = explode('.', $_POST['fromDate']);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }
        if (@$_POST['to'] != "") {
            $this->getUser()->setAttribute('managerstatsTo', $_POST['to']);
            $from = explode('.', $_POST['to']);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }
        //$this->getUser()->setAttribute('searchLogTo', '');
        if ($this->getUser()->getAttribute('managerstatsFrom') != "") {

            $managerstats = $this->getUser()->getAttribute('managerstatsFrom');
            $_POST['fromDate'] = $managerstats;
            $from = explode('.', $managerstats);
            unset($managerstats);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }

        if ($this->getUser()->getAttribute('managerstatsTo') != "") {
            $searchLog2 = $this->getUser()->getAttribute('managerstatsTo');
            $_POST['to'] = $searchLog2;
            $from = explode('.', $searchLog2);
            //unset($searchLog);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        if (count($managerstats) > 0) {

            $orders = $q->execute("SELECT * from orders WHERE  created_at > '" . $managerstats['created_at']['from']['year'] . "-" . $managerstats['created_at']['from']['month'] . "-" . $managerstats['created_at']['from']['day'] . "' and created_at < '" . $managerstats['created_at']['to']['year'] . "-" . $managerstats['created_at']['to']['month'] . "-" . $managerstats['created_at']['to']['day'] . "'")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            if (sizeof($orders) > 0) {
                foreach ($orders as $order) {
                    $productsToCart = unserialize($order['text']);
                    foreach ($productsToCart as $product) {
                        if ($product['price_w_discount'] > @$_POST['priceFrom'] and $product['price_w_discount'] < @$_POST['priceTo']) {

                            //print_r($product);
                            if ($product['productId'] > 0) {
                                $product['product_id'] = $product['productId'];
                            } elseif ($product['product_id'] == "" or $product['product_id'] == "0") {
                                $product['product_id'] = $q->execute("SELECT id from product WHERE  code = '" . str_replace("'", "\'", $product['article']) . "'")
                                        ->fetch(Doctrine_Core::FETCH_COLUMN);
                            }
                            @$arrayProductBestSales[$product['product_id']]['salesCount'] = $arrayProductBestSales[$product['product_id']]['salesCount'] + $product['quantity'];
                            @$arrayProductBestSales[$product['product_id']]['info'] = $product;

                            @$arrayProductBestSales[$product['product_id']]['infoBD'] = $q->execute("SELECT * from product WHERE  id = '" . $product['product_id'] . "'")
                                    ->fetch(Doctrine_Core::FETCH_ASSOC);
                        }
                    }
                }
                if (@sizeof($arrayProductBestSales) > 0) {
                    foreach ($arrayProductBestSales as $c => $key) {
                        $sort_numcie[$c] = $key['salesCount'];
                    }
                    $sort_numcie2 = $sort_numcie;
                    array_multisort($sort_numcie, SORT_DESC, $arrayProductBestSales);
                    asort($sort_numcie2);

                    foreach ($sort_numcie2 as $c => $null) {
                        $arrayProductBestSalesKeys[] = $c;
                    }
                    $arrayProductBestSales = array_combine($arrayProductBestSalesKeys, $arrayProductBestSales);
                }
            }
            $this->arrayProductBestSales = $arrayProductBestSales;
            if(isset($_POST['in_file']) && $_POST['in_file'] && isset($_POST['to']) && isset($_POST['fromDate'])){//Выгрузка в файл
              $filename='onona_products_sales_report_';
              $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
              $cacheSettings = array('memoryCacheSize ' => '512MB');
              // die('<pre>'.print_r($arrayProductBestSales, true));
              PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
              $objPHPExcel = new PHPExcel();
              $objPHPExcel->getProperties()->setCreator("OnOna");
              $objPHPExcel->getProperties()->setLastModifiedBy("OnOna");
              $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
              $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
              $objPHPExcel->getProperties()->setDescription("OnOna document for Office 2007 XLSX.");
              $objPHPExcel->setActiveSheetIndex(0);
              $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Артикул');
              $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Количество');
              $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'C');
              $objPHPExcel->getActiveSheet()->SetCellValue('D1', $_POST['fromDate']);
              $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'По');
              $objPHPExcel->getActiveSheet()->SetCellValue('F1', $_POST['to']);
              ini_set("max_execution_time", 600);
              $i=2;
              foreach ($arrayProductBestSales as $num => $line) {
                if($line['infoBD']['code']=='д1') continue;
                  @$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $line['infoBD']['code']);
                  // @$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $line['info']['product_id']);
                  @$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i++, $line['salesCount']);
              }
              $objPHPExcel->getActiveSheet()->setTitle("Отчет по продажам товаров");
              header("Content-Type:application/vnd.ms-excel");
              header("Content-Disposition:attachment;filename=\"".$filename.$_POST['fromDate'].'-'.$_POST['to'].".xlsx\"");
              $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
              // die('<pre>'.print_r($data, true));
              $objWriter->save('php://output');

              exit;
            }
            //@print_r($arrayProductBestSales);
        }
        //print_r($this->productsUsersend);
    }

    public function executeBestCommentsProducts(sfWebRequest $request) {
        if (@$_POST['fromDate'] != "") {

            $this->getUser()->setAttribute('managerstatsFrom', $_POST['fromDate']);
            $from = explode('.', $_POST['fromDate']);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }
        if (@$_POST['to'] != "") {
            $this->getUser()->setAttribute('managerstatsTo', $_POST['to']);
            $from = explode('.', $_POST['to']);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }
        //$this->getUser()->setAttribute('searchLogTo', '');
        if ($this->getUser()->getAttribute('managerstatsFrom') != "") {

            $managerstats = $this->getUser()->getAttribute('managerstatsFrom');
            $_POST['fromDate'] = $managerstats;
            $from = explode('.', $managerstats);
            unset($managerstats);
            $managerstats['created_at']['from']['year'] = $from[2];
            $managerstats['created_at']['from']['month'] = $from[1];
            $managerstats['created_at']['from']['day'] = $from[0];
        }

        if ($this->getUser()->getAttribute('managerstatsTo') != "") {
            $searchLog2 = $this->getUser()->getAttribute('managerstatsTo');
            $_POST['to'] = $searchLog2;
            $from = explode('.', $searchLog2);
            //unset($searchLog);
            $managerstats['created_at']['to']['year'] = $from[2];
            $managerstats['created_at']['to']['month'] = $from[1];
            $managerstats['created_at']['to']['day'] = $from[0];
        }
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        if (count($managerstats) > 0) {
            $_POST['priceFrom'] = $_POST['priceFrom'] > 0 ? $_POST['priceFrom'] : 0;
            $_POST['priceTo'] = $_POST['priceTo'] > 0 ? $_POST['priceTo'] : 999999;
            $comments = $q->execute("select comm.product_id, comm . * from (SELECT *, count(product_id) as countComm FROM comments WHERE  created_at > '" . $managerstats['created_at']['from']['year'] . "-" . $managerstats['created_at']['from']['month'] . "-" . $managerstats['created_at']['from']['day'] . "' and created_at < '" . $managerstats['created_at']['to']['year'] . "-" . $managerstats['created_at']['to']['month'] . "-" . $managerstats['created_at']['to']['day'] . "' and product_id>0 group by product_id) as comm order by comm.countComm DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $productInfoBD = $q->execute("SELECT * from product WHERE  id in (" . (implode(",", array_keys($comments))) . ") and price<" . $_POST['priceTo'] . " and price>" . $_POST['priceFrom'] . "")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            foreach ($comments as $product['id'] => $product) {
                if (@is_array($productInfoBD[$product['id']])) {
                    $commentsTemp[$product['id']] = $comments[$product['id']];
                }
            }
            $this->comments = $commentsTemp;
            //@print_r($this->comments);
        }
        //print_r($this->productsUsersend);
    }

}
