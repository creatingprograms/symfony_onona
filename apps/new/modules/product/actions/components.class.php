<?php

class productComponents extends sfComponents {

  public function executeProductOfDay(sfWebRequest $request) {// Показать товар дня
    $productsPrior=explode(',', csSettings::get('id_product_action_1'));

    if(!reset($productsPrior)) $productsPrior=[-1];//чтобы не получился пустой IN
    // if(isset($_GET['ildebug'])) die (print_r($productsPrior, true));
    $products = ProductTable::getInstance()->createQuery()
      ->where("is_public = '1'")
      // ->addWhere('count >0')
      ->addWhere('id IN('.implode(', ', $productsPrior).')')
      // ->orderBy('countsell DESC')
      ->limit(1)
      ->execute();
    $this->products=$products;
  }

  public function executeProductInListById(sfWebRequest $request) { // Показать продукт в списке
    $product = Doctrine_Core::getTable('Product')->findOneById($this->id);
    
    if(!is_object($product)) return false;
    
    $this->product = $product;
    $photos = PhotoTable::getInstance()
      ->createQuery()
      ->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $this->product->getId() . " limit 0,1)")
      ->orderBy("position")
      ->execute();
      
    if (sizeof($photos)) foreach ($photos as $photo) {
      $tmpPhotos = "/uploads/photo/" . $photo->getFilename();
      break;
    }
    $this->photo = $tmpPhotos;
    // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r($this->photoss, true) . '</pre>');

  }
  public function executeProductInList(sfWebRequest $request) {// Показать продукт в списке
    // global $isTest;
    // $this->style="default";
    // if($isTest)

    if($this->style!='fav') $this->style="picture-slide";
    // if($this->catId==136) $this->style="178453";
    $rating = $this->product->getRating()/2; //В новом дизайне звезд всего 5
    $votesCount = $this->product->getVotesCount();
    $numStars = round($rating/$votesCount + 0.2);
    if(!$numStars || $numStars>5) $numStars=5;
    $this->getComments();
    $this->bonus = round(
      // (
      $this->product->getPrice() //- $this->product->getPrice() * ($this->product->getBonuspay() > 0 ? $this->product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100)
      *
      (($this->product->getBonus() > 0 ? $this->product->getBonus() : csSettings::get('persent_bonus_add')) / 100)
    ) ;
    if($this->style=='picture-slide'){

      if($this->product->getFile()){
        $tmpPhotos[]="/uploads/photo/".$this->product->getFile();
      }
      try {
        $this->style="picture-slide-hover";
        $photos = PhotoTable::getInstance()
          ->createQuery()
          ->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $this->product->getId() . " limit 0,10)")
          ->orderBy("position")
          ->execute();
        if(sizeof($photos)) foreach ($photos as $photo) {
          // die('sdfsdf---------------');
          $tmpPhotos[]="/uploads/photo/thumbnails_250x250/".$photo->getFilename();
          // $this->photo="/uploads/photo/thumbnails_250x250/".$photos[0]->getFilename();
        }
      } catch (\Exception $e) {

      }


      $this->photos=$tmpPhotos;
      // die('!!'.print_r([$tmpPhotos, $this->photos], true));
    }
    else{
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
          $this->photo="/uploads/photo/".$photos[0]->getFilename();
          // $this->photo="/uploads/photo/thumbnails_250x250/".$photos[0]->getFilename();
      }
    }

    if(sizeof($this->comments))
      $this->reviewText = ILTools::getWordForm(sizeof($this->comments), 'отзыв');
    else
      $this->reviewText = false;
    // $this->reviewText = ILTools::getWordForm($this->product->getVotesCount(), 'отзыв');
    $this->isOfDay=ILTools::isOfDay($this->product->getId());
    $this->isExpress=ILTools::isExpress($this->product->getId());
    $this->isNew=ILTools::isNew($this->product);

    $this->numStars=$numStars;
    if($this->product->getSetHim())
      $this->setParms=[
        'name' => 'него',
        'class' => 'him',
      ];
    if($this->product->getSetHer())
      $this->setParms=[
        'name' => 'неё',
        'class' => 'her',
      ];
    if($this->product->getSetPairs())
    $this->setParms=[
      'name' => 'пар',
      'class' => 'pair',
    ];

    if($this->style=="fav"){//
      $this->bonus = round($this->product->getPrice() * (($this->product->getBonus() > 0 ? $this->product->getBonus() : csSettings::get('persent_bonus_add')) / 100));
    }
    if($this->style=="picture-slide-hover"){//Нужны еще свойства товаров
      if ($this->product->getParent() != "")
          $productProp = $this->product->getParent();
      else
          $productProp = $this->product;
      // $this->parent=$productProp;

      foreach ($productProp->getChildren() as $children) {//нужны только активные дети)
        // echo $children->getIsPublic().'|';
        if($children->getIsPublic()) $childrens[]=$children->getId();
      }
      if($productProp->getIsPublic()) $childrens[] = $productProp->getId();
      if(empty($childrens)) $childrens[]=-1;//Чтобы не было ошибки в IN()

      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $result = $q->execute("SELECT dic.name as name, "
              . "di.value as value "
              . "FROM `dop_info` as di "
              . "left join dop_info_category as dic on dic.id = di.dicategory_id "
              . "left join dop_info_product as dip on dip.dop_info_id=di.id "
              // . "left join product as p on p.id=dip.product_id "
              . "where dip.product_id in (" . implode(",", $childrens) . ") AND dic.is_public =1  "
              . "group by di.id "
              . "ORDER BY `dic`.`position` ASC, `di`.`position` ASC");
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $params = $result->fetchAll();
      // die(__DIR__.__FILE__.'|'.__LINE__.'<pre>'.print_r([$params], true).'</pre>');
      $i=1;
      foreach ($params as  $property) {
        if(empty($props[$property['name']])) $props[$property['name']]=$property['value'];
        else $props[$property['name']].=', '. $property['value'];
        if(5<$i++) break;
      }
      $this->props=$props;
    }
    if ($this->getUser()->isAuthenticated()){
      $this->authUser=true;
      $GuardUser = $this->getUser()->getGuardUser();
      $items=$GuardUser->get(ILTools::FAVORITES_FIELD);
      if($items) $items=unserialize($items);
      if(isset($items[$this->product->getId()])) $this->isChoosen  = true;

      // $this->isChoosen  = true;
    }
    if(!isset($this->photo)) $this->photo = false;
    if(!isset($this->isSets)) $this->isSets = false;
    if(!isset($this->setParms)) $this->setParms = false;
    if(!isset($this->isShortText)) $this->isShortText = false;
    if(!isset($this->isChoosen)) $this->isChoosen = false;
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
      $sqlBody = "SELECT dic.name as name, "
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
        // . "AND p.is_public=1 "
        . "group by di.id "
        . "HAVING  count_params < $count "
        . "ORDER BY `dic`.`position` ASC, `di`.`position` ASC";
      $result = $q->execute($sqlBody);
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $params = $result->fetchAll();
      $parms = $ids = [];
      foreach ($params as $param) {
        if(!$param['is_public']) continue;
        $parms[$param['name']][]=$param;
        $ids[$param['dopinfo_id']] = $param['dopinfo_id'];//Собираем id допхарактеристик
      }
      $ids[-1] = -1;

      foreach ($parms as $key => $value) {
        if (sizeof($value) < 2) unset($parms[$key]);
      }

      if(sizeof($parms) > 1){//Если параметров больше одного
        $sqlBody = "SELECT * FROM `dop_info_product` WHERE `product_id`=" . $this->product->getId() . " AND `dop_info_id`  IN(" . implode(',', $ids) . ")";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $selfIds = $result->fetchAll();
        $itemIds = $alterIds = $productIds = [];
        foreach ($selfIds as $id) $itemIds[$id['dop_info_id']] = $id['dop_info_id'];//Собрали значения которые у товара есть
        
        foreach ($ids as $id) if(empty($itemIds[$id])) $alterIds[$id] = $id;  //Собрали значения которых у товара нет
        $itemIds[-1] = -1;

        foreach($childrens as $children) if($children != $this->product->getId()) $productIds[] = $children; //Собрали id товаров кроме этого
        $productIds[] = -1;

        $sqlBody = 
          "SELECT dip.dop_info_id, dip.product_id, p.slug FROM dop_info_product AS dip ".
            "LEFT JOIN dop_info_product AS dip2 on dip2.product_id = dip.product_id ".
            "LEFT JOIN product p ON dip.product_id = p.id ".
          "WHERE dip.dop_info_id IN(" . implode(',', $alterIds) . ") ".
            "AND dip.product_id IN (" . implode(',', $productIds) . ") ".
            "AND dip2.dop_info_id IN(" . implode(',', $itemIds) . ");";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $list = $result->fetchAll();

        foreach($list as $line) $alterList[$line['dop_info_id']] = $line;

        foreach($parms as $key => $parm)
          foreach($parm as $pKey => $value){
            if(in_array($value['dopinfo_id'], $itemIds)){//характеристика принадлежит товару
              $parms[$key][$pKey]['prod_slug'] = $this->product->getSlug();//ссылка должна быть на этот же товар
            }
            elseif(!empty($alterList[$value['dopinfo_id']])) {
              $parms[$key][$pKey]['prod_slug'] = $alterList[$value['dopinfo_id']]['slug'];//ссылка должна быть на другой товар из нового списка
              $parms[$key][$pKey]['prod_id'] = $alterList[$value['dopinfo_id']]['product_id'];//ссылка должна быть на другой товар из нового списка
            }
          }
      }
    }
    
    $this->params=$parms;
  }

  public function executeParams(sfWebRequest $request) {//показывает характеристики товара
    if ($this->product->getParent() != "")
        $productProp = $this->product->getParent();
    else
        $productProp = $this->product;
    // $this->parent=$productProp;

    foreach ($productProp->getChildren() as $children) {//нужны только активные дети)
      // echo $children->getIsPublic().'|';
      if($children->getIsPublic()) $childrens[]=$children->getId();
    }
    if($productProp->getIsPublic()) $childrens[] = $productProp->getId();
    if(empty($childrens)) $childrens[]=-1;//Чтобы не было ошибки в IN()

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sqlBody = "SELECT dic.name as name, "
      . "di.value as value, "
      . "di.description as description, "
      . "p.slug as prod_slug, "
      . "p.id as prod_id, "
      . "p.count as prod_count, "
      . "dic.position as sort, "
      . "dic.id as category_id, "
      . "di.id as dopinfo_id, "
      // . "p.is_public as p_public, "
      . "count(dic.id) as count_params "
      . "FROM `dop_info` as di "
      . "left join dop_info_category as dic on dic.id = di.dicategory_id "
      . "left join dop_info_product as dip on dip.dop_info_id=di.id "
      . "left join product as p on p.id=dip.product_id "
      . "where dip.product_id in (" . implode(",", $childrens) . ") AND dic.is_public =1  "
      . "group by di.id "
      . "ORDER BY `dic`.`position` ASC, `di`.`position` ASC";
    $result = $q->execute($sqlBody);
    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $params = $result->fetchAll();
    foreach ($params as $key => $property) {
      if($property['name'] == "Производитель" || $property['name'] == "Бренд(ы)" || $property['name'] == "Бренд"){
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

  private function getComments(){//Подгружает отзывы к товару
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

  public function executeComments(sfWebRequest $request) {//показывает отзывы к товару
    $this->getComments();
    /*
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
      ->execute();*/
  }

  public function executeProductStock(sfWebRequest $request) {//показывает магазины с остатками для избранного
    $stock = unserialize($this->product->getStock());
    if ($this->product->getCount() && count($stock['storages']['storage']) > 0) {
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
    $this->shopsInStock=$shopsInStock;
  }

  public function executeStock(sfWebRequest $request) {//показывает магазины с остатками
    $stock = unserialize($this->product->getStock());
    if ($this->product->getCount() && count($stock['storages']['storage']) > 0) {
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
