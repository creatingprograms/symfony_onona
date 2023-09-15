<?php

require_once dirname(__FILE__) . '/../lib/deliveryGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/deliveryGeneratorHelper.class.php';

/**
 * delivery actions.
 *
 * @package    Magazin
 * @subpackage delivery
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class deliveryActions extends autoDeliveryActions {

    public function executeSortChange() {
        
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;

            $deliverysEnd = DeliveryTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $delivery = DeliveryTable::getInstance()->findOneById($rowId);
            $delivery->setPosition($deliverysEnd[0]->getPosition() + 1);
            $delivery->save();


            $deliverysUpSO = DeliveryTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();

            foreach ($deliverysUpSO as $delivery) {
                $delivery->setPosition($delivery->getPosition() - 1);
                $delivery->save();
            }

            $delivery = DeliveryTable::getInstance()->findOneById($rowId);
            $delivery->setPosition($rowsList[$last]);
            $delivery->save();
            //die;
        }
        if ($currentSortOrder > $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вверх
            /* $deliverysUpSO = DeliveryTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($deliverysUpSO as $delivery) {
              $delivery->setPosition($delivery->getPosition() + 1);
              $delivery->save();
              }
              $delivery = DeliveryTable::getInstance()->findOneById($rowId);
              $delivery->setPosition($nextSortOrder);
              $delivery->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE delivery SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");
            $delivery = DeliveryTable::getInstance()->findOneById($rowId);
            $delivery->setPosition($nextSortOrder);
            $delivery->save();
        }
        if ($currentSortOrder < $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вниз
            /* $deliverysUpSO = DeliveryTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($deliverysUpSO as $delivery) {
              $delivery->setPosition($delivery->getPosition() + 1);
              $delivery->save();
              }
              $delivery = DeliveryTable::getInstance()->findOneById($rowId);
              $delivery->setPosition($nextSortOrder);
              $delivery->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE delivery SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");

            $delivery = DeliveryTable::getInstance()->findOneById($rowId);
            $delivery->setPosition($nextSortOrder);
            $delivery->save();
        }


        $pager = $this->configuration->getPager('Delivery');
        /* $pager->setQuery($this->buildQuery()->select("*")->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_delivery")->addWhere('parents_id IS NULL ')->orderBy("new_delivery ASC")->addOrderBy("(count>0) DESC")->addOrderBy("position ASC"));
         */

        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_delivery";
        $pager->setQuery($this->buildQuery()
                        ->select("*")
                        ->addSelect($selectDate)
                        ->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_delivery")
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
