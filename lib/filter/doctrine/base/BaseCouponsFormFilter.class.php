<?php

/**
 * Coupons filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCouponsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'startaction'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'endaction'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'text'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'discount'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'discount_second'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'free_third'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'discount_sum'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'min_sum'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'conditions'              => new sfWidgetFormFilterInput(),
      'is_active'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_promo'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_important'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'express_disc_50_if_gt_3' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'startaction'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'endaction'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'text'                    => new sfValidatorPass(array('required' => false)),
      'discount'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'discount_second'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'free_third'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'discount_sum'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'min_sum'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'conditions'              => new sfValidatorPass(array('required' => false)),
      'is_active'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_promo'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_important'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'express_disc_50_if_gt_3' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('coupons_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Coupons';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'startaction'             => 'Date',
      'endaction'               => 'Date',
      'text'                    => 'Text',
      'discount'                => 'Number',
      'discount_second'         => 'Number',
      'free_third'              => 'Boolean',
      'discount_sum'            => 'Number',
      'min_sum'                 => 'Number',
      'conditions'              => 'Text',
      'is_active'               => 'Boolean',
      'is_promo'                => 'Boolean',
      'is_important'            => 'Boolean',
      'express_disc_50_if_gt_3' => 'Boolean',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
    );
  }
}
