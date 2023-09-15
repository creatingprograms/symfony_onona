<?php

/**
 * Iml form base class.
 *
 * @method Iml getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseImlForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'Address'    => new sfWidgetFormInputText(),
      'Regioncode' => new sfWidgetFormInputText(),
      'Workmode'   => new sfWidgetFormInputText(),
      'Code'       => new sfWidgetFormInputText(),
      'Name'       => new sfWidgetFormInputText(),
      'Latitude'   => new sfWidgetFormInputText(),
      'Longitude'  => new sfWidgetFormInputText(),
      'city_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'Address'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Regioncode' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Workmode'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Code'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Name'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Latitude'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Longitude'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('iml[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Iml';
  }

}
