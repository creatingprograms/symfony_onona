<?php

/**
 * Shops form base class.
 *
 * @method Shops getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseShopsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'is_active'      => new sfWidgetFormInputCheckbox(),
      'is_onmain'      => new sfWidgetFormInputCheckbox(),
      'is_new'         => new sfWidgetFormInputCheckbox(),
      'slug'           => new sfWidgetFormInputText(),
      'Address'        => new sfWidgetFormInputText(),
      'phone'          => new sfWidgetFormInputText(),
      'Card'           => new sfWidgetFormInputCheckbox(),
      'Cash'           => new sfWidgetFormInputCheckbox(),
      'House'          => new sfWidgetFormInputText(),
      'Description'    => new sfWidgetFormTextarea(),
      'Latitude'       => new sfWidgetFormInputText(),
      'Longitude'      => new sfWidgetFormInputText(),
      'Metro'          => new sfWidgetFormInputText(),
      'Iconmetro'      => new sfWidgetFormInputText(),
      'preview_image'  => new sfWidgetFormInputText(),
      'Name'           => new sfWidgetFormInputText(),
      'OutDescription' => new sfWidgetFormTextarea(),
      'Status'         => new sfWidgetFormInputText(),
      'Street'         => new sfWidgetFormInputText(),
      'WorkTime'       => new sfWidgetFormInputText(),
      'city_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)),
      'page_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true)),
      'id1c'           => new sfWidgetFormInputText(),
      'google'         => new sfWidgetFormTextarea(),
      'yandex'         => new sfWidgetFormTextarea(),
      '2gis'           => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'is_active'      => new sfValidatorBoolean(array('required' => false)),
      'is_onmain'      => new sfValidatorBoolean(array('required' => false)),
      'is_new'         => new sfValidatorBoolean(array('required' => false)),
      'slug'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Address'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'phone'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Card'           => new sfValidatorBoolean(array('required' => false)),
      'Cash'           => new sfValidatorBoolean(array('required' => false)),
      'House'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Description'    => new sfValidatorString(array('required' => false)),
      'Latitude'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Longitude'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Metro'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Iconmetro'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'preview_image'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Name'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'OutDescription' => new sfValidatorString(array('required' => false)),
      'Status'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'Street'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'WorkTime'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'required' => false)),
      'page_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'required' => false)),
      'id1c'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'google'         => new sfValidatorString(array('required' => false)),
      'yandex'         => new sfValidatorString(array('required' => false)),
      '2gis'           => new sfValidatorString(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Shops', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('shops[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Shops';
  }

}
