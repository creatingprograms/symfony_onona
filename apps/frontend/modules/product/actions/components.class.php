<?php

class productComponents extends sfComponents {
  public function __construct($context, $moduleName, $controllerName) {
    parent::__construct($context, $moduleName, $controllerName);

    $this->csrf = new CSRFToken($this);
    // die($this->csrf);
  }

  public function executeProductInList(sfWebRequest $request) {
    $rating = $this->product->getRating()/2; //В новом дизайне звезд всего 5
    $votesCount = $this->product->getVotesCount();
    $numStars = $rating/$votesCount;
    if(!$numStars || $numStars>5) $numStars=5;
    $this->bonus = round(
      // (
      $this->product->getPrice() //- $this->product->getPrice() * ($this->product->getBonuspay() > 0 ? $this->product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100)
      *
      (($this->product->getBonus() > 0 ? $this->product->getBonus() : csSettings::get('persent_bonus_add')) / 100)
    ) ;
    if($this->product->getFile()){
      $this->photo="/uploads/photo/".$this->product->getFile();
    }
    else{
      $photos = PhotoTable::getInstance()
        ->createQuery()
        ->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $this->product->getId() . " limit 0,1)")
        ->orderBy("position")
        ->execute();
      $this->photo=false;
      if(sizeof($photos))
        $this->photo="/uploads/photo/thumbnails_250x250/".$photos[0]->getFilename();
      // if(!$this->photo || file_exists($_SERVER['DOCUMENT_ROOT'].$this->photo))
      //   $this->photo='/frontend/images/nophoto.jpg';
    }
    $this->numStars=$numStars;
    $this->needBlured=$this->checkForBlur($this->product->getId());
  }

  public function executeSqu(sfWebRequest $request) {//показывает выбор между товарами
    if ($this->product->getParent() != "")
        $productProp = $this->product->getParent();
    else
        $productProp = $this->product;

    $childrens = $productProp->getChildren()->getPrimaryKeys();
    $childrens[] = $productProp->getId();
    $count=sizeof($childrens);
    if($count>1){
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $result = $q->execute("SELECT dic.name as name, "
              . "di.value as value, "
              . "p.slug as prod_slug, "
              . "p.id as prod_id, "
              . "p.count as prod_count, "
              . "dic.position as sort, "
              . "dic.id as category_id, "
              . "di.id as dopinfo_id, "
              . "dicf.filename as filename, "
              . "p.is_public as is_public, "
              . "count(dic.id) as count_params "
              . "FROM `dop_info` as di "
              . "left join dop_info_category as dic on dic.id = di.dicategory_id "
              . "left join dop_info_product as dip on dip.dop_info_id=di.id "
              . "left join product as p on p.id=dip.product_id "
              . "left join dop_info_category_full dicf ON dicf.name=di.value "
              . "where dip.product_id in (" . implode(",", $childrens) . ") AND dic.is_public =1 "
              . "group by di.id "
              . "HAVING  count_params < $count "
              . "ORDER BY `dic`.`position` ASC, `di`.`position` ASC");
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $params = $result->fetchAll();
      // die('<pre>'.print_r($params, true));
      foreach ($params as $param) {
        $parms[$param['name']][]=$param;
      }
    }
    $this->params=$parms;
    // die('<pre>~!~'.print_r($parms, true).'-|');
  }

  public function executeNeedtoblur(sfWebRequest $request) {//определяем принадлежность товара к некоторым группам, производителям и коллекциям с целью затемнения их фото для модерации Юкасса
    $this->needTo=$this->checkForBlur($this->product->getId());
  }

  private function checkForBlur($id){
    $denyArts=[
      '23518',
      '26799',
      '23512',
      '23520',
      '23513',
      '23586',
      '14443',
      '15410',
      '19000',
      '15394',
      '19620',
      '14446',
      '23523',
      '19618',
      '19601',
      '19998',
      '24913',
      '24775',
      '24774',
      '24777',
      '24781',
      '24784',
      '23518',
      '23519',
      '14444',
      '15414',
      '15399',
      '415',
      '19621',
      '14445',
      '24796',
      '19615',
      '19597',
      '23784',
      '15420',
      '24779',
      '19600',
      '24782',
      '23514',
      '24780',
      '19598',
      '16908',
      '24785',
      '15403',
      '15395',
      '19994',
      '23725',
      '2002',
      '19632',
      '24789',
      '19619',
      '20865',
      '20866',
      '20870',
      '15427',
      '15422',
      '15423',
      '15424',
      '24778',
      '6649',
      '15404',
      '16363',
      '19629',
      '19999',
      '19622',
      '19633',
      '24791',
      '22765',
      '15425',
      '18999',
      '15953',
      '16361',
      '17044',
      '17043',
      '14441',
      '15419',
      '14683',
      '15426',
      '19599',
      '19638',
      '20001',
      '20000',
      '19996',
      '26623',
      '25166',
      '14573',
      '19626',
      '20017',
      '20009',
      '20014',
      '15970',
      '19634',
      '15798',
      '20008',
      '20004',
      '24788',
      '24912',
      '24064',
      '24790',
      '15976',
      '472',
      '24792',
      '473',
      '16367',
      '19627',
      '10644',
      '6650',
      '106634',
      '19624',
      '15037',
      '15799',
      '10633',
      '6597',
      '19635',
      '19631',
      '12456',
      '16364',
      '19637',
      '19628',
      '20023',
      '19630',
      '14458',
      '16904',
      '16951',
      '12455',
      '16909',
      '16950',
      '16947',
      '14457',
      '15034',
      '16948',
      '16076',
      '10631',
      '14455',
      '14456',
      '19940',
      '66660',
      '10632',
      '1102',
      '25444',
      '1103',
      '24773',
      '23800',
      '1101',
      '24772',
      '24567',
      '22544',
      '21364',
      '24771',
      '22010',
      '1104',
      '21479',
      '22013',
      '21363',
      '23611',
      '18202',
      '1104',
      '22013',
      '21363',
      '18201',
      '20859',
      '15572',
      '18768',
      '18905',
      '18928',
      '22761',
      '25301',
      '19409',
      '19389',
      '17676',
      '18303',
      '25645',
      '25644',
      '23428',
      '23426',
      '16007',
      '20664',
      '18905',
      '18928',
      '22761',
      '25301',
      '18889',
      '19409',
      '15574',
      '18929',
      '19424',
      '22762',
      '20659',
      '15532',
      '18912',
      '17990',
      '17150',
      '840',
      '847',
      '18914',
      '22923',
      '15984',
      '608',
      '18908',
      '19425',
      '323',
      '865',
      '3374',
      '14869',
      '376',
      '15983',
      '367',
      'PD1522-21',
      '18066',
      '16949',
      '17235',
      '19609',
      '19602',
      '19616',
      '26180',
      '26183',
      'PD4129-21',
      '19422',
      '21375',
      '18913',
      '19392',
      '19410',
      '15575',
      '17149',
      '22525',
      '25334',
      '19476',
      '20851',
      '18772',
      '19427',
      '22527',
      '19427',
      '22530',
      '17153',
      '19477',
      '18773',
      '19402',
      '19428',
      '22530',
      '17154',
      '18063',
      '18915',
      '19396',
      '19941',
      '20854',
      '18263',
      '22531',
      '25133',
      '22764',
      '23079',
      '23071',
      '23091',
      '23079',
      '23078',
      '18906',
      '26351',
      '17336',
      '19945',
      '479',
      '17332',
      '23992',
      '19945',
      '26673',
      '17810',
      '17811',
      '21504',
      '26674',
      '17334',
      '17332',
      '19945',
      '17335',
      '15986',
      '218',
      '17337',
      '17333',
      '24381',
      '15052',
      '22547',
      '26829',
      '19606',
      '19625',
      '23089',
      '27471',
      '19608',
      '19988',
      '23774',
      '23082',
      '18267',
      '18787',
      '19610',
      '23084',
      '19611',
      '2385',
      '26787',
      '19603',
      '19613',
      '19611',
      '23164',
      '19987',
      '23092',
      '19984',
      '23116',
      '23115',
      '24793',
      '19979',
      '16635',
      '23163',
      '23086',
      '19947',
      '23113',
      '464',
      '465',
      '462',
      '26006',
      '460',
      '451',
      '10636',
      '23166',
      '16613',
      '26009',
      '29982',
      '26002',
      '18268',
      '25643',
      '15119',
      '23173',
      '18270',
      '19982',
      '20006',
      '21751',
      '23078',
      '23427',
      '24794',
      '25010',
      '16840',
      '17237',
      '18271',
      '19614',
      '23087',
      '23428',
      '18421',
      '19479',
      '19659',
      '23090',
      '23167',
      '24060',
      '24799',
      '25014',
      '25372',
      '459',
      '17233',
      '18785',
      '19481',
      '19609',
      '23075',
      '23083',
      '24462',
      '24800',
      '24795',
      '26793',
      '17843',
      '15797',
      '90',
      '23165',
      '21751',
      '21746',
      '22766',
      '24065',
      '211',
      '24133',
      '24061',
      '24062',
      '24133',
      '25012',
      '25013',
      '20003',
      '20005',
      '20006',
      '20007',
      '20010',
      '463',
      '25015',
      '15234',
      '21758',
      '16073',
      '16836',
      '26004',
      '26007',
      '24913',
      '26011',
      '20013',
      '454',
      '458',
      '25645',
      '25999',
      '12713',
      '24758',
      '14863',
      '14865',
      '15653',
      '862',
      '24800',
      '19982',
      '23580',
      '19995',
      '24797',
      '24798',
      '17662',
      '26014',
      '20024',
      '19985',
      '24189',
      '20012',
      '25165',
      '6456',
      '25644',
      '25140',
      '24716',
      '24718',
      '14864',
      '26450',
      '18168',
      '852',
      '24799',
      '18421',
      '16297',
      '10645',
      '18271',
      '454',
      '23269',
      '26014',
      '20024',
      '19985',
      '24189',
      '20012',
      '25165',
      '6456',
      '25644',
      '25140',
      '24716',
      '24718',
      '14864',
      '26450',
      '18168',
      '852',
      '24799',
      '18421',
      '16297',
      '10645',
      '18271',
      '454',
      '23269',
      '26001',
      '10648',
      '17676',
      '19478',
      '24463',
      '20015',
      '14758',
      '24912',
      '26613',
      '23824',
      '18093',
      '25188',
      '18167',
      '6644',
      '18907',
      '12025',
      '16146',
      '19004',
      '459',
      '365',
      '453',
      '466',
      'BAI-BI-0325012-0101',
      'PD3231-00',
      'PD3238-23',
      '700004',
      '700005',
      '700007',
      '24902694001',
      'PD2128-23',
      'PD5381-11',
      'PD1514-21',
      '984104',
      '6501 BX SIT',
      'PD3366-21',
      '26000',
      '18262',
      '19482',
      '457',
      '16839',
      '16168',
      '10648',
      '458',
      '19605',
      '5288110000',
      'PD3694-00',
      '5213370000',
      '700011',
      '3012-2-BX SIT',
      '6023-2 BX SIT',
      '700009',
      'PD2173-10',
      'PD5382-12',
      'Bai-Bw-022034',
      'Bai-Bw-022048',
      'PD3351-20',
      'PD3367-21',
      '26010',
      '16575',
      '24063',
      '456',
      '23267',
      '15539',
      '10647',
      '15540',
      '15541',
      '5208450000',
      'PD3236-12',
      'GP-2K130-BX',
      '700016',
      '3015-1 BX SIT',
      '6013-1 BX SIT',
      '700010',
      '2504220000',
      'PD5684-21',
      'OU273SKN',
      'PD5621-21',
      'SE1514-20-3',
      'PD3374-21',
      '25139',
      '856',
      '224',
      '16012',
      '18172',
      '10649',
      '18785',
      '18269',
      '16840',
      'PD3230-23',
      'PD3224-00',
      'PD3232-23',
      '700008',
      '6014-2 BX SIT',
      '6023-1 BX SIT',
      '700003',
      '700015',
      '15103',
      'DY1015-18BX',
      'DY1015-17BX',
      'PD5622-21',
      '748013',
      '26012',
      '855',
      '19479',
      '25011',
      '12024',
      '25019',
      '25166',
      '15948',
      '12061',
      'se-0612-04-3',
      'PD3237-00',
      'PD3692-20',
      '700006',
      '3015-2 BX SIT',
      '6014-1 BX SIT',
      '700013',
      '700014',
      'PD5382-11',
      'PD5681-23',
      '99088ACHBX GR',
      'DY1050-03 BX',
      '25138',
      '19623',
      '19408',
      '17236',
      '25096',
      '24059',
      '25017',
      '10643',
      '15538',
      '26003',

    ];
    // $denyArts[]='-1';
    if(in_array($id, $denyArts)) return true;
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();

    $sqlBody=
      "SELECT `code` "
      ."FROM `product` "
      ."WHERE `id` = ".$id." "
      ."AND `code` IN(\"".implode('", "', $denyArts)."\") "
      ."";
      // die($sqlBody);
    $res=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    $result=!empty($res);
    return $result;

    return false;
    $denyCats=[
      278,  //  /catalog/eroticheskoe-bele
      //-------------------------------------------------------
      127,  //  /category/mega-masturbatory-realistiki
      18,   //  /category/muzhskie-masturbatory
      22,   //  /category/popki
      24,   //  /category/vaginy
      26,   //  /category/rotiki
      220,  //  /category/universalnye
      419,  //  /category/avtomaticheskie-masturbatory
      420,  //  /category/v-banke
      421,  //  /category/vakuumnye
      422,  //  /category/grud
      423,  //  /category/devstvennica
      424,  //  /category/nabory
      425,  //  /category/originalnye
      426,  //  /category/realistiki
      427,  //  /category/ruki
      428,  //  /category/masturbator-s-vibraciej
      429,  //  /category/s-vibropulej
      430,  //  /category/masturbator-s-podogrevom
      431,  //  /category/masturbator-s-pompoj
      432,  //  /category/masturbator-s-prisoskoj
      433,  //  /category/s-falloimitatorom
      //-------------------------------------------------------
      32,   //  /category/falloimitatory
      39,   //  /category/dildo-bez-vibracii
      41,   //  /category/dildo-s-vibraciei
      139,  //  /category/bolshie-falloimitatory
      280,  //  /category/realistichnye_falloimitatory
      290,  //  /category/falloimitatory_na_prisoske
      301,  //  /category/falloimitatory-s-semyaizverzheniem
      304,  //  /category/analno-vaginalnye-falloimitatory
      305,  //  /category/dvustoronnie-falloimitatory
      306,  //  /category/gelevye-falloimitatory
      307,  //  /category/steklyannye-falloimitatory
      308,  //  /category/silikonovye-falloimitatory
      309,  //  /category/falloimitatory-iz-kiberkozhi
      310,  //  /category/naduvnye-falloimitatory
      371,  //  /category/vodonepronicaemye
      372,  //  /category/giganty
      373,  //  /category/klassicheskie
      373,  //  /category/multiskorostnye
      375,  //  /category/nabory-fallosov
      376,  //  /category/falloimitatory-neobychnoj-formy
      377,  //  /category/falloimitatory-perezaryazhaemye
      378,  //  /category/rebristye-falloimitatory
      379,  //  /category/falloimitatory-s-moshonkoj
      380,  //  /category/s-podogrevom
      381,  //  /category/s-rotaciej
      382,  //  /category/falloimitatory-s-ehlektrostimulyaciej
      383,  //  /category/falloimitatory-svetyashchiesya
      384,  //  /category/tolstye
      385,  //  /category/dizajnerskie
      //-------------------------------------------------------
      80,   //  /category/sex_kukly
      136,  //  /category/kukly-prostye
      137,  //  /category/realistichnye_seks-kukly
      245,  //  /category/mini-seks-kukly
      282,  //  /category/rezinovye_sex_kukly
      283,  //  /category/muzhchiny_i_transseksualy
      284,  //  /category/silikonovye_seks_kukly
      //-------------------------------------------------------
    ];
    $denyProps=[

    ];
    $denyCats[]=-1;//Чтобы IN в запросе не был пустым

    // $denyIds=$denyArts;

    // $q = Doctrine_Manager::getInstance()->getCurrentConnection();

    $sqlBody=
      "SELECT `category_id` "
      ."FROM `category_product` "
      ."WHERE `product_id` = ".$id." "
      ."AND `category_id` IN(".implode(', ', $denyCats).") "
      ."";
      // die($sqlBody);
    $res=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    $result=!empty($res);
    return $result;
  }

  public function executeParams(sfWebRequest $request) {//показывает характеристики товара
    if ($this->product->getParent() != "")
        $productProp = $this->product->getParent();
    else
        $productProp = $this->product;
    // $this->parent=$productProp;

    $childrens = $productProp->getChildren()->getPrimaryKeys();
    $childrens[] = $productProp->getId();
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $result = $q->execute("SELECT dic.name as name, "
            . "di.value as value, "
            . "p.slug as prod_slug, "
            . "p.id as prod_id, "
            . "p.count as prod_count, "
            . "dic.position as sort, "
            . "dic.id as category_id, "
            . "di.id as dopinfo_id, "
            . "count(dic.id) as count_params "
            . "FROM `dop_info` as di "
            . "left join dop_info_category as dic on dic.id = di.dicategory_id "
            . "left join dop_info_product as dip on dip.dop_info_id=di.id "
            . "left join product as p on p.id=dip.product_id "
            . "where dip.product_id in (" . implode(",", $childrens) . ") AND dic.is_public =1  "
            . "group by di.id "
            . "ORDER BY `dic`.`position` ASC, `di`.`position` ASC");
    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $params = $result->fetchAll();
    // die('<pre>~!~'.print_r($params, true).'-|');
    foreach ($params as $key => $property) {
      if($property['name'] == "Производитель"){
        $manufacturer = ManufacturerTable::getInstance()->findOneBySubid($property['dopinfo_id']);
        if ($manufacturer)
          if ($manufacturer->getIsPublic())
            $params[$key]['url']='/manufacturer/'.$manufacturer->getSlug();
      }
      if ($property['name'] == "Коллекция") {
        $collection = CollectionTable::getInstance()->findOneBySubid($property['dopinfo_id']);
        if ($collection){
          if ($collection->getIsPublic())
            $params[$key]['url']="/collection/" . $collection->getSlug();
        }
        else
          $params[$key]['url']="/collection/" . $property['dopinfo_id'];
      }
      // if(!isset($newParams[$property['name']]) || $property['prod_slug'] == $this->product->getSlug())
      $newParams[$property['name']][]=$params[$key];
    }
    // $newParams['id']=[
    //   'name' => 'id',
    //   'value' => $this->product->getSlug()
    // ];
    // $this->params=$params;
    $this->newParams=$newParams;

  }

  public function executeComments(sfWebRequest $request) {//показывает отзывы к товару

    if ($this->product->getParent() != "")
        $productProp = $this->product->getParent();
    else
        $productProp = $this->product;
    //Нашли связанные товары
    $i = 0;
    $childrens = $productProp->getChildren();
    $childrensId = $childrens->getPrimaryKeys();
    $childrensId[] = $productProp->getId();
    //Нашли всех потомков

    $this->comments = Doctrine_Core::getTable('Comments')
      ->createQuery('c')
      ->where("is_public = '1'")
      ->addWhere('product_id in (' . implode(',', $childrensId) . ')')
      ->orderBy('created_at desc')
      ->execute();
  }

  public function executeStock(sfWebRequest $request) {//показывает магазины с остатками
    $stock = unserialize($this->product->getStock());
    if (( count($stock['storages']['storage']) > 0)) {
      foreach ($stock['storages']['storage'] as $storage){
        if ($storage['@attributes']['code1c']) {
            $shop = ShopsTable::getInstance()->findOneById1c($storage['@attributes']['code1c']);
            $codeShop1cIsStock[] = "'".$storage['@attributes']['code1c']."'";
        } else {
            $shop = ShopsTable::getInstance()->findOneById1c($storage['code1c']);
            $codeShop1cIsStock[] = "'".$storage['code1c']."'";
        }//Получаем магазин

        if ($shop){
          if(!$shop->getIsActive()) continue;
        }
        else continue;
        $shopsInStock[]=$shop;
      }

    }
    $codeShop1cIsStock[]='-1';
    $this->shopsNotCount =
      ShopsTable::getInstance()
        ->createQuery()
        ->where("(id1c not in (" . implode(",", $codeShop1cIsStock) . ") ) and id1c is NOT NULL and id1c<>'' and is_active=1 ")
        ->execute();
    $this->shopsInStock=$shopsInStock;
  }
}
?>
