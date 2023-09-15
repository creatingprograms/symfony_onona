<?php

/**
 * cart actions.
 *
 * @package    Magazin
 * @subpackage cart
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cart_newActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {



        $productId = (int) $request->getParameter('id');
        $this->cartFirstPage = 1;
        if ($this->getUser()->isAuthenticated()) {
            $this->bonus = BonusTable::getInstance()->findBy('user_id', $this->getUser()->getGuardUser()->getId());
            $this->bonusCount = 0;
            foreach ($this->bonus as $bonus) {
                $this->bonusCount = $this->bonusCount + $bonus->getBonus();
            }
        } else {
            $this->bonusCount = 0;
        }
        $bonusCountTemp = $this->bonusCount;
        $this->products_old = $this->getUser()->getAttribute('products_to_cart');
        $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';
        if (is_array($this->products_old)) {
            unset($this->products_old['д1']);
        }
        foreach ($this->products_old as $keyProdCart => $prod) {
            if ($prod['price'] == $prod['price_w_discount'] and $bonusCountTemp > 0) {
                $product = ProductTable::getInstance()->findOneById($prod["productId"]);
                if(!is_object($product)){
                  // die('sdfsdfsdf-1');
                  unset($this->products_old[$keyProdCart]);
                  continue;
                }
                if ($product->getBonuspay() != '') {
                    $percentBonuspay = $product->getBonuspay();
                } else {
                    $percentBonuspay = csSettings::get('PERSENT_BONUS_PAY');
                }
                $procBonus = $bonusCountTemp / ($prod['quantity'] * $prod['price']) * 100;
                $percentBonusPayForCount = floor($procBonus / 5) * 5;
                if ($percentBonusPayForCount < $percentBonuspay) {
                    $percentBonuspay = $percentBonusPayForCount;
                }
                $bonusCountTemp = $bonusCountTemp - round($prod['price'] * ($percentBonuspay / 100));
                $this->products_old[$keyProdCart]['bonuspay'] = round($prod['quantity'] * $prod['price'] * ($percentBonuspay / 100));
                $this->products_old[$keyProdCart]['percentbonuspay'] = $percentBonuspay;
                $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
            } else {
                $this->products_old[$keyProdCart]['bonuspay'] = 0;
                $this->products_old[$keyProdCart]['percentbonuspay'] = 0;
                $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
            }
        }
        //print_r($this->products_old);

        if ($productId > 0 and is_array($this->products_old)) {
            $productId = $productId - 1;
            unset($this->products_old[$productId]);
        }
        $this->getUser()->setAttribute('products_to_cart', serialize($this->products_old));
        if ($this->getUser()->getAttribute('actionCartCode') != "") {
            $this->executeActioninfo($request);
        }

        if ($this->getUser()->isAuthenticated()) {
            $GuardUser = $this->getUser()->getGuardUser();
            $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
            $GuardUser->save();
        }
        // die('sdfsdfsdf-3');
    }

    public function executeIndextest(sfWebRequest $request) {

        $productId = (int) $request->getParameter('id');
        $this->cartFirstPage = 1;
        if ($this->getUser()->isAuthenticated()) {
            $this->bonus = BonusTable::getInstance()->findBy('user_id', $this->getUser()->getGuardUser()->getId());
            $this->bonusCount = 0;
            foreach ($this->bonus as $bonus) {
                $this->bonusCount = $this->bonusCount + $bonus->getBonus();
            }
        } else {
            $this->bonusCount = 0;
        }
        $bonusCountTemp = $this->bonusCount;
        $this->products_old = $this->getUser()->getAttribute('products_to_cart');
        $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';
        if (is_array($this->products_old)) {
            unset($this->products_old['д1']);
        }
        foreach ($this->products_old as $keyProdCart => $prod) {
            if ($prod['price'] == $prod['price_w_discount'] and $bonusCountTemp > 0) {
                $product = ProductTable::getInstance()->findOneById($prod["productId"]);
                if ($product->getBonuspay() != '') {
                    $percentBonuspay = $product->getBonuspay();
                } else {
                    $percentBonuspay = csSettings::get('PERSENT_BONUS_PAY');
                }
                $procBonus = $bonusCountTemp / ($prod['quantity'] * $prod['price']) * 100;
                $percentBonusPayForCount = floor($procBonus / 5) * 5;
                if ($percentBonusPayForCount < $percentBonuspay) {
                    $percentBonuspay = $percentBonusPayForCount;
                }
                $bonusCountTemp = $bonusCountTemp - round($prod['price'] * ($percentBonuspay / 100));
                $this->products_old[$keyProdCart]['bonuspay'] = round($prod['quantity'] * $prod['price'] * ($percentBonuspay / 100));
                $this->products_old[$keyProdCart]['percentbonuspay'] = $percentBonuspay;
                $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
            } else {
                $this->products_old[$keyProdCart]['bonuspay'] = 0;
                $this->products_old[$keyProdCart]['percentbonuspay'] = 0;
                $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
            }
        }
        //print_r($this->products_old);
        if ($productId > 0 and is_array($this->products_old)) {
            $productId = $productId - 1;
            unset($this->products_old[$productId]);
        }
        $this->getUser()->setAttribute('products_to_cart', serialize($this->products_old));
    }

    public function executeActioninfo(sfWebRequest $request) {
        if ($_POST['text'] == "" and $this->getUser()->getAttribute('actionCartCode') != "") {
            $_POST['text'] = $this->getUser()->getAttribute('actionCartCode');
        }
        $this->cartFirstPage = 1;
        $this->getUser()->setAttribute('actionCartCode', $_POST['text']);
        $this->products_old = $this->getUser()->getAttribute('products_to_cart');
        $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';
        $discountAction = ActionsDiscountTable::getInstance()->createQuery()->where("text=?", $_POST['text'])->addWhere("startaction <= ?", date("Y-m-d H-i-s"))->addWhere("endaction >= ?", date("Y-m-d H-i-s"))->fetchOne();

        if ($discountAction) {
            if ($this->getUser()->isAuthenticated()) {
                $this->bonus = BonusTable::getInstance()->findBy('user_id', $this->getUser()->getGuardUser()->getId());
                $this->bonusCount = 0;
                foreach ($this->bonus as $bonus) {
                    $this->bonusCount = $this->bonusCount + $bonus->getBonus();
                }
            } else {
                $this->bonusCount = 0;
            }
            $bonusCountTemp = $this->bonusCount;
            $this->totalCost = 0;
            foreach ($this->products_old as $product) {
                $this->totalCost += ($product["quantity"] * $product["price"]);
            }
            $discountActionInterval = ActionsDiscountIntervalTable::getInstance()->createQuery()->where("actionsdiscount_id=?", $discountAction->getId())->addWhere("start <= ?", $this->totalCost)->addWhere("end >= ?", $this->totalCost)->fetchOne();
            if ($discountActionInterval) {
                $discountValue = $discountActionInterval->getDiscount();
            } else {
                $discountValue = $discountAction->getDiscount();
            }
            $products_old = $this->products_old;
            foreach ($products_old as $key => $product) {

                $productDB = ProductTable::getInstance()->findOneById($product["productId"]);
                if (@$product['discount'] < $discountValue) {
                    $products_old[$key]['discount'] = $discountValue;

                    $products_old[$key]["price_w_discount"] = $product["price"] * (1 - ($products_old[$key]['discount'] / 100));
                } elseif (@$product['discount'] > $productDB->getDiscount()) {
                    if (@$productDB->getDiscount() < $discountValue) {

                        $products_old[$key]['discount'] = $discountValue;

                        $products_old[$key]["price_w_discount"] = $product["price"] * (1 - ($products_old[$key]['discount'] / 100));
                    } else {

                        $products_old[$key]['discount'] = $productDB->getDiscount();

                        $products_old[$key]["price_w_discount"] = $product["price"] * (1 - ($products_old[$key]['discount'] / 100));
                    }
                }
            }

            $this->products_old = $products_old;
            foreach ($this->products_old as $keyProdCart => $prod) {
                if ($prod['price'] == $prod['price_w_discount'] and $bonusCountTemp > 0) {
                    $product = ProductTable::getInstance()->findOneById($prod["productId"]);
                    if ($product->getBonuspay() != '') {
                        $percentBonuspay = $product->getBonuspay();
                    } else {
                        $percentBonuspay = csSettings::get('PERSENT_BONUS_PAY');
                    }
                    $procBonus = $bonusCountTemp / ($prod['quantity'] * $prod['price']) * 100;
                    $percentBonusPayForCount = floor($procBonus / 5) * 5;
                    if ($percentBonusPayForCount < $percentBonuspay) {
                        $percentBonuspay = $percentBonusPayForCount;
                    }
                    $bonusCountTemp = $bonusCountTemp - round($prod['price'] * ($percentBonuspay / 100));
                    $this->products_old[$keyProdCart]['bonuspay'] = round($prod['quantity'] * $prod['price'] * ($percentBonuspay / 100));
                    $this->products_old[$keyProdCart]['percentbonuspay'] = $percentBonuspay;
                    $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
                } else {
                    $this->products_old[$keyProdCart]['bonuspay'] = 0;
                    $this->products_old[$keyProdCart]['percentbonuspay'] = 0;
                    $this->products_old[$keyProdCart]['priceonus5persent'] = $prod['price'] * 0.05;
                }
            }
            $this->getUser()->setAttribute('products_to_cart', serialize($this->products_old));
        }
    }

    public function executeClearcart(sfWebRequest $request) {
        $this->getUser()->setAttribute('products_to_cart', '');
        return $this->renderText("Ваша корзина пуста.<br /><br />");
    }
    /*
    public function executeCartrrlist(sfWebRequest $request) {
      $products_old = $this->getUser()->getAttribute('products_to_cart');
      throw new Doctrine_Table_Exception('DEBUG <pre/>~|'.print_r(   $products_old, true).'|~</pre>');
      $products_old = $products_old != '' ? unserialize($products_old) : '';
      $this->products_old=$products_old;
    }*/
    public function executeCartinfoheader(sfWebRequest $request) {
        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($products_old as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * ($product['price_w_discount'] > 0 ? $product['price_w_discount'] : $product['price']));
        }
        $this->products_old = $products_old;
    }

    public function executeCartinfotop(sfWebRequest $request) {
        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($products_old as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * ($product['price_w_discount'] > 0 ? $product['price_w_discount'] : $product['price']));
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

    public function executeDesire(sfWebRequest $request) {
        $productId = (int) $request->getParameter('id');

        if ($this->getUser()->isAuthenticated()) {
            //$desire = CompareTable::getInstance()->findOneByUserId($this->getUser()->getGuardUser()->getId());
            $this->products_desire_private = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("rule='Личный'")->fetchOne();

            if ($this->products_desire_private)
                $this->products_jel = $this->products_desire_private->getProducts() != '' ? unserialize($this->products_desire_private->getProducts()) : '';
            else {
                $this->products_jel = $this->getUser()->getAttribute('products_to_desire');
                //var_dump($this->products_jel);
                $this->products_jel = $this->products_jel != '' ? unserialize($this->products_jel) : array();

                //var_dump($this->products_jel);
            }
            $this->products_desire_public = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("rule='Публичный'")->fetchOne();
            $this->products_desire_completed = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("rule='Исполненый'")->fetchOne();
        } else {
            $this->products_jel = $this->getUser()->getAttribute('products_to_desire');
            $this->products_jel = $this->products_jel != '' ? unserialize($this->products_jel) : array();
        }
        if ($productId > 0 and is_array($this->products_jel)) {
            $d = array_search($productId, $this->products_jel);
            if ($d !== false) {
                unset($this->products_jel[$d]);
                if ($this->products_desire_private) {

                    $this->products_desire_private->setProducts(serialize($this->products_jel));
                    $this->products_desire_private->save();
                }
            }
        }
        if ($this->products_desire_public) {
            if ($productId > 0 and is_array($this->products_jel_public = unserialize($this->products_desire_public->getProducts()))) {
                $d = array_search($productId, $this->products_jel_public);
                if ($d !== false) {
                    unset($this->products_jel_public[$d]);
                    if ($this->products_desire_public) {

                        $this->products_desire_public->setProducts(serialize($this->products_jel_public));
                        $this->products_desire_public->save();
                    }
                }
            }
        }
        if ($this->products_desire_completed) {
            if ($productId > 0 and is_array($this->products_jel_completed = unserialize($this->products_desire_completed->getProducts()))) {
                $d = array_search($productId, $this->products_jel_completed);
                if ($d !== false) {
                    unset($this->products_jel_completed[$d]);
                    if ($this->products_desire_completed) {

                        $this->products_desire_completed->setProducts(serialize($this->products_jel_completed));
                        $this->products_desire_completed->save();
                    }
                }
            }
        }
        $this->getUser()->setAttribute('products_to_desire', serialize($this->products_jel));
    }

    public function executeCompare(sfWebRequest $request) {
        $productId = (int) $request->getParameter('id');

        $this->products_compare = $this->getUser()->getAttribute('products_to_compare');
        $this->products_compare = $this->products_compare != '' ? unserialize($this->products_compare) : '';
        if ($productId > 0 and is_array($this->products_compare)) {
            $d = array_search($productId, $this->products_compare);
            unset($this->products_compare[$d]);
        }

        $this->getUser()->setAttribute('products_to_compare', serialize($this->products_compare));
    }

    public function executeAddtocart(sfWebRequest $request) {

        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
        //if (@!isset($products_old[(int) $request->getParameter('id')])):
        $row = array();
        $row["productId"] = (int) $request->getParameter('id');
        $row["productOptions"] = $request->getParameter('productOptions');
        $row["quantity"] = $request->getParameter('quantity', 1);
        $product = ProductTable::getInstance()->findOneById($row["productId"]);

        $row["price"] = $product->getPrice();
        if ($product->getOldPrice() > 0) {
            $row["price"] = $product->getOldPrice();
            $row["price_w_discount"] = $product->getPrice();
            $row["discount"] = round((1 - ($product->getPrice() / $product->getOldPrice())) * 100);
        } else {
            $row["price"] = $product->getPrice();
            $row["price_w_discount"] = $product->getPrice();
        }
        /* foreach ($products_old as $key => $product) {
          if (@$product["productId"] == @$row["productId"] && @$product["productOptions"] == @$row["productOptions"]) {
          //$products_old[$key]["quantity"] = $products_old[$key]["quantity"] + $row['quantity'];
          $row = array();
          }
          }
          if (isset($row["productId"]) && $row["productId"] > 0) { */
        $products_old[$row["productId"]] = $row;
        $productDB = ProductTable::getInstance()->findOneById($row["productId"]);
        $generalCategory = $productDB->getGeneralCategory();

        $personalRecomendation = unserialize(base64_decode(sfContext::getInstance()->getRequest()->getCookie('personalRecomendationCategory')));
        $personalRecomendation['category'][$generalCategory->getId()] = $personalRecomendation['category'][$generalCategory->getId()] + 2;
        $personalRecomendation['products'][$row["productId"]] = $personalRecomendation['products'][$row["productId"]] + 3;

        sfContext::getInstance()->getResponse()->setCookie('personalRecomendationCategory', base64_encode(serialize($personalRecomendation)), time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
        if ($this->getUser()->isAuthenticated()) {
            $GuardUser = $this->getUser()->getGuardUser();
            $GuardUser->set("personal_recomendation", serialize($personalRecomendation));
            $GuardUser->save();
        }
        //slot('personalRecomendationCategoryId', array($generalCategory->getId(), 2));
        //$this->getResponse()->setSlot('personalRecomendationCategoryId', array($generalCategory->getId(), 2));
        //}

        $this->totalCost = 0;
        $this->productsCount = 0;
        foreach ($products_old as $product) {
            $this->productsCount += $product["quantity"];
            $this->totalCost += ($product["quantity"] * $product["price"]);
        }
        $this->getUser()->setAttribute('productsCount', $this->productsCount);
        $this->getUser()->setAttribute('totalCost', $this->totalCost);
        $this->getUser()->setAttribute('products_to_cart', serialize($products_old));


        // echo '<pre>'.print_r(date('d.m.Y', 1451606408), true).'</pre>';
        // echo '<pre>'.print_r(unserialize($this->getUser()->getAttribute('products_to_cart')), true).'</pre>';
        if ($this->getUser()->isAuthenticated()) {
            $GuardUser = $this->getUser()->getGuardUser();
            $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
            $GuardUser->save();
        }
        //endif;
        return $this->renderText(false);
    }
    public function executeAddtocartya(sfWebRequest $request) {

        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
        $row = array();
        $row["productId"] = (int) $request->getParameter('id');
        $row["productOptions"] = $request->getParameter('productOptions');
        $row["quantity"] = $request->getParameter('quantity', 1);
        $product = ProductTable::getInstance()->findOneById($row["productId"]);
        if(is_object($product)){

          $row["price"] = $product->getPrice();
          if ($product->getOldPrice() > 0) {
              $row["price"] = $product->getOldPrice();
              $row["price_w_discount"] = $product->getPrice();
              $row["discount"] = round((1 - ($product->getPrice() / $product->getOldPrice())) * 100);
          } else {
              $row["price"] = $product->getPrice();
              $row["price_w_discount"] = $product->getPrice();
          }
          $products_old[$row["productId"]] = $row;
        }

        // $productDB = ProductTable::getInstance()->findOneById($row["productId"]);
        // $generalCategory = $productDB->getGeneralCategory();

        // $personalRecomendation = unserialize(base64_decode(sfContext::getInstance()->getRequest()->getCookie('personalRecomendationCategory')));
        // $personalRecomendation['category'][$generalCategory->getId()] = $personalRecomendation['category'][$generalCategory->getId()] + 2;
        // $personalRecomendation['products'][$row["productId"]] = $personalRecomendation['products'][$row["productId"]] + 3;

        // sfContext::getInstance()->getResponse()->setCookie('personalRecomendationCategory', base64_encode(serialize($personalRecomendation)), time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
        // if ($this->getUser()->isAuthenticated()) {
        //     $GuardUser = $this->getUser()->getGuardUser();
        //     $GuardUser->set("personal_recomendation", serialize($personalRecomendation));
        //     $GuardUser->save();
        // }

        $this->totalCost = 0;
        $this->productsCount = 0;
        $text=
          '<p>Onona.ru</p>'.
          '<h1>Ваша корзина</h1>'.
          '<table>'.
            '<thead>'.
              '<tr>'.
                '<th>№</th>'.
                '<th>Наименование</th>'.
                '<th>Количество</th>'.
                '<th>Цена</th>'.
                '<th>Сумма</th>'.
              '</tr>'.
            '</thead>'.
            '<tbody>'.
        '';
        $i=1;
        foreach ($products_old as $key => $product) {
          $this->productsCount += $product["quantity"];
          $this->totalCost += ($product["quantity"] * $product["price"]);
          $productBase = ProductTable::getInstance()->findOneById($key);
          if(is_object($productBase))
            $text.=
              '<tr>'.
                '<td>'.$i++.'</td>'.
                '<td>'.$productBase->getName().'</td>'.
                '<td>'.$product["quantity"].'</td>'.
                '<td>'.$product["price"].'</td>'.
                '<td>'.($product["quantity"] * $product["price"]).'</td>'.
              '</tr>'.
              '';
        }
        $text.=
            '</tbody>'.
            '<tfoot>'.
            '<tfoot>'.
            '<tr>'.
              '<th></th>'.
              '<th>Итого</th>'.
              '<th>'.$this->productsCount.'</th>'.
              '<th></th>'.
              '<th>'.$this->totalCost.'</th>'.
            '</tr>'.
          '</table>'.
        '';
        // die('<pre>'.print_r($text, true));
        $this->getUser()->setAttribute('productsCount', $this->productsCount);
        $this->getUser()->setAttribute('totalCost', $this->totalCost);
        $this->getUser()->setAttribute('products_to_cart', serialize($products_old));


        if ($this->getUser()->isAuthenticated()) {
            $GuardUser = $this->getUser()->getGuardUser();
            $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
            $GuardUser->save();
        }

        header('Content-Type: text/html; charset=utf-8');
        die($text);

        // return $this->renderText(false);
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


        if ($this->getUser()->isAuthenticated()) {
            $desirePrivate = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("rule='Личный'")->fetchOne();

            if (!$desirePrivate) {
                $desirePrivate = new Compare();
                $desirePrivate->setUserId($this->getUser()->getGuardUser());
                $desirePrivate->setRule("Личный");
            }

            $desirePrivateArray = unserialize($desirePrivate->getProducts());
            $desirePrivateArrayInfo = unserialize($desirePrivate->getProductsinfo());

            $row = array();
            $row["productId"] = (int) $request->getParameter('id');
            $row["time"] = (int) time();

            if (in_array($row["productId"], $desirePrivateArray) === true)
                $row = array();

            if (isset($row["productId"]) && $row["productId"] > 0) {
                $desirePrivateArray[] = $row["productId"];
                $desirePrivateArrayInfo[] = $row;
            }

            $desirePrivate->setProducts(serialize($desirePrivateArray));
            $desirePrivate->setProductsinfo(serialize($desirePrivateArrayInfo));
            $desirePrivate->save();

            $this->getUser()->setAttribute('products_to_desire', serialize($desirePrivateArray));
        } else {
            $desirePrivateArray = unserialize($this->getUser()->getAttribute('products_to_desire'));

            $row = array();
            $row["productId"] = (int) $request->getParameter('id');

            if (in_array($row["productId"], $desirePrivateArray) === true)
                $row = array();

            if (isset($row["productId"]) && $row["productId"] > 0) {
                $desirePrivateArray[] = $row["productId"];
            }
            $this->getUser()->setAttribute('products_to_desire', serialize($desirePrivateArray));
        }



        $productDB = ProductTable::getInstance()->findOneById((int) $request->getParameter('id'));
        $generalCategory = $productDB->getGeneralCategory();
        $personalRecomendation = unserialize(base64_decode(sfContext::getInstance()->getRequest()->getCookie('personalRecomendationCategory')));
        $personalRecomendation['category'][$generalCategory->getId()] = $personalRecomendation['category'][$generalCategory->getId()] + 2;
        $personalRecomendation['products'][$row["productId"]] = $personalRecomendation['products'][$row["productId"]] + 2;
        sfContext::getInstance()->getResponse()->setCookie('personalRecomendationCategory', base64_encode(serialize($personalRecomendation)), time() + 60 * 60 * 24 * 30, '/', ".onona.ru");
        if ($this->getUser()->isAuthenticated()) {
            $GuardUser = $this->getUser()->getGuardUser();
            $GuardUser->set("personal_recomendation", serialize($personalRecomendation));
            $GuardUser->save();
        }
        /* $products_jel = unserialize($this->getUser()->getAttribute('products_to_desire'));

          $row = array();
          $row["productId"] = (int) $request->getParameter('id');
          $row["time"] = (int) time();

          foreach ($products_jel as $key => $product) {
          if (@$product["productId"] == @$row["productId"]) {
          $row = array();
          }
          }

          if (isset($row["productId"]) && $row["productId"] > 0) {
          $products_jel[] = $row["productId"];
          }

          $this->getUser()->setAttribute('products_to_desire', serialize($products_jel));

          if ($this->getUser()->isAuthenticated()) {
          //$desire = CompareTable::getInstance()->findOneByUserId($this->getUser()->getGuardUser()->getId());
          $desire = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->addWhere("rule='Личный'")->fetchOne();
          if (!$desire) {
          $desire = new Compare();
          $desire->setUserId($this->getUser()->getGuardUser());
          $desire->setRule("Личный");
          }
          $desire->setProducts(serialize($products_jel));
          $desire->save();
          } */
        return $this->renderText(count($desirePrivateArray));
    }

    public function executeAddtocompare(sfWebRequest $request) {
        $products_compare = unserialize($this->getUser()->getAttribute('products_to_compare'));

        $row = array();
        $row["productId"] = (int) $request->getParameter('id');

        foreach ($products_compare as $key => $product) {
            if (@$product["productId"] == @$row["productId"]) {
                $row = array();
            }
        }

        if (isset($row["productId"]) && $row["productId"] > 0) {
            $products_compare[] = $row["productId"];
        }

        $this->getUser()->setAttribute('products_to_compare', serialize($products_compare));

        return $this->renderText(count($products_compare));
    }

    public function executeDelalltocompare(sfWebRequest $request) {
        $this->getUser()->setAttribute('products_to_compare', "");

        return $this->renderText("Список сравнения очищен.");
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

    public function executeConfirmedcss(sfWebRequest $request) {
      $this->order=OrdersTable::getInstance()->findOneById(197948);//197948 с промокодом, 197942 без
    }

    public function executeConfirmedtest(sfWebRequest $request) {


        $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
        $TotalSumm = 0;
        $bonusPay = 0;
        if ($request->getParameter('formType') == 1) {
            $bonusPercent = $request->getParameter('bonusPercet1Form');
        } else {
            $bonusPercent = $request->getParameter('bonusPercet2Form');
        }
        //print_r($bonusPercent);
        foreach ($products_old as $key => $productInfo):
            $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
            if ($product->getBonuspay() != '') {
                $percentBonuspay = $product->getBonuspay();
            } else {
                $percentBonuspay = csSettings::get('PERSENT_BONUS_PAY');
            }
            if ($percentBonuspay > $bonusPercent[$key]) {
                $percentBonuspay = $bonusPercent[$key];
            }
            $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) );
            if ($percentBonuspay > 0) {
                $bonusPay = $bonusPay + round(($percentBonuspay / 100) * $productInfo['price'] * $productInfo['quantity']);
            }
        endforeach;
        //echo $TotalSumm;
        return true;
    }

    public function executeConfirmed(sfWebRequest $request) {
        $ordersPerHour=csSettings::get('orders_per_hour');

        // return $this->redirect('/ip_denied');
        // $order->set('ipUser', $_SERVER['REMOTE_ADDR']);
        $ip = $_SERVER['REMOTE_ADDR'];
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sqlBody="SELECT COUNT(*) as order_count from `orders` WHERE ipuser='$ip' AND created_at > '".date('Y-m-d H:i', time()-60*60)."'";
        $count= $q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);
        // die('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><pre>'.print_r([$count, $ordersPerHour, $sqlBody], true).'</pre>');
        if ($ordersPerHour <= $count[0]['order_count'] )
          return $this->redirect('/ip_denied');
          $autorizationUserNow = false;
        if ($this->getUser()->isAuthenticated()) {
            $user = $this->getUser()->getGuardUser();
        } else {
            $sucaptcha=$request->getParameter('captcha');
            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='su'")->fetchOne();
            if($_SERVER['SERVER_NAME']!='dev.onona.ru') $captchaVal=$captcha->getVal();
            else $captchaVal=$sucaptcha;
            // /*
            if (!$sucaptcha || $sucaptcha != $captchaVal){
              return $this->redirect('/cart');
            }
            // */
            if ($request->getParameter('formType') == 1) {
                $user = sfGuardUserTable::getInstance()->findOneByEmailAddress(trim($request->getParameter('user_mail')));
                if (!$user) {
                    $user = new sfGuardUser();
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
                    $user->setUsername($username);
                    $password = rand(100000, 999999);
                    $user->set("password", $password);
                    $autorizationUserNow = true;
                }
            } elseif ($request->getParameter('formType') == 2) {
                $userForm = $request->getParameter('sf_guard_user');
                $user = sfGuardUserTable::getInstance()->findOneByEmailAddress(trim($userForm['email_address']));
                if (!$user) {
                    $user = new sfGuardUser();
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
                    $user->setUsername($username);
                    $password = rand(100000, 999999);
                    $user->set("password", $password);
                    $autorizationUserNow = true;
                }
            } else {
                return $this->redirect('/cart');
            }
        }

        if ($request->getParameter('formType') == 1) {
            if ($request->getParameter('user_mail') != "") {
                $user->setFirstName($request->getParameter('user_name'));
                $user->setLastName($request->getParameter('last_name'));
                $user->setEmailAddress(trim($request->getParameter('user_mail')));
                $user->setPhone($request->getParameter('user_phone'));
            } elseif ($this->getUser()->getAttribute('products_to_cart') != "") {
                return $this->redirect('/cart');
            } else {
                return $this->redirect('/sexshop');
            }
        } else {
            $userForm = $request->getParameter('sf_guard_user');
            if ($userForm['email_address'] != "") {
                $user->setFirstName($userForm['first_name']);
                $user->setLastName($userForm['last_name']);
                $user->setEmailAddress(trim($userForm['email_address']));
                $user->setPhone($userForm['phone']);
                $user->setBirthday($userForm['birthday']);
                $user->setCity($userForm['city']);
                $user->setStreet($userForm['street']);
                $user->setHouse($userForm['house']);
                $user->setApartament($userForm['apartament']);
            } elseif ($this->getUser()->getAttribute('products_to_cart') != "") {
                return $this->redirect('/cart');
            } else {
                return $this->redirect('/sexshop');
            }
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
                $this->bonus = BonusTable::getInstance()->findBy('user_id', $user->getGuardUser()->getId());
                $this->bonusCount = 0;
                foreach ($this->bonus as $bonus) {
                    $this->bonusCount = $this->bonusCount + $bonus->getBonus();
                }
            } else {
                $this->bonusCount = 0;
            }

            /* $products_old = $user->getAttribute('products_to_cart');
              $products_old = $products_old != '' ? unserialize($products_old) : '';
              $TotalSumm = 0;
              $bonusPay = 0;
              foreach ($products_old as $key => $productInfo):
              $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
              $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) );
              if ($productInfo['price'] == $productInfo['price_w_discount']) {
              $bonusPay = $bonusPay + round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $productInfo['price']);
              }
              endforeach; */
            $products_old = unserialize($this->getUser()->getAttribute('products_to_cart'));
            $TotalSumm = 0;
            $bonusPay = 0;
            if ($request->getParameter('formType') == 1) {
                $bonusPercent = $request->getParameter('bonusPercet1Form');
            } else {
                $bonusPercent = $request->getParameter('bonusPercet2Form');
            }
            $isbonuspayper = false;
            //print_r($bonusPercent);
            $notadddelivery = true;
            foreach ($products_old as $key => $productInfo):
                $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                if(!is_object($product)){
                  // die('sdfsdfsdf-4');
                  unset($products_old[$key]);
                  continue;
                }
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
                // $cache->removePattern('/sf_cache_partial/category/__productsbestprice/sf_cache_key/' . $product->getId());
                /*
                 * 02.07.15
                 */
//                $newdis_cache_dir = '/var/www/ononaru/data/www/cache/newdis/*/template/*/all';
//                $cache = new sfFileCache(array('cache_dir' => $newdis_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
//                $cache->removePattern('/sf_cache_partial/category/__products/sf_cache_key/' . $product->getId() . "-");
//
//                $newdis_cache_dir = '/var/www/ononaru/data/www/cache/newcat/*/template/*/all';
//                $cache = new sfFileCache(array('cache_dir' => $newdis_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
//                $cache->removePattern('/sf_cache_partial/category/__products/sf_cache_key/' . $product->getId() . "-");


                if ($product->getBonuspay() != '') {
                    $percentBonuspay = $product->getBonuspay();
                } else {
                    $percentBonuspay = csSettings::get('PERSENT_BONUS_PAY');
                }
                if ($percentBonuspay > $bonusPercent[$key]) {
                    $percentBonuspay = $bonusPercent[$key];
                }
                //$TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) );
                if ($percentBonuspay > 0) {
                    $bonusPay = $bonusPay + round(($percentBonuspay / 100) * $productInfo['price'] * $productInfo['quantity']);

                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price'] ) - round(($percentBonuspay / 100) * $productInfo['price'] * $productInfo['quantity']);

                    $products_old[$key]['price_w_discount'] = $productInfo['price'];
                    $products_old[$key]['price_w_bonuspay'] = $productInfo['price'] - round(($percentBonuspay / 100) * $productInfo['price']);
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

            $dlh_shop_id = trim($_POST["dlh_shop_id"]);
            if (!empty($dlh_shop_id)) {
                $order->setComments($order->getComments() . " \r\n ID точки доставки DLH: " . $dlh_shop_id);
            }
            $dlh_address_line = trim($_POST["dlh_address_line"]);
            if (!empty($dlh_address_line)) {
                $order->setComments($order->getComments() . " \r\n Адрес точки DLH: " . $dlh_address_line);
            }

            $qiwi_id = trim($_POST["qiwiId"]);
            if (!empty($qiwi_id) and $_POST['deliveryId'] == 13) {
                $order->setComments($order->getComments() . " \r\n ID постамата QIWI: " . $qiwi_id);
            }
            $qiwi_address = trim($_POST["qiwiAddr"]);
            if (!empty($qiwi_address) and $_POST['deliveryId'] == 13) {
                $order->setComments($order->getComments() . " \r\n Адрес постамата QIWI: " . $qiwi_address);
            }
            /*
            if(isset($_COOKIE['utm_source']) && $_COOKIE['utm_source']){
              $order->setComments($order->getComments() . "\r\n---------------------------------------------------------- \r\n Источник: " . $_COOKIE['utm_source']);
            }*/


            /* if (!empty($_COOKIE["samyragon"])) {
              $order->setSamyragon($_COOKIE["samyragon"]);
              } */

            if (($_POST['deliveryPriceSend'] > 0) and ! $notadddelivery) {

                //$products_old = unserialize($user->getAttribute('products_to_cart'));

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
            if($_COOKIE['utm_source']=='advcake'){//Если заказ пришел от advcake - заполняем их служебные поля
              $order->setAdvcakeUrl($_COOKIE['advcake_url']);
              $order->setAdvcakeTrackid($_COOKIE['advcake_trackid']);
              // setcookie('advcake_url', false, time() -1, '/', '.onona.ru');  //Запоминаем на 30 дней платный источник
              // setcookie('advcake_trackid', false, time() -1, '/', '.onona.ru');  //Запоминаем на 30 дней платный источник
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
            // setcookie('utm_source', false, time() -1, '/', '.onona.ru');  //Запоминаем на 30 дней платный источник

            if ($this->getUser()->isAuthenticated()) {
                $newBonusActive = new Bonus();
                $newBonusActive->setUserId($userId);
                $newBonusActive->setBonus(0);
                $newBonusActive->setComment('Продление жизни бонусов за заказ');
                $newBonusActive->save();
                if(date('d.m.Y')=='17.02.2019'){
                  $actionBonusActive = new Bonus();
                  $actionBonusActive->setUserId($userId);
                  $actionBonusActive->setBonus(777);
                  $actionBonusActive->setComment('Подарок за заказ 17 февраля ('.csSettings::get('order_prefix') . $order->getId().')');
                  $actionBonusActive->save();
                }
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

            //file_get_contents('http://' . $_SERVER['HTTP_HOST'] . '?r=cmd&command=upload_order&err=1&api=8d956bbe43b2809baa904afb20a65831&id=' . $order->getId());
            exec('php -r "echo file_get_contents(\'https://onona.ru?r=cmd&command=upload_order&err=1&api=8d956bbe43b2809baa904afb20a65831&id=' . $order->getId() . '\');" > /dev/null &');
            /*  $fp = fsockopen($_SERVER['HTTP_HOST'], 80, $errno, $errstr, 30);

              if ($fp)  {
              $parts=parse_url('http://' . $_SERVER['HTTP_HOST'] . '?r=cmd&command=upload_order&err=1&api=8d956bbe43b2809baa904afb20a65831&id=' . $order->getId());
              $out = "POST " . $parts['path'] . " HTTP/1.1\r\n";
              $out.= "Host: " . $parts['host'] . "\r\n";
              $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
              $out.= "Content-Length: " . strlen($parts['query']) . "\r\n";
              $out.= "Connection: Close\r\n\r\n";
              if (isset($parts['query']))
              $out.= $parts['query'];

              fwrite($fp, $out);
              fclose($fp);
              } */



            $products_old = $user->getAttribute('products_to_cart');
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            $TotalSumm = 0;
            $yaParams = '';
            $googleParams = "";
            $bonusAddUser = 0;
            $tableOrder =
              '<table  cellpadding="0" cellspacing="0" border="0" style="width: 100%;border-collapse: collapse;background: white;">'.
                '<tr>'.
                  '<td style="width: 55px;"></td>'.
                  '<td>'.
                    '<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: 170px; border-collapse: collapse;background: white;">'.
                      '<tr>'.
                        '<th colspan="2" style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;">Наименование</th>'.
                        '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;">Цена, руб</th>'.
                        '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;">Скидка, %</th>'.
                        '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;width:140px;">Бонусы, %</th>'.
                        '<th style="background: #f2cfb9;height: 40px;border:5px solid #f2cfb9;border-bottom: 0;width: 150px;">Сумма, руб</th>'.
                      '</tr>';
            foreach ($products_old as $key => $productInfo):
              if ($productInfo['productId'] > 0):
                  $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                  $photoalbum = $product->getPhotoalbums();
                  $photos = $photoalbum[0]->getPhotos();
                  $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) );
                  $categoryProd = $product->getGeneralCategory();
                  if ($product->getBonus() != 0) {
                      if ($productInfo['price_w_bonuspay'] > 0) {

                          $bonusAddUser = $bonusAddUser + round(($productInfo['price_w_bonuspay'] * $productInfo['quantity'] * $product->getBonus()) / 100);
                      } else {
                          $bonusAddUser = $bonusAddUser + round(($productInfo['price_w_discount'] * $productInfo['quantity'] * $product->getBonus()) / 100);
                      }
                  } else {
                      if ($productInfo['price_w_bonuspay'] > 0) {
                          $bonusAddUser = $bonusAddUser + round(($productInfo['price_w_bonuspay'] * $productInfo['quantity'] * csSettings::get("persent_bonus_add")) / 100);
                      } else {
                          $bonusAddUser = $bonusAddUser + round(($productInfo['price_w_discount'] * $productInfo['quantity'] * csSettings::get("persent_bonus_add")) / 100);
                      }
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


                  if ($product->getId() != 14613) {
                    if ($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) {
                        $priceOneProd = $productInfo['price_w_discount'];
                    } elseif ($productInfo['bonus_percent'] > 0) {
                        $priceOneProd = $productInfo['price'] - round(($productInfo['bonus_percent'] / 100) * $productInfo['price']);
                    } else {
                        $priceOneProd = $productInfo['price'];
                    }
                    $priceAllProd = $priceOneProd * $productInfo['quantity'];
                    $tableOrder .=
                      '<tr>'.
                        '<td style="width: 161px; height: 123px;border:5px solid #dfdbdc;border-right: 0;border-top: 5px solid #f2cfb9;">'.
                          '<img style="display: block; height:123px; margin: auto;" src="https://onona.ru/uploads/photo/thumbnails_250x250/'. $photos[0]->getFilename().'">'.
                        '</td>'.
                        '<td style="width: 280px; border:5px solid #dfdbdc;border-left: 0;border-top: 5px solid #f2cfb9;">'.
                          '<a href="https://onona.ru/product/'.$product->getSlug().'" style="display: inline-block;padding: 30px;color:black;">'.
                            $product->getName().
                          '</a>'.
                        '</td>'.
                        '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;">'.$productInfo['price'].'</td>'.
                        '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;">'.(($productInfo['price_w_discount'] > 0 and $productInfo['price_w_discount'] != $productInfo['price']) ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0').'</td>'.
                        '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;">'.$products_old[$key]['bonus_percent'].'</td>'.
                        '<td style="border:5px solid #dfdbdc;border-top: 5px solid #f2cfb9;text-align: center;font-size: 20px;"><b>'.$priceAllProd.'</b></td>'.
                      '</tr>';
                            /*
                        $tableOrder .= ' <div style="float:left;margin: -10px 10px -10px 0">  <a href="https://onona.ru/product/' . $product->getSlug() . '"><img width="70" src="https://onona.ru/uploads/photo/thumbnails_250x250/' . $photos[0]->getFilename() . '"></a></div>

                            <a href="https://onona.ru/product/' . $product->getSlug() . '">' . $product->getName() . '</a> ';*/
                    }/* else {

                        $tableOrder .= '   ' . $product->getName() . '
                        ';
                    }*/


                      /*$tableOrder .= '     </td>              <td style="text-align: center;   -moz-border-bottom-colors: none;
                      -moz-border-left-colors: none;">' . $productInfo['price'] . '</td>
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
                                  */
                endif;
            endforeach;
            $tableOrder .=
                    '</table>'.
                  '</td>'.
                  '<td style="width: 55px;"></td>'.
                '</tr>'.
              '</table>'.
              '';
            $this->TotalSumm = $TotalSumm;
            /* $Body = "Здравствуйте!<br>
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
              if ($productInfo['productId'] > 0):
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

              endif;
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

              $Body .= "Спасибо!"; */


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





            if ($this->is_email($userEmailAddress)) {

              /*105274 Сделать автоматическую рассылку "Выберите свой подарок от Он и Она!" после оформления заказа. */
              /*
              if(@sfContext::getInstance()->getUser()->getAttribute('regMO')){//Москва и МО
                $mailTemplateGift = MailTemplatesTable::getInstance()->findOneBySlug('order_create_gift');
                $mailTemplateGift->setText(str_replace('{dateOrder}', date('d.m.Y'), $mailTemplateGift->getText()));
                $mailTemplateGift->setText(str_replace('{idOrder}', $order->getPrefix() . $order->getId(), $mailTemplateGift->getText()));
                $mailTemplateGift->setText(str_replace('{nameCustomer}', $userSave->getFirstName(), $mailTemplateGift->getText()));

                $message = Swift_Message::newInstance()
                        ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                        ->setTo($userEmailAddress)
                        ->setSubject($mailTemplateGift->getSubject())
                        ->setBody($mailTemplateGift->getText())
                        ->setContentType('text/html')
                ;
                $this->getMailer()->send($message);
              }*/
              /*105274*/

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
                /*
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
            		</table>";*/
                $tableOrderHeader=$tableOrderFooter='';
                $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText()));

                $message = Swift_Message::newInstance()
                        ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                        ->setTo($userEmailAddress)
                        ->setSubject($mailTemplate->getSubject())
                        ->setBody($mailTemplate->getText())
                        ->setContentType('text/html')
                ;
                //echo $mailTemplate->getText();
                //exit;
                try{
                  $this->getMailer()->send($message); //exit;
                }
                catch(Exception $e){
                  //Как обрабатывать - ХЗ
                }
            } /* elseif ($this->is_email($this->getUser()->getGuardUser()->getEmailAddress())) {
              $message = Swift_Message::newInstance()
              ->setFrom(csSettings::get('smtp_user'))
              ->setTo($this->getUser()->getGuardUser()->getEmailAddress())
              ->setSubject('Ваш заказ на сайте OnOna.ru принят')
              ->setBody($Body)
              ->setContentType('text/html')
              ;
              $this->getMailer()->send($message);
              } */
            $user->setAttribute('deliveryId', "");
            $this->getUser()->setAttribute('products_to_cart', '');
            if ($this->getUser()->isAuthenticated()) {
                $GuardUser = $this->getUser()->getGuardUser();
                $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
                $GuardUser->save();
            }
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
