<?php

/**
 * Page form base class.
 *
 * @method Page getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'name'                => new sfWidgetFormInputText(),
      'slug'                => new sfWidgetFormInputText(),
      'sitemapRate'         => new sfWidgetFormInputText(),
      'content'             => new sfWidgetFormTextarea(),
      'content_mobile'      => new sfWidgetFormTextarea(),
      'content_mo'          => new sfWidgetFormTextarea(),
      'content_mo_mobile'   => new sfWidgetFormTextarea(),
      'content_new_version' => new sfWidgetFormTextarea(),
      'is_public'           => new sfWidgetFormInputCheckbox(),
      'is_show_right_block' => new sfWidgetFormInputCheckbox(),
      'title'               => new sfWidgetFormInputText(),
      'keywords'            => new sfWidgetFormInputText(),
      'description'         => new sfWidgetFormInputText(),
      'tags'                => new sfWidgetFormInputText(),
      'class'               => new sfWidgetFormInputText(),
      'city_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)),
      'views_count'         => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
      'position'            => new sfWidgetFormInputText(),
      'categorypage_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Categorypage')),
      'news_list'           => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'News')),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                => new sfValidatorString(array('max_length' => 255)),
      'slug'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sitemapRate'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'             => new sfValidatorString(array('required' => false)),
      'content_mobile'      => new sfValidatorString(array('required' => false)),
      'content_mo'          => new sfValidatorString(array('required' => false)),
      'content_mo_mobile'   => new sfValidatorString(array('required' => false)),
      'content_new_version' => new sfValidatorString(array('required' => false)),
      'is_public'           => new sfValidatorBoolean(array('required' => false)),
      'is_show_right_block' => new sfValidatorBoolean(array('required' => false)),
      'title'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tags'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'class'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'required' => false)),
      'views_count'         => new sfValidatorInteger(array('required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
      'position'            => new sfValidatorInteger(array('required' => false)),
      'categorypage_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Categorypage', 'required' => false)),
      'news_list'           => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'News', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Page', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'Page', 'column' => array('slug'))),
        new sfValidatorDoctrineUnique(array('model' => 'Page', 'column' => array('position'))),
      ))
    );

    $this->widgetSchema->setNameFormat('page[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Page';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['categorypage_list']))
    {
      $this->setDefault('categorypage_list', $this->object->Categorypage->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['news_list']))
    {
      $this->setDefault('news_list', $this->object->News->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveCategorypageList($con);
    $this->saveNewsList($con);

    parent::doSave($con);
  }

  public function saveCategorypageList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['categorypage_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Categorypage->getPrimaryKeys();
    $values = $this->getValue('categorypage_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Categorypage', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Categorypage', array_values($link));
    }
  }

  public function saveNewsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['news_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->News->getPrimaryKeys();
    $values = $this->getValue('news_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('News', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('News', array_values($link));
    }
  }

}
