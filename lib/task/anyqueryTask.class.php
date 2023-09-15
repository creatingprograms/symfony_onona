<?php

class anyqueryTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "newcat"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'anyquery';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [anyquery|INFO]Генерирует отдельный фид для AnyQyery

  [php symfony anyquery|INFO]
EOF;
    }
    private function getMeasure($string){
      $lastSpaceIndex=strrchr (trim($string), ' ');

      return [
        'value' =>trim(mb_strlen(trim($lastSpaceIndex)) ? str_replace($lastSpaceIndex, '', $string) : $string),
        'unit' => trim($lastSpaceIndex),
        'strlen' => mb_strlen($lastSpaceIndex)

      ];
    }

    protected function execute($arguments = array(), $options = array()) {
        $isTest =false;
        // $isTest =true;

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        ini_set("max_execution_time", 3000);
        $sqlBody="UPDATE dop_info di LEFT JOIN dop_info_category dic ON dic.id=di.dicategory_id SET di.name=dic.name WHERE di.name='' OR di.NAME IS NULL"; //Заполняем пустые имена свойств если они есть
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute($sqlBody);

        function prepareText($text, $isName = false, $notStipTags=false) {
            if(!$notStipTags) $text = strip_tags($text);
            if(!$notStipTags) $text = htmlspecialchars($text);
            // if ($isName) $text = str_ireplace([' – в ассортименте', ' - в ассортименте', 'в ассортименте'], '', $text);
            $wordsToRemove=[

            ];
            if(!$isName){
              $text = str_ireplace($wordsToRemove, '', $text);
            }
            $re = '/(class="[\S\s]+")|(<\/a>)|(<a[\S\s]+>)|(<\/div>)|(<div[\S\s]+>)|(id="[\S\s]+")|(style="[\S\s]+")/mU';
            $text=preg_replace($re, '', $text);
            return trim($text);
        }
        $discountVendors=[ ];

        $xml_name = 'Он и Она';
        $xml_company = 'ONONA';
        $SITE_NAME = "onona.ru";

        $catalog = CategoryTable::getInstance()->findAll();

        $content = '<?xml version="1.0" encoding="utf-8"?>
				<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'."\n";

        $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'."\n";
        $content .= '<shop>'."\n";
        $content .= '<name>' . $xml_name . '</name>'."\n";
        $content .= '<company>' . $xml_company . '</company>'."\n";
        $content .= '<url>https://' . $SITE_NAME . '</url>'."\n";
        $content .= '<currencies>'."\n";
        $content .= '<currency id="RUR" rate="1" />';
        $content .= '</currencies>'."\n";
        $content .= '<categories>'."\n";
        if(true)//!$isTest)
          foreach ($catalog as $category) {
            if ($category->getId() != 135 && $category->getIsPublic()) {
              //echo $category->getName();exit;
              $content .= '<category id="' . $category->getId() .'"';
              $catSlug  = mb_strtolower($category->getSlug());
              if(mb_stripos($catSlug, 'service-', 0, 'utf-8')===false)
                $content .= ' url="https://'.$SITE_NAME.'/category/'. $catSlug .'"';
              else
                $content .= ' url="https://'.$SITE_NAME.'/catalog/'. str_replace('service-', '', $catSlug) .'"';
              $content .= '>';
              $content .= str_replace('служебная ', '', $category->getName());
              $content .= '</category>'."\n";
              }
          }
        $content .= '</categories>'."\n";

        $content .= '<offers>'."\n";

        $i=0;
        $utm='';
        // $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=';
        $products = ProductTable::getInstance()->createQuery()->where("is_public =1")->execute();
        $ids=[
          19026,
          18245,
        ];
        foreach ($products as $product) {
          $id=$product->getId();
          if($isTest && !in_array($id, $ids) ) continue;
            // if($isTest && $i++>2) break;
            //$categoryProduct = $product->getCategoryProducts();
            if ($product->getGeneralCategory()->getId() != 135 && $product->getPrice() > 0) {
              $content .= '<offer id="' . $product->getId() . '"';
              if ($product->getCount() > 1) {
                  $content .= ' available="true"';
              } else {
                  $content .= ' available="false"';
              }

              $content .= '>'."\n";
              $content .= '<url>https://' . $SITE_NAME . '/product/' . str_replace('&', '&amp;',$product->getSlug()) . $utm. '</url>'."\n";
              $content .= '<price>';
              $content .= $product->getPrice();
              $content .= '</price>'."\n";
              if($product->getOldPrice()) $content .= '<oldprice>'.$product->getOldPrice().'</oldprice>'."\n";
              $content .= '<currencyId>RUR</currencyId>'."\n";
              if($product->getBarcode()) $content .= '<barcode>'.$product->getBarcode().'</barcode>'."\n";
              $content .= '<categoryId>' . $product->getGeneralCategory()->getId() . '</categoryId>'."\n";

              $photoalbum = $product->getPhotoalbums();
              //$photos = $photoalbum[0]->getPhotos();
              $photos = PhotoTable::getInstance()->createQuery()->where("album_id='" . $photoalbum[0]->getId() . "'")->orderBy("position")->execute();
              $photosCount=0;
              if (sizeof($photos)) foreach($photos as $photo) {
                if($photosCount++==1) break;
                $content .= '<picture>https://' . $SITE_NAME . '/uploads/photo/' . $photo->getFilename() . '</picture>'."\n";
              }
              $content .= '<delivery>true</delivery>'."\n";

              $content .= '<name>' . prepareText($product->getName(), true) . '</name>'."\n";

              $dopInfos = $product->getDopInfoProducts();
              $isNeedVendor=true;
              if($isTest) echo "-----------------------\n$id. ".$product->getName()."\n-----------------------\n";
              // die(print_r($dopInfos, true));
              $propertyToShow=[
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
                // 'Батарейки', 'Особенность', 'Аромат', 'Свойство', 'Тип'
              ];
              $propetySkip=[
                "Таблица размеров",
                'Автор',
                'Особенность 2',
                'Издательство',
                'Серия',
                'Часть',
                'Организатор',
                'Продолжительность',
                'Номинал',
                'Для кого',
              ];
              foreach ($dopInfos as $property){
                if (in_array($property['name'], $propetySkip)) continue;
                if ($property['name'] == "Производитель") {
                  $arrVendor=explode(',', str_replace("™", "", $property['value']));
                  $vendor=$arrVendor[0];
                  if (in_array(strtolower($vendor), $discountVendors)) $promoItems[]=$product->getId();
                  if(!$isNeedVendor) continue;
                  $isNeedVendor=false;
                  $content .= '<vendor>' . prepareText($vendor) . '</vendor>'."\n";
                  if($arrVendor[1]) $content .= '<country_of_origin>' . prepareText($arrVendor[1]) . '</country_of_origin>'."\n";
                  $content .= '<param name="Страна происхождения">' . prepareText($arrVendor[1]) . '</param>'."\n";
                }
                elseif ($property['name'] == "Размер") {
                  $sizeProd = $property['value'];
                  $content .= '<param name="Размер" unit="INT">' . prepareText($sizeProd) . '</param>';
                }
                elseif (in_array($property['name'], $propertyToShow)) {
                  if($isTest) {
                    echo "prop:".$property['name']."\n". 'value:'.$property['value']."\n";
                  }
                  $param=$this->getMeasure( $property['value']);
                  // if($isTest) echo (print_r(['src' => $propety['value'], 'res' => $param], true));
                  $content .= '<param name="'.$property['name'].'"'.($param['unit'] ? ' unit="'.$param['unit'].'"' : '').'>' . prepareText($param['value']) . '</param>'."\n";
                }
                else{
                  $content .= '<param name="' . $property['name'] . '">' . prepareText($property['value']) . '</param>'."\n";
                }
              }
              $content .= '<param name="Артикул">' . prepareText($product->getCode()) . '</param>'."\n";
              $content .= '<vendorCode>' . (substr($product->getId1c(), -5, 5) != "" ? substr($product->getId1c(), -5, 5) : $product->getCode()) . '</vendorCode>'."\n";

              $content .= '<description><![CDATA[' . prepareText($product->getContent(), false, true) . ']]></description>'."\n";
              if ($product->getAdult()) {
                  $content .= '<adult>true</adult>'."\n";
              }
              $content .= '</offer>'."\n";
              if($isTest) $content .= "\n"."\n";
            }
        }
        $content .= '</offers>'."\n";

        $content .= '</shop>'."\n";
        $content .= '</yml_catalog>'."\n";
        $filename="anyquery";

        if($isTest) $file = fopen('/home/i9s/p702/run/web/' . substr($SITE_NAME, 0, -3) . '_'.$filename.'_test.xml', "w+");
        else $file = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_'.$filename.'.xml', "w+");
        fputs($file, $content, (strlen($content) + 1));
        fclose($file);

        if($isTest) exit('anyquery test run success. '.date('d.m.Y H:i')."\n");


    }

}
