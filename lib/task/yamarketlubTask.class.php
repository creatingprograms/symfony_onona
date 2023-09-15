<?php

class yamarketlubTask extends sfBaseTask {

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
        $this->name = 'yamarketlub';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [yamarketlub|INFO] task does things.
Call it with:

  [php symfony yamarket|INFO]
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
            if ($isName) $text = str_replace([' – в ассортименте', ' - в ассортименте', 'в ассортименте'], '', $text);
            return $text;
        }

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
        // if(!$isTest)
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
        $lubrCats=[
          98, 199, 200, 201, 202, 203, 204, 205, 206, 207, 217
        ];
        $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=5211';
        $products = ProductTable::getInstance()->createQuery()->where("yamarket = '1'")->addWhere("is_public =1")->addWhere("generalcategory_id IN (".implode(',', $lubrCats).")")->addWhere("count >1")->execute(); //findByYamarket(true);
        $ids=[
          19026,
          18026,
          15305,
          // 15316,
          // 16059,
          // 16853,
          // 15130,
          // 17384,
          // 17383,
          // 15132,
          // 18422,
          // 17858
        ];

        foreach ($products as $product) {
          $id=$product->getId();
          // if($isTest && !in_array($id, $ids) ) continue;
            // if($isTest && $i++>2) break;
            //$categoryProduct = $product->getCategoryProducts();
            if ($product->getGeneralCategory()->getId() != 135) {
              // if($product->getId()!=12390) continue;
                if ($product->getPrice() > 0) {
                    if (/* $product->getCount() > 0 */ true) {
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
                        if($product->getBarcode()) $content .= '<barcode>'.$product->getBarcode().'</barcode>';
                        if ($yamarketVendorModel) {
                            $content .= '<market_category>' . $product->getYamarketCategory() . '</market_category>'."\n";
                        }
                        $photoalbum = $product->getPhotoalbums();
                        //$photos = $photoalbum[0]->getPhotos();
                        $photos = PhotoTable::getInstance()->createQuery()->where("album_id='" . $photoalbum[0]->getId() . "'")->orderBy("position")->execute();

                        if (sizeof($photos)) foreach($photos as $photo) {
                          $content .= '<picture>https://' . $SITE_NAME . '/uploads/photo/' . $photo->getFilename() . '</picture>'."\n";
                        }
                        /*if (isset($photos[0])) {
                            $content .= '<picture>https://' . $SITE_NAME . '/uploads/photo/' . $photos[0]->getFilename() . '</picture>'."\n";
                        }*/
                        $content .= '<delivery>true</delivery>'."\n";

                        if (!$yamarketVendorModel) {
                            $content .= '<name>' . prepareText($product->getName(), true) . '</name>'."\n";
                        }
                        $dopInfos = $product->getDopInfoProducts();
                        $isNeedVendor=true;
                        if($isTest) echo "-----------------------\n$id. ".$product->getName()."\n-----------------------\n";
                        // die(print_r($dopInfos, true));
                        foreach ($dopInfos as $property):
                            if($isTest) {
                              echo "id:".$property['id']."\nprop:".$property['name']."\n". 'value:'.$property['value']."\n";
                            }
                            if ($property['name'] == "Производитель") {
                              if(!$isNeedVendor) continue;
                              $isNeedVendor=false;
                              $content .= '<vendor>' . explode(',', str_replace("™", "", $property['value']))[0] . '</vendor>'."\n";
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
                    }
                }
            }
        }
        $content .= '</offers>'."\n";
        $content .= '</shop>'."\n";
        $content .= '</yml_catalog>'."\n";

        if($isTest) $file = fopen('/home/i9s/p702/run/web/' . substr($SITE_NAME, 0, -3) . '_yaxml_lub_test.xml', "w+");
        else $file = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_yaxml_lub.xml', "w+");
        fputs($file, $content, (strlen($content) + 1));
        fclose($file);

        if($isTest) exit('yamarket test run success. '.date('d.m.Y H:i')."\n");



    }

}
