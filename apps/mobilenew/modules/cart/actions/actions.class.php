<?php

/**
 * cart actions.
 *
 * @package    test
 * @subpackage cart
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cartActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $this->productsToCart = $this->getUser()->getAttribute('products_to_cart');
        $this->productsToCart = $this->productsToCart != '' ? unserialize($this->productsToCart) : '';
        $productNumber = (int) $request->getParameter('id');
        if ($productNumber > 0 and is_array($this->productsToCart)) {
            $productNumber = $productNumber - 1;
            unset($this->productsToCart[$productNumber]);
        }
        if (is_array($this->productsToCart) and count($this->productsToCart) > 0) {

            $arrayProductsId = array();
            foreach ($this->productsToCart as $product) {
                $arrayProductsId[$product['productId']] = $product['productId'];
            }

            $timerProducts = sfTimerManager::getTimer('Action: Загрузка всех товаров');
            $this->productsAll = $q->execute("SELECT id, name, slug, rating, votes_count, price, old_price, bonuspay, bonus, discount, created_at, video, videoenabled, endaction, step, count "
                            . "FROM product "
                            . "WHERE id in (" . implode(",", $arrayProductsId) . ") ")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerProducts->addTime();

            $timerPhotos = sfTimerManager::getTimer('Action: Загрузка фото для всех товаров');
            $this->photosAll = $q->execute("SELECT ppa.product_id,photo.filename as filename from photo "
                            . "left join product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                            . "WHERE ppa.product_id in (" . implode(",", $arrayProductsId) . ") "
                            . "ORDER BY photo.position DESC")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPhotos->addTime();

            $timerBonus = sfTimerManager::getTimer('Action: Определение количества бонусов на счете');
            if ($this->getUser()->isAuthenticated()) {
                $this->bonus = $q->execute("SELECT sum(bonus) as sum "
                                . "FROM bonus "
                                . "WHERE user_id='" . $this->getUser()->getGuardUser()->getId() . "' ")
                        ->fetch(Doctrine_Core::FETCH_ASSOC);

                $this->bonusCount = $this->bonus['sum'];
            } else {
                $this->bonusCount = 0;
            }

            $timerBonus->addTime();

            $timerBonuspay = sfTimerManager::getTimer('Action: Определение параметров управляй ценой');
            $bonusCountTemp = $this->bonusCount;
            foreach ($this->productsToCart as $keyProdCart => $product) {
                if ($product['price'] == $product['price_w_discount'] and $bonusCountTemp > 0) {
                    if ($this->productsAll[$product['productId']]['bonuspay'] != '') {
                        $percentBonuspay = $this->productsAll[$product['productId']]['bonuspay'];
                    } else {
                        $percentBonuspay = csSettings::get('PERSENT_BONUS_PAY');
                    }
                    $procBonus = $bonusCountTemp / ($product['quantity'] * $product['price']) * 100;
                    $percentBonusPayForCount = floor($procBonus / 5) * 5;
                    if ($percentBonusPayForCount < $percentBonuspay) {
                        $percentBonuspay = $percentBonusPayForCount;
                    }
                    $bonusCountTemp = $bonusCountTemp - round($product['price'] * ($percentBonuspay / 100));
                    $this->productsToCart[$keyProdCart]['bonuspay'] = round($product['quantity'] * $product['price'] * ($percentBonuspay / 100));
                    $this->productsToCart[$keyProdCart]['percentbonuspay'] = $percentBonuspay;
                    $this->productsToCart[$keyProdCart]['priceonus5persent'] = $product['price'] * 0.05;
                } else {
                    $this->productsToCart[$keyProdCart]['bonuspay'] = 0;
                    $this->productsToCart[$keyProdCart]['percentbonuspay'] = 0;
                    $this->productsToCart[$keyProdCart]['priceonus5persent'] = $product['price'] * 0.05;
                }
            }
            $timerBonuspay->addTime();
        } else {

            $timerPage = sfTimerManager::getTimer('Action: Загрузка страницы отсутствия товаров в корзине');
            $this->page = $q->execute("SELECT * from page WHERE slug='stranica-pustoi-korziny'")->fetch(Doctrine_Core::FETCH_ASSOC);
            $this->forward404Unless($this->page);
            $timerPage->addTime();
        }

        $this->getUser()->setAttribute('products_to_cart', serialize($this->productsToCart));
        if ($this->getUser()->isAuthenticated()) {
            $GuardUser = $this->getUser()->getGuardUser();
            $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
            $GuardUser->save();
        }
    }

    public function executeConfirmed(sfWebRequest $request) {
        $user = $this->getUser();

        if ($user->getAttribute('products_to_cart') == "" or count(unserialize($user->getAttribute('products_to_cart'))) == 0 or unserialize($user->getAttribute('products_to_cart')) == "") {
            return $this->redirect('/cart');
        }

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $autorizationUserNow = false;
        if ($this->getUser()->isAuthenticated()) {
            $user = $this->getUser()->getGuardUser();
        } else {
            $user = sfGuardUserTable::getInstance()->findOneByEmailAddress($request->getParameter('user_mail', "notEmail"));
            if (!$user) {
                $user = new sfGuardUser();
                $username = uniqid("user_");
                //'user_' . rand(0, 9999999999999);
                $user->setUsername($username);
                $password = rand(100000, 999999);
                $user->set("password", $password);
                $autorizationUserNow = true;
            }
        }

        if ($request->getParameter('user_mail') != "") {
            $user->setFirstName($request->getParameter('user_name'));
            $user->setEmailAddress($request->getParameter('user_mail'));
            $user->setPhone($request->getParameter('user_phone'));
        } elseif ($this->getUser()->getAttribute('products_to_cart') != "") {
            return $this->redirect('/cart');
        } else {
            return $this->redirect('/sexshop');
        }

        $user->save();
        $userPhone = $user->getPhone();

        if ($autorizationUserNow)
            $this->getUser()->signin($user);
        $userId = $user->getId();
        $userEmailAddress = $user->getEmailAddress();
        $userSave = $user;
        $user = $this->getUser();

        if ($user->getAttribute('products_to_cart') == "" or count(unserialize($user->getAttribute('products_to_cart'))) == 0 or unserialize($user->getAttribute('products_to_cart')) == "") {
            return $this->redirect('/cart');
        }

        if ($_POST['deliveryId'] == "" or $_POST['deliveryId'] == 0) {
            $_POST['deliveryId'] = 12;
        }
        if ($_POST['paymentId'] == "" or $_POST['paymentId'] == 0) {
            $_POST['paymentId'] = 46;
        }
        if ($user->getAttribute('products_to_cart') != "") {
            if ($this->getUser()->isAuthenticated()) {
                $this->bonus = $q->execute("SELECT sum(bonus) as sum "
                                . "FROM bonus "
                                . "WHERE user_id='" . $this->getUser()->getGuardUser()->getId() . "' ")
                        ->fetch(Doctrine_Core::FETCH_ASSOC);

                $this->bonusCount = $this->bonus['sum'];
            } else {
                $this->bonusCount = 0;
            }

            $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
            $TotalSumm = 0;
            $bonusPay = 0;
            $bonusPercent = $request->getParameter('bonusPercet1Form');
            $isbonuspayper = false;
            $notadddelivery = true;
            foreach ($products_old as $key => $productInfo):
                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                if (!$product->get("is_notadddelivery")) {
                    $notadddelivery = false;
                }


                $product->setCount($product->getCount() - $productInfo['quantity']);
                $product->save();



                $cacheManager = sfContext::getInstance()->getViewCacheManager();
                if ($cacheManager) {
                    $cacheManager->remove('@sf_cache_partial?module=category&action=_products&sf_cache_key=' . ($product->getId()) . '*');
                    $cacheManager->remove('@sf_cache_partial?module=category&action=_changechildren&sf_cache_key=' . ($product->getId()) . '*');
                    $cacheManager->remove('@sf_cache_partial?module=product&action=_params&sf_cache_key=' . ($product->getId()) . '*');
                    $cacheManager->remove('@sf_cache_partial?module=product&action=_stock&sf_cache_key=' . ($product->getId()) . '*');
                }


                if ($product->getBonuspay() != '') {
                    $percentBonuspay = $product->getBonuspay();
                } else {
                    $percentBonuspay = csSettings::get('PERSENT_BONUS_PAY');
                }
                if ($percentBonuspay > $bonusPercent[$key]) {
                    $percentBonuspay = $bonusPercent[$key];
                }

                if ($percentBonuspay > 0) {
                    $bonusPay = $bonusPay + round(($percentBonuspay / 100) * $productInfo['price'] * $productInfo['quantity']);

                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price'] ) - round(($percentBonuspay / 100) * $productInfo['price'] * $productInfo['quantity']);

                    $products_old[$key]['price_w_discount'] = $productInfo['price'];
                    $products_old[$key]['bonus_percent'] = $percentBonuspay;
                } else {
                    $products_old[$key]['bonus_percent'] = 0;
                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) );
                }


                if (@$products_old[$key]['bonus_percent'] > 0 and $products_old[$key]['bonus_percent'] > 49)
                    $isbonuspayper = true;
            endforeach;

            $order = new Orders();
            $order->setComments($_POST['comments']);
            $order->setCustombonusper($isbonuspayper);



            if (isset($_POST['payBonus']) and $_POST['payBonus'] == "1" or true) {

                if ($bonusPay > $this->bonusCount) {
                    $bonusDropCost = $this->bonusCount;
                } else {
                    $bonusDropCost = $bonusPay;
                }
                $commentBonus = " \r\n\r\n Пользователь оплатил " . $bonusDropCost . " рублей бонусами со своего счёта";
                $order->setComments($order->getComments() . $commentBonus);
                $order->setBonuspay($bonusDropCost);

                $this->bonusDropCost = $bonusDropCost;
            } else {
                $this->bonusDropCost = 0;
                $order->setBonuspay(0);
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



            if (($_POST['deliveryPriceSend'] > 0) and ! $notadddelivery) {


                $row = array();
                $row["productId"] = 14613;
                $row["quantity"] = 1;

                $row["price"] = $_POST['deliveryPriceSend'];
                $row["price_w_discount"] = $_POST['deliveryPriceSend'];

                $products_old[] = $row;
            }

            $user->setAttribute('products_to_cart', serialize($products_old));
            $order->setText($user->getAttribute('products_to_cart'));
            $order->setFirsttext($user->getAttribute('products_to_cart'));
            $order->setDeliveryId((int) $_POST['deliveryId']);
            $order->setPaymentId((int) $_POST['paymentId']);
            $order->setCustomerId($userId);
            $order->setDeliveryPrice($_POST['deliveryPriceSend']);

            $order->setCoupon($_POST['coupon']);
            $order->setStatus('Новый');
            $order->setFirsttotalcost($TotalSumm);
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

            if ($this->getUser()->isAuthenticated()) {
                $newBonusActive = new Bonus();
                $newBonusActive->setUserId($userId);
                $newBonusActive->setBonus(0);
                $newBonusActive->setComment('Продление жизни бонусов за заказ');
                $newBonusActive->save();
            }

            $this->getUser()->setAttribute('RegisterSuccessRedirect', false);

            if (isset($_POST['payBonus']) and $_POST['payBonus'] == "1" or $bonusDropCost > 0) {
                $bonusLog = new Bonus();
                $bonusLog->setUserId($userId);
                $bonusLog->setBonus("-" . $bonusDropCost);
                $bonusLog->setComment("Снятие бонусов в счет оплаты заказа #" . csSettings::get('order_prefix') . $order->getId());
                $bonusLog->save();
            }

            $this->order = $order;

            exec('php -r "echo file_get_contents(\'https://onona.ru?r=cmd&command=upload_order&err=1&api=8d956bbe43b2809baa904afb20a65831&id=' . $order->getId() . '\');" > /dev/null &');


            $products_old = $user->getAttribute('products_to_cart');
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            $TotalSumm = 0;
            $yaParams = '';
            $googleParams = "";
            $bonusAddUser = 0;
            $tableOrder = "";
            foreach ($products_old as $key => $productInfo):
                if ($productInfo['productId'] > 0):
                    $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    $photoalbum = $product->getPhotoalbums();
                    $photos = $photoalbum[0]->getPhotos();
                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) );
                    $categoryProd = $product->getGeneralCategory();
                    if ($product->getBonus() != 0) {
                        $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $productInfo['quantity'] * $product->getBonus()) / 100);
                    } else {
                        $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $productInfo['quantity'] * csSettings::get("persent_bonus_add")) / 100);
                    }
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

                    $tableOrder .= '<tbody>
                    <tr>
                        <td style="text-align: left;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    text-align: left;
    vertical-align: top;">                                
    ';
                    if ($product->getId() != 14613) {
                        $tableOrder .= ' <div style="float:left;margin: -10px 10px -10px 0">  <a href="https://onona.ru/product/' . $product->getSlug() . '"><img width="70" src="https://onona.ru/uploads/photo/thumbnails_250x250/' . $photos[0]->getFilename() . '"></a></div>

                            <a href="https://onona.ru/product/' . $product->getSlug() . '">' . $product->getName() . '</a>
                              
                        ';
                    } else {

                        $tableOrder .= '   ' . $product->getName() . '
                        ';
                    }

                    if ($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) {
                        $priceOneProd = $productInfo['price_w_discount'];
                    } elseif ($productInfo['bonus_percent'] > 0) {
                        $priceOneProd = $productInfo['price'] - round(($productInfo['bonus_percent'] / 100) * $productInfo['price']);
                    } else {
                        $priceOneProd = $productInfo['price'];
                    }
                    $priceAllProd = $priceOneProd * $productInfo['quantity'];
                    $tableOrder .= '     </td>              <td style="text-align: center;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    vertical-align: top;">' . $productInfo['price'] . '</td>
                        <td style="text-align: center;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    vertical-align: top;">' . (($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0') . '</td>
                        <td class="count" style="text-align: center;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    vertical-align: top;">

                            <div class="cartCount" style="" id="quantity_765">' . $products_old[$key]['bonus_percent'] . '</div>

                        </td>  <td class="count" style="text-align: center;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    vertical-align: top;">

                            <div class="cartCount" style="" id="quantity_765">' . $productInfo['quantity'] . '</div>

                        </td>
                        <td style="text-align: center;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    vertical-align: top;"><div id="totalcost_765" style="display: inline-block;">' . $priceAllProd . '</div></td>
                    </tr>
                </tbody>';
                endif;
            endforeach;
            $this->TotalSumm = $TotalSumm;



            $sPhone = ereg_replace("[^0-9]", '', $userPhone);
            if ($sPhone[0] == 8)
                $sPhone[0] = 7;
            if ($sPhone[0] == 7 and $sPhone != "77777777777") {
                $sms_text = "Вы сделали заказ " . $order->getPrefix() . $order->getId() . " на сумму " . (($TotalSumm) - $bonusDropCost) . "р. Ожидайте звонок для уточнения способа и адреса доставки. Благодарим за покупку onona.ru";


// Параметры сообщения
// Если скрипт в кодировке UTF-8, не используйте iconv
                $sms_from = "OnOna";
                $sms_to = $sPhone;
                $u = 'http://www.websms.ru/http_in6.asp';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'Http_username=' . urlencode("onona") . '&Http_password=' . urlencode("77onona") . '&Phone_list=' . $sms_to . '&Message=' . urlencode($sms_text) . '&fromPhone=' . urlencode("OnOna"));
                curl_setopt($ch, CURLOPT_URL, $u);
                $u = trim(curl_exec($ch));
                curl_close($ch);
                preg_match("/message_id\s*=\s*[0-9]+/i", $u, $arr_id);
                $id = preg_replace("/message_id\s*=\s*/i", "", @strval($arr_id[0]));
                preg_match("/message_cost\s*=\s*[0-9,]+/i", $u, $arr_cost);
                $message_cost = preg_replace("/message_cost\s*=\s*/i", "", @strval($arr_cost[0]));
                $order->setSmsId($id);
                $order->setSmsPrice($message_cost);
                $order->save();
                //$sms_text = $sms_text;

                /*
                  // // Создаём POST-запрос
                  // Ваш ключ доступа к API (из Личного Кабинета)
                  $api_key = "56iequfweownf4dzqq9opy9wtksyk98sticbpqqo";
                  $POST = array(
                  'api_key' => $api_key,
                  'phone' => $sms_to,
                  'sender' => $sms_from,
                  'text' => $sms_text
                  );

                  // Устанавливаем соединение
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($ch, CURLOPT_POST, 1);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $POST);
                  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                  curl_setopt($ch, CURLOPT_URL, 'http://api.unisender.com/ru/api/sendSms?format=json');
                  $result = curl_exec($ch);

                  if ($result) {
                  // Раскодируем ответ API-сервера
                  $jsonObj = json_decode($result);

                  if (null === $jsonObj) {
                  // Ошибка в полученном ответе
                  //echo "Invalid JSON";
                  } elseif (!empty($jsonObj->error)) {
                  // Ошибка отправки сообщения
                  //echo "An error occured: " . $jsonObj->error . "(code: " . $jsonObj->code . ")";
                  } else {
                  $order->setSmsId($jsonObj->result->sms_id);
                  $order->setSmsPrice($jsonObj->result->price);
                  $order->setSmsCurrency($jsonObj->result->currency);
                  $order->save();
                  // Сообщение успешно отправлено
                  //echo "SMS message is sent. Message id " . $jsonObj->result->sms_id;
                  //echo "SMS cost is " . $jsonObj->result->price . " " . $jsonObj->result->currency;
                  }
                  } else {
                  // Ошибка соединения с API-сервером
                  //echo "API access error";
                  } */
            }





            if (filter_var($userEmailAddress, FILTER_VALIDATE_EMAIL)) {
                $this->bonus = BonusTable::getInstance()->findBy('user_id', $userId);
                $this->bonusCount = 0;
                foreach ($this->bonus as $bonus) {
                    $this->bonusCount = $this->bonusCount + $bonus->getBonus();
                }

                $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('order_create');
                $mailTemplate->setText(str_replace('{dateOrder}', date('d.m.Y'), $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{idOrder}', $order->getPrefix() . $order->getId(), $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{nameCustomer}', $userSave->getFirstName(), $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{bonysCustomer}', $this->bonusCount, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{summOrder}', $TotalSumm - $_POST['deliveryPriceSend'] - ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{deliveryPriceOrder}', $_POST['deliveryPriceSend'], $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{bonusPayOrder}', ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm) - $bonusDropCost, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));

                $tableOrderHeader = '<table width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000" border="0" align="center" class="cartContent" style="margin-top: 10px;border-collapse: collapse;">
                <thead style="background-color: #F1F1F1;"><tr>
                        <th style="border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Наименование</th>
                        <th style=" width: 111px;border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Цена, руб.</th>
                        <th style=" width: 88px;border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Скидка, %</th>
                        <th style=" width: 88px;border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Бонусы, %</th>
                        <th style=" width: 110px;border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Кол-во</th>
                        <th style=" width: 108px;border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Сумма, руб.</th>
                    </tr>
                </thead>';
                $tableOrderFooter = "
		</table>";
                $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText()));

                $message = Swift_Message::newInstance()
                        ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                        ->setTo($userEmailAddress)
                        ->setSubject($mailTemplate->getSubject())
                        ->setBody($mailTemplate->getText())
                        ->setContentType('text/html')
                ;
                $this->getMailer()->send($message); //exit;
            }
            $user->setAttribute('deliveryId', "");
            $this->getUser()->setAttribute('products_to_cart', '');
            if ($this->getUser()->isAuthenticated()) {
                $GuardUser = $this->getUser()->getGuardUser();
                $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
                $GuardUser->save();
            }
        }
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

}
