<?php

class ILTools {

  const FAVORITES_FIELD = 'personal_recomendation';

  public static function generateVideoThumb($videoUrl){ //Сгеренировать миниатюру для видео
    $videoUrlArr = explode('.', $videoUrl);

    if ($videoUrlArr[1] == 'webm') $videoUrlArr[1] = 'mp4';
    else $videoUrlArr[1] = 'webm';

    if ($videoUrl) $videoUrl = '/uploads/video/' . $videoUrl;
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $videoUrl)) $videoUrl = false;

    if (!$videoUrl && file_exists($_SERVER['DOCUMENT_ROOT'] . '/uploads/video/' . implode('.', $videoUrlArr))) $videoUrl = '/uploads/video/' . implode('.', $videoUrlArr);

    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $videoUrl)) return false;
    
    $thumbName = $videoUrlArr[0] . '.jpg';
    $thumbUrl = '/uploads/videothumbs/' . $thumbName;

    if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $thumbUrl)) {
      $folder = $_SERVER['DOCUMENT_ROOT'] . '/uploads/video/';
      $cmd = "\n" .
        "cd $folder \n" .
        "ffmpeg -i " . implode('.', $videoUrlArr) . " -ss 00:00:00 -vframes 1 -f mjpeg -s 76x43 ../videothumbs/$thumbName \n";  

      $text = shell_exec($cmd);

      // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$thumbName, $videoUrl, $cmd, $thumbUrl], true) . "</pre>\n");

      if(!file_exists($_SERVER['DOCUMENT_ROOT']. $thumbUrl)) $thumbUrl = false;
    }
    // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$thumbName, $videoUrl, $thumbUrl], true) . "</pre>\n");

    return $thumbUrl;
  }

  public static function saveXLS($data, $name = 'report')
  {
    if (is_array($data) && !empty($data)) {
      $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
      $cacheSettings = array('memoryCacheSize ' => '1024MB');
      PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
      $objPHPExcel = new PHPExcel();
      $objPHPExcel->getProperties()->setCreator("OnOna");
      $objPHPExcel->getProperties()->setLastModifiedBy("OnOna");
      $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
      $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
      $objPHPExcel->getProperties()->setDescription("OnOna document for Office 2007 XLSX.");
      $objPHPExcel->setActiveSheetIndex(0);
      ini_set("max_execution_time", 240);

      foreach ($data as $key => $line) {
        foreach ($line as $colKey => $col) {
          $objPHPExcel->getActiveSheet()->SetCellValue(chr(65 + $colKey) . ($key + 1), ''.$col);
        }
      }

      $objPHPExcel->getActiveSheet()->setTitle("report");

      header("Content-Type:application/vnd.ms-excel");
      header("Content-Disposition:attachment;filename=\"$name.xlsx\"");
      $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
      $objWriter->save('php://output');
    }
  }

  public static function getMoscowCodes(){//Разрешено ли использовать доставку такси
    return [3, 5, 126, 144, 59];
  }

  static function getFavCount($user){//количество товаров в избранном
    $items=$user->get(ILTools::FAVORITES_FIELD);
    if($items) $items=unserialize($items);
    else return false;
    return count($items);
  }

  public static function getRegion(){//Разрешено ли использовать доставку такси

    if (@sfContext::getInstance()->getUser()->getAttribute('regMO') !== true and @ sfContext::getInstance()->getUser()->getAttribute('regMO') !== false) {
      $ip = $_SERVER['REMOTE_ADDR'];
      if(!empty($ip)){
        $SxGeo = new SxGeo('../apps/new/lib/SxGeoCity.dat');
        $geoData = $SxGeo->getCityFull($ip); //Москва Московская область
        if ($geoData['region']['name_ru'] == "Москва" or $geoData['region']['name_ru'] == "Московская область") {
            sfContext::getInstance()->getUser()->setAttribute('regMO', true);
        } else {
            sfContext::getInstance()->getUser()->setAttribute('regMO', false);
        }
      }
    }
  }

  public static function isTaxiEnabed($products, $sf_user=false){//Разрешено ли использовать доставку такси
    global $isTest;
    if($isTest) return true;
    if(!sizeof($products)) return false;
    $currentHour=date('H');
    if($currentHour<9 || $currentHour>20) return false;
    /*
    $user=sfContext::getInstance()->getUser();
    // var_dump($user);
    $allowedUsers=[
      160731, 159759, 160620, 160711, 161074, 177106, 276413, 304472
    ];
    // die('-------------------------'.$user->getGuardUser()->getId());
    if(is_object($user) && !$user->isAuthenticated()) return false;
    else if(!empty($allowedUsers) && !in_array($user->getGuardUser()->getId(), $allowedUsers)) return false;
    */
    foreach ($products as $product) {
      if(!$product->getIsExpress() || $product->getCount()<1) return false;
    }
    return true;
  }

  public static function getRedsmsObject(){//Возвращает инициализированный объект апи Redsms
    global $isTest;
    $obj= new Redsms(
      csSettings::get('red_sms_login'),
      csSettings::get('red_sms_api_key')
      // , $isTest
    );
    return $obj;
  }

  public static function getOzonDeliveryObject(){//Возвращает инициализированный объект апи ЯндексТакси
    global $isTest;
    $obj= new OzonDelivery(
      csSettings::get('ozon_client_id'),
      csSettings::get('ozon_key')
      // , $isTest
    );
    return $obj;
  }
  public static function getYandexTaxiObject(){//Возвращает инициализированный объект апи ЯндексТакси
    global $isTest;
    if ($isTest)
      $params=[
        'full_address' => 'Москва, проезд Серябрякова 14б строение 7',
        'phone' => '+74953749878',
        'email' => 'info@onona.ru',
        'name' => 'Алексей',
      ];
      else
        $params=[
          'full_address' => 'Москва, проезд Серябрякова 14б строение 7',
          'phone' => '+74953749878',
          'email' => 'info@onona.ru',
          'name' => 'Оператор'
        ];
    $obj= new YandexTaxi(
      csSettings::get('yandex_taxi_token'),
      csSettings::get('yandex_taxi_coords'),
      $params
    );
    return $obj;
  }

  public static function array_in($arrayFromFind, $arrayWhere){//Ищет вхождение одного из элементов массива в другом
    if(!sizeof($arrayWhere)) return false;
    if(sizeof($arrayFromFind)) foreach ($arrayFromFind as $value) {
      if(in_array($value, $arrayWhere)) return true;
    }
    return false;
  }

  public static function getOrderInfo($order){//Собирает информацию о заказе. Нужна потому что инфо о товарах сериализовано
    if(!is_object($order)) return false;
    $products_old = $order->getText() != '' ? unserialize($order->getText()) : '';
    $TotalSumm = $quantity = 0;
    $newVersion=false;
    foreach ($products_old as $key => $productInfo){
      $lineTotal = ($productInfo['quantity'] * (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']));
      if(!empty($productInfo['price_final'])) {
        $lineTotal = $productInfo['price_final'] * $productInfo['quantity'];
        $newVersion=true;
      }
      $TotalSumm = $TotalSumm + $lineTotal;
      if($productInfo["productId"]!=14613) $quantity = $quantity + $productInfo['quantity'];
    }
    $executed = true;
    if($order->getStatus() == 'Выполнен' || $order->getStatus() == 'Отмена') $executed = false;
    $executedIcon='executed-i';
    if($order->getStatus() == 'Выполнен'){
      $executedIcon='done-i';
    }
    if($order->getStatus() == 'Отмена'){
      $executedIcon='done-i';
      // $executedIcon='cancel-i';
    }
    $info=[
      'price' => $TotalSumm,
      'quant' => $quantity,
      'status-executed' => $executed,
      'icon' => $executedIcon,
      'new_version' => $newVersion,
    ];
    return $info;
  }

  public static function GetShortText($text, $length){//Получает текст до ближайшей точки длиной не более $length
    $str=mb_substr($text, 0, $length);
    $point=mb_strrichr ($str, '.', true);
    if(false===$point)
      $point=mb_strrichr ($str, '?', true);
    else
      return $point.'.';

    if(false===$point)
      $point=mb_strrichr ($str, '!', true);
    else
      return $point.'?';

    if(false===$point)
      $point=mb_strrichr ($str, ' ', true);
    else
      return $point.'!';

    return $point;//$str.'|'.$point.'|'. $length.'|'.mb_substr($str, 0, $point);
  }

  public static function is_email($email) {
    //return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    } else {
      return false;
    }
  }

  public static function getWordForm($value, $word='недел', $supressNumber=false){//получает слова в зависимости от количества
    $last=$end='';
    switch ($word) {
      case 'год':
        if($value%100!=10 && ($value%10==2 || $value%10==3 || $value%10==4)) $last='а';
        if($value%10==5 || $value%10==6 || $value%10==7 || $value%10==8 || $value%10==9 || $value%10==0) $word='лет';
        if($value%100 > 9 && $value%100 < 20)  $word='лет';
        break;

      case 'человек':
        if($value%100!=10 && ($value%10==1)) $last='';
        if($value%100!=10 && ($value%10==2 || $value%10==3 || $value%10==4)) $last='а';
        if($value%10==5 || $value%10==6 || $value%10==7 || $value%10==8 || $value%10==9 || $value%10==0) $last='';
        if($value%100 > 10 && $value%100 < 20)  $last='';
        break;

      case 'недел':
        if($value%100!=10 && ($value%10==1)) $last='я';
        if($value%100!=10 && ($value%10==2 || $value%10==3 || $value%10==4)) $last='и';
        if($value%10==5 || $value%10==6 || $value%10==7 || $value%10==8 || $value%10==9 || $value%10==0) $last='ь';
        if($value%100 > 10 && $value%100 < 20)  $last='ь';
        break;

      case 'месяц':
        if($value%100!=10 && ($value%10==2 || $value%10==3 || $value%10==4)) $last='а';
        if($value%10==5 || $value%10==6 || $value%10==7 || $value%10==8 || $value%10==9 || $value%10==0) $last='ев';
        if($value%100 > 10 && $value%100 < 20)  $last='ев';
        break;

      case 'отзыв':
      case 'бонус':
      case 'товар':
        if($value%10==2 || $value%10==3 || $value%10==4) $last='а';
        if($value%10==5 || $value%10==6 || $value%10==7 || $value%10==8 || $value%10==9 || $value%10==0) $last='ов';
        if($value%100 > 10 && $value%100 < 20) $last='ов';
        break;

      case 'день':
        if($value%100!=10 && ($value%10==2 || $value%10==3 || $value%10==4)) $word='дня';
        if($value%10==5 || $value%10==6 || $value%10==7 || $value%10==8 || $value%10==9 || $value%10==0) $word='дней';
        if($value%100 > 10 && $value%100 < 20)  $word='дней';
        break;

      case 'магазин':
        if($value%100!=11 && $value%10==1) $last='е';
        else $last='ax';
        break;

      case 'категория':
        if($value%100!=10 && ($value%10==2 || $value%10==3 || $value%10==4)) $word='категории';
        if($value%10==5 || $value%10==6 || $value%10==7 || $value%10==8 || $value%10==9 || $value%10==0) $word='категорий';
        if($value%100 > 10 && $value%100 < 20)  $word='категорий';
        break;

      default:
        // code...
        break;
    }
    return ($supressNumber ? '' : $value.' ').$word.$last;
  }

  public static function cleanPhone($userPhone){//Очищает телефон от посторонних знаков
    $sPhone = preg_replace("/[^0-9]/m", '', $userPhone);
    if ($sPhone[0] == 8) $sPhone[0] = 7;

    return $sPhone;
  }

  public static function formatPrice($price){//Показывает цену с разделением тысяч
    return number_format($price, 0, '.', ' ');
  }

  public static function isNew($product){//Входит ли товар в экспресс доставку
    if(!is_object($product)) return false;
    $ids=explode(',', csSettings::get('optimization_newProductId'));
    $isNew=in_array($product->getId(), $ids);
    $timeToNew=csSettings::get('logo_new')*24*60*60;//В секундах
    $timeCreate=strtotime($product->getCreatedAt());
    if($timeCreate > (time() - $timeToNew)) $isNew=true;
    // if(isset($_GET['ildebug'])) die('<pre>'.print_r(['id'=>$product->getId(), '$ids'=>$ids, '$timeToNew'=>$timeToNew, '$timeCreate'=>$timeCreate, 'time-$timeToNew'=> time()-$timeToNew, 'time'=> time()], true));
    return $isNew;
  }

  public static function isExpress($id){//Входит ли товар в экспресс доставку
    $category = Doctrine_Core::getTable('Category')->findOneBySlug('express');
    if(!is_object($category)) return false;
    $sqlBody="SELECT `product_id` FROM `category_product` where `category_id`=".$category->getId();
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $tableIds=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    $ids=array_keys($tableIds);

    return in_array($id, $ids);
  }

  public static function isOfDay($id){//Является ли товар товаром дня
    $productsPrior=explode(',', csSettings::get('id_product_action_1'));
    return in_array($id, $productsPrior);
  }

  public static function GetListByLetter($list){//формирует массив объектов с разбиением по буквам
    foreach ($list as $object) {
      $firstLetter=mb_strtoupper(mb_substr($object->getName(), 0, 1));
      $newList[$firstLetter][]=$object;
    }
    return $newList;
  }

  public static function getImageSrc($manufacturer, $folder='manufacturer'){//Выдергивает изображения из контента
    $image=false;
    if($manufacturer->getImage()) $image='/uploads/'.$folder.'/'.$manufacturer->getImage();
    if(!$image){
      $reg='/<img.+src="(.+\.png)".*>/mU';//Выдергивает из контента png
      $res=preg_match($reg, $manufacturer->getContent(), $matches);
      if($res) $image=$matches[1];
    }

    if(!$image || !file_exists($_SERVER['DOCUMENT_ROOT'].$image)) $image='/frontend/images/no-image.png';
    return $image;
  }

  public static function csvToPhp(){
    $csv=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../blured.csv');
    $delimiter='|';
    $lines=explode("\n", $csv);
    foreach ($lines as $key => $value) {
      $elements=explode($delimiter, $value);
      foreach ($elements as $key => $element) {
        if(!trim($element)) continue;
        $final[]=trim($element);
      }
    }
    die("'".implode("', '", $final)."'");
  }

  public static function logToFile($data, $name='main_log.log', $supressDelimiter=false){
    $lineDelimiter="\n\n------------------------------------------";
    if($supressDelimiter) $lineDelimiter='';
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/../'.$name, $lineDelimiter.print_r($data, true), FILE_APPEND);
    // echo $_SERVER['DOCUMENT_ROOT'].'/../'.$name."\n";
  }

  public static function isBlured($product){
    return false;
    $blured=[
      '5119270000', '117010', 'PDRD335', 'Bai-BM-015012', 'PDRD364', 'SE-0450-01-3', 'DJ5543-01BX', 'Bai-BM-009105', '66660', 'Bai-BW-022020', 'PD4129-21', 'PD5709-21', 'DJ5075-02BX',
      '29982', 'KK-M03-001-01', 'SLI154', '117009', '117018', 'Bai-BM-015018', 'TS1002018', 'KK-M01-002-01V', 'SE-7520-10-3', 'KK-M03-002-01V', 'PDRD176', 'PD5710-21', 'LT572713',
      'ET424SKN', 'DJ5410-02BX', '26002', '14469', '5113150000', '117013', 'KK-M01-003-06', 'Bai-BM-015019', 'GP-20K36-BX', 'Bai-BM-009180', 'KK-M01-002-02V', 'TS1070098', '1102',
      'TS1101042', '21375', '17336', '19610', '18268', '23083', '5016700000', '117016', '117021', 'PDRD365', 'SE-7531-20-3', 'Bai-BM-009141', 'KK-M01-002-02V', 'TS1070011', 'P7241',
      '861702ru', '18913', '19945', '23084', '25643', '24462', '5162010000', '117017', '171860000', 'PDRD361', 'TS1002021', 'Bai-BM-009175', 'PDRD201', 'TS1070099', '3141-3 BX SIT',
      '964009', '19392', '479', '19611', '15119', '24800', '5139540000', '5119270000', '117020', 'PDRD362', 'TS1002016', 'GP-2K469-BX', '106634', 'TS1074887', '703003', 'PD3375-21',
      '19410', '17332', '2385', '23173', '24795', 'TS1002019', '5119190000', 'KK-M01-002-06', 'PDRD363', 'TS1002014', 'KK-M04-002-03D', 'KK-M10-00-07-3', 'TS1070012', '24907301001',
      'LT532303', '15575', '23992', '26787', '18270', '26793', 'PDRD331', 'TS1002020', 'TS1002015', '117014', 'PDRD334', 'TS1101018', 'PDRD202', 'TS1074957', '3141-1 BX SIT', 'PD1519-21',
      '17149', '19945', '19603', '19982', '17843', 'TS1002497', 'PDRD332', '117019', 'PDRD300-1', 'SE-1915-01-3', 'Bai-BM-009136', 'TS1070010', 'TS1070094', '703001', 'TS1101041', '22525',
      '26673', '19613', '20006', '15797', 'PDRD301', 'PDRD303', 'PDRD304', 'PDRD305', 'PDRD300', 'Bai-BM-009115', 'PDRD203', 'TS1070093', 'BLM3060', 'LT573113', '25334', '17810', '19611',
      '21751', '90', 'KK-M01-003-08', 'DJ5543-14BX', 'PDRD302', 'SE-7532-10-3', 'KK-M01-003-05', '893011', 'PDRD208', 'PDRD174-23', 'PD3668-23', '840402ru', '19476', '17811', '23164',
      '23078', '23165', 'TS1002012', 'KK-M01-003-08V', 'Bai-BM-00900T28', 'KK-M01-003-04V', 'KK-M10-03-25V', '893016', 'KK-M10-03-21V', 'TS1070097', '3142-1 BX SIT', 'Bai-BW-022047',
      '20851', '21504', '19987', '23427', '21751', '5033470000', 'TS1002013', 'RD341', 'Bai-BM-009096', 'Bai-BM-009106', 'SE-0889-01-3', 'KK-M03-003-01V', 'SE-7525-10-3', '703002',
      'DJ1015-36BX', '18772', '26674', '23092', '24794', '21746', 'KK-M01-003-07-1', '893010', '2002', 'KK-M10-03-21-1', 'Bai-BM-009102', '893013', 'TS1590457', 'PDRD175', '3143-1 BX SIT',
      'PD5704-21', '19427', '17334', '19984', '25010', '22766', 'KK-M10-03-20-2', 'KK-M10-03-20', 'KK-M04-002-01D', 'KK-M04-002-02D', 'Bai-BM-009042', 'GP-2K642-BX', 'SE-7521-05-3',
      'TS1070096', '3142-2 BX SIT', 'PD5804-12', '22527', '17332', '23116', '16840', '24065', 'Bai-BM-009095', 'KK-M10-03-20-1', '893012', '893014', 'RB001', 'DJ5539-01BX',
      'KK-M10-03-29-1V', 'TS1070095', '3140-2 BX SIT', 'DJ1050-01BX', '19427', '19945', '23115', '17237', '211', '893018', 'BLM018-BLKSM', 'KK-M01-003-07D', '2100-04Lola',
      'SE-0912-10-3', '893015', 'KK-M01-003-02V', 'Bai-BM-00900T25', '3143-2 BX SIT', 'DJ1015-06BX', '22530', '17335', '24793', '18271', '24133', '21363', '847', '17153', '15986',
      '19979', '19614', '24061', 'se-0612-04-3', 'PD3230-23', '5208450000', '5288110000', 'BAI-BI-0325012-0101', '26001', '26014', '26004', '23611', '18914', '19477', '218', '16635',
      '23087', '24062', 'PD3237-00', 'PD3224-00', 'PD3236-12', 'PD3694-00', 'PD3231-00', '10648', '20024', '26007', '18202', '22923', '18773', '17337', '23163', '23428', '24133',
      'PD3692-20', 'PD3232-23', 'GP-2K130-BX', '5213370000', 'PD3238-23', '17676', '19985', '24913', '1104', '15984', '19402', '17333', '23086', '18421', '25012', '700006', '700008',
      '700016', '700011', '700004', '19478', '24189', '26011', '22013', '608', '19428', '24381', '19947', '19479', '25013', '3015-2 BX SIT', '6014-2 BX SIT', '3015-1 BX SIT',
      '3012-2-BX SIT', '700005', '24463', '20012', '20013', '21363', '18908', '22530', '15052', '23113', '19659', '20003', '6014-1 BX SIT', '6023-1 BX SIT', '6013-1 BX SIT', '6023-2 BX SIT',
      '700007', '20015', '25165', '454', '18201', '19425', '17154', '22547', '464', '23090', '20005', '700013', '700003', '700010', '700009', '24902694001', '14758', '6456', '458',
      '20859', '323', '18063', '26829', '465', '23167', '20006', '700014', '700015', '2504220000', 'PD2173-10', 'PD2128-23', '24912', '25644', '25645', '15572', '865', '18915', '19606',
      '462', '24060', '20007', 'PD5382-11', '15103', 'PD5684-21', 'PD5382-12', 'PD5381-11', '26613', '25140', '25999', '18768', '3374', '19396', '19625', '26006', '24799', '20010',
      'PD5681-23', 'DY1015-18BX', 'OU273SKN', 'Bai-Bw-022034', 'PD1514-21', '23824', '24716', '12713', '18905', '14869', '19941', '23089', '460', '25014', '463', '99088ACHBX GR',
      'DY1015-17BX', 'PD5621-21', 'Bai-Bw-022048', '984104', '18093', '24718', '24758', '18928', '376', '20854', '27471', '451', '25372', '25015', 'DY1050-03 BX', 'PD5622-21', 'SE1514-20-3',
      'PD3351-20', '6501 BX SIT', '25188', '14864', '14863', '22761', '15983', '18263', '19608', '10636', '459', '15234', '25138', '748013', 'PD3374-21', 'PD3367-21', 'PD3366-21', '18167',
      '26450', '14865', '25301', '367', '22531', '19988', '23166', '17233', '21758', '19623', '26012', '25139', '26010', '26000', '6644', '18168', '15653', '19409', 'PD1522-21', '25133',
      '23774', '16613', '18785', '16073', '19408', '855', '856', '16575', '18262', '18907', '852', '862', '19389', '18066', '22764', '23082', '26009', '19481', '16836', '17236',
      '19479', '224', '24063', '19482', '12025', '24799', '24800', '17676', '16949', '23079', '25096', '25011', '16012', '456', '457', '16146', '18421', '19982', '18303', '17235',
      '23071', '24059', '12024', '18172', '23267', '16839', '19004', '16297', '23580', '25645', '19609', '23091', '25017', '25019', '10649', '15539', '16168', '459', '10645', '19995',
      '25644', '19602', '23079', '10643', '25166', '18785', '10647', '10648', '365', '18271', '24797', '23428', '19616', '23078', '15538', '15948', '18269', '15540', '458', '453',
      '454', '24798', '23426', '26180', '26003', '12061', '16840', '15541', '19605', '466', '23269', '17662', '16007', '26183',

      '5037970000', 'GP-7199PMB-BX', 'SE-0863-10-2', 'PD3882-24', 'Bai-BW-008017AR', 'GP-2K747 ACHBCD', 'SE-0080-10-3', '5067020000', 'DJ0279-25CD', '981038', 'SE-2712-60-3',
      'Bai-BI-014084', '981038', '5913510000', '5781690000', 'DJ0228-02CD', 'Bai-BW-008074Z', 'SE-1498-50-3', 'TS1071016', 'PD1402-01', 'SE-0612-04-3', '5781850000', 'DJ0279-08CD',
      'Bai-BW-008040Z', 'LT33003', 'GP-B0081Y4SPGAC', 'PD5405-21', '6910-01Lola', 'GP-2K79LV BCD', 'DJ0279-17CD', 'Bai-BW-008077Z', '986009', '761003', 'RF-WBC10029V', 'PD4449-23',
      'GP-2K237 BX', 'DJ0279-28CD', 'Bai-BW-022034', '966009', '981102', 'LT45523', '5174960000', 'SE-1233-14-2', 'DJ0279-26CD', '984002', 'PD3662-21', '981037', '981104', '5725270000',
      'SE-1233-04-2', 'DJ0279-27CD', 'DJ1015-18BX', 'LT81503', 'GP-B0082Y4SPGAC', 'Bai-BI-040012-1002S', '5725190000', '5847380000', 'DJ0279-16CD', 'Bai-BW-022028', 'DJ1070-18BX', '863003',
       'DJ5009-03CD', '5787540000', '5834640000', 'GP-90302200TBL1 CS', 'DJ1015-17BX', 'DJ8053-01BX', 'GP-2K747 ACHBCD', '5227160000', 'GP-2K335-BX', '5131480000', 'SE-0131-80-2',
       'Bai-BW-022048', 'DJ1050-07BX', 'RF-T10005L', 'GP-7334 BX', 'GP-2K539-BX', 'GP-2K322S-BCD', 'Bai-BW-008017AR', 'PD3377-21', 'SE-2711-91-3', '863004', 'GP-J7214IV-BX', '5038000000',
       '761040', 'PD2162-23', '761005', 'RF-WBC10029V', '5125320000', 'GP-7199PM-BX', 'SE-0866-02-2', '5214340000', '581001', 'LT45523', 'TS1486011', 'D3882-24', 'RW72102', 'OU347BLK',
       'RM10008', 'SE-1461-00-3', 'RWD10046', 'OU044BLK', 'PD4118-21', 'SE-0989-00-2', 'SE-0912-15-3', 'OU332BLK', 'Bai-BW-015015', 'SE-1461-03-3', 'RWV1020', 'SE-2712-67-3', '5163410000',
       'PD8103-00', '14544', 'OU341BLK', '5073420000', 'PD3649-00', '5822800000', 'SE-2712-14-3', '14445', 'PD2194-00', '14407', 'OU139BLK', '5038000000', 'PD3731-23', '5848780000',
       'OU450BLK', '14728', 'Bai-BI-017002-0101S', 'BLM3053', '701006', 'Bai-BM-015025', 'GP-7199PM-BX', '14612', 'OU001PUR', '5141280000', 'GP-7199PMB-BX', 'BLM3057', '701005',
       'PD4147-21', 'GP-2K299-BCD', 'DJ5410-05BX', '702005', '964042', 'GP-2K322L-BCD', 'Bai-BI-026201', '704001', 'SB-006ES', 'DJ0244-20CD', '14476', '704010', 'PD4114-29',
       'DJ5583-01CD', 'BLM3049', 'OU001PNK', '2101-03Lola', 'GP-90302200TBL1 CS', 'KK-M04-001-001', '704007', 'PD3261-11', 'PrL-BI-014157-0602S', 'BLM3043', '702005', 'KL-7012-01',
       'Bai-BW-022034', '14742', '704012', 'SB-005ES', 'PD4429-23', '14421', 'PD3716-00', 'PD4149-00', '984002', 'Bai-BI-026201A-0101', '24903071001', '2101-06Lola', '99088 ACHBX GP',
       'DJ0683-10BX', 'PD3741-23', 'Bai-BM-009150', 'Bai-BI-014084', '2101-10Lola', '704005', '2102-03Lola', 'DJ1050-03BX', '2101-15Lola', '702003', '5135980000', 'PD2162-23', '14964',
       '5254990000', '5219650000', 'DJ1050-02BX', '14889', '24903903001', 'PD3543-00', '5214340000', '14971', '704003', '11543', 'DJ1050-15BX', 'SE-7530-30-3', 'PD3661-23', 'DJ1713-00BX',
       'Bai-BW-022037-1002', 'SE-7530-60-3', 'PD3987-27', '2102-04Lola', '966001', 'SE-0883-20-3', 'PD3603-26', 'NSN-1000-21', 'PD3956-23', 'SE-1461-00-3', 'PD3842-00', 'BLM5005',
       'PD3656-23', 'SE-1619-10-3', 'PD2177-00', 'TS1004037', 'Bai-BW-022031', 'SLT012FLE', 'PD3620-12', '5835370000', '5037970000', 'SE-1920-01-3', 'PD3938-00', 'DJ5332-01BX',
       '578169000', 'SE-1932-01-3', 'PD3615-23', '2100-02Lola', 'GP-2K79LV BCD', '5883420000', 'P716', 'GP-20K17-BX', 'GP-2K237 BX', '5145860000', 'P724', '700106lola', '512656000',
       'TS1091001', 'PD3731-23', '5146320000', '5834640000', '117022', 'P716', 'TS1091003', '5839790000', 'TS1097127', 'P723', '14445', 'GP-2K322S BCD', '5108150000', 'PD3613-00',
       'Bai-BW-015015', 'TS1091003', 'PD2187-00', '14445', '5227160000'
    ];
    $categorys=[
      127, //mega-masturbatory-realistiki
    ];
    if(is_object($product)){
      if(in_array($product->getGeneralCategory()->getId(), $categorys))
        return true;
      if(in_array($product->getId(), $blured)) return true;
      if(in_array($product->getCode(), $blured)) return true;
    }
    return false;
  }
}
?>
