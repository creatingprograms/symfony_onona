<?php

/**
 * servicerequest actions.
 *
 * @package    test
 * @subpackage servicerequest
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class servicerequestActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->servicerequests = Doctrine_Core::getTable('Servicerequest')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->servicerequest = Doctrine_Core::getTable('Servicerequest')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->servicerequest);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ServicerequestForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new ServicerequestForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($servicerequest = Doctrine_Core::getTable('Servicerequest')->find(array($request->getParameter('id'))), sprintf('Object servicerequest does not exist (%s).', $request->getParameter('id')));
    $this->form = new ServicerequestForm($servicerequest);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($servicerequest = Doctrine_Core::getTable('Servicerequest')->find(array($request->getParameter('id'))), sprintf('Object servicerequest does not exist (%s).', $request->getParameter('id')));
    $this->form = new ServicerequestForm($servicerequest);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($servicerequest = Doctrine_Core::getTable('Servicerequest')->find(array($request->getParameter('id'))), sprintf('Object servicerequest does not exist (%s).', $request->getParameter('id')));
    $servicerequest->delete();

    $this->redirect('servicerequest/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $servicerequest = $form->save();

      $this->redirect('servicerequest/edit?id='.$servicerequest->getId());
    }
  }
}
