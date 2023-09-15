<?php

/**
 * Cardsmobile form base class.
 *
 * @method Cardsmobile getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCardsmobileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'barcode'        => new sfWidgetFormInputText(),
      'user_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'is_public'      => new sfWidgetFormInputCheckbox(),
      'phone'          => new sfWidgetFormInputText(),
      'city'           => new sfWidgetFormInputText(),
      'country'        => new sfWidgetFormInputText(),
      'email'          => new sfWidgetFormInputText(),
      'sex'            => new sfWidgetFormInputText(),
      'user_name'      => new sfWidgetFormInputText(),
      'user_family'    => new sfWidgetFormInputText(),
      'user_subname'   => new sfWidgetFormInputText(),
      'is_reserved'    => new sfWidgetFormInputCheckbox(),
      'birthday'       => new sfWidgetFormDate(),
      'is_allow_email' => new sfWidgetFormInputCheckbox(),
      'is_allow_sms'   => new sfWidgetFormInputCheckbox(),
      'is_allow_call'  => new sfWidgetFormInputCheckbox(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'barcode'        => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'user_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'required' => false)),
      'is_public'      => new sfValidatorBoolean(array('required' => false)),
      'phone'          => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'city'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'country'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'email'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sex'            => new sfValidatorString(array('max_length' => 1, 'required' => false)),
      'user_name'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_family'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_subname'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_reserved'    => new sfValidatorBoolean(array('required' => false)),
      'birthday'       => new sfValidatorDate(array('required' => false)),
      'is_allow_email' => new sfValidatorBoolean(array('required' => false)),
      'is_allow_sms'   => new sfValidatorBoolean(array('required' => false)),
      'is_allow_call'  => new sfValidatorBoolean(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('cardsmobile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cardsmobile';
  }

}
