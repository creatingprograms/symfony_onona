<?php

require_once dirname(__FILE__).'/../lib/articlecatalogGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/articlecatalogGeneratorHelper.class.php';

/**
 * articlecatalog actions.
 *
 * @package    test
 * @subpackage articlecatalog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articlecatalogActions extends autoArticlecatalogActions
{
    public function executeSortChange() {
        
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;

            $articlecatalogsEnd = ArticlecatalogTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $articlecatalog = ArticlecatalogTable::getInstance()->findOneById($rowId);
            $articlecatalog->setPosition($articlecatalogsEnd[0]->getPosition() + 1);
            $articlecatalog->save();


            $articlecatalogsUpSO = ArticlecatalogTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();

            foreach ($articlecatalogsUpSO as $articlecatalog) {
                $articlecatalog->setPosition($articlecatalog->getPosition() - 1);
                $articlecatalog->save();
            }

            $articlecatalog = ArticlecatalogTable::getInstance()->findOneById($rowId);
            $articlecatalog->setPosition($rowsList[$last]);
            $articlecatalog->save();
            //die;
        }
        if ($currentSortOrder > $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вверх
            /* $articlecatalogsUpSO = ArticlecatalogTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($articlecatalogsUpSO as $articlecatalog) {
              $articlecatalog->setPosition($articlecatalog->getPosition() + 1);
              $articlecatalog->save();
              }
              $articlecatalog = ArticlecatalogTable::getInstance()->findOneById($rowId);
              $articlecatalog->setPosition($nextSortOrder);
              $articlecatalog->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE articlecatalog SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");
            $articlecatalog = ArticlecatalogTable::getInstance()->findOneById($rowId);
            $articlecatalog->setPosition($nextSortOrder);
            $articlecatalog->save();
        }
        if ($currentSortOrder < $nextSortOrder and !empty($nextSortOrder)) { //	перемещение вниз
            /* $articlecatalogsUpSO = ArticlecatalogTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($articlecatalogsUpSO as $articlecatalog) {
              $articlecatalog->setPosition($articlecatalog->getPosition() + 1);
              $articlecatalog->save();
              }
              $articlecatalog = ArticlecatalogTable::getInstance()->findOneById($rowId);
              $articlecatalog->setPosition($nextSortOrder);
              $articlecatalog->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE articlecatalog SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");

            $articlecatalog = ArticlecatalogTable::getInstance()->findOneById($rowId);
            $articlecatalog->setPosition($nextSortOrder);
            $articlecatalog->save();
        }


        $pager = $this->configuration->getPager('Articlecatalog');
        /* $pager->setQuery($this->buildQuery()->select("*")->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_articlecatalog")->addWhere('parents_id IS NULL ')->orderBy("new_articlecatalog ASC")->addOrderBy("(count>0) DESC")->addOrderBy("position ASC"));
         */

        $selectDate = "DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_articlecatalog";
        $pager->setQuery($this->buildQuery()
                        ->select("*")
                        ->addSelect($selectDate)
                        ->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_articlecatalog")
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
