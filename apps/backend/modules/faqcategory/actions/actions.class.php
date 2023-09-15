<?php

/**
 * faqcategory actions.
 *
 * @package    test
 * @subpackage faqcategory
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class faqcategoryActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->faqcategorys = Doctrine_Core::getTable('Faqcategory')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->faqcategory = Doctrine_Core::getTable('Faqcategory')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->faqcategory);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new FaqcategoryForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new FaqcategoryForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($faqcategory = Doctrine_Core::getTable('Faqcategory')->find(array($request->getParameter('id'))), sprintf('Object faqcategory does not exist (%s).', $request->getParameter('id')));
    $this->form = new FaqcategoryForm($faqcategory);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($faqcategory = Doctrine_Core::getTable('Faqcategory')->find(array($request->getParameter('id'))), sprintf('Object faqcategory does not exist (%s).', $request->getParameter('id')));
    $this->form = new FaqcategoryForm($faqcategory);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($faqcategory = Doctrine_Core::getTable('Faqcategory')->find(array($request->getParameter('id'))), sprintf('Object faqcategory does not exist (%s).', $request->getParameter('id')));
    $faqcategory->delete();

    $this->redirect('faqcategory/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $faqcategory = $form->save();

      $this->redirect('faqcategory/edit?id='.$faqcategory->getId());
    }
  }
}
