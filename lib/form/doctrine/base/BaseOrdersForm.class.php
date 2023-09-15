<?php

/**
 * Orders form base class.
 *
 * @method Orders getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseOrdersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'text'             => new sfWidgetFormTextarea(),
      'delivery_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Delivery'), 'add_empty' => false)),
      'payment_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Payment'), 'add_empty' => false)),
      'customer_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'delivery_price'   => new sfWidgetFormInputText(),
      'firsttotalcost'   => new sfWidgetFormInputText(),
      'coupon'           => new sfWidgetFormInputText(),
      'advcake_url'      => new sfWidgetFormTextarea(),
      'advcake_trackid'  => new sfWidgetFormInputText(),
      'comments'         => new sfWidgetFormTextarea(),
      'status_detail'    => new sfWidgetFormTextarea(),
      'comment_1c'       => new sfWidgetFormTextarea(),
      'status'           => new sfWidgetFormInputText(),
      'referal'          => new sfWidgetFormInputText(),
      'source'           => new sfWidgetFormInputText(),
      'medium'           => new sfWidgetFormInputText(),
      'source_ym'        => new sfWidgetFormInputText(),
      'medium_ym'        => new sfWidgetFormInputText(),
      'region'           => new sfWidgetFormInputText(),
      'sync_status'      => new sfWidgetFormInputText(),
      'internetid'       => new sfWidgetFormInputText(),
      'prxcityads'       => new sfWidgetFormInputText(),
      'ipUser'           => new sfWidgetFormInputText(),
      'prefix'           => new sfWidgetFormInputText(),
      'referurl'         => new sfWidgetFormTextarea(),
      'samyragon'        => new sfWidgetFormInputText(),
      'manager'          => new sfWidgetFormInputText(),
      'bonuspay'         => new sfWidgetFormInputText(),
      'yaid'             => new sfWidgetFormInputText(),
      'yalaststatussend' => new sfWidgetFormTextarea(),
      'firsttext'        => new sfWidgetFormTextarea(),
      'custombonusper'   => new sfWidgetFormInputCheckbox(),
      'sms_id'           => new sfWidgetFormInputText(),
      'sms_price'        => new sfWidgetFormInputText(),
      'sms_currency'     => new sfWidgetFormInputText(),
      'yataxi_id'        => new sfWidgetFormInputText(),
      'yataxi_status'    => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'text'             => new sfValidatorString(),
      'delivery_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Delivery'))),
      'payment_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Payment'))),
      'customer_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'delivery_price'   => new sfValidatorString(array('max_length' => 255)),
      'firsttotalcost'   => new sfValidatorInteger(),
      'coupon'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'advcake_url'      => new sfValidatorString(array('required' => false)),
      'advcake_trackid'  => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'comments'         => new sfValidatorString(array('required' => false)),
      'status_detail'    => new sfValidatorString(array('required' => false)),
      'comment_1c'       => new sfValidatorString(array('required' => false)),
      'status'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'referal'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'source'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'medium'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'source_ym'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'medium_ym'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'region'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sync_status'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'internetid'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'prxcityads'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ipUser'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'prefix'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'referurl'         => new sfValidatorString(array('required' => false)),
      'samyragon'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'manager'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'bonuspay'         => new sfValidatorInteger(array('required' => false)),
      'yaid'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'yalaststatussend' => new sfValidatorString(array('required' => false)),
      'firsttext'        => new sfValidatorString(array('required' => false)),
      'custombonusper'   => new sfValidatorBoolean(array('required' => false)),
      'sms_id'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sms_price'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sms_currency'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'yataxi_id'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'yataxi_status'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('orders[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Orders';
  }

}
