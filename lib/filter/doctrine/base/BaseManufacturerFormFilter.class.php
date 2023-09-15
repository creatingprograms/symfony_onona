<?php

/**
 * Manufacturer filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseManufacturerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'subid'              => new sfWidgetFormFilterInput(),
      'name'               => new sfWidgetFormFilterInput(),
      'content'            => new sfWidgetFormFilterInput(),
      'is_public'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_popular'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'countactionproduct' => new sfWidgetFormFilterInput(),
      'maxdiscount'        => new sfWidgetFormFilterInput(),
      'minprice'           => new sfWidgetFormFilterInput(),
      'priority_sort_list' => new sfWidgetFormFilterInput(),
      'title'              => new sfWidgetFormFilterInput(),
      'keywords'           => new sfWidgetFormFilterInput(),
      'description'        => new sfWidgetFormFilterInput(),
      'image'              => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'           => new sfWidgetFormFilterInput(),
      'slug'               => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'subid'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'name'               => new sfValidatorPass(array('required' => false)),
      'content'            => new sfValidatorPass(array('required' => false)),
      'is_public'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_popular'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'countactionproduct' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'maxdiscount'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'minprice'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'priority_sort_list' => new sfValidatorPass(array('required' => false)),
      'title'              => new sfValidatorPass(array('required' => false)),
      'keywords'           => new sfValidatorPass(array('required' => false)),
      'description'        => new sfValidatorPass(array('required' => false)),
      'image'              => new sfValidatorPass(array('required' => false)),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'               => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('manufacturer_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Manufacturer';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'subid'              => 'Number',
      'name'               => 'Text',
      'content'            => 'Text',
      'is_public'          => 'Boolean',
      'is_popular'         => 'Boolean',
      'countactionproduct' => 'Number',
      'maxdiscount'        => 'Number',
      'minprice'           => 'Number',
      'priority_sort_list' => 'Text',
      'title'              => 'Text',
      'keywords'           => 'Text',
      'description'        => 'Text',
      'image'              => 'Text',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
      'position'           => 'Number',
      'slug'               => 'Text',
    );
  }
}
