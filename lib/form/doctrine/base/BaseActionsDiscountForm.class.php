<?php

/**
 * ActionsDiscount form base class.
 *
 * @method ActionsDiscount getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseActionsDiscountForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'startaction' => new sfWidgetFormDateTime(),
      'endaction'   => new sfWidgetFormDateTime(),
      'text'        => new sfWidgetFormInputText(),
      'discount'    => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'startaction' => new sfValidatorDateTime(),
      'endaction'   => new sfValidatorDateTime(),
      'text'        => new sfValidatorString(array('max_length' => 255)),
      'discount'    => new sfValidatorInteger(),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'ActionsDiscount', 'column' => array('text')))
    );

    $this->widgetSchema->setNameFormat('actions_discount[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ActionsDiscount';
  }

}
