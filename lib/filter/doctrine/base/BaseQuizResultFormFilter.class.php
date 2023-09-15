<?php

/**
 * QuizResult filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseQuizResultFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'quiz_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Quiz'), 'add_empty' => true)),
      'name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'balls_to'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'balls_from' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'results'    => new sfWidgetFormFilterInput(),
      'link'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'link_1'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'link_2'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'quiz_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Quiz'), 'column' => 'id')),
      'name'       => new sfValidatorPass(array('required' => false)),
      'balls_to'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'balls_from' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'results'    => new sfValidatorPass(array('required' => false)),
      'link'       => new sfValidatorPass(array('required' => false)),
      'link_1'     => new sfValidatorPass(array('required' => false)),
      'link_2'     => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('quiz_result_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuizResult';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'quiz_id'    => 'ForeignKey',
      'name'       => 'Text',
      'balls_to'   => 'Number',
      'balls_from' => 'Number',
      'results'    => 'Text',
      'link'       => 'Text',
      'link_1'     => 'Text',
      'link_2'     => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
