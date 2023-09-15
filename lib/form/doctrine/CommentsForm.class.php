<?php

/**
 * Comments form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CommentsForm extends BaseCommentsForm
{
  public function configure()
  {
      unset($this['customer_id']);
      //$this->setWidget('customer_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'method' => 'getSelectView')));
      // $this->setWidget('shops_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Shops'), 'add_empty' => true, 'method' =>'getName')));
      $this->setWidget('shops_id', new sfWidgetFormInputText());
      $this->setWidget('article_id', new sfWidgetFormInputText());
      $this->setWidget('compare_id', new sfWidgetFormInputText());
      $this->setWidget('product_id', new sfWidgetFormInputText());
      // $this->setWidget('customer_id', new sfWidgetFormInputText());
      $this->setWidget('page_id', new sfWidgetFormInputText());
  }
}
