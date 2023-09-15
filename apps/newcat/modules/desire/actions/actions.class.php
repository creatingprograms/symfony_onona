<?php

/**
 * desire actions.
 *
 * @package    test
 * @subpackage desire
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class desireActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->desires = CompareTable::getInstance()->findByRule('Публичный');
    }

    public function executeRate(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated()) {
            $this->products_desire = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->execute();
            foreach ($this->products_desire as $desire) {
                if ($desire) {
                    $products_jel = $desire->getProductsinfo() != '' ? unserialize($desire->getProductsinfo()) : '';
                    foreach ($products_jel as $keyProd => $product) {
                        if ($product["productId"] == $request->getParameter('productId')) {
                            $products_jel[$keyProd]['rate'] = $request->getParameter('value');
                            $desire->setProductsinfo(serialize($products_jel));
                            $desire->save();
                        }
                    }
                }
            }
        }
        return $this->renderText($request->getParameter('value'));
    }

    public function executeSetting(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated()) {
            if ($request->getParameter('selCat') == "private") {
                $desiresPrivate = CompareTable::getInstance()->createQuery()->where("rule='Личный'")->addWhere("user_id=?", $this->getUser()->getGuardUser()->getId())->fetchOne();
                if (!$desiresPrivate) {
                    $desiresPrivate = new Compare();
                    $desiresPrivate->setRule('Личный');
                    $desiresPrivate->setUserId($this->getUser()->getGuardUser()->getId());
                    $desiresPrivate->save();
                }
            }
            if ($request->getParameter('selCat') == "public") {
                $desiresPublic = CompareTable::getInstance()->createQuery()->where("rule='Публичный'")->addWhere("user_id=?", $this->getUser()->getGuardUser()->getId())->fetchOne();
                if (!$desiresPublic) {
                    $desiresPublic = new Compare();
                    $desiresPublic->setRule('Публичный');
                    $desiresPublic->setUserId($this->getUser()->getGuardUser()->getId());
                    $desiresPublic->save();
                }
            }
            if ($request->getParameter('selCat') == "completed") {
                $desiresComleted = CompareTable::getInstance()->createQuery()->where("rule='Исполненый'")->addWhere("user_id=?", $this->getUser()->getGuardUser()->getId())->fetchOne();
                if (!$desiresComleted) {
                    $desiresComleted = new Compare();
                    $desiresComleted->setRule('Исполненый');
                    $desiresComleted->setUserId($this->getUser()->getGuardUser()->getId());
                    $desiresComleted->save();
                }
            }
            $this->products_desire = CompareTable::getInstance()->createQuery()->where("user_id=?", $this->getUser()->getGuardUser()->getId())->execute();

            foreach ($this->products_desire as $desire) {
                if ($desire) {

                    $products_jelId = $desire->getProducts() != '' ? unserialize($desire->getProducts()) : array();

                    if ((in_array($request->getParameter('productId'), $products_jelId) === false) and ( ($desire->getRule() == "Личный" and $request->getParameter('selCat') == "private") or ( $desire->getRule() == "Публичный" and $request->getParameter('selCat') == "public") or ( $desire->getRule() == "Исполненый" and $request->getParameter('selCat') == "completed"))) {
                        $products_jelId[] = $request->getParameter('productId');
                    } elseif ((in_array($request->getParameter('productId'), $products_jelId) === true) and ( ($desire->getRule() == "Личный" and $request->getParameter('selCat') == "private") or ( $desire->getRule() == "Публичный" and $request->getParameter('selCat') == "public") or ( $desire->getRule() == "Исполненый" and $request->getParameter('selCat') == "completed"))) {
                        
                    } else {
                        $keyProd = array_search($request->getParameter('productId'), $products_jelId);
                        if ($keyProd !== false) {
                            unset($products_jelId[$keyProd]);
                        }
                    }

                    $products_jel = $desire->getProductsinfo() != '' ? unserialize($desire->getProductsinfo()) : array();
                    unset($edit);
                    foreach ($products_jel as $keyProd => $product) {
                        if ($product["productId"] == $request->getParameter('productId')) {
                            if (($desire->getRule() == "Личный" and $request->getParameter('selCat') == "private") or ( $desire->getRule() == "Публичный" and $request->getParameter('selCat') == "public") or ( $desire->getRule() == "Исполненый" and $request->getParameter('selCat') == "completed")) {

                                $products_jel[$keyProd]['comment'] = $request->getParameter('comment');
                                $edit = true;
                            } else {
                                unset($products_jel[$keyProd]);
                                $edit = true;
                            }
                        }
                    }
                    if (!$edit) {
                        $row['productId'] = $request->getParameter('productId');
                        $row['comment'] = $request->getParameter('comment');
                        if ($request->getParameter('rate')!='')
                            $row['rate'] = $request->getParameter('rate');
                        $products_jel[] = $row;
                        unset($row);
                    }

                    $desire->setProducts(serialize($products_jelId));
                    $desire->setProductsinfo(serialize($products_jel));
                    $desire->save();
                }
            }
        }
        return $this->renderText("Изменения сохранены");
    }

    public function executeShow(sfWebRequest $request) {
        $this->desire = CompareTable::getInstance()->createQuery()->where("md5(created_at)=?", $request->getParameter('slug'))->andWhere("rule='Доступен со ссылкой' or rule='Публичный'")->fetchOne();


        $this->forward404Unless($this->desire);
    }

}
