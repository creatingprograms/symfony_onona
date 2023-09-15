<?php

class rrmarketTask extends sfBaseTask {

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
        $this->name = 'rrmarket';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [yamarket|INFO] generate yml based feed.
Call it with:

  [php symfony rrmarket|INFO]
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

        function prepareText($text, $isName = false, $notStipTags=false) {
            if(!$notStipTags) $text = strip_tags($text);
            if(!$notStipTags) $text = htmlspecialchars($text);
            if ($isName) $text = str_ireplace([' – в ассортименте', ' - в ассортименте', 'в ассортименте'], '', $text);
            $wordsToRemove=[
              // 'хит',
              // 'новинка',
              // 'скидка',
              // 'распродажа',
              // 'дешевый',
              // 'дешёвый',
              // 'подарок',
              // 'бесплатно',
              // 'акция',
              // 'специальная цена',
              // 'new',
              // 'аналог',
              // 'заказ',
            ];
            if(!$isName){
              $text = str_ireplace($wordsToRemove, '', $text);
            }
            $re = '/(class="[\S\s]+")|(<\/a>)|(<a[\S\s]+>)|(id="[\S\s]+")|(style="[\S\s]+")/mU';
            $text=preg_replace($re, '', $text);
            return $text;
        }

        $xml_name = 'Он и Она';
        $xml_company = 'ONONA';
        $SITE_NAME = "onona.ru";
        $persent_bonus_add=csSettings::get('persent_bonus_add');
        $persent_bonus_pay=csSettings::get('PERSENT_BONUS_PAY');

        $catalog = CategoryTable::getInstance()->findAll();

        // if($isTest) $file = fopen('/home/i9s/p702/run/web/' . substr($SITE_NAME, 0, -3) . '_yaxml_test.xml', "w+");
        // else $file = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_yaxml.xml', "w+");
        // fputs($file, $content, (strlen($content) + 1));
        // fclose($file);

        //        Ретайл рокет

        $content = '<?xml version="1.0" encoding="utf-8"?>
				<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';

        $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">';
        $content .= '<shop>';
        $content .= '<name>' . $xml_name . '</name>';
        $content .= '<company>' . $xml_company . '</company>';
        $content .= '<url>https://' . $SITE_NAME . '</url>';
        $content .= '<currencies>';
        $content .= '<currency id="RUR" rate="1" />';
        $content .= '</currencies>';
        $content .= '<categories>';
        //Получаем список сервисных категорий, которые теперь заменяют каталоги
        $catalogTable=CatalogTable::getInstance()->findAll();
        foreach ($catalogTable as $cat) {
          $serviceCat= CategoryTable::getInstance()->findOneBySlug('service-'.$cat->getSlug());
          if(is_object($serviceCat)){  //Если есть служебная категория каталога, то будем выводить ее
            $catalogToCategory[$cat->getId()]=$serviceCat->getId();
            $content .= '<category id="' . $serviceCat->getId() .'" parentId=""';
          }
          else{ //Если ее нет то можно ничего не делать, один хрен мы все умрем (она должна быть всегда)
            $catalogToCategory[$cat->getId()]='1000000'.$cat->getId();
            $content .= '<category id="1000000' . $cat->getId() .'" parentId=""';
          }
          $catalogToCategory[0]=135;//Что это за категория ХЗ, но она игнорировалась всегда. Сюда заносим чтоб не выводить ее в общем списке
          $content .= '>'."\n";
          $content .= $cat->getName().' '.$cat->getDescription();
          $content .= '</category>';
          /*
          $catalog[]=[
          'id'=>'c_'.$cat->getId(),
          'name'=>$cat->getName().$cat->getDescription(),
        ];*/
      }
        $catalogCategory=Doctrine_Core::getTable('categoryCatalog')
                ->createQuery('n')->select("*")->execute();
        foreach ($catalogCategory as $catCat) {
          $catCatArray[$catCat->getCategoryId()]=$catCat->getCatalogId();
        }
        // print_r($catCatArray);
        // echo "\n";

        foreach ($catalog as $category) {
            if (!in_array($category->getId(), $catalogToCategory) && $category->getIsPublic()) {
                //echo $category->getName();exit;
                $parent=$category->getParentsId();
                $content .=
                  '<category id="' . $category->getId() .
                  //Родитель либо свой, либо берем id сервисной категории по id каталога
                  '" parentId="'.($parent!='' ? $parent : $catalogToCategory[$catCatArray[$category->getId()]]) .'"';
                $content .= '>';
                $content .= $category->getName();
                $content .= '</category>';
            }
        }
        // die (print_r([$catCatArray, 'content'=>$content], true));
        $content .= '</categories>';

        //$content .= '<adult>true</adult>';
        $content .= '<offers>';


        //$products = ProductTable::getInstance()->findByYamarket(true);

        $products = ProductTable::getInstance()->createQuery()->where("yamarket = '1'")->execute(); //findByYamarket(true);
        $iii=0;
        foreach ($products as $product) {
          if($isTest && $iii>2) break;
            //$categoryProduct = $product->getCategoryProducts();
            if ($product->getGeneralCategory()->getId() != 135) {
                if ($product->getPrice() > 0) {
                    if (/* $product->getCount() > 0 */ true) {
                        $content .= '<offer id="' . $product->getId() . '"';
                        if ($product->getCount() > 0) {
                            $content .= ' available="true"';
                        } else {
                            $content .= ' available="false"';
                        }
                        if ($product->getYamarketClothes()) {
                            $content .= ' type="vendor.model"';
                        }

                        $content .= '>';
                        $content .= '<url>https://' . $SITE_NAME . '/product/' . str_replace('&', '&amp;',$product->getSlug()) . '</url>';
                        $content .= '<price>';
                        $content .= $product->getPrice();
                        $content .= '</price>';
                        if($oldPrice=$product->getOldPrice()) {
                          $content.="<oldprice>$oldPrice</oldprice>";
                          $iii++;
                        }
                        $content .= '<currencyId>RUR</currencyId>';
                        $content .= '<categoryId>' . $product->getGeneralCategory()->getId() . '</categoryId>';
                        if ($product->getYamarketClothes()) {
                            $content .= '<market_category>' . $product->getYamarketCategory() . '</market_category>';
                        }
                        $photoalbum = $product->getPhotoalbums();
                        //$photos = $photoalbum[0]->getPhotos();
                        $photos = PhotoTable::getInstance()->createQuery()->where("album_id='" . $photoalbum[0]->getId() . "'")->orderBy("position")->execute();

                        if (isset($photos[0])) {
                            $content .= '<picture>https://' . $SITE_NAME . '/uploads/photo/' . $photos[0]->getFilename() . '</picture>';
                        }
                        $content .= '<delivery>true</delivery>';

                        if (!$product->getYamarketClothes()) {
                            $content .= '<name>' . prepareText($product->getName()) . '</name>';
                        }
                        $isNeedVendor=true;
                        $dopInfos = $product->getDopInfoProducts();
                        foreach ($dopInfos as $property):
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
                        $content .= '<vendorCode>' . (substr($product->getId1c(), -5, 5) != "" ? substr($product->getId1c(), -5, 5) : $product->getCode())/*str_replace("&", "", $product->getCode())*/ . '</vendorCode>';
                        if ($product->getYamarketClothes()) {

                            $content .= '<model>' . $product->getYamarketModel() . '</model>';
                        }
                        $content .= '<description>' . prepareText($product->getContent()) . '</description>';
                        $rating = round(($product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0) / 2);
                        if($rating) $content .= '<param name="Рейтинг">'.$rating.'</param>';
                        $bonus = round(
                          ($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : $persent_bonus_pay) / 100)
                          *
                          (($product->getBonus() > 0 ? $product->getBonus() : $persent_bonus_add) / 100)
                        );
                        // if ($isTest) die ('!'.print_r([$product->getBonus(), $product->getPrice(), $product->getBonuspay(), $persent_bonus_add, $bonus], true));
                        /*
                            !Array (
                              [0] =>
                              [1] => 3900
                              [2] =>
                              [3] => 30
                              [4] => 819
                            )
                        */
                        if($bonus) $content .= '<param name="Бонус">'.$bonus.'</param>';

                        if ($product->getAdult()) {
                            $content .= '<adult>true</adult>';
                        }

                        if ($product->getYamarketClothes()) {

                            $content .= '<param name="Размер" unit="INT">' . $sizeProd . '</param>'.
                            '<param name="Цвет">' . $product->getYamarketColor() . '</param>'
                             . ($product->getYamarketTypeimg() != "" ? ('<param name="Тип рисунка">' . $product->getYamarketTypeimg() . '</param>') : "") .
                             '<param name="Пол">' . $product->getYamarketSex() . '</param>'.
                             '<param name="Возраст">Взрослый</param>'.
                             '<param name="Материал">' . $matherial . '</param>';
                        }
                        unset($sizeProd, $matherial);
                        $content .= '</offer>';
                    }
                }
            }
        }
        $content .= '</offers>';
        $content .= '</shop>';
        $content .= '</yml_catalog>';

        if(!$isTest) $file = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_rryaxml.xml', "w+");
        else $file = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_rryaxml_test.xml', "w+");
        fputs($file, $content, (strlen($content) + 1));
        fclose($file);


        if($isTest) exit('rr done');









        /*


          //         Raketa

          // Categorys
          $RaketaCategorys = 'id;parent_id;url;min_price;max_price;models_qty;name;page_type';

          // $catalog = CategoryTable::getInstance()->findAll();

          foreach ($catalog as $category) {
          if ($category->getId() != 135) {
          //echo $category->getName();exit;
          $RaketaCategorys .= '
          ' . $category->getId() . ';' . $category->get('parents_id') . ';https://onona.ru/category/' . $category->get('slug') . ';;;;"' . $category->getName() . '";категория';
          }
          }


          $file = fopen('/var/www/ononaru/data/www/onona.ru/raketa_categorys.csv', "w+");
          fputs($file, $RaketaCategorys, (strlen($RaketaCategorys) + 1));
          fclose($file);


          // Products


          $RaketProducts = '<?xml version="1.0" encoding="utf-8"?>
          <!DOCTYPE yml_catalog SYSTEM "shops.dtd">';

          $RaketProducts .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">';
          $RaketProducts .= '<shop>';
          $RaketProducts .= '<name>' . $xml_name . '</name>';
          $RaketProducts .= '<company>' . $xml_company . '</company>';
          $RaketProducts .= '<url>http://' . $SITE_NAME . '</url>';
          $RaketProducts .= '<currencies>';
          $RaketProducts .= '<currency id="RUR" rate="1" />';
          $RaketProducts .= '</currencies>';
          $RaketProducts .= '<categories>';
          foreach ($catalog as $category) {
          if ($category->getId() != 135) {
          //echo $category->getName();exit;
          $RaketProducts .= '<category id="' . $category->getId() . '"';
          $RaketProducts .= '>';
          $RaketProducts .= $category->getName();
          $RaketProducts .= '</category>';
          }
          }
          $RaketProducts .= '</categories>';
          //$content .= '<adult>true</adult>';
          $RaketProducts .= '<offers>';
          //$products = ProductTable::getInstance()->findAll(true);

          foreach ($products as $product) {
          //$categoryProduct = $product->getCategoryProducts();
          if ($product->getGeneralCategory()->getId() != 135) {
          if ($product->getPrice() > 0) {
          if ($product->getCount() > 0) {
          $RaketProducts .= '<offer id="' . $product->getId() . '"  type="vendor.model" available="true">';
          $RaketProducts .= '<url>http://' . $SITE_NAME . '/product/' . $product->getSlug() . '</url>';
          $RaketProducts .= '<price>';
          $RaketProducts .= $product->getPrice();
          $RaketProducts .= '</price>';
          $RaketProducts .= '<currencyId>RUR</currencyId>';
          $RaketProducts .= '<categoryId>' . $product->getGeneralCategory()->getId() . '</categoryId>';
          $photoalbum = $product->getPhotoalbums();
          //$photos = $photoalbum[0]->getPhotos();
          $photos = PhotoTable::getInstance()->createQuery()->where("album_id='" . $photoalbum[0]->getId() . "'")->orderBy("position")->execute();

          if (isset($photos[0])) {
          $RaketProducts .= '<picture>http://' . $SITE_NAME . '/uploads/photo/' . $photos[0]->getFilename() . '</picture>';
          }
          $RaketProducts .= '<delivery>true</delivery>';
          $dopInfos = $product->getDopInfoProducts();
          foreach ($dopInfos as $property):
          if ($property['name'] == "Производитель") {
          $RaketProducts .= '<vendor>' . str_replace("™", "", $property['value']) . '</vendor>';
          }
          endforeach;
          $RaketProducts .= '<vendorCode>' . str_replace("&", "", $product->getCode()) . '</vendorCode>';
          $RaketProducts .= '<model>' . prepareText($product->getName()) . '</model>';
          $RaketProducts .= '<description>' . prepareText($product->getContent()) . '</description>';
          if ($product->getAdult()) {
          $RaketProducts .= '<adult>true</adult>';
          }
          $RaketProducts .= '</offer>';
          }
          }
          }
          }


          $RaketProducts .= '</offers>';
          $RaketProducts .= '</shop>';
          $RaketProducts .= '</yml_catalog>';
          $file = fopen('/var/www/ononaru/data/www/onona.ru/raketa_products.xml', "w+");
          fputs($file, $RaketProducts, (strlen($RaketProducts) + 1));
          fclose($file);



         */

    }

}
