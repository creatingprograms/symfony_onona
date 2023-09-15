<?php

/**
 * Orders filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseOrdersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'text'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'delivery_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Delivery'), 'add_empty' => true)),
      'payment_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Payment'), 'add_empty' => true)),
      'customer_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'delivery_price'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'firsttotalcost'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'coupon'           => new sfWidgetFormFilterInput(),
      'advcake_url'      => new sfWidgetFormFilterInput(),
      'advcake_trackid'  => new sfWidgetFormFilterInput(),
      'comments'         => new sfWidgetFormFilterInput(),
      'status_detail'    => new sfWidgetFormFilterInput(),
      'comment_1c'       => new sfWidgetFormFilterInput(),
      'status'           => new sfWidgetFormFilterInput(),
      'referal'          => new sfWidgetFormFilterInput(),
      'source'           => new sfWidgetFormFilterInput(),
      'medium'           => new sfWidgetFormFilterInput(),
      'source_ym'        => new sfWidgetFormFilterInput(),
      'medium_ym'        => new sfWidgetFormFilterInput(),
      'region'           => new sfWidgetFormFilterInput(),
      'sync_status'      => new sfWidgetFormFilterInput(),
      'internetid'       => new sfWidgetFormFilterInput(),
      'prxcityads'       => new sfWidgetFormFilterInput(),
      'ipUser'           => new sfWidgetFormFilterInput(),
      'prefix'           => new sfWidgetFormFilterInput(),
      'referurl'         => new sfWidgetFormFilterInput(),
      'samyragon'        => new sfWidgetFormFilterInput(),
      'manager'          => new sfWidgetFormFilterInput(),
      'bonuspay'         => new sfWidgetFormFilterInput(),
      'yaid'             => new sfWidgetFormFilterInput(),
      'yalaststatussend' => new sfWidgetFormFilterInput(),
      'firsttext'        => new sfWidgetFormFilterInput(),
      'custombonusper'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sms_id'           => new sfWidgetFormFilterInput(),
      'sms_price'        => new sfWidgetFormFilterInput(),
      'sms_currency'     => new sfWidgetFormFilterInput(),
      'yataxi_id'        => new sfWidgetFormFilterInput(),
      'yataxi_status'    => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'text'             => new sfValidatorPass(array('required' => false)),
      'delivery_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Delivery'), 'column' => 'id')),
      'payment_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Payment'), 'column' => 'id')),
      'customer_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'delivery_price'   => new sfValidatorPass(array('required' => false)),
      'firsttotalcost'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'coupon'           => new sfValidatorPass(array('required' => false)),
      'advcake_url'      => new sfValidatorPass(array('required' => false)),
      'advcake_trackid'  => new sfValidatorPass(array('required' => false)),
      'comments'         => new sfValidatorPass(array('required' => false)),
      'status_detail'    => new sfValidatorPass(array('required' => false)),
      'comment_1c'       => new sfValidatorPass(array('required' => false)),
      'status'           => new sfValidatorPass(array('required' => false)),
      'referal'          => new sfValidatorPass(array('required' => false)),
      'source'           => new sfValidatorPass(array('required' => false)),
      'medium'           => new sfValidatorPass(array('required' => false)),
      'source_ym'        => new sfValidatorPass(array('required' => false)),
      'medium_ym'        => new sfValidatorPass(array('required' => false)),
      'region'           => new sfValidatorPass(array('required' => false)),
      'sync_status'      => new sfValidatorPass(array('required' => false)),
      'internetid'       => new sfValidatorPass(array('required' => false)),
      'prxcityads'       => new sfValidatorPass(array('required' => false)),
      'ipUser'           => new sfValidatorPass(array('required' => false)),
      'prefix'           => new sfValidatorPass(array('required' => false)),
      'referurl'         => new sfValidatorPass(array('required' => false)),
      'samyragon'        => new sfValidatorPass(array('required' => false)),
      'manager'          => new sfValidatorPass(array('required' => false)),
      'bonuspay'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'yaid'             => new sfValidatorPass(array('required' => false)),
      'yalaststatussend' => new sfValidatorPass(array('required' => false)),
      'firsttext'        => new sfValidatorPass(array('required' => false)),
      'custombonusper'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sms_id'           => new sfValidatorPass(array('required' => false)),
      'sms_price'        => new sfValidatorPass(array('required' => false)),
      'sms_currency'     => new sfValidatorPass(array('required' => false)),
      'yataxi_id'        => new sfValidatorPass(array('required' => false)),
      'yataxi_status'    => new sfValidatorPass(array('required' => false)),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('orders_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Orders';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'text'             => 'Text',
      'delivery_id'      => 'ForeignKey',
      'payment_id'       => 'ForeignKey',
      'customer_id'      => 'ForeignKey',
      'delivery_price'   => 'Text',
      'firsttotalcost'   => 'Number',
      'coupon'           => 'Text',
      'advcake_url'      => 'Text',
      'advcake_trackid'  => 'Text',
      'comments'         => 'Text',
      'status_detail'    => 'Text',
      'comment_1c'       => 'Text',
      'status'           => 'Text',
      'referal'          => 'Text',
      'source'           => 'Text',
      'medium'           => 'Text',
      'source_ym'        => 'Text',
      'medium_ym'        => 'Text',
      'region'           => 'Text',
      'sync_status'      => 'Text',
      'internetid'       => 'Text',
      'prxcityads'       => 'Text',
      'ipUser'           => 'Text',
      'prefix'           => 'Text',
      'referurl'         => 'Text',
      'samyragon'        => 'Text',
      'manager'          => 'Text',
      'bonuspay'         => 'Number',
      'yaid'             => 'Text',
      'yalaststatussend' => 'Text',
      'firsttext'        => 'Text',
      'custombonusper'   => 'Boolean',
      'sms_id'           => 'Text',
      'sms_price'        => 'Text',
      'sms_currency'     => 'Text',
      'yataxi_id'        => 'Text',
      'yataxi_status'    => 'Text',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
