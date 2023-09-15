<?php

require_once dirname(__FILE__) . '/../lib/deliverypaymentGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/deliverypaymentGeneratorHelper.class.php';

/**
 * deliverypayment actions.
 *
 * @package    test
 * @subpackage deliverypayment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class deliverypaymentActions extends autoDeliverypaymentActions {

    public function executeSortChange() {
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ?  $_POST["rowId"] : 0;
        
       // print_r($rowId);
        if($rowId!=0){
            $rowId=  explode("t", $rowId);
        }
       // print_r($rowId);
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;
//echo $last;
            $DeliveryPaymentsEnd = DeliveryPaymentTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $DeliveryPayment = DeliveryPaymentTable::getInstance()->createQuery()->where('delivery_id='.$rowId[0])->andWhere('payment_id='.$rowId[1])->fetchOne();//findOneById($rowId);
            $DeliveryPayment->setPosition($DeliveryPaymentsEnd[0]->getPosition() + 1);
            //echo $DeliveryPaymentsEnd[0]->getPosition();
            $DeliveryPayment->save();


            $DeliveryPaymentsUpSO = DeliveryPaymentTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();

            foreach ($DeliveryPaymentsUpSO as $DeliveryPayment) {
                $DeliveryPayment->setPosition($DeliveryPayment->getPosition() - 1);
                $DeliveryPayment->save();
            }

            $DeliveryPayment = DeliveryPaymentTable::getInstance()->createQuery()->where('delivery_id='.$rowId[0])->andWhere('payment_id='.$rowId[1])->fetchOne();
            $DeliveryPayment->setPosition($rowsList[$last]);
            // echo $rowsList[$last];
            $DeliveryPayment->save();
            //die;
        }
        if ($currentSortOrder > $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вверх
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE delivery_payment SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");
            $DeliveryPayment = DeliveryPaymentTable::getInstance()->createQuery()->where('delivery_id='.$rowId[0])->andWhere('payment_id='.$rowId[1])->fetchOne();
            $DeliveryPayment->setPosition($nextSortOrder);
            $DeliveryPayment->save();
        }
        if ($currentSortOrder < $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вниз
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE delivery_payment SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");

            $DeliveryPayment = DeliveryPaymentTable::getInstance()->createQuery()->where('delivery_id='.$rowId[0])->andWhere('payment_id='.$rowId[1])->fetchOne();
            $DeliveryPayment->setPosition($nextSortOrder);
            $DeliveryPayment->save();
        }


        $pager = $this->configuration->getPager('DeliveryPayment');
        /* $pager->setQuery($this->buildQuery()->select("*")->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_DeliveryPayment")->addWhere('parents_id IS NULL ')->orderBy("new_DeliveryPayment ASC")->addOrderBy("(count>0) DESC")->addOrderBy("position ASC"));
         */

        $pager->setQuery($this->buildQuery()
                        ->select("*")
                        ->addOrderBy("position ASC"));
        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        $this->sort = $this->getSort();
    }
    
    
    protected function addSortQuery($query) {
        $query->addOrderBy('position ASC');
    }

}
