<?php

/**
 * ProductForBackUp filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProductForBackUpFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'               => new sfWidgetFormFilterInput(),
      'code'               => new sfWidgetFormFilterInput(),
      'content'            => new sfWidgetFormFilterInput(),
      'price'              => new sfWidgetFormFilterInput(),
      'bonus'              => new sfWidgetFormFilterInput(),
      'old_price'          => new sfWidgetFormFilterInput(),
      'discount'           => new sfWidgetFormFilterInput(),
      'count'              => new sfWidgetFormFilterInput(),
      'video'              => new sfWidgetFormFilterInput(),
      'views_count'        => new sfWidgetFormFilterInput(),
      'votes_count'        => new sfWidgetFormFilterInput(),
      'rating'             => new sfWidgetFormFilterInput(),
      'is_related'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_public'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'title'              => new sfWidgetFormFilterInput(),
      'keywords'           => new sfWidgetFormFilterInput(),
      'description'        => new sfWidgetFormFilterInput(),
      'parents_id'         => new sfWidgetFormFilterInput(),
      'id1c'               => new sfWidgetFormFilterInput(),
      'generalcategory_id' => new sfWidgetFormFilterInput(),
      'adult'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'endaction'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'step'               => new sfWidgetFormChoice(array('choices' => array('' => '', '1 сутки' => '1 сутки', '2 суток' => '2 суток', '3 суток' => '3 суток', '4 суток' => '4 суток', '5 суток' => '5 суток'))),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'           => new sfWidgetFormFilterInput(),
      'slug'               => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'               => new sfValidatorPass(array('required' => false)),
      'code'               => new sfValidatorPass(array('required' => false)),
      'content'            => new sfValidatorPass(array('required' => false)),
      'price'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bonus'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'old_price'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'discount'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'count'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'video'              => new sfValidatorPass(array('required' => false)),
      'views_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'votes_count'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rating'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_related'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_public'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'title'              => new sfValidatorPass(array('required' => false)),
      'keywords'           => new sfValidatorPass(array('required' => false)),
      'description'        => new sfValidatorPass(array('required' => false)),
      'parents_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'id1c'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'generalcategory_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'adult'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'endaction'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'step'               => new sfValidatorChoice(array('required' => false, 'choices' => array('' => '', '1 сутки' => '1 сутки', '2 суток' => '2 суток', '3 суток' => '3 суток', '4 суток' => '4 суток', '5 суток' => '5 суток'))),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'               => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('product_for_back_up_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProductForBackUp';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'name'               => 'Text',
      'code'               => 'Text',
      'content'            => 'Text',
      'price'              => 'Number',
      'bonus'              => 'Number',
      'old_price'          => 'Number',
      'discount'           => 'Number',
      'count'              => 'Number',
      'video'              => 'Text',
      'views_count'        => 'Number',
      'votes_count'        => 'Number',
      'rating'             => 'Number',
      'is_related'         => 'Boolean',
      'is_public'          => 'Boolean',
      'title'              => 'Text',
      'keywords'           => 'Text',
      'description'        => 'Text',
      'parents_id'         => 'Number',
      'id1c'               => 'Number',
      'generalcategory_id' => 'Number',
      'adult'              => 'Boolean',
      'endaction'          => 'Date',
      'step'               => 'Enum',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
      'position'           => 'Number',
      'slug'               => 'Text',
    );
  }
}
