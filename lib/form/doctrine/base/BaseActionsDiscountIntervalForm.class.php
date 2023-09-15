<?php

/**
 * ActionsDiscountInterval form base class.
 *
 * @method ActionsDiscountInterval getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseActionsDiscountIntervalForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'actionsdiscount_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ActionsDiscount'), 'add_empty' => false)),
      'start'              => new sfWidgetFormInputText(),
      'end'                => new sfWidgetFormInputText(),
      'discount'           => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'actionsdiscount_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ActionsDiscount'))),
      'start'              => new sfValidatorInteger(),
      'end'                => new sfValidatorInteger(),
      'discount'           => new sfValidatorInteger(),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('actions_discount_interval[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ActionsDiscountInterval';
  }

}
