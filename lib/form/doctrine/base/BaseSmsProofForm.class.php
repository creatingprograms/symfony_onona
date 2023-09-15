<?php

/**
 * SmsProof form base class.
 *
 * @method SmsProof getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSmsProofForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'phone'      => new sfWidgetFormInputHidden(),
      'text'       => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'phone'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('phone')), 'empty_value' => $this->getObject()->get('phone'), 'required' => false)),
      'text'       => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('sms_proof[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SmsProof';
  }

}
