<?php

/**
 * Paymentdoc form base class.
 *
 * @method Paymentdoc getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePaymentdocForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'order_id'      => new sfWidgetFormInputText(),
      'amount'        => new sfWidgetFormInputText(),
      'card_no'       => new sfWidgetFormInputText(),
      'fio'           => new sfWidgetFormInputText(),
      'sync_status'   => new sfWidgetFormInputCheckbox(),
      'response_code' => new sfWidgetFormInputText(),
      'payment_type'  => new sfWidgetFormInputText(),
      'receipt_date'  => new sfWidgetFormInputText(),
      'receipt_url'   => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'order_id'      => new sfValidatorInteger(array('required' => false)),
      'amount'        => new sfValidatorInteger(array('required' => false)),
      'card_no'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'fio'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sync_status'   => new sfValidatorBoolean(array('required' => false)),
      'response_code' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'payment_type'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'receipt_date'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'receipt_url'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('paymentdoc[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Paymentdoc';
  }

}
