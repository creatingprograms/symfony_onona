<?php

class googlemerchantTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "newcat"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('mode', null, sfCommandOption::PARAMETER_REQUIRED, 'mode', 'test'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'googlemerchant';
        $this->briefDescription = '';
        $this->detailedDescription = 'The [googlemerchant|INFO]Формирует фид для Google merchant
        Call it with:

        [php symfony googlemerchant|INFO]
        ';
    }
    /*
    private function getMeasure($string){
      $lastSpaceIndex=strrchr (trim($string), ' ');

      return [
        'value' =>trim(mb_strlen(trim($lastSpaceIndex)) ? str_replace($lastSpaceIndex, '', $string) : $string),
        'unit' => trim($lastSpaceIndex),
        'strlen' => mb_strlen($lastSpaceIndex)

      ];
    }*/
    private function prepareText($text, $isName = false, $notStipTags=false) {
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
          'маска',
          'маски'
        ];
        if(!$isName){
          $text = str_ireplace($wordsToRemove, '', $text);
        }
        $re = '/(class="[\S\s]+")|(<\/a>)|(<a[\S\s]+>)|(id="[\S\s]+")|(style="[\S\s]+")|(<a.*>)|(<\/a>)|(<iframe.*\/iframe>)/mU';
        $text=preg_replace($re, '', $text);
        return trim($text);
    }
    protected function execute($arguments = array(), $options = array()) {

        $this->absPath=str_replace('lib/task', '', __DIR__).'web/';
          if ($options['env']=='dev'){
            $lf="\n";
            $isTest=true;
          }
          else {
            $isTest= false;
            $lf='';
          }
        $this->lf=$lf;

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $this->q = Doctrine_Manager::getInstance()->getCurrentConnection();

        ini_set("max_execution_time", 3000);

        $xml_name = 'Он и Она';
        $xml_company = 'ONONA';
        $SITE_NAME = "onona.ru";
        $this->SITE_NAME = $SITE_NAME;
        $this->currency=$currency='RUB';

        // $catalog = CategoryTable::getInstance()->findAll();
        $lf="\n";
        $content =
          '<?xml version="1.0"?>'.$lf.
            '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">'.$lf.
              '<channel>'.$lf.
                '<title>Товарные предложения для сайта onona.ru</title>'.$lf.
                '<link>https://'.$SITE_NAME.'</link>'.$lf.
                '<description>Тестовый фид Merchant для сайта onona.ru</description>'.$lf;


        $i=0;
        $utm='';

        switch ($options['mode']){
          case 'prez':
            $sqlBody="SELECT `id` FROM `category` WHERE `parents_id`=142 OR `id`=142";
            $cats = array_keys($this->q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE));
            $cats[]=-1;
            $sqlBody=
              "SELECT DISTINCT p.`id`, p.`count`, p.`name`, p.`price`, p.`slug`, p.`adult` ".
              " FROM `product` p ".
              " LEFT JOIN `category_product` cp ON p.`id`=cp.`product_id` ".
              " WHERE p.`count` > 0 ".
                " AND p.`price` > 0 ".
                " AND p.`is_public` > 0 ".
                " AND p.`yamarket` = 1 ".
                " AND cp.`category_id` IN (".implode(',',$cats).") ".
              "";

            // echo $sqlBody.$lf;
            $result=$this->q->execute($sqlBody);
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            while($product=$result->fetch()){
              $content.=$this->getItem($product);
              if($isTest && $i++>5 ) break;
            }
            //а теперь тоже самое для футболок
            $sqlBody=
              "SELECT p.`id`, p.`count`, p.`name`, p.`price`, p.`slug`, p.`adult` ".
              " FROM `product` p ".
              // " LEFT JOIN `category_product` cp ON p.`id`=cp.`product_id` ".
              " WHERE p.`count` > 0 ".
                " AND p.`price` > 0 ".
                " AND p.`is_public` > 0 ".
                " AND p.`yamarket` = 1 ".
                " AND p.`name` LIKE '%футболк%' ".
              "";

            // echo $sqlBody.$lf;
            $result=$this->q->execute($sqlBody);
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            while($product=$result->fetch()){
              $content.=$this->getItem($product);
              if($isTest && $i++>5 ) break;
            }
            $filename='merchant_2.xml';
            break;

          case 'cosmetic':
            $sqlBody="SELECT `id` FROM `category` WHERE `parents_id`=98 OR `parents_id`=142 OR `id`=98 OR `id`=142";
            $cats = array_keys($this->q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE));
            $cats[]=-1;
            $sqlBody=
              "SELECT DISTINCT p.`id`, p.`count`, p.`name`, p.`price`, p.`slug`, p.`adult` ".
              " FROM `product` p ".
              " LEFT JOIN `category_product` cp ON p.`id`=cp.`product_id` ".
              " WHERE p.`count` > 0 ".
                " AND p.`price` > 0 ".
                " AND p.`is_public` > 0 ".
                " AND p.`yamarket` = 1 ".
                " AND cp.`category_id` IN (".implode(',',$cats).") ".
              "";

            // echo $sqlBody.$lf;
            $result=$this->q->execute($sqlBody);
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            while($product=$result->fetch()){
              $content.=$this->getItem($product);
              if($isTest && $i++>5 ) break;
            }
            //а теперь тоже самое для футболок
            $sqlBody=
              "SELECT p.`id`, p.`count`, p.`name`, p.`price`, p.`slug`, p.`adult` ".
              " FROM `product` p ".
              // " LEFT JOIN `category_product` cp ON p.`id`=cp.`product_id` ".
              " WHERE p.`count` > 0 ".
                " AND p.`price` > 0 ".
                " AND p.`is_public` > 0 ".
                " AND p.`yamarket` = 1 ".
                " AND p.`name` LIKE '%футболк%' ".
              "";

            // echo $sqlBody.$lf;
            $result=$this->q->execute($sqlBody);
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            while($product=$result->fetch()){
              $content.=$this->getItem($product);
              if($isTest && $i++>5 ) break;
            }
            $filename='merchant_1.xml';

            break;

          default:
            $products = ProductTable::getInstance()
              ->createQuery()
              ->where("yamarket = '1'")
              ->addWhere("is_public =1")
              ->addWhere("count >1")
              ->addWhere("price >0")
              ->execute();
            foreach ($products as $product) {
              $id=$product->getId();
              if($isTest && $i++>5 ) break;

              if ($product->getGeneralCategory()->getId() != 135) {
                  if ($product->getPrice() > 0) {
                    if(mb_stristr($product->getName(), 'маск', false, 'utf-8')!==false) continue;
                    $item =
                      '<item>'.$lf.
                      '<title>'.$this->prepareText($product->getName(), true).'</title>'.$lf.
                      '<link>https://' . $SITE_NAME . '/product/' . str_replace('&', '&amp;',$product->getSlug()) . $utm. '</link>'.$lf.
                      '<description>'./*htmlentities ($this->prepareText($product->getContent(), false, true), ENT_QUOTES|ENT_XML1, 'UTF-8').*/'</description>'.$lf.
                      '<g:condition>new</g:condition>'.$lf.
                      '<g:id>'.$product->getId().'</g:id>'.$lf.
                      '<g:availability>'.($product->getCount() > 0 ? 'in_stock' : 'preorder').'</g:availability>'.$lf.
                      '<g:price>'.$product->getPrice().' '.$currency.'</g:price>'.$lf.
                      ($product->getAdult() ? '<g:adult>yes</g:adult>'.$lf : '')
                      ;
                      $photoalbum = $product->getPhotoalbums();
                      $photos = PhotoTable::getInstance()->createQuery()->where("album_id='" . $photoalbum[0]->getId() . "'")->orderBy("position")->execute();
                      if(!$photos[0]->getFilename() || !file_exists($absPath. 'uploads/photo/' . $photos[0]->getFilename()))
                        continue; //Пропускаем товары без изображений, гугл их не пропустит
                      $item .= '<g:image_link>https://' . $SITE_NAME . '/uploads/photo/' . $photos[0]->getFilename() . '</g:image_link>'.$lf;

                      $dopInfos = $product->getDopInfoProducts();
                      $isNeedVendor=true;
                      foreach ($dopInfos as $property){
                        if ($property['name'] == "Производитель") {
                          if(!$isNeedVendor) continue;
                          $isNeedVendor=false;
                          $arrVendor=explode(',', str_replace("™", "", $property['value']));
                          $vendor=$arrVendor[0];
                          $item .= '<g:brand>' . $this->prepareText($vendor) . '</g:brand>'."\n";
                        }
                      }
                    $item.='</item>'.$lf;
                    $content.=$item;
                    }
                }
            }

            $filename='merchant.xml';
        }
        $content.=
              '</channel>'.$lf.
            '</rss>';


        $file = fopen($this->absPath.$filename, "w+");
        fputs($file, $content, (strlen($content) + 1));
        fclose($file);

        if($isTest) exit('merchant test run success. '.date('d.m.Y H:i')."\n");
    }

    private function getItem($product){
      $lf=$this->lf;
      $item =
        '<item>'.$lf.
        '<title>'.$this->prepareText($product['name'], true).'</title>'.$lf.
        '<link>https://' . $this->SITE_NAME . '/product/' . str_replace('&', '&amp;',$product['slug']) . $utm. '</link>'.$lf.
        '<description>'./*htmlentities ($this->prepareText($product->getContent(), false, true), ENT_QUOTES|ENT_XML1, 'UTF-8').*/'</description>'.$lf.
        '<g:condition>new</g:condition>'.$lf.
        '<g:id>'.$product['id'].'</g:id>'.$lf.
        '<g:availability>'.($product['count'] > 0 ? 'in_stock' : 'preorder').'</g:availability>'.$lf.
        '<g:price>'.$product['price'].' '.$this->currency.'</g:price>'.$lf.
        ($product['adult'] ? '<g:adult>yes</g:adult>'.$lf : '')
        ;

        $sqlBody="SELECT `filename` FROM `product_photoalbum` pa "
          ."LEFT join `photo` p ON p.`album_id`=pa.`photoalbum_id` "
          ."WHERE pa.`product_id`=".$product['id'].' '
          ."ORDER BY p.`position` "
          ."LIMIT 1 "
          ."";
        $photos=$this->q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
        if(!$photos[0]['filename'] || !file_exists($this->absPath. 'uploads/photo/' . $photos[0]['filename']))
          return ''; //Пропускаем товары без изображений, гугл их не пропустит
        $item .= '<g:image_link>https://' . $this->SITE_NAME . '/uploads/photo/' . $photos[0]['filename'] . '</g:image_link>'.$lf;

        $sqlBody="SELECT di.`name`, di.`value` FROM `dop_info_product` dip ".
          "LEFT JOIN `dop_info` di on di.`id`= dip.`dop_info_id` "
          ."WHERE dip.`product_id`=".$product['id']." "
          ."AND di.`name`='Производитель' "
          ."LIMIT 1 "
          ."";
        $dopInfos=$this->q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach ($dopInfos as $property){
          $arrVendor=explode(',', str_replace("™", "", $property['value']));
          $vendor=$arrVendor[0];
          $item .= '<g:brand>' . $this->prepareText($vendor) . '</g:brand>'.$lf;
          break;
        }
      $item.='</item>'.$lf;

      return $item;
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
