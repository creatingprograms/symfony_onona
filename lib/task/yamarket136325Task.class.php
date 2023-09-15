<?php

class yamarket136325Task extends sfBaseTask {

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
        $this->name = 'yamarket136325';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [yamarket|INFO] task формирует фид для задачи 136325. Он как обычный, но сильно короче
Call it with:

  [php symfony yamarket136325|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $isTest =false;
        // $isTest =true;

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        // add your code here

        ini_set("max_execution_time", 3000);
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        function prepareText($text, $isName = false, $notStipTags=false) {
            if(!$notStipTags) $text = strip_tags($text);
            if(!$notStipTags) $text = htmlspecialchars($text);
            if ($isName) $text = str_ireplace([' – в ассортименте', ' - в ассортименте', 'в ассортименте'], '', $text);
            $wordsToRemove=[
              'хит',
              'новинка',
              'скидка',
              'распродажа',
              'дешевый',
              'дешёвый',
              'подарок',
              'бесплатно',
              'акция',
              'специальная цена',
              'new',
              'аналог',
              'заказ',
            ];
            if(!$isName){
              $text = str_ireplace($wordsToRemove, '', $text);
            }
            $re = '/(class="[\S\s]+")|(<\/a>)|(<a[\S\s]+>)|(id="[\S\s]+")|(style="[\S\s]+")/mU';
            $text=preg_replace($re, '', $text);
            return $text;
        }
        $discountVendors=[
          'systemjo',
          'kanikule',
          'sitabella',
          'calexotics',
          'real',
        ];

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
        if(!$isTest)
          foreach ($catalog as $category) {
          if ($category->getId() != 135 /*&& $category->getIsPublic()*/) { //задача 117012 - onona.ru - Внести изменения в фиде
                  //echo $category->getName();exit;
                  $content .= '<category id="' . $category->getId() .'"';
                  $content .= '>';
                  $content .= $category->getName();
                  $content .= '</category>'."\n";
              }
          }
        $content .= '</categories>'."\n";
        // $content .= '<adult>true</adult>';
        $content .= '<offers>'."\n";

        //$products = ProductTable::getInstance()->findByYamarket(true);
        $i=0;
        $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=5211';
        $ids=[
          "'EGG-VP6'",
          "'EGG-VP6-2'",
          "'JO10128'",
          "'JO40034'",
          "'Eska01'",
          "'291 MIL'",
          "'JO40035'",
          "'JO40111'",
          "'JO40107'",
          "'JO40210'",
          "'JO30232'",
          "'JO41064'",
          "'JO40079'",
          "'JO10127'",
          "'JO40080'",
          "'44027 HOT'",
          "'JO40011'",
          "'JO40211'",
          "'JO40109'",
          "'44025 HOT'",
          "'JO41041'",
          "'44043 HOT'",
          "'44026 HOT'",
          "'Eska02'",
          "'RW72104'",
          "'44030 HOT'",
          "'4720 SIT'",
          "'JO40207'",
          "'RW72103'",
          "'JO40216'",
          "'44028 HOT'",
          "'44033 HOT'",
          "'JO42032'",
          "'44042 HOT'",
          "'44032 HOT'",
          "'JO10632'",
          "'44040 HOT'",
          "'4710 SIT'",
          "'44130 HOT'",
          "'JO41065'",
        ];
        $products = ProductTable::getInstance()
          ->createQuery()
          // ->where("yamarket = '1'")
          ->where("is_public =1")
          ->addWhere("code IN(".implode(',', $ids).")")
          // ->addWhere("count >1")
          ->execute();
        $i=0;
        foreach ($products as $product) {
          $id=$product->getId();
          if($isTest && !in_array($id, $ids) ) continue;
            if ($product->getGeneralCategory()->getId() != 135) {
              // if($product->getId()!=12390) continue;
                if ($product->getPrice() > 0) {
                    if ( true) {
                        $content .= '<offer id="' . $product->getId() . '"';
                        if ($product->getCount() > 1) {
                            $content .= ' available="true"';
                        } else {
                            $content .= ' available="false"';
                        }
                        $yamarketVendorModel=($product->getYamarketClothes() && $product->getYamarketModel() && $product->getYamarketTypeprefix());
                        if ($yamarketVendorModel) {
                            $content .= ' type="vendor.model"';
                        }

                        $content .= '>'."\n";
                        $content .= '<url>https://' . $SITE_NAME . '/product/' . str_replace('&', '&amp;',$product->getSlug()) . $utm. '</url>'."\n";
                        $content .= '<price>';
                        $content .= $product->getPrice();
                        $content .= '</price>'."\n";
                        $content .= '<currencyId>RUR</currencyId>'."\n";
                        $content .= '<categoryId>' . $product->getGeneralCategory()->getId() . '</categoryId>'."\n";
                        if ($yamarketVendorModel) {
                            $content .= '<market_category>' . $product->getYamarketCategory() . '</market_category>'."\n";
                        }
                        $photoalbum = $product->getPhotoalbums();
                        $photos = PhotoTable::getInstance()->createQuery()->where("album_id='" . $photoalbum[0]->getId() . "'")->orderBy("position")->execute();

                        if (sizeof($photos)) foreach($photos as $photo) {
                          $content .= '<picture>https://' . $SITE_NAME . '/uploads/photo/' . $photo->getFilename() . '</picture>'."\n";
                        }
                        $content .= '<delivery>true</delivery>'."\n";

                        if (!$yamarketVendorModel) {
                            $content .= '<name>' . prepareText($product->getName(), true) . '</name>'."\n";
                        }
                        $dopInfos = $product->getDopInfoProducts();
                        $isNeedVendor=true;
                        // if($isTest) echo "-----------------------\n$id. ".$product->getName()."\n-----------------------\n";
                        foreach ($dopInfos as $property):
                            if($isTest) {
                              echo "id:".$property['id']."\nprop:".$property['name']."\n". 'value:'.$property['value']."\n";
                            }
                            if ($property['name'] == "Производитель") {
                              $vendor=explode(',', str_replace("™", "", $property['value']))[0];
                              if (in_array(strtolower($vendor), $discountVendors)) $promoItems[]=$product->getId();
                              if(!$isNeedVendor) continue;
                              $isNeedVendor=false;
                              $content .= '<vendor>' . prepareText($vendor) . '</vendor>'."\n";
                            }

                            if ($property['name'] == "Размер") {
                                $sizeProd = $property['value'];
                            }

                            if ($property['name'] == "Материал") {
                                $matherial = $property['value'];
                            }
                        endforeach;
                        $content .= '<vendorCode>' . (substr($product->getId1c(), -5, 5) != "" ? substr($product->getId1c(), -5, 5) : $product->getCode())/*str_replace("&", "", $product->getCode())*/ . '</vendorCode>'."\n";
                        if ($yamarketVendorModel) {
                          $model=$product->getYamarketModel();
                          if($model)
                            $content .= '<model>' . $model . '</model>'."\n";
                          $typePrefix=$product->getYamarketTypeprefix();
                          if($typePrefix)
                            $content .= '<typePrefix>' . $typePrefix . '</typePrefix>'."\n";
                        }
                        $content .= '<description><![CDATA[' . prepareText($product->getContent(), false, true) . ']]></description>'."\n";
                        if ($product->getAdult()) {
                            $content .= '<adult>true</adult>'."\n";
                        }

                        if ($yamarketVendorModel) {

                            $content .= '<param name="Размер" unit="INT">' . $sizeProd . '</param>'.
                            '<param name="Цвет">' . $product->getYamarketColor() . '</param>'.
                            ($product->getYamarketTypeimg() != "" ? ('<param name="Тип рисунка">' . $product->getYamarketTypeimg() . '</param>') : "") .
                            '<param name="Пол">' . $product->getYamarketSex() . '</param>'.
                            '<param name="Возраст">Взрослый</param>'.
                            '<param name="Материал">' . $matherial . '</param>'."\n";
                        }
                        unset($sizeProd, $matherial);
                        $content .= '</offer>'."\n";
                        $i++;
                    }
                }
            }
        }
        $content .= '</offers>'."\n";
        $content .= $this->getPromos($promoItems);
        $content .= '</shop>'."\n";
        $content .= '</yml_catalog>'."\n";

        if($isTest) $file = fopen('/home/i9s/p702/run/web/' . substr($SITE_NAME, 0, -3) . '_yaxml_test_136325.xml', "w+");
        else $file = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_yaxml_136325.xml', "w+");
        fputs($file, $content, (strlen($content) + 1));
        fclose($file);

        echo 'Передано '.sizeof($ids).' артикулов. Выведено '.$i.' предложений'."\n";

        if($isTest) exit('yamarket test run success. '.date('d.m.Y H:i')."\n");



    }
    private function getPromos($promoItems){
      $retStr='';
      if(sizeof($promoItems)){
        $retStr.='<promos>'."\n";
        $retStr.='<promo id="Promo15" type="promo code">'."\n";
        $retStr.='<promo-code>YM15</promo-code>'."\n";
        $retStr.='<discount unit="percent">15</discount>'."\n";
        $retStr.='<purchase>'."\n";

        foreach ($promoItems as $itemId) {
          $retStr.='<product offer-id="'.$itemId.'"/> '."\n";
        }

        $retStr.='</purchase>'."\n";
        $retStr.='</promo>'."\n";
        $retStr.='</promos>'."\n";
      }
      return $retStr;

    }

}
