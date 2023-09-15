<?php

/**
 * OrdersShop form base class.
 *
 * @method OrdersShop getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseOrdersShopForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'text'          => new sfWidgetFormTextarea(),
      'dopid'         => new sfWidgetFormInputText(),
      'date'          => new sfWidgetFormDateTime(),
      'checknumber'   => new sfWidgetFormInputText(),
      'smena'         => new sfWidgetFormInputText(),
      'discountcard'  => new sfWidgetFormInputText(),
      'cardownername' => new sfWidgetFormInputText(),
      'cardowner'     => new sfWidgetFormInputText(),
      'price'         => new sfWidgetFormInputText(),
      'active'        => new sfWidgetFormInputCheckbox(),
      'dateactive'    => new sfWidgetFormDateTime(),
      'ipactive'      => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'text'          => new sfValidatorString(),
      'dopid'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'date'          => new sfValidatorDateTime(array('required' => false)),
      'checknumber'   => new sfValidatorInteger(),
      'smena'         => new sfValidatorString(array('max_length' => 255)),
      'discountcard'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'cardownername' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'cardowner'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'price'         => new sfValidatorInteger(array('required' => false)),
      'active'        => new sfValidatorBoolean(array('required' => false)),
      'dateactive'    => new sfValidatorDateTime(array('required' => false)),
      'ipactive'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('orders_shop[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'OrdersShop';
  }

}
