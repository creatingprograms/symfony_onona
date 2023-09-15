<?php

/**
 * customer actions.
 *
 * @package    Magazin
 * @subpackage customer
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class customerActions extends sfActions {

  public function executeIndex(sfWebRequest $request) {
    $user = $this->getUser();
    if ($user->isAuthenticated()) {
        $this->user=$user;
        $this->orders = OrdersTable::getInstance()
          ->createQuery('o')
          ->where('customer_id=?', $user->getGuardUser()->getId())
          ->orderBy("id DESC")
          // ->limit(4)
          ->execute();
        $this->bonus = BonusTable::getInstance()->createQuery()->where('user_id = ?', $user->getGuardUser()->getId())->orderBy("created_at DESC")->execute();
        $this->bonusCount = 0;
        // $lastBonus = $this->bonus->toArray();
        // $lastBonus = $lastBonus[0];
        // $this->lastDate = strtotime($lastBonus['created_at']);
        foreach ($this->bonus as $bonus) {
            $this->bonusCount = $this->bonusCount + $bonus->getBonus();
          }
    } else {
        $this->redirect("/guard/login");
    }
  }
  /*
  public function executeMyorders(sfWebRequest $request) {// Мои заказы
      $user = $this->getUser();
      if ($user->isAuthenticated()) {
          $this->orders = OrdersTable::getInstance()
          ->createQuery('o')
          ->where('customer_id=?', $user->getGuardUser()->getId())
          ->orderBy("id DESC")->execute()
          ;
      } else {
          $this->redirect("@homepage");
      }
  }*/

  public function executeOrderdetails(sfWebRequest $request) {// Заказ детально
    if (!$this->getUser()->isAuthenticated() || !($request->getParameter('id') > 0)) $this->redirect("@homepage");

    $this->order = OrdersTable::getInstance()->findOneById($request->getParameter('id'));
    $user = $this->getUser()->getGuardUser();

    if ($this->order)
      if ($this->order->getCustomerId() != $user->getId())
        $this->forward404Unless(false);
    $this->forward404Unless($this->order);
    $bonusAddUser = $TotalSumm = 0;
    $this->customer=$user;
    $products_old = $this->order->getText() != '' ? unserialize($this->order->getText()) : '';
    foreach ($products_old as $key => $productInfo){
        if (isset($productInfo['article'])) {
            $product = ProductTable::getInstance()->findOneByCode($productInfo['article']);
        }
        if (isset($productInfo['productId']) and !$product) {
          $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
        }
        $products[$key]=$product;
        $TotalSumm = $TotalSumm + ($productInfo['quantity'] * (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']));
        if ($product) {
            if ($product->getBonus() != 0) {
                $bonusAddUser = $bonusAddUser + round(((($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']) * $productInfo['quantity'] * $product->getBonus()) / 100);
            } else {
                $bonusAddUser = $bonusAddUser + round(((($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']) * $productInfo['quantity'] * csSettings::get("persent_bonus_add")) / 100);
            }
        }
        unset ($product);
    }
    if(isset($_GET['ildebug'])) die(__FILE__.':'.__LINE__.'<pre>'.print_r(['bonusAddUser'=> $bonusAddUser, 'TotalSumm'=>$TotalSumm, 'products' => $products_old], true));
    $this->bonusAddUser = $bonusAddUser;
    $this->TotalSumm = $TotalSumm;
    $this->products = $products;
    $this->products_old = $products_old;
    $this->pay=PaymentTable::getInstance()->findOneById((int) $this->order->getPaymentId());
    $this->paymentDoc = PaymentdocTable::getInstance()->findOneByOrderId((int) $this->order->getId());
    $this->exchangeRate=csSettings::get('change_rate_ime');

  }

  public function executeMydata(sfWebRequest $request) {//Мои данные
    $user = $this->getUser();
    if ($user->isAuthenticated()) {
      $this->user=$user->getGuardUser();
      $form = new sfGuardUserForm($user->getGuardUser());
      unset($form['algorithm'], $form['salt'], $form['is_active'], $form['is_super_admin'], $form['last_login'], $form['last_ip'], $form['personal_recomendation'], $form['groups_list'], $form['permissions_list'], $form['cart']);

      if (!$request->isMethod('post'))
          $form->setDefault('password', '');
      $form->setWidget('password', new sfWidgetFormInputPassword(array('label' => 'Пароль(заполняйте, если хотите изменить)')));
      // $form->setLabel('password', 'Пароль(заполняйте, если хотите изменить)');
      $this->form = $form;
      $this->update = false;
      if ($request->isMethod('post')){
        // die(print_r($_POST, true));
        if($_POST['token'] != 'Роботы нам тут не нужны!'){
          return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
        }
        if(!$_POST['sf_guard_user']['first_name'])
          return $this->renderText(json_encode(['error' => 'Имя не может быть пустым', 'field' => 'sf_guard_user[first_name]']));
        if(!$_POST['sf_guard_user']['sex'])
          return $this->renderText(json_encode(['error' => 'Укажите пол', 'field' => 'sf_guard_user[sex]']));
        if(!$_POST['sf_guard_user']['last_name'])
          return $this->renderText(json_encode(['error' => 'Фамилия не может быть пустой', 'field' => 'sf_guard_user[last_name]']));
        if(!$_POST['sf_guard_user']['email_address'])
          return $this->renderText(json_encode(['error' => 'Почта не может быть пустой', 'field' => 'sf_guard_user[email_address]']));
        if(!$_POST['sf_guard_user']['phone'])
          return $this->renderText(json_encode(['error' => 'Телефон не может быть пустым', 'field' => 'sf_guard_user[phone]']));
        if(!$_POST['sf_guard_user']['city'])
          return $this->renderText(json_encode(['error' => 'Город не может быть пустым', 'field' => 'sf_guard_user[city]']));
        if(!$_POST['sf_guard_user']['street'])
          return $this->renderText(json_encode(['error' => 'Город не может быть пустым', 'field' => 'sf_guard_user[street]']));

        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        $form->save();

        return $this->renderText(json_encode(['success' => 'Данные успешно изменены']));

      }
    }else {
        $this->forward404Unless(false);
    }
  }
  /*
  protected function processForm(sfWebRequest $request, sfForm $form) {//Обработка формы Мои данные
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid()) {
      $page = $form->save();
      $this->update = true;
      $this->redirect('/customer/mydata');
    }
  }*/
  /*
  public function executeBonus(sfWebRequest $request) {//Мои бонусы
    $user = $this->getUser();
    if ($user->isAuthenticated()) {
      $this->bonus = BonusTable::getInstance()->createQuery()->where('user_id = ?', $user->getGuardUser()->getId())->orderBy("created_at DESC")->execute();
      $this->bonusCount = 0;
      $lastBonus = $this->bonus->toArray();
      $lastBonus = $lastBonus[0];
      $this->lastDate = strtotime($lastBonus['created_at']);
      foreach ($this->bonus as $bonus) {
          $this->bonusCount = $this->bonusCount + $bonus->getBonus();
        }
    } else {
      $this->redirect("/guard/login");
    }
  }*/

  public function executeMyphoto(sfWebRequest $request) {// Мои фотографии
    $user = $this->getUser();
    if ($user->isAuthenticated()) {
      $user = $this->getUser()->getGuardUser();
      $this->userPhotos = PhotosUserTable::getInstance()->createQuery()->where("user_id=?", $user->getId())->execute();
    } else {
      $this->redirect("/guard/login");
    }
  }

  public function executeNotification(sfWebRequest $request) {// Мои оповещения
      $user = $this->getUser();
      if ($user->isAuthenticated()) {

      } else {
          $this->redirect("/guard/login");
      }
  }

  public function executeFavorites(sfWebRequest $request) {// Избранное
    $user = $this->getUser();
    if ($user->isAuthenticated()) {
      $GuardUser = $user->getGuardUser();
      $items=$GuardUser->get(ILTools::FAVORITES_FIELD);
      if($items) $items=unserialize($items);
    }
    else $this->forward404();
    $this->itemIds=array_keys($items);

    // personal_recomendation  now is not in use. may be use it

  }

  public function executeAddFavorite(sfWebRequest $request) {// Добавить в избранное
    $user = $this->getUser();
    if ($user->isAuthenticated()) {
      // $user = $this->getUser()->getGuardUser();
      $id = $request->getParameter('id', 0);
      if(!$id){
        $ret=[
          'success' => false,
          'message' => 'Не указан товар',
        ];
      }
      else{
        $GuardUser = $user->getGuardUser();
        $items=$GuardUser->get(ILTools::FAVORITES_FIELD);

        // $items=$user->getAttribute('favoriteProducts');
        if($items) $items=unserialize($items);

        if(!isset($items[$id])) {
          $items[$id]='';
          $ret=[
            'success' => true,
            'message' => 'Товар успешно добавлен в избранное',
            'direction' => 'in',
          ];
        }
        else {
          unset($items[$id]);
          $ret=[
            'success' => true,
            'message' => 'Товар успешно удален из избранного',
            'direction' => 'out',
          ];
        }

        $itemsSer=serialize($items);
        $GuardUser->set(ILTools::FAVORITES_FIELD, $itemsSer);
        $GuardUser->save();

      }
    } else {
      $ret=[
        'message' => 'Пользователь не авторизован',
        'success' => false,
      ];
    }
    die (json_encode($ret));
  }

  public function executeRemoveFavorite(sfWebRequest $request) {// Добавить в избранное
    $user = $this->getUser();
    if ($user->isAuthenticated()) {
      $id = $request->getParameter('id', 0);
      if(!$id){
        $ret=[
          'success' => false,
          'message' => 'Не указан товар',
        ];
      }
      else{
        $GuardUser = $user->getGuardUser();
        $items=$GuardUser->get(ILTools::FAVORITES_FIELD);

        if($items) $items=unserialize($items);

        unset($items[$id]);
        $ret=[
          'success' => true,
          'message' => 'Товар успешно удален из избранного',
          'direction' => 'out',
        ];

        $itemsSer=serialize($items);
        $GuardUser->set(ILTools::FAVORITES_FIELD, $itemsSer);
        $GuardUser->save();

      }
    } else {
      $ret=[
        'message' => 'Пользователь не авторизован',
        'success' => false,
      ];
    }
    die (json_encode($ret));
  }

  public function executeBonusshop(sfWebRequest $request) {//Добавить бонус за покупку в магазине
    $user = $this->getUser();
    if ($user->isAuthenticated()) {
        if ($request->isMethod(sfRequest::POST)) {
          if($_POST['token'] != 'Роботы нам тут не нужны!'){
            return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
          }
          if(!$_POST['checknum'])
            return $this->renderText(json_encode(['error' => 'Укажите номер чека', 'field' => 'checknum']));
          if(!$_POST['summ'])
            return $this->renderText(json_encode(['error' => 'Укажите сумму чека', 'field' => 'summ']));

          $ordershop = OrdersShopTable::getInstance()->createQuery()->where("checknumber=?", $request->getParameter('checknum'))->addWhere("price=?", intval($request->getParameter('summ')))->fetchOne();
          if ($ordershop) {
              $bonus = BonusTable::getInstance()->createQuery()->where("comment like '%Зачисление за покупку в магазине. Номер чека " . $ordershop->getChecknumber() . ". Сумма: " . $ordershop->getPrice() . "%'")->fetchOne();
              if (!$bonus and ! $ordershop->getActive()) {
                  $bonusCount = round(($ordershop->getPrice() * csSettings::get("bonus_percent_shop")) / 100);
                  // $this->page = PageTable::getInstance()->findOneById(291);
                  // $this->page->setContent(str_replace("{bonus}", $bonusCount, $this->page->getContent()));
                  $bonusSet = new Bonus();
                  $bonusSet->setBonus($bonusCount);
                  $bonusSet->setComment("Зачисление за покупку в магазине. Номер чека " . $ordershop->getChecknumber() . ". Сумма: " . $ordershop->getPrice() . "");
                  $bonusSet->setUserId($user->getGuardUser());
                  $bonusSet->save();
                  $ordershop->setActive(true);
                  $ordershop->setDateactive(date("Y-m-d H:i:s", time()));
                  $ordershop->setIpactive($_SERVER['REMOTE_ADDR']);
                  $ordershop->save();
                  return $this->renderText(json_encode(['success' => 'Вам начислены '.$bonusCount.' бонусов']));
              } else {
                  return $this->renderText(json_encode(['error' => 'Ошибка начисления. Уже начислено']));
              }
          } else {
              return $this->renderText(json_encode(['error' => 'Заказ не найден']));
          }

        } else {
          return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
        }
    } else {
      return $this->renderText(json_encode(['error' => 'Пользователь не авторизован']));
    }
  }

  public function executeBonusadd(sfWebRequest $request) {////Добавить бонус за заказ
      $this->error = true;
      $user = $this->getUser();
      if ($user->isAuthenticated()) {
          if ($request->isMethod(sfRequest::POST)) {
              $order = OrdersTable::getInstance()->createQuery()->where('id=' . $request->getParameter('id'))->fetchOne();
              $this->order = $order;
              $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='sup'")->fetchOne();
              if ($request->getParameter('code') == $captcha->getVal()) {
                  $bonuslog = BonusTable::getInstance()->createQuery()->where('comment like \'%Зачисление за заказ #' . $order->getPrefix() . $order->getId() . '%\'')->fetchOne();
                  if (!$bonuslog and $order->getCustomerId() == $user->getGuardUser()->getId()) {
                      //  echo "1";

                      $productsForBonus = unserialize($order->getText());
                      $bonusAddUser = 0;
                      foreach ($productsForBonus as $artForBonus => $artInfoForBonus) {//echo $stockId;exit;
                          if (isset($artInfoForBonus['article'])) {
                              $product = ProductTable::getInstance()->findOneByCode($artInfoForBonus['article']);
                          }
                          if (isset($artInfoForBonus['productId']) and ! $product) {
                              $product = ProductTable::getInstance()->findOneById($artInfoForBonus['productId']);
                          }
                          if ($product) {
                              if ($product->getBonus() != 0) {
                                  $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $artInfoForBonus['quantity'] * $product->getBonus()) / 100);
                              } else {
                                  $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $artInfoForBonus['quantity'] * csSettings::get("persent_bonus_add")) / 100);
                              }
                          }
                      }
                      $this->error = false;
                      $bonus = new Bonus();
                      $bonus->setBonus($bonusAddUser);
                      $bonus->setComment("Зачисление за заказ #" . $order->getPrefix() . $order->getId());
                      $bonus->setUserId($user->getGuardUser()->getId());
                      $bonus->setActivatelk(1);
                      $bonus->save();
                      // echo $bonusAddUser;
                  }
              } else {

                  $this->errorCap = "Ошибка капчи.";
              }
          }
      }
  }


}
