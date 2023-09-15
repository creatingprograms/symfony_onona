<?php

/**
 * Catalog filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCatalogFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                   => new sfWidgetFormFilterInput(),
      'description'            => new sfWidgetFormFilterInput(),
      'canonical'              => new sfWidgetFormFilterInput(),
      'menu_name'              => new sfWidgetFormFilterInput(),
      'page_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true)),
      'is_public'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'img'                    => new sfWidgetFormFilterInput(),
      'img_top'                => new sfWidgetFormFilterInput(),
      'img_bottom'             => new sfWidgetFormFilterInput(),
      'maxaction'              => new sfWidgetFormFilterInput(),
      'page'                   => new sfWidgetFormFilterInput(),
      'class'                  => new sfWidgetFormFilterInput(),
      'tags'                   => new sfWidgetFormFilterInput(),
      'title'                  => new sfWidgetFormFilterInput(),
      'keywords'               => new sfWidgetFormFilterInput(),
      'metadescription'        => new sfWidgetFormFilterInput(),
      'prodid_priority'        => new sfWidgetFormFilterInput(),
      'prodid_bestprice'       => new sfWidgetFormFilterInput(),
      'top20_list'             => new sfWidgetFormFilterInput(),
      'sale_list'              => new sfWidgetFormFilterInput(),
      'gifts_list'             => new sfWidgetFormFilterInput(),
      'best_sales_list'        => new sfWidgetFormFilterInput(),
      'prodid_random'          => new sfWidgetFormFilterInput(),
      'brands_ids'             => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'               => new sfWidgetFormFilterInput(),
      'slug'                   => new sfWidgetFormFilterInput(),
      'category_catalogs_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Category')),
    ));

    $this->setValidators(array(
      'name'                   => new sfValidatorPass(array('required' => false)),
      'description'            => new sfValidatorPass(array('required' => false)),
      'canonical'              => new sfValidatorPass(array('required' => false)),
      'menu_name'              => new sfValidatorPass(array('required' => false)),
      'page_id'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Page'), 'column' => 'id')),
      'is_public'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'img'                    => new sfValidatorPass(array('required' => false)),
      'img_top'                => new sfValidatorPass(array('required' => false)),
      'img_bottom'             => new sfValidatorPass(array('required' => false)),
      'maxaction'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'page'                   => new sfValidatorPass(array('required' => false)),
      'class'                  => new sfValidatorPass(array('required' => false)),
      'tags'                   => new sfValidatorPass(array('required' => false)),
      'title'                  => new sfValidatorPass(array('required' => false)),
      'keywords'               => new sfValidatorPass(array('required' => false)),
      'metadescription'        => new sfValidatorPass(array('required' => false)),
      'prodid_priority'        => new sfValidatorPass(array('required' => false)),
      'prodid_bestprice'       => new sfValidatorPass(array('required' => false)),
      'top20_list'             => new sfValidatorPass(array('required' => false)),
      'sale_list'              => new sfValidatorPass(array('required' => false)),
      'gifts_list'             => new sfValidatorPass(array('required' => false)),
      'best_sales_list'        => new sfValidatorPass(array('required' => false)),
      'prodid_random'          => new sfValidatorPass(array('required' => false)),
      'brands_ids'             => new sfValidatorPass(array('required' => false)),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'                   => new sfValidatorPass(array('required' => false)),
      'category_catalogs_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Category', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('catalog_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
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
      ->andWhereIn('CategoryCatalog.category_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Catalog';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'name'                   => 'Text',
      'description'            => 'Text',
      'canonical'              => 'Text',
      'menu_name'              => 'Text',
      'page_id'                => 'ForeignKey',
      'is_public'              => 'Boolean',
      'img'                    => 'Text',
      'img_top'                => 'Text',
      'img_bottom'             => 'Text',
      'maxaction'              => 'Number',
      'page'                   => 'Text',
      'class'                  => 'Text',
      'tags'                   => 'Text',
      'title'                  => 'Text',
      'keywords'               => 'Text',
      'metadescription'        => 'Text',
      'prodid_priority'        => 'Text',
      'prodid_bestprice'       => 'Text',
      'top20_list'             => 'Text',
      'sale_list'              => 'Text',
      'gifts_list'             => 'Text',
      'best_sales_list'        => 'Text',
      'prodid_random'          => 'Text',
      'brands_ids'             => 'Text',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
      'position'               => 'Number',
      'slug'                   => 'Text',
      'category_catalogs_list' => 'ManyKey',
    );
  }
}
