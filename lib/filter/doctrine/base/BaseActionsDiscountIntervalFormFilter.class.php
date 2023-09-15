<?php

/**
 * ActionsDiscountInterval filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseActionsDiscountIntervalFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'actionsdiscount_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ActionsDiscount'), 'add_empty' => true)),
      'start'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'discount'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'actionsdiscount_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ActionsDiscount'), 'column' => 'id')),
      'start'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'discount'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('actions_discount_interval_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ActionsDiscountInterval';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'actionsdiscount_id' => 'ForeignKey',
      'start'              => 'Number',
      'end'                => 'Number',
      'discount'           => 'Number',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
