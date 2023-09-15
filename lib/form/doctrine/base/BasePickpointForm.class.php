<?php

/**
 * Pickpoint form base class.
 *
 * @method Pickpoint getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePickpointForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'Address'        => new sfWidgetFormInputText(),
      'Card'           => new sfWidgetFormInputCheckbox(),
      'Cash'           => new sfWidgetFormInputCheckbox(),
      'CitiId'         => new sfWidgetFormInputText(),
      'CitiName'       => new sfWidgetFormInputText(),
      'CitiOwnerId'    => new sfWidgetFormInputText(),
      'CountryName'    => new sfWidgetFormInputText(),
      'House'          => new sfWidgetFormInputText(),
      'dopid'          => new sfWidgetFormInputText(),
      'InDescription'  => new sfWidgetFormTextarea(),
      'IndoorPlace'    => new sfWidgetFormInputText(),
      'Latitude'       => new sfWidgetFormInputText(),
      'Longitude'      => new sfWidgetFormInputText(),
      'Metro'          => new sfWidgetFormInputText(),
      'Name'           => new sfWidgetFormInputText(),
      'Number'         => new sfWidgetFormInputText(),
      'OutDescription' => new sfWidgetFormTextarea(),
      'OwnerId'        => new sfWidgetFormInputText(),
      'PostCode'       => new sfWidgetFormInputText(),
      'Region'         => new sfWidgetFormInputText(),
      'Status'         => new sfWidgetFormInputText(),
      'Street'         => new sfWidgetFormInputText(),
      'TypeTitle'      => new sfWidgetFormInputText(),
      'WorkTime'       => new sfWidgetFormInputText(),
      'city_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)),
      'is_public'      => new sfWidgetFormInputCheckbox(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'Address'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Card'           => new sfValidatorBoolean(array('required' => false)),
      'Cash'           => new sfValidatorBoolean(array('required' => false)),
      'CitiId'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'CitiName'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'CitiOwnerId'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'CountryName'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'House'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'dopid'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'InDescription'  => new sfValidatorString(array('required' => false)),
      'IndoorPlace'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Latitude'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Longitude'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Metro'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Name'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Number'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'OutDescription' => new sfValidatorString(array('required' => false)),
      'OwnerId'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'PostCode'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Region'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Status'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Street'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'TypeTitle'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'WorkTime'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'required' => false)),
      'is_public'      => new sfValidatorBoolean(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('pickpoint[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Pickpoint';
  }

}
