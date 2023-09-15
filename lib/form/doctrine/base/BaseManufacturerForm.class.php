<?php

/**
 * Manufacturer form base class.
 *
 * @method Manufacturer getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseManufacturerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'subid'              => new sfWidgetFormInputText(),
      'name'               => new sfWidgetFormInputText(),
      'content'            => new sfWidgetFormTextarea(),
      'is_public'          => new sfWidgetFormInputCheckbox(),
      'is_popular'         => new sfWidgetFormInputCheckbox(),
      'countactionproduct' => new sfWidgetFormInputText(),
      'maxdiscount'        => new sfWidgetFormInputText(),
      'minprice'           => new sfWidgetFormInputText(),
      'priority_sort_list' => new sfWidgetFormTextarea(),
      'title'              => new sfWidgetFormInputText(),
      'keywords'           => new sfWidgetFormInputText(),
      'description'        => new sfWidgetFormInputText(),
      'image'              => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'position'           => new sfWidgetFormInputText(),
      'slug'               => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'subid'              => new sfValidatorInteger(array('required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'            => new sfValidatorString(array('required' => false)),
      'is_public'          => new sfValidatorBoolean(array('required' => false)),
      'is_popular'         => new sfValidatorBoolean(array('required' => false)),
      'countactionproduct' => new sfValidatorInteger(array('required' => false)),
      'maxdiscount'        => new sfValidatorInteger(array('required' => false)),
      'minprice'           => new sfValidatorInteger(array('required' => false)),
      'priority_sort_list' => new sfValidatorString(array('required' => false)),
      'title'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
      'position'           => new sfValidatorInteger(array('required' => false)),
      'slug'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Manufacturer', 'column' => array('position'))),
        new sfValidatorDoctrineUnique(array('model' => 'Manufacturer', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('manufacturer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Manufacturer';
  }

}
