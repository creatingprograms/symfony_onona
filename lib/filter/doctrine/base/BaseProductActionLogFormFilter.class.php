<?php

/**
 * ProductActionLog filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProductActionLogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'prodid'     => new sfWidgetFormFilterInput(),
      'manid'      => new sfWidgetFormFilterInput(),
      'count'      => new sfWidgetFormFilterInput(),
      'discount'   => new sfWidgetFormFilterInput(),
      'endaction'  => new sfWidgetFormFilterInput(),
      'step'       => new sfWidgetFormFilterInput(),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'prodid'     => new sfValidatorPass(array('required' => false)),
      'manid'      => new sfValidatorPass(array('required' => false)),
      'count'      => new sfValidatorPass(array('required' => false)),
      'discount'   => new sfValidatorPass(array('required' => false)),
      'endaction'  => new sfValidatorPass(array('required' => false)),
      'step'       => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('product_action_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProductActionLog';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'prodid'     => 'Text',
      'manid'      => 'Text',
      'count'      => 'Text',
      'discount'   => 'Text',
      'endaction'  => 'Text',
      'step'       => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
