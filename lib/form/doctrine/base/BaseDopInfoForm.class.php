<?php

/**
 * DopInfo form base class.
 *
 * @method DopInfo getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDopInfoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                                   => new sfWidgetFormInputHidden(),
      'name'                                 => new sfWidgetFormInputText(),
      'dicategory_id'                        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DopInfoCategory'), 'add_empty' => true)),
      'value'                                => new sfWidgetFormInputText(),
      'description'                          => new sfWidgetFormTextarea(),
      'created_at'                           => new sfWidgetFormDateTime(),
      'updated_at'                           => new sfWidgetFormDateTime(),
      'position'                             => new sfWidgetFormInputText(),
      'slug'                                 => new sfWidgetFormInputText(),
      'dop_info_products_list'               => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo')),
      'dop_info_category_full_dop_info_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfoCategoryFull')),
      'product_list'                         => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Product')),
    ));

    $this->setValidators(array(
      'id'                                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'dicategory_id'                        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DopInfoCategory'), 'required' => false)),
      'value'                                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'                          => new sfValidatorString(array('required' => false)),
      'created_at'                           => new sfValidatorDateTime(),
      'updated_at'                           => new sfValidatorDateTime(),
      'position'                             => new sfValidatorInteger(array('required' => false)),
      'slug'                                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'dop_info_products_list'               => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo', 'required' => false)),
      'dop_info_category_full_dop_info_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfoCategoryFull', 'required' => false)),
      'product_list'                         => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Product', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'DopInfo', 'column' => array('position')))
    );

    $this->widgetSchema->setNameFormat('dop_info[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DopInfo';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['dop_info_products_list']))
    {
      $this->setDefault('dop_info_products_list', $this->object->DopInfoProducts->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['dop_info_category_full_dop_info_list']))
    {
      $this->setDefault('dop_info_category_full_dop_info_list', $this->object->DopInfoCategoryFullDopInfo->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['product_list']))
    {
      $this->setDefault('product_list', $this->object->Product->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveDopInfoProductsList($con);
    $this->saveDopInfoCategoryFullDopInfoList($con);
    $this->saveProductList($con);

    parent::doSave($con);
  }

  public function saveDopInfoProductsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['dop_info_products_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->DopInfoProducts->getPrimaryKeys();
    $values = $this->getValue('dop_info_products_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('DopInfoProducts', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('DopInfoProducts', array_values($link));
    }
  }

  public function saveDopInfoCategoryFullDopInfoList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['dop_info_category_full_dop_info_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->DopInfoCategoryFullDopInfo->getPrimaryKeys();
    $values = $this->getValue('dop_info_category_full_dop_info_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('DopInfoCategoryFullDopInfo', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('DopInfoCategoryFullDopInfo', array_values($link));
    }
  }

  public function saveProductList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['product_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Product->getPrimaryKeys();
    $values = $this->getValue('product_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Product', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Product', array_values($link));
    }
  }

}
