<?php

require_once dirname(__FILE__) . '/../lib/productGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/productGeneratorHelper.class.php';

/**
 * product actions.
 *
 * @package    Magazin
 * @subpackage product
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class productActions extends autoProductActions {

    public function __construct($context, $moduleName, $controllerName) {
        parent::__construct($context, $moduleName, $controllerName);
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры"))
            if ($controllerName != "index" and $controllerName != "editseo" and $controllerName != "filter" and $controllerName != "update") {
                $this->redirect("@product");
            }
        if (!sfContext::getInstance()->getUser()->hasPermission("All")
                and ! sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")
                and ! sfContext::getInstance()->getUser()->hasPermission("Модерирование товаров")
                and ! sfContext::getInstance()->getUser()->hasPermission("Просмотр фото и видео товаров")
                and ! sfContext::getInstance()->getUser()->hasPermission("Manager Sravnenie Article")) {
            $this->redirect("@homepage");
        }
    }

    public function executeEditseo(sfWebRequest $request) {
        $this->product = $this->getRoute()->getObject();
        $this->form = new ProductSEOForm($this->product);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->product = $this->getRoute()->getObject();
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")) {
            $this->form = new ProductSEOForm($this->product);
            $this->processForm($request, $this->form);

            $this->setTemplate('editseo');
        } else {
            $this->form = $this->configuration->getForm($this->product);
            $this->processForm($request, $this->form);

            $this->setTemplate('edit');
        };
    }

    public function executeIndex(sfWebRequest $request) {

        // sorting
        if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort'))) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        }

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }

        $filters = $this->getFilters();
        $pager = $this->configuration->getPager('Product');
        if (isset($filters['category_products_list']))
            $pager = new sfDoctrinePager('Product', 1000);
        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        $pager->setQuery($this->buildQuery()
                        ->select("*")
                        ->addSelect($selectDate)
                        ->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product")
                        ->addWhere('parents_id IS NULL ')
                        ->orderBy("new_product < " . csSettings::get('logo_new') . " Desc")
                        ->addOrderBy("(count>0) DESC")
                        ->addOrderBy("position ASC"));

        /* $pager->setQuery($this->buildQuery()
          ->select("*")
          ->addSelect($selectDate)
          ->addWhere('parents_id IS NULL ')
          ->addOrderBy("(count>0) DESC")
          ->addOrderBy("new_product < ".csSettings::get('logo_new')." Desc")); */


        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        $this->sort = $this->getSort();
    }

    public function executeGetcodeproducts(sfWebRequest $request) {
        header('Content-Type: text/html; charset=windows-1251');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-transfer-encoding: binary');
        header('Content-Disposition: attachment; filename=Articles.xls');
        header('Content-Type: application/x-unknown');

        echo '<table>';

        $products = ProductTable::getInstance()->findAll();
        foreach ($products as $product) {
            echo "<tr><td>" . htmlentities(iconv("utf-8", "windows-1251", $product->getCode()), ENT_QUOTES, "cp1251") . "</td></tr>";
        }
        echo '</table>';
        return true;
    }

    public function executeIsnotenabled(sfWebRequest $request) {

        $pager = new sfDoctrinePager('Product', 1000);
        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        $pager->setQuery($this->buildQuery()
                        ->select("*")
                        ->addSelect($selectDate)
                        ->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product")
                        ->addWhere('is_public IS NULL or is_public="0"')
                        ->orderBy("new_product < " . csSettings::get('logo_new') . " Desc")
                        ->addOrderBy("(count>0) DESC")
                        ->addOrderBy("position ASC"));

        /* $pager->setQuery($this->buildQuery()
          ->select("*")
          ->addSelect($selectDate)
          ->addWhere('parents_id IS NULL ')
          ->addOrderBy("(count>0) DESC")
          ->addOrderBy("new_product < ".csSettings::get('logo_new')." Desc")); */


        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        // $this->filters=new sfForm();
        // $this->setTemplate("index");
    }

    public function executeFilter(sfWebRequest $request) {
        $this->setPage(1);

        if ($request->hasParameter('_reset')) {
            $this->setFilters($this->configuration->getFilterDefaults());

            $this->redirect($request->getReferer());
        }

        $this->filters = $this->configuration->getFilterForm($this->getFilters());

        $this->filters->bind($request->getParameter($this->filters->getName()));
        if ($this->filters->isValid()) {
            $this->setFilters($this->filters->getValues());

            $this->redirect($request->getReferer());
        }

        $this->pager = $this->getPager();
        $this->sort = $this->getSort();

        if (substr_count($request->getReferer(), 'moder') > 0) {
            $this->setTemplate('moder');
        } else {
            $this->setTemplate('index');
        }
    }

    public function executeModer(sfWebRequest $request) {

        // sorting
        if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort'))) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        }

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }

        $filters = $this->getFilters();

        $this->filters = new ProductModerFormFilter($filters);
        $this->setFilters($filters);


        $pager = new sfDoctrinePager('Product\Moder', 100);
        $query = ProductTable::getInstance()->createQuery()/* $this->buildQuery() */
                ->select("*")
                ->addWhere('parents_id IS NULL ')
                ->addOrderBy("moder DESC")
                ->addOrderBy("position ASC");

        if ($filters['user'] != "") {
            $query = $query->addWhere("user = '" . $filters['user'] . "'");
        } else {
            $query = $query->addWhere("user <> ''");
        }
        if ($filters['created_at']['from'] != "") {

            $query = $query->addWhere("created_at > '" . $filters['created_at']['from'] . "'");
        }
        if ($filters['created_at']['to'] != "") {

            $query = $query->addWhere("created_at < '" . $filters['created_at']['to'] . "'");
        }

        $pager->setQuery($query);
        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        $this->sort = $this->getSort();
        // throw new Doctrine_Table_Exception('DEBUG <pre/>~|'.print_r(   $filters, true).'|~</pre>');
    }

    public function executeIndexrelated(sfWebRequest $request) {

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }


        $pager = new sfDoctrinePager('Product', 500);

        $pager->setQuery(Doctrine_Core::getTable('Product')
                        ->createQuery('a')
                        ->select("*")
                        ->addWhere('parents_id IS NULL ')
                        ->addWhere('is_related=1 ')
                        ->addOrderBy("positionrelated ASC"));


        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        //$this->sort = $this->getSort();
    }

    public function executeCodeisset(sfWebRequest $request) {
        $this->file = $_FILES['file'];
        $this->artNot = array();
        if (is_array($this->file)) {
            if (is_uploaded_file($this->file['tmp_name'])) {
                $handle = fopen($this->file['tmp_name'], "r");
                while (!feof($handle)) {
                    $buffer = fgets($handle, 4096);
                    $product2 = ProductTable::getInstance()->findOneByCode(trim($buffer));

                    //  echo $buffer.$product2."<br/>";
                    if (!$product2) {
                        $this->artNot[] = $buffer;
                    } else {

                    }
                    $product2 = null;
                    //echo $buffer;
                }
                fclose($handle);
            }
        }
    }

    public function executeCreate(sfWebRequest $request) {
        $this->form = $this->configuration->getForm();
        $this->product = $this->form->getObject();
        $valuesIsPublic = $this->product->getIsPublic();
        $this->processForm($request, $this->form);

        $this->form->setDefault("is_public", $valuesIsPublic);
        //echo $valuesIsPublic;
        //$this->form->UpdateObject();
        $this->setTemplate('new');
    }

    public function executeSitemap(sfWebRequest $request) {
        $sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
<url>
<loc>https://onona.ru/</loc>
<lastmod>" . date('Y-m-d') . "</lastmod>
<changefreq>weekly</changefreq>
<priority>1.0</priority>
</url>";
        $catalog = CategoryTable::getInstance()->findAll();
        foreach ($catalog as $category) {
            if ($category->getId() != '135'):
                $sitemap.="
<url>
<loc>https://onona.ru/category/" . $category->getSlug() . "</loc>
<lastmod>" . date('Y-m-d') . "</lastmod>
<changefreq>weekly</changefreq>
<priority>" . ($category->getSlug() != '' ? '0.6' : '0.4') . "</priority>
</url>";
            // print_r($category);

            endif;
        }
        $products = ProductTable::getInstance()->findAll();
        foreach ($products as $product) {
            $categoryProduct = $product->getCategoryProducts();
            if ($categoryProduct[0]->getId() != 135) {
                $sitemap.="
<url>
<loc>https://onona.ru/product/" . $product->getSlug() . "</loc>
<lastmod>" . date('Y-m-d') . "</lastmod>
<changefreq>weekly</changefreq>
<priority>" . ($product->getSlug() != '' ? '0.6' : '0.4') . "</priority>
</url>";
            }
        }


        $pages = PageTable::getInstance()->findAll();
        foreach ($pages as $page) {
            //echo $page->getName();
            $sitemap.="
<url>
<loc>https://onona.ru/" . $page->getSlug() . "</loc>
<lastmod>" . date('Y-m-d') . "</lastmod>
<changefreq>weekly</changefreq>
<priority>" . ($page->get('sitemapRate') != '' ? $page->get('sitemapRate') : '0.6') . "</priority>
</url>";
        }





        $sitemap.="
<url>
<loc>https://onona.ru/sexopedia</loc>
<lastmod>" . date('Y-m-d') . "</lastmod>
<changefreq>weekly</changefreq>
<priority>1.0</priority>
</url>";
        $artCategory = ArticlecategoryTable::getInstance()->findAll();
        foreach ($artCategory as $page) {
            //echo $page->getName();
            $sitemap.="
<url>
<loc>https://onona.ru/sexopedia/category/" . $page->getSlug() . "</loc>
<lastmod>" . date('Y-m-d') . "</lastmod>
<changefreq>weekly</changefreq>
<priority>0.6</priority>
</url>";
        }
        $article = ArticleTable::getInstance()->findAll();
        foreach ($article as $page) {
            //echo $page->getName();
            $sitemap.="
<url>
<loc>https://onona.ru/sexopedia/" . $page->getSlug() . "</loc>
<lastmod>" . date('Y-m-d') . "</lastmod>
<changefreq>weekly</changefreq>
<priority>0.6</priority>
</url>";
        }

        $sitemap.='</urlset>';
        if (defined("DEBUG_ENV") && DEBUG_ENV===true)
          $file = fopen('/home/i9s/p702/run/web/sitemap.xml', "w+");
        else
          $file = fopen('/var/www/ononaru/data/www/onona.ru/sitemap.xml', "w+");
        $str = $sitemap;
        if (!$file) {
            echo("Ошибка открытия файла");
        } else {
            ftruncate($file, 0);
            fputs($file, $str);
        }
        fclose($file);
    }

    public function executeYandexMarket(sfWebRequest $request) {
        /* $productsBackup = ProductTable::getInstance()->createQuery()->where('price is NULL')->execute();

          foreach($productsBackup as $productBackup){
          $productBDbackup=  ProductForBackUpTable::getInstance()->findOneById($productBackup->getId());
          $productBackup->setPrice($productBDbackup->getPrice());
          $productBackup->setOldPrice($productBDbackup->getOldPrice());
          $productBackup->save();

          }
          exit; */
        ini_set("max_execution_time", 120);

        function prepareText($text) {
            $text = strip_tags($text);
            $text = htmlspecialchars($text);
            return $text;
        }

        $xml_name = 'Он и Она';
        $xml_company = 'ONONA';
        $SITE_NAME = "onona.ru";
        $catalog = CategoryTable::getInstance()->findAll();

        $content = '<?xml version="1.0" encoding="utf-8"?>
				<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';

        $content .= '<yml_catalog date="' . date("Y-m-d H:i:s") . '">';
        $content .= '<shop>';
        $content .= '<name>' . $xml_name . '</name>';
        $content .= '<company>' . $xml_company . '</company>';
        $content .= '<url>http://' . $SITE_NAME . '</url>';
        $content .= '<currencies>';
        $content .= '<currency id="RUR" rate="1" />';
        $content .= '</currencies>';
        $content .= '<categories>';
        foreach ($catalog as $category) {
            if ($category->getId() != 135) {
                $content .= '<category id="' . $category->getId() . '"';
                $content .= '>';
                $content .= $category->getName();
                $content .= '</category>';
            }
        }
        $content .= '</categories>';
        //$content .= '<adult>true</adult>';
        $content .= '<offers>';


        $products = ProductTable::getInstance()->findByYamarket(true);

        foreach ($products as $product) {
            //$categoryProduct = $product->getCategoryProducts();
            if ($product->getGeneralCategory()->getId() != 135) {
                if ($product->getPrice() > 0) {
                    $content .= '<offer id="' . $product->getId() . '" available="';
                    if ($product->getCount() > 1) {
                        $content .= 'true';
                    } else {
                        $content .= 'false';
                    }
                    $content .= '">';
                    $content .= '<url>http://' . $SITE_NAME . '/product/' . $product->getSlug() . '</url>';
                    $content .= '<price>';
                    $content .= $product->getPrice();
                    $content .= '</price>';
                    $content .= '<currencyId>RUR</currencyId>';
                    $content .= '<categoryId>' . $product->getGeneralCategory()->getId() . '</categoryId>';
                    $photoalbum = $product->getPhotoalbums();
                    //$photos = $photoalbum[0]->getPhotos();
                    $photos = PhotoTable::getInstance()->createQuery()->where("album_id='" . $photoalbum[0]->getId() . "'")->orderBy("position")->execute();

                    if (isset($photos[0])) {
                        $content .= '<picture>http://' . $SITE_NAME . '/uploads/photo/' . $photos[0]->getFilename() . '</picture>';
                    }
                    $content .= '<delivery>true</delivery>';
                    $content .= '<name>' . prepareText($product->getName()) . '</name>';
                    $dopInfos = $product->getDopInfoProducts();
                    foreach ($dopInfos as $property):
                        if ($property['name'] == "Производитель") {
                            $content .= '<vendor>' . str_replace("™", "", $property['value']) . '</vendor>';
                        }
                    endforeach;
                    $content .= '<vendorCode>' . str_replace("&", "", $product->getCode()) . '</vendorCode>';
                    $content .= '<description>' . prepareText($product->getContent()) . '</description>';
                    if ($product->getAdult()) {
                        $content .= '<adult>true</adult>';
                    }
                    $content .= '</offer>';
                }
            }
        }
        $content .= '</offers>';
        $content .= '</shop>';
        $content .= '</yml_catalog>';

        $file = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_yaxml.xml', "w+");
        fputs($file, $content, (strlen($content) + 1));
        fclose($file);

        /*
          while ($row = db_fetch_array($res)) {
          $product = new Product($row[0]);
          if ($product->getCategoryId() != 135) {
          $properties = new ProductProperties($product->id);
          if ($product->getPrice() > 0) {
          $content .= '<offer id="' . $product->id . '" available="';
          if ($product->getInstock() > 0) {
          $content .= 'true';
          } else {
          $content .= 'false';
          }
          $content .= '">';
          $content .= '<url>http://' . $SITE_NAME . '/product/' . $product->id . '/</url>';
          $content .= '<price>';
          $content .= $product->getPrice();
          $content .= '</price>';
          $content .= '<currencyId>RUR</currencyId>';
          $content .= '<categoryId>' . $product->getCategoryId() . '</categoryId>';
          $phGall = new PhotoGallery($product->getPhotoGalleryId());
          $photo = $phGall->getPhotos();
          $picture = $photo[0]["filename"];
          $content .= '<picture>http://' . $SITE_NAME . '/photo/' . $picture . '</picture>';
          $content .= '<delivery>true</delivery>';
          $content .= '<name>' . prepareText($product->getName()) . '</name>';
          // print_R($properties);
          if ($properties->getPropertiesSet())
          foreach ($properties->getPropertiesSet() as $propertie) {
          if ($propertie[0]['name'] == "Производитель")
          $content .= '<vendor>' . str_replace("™", "", $propertie[0]['value']) . '</vendor>';
          }
          $content .= '<vendorCode>' . $product->getProductCode() . '</vendorCode>';
          $content .= '<description>' . prepareText($product->getDescription()) . '</description>';
          $content .= '</offer>';
          }
          }
          }
          $content .= '</offers>';
          $content .= '</shop>';
          $content .= '</yml_catalog>';

          $file = fopen(ROOT . '/' . substr($SITE_NAME, 0, -3) . '_yaxml.xml', "w+");
          fputs($file, $content, (strlen($content) + 1));
          fclose($file);
          $smarty->assign("content", $content);
          $smarty->assign("sitename", substr($SITE_NAME, 0, -3));
          $main_content_template = 'yandex/complete.tpl.html'; */
    }

    protected function addSortQuery($query) {
        $query->addOrderBy('position ASC');
    }

    public function executePromote() {
        $object = Doctrine::getTable('Product')->findOneById($this->getRequestParameter('id'));


        $object->promote();
        $this->redirect("@product");
    }

    public function executeDemote() {
        $object = Doctrine::getTable('Product')->findOneById($this->getRequestParameter('id'));

        $object->demote();
        $this->redirect("@product");
    }

    public function executeRelatedChange() {
        $object = Doctrine::getTable('Product')->findOneById($this->getRequestParameter('id'));

        $object->setIsRelated($object->getIsRelated() ? 0 : 1);
        $object->save();
        $this->product = $object;
    }

    public function executePublicChange() {
        $object = Doctrine::getTable('Product')->findOneById($this->getRequestParameter('id'));

        $object->setIsPublic($object->getIsPublic() ? 0 : 1);
        $object->save();
        $this->product = $object;
    }

    public function executeAdultChange() {
        $object = Doctrine::getTable('Product')->findOneById($this->getRequestParameter('id'));

        $object->setAdult($object->getAdult() ? 0 : 1);
        $object->save();
        $this->product = $object;
    }

    public function executeModerChange() {
        $object = Doctrine::getTable('Product')->findOneById($this->getRequestParameter('id'));

        $object->setModer($object->getModer() ? 0 : 1);
        $object->setModeruser(sfContext::getInstance()->getUser()->getGuardUser()->getId());
        $object->save();
        $this->product = $object;
    }

    public function executeSetFilterTag() {
        $this->setPage(1);
        $filtersTag['category_products_list'] = array("0" => $this->getRequestParameter('filter'));

        $this->setFilters($filtersTag);

        $this->redirect('@product');
    }

    public function executeSortChange() {
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;

            $productsEnd = ProductTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $product = ProductTable::getInstance()->findOneById($rowId);
            $product->setPosition($productsEnd[0]->getPosition() + 1);
            $product->save();


            $productsUpSO = ProductTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();

            foreach ($productsUpSO as $product) {
                $product->setPosition($product->getPosition() - 1);
                $product->save();
            }

            $product = ProductTable::getInstance()->findOneById($rowId);
            $product->setPosition($rowsList[$last]);
            $product->save();
            //die;
        }
        if ($currentSortOrder > $nextSortOrder) { //	перемещение вверх
            /* $productsUpSO = ProductTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($productsUpSO as $product) {
              $product->setPosition($product->getPosition() + 1);
              $product->save();
              }
              $product = ProductTable::getInstance()->findOneById($rowId);
              $product->setPosition($nextSortOrder);
              $product->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE product SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");
            $product = ProductTable::getInstance()->findOneById($rowId);
            $product->setPosition($nextSortOrder);
            $product->save();
        }
        if ($currentSortOrder < $nextSortOrder) { //	перемещение вниз
            /* $productsUpSO = ProductTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($productsUpSO as $product) {
              $product->setPosition($product->getPosition() + 1);
              $product->save();
              }
              $product = ProductTable::getInstance()->findOneById($rowId);
              $product->setPosition($nextSortOrder);
              $product->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE product SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");

            $product = ProductTable::getInstance()->findOneById($rowId);
            $product->setPosition($nextSortOrder);
            $product->save();
        }


        $pager = $this->configuration->getPager('Product');
        /* $pager->setQuery($this->buildQuery()->select("*")->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product")->addWhere('parents_id IS NULL ')->orderBy("new_product ASC")->addOrderBy("(count>0) DESC")->addOrderBy("position ASC"));
         */

        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product";
        $pager->setQuery($this->buildQuery()
                        ->select("*")
                        ->addSelect($selectDate)
                        ->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_product")
                        ->addWhere('parents_id IS NULL ')
                        ->orderBy("new_product < " . csSettings::get('logo_new') . " Desc")
                        ->addOrderBy("(count>0) DESC")
                        ->addOrderBy("position ASC"));
        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        $this->sort = $this->getSort();
    }

    public function executePhotosSortChange() {
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;
            $photosEnd = PhotoTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $photo = PhotoTable::getInstance()->findOneById($rowId);
            $photo->setPosition($photosEnd[0]->getPosition() + 1);
            $photo->save();

            $photosUpSO = PhotoTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();

            foreach ($photosUpSO as $photo) {
                $photo->setPosition($photo->getPosition() - 1);
                $photo->save();
            }

            $photo = PhotoTable::getInstance()->findOneById($rowId);
            $photo->setPosition($rowsList[$last]);
            $photo->save();
            echo $photo->getPosition();
        }
        if ($currentSortOrder > $nextSortOrder and $nextSortOrder != "") { //	перемещение вверх
            /* $photosUpSO = PhotoTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($photosUpSO as $photo) {
              $photo->setPosition($photo->getPosition() + 1);
              $photo->save();
              } */
            /* $q = Doctrine_Query::create()
              ->update('Photo')
              ->set('position=position+1')
              ->where('position >= ?', $nextSortOrder)
              ->orderBy("position DESC")
              ->execute(); */
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE photo SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");
            $photo = PhotoTable::getInstance()->findOneById($rowId);
            $photo->setPosition($nextSortOrder);
            $photo->save();
        }
        if ($currentSortOrder < $nextSortOrder) { //	перемещение вниз
            /* $photosUpSO = PhotoTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($photosUpSO as $photo) {
              $photo->setPosition($photo->getPosition() + 1);
              $photo->save();
              } */
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE photo SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");

            $photo = PhotoTable::getInstance()->findOneById($rowId);
            $photo->setPosition($nextSortOrder);
            $photo->save();
        }
        $this->product = ProductTable::getInstance()->findOneById($_POST["productId"]);
        $this->form = new ProductForm($this->product);
    }

    public function executeSortChangerelated() {
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;

            $productsEnd = ProductTable::getInstance()->createQuery()->orderBy("positionrelated DESC")->Limit(1)->execute();
            $product = ProductTable::getInstance()->findOneById($rowId);
            $product->setPositionrelated($productsEnd[0]->getPositionrelated() + 1);
            $product->save();


            $productsUpSO = ProductTable::getInstance()->createQuery()->where("positionrelated >?", $currentSortOrder)->andWhere("positionrelated <=?", $rowsList[$last])->orderBy("positionrelated ASC")->execute();

            foreach ($productsUpSO as $product) {
                $product->setPositionrelated($product->getPositionrelated() - 1);
                $product->save();
            }

            $product = ProductTable::getInstance()->findOneById($rowId);
            $product->setPositionrelated($rowsList[$last]);
            $product->save();
            //die;
        }
        if ($currentSortOrder > $nextSortOrder) { //	перемещение вверх
            /* $productsUpSO = ProductTable::getInstance()->createQuery()->where("positionrelated >=?", $nextSortOrder)->orderBy("positionrelated DESC")->execute();
              foreach ($productsUpSO as $product) {
              $product->setPositionrelated($product->getPositionrelated() + 1);
              $product->save();
              }
              $product = ProductTable::getInstance()->findOneById($rowId);
              $product->setPositionrelated($nextSortOrder);
              $product->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE product SET positionrelated = positionrelated+1  WHERE (positionrelated >= '" . $nextSortOrder . "') ORDER BY positionrelated DESC");
            $product = ProductTable::getInstance()->findOneById($rowId);
            $product->setPositionrelated($nextSortOrder);
            $product->save();
        }
        if ($currentSortOrder < $nextSortOrder) { //	перемещение вниз
            /* $productsUpSO = ProductTable::getInstance()->createQuery()->where("positionrelated >=?", $nextSortOrder)->orderBy("positionrelated DESC")->execute();
              foreach ($productsUpSO as $product) {
              $product->setPositionrelated($product->getPositionrelated() + 1);
              $product->save();
              }
              $product = ProductTable::getInstance()->findOneById($rowId);
              $product->setPositionrelated($nextSortOrder);
              $product->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE product SET positionrelated = positionrelated+1  WHERE (positionrelated >= '" . $nextSortOrder . "') ORDER BY positionrelated DESC");

            $product = ProductTable::getInstance()->findOneById($rowId);
            $product->setPositionrelated($nextSortOrder);
            $product->save();
        }



        $pager = new sfDoctrinePager('Product', 500);

        $pager->setQuery(Doctrine_Core::getTable('Product')
                        ->createQuery('a')
                        ->select("*")
                        ->addWhere('parents_id IS NULL ')
                        ->addWhere('is_related=1 ')
                        ->addOrderBy("positionrelated ASC"));


        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {

            if (!$form->getObject()->isNew()) {
              global $isTest;
                sfCacheManager::clearCache('@sf_cache_partial?module=category&action=_products&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                sfCacheManager::clearCache('@sf_cache_partial?module=category&action=_changechildren&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                sfCacheManager::clearCache('@sf_cache_partial?module=product&action=_params&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                sfCacheManager::clearCache('@sf_cache_partial?module=product&action=_stock&sf_cache_key=' . ($form->getObject()->getId()) . '*');

                /* $prev_sfconfig = sfConfig::getAll();
                  $currentConfiguration = sfContext::getInstance()->getConfiguration();

                  $env_types[] = $currentConfiguration->getEnvironment();
                  $prev_application = $currentConfiguration->getApplication();
                  $is_debug = $currentConfiguration->isDebug();
                  //sfContext::switchTo('newcat');
                  ini_set("display_errors",1);
                  error_reporting(E_ALL);
                  $configuration = ProjectConfiguration::getApplicationConfiguration("newcat", "dev",true);
                  $sfContextInstance = sfContext::createInstance($configuration, sprintf('system_%s', "dev"));
                  $cacheManager = $sfContextInstance->getViewCacheManager();
                  if ($cacheManager) {
                  $cacheManager->remove('@sf_cache_partial?module=category&action=_products&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                  $cacheManager->remove('@sf_cache_partial?module=category&action=_changechildren&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                  $cacheManager->remove('@sf_cache_partial?module=product&action=_params&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                  $cacheManager->remove('@sf_cache_partial?module=product&action=_stock&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                  }
                  $configuration = ProjectConfiguration::getApplicationConfiguration("newcat", "prod", false);
                  $sfContextInstance = sfContext::createInstance($configuration, sprintf('system_%s', "prod"));
                  $cacheManager = $sfContextInstance->getViewCacheManager();
                  if ($cacheManager) {
                  $cacheManager->remove('@sf_cache_partial?module=category&action=_products&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                  $cacheManager->remove('@sf_cache_partial?module=category&action=_changechildren&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                  $cacheManager->remove('@sf_cache_partial?module=product&action=_params&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                  $cacheManager->remove('@sf_cache_partial?module=product&action=_stock&sf_cache_key=' . ($form->getObject()->getId()) . '*');
                  }
                  if (sfContext::hasInstance($prev_application)) {
                  sfContext::switchTo($prev_application);
                  $sf_config_diff = array_diff($prev_sfconfig, sfConfig::getAll());
                  sfConfig::add($sf_config_diff);
                  } */
                //sfContext::switchTo('backend');
            }
            /*
             * 02.07.15
             */
//            $frontend_cache_dir = '/var/www/ononaru/data/www/cache/frontend/*/template/*/all';
//            $cache = new sfFileCache(array('cache_dir' => $frontend_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
//            $cache->removePattern('/sf_cache_partial/category/__products/sf_cache_key/' . $form->getObject()->getId());
//            $cache->removePattern('/product/show/slug/' . $form->getObject()->getSlug());
//            $newdis_cache_dir = '/var/www/ononaru/data/www/cache/newdis/*/template/*/all';
//            $cache = new sfFileCache(array('cache_dir' => $newdis_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
//            $cache->removePattern('/sf_cache_partial/category/__products/sf_cache_key/' . $form->getObject()->getId());
//            $cache->removePattern('/product/show/slug/' . $form->getObject()->getSlug());
//
//            $newdis_cache_dir = '/var/www/ononaru/data/www/cache/newdis/*/template/*/all';
//            $cache = new sfFileCache(array('cache_dir' => $newdis_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
//            $cache->removePattern('/sf_cache_partial/product/__params/sf_cache_key/' . $form->getObject()->getId());

            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {
                $valuesForm = $form->getValues();
                if ($form->getObject()->isNew() and $valuesForm['is_public'] == true) {

                    $notifications = NotificationCategoryTable::getInstance()->createQuery()->where("category_id='300000'")->addWhere("is_enabled='1'")->execute();
                    if (!$isTest) foreach ($notifications as $notification) {
                        if ($notification->getSfGuardUser()) {
                            $emailAddress = $notification->getSfGuardUser()->getEmailAddress();
                        } else {
                            $emailAddress = $notification->getUserMail();
                        }

                        if (preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $emailAddress)) {
                            $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_category');
                            $mailTemplate->setText(str_replace('{categoryLink}', '<a href="https://onona.ru/newprod">Новые товары</a>', $mailTemplate->getText()));
                            $mailTemplate->setText(str_replace('{categoryButtons}', '<a href="https://onona.ru/newprod" target="_blank" style="width: 211px;
    height: 40px;
    background-image: url(\'https://onona.ru/images/newcat/imagesMail/button4.png\');
    margin: auto;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    text-align: center;
    display: block;"></a>', $mailTemplate->getText()));
                            $UserNotification = $notification->getSfGuardUser();
                            $mailTemplate->setText(str_replace('{username}', $UserNotification->getFirstName(), $mailTemplate->getText()));

                            /* $mailTemplate->setText(str_replace('{nameCustomer}', $user->getFirstName(), $mailTemplate->getText()));
                              $mailTemplate->setText(str_replace('{bonusCustomer}', $this->bonusCount, $mailTemplate->getText()));
                              $mailTemplate->setText(str_replace('{summOrder}', $TotalSumm, $mailTemplate->getText()));
                              $mailTemplate->setText(str_replace('{bonusPayOrder}', ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
                              $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm) - $bonusDropCost, $mailTemplate->getText()));
                              $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));
                              $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText())); */


                            $message = Swift_Message::newInstance()
                                    ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                                    ->setTo($emailAddress)
                                    ->setSubject($mailTemplate->getSubject())
                                    ->setBody($mailTemplate->getText())
                                    ->setContentType('text/html');

                            try{
                              $numSent = $this->getMailer()->send($message);
                            }
                            catch(Exception $e){
                              $numSent =0;
                              //Как обрабатывать - ХЗ
                            }

                            $mailLog = new MailsendLog();
                            $mailLog->set("comment", "Письмо-уведомление о новом товаре в категории <br>Почта: " . $emailAddress);
                            $mailLog->save();
                        }
                    }

                    foreach ($valuesForm['category_products_list'] as $categoryId) {
                        $category = CategoryTable::getInstance()->findOneById($categoryId);
                        $notifications = NotificationCategoryTable::getInstance()->createQuery()->where("category_id='" . $categoryId . "'")->addWhere("is_enabled='1'")->execute();
                        if (!$isTest) foreach ($notifications as $notification) {
                            if ($notification->getSfGuardUser()) {
                                $emailAddress = $notification->getSfGuardUser()->getEmailAddress();
                            } else {
                                $emailAddress = $notification->getUserMail();
                            }
                            if (preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $emailAddress)) {
                                $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_category');
                                $mailTemplate->setText(str_replace('{categoryLink}', '<a href="https://onona.ru/category/' . $category->getSlug() . '">' . $category->getName() . '</a>', $mailTemplate->getText()));
                                $mailTemplate->setText(str_replace('{categoryButtons}', '<a href="https://onona.ru/category/' . $category->getSlug() . '" target="_blank" style="width: 211px;
    height: 40px;
    background-image: url(\'https://onona.ru/images/newcat/imagesMail/button4.png\');
    margin: auto;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    text-align: center;
    display: block;"></a>', $mailTemplate->getText()));
                                $UserNotification = $notification->getSfGuardUser();
                                $mailTemplate->setText(str_replace('{username}', $UserNotification->getFirstName(), $mailTemplate->getText()));

                                /* $mailTemplate->setText(str_replace('{nameCustomer}', $user->getFirstName(), $mailTemplate->getText()));
                                  $mailTemplate->setText(str_replace('{bonusCustomer}', $this->bonusCount, $mailTemplate->getText()));
                                  $mailTemplate->setText(str_replace('{summOrder}', $TotalSumm, $mailTemplate->getText()));
                                  $mailTemplate->setText(str_replace('{bonusPayOrder}', ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
                                  $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm) - $bonusDropCost, $mailTemplate->getText()));
                                  $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));
                                  $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText())); */


                                $message = Swift_Message::newInstance()
                                        ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                                        ->setTo($emailAddress)
                                        ->setSubject($mailTemplate->getSubject())
                                        ->setBody($mailTemplate->getText())
                                        ->setContentType('text/html');

                                $numSent = $this->getMailer()->send($message);

                                $mailLog = new MailsendLog();
                                $mailLog->set("comment", "Письмо-уведомление о новом товаре в категории <br>Почта: " . $emailAddress);
                                $mailLog->save();
                            }
                        }
                    }

                    if (!$isTest) foreach ($valuesForm['dop_info_products_list'] as $dopInfoId) {
                        unset($collection);
                        $collection = CollectionTable::getInstance()->findOneBySubid($dopInfoId);
                        if ($collection) {
                            if ($collection->getId() > 0) {
                                $notifications = NotificationCategoryTable::getInstance()->createQuery()->where("category_id='" . ($collection->getId() + 100000) . "'")->addWhere("is_enabled='1'")->execute();
                                foreach ($notifications as $notification) {
                                    if ($notification->getSfGuardUser()) {
                                        $emailAddress = $notification->getSfGuardUser()->getEmailAddress();
                                    } else {
                                        $emailAddress = $notification->getUserMail();
                                    }
                                    if (preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $emailAddress)) {
                                        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_category');
                                        $mailTemplate->setText(str_replace('{categoryLink}', '<a href="https://onona.ru/collection/' . $collection->getSlug() . '">' . $collection->getName() . '</a>', $mailTemplate->getText()));
                                        $mailTemplate->setText(str_replace('{categoryButtons}', '<a href="https://onona.ru/collection/' . $collection->getSlug() . '" target="_blank" style="width: 211px;
    height: 40px;
    background-image: url(\'https://onona.ru/images/newcat/imagesMail/button4.png\');
    margin: auto;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    text-align: center;
    display: block;"></a>', $mailTemplate->getText()));
                                        $UserNotification = $notification->getSfGuardUser();
                                        $mailTemplate->setText(str_replace('{username}', $UserNotification->getFirstName(), $mailTemplate->getText()));
                                        /* $mailTemplate->setText(str_replace('{nameCustomer}', $user->getFirstName(), $mailTemplate->getText()));
                                          $mailTemplate->setText(str_replace('{bonusCustomer}', $this->bonusCount, $mailTemplate->getText()));
                                          $mailTemplate->setText(str_replace('{summOrder}', $TotalSumm, $mailTemplate->getText()));
                                          $mailTemplate->setText(str_replace('{bonusPayOrder}', ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
                                          $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm) - $bonusDropCost, $mailTemplate->getText()));
                                          $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));
                                          $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText())); */


                                        $message = Swift_Message::newInstance()
                                                ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                                                ->setTo($emailAddress)
                                                ->setSubject($mailTemplate->getSubject())
                                                ->setBody($mailTemplate->getText())
                                                ->setContentType('text/html');

                                        $numSent = $this->getMailer()->send($message);

                                        $mailLog = new MailsendLog();
                                        $mailLog->set("comment", "Письмо-уведомление о новом товаре в коллекции <br>Почта: " . $emailAddress);
                                        $mailLog->save();
                                    }
                                }
                                unset($collection);
                            }
                        }
                        unset($manufacturer);
                        $manufacturer = ManufacturerTable::getInstance()->findOneBySubid($dopInfoId);
                        if (!$isTest) if ($manufacturer) {
                            if ($manufacturer->getId() > 0) {
                                $notifications = NotificationCategoryTable::getInstance()->createQuery()->where("category_id='" . ($manufacturer->getId() + 200000) . "'")->addWhere("is_enabled='1'")->execute();
                                foreach ($notifications as $notification) {
                                    if ($notification->getSfGuardUser()) {
                                        $emailAddress = $notification->getSfGuardUser()->getEmailAddress();
                                    } else {
                                        $emailAddress = $notification->getUserMail();
                                    }
                                    $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_category');
                                    $mailTemplate->setText(str_replace('{categoryLink}', '<a href="https://onona.ru/manufacturer/' . $manufacturer->getSlug() . '">' . $manufacturer->getName() . '</a>', $mailTemplate->getText()));
                                    $mailTemplate->setText(str_replace('{categoryButtons}', '<a href="https://onona.ru/manufacturer/' . $manufacturer->getSlug() . '" target="_blank" style="width: 211px;
    height: 40px;
    background-image: url(\'https://onona.ru/images/newcat/imagesMail/button4.png\');
    margin: auto;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    text-align: center;
    display: block;"></a>', $mailTemplate->getText()));
                                    $UserNotification = $notification->getSfGuardUser();
                                    $mailTemplate->setText(str_replace('{username}', $UserNotification->getFirstName(), $mailTemplate->getText()));
                                    /* $mailTemplate->setText(str_replace('{nameCustomer}', $user->getFirstName(), $mailTemplate->getText()));
                                      $mailTemplate->setText(str_replace('{bonusCustomer}', $this->bonusCount, $mailTemplate->getText()));
                                      $mailTemplate->setText(str_replace('{summOrder}', $TotalSumm, $mailTemplate->getText()));
                                      $mailTemplate->setText(str_replace('{bonusPayOrder}', ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
                                      $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm) - $bonusDropCost, $mailTemplate->getText()));
                                      $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));
                                      $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText())); */


                                    $message = Swift_Message::newInstance()
                                            ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                                            ->setTo($emailAddress)
                                            ->setSubject($mailTemplate->getSubject())
                                            ->setBody($mailTemplate->getText())
                                            ->setContentType('text/html');

                                    $numSent = $this->getMailer()->send($message);

                                    $mailLog = new MailsendLog();
                                    $mailLog->set("comment", "Письмо-уведомление о новом товаре в коллекции <br>Почта: " . $emailAddress);
                                    $mailLog->save();
                                }
                            }
                            unset($manufacturer);
                        }
                    }
                } else {
                    $product = ProductTable::getInstance()->findOneById($form->getObject()->getId());
                    if (($valuesForm['discount'] > 0 and $product->getDiscount() != $valuesForm['discount']) or ( $valuesForm['bonuspay'] > 0 and $product->getBonuspay() != $valuesForm['bonuspay'])) {
                        $notifications = NotificationTable::getInstance()->createQuery()->where("type='Action'")->addWhere("product_id='" . $form->getObject()->getId() . "' or product_id is null")->execute();
                        if (!$isTest) foreach ($notifications as $notification) {
                            $UserNotification = $notification->getSfGuardUser();
                            $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_action');
                            $mailTemplate->setText(str_replace('{username}', $UserNotification->getFirstName(), $mailTemplate->getText()));
                            $mailTemplate->setText(str_replace('{prodLink}', '<a href="https://onona.ru/product/' . $product->getSlug() . '">' . $product->getName() . '</a>', $mailTemplate->getText()));
                            $mailTemplate->setText(str_replace('{actionDescription}', ($valuesForm['discount'] > 0) ? '<a href="https://onona.ru/category/Luchshaya_tsena">Лучшая цена</a> - скидка ' . $valuesForm['discount'] . '%' : '<a href="https://onona.ru/category/upravlyai-cenoi">Управляй ценой</a> - оплата бонусами до ' . $valuesForm['bonuspay'] . '%', $mailTemplate->getText()));

                            $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();

                            $prodDescription = '    <div style="  border: 1px solid #e0e0e0;
         width: 300px;
         height: 300px;
         /* margin: 5px; */
         cursor: pointer;
         text-align: center;
         display: inline-block;
         vertical-align: middle;
         float: left;">
        <a href="https://onona.ru/product/' . $product->getSlug() . '"><img src="https://onona.ru/uploads/photo/' . $photos[0]->getFilename() . '" style="max-width: 300px; max-height: 300px;"></a>
    </div>
    <div style="  border: 0px solid #e0e0e0;
         width: 378px;
         margin-left: 20px;
         height: 300px;
         /* margin: 5px; */
         cursor: pointer;
         display: inline-block;
         vertical-align: middle;
         float: left;">
        <a href="https://onona.ru/product/' . $product->getSlug() . '"><span style="color: #c3060e;font: 14px/18px Tahoma, Geneva, sans-serif;margin-top: -4px;">' . $product->getName() . '</span></a><br />
<br />';
                            if ($valuesForm['discount'] > 0) {

                                $prodDescription .= '        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость сегодня:</div>
                <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop=price>' . round($valuesForm['price'] - ($valuesForm['price'] * $valuesForm['discount'] / 100)) . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость без скидки:</div>
                <div style="float: left;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;">' . $product->getPrice() . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Экономия:</div>
                <div style="float: left;font-size: 16px; color: #414141;">' . number_format($product->getPrice() - round($valuesForm['price'] - ($valuesForm['price'] * $valuesForm['discount'] / 100)), 0, '', ' ') . ' р.</div>


    ';
                            } else {
                                $prodDescription .= '      <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость с учетом бонусов:</div>
        <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop="price">' . number_format($product->getPrice() - $product->getPrice() * $valuesForm['bonuspay'] / 100, 0, '', ' ') . ' р.</span></div>
        <div style="clear:both;  height: 10px;"></div>
        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Полная стоимость:</div>
        <div style="float: left;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;">' . number_format($product->getPrice(), 0, '', ' ') . ' р.</span></div>
        <div style="clear:both;  height: 10px;"></div>
        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Оплата бонусами:</div>
        <div style="float: left;font-size: 16px; color: #414141;">' . number_format($product->getPrice() * $valuesForm['bonuspay'] / 100, 0, '', ' ') . ' р.</div>

    ';
                            }

                            $prodDescription .= '<div style="clear:both">
        &nbsp;</div>
    <a href="https://onona.ru/product/' . $product->getSlug() . '" target="_blank" style="width: 211px;
    height: 40px;
    background-image: url(\'https://onona.ru/images/newcat/imagesMail/button1.png\');
    margin: auto;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    text-align: center;
    display: block;"></a>
    </div>';

                            $mailTemplate->setText(str_replace('{prodDescription}', $prodDescription, $mailTemplate->getText()));
                            /* $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm) - $bonusDropCost, $mailTemplate->getText()));
                              $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));
                              $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText())); */


                            $message = Swift_Message::newInstance()
                                    ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                                    ->setTo($notification->getSfGuardUser()->getEmailAddress())
                                    ->setSubject($mailTemplate->getSubject())
                                    ->setBody($mailTemplate->getText())
                                    ->setContentType('text/html');

                            $numSent = $this->getMailer()->send($message);

                            $mailLog = new MailsendLog();
                            $mailLog->set("comment", "Письмо-уведомление о акции на товар <br>Почта: " . $notification->getSfGuardUser()->getEmailAddress());
                            $mailLog->save();
                        }
                    }
                }
                $product = $form->save();
                $product->set("pointcreate", "admin");
                $product->save();
            } catch (Doctrine_Validator_Exception $e) {

                $errorStack = $form->getObject()->getErrorStack();

                $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
                foreach ($errorStack as $field => $errors) {
                    $message .= "$field (" . implode(", ", $errors) . "), ";
                }
                $message = trim($message, ', ');

                $this->getUser()->setFlash('error', $message);
                return sfView::SUCCESS;
            }

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $product)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@product_new');
            } else {
                $this->getUser()->setFlash('notice', $notice);

                $this->redirect(array('sf_route' => 'product_edit', 'sf_subject' => $product));
            }
        } else {
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
    }

}
