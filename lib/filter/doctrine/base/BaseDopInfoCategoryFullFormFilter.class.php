<?php

/**
 * DopInfoCategoryFull filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDopInfoCategoryFullFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                   => new sfWidgetFormFilterInput(),
      'filename'               => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'               => new sfWidgetFormFilterInput(),
      'dop_info_category_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfoCategory')),
      'category_full_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo')),
    ));

    $this->setValidators(array(
      'name'                   => new sfValidatorPass(array('required' => false)),
      'filename'               => new sfValidatorPass(array('required' => false)),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'dop_info_category_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfoCategory', 'required' => false)),
      'category_full_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('dop_info_category_full_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addDopInfoCategoryListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.DopInfoCategoryFullCategory DopInfoCategoryFullCategory')
      ->andWhereIn('DopInfoCategoryFullCategory.dop_info_category_id', $values)
    ;
  }

  public function addCategoryFullListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.DopInfoCategoryFullDopInfo DopInfoCategoryFullDopInfo')
      ->andWhereIn('DopInfoCategoryFullDopInfo.dop_info_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'DopInfoCategoryFull';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'name'                   => 'Text',
      'filename'               => 'Text',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
      'position'               => 'Number',
      'dop_info_category_list' => 'ManyKey',
      'category_full_list'     => 'ManyKey',
    );
  }
}
