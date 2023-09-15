<?php

/**
 * Order form base class.
 *
 * @method Order getObject() Returns the current form's model object
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseOrderForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'text'           => new sfWidgetFormTextarea(),
      'delivery_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Delivery'), 'add_empty' => false)),
      'payment_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Payment'), 'add_empty' => false)),
      'customer_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'delivery_price' => new sfWidgetFormInputText(),
      'coupon'         => new sfWidgetFormInputText(),
      'comments'       => new sfWidgetFormTextarea(),
      'status'         => new sfWidgetFormChoice(array('choices' => array('Новый' => 'Новый', 'Оформление' => 'Оформление', 'Закрыт' => 'Закрыт'))),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'text'           => new sfValidatorString(),
      'delivery_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Delivery'))),
      'payment_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Payment'))),
      'customer_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'delivery_price' => new sfValidatorString(array('max_length' => 255)),
      'coupon'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'comments'       => new sfValidatorString(array('required' => false)),
      'status'         => new sfValidatorChoice(array('choices' => array(0 => 'Новый', 1 => 'Оформление', 2 => 'Закрыт'), 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('order[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Order';
  }

}
