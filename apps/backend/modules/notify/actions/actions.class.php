<?php

/**
 * notify actions.
 *
 * @package    test
 * @subpackage notify
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class notifyActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->notifys = Doctrine_Core::getTable('Notify')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->notify = Doctrine_Core::getTable('Notify')->find(array($request->getParameter('email'),
                                                     $request->getParameter('product_id')));
    $this->forward404Unless($this->notify);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new NotifyForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new NotifyForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($notify = Doctrine_Core::getTable('Notify')->find(array($request->getParameter('email'),
                               $request->getParameter('product_id'))), sprintf('Object notify does not exist (%s).', $request->getParameter('email'),
                               $request->getParameter('product_id')));
    $this->form = new NotifyForm($notify);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($notify = Doctrine_Core::getTable('Notify')->find(array($request->getParameter('email'),
                               $request->getParameter('product_id'))), sprintf('Object notify does not exist (%s).', $request->getParameter('email'),
                               $request->getParameter('product_id')));
    $this->form = new NotifyForm($notify);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($notify = Doctrine_Core::getTable('Notify')->find(array($request->getParameter('email'),
                               $request->getParameter('product_id'))), sprintf('Object notify does not exist (%s).', $request->getParameter('email'),
                               $request->getParameter('product_id')));
    $notify->delete();

    $this->redirect('notify/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notify = $form->save();

      $this->redirect('notify/edit?email='.$notify->getEmail().'&product_id='.$notify->getProductId());
    }
  }
}
