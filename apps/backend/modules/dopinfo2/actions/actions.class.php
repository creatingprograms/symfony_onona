<?php

/**
 * dopinfo2 actions.
 *
 * @package    test
 * @subpackage dopinfo2
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class dopinfo2Actions extends sfActions
{
  public function executeIndex(sfWebRequest $request) {
    $pageCount=50;
    $pageNum=($request->getParameter('page') ? $request->getParameter('page') : 1);

    $dopinfos = Doctrine_Core::getTable('Dopinfo')
      ->createQuery('a')
    ;
    if ($request->getParameter('dicategory'))
      $dopinfos = $dopinfos -> where('dicategory_id = '.$request->getParameter('dicategory'));

    $dopinfoCategories = Doctrine_Core::getTable('DopInfoCategory')
      ->createQuery('b')
      ->execute();
    foreach ($dopinfoCategories as $cat) {
      $dopinfoCats[$cat->getId()]=$cat->getName();
    }

    $count=$dopinfos->count();
    $this->dopinfos = $dopinfos->offset($pageCount*($pageNum-1))->limit($pageCount)->execute();
    $this->dopinfoCats=$dopinfoCats;
    $this->count=$count;
    $this->pageNum=$pageNum;
    $this->filter=$request->getParameter('dicategory') ? '&dicategory='.$request->getParameter('dicategory') : '';
    $this->pagesCount=ceil($count/$pageCount);
  }

  public function executeShow(sfWebRequest $request) {
    $this->dopinfo = Doctrine_Core::getTable('Dopinfo')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->dopinfo);
  }

  public function executeNew(sfWebRequest $request) {
    $this->form = new DopinfoForm();
  }

  public function executeCreate(sfWebRequest $request) {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new DopinfoForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request) {
    $this->forward404Unless($dopinfo = Doctrine_Core::getTable('Dopinfo')->find(array($request->getParameter('id'))), sprintf('Object dopinfo does not exist (%s).', $request->getParameter('id')));
    $this->form = new DopinfoForm($dopinfo);
  }

  public function executeUpdate(sfWebRequest $request) {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($dopinfo = Doctrine_Core::getTable('Dopinfo')->find(array($request->getParameter('id'))), sprintf('Object dopinfo does not exist (%s).', $request->getParameter('id')));
    $this->form = new DopinfoForm($dopinfo);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request) {
    $request->checkCSRFProtection();

    $this->forward404Unless($dopinfo = Doctrine_Core::getTable('Dopinfo')->find(array($request->getParameter('id'))), sprintf('Object dopinfo does not exist (%s).', $request->getParameter('id')));
    $dopinfo->delete();

    $this->redirect('dopinfo2/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form) {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $dopinfo = $form->save();

      $this->redirect('dopinfo2/edit?id='.$dopinfo->getId());
    }
  }
}
