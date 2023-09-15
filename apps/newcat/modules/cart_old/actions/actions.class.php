<?php

/**
 * cart actions.
 *
 * @package    Magazin
 * @subpackage cart
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cartActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        //$this->getUser()->setAttribute('deliveryId', "");
        $productId = (int) $request->getParameter('id');
        $this->cartFirstPage = 1;

        $this->products_old = $this->getUser()->getAttribute('products_to_cart');
        $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';


        if ($productId > 0 and is_array($this->products_old)) {
            $productId = $productId - 1;
            unset($this->products_old[$productId]);
        }
        //print_r($this->products_old);
        $this->getUser()->setAttribute('products_to_cart', serialize($this->products_old));
    }

    public function executeClearcart(sfWebRequest $request) {
        $this->getUser()->setAttribute('products_to_cart', '');
        return $this->renderText("Ваша корзина пуста.<br /><br />");
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

    public function executeJelinfoheader(sfWebRequest $request) {
        $this->products_jel = unserialize($this->getUser()->getAttribute('products_to_desire'));
        if ($this->products_jel == "") {
            return $this->renderText('0');
        } else {
            return $this->renderText(count($this->products_jel));
        }
    }

    public function executeCompare(sfWebRequest $request) {
        $productId = (int) $request->getParameter('id');

        $this->products_jel = $this->getUser()->getAttribute('products_to_desire');
        $this->products_jel = $this->products_jel != '' ? unserialize($this->products_jel) : '';
        // print_r($this->products_jel);
        if ($productId > 0 and is_array($this->products_jel)) {
            $d = array_search($productId, $this->products_jel);
            unset($this->products_jel[$d]);
        }

        $this->getUser()->setAttribute('products_to_desire', serialize($this->products_jel));
    }

    public function executeAddtocart(sfWebRequest $request) {

        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

        $row = array();
        $row["productId"] = (int) $request->getParameter('id');
        $row["productOptions"] = $request->getParameter('productOptions');
        $row["quantity"] = $request->getParameter('quantity');
        $product = ProductTable::getInstance()->findOneById($row["productId"]);

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

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($products_old as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * $product["price"]);
        }

        $this->getUser()->setAttribute('products_to_cart', serialize($products_old));
        $this->getUser()->setAttribute('productsCount', $this->productsCount);
        $this->getUser()->setAttribute('totalCost', $this->totalCost);
        print_r(unserialize($this->getUser()->getAttribute('products_to_cart')));

        return $this->renderText(false);
    }

    public function executeAddtocartcount(sfWebRequest $request) {
        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

        foreach ($products_old as $key => $product) {
            if (@$product["productId"] == @$request->getParameter('id')) {
                $products_old[$key]["quantity"] = $request->getParameter('count');
            }
        }

        $this->getUser()->setAttribute('products_to_cart', serialize($products_old));
        return $this->renderText($request->getParameter('count'));
    }

    public function executeAddtodesire(sfWebRequest $request) {
        $products_jel = unserialize($this->getUser()->getAttribute('products_to_desire'));

        $row = array();
        $row["productId"] = (int) $request->getParameter('id');

        foreach ($products_jel as $key => $product) {
            if (@$product["productId"] == @$row["productId"]) {
                $row = array();
            }
        }

        if (isset($row["productId"]) && $row["productId"] > 0) {
            $products_jel[] = $row["productId"];
        }

        $this->getUser()->setAttribute('products_to_desire', serialize($products_jel));

        return $this->renderText(count($products_jel));
    }

    public function executeProcessorder(sfWebRequest $request) {
        $this->getUser()->setAttribute('deliveryId', "");
        $this->getUser()->setAttribute('RegisterSuccessRedirect', false);
        //print_r(unserialize($this->getUser()->getAttribute('products_to_cart')));
        $productId = (int) $request->getParameter('id');

        $this->products_old = $this->getUser()->getAttribute('products_to_cart');
        $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';


        if ($productId > 0 and is_array($this->products_old)) {
            $productId = $productId - 1;
            unset($this->products_old[$productId]);
        }
        //print_r($this->products_old);
        $this->getUser()->setAttribute('products_to_cart', serialize($this->products_old));
        if ($this->getUser()->isAuthenticated()) {
            $user = $this->getUser();
            $this->bonus = BonusTable::getInstance()->findBy('user_id', $user->getGuardUser()->getId());
            $this->bonusCount = 0;
            foreach ($this->bonus as $bonus) {
                $this->bonusCount = $this->bonusCount + $bonus->getBonus();
            }
        } else {
            $this->bonusCount = 0;
        }
    }

    public function is_email($email) {
        return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
    }

    public function executeConfirmed(sfWebRequest $request) {

        //$this->order=  OrdersTable::getInstance()->findOneById(105095);
        $user = $this->getUser();
        //print_r();
        if ($user->getAttribute('products_to_cart') == "" or count(unserialize($user->getAttribute('products_to_cart'))) == 0 or unserialize($user->getAttribute('products_to_cart')) == "") {
            return $this->redirect('/cart');
        }
        //$this->getUser()->setAttribute('RegisterSuccessRedirect', false);
        /* if (($user->getAttribute('deliveryId') == "" or ($request->getParameter('deliveryId') != $user->getAttribute('deliveryId')) and $request->getParameter('deliveryId') != "")) {
          $user->setAttribute('deliveryId', $request->getParameter('deliveryId'));
          $user->setAttribute('paymentId', $request->getParameter('paymentId'));
          $user->setAttribute('comments', $request->getParameter('comments'));
          $user->setAttribute('coupon', $request->getParameter('coupon'));
          $user->setAttribute('timeCall', $request->getParameter('timeCall'));
          $user->setAttribute('deliveryPrice', $request->getParameter('deliveryPrice'));
          $user->setAttribute('payBonus', $request->getParameter('payBonus'));
          $user->setAttribute('pickpoint_id', $request->getParameter('pickpoint_id'));
          $user->setAttribute('pickpoint_address', $request->getParameter('pickpoint_address'));
          } */
        if ($request->getParameter('deliveryId') != "") {
            $user->setAttribute('deliveryId', $request->getParameter('deliveryId'));
            $user->setAttribute('paymentId', $request->getParameter('paymentId'));
            $user->setAttribute('comments', $request->getParameter('comments'));
            $user->setAttribute('coupon', $request->getParameter('couponTxt'));
            $user->setAttribute('timeCall', $request->getParameter('timeCall'));
            $user->setAttribute('deliveryPrice', $request->getParameter('deliveryPrice'));
            $user->setAttribute('payBonus', $request->getParameter('payBonus'));
            $user->setAttribute('pickpoint_id', $request->getParameter('pickpoint_id'));
            $user->setAttribute('pickpoint_address', $request->getParameter('pickpoint_address'));
            $user->setAttribute('qiwiId', $request->getParameter('qiwiId'));
            $user->setAttribute('qiwiAddr', $request->getParameter('qiwiAddr'));
            //echo $request->getParameter('couponTxt');exit;
            //print_r($_POST);exit;
        }

        if (!$this->getUser()->getAttribute('RegisterSuccessRedirect')) {
            $this->getUser()->setAttribute('RegisterSuccessRedirect', true);
            $this->redirect('/register');
        }
        if ($user->getAttribute('deliveryId') != "") {
            $_POST['deliveryId'] = $user->getAttribute('deliveryId');
            $_POST['paymentId'] = $user->getAttribute('paymentId');
            $_POST['comments'] = $user->getAttribute('comments');
            $_POST['coupon'] = $user->getAttribute('coupon');

            $_POST['timeCall'] = $user->getAttribute('timeCall');
            $_POST['deliveryPrice'] = $user->getAttribute('deliveryPrice');
            $_POST['payBonus'] = $user->getAttribute('payBonus');
            $_POST['pickpoint_id'] = $user->getAttribute('pickpoint_id');
            $_POST['pickpoint_address'] = $user->getAttribute('pickpoint_address');
            $_POST['qiwiId'] = $user->getAttribute('qiwiId');
            $_POST['qiwiAddr'] = $user->getAttribute('qiwiAddr');
        }
        if ($_POST['deliveryId'] == "" or $_POST['deliveryId'] == 0) {
            $_POST['deliveryId'] = 12;
        }
        if ($_POST['paymentId'] == "" or $_POST['paymentId'] == 0) {
            $_POST['paymentId'] = 46;
        }
        if ($user->getAttribute('products_to_cart') != "") {
            $this->bonus = BonusTable::getInstance()->findBy('user_id', $user->getGuardUser()->getId());
            $this->bonusCount = 0;
            foreach ($this->bonus as $bonus) {
                $this->bonusCount = $this->bonusCount + $bonus->getBonus();
            }

            $products_old = $user->getAttribute('products_to_cart');
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            $TotalSumm = 0;
            foreach ($products_old as $key => $productInfo):
                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
            endforeach;


            $order = new Orders();
            $order->setComments($_POST['comments']);



            if (isset($_POST['payBonus']) and $_POST['payBonus'] == "1") {

                if (round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm) > $this->bonusCount) {
                    $bonusDropCost = $this->bonusCount;
                } else {
                    $bonusDropCost = round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm);
                }
                $commentBonus = " \r\n\r\n Пользователь оплатил " . $bonusDropCost . " рублей бонусами со своего счёта";
                $order->setComments($order->getComments() . $commentBonus);
                $this->bonusDropCost = $bonusDropCost;
            } else {
                $this->bonusDropCost = 0;
            }
            if (!empty($_POST["timeCall"])) {
                $order->setComments($order->getComments() . " \r\n Удобное время звонка: " . $_POST["timeCall"]);
            }

            $pickpoint_id = trim($_POST["pickpoint_id"]);
            if (!empty($pickpoint_id)) {
                $order->setComments($order->getComments() . " \r\n ID постамата: " . $pickpoint_id);
            }
            $pickpoint_address = trim($_POST["pickpoint_address"]);
            if (!empty($pickpoint_address)) {
                $order->setComments($order->getComments() . " \r\n Адрес постамата: " . $pickpoint_address);
            }

            $qiwi_id = trim($_POST["qiwiId"]);
            if (!empty($qiwi_id) and $_POST['deliveryId'] == 13) {
                $order->setComments($order->getComments() . " \r\n ID постамата QIWI: " . $qiwi_id);
            }
            $qiwi_address = trim($_POST["qiwiAddr"]);
            if (!empty($qiwi_address) and $_POST['deliveryId'] == 13) {
                $order->setComments($order->getComments() . " \r\n Адрес постамата QIWI: " . $qiwi_address);
            }


            if (!empty($_COOKIE["samyragon"])) {
                $order->setSamyragon($_COOKIE["samyragon"]);
            }
            /* if ($user->getGuardUser()->getId() == 17101) {
              print_r($order->getComments());
              exit;
              } */

            $order->setText($user->getAttribute('products_to_cart'));
            $order->setDeliveryId((int) $_POST['deliveryId']);
            $order->setPaymentId((int) $_POST['paymentId']);
            $order->setCustomerId($user->getGuardUser());
            $order->setDeliveryPrice($_POST['deliveryPrice']);
            if (!empty($_POST["coupon"])) {
                $order->setComments($order->getComments() . " \r\n Купон на скидку: " . $_POST["coupon"]);
            }

            $order->setCoupon($_POST['coupon']);
            $order->setStatus('Новый');
            $order->set('sync_status', 'new');

            $ref = 'NULL';
            if (defined('REFERALID')) {
                $ref = REFERALID;
            }

            $order->set('referurl', $_COOKIE['referalurl']);

            $order->set('referal', $ref);

            $prxCityads = 'NULL';
            if (defined('PRXCITYADS')) {
                $prxCityads = PRXCITYADS;
            }
            $order->set('prxcityads', $prxCityads);
            $order->set('ipUser', $_SERVER['REMOTE_ADDR']);
            $order->set('prefix', csSettings::get('order_prefix'));
            $order->save();


            $newBonusActive = new Bonus();
            $newBonusActive->setUserId($user->getGuardUser());
            $newBonusActive->setBonus(0);
            $newBonusActive->setComment('Продление жизни бонусов за заказ');
            $newBonusActive->save();


            $this->getUser()->setAttribute('RegisterSuccessRedirect', false);

            if (isset($_POST['payBonus']) and $_POST['payBonus'] == "1") {
                $bonusLog = new Bonus();
                $bonusLog->setUserId($user->getGuardUser()->getId());
                $bonusLog->setBonus("-" . $bonusDropCost);
                $bonusLog->setComment("Снятие бонусов в счет оплаты заказа #" . csSettings::get('order_prefix') . $order->getId());
                $bonusLog->save();
            }

            $this->order = $order;

            file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '?r=cmd&command=upload_order&err=1&api=8d956bbe43b2809baa904afb20a65831&id=' . $order->getId());
            //echo 'http://'.$_SERVER['HTTP_HOST'].'?r=cmd&command=upload_order&err=1&api=63d4b144ce7b48dbd0e7711b62aa0c05&id='.$order->getId(); exit;
            $products_old = $user->getAttribute('products_to_cart');
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            $TotalSumm = 0;
            $yaParams = '';
            $googleParams = "";
            foreach ($products_old as $key => $productInfo):
                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
                $categoryProd = $product->getGeneralCategory();
                $this->googleParams.="_gaq.push(['_addItem',
'" . $order->getId() . "',
'" . $product->getCode() . "',
'" . $product->getName() . "',
'" . $categoryProd->getName() . "',
'" . $productInfo['price'] . "',
'" . $productInfo['quantity'] . "'
]);";
                $this->yaParams.="{
	  id: \"" . $product->getId() . "\", 
          name: '" . addslashes($product->getName()) . "',
	  price: " . $productInfo['price'] . ",
          quantity: " . $productInfo['quantity'] . "
	}, ";
            endforeach;
            $this->TotalSumm = $TotalSumm;
            $Body = "Здравствуйте!<br>
		Ваш заказ ";
            $Body .= '№ ' . csSettings::get('order_prefix') . $order->getId();
            $Body .= " принят. Наш менеджер свяжется с Вами в ближайшее время.<br>
		Заказанные товары:<br>
		<table width='600' border='1' cellpadding='5' style='border-collapse:collapse;'>
		<tr>
			<td width='60' align='center'>№</td>
			<td width='140' align='center'>Наименование</td>
			<td width='60' align='center'>Кол-во</td>
			<td width='100' align='center'>Цена</td>
			<td width='140' align='center'>Сумма</td>
		</tr>
		";
            $counter = 1;
            foreach ($products_old as $key => $productInfo):
                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                $Body .= "
			<tr>
				<td>$counter</td>
				<td><a href='https://onona.ru/product/" . $product->getSlug() . "'>" . $product->getName() . "</a></td>
				<td>" . $productInfo['quantity'] . "</td>
				<td>" . $productInfo['price'] . "</td>
				<td>" . ($productInfo['price'] * $productInfo['quantity']) . "</td>
			</tr>";
                $counter++;
            endforeach;
            $Body .= "<tr>
		<td colspan='4' align='right'>Итого:</td>
		<td>" . ceil($this->TotalSumm) . " руб.</td>
		</tr>
		</table>";
            if ($order->getComments() != "")
                $Body .= "<p>Комментарий к заказу: " . $order->getComments() . "</p>";
            if ($order->getCoupon() != "")
                $Body .= "<p>Купон на скидку: " . $order->getCoupon() . "</p>";

            $Body .= "Спасибо!";


            if ($this->is_email($this->getUser()->getGuardUser()->getEmailAddress())) {
                $message = Swift_Message::newInstance()
                        ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                        ->setTo($this->getUser()->getGuardUser()->getEmailAddress())
                        ->setSubject('Ваш заказ на сайте OnOna.ru принят')
                        ->setBody($Body)
                        ->setContentType('text/html')
                ;
                $this->getMailer()->send($message);
            }
            $user->setAttribute('deliveryId', "");
            $this->getUser()->setAttribute('products_to_cart', '');
        }
    }

    public function executePayments(sfWebRequest $request) {
        $delivery = DeliveryTable::getInstance()->FindOneById($request->getParameter('deliveryId'));
        $payments = $delivery->getDeliveryPayments();
        $result = "<b>Выберите cпособ оплаты:</b><br />";
        foreach ($payments as $payment) {
            $result .= '<div style="padding:0px 0px 5px 10px;"><input type="radio" name="paymentId" onclick="checkPayment(this);" value="' . $payment->getId() . '">&nbsp;' . $payment->getName() . '<br />' . $payment->getContent() . '</div><br />';
        }
        return $this->renderText($result);
    }

}
