<?php

/**
 * Compare form base class.
 *
 * @method Compare getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCompareForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'products'     => new sfWidgetFormTextarea(),
      'productsinfo' => new sfWidgetFormTextarea(),
      'rule'         => new sfWidgetFormChoice(array('choices' => array('Личный' => 'Личный', 'Доступен со ссылкой' => 'Доступен со ссылкой', 'Публичный' => 'Публичный'))),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'products'     => new sfValidatorString(array('required' => false)),
      'productsinfo' => new sfValidatorString(array('required' => false)),
      'rule'         => new sfValidatorChoice(array('choices' => array(0 => 'Личный', 1 => 'Доступен со ссылкой', 2 => 'Публичный'), 'required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('compare[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Compare';
  }

}
