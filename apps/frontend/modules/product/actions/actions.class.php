<?php

/**
 * product actions.
 *
 * @package    Magazin
 * @subpackage product
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class productActions extends sfActions {
  public function __construct($context, $moduleName, $controllerName) {
    parent::__construct($context, $moduleName, $controllerName);

    $this->csrf = new CSRFToken($this);
  }

  public function executeAddComment(sfWebRequest $request) {
    // if (!$this->csrf->isValidKey('_csrf_token', $_POST['sf_guard_user'])) {
    //   return $this->renderText(json_encode(['error' => 'некорректный CSRF-токен']));
    // }
    if($request->getParameter('token') != 'Роботы нам тут не нужны!')
      return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
    if(!$request->getParameter('agreement'))
      return $this->renderText(json_encode(['error' => 'Необходимо принять пользовательское соглашение']));
    if(!$request->getParameter('fio'))
      return $this->renderText(json_encode(['error' => 'Заполните имя', 'field' => 'fio']));
    if(!$request->getParameter('email'))
      return $this->renderText(json_encode(['error' => 'Заполните электронную почту', 'field' => 'email']));
    if(!$request->getParameter('comment'))
      return $this->renderText(json_encode(['error' => 'Заполните отзыв', 'field' => 'comment']));
    if(!$request->getParameter('rating'))
      return $this->renderText(json_encode(['error' => 'Необходимо указать рейтинг']));

    //Валидация прошла
    $this->product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('id')));
    $this->product->setRating($this->product->getRating() + $request->getParameter('rating'));
    $this->product->setVotesCount($this->product->getVotesCount() + 1);
    $this->product->save();
    //Обновили рейтинг у товара

    $comment = new Comments();
    $comment->setText($request->getParameter('comment'));
    $comment->setProductId($this->product->getId());
    $comment->setRateSet($request->getParameter('rating'));
    if ($this->getUser()->isAuthenticated()) $comment->setCustomerId($this->getUser()->getGuardUser()->getId());
    $comment->setUsername($request->getParameter('fio'));
    $comment->setMail($request->getParameter('email'));
    $comment->save();
    //Сохранили коммент в базе

    return $this->renderText(json_encode([
      'success' => 'Ваш отзыв отправлен на модерацию!',
    ]));
    //ответили серверу, что все ок
  }

  public function executeShow(sfWebRequest $request) {
    if($request->getParameter('slug')!=strtolower($request->getParameter('slug')))//редирект на нижний регистр
      return $this->redirect('/product/' . strtolower($request->getParameter('slug')), 301);

    $this->product = Doctrine_Core::getTable('Product')->findOneBySlug(array($request->getParameter('slug')));
    if (empty($this->product))//Если не нашли по slug - ищем по id
        $this->product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('slug')));

    if (empty($this->product)) {//Если не нашли по slug и по id - ищем по старому slug
        $oldSlug = OldslugTable::getInstance()->createQuery()->where("module='Product' and oldslug=?", $request->getParameter('slug'))->orderBy("id DESC")->fetchOne();
        if ($oldSlug) {
            $this->product = Doctrine_Core::getTable('Product')->findOneById($oldSlug->getDopid());
            return $this->redirect('/product/' . $this->product->getSlug(), 301);
        }
    }

    $this->forward404Unless($this->product);
    //Если не нашли - 404
    if(!$this->product->getIsPublic()) $this->forward404Unless(false);
    //Если товар не активен - 404
    $this->product->setViewsCount($this->product->getViewsCount() + 1);

    $this->product->save();
    //увеличиваем число просмотров
    if( $this->product->getExternallink() ) return $this->redirect( $this->product->getExternallink() , 302);

    $this->bonus = round(
      //(
      $this->product->getPrice()// - $this->product->getPrice() * ($this->product->getBonuspay() > 0 ? $this->product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100)
      *
      (($this->product->getBonus() > 0 ? $this->product->getBonus() : csSettings::get('persent_bonus_add')) / 100)
    ) ;

    $this->forward404Unless($this->product);


    //Собираем крошки
    $this->generalCategory = $this->product->getGeneralCategory();
    if ($this->generalCategory->getParentsId() != "") {
      if($this->generalCategory->getParent()->getIsPublic())
        $crumbs[]=[
          'text' => $this->generalCategory->getParent()->getName(),
          'link' => '/category/'.$this->generalCategory->getParent()->getSlug(),
        ];
    }
    if ($this->generalCategory->getIsPublic())
      $crumbs[]=[
        'text' => $this->generalCategory->getName(),
        'link' => '/category/'.$this->generalCategory->getSlug(),
      ];

    if (isset($crumbs)) $this->crumbs=$crumbs;

    //Фотографии
    $this->photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $this->product->getId() . " limit 0,1)")->orderBy("position")->execute();
    //Показывать ли кнопку купить в 1 клик. Запрещено для advcake
    $this->showBtnOneClickBuy = (!isset($_COOKIE['utm_source']) || $_COOKIE['utm_source']!='advcake') && (!isset($_GET['utm_source']) || $_GET['utm_source']!='advcake');

    $this->parent=$this->product->getParent();
    if(is_object($this->parent) && $this->parent->getSlug()) $this->canonical = $this->parent->getSlug();
    else $this->canonical = false;
    if(!$this->parent->getIsPublic()) $this->canonical = false;
    if($this->product->getCanonical()) $this->canonical = $this->product->getCanonical();
    // $category = $this->product->getCategoryProducts();
    // $this->category = $category[0];
    //Полуили категорию товара
    /*следующий и предыдущий товары. сейчас не используется*/
    /*
      $this->prev = Doctrine_Core::getTable('Product')
              ->createQuery('p')
              ->leftJoin('p.CategoryProduct c')
              ->where("c.category_id IN ('" . $this->category->getId() . "')")
              ->addWhere('p.id < ?', $this->product->getId())
              ->orderBy('id DESC')
              ->limit(1)
              ->fetchOne();
      if ($this->prev == "")
          $this->prev = Doctrine_Core::getTable('Product')
                  ->createQuery('p')
                  ->leftJoin('p.CategoryProduct c')
                  ->where("c.category_id IN ('" . $this->category->getId() . "')")
                  ->addWhere('p.id > ?', $this->product->getId())
                  ->orderBy('id DESC')
                  ->limit(1)
                  ->fetchOne();

      $this->next = Doctrine_Core::getTable('Product')
              ->createQuery('p')
              ->leftJoin('p.CategoryProduct c')
              ->where("c.category_id IN ('" . $this->category->getId() . "')")
              ->addWhere('p.id > ?', $this->product->getId())
              ->orderBy('id ASC')
              ->limit(1)
              ->fetchOne();
      if ($this->next == "")
          $this->next = Doctrine_Core::getTable('Product')
                  ->createQuery('p')
                  ->leftJoin('p.CategoryProduct c')
                  ->where("c.category_id IN ('" . $this->category->getId() . "')")
                  ->addWhere('p.id < ?', $this->product->getId())
                  ->orderBy('id ASC')
                  ->limit(1)
                  ->fetchOne();
    */

    // die('product action');


    /*  Вы смотрели. Не используется */
    /*
      $showProducts = $this->getUser()->getAttribute('showProducts');
      if (isset($showProducts))
          $showProducts = unserialize($showProducts);
      else
          $showProducts = array();

      if (!in_array($this->product->getId(), $showProducts))
          $showProducts[] = $this->product->getId();

          //shuffle($ShowProdus);
      $this->getUser()->setAttribute('showProducts', serialize($showProducts));
      $this->ShowProdus = array_reverse($showProducts);

    */
    /* загрузка товаров в нижний блок */
    /*
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();

      $timerProducts = sfTimerManager::getTimer('Action: Загрузка товаров в нижний блок');
      $this->categoryProduct = $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, product.created_at, video, videoenabled, endaction, step, count, product.is_public, parents_id, code, '".$this->product->getGeneralCategory()."' as cat_name "
                      . "FROM product "
                      . "WHERE  `generalcategory_id` = " . ($this->product->getGeneralCategory()->getId()) . " "
                      . "and product.is_public='1' "
                      . "AND product.count>0 "
                      . "ORDER BY rand() "
                      . "LIMIT 10")
              ->fetchAll(Doctrine_Core::FETCH_UNIQUE);

      if ($this->product->getBuywithitem() != "") {
          $this->productsBuywithitem = $q->execute(
              "SELECT product.id, product.name, product.slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, product.created_at, video, videoenabled, endaction, step, count, product.is_public, product.parents_id, product.code, c.name as cat_name "
              . "FROM product "
              . "LEFT JOIN category c ON c.id=product.generalcategory_id "
              . "WHERE product.id in (" . implode(',', array_keys(array_slice(unserialize($this->product->getBuywithitem()), 0, 10, true))) . ") "
              . "and product.is_public='1' "
              . "AND product.count>0 "
              . "ORDER BY countsell DESC "
              . "LIMIT 10")
          ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
      }

      if (count($showProducts) > 0) {
          $this->ShowProducts = $q->execute("SELECT product.id, product.name, product.slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, product.created_at, video, videoenabled, endaction, step, count, product.is_public, product.parents_id, product.code, c.name as cat_name "
                          . "FROM product "
                          . "LEFT JOIN category c ON c.id=product.generalcategory_id "
                          . "WHERE  product.id in (" . implode(",", $showProducts) . ") "
                          . "and product.is_public='1' "
                          . "AND product.count>0 "
                          . "ORDER BY FIELD( product.id, " . implode(",", $showProducts) . " )  "
                          . "LIMIT 10")
                  ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
      }

      $this->productsBestsellers = $q->execute("SELECT product.id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, product.created_at, video, videoenabled, endaction, step, count, product.is_public, parents_id, code, '".$this->product->getGeneralCategory()."' as cat_name "
                      . "FROM product "
                      . "WHERE  `generalcategory_id` = " . ($this->product->getGeneralCategory()->getId()) . " "
                      . "and product.is_public='1' "
                      . "AND product.count>0 "
                      . "ORDER BY countsell DESC "
                      . "LIMIT 10")
              ->fetchAll(Doctrine_Core::FETCH_UNIQUE);

      $this->productsFooter = (array)$this->categoryProduct + (array)$this->productsBuywithitem + (array)$this->ShowProducts + (array)$this->productsBestsellers;
      if ($this->getUser()->isAuthenticated()):

          if ($this->getUser()->getGuardUser()->getId() == 17101) {
              //print_R($this->ShowProducts);
          }
      endif;
      $timerProducts->addTime();

      if (count($this->productsFooter) > 0) {

          $timerChildren = sfTimerManager::getTimer('Action: Загрузка дочерних товаров для всех товаров');
          $this->childrensAll = $q->execute("SELECT IFNULL( parents_id, p.id ),p.* FROM product as p "
                          . "WHERE parents_id in (" . implode(",", array_keys($this->productsFooter)) . ") or id in (" . implode(",", array_keys($this->productsFooter)) . ") "
                          . "ORDER BY parents_id ASC")
                  ->fetchAll(Doctrine_Core::FETCH_GROUP);
          $timerChildren->addTime();

          $timerProductsIdAll = sfTimerManager::getTimer('Action: Получение id всех товаров на странице, включая дочерних');
          $this->productsIdAll = $q->execute("SELECT p.id FROM product as p "
                          . "WHERE parents_id in (" . implode(",", array_keys($this->productsFooter)) . ") or id in (" . implode(",", array_keys($this->productsFooter)) . ") ")
                  ->fetchAll(Doctrine_Core::FETCH_COLUMN);
          $timerProductsIdAll->addTime();

          $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
          $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm FROM comments "
                          . "WHERE is_public='1' and product_id in (" . implode(',', $this->productsIdAll) . ") "
                          . "group by product_id")
                  ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerComments->addTime();

          $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
          $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename FROM photo "
                          . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                          . "WHERE ppa.product_id in (" . implode(",", $this->productsIdAll) . ") "
                          . "ORDER BY photo.position DESC")
                  ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
          $timerPhotos->addTime();
      }*/
  }

  public function executeFastorder(sfWebRequest $request) {//Быстрый заказ
    if (!$this->csrf->isValidKey('_csrf_token', $_POST['sf_guard_user'])) {
      return $this->renderText(json_encode(['error' => 'некорректный CSRF-токен']));
    }
    if($request->getParameter('token') != 'Роботы нам тут не нужны!')
      return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
    if(!$request->getParameter('email') || !filter_var($request->getParameter('email'), FILTER_VALIDATE_EMAIL))
      return $this->renderText(json_encode(['error' => 'Укажите email', 'field' => 'email']));
    if(!$request->getParameter('phone'))
      return $this->renderText(json_encode(['error' => 'Укажите телефон', 'field' => 'phone']));
    //Валидация прошла

    $product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('id')));
    $this->product = $product;

    $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
            ->setUsername(csSettings::get("smtp_user"))
            ->setPassword(csSettings::get("smtp_pass"));
    $mailer = Swift_Mailer::newInstance($transport);
    global $isTest;
    if(!$isTest)
      $emailsfastorder = explode(";", csSettings::get("emailsfastorder"));
    else $emailsfastorder=[
      'aushakov@interlabs.ru',
      "ctapocta13@gmail.com"
    ];

    foreach ($emailsfastorder as $email) {
        $emailMag[$email] ='';
    }
    $messageText="Здравствуйте!<br>
      Артикул товара: " . $product->getCode() . "<br>
      Название товара: " . $product->getName() . "<br>
      Ссылка на товар: <a href=\"https://onona.ru/product/" . $product->getSlug() . "\">https://onona.ru/product/" . $product->getSlug() . "</a><br>
      Получатель: " . $_POST['fio'] . "<br>
      Его телефон: " . $_POST['phone'] .
      "<br>
      Его почта: " . $_POST['email'] .
      "<br>
      Комментарий: " . nl2br(htmlspecialchars($_POST['comment'])) .
       "<br>";

    // echo print_r($arrayemailsfastorder, true);exit;
    // die('bar');
    $message = Swift_Message::newInstance()
        ->setFrom(csSettings::get('smtp_user'), "Почтовый робот сайта OnOna.Ru")
        ->setTo($emailMag)
        ->setSubject("Поступил запрос на быстрый заказ")
        ->setBody($messageText)
        ->setContentType('text/html')
      ;
      // die('foo');
              //Send the message
    try {
      // $numSent = $mailer->send($message);
      $this->getMailer()->send($message);

    } catch (\Exception $e) {
      // print_r($e, true);
    }

    $fasOrder = new FastOrderLog();
    $fasOrder->set('code', $product->getCode());
    $fasOrder->set('name', $product->getName());
    $fasOrder->set('username', $_POST['fio'].' - '.$_POST['fio']);
    $fasOrder->set('mail', $_POST['email']);
    $fasOrder->save();

		//Roistat Отправка данных в проксилид
		if(file_exists($_SERVER["DOCUMENT_ROOT"].'/roistat/proxilid.php')){
			$roisUser = array(
				'name'      => $_POST['fio'],
				'phone'     => $_POST['phone'],
				'comment'   => $_POST['comment'],
				'form_name' => 'Быстрый заказ',
				'products'  => "Название товара: {$product->getName()}\nАртикул товара: {$product->getCode()}\n Ссылка на товар: https://onona.ru/product/{$product->getSlug()}"
			);
			include $_SERVER["DOCUMENT_ROOT"].'/roistat/proxilid.php';
			proxilid($roisUser);
		}
		//Roistat END

    return $this->renderText(json_encode([
      'success' => '<br><span style="font-size: 150%;">Ваш Заказ получен! Ожидайте звонка</span>',
      'sendRR' => [
        'productID' => $product->getId(),
        'transaction' => 'one_click_'.$fasOrder->getId(),
        'price' => $product->getPrice(),
      ]
    ]));
    }




    public function sendOrderWhenAskForNoticeOutStock($senduser){

      // $senduser = new Senduser();
      // $senduser->set("product_id", $this->product->getId());
      // $senduser->setName($request->getParameter('name'));
      // $senduser->setMail($request->getParameter('mail'));
      // $senduser->setPhone($request->getParameter('phone'));
      // $senduser->save();

      $product = Doctrine_Core::getTable('Product')->findOneById($senduser->getProduct_id());
      // $this->product = $product;

      $order = new Orders();
      $products_old = array();
      $row = array();
      $row["productId"] = $product->getId();
      $row["productOptions"] = '';
      $row["quantity"] = 1;

      $row["price"] = $product->getPrice();
      foreach ($products_old as $key => $product) {
          if (@$product["productId"] == @$row["productId"] && @$product["productOptions"] == @$row["productOptions"]) {
              $products_old[$key]["quantity"] = $products_old[$key]["quantity"] + $row['quantity'];
              $row = array();
          }
      }
      if (isset($row["productId"]) && $row["productId"] > 0) {
          $products_old[] = $row;
      }
      $products_old = $products_old != '' ? serialize($products_old) : '';

      $order->setText($products_old);
      $comment = "Запрос на отсутствующий товар.. \r\n ";
      $order->setComments($comment);

      /* ИМХО необязательно ↓*/
      $order->setCustombonusper(false);
      $order->setBonuspay(0);
      /* ИМХО необязательно ↑*/

      $order->setStatus('Новый');
      $order->set('sync_status', 'new');
      $ref = 'NULL';
      if (defined('REFERALID')) {
          $ref = REFERALID;
      }

      $order->set('referurl', $_COOKIE['referalurl']);

      $order->set('referal', $ref);
      $order->set('ipUser', $_SERVER['REMOTE_ADDR']);
      $order->set('prefix', csSettings::get('order_prefix'));
      $order->setDeliveryId(12);
      $order->setPaymentId(56); // Запрос на отсутствующий товар

      $userNew = sfGuardUserTable::getInstance()->findOneByEmailAddress($senduser->getMail());
      if (!$userNew) {
          $userNew = new sfGuardUser();
          $userNew->setFirstName($senduser->getName());
          $username = 'user_' . rand(0, 9999999999999);
          $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
          if ($isExistUserName->count() != 0) {
              $username = 'user_' . rand(0, 9999999999999);
              $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
              if ($isExistUserName->count() != 0) {
                  $username = 'user_' . rand(0, 9999999999999);
                  $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                  if ($isExistUserName->count() != 0) {
                      $username = 'user_' . rand(0, 9999999999999);
                  }
              }
          }
          $userNew->setUsername($username);
          $userNew->setPhone($senduser->getPhone());
          $userNew->setPassword(rand(1000000000, 9999999999));
          $userNew->setEmailAddress($senduser->getMail());
          $userNew->save();
      }

      $order->setCustomerId($userNew);
      $order->save();

      exec('php -r "echo file_get_contents(\'https://onona.ru?r=cmd&command=upload_order&err=1&api=8d956bbe43b2809baa904afb20a65831&id=' . $order->getId() . '\');" > /dev/null &');
    }

    public function executeFastorderOld(sfWebRequest $request) {
        $product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('id')));
        $this->product = $product;
        $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='fo'")->fetchOne();
        if ($captcha->getVal() == $_POST["cText"]) {
            $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                    ->setUsername(csSettings::get("smtp_user"))
                    ->setPassword(csSettings::get("smtp_pass"));
            $mailer = Swift_Mailer::newInstance($transport);

            $emailsfastorder = explode(";", csSettings::get("emailsfastorder"));
            foreach ($emailsfastorder as $email) {
                $arrayemailsfastorder[$email] = "";
            }
          //print_r($arrayemailsfastorder);exit;
            $message = Swift_Message::newInstance('Поступил запрос на быстрый заказ', "Здравствуйте!<br>
          		Артикул товара: " . $product->getCode() . "<br>
          		Название товара: " . $product->getName() . "<br>
          		Ссылка на товар: <a href=\"https://onona.ru/product/" . $product->getSlug() . "\">https://onona.ru/product/" . $product->getSlug() . "</a><br>
          		Получатель: " . $_POST['name'] . "<br>
          		Его телефон: " . $_POST['phone'] . "<br>
          		Его почта: " . $_POST['mail'] . "<br>", 'text/html', 'utf-8')
                              ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                              ->setTo($arrayemailsfastorder)
                      ;

                      //Send the message
            $numSent = $mailer->send($message);
            $fasOrder = new FastOrderLog();
            $fasOrder->set('code', $product->getCode());
            $fasOrder->set('name', $product->getName());
            $fasOrder->set('username', $_POST['name']);
            $fasOrder->set('mail', $_POST['mail']);
            $fasOrder->save();


            //$products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
            $products_old = array();
            $row = array();
            $row["productId"] = $product->getId();
            $row["productOptions"] = $request->getParameter('productOptions');
            $row["quantity"] = 1;

            $row["price"] = $product->getPrice();
            foreach ($products_old as $key => $product) {
                if (@$product["productId"] == @$row["productId"] && @$product["productOptions"] == @$row["productOptions"]) {
                    $products_old[$key]["quantity"] = $products_old[$key]["quantity"] + $row['quantity'];
                    $row = array();
                }
            }
            if (isset($row["productId"]) && $row["productId"] > 0) {
                $products_old[] = $row;
            }
            $products_old = $products_old != '' ? serialize($products_old) : '';
            //echo $products_old;

            $order = new Orders();
            $order->setText($products_old);
            /* $comment = "Получатель: " . $_POST['name'] . "\r\n
              Его телефон: " . $_POST['phone'] . "\r\n
              Его почта: " . $_POST['mail'] . "\r\n "; */
            $comment = "Быстрый заказ. \r\n ";
            $order->setComments($comment);

            $order->setStatus('Новый');
            $order->set('sync_status', 'new');
            $ref = 'NULL';
            if (defined('REFERALID')) {
                $ref = REFERALID;
            }

            $order->set('referurl', $_COOKIE['referalurl']);

            $order->set('referal', $ref);
            $order->set('ipUser', $_SERVER['REMOTE_ADDR']);
            $order->set('prefix', csSettings::get('order_prefix'));
            $order->setDeliveryId(12);
            $order->setPaymentId(46);
            $userNew = sfGuardUserTable::getInstance()->findOneByEmailAddress($_POST['mail']);
            if (!$userNew) {
                $userNew = new sfGuardUser();
                $userNew->setFirstName($_POST['name']);
                $username = 'user_' . rand(0, 9999999999999);
                $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                if ($isExistUserName->count() != 0) {
                    $username = 'user_' . rand(0, 9999999999999);
                    $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                    if ($isExistUserName->count() != 0) {
                        $username = 'user_' . rand(0, 9999999999999);
                        $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                        if ($isExistUserName->count() != 0) {
                            $username = 'user_' . rand(0, 9999999999999);
                        }
                    }
                }
                $userNew->setUsername($username);
                $userNew->setPhone($_POST['phone']);
                $userNew->setPassword(rand(1000000000, 9999999999));
                $userNew->setEmailAddress($_POST['mail']);
                $userNew->save();
            }
            $order->setCustomerId($userNew);
            $order->save();
            $this->order = $order;
        } else {
            $this->captcha_error = 1;
            $this->name = $_POST["name"];
            $this->mail = isset($_POST["mail"]) ? $_POST["mail"] : "";
            $this->phone = $_POST["phone"];
        }
    }

    public function executeRate(sfWebRequest $request) {

        if ($request->getParameter('nonAdd') != "1") {
            $product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('productId')));
            $product->setRating($product->getRating() + $request->getParameter('value'));
            $product->setVotesCount($product->getVotesCount() + 1);
            $product->save();
            $this->getResponse()->setCookie("rate_" . $product->getId(), 1, time() + 60 * 60 * 24 * 365, '/');

            $cacheManager = sfContext::getInstance()->getViewCacheManager();
            if ($cacheManager) {
                $cacheManager->remove('@sf_cache_partial?module=category&action=_products&sf_cache_key=' . ($product->getId()) . '*');
                $cacheManager->remove('@sf_cache_partial?module=category&action=_changechildren&sf_cache_key=' . ($product->getId()) . '*');
                $cacheManager->remove('@sf_cache_partial?module=product&action=_params&sf_cache_key=' . ($product->getId()) . '*');
                $cacheManager->remove('@sf_cache_partial?module=product&action=_stock&sf_cache_key=' . ($product->getId()) . '*');
            }
            /*
             * 02.07.15
             */

             //$newdis_cache_dir = '/var/www/ononaru/data/www/cache/newdis/*/template/*/all';
            //            $cache = new sfFileCache(array('cache_dir' => $newdis_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
            //            $cache->removePattern('/sf_cache_partial/category/__products/sf_cache_key/' . $product->getId() . "-");
            //            $cache->removePattern('/product/show/slug/' . $product->getSlug());
            //
            //            $newdis_cache_dir = '/var/www/ononaru/data/www/cache/newcat/*/template/*/all';
            //            $cache = new sfFileCache(array('cache_dir' => $newdis_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
            //            $cache->removePattern('/sf_cache_partial/category/__products/sf_cache_key/' . $product->getId() . "-");
            //            $cache->removePattern('/product/show/slug/' . $product->getSlug());

            $this->product = $product;
        } else {
            return $this->renderText($request->getParameter('value'));
        }
    }

    public function executeRatecomment(sfWebRequest $request) {

        $comment = Doctrine_Core::getTable('Comments')->findOneById(array($request->getParameter('id')));
        if (sfContext::getInstance()->getRequest()->getCookie('rateComment_' . $request->getParameter('id')) != "") {


            if ($request->getParameter('rate') == "plus") {
                $comment->setRatePlus($comment->getRatePlus() - 1);
            } else {
                $comment->setRateMinus($comment->getRateMinus() - 1);
            }
            sfContext::getInstance()->getResponse()->setCookie('rateComment_' . $request->getParameter('id'), '');
            $this->cookie = '';
            $comment->save();
        } else {

            if ($request->getParameter('rate') == "plus") {
                $comment->setRatePlus($comment->getRatePlus() + 1);
                sfContext::getInstance()->getResponse()->setCookie('rateComment_' . $request->getParameter('id'), 'like');
                $this->cookie = 'like';
            } else {
                $comment->setRateMinus($comment->getRateMinus() + 1);
                sfContext::getInstance()->getResponse()->setCookie('rateComment_' . $request->getParameter('id'), 'dislike');
                $this->cookie = 'dislike';
            }
            $comment->save();
        }
        $this->comment = $comment;
    }

    private function create_watermark($main_img_obj, $text, $font, $r = 128, $g = 128, $b = 128, $alpha_level = 100) {

        $width = imagesx($main_img_obj);
        $height = imagesy($main_img_obj);
        $angle = rad2deg(atan2(($height), ($width)));

        $c = imagecolorallocatealpha($main_img_obj, $r, $g, $b, $alpha_level);
        $size = (($width + $height) / 2) * 2 / strlen($text);
        $box = imagettfbbox($size, $angle, $font, $text);
        $x = $width / 2 - abs($box[4] - $box[0]) / 2;
        $y = $height / 2 + abs($box[5] - $box[1]) / 2;

        imagettftext($main_img_obj, $size, $angle, $x, $y, $c, $font, $text);
        return $main_img_obj;
    }

    public function executeUploadUserPhoto(sfWebRequest $request) {

        //header('Content-Type: image/jpeg');
        /* print_r($_FILES);
          ?><br><br><br><br><br><br><br><br><br><br><?
          print_r($_POST);exit; */
        if (exif_imagetype($_FILES["image"]["tmp_name"]) == IMAGETYPE_GIF) {
            $image = imagecreatefromgif($_FILES["image"]["tmp_name"]);
        } elseif (exif_imagetype($_FILES["image"]["tmp_name"]) == IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($_FILES["image"]["tmp_name"]);
        } elseif (exif_imagetype($_FILES["image"]["tmp_name"]) == IMAGETYPE_PNG) {
            $image = imagecreatefrompng($_FILES["image"]["tmp_name"]);
        }
        if ($image) {

            /*
              Перед тем как произодить опрерации с новым ресурсом,
              установим некоторые опции
              imageAlphaBlending - устанавливает режим смешивания(режим
              смешивания недоступен для изображений с палитрой)
              по умолчанию для truecolor изображений - true, для изображений
              с палитрой - false
              true/false - включен/выключен

              true - при накладывании одного изображения на другое
              цвета пикселей нижележащего и накладываемого изображения смешиваются,
              параметры смешивания определяются прозрачностью пикселя.

              false - накладываемый пиксель заменяет исходный
             */
            imageAlphaBlending($image, true);
            /*
              ImageSaveAlpha
              Сохранять или не сохранять информацию о прозрачности
              по умолчанию - false, а надо true
             */
            imageSaveAlpha($image, true);
            $info_o = @getImageSize($_FILES["image"]["tmp_name"]);
            $txt = '  OnOna.Ru  ';
            $im = $this->create_watermark($image, $txt, "/var/www/ononaru/data/www/onona.ru/fonts/arial.ttf"/* ,0,0,255,100 */);
            $out = imageCreateTrueColor($info_o[0], $info_o[1]);

            imageAlphaBlending($out, false);
            imageSaveAlpha($out, true);
            imageAlphaBlending($im, false);
            imageSaveAlpha($im, true);

            imageCopy($out, $im, 0, 0, 0, 0, $info_o[0], $info_o[1]);
            $namePhoto = md5(time());
            imagejpeg($out, "/var/www/ononaru/data/www/onona.ru/uploads/photouser/" . $namePhoto . ".jpg");

            if ($info_o[0] > $info_o[1])
                $max = $info_o[0];
            else
                $max = $info_o[1];
            $koef = $max / 180;
            $out2 = imageCreateTrueColor($info_o[0] / $koef, $info_o[1] / $koef);
            imageAlphaBlending($out2, false);
            imageSaveAlpha($out2, true);
            ImageCopyResampled($out2, $out, 0, 0, 0, 0, $info_o[0] / $koef, $info_o[1] / $koef, $info_o[0], $info_o[1]);
            imagejpeg($out2, "/var/www/ononaru/data/www/onona.ru/uploads/photouser/thumbnails/" . $namePhoto . ".jpg");
            ImageDestroy($image);
            ImageDestroy($out2);
            ImageDestroy($out);
            $userPhoto = new PhotosUser();
            $userPhoto->set("product_id", $request->getParameter('id'));
            $userPhoto->set("filename", $namePhoto . ".jpg");
            $userPhoto->set("is_public", 0);
            if ($this->getUser()->isAuthenticated())
                $userPhoto->set("user_id", $this->getUser()->getGuardUser()->getId());
            $userPhoto->save();
            return $this->renderText('true');
        }else {

            return $this->renderText('false');
        }
    }

    public function executeSetmintoprice(sfWebRequest $request) {
        $product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('productId')));
        $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                ->setUsername(csSettings::get("smtp_user"))
                ->setPassword(csSettings::get("smtp_pass"));
        $mailer = Swift_Mailer::newInstance($transport);

        $emailsfastorder = explode(";", csSettings::get("mintopriceMail"));
        foreach ($emailsfastorder as $email) {
            $arrayemailsfastorder[trim($email)] = "";
        }
        //print_r($arrayemailsfastorder);exit;
        if ($this->getUser()->isAuthenticated()) {
            $message = Swift_Message::newInstance('Поступил запрос на другую цену', "Здравствуйте!<br>
          		Артикул товара: " . $product->getCode() . "<br>
          		Название товара: " . $product->getName() . "<br>
          		Ссылка на товар: <a href=\"https://onona.ru/product/" . $product->getSlug() . "\">https://onona.ru/product/" . $product->getSlug() . "</a><br>
          		Цена товара у конкурента: " . $_POST['price'] . "<br>
          		Ссылка на товар конкурента: " . $_POST['link'] . "<br>
          		ФИО: " . $this->getUser()->getGuardUser()->getName() . "<br>
          		Email: " . $this->getUser()->getGuardUser()->getEmailAddress() . "<br>
          		Телефон: " . $this->getUser()->getGuardUser()->getPhone() . "<br>", 'text/html', 'utf-8')
                    ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                    ->setTo($arrayemailsfastorder)
            ;

            //Send the message
            $numSent = $mailer->send($message);
            /*
            $this->debug=[
              'numSent'=> $numSent,
              'to' => $arrayemailsfastorder,
              // 'message' => $message
            ];*/
        }
    }

    public function executeSetnotification(sfWebRequest $request) {
        $productId = $request->getParameter('productId');
        if ($request->getParameter('ActionAllProd') == "on") {
            NotificationTable::getInstance()->createQuery()->delete()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='Action'")->execute();
            $NotificationAction = new Notification();
            $NotificationAction->setUserId($this->getUser()->getGuardUser()->getId());
            $NotificationAction->setType('Action');
            $NotificationAction->save();
        } elseif ($request->getParameter('ThisProd') == "on") {
            NotificationTable::getInstance()->createQuery()->delete()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='Action'")->addWhere("product_id is null")->execute();
            if (!NotificationTable::getInstance()->createQuery()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='Action'")->addWhere("product_id=?", $productId)->fetchOne()) {
                $NotificationAction = new Notification();
                $NotificationAction->setUserId($this->getUser()->getGuardUser()->getId());
                $NotificationAction->setType('Action');
                $NotificationAction->setProductId($productId);
                $NotificationAction->save();
            }
        } else {
            NotificationTable::getInstance()->createQuery()->delete()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='Action'")->addWhere("product_id is null")->execute();
            NotificationTable::getInstance()->createQuery()->delete()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='Action'")->addWhere("product_id=?", $productId)->execute();
        }
        if ($request->getParameter('CommentThisProd') == "on") {
            if (!NotificationTable::getInstance()->createQuery()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='Comment'")->addWhere("product_id=?", $productId)->fetchOne()) {
                $NotificationComment = new Notification();
                $NotificationComment->setUserId($this->getUser()->getGuardUser()->getId());
                $NotificationComment->setType('Comment');
                $NotificationComment->setProductId($productId);
                $NotificationComment->save();
            }
        } else {
            NotificationTable::getInstance()->createQuery()->delete()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='Comment'")->addWhere("product_id=?", $productId)->execute();
        }
        if ($request->getParameter('UserPhotoThisProd') == "on") {
            if (!NotificationTable::getInstance()->createQuery()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='UserPhoto'")->addWhere("product_id=?", $productId)->fetchOne()) {
                $NotificationUserPhoto = new Notification();
                $NotificationUserPhoto->setUserId($this->getUser()->getGuardUser()->getId());
                $NotificationUserPhoto->setType('UserPhoto');
                $NotificationUserPhoto->setProductId($productId);
                $NotificationUserPhoto->save();
            }
        } else {
            NotificationTable::getInstance()->createQuery()->delete()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='UserPhoto'")->addWhere("product_id=?", $productId)->execute();
        }
        if ($request->getParameter('ReminderThisProd') == "on") {
            if (!$notificationReminder = NotificationTable::getInstance()->createQuery()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='ReminderThisProd'")->addWhere("product_id=?", $productId)->fetchOne()) {
                $notificationReminder = new Notification();
                $notificationReminder->setUserId($this->getUser()->getGuardUser()->getId());
                $notificationReminder->setType('ReminderThisProd');
                $notificationReminder->setProductId($productId);
                $notificationReminder->setDateevent(date("Y-m-d", strtotime($request->getParameter('dateevent'))));
                $notificationReminder->setComment($request->getParameter('comment'));
                $notificationReminder->save();
            } else {
                $notificationReminder->setDateevent(date("Y-m-d", strtotime($request->getParameter('dateevent'))));
                $notificationReminder->setComment($request->getParameter('comment'));
                $notificationReminder->save();
            }
        } else {
            NotificationTable::getInstance()->createQuery()->delete()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("type='ReminderThisProd'")->addWhere("product_id=?", $productId)->execute();
        }
    }

    public function executeDelnotification(sfWebRequest $request) {
        $productId = $request->getParameter('productId');
        if ($productId > 0) {
            NotificationTable::getInstance()->createQuery()->delete()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->addWhere("product_id=?", $productId)->execute();
        } else {
            NotificationTable::getInstance()->createQuery()->delete()->where("user_id = ?", $this->getUser()->getGuardUser()->getId())->execute();
        }
        return $this->renderText("true");
    }

    public function executeShowUserPhotos(sfWebRequest $request) {
        $photosKeys = explode(",", $request->getParameter('photosKeys'));
        if (array_search($request->getParameter('id'), $photosKeys) !== false) {
            $keyId = array_search($request->getParameter('id'), $photosKeys);
            if ($keyId != 0) {
                $this->preId = $photosKeys[$keyId - 1];
            }
            if ($keyId != count($photosKeys) - 1) {
                $this->postId = $photosKeys[$keyId + 1];
            }
        }
        $this->photosKeys = $photosKeys;
        $photo = Doctrine_Core::getTable('PhotosUser')->findOneById(array($request->getParameter('id')));

        $this->photo = $photo;
    }

    public function executePreshow(sfWebRequest $request) {
        $productsKeys = explode(",", $request->getParameter('productsKeys'));
        $parentsId = $request->getParameter('parentsId');
        if ($parentsId > 0) {
            $idForFindProductsKeys = $parentsId;
        } else {
            $idForFindProductsKeys = $request->getParameter('id');
        }
        if (array_search($idForFindProductsKeys, $productsKeys) !== false) {
            $keyId = array_search($idForFindProductsKeys, $productsKeys);
            if ($keyId != 0) {
                $this->preId = $productsKeys[$keyId - 1];
            }
            if ($keyId != count($productsKeys) - 1) {
                $this->postId = $productsKeys[$keyId + 1];
            }
        }
        $this->productsKeys = $productsKeys;
        $product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('id')));

        $this->product = $product;
    }

    public function executePreshowPhoto(sfWebRequest $request) {

        $product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('id')));

        $this->product = $product;
    }

    public function executeUserSendForm(sfWebRequest $request) {
        $product = ProductTable::getInstance()->findOneById($request->getParameter('id'));
        $this->product = $product;
    }

    public function executeChangechildren(sfWebRequest $request) {

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $this->id = $request->getParameter('id');
        $photo['filename'] = $request->getParameter('photo');
        $this->photo = $photo;
        $comments['countcomm'] = $request->getParameter('comments');
        $this->comments = $comments;
        $this->product = $q->execute("SELECT *  "
                        . "FROM product "
                        . "WHERE id =  ? "
                        . "AND is_public='1'", array($this->id))->fetch(Doctrine_Core::FETCH_ASSOC);
    }

}
