<?php

/**
 * experts actions.
 *
 * @package    test
 * @subpackage experts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class expertsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->expertss = Doctrine_Core::getTable('Experts')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->experts = Doctrine_Core::getTable('Experts')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->experts);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ExpertsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ExpertsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($experts = Doctrine_Core::getTable('Experts')->find(array($request->getParameter('id'))), sprintf('Object experts does not exist (%s).', $request->getParameter('id')));
    $this->form = new ExpertsForm($experts);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($experts = Doctrine_Core::getTable('Experts')->find(array($request->getParameter('id'))), sprintf('Object experts does not exist (%s).', $request->getParameter('id')));
    $this->form = new ExpertsForm($experts);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($experts = Doctrine_Core::getTable('Experts')->find(array($request->getParameter('id'))), sprintf('Object experts does not exist (%s).', $request->getParameter('id')));
    $experts->delete();

    $this->redirect('experts/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $experts = $form->save();

      $this->redirect('experts/edit?id='.$experts->getId());
    }
  }
}
