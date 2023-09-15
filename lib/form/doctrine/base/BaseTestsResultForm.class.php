<?php

/**
 * TestsResult form base class.
 *
 * @method TestsResult getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTestsResultForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'test_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tests'), 'add_empty' => false)),
      'balls_to'   => new sfWidgetFormInputText(),
      'balls_from' => new sfWidgetFormInputText(),
      'results'    => new sfWidgetFormTextarea(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'test_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Tests'))),
      'balls_to'   => new sfValidatorInteger(),
      'balls_from' => new sfValidatorInteger(),
      'results'    => new sfValidatorString(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('tests_result[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TestsResult';
  }

}
