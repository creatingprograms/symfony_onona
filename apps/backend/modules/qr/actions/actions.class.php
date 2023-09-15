<?php

require_once dirname(__FILE__) . '/../lib/qrGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/qrGeneratorHelper.class.php';

/**
 * qr actions.
 *
 * @package    test
 * @subpackage qr
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class qrActions extends autoQrActions {
  public function __construct($context, $moduleName, $controllerName) {
    parent::__construct($context, $moduleName, $controllerName);

    $shops = ShopsTable::getInstance()->createQuery()->where("`is_active`= 1 AND `city_id` IN (?)", implode(', ', ILTools::getMoscowCodes()))->orderBy("NAME ASC")->execute();
    $GLOBALS['shops'] = $shops;

  }

  public function executeDownload(sfWebRequest $request) {
    ini_set("max_execution_time", 240);
    $cacheSettings = array('memoryCacheSize ' => '512MB');

    ob_start();
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();

    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("OnOna");
    $objPHPExcel->getProperties()->setLastModifiedBy("OnOna");
    $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
    $objPHPExcel->getProperties()->setDescription("OnOna document for Office 2007 XLSX.");
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Дата');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Магазин');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Площадка');

    $where = '';
    $params = $request->getParameter('qr_redirects_filters', false);
    if(!empty($params['created_at']['from'])){
      $date = mktime(0, 0, 0, $params['created_at']['from']['month'], $params['created_at']['from']['day'], $params['created_at']['from']['year']);
      if($date > 10000)
        $whereLines[] = '`created_at` >= "'.date('Y-m-d H:i:s', $date).'"';
    }
    if(!empty($params['created_at']['to'])){
      $date = mktime(23, 59, 59, $params['created_at']['to']['month'], $params['created_at']['to']['day'], $params['created_at']['to']['year']);
      if($date > 10000)
        $whereLines[] = '`created_at` <= "'.date('Y-m-d H:i:s', $date).'"';
    }
    if(!empty($params['type'])){
      $whereLines[] = '`type` = "'.$params['type'].'"';
    }
    if(!empty($params['shop_id'])){
      $whereLines[] = '`shop_id` = "'.$params['shop_id'].'"';
    }

    if(!empty($whereLines)) $where .= "WHERE ".implode(" AND ", $whereLines);

    $sqlBody = "SELECT `created_at`, `shop`, `type` FROM `qr_redirects` ".$where;
    $redirects = $q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);

    // die('<pre>!'.print_r([$where, $redirects], true));
    foreach ($redirects as $num => $redirect) {
        @$objPHPExcel->getActiveSheet()->SetCellValue('A' . ($num + 2), $redirect['created_at']);
        @$objPHPExcel->getActiveSheet()->SetCellValue('B' . ($num + 2), $redirect['shop']);
        @$objPHPExcel->getActiveSheet()->SetCellValue('C' . ($num + 2), $redirect['type']);
    }


    $objPHPExcel->getActiveSheet()->setTitle("Переходы по QR");

    ob_end_clean();
    header("Content-Type:application/vnd.ms-excel");
    header("Content-Disposition:attachment;filename=\"Переходы по QR.xlsx\"");
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save('php://output');

    exit;
  }
}
