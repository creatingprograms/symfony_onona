<?php

/**
 * QuizResult form base class.
 *
 * @method QuizResult getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseQuizResultForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'quiz_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Quiz'), 'add_empty' => false)),
      'name'       => new sfWidgetFormInputText(),
      'balls_to'   => new sfWidgetFormInputText(),
      'balls_from' => new sfWidgetFormInputText(),
      'results'    => new sfWidgetFormTextarea(),
      'link'       => new sfWidgetFormInputText(),
      'link_1'     => new sfWidgetFormInputText(),
      'link_2'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'quiz_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Quiz'))),
      'name'       => new sfValidatorString(array('max_length' => 255)),
      'balls_to'   => new sfValidatorInteger(),
      'balls_from' => new sfValidatorInteger(),
      'results'    => new sfValidatorString(array('required' => false)),
      'link'       => new sfValidatorString(array('max_length' => 255)),
      'link_1'     => new sfValidatorString(array('max_length' => 255)),
      'link_2'     => new sfValidatorString(array('max_length' => 255)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('quiz_result[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuizResult';
  }

}
