<?php

/**
 * Quiz form base class.
 *
 * @method Quiz getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseQuizForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'slug'        => new sfWidgetFormInputText(),
      'content'     => new sfWidgetFormTextarea(),
      'is_public'   => new sfWidgetFormInputCheckbox(),
      'img'         => new sfWidgetFormInputText(),
      'views_count' => new sfWidgetFormInputText(),
      'votes_count' => new sfWidgetFormInputText(),
      'title'       => new sfWidgetFormInputText(),
      'keywords'    => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 255)),
      'slug'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'     => new sfValidatorString(array('required' => false)),
      'is_public'   => new sfValidatorBoolean(array('required' => false)),
      'img'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'views_count' => new sfValidatorInteger(array('required' => false)),
      'votes_count' => new sfValidatorInteger(array('required' => false)),
      'title'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Quiz', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'Quiz', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('quiz[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Quiz';
  }

}
