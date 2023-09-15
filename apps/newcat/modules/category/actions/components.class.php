<?php

class categoryComponents extends sfComponents {

    public function executeCatalog(sfWebRequest $request) {

    }
    public function executeCatalogfilters(sfWebRequest $request) {

    }
    public function executeCatalogicons(sfWebRequest $request) {

      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $sqlBody=
        "SELECT c.name, c.img, c.slug, c.icon_name ".
        "FROM category_catalog cc ".
        "LEFT JOIN category c ON cc.category_id=c.id  OR cc.category_id=c.parents_id ".
        "WHERE cc.catalog_id='".$this->id."' ".
          "AND c.show_in_catalog=1 ".
          "AND c.img<>'' ".
          "AND c.img IS NOT NULL ".
        "LIMIT 7 ".
        "";
      $icons=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
      $this->icons=$icons;
      $this->sql=$sqlBody;
    }
    public function executeCatalogsorters(sfWebRequest $request) {

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
