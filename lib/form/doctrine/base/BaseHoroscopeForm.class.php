<?php

/**
 * Horoscope form base class.
 *
 * @method Horoscope getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseHoroscopeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'name'           => new sfWidgetFormInputText(),
      'date'           => new sfWidgetFormInputText(),
      'image'          => new sfWidgetFormInputText(),
      'info'           => new sfWidgetFormTextarea(),
      'month'          => new sfWidgetFormTextarea(),
      'year'           => new sfWidgetFormTextarea(),
      'characteristic' => new sfWidgetFormTextarea(),
      'compatibility'  => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
      'slug'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'           => new sfValidatorString(array('max_length' => 255)),
      'date'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image'          => new sfValidatorString(array('max_length' => 50)),
      'info'           => new sfValidatorString(array('required' => false)),
      'month'          => new sfValidatorString(array('required' => false)),
      'year'           => new sfValidatorString(array('required' => false)),
      'characteristic' => new sfValidatorString(array('required' => false)),
      'compatibility'  => new sfValidatorString(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
      'slug'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Horoscope', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'Horoscope', 'column' => array('image'))),
        new sfValidatorDoctrineUnique(array('model' => 'Horoscope', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('horoscope[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Horoscope';
  }

}
