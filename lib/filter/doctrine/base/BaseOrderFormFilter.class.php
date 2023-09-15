<?php

/**
 * Order filter form base class.
 *
 * @package    Magazin
 * @subpackage filter
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseOrderFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'text'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'delivery_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Delivery'), 'add_empty' => true)),
      'payment_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Payment'), 'add_empty' => true)),
      'customer_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'delivery_price' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'coupon'         => new sfWidgetFormFilterInput(),
      'comments'       => new sfWidgetFormFilterInput(),
      'status'         => new sfWidgetFormChoice(array('choices' => array('' => '', 'Новый' => 'Новый', 'Оформление' => 'Оформление', 'Закрыт' => 'Закрыт'))),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'text'           => new sfValidatorPass(array('required' => false)),
      'delivery_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Delivery'), 'column' => 'id')),
      'payment_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Payment'), 'column' => 'id')),
      'customer_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'delivery_price' => new sfValidatorPass(array('required' => false)),
      'coupon'         => new sfValidatorPass(array('required' => false)),
      'comments'       => new sfValidatorPass(array('required' => false)),
      'status'         => new sfValidatorChoice(array('required' => false, 'choices' => array('Новый' => 'Новый', 'Оформление' => 'Оформление', 'Закрыт' => 'Закрыт'))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('order_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Order';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'text'           => 'Text',
      'delivery_id'    => 'ForeignKey',
      'payment_id'     => 'ForeignKey',
      'customer_id'    => 'ForeignKey',
      'delivery_price' => 'Text',
      'coupon'         => 'Text',
      'comments'       => 'Text',
      'status'         => 'Enum',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
