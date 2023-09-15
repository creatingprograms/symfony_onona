<?php

/**
 * Video form base class.
 *
 * @method Video getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseVideoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'name'              => new sfWidgetFormInputText(),
      'slug'              => new sfWidgetFormInputText(),
      'link'              => new sfWidgetFormTextarea(),
      'videoyoutube'      => new sfWidgetFormTextarea(),
      'videoserver'       => new sfWidgetFormInputText(),
      'photo'             => new sfWidgetFormInputText(),
      'timing'            => new sfWidgetFormInputText(),
      'subname'           => new sfWidgetFormInputText(),
      'content'           => new sfWidgetFormTextarea(),
      'is_public'         => new sfWidgetFormInputCheckbox(),
      'is_publicmainpage' => new sfWidgetFormInputCheckbox(),
      'is_related'        => new sfWidgetFormInputCheckbox(),
      'tag'               => new sfWidgetFormInputText(),
      'title'             => new sfWidgetFormInputText(),
      'keywords'          => new sfWidgetFormInputText(),
      'description'       => new sfWidgetFormInputText(),
      'youtubelink'       => new sfWidgetFormInputText(),
      'point'             => new sfWidgetFormInputText(),
      'comment_manager'   => new sfWidgetFormTextarea(),
      'manager_id'        => new sfWidgetFormInputText(),
      'username'          => new sfWidgetFormInputText(),
      'product_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'add_empty' => true)),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
      'position'          => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 255)),
      'slug'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'link'              => new sfValidatorString(array('required' => false)),
      'videoyoutube'      => new sfValidatorString(array('required' => false)),
      'videoserver'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'photo'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'timing'            => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'subname'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'           => new sfValidatorString(array('required' => false)),
      'is_public'         => new sfValidatorBoolean(array('required' => false)),
      'is_publicmainpage' => new sfValidatorBoolean(array('required' => false)),
      'is_related'        => new sfValidatorBoolean(array('required' => false)),
      'tag'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'title'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'youtubelink'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'point'             => new sfValidatorInteger(array('required' => false)),
      'comment_manager'   => new sfValidatorString(array('required' => false)),
      'manager_id'        => new sfValidatorInteger(array('required' => false)),
      'username'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'product_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
      'position'          => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Video', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'Video', 'column' => array('slug'))),
        new sfValidatorDoctrineUnique(array('model' => 'Video', 'column' => array('position'))),
      ))
    );

    $this->widgetSchema->setNameFormat('video[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Video';
  }

}
