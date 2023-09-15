<?php

ini_set('display_errors', 1);
ini_set('error_reporting', 2039);
error_reporting(E_ALL);

/* ini_set('display_errors', 0);
  error_reporting(0);
  ini_set('memory_limit', '128M'); */
require_once __DIR__ . '/cfg/config.php';
require_once __DIR__ . '/CurlClient.php';
require_once __DIR__ . '/lib/ActiveRecord/ActiveRecord.php';
require_once __DIR__ . '/Cart.php';
ActiveRecord\Config::initialize(
        function($cfg) {
    global $config;
    $cfg->set_model_directory(__DIR__ . '/models/');
    $cfg->set_connections(array('erocollection' => $config['shop_db']));
    $cfg->set_default_connection('erocollection');
});

class SyncOrder {

    protected $curl;

    function __construct($curl) {
        $this->curl = $curl;
    }

    private function get_new_orders($oid = Null) {
        $Orders = array();
        $oid = $oid ? " AND o.id =" . $oid : "";
        /* Get new orders from orders table */



        foreach (Orders::find_by_sql("select
							o.referal,
							o.id,
							o.text as cart,
							o.created_at as orderDate,
							o.comments,
							o.coupon,
							o.ipuser,
							o.bonuspay,
							c.last_name as lastName,
							c.first_name as firstName,
							c.city,
							c.street,
							c.house,
							c.korpus as part,
							c.apartament as flat,
							c.phone,
							c.email_address as email,
							c.index_house as post_index,
							d.name,
							d.content as description,
							p.name as p_name,
							p.content as p_description,
							o.status as os_name,
							c.username as customer_login,
							o.referurl as referurl
						from
							orders o
							LEFT JOIN sf_guard_user c ON o.customer_id = c.id
							LEFT JOIN delivery d ON o.delivery_id = d.id
							LEFT JOIN payment p ON o.payment_id = p.id
						WHERE
							o.sync_status = 'new'
                                                        and o.customer_id!='138816'
							" . $oid . ";") as $Order_i) {

            $Order = $Order_i->to_array();
            $OrderObj = unserialize($Order['cart']);
            $Orders[$Order['id']]['referal'] = $Order['referal'];
            $Orders[$Order['id']]['full_name'] = array('lastName' => $Order['lastname'], 'firstName' => $Order['firstname'], 'middleName' => "");
            $Orders[$Order['id']]['address'] = array(
                'city' => $Order['city']
                , 'street' => $Order['street']
                , 'house' => $Order['house']
                , 'part' => $Order['part']
                , 'flat' => $Order['flat']
                , 'phone' => $Order['phone']
                , 'email' => $Order['email']
                , 'postIndex' => $Order['post_index']
                , 'additional_contacts' => ""
            );
            $Orders[$Order['id']]['delivery'] = array('name' => $Order['name'], 'description' => $Order['description']);
            $Orders[$Order['id']]['payment'] = array('name' => $Order['p_name'], 'description' => $Order['p_description']);
            $Orders[$Order['id']]['referurl'] = $Order['referurl'];
            $Orders[$Order['id']]['status'] = $Order['os_name'];
            $Orders[$Order['id']]['comment'] = $Order['comments'];
            $Orders[$Order['id']]['ipUser'] = $Order['ipuser'];
            $Orders[$Order['id']]['bonuspay'] = $Order['bonuspay'];
            $Orders[$Order['id']]['comment'] = $Orders[$Order['id']]['comment'] . " Купон на скидку:" . $Order['coupon'];
            $Orders[$Order['id']]['orderDate'] = $Order['orderdate'];
            $Orders[$Order['id']]['customer_login'] = $Order['customer_login'];

            $Cart = new Cart();
            $Cart->set_products($OrderObj);
            $totalPrice = 0;
            if (is_array($OrderObj) and count($OrderObj) > 0)
                foreach ($OrderObj as $productInfo) {
                    $totalPrice = $totalPrice + (($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) * $productInfo['quantity']);
                }
            $Cart->update_totalCost($totalPrice);
            $OrderObj = $Cart;
            $CartOrders = $OrderObj->get_products_by_id();

            $Orders[$Order['id']]['totalPrice'] = $OrderObj->get_totalCost();
      //exit;
            if (is_array($CartOrders) and count($CartOrders) > 0)
                foreach (Products::find_all_by_id(array_keys($CartOrders)) as $art) {
                    $art = $art->to_array();
                    $productCategory = ProductCategory::find(array('conditions' => array('`product_id` = ? ', $art['id'])));
                    /* print_r($productCategory);
                      exit; */
                    $Orders[$Order['id']]['products'][$art['id']] = array(
                        'product_id' => $art['id'],
                        'article' => $art['code'],
                        'title' => $art['name'],
                        'price' => $CartOrders[$art['id']]['price'],
                        'price_w_discount' => $CartOrders[$art['id']]['price_w_discount'],
                        'discount' => $CartOrders[$art['id']]['discount'],
                        'count' => $CartOrders[$art['id']]['quantity'],
                        'section' => $productCategory->category_id,
                        'id1c' => isset($art['id1c']) ? $art['id1c'] : $art['id1c']
                    );
                }
            $O = Orders::find(array('conditions' => array('`id` = ? ', $Order['id'])));
            if (!empty($O)) {
                $O->internetid = HSHOPID . $O->id;
                $O->save();
            }
        }

      //@file_put_contents('/var/www/ononaru/data/logs/test.log', __FILE__.':'.__LINE__ . ' ' . date('c') . ' ' . print_r($Orders, 1)."\n", FILE_APPEND | FILE_TEXT);
        return $Orders;
    }

    private function update_orders($orders) {

        $ids = array();
        foreach ($orders as $order_id => $order_data) {
            //print_r($order_data);exit;
            $order = Orders::find_by_id($order_id);


            if (isset($order)):
                $customer = Customers::find_by_id($order->customer_id);
                #UPDATE Customer info
                $customer->first_name = $order_data['full_name']['firstName'];
                //$customer->middlename = $order_data['full_name']['middleName'];
                $customer->last_name = $order_data['full_name']['lastName'];
                #UPDATE address
                //$address = Addresses::find_by_customerid($order->customerid);
                $customer->index_house = $order_data['address']['postIndex'];
                $customer->city = $order_data['address']['city'];
                $customer->street = $order_data['address']['street'];
                $customer->house = $order_data['address']['house'];
                $customer->korpus = $order_data['address']['part'];
                $phone = preg_replace('/[^\d]/', '', $order_data['address']['phone']);
                if (strlen($phone) == 11 and ( $phone[0] == 7 or $phone[0] == 8)) {

                    $phone = "+7(" . $phone[1] . $phone[2] . $phone[3] . ")" . $phone[4] . $phone[5] . $phone[6] . "-" . $phone[7] . $phone[8] . $phone[9] . $phone[10];
                } elseif (strlen($phone) == 10 and $phone[0] != 8 and $phone[0] != 7) {
                    $phone = "+7(" . $phone[0] . $phone[1] . $phone[2] . ")" . $phone[3] . $phone[4] . $phone[5] . "-" . $phone[6] . $phone[7] . $phone[8] . $phone[9];
                } else {
                    $phone = $phone;
                }
                $customer->phone = $phone;
                /* if ($order_data['address']['email'] != "")
                  $customer->email_address = $order_data['address']['email']; */
                $customer->apartament = $order_data['address']['flat'];
                //$customer->additional = $order_data['address']['additional_contacts'];
                #UPDATE delivery
                /*
                  if( !$d = DeliveryModules::find_by_name( $order_data['delivery']['name'] )){
                  $d = new DeliveryModules();
                  $d->name = $order_data['delivery']['name'];
                  $d->description = $order_data['delivery']['description'];
                  $d->save();
                  }
                  $order->deliveryid = $d->id;
                 */
                #
                #UPDATE payment

                if (!$p = PaymentModules::find_by_name($order_data['payment']['name'])) {
                    $p = new PaymentModules();
                    $p->name = $order_data['payment']['name'];
                    $p->content = $order_data['payment']['description'];
                    $p->save();
                }
                $order->payment_id = $p->id;

                #
                #UPDATE status
                /* if (!$s = OrderStatuses::find_by_name($order_data['status'])) {
                  $s = new OrderStatuses();
                  $s->name = $order_data['status'];
                  $s->save();
                  }
                  $newStatusId = $s->id;
                 *
                 * */ $newStatusId = $order_data['status'];
                include __DIR__ . '/bonus.php';
                include __DIR__ . '/finstatus.php';
                $order->status = $order_data['status'];
                if ($order->yaid != "") {
                    if ($order->status != "Новый" and $order->status != "Отмена" and $order->status != "В обработке" and $order->status != "Заказано поставщику" and $order->status != "В резерве" and $order->status != "Оплачен") {
                        $data = array("order" => array('status' => 'DELIVERY'));
                    } elseif ($order->status == "Отмена") {
                        $data = array("order" => array('status' => 'CANCELLED', "substatus" => "USER_CHANGED_MIND"));
                    } elseif ($order->status == "Оплачен") {
                        $data = array("order" => array('status' => 'DELIVERED'));
                    } elseif ($order->status == "В резерве") {
                        $data = array("order" => array('status' => 'RESERVED'));
                    } else {
                        $data = array("order" => array('status' => 'PROCESSING'));
                    }
                    if ($order->status != "Новый") {
                        $data_json = json_encode($data);

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://api.partner.market.yandex.ru/v2/campaigns/21016997/orders/" . ($order->yaid) . "/status.json");
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: OAuth oauth_token="AQAAAAACJc4mAAPxF4ktOKzR30e6q5LahhjMOi8", oauth_client_id="1a7defe1cd0246a2a6cc8acfbd5b9bcd"'));
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $responsePUT = curl_exec($ch);
                        curl_close($ch);

                        $data['status'] = $order->status;
                        $order->yalaststatussend = serialize($data);
                        $order->save();
                    }
                }

                #
                #UPDATE comment
                $order->comments = $order_data['comments'];
                $order->comment_1c = $order_data['comment_1c'];
                $order->manager = $order_data['manager'];
                $order->status_detail = $order_data['status_detail'];
                #
                #UPDATE Cart
                /* $cart = unserialize($order->cart);
                  $cart->replace_products_from_array($order_data['products']);
                  $cost = 0;
                  //foreach( $order_data['products'] as $info ){ $cost += ($info['price'] * $info['count'] ); }
                  $cart->update_totalCost($order_data['total_cost']);
                 */
                foreach ($order_data['products'] as $key => $info) {
                    //$cost += ($info['price'] * $info['count'] );
                    $order_data['products'][$key]['price'] = $info['price'];
                    $order_data['products'][$key]['quantity'] = $info['count'];
                    $order_data['products'][$key]['productId'] = $info['product_id'];
                }
                $order->text = serialize($order_data['products']);
                #Save All changes
                $customer->save();
                //$address->save();
                $order->save();
            endif;
            $ids[] = $order_id;
        }
        return $ids;
    }

    public function test_bonus($orders) {
        $ids = ($this->curl->exec('shop/api/Orders/get/precomplete')->json_result());
        //print_r($ids);
        //print_R($ids); exit;
        $this->update_orders($ids);
        //$this->update_orders($ids);
        // $this->upload_order(5);
        /* $ids =($this->curl->exec('shop/api/Orders/get/complete')->json_result());
          foreach($ids as $key => $id){
          if($key==100157){
          print_r($id);
          }
          }
          $this->update_orders($newIds); */
        /* $ids = $this->update_orders($this->curl->exec('shop/api/Orders/get/complete')->json_result());
          if (is_array($ids)) {
          $resp = $this->curl->exec(array('shop/api/Orders/updateStatus', array("json" => json_encode($ids))))->json_result();

          $this->updateStatusBy4partnerResult($resp, 'complete');
          } else {
          echo json_encode(array('resp' => False));
          } */
        //$this->update_orders($this->curl->exec('shop/api/Orders/get/complete')->json_result());
        //$this->update_orders($orders);
    }

    public function upload_orders() {
        $this->updateStatusBy4partnerResult($this->curl->exec(array('shop/api/Orders/add/', array("json" => json_encode($this->get_new_orders(), true))))->json_result(), 'processing'); //партнерка возвращает айди обработанных заказов
    }

    public function upload_order($orderId) {
        $this->updateStatusBy4partnerResult($this->curl->exec(array('shop/api/Orders/add/', array("json" => json_encode($this->get_new_orders($orderId), true))))->json_result(), 'processing');
        /* $file = '/var/www/ononaru/data/www/testfunc';
          $current = file_get_contents($file);
          $current .= "работает  Время: ".date("d.m.Y H:i:s")."\n";
          file_put_contents($file, $current); */
    }

    protected function updateStatusBy4partnerResult($result, $status) {
        //print_r($result);
        /* Обновление статусов обработанных заказов */
        //        @file_put_contents('/var/www/ononaru/data/logs/test.log', __FILE__.':'.__LINE__ . ' ' . date('c') . ' ' . print_r($result, 1)."\n", FILE_APPEND | FILE_TEXT);
        if ($result['resp']) {
            foreach ($result['accept_ids'] as $id) {
                if ($o = Orders::find_by_id($id)) {
                    $o->sync_status = $status;
                    $o->save();
                } else {
                    echo json_encode(array('resp' => False, 'Err' => "Can't find Order id in shop DB $id "));
                    exit;
                }
            }
            echo json_encode(array('resp' => True, 'accept_ids' => $result['accept_ids']));
        } else {
            echo json_encode($result);
        }
        /*         * *************************************** */
    }

    public function download_orders() {
        $ids = $this->update_orders($this->curl->exec('shop/api/Orders/get/precomplete')->json_result());
        if (is_array($ids)) {
            $resp = $this->curl->exec(array('shop/api/Orders/updateStatus', array("json" => json_encode($ids))))->json_result();

            $this->updateStatusBy4partnerResult($resp, 'complete');
        } else {
            echo json_encode(array('resp' => False));
        }
    }

    private function update_product_item($art, $info, $productStock) {
        $item = Products::first(array('conditions' => array(' code = ? ', $art)));
        // if ($art=='SE-4545-20-3') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'update_product_item '.print_r(['info'=>$info, 'prStock'=>$productStock[$art], 'item'=>$item], true)." \n", FILE_APPEND );
        if (empty($item)) {
            /* $item = new Products();
              $item->code = $info['article'];
              $item->id1c = $info['id1c'];
              $item->name = $info['title'];
              //$item->content = $info['brief_description'];
              $item->content = $info['description'];
              $item->price = $info['price'];
              $item->is_public = 0;
              $item->generalcategory_id = 135; */ //установить в зависимости от того какой айди группы новых товаров у вас в базе
            $nonProducts = true;
        } else {
            if ((integer) $info['in_stock'] <= 1) {
                //$item->is_public = 0;
            } else {
                if ($item->sync) {
                    include __DIR__ . '/senduser.php';
                }
                //require_once __DIR__ . '/senduser.php';
                // $item->is_public = 1;
                //$item->is_public = 1;
            }
            $nonProducts = false;



            if ($item->discount > 0) {
                if ($info['price'] < $item->old_price) {
                    $item->price = $info['price'];
                    $item->discount = 0;
                    $item->old_price = 0;
                    $item->step = null;
                    $item->endaction = null;
                } else {
                    $item->old_price = $info['price'];
                    $item->price = round($info['price'] - ($info['price'] * $item->discount / 100));
                }
            } else {
                $item->price = $info['price'];

                $item->old_price = 0;
            }

            if (isset($productStock[$art])) {
                $item->stock = $productStock[$art];
            }
        }
        /* if ($item->id != 12385 and $item->id != 12384) {
          if ($item->discount > 0) {
          $item->old_price = $info['price'];
          $item->price = round($info['price'] - ($info['price'] * $item->discount / 100));
          } else {
          $item->price = $info['price'];

          $item->old_price = 0;
          }
          } */
        /* if($item->id==579 or $item->id==560 or $item->id==573){
          echo $info['price']."e2e";} */
        //$item->price =  $info['price'];

        @ $item->id1c = $info['id1c'];
        $item->count = (integer) $info['in_stock'];
        if ($nonProducts) {
            /* $ProductCategory = new ProductCategory();
              $ProductCategory->category_id = 135;
              $ProductCategory->product_id = $item->id; */
            // $item->save();
            // $ProductCategory->save();
        } else {
            if ($item->sync) {
                $item->save();
            }
        }
    }

    public function update_catalog() {
        global $config;
        $prdb = $this->curl->exec('shop/api/catalog/get')->json_result();
        //print_r($prdb);exit;
        file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", '----------- start at '.date('d.m.Y H:i:s')." --------------\n".print_r($prdb['products']['JO10632'], true));
        $updated_items = array();
        $productStock = array();
        $xmlstock = simplexml_load_file($config['stockxml'], 'SimpleXMLElement', LIBXML_NOCDATA);
        foreach ($xmlstock->xpath('products/product') as $product) {
            $product = json_decode(json_encode($product), TRUE);
            if (@$product['storages']['storage']['@attributes']['code1c'] == "000000044") {
                unset($product['storages']['storage']);
            }
            $productStock[trim($product['@attributes']['code'])] = serialize($product);
            //print_r($product);exit;
        }
        // file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", '----------- stockxml parsed '.date('d.m.Y H:i:s')." --------------\n", FILE_APPEND );
        if ($xmldb = simplexml_load_file($config['productsxml'])) {
            // file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_log.txt", date("d-m-Y H:i:s") . " Колличество товаров из партнёрки:" . count($prdb['products']) . " Количество товаров из xml:" . count($xmldb->xpath('products/product')) . "\r\n", FILE_APPEND);
            // echo "<pre>";print_r($prdb['products']);echo "</pre>";exit;
            Products::update_all(array('set' => 'count = 0', 'conditions' => 'sync = 1'));
            foreach ($xmldb->xpath('products/product') as $product) {
                $art = trim($product['code']);
                // if ($art=='SE-4545-20-3') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'point #1 '.print_r($product, true)." \n", FILE_APPEND );
                //print_r($art);exit;
                if (isset($prdb['products'][$art])) {
                    // if ($art=='SE-4545-20-3') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'point #2 '.date('d.m.Y H:i:s')." \n", FILE_APPEND );
                    $this->update_product_item($art, $prdb['products'][$art], $productStock);
                }
                elseif ($item = Products::first(array('conditions' => array(' code = ? ', $art)))) {
                    if ($item->sync) {
                        if (isset($productStock[$art])) {
                            // if ($art=='SE-4545-20-3') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'point #3 '.date('d.m.Y H:i:s')." \n", FILE_APPEND );
                            $item->stock = $productStock[$art];
                        }
                        if ((integer) $product['in-stock'] <= 1) {
                            // $item->is_public = 0;
                        } else {
                            include __DIR__ . '/senduser.php';
                            //require_once __DIR__ . '/senduser.php';
                            // $item->is_public = 1;
                        }

                        // print_r((integer) $product['code1c']);exit;

                        @ $item->id1c = $product['code1c'];
                        $item->price = (integer) $product['price'];

                        if ($item->discount > 0) {
                          // if ($art=='SE-4545-20-3') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'point #4 '.date('d.m.Y H:i:s')." \n", FILE_APPEND );
                            if ($product['price'] < $item->old_price) {
                              // if ($art=='SE-4545-20-3') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'point #5 '.date('d.m.Y H:i:s')." \n", FILE_APPEND );
                                $item->price = $product['price'];
                                $item->discount = 0;
                                $item->old_price = 0;
                                $item->step = null;
                                $item->endaction = null;
                            } else {
                              // if ($art=='SE-4545-20-3') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'point #5-alter '.date('d.m.Y H:i:s')." \n", FILE_APPEND );
                                $item->old_price = $product['price'];
                                $item->price = round($product['price'] - ($product['price'] * $item->discount / 100));
                            }
                        } else {
                            $item->price = $product['price'];
                            // if ($art=='SE-4545-20-3') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'point #6 '.date('d.m.Y H:i:s')." \n", FILE_APPEND );
                            $item->old_price = 0;
                        }
                        $item->count = (integer) $product['in-stock'];
                        //���� - ���������� ������, ���� ������ ��� ���������� �� ������ ������ 1
                        //if ($item->count > 0)
                          // if ($art=='SE-4545-20-3') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'point #7 '.date('d.m.Y H:i:s')." \n", FILE_APPEND );
                            $item->save();
                    }
                }
                $updated_items[$art] = $product['in-stock'];
            }
            $articles_to_check = array();
            foreach (Products::find('all') as $p) {
                if (!isset($updated_items[$p->code])) {
                    $articles_to_check[] = $p->code;
                }
            }
            $checkedItems = $this->curl->exec(array('shop/api/catalog/getByArt/', array('json' => json_encode(array('article' => $articles_to_check)))))->json_result();
            foreach ($checkedItems['products'] as $prodItem) {
              if ($prodItem['article']=='JO10632') file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", 'shop/api/catalog/getByArt/ '.print_r($prodItem, true)." \n", FILE_APPEND );
                $this->update_product_item($prodItem['article'], $prodItem, $productStock);
            }
        } else {
            echo json_encode(array(
                'resp' => False,
                'Err' => "Can't get " . $config['productsxml']
            ));
        }
        // file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", '----------- productsxml parsed '.date('d.m.Y H:i:s')." --------------\n", FILE_APPEND );
        exec("/var/www/ononaru/data/www/symfony cc", $test);
        exec("/var/www/ononaru/data/www/symfony yamarket", $test);
        //print_r($test);
        echo json_encode(array('resp' => True));
        // file_put_contents("/var/www/ononaru/data/www/onona.ru/sync_catalog_data.txt", '----------- finish at '.date('d.m.Y H:i:s')." --------------\n", FILE_APPEND );
    }

    public function test() {
        echo $this->curl->exec(array(
            'shop/api/catalog/getByArt/',
            array(
                'json' => json_encode(array('article' => '00001 BT'))
            )
        ))->result();
    }

}
