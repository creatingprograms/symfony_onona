<?php

/**
 * Comments form base class.
 *
 * @method Comments getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCommentsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'text'            => new sfWidgetFormTextarea(),
      'product_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'add_empty' => true)),
      'page_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true)),
      'shops_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Shops'), 'add_empty' => true)),
      'article_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Article'), 'add_empty' => true)),
      'compare_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Compare'), 'add_empty' => true)),
      'customer_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'sort_index'      => new sfWidgetFormInputText(),
      'is_public'       => new sfWidgetFormInputCheckbox(),
      'is_onmain'       => new sfWidgetFormInputCheckbox(),
      'is_novice'       => new sfWidgetFormInputCheckbox(),
      'username'        => new sfWidgetFormInputText(),
      'mail'            => new sfWidgetFormInputText(),
      'answer'          => new sfWidgetFormTextarea(),
      'rate_plus'       => new sfWidgetFormInputText(),
      'rate_minus'      => new sfWidgetFormInputText(),
      'rate_set'        => new sfWidgetFormInputText(),
      'point'           => new sfWidgetFormInputText(),
      'comment_manager' => new sfWidgetFormTextarea(),
      'manager_id'      => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'text'            => new sfValidatorString(),
      'product_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'required' => false)),
      'page_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'required' => false)),
      'shops_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Shops'), 'required' => false)),
      'article_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Article'), 'required' => false)),
      'compare_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Compare'), 'required' => false)),
      'customer_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'required' => false)),
      'sort_index'      => new sfValidatorInteger(array('required' => false)),
      'is_public'       => new sfValidatorBoolean(array('required' => false)),
      'is_onmain'       => new sfValidatorBoolean(array('required' => false)),
      'is_novice'       => new sfValidatorBoolean(array('required' => false)),
      'username'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mail'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'answer'          => new sfValidatorString(array('required' => false)),
      'rate_plus'       => new sfValidatorInteger(array('required' => false)),
      'rate_minus'      => new sfValidatorInteger(array('required' => false)),
      'rate_set'        => new sfValidatorInteger(array('required' => false)),
      'point'           => new sfValidatorInteger(array('required' => false)),
      'comment_manager' => new sfValidatorString(array('required' => false)),
      'manager_id'      => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('comments[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Comments';
  }

}
