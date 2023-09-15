<?php

/**
 * DopInfoCategoryFull form base class.
 *
 * @method DopInfoCategoryFull getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDopInfoCategoryFullForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'name'                   => new sfWidgetFormInputText(),
      'filename'               => new sfWidgetFormInputText(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
      'position'               => new sfWidgetFormInputText(),
      'dop_info_category_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfoCategory')),
      'category_full_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo')),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'filename'               => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
      'position'               => new sfValidatorInteger(array('required' => false)),
      'dop_info_category_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfoCategory', 'required' => false)),
      'category_full_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'DopInfoCategoryFull', 'column' => array('position')))
    );

    $this->widgetSchema->setNameFormat('dop_info_category_full[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DopInfoCategoryFull';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['dop_info_category_list']))
    {
      $this->setDefault('dop_info_category_list', $this->object->DopInfoCategory->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['category_full_list']))
    {
      $this->setDefault('category_full_list', $this->object->CategoryFull->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveDopInfoCategoryList($con);
    $this->saveCategoryFullList($con);

    parent::doSave($con);
  }

  public function saveDopInfoCategoryList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['dop_info_category_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->DopInfoCategory->getPrimaryKeys();
    $values = $this->getValue('dop_info_category_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('DopInfoCategory', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('DopInfoCategory', array_values($link));
    }
  }

  public function saveCategoryFullList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['category_full_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->CategoryFull->getPrimaryKeys();
    $values = $this->getValue('category_full_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('CategoryFull', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('CategoryFull', array_values($link));
    }
  }

}
