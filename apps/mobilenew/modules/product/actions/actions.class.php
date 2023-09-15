<?php

/**
 * product actions.
 *
 * @package    test
 * @subpackage product
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class productActions extends sfActions {

    public function executeShow(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerProduct = sfTimerManager::getTimer('Action: Загрузка товара');
        $this->product = $q->execute("SELECT * FROM product WHERE slug=?", array($request->getParameter('slug')))->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->forward404Unless($this->product);
        $timerProduct->addTime();


        $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для товара');
        $this->commentsProduct = $q->execute("SELECT c.rate_set,c.username,c.text,c.created_at, u.first_name as first_name "
                        . "from comments as c "
                        . "left join sf_guard_user as u on u.id=c.customer_id "
                        . "WHERE c.is_public='1' and c.product_id = ?", array($this->product['id']))
                ->fetchAll(Doctrine_Core::FETCH_ASSOC);
        $timerComments->addTime();

        $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для товара');
        $this->photosProduct = $q->execute("SELECT photo.filename as filename from photo "
                        . "left join product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                        . "WHERE ppa.product_id = ? "
                        . "ORDER BY photo.position ASC", array($this->product['id']))
                ->fetchAll(Doctrine_Core::FETCH_ASSOC);
        //print_r($this->photosAll);exit;
        $timerPhotos->addTime();

        $timerParams = sfTimerManager::getTimer('Action: Загрузка параметров товара');
        $productParentId = $this->product['parents_id'] != "" ? $this->product['parents_id'] : $this->product['id'];
        $productsIdForParams = $q->execute("SELECT id "
                        . "FROM product "
                        . "WHERE id=? "
                        . "OR parents_id=?", array($productParentId, $productParentId))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $productsIdForParams = array_keys($productsIdForParams);
        $this->params = $q->execute("SELECT di.id as dopinfo_id, "
                        . "dic.name as name, "
                        . "di.value as value, "
                        . "p.slug as prod_slug, "
                        . "p.id as prod_id, "
                        . "p.count as prod_count, "
                        . "dic.position as sort, "
                        . "dic.id as category_id, "
                        . "count(dic.id) as count_params, "
                        . "man.slug AS man_slug, "
                        . "col.slug AS col_slug "
                        . "FROM `dop_info` as di "
                        . "left join dop_info_category as dic on dic.id = di.dicategory_id "
                        . "left join dop_info_product as dip on dip.dop_info_id=di.id "
                        . "left join product as p on p.id=dip.product_id "
                        . "LEFT JOIN manufacturer AS man ON man.subid = di.id and man.is_public='1' "
                        . "LEFT JOIN collection AS col ON col.subid = di.id and col.is_public='1' "
                        . "where dip.product_id in (" . implode(",", $productsIdForParams) . ") AND dic.is_public =1  "
                        . "group by di.id "
                        . "ORDER BY `dic`.`position` ASC, `di`.`position` ASC")->fetchAll(Doctrine_Core::FETCH_ASSOC);

        $this->countProductsParams = count($productsIdForParams);
        $timerParams->addTime();

        $timerStock = sfTimerManager::getTimer('Action: Загрузка данных наличия в магазине');
        $stock = unserialize($this->product['stock']);
        if (@is_array($stock['storages']['storage']['@attributes'])) {
            $stockArray[] = $stock['storages']['storage'];
        } else {
            $stockArray = $stock['storages']['storage'];
        }
        $shopsId = "";
        if (@count($stockArray) > 0)
            foreach ($stockArray as $storage) {
                $shopsId = $shopsId . ($shopsId == "" ? "'".$storage['@attributes']['code1c']."'" : ",'" . $storage['@attributes']['code1c']."'");
            }
        if ($shopsId != "")
            $this->shops = $q->execute("SELECT id1c,iconmetro,metro,address,worktime, p.slug "
                            . "FROM shops "
                            . "LEFT JOIN page AS p ON p.id = shops.page_id "
                            . "WHERE id1c in (" . $shopsId . ")")->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerStock->addTime();


        $timerDeliveryAndPayment = sfTimerManager::getTimer('Action: Загрузка страницы доставка и оплата');
        $this->deliveryAndPayment = $q->execute("SELECT * from page WHERE slug='dostavka-i-oplata'")->fetch(Doctrine_Core::FETCH_ASSOC);
        $timerDeliveryAndPayment->addTime();




        $timerCategory = sfTimerManager::getTimer('Action: Главная категория товара');
        $this->category = $q->execute("SELECT * "
                        . "FROM category "
                        . "WHERE id = '" . $this->product['generalcategory_id'] . "'")
                ->fetch(Doctrine_Core::FETCH_ASSOC);
        $timerCategory->addTime();


        $timerSimilarProducts = sfTimerManager::getTimer('Action: Товары раздела Похожие товары');
        $this->productsSimilar = $q->execute("SELECT id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
                        . "FROM product "
                        . "WHERE  generalcategory_id='" . $this->product['generalcategory_id'] . "' and is_public='1' and count>0 "
                        . "LIMIT 2")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerSimilarProducts->addTime();

        if (count($this->productsSimilar) > 0) {
            $timerComments = sfTimerManager::getTimer('Action: Загрузка комментариев для всех товаров');
            $this->commentsAll = $q->execute("SELECT product_id,count(product_id) as countcomm from comments "
                            . "WHERE is_public='1' and product_id in (" . implode(',', array_keys($this->productsSimilar)) . ") "
                            . "group by product_id")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerComments->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename from photo "
                            . "left join product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", array_keys($this->productsSimilar)) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();
        }
    }

    public function executeSenduser(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerProduct = sfTimerManager::getTimer('Action: Загрузка товара');
        $this->product = $q->execute("SELECT * FROM product WHERE slug=?", array($request->getParameter('slug')))->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->forward404Unless($this->product);
        $timerProduct->addTime();

        $this->senduserexist = false;
        $this->errorCapSu = false;

        if ($request->isMethod(sfRequest::POST)) {

            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='su'")->fetchOne();
            if ($request->getParameter('sucaptcha') == $captcha->getVal()) {
                $senduserexist = SenduserTable::getInstance()->createQuery()->where("product_id = ?", $this->product['id'])->addWhere("mail = ?", $request->getParameter('mail'))->execute();

                if (count($senduserexist->toArray()) > 0) {
                    $this->senduserexist = true;
                } else {
                    $senduser = new Senduser();
                    $senduser->set("product_id", $this->product['id']);
                    $senduser->setName($request->getParameter('name'));
                    $senduser->setMail($request->getParameter('mail'));
                    $senduser->save();

                    /* оформляем запрос как заказ */
                    $this->sendOrderWhenAskForNoticeOutStock($senduser);
                }
                $this->errorCapSu = false;
            } else {
                $this->errorCapSu = true;
            }
            $this->setTemplate("senduser");
        }
    }
    public function sendOrderWhenAskForNoticeOutStock($senduser){

      $product = Doctrine_Core::getTable('Product')->findOneById($senduser->getProduct_id());

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
      $comment = "Запрос на отсутствующий товар. \r\n ";
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

    public function executeAddComment(sfWebRequest $request) {

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerProduct = sfTimerManager::getTimer('Action: Загрузка товара');
        $this->product = $q->execute("SELECT * FROM product WHERE slug=?", array($request->getParameter('slug')))->fetch(Doctrine_Core::FETCH_ASSOC);
        $this->forward404Unless($this->product);
        $timerProduct->addTime();
        $this->form = false;
        if ($request->isMethod(sfRequest::POST)) {
            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='k'")->fetchOne();
            if ($request->getParameter('cText') == $captcha->getVal() and $request->getParameter('cComment') != "") {
                $this->product = Doctrine_Core::getTable('Product')->findOneBySlug(array($request->getParameter('slug')));
                $this->product->setRating($this->product->getRating() + $request->getParameter('cRate'));
                $this->product->setVotesCount($this->product->getVotesCount() + 1);
                $this->product->save();

                $comment = new Comments();
                $comment->setText($request->getParameter('cComment'));
                if ($this->getUser()->isAuthenticated()) {
                    $comment->setCustomerId($this->getUser()->getGuardUser()->getId());
                    $comment->save();
                } else {
                    $comment->setUsername($request->getParameter('cName'));
                    $comment->setMail($request->getParameter('cEmail'));
                }
                $comment->setProductId($this->product->getId());
                $comment->setRateSet($request->getParameter('cRate'));
                $comment->save();
            } else {
                $this->form = true;
            }
            $this->cComment = $request->getParameter('cComment');
            $this->cName = $request->getParameter('cName');
            $this->cEmail = $request->getParameter('cEmail');
            $this->cRate = $request->getParameter('cRate');
        } else {
            $this->form = true;
            $this->cComment = "";
            $this->cName = "";
            $this->cEmail = "";
            $this->cRate = "";
        }
    }

    public function executeAddtocart(sfWebRequest $request) {

        $productsToCart = unserialize($this->getUser()->getAttribute('products_to_cart'));
        $row = array();
        $row["productId"] = (int) $request->getParameter('id');
        $row["productOptions"] = $request->getParameter('productOptions');
        $row["quantity"] = $request->getParameter('quantity', 1);
        $product = ProductTable::getInstance()->findOneById($row["productId"]);

        if ($product->getOldPrice() > 0) {
            $row["price"] = $product->getOldPrice();
            $row["price_w_discount"] = $product->getPrice();
            $row["discount"] = round((1 - ($product->getPrice() / $product->getOldPrice())) * 100);
        } else {
            $row["price"] = $product->getPrice();
            $row["price_w_discount"] = $product->getPrice();
        }
        foreach ($productsToCart as $key => $product) {
            if (@$product["productId"] == @$row["productId"] && @$product["productOptions"] == @$row["productOptions"]) {
                $row = array();
            }
        }
        if (isset($row["productId"]) && $row["productId"] > 0) {
            $productsToCart[$row["productId"]] = $row;
        }

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($productsToCart as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * $product["price"]);
        }
        $this->getUser()->setAttribute('productsCount', $this->productsCount);
        $this->getUser()->setAttribute('totalCost', $this->totalCost);
        $this->getUser()->setAttribute('products_to_cart', serialize($productsToCart));

        if ($this->getUser()->isAuthenticated()) {
            $GuardUser = $this->getUser()->getGuardUser();
            $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
            $GuardUser->save();
        }


        return $this->renderText(count($productsToCart));
    }

    public function executeRate(sfWebRequest $request) {

        if ($request->getParameter('nonAdd') != "1") {
            $product = Doctrine_Core::getTable('Product')->findOneById(array($request->getParameter('productId')));
            $product->setRating($product->getRating() + $request->getParameter('value'));
            $product->setVotesCount($product->getVotesCount() + 1);
            $product->save();
            $this->getResponse()->setCookie("rate_" . $product->getId(), 1, time() + 60 * 60 * 24 * 365, '/');
            $this->product = $product;
        } else {
            return $this->renderText($request->getParameter('value'));
        }
    }

}
