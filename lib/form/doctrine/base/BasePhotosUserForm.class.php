<?php

/**
 * PhotosUser form base class.
 *
 * @method PhotosUser getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePhotosUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'product_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'add_empty' => false)),
      'filename'        => new sfWidgetFormInputText(),
      'name'            => new sfWidgetFormInputText(),
      'is_public'       => new sfWidgetFormInputCheckbox(),
      'comment'         => new sfWidgetFormTextarea(),
      'point'           => new sfWidgetFormInputText(),
      'comment_manager' => new sfWidgetFormTextarea(),
      'manager_id'      => new sfWidgetFormInputText(),
      'username'        => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'position'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'required' => false)),
      'product_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Product'))),
      'filename'        => new sfValidatorString(array('max_length' => 50)),
      'name'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_public'       => new sfValidatorBoolean(array('required' => false)),
      'comment'         => new sfValidatorString(array('required' => false)),
      'point'           => new sfValidatorInteger(array('required' => false)),
      'comment_manager' => new sfValidatorString(array('required' => false)),
      'manager_id'      => new sfValidatorInteger(array('required' => false)),
      'username'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
      'position'        => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'PhotosUser', 'column' => array('filename'))),
        new sfValidatorDoctrineUnique(array('model' => 'PhotosUser', 'column' => array('position'))),
      ))
    );

    $this->widgetSchema->setNameFormat('photos_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PhotosUser';
  }

}
