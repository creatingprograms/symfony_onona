<?php

/**
 * Senduser form base class.
 *
 * @method Senduser getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSenduserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'product_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'add_empty' => false)),
      'name'       => new sfWidgetFormInputText(),
      'mail'       => new sfWidgetFormInputText(),
      'phone'      => new sfWidgetFormInputText(),
      'is_send'    => new sfWidgetFormInputCheckbox(),
      'is_manager' => new sfWidgetFormInputCheckbox(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'product_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Product'))),
      'name'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mail'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'phone'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_send'    => new sfValidatorBoolean(array('required' => false)),
      'is_manager' => new sfValidatorBoolean(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('senduser[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Senduser';
  }

}
