<?php

/**
 * ProductActionLog form base class.
 *
 * @method ProductActionLog getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProductActionLogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'prodid'     => new sfWidgetFormInputText(),
      'manid'      => new sfWidgetFormInputText(),
      'count'      => new sfWidgetFormInputText(),
      'discount'   => new sfWidgetFormInputText(),
      'endaction'  => new sfWidgetFormInputText(),
      'step'       => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'prodid'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'manid'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'count'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'discount'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'endaction'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'step'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('product_action_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProductActionLog';
  }

}
