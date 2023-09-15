<?php

require_once dirname(__FILE__).'/../lib/dopinfocategoryGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/dopinfocategoryGeneratorHelper.class.php';

/**
 * dopinfocategory actions.
 *
 * @package    Magazin
 * @subpackage dopinfocategory
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class dopinfocategoryActions extends autoDopinfocategoryActions
{

    protected function addSortQuery($query) {
        $query->addOrderBy('position ASC');
    }
    
    public function executeSortChange() {
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;

            $dopinfosEnd = DopInfoCategoryTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $dopinfo = DopInfoCategoryTable::getInstance()->findOneById($rowId);
            $dopinfo->setPosition($dopinfosEnd[0]->getPosition() + 1);
            $dopinfo->save();


            $dopinfosUpSO = DopInfoCategoryTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();
            
            foreach ($dopinfosUpSO as $dopinfo) {
                $dopinfo->setPosition($dopinfo->getPosition() - 1);
                $dopinfo->save();
            }

            $dopinfo = DopInfoCategoryTable::getInstance()->findOneById($rowId);
            $dopinfo->setPosition($rowsList[$last]);
            $dopinfo->save();
            die;
        }
        if ($currentSortOrder > $nextSortOrder) { //	перемещение вверх
            $dopinfosUpSO = DopInfoCategoryTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
            foreach ($dopinfosUpSO as $dopinfo) {
                $dopinfo->setPosition($dopinfo->getPosition() + 1);
                $dopinfo->save();
            }
            $dopinfo = DopInfoCategoryTable::getInstance()->findOneById($rowId);
            $dopinfo->setPosition($nextSortOrder);
            $dopinfo->save();
            die;
        }
        if ($currentSortOrder < $nextSortOrder) { //	перемещение вниз
            $dopinfosUpSO = DopInfoCategoryTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
            foreach ($dopinfosUpSO as $dopinfo) {
                $dopinfo->setPosition($dopinfo->getPosition() + 1);
                $dopinfo->save();
            }
            $dopinfo = DopInfoCategoryTable::getInstance()->findOneById($rowId);
            $dopinfo->setPosition($nextSortOrder);
            $dopinfo->save();
            die;
        }
    }
}
