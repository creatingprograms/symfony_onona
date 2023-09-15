<?php

/**
 * yamarketpay actions.
 *
 * @package    test
 * @subpackage yamarketpay
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class yamarketpayActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeCart(sfWebRequest $request) {
        header('Content-Type: application/json');
        if ($_GET['auth-token'] == "2C0000018E4646CC") {

            $request = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
            $totalPrice = 0;
            //print_r($request);exit;
            foreach ($request['cart']['items'] as $item) {
//print_r($item);exit;
                $partResponse['feedId'] = $item['feedId'];
                $partResponse['offerId'] = $item['offerId'];
                $partResponse['delivery'] = true;
                // print_r($item['offer-id']);exit;
                $product = ProductTable::getInstance()->findOneById($item['offerId']);
                if ($product) {
                    $partResponse['price'] = (int) $product->getPrice();
                    $partResponse['count'] = (int) $product->getCount();
                    $totalPrice = $totalPrice + $product->getPrice();
                    $response['cart']['items'][] = $partResponse;
                }
                unset($product, $partResponse);
            }
            $response['cart']['paymentMethods'][] = "SHOP_PREPAID";
            $response['cart']['paymentMethods'][] = "CASH_ON_DELIVERY";
            $response['cart']['paymentMethods'][] = "CARD_ON_DELIVERY";
            //var_dump($request['cart']['delivery']['region']['parent']['name'] );
            if ($request['cart']['delivery']['region']['parent']['name'] == "Москва и Московская область") {
                $deliveryPart['id'] = "1";
                $deliveryPart['type'] = DELIVERY;
                $deliveryPart['serviceName'] = " Курьер (Москва и Московская область)";
                if ($totalPrice > 2990) {
                    $deliveryPart['price'] = 0;
                } else {
                    $deliveryPart['price'] = 200;
                }
                $deliveryPart['paymentMethods'][] = "CASH_ON_DELIVERY";
                $deliveryPart['dates']['fromDate'] = date("d-m-Y", time());
                $deliveryPart['dates']['toDate'] = date("d-m-Y", time() + 259200);
                $response['cart']['deliveryOptions'][] = $deliveryPart;
                unset($deliveryPart);

                $deliveryPart['id'] = "10";
                $deliveryPart['type'] = PICKUP;
                $deliveryPart['serviceName'] = " Самовывоз из магазинов Он и Она в г. Москва";
                $deliveryPart['price'] = 0;
                $deliveryPart['dates']['fromDate'] = date("d-m-Y", time());
                $deliveryPart['dates']['toDate'] = date("d-m-Y", time() + 259200);
                $deliveryPart['outlets'][]['id'] = 284925;
                $deliveryPart['outlets'][]['id'] = 284929;
                $deliveryPart['outlets'][]['id'] = 284934;
                $deliveryPart['outlets'][]['id'] = 284935;
                $deliveryPart['outlets'][]['id'] = 284936;
                $deliveryPart['outlets'][]['id'] = 284924;
                $deliveryPart['outlets'][]['id'] = 284932;
                $deliveryPart['outlets'][]['id'] = 262442;
                $deliveryPart['outlets'][]['id'] = 259289;
                $deliveryPart['outlets'][]['id'] = 259301;
                $deliveryPart['outlets'][]['id'] = 259294;
                $response['cart']['deliveryOptions'][] = $deliveryPart;
                unset($deliveryPart);
            } else {
                $deliveryPart['id'] = "9";
                $deliveryPart['type'] = DELIVERY;
                $deliveryPart['serviceName'] = "  Курьерская доставка по России";
                if ($totalPrice > 2990) {
                    $deliveryPart['price'] = 0;
                } else {
                    $deliveryPart['price'] = 300;
                }
                $deliveryPart['paymentMethods'][] = "CASH_ON_DELIVERY";
                $deliveryPart['dates']['fromDate'] = date("d-m-Y", time());
                $deliveryPart['dates']['toDate'] = date("d-m-Y", time() + 7776000);
                $response['cart']['deliveryOptions'][] = $deliveryPart;
                unset($deliveryPart);
            }

            $deliveryPart['id'] = "3";
            $deliveryPart['type'] = POST;
            $deliveryPart['serviceName'] = " Почта России";

            if ($totalPrice > 2990) {
                $deliveryPart['price'] = 0;
            } else {
                $deliveryPart['price'] = 220 + $totalPrice * 0.05;
            }
            $deliveryPart['dates']['fromDate'] = date("d-m-Y", time());
            $deliveryPart['dates']['toDate'] = date("d-m-Y", time() + 7776000);
            $deliveryPart['paymentMethods'][] = "CASH_ON_DELIVERY";
            $response['cart']['deliveryOptions'][] = $deliveryPart;
            unset($deliveryPart);
        }
        echo json_encode($response);
        exit;
    }

    public function executeOrderaccept(sfWebRequest $request) {

        header('Content-Type: application/json');
        if ($_GET['auth-token'] == "2C0000018E4646CC") {
            $request = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
            $request = $request['order'];
            $TotalSumm = 0;
            foreach ($request['items'] as $item) {
                $product = ProductTable::getInstance()->findOneById($item['offerId']);
                if ($product) {
                    $row = array();
                    $row["productId"] = (int) $item['offerId'];
                    $row["quantity"] = $item['count'];

                    $row["price"] = $product->getPrice();
                    if ($product->getOldPrice() > 0) {
                        $row["price"] = $product->getOldPrice();
                        $row["price_w_discount"] = $product->getPrice();
                        $row["discount"] = round((1 - ($product->getPrice() / $product->getOldPrice())) * 100);
                    } else {
                        $row["price"] = $product->getPrice();
                        $row["price_w_discount"] = $product->getPrice();
                    }
                    $TotalSumm = $TotalSumm + ($row["price_w_discount"] * $item['count']);
                    $products_old[] = $row;
                }
                unset($product);
            }


            $user = sfGuardUserTable::getInstance()->findOneById('138816');

            $order = new Orders();
            $order->setComments($request['notes']);
            $order->setYaid($request['id']);
            $order->setText(serialize($products_old));
            $order->setFirsttext(serialize($products_old));
            $order->setDeliveryId((int) $request['delivery']['id']);
            $order->setPaymentId(46);
            $order->setCustomerId($user);
            $order->setDeliveryPrice($request['delivery']['price']);

            $order->setStatus('Новый');
            $order->setFirsttotalcost($TotalSumm);
            $order->set('sync_status', 'new');
            $order->set('ipUser', $_SERVER['REMOTE_ADDR']);
            $order->set('prefix', csSettings::get('order_prefix'));
            $order->save();

            $response['order']['accepted'] = TRUE;
            $response['order']['id'] = $order->getId();

            //print_r($request);
        }
        echo json_encode($response);
        exit;
    }

    public function executeOrderstatus(sfWebRequest $request) {


        header('Content-Type: application/json');
        if ($_GET['auth-token'] == "2C0000018E4646CC") {
            $request = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
            $request = $request['order'];

            $order = OrdersTable::getInstance()->findOneByYaid($request['id']);
            if ($request['status'] == "CANCELLED") {


                $order->set("sync_status", "complete");
                $order->set("status", "Отмена");
                if ($request['substatus'] == "RESERVATION_EXPIRED") {
                    $order->set("status_detail", $order->get("status_detail") . " покупатель не завершил вовремя оформление зарезервированного заказа");
                } else {
                    $order->set("status_detail", $order->get("status_detail") . $request['substatus']);
                }
                $order->save();
            } else {
                $user = sfGuardUserTable::getInstance()->findOneByEmailAddress(trim($request['buyer']['email']));
                if (!$user) {
                    $user = new sfGuardUser();
                    $username = uniqid("user_");
                    $user->setUsername($username);
                    $password = uniqid();
                    $user->set("password", $password);
                }
                $user->setFirstName(trim($request['buyer']['firstName']));
                $user->setLastName(trim($request['buyer']['lastName']));
                $user->setEmailAddress(trim($request['buyer']['email']));
                $user->setPhone($request['buyer']['phone']);
                $user->save();

                $order->setCustomerId($user);
                $order->setComments($request['notes']);
                $order->save();

                //echo json_encode($response);
                exec('php -r "echo file_get_contents(\'https://onona.ru?r=cmd&command=upload_order&err=1&api=8d956bbe43b2809baa904afb20a65831&id=' . $order->getId() . '\');" > /dev/null &');
            }
        }

        exit;
    }

}
