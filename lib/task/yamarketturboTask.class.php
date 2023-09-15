<?php

class yamarketturboTask extends sfBaseTask {

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
        $this->name = 'yamarketturbo';
        $this->briefDescription = 'Формирует фид для турбостраниц Яндекса';
        $this->detailedDescription = <<<EOF
The [yamarket|INFO] формирует фид для турбостраниц Яндекса.
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

        ini_set("max_execution_time", 30000);

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
        $i=0;
        foreach ($catalog as $category) {
          if($isTest && $i++>2) break;
          if ($category->getId() != 135 /*&& $category->getIsPublic()*/) { //задача 117012 - onona.ru - Внести изменения в фиде
                  $content .= '<category id="' . $category->getId() .'"';
                  $content .= '>';
                  $content .= $category->getName();
                  $content .= '</category>'."\n";
              }
        }
        $content .= '</categories>'."\n";
        $content .= '<offers>'."\n";

        //$products = ProductTable::getInstance()->findByYamarket(true);
        $i=0;
        // $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=5211';
        $utm='';
        $products = ProductTable::getInstance()->createQuery()->where("yamarket = '1'")->addWhere("is_public =1")->addWhere("count >1")->execute(); //findByYamarket(true);
        $ids=[
          19026,
          18026,
          15305,
        ];
        foreach ($products as $product) {
          $id=$product->getId();
          // if($isTest && !in_array($id, $ids) ) continue;
            if($isTest && $i++>2) break;
            //$categoryProduct = $product->getCategoryProducts();
            if ($product->getGeneralCategory()->getId() != 135) {
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
                        if($isTest) echo "-----------------------\n$id. ".$product->getName()."\n-----------------------\n";
                        // die(print_r($dopInfos, true));
                        foreach ($dopInfos as $property):
                            if ($property['name'] == "Производитель") {
                              $vendor=explode(',', str_replace(["&", "™"], ['and', ""], $property['value']))[0];
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
                    }
                }
            }
        }
        $content .= '</offers>'."\n";
        // $content .= $this->getPromos($promoItems);
        $content .= '</shop>'."\n";
        $content .= '</yml_catalog>'."\n";

        if($isTest) $file = fopen('/home/i9s/p702/run/web/' . substr($SITE_NAME, 0, -3) . '_yaxml_turbo_test.xml', "w+");
        else $file = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_yaxml_turbo.xml', "w+");
        fputs($file, $content, (strlen($content) + 1));
        fclose($file);

        $content =
          '<?xml version="1.0" encoding="UTF-8"?>'."\n".
          '<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">'."\n".
            '<channel>'."\n".
              "<title>Сексопедия</title>"."\n".
              "<link>https://$SITE_NAME</link>"."\n".
              "<description>Сексопедия</description>"."\n".
              "<language>ru</language>"."\n".
              // "<turbo:analytics></turbo:analytics>"."\n".
              // "<turbo:adNetwork></turbo:adNetwork>"."\n".
          '';
        $i=0;
        $articles = ArticleTable::getInstance()->createQuery()->where("is_public =1")->execute();
        foreach ($articles as $article) {
          if($isTest && $i++>2) break;
          $content .=
            '<item turbo="true">'."\n".
              '<link>https://'.$SITE_NAME.'/sexopedia/'.$article->getSlug().'</link>'."\n".
              // '<turbo:source></turbo:source>'."\n".
              // '<turbo:topic></turbo:topic>'."\n".
              '<pubDate>'.date('r', strtotime($article->getUpdatedAt())).'</pubDate>'."\n".
              '<author>OnOna.ru</author>'."\n".
              // '<yandex:related></yandex:related>'."\n".
              '<turbo:content>'."\n".
                '<![CDATA['.
                  '<header>'."\n".
                    '<h1>'.$article->getName().'</h1>'."\n".
                    '';
          $img=$article->getImg();
          if ($img)
            $content.=
                    '<figure><img src="https://onona.ru/uploads/photo/'.$img.'"/></figure>'."\n";
          $content.=
                  '</header>'."\n".
                  $this->stripeProducts($article->getContent()).
                ']]>'.
              '</turbo:content>'."\n".
            '</item>'."\n".
            '';
        }

        $content.=
            '</channel>'."\n".
          '</rss>'."\n".
          '';


        if($isTest) $file2 = fopen('/home/i9s/p702/run/web/' . substr($SITE_NAME, 0, -3) . '_yaxml_turbopages_test.xml', "w+");
        else $file2 = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_yaxml_turbopages.xml', "w+");
        fputs($file2, $content, (strlen($content) + 1));
        fclose($file2);

        if($isTest) exit('yamarket test run success. '.date('d.m.Y H:i')."\n");

    }
    private function stripeProducts($text){
      $re = '/(class="[\S\s]+")|(id="[\S\s]+")|(style="[\S\s]+")|({products:[\S\s]+})/mU';
      $text=preg_replace($re, '', $text);
      $text=str_ireplace('src="/','src="https://onona.ru/' , $text);
      $text=str_ireplace('href="/','href="https://onona.ru/' , $text);
      $text=str_ireplace('http://onona.ru','https://onona.ru' , $text);
      return $text;
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
