<?php

/**
 * Paymentdoc filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePaymentdocFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_id'      => new sfWidgetFormFilterInput(),
      'amount'        => new sfWidgetFormFilterInput(),
      'card_no'       => new sfWidgetFormFilterInput(),
      'fio'           => new sfWidgetFormFilterInput(),
      'sync_status'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'response_code' => new sfWidgetFormFilterInput(),
      'payment_type'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'receipt_date'  => new sfWidgetFormFilterInput(),
      'receipt_url'   => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'order_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'amount'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'card_no'       => new sfValidatorPass(array('required' => false)),
      'fio'           => new sfValidatorPass(array('required' => false)),
      'sync_status'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'response_code' => new sfValidatorPass(array('required' => false)),
      'payment_type'  => new sfValidatorPass(array('required' => false)),
      'receipt_date'  => new sfValidatorPass(array('required' => false)),
      'receipt_url'   => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('paymentdoc_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Paymentdoc';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'order_id'      => 'Number',
      'amount'        => 'Number',
      'card_no'       => 'Text',
      'fio'           => 'Text',
      'sync_status'   => 'Boolean',
      'response_code' => 'Text',
      'payment_type'  => 'Text',
      'receipt_date'  => 'Text',
      'receipt_url'   => 'Text',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
