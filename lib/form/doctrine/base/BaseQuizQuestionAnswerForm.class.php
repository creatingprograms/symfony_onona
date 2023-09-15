<?php

/**
 * QuizQuestionAnswer form base class.
 *
 * @method QuizQuestionAnswer getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseQuizQuestionAnswerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'quizquestion_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('QuizQuestion'), 'add_empty' => false)),
      'answer'          => new sfWidgetFormTextarea(),
      'balls'           => new sfWidgetFormInputText(),
      'comment'         => new sfWidgetFormTextarea(),
      'is_correct'      => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'quizquestion_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('QuizQuestion'))),
      'answer'          => new sfValidatorString(),
      'balls'           => new sfValidatorInteger(),
      'comment'         => new sfValidatorString(array('required' => false)),
      'is_correct'      => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('quiz_question_answer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuizQuestionAnswer';
  }

}
