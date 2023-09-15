<?php

/**
 * sliders actions.
 *
 * @package    test
 * @subpackage sliders
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
 /*
class slidersActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->sliderss = Doctrine_Core::getTable('Sliders')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->sliders = Doctrine_Core::getTable('Sliders')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->sliders);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new SlidersForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new SlidersForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($sliders = Doctrine_Core::getTable('Sliders')->find(array($request->getParameter('id'))), sprintf('Object sliders does not exist (%s).', $request->getParameter('id')));
    $this->form = new SlidersForm($sliders);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($sliders = Doctrine_Core::getTable('Sliders')->find(array($request->getParameter('id'))), sprintf('Object sliders does not exist (%s).', $request->getParameter('id')));
    $this->form = new SlidersForm($sliders);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($sliders = Doctrine_Core::getTable('Sliders')->find(array($request->getParameter('id'))), sprintf('Object sliders does not exist (%s).', $request->getParameter('id')));
    $sliders->delete();

    $this->redirect('sliders/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $sliders = $form->save();

      $this->redirect('sliders/edit?id='.$sliders->getId());
    }
  }
}
*/
class slidersActions extends autoSlidersActions
{
}
