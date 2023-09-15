<?php

/**
 * tests actions.
 *
 * @package    test
 * @subpackage tests
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class testsActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {

        $this->pager = new sfDoctrinePager('tests', 10);

        $query=Doctrine_Core::getTable('Tests')->createQuery('a')->where("is_public = 1");
        
         if (sfContext::getInstance()->getRequest()->getCookie('sortOrder') != "") {
            //$this->sortOrder = sfContext::getInstance()->getRequest()->getCookie('sortOrder');
        }
        if ($request->getParameter('sortOrder') != "") {
            sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
            $this->sortOrder = $request->getParameter('sortOrder');
        }
        if (sfContext::getInstance()->getRequest()->getCookie('direction') != "") {
            //$this->direction = sfContext::getInstance()->getRequest()->getCookie('direction');
        }
        if ($request->getParameter('direction') != "") {
            sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
            $this->direction = $request->getParameter('direction');
        }

        if ($this->sortOrder == "date") {
            if ($this->direction == "asc") {
                $query->addOrderBy("created_at asc");
            } else {
                $query->addOrderBy("created_at desc");
            }
        }

        if ($this->sortOrder == "rating") {
            if ($this->direction == "asc") {
                $query->addOrderBy("writing asc");
            } else {
                $query->addOrderBy("writing desc");
            }
        }
        
        
        
        
        
        
        
        
        
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        /* $this->testss = Doctrine_Core::getTable('Tests')
          ->createQuery('a')
          ->execute(); */
    }

    public function executeShow(sfWebRequest $request) {
        $this->test = Doctrine_Core::getTable('Tests')->findOneBySlug(array($request->getParameter('slug')));
        $this->forward404Unless($this->test);
        if ($request->getParameter('page', 1)==1)
            $this->getUser()->setAttribute('balls_test_' . $this->test->getId(), 0);

        $this->pager = new sfDoctrinePager('tests', 5);

        $this->pager->setQuery(Doctrine_Core::getTable('TestsQuestion')->createQuery('a')->where("test_id = ?", $this->test->getId())->orderBy("number ASC"));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        $ballsPrevPage = 0;
        if (isset($_POST['answer']))
            foreach ($_POST['answer'] as $answer) {
                $ballsPrevPage = $ballsPrevPage + $answer;
            }

        $this->ballsTest = $this->getUser()->getAttribute('balls_test_' . $this->test->getId());
        $this->ballsTest = $this->ballsTest + $ballsPrevPage;

        if ($request->getParameter('page', 1) > count($this->pager->getLinks(20))) {

            $this->result = TestsResultTable::getInstance()->createQuery()->where("test_id = ?", $this->test->getId())->addWhere("balls_to<=?", $this->ballsTest)->addWhere("balls_from>=?", $this->ballsTest)->fetchOne();

            if (!$this->getUser()->getAttribute('test_writing_' . $this->test->getId())) {
                $this->test->setWriting($this->test->getWriting() + 1);
                $this->test->save();
                $this->getUser()->setAttribute('test_writing_' . $this->test->getId(), true);
            }
            $this->allTests = Doctrine_Core::getTable('Tests')->createQuery('a')->where("is_public = 1")->execute();
        } else {
            $this->getUser()->setAttribute('balls_test_' . $this->test->getId(), $this->ballsTest);
        }
    }

    public function executeRate(sfWebRequest $request) {

        $test = Doctrine_Core::getTable('Tests')->findOneById(array($request->getParameter('testId')));
        $test->setRating($test->getRating() + $request->getParameter('value'));
        $test->setVotesCount($test->getVotesCount() + 1);
        $test->save();
        $this->getResponse()->setCookie("ratete_" . $test->getId(), 1, time() + 60 * 60 * 24 * 365, '/');
        $this->test = $test;
    }

}
