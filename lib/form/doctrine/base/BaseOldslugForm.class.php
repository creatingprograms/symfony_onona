<?php

/**
 * Oldslug form base class.
 *
 * @method Oldslug getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseOldslugForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'module'     => new sfWidgetFormInputText(),
      'oldslug'    => new sfWidgetFormInputText(),
      'newslug'    => new sfWidgetFormInputText(),
      'dopid'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'module'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'oldslug'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'newslug'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'dopid'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('oldslug[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Oldslug';
  }

}
