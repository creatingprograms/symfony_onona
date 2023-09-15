<?php

/**
 * Menu form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MenuForm extends BaseMenuForm
{
  public function configure()
  {
      unset($this['position']);
        $this->setWidget('parents_id', new sfWidgetFormDoctrineChoiceNestedSet(array('multiple' => false, 'model' => $this->getRelatedModelName('Parent'), 'method' => 'getText', 'add_empty'=> true)));
        
  }
}
