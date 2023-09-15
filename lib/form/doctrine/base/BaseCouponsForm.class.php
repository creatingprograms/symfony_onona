<?php

/**
 * Coupons form base class.
 *
 * @method Coupons getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCouponsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'startaction'             => new sfWidgetFormDateTime(),
      'endaction'               => new sfWidgetFormDateTime(),
      'text'                    => new sfWidgetFormInputText(),
      'discount'                => new sfWidgetFormInputText(),
      'discount_second'         => new sfWidgetFormInputText(),
      'free_third'              => new sfWidgetFormInputCheckbox(),
      'discount_sum'            => new sfWidgetFormInputText(),
      'min_sum'                 => new sfWidgetFormInputText(),
      'conditions'              => new sfWidgetFormTextarea(),
      'is_active'               => new sfWidgetFormInputCheckbox(),
      'is_promo'                => new sfWidgetFormInputCheckbox(),
      'is_important'            => new sfWidgetFormInputCheckbox(),
      'express_disc_50_if_gt_3' => new sfWidgetFormInputCheckbox(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'startaction'             => new sfValidatorDateTime(),
      'endaction'               => new sfValidatorDateTime(),
      'text'                    => new sfValidatorString(array('max_length' => 255)),
      'discount'                => new sfValidatorInteger(array('required' => false)),
      'discount_second'         => new sfValidatorInteger(array('required' => false)),
      'free_third'              => new sfValidatorBoolean(array('required' => false)),
      'discount_sum'            => new sfValidatorInteger(array('required' => false)),
      'min_sum'                 => new sfValidatorInteger(array('required' => false)),
      'conditions'              => new sfValidatorString(array('required' => false)),
      'is_active'               => new sfValidatorBoolean(array('required' => false)),
      'is_promo'                => new sfValidatorBoolean(array('required' => false)),
      'is_important'            => new sfValidatorBoolean(array('required' => false)),
      'express_disc_50_if_gt_3' => new sfValidatorBoolean(array('required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Coupons', 'column' => array('text')))
    );

    $this->widgetSchema->setNameFormat('coupons[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Coupons';
  }

}
