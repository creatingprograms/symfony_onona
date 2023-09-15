<?php

require_once dirname(__FILE__) . '/../lib/articlecategoryGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/articlecategoryGeneratorHelper.class.php';

/**
 * articlecategory actions.
 *
 * @package    test
 * @subpackage articlecategory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articlecategoryActions extends autoArticlecategoryActions {

    public function executeSortChange() {
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;

            $articlecategorysEnd = ArticlecategoryTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $articlecategory = ArticlecategoryTable::getInstance()->findOneById($rowId);
            $articlecategory->setPosition($articlecategorysEnd[0]->getPosition() + 1);
            $articlecategory->save();


            $articlecategorysUpSO = ArticlecategoryTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();

            foreach ($articlecategorysUpSO as $articlecategory) {
                $articlecategory->setPosition($articlecategory->getPosition() - 1);
                $articlecategory->save();
            }

            $articlecategory = ArticlecategoryTable::getInstance()->findOneById($rowId);
            $articlecategory->setPosition($rowsList[$last]);
            $articlecategory->save();
            //die;
        }
        if ($currentSortOrder > $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вверх
            /* $articlecategorysUpSO = ArticlecategoryTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($articlecategorysUpSO as $articlecategory) {
              $articlecategory->setPosition($articlecategory->getPosition() + 1);
              $articlecategory->save();
              }
              $articlecategory = ArticlecategoryTable::getInstance()->findOneById($rowId);
              $articlecategory->setPosition($nextSortOrder);
              $articlecategory->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE articlecategory SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");
            $articlecategory = ArticlecategoryTable::getInstance()->findOneById($rowId);
            $articlecategory->setPosition($nextSortOrder);
            $articlecategory->save();
        }
        if ($currentSortOrder < $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вниз
            /* $articlecategorysUpSO = ArticlecategoryTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($articlecategorysUpSO as $articlecategory) {
              $articlecategory->setPosition($articlecategory->getPosition() + 1);
              $articlecategory->save();
              }
              $articlecategory = ArticlecategoryTable::getInstance()->findOneById($rowId);
              $articlecategory->setPosition($nextSortOrder);
              $articlecategory->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE articlecategory SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");

            $articlecategory = ArticlecategoryTable::getInstance()->findOneById($rowId);
            $articlecategory->setPosition($nextSortOrder);
            $articlecategory->save();
        }


        $pager = $this->configuration->getPager('Articlecategory');
        /* $pager->setQuery($this->buildQuery()->select("*")->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_articlecategory")->addWhere('parents_id IS NULL ')->orderBy("new_articlecategory ASC")->addOrderBy("(count>0) DESC")->addOrderBy("position ASC"));
         */

        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_articlecategory";
        $pager->setQuery($this->buildQuery()
                        ->select("*")
                        ->addSelect($selectDate)
                        ->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_articlecategory")
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
