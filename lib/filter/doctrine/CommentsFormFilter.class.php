<?php

/**
 * Comments filter form.
 *
 * @package    Magazin
 * @subpackage filter
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CommentsFormFilter extends BaseCommentsFormFilter
{
  public function configure()
  {
      $this->setWidget('customer_id', new sfWidgetFormInputText());
      // $this->setWidget('shops_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Shops'), 'add_empty' => true, 'method' =>'getName')));
      $this->setWidget('product_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'add_empty' => true, 'table_method' => 'getFilterList')));

        $this->setValidator('customer_id', new sfValidatorString(array('required' => false)));

  }
}
