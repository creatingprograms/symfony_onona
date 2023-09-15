<?php

/**
 * DopInfoProduct form base class.
 *
 * @method DopInfoProduct getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDopInfoProductForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'dop_info_id' => new sfWidgetFormInputHidden(),
      'product_id'  => new sfWidgetFormInputHidden(),
      'code'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'dop_info_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('dop_info_id')), 'empty_value' => $this->getObject()->get('dop_info_id'), 'required' => false)),
      'product_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('product_id')), 'empty_value' => $this->getObject()->get('product_id'), 'required' => false)),
      'code'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('dop_info_product[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DopInfoProduct';
  }

}
