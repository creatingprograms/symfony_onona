<?php

/**
 * DopInfo filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDopInfoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                                 => new sfWidgetFormFilterInput(),
      'dicategory_id'                        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DopInfoCategory'), 'add_empty' => true)),
      'value'                                => new sfWidgetFormFilterInput(),
      'description'                          => new sfWidgetFormFilterInput(),
      'created_at'                           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'                             => new sfWidgetFormFilterInput(),
      'slug'                                 => new sfWidgetFormFilterInput(),
      'dop_info_products_list'               => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo')),
      'dop_info_category_full_dop_info_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfoCategoryFull')),
      'product_list'                         => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Product')),
    ));

    $this->setValidators(array(
      'name'                                 => new sfValidatorPass(array('required' => false)),
      'dicategory_id'                        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DopInfoCategory'), 'column' => 'id')),
      'value'                                => new sfValidatorPass(array('required' => false)),
      'description'                          => new sfValidatorPass(array('required' => false)),
      'created_at'                           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'                             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'                                 => new sfValidatorPass(array('required' => false)),
      'dop_info_products_list'               => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo', 'required' => false)),
      'dop_info_category_full_dop_info_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfoCategoryFull', 'required' => false)),
      'product_list'                         => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Product', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('dop_info_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addDopInfoProductsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.DopInfoProduct DopInfoProduct')
      ->andWhereIn('DopInfoProduct.dop_info_id', $values)
    ;
  }

  public function addDopInfoCategoryFullDopInfoListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('DopInfoCategoryFullDopInfo.dop_info_category_full_id', $values)
    ;
  }

  public function addProductListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.DopInfoProduct DopInfoProduct')
      ->andWhereIn('DopInfoProduct.product_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'DopInfo';
  }

  public function getFields()
  {
    return array(
      'id'                                   => 'Number',
      'name'                                 => 'Text',
      'dicategory_id'                        => 'ForeignKey',
      'value'                                => 'Text',
      'description'                          => 'Text',
      'created_at'                           => 'Date',
      'updated_at'                           => 'Date',
      'position'                             => 'Number',
      'slug'                                 => 'Text',
      'dop_info_products_list'               => 'ManyKey',
      'dop_info_category_full_dop_info_list' => 'ManyKey',
      'product_list'                         => 'ManyKey',
    );
  }
}
