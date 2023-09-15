<?php

require_once dirname(__FILE__).'/../lib/mproductGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/mproductGeneratorHelper.class.php';

/**
 * mproduct actions.
 *
 * @package    test
 * @subpackage mproduct
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mproductActions extends autoMproductActions
{

    protected function addSortQuery($query) {
        $query->addOrderBy('position ASC');
    }
    

  public function executeEdit(sfWebRequest $request)
  {
    $this->product = $this->getRoute()->getObject();
    $this->form = new MProductForm($this->product);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->product = $this->getRoute()->getObject();
    $this->form = new MProductForm($this->product);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }
}
