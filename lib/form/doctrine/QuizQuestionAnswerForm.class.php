<?php

/**
 * QuizQuestionAnswer form.
 *
 * @package    symfony
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class QuizQuestionAnswerForm extends BaseQuizQuestionAnswerForm
{
  public function configure() {
    // unset($this['quizquestion_id']);
    /*

    if ($this->object->exists()) {
        $this->widgetSchema['delete'] = new sfWidgetFormInputCheckbox();
        $this->validatorSchema['delete'] = new sfValidatorPass();
    }
    $this->validatorSchema['answer']->setOption('required', false);
    $this->validatorSchema['balls']->setOption('required', false);


    $this->widgetSchema['answer']->setLabel('Ответ');
    $this->widgetSchema['balls']->setLabel('Баллы за ответ');
    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', false);*/
  }
}
