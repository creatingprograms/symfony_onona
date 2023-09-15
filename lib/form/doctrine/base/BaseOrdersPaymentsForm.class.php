<?php

/**
 * OrdersPayments form base class.
 *
 * @method OrdersPayments getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseOrdersPaymentsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'order_id'        => new sfWidgetFormInputText(),
      'payment_id'      => new sfWidgetFormInputText(),
      'idempotence'     => new sfWidgetFormInputText(),
      'payment_service' => new sfWidgetFormInputText(),
      'status'          => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_id'        => new sfValidatorInteger(),
      'payment_id'      => new sfValidatorString(array('max_length' => 255)),
      'idempotence'     => new sfValidatorString(array('max_length' => 255)),
      'payment_service' => new sfValidatorString(array('max_length' => 255)),
      'status'          => new sfValidatorString(array('max_length' => 255)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'OrdersPayments', 'column' => array('order_id'))),
        new sfValidatorDoctrineUnique(array('model' => 'OrdersPayments', 'column' => array('payment_id'))),
      ))
    );

    $this->widgetSchema->setNameFormat('orders_payments[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'OrdersPayments';
  }

}
