<?php

require_once dirname(__FILE__) . '/../lib/articleGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/articleGeneratorHelper.class.php';

/**
 * article actions.
 *
 * @package    test
 * @subpackage article
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articleActions extends autoArticleActions {

    protected function buildQuery() {
      
        $tableMethod = $this->configuration->getTableMethod();
        if (null === $this->filters) {
            $this->filters = $this->configuration->getFilterForm($this->getFilters());
        }

        $this->filters->setTableMethod($tableMethod);

        $query = $this->filters->buildQuery($this->getFilters());

        $this->addSortQuery($query);

        $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
        $query = $event->getReturnValue();
        /* //113629 - onona.ru - Разграничение прав
        if (sfContext::getInstance()->getUser()->hasPermission("Manager article")) {
            $query->addWhere("user=?", sfContext::getInstance()->getUser()->getGuardUser()->getId());
        }*/
        return $query;
    }

    public function executeSortChange() {
        $nextSortOrder = (isset($_POST["nextSortOrder"]) && !empty($_POST["nextSortOrder"])) ? (int) $_POST["nextSortOrder"] : null;
        $currentSortOrder = isset($_POST["currentSortOrder"]) ? (int) $_POST["currentSortOrder"] : null;
        $rowId = isset($_POST["rowId"]) ? (int) $_POST["rowId"] : 0;
        $rowsList = isset($_POST["rowsList"]) ? explode(',', $_POST["rowsList"]) : array();
        if (empty($nextSortOrder)) { //	перемещение в конец
            $last = count($rowsList) - 3;
            //echo $last;
            $articlesEnd = ArticleTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $article = ArticleTable::getInstance()->findOneById($rowId);
            $article->setPosition($articlesEnd[0]->getPosition() + 1);
            //echo $articlesEnd[0]->getPosition();
            $article->save();


            $articlesUpSO = ArticleTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();

            foreach ($articlesUpSO as $article) {
                $article->setPosition($article->getPosition() - 1);
                $article->save();
            }

            $article = ArticleTable::getInstance()->findOneById($rowId);
            $article->setPosition($rowsList[$last]);
            // echo $rowsList[$last];
            $article->save();
            //die;
        }
        if ($currentSortOrder > $nextSortOrder and ! empty($nextSortOrder)) { //	перемещение вверх
            /* $articlesUpSO = ArticleTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($articlesUpSO as $article) {
              $article->setPosition($article->getPosition() + 1);
              $article->save();
              }
              $article = ArticleTable::getInstance()->findOneById($rowId);
              $article->setPosition($nextSortOrder);
              $article->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE article SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");
            $article = ArticleTable::getInstance()->findOneById($rowId);
            $article->setPosition($nextSortOrder);
            $article->save();
        }
        if ($currentSortOrder < $nextSortOrder and ! empty($nextSortOrder)) { //	перемещение вниз
            /* $articlesUpSO = ArticleTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($articlesUpSO as $article) {
              $article->setPosition($article->getPosition() + 1);
              $article->save();
              }
              $article = ArticleTable::getInstance()->findOneById($rowId);
              $article->setPosition($nextSortOrder);
              $article->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE article SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");

            $article = ArticleTable::getInstance()->findOneById($rowId);
            $article->setPosition($nextSortOrder);
            $article->save();
        }


        $pager = $this->configuration->getPager('Article');
        /* $pager->setQuery($this->buildQuery()->select("*")->addSelect("DATEDIFF(NOW(),created_at) < " . csSettings::get('logo_new') . " as new_article")->addWhere('parents_id IS NULL ')->orderBy("new_article ASC")->addOrderBy("(count>0) DESC")->addOrderBy("position ASC"));
         */

        $pager->setQuery($this->buildQuery()
                        ->select("*")
                        ->addOrderBy("position ASC"));
        $pager->setPage($this->getPage());
        $pager->init();

        $this->pager = $pager;
        $this->sort = $this->getSort();
    }

    public function executePublicChange() {
        $object = Doctrine::getTable('Article')->findOneById($this->getRequestParameter('id'));

        $object->setIsPublic($object->getIsPublic() ? 0 : 1);
        $object->save();
        $this->article = $object;
    }

    public function executeRelatedChange() {
        $object = Doctrine::getTable('Article')->findOneById($this->getRequestParameter('id'));

        $object->setIsRelated($object->getIsRelated() ? 0 : 1);
        $object->save();
        $this->article = $object;
    }

    public function executePositionrelatedChange() {
        $object = Doctrine::getTable('Article')->findOneById($this->getRequestParameter('id'));

        $object->setPositionrelated($_POST['positionrelated']);
        $object->save();
        $this->article = $object;
        return true;
    }

    protected function addSortQuery($query) {
        $query->addOrderBy('position ASC');
    }

}
