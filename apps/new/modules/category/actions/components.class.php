<?php

class categoryComponents extends sfComponents {

  public function executeMaincatalog(sfWebRequest $request) {//Каталоги на главной
    $sqlBody="SELECT `name`, `img`, `description`, `slug`, `menu_name`, `img_top`, `img_bottom`, `class` FROM `catalog` WHERE `is_public`=1 ORDER BY `position`";
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $res=$q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_ASSOC);
    foreach ($res as $key => $cat) {
      $sqlBody=
        "SELECT count(p.`id`) as `pcount`".
        "FROM `product` p ".
        "LEFT JOIN `category_product` cp ON cp.`product_id`=p.`id` ".
        "LEFT JOIN `category` c ON c.`id`=cp.`category_id` ".
        "WHERE p.`is_public`=1 AND c.`slug`='service-".$cat['slug']."' ".
      "";
      $p=$q->execute($sqlBody)->Fetch(Doctrine_Core::FETCH_ASSOC);
      $res[$key]['count']=$p['pcount'];
      $sqlBody=
        "SELECT min(p.`price`) as `pminprice` ".
        "FROM `product` p ".
        "LEFT JOIN `category_product` cp ON cp.`product_id`=p.`id` ".
        "LEFT JOIN `category` c ON c.`id`=cp.`category_id` ".
        "WHERE p.`is_public`=1 AND c.`slug`='service-".$cat['slug']."' AND p.`count`>0 ".
      "";
      $p=$q->execute($sqlBody)->Fetch(Doctrine_Core::FETCH_ASSOC);
      $res[$key]['minprice']=$p['pminprice'];
    }
    $this->catalog=$res;
  }

  public function executeFavoriteItems(sfWebRequest $request) {
    if(empty($this->ids)) $this->ids=[-1];
    $products = ProductTable::getInstance()->createQuery()
      ->where("is_public = 1")
      ->addWhere('id IN('.implode(', ', $this->ids).')')
      ->orderBy('count > 0 DESC')
      // ->limit($limit)
      ->execute();
    $this->products=$products;
  }
  //Показывает слайдер из товаров
  public function executeSliderItems(sfWebRequest $request) {
    $limit=12;
    if($this->no_limit) $limit = 120;
    if(!empty($this->limit)) $limit = $this->limit;
    switch($this->type){
      case 'popular':
        $ids=explode(',', csSettings::get('bestsellersProducts'));
        foreach ($ids as $key => $value) //
          if(intval($value)>0)
            $productsPrior[]=intval($value);
          $productsPrior[]=-1;
        // die('<pre>'.print_r($productsPrior, true));
        $products = ProductTable::getInstance()->createQuery()
          ->where("is_public = '1'")
          ->addWhere('count >0')
          ->addWhere('id IN('.implode(', ', $productsPrior).')')
          ->orderBy('countsell DESC')
          ->limit($limit)
          ->execute();
        $this->texts=[
          'h2' => 'Популярное',
          // 'link' => '/related',
          // 'link-name' => 'Все популярные товары',
        ];
        //<div data-retailrocket-markup-block="5ba3a54197a52530d41bb22c">&nbsp;</div>
        break;

      case 'dopinfoaction':
        $curDate=date('Y-m-d H:i:s');
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sqlBody=
          "SELECT `dopinfo_id`, `name`, `value`, `endaction` "
          ."FROM `dopinfoaction` dia "
          ."LEFT JOIN `dop_info` di ON  di.`id`=dia.`dopinfo_id` "
          ."WHERE `is_active`=1 "
          ."AND `startaction` <='$curDate' AND `endaction` >= '$curDate' "
          ."ORDER BY dia.`updated_at` DESC "
          ."LIMIT 1"
          ."";
        $res=$q->execute($sqlBody)->Fetch(Doctrine_Core::FETCH_ASSOC);
        if(!$res) {
          $products=[];
        }
        else{
          $link=$res['name']=='Коллекция' ? '/collection/' : '/manufacturer/';
          $link.=$res['dopinfo_id'];

          $dateEnd = strtotime($res['endaction']);

          $this->texts=[
            'h2' => $res['value'],
            'link' => $link,
            'link-name' => 'Все акционные товары',
            'v-block-mod' => 'pink-wrap',
            'timer' => date('Y/m/d 23:59:59',$dateEnd),
          ];

          $sqlBody=
            "SELECT p.`id` "
            ."FROM `product` p "
            ."LEFT JOIN `dop_info_product` dip ON p.`id`=dip.`product_id` "
            ."WHERE dip.`dop_info_id`= ".$res['dopinfo_id']." "
            ."ORDER BY RAND() DESC "
            ."";
          $res=$q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_UNIQUE);
          // die('<pre>'.print_r(implode(', ',array_keys($res)), true));
          if(sizeof($res))
            $products = ProductTable::getInstance()->createQuery()
              ->where("is_public = '1'")
              ->addWhere('count >0')
              ->addWhere('id IN('.implode(', ', array_keys($res)).')')
              ->orderBy('RAND() DESC')
              ->limit($limit)
              ->execute();
          else $products = [];
        }


        break;

      case 'new':
        $ids=explode(',', csSettings::get('optimization_newProductId'));
        foreach ($ids as $key => $value) //
          if(intval($value)>0)
            $productsPrior[]=intval($value);

        if(empty($productsPrior)) $productsPrior[]=-1;
        $orderBy = "FIELD(id, ".implode(', ', array_reverse($productsPrior)).") DESC";

        $products = ProductTable::getInstance()->createQuery()
          ->where("is_public = 1")
          ->addWhere('count > 0')
          ->addWhere('id IN('.implode(', ', $productsPrior).')')
          ->orderBy($orderBy)
          ->limit($limit)
          ->execute();

        $this->texts=[
          'h2' => 'Новинки',
          'link' => '/newprod',
          'link-name' => 'Все новинки',
        ];

        break;

      case 'by-ids'://Переданные id
        $ids=explode(',', $this->strIds);
        foreach ($ids as $key => $value) //
          if(intval($value)>0)
            $productsPrior[]=intval($value);

        if(empty($productsPrior)) $productsPrior[]=-1;
        $orderBy = "FIELD(id, ".implode(', ', array_reverse($productsPrior)).") DESC";
        if(!$this->out_of_stock)
          $products = ProductTable::getInstance()->createQuery()
            ->where("is_public = 1")
            ->addWhere('count > 0')
            ->addWhere('id IN('.implode(', ', $productsPrior).')')
            ->orderBy($orderBy)
            ->limit($limit)
            ->execute();
        else
          $products = ProductTable::getInstance()->createQuery()
            ->where("is_public = 1")
            ->addWhere('id IN('.implode(', ', $productsPrior).')')
            ->orderBy($orderBy)
            ->limit($limit)
            ->execute();

        $this->texts=[
          'h2' => $this->blockName,
          // 'link' => '/newprod',
          // 'link-name' => 'Все новинки',
        ];
        break;

      case 'new-by-created-time':
        $products = ProductTable::getInstance()->createQuery()
          ->where("is_public = '1'")
          ->addWhere('count >0')
          ->orderBy('created_at DESC')
          ->limit($limit)
          ->execute();
        $this->texts=[
          'h2' => 'Новинки',
          'link' => '/newprod',
          'link-name' => 'Все новинки',
        ];
        break;

      case 'similar':
        $products = ProductTable::getInstance()->createQuery()
          ->where("is_public = '1'")
          ->addWhere('count >0')
          ->orderBy('created_at DESC')
          ->limit($limit)
          ->execute();
        $this->texts=[
          'h2' => 'ПОХОЖИЕ ТОВАРЫ',
          'link' => '#',
          'link-name' => 'Все похожие товары',
        ];
        break;

      case 'recommended'://При подтверждении что это делаем мы взять запрос из actionShow product
        $products = ProductTable::getInstance()->createQuery()
          ->where("is_public = '1'")
          ->addWhere('count >0')
          ->orderBy('created_at DESC')
          ->limit($limit)
          ->execute();
        $this->texts=[
          'h2' => 'РЕКОМЕНДУЕМ ДОБАВИТЬ',
          'link' => '#',
          // 'link-name' => 'Все рекомендуемые товары',
        ];
        break;

      case 'recommend':
        $products = ProductTable::getInstance()->createQuery()
          ->where("is_public = '1'")
          ->addWhere('count >0')
          ->orderBy('rating DESC')
          ->limit($limit)
          ->execute();
        $this->texts=[
          'h2' => 'Рекомендуем',
          // 'link' => '#',
          // 'link-name' => 'Все товары',
        ];
        break;

      case 'sale':
        $ids=explode(',', csSettings::get('saleProducts'));
        foreach ($ids as $key => $value) //
          if(intval($value)>0)
            $productsPrior[]=intval($value);
          $productsPrior[]=-1;

        $orderBy = "FIELD(id, ".implode(', ', array_reverse($productsPrior)).") DESC";

        $products = ProductTable::getInstance()->createQuery()
          ->where("is_public = '1'")
          ->addWhere('count >0')
          ->addWhere('id IN('.implode(', ', $productsPrior).')')
          ->addWhere('price <> old_price AND old_price>0')
          ->orderBy($orderBy)
          ->limit($limit)
          ->execute();

        $this->texts=[
          'h2' => 'Акции и спецпредложения',
          'link' => '/category/skidki_do_60_percent',
          'link-name' => 'Все товары',
        ];
        break;

      case 'by-cat-id':
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sqlBody = "SELECT product_id FROM category_product WHERE category_id = " . $this->catId;
        $res = $q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_UNIQUE);
        
        $ids = array_keys($res);
        $ids[] = -1;


        $products = ProductTable::getInstance()->createQuery('p')
          ->where("is_public = '1'")
          ->addWhere('count >0')
          ->addWhere('id IN(' . implode(',', $ids) . ')')
          ->orderBy('sortpriority DESC')
          ->limit($limit)
          ->execute();
        $this->texts = [
          'h2' => $this->blockName,
        ];
        break;

        default:
        $products=false;
    }
    $this->products=$products;
  }

  //Показывает список из товаров по массиву id
  public function executeListItems(sfWebRequest $request) {
    if(!isset($this->products)){
      // $idIn=$this->ids;
      $tmp=$this->ids;
      if(!empty($this->limit)){//если задано, отбрасываем все, что не влезает
        $tmp=array_chunk($tmp, $this->limit)[0];
      }
      $idIn=array_merge([-1], $tmp);
      // die('<pre>'.print_r($idIn, true));
      $products = ProductTable::getInstance()->createQuery()
        ->where("is_public = '1'")
        ->addWhere('id IN('.implode(',', $idIn).')')
        // ->addWhere('count >0')
        ->orderBy("FIELD(id, ".implode(',', $idIn).") ")
        ->execute();
      // $this->idIn=$idIn;
      $this->products=$products;
    }
  }

  //Параметры отображения брендов и коллекций
  public function executeSetViewMode(sfWebRequest $request) {

  }
  //Популярные бренды
  public function executePopularBrands(sfWebRequest $request) {
    //Получаем 24 популярных брендов
    $this->manufacturerPopular =  Doctrine_Core::getTable('Manufacturer')->createQuery()
      ->where("is_public='1'")
      ->addWhere("is_popular='1'")
      ->addWhere("subid>0")
      ->orderBy("position DESC")
      ->limit(24)
      ->execute();
  }

  //Популярные коллекции
  public function executePopularCollections(sfWebRequest $request) {
    //Получаем 10 популярных коллекций
    $this->collectionPopular =  Doctrine_Core::getTable('Collection')->createQuery()
      ->where("is_public='1'")
      ->addWhere("is_popular='1'")
      ->orderBy("position DESC")
      ->limit(15)
      ->execute();
  }

  public function executeBrandsSidebarFilter(sfWebRequest $request){//Фильтр по производителю/бренду
    $sqlBody="SELECT  c.id, c.name, c.parents_id, c.slug, count(dip.product_id) as c_count "
    ."FROM dop_info_product dip "
      ."JOIN category_product cp ON cp.product_id=dip.product_id "
      ."JOIN category c ON cp.category_id=c.id "
    ."WHERE dip.dop_info_id = ". $this->current ." AND c.is_public=1 AND c.slug NOT LIKE 'service%' "
    ."GROUP BY c.id, c.name, c.parents_id, c.slug "
    ."";

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $res=$q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_ASSOC);
    foreach ($res as $value) {
      $id=$value['parents_id'] ? $value['parents_id'] : $value['id'];
      $subCats[$id][$value['id']]=$value;
      $parentsIds[$id]='';
    }
    $parentsIds[-1]='';
    $sqlBody="SELECT name, slug, id FROM category WHERE is_public=1 AND id IN(".implode(', ', array_keys($parentsIds)).")";
    $res=$q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_ASSOC);
    $menu[0]=['name' => 'Заголовок фильтра',];
    foreach ($res as $line) {
      if(isset($subCats[$line['id']])) $line['submenu']=$subCats[$line['id']];
      $menu[0]['submenu'][]=$line;
    }
    $this->menu=$menu;

  }

  public function executeBrandsSidebar(sfWebRequest $request){
    $this->brands =  Doctrine_Core::getTable('Manufacturer')->createQuery()
      ->where("is_public='1'")
      // ->addWhere("image IS NOT NULL")
      ->orderBy("NAME")
      // ->limit(15)
      ->execute();
  }

  public function executeLeftMenuBrands(sfWebRequest $request) {
    $this->manufacturers =  Doctrine_Core::getTable('Manufacturer')->createQuery()
      ->where("is_public='1'")
      ->orderBy("position DESC")
      // ->limit(47)
      ->execute();
  }

  public function executeLeftMenuCollections(sfWebRequest $request) {
    $this->collections =  Doctrine_Core::getTable('Collection')->createQuery()
      ->where("is_public='1'")
      ->orderBy("position DESC")
      // ->limit(47)
      ->execute();
  }

  public function executeCatalogsorters(sfWebRequest $request) {

  }

  public function executeCatalogicons(sfWebRequest $request) {

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    if(isset($this->id))
      $sqlBody=
        "SELECT c.name, c.img, c.slug, c.icon_name ".
        "FROM category_catalog cc ".
        "LEFT JOIN category c ON cc.category_id=c.id  OR cc.category_id=c.parents_id ".
        "WHERE cc.catalog_id='".$this->id."' ".
          "AND c.show_in_catalog=1 ".
          "AND c.img<>'' ".
          "AND c.img IS NOT NULL ".
        "ORDER BY c.icon_priority DESC, c.position ASC ".
        "LIMIT 10 ".
        "";
    else{
      $sqlBody=
        "SELECT c.name, c.img, c.slug, c.icon_name ".
        "FROM category c ".
        "LEFT JOIN category cp ON c.parents_id=cp.id ".
        "WHERE cp.slug='".$this->parent_slug."' ".
          "AND c.show_in_catalog=1 ".
          "AND c.img<>'' ".
          "AND c.img IS NOT NULL ".
        "ORDER BY c.icon_priority DESC, c.position ASC ".
        "LIMIT 10 ".
        "";
    }

    if(!isset($this->hide_text)) $this->hide_text=false;
    $icons=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
    $this->icons=$icons;
  }

  //Популярные категории  на страницах каталога
  /*
  'catalog'=> ''
  'category' => ''
  */
  public function executeCatalogPopularCats(sfWebRequest $request) {

  }

  //Фильтры на страницах каталога
  public function executeCatalogfilters(sfWebRequest $request) {
    $this->filtersDB = unserialize($this->filtersCategory['filters']);
    if(!isset($this->isCat)) $this->isCat=false;
    if(!isset($this->isNewby)) $this->isNewby=false;
    if(!isset($this->isFullCatalog)) $this->isFullCatalog=false;
    if(!isset($this->isSale)) $this->isSale=false;
    $sqlBody =
      "SELECT s.`name`, ss.`shop_id` ,COUNT(ss.`product_id`) AS `shop_count` FROM `category_product` cp ".
      "LEFT JOIN `shop_stocks` ss ON ss.`product_id`=cp.`product_id` ".
      "LEFT JOIN `shops` s ON ss.`shop_id`=s.`id1c` ".
      "WHERE cp.category_id ".(is_array($this->filtersCategory['this_id']) ? " IN (". implode(',', $this->filtersCategory['this_id']) .")" : " = " . $this->filtersCategory['this_id']." ").
        "AND s.`is_active`=1 ".
      "GROUP BY ss.`shop_id`, s.`name` ".
      ";";
    $q=Doctrine_Manager::getInstance()->getCurrentConnection();
    $res=$q->execute($sqlBody)->FetchAll(Doctrine_Core::FETCH_ASSOC);
    $this->shopsList=$res;
    // die('<pre>'.print_r([$res, $sqlBody, $this->filtersCategory['this_id'], $this->slug], true));
    // $this->filters = $request->getParameter('filters');
    /*
    $page
    $price = unserialize(get_slot("filtersPrice"));
    $page = unserialize(get_slot("filtersPage"));
    $filtersCountProducts = unserialize(get_slot("filtersCountProducts"));
    */
  }

  /* ********************************************** old **************************************************** */
    /*public function executeCatalog(sfWebRequest $request) {

    }


    public function executeCatalogDev(sfWebRequest $request) {

    }

    public function executeProductsnewmain(sfWebRequest $request) {
      $newsList=csSettings::get('optimization_newProductId');
      $newsListAr=explode(',', $newsList);
      $product=$this->product;

      if ((strtotime($product->getEndaction()) + 86399) < time() and $product->getEndaction() != "") {
          $product->setEndaction(NULL);
          if ($product->getDiscount() > 0) {
              $product->setPrice($product->getOldPrice());
              $product->setOldPrice(Null);
          }
          $product->setDiscount(0);
          $product->setBonuspay(NULL);
          $product->setStep(NULL);
          $product->save();
      }
      if ($photoFilename == "") {
          $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
          $photoFilename = $photos[0]['filename'];
      }
      $this->photoFilename=$photoFilename;
      $this->product=$product;

    }
    public function executeProducts(sfWebRequest $request) {

    }
    public function executeArticleproductpart(sfWebRequest $request){
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      if(!isset($this->not_replace)) $this->not_replace=false;
      $addleftJoin=$addWhere='';
      if(!sizeof($this->ids)) return;
      $productNotIn[]=-1;
      foreach ($this->ids as $id) {
        $productNotIn[]=$id;
        $product=$q->execute(
                  "SELECT p.id, p.id, p.name, p.slug, rating, votes_count, price, "
                  . "old_price, bonuspay, bonus, discount, p.created_at, video, videoenabled, "
                  . "endaction, step, count, p.is_public, p.parents_id, p.code, generalcategory_id, c.name as cat_name "
                  . "FROM product p "
                  . "LEFT JOIN category c ON c.id=p.generalcategory_id "
                  // . $addleftJoin
                  . "WHERE p.id = $id "
                  // . "and product.is_public='1' "
                  . "" . $addWhere . " "
                   )
                ->fetchAll(Doctrine_Core::FETCH_GROUP);
        $product=end($product)[0];
        if(!isset($product['id'])) continue;
        if ($product['count']==0 && !$this->not_replace){
          $product=$q->execute(
                    "SELECT p.id, p.slug, p.id, p.name, p.slug, rating, votes_count, price, "
                    . "old_price, bonuspay, bonus, discount, p.created_at, video, videoenabled, "
                    . "endaction, step, count, p.is_public, p.parents_id, p.code, c.name as cat_name "
                    . "FROM product p "
                    . "LEFT JOIN category c ON c.id=p.generalcategory_id "
                    . "WHERE  generalcategory_id= '".$product['generalcategory_id']."' "
                    . 'AND p.id NOT IN('. implode(',', $productNotIn).') '
                    . "and p.is_public='1' "
                    . "and count>0 "
                     )
                  ->fetchAll(Doctrine_Core::FETCH_GROUP);
                  $product=end($product)[0];
                  if(!isset($product['id'])) continue;
                  $productNotIn[]=$product['id'];
        }
      $products[$product['id']]=$product;
    }
    if(!sizeof($products)) return;
    $this->products=$products;

      $this->productsIdAll = $q->execute(
            "SELECT p.id FROM product as p "
            . "WHERE parents_id in (" . implode(",", array_keys($this->products)) . ") or id in (" . implode(",", array_keys($this->products)) . ") "
            // ." ORDER BY p.count>0 desc"
      )->fetchAll(Doctrine_Core::FETCH_COLUMN);
      $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                      . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                      . "WHERE ppa.product_id in (" . implode(",", $this->productsIdAll) . ") "
                      . "ORDER BY photo.position DESC")
              ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    $this->productNotIn=$productNotIn;

    }
    public function executeArticlecategory(sfWebRequest $request){
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $this->categorys = $q->execute("SELECT  cat.id as this_id, cat.name as this_name, cat.h1 as this_h1, cat.slug as this_slug  "
                      // .", cat.title, cat.keywords, cat.description  "
                      . "FROM category as cat "
                      // . "LEFT JOIN category AS children ON children.parents_id = cat.id "
                      // . "LEFT JOIN category AS parent ON parent.id = cat.parents_id "
                      . "WHERE cat.id =  ? "
                      . "ORDER BY cat.position ASC ", array($this->slug))->fetchAll(Doctrine_Core::FETCH_ASSOC);
      if(!sizeof($this->categorys)) return;
      if (array_keys($this->categorys)[0] != "") {
          $categorysId = implode(",", array_merge(array_keys($this->categorys), array(end($this->categorys)['this_id'])));
      } else {
          $categorysId = array_keys($this->categorys)[0];
      }
      $categorysId=$this->categorys[0]['this_id'];
      $this->categorysId=$categorysId;
      // $categorysId=89;
      $addleftJoin=$addWhere='';
      $addWhere .=' and count>0';

      $this->products = $q->execute(
                "SELECT product.id, name, slug, rating, votes_count, price, "
                . "old_price, bonuspay, bonus, discount, product.created_at, video, videoenabled, "
                . "endaction, step, count, product.is_public, parents_id, code "
                . "FROM product "
                . "LEFT JOIN category_product as cp on product.id=product_id "
                . $addleftJoin
                . "WHERE  cp.category_id IN ( $categorysId ) "
                . "and product.is_public='1' "
                . "AND (parents_id IS NULL or (parents_id IS NOT NULL and is_visiblecategory = '1')) "
                . "" . $addWhere . " "
                . "group by product.id "
                . "LIMIT 18 "
                // ,
                //  $categorysId
                 )
              ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
      if(!sizeof($this->products)) return;
      $this->productsIdAll = $q->execute("SELECT p.id FROM product as p "
                      . "WHERE parents_id in (" . implode(",", array_keys($this->products)) . ") or id in (" . implode(",", array_keys($this->products)) . ") ")
              ->fetchAll(Doctrine_Core::FETCH_COLUMN);
      $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                      . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                      . "WHERE ppa.product_id in (" . implode(",", $this->productsIdAll) . ") "
                      . "ORDER BY photo.position DESC")
              ->fetchAll(Doctrine_Core::FETCH_UNIQUE);

      // $this->$categorysId=$categorysId;
    }

    public function executeProductsnew(sfWebRequest $request) {

    }

    public function executeProductsbestprice(sfWebRequest $request) {

    }

    public function executePaginator(sfWebRequest $request) {

    }

    public function executePaginatorNew(sfWebRequest $request) {

    }


    public function executeChangechildren(sfWebRequest $request) {
        if (!$this->product) {
            $product = Doctrine_Core::getTable('Product')->findOneById(array($this->id));

            $this->product = $product;
        }
    }

    public function executeSliderCategory(sfWebRequest $request) {
        if (implode(",", $this->productsCategory->getPrimaryKeys()) != "") {
            $products = ProductTable::getInstance()->createQuery()->where("id in (" . implode(",", $this->productsCategory->getPrimaryKeys()) . ")")
                    ->addWhere('is_public = \'1\'')
                    ->addWhere('count >0')
                    ->orderBy('countsell DESC')
                    ->limit(10)
                    ->execute();
            $this->products = $products;
        }
         // if ($this->prodId != "") {
         //  $products = ProductTable::getInstance()->createQuery()->where("id in (" . $this->prodId . ")")
         //  ->addWhere('is_public = \'1\'')
         //  ->addWhere('moder = \'0\'')
         //  ->execute();
         //  $this->products = $products;
         //  }
    }*/

}
?>
