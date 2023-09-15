<?php

/**
 * ProductForBackUp form base class.
 *
 * @method ProductForBackUp getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProductForBackUpForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'name'               => new sfWidgetFormInputText(),
      'code'               => new sfWidgetFormInputText(),
      'content'            => new sfWidgetFormTextarea(),
      'price'              => new sfWidgetFormInputText(),
      'bonus'              => new sfWidgetFormInputText(),
      'old_price'          => new sfWidgetFormInputText(),
      'discount'           => new sfWidgetFormInputText(),
      'count'              => new sfWidgetFormInputText(),
      'video'              => new sfWidgetFormInputText(),
      'views_count'        => new sfWidgetFormInputText(),
      'votes_count'        => new sfWidgetFormInputText(),
      'rating'             => new sfWidgetFormInputText(),
      'is_related'         => new sfWidgetFormInputCheckbox(),
      'is_public'          => new sfWidgetFormInputCheckbox(),
      'title'              => new sfWidgetFormInputText(),
      'keywords'           => new sfWidgetFormInputText(),
      'description'        => new sfWidgetFormInputText(),
      'parents_id'         => new sfWidgetFormInputText(),
      'id1c'               => new sfWidgetFormInputText(),
      'generalcategory_id' => new sfWidgetFormInputText(),
      'adult'              => new sfWidgetFormInputCheckbox(),
      'endaction'          => new sfWidgetFormDate(),
      'step'               => new sfWidgetFormChoice(array('choices' => array('' => '', '1 сутки' => '1 сутки', '2 суток' => '2 суток', '3 суток' => '3 суток', '4 суток' => '4 суток', '5 суток' => '5 суток'))),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'position'           => new sfWidgetFormInputText(),
      'slug'               => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'code'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'            => new sfValidatorString(array('required' => false)),
      'price'              => new sfValidatorInteger(array('required' => false)),
      'bonus'              => new sfValidatorInteger(array('required' => false)),
      'old_price'          => new sfValidatorInteger(array('required' => false)),
      'discount'           => new sfValidatorInteger(array('required' => false)),
      'count'              => new sfValidatorInteger(array('required' => false)),
      'video'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'views_count'        => new sfValidatorInteger(array('required' => false)),
      'votes_count'        => new sfValidatorInteger(array('required' => false)),
      'rating'             => new sfValidatorInteger(array('required' => false)),
      'is_related'         => new sfValidatorBoolean(array('required' => false)),
      'is_public'          => new sfValidatorBoolean(array('required' => false)),
      'title'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'parents_id'         => new sfValidatorInteger(array('required' => false)),
      'id1c'               => new sfValidatorInteger(array('required' => false)),
      'generalcategory_id' => new sfValidatorInteger(array('required' => false)),
      'adult'              => new sfValidatorBoolean(array('required' => false)),
      'endaction'          => new sfValidatorDate(array('required' => false)),
      'step'               => new sfValidatorChoice(array('choices' => array(0 => '', 1 => '1 сутки', 2 => '2 суток', 3 => '3 суток', 4 => '4 суток', 5 => '5 суток'), 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
      'position'           => new sfValidatorInteger(array('required' => false)),
      'slug'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'ProductForBackUp', 'column' => array('position'))),
        new sfValidatorDoctrineUnique(array('model' => 'ProductForBackUp', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('product_for_back_up[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProductForBackUp';
  }

}
