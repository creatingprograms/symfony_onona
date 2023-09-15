<?php

/**
 * photoalbum actions.
 *
 * @package    Magazin
 * @subpackage photoalbum
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class photoalbumActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->photoalbums = Doctrine_Core::getTable('Photoalbum')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->photoalbum = Doctrine_Core::getTable('Photoalbum')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->photoalbum);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PhotoalbumForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PhotoalbumForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($photoalbum = Doctrine_Core::getTable('Photoalbum')->find(array($request->getParameter('id'))), sprintf('Object photoalbum does not exist (%s).', $request->getParameter('id')));
    $this->form = new PhotoalbumForm($photoalbum);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($photoalbum = Doctrine_Core::getTable('Photoalbum')->find(array($request->getParameter('id'))), sprintf('Object photoalbum does not exist (%s).', $request->getParameter('id')));
    $this->form = new PhotoalbumForm($photoalbum);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($photoalbum = Doctrine_Core::getTable('Photoalbum')->find(array($request->getParameter('id'))), sprintf('Object photoalbum does not exist (%s).', $request->getParameter('id')));
    $photoalbum->delete();

    $this->redirect('photoalbum/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $photoalbum = $form->save();

      $this->redirect('photoalbum/edit?id='.$photoalbum->getId());
    }
  }
}
