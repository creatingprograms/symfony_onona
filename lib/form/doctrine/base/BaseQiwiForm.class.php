<?php

/**
 * Qiwi form base class.
 *
 * @method Qiwi getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseQiwiForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'town'       => new sfWidgetFormInputText(),
      'citygroup'  => new sfWidgetFormInputText(),
      'addr'       => new sfWidgetFormInputText(),
      'latitude'   => new sfWidgetFormInputText(),
      'longitude'  => new sfWidgetFormInputText(),
      'descr'      => new sfWidgetFormTextarea(),
      'oh'         => new sfWidgetFormInputText(),
      'city_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'town'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'citygroup'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'addr'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'latitude'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'longitude'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'descr'      => new sfValidatorString(array('required' => false)),
      'oh'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('qiwi[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Qiwi';
  }

}
