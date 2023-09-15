<?php

/**
 * Catalog form base class.
 *
 * @method Catalog getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCatalogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'name'                   => new sfWidgetFormInputText(),
      'description'            => new sfWidgetFormInputText(),
      'canonical'              => new sfWidgetFormInputText(),
      'menu_name'              => new sfWidgetFormInputText(),
      'page_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true)),
      'is_public'              => new sfWidgetFormInputCheckbox(),
      'img'                    => new sfWidgetFormInputText(),
      'img_top'                => new sfWidgetFormInputText(),
      'img_bottom'             => new sfWidgetFormInputText(),
      'maxaction'              => new sfWidgetFormInputText(),
      'page'                   => new sfWidgetFormTextarea(),
      'class'                  => new sfWidgetFormInputText(),
      'tags'                   => new sfWidgetFormInputText(),
      'title'                  => new sfWidgetFormInputText(),
      'keywords'               => new sfWidgetFormInputText(),
      'metadescription'        => new sfWidgetFormInputText(),
      'prodid_priority'        => new sfWidgetFormTextarea(),
      'prodid_bestprice'       => new sfWidgetFormTextarea(),
      'top20_list'             => new sfWidgetFormTextarea(),
      'sale_list'              => new sfWidgetFormTextarea(),
      'gifts_list'             => new sfWidgetFormTextarea(),
      'best_sales_list'        => new sfWidgetFormTextarea(),
      'prodid_random'          => new sfWidgetFormTextarea(),
      'brands_ids'             => new sfWidgetFormInputText(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
      'position'               => new sfWidgetFormInputText(),
      'slug'                   => new sfWidgetFormInputText(),
      'category_catalogs_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Category')),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'canonical'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'menu_name'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'page_id'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'required' => false)),
      'is_public'              => new sfValidatorBoolean(array('required' => false)),
      'img'                    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'img_top'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'img_bottom'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'maxaction'              => new sfValidatorInteger(array('required' => false)),
      'page'                   => new sfValidatorString(array('required' => false)),
      'class'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tags'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'title'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'metadescription'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'prodid_priority'        => new sfValidatorString(array('required' => false)),
      'prodid_bestprice'       => new sfValidatorString(array('required' => false)),
      'top20_list'             => new sfValidatorString(array('required' => false)),
      'sale_list'              => new sfValidatorString(array('required' => false)),
      'gifts_list'             => new sfValidatorString(array('required' => false)),
      'best_sales_list'        => new sfValidatorString(array('required' => false)),
      'prodid_random'          => new sfValidatorString(array('required' => false)),
      'brands_ids'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
      'position'               => new sfValidatorInteger(array('required' => false)),
      'slug'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'category_catalogs_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Category', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Catalog', 'column' => array('position'))),
        new sfValidatorDoctrineUnique(array('model' => 'Catalog', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('catalog[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Catalog';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['category_catalogs_list']))
    {
      $this->setDefault('category_catalogs_list', $this->object->CategoryCatalogs->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveCategoryCatalogsList($con);

    parent::doSave($con);
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
