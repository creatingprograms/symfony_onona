<?php

/**
 * Oprosnik filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseOprosnikFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'orderid'    => new sfWidgetFormFilterInput(),
      'dataans'    => new sfWidgetFormFilterInput(),
      'rating'     => new sfWidgetFormFilterInput(),
      'shop'       => new sfWidgetFormChoice(array('choices' => array('' => '', 'Магазин' => 'Магазин', 'Интернет магазин' => 'Интернет магазин'))),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'orderid'    => new sfValidatorPass(array('required' => false)),
      'dataans'    => new sfValidatorPass(array('required' => false)),
      'rating'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'shop'       => new sfValidatorChoice(array('required' => false, 'choices' => array('Магазин' => 'Магазин', 'Интернет магазин' => 'Интернет магазин'))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('oprosnik_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Oprosnik';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'orderid'    => 'Text',
      'dataans'    => 'Text',
      'rating'     => 'Number',
      'shop'       => 'Enum',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
