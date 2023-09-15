<?php

class feedgeneratorTask extends sfBaseTask {
    const SITE_PATH='https://onona.ru';

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "new"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('mode', null, sfCommandOption::PARAMETER_REQUIRED, 'mode', 'test'),
            new sfCommandOption('full', null, sfCommandOption::PARAMETER_REQUIRED, 'Выгружать все товары', false),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'feedgenerator';
        $this->briefDescription = '';
        $this->detailedDescription = "\nThe [anyquery|INFO]Генерирует отдельный фиды xml для различных нужд\n[php symfony anyquery|INFO]";
    }

    private function getMeasure($string){
      $lastSpaceIndex=strrchr (trim($string), ' ');

      return [
        'value' =>trim(mb_strlen(trim($lastSpaceIndex)) ? str_replace($lastSpaceIndex, '', $string) : $string),
        'unit' => trim($lastSpaceIndex),
        'strlen' => mb_strlen($lastSpaceIndex)

      ];
    }

    private function getXmlPhotos($product, $q){
      $nl=$this->nl;
      $sqlBody="SELECT `filename` FROM `product_photoalbum` pa "
        ."LEFT join `photo` p ON p.`album_id`=pa.`photoalbum_id` "
        ."WHERE pa.`product_id`=".$product['id'].' '
        ."ORDER BY p.`position` "
        ."";
      $rsPhotos=$q->execute($sqlBody);
      $rsPhotos->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $photos=$rsPhotos->fetchAll();
      $content='';
      if (sizeof($photos)) {
        $content.=
          '<url>'.$nl.
            '<loc>'.self::SITE_PATH.'/product/'.str_replace('&', '&amp;',$product['slug']).'</loc>'.$nl;
        foreach($photos as $photo) {
          $content.=
          '<image:image>'.$nl.
            '<image:loc>'.self::SITE_PATH.'/uploads/photo/'. $photo['filename'].'</image:loc>'.$nl.
            '<image:title>'.($product['h1'] ? str_replace('&', '&amp;', $product['h1']) : str_replace('&', '&amp;', $product['name'])).'</image:title>'.$nl.
          '</image:image>'.$nl;
        }
        $content.=
          '</url>'.$nl;
      }
      return $content;
    }

    private function getPhotos($productId, $photosCountMax, $q, $isShowComressedImg, $showAlterImages=false){
      //фотоальбомы
      $sqlBody="SELECT `filename` FROM `product_photoalbum` pa "
        ."LEFT join `photo` p ON p.`album_id`=pa.`photoalbum_id` "
        ."WHERE pa.`product_id`=".$productId.' '
        ."ORDER BY p.`position` "
        ."";
      $rsPhotos=$q->execute($sqlBody);
      $rsPhotos->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $photos=$rsPhotos->fetchAll();
      $photosCount=0;
      $content ='';
      if (sizeof($photos)) foreach($photos as $photo) {
        if($photosCount++==$photosCountMax) break;
        if(!$photo['filename']) $imageSrc='/frontend/images/no-image.png';
        else $imageSrc= ($isShowComressedImg ? '/resize/170/' : '/uploads/photo/') . $photo['filename'];
        $content .= '<picture>https://' . $this->SITE_NAME . $imageSrc . '</picture>'.$this->nl;
      }
      if($showAlterImages) {
        unset($photos[0]);
        if(sizeof($photos)) foreach ($photos as $photo) {
          if(!$photo['filename']) continue;
          $imageSrc= ($isShowComressedImg ? '/resize/170/' : '/uploads/photo/') . $photo['filename'];
          $content .= '<picture_additional>https://' . $this->SITE_NAME . $imageSrc . '</picture_additional>'.$this->nl;
        }
      }
      return $content;
    }

    private function getDiscountVendors(){
      return [
        'systemjo',
        'kanikule',
        'sitabella',
        'calexotics',
        'real',
      ];
    }

    private function getProps($q, $productId, $propertyToShow, $propetySkip, $propertyNeed = false, $isHaveDiscountVendors=false){
      $nl=$this->nl;
      $sqlBody="SELECT di.`name`, di.`value` FROM `dop_info_product` dip ".
        "LEFT JOIN `dop_info` di on di.`id`= dip.`dop_info_id` "
        ."WHERE dip.`product_id`=".$productId." "
        .(sizeof($propetySkip) ? "AND di.`name` NOT IN('".implode("', '", $propetySkip)."')": "")
        .($propertyNeed && sizeof($propertyNeed) ? "AND di.`name` IN('".implode("', '", $propertyNeed)."')": "")
        ."";
        // die($sqlBody. $nl);
      $isNeedVendor=true;
      $rsPhotos=$q->execute($sqlBody);
      $rsPhotos->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $dopInfos=$rsPhotos->fetchAll();
      $content='';
      // die([print_r($dopInfos, true)]);

      if($isHaveDiscountVendors){
        $discountVendors=$this->getDiscountVendors();
      }

      foreach ($dopInfos as $property){
        if (in_array($property['name'], $propetySkip)) continue;
        if ($property['name'] == "Производитель" || $property['name'] == "Бренд(ы)" || $property['name'] == "Бренд") {
          $arrVendor=explode(',', str_replace("™", "", $property['value']));
          $vendor=$arrVendor[0];
          if ($isHaveDiscountVendors && in_array(strtolower($vendor), $discountVendors)) $this->promoItems[]=$product['id'];
          if(!$isNeedVendor) continue;
          $isNeedVendor=false;
          $content .= '<vendor>' . $this->prepareText($vendor) . '</vendor>'.$nl;
          if(isset($arrVendor[1]) && $arrVendor[1]) $content .= '<country_of_origin>' . $this->prepareText($arrVendor[1]) . '</country_of_origin>'.$nl;
          if(isset($arrVendor[1]) && $arrVendor[1]) $content .= '<param name="Страна происхождения">' . $this->prepareText($arrVendor[1]) . '</param>'.$nl;
        }
        elseif ($property['name'] == "Размер") {
          $sizeProd = $property['value'];
          $content .= '<param name="Размер" unit="INT">' . $this->prepareText($sizeProd) . '</param>';
        }
        elseif (in_array($property['name'], $propertyToShow)) {
          $param=$this->getMeasure( $property['value']);
          $content .= '<param name="'.$property['name'].'"'.($param['unit'] ? ' unit="'.$param['unit'].'"' : '').'>' . $this->prepareText($param['value']) . '</param>'.$nl;
        }
        else{
          $content .= '<param name="' . $property['name'] . '">' . $this->prepareText($property['value']) . '</param>'.$nl;
        }

      }

      return $content;
    }

    private function getCatalog($q, $isShowParent=false, $isShowUrl=false, $isShowServiceAll=false, $params=[]){
      $nl=$this->nl;
      $content = '<categories>'.$nl;
      if($isShowParent){

        $sqlBody="SELECT `category_id`, cat.`id` FROM `category_catalog` cc "
          ."LEFT JOIN `catalog` c ON cc.`catalog_id` = c.`id` "
          ."LEFT JOIN `category` cat ON CONCAT('service-', c.`slug`)=cat.`slug` "
          // ."WHERE c.`is_public`=1 "
          ."";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $catalog=$result->fetchAll();
        foreach ($catalog as $catCat) {//Запоминаем привязку
          $catToCat[$catCat['category_id']]=$catCat['id'];
        }
        // die(print_r($catToCat, true));

      }
      //записываем каталог
      $sqlBody="SELECT `id`, `slug`, `name`, `parents_id` FROM `category` WHERE `id`<>135 AND `id`<>227 /*AND `is_public`=1*/ ".
      (!$isShowServiceAll ? "AND `slug`<>'service_all' " : "") .
      " ORDER BY `id`";
      $result = $q->execute($sqlBody);
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $catalog=$result->fetchAll();
      $category['name'] = str_ireplace('Служебная ', '', $category['name']);
      foreach ($catalog as $category) {
        $content .= '<category id="' . $category['id'] .'" ';
        if($params['is_show_name']) $content .= 'name="'.$category['name'].'"';
        $catSlug  = mb_strtolower($category['slug']);

        if($isShowUrl){
          if(mb_stripos($catSlug, 'service-', 0, 'utf-8')===false)
            $content .= ' url="https://'.$this->SITE_NAME.'/category/'. $catSlug .'"';
          else{
            $content .= ' url="https://'.$this->SITE_NAME.'/catalog/'. str_replace('service-', '', $catSlug) .'"';
          }
        }
        if($catToCat[$category['id']]==$category['id'])//Категория не может быть родителем самой себе. Все сервисные категории являются корневыми родителями
          unset($catToCat[$category['id']]);
        if($isShowParent && ($category['parents_id'] || isset($catToCat[$category['id']])))
          $content .= ' parentId="'.($category['parents_id'] ? $category['parents_id'] : $catToCat[$category['id']]).'"';
        $content .= '>';
        $content .=  $category['name'];
        $content .= '</category>'.$nl;
      }

      $content .= '</categories>'.$nl;
      return $content;
    }

    private function prepareText($text, $isName = false, $notStipTags=false) {
        if(!$notStipTags) $text = strip_tags($text);
        if(!$notStipTags) $text = htmlspecialchars($text);
        // if ($isName) $text = str_ireplace([' – в ассортименте', ' - в ассортименте', 'в ассортименте'], '', $text);
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
          'new! ',
          'new',
          'аналог',
          'заказ',
          ' – в ассортименте',
        ];
        if(!$isName){
          $text = str_ireplace($wordsToRemove, '', $text);
        }
        $re = '/(class="[\S\s]+")|(<\/a>)|(<a[\S\s]+>)|(<\/div>)|(<div[\S\s]+>)|(id="[\S\s]+")|(style="[\S\s]+")/mU';
        $text=preg_replace($re, '', $text);
        return trim($text);
    }

    private function getOffer($product, $q, $mods=[]){
      // $mods['photos_count'] //количество фото
      // $mods['props_to_show'] //Свойства к показу
      // $mods['props_to_hide'] //Свойства которые пропускаем
      // $mods['props_to_select'] //Свойства которые выбираем
      // $mods['utm'] //UTM метка
      // $mods['store_discount_vendors'] //Формировать список промокодов для производителей
      // $mods['not_add_id_in_utm'] //Не добавлять id предложения в UTM
      // $mods['show_compressed_img'] //Не добавлять id предложения в UTM

      $nl=$this->nl;

      $yamarketVendorModel=(isset($product['yamarket_clothes']) && $product['yamarket_clothes'] && $product['yamarket_model'] && $product['yamarket_typeprefix']);
      $content = '<offer id="' . $product['id'] . '" available="'.($product['count'] > 0 ? 'true' : 'false')
        .($yamarketVendorModel ? ' type="vendor.model"' : '')
        .'">'.$nl;
      $utm= $mods['utm'];
      if($utm && !isset($mods['not_add_id_in_utm'])) $utm.=$product['id'];
      $content .= '<url>https://' . $this->SITE_NAME . '/product/' . str_replace('&', '&amp;',$product['slug']) . $utm. '</url>'.$nl;
      $content .= '<price>'.$product['price'].'</price>'.$nl;
      if($product['old_price'] && $product['old_price'] > $product['price']) $content .= '<oldprice>'.$product['old_price'].'</oldprice>'.$nl;
      $content .= '<currencyId>RUR</currencyId>'.$nl;
      $content .= '<categoryId>' . $product['generalcategory_id'] . '</categoryId>'.$nl;
      if ($yamarketVendorModel) {
          $content .= '<market_category>' . $product['yamarket_category'] . '</market_category>';
          $content .= '<model>' . $product['yamarket_model'] . '</model>';
          $content .= '<typePrefix>'.$product['yamarket_typeprefix'].'</typePrefix>';
          $content .= '<param name="Цвет">' . $product['yamarket_color'] . '</param>'
           . ($product['yamarket_typeimg'] != "" ? ('<param name="Тип рисунка">' . $product['yamarket_typeimg'] . '</param>') : "") .
           '<param name="Пол">' . $product['yamarket_sex'] . '</param>'.$nl.
           '<param name="Возраст">Взрослый</param>'.$nl;
      }
      $content .= $this->getPhotos($product['id'], $mods['photos_count'], $q, isset($mods['show_compressed_img']));
      $content .= '<delivery>true</delivery>'.$nl;
      if (!$yamarketVendorModel) {
        $content .= '<name>' . $this->prepareText($product['name']) . '</name>'.$nl;
      }

      $propertyToShow=$mods['props_to_show'];
      $propertyToHide=$mods['props_to_hide'];
      $propertyToSelect=$mods['props_to_select'];

      $content .= $this->getProps($q, $product['id'], $propertyToShow, $propertyToHide, $propertyToSelect, (isset($mods['store_discount_vendors']) && $mods['store_discount_vendors']));
      if(!isset($mods['use_id_as_vendorcode']) || !$mods['use_id_as_vendorcode']) $content .= '<vendorCode>' . $this->prepareText($product['code']) . '</vendorCode>'.$nl;
      else $content .= '<vendorCode>' . $product['id'] . '</vendorCode>'.$nl;// id1C??
      $content .= '<param name="Артикул">' . $this->prepareText($product['code']) . '</param>'.$nl;
      $content .= '<description><![CDATA[' . $this->prepareText($product['content']) . ']]></description>'.$nl;
      if(isset($product['rating'])){
        $rating = round((($product['rating'] > 0 && $product['votes_count']>0) ? @round($product['rating'] / $product['votes_count']) : 0) / 2);
        if($rating > 5) $rating = 5;
        if($rating) $content .= '<param name="Рейтинг">'.$rating.'</param>';
      }
      // if($product['id']=='27400') echo(print_r(['persent_bonus_add'=>$persent_bonus_add, 'persent_bonus_pay'=> $persent_bonus_pay, 'bonus'=> $bonus, 'product'=>$product], true));
      // if(isset($product['bonus'])){
      if(array_key_exists ('bonus', $product)){
        // if($product['id']=='27400') echo('in||'.print_r(['persent_bonus_add'=>$persent_bonus_add, 'persent_bonus_pay'=> $persent_bonus_pay, 'bonus'=> $bonus, 'product'=>$product], true));
        $persent_bonus_add=csSettings::get('persent_bonus_add');
        $persent_bonus_pay=csSettings::get('PERSENT_BONUS_PAY');
        $bonus = round(
          // (
          $product['price'] // - $product['price'] * ($product['bonuspay'] > 0 ? $product['bonuspay'] : $persent_bonus_pay) / 100)
          *
          ((1*$product['bonus'] > 0 ? $product['bonus'] : $persent_bonus_add) / 100)
        );

        if($bonus) $content .= '<param name="Бонус">'.$bonus.'</param>';
      }

      if ($product['barcode']) {
        $content .= '<barcode>'.$product['barcode'].'</barcode>'.$nl;
      }
      if ($product['adult']) {
        $content .= '<adult>true</adult>'.$nl;
      }
      if (isset($product['is_express']) && $product['is_express'])
        $content .= '<param name="express_delivery"></param>';

      $content .= '</offer>'.$nl;
      return $content;
    }

    private function stripeProducts($text){
      $re = '/(class="[\S\s]+")|(id="[\S\s]+")|(style="[\S\s]+")|({products:[\S\s]+})/mU';
      $text=preg_replace($re, '', $text);
      $text=str_ireplace('src="/','src="https://onona.ru/' , $text);
      $text=str_ireplace('href="/','href="https://onona.ru/' , $text);
      $text=str_ireplace('http://onona.ru','https://onona.ru' , $text);
      return $text;
    }

    private function echoUrl($url, $priority = false, $lastmod = false){
      $nl=$this->nl;
      $slug=str_replace('&', '&amp;', $url);
      $slug=mb_strtolower($slug, 'utf-8');

      $ret = "<url>".
      "<loc>https://onona.ru/" . $slug . "</loc>".
      "<changefreq>weekly</changefreq>";
      if($lastmod !== false){
        $lastmod = strtotime($lastmod);
        $ret.= "<lastmod>" . date('Y-m-d', $lastmod) . "</lastmod>";
      }
      if($priority !== false)
        $ret.= "<priority>$priority</priority>";
      $ret.= "</url>".$nl;
      return $ret;
    }

    protected function execute($arguments = array(), $options = array()) {
      ini_set("max_execution_time", 3000);
      $databaseManager = new sfDatabaseManager($this->configuration);
      $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $this->SITE_NAME="onona.ru";
      $xml_name = 'Он и Она';
      $xml_company = 'ONONA';

      $localPath=str_replace('lib/task', '', __DIR__).'web/';
      if ($options['env']=='dev'){
        $nl="\n";
        $this->isTest=true;
      }
      else {
        $this->isTest= false;
        $nl='';
      }
      $this->nl=$nl;

      switch ($options['mode']){
        case '1c':
          $this->nl = "\n";
          $filename="1c";
          if($options['full']!='y') $options['full']=false;
          if(!$options['full']) $filename.='_short';
          // echo $filename."\n";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';
          $content='<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;
          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;
          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q, true, false, true, ['is_show_name' => true]);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = $this->getDopCats($q);
          file_put_contents($tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='';
          $countWhere=$options['full'] ? '' : " AND `count` > 0 ";
          $sqlBody=
            "SELECT * "
            ."FROM `product` "
            ."WHERE `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 "
            .$countWhere
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($product=$result->fetch()){
            $content=$this->getOfferFor1C($product, $q);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);

            if($this->isTest && $i++>5) break;
          }

          $content = '</offers>'.$nl;

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          // die("1c mode detected\n".($options['full'] ? 'full mode on' : 'full mode off')."\n");
          break;

        case 'coupons':
          $filename='/../coupons.csv';
          $sqlBody="SELECT * FROM `coupons` ORDER BY `id`";
          $result=$q->execute($sqlBody);
          $table=$result->fetchAll(Doctrine_Core::FETCH_ASSOC);
          $arr[]=implode('|',array_keys($table[0]));
          foreach ($table as $line) {
            $arr[]=implode('|', $line);
          }
          file_put_contents($localPath.$filename, implode("\n", $arr));
          die("success\n");
          die(print_r($arr, true));
          break;

        case 'tiu':
          $filename="tiu";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';
          $content = '<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '';

          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;

          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q, true);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $brandsList=[
            1240 => 'Satisfyer, Германия',
            1828 => 'Amor El',
            844=> 'JO system, США',
            1296 => 'Shots toys, Нидерланды',
            1797 => 'Shots Toys', //Коллекция, но пофиг
            1406 => 'REAL',
            841 => 'California exotics',//
          ];

          $sqlBody="SELECT `product_id` FROM `dop_info_product` WHERE `dop_info_id` IN(".implode(', ', array_keys($brandsList)).")";
          $result=$q->execute($sqlBody);
          $prIds=array_keys($result->fetchAll(Doctrine_Core::FETCH_UNIQUE));
          $prIds[]=-1;//Чтобы не был пустым IN

          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
              ."`yamarket_clothes`, `yamarket_category`, `yamarket_model` "
              ."`yamarket_typeimg`, `yamarket_sex`"
            ."FROM `product` p "
            ."WHERE `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 "
            ."AND p.`id` IN(".implode(', ', $prIds).") "
            ."";

          // die(print_r($sqlBody, true));
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $utm='';
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 1,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
              ],
              'props_to_hide' => [
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
              ],
              'utm' => '',
              'show_compressed_img' => true,
              'use_id_as_vendorcode' => true,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            // if($this->isTest && $i++>5) break;
          }
          $content = '</offers>'.$nl;

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'sber_goods':
          $filename="sber";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';
          $content = '<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '';

          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;

          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q, true);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $brandsList=[
            1240 => 'Satisfyer, Германия',
            1828 => 'Amor El',
            844=> 'JO system, США',
            1296 => 'Shots toys, Нидерланды',
            1797 => 'Shots Toys', //Коллекция, но пофиг
            1406 => 'REAL',
          ];
          $sqlBody="SELECT `product_id` FROM `dop_info_product` WHERE `dop_info_id` IN(".implode(', ', array_keys($brandsList)).")";
          $result=$q->execute($sqlBody);
          $prIds=array_keys($result->fetchAll(Doctrine_Core::FETCH_UNIQUE));
          $prIds[]=-1;//Чтобы не был пустым IN

          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
              ."`yamarket_clothes`, `yamarket_category`, `yamarket_model` "
              ."`yamarket_typeimg`, `yamarket_sex`"
            ."FROM `product` p "
            ."WHERE `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 "
            ."AND p.`id` IN(".implode(', ', $prIds).") "
            ."";

            //507 это id категории экспресс. для нее выводится фиксированный параметр для retailRocket

          // die(print_r($sqlBody, true));
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $utm='';
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 1,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
              ],
              'props_to_hide' => [
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
              ],
              'utm' => '',
              'show_compressed_img' => true,
              'use_id_as_vendorcode' => true,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            // if($this->isTest && $i++>5) break;
          }
          $content = '</offers>'.$nl;

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'task_158522':
          $filename="yaxml_special";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';
          $inputFile=$localPath.'products-to-feed.csv';
          $delimiter="\t";
          $productsLines=explode("\n", file_get_contents($inputFile));
          if(empty($productsLines)) die('Нет файла'."\n");
          foreach ($productsLines as $key => $line) {
            if(!$key) continue;
            $line=explode($delimiter, $line);
            if($line[0]) $codes[]="'".iconv('CP1251', 'UTF-8', trim($line[0]))."'";
          }
          $codes=implode(', ', $codes);

          $content = '<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;

          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;

          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=';
          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
            ."`yamarket_clothes`, `yamarket_category`, `yamarket_model`, `bonuspay`, `yamarket_color`, "
            ."`yamarket_typeimg`, `yamarket_sex` "
            ."FROM `product` "
            ."WHERE `yamarket`=1 AND `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 AND `code` IN($codes) "
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 10,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
              ],
              'props_to_hide' => [
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
              ],
              'props_to_select' => false,
              'utm' => $utm,
              'store_discount_vendors' => true,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }
          $content = '</offers>'.$nl;

          $content .= sizeof($this->promoItems) ? $this->getPromos($this->promoItems) : '';

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          break;

        case 'image_sitemap':
          $filename="image_sitemap";

          $sqlBody=
            "SELECT `id`, `slug`, `name`, `h1` "
            ."FROM `product` "
            ."WHERE `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 "
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $part=$i=0;
          while($product=$result->fetch()){
            if(!($i++%5000)){
              if($part){//закрываем часть
                $content ="</urlset>";
                file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);
              }
              $tmpFilename= substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_part_'.$part++.'.tmp';
              $tmpFilenames[]=$tmpFilename;

              $content='<?xml version="1.0" encoding="UTF-8"?>'.$nl.
              '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"  xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'.$nl;
              file_put_contents( $localPath.$tmpFilename, $content);
              // die("i is a $i\n");
            }
            $content = $this->getXmlPhotos($product, $q);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

            // if($this->isTest && $i++>5) break;
          }

          $content ="</urlset>";
          file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);
          $filename='onona_'.$filename.'.xml';
          $content2='<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$nl;
          file_put_contents( $localPath.$filename, $content2);
          foreach ($tmpFilenames as $key => $value) {
            $filenamePart=explode('.', $value)[0];
            // die(print_r([$value, $filenamePart, $filename], true));
            // $filenamePartShort=end(explode('/', $filenamePart));
            rename ($localPath.$value, $localPath.$filenamePart.'.xml');
            $content2='<sitemap>'.$nl.
            '<loc>https://onona.ru/'.$filenamePart.'.xml</loc>'.$nl.
            '<lastmod>'.date(DATE_ATOM, time()).'</lastmod>'.$nl.
            '</sitemap>'.$nl;
            file_put_contents( $localPath.$filename, $content2, FILE_APPEND);
          }
          $content2='</sitemapindex>';
          file_put_contents( $localPath.$filename, $content2, FILE_APPEND);
          if($this->isTest) echo "image_sitemap success run\n";
          die();
          break;

        case 'anyquery':
          $filename="anyquery";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';
          $content='<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;
          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;
          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q, false, true);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='';

          //записываем предложения
          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c` "
            ."FROM `product` "
            ."WHERE `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 "
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 1,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
                // 'Батарейки', 'Особенность', 'Аромат', 'Свойство', 'Тип'
              ],
              'props_to_hide' => [
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
              ],
              'props_to_select' => false,
              'utm' => $utm,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);

            if($this->isTest && $i++>5) break;
          }

          $content = '</offers>'.$nl;

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'admitad':
          $filename="admitad";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';
          $content='<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;
          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;
          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q, false, true);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='';

          //записываем предложения
          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c` "
            ."FROM `product` "
            ."WHERE `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 "
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 10,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
                // 'Батарейки', 'Особенность', 'Аромат', 'Свойство', 'Тип'
              ],
              'props_to_hide' => [
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
              ],
              'props_to_select' => false,
              'utm' => $utm,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);

            if($this->isTest && $i++>5) break;
          }

          $content = '</offers>'.$nl;

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'rrmarket':
          $filename="rryaxml";

          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';
          $content='<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;
          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />'.$nl;
          $content .= '</currencies>'.$nl;
          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q, true);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='';

          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
              ."`yamarket_clothes`, `yamarket_category`, `yamarket_model`, `rating`, `votes_count`, `bonuspay`, `yamarket_color`, "
              ."`yamarket_typeimg`, `yamarket_sex`, `bonus`, cp.`product_id` as `is_express` "
            ."FROM `product` p "
            ."LEFT JOIN `category_product` cp ON (cp.`product_id` = p.`id` AND cp.`category_id` = 507) "
            ."WHERE `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 "
            ."";

            //507 это id категории экспресс. для нее выводится фиксированный параметр для retailRocket

          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 1,
              'props_to_show' => [
                'Размер',
                // 'Материал',
              ],
              'props_to_hide' => [],
              'props_to_select' => [
                'Производитель',
                'Бренд(ы)',
                'Бренд',
                'Размер',
                'Материал',
              ],
              'utm' => '',
              'show_compressed_img' => true,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }
          $content = '</offers>'.$nl;

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'sitemap':
          $filename="sitemap";
          $tmpFilename= substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_categories.tmp';
          $tmpFilenames[]=$tmpFilename;

          $content="<?xml version=\"1.0\" encoding=\"UTF-8\"?>".$nl.
            "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">".$nl
          ;

          file_put_contents( $localPath.$tmpFilename, $content);
          // die('line '.__LINE__."\n".print_r([$localPath.$tmpFilename, $content], true));

          $sqlBody="SELECT `slug`, `updated_at` FROM `category` WHERE `id`<>135 AND `is_public`=1 AND `slug` NOT LIKE 'service_%' ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            if(mb_stripos($category['slug'], 'express_')!==false)
              $content = $this->echoUrl('category/express/'. $category['slug'], '0.6', $category['updated_at']);
            elseif(mb_stripos($category['slug'], 'vse-po-')!==false)
              $content = $this->echoUrl('category/vse-po/'. $category['slug'], '0.6', $category['updated_at']);
            else
              $content = $this->echoUrl('category/'. $category['slug'], '0.6', $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          }

          $sqlBody="SELECT `slug`, `updated_at` FROM `catalog` WHERE `is_public`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('catalog/'. $category['slug'], '0.6', $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          }

          $sqlBody="SELECT `slug`, `updated_at` FROM `collection` WHERE `is_public`=1 AND id<>833 AND `slug`<>'833' ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('collection/'. $category['slug'], '0.6', $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          }

          $sqlBody="SELECT `slug`, `updated_at` FROM `manufacturer` WHERE `is_public`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('manufacturer/'. $category['slug'], '0.6', $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          }
          $content = '</urlset>';
          file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          $tmpFilename= substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_pages.tmp';
          $tmpFilenames[]=$tmpFilename;

          $content="<?xml version=\"1.0\" encoding=\"UTF-8\"?>".$nl.
            "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">".$nl;

          $content .= $this->echoUrl('','1.0');
          file_put_contents( $localPath.$tmpFilename, $content);


          $sqlBody="SELECT `slug`, `sitemapRate`, `updated_at` FROM `page` WHERE `is_public`=1 AND `city_id` IS NULL ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          $excludesList=[
            'IzmaYlovskoe_shosse',
            'Nosovihinskoe_shosse',
            'LigovskiY_prospekt',
            'magazin-dlya-vzroslyh-on-i-ona-v-g-pushkino',
            'set-magazinov-dlya-vzroslyh-on-i-ona-v-g-krasnodar',
            'banner-nad-shapkoi',
            'Adresa_magazinov_v_Rossii',
            'dostavka-i-oplata',
            'articleTest',
            'programma-on-i-ona-bonus-1',
            'podarochnaya_upakovka',
            'programma-on-i-ona-bonus2',
            'lk',
            'login',
            'error404',
            'mainNew',
            'mainNewDis',
            'sexshop',
            'main',
            'ulitsa_SHirokaya',
            'magazin-on-i-ona-v-moskve-schitnikovo',
            'magazin-balashiha-kvartal-shhitnikovo-46-a',
            'dostavka_pickpoint',
            'priglashaem-aktrisu',
            'pokupaem-video-kreativ',
            'dzho-darit-podarki',
            'muzhskoi-den',
            'darim-udovolstvie-ot-pokupok',
            'confidecialn',
            'den-studenta',
            'chernaya-pyatnica-2019',
            'soobschenie-otpravleno',
            'poslednyaya-akciya-leta',
            'ip_denied',
            'podarki-pri-pokupke',
            'podgotovtes-zaranee',
            'koshmarnaya-pyatnica-dlya-fantazii',
            'otzyvy-na-yandeks-markete',
            'skidka-po-temperature',
            'den-rozhdeniya-on-i-ona',
            'success_payment',
            'treningi-shig',
            'treningi-shig-2',
            'rozygrysh-sertifikatov',
            'kiberponedelnik',
            'ispolnyaem-novogodnie-mechty-2015-bonusov-v-podarok-akciya-30',
            'skidka-na-vse-20',
            'tatyanin-den',
            'den-rozhdenie-roznicy',
            'vebinar',
            'noch-helluina',
            'ozon-podarok',
            '8_marta',
            'tretii-tovar-za-odin-rubl',
            'skidka-na-vtoroi-tovar',
            'darim-vibrator',
            'leninskii',
            'podarok-pri-pokupke',
            'sluchainye-skidki',
            'legendarnaya-akciya',
            'skidka-na-vsu-kollekciu-dlya-par',
            'platim-za-kreativ',
            'skidka-pri-pokupke-ot-4990',
            'den-spontannoi-dobroty',
            'x-show_2012',
            'aktivatsiya_skidki',
            'grafik-raboty-magazinov-on-i-ona-2015-2016',
            'dzho-darit-skidku',
            'sex-igrushki',
            // 'collection/833',
            'momentalnaya-dostavka',
            'skidka-na-vtoroi-tovar-50-procentov',
            'shkola-intimnoi-garmonii',
            'newshop_kutuzovskiy26',
            'frunzenskaya',
            'novogodnii-on-i-ona',
            'trening-v-podarok',
            'xshow2013',
            'novogodnyaya-akciya-podarki-vsem',
            'pustaya-stranica-spiska-zhelanii',
            '30-procentov-na-vse',
            'skidka-15-za-foto',
            'kiberdni-nachalis',
            'grafik-raboty-magazinov-on-i-ona-v-novogodnie-prazdniki-2014-2015',
            'nedelya-kategoriinyh-skidok',
            'skidki_ko_dnu_vlublennyh',
            'den-vlublennyx',
            'novogodnyaya-rasprodazha-onona-ru',
            'fail_payment',
            'stranica-nachisleniya-bonusov-posle-pokupki-v-magazine',
            'pozdravlyaem-s-nastupauschim-2020-godom',
            'stranica-pustoi-korziny',
            'tretii-tovar',
            'skidka-na-muzhskuu-kollekciu',
            'besplatnaya-dostavka-po-vsei-rossii',
            'kupony-na-skidku',
            'shots',
            'vazhnaya-informaciya-blok-bonusov-na-stranice-korziny',
            'kiberponedelnik-2020',
            'darim-bonusy',
            'chernaya-pyatnica',
            'tekst-stranicy-registracii',
            'konsultatsia_seksologa-2',
            'satisfaer-20-na-vakumnye',
            '1_aprelja',
            'den-lubvi-semi-i-vernosti',
            'avtorizaciya-soc-seti',
            'bonus',
            'pozdravlyaem-vas-s-novym-2014-godom',
            'skidka-20-na-pervyi-zakaz',
            'shapka-spiska-magazinov-on-i-ona',
            'tehpodderzhka-pomosch-v-reshenii-problem',
            'marina-roscha',
            'vopros-seksologu-2',
            'dostavka_qiwi_post',
            'varshavskii',
            'konsultatsia_seksologa',
            'darim-kupony',
            'v-podarok',
            'den-orgazma',
            'tehpodderzhka-obraschenie-otpravleno',
            'yandeks-karty',
            '1000-bonusov',
            'spasibo-za-registraciu',
            '3-tovar-besplatno',
            'rozygrysh-sertifikatov-1',
            '5-kreativnyh-idei-dlya-eroticheskoi-stimulyacii',
            'noch-zhenschin-v-norvegii',
            'skidka-na-kollekcii-ot-california-exotics',
            'kiberponedelnik-skidka-30-na-ves-assortiment',
            'realistiki-vakum-upakovka',
            'kutuzovskii',
            'videoorder',
            'shok-pokupaesh-odin-tovar-vtoroi-absolutno-besplatno',
            'ozon-skidka',
            'nashi-preimuschestva',
            'novogodnie_skidki',
            'vivaib',
            'skidki-na-vse-tolko-2-dnya',
            'pravila-razmescheniya-foto',
            'payment_cancel',
            'diskontnaya_karta',
            'dogovor-oferta',
            'dostavka_pickpoint',
            'personal_accept',
            'podarochnye_sertifikaty',
            'rassylka',
            'subscription',
            'video',
            'main_new'
          ];

          while($category=$result->fetch()){
            if (in_array(mb_strtolower($category['slug']), $excludesList)) continue;
            $content = $this->echoUrl($category['slug'],($category['sitemap_rate'] ? $category['sitemap_rate'] : '0.6'), $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          }

          $sqlBody="SELECT `slug`, `updated_at` FROM `shops` WHERE `is_active`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('shops/'. $category['slug'], '0.6', $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          }

          $sqlBody="SELECT `slug`, `updated_at` FROM `tests` WHERE `is_public`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('tests/'. $category['slug'], '0.6', $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          }

          $sitemap = $this->echoUrl("sexopedia" , '0.6');
          file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          $sqlBody="SELECT `slug`, `updated_at` FROM `articlecategory` WHERE `is_public`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('sexopedia/category/'. $category['slug'], '0.6', $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);
          }

          $sqlBody="SELECT `slug`, `updated_at` FROM `article` WHERE  `is_public`=1 AND `slug`!='vybiraem-maslo-dlya-eroticheskogo-massazha' ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('sexopedia/'. $category['slug'], '0.6', $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          }
          $content = '</urlset>';
          file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          $part=$i=0;

          $sqlBody="SELECT `slug`, `updated_at` FROM `product` WHERE `is_public`=1 AND `generalcategory_id`<>135 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($category=$result->fetch()){
            if(!($i++%5000)){
              if($part){//закрываем часть
                $content ="</urlset>";
                file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);
              }
              $tmpFilename= substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_products_part_'.$part++.'.tmp';
              $tmpFilenames[]=$tmpFilename;

              $content='<?xml version="1.0" encoding="UTF-8"?>'.$nl.
              '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"  xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'.$nl;
              file_put_contents( $localPath.$tmpFilename, $content);
              // die("i is a $i\n");
            }

            $content = $this->echoUrl('product/'. $category['slug'], '0.6', $category['updated_at']);
            file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          }

          $content = '</urlset>';
          file_put_contents( $localPath.$tmpFilename, $content, FILE_APPEND);

          $filename='sitemap.xml';

          $content2='<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$nl;
          file_put_contents( $localPath.$filename, $content2);

          foreach ($tmpFilenames as $key => $value) {
            $filenamePart=explode('.', $value)[0];
            // die(print_r([$tmpFilenames, $value, $filenamePart, $filename], true));
            // $filenamePartShort=end(explode('/', $filenamePart));
            rename ($localPath.$value, $localPath.$filenamePart.'.xml');
            $content2='<sitemap>'.$nl.
            '<loc>https://onona.ru/'.$filenamePart.'.xml</loc>'.$nl.
            '<lastmod>'.date(DATE_ATOM, time()).'</lastmod>'.$nl.
            '</sitemap>'.$nl;
            file_put_contents( $localPath.$filename, $content2, FILE_APPEND);
          }
          $content2='</sitemapindex>';
          file_put_contents( $localPath.$filename, $content2, FILE_APPEND);
          if($this->isTest) echo "sitemap success run\n";
          die();

          break;

        case 'sitemap_old':
          $filename="sitemap";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';
          $content="<?xml version=\"1.0\" encoding=\"UTF-8\"?>".
            "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">".
          $this->echoUrl('','1.0');
          $sitemap = $this->echoUrl("sexopedia" , '0.6');

          file_put_contents( $tmpFilename, $content);
          $sqlBody="SELECT `slug` FROM `category` WHERE `id`<>135 AND `is_public`=1 AND `slug` NOT LIKE 'service_%' ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            if(mb_stripos($category['slug'], 'express_')!==false)
              $content = $this->echoUrl('category/express/'. $category['slug'], '0.6');
            else
              $content = $this->echoUrl('category/'. $category['slug'], '0.6');
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $sqlBody="SELECT `slug` FROM `catalog` WHERE `is_public`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('catalog/'. $category['slug'], '0.6');
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $sqlBody="SELECT `slug` FROM `collection` WHERE `is_public`=1 AND id<>833 AND `slug`<>'833' ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('collection/'. $category['slug'], '0.6');
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $sqlBody="SELECT `slug` FROM `manufacturer` WHERE `is_public`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('manufacturer/'. $category['slug'], '0.6');
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $sqlBody="SELECT `slug` FROM `tests` WHERE `is_public`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('tests/'. $category['slug'], '0.6');
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $sqlBody="SELECT `slug` FROM `product` WHERE `is_public`=1 AND `generalcategory_id`<>135 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('product/'. $category['slug'], '0.6');
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $sqlBody="SELECT `slug`, `sitemapRate` FROM `page` WHERE `is_public`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          $excludesList=[
            'IzmaYlovskoe_shosse',
            'Nosovihinskoe_shosse',
            'LigovskiY_prospekt',
            'magazin-dlya-vzroslyh-on-i-ona-v-g-pushkino',
            'set-magazinov-dlya-vzroslyh-on-i-ona-v-g-krasnodar',
            'banner-nad-shapkoi',
            'Adresa_magazinov_v_Rossii',
            'dostavka-i-oplata',
            'articleTest',
            'programma-on-i-ona-bonus-1',
            'podarochnaya_upakovka',
            'programma-on-i-ona-bonus2',
            'lk',
            'login',
            'error404',
            'mainNew',
            'mainNewDis',
            'sexshop',
            'main',
            'ulitsa_SHirokaya',
            'magazin-on-i-ona-v-moskve-schitnikovo',
            'magazin-balashiha-kvartal-shhitnikovo-46-a',
            'dostavka_pickpoint',
            'priglashaem-aktrisu',
            'pokupaem-video-kreativ',
            'dzho-darit-podarki',
            'muzhskoi-den',
            'darim-udovolstvie-ot-pokupok',
            'confidecialn',
            'den-studenta',
            'chernaya-pyatnica-2019',
            'soobschenie-otpravleno',
            'poslednyaya-akciya-leta',
            'ip_denied',
            'podarki-pri-pokupke',
            'podgotovtes-zaranee',
            'koshmarnaya-pyatnica-dlya-fantazii',
            'otzyvy-na-yandeks-markete',
            'skidka-po-temperature',
            'den-rozhdeniya-on-i-ona',
            'success_payment',
            'treningi-shig',
            'treningi-shig-2',
            'rozygrysh-sertifikatov',
            'kiberponedelnik',
            'ispolnyaem-novogodnie-mechty-2015-bonusov-v-podarok-akciya-30',
            'skidka-na-vse-20',
            'tatyanin-den',
            'den-rozhdenie-roznicy',
            'vebinar',
            'noch-helluina',
            'ozon-podarok',
            '8_marta',
            'tretii-tovar-za-odin-rubl',
            'skidka-na-vtoroi-tovar',
            'darim-vibrator',
            'leninskii',
            'podarok-pri-pokupke',
            'sluchainye-skidki',
            'legendarnaya-akciya',
            'skidka-na-vsu-kollekciu-dlya-par',
            'platim-za-kreativ',
            'skidka-pri-pokupke-ot-4990',
            'den-spontannoi-dobroty',
            'x-show_2012',
            'aktivatsiya_skidki',
            'grafik-raboty-magazinov-on-i-ona-2015-2016',
            'dzho-darit-skidku',
            'sex-igrushki',
            // 'collection/833',
            'momentalnaya-dostavka',
            'skidka-na-vtoroi-tovar-50-procentov',
            'shkola-intimnoi-garmonii',
            'newshop_kutuzovskiy26',
            'frunzenskaya',
            'novogodnii-on-i-ona',
            'trening-v-podarok',
            'xshow2013',
            'novogodnyaya-akciya-podarki-vsem',
            'pustaya-stranica-spiska-zhelanii',
            '30-procentov-na-vse',
            'skidka-15-za-foto',
            'kiberdni-nachalis',
            'grafik-raboty-magazinov-on-i-ona-v-novogodnie-prazdniki-2014-2015',
            'nedelya-kategoriinyh-skidok',
            'skidki_ko_dnu_vlublennyh',
            'den-vlublennyx',
            'novogodnyaya-rasprodazha-onona-ru',
            'fail_payment',
            'stranica-nachisleniya-bonusov-posle-pokupki-v-magazine',
            'pozdravlyaem-s-nastupauschim-2020-godom',
            'stranica-pustoi-korziny',
            'tretii-tovar',
            'skidka-na-muzhskuu-kollekciu',
            'besplatnaya-dostavka-po-vsei-rossii',
            'kupony-na-skidku',
            'shots',
            'vazhnaya-informaciya-blok-bonusov-na-stranice-korziny',
            'kiberponedelnik-2020',
            'darim-bonusy',
            'chernaya-pyatnica',
            'tekst-stranicy-registracii',
            'konsultatsia_seksologa-2',
            'satisfaer-20-na-vakumnye',
            '1_aprelja',
            'den-lubvi-semi-i-vernosti',
            'avtorizaciya-soc-seti',
            'bonus',
            'pozdravlyaem-vas-s-novym-2014-godom',
            'skidka-20-na-pervyi-zakaz',
            'shapka-spiska-magazinov-on-i-ona',
            'tehpodderzhka-pomosch-v-reshenii-problem',
            'marina-roscha',
            'vopros-seksologu-2',
            'dostavka_qiwi_post',
            'varshavskii',
            'konsultatsia_seksologa',
            'darim-kupony',
            'v-podarok',
            'den-orgazma',
            'tehpodderzhka-obraschenie-otpravleno',
            'yandeks-karty',
            '1000-bonusov',
            'spasibo-za-registraciu',
            '3-tovar-besplatno',
            'rozygrysh-sertifikatov-1',
            '5-kreativnyh-idei-dlya-eroticheskoi-stimulyacii',
            'noch-zhenschin-v-norvegii',
            'skidka-na-kollekcii-ot-california-exotics',
            'kiberponedelnik-skidka-30-na-ves-assortiment',
            'realistiki-vakum-upakovka',
            'kutuzovskii',
            'videoorder',
            'shok-pokupaesh-odin-tovar-vtoroi-absolutno-besplatno',
            'ozon-skidka',
            'nashi-preimuschestva',
            'novogodnie_skidki',
            'vivaib',
            'skidki-na-vse-tolko-2-dnya',
            'pravila-razmescheniya-foto',
            'payment_cancel',
            'diskontnaya_karta',
            'dogovor-oferta',
            'dostavka_pickpoint',
            'personal_accept',
            'podarochnye_sertifikaty',
            'rassylka',
            'subscription'
          ];

          while($category=$result->fetch()){
            if (in_array(mb_strtolower($category['slug']), $excludesList)) continue;
            $content = $this->echoUrl($category['slug'],($category['sitemap_rate'] ? $category['sitemap_rate'] : '0.6'));
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $sqlBody="SELECT `slug` FROM `articlecategory` ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('sexopedia/category/'. $category['slug'], '0.6');
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $sqlBody="SELECT `slug` FROM `article` WHERE `slug`!='vybiraem-maslo-dlya-eroticheskogo-massazha' ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('sexopedia/'. $category['slug'], '0.6');
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $sqlBody="SELECT `slug` FROM `shops` WHERE `is_active`=1 ORDER BY `id`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $i=0;
          while($category=$result->fetch()){
            $content = $this->echoUrl('shops/'. $category['slug'], '0.6');
            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }

          $content = '</urlset>';
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'yamarketturbo':
          $filename="yaxml_turbo";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';

          $content = '<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;

          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;

          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='';
          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=5211';
          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
            ."`yamarket_clothes`, `yamarket_category`, `yamarket_model`, `bonuspay`, `yamarket_color`, "
            ."`yamarket_typeimg`, `yamarket_sex` "
            ."FROM `product` "
            ."WHERE `yamarket`=1 AND `is_public`=1 AND `price` > 0 AND `count` > 0 AND `generalcategory_id`<>135"
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 10,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
              ],
              'props_to_hide' => [
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
              ],
              'props_to_select' => false,
              'utm' => $utm,
              'store_discount_vendors' => true,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }
          $content = '</offers>'.$nl;

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          //Теперь генерируем второй файл с турбостраницами
          $filename_2="yaxml_turbopages";
          $tmpFilename_2= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename_2.'_tmp'.'.xml';
          $content =
            '<?xml version="1.0" encoding="UTF-8"?>'.$nl.
            '<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">'.$nl.
              '<channel>'.$nl.
                "<title>Сексопедия</title>"."\n".
                "<link>https://$this->SITE_NAME</link>".$nl.
                "<description>Сексопедия</description>".$nl.
                "<language>ru</language>".$nl.
                // "<turbo:analytics></turbo:analytics>".$nl.
                // "<turbo:adNetwork></turbo:adNetwork>".$nl.
            '';
          $i=0;
          file_put_contents( $tmpFilename_2, $content, FILE_APPEND);
          $sqlBody=
            "SELECT `id`, `slug`, `name`, `img`, `content`, `updated_at` "
            ."FROM `article` "
            ."WHERE `is_public`=1 "
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($article=$result->fetch()){
            $content =
              '<item turbo="true">'.$nl.
                '<link>https://'.$this->SITE_NAME.'/sexopedia/'.$article['slug'].'</link>'.$nl.
                // '<turbo:source></turbo:source>'."\n".
                // '<turbo:topic></turbo:topic>'."\n".
                '<pubDate>'.date('r', strtotime($article['updated_at'])).'</pubDate>'.$nl.
                '<author>OnOna.ru</author>'.$nl.
                // '<yandex:related></yandex:related>'."\n".
                '<turbo:content>'.$nl.
                  '<![CDATA['.
                    '<header>'.$nl.
                      '<h1>'.$article['name'].'</h1>'.$nl.
                      '';
            $img=$article['img'];
            if ($img)
              $content.=
                      '<figure><img src="https://onona.ru/uploads/photo/'.$img.'"/></figure>'.$nl;
              $content.=
                      '</header>'.$nl.
                      $this->stripeProducts($article['content']).
                    ']]>'.
                  '</turbo:content>'.$nl.
                '</item>'.$nl.
                '';
            file_put_contents( $tmpFilename_2, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }
          $content=
              '</channel>'."\n".
            '</rss>'."\n".
            '';
          file_put_contents( $tmpFilename_2, $content, FILE_APPEND);

          break;

        case 'yamarketfull':
          $filename="yaxmlfull";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';

          $content = '<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;

          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;
          
          file_put_contents( $tmpFilename, $content);
          
          $content = $this->getCatalog($q);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=5211';
          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
            ."`yamarket_clothes`, `yamarket_category`, `yamarket_model`, `bonuspay`, `yamarket_color`, "
            ."`yamarket_typeimg`, `yamarket_sex` "
            ."FROM `product` "
            ."WHERE `yamarket`=1 AND `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135"
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 1,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
              ],
              'props_to_hide' => [
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
              ],
              'props_to_select' => false,
              'utm' => $utm,
              'store_discount_vendors' => true,
              'not_add_id_in_utm' => true,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }
          $content = '</offers>'.$nl;

          $content .= sizeof($this->promoItems) ? $this->getPromos($this->promoItems) : '';

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'yamarket':
          $sqlBody="UPDATE dop_info di LEFT JOIN dop_info_category dic ON dic.id=di.dicategory_id SET di.name=dic.name WHERE di.name='' OR di.NAME IS NULL"; //Заполняем пустые имена свойств если они есть
          $result = $q->execute($sqlBody);

          $filename="yaxml";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';

          $content = '<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;

          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;
          
          file_put_contents( $tmpFilename, $content);
          
          $content = $this->getCatalog($q);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=';
          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
            ."`yamarket_clothes`, `yamarket_category`, `yamarket_model`, `bonuspay`, `yamarket_color`, "
            ."`yamarket_typeimg`, `yamarket_sex` "
            ."FROM `product` "
            ."WHERE `yamarket`=1 AND `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 AND `count` >0 "
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 10,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
              ],
              'props_to_hide' => [
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
              ],
              'props_to_select' => false,
              'utm' => $utm,
              'store_discount_vendors' => true,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }
          $content = '</offers>'.$nl;

          $content .= sizeof($this->promoItems) ? $this->getPromos($this->promoItems) : '';

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'yamarketbyprice':
          $sqlBody="UPDATE dop_info di LEFT JOIN dop_info_category dic ON dic.id=di.dicategory_id SET di.name=dic.name WHERE di.name='' OR di.NAME IS NULL"; //Заполняем пустые имена свойств если они есть
          $result = $q->execute($sqlBody);

          $ymlPartsList = [
            1 => [
              'filename' => 'yaxml_price_0_1499',
              'priceMin' => 0,
              'priceMax' => 1499,
              'content' => '',
            ],
            2 => [
              'filename' => 'yaxml_price_1500_2999',
              'priceMin' => 1500,
              'priceMax' => 2999,
              'content' => '',
            ],
            3 => [
              'filename' => 'yaxml_price_3000_5999',
              'priceMin' => 3000,
              'priceMax' => 5999,
              'content' => '',
            ],
            4 => [
              'filename' => 'yaxml_price_6000_9999',
              'priceMin' => 6000,
              'priceMax' => 9999,
              'content' => '',
            ],
            5 => [
              'filename' => 'yaxml_price_10000_999999',
              'priceMin' => 10000,
              'priceMax' => 10000000,
              'content' => '',
            ],
          ];

          foreach ($ymlPartsList as $id => $part) {
            $tmpFilename = $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$part['filename'].'_tmp'.'.xml';
            $part['tmpFilename'] = $tmpFilename;

            $content = '<?xml version="1.0" encoding="utf-8"?>'.$nl.
              '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;
            $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
            $content .= '<shop>'.$nl;
            $content .= '<name>' . $xml_name . '</name>'.$nl;
            $content .= '<company>' . $xml_company . '</company>'.$nl;
            $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
            $content .= '<currencies>'.$nl;
            $content .= '<currency id="RUR" rate="1" />';
            $content .= '</currencies>'.$nl;
            file_put_contents($tmpFilename, $content);
            
            $content = $this->getCatalog($q);
            file_put_contents($tmpFilename, $content, FILE_APPEND);

            $content = '<offers>'.$nl;
            file_put_contents($tmpFilename, $content, FILE_APPEND);

            $ymlPartsList[$id] = $part;
          }

          $i=0;
          $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=';
          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
            ."`yamarket_clothes`, `yamarket_category`, `yamarket_model`, `bonuspay`, `yamarket_color`, "
            ."`yamarket_typeimg`, `yamarket_sex` "
            ."FROM `product` "
            ."WHERE `yamarket`=1 AND `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 AND `count` >0 "
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while ($product=$result->fetch()) {
            $content = $this->getOffer($product, $q, [
              'photos_count' => 10,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
              ],
              'props_to_hide' => [
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
              ],
              'props_to_select' => false,
              'utm' => $utm,
              'store_discount_vendors' => true,
            ]);

            foreach ($ymlPartsList as $part) {
              if ($product['price'] >= $part['priceMin'] && $product['price'] <= $part['priceMax']) {
                file_put_contents($part['tmpFilename'], $content, FILE_APPEND);
              }
            }
            if($this->isTest && $i++>5) break;
          }

          foreach ($ymlPartsList as $part) {
            $content = '</offers>'.$nl;

            $content .= sizeof($this->promoItems) ? $this->getPromos($this->promoItems) : '';

            $content .= '</shop>'.$nl;
            $content .= '</yml_catalog>'.$nl;
            file_put_contents($part['tmpFilename'], $content, FILE_APPEND);
          }

          unset($tmpFilename, $filename);

          break;

        case 'ymforhim':
          $sqlBody = "UPDATE dop_info di LEFT JOIN dop_info_category dic ON dic.id=di.dicategory_id SET di.name=dic.name WHERE di.name='' OR di.NAME IS NULL"; //Заполняем пустые имена свойств если они есть
          $result = $q->execute($sqlBody);

          $filename = "ymforhim";
          $tmpFilename = $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';

          /* $category = CategoryTable::getInstance()->createQuery()->where('slug = ?', 'preparaty-dlya-nego')->execute()->get(0)->toArray();
          $categoriesIds = [$category['id']]; */
          $catalogForHim = CatalogTable::getInstance()->createQuery()->where('slug = ?', 'sex-igrushki-dlya-muzhchin')->addWhere('is_public=1')->execute()->get(0)->toArray();
          $categoriesIds = array_map(function ($item) {
            return $item['category_id'];
          }, CategoryCatalogTable::getInstance()->createQuery()->where('catalog_id = ?', $catalogForHim['id'])->execute()->toArray());

          $categoriesIds = array_unique(array_map(function ($category) {
            return $category['id'];
          }, $q->execute("SELECT `id` FROM `category` WHERE `is_public` = 1 AND `id` IN (".implode(',', $categoriesIds).")")->fetchAll()));
          $categoriesIdsStr = implode(',', $categoriesIds);

          $content = '<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;

          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;

          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=';

          $itemsIds = array_map(function ($item) {
            return $item['product_id'];
          }, $q->execute("SELECT DISTINCT(`product_id`) FROM `category_product` WHERE `category_id` IN (".$categoriesIdsStr.")")->fetchAll());

          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
            ."`yamarket_clothes`, `yamarket_category`, `yamarket_model`, `bonuspay`, `yamarket_color`, "
            ."`yamarket_typeimg`, `yamarket_sex` "
            ."FROM `product` "
            //."WHERE `yamarket`=1 AND `id` IN (".implode(',', $itemsIds).") AND `is_public`=1 AND `price` > 0 AND `generalcategory_id`<>135 AND `count` >0 "
            ."WHERE `id` IN (".implode(',', $itemsIds).") AND `is_public`=1 "
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 10,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
              ],
              'props_to_hide' => [
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
              ],
              'props_to_select' => false,
              'utm' => $utm,
              'store_discount_vendors' => true,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }
          $content = '</offers>'.$nl;

          $content .= sizeof($this->promoItems) ? $this->getPromos($this->promoItems) : '';

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'task-203880':
          $sqlBody = "SELECT id, email_address, city FROM sf_guard_user WHERE is_active = 1 AND LENGTH(city) > 0";

          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          while($row=$result->fetch()){
            $data[] = implode('}', [trim($row['id']), trim($row['email_address']), str_replace(["\n"], '', trim($row['city'])) ]);
          }
          $res = implode("\n", $data);

          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_users_city.csv';

          file_put_contents( $tmpFilename, $res);

          die("\nDone\n");

          break;

        case 'task-202769':
          $lf = file_get_contents($localPath.'list.txt');
          $list = explode("\n", $lf);
          foreach ($list as $key => $line){
            if(!$key) continue;
            $slug = trim(end(explode('/', $line)));
            $sqlBody = "SELECT id FROM product WHERE slug='".$slug."'";
            $rsProd = $q->execute($sqlBody);
            $rsProd->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $prod = $rsProd->fetch();
            $sqlBody = "SELECT `filename` FROM `product_photoalbum` pa "
            . "LEFT join `photo` p ON p.`album_id`=pa.`photoalbum_id` "
            . "WHERE pa.`product_id`=" . $prod['id'] . ' '
            . "ORDER BY p.`position` "
            . "";
            $rsPhotos = $q->execute($sqlBody);
            $rsPhotos->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $photo = $rsPhotos->fetch();
            $result[] = implode('|', [trim($line), 'https://onona.ru/uploads/photo/'.$photo['filename']]);
          }
          file_put_contents($localPath.'photos.csv', implode("\n", $result));

          break;

        case 'yamarket-limited':
          $sqlBody="UPDATE dop_info di LEFT JOIN dop_info_category dic ON dic.id=di.dicategory_id SET di.name=dic.name WHERE di.name='' OR di.NAME IS NULL"; //Заполняем пустые имена свойств если они есть
          $result = $q->execute($sqlBody);

          $filename = "yaxml-limited";
          $tmpFilename= $localPath.substr($this->SITE_NAME, 0, -3) . '_'.$filename.'_tmp'.'.xml';

          $content = '<?xml version="1.0" encoding="utf-8"?>'.$nl.
            '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">'.$nl;

          $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">'.$nl;
          $content .= '<shop>'.$nl;
          $content .= '<name>' . $xml_name . '</name>'.$nl;
          $content .= '<company>' . $xml_company . '</company>'.$nl;
          $content .= '<url>https://' . $this->SITE_NAME . '</url>'.$nl;
          $content .= '<currencies>'.$nl;
          $content .= '<currency id="RUR" rate="1" />';
          $content .= '</currencies>'.$nl;

          file_put_contents( $tmpFilename, $content);

          $content = $this->getCatalog($q);
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          $content = '<offers>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);
          $i=0;
          $utm='?utm_source=YandexMarket&amp;utm_medium=cpc&amp;utm_content=5211&amp;utm_term=';

          $ids[] = -1;
          $sqlBody = "SELECT `product_id` FROM `category_product` WHERE `category_id` IN(277, 522, 273, 274, 275)";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $list = $result->FetchAll();

          $sqlBody = "SELECT `product_id` FROM `category_product` WHERE `category_id` IN(279, 276, 278)";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $excludeList = $result->FetchAll();

          foreach($excludeList as $l) $exList[$l['product_id']] = $l['product_id'];

          foreach($list as $listId)
            if(empty($exList[$listId['product_id']])) $ids[$listId['product_id']] = $listId['product_id'];

          $denyCats = [135, 177];
          $sqlBody = "SELECT `category_id` FROM `category_catalog` WHERE `catalog_id` IN (6, 4, 7)";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $denyList = $result->FetchAll();

          foreach($denyList as $denyCat) $denyCats[] = $denyCat['category_id'];
          // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r($denyCats, true) . '</pre>');

          $sqlBody=
            "SELECT `id`, `slug`, `name`, `count`, `price`, `old_price`, `barcode`, `generalcategory_id`, `content`, `adult`, `code`, `id1c`, "
            . "`yamarket_clothes`, `yamarket_category`, `yamarket_model`, `bonuspay`, `yamarket_color`, "
            . "`yamarket_typeimg`, `yamarket_sex` "
            . "FROM `product` "
            . "WHERE `id` IN(".implode(',', $ids).") AND `is_public`=1 AND `price` > 0 AND `generalcategory_id` NOT IN(".implode(',', $denyCats).") AND `count` >0 "
            . "";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);

          while($product=$result->fetch()){
            $content=$this->getOffer($product, $q, [
              'photos_count' => 10,
              'props_to_show' => [
                'Длина',
                'Диаметр',
                'Объем',
                'Ширина',
                'Количество',
              ],
              'props_to_hide' => [
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
              ],
              'props_to_select' => false,
              'utm' => $utm,
              'store_discount_vendors' => true,
            ]);

            file_put_contents( $tmpFilename, $content, FILE_APPEND);
            if($this->isTest && $i++>5) break;
          }
          $content = '</offers>'.$nl;

          $content .= sizeof($this->promoItems) ? $this->getPromos($this->promoItems) : '';

          $content .= '</shop>'.$nl;
          $content .= '</yml_catalog>'.$nl;
          file_put_contents( $tmpFilename, $content, FILE_APPEND);

          break;

        case 'anyquery_delta':
          $sqlBody = "SELECT id, count, price, is_public FROM `product`";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          // $productsDB = $result->fetchAll();
          while($product = $result->fetch()){
            $productsDB[$product['id']] = $product;
            if(empty($productsDB[$product['id']]['count'])) $productsDB[$product['id']]['count'] = 0;
            if(!$productsDB[$product['id']]['is_public']) $productsDB[$product['id']]['count'] = 0;
          }

          $productsXml = simplexml_load_file($localPath. 'onona_anyquery.xml');
          if ($productsXml===false) {
            $this->myLogItems("Ошибка разбора файла onona_anyquery.xml");
            die('Ошибка разбора файла onona_anyquery.xml'."\n");
          }
          // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r($productsXml, true) . '</pre>' . "\n");
          $delta = [];
          foreach ($productsXml->shop->offers->offer as $product) {
            $id = '' . $product['id'];
            $avail = '' . $product['available'];
            $avail = $avail == 'true' ? 1 : 0;
            $price = '' . $product->price;
            $productDB = $productsDB[$id];
            //если все соответствует - пропускаем
            if($productDB['price'] == $price && ($productDB['count'] > 0) == $avail) continue;

            $delta[] = implode(';', [$id, $productDB['price'] ? $productDB['price'] : $price, (($productDB['count'] > 0) ? 1 : 0)]);
            // die(__FILE__.'|'.__LINE__.'<pre>'.print_r([$delta, $productDB, $id, $avail, $price], true).'</pre>'."\n");

          }
          file_put_contents($localPath.'anyquery_delta.csv', implode("\n", $delta));
          die("");

          break;
        default:
          die("\nНе выбран режим работы\n");

      }
      if($this->isTest) echo "\n"."Создан фид  ".$tmpFilename."\n";
      if($filename=='sitemap')
        rename ($tmpFilename, $localPath.$filename.'.xml');
      elseif($filename=='tiu')
        rename ($tmpFilename, $localPath.'yml_tiu.xml');
      else if (is_string($tmpFilename))
        rename ($tmpFilename, $localPath.substr($this->SITE_NAME, 0, -3).'_'.$filename.'.xml');
      if($this->isTest) echo "\n"."Фид  ".substr($this->SITE_NAME, 0, -3).'_'.$filename.'.xml'." Перезаписан\n";
      if(isset($filename_2)){
        if($this->isTest) echo "\n"."Создан фид  ".$tmpFilename_2."\n";
        rename ($tmpFilename_2, $localPath.substr($this->SITE_NAME, 0, -3).'_'.$filename_2.'.xml');
        if($this->isTest) echo "\n"."Фид  ".substr($this->SITE_NAME, 0, -3).'_'.$filename_2.'.xml'." Перезаписан\n";
      }

      if (isset($ymlPartsList)) {
        foreach ($ymlPartsList as $part) {
          rename($part['tmpFilename'], $localPath.substr($this->SITE_NAME, 0, -3).'_'.$part['filename'].'.xml');
        }
      }

      if($this->isTest)
        echo 'Пиковое использование памяти: '. memory_get_peak_usage().' bytes'.$nl;
    }

    private function getOfferFor1C($product, $q){
      // die(print_r(array_keys($product), true));
      $nl=$this->nl;
      $content = '<offer id="' . $product['id'] . '">'.$nl;

      $content .= '<url>https://' . $this->SITE_NAME . '/product/' . str_replace('&', '&amp;',$product['slug']). '</url>'.$nl;
      $content .= '<name>' . $this->prepareText($product['name']) . '</name>'.$nl;
      $content .= '<code>' . $this->prepareText($product['code']) . '</code>'.$nl;
      $content .= '<count>' . $product['count'] . '</count>'.$nl;
      if($product['id1c']) $content .= '<id1c>'.$product['id1c'].'</id1c>'.$nl;
      $content .= '<price>'.$product['price'].'</price>'.$nl;
      if($product['old_price']) $content .= '<oldprice>'.$product['old_price'].'</oldprice>'.$nl;
      if($product['discount']) $content .= '<discount>'.$product['discount'].'</discount>'.$nl;
      if ($product['bonus']) $content .= '<bonus>'.$product['bonus'].'</bonus>'.$nl;
      if($product['sync']) $content .= '<sync>'.$product['sync'].'</sync>'.$nl;
      if($product['is_notadddelivery']) $content .= '<is_notadddelivery>'.$product['is_notadddelivery'].'</is_notadddelivery>'.$nl;
      if($product['sortpriority']) $content .= '<sortpriority>'.$product['sortpriority'].'</sortpriority>'.$nl;
      if($product['position']) $content .= '<position>'.$product['position'].'</position>'.$nl;
      if($product['parents_id']) $content .= '<parents_id>'.$product['parents_id'].'</parents_id>'.$nl;
      $content .= '<currencyId>RUR</currencyId>'.$nl;

      $content .= '<created_at>' . $product['created_at'] . '</created_at>'.$nl;
      $content .= '<updated_at>' . $product['updated_at'] . '</updated_at>'.$nl;
      $content .= '<categoryId>' . $product['generalcategory_id'] . '</categoryId>'.$nl;

      $sqlBody = "SELECT `category_id` FROM `category_product` WHERE `product_id`=".$product['id'];

      $rsCats = $q->execute($sqlBody);
      $rsCats->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $cats=$rsCats->fetchAll();
      if(sizeof($cats)){
        $content.="<categories>".$nl;
        foreach ($cats as $cat) {
          $content.="<category>".$cat['category_id']."</category>".$nl;
        }
        $content.="</categories>".$nl;
      }

      if(isset($product['rating'])){
        $rating = round(($product['rating'] > 0 ? @round($product['rating'] / $product['votes_count']) : 0) / 2);
        if($rating) $content .= '<rating>'.$rating.'</rating>'.$nl;
      }

      if ($product['is_express']) $content .= '<is_express>'.$product['is_express'].'</is_express>'.$nl;
      if ($product['set_pairs']) $content .= '<set_pairs>'.$product['set_pairs'].'</set_pairs>'.$nl;
      if ($product['set_her']) $content .= '<set_her>'.$product['set_her'].'</set_her>'.$nl;
      if ($product['set_him']) $content .= '<set_him>'.$product['set_him'].'</set_him>'.$nl;

      if ($product['is_coupon_enabled']) $content .= '<is_coupon_enabled>'.$product['is_coupon_enabled'].'</is_coupon_enabled>'.$nl;
      if ($product['views_count']) $content .= '<views_count>'.$product['views_count'].'</views_count>'.$nl;

      if ($product['for_pairs']) $content .= '<for_pairs>'.$product['for_pairs'].'</for_pairs>'.$nl;
      if ($product['bdsm']) $content .= '<bdsm>'.$product['bdsm'].'</bdsm>'.$nl;
      if ($product['for_she']) $content .= '<for_she>'.$product['for_she'].'</for_she>'.$nl;
      if ($product['for_her']) $content .= '<for_her>'.$product['for_her'].'</for_her>'.$nl;
      if ($product['cosmetics']) $content .= '<cosmetics>'.$product['cosmetics'].'</cosmetics>'.$nl;
      if ($product['belie']) $content .= '<belie>'.$product['belie'].'</belie>'.$nl;
      if ($product['other']) $content .= '<other>'.$product['other'].'</other>'.$nl;
      if ($product['barcode']) $content .= '<barcode>'.$product['barcode'].'</barcode>'.$nl;
      if ($product['weight']) $content .= '<weight>'.$product['weight'].'</weight>'.$nl;
      if ($product['adult']) $content .= '<adult>true</adult>'.$nl;

      $content .= '<description><![CDATA[' . $this->prepareText($product['content']) . ']]></description>'.$nl;
      $content .= $this->getPhotos($product['id'], 1, $q, false, true);
      if ($product['file']) $content .= '<picture_gif>https://onona.ru/uploads/photo/'.$product['file'].'</picture_gif>'.$nl;
      if ($product['video']) $content .= '<video>https://onona.ru/uploads/video/'.$product['video'].'</video>'.$nl;

      $sqlBody="SELECT di.`name`, di.`value`, di.`id` FROM `dop_info_product` dip ".
        "LEFT JOIN `dop_info` di on di.`id`= dip.`dop_info_id` "
        ."WHERE dip.`product_id`=".$product['id'];

      $rsPhotos=$q->execute($sqlBody);
      $rsPhotos->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $dopInfos=$rsPhotos->fetchAll();
      if(sizeof($dopInfos)) foreach ($dopInfos as $property) {
        $content .= '<param id="' . $property['id'] . '" name="'.$property['name'].'" value="'.$this->prepareText(str_replace("™", "", $property['value'])).'">' . $this->prepareText(str_replace("™", "", $property['value'])) . '</param>'.$nl;
      }
      // $content .= $this->getProps($q, $product['id'], $propertyToShow, $propertyToHide, $propertyToSelect, (isset($mods['store_discount_vendors']) && $mods['store_discount_vendors']));




      $content .= '</offer>'.$nl;
      return $content;
    }

    private function getDopCats($q){
      $nl=$this->nl;

      $retStr = '<params>'.$nl;
      $sqlBody = "SELECT * FROM dop_info_category";
      $rs = $q->execute($sqlBody);
      $rs->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $cats = $rs->fetchAll();
      if(!empty($cats)) foreach ($cats as $cat){
        $retStr .= '<param_category id="' . $cat['id'] . '" name="' . $cat['name'] . '">'.$nl;
        $sqlBody = "SELECT * FROM dop_info WHERE dicategory_id=" . $cat['id'];
        $rs = $q->execute($sqlBody);
        $rs->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $params = $rs->fetchAll();
        if(!empty($params)) foreach($params as $param)
          $retStr .= '<param id="' . $param['id'] . '" value="' . $this->prepareText(str_replace("™", "", $param['value'])) . '">' . $this->prepareText(str_replace("™", "", $param['value'])) . '</param>'.$nl;
        $retStr .= '</param_category>' . $nl;
      }
      $retStr.= '</params>' . $nl;

      return $retStr;
    }

    private function getPromos($promoItems){
      $retStr='';
      /*$nl=$this->nl;
      if(sizeof($promoItems)){
        $retStr.='<promos>'.$nl;
        $retStr.='<promo id="Promo15" type="promo code">'.$nl;
        $retStr.='<promo-code>YM15</promo-code>'.$nl;
        $retStr.='<discount unit="percent">15</discount>'.$nl;
        $retStr.='<purchase>'.$nl;

        foreach ($promoItems as $itemId) {
          $retStr.='<product offer-id="'.$itemId.'"/> '.$nl;
        }

        $retStr.='</purchase>'.$nl;
        $retStr.='</promo>'.$nl;
        $retStr.='</promos>'.$nl;
      }*/
      return $retStr;

    }
}
