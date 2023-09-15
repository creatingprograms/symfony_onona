<?php

/**
 * noncache actions.
 *
 * @package    test
 * @subpackage noncache
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class noncacheActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeLogvalidmail(sfWebRequest $request) {
        $file = '/var/www/ononaru/data/www/onona.ru/logvalidmail.txt';
        $current = file_get_contents($file);
        $current .= "Почта: " . ($_POST['mail']) . " Форма: " . ($_POST['form']) . " Время: " . date("d.m.Y H:i:s") . "\n";
        file_put_contents($file, $current);
        return true;
    }

    public function executeRegssauth(sfWebRequest $request) {
        $this->email = $this->getUser()->getAttribute('emailReggSSAuth');
    }

    public function executeAddNotification(sfWebRequest $request) {
        $this->email = $request->getParameter('emailAddNotification');
        $this->category = $request->getParameter('categoryAddNotification');
        if ($this->getUser()->isAuthenticated()) {
            $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("category_id=?", $this->category)->fetchOne();
        } else {
            $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_mail=?", $this->email)->addWhere("category_id=?", $this->category)->fetchOne();
        }
        if ($notificationCategory) {
            $notificationCategory->set("is_enabled", true);
            $notificationCategory->save();
        } else {
            $notificationCategory = new NotificationCategory();
            if ($this->getUser()->isAuthenticated()) {
                $notificationCategory->set("user_id", $this->getUser()->getGuardUser()->getId());
            } else {
                $notificationCategory->set("user_mail", $this->email);
            }
            $notificationCategory->set("category_id", $this->category);
            $notificationCategory->set("is_enabled", true);
            $notificationCategory->save();
        }
    }

    public function executeDelNotification(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated()) {
            $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("category_id=?", $request->getParameter('catid'))->fetchOne();
        } else {
            //$notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_mail=?", $this->email)->addWhere("category_id=?", $this->category)->fetchOne();
        }
        if ($notificationCategory) {
            $notificationCategory->set("is_enabled", false);
            $notificationCategory->save();
        }
    }

    public function executeFirstvisit(sfWebRequest $request) {
        $this->getResponse()->setCookie("firstvisit", 1, time() + 60 * 60 * 24 * 365, '/');
        if ($request->getParameter('stats') == 'yes') {
            if ($request->getParameter('user_mail') != "") {
                $userIsSet = sfGuardUserTable::getInstance()->findOneByEmailAddress($request->getParameter('user_mail'));
                if (!$userIsSet) {

                    $user = new sfGuardUser();

                    $username = uniqid("user_");
                    $user->setUsername($username);
                    $password = uniqid();
                    $user->set("password", $password);
                    $user->setFirstName($request->getParameter('user_name'));
                    $user->setEmailAddress($request->getParameter('user_mail'));
                    $user->setSex($request->getParameter('user_sex'));

                    $user->save();
                    $this->getUser()->signin($user);



                    if ($this->is_email($user->getEmailAddress())) {
                        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('register');
                        $message = Swift_Message::newInstance()
                                ->setFrom(csSettings::get('smtp_user'), "OnOna.ru"/* sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com') */)
                                ->setTo($user->getEmailAddress())
                                ->setSubject(csSettings::get("theme_register_email")/* .$this->form->user->username */)
                                ->setBody(str_replace(array('{$login}', '{$password}'), array($user->getEmailAddress(), $password), $mailTemplate->getText()), 'text/html')
                                ->setContentType('text/html')
                        ;
                        //echo $user->getEmailAddress();
                        $this->getMailer()->send($message);
                    }

                    $bonus = new Bonus();
                    $bonus->setUserId($user);
                    $bonus->setBonus(csSettings::get("register_bonus_add"));
                    $bonus->setComment("Зачисление за подписку");
                    $bonus->save();
                } else {
                    return $this->redirect('/subscription');
                }
            }
        }
        $user = $this->getUser();
        $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));

        return $this->redirect('' != $signinUrl ? $signinUrl : '/sexshop');
        return true;
    }

    public function executeCartinfoheader(sfWebRequest $request) {
        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($products_old as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * $product["price"]);
        }
        $this->products_old = $products_old;
    }

    public function executeCartinfotop(sfWebRequest $request) {
        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($products_old as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * $product["price"]);
        }
        $this->products_old = $products_old;
    }

    public function executeKonultsexolog(sfWebRequest $request) {
        $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                ->setUsername(csSettings::get("smtp_user"))
                ->setPassword(csSettings::get("smtp_pass"));
        $mailer = Swift_Mailer::newInstance($transport);

        $emailsfastorder = explode(";", csSettings::get("emailskonssex"));
        foreach ($emailsfastorder as $email) {
            $arrayemailsfastorder[$email] = "";
        }
        //print_r($arrayemailsfastorder);exit;
        $message = Swift_Message::newInstance('Поступил запрос на консультацию сексолога', "Здравствуйте!<br>
		Ваше имя: " . $_POST['form_text_638'] . "<br>
		Фамилия: " . $_POST['form_text_639'] . "<br>
		Телефон: " . $_POST['form_text_640'] . "<br>
		Получатель: " . $_POST['name'] . "<br>
		Его телефон: " . $_POST['phone'] . "<br>
		Его почта: " . $_POST['mail'] . "<br>", 'text/html', 'utf-8')
                ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                ->setTo($arrayemailsfastorder)
        ;

//Send the message
        $numSent = $mailer->send($message);
    }

    public function execute18age(sfWebRequest $request) {
        
    }

    public function executeBonuspayprod(sfWebRequest $request) {
        if ($request->getParameter('nonMessageBonusProd') == "on") {
            sfContext::getInstance()->getResponse()->setCookie('nonMessageBonusProd', true, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
            if ($request->getReferer() != "") {
                echo "!";
                $this->redirect($request->getReferer());
            } else {
                $this->redirect("/");
            }
        } else {

            sfContext::getInstance()->getResponse()->setCookie('oneMessageBonusProd', true, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
            if ($request->getReferer() != "") {
                echo "!";
                $this->redirect($request->getReferer());
            } else {
                $this->redirect("/");
            }
        }
    }

    public function executeRefid(sfWebRequest $request) {
        $this->partner = PartnerIdTable::getInstance()->findByRefid($request->getParameter('refid'));
    }

    public function executeRefidpartner(sfWebRequest $request) {
        $this->partner = PartnerRefidTable::getInstance()->findAll();
    }

    public function is_email($email) {
        //return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //echo "Valid email address.";
            return true;
        } else {
            return false;
            //echo "Invalid email address.";
        }
    }

    public function executeSupport(sfWebRequest $request) {
        sfContext::getInstance()->getResponse()->setCookie('age18', true, time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
        if ($request->isMethod(sfRequest::POST)) {
            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='sup'")->fetchOne();
            if ($request->getParameter('code') == $captcha->getVal()) {
                /* $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                  ->setUsername(csSettings::get("smtp_user"))
                  ->setPassword(csSettings::get("smtp_pass"));
                  $mailer = Swift_Mailer::newInstance($transport); */
                if ($this->is_email($_POST['email'])) {
                    $message = Swift_Message::newInstance('Сообщение - форма обратной связи.', "Здравствуйте!<br>
		Имя: " . $_POST['name'] . "<br>
		Email: " . $_POST['email'] . "<br>
		Тема сообщения: " . $_POST['sub'] . "<br>
		Сообщение: " . nl2br($_POST['msg']) . "<br>", 'text/html', 'utf-8')
                            ->setFrom(array((csSettings::get("smtp_user") != "" ? csSettings::get("smtp_user") : 'info@onona.ru') => 'Почтовый робот сайта OnOna.Ru'))
                            ->setTo("info@onona.ru")
                            ->setSubject('Сообщение - форма обратной связи.')
                            ->setBody("Здравствуйте!<br>
		Имя: " . $_POST['name'] . "<br>
		Email: " . $_POST['email'] . "<br>
		Тема сообщения: " . $_POST['sub'] . "<br>
		Сообщение: " . nl2br($_POST['msg']) . "<br>")
                            ->setContentType('text/html')
                    ;
                    $this->getMailer()->send($message);

                    /*  $message = Swift_Message::newInstance('Сообщение - форма обратной связи.', "Здравствуйте!<br>
                      Имя: " . $_POST['name'] . "<br>
                      Email: " . $_POST['email'] . "<br>
                      Тема сообщения: " . $_POST['sub'] . "<br>
                      Сообщение: " . nl2br($_POST['msg']) . "<br>", 'text/html', 'utf-8')
                      ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                      ->setTo("svs@onona.ru");

                      $numSent = $mailer->send($message); */
                }
                $this->errorCap = false;
            } else {
                $this->errorCap = true;
            }
        }
    }

    public function executeXmladmitad(sfWebRequest $request) {
        header('Content-type: text/xml; charset=utf-8');
        mb_internal_encoding('UTF-8');
        $pass = "Wiea9CrK0e";

        if ($_GET['pass'] != $pass)
            die('<?xml version="1.0"?><error>no confirm pass</error>');
        $ordersAdmitad = OrdersTable::getInstance()->createQuery()->where("referal =2801045062")->addWhere("status='Отмена' or status='Возврат' or status='Оплачен'")->execute();

        $res = '';
        $res.="<?xml version=\"1.0\"?><Payments xmlns=\"http://admitad.com/payments-revision\">";
        foreach ($ordersAdmitad as $order) {
            // вместо getOrderById вам нужно прописать
            // свою функцию, которая получает данные из БД
            $TotalSumm = 0;
            $products_old = $order->getText();
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            foreach ($products_old as $key => $productInfo):
                $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price_w_discount']);
            endforeach;
            //echo mb_strtolower($order->getStatus());
            if (mb_strtolower($order->getStatus()) == "оплачен") {
                $status = '1';
            } elseif (mb_strtolower($order->getStatus()) == "отмена" or mb_strtolower($order->getStatus()) == "возврат") {
                $status = '2';
            }
            $res .= '<Payment>';
            $res .= '<OrderID>' . ($order->getId()) . '</OrderID>';
            $res .= '<OrderAmount>' . $TotalSumm . '</OrderAmount>';
            $res .= '<Status>' . $status . '</Status>';
            $res .= '</Payment>';
        }

        //$res = '' . $res . '';
        $res.="</Payments>";
        echo $res;
        exit;
        //return $this->renderPartial("xmlmyadsSuccess");
    }

    public function executeXmlmyads(sfWebRequest $request) {
        header('Content-type: text/xml; charset=utf-8');
        mb_internal_encoding('UTF-8');
        $pass = "h478v7en4";

        if ($_POST['pass'] != md5($pass))
            die('<?xml version="1.0"?><error>no confirm pass</error>');
        /* $_POST['xml'] = "<items>
          <item>107689</item>
          <item>107688</item>
          <item>107678</item>
          <item>107611</item>
          </items>"; */
        $res = '';
        preg_match_all("/<item>(.*)<\/item>/Uis", $_POST['xml'], $items);
        $res.="<?xml version=\"1.0\"?><items>";
        foreach ($items[1] as $oid) {
            // вместо getOrderById вам нужно прописать
            // свою функцию, которая получает данные из БД
            $order = OrdersTable::getInstance()->findOneById($oid);
            $TotalSumm = 0;
            $products_old = $order->getText();
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            foreach ($products_old as $key => $productInfo):
                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
            endforeach;
            //echo mb_strtolower($order->getStatus());
            if (mb_strtolower($order->getStatus()) == "оплачен") {
                $status = 'done';
            } elseif (mb_strtolower($order->getStatus()) == "отмена") {
                $status = 'cancel';
            } else {
                $status = 'wait';
            }
            $res .= '<item>';
            $res .= '<id>' . $oid . '</id>';
            $res .= '<status>' . $status . '</status>';
            $res .= '<price>' . $TotalSumm . '</price>';
            $res .= '</item>';
        }

        //$res = '' . $res . '';
        $res.="</items>";
        echo $res;
        exit;
        //return $this->renderPartial("xmlmyadsSuccess");
    }

    public function executeXmlleadtrade(sfWebRequest $request) {
        /* header('Content-type: text/xml; charset=utf-8');
          mb_internal_encoding('UTF-8');

          $res = '';
          preg_match_all("/<item>(.*)<\/item>/Uis", $_POST['xml'], $items);
          $res.="<?xml version=\"1.0\"?><items>";
          foreach ($items[1] as $oid) {
          // вместо getOrderById вам нужно прописать
          // свою функцию, которая получает данные из БД
          $order = OrdersTable::getInstance()->findOneById($oid);
          if ($order):
          //echo mb_strtolower($order->getStatus());
          if (mb_strtolower($order->getStatus()) == "оплачен") {
          $status = '1';
          } elseif (mb_strtolower($order->getStatus()) == "отмена") {
          $status = '3';
          } else {
          $status = '2';
          }
          $res .= '<item>';
          $res .= '<id>' . $oid . '</id>';
          $res .= '<status>' . $status . '</status>';
          $res .= '</item>';
          endif;
          }

          //$res = '' . $res . '';
          $res.="</items>";
          echo $res;
          exit; */
    }

    public function executeXmlcityads(sfWebRequest $request) {
        /* header('Content-type: text/xml; charset=utf-8');
          mb_internal_encoding('UTF-8');
          if (empty($_POST['xml']))
          die('<?xml version="1.0"?><error>no xml params</error>');
          $res = '';
          preg_match_all("/<date_from>(.*)<\/date_from>/Uis", $_POST['xml'], $date_from);
          preg_match_all("/<date_to>(.*)<\/date_to>/Uis", $_POST['xml'], $date_to);
          $res.="<?xml version=\"1.0\"?><items>";
          //print_r(date("Y-m-d H:i:s", (int) $date_from[1][0]) );
          $orders = OrdersTable::getInstance()->createQuery()->where('updated_at> "' . date("Y-m-d H:i:s", (int) $date_from[1][0]) . '"')->andWhere('updated_at<"' . date("Y-m-d H:i:s", (int) $date_to[1][0]) . '"')->andWhere('prxcityads <> "NULL" and prxcityads is not null')->execute();
          foreach ($orders as $order) {
          if (mb_strtolower($order->getStatus()) == "оплачен") {
          $status = 'done';
          } elseif (mb_strtolower($order->getStatus()) == "отмена") {
          $status = 'cancel';
          } else {
          $status = 'wait';
          }
          $res .= '<item>';
          $res .= '<prx>' . $order->getPrxcityads() . '</prx>';
          $res .= '<id>' . $order->getId() . '</id>';
          $res .= '<status>' . $status . '</status>';
          //$res .= '<price>' . $TotalSumm . '</price>';
          $res .= '<date>' . strtotime($order->getUpdatedAt()) . '</date>';
          $res .= '</item>';
          }

          //$res = '' . $res . '';
          $res.="</items>";
          echo $res;
          exit;
          //return $this->renderPartial("xmlmyadsSuccess"); */
    }

    public function executeXmlmyragon(sfWebRequest $request) {
        /*  header('Content-type: text/xml; charset=utf-8');
          mb_internal_encoding('UTF-8');
          if (empty($_POST['xml']))
          die('<?xml version="1.0"?><error>no xml params</error>');
          $res = '';
          preg_match_all("/<date_from>(.*)<\/date_from>/Uis", $_POST['xml'], $date_from);
          preg_match_all("/<date_to>(.*)<\/date_to>/Uis", $_POST['xml'], $date_to);
          $res.="<?xml version=\"1.0\"?><items>";
          //print_r(date("Y-m-d H:i:s", (int) $date_from[1][0]) );
          $orders = OrdersTable::getInstance()->createQuery()->where('((updated_at> "' . date("Y-m-d H:i:s", (int) $date_from[1][0]) . '" and updated_at<"' . date("Y-m-d H:i:s", (int) $date_to[1][0]) . '") or (created_at> "' . date("Y-m-d H:i:s", (int) $date_from[1][0]) . '" and created_at<"' . date("Y-m-d H:i:s", (int) $date_to[1][0]) . '"))')->addWhere('referal ="2764355315"')->execute();
          foreach ($orders as $order) {
          if (mb_strtolower($order->getStatus()) == "оплачен") {
          $status = 'done';
          } elseif (mb_strtolower($order->getStatus()) == "отмена") {
          $status = 'cancel';
          } else {
          $status = 'wait';
          }
          $TotalSumm = 0;
          $products_old = $order->getText();
          $products_old = $products_old != '' ? unserialize($products_old) : '';
          foreach ($products_old as $key => $productInfo):
          $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
          $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
          endforeach;
          $res .= '<item>
          <subaccount>' . $order->getSamyragon() . '</subaccount>
          <id>' . $order->getId() . '</id>
          <price>' . $TotalSumm . '</price>
          <currency>RUR</currency>
          <status>' . $status . '</status>';
          if ($status == 'cancel')
          $res .= '<status_ext>wrong_phone</status_ext>';

          $res .= '<date>' . strtotime($order->getUpdatedAt()) . '</date>  ';
          $res .= '</item>';
          }

          //$res = '' . $res . '';
          $res.="</items>";
          echo $res;
          exit;
          //return $this->renderPartial("xmlmyadsSuccess"); */
    }

    public function executeSliderrelated(sfWebRequest $request) {
        $content = '<html>
<head>
<script src="https://onona.ru/newdis/js/jquery-1.7.2.min.js" type="text/javascript"></script>
           <script src="https://onona.ru/newdis/js/jquery.main.js" type="text/javascript"></script>
           <link href="https://onona.ru/newdis/css/all.css?v=14" media="screen" type="text/css" rel="stylesheet">
           

</head><body style="background: none;"> ';

        $relatedProduct = ProductTable::getInstance()->getRelatedProduct();
        //echo $relatedProduct->count();
        if ($relatedProduct->count() > 0):
            $content.='<div id="header"></div><div id="main" style="padding: 0;"><div class="aside" style="float: left; margin: 0px;">
                                <div class="leaders-galery box">
                                    <div class="title-holder"><a href="https://onona.ru/related?r=' . $_GET['refpar'] . '" style="color: #C3060E" target="_top">Лидеры продаж</a></div>
                                    <div class="galery-holder">
                                        <a href="#" class="prev"></a>
                                        <a href="#" class="next"></a>
                                        <div class="galery" style="height: 550px">
                                            <ul>
                                                ';
            $q = Doctrine_Query::create()->select("name, slug, (select filename from photo where album_id=(select photoalbum_id from product_photoalbum where product_id=product.id) order by position asc limit 0,1) as filename")->from("product")->where("`is_related` = 1")->orderBy("rand()")->execute();
            foreach ($q as $product):
                $content.='<li><div style="text-align: center; width:186px;margin:0 auto 10px;">' . $product->getName() . '</div><div class="prod">' . ( $product->getDiscount() > 0 ? ('<span class="sale' . $product->getDiscount() . '" style="top: 0px;"></span>') : '') . '<a target="_top" href="https://onona.ru/product/' . $product->getSlug() . '?r=' . $_GET['refpar'] . '" style="display: table-cell;vertical-align: middle;height: 268px;"><img src="/uploads/photo/thumbnails_250x250/' . $product->getFilename() . '" style="max-width: 188px; max-height: 260px;" alt="' . str_replace(array("'", '"'), "", $product->getName()) . '" /></a></div></li> ';
            endforeach;
            $content.='
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            ';
        endif;
        $content.='
                        </div></div></div><div id="footer"></div><a class="to-up"></a></body></html>';

        echo $content;
        exit;
    }


    public function executeSliderMainPageProd(sfWebRequest $request) {
        if ($request->getParameter('prodId') != "") {
            $products = ProductTable::getInstance()->createQuery()->where("id in (" . $request->getParameter('prodId') . ")")
                    ->addWhere('is_public = \'1\'')
                    ->addWhere('count > 0');
            foreach (explode(",", $request->getParameter('prodId')) as $id) {
                $products->addOrderBy("(id=" . (int) $id . ") desc");
            }

            $products = $products->execute();
            $this->products = $products;
        }
    }

    public function executeTest(sfWebRequest $request) {
        
    }

    public function executeApiGetBonusCount(sfWebRequest $request) {
        $userPhone = $request->getParameter('userPhone');
        $randomKey = $request->getParameter('randomKey');
        $secretKey = $request->getParameter('secretKey');
        if (md5($userPhone . $randomKey . "AIY3ESfsJf7MaXSZvN7rmY7DuBgI4s") == $secretKey and $userPhone != "" and $randomKey != "" and $secretKey != "") {

            /* if ($userPhone[0] != "+" and $userPhone[0] != "8") {
              $userPhone = "+7" . $userPhone;
              } */
            $numbersPhoneReplace77 = str_replace("+77", "+7", $userPhone);
            $numbersPhoneReplace77 = preg_replace('/[^\d]/', '', $numbersPhoneReplace77);
            $numbersPhone = preg_replace('/[^\d]/', '', ($userPhone));
            if (strlen($numbersPhone) == 11 and ( $numbersPhone[0] == 7 or $numbersPhone[0] == 8)) {

                $sPhone = "+7(" . $numbersPhone[1] . $numbersPhone[2] . $numbersPhone[3] . ")" . $numbersPhone[4] . $numbersPhone[5] . $numbersPhone[6] . "-" . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9] . $numbersPhone[10];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } elseif (strlen($numbersPhone) == 10 and $numbersPhone[0] != 8 and $numbersPhone[0] != 7) {
                $sPhone = "+7(" . $numbersPhone[0] . $numbersPhone[1] . $numbersPhone[2] . ")" . $numbersPhone[3] . $numbersPhone[4] . $numbersPhone[5] . "-" . $numbersPhone[6] . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } elseif (strlen($numbersPhoneReplace77) == 11 and ( $numbersPhoneReplace77[0] == 7 or $numbersPhoneReplace77[0] == 8)) {

                $sPhone = "+7(" . $numbersPhoneReplace77[1] . $numbersPhoneReplace77[2] . $numbersPhoneReplace77[3] . ")" . $numbersPhoneReplace77[4] . $numbersPhoneReplace77[5] . $numbersPhoneReplace77[6] . "-" . $numbersPhoneReplace77[7] . $numbersPhoneReplace77[8] . $numbersPhoneReplace77[9] . $numbersPhoneReplace77[10];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } else {
                $sPhone = $userPhone;
                $numPhoneUnCorrect++;
                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            }
            $userFind = sfGuardUserTable::getInstance()->createQuery()->where("phone = ?", $sPhone)->addWhere("activephone = '1'")->orderBy("last_login DESC")->limit(1)->fetchOne();
            if ($userFind) {
                $bonusSum = BonusTable::getInstance()->createQuery()->select("sum(bonus) as bonus")->where("user_id = ?", $userFind->getId())->fetchOne();
                return $this->renderText($bonusSum->getBonus());
            } else {

                return $this->renderText('not register');
            }
        } else {
            //echo md5($userPhone . $randomKey . "AIY3ESfsJf7MaXSZvN7rmY7DuBgI4s");
            return $this->renderText("error");
        }
    }

    public function executeApiBonusDown(sfWebRequest $request) {
        $userPhone = $request->getParameter('userPhone');
        $randomKey = $request->getParameter('randomKey');
        $bonus = $request->getParameter('bonus');
        $sumOrder = $request->getParameter('sumOrder');
        $secretKey = $request->getParameter('secretKey');
        if (md5($userPhone . $randomKey . $bonus . $sumOrder . "AIY3ESfsJf7MaXSZvN7rmY7DuBgI4s") == $secretKey and $userPhone != "" and $randomKey != "" and $bonus != "" and $sumOrder != "" and $secretKey != "") {

            /* if ($userPhone[0] != "+" and $userPhone[0] != "8") {
              $userPhone = "+7" . $userPhone;
              } */
            $numbersPhoneReplace77 = str_replace("+77", "+7", $userPhone);
            $numbersPhoneReplace77 = preg_replace('/[^\d]/', '', $numbersPhoneReplace77);
            $numbersPhone = preg_replace('/[^\d]/', '', ($userPhone));
            if (strlen($numbersPhone) == 11 and ( $numbersPhone[0] == 7 or $numbersPhone[0] == 8)) {

                $sPhone = "+7(" . $numbersPhone[1] . $numbersPhone[2] . $numbersPhone[3] . ")" . $numbersPhone[4] . $numbersPhone[5] . $numbersPhone[6] . "-" . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9] . $numbersPhone[10];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } elseif (strlen($numbersPhone) == 10 and $numbersPhone[0] != 8 and $numbersPhone[0] != 7) {
                $sPhone = "+7(" . $numbersPhone[0] . $numbersPhone[1] . $numbersPhone[2] . ")" . $numbersPhone[3] . $numbersPhone[4] . $numbersPhone[5] . "-" . $numbersPhone[6] . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } elseif (strlen($numbersPhoneReplace77) == 11 and ( $numbersPhoneReplace77[0] == 7 or $numbersPhoneReplace77[0] == 8)) {

                $sPhone = "+7(" . $numbersPhoneReplace77[1] . $numbersPhoneReplace77[2] . $numbersPhoneReplace77[3] . ")" . $numbersPhoneReplace77[4] . $numbersPhoneReplace77[5] . $numbersPhoneReplace77[6] . "-" . $numbersPhoneReplace77[7] . $numbersPhoneReplace77[8] . $numbersPhoneReplace77[9] . $numbersPhoneReplace77[10];

                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            } else {
                $sPhone = $userPhone;
                $numPhoneUnCorrect++;
                //echo $userPhone . "  -  " . $sPhone . "  \r\n";
            }
            $userFind = sfGuardUserTable::getInstance()->createQuery()->where("phone = ?", $sPhone)->addWhere("activephone = '1'")->orderBy("last_login DESC")->limit(1)->fetchOne();
            if ($userFind) {
                $bonusSum = BonusTable::getInstance()->createQuery()->select("sum(bonus) as bonus")->where("user_id = ?", $userFind->getId())->fetchOne();

                $bonusLog = new Bonus();
                $bonusLog->setUserId($userFind);
                $bonusLog->setBonus("-" . $bonus);
                $bonusLog->setComment("Снятие бонусов в счет оплаты покупки в магазине на сумму " . $sumOrder);
                $bonusLog->save();

                return $this->renderText("success");
            } else {

                return $this->renderText('not register');
            }
        } else {
            //echo md5($userPhone . $randomKey . $bonus . $sumOrder . "AIY3ESfsJf7MaXSZvN7rmY7DuBgI4s");
            return $this->renderText("error");
        }
    }

}
