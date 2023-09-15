<?php

/**
 * Category filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCategoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                   => new sfWidgetFormFilterInput(),
      'h1'                     => new sfWidgetFormFilterInput(),
      'content'                => new sfWidgetFormFilterInput(),
      'is_open'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_public'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'adult'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'show_in_catalog'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'img'                    => new sfWidgetFormFilterInput(),
      'icon_name'              => new sfWidgetFormFilterInput(),
      'icon_priority'          => new sfWidgetFormFilterInput(),
      'title'                  => new sfWidgetFormFilterInput(),
      'keywords'               => new sfWidgetFormFilterInput(),
      'description'            => new sfWidgetFormFilterInput(),
      'parents_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'lovepricename'          => new sfWidgetFormFilterInput(),
      'positionloveprice'      => new sfWidgetFormFilterInput(),
      'filters'                => new sfWidgetFormFilterInput(),
      'tags'                   => new sfWidgetFormFilterInput(),
      'rrproductid'            => new sfWidgetFormFilterInput(),
      'prodid_priority'        => new sfWidgetFormFilterInput(),
      'lastupdaterrproductid'  => new sfWidgetFormFilterInput(),
      'filtersnew'             => new sfWidgetFormFilterInput(),
      'minPrice'               => new sfWidgetFormFilterInput(),
      'maxPrice'               => new sfWidgetFormFilterInput(),
      'countProductActions'    => new sfWidgetFormFilterInput(),
      'views_count'            => new sfWidgetFormFilterInput(),
      'canonical'              => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'               => new sfWidgetFormFilterInput(),
      'slug'                   => new sfWidgetFormFilterInput(),
      'category_products_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Product')),
      'category_catalogs_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Catalog')),
    ));

    $this->setValidators(array(
      'name'                   => new sfValidatorPass(array('required' => false)),
      'h1'                     => new sfValidatorPass(array('required' => false)),
      'content'                => new sfValidatorPass(array('required' => false)),
      'is_open'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_public'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'adult'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'show_in_catalog'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'img'                    => new sfValidatorPass(array('required' => false)),
      'icon_name'              => new sfValidatorPass(array('required' => false)),
      'icon_priority'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'                  => new sfValidatorPass(array('required' => false)),
      'keywords'               => new sfValidatorPass(array('required' => false)),
      'description'            => new sfValidatorPass(array('required' => false)),
      'parents_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Parent'), 'column' => 'id')),
      'lovepricename'          => new sfValidatorPass(array('required' => false)),
      'positionloveprice'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'filters'                => new sfValidatorPass(array('required' => false)),
      'tags'                   => new sfValidatorPass(array('required' => false)),
      'rrproductid'            => new sfValidatorPass(array('required' => false)),
      'prodid_priority'        => new sfValidatorPass(array('required' => false)),
      'lastupdaterrproductid'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'filtersnew'             => new sfValidatorPass(array('required' => false)),
      'minPrice'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'maxPrice'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'countProductActions'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'views_count'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'canonical'              => new sfValidatorPass(array('required' => false)),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'                   => new sfValidatorPass(array('required' => false)),
      'category_products_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Product', 'required' => false)),
      'category_catalogs_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Catalog', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('category_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addCategoryProductsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.CategoryProduct CategoryProduct')
      ->andWhereIn('CategoryProduct.product_id', $values)
    ;
  }

  public function addCategoryCatalogsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.CategoryCatalog CategoryCatalog')
      ->andWhereIn('CategoryCatalog.catalog_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Category';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'name'                   => 'Text',
      'h1'                     => 'Text',
      'content'                => 'Text',
      'is_open'                => 'Boolean',
      'is_public'              => 'Boolean',
      'adult'                  => 'Boolean',
      'show_in_catalog'        => 'Boolean',
      'img'                    => 'Text',
      'icon_name'              => 'Text',
      'icon_priority'          => 'Number',
      'title'                  => 'Text',
      'keywords'               => 'Text',
      'description'            => 'Text',
      'parents_id'             => 'ForeignKey',
      'lovepricename'          => 'Text',
      'positionloveprice'      => 'Number',
      'filters'                => 'Text',
      'tags'                   => 'Text',
      'rrproductid'            => 'Text',
      'prodid_priority'        => 'Text',
      'lastupdaterrproductid'  => 'Number',
      'filtersnew'             => 'Text',
      'minPrice'               => 'Number',
      'maxPrice'               => 'Number',
      'countProductActions'    => 'Number',
      'views_count'            => 'Number',
      'canonical'              => 'Text',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
      'position'               => 'Number',
      'slug'                   => 'Text',
      'category_products_list' => 'ManyKey',
      'category_catalogs_list' => 'ManyKey',
    );
  }
}
