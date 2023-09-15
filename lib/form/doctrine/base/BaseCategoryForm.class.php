<?php

/**
 * Category form base class.
 *
 * @method Category getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'name'                   => new sfWidgetFormInputText(),
      'h1'                     => new sfWidgetFormInputText(),
      'content'                => new sfWidgetFormTextarea(),
      'is_open'                => new sfWidgetFormInputCheckbox(),
      'is_public'              => new sfWidgetFormInputCheckbox(),
      'adult'                  => new sfWidgetFormInputCheckbox(),
      'show_in_catalog'        => new sfWidgetFormInputCheckbox(),
      'img'                    => new sfWidgetFormInputText(),
      'icon_name'              => new sfWidgetFormInputText(),
      'icon_priority'          => new sfWidgetFormInputText(),
      'title'                  => new sfWidgetFormInputText(),
      'keywords'               => new sfWidgetFormInputText(),
      'description'            => new sfWidgetFormInputText(),
      'parents_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'lovepricename'          => new sfWidgetFormInputText(),
      'positionloveprice'      => new sfWidgetFormInputText(),
      'filters'                => new sfWidgetFormTextarea(),
      'tags'                   => new sfWidgetFormInputText(),
      'rrproductid'            => new sfWidgetFormTextarea(),
      'prodid_priority'        => new sfWidgetFormTextarea(),
      'lastupdaterrproductid'  => new sfWidgetFormInputText(),
      'filtersnew'             => new sfWidgetFormTextarea(),
      'minPrice'               => new sfWidgetFormInputText(),
      'maxPrice'               => new sfWidgetFormInputText(),
      'countProductActions'    => new sfWidgetFormInputText(),
      'views_count'            => new sfWidgetFormInputText(),
      'canonical'              => new sfWidgetFormInputText(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
      'position'               => new sfWidgetFormInputText(),
      'slug'                   => new sfWidgetFormInputText(),
      'category_products_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Product')),
      'category_catalogs_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Catalog')),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'h1'                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'                => new sfValidatorString(array('required' => false)),
      'is_open'                => new sfValidatorBoolean(array('required' => false)),
      'is_public'              => new sfValidatorBoolean(array('required' => false)),
      'adult'                  => new sfValidatorBoolean(array('required' => false)),
      'show_in_catalog'        => new sfValidatorBoolean(array('required' => false)),
      'img'                    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'icon_name'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'icon_priority'          => new sfValidatorInteger(array('required' => false)),
      'title'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'parents_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'required' => false)),
      'lovepricename'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'positionloveprice'      => new sfValidatorInteger(array('required' => false)),
      'filters'                => new sfValidatorString(array('required' => false)),
      'tags'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'rrproductid'            => new sfValidatorString(array('required' => false)),
      'prodid_priority'        => new sfValidatorString(array('required' => false)),
      'lastupdaterrproductid'  => new sfValidatorInteger(array('required' => false)),
      'filtersnew'             => new sfValidatorString(array('required' => false)),
      'minPrice'               => new sfValidatorInteger(array('required' => false)),
      'maxPrice'               => new sfValidatorInteger(array('required' => false)),
      'countProductActions'    => new sfValidatorInteger(array('required' => false)),
      'views_count'            => new sfValidatorInteger(array('required' => false)),
      'canonical'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
      'position'               => new sfValidatorInteger(array('required' => false)),
      'slug'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'category_products_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Product', 'required' => false)),
      'category_catalogs_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Catalog', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Category', 'column' => array('position'))),
        new sfValidatorDoctrineUnique(array('model' => 'Category', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Category';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['category_products_list']))
    {
      $this->setDefault('category_products_list', $this->object->CategoryProducts->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['category_catalogs_list']))
    {
      $this->setDefault('category_catalogs_list', $this->object->CategoryCatalogs->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveCategoryProductsList($con);
    $this->saveCategoryCatalogsList($con);

    parent::doSave($con);
  }

  public function saveCategoryProductsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['category_products_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->CategoryProducts->getPrimaryKeys();
    $values = $this->getValue('category_products_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('CategoryProducts', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('CategoryProducts', array_values($link));
    }
  }

  public function saveCategoryCatalogsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['category_catalogs_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->CategoryCatalogs->getPrimaryKeys();
    $values = $this->getValue('category_catalogs_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('CategoryCatalogs', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('CategoryCatalogs', array_values($link));
    }
  }

}
