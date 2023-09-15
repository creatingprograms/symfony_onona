<?php

/**
 * video actions.
 *
 * @package    test
 * @subpackage video
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class videoActions extends sfActions {

  public function executeIndex(sfWebRequest $request) {
    $this->pagesize=12;

    $this->pager = new sfDoctrinePager('Video', $this->pagesize);

    $query = Doctrine_Core::getTable('Video')
            ->createQuery('a')
            ->where("is_public='1'")
            ->addWhere("manager_id is null")
            ->orderBy("created_at desc")
            // ->execute()
            ;
    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    if(count($this->pager->getLinks(2000))<$request->getParameter('page', 1)) $this->forward404();
  }
  public function executeShow(sfWebRequest $request) {
    $this->pagesize=12;

    $this->pager = new sfDoctrinePager('Video', $this->pagesize);

    if ($request->getParameter('slug') == "Рекомендованное") {

      $query = Doctrine_Core::getTable('Video')
              ->createQuery('a')
              ->where("is_public='1'")
              ->addWhere("is_related='1'")
              ->addWhere("manager_id is null")
              ->orderBy("created_at desc")
              ;
    }
    elseif ($request->getParameter('slug') == "Новое") {

      $query = Doctrine_Core::getTable('Video')
              ->createQuery('a')
              ->where("is_public='1'")
              ->addWhere("UNIX_TIMESTAMP(created_at)>" . (time() - 30 * 24 * 60 * 60))
              ->addWhere("manager_id is null")
              ->orderBy("created_at desc")
              ;
    }
    else {

      $query = Doctrine_Core::getTable('Video')
              ->createQuery('a')
              ->where("is_public='1' and tag like ?", '%' . $request->getParameter('slug') . '%')
              ->addWhere("manager_id is null")
              ->orderBy("created_at desc")
              ;
    }
    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    if(count($this->pager->getLinks(2000))<$request->getParameter('page', 1)) $this->forward404();

    // if ($request->getParameter('slug') != "Новое" && !sizeof($this->videos)) $this->forward404Unless($this->video);
  }

}
