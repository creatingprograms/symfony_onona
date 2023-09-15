<?php

/**
 * page actions.
 *
 * @package    Magazin
 * @subpackage page
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pageActions extends sfActions {

  public function __construct($context, $moduleName, $controllerName) {
    parent::__construct($context, $moduleName, $controllerName);

    $this->csrf = new CSRFToken($this);
  }

  public function executeShopsMore(sfWebRequest $request) {
    $this->pagesize=$request->getParameter('page-size', 1);
    if(!$this->pagesize || !$request->getParameter('page', 1) || $request->getParameter('ar_cities', 1)=='') $this->forward404();
    $this->pager = new sfDoctrinePager('shops', $this->pagesize);
    // die($request->getParameter('ar_cities', 1));

    //Готовим массив
    $query =  Doctrine_Core::getTable('Shops')->createQuery('a')
      ->where("is_active='1'")
      ->andWhere('city_id IN('.'-1,'.$request->getParameter('ar_cities', 1).')')
    ;
      // ->orderBy("position DESC");
      // ->limit(47)
      // ->execute();

    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->showMore=[
      'limit'=>$this->pagesize,
      'cities' => $request->getParameter('ar_cities', 1),
    ];

  }

  public function executeShow(sfWebRequest $request) {
    $url=strtolower($request->getParameter('slug'));
    if($url!='main' && $request->getParameter('slug')!=$url)  //Редирект на нижний регистр
      return $this->redirect('/' . strtolower($request->getParameter('slug')), 301);
    $timer = sfTimerManager::getTimer('Action: Переадресация на страницу магазина, если он есть');
    $shop = Doctrine_Core::getTable('Shops')->findOneBySlug(array($request->getParameter('slug')));
    if(!empty($shop))
      return $this->redirect('/shops/' . mb_strtolower($shop->getSlug(), 'utf-8'), 301);
    $timer->addTime();

      $timer = sfTimerManager::getTimer('Action: Установка кодировки');

      mb_internal_encoding('UTF-8');

      $timer->addTime();

                 //Поиск страницы

      $timer = sfTimerManager::getTimer('Action: Получение страницы из базы');
      $this->page = Doctrine_Core::getTable('Page')->findOneBySlug(array($request->getParameter('slug')));

      $timer->addTime();
      if (empty($this->page)) {
          $timer = sfTimerManager::getTimer('Action: find old slug page');

          $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Page' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();

          $timer->addTime();
          if ($oldSlug) {
              $this->page = Doctrine_Core::getTable('Page')->findOneById($oldSlug->getDopid());
              return $this->redirect('/' . $this->page->getSlug(), 301);
          }
      }

       //         Вывод 404 ошибки если есть проблемы

      $timer = sfTimerManager::getTimer('Action: Вывод 404 ошибки если есть проблемы');
      $this->forward404Unless($this->page);
      if (!$this->page->getIsPublic())
          $this->forward404();

      $timer->addTime();
      $this->page->setViewsCount($this->page->getViewsCount()+1);
      $this->page->save();
      // if (trim(strip_tags($this->page->getContentNewVersion()))) $this->page->setContent($this->page->getContentNewVersion());

      /*
       *          Обработка переменных
       */
      $timer = sfTimerManager::getTimer('Action: Обработка переменных');
      @dopFuncPage::variableReplace();

      $timer->addTime();

      /*

      $timer = sfTimerManager::getTimer('Action: Обработка форм комментариев');
      @dopBlockPage::blockAddComment();

      $timer->addTime();*/
  }

  public function executeShopsDetail(sfWebRequest $request){
    if($request->getParameter('slug')!=strtolower($request->getParameter('slug')))  //Редирект на нижний регистр
      return $this->redirect('/shops/' . strtolower($request->getParameter('slug')), 301);
    $this->shop = Doctrine_Core::getTable('Shops')->findOneBySlug(array($request->getParameter('slug')));
    // die('<pre>'.print_r(array($this->shop->getIsActive(), $request->getParameter('slug')), true));
    $this->forward404Unless($this->shop);
    if(!$this->shop->getIsActive()) unset($this->shop);
    $this->forward404Unless($this->shop);
    $re = '/<img.+.+src="(.+)".+\/>/mU';
    $imgCount = preg_match_all($re, $this->shop->getDescription(), $images);
    $this->images=$images;
    if ($this->shop->getPageId()){
      $this->comments = CommentsTable::getInstance()
        ->createQuery()
        ->where("is_public='1' and (page_id = " . $this->shop->getPageId()." or shops_id = ".$this->shop->getId().") ")
        ->execute();
    }
    $cityId=$this->shop->getCityId();
    // Поскольку с крошками жопа - собираем их ручками
    $allShops=[
      'link' => '/adresa-magazinov-on-i-ona-v-moskve-i-mo',
      'text' => 'Магазины «Он и Она» в Москве'
    ];
    if(in_array($catId, [192, 221, 218])) //Крым
      $allShops=[
        'link' => '/set-magazinov-dlya-vzroslyh-on-i-ona-v-krymu',
        'text' => 'Магазины «Он и Она» в Крыму'
      ];
    if(in_array($catId, [17])) //Ростов
      $allShops=[
        'link' => '/set-magazinov-dlya-vzroslyh-eros-v-g-rostov-na-donu',
        'text' => 'Магазины «ЭРОС» в Ростове-на-Дону'
      ];
    if(in_array($catId, [11])) //Ростов
      $allShops=[
        'link' => '/magaziny-on-i-ona-v-krasnodare',
        'text' => 'Магазины «Взрослые подарки» в Краснодаре'
      ];
    if(in_array($catId, [4])) //Ростов
      $allShops=[
        'link' => '/magaziny-on-i-ona-v-sankt-peterburge',
        'text' => 'Магазины «Он и Она» в Санкт-Петербурге'
      ];
    $this->allShops=$allShops;

  }

  public function executeShopsComment(sfWebRequest $request){
    if($_POST['token'] != 'Роботы нам тут не нужны!'){
      return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
    }
    if(!$_POST['agreement']){
      return $this->renderText(json_encode(['error' => 'Необходимо принять пользовательское соглашение']));
    }
    if(!$_POST['fio'])
      return $this->renderText(json_encode(['error' => 'Заполните имя', 'field' => 'fio']));
    if(!$_POST['email'])
      return $this->renderText(json_encode(['error' => 'Заполните email', 'field' => 'email']));
    if(!$_POST['comment'] )
      return $this->renderText(json_encode(['error' => 'Заполните отзыв', 'field' => 'comment']));


    $newComments = new Comments();
    $newComments->setShopsId($_POST["id"]);
    $newComments->setUsername($_POST["fio"]);
    $newComments->setMail($_POST["email"]);
    $newComments->setText($_POST["comment"]);
    $newComments->save();

    return $this->renderText(json_encode([
      'success' => 'Ваш отзыв отправлен на модерацию!',
    ]));
  }

  public function executeSitemap(sfWebRequest $request){

      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $result = $q->execute(
        "SELECT catalog.id AS catid, catalog.name AS catname, catalog.description AS catdescription, catalog.slug AS catslug, category.name, category.slug ".
        "FROM  `catalog` ".
        "LEFT JOIN category_catalog ON category_catalog.catalog_id = catalog.id ".
        "LEFT JOIN category ON category_catalog.category_id = category.id ".
        "WHERE catalog.is_public =  '1' ".
        "AND category.is_public =  '1' ".
        "ORDER BY catalog.position ASC , category.position ASC "
      );

      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $this->categorys = $result->fetchAll();

  }

  public function executeSearchsphinx(sfWebRequest $request) {
    $str = $_GET['search'];
    $dbSph = new DataBaseMysql('127.0.0.1:9306', '', '');
    $this->countArr = $dbSph->SelectRow("select *, 1 as q, count(*) as _count from `onona_index_suggest` WHERE match ('".$this->escapeSphinx($str)."') group by q limit 1000000 OPTION ranker=matchany");
    if ($this->countArr['@count'] > 0) {
      $ids = array();
      $this->itemsTmp = $dbSph->SelectSet("select *, weight() w from `onona_index_suggest` WHERE match ('".$this->escapeSphinx($str)."') ORDER BY w DESC LIMIT 1000000");
      foreach ($this->itemsTmp as $k => $v) {
        $ids[] = $v['id'];
      }
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $result = $q->execute("SELECT * FROM product WHERE id IN (".implode(",", $ids).")");
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $this->products = $result->fetchAll();
    }
    $dbSph->Destroy();
    unset($dbSph);
  }

  public function escapeSphinx($string) {
      $from = array ( '\\', '(',')','|','-','!','@','~','"','&', '/', '^', '$', '=' );
      $to   = array ( '\\\\', '\(','\)','\|','\-','\!','\@','\~','\"', '\&', '\/', '\^', '\$', '\=' );
      return addslashes(str_replace ( $from, $to, $string ));
  }

  public function fillWord(&$arr, $idx = 0) {
        static $line = array();
        static $keys;
        static $max;
        static $results;
        if ($idx == 0) {
            $keys = array_keys($arr);
            $max = count($arr);
            $results = array();
        }
        if ($idx < $max) {
            $values = $arr[$keys[$idx]];
            foreach ($values as $value) {
                array_push($line, $value);
                $this->fillWord($arr, $idx + 1);
                array_pop($line);
            }
        } else {
            $results[] = $line;
        }
        if ($idx == 0)
            return $results;
  }

  public function executeSuggest(sfWebRequest $request) {
    $json = array();
    $json['suggestions'] = array();

    $str = trim(strip_tags($_GET['text']));
    if ($str == 'Поиск') {
      echo json_encode($json);
      die;
    }

    $strSearchSphinx = " match ('".$this->escapeSphinx($str)."')";
    $strOrderBySphinx = "ORDER BY w DESC";

    if (in_array(mb_strtolower($str, 'utf-8'), array('вивайб', 'сатисфаер', 'партнер', 'фанфэктори', 'джо'))) {
      if (preg_match('/^вивайб$/i', mb_strtolower($str, 'utf-8'))) $dopInfoId = 44;
      else if (preg_match('/^сатисфаер$/i', mb_strtolower($str, 'utf-8'))) $dopInfoId = 1240;
      else if (preg_match('/^партнер$/i', mb_strtolower($str, 'utf-8'))) $dopInfoId = 1325;
      else if (preg_match('/^фанфэктори$/i', mb_strtolower($str, 'utf-8'))) $dopInfoId = 861;
      else if (preg_match('/^джо$/i', mb_strtolower($str, 'utf-8'))) $dopInfoId = 844;

      $strSearchSphinx = " dop_info_ids IN (".$dopInfoId.")";
    }

    if (in_array(mb_strtolower($str, 'utf-8'), array('we vibe'))) {
      $strOrderBySphinx = "ORDER BY is_we_vibe DESC, w DESC";
    }

    $dbSph = new DataBaseMysql('127.0.0.1:9306', '', '');
    $this->countArr = $dbSph->SelectRow("select *, 1 as q, count(*) as _count from `onona_index_suggest` WHERE ".$strSearchSphinx." group by q limit 1000000 OPTION ranker=matchany");
    if ($this->countArr['@count'] > 0) {
      $ids = array();
      $this->itemsTmp = $dbSph->SelectSet("select *, weight() w from `onona_index_suggest` WHERE ".$strSearchSphinx." ".$strOrderBySphinx." LIMIT 1000000 OPTION ranker=matchany");
      foreach ($this->itemsTmp as $k => $v) {
        $ids[] = $v['id'];
      }
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $result = $q->execute("SELECT * FROM product WHERE id IN (".implode(",", $ids).") AND generalcategory_id<>135 AND is_public=1 ORDER BY (count>0) DESC, FIELD(id, ".implode(",", $ids).") LIMIT 10");
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $this->products = $result->fetchAll();
    }
    $dbSph->Destroy();
    unset($dbSph);

    if (is_array($this->products) && sizeOf($this->products) > 0) foreach ($this->products as $k => $v) {
      $html = '<a href="/product/'.$v['slug'].'">'.$v['name'].'</a>';
      $json['suggestions'][] = array('value' => $v['name'], 'data' => array('name' => $v['name'], 'html' => $html));
    }

    echo json_encode($json);
    die;
  }

  public function executeError404(sfWebRequest $request) {
    $e404 = new Logs404();
    $e404->setUrl('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

    $e404->setUrlFrom($_SERVER["HTTP_REFERER"]);
    $e404->setIp($_SERVER['REMOTE_ADDR']);
    $e404->save();
    $this->page = Doctrine_Core::getTable('Page')->findOneBySlug('error404');
    $this->forward404Unless($this->page);
    // if (trim(strip_tags($this->page->getContentNewVersion()))) $this->page->setContent($this->page->getContentNewVersion());
    $this->setTemplate('show');
  }

  public function executeSearch(sfWebRequest $request) {
      $searchLog = new SearchLog;
      $searchLog->setText(addslashes($request->getParameter('searchString')));
      $searchLog->save();

      mb_internal_encoding('UTF-8');
      $testQuest = addslashes($request->getParameter('searchString'));
      preg_match_all('/([-a-z-A-Z-а-я-А-Я-0-9]+)/u', $testQuest, $parseText);
      foreach ($parseText[1] as $key => $word) {
          $synonyms = SynonymsTable::getInstance()->createQuery()->where('text like "%' . $word . '%"')->fetchArray();
          if ($synonyms) {
              $allSynWords[$key] = explode(",", $synonyms[0]['text']);
          } else {
              $allSynWords[$key][0] = $word;
          }
      }
      foreach ($this->fillWord($allSynWords) as $queryOption) {
          $queryOptions[] = implode(" ", $queryOption);
      }
      $queryOptions[] = $request->getParameter('searchString');

      foreach ($queryOptions as $keyOptions => $queryOption) {
          if ($keyOptions == 0) {
              $nameOption = "(name like ?";
              $nameArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndContentOption = "(name like ? or content like ?";
              $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cOption = "(name like ? or code like ? or content like ? or id like ? or id = ?";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = addslashes($queryOption);
          } elseif ($keyOptions == (count($queryOptions) - 1)) {
              $nameOption.=" or name like ?)";
              $nameArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndContentOption.= " or name like ? or content like ?)";
              $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cOption .= " or name like ? or code like ? or content like ? or id like ? or id = ?)";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = addslashes($queryOption);
          } else {
              $nameOption.=" or name like ?";
              $nameArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndContentOption.=" or name like ? or content like ?";
              $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndContentArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cOption .= " or name like ? or code like ? or content like ? or id like ? or id = ?";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = "%" . addslashes($queryOption) . "%";
              $nameAndCodeAndContentAndid1cArrOptions[] = addslashes($queryOption);
          }
          if (count($queryOptions) == 1) {
              $nameOption.=")";
              $nameAndContentOption.= ")";
              $nameAndCodeAndContentAndid1cOption.=")";
          }
      }

      $this->categorys = CategoryTable::getInstance()->createQuery()->where($nameOption, $nameArrOptions)->addWhere("is_public=1")->execute();
      $this->articles = ArticleTable::getInstance()->createQuery()->where($nameAndContentOption, $nameAndContentArrOptions)->addWhere("is_public=1")->execute();

        $sqlBody=
          "SELECT p.`id` ".
          "FROM `product` p ".
          "LEFT JOIN `dop_info_product` dip ON p.`id`=dip.`product_id` ".
          "LEFT JOIN `dop_info` di ON dip.`dop_info_id`=di.`id` ".
          "WHERE ".
          	"di.`value` LIKE '%".addslashes($request->getParameter('searchString'))."%' ".
          	"and p.`is_public`=1 ";
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $arrProdIds = $q->execute($sqlBody)->fetchAll();
        if(sizeof($arrProdIds)) foreach ($arrProdIds as $value) {
          $prodIds[]=$value['id'];
        }
        if(sizeof($prodIds))
          $prodIdsWhere='id IN('.implode(', ', $prodIds).') ';
        else
          $prodIdsWhere='id IN(-1) ';

      $strQuerySearch = $request->getParameter('searchString');

      $strSearchSphinx = " match ('".$this->escapeSphinx($strQuerySearch)."')";
      $strOrderBySphinx = "ORDER BY w DESC";

      if (in_array(mb_strtolower($strQuerySearch, 'utf-8'), array('вивайб', 'сатисфаер', 'партнер', 'фанфэктори', 'джо'))) {
        if (preg_match('/^вивайб$/i', mb_strtolower($strQuerySearch, 'utf-8'))) $dopInfoId = 44;
        else if (preg_match('/^сатисфаер$/i', mb_strtolower($strQuerySearch, 'utf-8'))) $dopInfoId = 1240;
        else if (preg_match('/^партнер$/i', mb_strtolower($strQuerySearch, 'utf-8'))) $dopInfoId = 1325;
        else if (preg_match('/^фанфэктори$/i', mb_strtolower($strQuerySearch, 'utf-8'))) $dopInfoId = 861;
        else if (preg_match('/^джо$/i', mb_strtolower($strQuerySearch, 'utf-8'))) $dopInfoId = 844;

        $strSearchSphinx = " dop_info_ids IN (".$dopInfoId.")";
      }

      if (in_array(mb_strtolower($strQuerySearch, 'utf-8'), array('we vibe'))) {
        $strOrderBySphinx = "ORDER BY is_we_vibe DESC, w DESC";
      }

      //Поиск sphinx
      $prodIdsWhereSphinx = '';
      $idsFinded = array();
      $dbSph = new DataBaseMysql('127.0.0.1:9306', '', '');
      $this->countArr = $dbSph->SelectRow("select *, 1 as q, count(*) as _count from `onona_index_suggest` WHERE ".$strSearchSphinx." group by q limit 1000000 OPTION ranker=matchany");
      if ($this->countArr['@count'] > 0) {
        $this->itemsTmp = $dbSph->SelectSet("select *, weight() w from `onona_index_suggest` WHERE ".$strSearchSphinx." ".$strOrderBySphinx." LIMIT 1000000 OPTION ranker=matchany");
        foreach ($this->itemsTmp as $k => $v) {
          $idsFinded[] = $v['id'];
        }
      }
      if(sizeof($idsFinded))
        $prodIdsWhereSphinx='id IN('.implode(', ', $idsFinded).') ';
      else
        $prodIdsWhereSphinx='id IN(-1) ';
      $dbSph->Destroy();
      unset($dbSph);

      $this->pager = new sfDoctrinePager('Product', 12);

      $this->pager->setQuery(
        ProductTable::getInstance()->
        createQuery()->
        where($prodIdsWhereSphinx)->
        addWhere("is_public=1")->
        addWhere("generalcategory_id<>135")->
        orderBy("(count>0) DESC")->
        addOrderBy((sizeOf($idsFinded) > 0 ? "FIELD(id, ".implode(', ', $idsFinded).")" : "id = '".addslashes(str_replace(array("(",")","\"","'","select","union","drop","use","update","insert"), "", $queryOption))."' desc")));
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();

      $this->pages = PageTable::getInstance()->createQuery()->where("name like ?", "%" . addslashes($request->getParameter('searchString')) . "%")->addWhere("is_public=1")->execute();
      $this->manufacturer = ManufacturerTable::getInstance()->createQuery()->where("name like ?", "%" . addslashes($request->getParameter('searchString')) . "%")->addWhere("is_public=1")->execute();
      $this->collections = CollectionTable::getInstance()->createQuery()->where("name like ?", "%" . addslashes($request->getParameter('searchString')) . "%")->addWhere("is_public=1")->execute();
  }

  public function executeComments(sfWebRequest $request) {
    $this->pager = new sfDoctrinePager('comments', 2);
    $this->query = Doctrine_Core::getTable('comments')->createQuery('c')->select('*')->where('is_public = \'1\' and product_id > 0')->addSelect("MAX(created_at) as max")->orderBy("max desc")->groupBy('product_id')/* ->execute() */;

    $this->pager->setQuery($this->query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $result = $q->execute("SELECT c.* , COUNT( com.id ) AS countComment, cat.catalog_id AS catalogId ".
      "FROM  `category` AS c ".
      "LEFT JOIN category AS catPar ON ( catPar.parents_id = c.id ) ".
      "LEFT JOIN product AS prod ON ( prod.generalcategory_id = c.id ".
      "OR prod.generalcategory_id = catPar.id ) ".
      "LEFT JOIN category_catalog AS cat ON ( cat.category_id = c.id ) ".
      "LEFT JOIN comments AS com ON ( com.product_id = prod.id ) ".
      "WHERE ( ".
      "com.is_public =  '1' ".
      "OR com.is_public IS NULL ".
      ") ".
      "AND c.is_public =  '1' ".
      "AND cat.catalog_id IS NOT NULL ".
      "AND ( ".
      "prod.is_public =  '1' ".
      "OR prod.is_public IS NULL ".
      ") ".
      "GROUP BY c.id ".
      "ORDER BY cat.catalog_id ASC , COUNT( com.id ) DESC "
    );

    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $catComments = $result->fetchAll();
    foreach ($catComments as $catComment) {
        //echo $catComment['catalogId'];
        $catCommentsTemp[$catComment['catalogId']][] = $catComment;
    }
    $this->catComments = $catCommentsTemp;
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $result = $q->execute("SELECT COUNT( id ) AS countComment FROM  comments where is_public =  '1'");

    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $this->countComments = $result->fetchAll();

    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $result = $q->execute("SELECT * FROM  catalog where is_public =  '1'");

    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $catNamestmp = $result->fetchAll();
    foreach ($catNamestmp as  $value) {
      $catNames[$value['id']]=$value;
    }
    $this->catNames = $catNames;


  }

  public function executeShowPopup15(sfWebRequest $request) {//Показывает всплываху при запросе
  }

  public function execute18age(sfWebRequest $request) {
      if ($request->getParameter('slug') == "yes") {
          sfContext::getInstance()->getResponse()->setCookie('age18', true, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
          if ($request->getReferer() != "") {
              $this->redirect($request->getReferer());
          } else {
              $this->redirect("/");
          }
      }
      else $this->redirect("/no18");
  }

  public function executeShowPickpoint(sfWebRequest $request) {//Показывает страницу города pickpoint
    $timer = sfTimerManager::getTimer('Action: Установка кодировки');

    mb_internal_encoding('UTF-8');

    $timer->addTime();
    /*
     *          Поиск страницы
     */
    $timer = sfTimerManager::getTimer('Action: Получение страницы из базы');
    $this->city = Doctrine_Core::getTable('City')->findOneBySlug(array($request->getParameter('slug')));

    $this->page = new Page();
    $this->page->setContent($this->city->getPickpointpage());
    $this->page->setCity($this->city);
    $this->page->setIsPublic($this->city->getIsPublic());
    $this->page->setName("Быстрая, выгодная доставка в г. " . $this->city->getName() . " от секс-шопа «Он и Она»");
    $timer->addTime();
    $this->page->setClass('-innerWhite');
    /*          Вывод 404 ошибки если есть проблемы */
   $timer = sfTimerManager::getTimer('Action: Вывод 404 ошибки если есть проблемы');
    $this->forward404Unless($this->page);
    if (!$this->page->getIsPublic())
        $this->forward404();

    $timer->addTime();

    /* Обработка переменных */
    $timer = sfTimerManager::getTimer('Action: Обработка переменных');
    @dopFuncPage::variableReplace();

    $timer->addTime();

    $timer->addTime();
    $this->isPickPoint=true;
    $this->setTemplate("show");
  }

  public function executeOprosniksave(sfWebRequest $request) {
    if($request->getParameter('token') != 'Роботы нам тут не нужны!'){
      return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
    }
    $order = OrdersTable::getInstance()->createQuery()->where("id=?", $request->getParameter('id'))->fetchOne();
    if(!is_object($order))
      return $this->renderText(json_encode(['error' => 'Заказ не найден!']));
    $orderOprosnik = OprosnikTable::getInstance()->findOneByOrderid($order->getId());
    if ($orderOprosnik)
      return $this->renderText(json_encode(['error' => 'Вы уже заполнили форму по этому заказу!']));
    $bonusSumm = 0;
    foreach ($_POST as $keypost => $param) {
      if (!is_array($param) && $param != "" && $keypost != "token" && $keypost != "id") {
          $bonusSumm = $bonusSumm + csSettings::get('bonus_add_oprosnik');
      }
    }
    $rateCount=0;
    $rateSum=0;
    if(isset($_POST['is_recommend'])){
      $rateCount++;
      $rateSum+=$_POST['is_recommend']=='да' ? 5 : 1;
    }
    if(isset($_POST['is_easy'])){
      $rateCount++;
      $rateSum+=$_POST['is_easy']=='да' ? 5 : 1;
    }
    if(isset($_POST['is_items_ok'])){
      $rateCount++;
      $rateSum+=$_POST['is_items_ok']=='да' ? 5 : 1;
    }
    if(isset($_POST['manager_rate'])){
      $rateCount++;
      $rateSum+=$_POST['manager_rate'];
    }
    if(isset($_POST['delivery_rate'])){
      $rateCount++;
      $rateSum+=$_POST['delivery_rate'];
    }
    $data=$_POST;
    unset($data['token']);
    unset($data['id']);
    $ratingShop=$rateCount ? $rateSum/$rateCount : 0;
    $oprosnik = new Oprosnik();
    $oprosnik->setOrderid($order->getId());
    $oprosnik->setDataans(serialize($_POST));
    $oprosnik->setRating($ratingShop);
    $oprosnik->setShop("Интернет магазин");
    $oprosnik->save();

    $emailsfastorder = explode(";", csSettings::get("mail_oprosnik"));
    foreach ($emailsfastorder as $email) {
        $arrayemailsfastorder[$email] = "";
    }
    global $isTest;
    if ($isTest){
      unset($arrayemailsfastorder);
      $arrayemailsfastorder['aushakov@interlabs.ru'] = "";
    }
    $mailText='<table style="width: 100%; border: 0px;">';

    $mailText.=
        '<tr>'.
          '<td>Обратитесь ли вы вновь в магазин onona.ru, порекомендуете ли его друзьям и знакомым?</td>'.
          '<td>'.$_POST['is_recommend'].'</td>'.
        '</tr>';
    if(isset($_POST['is_easy']))
      $mailText.=
          '<tr>'.
            '<td>Удобен ли сайт нашего Интернет-магазина?</td>'.
            '<td>'.$_POST['is_easy'].'</td>'.
          '</tr>';
    $mailText.=
        '<tr>'.
          '<td>Оцените работу менеджера, принявшего и оформившего ваш заказ.</td>'.
          '<td>'.$_POST['manager_rate'].'</td>'.
        '</tr>';
    if(isset($_POST['delivery_rate']))
      $mailText.=
          '<tr>'.
            '<td>Оцените работу курьера, доставившего ваш заказ.</td>'.
            '<td>'.$_POST['delivery_rate'].'</td>'.
          '</tr>';
    if(isset($_POST['is_items_ok']))
      $mailText.=
          '<tr>'.
            '<td>Довольны ли Вы приобретенным товаром?</td>'.
            '<td>'.$_POST['is_items_ok'].'</td>'.
          '</tr>';
    $mailText.=
        '<tr>'.
          '<td colspan="2">'.nl2br($_POST['comment_short']).'</td>'.
        '</tr>'.
      '</table>';

    $message = Swift_Message::newInstance()
            ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
            ->setTo($arrayemailsfastorder)
            ->setSubject("Заполнена анкета. Статус заказа: " . $order->getStatus())
            ->setBody(" Анкету можно посмотреть тут: <a href='https://onona.ru/backend.php/oprosnik/" . $oprosnik->getId() . "/edit'>анкета</a><br><br>" . $mailText)
            ->setContentType('text/html')
    ;
    try {
      $this->getMailer()->send($message);

    } catch (\Exception $e) {

    }


    if ($order->getStatus() == "Оплачен") {
        $bonus = new Bonus();
        $bonus->setBonus($bonusSumm);
        $bonus->setComment("Бонусы за отзыв о магазине по заказу #" . $order->getPrefix() . $order->getId());
        $bonus->setUserId($order->getSfGuardUser());
        $bonus->save();
    }
    $message='<br><br><p style="font-size=16px;">Спасибо! Ваше мнение учтено!<br>';
    if($bonusSumm && $order->getStatus() == "Оплачен") $message.='За Ваши ответы вы получаете '.$bonusSumm.' бонусов<br>';
    $message.='</p>';
    return $this->renderText(json_encode(['error' => $message]));
  }

  public function executeOprosnik(sfWebRequest $request) {
    // die(md5(198086));

    $order = OrdersTable::getInstance()->createQuery()->where("md5(id)=?", $request->getParameter('order'))->fetchOne();
    $this->order = $order;
    $orderOprosnik = OprosnikTable::getInstance()->findOneByOrderid($order->getId());
    $this->orderExist = false;
    if ($orderOprosnik) {
        $this->orderExist = true;
    }

  }
  /* ********************************************** old **************************************************** */



  public function executeOprosnikShop(sfWebRequest $request) {

      $order = OrdersShopTable::getInstance()->createQuery()->select("*")->addSelect("MD5( dopid + checknumber + price ) as md5")->where("MD5( dopid + checknumber + price ) =?", $request->getParameter('order'))->fetchOne();
      $this->order = $order;
      $orderOprosnik = OprosnikTable::getInstance()->findOneByOrderid($order->getMd5());
      $this->orderExist = false;
      /*   $this->errorCap = false;
        $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='o'")->fetchOne(); */
      if ($orderOprosnik) {
          $this->orderExist = true;
      }
      if ($request->isMethod(sfRequest::POST)) {

          if ($_POST['shopBall'] == "Отлично") {
              $ratingShop = 5;
          } else if ($_POST['shopBall'] == "Хорошо") {
              $ratingShop = 4;
          } else if ($_POST['shopBall'] == "Так себе") {
              $ratingShop = 3;
          } else if ($_POST['shopBall'] == "Плохо") {
              $ratingShop = 2;
          } else {
              $ratingShop = 1;
          }
          $oprosnik = new Oprosnik();
          $oprosnik->setOrderid($order->getMd5());
          $oprosnik->setDataans(serialize($_POST));
          $oprosnik->setRating($ratingShop);
          $oprosnik->setShop("Магазин");
          $oprosnik->save();
      }
  }



    public function executeShowRussianPost(sfWebRequest $request) {
        $timer = sfTimerManager::getTimer('Action: Установка кодировки');

        mb_internal_encoding('UTF-8');

        $timer->addTime();
        /*
         *          Поиск страницы
         */
        $timer = sfTimerManager::getTimer('Action: Получение страницы из базы');
        $this->page = Doctrine_Core::getTable('RussianPostCity')->findOneBySlug(array($request->getParameter('slug')));

        $timer->addTime();
        if (empty($this->page)) {
            $timer = sfTimerManager::getTimer('Action: find old slug page');

            $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='RussianPostCity' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();

            $timer->addTime();
            if ($oldSlug) {
                $this->page = Doctrine_Core::getTable('RussianPostCity')->findOneById($oldSlug->getDopid());
                return $this->redirect('/' . $this->page->getSlug(), 301);
            }
        }


        /*
         *          Вывод 404 ошибки если есть проблемы
         */
        $timer = sfTimerManager::getTimer('Action: Вывод 404 ошибки если есть проблемы');
        $this->forward404Unless($this->page);
        if (!$this->page->getIsPublic())
            $this->forward404();

        $timer->addTime();

        /*
         *          Обработка встроенных форм
         */
        $timer = sfTimerManager::getTimer('Action: Обработка встроенных форм');
        @dopFormPage::handlerDopForm($request);

        $timer->addTime();


        /*
         *          Обработка переменных
         */
        $timer = sfTimerManager::getTimer('Action: Обработка переменных');
        @dopFuncPage::variableReplace();

        $timer->addTime();

        // $this->page->setContent(str_replace(, , $this->page->getContent()));

        /*
         *          Получение списка новостей
         */
        $timer = sfTimerManager::getTimer('Action: Получение списка новостей');
        $this->newsBlock = dopBlockPage::getBlockNews($this->page);

        $timer->addTime();


        /*
         *          Обработка форм комментариев
         */
        $timer = sfTimerManager::getTimer('Action: Обработка форм комментариев');
        @dopBlockPage::blockAddComment();

        $timer->addTime();
        $this->setTemplate("show");
    }

}

class DataBaseMysql
{
    var $dbId;

    function DataBaseMysql($host, $user, $password, $database)
    {
        if (!$this->dbId = @mysql_connect($host, $user, $password, true)) die("<b>MySQL</b>: Unable to connect to database");
        if (!mysql_select_db($database)) die("<b>MySQL</b>: Unable to select database <b>" . $database . "</b>");
    }

    function Query($sqlString)
    { /*echo $sqlString."<br />";*/
        if (!$resourseId = @mysql_query($sqlString, $this->dbId)) die("<b>MySQL</b>: Unable to execute<br /><b>SQL</b>: " . $sqlString . "<br /><b>Error (" . mysql_errno() . ")</b>: " . @mysql_error());
        return $resourseId;
    }

    function SelectValue($sqlString)
    {
        $resourseId = DataBaseMysql::Query($sqlString);
        $row = array();
        $row = mysql_fetch_row($resourseId);
        @mysql_free_result($resourseId);
        return $row[0];
    }

    function SelectRow($sqlString)
    {
        $resourseId = DataBaseMysql::Query($sqlString);
        $row = array();
        $row = mysql_fetch_assoc($resourseId);
        @mysql_free_result($resourseId);
        return $row;
    }

    function SelectSet($sqlString, $idTable = '')
    {
        $resourseId = DataBaseMysql::Query($sqlString);
        $row = array();
        while ($rowOne = mysql_fetch_assoc($resourseId)) {
            if ($idTable) $row[$rowOne[$idTable]] = $rowOne; else $row[] = $rowOne;
        }
        @mysql_free_result($resourseId);
        return $row;
    }

    function SelectLastInsertId()
    {
        return @mysql_insert_id($this->dbId);
    }

    function SelectAffectedRows()
    {
        return @mysql_affected_rows($this->dbId);
    }

    function Destroy()
    {
        if (!@mysql_close($this->dbId)) die("<b>MySQL</b>: Cann't disconnect from database");
    }
}
