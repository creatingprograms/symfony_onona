<?php

/**
 * DeliveryPayment form base class.
 *
 * @method DeliveryPayment getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDeliveryPaymentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'delivery_id' => new sfWidgetFormInputHidden(),
      'payment_id'  => new sfWidgetFormInputHidden(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
      'position'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'delivery_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('delivery_id')), 'empty_value' => $this->getObject()->get('delivery_id'), 'required' => false)),
      'payment_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('payment_id')), 'empty_value' => $this->getObject()->get('payment_id'), 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
      'position'    => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'DeliveryPayment', 'column' => array('position')))
    );

    $this->widgetSchema->setNameFormat('delivery_payment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DeliveryPayment';
  }

}
