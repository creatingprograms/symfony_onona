<?php

class categoryComponents extends sfComponents {

  //Показывает слайдер из товаров
  public function executeSliderItems(sfWebRequest $request) {
    $limit=16;
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
          'h2' => 'ПОПУЛЯРНОЕ',
          'link' => '/related',
          'link-name' => 'Все популярные товары',
        ];
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
        $products = ProductTable::getInstance()->createQuery()
          ->where("is_public = '1'")
          ->addWhere('count >0')
          ->orderBy('id DESC')
          ->limit($limit)
          ->execute();
        $this->texts=[
          'h2' => 'НОВИНКИ',
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
          'h2' => 'РЕКОМЕНДУЕМ',
          'link' => '#',
          // 'link-name' => 'Все рекомендуемые товары',
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
      $idIn=array_merge([-1], $this->ids);
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

  //Популярные бренды
  public function executePopularBrands(sfWebRequest $request) {
    //Получаем 10 популярных брендов
    $this->manufacturerPopular =  Doctrine_Core::getTable('Manufacturer')->createQuery()
      ->where("is_public='1'")
      ->addWhere("is_popular='1'")
      ->orderBy("position DESC")
      ->limit(10)
      ->execute();
  }

  //Популярные коллекции
  public function executePopularCollections(sfWebRequest $request) {
    //Получаем 10 популярных коллекций
    $this->collectionPopular =  Doctrine_Core::getTable('Collection')->createQuery()
      ->where("is_public='1'")
      ->addWhere("is_popular='1'")
      ->orderBy("position DESC")
      ->limit(10)
      ->execute();
  }

  public function executeBrandsSidebar(sfWebRequest $request){
    $this->brands =  Doctrine_Core::getTable('Manufacturer')->createQuery()
      ->where("is_public='1'")
      ->addWhere("image IS NOT NULL")
      ->orderBy("RAND()")
      ->limit(2)
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
        "LIMIT 8 ".
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
        "LIMIT 5 ".
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
    // $this->filters = $request->getParameter('filters');
    /*
    $page
    $price = unserialize(get_slot("filtersPrice"));
    $page = unserialize(get_slot("filtersPage"));
    $filtersCountProducts = unserialize(get_slot("filtersCountProducts"));
    */
  }

  /* ********************************************** old **************************************************** */
    public function executeCatalog(sfWebRequest $request) {

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
        /*   if ($this->prodId != "") {
          $products = ProductTable::getInstance()->createQuery()->where("id in (" . $this->prodId . ")")
          ->addWhere('is_public = \'1\'')
          ->addWhere('moder = \'0\'')
          ->execute();
          $this->products = $products;
          } */
    }

}
?>
