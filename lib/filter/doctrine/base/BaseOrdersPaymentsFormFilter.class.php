<?php

/**
 * OrdersPayments filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseOrdersPaymentsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_id'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'payment_id'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'idempotence'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'payment_service' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'payment_id'      => new sfValidatorPass(array('required' => false)),
      'idempotence'     => new sfValidatorPass(array('required' => false)),
      'payment_service' => new sfValidatorPass(array('required' => false)),
      'status'          => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('orders_payments_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'OrdersPayments';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'order_id'        => 'Number',
      'payment_id'      => 'Text',
      'idempotence'     => 'Text',
      'payment_service' => 'Text',
      'status'          => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
