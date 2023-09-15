<?php

/**
 * Product filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProductFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'h1'                     => new sfWidgetFormFilterInput(),
      'name'                   => new sfWidgetFormFilterInput(),
      'code'                   => new sfWidgetFormFilterInput(),
      'content'                => new sfWidgetFormFilterInput(),
      'newbie_description'     => new sfWidgetFormFilterInput(),
      'price'                  => new sfWidgetFormFilterInput(),
      'bonus'                  => new sfWidgetFormFilterInput(),
      'bonuspay'               => new sfWidgetFormFilterInput(),
      'old_price'              => new sfWidgetFormFilterInput(),
      'discount'               => new sfWidgetFormFilterInput(),
      'nextdiscount'           => new sfWidgetFormFilterInput(),
      'count'                  => new sfWidgetFormFilterInput(),
      'video'                  => new sfWidgetFormFilterInput(),
      'doc1'                   => new sfWidgetFormFilterInput(),
      'doc2'                   => new sfWidgetFormFilterInput(),
      'doc3'                   => new sfWidgetFormFilterInput(),
      'doc4'                   => new sfWidgetFormFilterInput(),
      'doc5'                   => new sfWidgetFormFilterInput(),
      'articles_ids'           => new sfWidgetFormFilterInput(),
      'file'                   => new sfWidgetFormFilterInput(),
      'canonical'              => new sfWidgetFormFilterInput(),
      'videoenabled'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_coupon_enabled'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_bonus_enabled'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'views_count'            => new sfWidgetFormFilterInput(),
      'votes_count'            => new sfWidgetFormFilterInput(),
      'rating'                 => new sfWidgetFormFilterInput(),
      'is_related'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_public'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_new_on_market'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_new_on_site'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_visiblechildren'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_visiblecategory'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_notadddelivery'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'set_pairs'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'set_her'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'set_him'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'for_pairs'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'for_she'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'for_her'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'bdsm'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'cosmetics'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'belie'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'other'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sync'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'title'                  => new sfWidgetFormFilterInput(),
      'externallink'           => new sfWidgetFormFilterInput(),
      'keywords'               => new sfWidgetFormFilterInput(),
      'description'            => new sfWidgetFormFilterInput(),
      'parents_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'id1c'                   => new sfWidgetFormFilterInput(),
      'generalcategory_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GeneralCategory'), 'add_empty' => true)),
      'adult'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'yamarket'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_express'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'endaction'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'startaction'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'step'                   => new sfWidgetFormChoice(array('choices' => array('' => '', '1 сутки' => '1 сутки', '2 суток' => '2 суток', '3 суток' => '3 суток', '4 суток' => '4 суток', '5 суток' => '5 суток'))),
      'positionrelated'        => new sfWidgetFormFilterInput(),
      'tags'                   => new sfWidgetFormFilterInput(),
      'pointcreate'            => new sfWidgetFormFilterInput(),
      'moder'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'moderuser'              => new sfWidgetFormFilterInput(),
      'user'                   => new sfWidgetFormFilterInput(),
      'yamarket_clothes'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'yamarket_color'         => new sfWidgetFormFilterInput(),
      'yamarket_typeimg'       => new sfWidgetFormFilterInput(),
      'yamarket_category'      => new sfWidgetFormFilterInput(),
      'yamarket_sex'           => new sfWidgetFormChoice(array('choices' => array('' => '', 'Женский' => 'Женский', 'Мужской' => 'Мужской'))),
      'yamarket_model'         => new sfWidgetFormFilterInput(),
      'yamarket_typeprefix'    => new sfWidgetFormFilterInput(),
      'stock'                  => new sfWidgetFormFilterInput(),
      'buywithitem'            => new sfWidgetFormFilterInput(),
      'countsell'              => new sfWidgetFormFilterInput(),
      'sortpriority'           => new sfWidgetFormFilterInput(),
      'barcode'                => new sfWidgetFormFilterInput(),
      'weight'                 => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'               => new sfWidgetFormFilterInput(),
      'slug'                   => new sfWidgetFormFilterInput(),
      'category_products_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Category')),
      'dop_info_products_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo')),
      'photoalbums_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Photoalbum')),
    ));

    $this->setValidators(array(
      'h1'                     => new sfValidatorPass(array('required' => false)),
      'name'                   => new sfValidatorPass(array('required' => false)),
      'code'                   => new sfValidatorPass(array('required' => false)),
      'content'                => new sfValidatorPass(array('required' => false)),
      'newbie_description'     => new sfValidatorPass(array('required' => false)),
      'price'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bonus'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bonuspay'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'old_price'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'discount'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nextdiscount'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'count'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'video'                  => new sfValidatorPass(array('required' => false)),
      'doc1'                   => new sfValidatorPass(array('required' => false)),
      'doc2'                   => new sfValidatorPass(array('required' => false)),
      'doc3'                   => new sfValidatorPass(array('required' => false)),
      'doc4'                   => new sfValidatorPass(array('required' => false)),
      'doc5'                   => new sfValidatorPass(array('required' => false)),
      'articles_ids'           => new sfValidatorPass(array('required' => false)),
      'file'                   => new sfValidatorPass(array('required' => false)),
      'canonical'              => new sfValidatorPass(array('required' => false)),
      'videoenabled'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_coupon_enabled'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_bonus_enabled'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'views_count'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'votes_count'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rating'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_related'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_public'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_new_on_market'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_new_on_site'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_visiblechildren'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_visiblecategory'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_notadddelivery'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'set_pairs'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'set_her'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'set_him'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'for_pairs'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'for_she'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'for_her'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'bdsm'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'cosmetics'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'belie'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'other'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sync'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'title'                  => new sfValidatorPass(array('required' => false)),
      'externallink'           => new sfValidatorPass(array('required' => false)),
      'keywords'               => new sfValidatorPass(array('required' => false)),
      'description'            => new sfValidatorPass(array('required' => false)),
      'parents_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Parent'), 'column' => 'id')),
      'id1c'                   => new sfValidatorPass(array('required' => false)),
      'generalcategory_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GeneralCategory'), 'column' => 'id')),
      'adult'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'yamarket'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_express'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'endaction'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'startaction'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'step'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('' => '', '1 сутки' => '1 сутки', '2 суток' => '2 суток', '3 суток' => '3 суток', '4 суток' => '4 суток', '5 суток' => '5 суток'))),
      'positionrelated'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tags'                   => new sfValidatorPass(array('required' => false)),
      'pointcreate'            => new sfValidatorPass(array('required' => false)),
      'moder'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'moderuser'              => new sfValidatorPass(array('required' => false)),
      'user'                   => new sfValidatorPass(array('required' => false)),
      'yamarket_clothes'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'yamarket_color'         => new sfValidatorPass(array('required' => false)),
      'yamarket_typeimg'       => new sfValidatorPass(array('required' => false)),
      'yamarket_category'      => new sfValidatorPass(array('required' => false)),
      'yamarket_sex'           => new sfValidatorChoice(array('required' => false, 'choices' => array('' => '', 'Женский' => 'Женский', 'Мужской' => 'Мужской'))),
      'yamarket_model'         => new sfValidatorPass(array('required' => false)),
      'yamarket_typeprefix'    => new sfValidatorPass(array('required' => false)),
      'stock'                  => new sfValidatorPass(array('required' => false)),
      'buywithitem'            => new sfValidatorPass(array('required' => false)),
      'countsell'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sortpriority'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'barcode'                => new sfValidatorPass(array('required' => false)),
      'weight'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'                   => new sfValidatorPass(array('required' => false)),
      'category_products_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Category', 'required' => false)),
      'dop_info_products_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo', 'required' => false)),
      'photoalbums_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Photoalbum', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('product_filters[%s]');

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
      ->andWhereIn('CategoryProduct.category_id', $values)
    ;
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

  public function addPhotoalbumsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.ProductPhotoalbum ProductPhotoalbum')
      ->andWhereIn('ProductPhotoalbum.photoalbum_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Product';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'h1'                     => 'Text',
      'name'                   => 'Text',
      'code'                   => 'Text',
      'content'                => 'Text',
      'newbie_description'     => 'Text',
      'price'                  => 'Number',
      'bonus'                  => 'Number',
      'bonuspay'               => 'Number',
      'old_price'              => 'Number',
      'discount'               => 'Number',
      'nextdiscount'           => 'Number',
      'count'                  => 'Number',
      'video'                  => 'Text',
      'doc1'                   => 'Text',
      'doc2'                   => 'Text',
      'doc3'                   => 'Text',
      'doc4'                   => 'Text',
      'doc5'                   => 'Text',
      'articles_ids'           => 'Text',
      'file'                   => 'Text',
      'canonical'              => 'Text',
      'videoenabled'           => 'Boolean',
      'is_coupon_enabled'      => 'Boolean',
      'is_bonus_enabled'       => 'Boolean',
      'views_count'            => 'Number',
      'votes_count'            => 'Number',
      'rating'                 => 'Number',
      'is_related'             => 'Boolean',
      'is_public'              => 'Boolean',
      'is_new_on_market'       => 'Boolean',
      'is_new_on_site'         => 'Boolean',
      'is_visiblechildren'     => 'Boolean',
      'is_visiblecategory'     => 'Boolean',
      'is_notadddelivery'      => 'Boolean',
      'set_pairs'              => 'Boolean',
      'set_her'                => 'Boolean',
      'set_him'                => 'Boolean',
      'for_pairs'              => 'Boolean',
      'for_she'                => 'Boolean',
      'for_her'                => 'Boolean',
      'bdsm'                   => 'Boolean',
      'cosmetics'              => 'Boolean',
      'belie'                  => 'Boolean',
      'other'                  => 'Boolean',
      'sync'                   => 'Boolean',
      'title'                  => 'Text',
      'externallink'           => 'Text',
      'keywords'               => 'Text',
      'description'            => 'Text',
      'parents_id'             => 'ForeignKey',
      'id1c'                   => 'Text',
      'generalcategory_id'     => 'ForeignKey',
      'adult'                  => 'Boolean',
      'yamarket'               => 'Boolean',
      'is_express'             => 'Boolean',
      'endaction'              => 'Date',
      'startaction'            => 'Date',
      'step'                   => 'Enum',
      'positionrelated'        => 'Number',
      'tags'                   => 'Text',
      'pointcreate'            => 'Text',
      'moder'                  => 'Boolean',
      'moderuser'              => 'Text',
      'user'                   => 'Text',
      'yamarket_clothes'       => 'Boolean',
      'yamarket_color'         => 'Text',
      'yamarket_typeimg'       => 'Text',
      'yamarket_category'      => 'Text',
      'yamarket_sex'           => 'Enum',
      'yamarket_model'         => 'Text',
      'yamarket_typeprefix'    => 'Text',
      'stock'                  => 'Text',
      'buywithitem'            => 'Text',
      'countsell'              => 'Number',
      'sortpriority'           => 'Number',
      'barcode'                => 'Text',
      'weight'                 => 'Number',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
      'position'               => 'Number',
      'slug'                   => 'Text',
      'category_products_list' => 'ManyKey',
      'dop_info_products_list' => 'ManyKey',
      'photoalbums_list'       => 'ManyKey',
    );
  }
}
