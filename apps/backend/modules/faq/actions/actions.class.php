<?php

require_once dirname(__FILE__) . '/../lib/faqGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/faqGeneratorHelper.class.php';

/**
 * faq actions.
 *
 * @package    test
 * @subpackage faq
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class faqActions extends autoFaqActions {

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
            $faqsEnd = FaqTable::getInstance()->createQuery()->orderBy("position DESC")->Limit(1)->execute();
            $faq = FaqTable::getInstance()->findOneById($rowId);
            $faq->setPosition($faqsEnd[0]->getPosition() + 1);
            //echo $faqsEnd[0]->getPosition();
            $faq->save();


            $faqsUpSO = FaqTable::getInstance()->createQuery()->where("position >?", $currentSortOrder)->andWhere("position <=?", $rowsList[$last])->orderBy("position ASC")->execute();

            foreach ($faqsUpSO as $faq) {
                $faq->setPosition($faq->getPosition() - 1);
                $faq->save();
            }

            $faq = FaqTable::getInstance()->findOneById($rowId);
            $faq->setPosition($rowsList[$last]);
            // echo $rowsList[$last];
            $faq->save();
            //die;
        }
        if ($currentSortOrder > $nextSortOrder and ! empty($nextSortOrder)) { //	перемещение вверх
            /* $faqsUpSO = ArticleTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($faqsUpSO as $faq) {
              $faq->setPosition($faq->getPosition() + 1);
              $faq->save();
              }
              $faq = ArticleTable::getInstance()->findOneById($rowId);
              $faq->setPosition($nextSortOrder);
              $faq->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE faq SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");
            $faq = FaqTable::getInstance()->findOneById($rowId);
            $faq->setPosition($nextSortOrder);
            $faq->save();
        }
        if ($currentSortOrder < $nextSortOrder and ! empty($nextSortOrder)) { //	перемещение вниз
            /* $faqsUpSO = ArticleTable::getInstance()->createQuery()->where("position >=?", $nextSortOrder)->orderBy("position DESC")->execute();
              foreach ($faqsUpSO as $faq) {
              $faq->setPosition($faq->getPosition() + 1);
              $faq->save();
              }
              $faq = ArticleTable::getInstance()->findOneById($rowId);
              $faq->setPosition($nextSortOrder);
              $faq->save();
              die; */

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("UPDATE faq SET position = position+1  WHERE (position >= '" . $nextSortOrder . "') ORDER BY position DESC");

            $faq = FaqTable::getInstance()->findOneById($rowId);
            $faq->setPosition($nextSortOrder);
            $faq->save();
        }


        $pager = $this->configuration->getPager('Faq');
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
        $object = Doctrine::getTable('Faq')->findOneById($this->getRequestParameter('id'));

        $object->setIsPublic($object->getIsPublic() ? 0 : 1);
        $object->save();
        $this->faq = $object;
    }

    public function executeRelatedChange() {
        $object = Doctrine::getTable('Faq')->findOneById($this->getRequestParameter('id'));

        $object->setIsRelated($object->getIsRelated() ? 0 : 1);
        $object->save();
        $this->faq = $object;
    }

    public function executePositionrelatedChange() {
        $object = Doctrine::getTable('Faq')->findOneById($this->getRequestParameter('id'));

        $object->setPositionrelated($_POST['positionrelated']);
        $object->save();
        $this->faq = $object;
        return true;
    }

    protected function addSortQuery($query) {
        $query->addOrderBy('position ASC');
    }

}
