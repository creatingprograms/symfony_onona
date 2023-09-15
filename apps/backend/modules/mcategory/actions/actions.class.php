<?php

require_once dirname(__FILE__).'/../lib/mcategoryGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/mcategoryGeneratorHelper.class.php';

/**
 * mcategory actions.
 *
 * @package    test
 * @subpackage mcategory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mcategoryActions extends autoMcategoryActions
{
    public function executeSortChange() {
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;

            $categoryEnd = CategoryTable::getInstance()->createQuery()->orderBy("positionloveprice DESC")->Limit(1)->execute();
            $category = CategoryTable::getInstance()->findOneById($rowId);
            $category->setPositionloveprice($categoryEnd[0]->getPositionloveprice() + 1);
            $category->save();


            $categoryUpSO = CategoryTable::getInstance()->createQuery()->where("positionloveprice >?", $currentSortOrder)->andWhere("positionloveprice <=?", $rowsList[$last])->orderBy("positionloveprice ASC")->execute();

            foreach ($categoryUpSO as $category) {
                $category->setPositionloveprice($category->getPositionloveprice() - 1);
                $category->save();
            }

            $category = CategoryTable::getInstance()->findOneById($rowId);
            $category->setPositionloveprice($rowsList[$last]);
            $category->save();
            //die;
        }
        if ($currentSortOrder > $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вверх
            /* $categoryUpSO = CategoryTable::getInstance()->createQuery()->where("positionloveprice >=?", $nextSortOrder)->orderBy("positionloveprice DESC")->execute();
              foreach ($categoryUpSO as $category) {
              $category->setPositionloveprice($category->getPositionloveprice() + 1);
              $category->save();
              }
              $category = CategoryTable::getInstance()->findOneById($rowId);
              $category->setPositionloveprice($nextSortOrder);
              $category->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE category SET positionloveprice = positionloveprice+1  WHERE (positionloveprice >= '" . $nextSortOrder . "') ORDER BY positionloveprice DESC");
            $category = CategoryTable::getInstance()->findOneById($rowId);
            $category->setPositionloveprice($nextSortOrder);
            $category->save();
        }
        if ($currentSortOrder < $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вниз
            /* $categoryUpSO = CategoryTable::getInstance()->createQuery()->where("positionloveprice >=?", $nextSortOrder)->orderBy("positionloveprice DESC")->execute();
              foreach ($categoryUpSO as $category) {
              $category->setPositionloveprice($category->getPositionloveprice() + 1);
              $category->save();
              }
              $category = CategoryTable::getInstance()->findOneById($rowId);
              $category->setPositionloveprice($nextSortOrder);
              $category->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE category SET positionloveprice = positionloveprice+1  WHERE (positionloveprice >= '" . $nextSortOrder . "') ORDER BY positionloveprice DESC");

            $category = CategoryTable::getInstance()->findOneById($rowId);
            $category->setPositionloveprice($nextSortOrder);
            $category->save();
        }



        $pager = new sfDoctrinePager('Category', 500);

        $pager->setQuery(Doctrine_Core::getTable('Category')
                        ->createQuery('a')
                        ->select("*")
                        ->addOrderBy("positionloveprice ASC"));


        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
    }

    protected function addSortQuery($query) {
        $query->addOrderBy('positionloveprice ASC');
    }
        public function executeLovepricenameChange() {
        $object = Doctrine::getTable('Category')->findOneById($this->getRequestParameter('id'));

        $object->setLovepricename($_POST['positionrelated']);
        $object->save();
        $this->article = $object;
        return true;
    }
}
