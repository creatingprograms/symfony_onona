<?php

/**
 * Article form base class.
 *
 * @method Article getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseArticleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'name'                 => new sfWidgetFormInputText(),
      'slug'                 => new sfWidgetFormInputText(),
      'precontent'           => new sfWidgetFormTextarea(),
      'content'              => new sfWidgetFormTextarea(),
      'is_public'            => new sfWidgetFormInputCheckbox(),
      'is_new'               => new sfWidgetFormInputCheckbox(),
      'tags'                 => new sfWidgetFormInputText(),
      'img'                  => new sfWidgetFormInputText(),
      'img_preview'          => new sfWidgetFormInputText(),
      'views_count'          => new sfWidgetFormInputText(),
      'votes_count'          => new sfWidgetFormInputText(),
      'rating'               => new sfWidgetFormInputText(),
      'is_related'           => new sfWidgetFormInputCheckbox(),
      'positionrelated'      => new sfWidgetFormInputText(),
      'title'                => new sfWidgetFormInputText(),
      'keywords'             => new sfWidgetFormInputText(),
      'description'          => new sfWidgetFormInputText(),
      'moder'                => new sfWidgetFormInputCheckbox(),
      'user'                 => new sfWidgetFormInputText(),
      'expert_id'            => new sfWidgetFormInputText(),
      'category_slug'        => new sfWidgetFormInputText(),
      'video'                => new sfWidgetFormInputText(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
      'position'             => new sfWidgetFormInputText(),
      'articlecategory_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Articlecategory')),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                 => new sfValidatorString(array('max_length' => 255)),
      'slug'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'precontent'           => new sfValidatorString(array('required' => false)),
      'content'              => new sfValidatorString(array('required' => false)),
      'is_public'            => new sfValidatorBoolean(array('required' => false)),
      'is_new'               => new sfValidatorBoolean(array('required' => false)),
      'tags'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'img'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'img_preview'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'views_count'          => new sfValidatorInteger(array('required' => false)),
      'votes_count'          => new sfValidatorInteger(array('required' => false)),
      'rating'               => new sfValidatorInteger(array('required' => false)),
      'is_related'           => new sfValidatorBoolean(array('required' => false)),
      'positionrelated'      => new sfValidatorInteger(array('required' => false)),
      'title'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'moder'                => new sfValidatorBoolean(array('required' => false)),
      'user'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'expert_id'            => new sfValidatorInteger(array('required' => false)),
      'category_slug'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'video'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
      'position'             => new sfValidatorInteger(array('required' => false)),
      'articlecategory_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Articlecategory', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Article', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'Article', 'column' => array('slug'))),
        new sfValidatorDoctrineUnique(array('model' => 'Article', 'column' => array('position'))),
      ))
    );

    $this->widgetSchema->setNameFormat('article[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Article';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['articlecategory_list']))
    {
      $this->setDefault('articlecategory_list', $this->object->Articlecategory->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveArticlecategoryList($con);

    parent::doSave($con);
  }

  public function saveArticlecategoryList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['articlecategory_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Articlecategory->getPrimaryKeys();
    $values = $this->getValue('articlecategory_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Articlecategory', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Articlecategory', array_values($link));
    }
  }

}
