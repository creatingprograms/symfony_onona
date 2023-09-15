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

  public function executeShowPopup15(sfWebRequest $request) {//Показывает всплываху при запросе
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

  public function executeSitemap(sfWebRequest $request){

            // $this->catalog = CatalogTable::getInstance()
            // ->createQuery()
            // ->where("is_public='1'")
            // ->orderBy('position')
            // ->execute();

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
      // $this->catalog = Doctrine_Core::getTable('Catalog')->createQuery()->execute();
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


  public function executeTest(sfWebRequest $request) {

        /* mb_internal_encoding('UTF-8');
          $testQuest = "Перезаряжаемый махинатор";
          preg_match_all('/([a-zA-Zа-яА-Я]+)/u', $testQuest, $parseText);
          foreach ($parseText[1] as $key => $word) {
          $synonyms = SynonymsTable::getInstance()->createQuery()->where('text like "%' . $word . '%"')->fetchArray();
          if ($synonyms) {
          $allSynWords[$key] = explode(",", $synonyms[0]['text']);
          } else {
          $allSynWords[$key][0] = $word;
          }
          }
          foreach($this->fillWord($allSynWords) as $queryOption){
          $queryOptions[]=  implode(" ", $queryOption);
          }

          print_r($queryOptions);
          $time = microtime(true) - $start;


          $timer = sfTimerManager::getTimer('Action: Тест перебора');
          for ($i = 0; $i < 10; $i++) {
          for ($p = 0; $p < 10; $p++) {

          for ($x = 0; $x < 10; $x++) {
          //echo $i . $p . $x;
          }
          }
          }
          $timer->addTime(); */

        /*
          http://onona.ru/oprosnik/d5acadf1b2e7b66ec4c8e46b9de59d1f - оплачен
          http://onona.ru/oprosnik/e568b8f6bbb04a676a4f9f7dd79dc79f - отмена
          http://onona.ru/oprosnik/06e0737be489ef06fe31018e21fcc5da - возврат
         */





         // echo "<pre>";
        /*   $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute("SELECT * FROM  `sessions` WHERE  `sess_data` LIKE  '%productId%' and  `sess_data` LIKE  '%authenticated|b:1%' and sess_time > " . (time() - 86400) . " and sess_time < " . (time() - 900));
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $sessions = $result->fetchAll();
          //echo count($sessions);
          foreach ($sessions as $session):
          //print_r($session);
          $current_session = session_encode();
          $_SESSION = array();

          session_decode($session['sess_data']);
          $sessionUser = $_SESSION;

          $_SESSION = array();
          session_decode($current_session);
          $userAtribute = $sessionUser['symfony/user/sfUser/attributes']['symfony/user/sfUser/attributes'];
          $products_old = $userAtribute['products_to_cart'];
          $products_old = $products_old != '' ? unserialize($products_old) : '';
          $userId = $sessionUser['symfony/user/sfUser/attributes']['sfGuardSecurityUser']['user_id'];
          //print_r($userId);
          $user = sfGuardUserTable::getInstance()->findOneById($userId);
          if (dopFuncPage::is_email($user->getEmailAddress())):
          print_r($products_old);
          endif;
          endforeach; */
          //echo md5("124018");
        //echo (date("l dS of F Y h:i:s A"));
        //echo print_r($productsArray);

        /*
          ini_set("max_execution_time", "600");
          set_time_limit(600);
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute("SELECT u.*
          FROM  `sf_guard_user` AS u
          LEFT JOIN bonus AS b ON b.user_id = u.id
          AND b.comment LIKE  \"%Зачисление за %\"
          WHERE u.created_at >=  '2014-01-01'
          AND u.created_at <  '2014-12-01'
          AND b.id IS NULL ");

          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $users = $result->fetchAll();
          foreach ($users as $user) {
          //print_r($user);

          // echo $user->getId() . ": ";
          $order = OrdersTable::getInstance()->createQuery()->where("created_at >=  ?", $user['created_at'])->addWhere("created_at <=  ?", date("Y-m-d H:i:s", (strtotime($user['created_at']) + 120)))->addWhere("customer_id=?", $user['id'])->execute();

          //echo $order->count() . " ";
          if ($order->count() == 0) {
          $bonus = BonusTable::getInstance()->createQuery()->where("user_id=?", $user['id'])->addWhere("comment like \"%Зачисление за %\"")->execute();

          // echo $bonus->count() . " <b>";
          if ($bonus->count() == 0) {
          echo $user['id'] . "<br/>";
          $bonus = new Bonus();
          $bonus->setUserId($user['id']);
          $bonus->setBonus(csSettings::get("register_bonus_add"));
          $bonus->setComment("Зачисление за регистрацию");
          $bonus->save();
          $count = $count + 1;
          }
          }
          }
          echo $count; */
        ?>

        <?

        exit;
  }

  public function executeShopsDetail(sfWebRequest $request){
    if($request->getParameter('slug')!=strtolower($request->getParameter('slug')))  //Редирект на нижний регистр
      return $this->redirect('/shops/' . strtolower($request->getParameter('slug')), 301);
    $this->shop = Doctrine_Core::getTable('Shops')->findOneBySlug(array($request->getParameter('slug')));
    // die('<pre>'.print_r(array($this->shop->getIsActive(), $request->getParameter('slug')), true));
    $this->forward404Unless($this->shop);
    if(!$this->shop->getIsActive()) unset($this->shop);
    $this->forward404Unless($this->shop);
    if ($this->shop->getPageId()){
      $this->comments = CommentsTable::getInstance()
        ->createQuery()
        ->where("is_public='1' and (page_id = " . $this->shop->getPageId()." or shops_id = ".$this->shop->getId().") ")
        ->execute();
    }
  }

  public function executeShopsComment(sfWebRequest $request){
    if(!$_POST['name'] || !$_POST['comment']){
      return $this->renderText(json_encode(['error' => 'Заполнены не все данные']));
    }
    if($_POST['passkey'] != 'Robots will not pass'){
      return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
    }

    $newComments = new Comments();
    $newComments->setShopsId($_POST["id"]);
    $newComments->setUsername($_POST["name"]);
    $newComments->setMail($_POST["email"]);
    $newComments->setText($_POST["comment"]);
    $newComments->save();

    return $this->renderText(json_encode([
      'success' => 'Ваш отзыв отправлен на модерацию!',
    ]));
  }

  public function executeShops(sfWebRequest $request){
    $this->pages = Doctrine_Core::getTable('Shops')
            ->createQuery('a')
            ->execute();
  }

  public function executeIndex(sfWebRequest $request) {
      $this->pages = Doctrine_Core::getTable('Page')
              ->createQuery('a')
              ->execute();
  }

  public function executeNews(sfWebRequest $request) {
      $this->news = Doctrine_Core::getTable('News')->findOneBySlug(array($request->getParameter('slug')));

      $this->forward404Unless($this->news);
  }

  public function executeNewslist(sfWebRequest $request) {
      $this->news = Doctrine_Core::getTable('News')->createQuery()->orderBy("created_at desc")->execute();
  }

  public function executeAddcomm(sfWebRequest $request) {
      $this->page = Doctrine_Core::getTable('Page')->findOneBySlug(array($request->getParameter('slug')));
      $this->page->setContent("{commentsMagazBlock}");
      @dopBlockPage::blockAddComment();
      $this->setTemplate('show');
  }

  public function executeShow(sfWebRequest $request) {
    if($request->getParameter('slug')!='mainnewdis' && $request->getParameter('slug')!='mainNewDis' && $request->getParameter('slug')!=strtolower($request->getParameter('slug')))  //Редирект на нижний регистр
      return $this->redirect('/' . strtolower($request->getParameter('slug')), 301);
    $timer = sfTimerManager::getTimer('Action: Переадресация на страницу магазина, если он есть');
    $shop = Doctrine_Core::getTable('Shops')->findOneBySlug(array($request->getParameter('slug')));
    if(!empty($shop))
      return $this->redirect('/shops/' . mb_strtolower($shop->getSlug(), 'utf-8'), 301);
    $timer->addTime();

      $timer = sfTimerManager::getTimer('Action: Установка кодировки');

      mb_internal_encoding('UTF-8');

      $timer->addTime();
      /*
       *          Поиск страницы
       */
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
      if($request->getParameter('slug')=='adresa-magazinov-on-i-ona-v-moskve-i-mo') $this->newsBlock='';

      $timer->addTime();


      /*
       *          Обработка форм комментариев
       */
      $timer = sfTimerManager::getTimer('Action: Обработка форм комментариев');
      @dopBlockPage::blockAddComment();

      $timer->addTime();
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

      //*/
      $this->categorys = CategoryTable::getInstance()->createQuery()->where($nameOption, $nameArrOptions)->addWhere("is_public=1")->execute();
      $this->articles = ArticleTable::getInstance()->createQuery()->where($nameAndContentOption, $nameAndContentArrOptions)->addWhere("is_public=1")->execute();



      //$this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySlug(addslashes($request->getParameter('searchString')));
      /* $this->manufacturer = ManufacturerTable::getInstance()->createQuery()->where("slug like '%" . addslashes($request->getParameter('searchString')) . "%'")->orWhere("name like '%" . addslashes($request->getParameter('searchString')) . "%'")->fetchOne();
        if ($this->manufacturer) {
        $idFind = $this->manufacturer->getSubid();
        $manIsset = true;
        } else {
        $this->manufacturer = Doctrine_Core::getTable('Manufacturer')->findOneBySubid(addslashes($request->getParameter('searchString')));

        if (preg_match("/^[\d]+$/", $request->getParameter('searchString'))) {
        $idFind = addslashes($request->getParameter('searchString'));
        $manIsset = true;
        }
        }
        if ($manIsset) {
        $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);
        $prod = "";
        foreach ($this->dopinfoProduct as $key => $product) {
        $prod.=" id = '" . $product->get('product_id') . "'";
        if ($key < count($this->dopinfoProduct) - 1)
        $prod.=" or";
        }
        } */
      /* $this->collection = CollectionTable::getInstance()->createQuery()->where("slug like '%" . addslashes($request->getParameter('searchString')) . "%'")->orWhere("name like '%" . addslashes($request->getParameter('searchString')) . "%'")->fetchOne();

        // $this->collection = Doctrine_Core::getTable('Collection')->findOneBySlug(addslashes($request->getParameter('searchString')));
        if ($this->collection) {
        $idFind = $this->collection->getSubid();
        $collIsset = true;
        } else {
        $this->collection = Doctrine_Core::getTable('Collection')->findOneBySubid(addslashes($request->getParameter('searchString')));
        // $idFind = addslashes($request->getParameter('searchString'));

        if (preg_match("/^[\d]+$/", $request->getParameter('searchString'))) {
        $idFind = addslashes($request->getParameter('searchString'));
        $collIsset = true;
        }
        }
        if ($collIsset) {
        $this->dopinfoProduct = Doctrine_Core::getTable('DopInfoProduct')->findByDopInfoId($idFind);
        foreach ($this->dopinfoProduct as $key => $product) {
        if ($prod != "")
        $prod.=" or";
        $prod.=" id = '" . $product->get('product_id') . "'";
        }
        } */
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
        /*
        die( '<pre>'.print_r(
          [
            '$prodIds'=>$prodIds,
            'query' => $sqlBody,
            '$queryOptions'=>$queryOptions,
            '$nameOption' => $nameOption,
            '$nameArrOptions' => $nameArrOptions,
            '$nameAndContentOption' => $nameAndContentArrOptions,
            '$nameAndCodeAndContentAndid1cOption' => $nameAndCodeAndContentAndid1cOption,
            '$nameAndCodeAndContentAndid1cArrOptions' => $nameAndCodeAndContentAndid1cArrOptions,

          ]
        , true).'</pre>');
        */


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
      //-----------------//


      $this->pager = new sfDoctrinePager('Product', 60);

      //$this->pager->setQuery(ProductTable::getInstance()->createQuery()->where("name like '%" . addslashes($request->getParameter('searchString')) . "%' or code like '%" . addslashes($request->getParameter('searchString')) . "%' or content like '%" . addslashes($request->getParameter('searchString')) . "%' or id1c like '%" . addslashes($request->getParameter('searchString')) . "%' " . ($prod != "" ? "or " . $prod : ""))->addWhere("is_public=1")->addWhere("generalcategory_id<>135")->orderBy("(count>0) DESC"));
      $this->pager->setQuery(
        ProductTable::getInstance()->
        createQuery()->
        //where($nameAndCodeAndContentAndid1cOption, $nameAndCodeAndContentAndid1cArrOptions)->
        where($prodIdsWhereSphinx)->
        addWhere("is_public=1")->
        addWhere("generalcategory_id<>135")->
        //orWhere($prodIdsWhere)->
        orderBy("(count>0) DESC")->
        addOrderBy((sizeOf($idsFinded) > 0 ? "FIELD(id, ".implode(', ', $idsFinded).")" : "id = '".addslashes(str_replace(array("(",")","\"","'","select","union","drop","use","update","insert"), "", $queryOption))."' desc")));
        //addOrderBy("id = '".addslashes(str_replace(array("(",")","\"","'","select","union","drop","use","update","insert"), "", $queryOption))."' desc")->
        //addOrderBy("name like '%" . addslashes(str_replace(array("(",")","\"","'","select","union","drop","use","update","insert"), "", $queryOption)) . "%' desc")->
        //addOrderBy("content like '%" . addslashes(str_replace(array("(",")","\"","'","select","union","drop","use","update","insert"), "", $queryOption)) . "%' desc"));
      $this->pager->setPage($request->getParameter('page', 1));
      $this->pager->init();
      //$this->products = ProductTable::getInstance()->createQuery()->where("name like '%" . addslashes($request->getParameter('searchString')) . "%' or code like '%" . addslashes($request->getParameter('searchString')) . "%' or content like '%" . addslashes($request->getParameter('searchString')) . "%' or id1c like '%" . addslashes($request->getParameter('searchString')) . "%' " . ($prod != "" ? "or " . $prod : ""))->addWhere("is_public=1")->addWhere("generalcategory_id<>135")->orderBy("(count>0) DESC")->execute();

     // $this->products = ProductTable::getInstance()->createQuery()->where($nameAndCodeAndContentAndid1cOption, $nameAndCodeAndContentAndid1cArrOptions)->addWhere("is_public=1")->addWhere("generalcategory_id<>135")->orderBy("(count>0) DESC")->execute();
      $this->pages = PageTable::getInstance()->createQuery()->where("name like ?", "%" . addslashes($request->getParameter('searchString')) . "%")->addWhere("is_public=1")->execute();
      $this->manufacturer = ManufacturerTable::getInstance()->createQuery()->where("name like ?", "%" . addslashes($request->getParameter('searchString')) . "%")->addWhere("is_public=1")->execute();
      $this->collections = CollectionTable::getInstance()->createQuery()->where("name like ?", "%" . addslashes($request->getParameter('searchString')) . "%")->addWhere("is_public=1")->execute();
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
  }

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

    public function executeOprosnik(sfWebRequest $request) {
        $order = OrdersTable::getInstance()->createQuery()->where("md5(id)=?", $request->getParameter('order'))->fetchOne();
        $this->order = $order;
        $orderOprosnik = OprosnikTable::getInstance()->findOneByOrderid($order->getId());
        $this->orderExist = false;
        /*   $this->errorCap = false;
          $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='o'")->fetchOne(); */
        if ($orderOprosnik) {
            $this->orderExist = true;
        }
        if ($request->isMethod(sfRequest::POST)) {
            if (/* $request->getParameter('captcha') == $captcha->getVal() and */!$orderOprosnik) {
                $bonusSumm = 0;

                foreach ($_POST as $keypost => $param) {
                    if (!is_array($param) and $param != "" and $keypost != "managerWork" and $keypost != "deliveryWork" and $keypost != "productWork" and $keypost != "wwwWork" and $keypost != "otherWork") {
                        $bonusSumm = $bonusSumm + csSettings::get('bonus_add_oprosnik');
                    }
                }

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
                $oprosnik->setOrderid($order->getId());
                $oprosnik->setDataans(serialize($_POST));
                $oprosnik->setRating($ratingShop);
                $oprosnik->setShop("Интернет магазин");
                $oprosnik->save();
                $data = $_POST;
                If ($order->getStatus() == "Оплачен"):
                    $mailText = '
        <form action="" method="POST" id="oprosnik">
            <table style="width: 100%; border: 0px;" class="noBorder">
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;">Работа менеджеров</td><td>';
                    for ($i = 1; $i <= $data['managerWork']; $i++) {
                        $mailText .= '<img src = "https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td>
                </tr>
                <tr>
                    <td>Насколько корректно и вежливо с Вами общался менеджер?</td><td>' . $data['managerComunication'] . '</td>
                </tr>
                <tr>
                    <td>Смог ли менеджер проконсультировать Вас по всем Вашим вопросам?</td><td>' . $data['managerAdvise'] . '
                    </td>
                </tr>
                <tr>
                    <td>Как быстро перезвонил Вам менеджер для подтверждения оформленного Вами заказа?</td><td>' . $data['managerSpeedCalled'] . '
                    </td>
                </tr>
                <tr>
                    <td>Если были изменены комплектация или срок доставки заказа, вовремя ли менеджер сообщил Вам об этом?</td><td>' . $data['managerListProduct'] . '
                    </td>
                </tr>
                <tr>
                    <td>Если Вы отправляли нам вопросы через онлайн чат или через обратную связь на сайте, достаточно ли быстро Вы получили ответ?</td><td>' . $data['managerLiveText'] . '
                    </td>
                </tr>
                <tr>
                    <td>Если Вы подавали запрос на ремонт/замену товара, достаточно ли быстро наш сервис-менеджер обработал Вашу заявку и связался с Вами?</td><td>' . $data['managerReturn'] . '
                    </td>
                </tr>
                <tr>
                    <td>Оцените работу менеджеров по пятибальной шкале</td><td>' . $data['managerBall'] . '</td>
                </tr>
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Работа службы доставки</td><td style="padding-top: 50px;"> ';
                    for ($i = 1; $i <= $data['deliveryWork']; $i++) {
                        $mailText .= '<img src = "https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td>
</tr>
<tr>
    <td>Был ли Ваш заказ доставлен Вам в согласованное с нашим менеджером время? </td><td>' . $data['deliveryTime'] . '
    </td>
</tr>
<tr>
    <td> Корректно ли общался с Вами курьер при доставке Вашего заказа? </td><td>' . $data['kurerSleng'] . '
    </td>
</tr>
<tr>
    <td>Была ли сохранена целостность упаковки при доставке Вашего заказа?</td><td>' . $data['productPacket'] . '

    </td>
</tr>
<tr>
    <td>Был ли Вам предоставлен кассовый чек?</td><td>' . $data['orderCheck'] . '</td>
</tr>
<tr>
    <td>Оцените работу доставки по пятибалльной шкале</td><td>' . $data['deliveryBall'] . '</td>
</tr>
<tr>
    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Качество товаров</td><td style="padding-top: 50px;"> ';
                    for ($i = 1; $i <= $data['productWork']; $i++) {
                        $mailText .= '<img src = "https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td>
</tr>
<tr>
    <td>Соответствует ли качество и характеристики товаров заявленным (описанию) на сайте? </td><td>' . $data['qualityProduct'] . '</td>
</tr>
<tr>
    <td>Оцените качество товаров по пятибалльной шкале</td><td>' . $data['productBall'] . '</td>
</tr>
<tr>
    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Сайт</td><td style="padding-top: 50px;">';
                    for ($i = 1; $i <= $data['wwwWork']; $i++) {
                        $mailText .= '<img src = "https://onona.ru/images/star_select.png" > ';
                    }
                    $mailText .= '</td></tr>
<tr>
    <td>Удобно ли пользоваться сайтом?</td><td>' . $data['wwwEasy'] . '
    </td>
</tr>
<tr>
    <td>Были ли у Вас трудности с поиском нужного Вам товара на сайте?</td><td>' . $data['wwwSearchProduct'] . '
    </td>
</tr>
<tr>
    <td>Легко ли Вы нашли информацию по доставке и оплате?</td><td>' . $data['wwwSearchDelPay'] . '</td>
</tr>
<tr>
    <td>Удобно ли пользоваться корзиной?</td><td>' . $data['wwwCart'] . '</td>
</tr>
<tr>
    <td>Оцените работу сайта по пятибалльной шкале</td><td>' . $data['wwwBall'] . '</td>
</tr>
<tr>
    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Общие вопросы</td><td style="padding-top: 50px;">';
                    for ($i = 1; $i <= $data['otherWork']; $i++) {
                        $mailText .= '<img src="https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td></tr>
<tr>
    <td>Сколько раз (примерно) Вы заказывали в нашем интернет-магазине за последний год?</td><td>' . $data['wwwOrder'] . '
    </td>
</tr>
<tr>
    <td>Оцените по пятибальной шкале работу магазина</td><td>' . $data['shopBall'] . '</td>
</tr>
<tr>
    <td>Что Вы могли бы порекомендовать нам?</td><td>' . $data['recomendation'] . '</td>
</tr></table>
</form>';
                    $comments = $_POST['comments'];
                    foreach ($comments as $prid => $comment) {
                        if ($comment != "") {
                            $product = ProductTable::getInstance()->findOneById($prid);
                            $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();

                            $mailText .= '  <table><tbody><tr><td><img height="100" border="0" src="https://onona.ru/uploads/photo/thumbnails_250x250/' . $photos[0]->getFilename() . '" alt="' . $product->getName() . '" class="item_picture">
                                        </td><td><a target="_blank" href="https://onona.ru/product/' . $product->getSlug() . '">' . $product->getName() . '</a></td>
                                        <td>' . $comment . '</td></tr></tbody></table>';
                        }
                    } elseif ($order->getStatus() == "Отмена"):



                    $mailText = ' <form action="" method="POST" id="oprosnik">
    <table style="width: 100%; border: 0px;" class="noBorder">
        <tr>
            <td style="font-size: 16px; color:#C3060E;width: 50%;">Работа менеджеров</td><td>';
                    for ($i = 1; $i <= $data['managerWork']; $i++) {
                        $mailText .= '<img src = "https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td>
        </tr>
        <tr>
            <td>Насколько корректно и вежливо с Вами общался менеджер?</td><td>' . $data['managerComunication'] . '</td>
        </tr>
        <tr>
            <td>Смог ли менеджер проконсультировать Вас по всем Вашим вопросам?</td><td>' . $data['managerAdvise'] . '
            </td>
        </tr>
        <tr>
            <td>Как быстро перезвонил Вам менеджер для подтверждения оформленного Вами заказа?</td><td>' . $data['managerSpeedCalled'] . '
            </td>
        </tr>

        <tr>
            <td>Если Вы отправляли нам вопросы через онлайн чат или через обратную связь на сайте, достаточно ли быстро Вы получили ответ?</td><td>' . $data['managerLiveText'] . '
            </td>
        </tr>

        <tr>
            <td>Оцените работу менеджеров по пятибальной шкале</td><td>' . $data['managerBall'] . '</td>
        </tr>

        <tr>
            <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Сайт</td><td style="padding-top: 50px;">';
                    for ($i = 1; $i <= $data['wwwWork']; $i++) {
                        $mailText .= '<img src = "https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td></tr>
        <tr>
            <td>Удобно ли пользоваться сайтом?</td><td>' . $data['wwwEasy'] . '
            </td>
        </tr>
        <tr>
            <td>Были ли у Вас трудности с поиском нужного Вам товара на сайте?</td><td>' . $data['wwwSearchProduct'] . '
            </td>
        </tr>
        <tr>
            <td>Легко ли Вы нашли информацию по доставке и оплате?</td><td>' . $data['wwwSearchDelPay'] . '</td>
        </tr>
        <tr>
            <td>Удобно ли пользоваться корзиной?</td><td>' . $data['wwwCart'] . '</td>
        </tr>
<tr>
    <td>Оцените работу сайта по пятибалльной шкале</td><td>' . $data['wwwBall'] . '</td>
</tr>
        <tr>
            <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Общие вопросы</td><td style="padding-top: 50px;">';
                    for ($i = 1; $i <= $data['otherWork']; $i++) {
                        $mailText .= '<img src="https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td></tr>
        <tr>
            <td>Сколько раз (примерно) Вы заказывали в нашем интернет-магазине за последний год?</td><td>' . $data['wwwOrder'] . '
            </td>
        </tr>
        <tr>
            <td>Оцените по пятибальной шкале работу магазина</td><td>' . $data['shopBall'] . '</td>
        </tr>
        <tr>
            <td>Сообщите, пожалуйста, причину отмены заказа:</td><td>' . $data['recomendation'] . '</td>
        </tr></table>
</form>'; elseif ($order->getStatus() == "Возврат"):


                    $mailText = '   <form action="" method="POST" id="oprosnik">
            <table style="width: 100%; border: 0px;" class="noBorder">
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;">Работа менеджеров</td><td>';
                    for ($i = 1; $i <= $data['managerWork']; $i++) {
                        $mailText .= '<img src="https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td>
                </tr>
                <tr>
                    <td>Насколько корректно и вежливо с Вами общался менеджер?</td><td>' . $data['managerComunication'] . '</td>
                </tr>
                <tr>
                    <td>Смог ли менеджер проконсультировать Вас по всем Вашим вопросам?</td><td>' . $data['managerAdvise'] . '
                    </td>
                </tr>
                <tr>
                    <td>Как быстро перезвонил Вам менеджер для подтверждения оформленного Вами заказа?</td><td>' . $data['managerSpeedCalled'] . '
                    </td>
                </tr>
                <tr>
                    <td>Если были изменены комплектация или срок доставки заказа, вовремя ли менеджер сообщил Вам об этом?</td><td>' . $data['managerListProduct'] . '
                    </td>
                </tr>

                <tr>
                    <td>Если Вы отправляли нам вопросы через онлайн чат или через обратную связь на сайте, достаточно ли быстро Вы получили ответ?</td><td>' . $data['managerLiveText'] . '
                    </td>
                </tr>
                <tr>
                    <td>Оцените работу менеджеров по пятибальной шкале</td><td>' . $data['managerBall'] . '</td>
                </tr>

                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Сайт</td><td style="padding-top: 50px;">';
                    for ($i = 1; $i <= $data['wwwWork']; $i++) {
                        $mailText .= '<img src="https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td></tr>
                <tr>
                    <td>Удобно ли пользоваться сайтом?</td><td>' . $data['wwwEasy'] . '
                    </td>
                </tr>
                <tr>
                    <td>Были ли у Вас трудности с поиском нужного Вам товара на сайте?</td><td>' . $data['wwwSearchProduct'] . '
                    </td>
                </tr>
                <tr>
                    <td>Легко ли Вы нашли информацию по доставке и оплате?</td><td>' . $data['wwwSearchDelPay'] . '</td>
                </tr>
                <tr>
                    <td>Удобно ли пользоваться корзиной?</td><td>' . $data['wwwCart'] . '</td>
                </tr>
<tr>
    <td>Оцените работу сайта по пятибалльной шкале</td><td>' . $data['wwwBall'] . '</td>
</tr>
                <tr>
                    <td style="font-size: 16px; color:#C3060E;width: 50%;padding-top: 50px;">Общие вопросы</td><td style="padding-top: 50px;">';
                    for ($i = 1; $i <= $data['otherWork']; $i++) {
                        $mailText .= '<img src="https://onona.ru/images/star_select.png">';
                    }
                    $mailText .= '</td></tr>
                <tr>
                    <td>Сколько раз (примерно) Вы заказывали в нашем интернет-магазине за последний год?</td><td>' . $data['wwwOrder'] . '
                    </td>
                </tr>
                <tr>
                    <td>Оцените по пятибальной шкале работу магазина</td><td>' . $data['shopBall'] . '</td>
                </tr>
                <tr>
                    <td>Сообщите, пожалуйста, причину почему вы не получили свой заказ:</td><td>' . $data['recomendation'] . '</td>
                </tr></table>
        </form>';



                endif;




                $emailsfastorder = explode(";", csSettings::get("mail_oprosnik"));
                foreach ($emailsfastorder as $email) {
                    $arrayemailsfastorder[$email] = "";
                }

                $message = Swift_Message::newInstance()
                        ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                        ->setTo($arrayemailsfastorder)
                        ->setSubject("Заполнена анкета. Статус заказа: " . $order->getStatus())
                        ->setBody(" Анкету можно посмотреть тут: <a href='https://onona.ru/backend.php/oprosnik/" . $oprosnik->getId() . "/edit'>анкета</a><br><br>" . $mailText)
                        ->setContentType('text/html')
                ;

                $this->getMailer()->send($message);



                If ($order->getStatus() == "Оплачен") {
                    $bonus = new Bonus();
                    $bonus->setBonus($bonusSumm);
                    $bonus->setComment("Бонусы за отзыв о магазине по заказу #" . $order->getPrefix() . $order->getId());
                    $bonus->setUserId($order->getSfGuardUser());
                    $bonus->save();
                }
                $comments = $_POST['comments'];
                foreach ($comments as $prid => $comment) {
                    if ($_POST['cRate'][$prid] > 0) {
                        $this->product = Doctrine_Core::getTable('Product')->findOneById($prid);

                        $this->product->setRating($this->product->getRating() + $_POST['cRate'][$prid]);
                        $this->product->setVotesCount($this->product->getVotesCount() + 1);
                        $this->product->save();
                    }
                    if ($comment != "") {
                        $commentNew = new Comments();
                        $commentNew->setText($comment);
                        $commentNew->setCustomerId($order->getSfGuardUser());
                        $commentNew->setProductId($prid);
                        $commentNew->setRateSet($_POST['cRate'][$prid]);
                        $commentNew->save();
                    }
                }
            } /* else {
              $this->errorCap = true;
              } */
        }
    }

    public function executeComments(sfWebRequest $request) {
        $this->pager = new sfDoctrinePager('comments', 3);
        $this->query = Doctrine_Core::getTable('comments')->createQuery('c')->select('*')->where('is_public = \'1\' and product_id > 0')->addSelect("MAX(created_at) as max")->orderBy("max desc")->groupBy('product_id')/* ->execute() */;


        $this->pager->setQuery($this->query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();

        /*
         *
          SELECT *, count(com.id) as countComment FROM `category` as c left join category_product as cp on cp.category_id=c.id  left join comments as com on com.product_id = cp.product_id
          where com.is_public='1' and c.is_public='1'
          group by c.id order by count(com.id) desc
         *
         *
         *
         *

          SELECT * , COUNT( com.id ) AS countComment, cat.catalog_id AS catalogId
          FROM  `category` AS c
          LEFT JOIN category_product AS cp ON cp.category_id = c.id
          LEFT JOIN category_catalog AS cat ON cat.category_id = cp.category_id
          LEFT JOIN comments AS com ON com.product_id = cp.product_id
          WHERE (com.is_public =  '1' or com.is_public is NULL)
          AND c.is_public =  '1'
          AND cat.catalog_id IS NOT NULL
          GROUP BY c.id
          ORDER BY cat.catalog_id ASC , COUNT( com.id ) DESC

         *
         */
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
    }

    public function executeError404(sfWebRequest $request) {
        $e404 = new Logs404();
        $e404->setUrl('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        // print_r($_SERVER);exit;
        $e404->setUrlFrom($_SERVER["HTTP_REFERER"]);
        $e404->setIp($_SERVER['REMOTE_ADDR']);
        $e404->save();
        $this->page = Doctrine_Core::getTable('Page')->findOneBySlug('error404');
        $this->forward404Unless($this->page);
        $this->setTemplate('show');
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

    public function executeShowPickpoint(sfWebRequest $request) {
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
        //$this->newsBlock = dopBlockPage::getBlockNews($this->page);
        $this->newsBlock = "";
        $timer->addTime();


        /*
         *          Обработка форм комментариев
         */
        $timer = sfTimerManager::getTimer('Action: Обработка форм комментариев');
        @dopBlockPage::blockAddComment();

        $timer->addTime();
        $this->isPickPoint=true;
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
