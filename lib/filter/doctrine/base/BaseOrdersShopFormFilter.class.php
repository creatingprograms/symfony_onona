<?php

/**
 * OrdersShop filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseOrdersShopFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'text'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'dopid'         => new sfWidgetFormFilterInput(),
      'date'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'checknumber'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'smena'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'discountcard'  => new sfWidgetFormFilterInput(),
      'cardownername' => new sfWidgetFormFilterInput(),
      'cardowner'     => new sfWidgetFormFilterInput(),
      'price'         => new sfWidgetFormFilterInput(),
      'active'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'dateactive'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'ipactive'      => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'text'          => new sfValidatorPass(array('required' => false)),
      'dopid'         => new sfValidatorPass(array('required' => false)),
      'date'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'checknumber'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'smena'         => new sfValidatorPass(array('required' => false)),
      'discountcard'  => new sfValidatorPass(array('required' => false)),
      'cardownername' => new sfValidatorPass(array('required' => false)),
      'cardowner'     => new sfValidatorPass(array('required' => false)),
      'price'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'active'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'dateactive'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'ipactive'      => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('orders_shop_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'OrdersShop';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'text'          => 'Text',
      'dopid'         => 'Text',
      'date'          => 'Date',
      'checknumber'   => 'Number',
      'smena'         => 'Text',
      'discountcard'  => 'Text',
      'cardownername' => 'Text',
      'cardowner'     => 'Text',
      'price'         => 'Number',
      'active'        => 'Boolean',
      'dateactive'    => 'Date',
      'ipactive'      => 'Text',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
