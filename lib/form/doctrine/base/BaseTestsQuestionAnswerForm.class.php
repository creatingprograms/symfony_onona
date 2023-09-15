<?php

/**
 * TestsQuestionAnswer form base class.
 *
 * @method TestsQuestionAnswer getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTestsQuestionAnswerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'testquestion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TestsQuestion'), 'add_empty' => false)),
      'answer'          => new sfWidgetFormTextarea(),
      'balls'           => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'testquestion_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TestsQuestion'))),
      'answer'          => new sfValidatorString(),
      'balls'           => new sfValidatorInteger(),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('tests_question_answer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TestsQuestionAnswer';
  }

}
