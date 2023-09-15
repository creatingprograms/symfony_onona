<?php

/**
 * Product form base class.
 *
 * @method Product getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProductForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'h1'                     => new sfWidgetFormInputText(),
      'name'                   => new sfWidgetFormInputText(),
      'code'                   => new sfWidgetFormInputText(),
      'content'                => new sfWidgetFormTextarea(),
      'newbie_description'     => new sfWidgetFormTextarea(),
      'price'                  => new sfWidgetFormInputText(),
      'bonus'                  => new sfWidgetFormInputText(),
      'bonuspay'               => new sfWidgetFormInputText(),
      'old_price'              => new sfWidgetFormInputText(),
      'discount'               => new sfWidgetFormInputText(),
      'nextdiscount'           => new sfWidgetFormInputText(),
      'count'                  => new sfWidgetFormInputText(),
      'video'                  => new sfWidgetFormInputText(),
      'doc1'                   => new sfWidgetFormInputText(),
      'doc2'                   => new sfWidgetFormInputText(),
      'doc3'                   => new sfWidgetFormInputText(),
      'doc4'                   => new sfWidgetFormInputText(),
      'doc5'                   => new sfWidgetFormInputText(),
      'articles_ids'           => new sfWidgetFormInputText(),
      'file'                   => new sfWidgetFormInputText(),
      'canonical'              => new sfWidgetFormInputText(),
      'videoenabled'           => new sfWidgetFormInputCheckbox(),
      'is_coupon_enabled'      => new sfWidgetFormInputCheckbox(),
      'is_bonus_enabled'       => new sfWidgetFormInputCheckbox(),
      'views_count'            => new sfWidgetFormInputText(),
      'votes_count'            => new sfWidgetFormInputText(),
      'rating'                 => new sfWidgetFormInputText(),
      'is_related'             => new sfWidgetFormInputCheckbox(),
      'is_public'              => new sfWidgetFormInputCheckbox(),
      'is_new_on_market'       => new sfWidgetFormInputCheckbox(),
      'is_new_on_site'         => new sfWidgetFormInputCheckbox(),
      'is_visiblechildren'     => new sfWidgetFormInputCheckbox(),
      'is_visiblecategory'     => new sfWidgetFormInputCheckbox(),
      'is_notadddelivery'      => new sfWidgetFormInputCheckbox(),
      'set_pairs'              => new sfWidgetFormInputCheckbox(),
      'set_her'                => new sfWidgetFormInputCheckbox(),
      'set_him'                => new sfWidgetFormInputCheckbox(),
      'for_pairs'              => new sfWidgetFormInputCheckbox(),
      'for_she'                => new sfWidgetFormInputCheckbox(),
      'for_her'                => new sfWidgetFormInputCheckbox(),
      'bdsm'                   => new sfWidgetFormInputCheckbox(),
      'cosmetics'              => new sfWidgetFormInputCheckbox(),
      'belie'                  => new sfWidgetFormInputCheckbox(),
      'other'                  => new sfWidgetFormInputCheckbox(),
      'sync'                   => new sfWidgetFormInputCheckbox(),
      'title'                  => new sfWidgetFormInputText(),
      'externallink'           => new sfWidgetFormInputText(),
      'keywords'               => new sfWidgetFormInputText(),
      'description'            => new sfWidgetFormInputText(),
      'parents_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'id1c'                   => new sfWidgetFormInputText(),
      'generalcategory_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GeneralCategory'), 'add_empty' => true)),
      'adult'                  => new sfWidgetFormInputCheckbox(),
      'yamarket'               => new sfWidgetFormInputCheckbox(),
      'is_express'             => new sfWidgetFormInputCheckbox(),
      'endaction'              => new sfWidgetFormDate(),
      'startaction'            => new sfWidgetFormDate(),
      'step'                   => new sfWidgetFormChoice(array('choices' => array('' => '', '1 сутки' => '1 сутки', '2 суток' => '2 суток', '3 суток' => '3 суток', '4 суток' => '4 суток', '5 суток' => '5 суток'))),
      'positionrelated'        => new sfWidgetFormInputText(),
      'tags'                   => new sfWidgetFormInputText(),
      'pointcreate'            => new sfWidgetFormInputText(),
      'moder'                  => new sfWidgetFormInputCheckbox(),
      'moderuser'              => new sfWidgetFormInputText(),
      'user'                   => new sfWidgetFormInputText(),
      'yamarket_clothes'       => new sfWidgetFormInputCheckbox(),
      'yamarket_color'         => new sfWidgetFormInputText(),
      'yamarket_typeimg'       => new sfWidgetFormInputText(),
      'yamarket_category'      => new sfWidgetFormInputText(),
      'yamarket_sex'           => new sfWidgetFormChoice(array('choices' => array('' => '', 'Женский' => 'Женский', 'Мужской' => 'Мужской'))),
      'yamarket_model'         => new sfWidgetFormInputText(),
      'yamarket_typeprefix'    => new sfWidgetFormInputText(),
      'stock'                  => new sfWidgetFormTextarea(),
      'buywithitem'            => new sfWidgetFormTextarea(),
      'countsell'              => new sfWidgetFormInputText(),
      'sortpriority'           => new sfWidgetFormInputText(),
      'barcode'                => new sfWidgetFormInputText(),
      'weight'                 => new sfWidgetFormInputText(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
      'position'               => new sfWidgetFormInputText(),
      'slug'                   => new sfWidgetFormInputText(),
      'category_products_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Category')),
      'dop_info_products_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo')),
      'photoalbums_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Photoalbum')),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'h1'                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'name'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'code'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'                => new sfValidatorString(array('required' => false)),
      'newbie_description'     => new sfValidatorString(array('required' => false)),
      'price'                  => new sfValidatorInteger(array('required' => false)),
      'bonus'                  => new sfValidatorInteger(array('required' => false)),
      'bonuspay'               => new sfValidatorInteger(array('required' => false)),
      'old_price'              => new sfValidatorInteger(array('required' => false)),
      'discount'               => new sfValidatorInteger(array('required' => false)),
      'nextdiscount'           => new sfValidatorInteger(array('required' => false)),
      'count'                  => new sfValidatorInteger(array('required' => false)),
      'video'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'doc1'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'doc2'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'doc3'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'doc4'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'doc5'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'articles_ids'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'file'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'canonical'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'videoenabled'           => new sfValidatorBoolean(array('required' => false)),
      'is_coupon_enabled'      => new sfValidatorBoolean(array('required' => false)),
      'is_bonus_enabled'       => new sfValidatorBoolean(array('required' => false)),
      'views_count'            => new sfValidatorInteger(array('required' => false)),
      'votes_count'            => new sfValidatorInteger(array('required' => false)),
      'rating'                 => new sfValidatorInteger(array('required' => false)),
      'is_related'             => new sfValidatorBoolean(array('required' => false)),
      'is_public'              => new sfValidatorBoolean(array('required' => false)),
      'is_new_on_market'       => new sfValidatorBoolean(array('required' => false)),
      'is_new_on_site'         => new sfValidatorBoolean(array('required' => false)),
      'is_visiblechildren'     => new sfValidatorBoolean(array('required' => false)),
      'is_visiblecategory'     => new sfValidatorBoolean(array('required' => false)),
      'is_notadddelivery'      => new sfValidatorBoolean(array('required' => false)),
      'set_pairs'              => new sfValidatorBoolean(array('required' => false)),
      'set_her'                => new sfValidatorBoolean(array('required' => false)),
      'set_him'                => new sfValidatorBoolean(array('required' => false)),
      'for_pairs'              => new sfValidatorBoolean(array('required' => false)),
      'for_she'                => new sfValidatorBoolean(array('required' => false)),
      'for_her'                => new sfValidatorBoolean(array('required' => false)),
      'bdsm'                   => new sfValidatorBoolean(array('required' => false)),
      'cosmetics'              => new sfValidatorBoolean(array('required' => false)),
      'belie'                  => new sfValidatorBoolean(array('required' => false)),
      'other'                  => new sfValidatorBoolean(array('required' => false)),
      'sync'                   => new sfValidatorBoolean(array('required' => false)),
      'title'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'externallink'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'parents_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'required' => false)),
      'id1c'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'generalcategory_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GeneralCategory'), 'required' => false)),
      'adult'                  => new sfValidatorBoolean(array('required' => false)),
      'yamarket'               => new sfValidatorBoolean(array('required' => false)),
      'is_express'             => new sfValidatorBoolean(array('required' => false)),
      'endaction'              => new sfValidatorDate(array('required' => false)),
      'startaction'            => new sfValidatorDate(array('required' => false)),
      'step'                   => new sfValidatorChoice(array('choices' => array(0 => '', 1 => '1 сутки', 2 => '2 суток', 3 => '3 суток', 4 => '4 суток', 5 => '5 суток'), 'required' => false)),
      'positionrelated'        => new sfValidatorInteger(array('required' => false)),
      'tags'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pointcreate'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'moder'                  => new sfValidatorBoolean(array('required' => false)),
      'moderuser'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'yamarket_clothes'       => new sfValidatorBoolean(array('required' => false)),
      'yamarket_color'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'yamarket_typeimg'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'yamarket_category'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'yamarket_sex'           => new sfValidatorChoice(array('choices' => array(0 => '', 1 => 'Женский', 2 => 'Мужской'), 'required' => false)),
      'yamarket_model'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'yamarket_typeprefix'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'stock'                  => new sfValidatorString(array('required' => false)),
      'buywithitem'            => new sfValidatorString(array('required' => false)),
      'countsell'              => new sfValidatorInteger(array('required' => false)),
      'sortpriority'           => new sfValidatorInteger(array('required' => false)),
      'barcode'                => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'weight'                 => new sfValidatorNumber(array('required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
      'position'               => new sfValidatorInteger(array('required' => false)),
      'slug'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'category_products_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Category', 'required' => false)),
      'dop_info_products_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo', 'required' => false)),
      'photoalbums_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Photoalbum', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Product', 'column' => array('code'))),
        new sfValidatorDoctrineUnique(array('model' => 'Product', 'column' => array('position'))),
        new sfValidatorDoctrineUnique(array('model' => 'Product', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('product[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Product';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['category_products_list']))
    {
      $this->setDefault('category_products_list', $this->object->CategoryProducts->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['dop_info_products_list']))
    {
      $this->setDefault('dop_info_products_list', $this->object->DopInfoProducts->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['photoalbums_list']))
    {
      $this->setDefault('photoalbums_list', $this->object->Photoalbums->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveCategoryProductsList($con);
    $this->saveDopInfoProductsList($con);
    $this->savePhotoalbumsList($con);

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

  public function savePhotoalbumsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['photoalbums_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Photoalbums->getPrimaryKeys();
    $values = $this->getValue('photoalbums_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Photoalbums', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Photoalbums', array_values($link));
    }
  }

}
