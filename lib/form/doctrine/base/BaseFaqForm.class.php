<?php

/**
 * Faq form base class.
 *
 * @method Faq getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFaqForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'name'             => new sfWidgetFormInputText(),
      'slug'             => new sfWidgetFormInputText(),
      'precontent'       => new sfWidgetFormTextarea(),
      'content'          => new sfWidgetFormTextarea(),
      'is_public'        => new sfWidgetFormInputCheckbox(),
      'img'              => new sfWidgetFormInputText(),
      'img_preview'      => new sfWidgetFormInputText(),
      'views_count'      => new sfWidgetFormInputText(),
      'is_related'       => new sfWidgetFormInputCheckbox(),
      'positionrelated'  => new sfWidgetFormInputText(),
      'title'            => new sfWidgetFormInputText(),
      'keywords'         => new sfWidgetFormInputText(),
      'description'      => new sfWidgetFormInputText(),
      'category_slug'    => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
      'position'         => new sfWidgetFormInputText(),
      'faqcategory_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Faqcategory')),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'             => new sfValidatorString(array('max_length' => 255)),
      'slug'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'precontent'       => new sfValidatorString(array('required' => false)),
      'content'          => new sfValidatorString(array('required' => false)),
      'is_public'        => new sfValidatorBoolean(array('required' => false)),
      'img'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'img_preview'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'views_count'      => new sfValidatorInteger(array('required' => false)),
      'is_related'       => new sfValidatorBoolean(array('required' => false)),
      'positionrelated'  => new sfValidatorInteger(array('required' => false)),
      'title'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'category_slug'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
      'position'         => new sfValidatorInteger(array('required' => false)),
      'faqcategory_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Faqcategory', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Faq', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'Faq', 'column' => array('slug'))),
        new sfValidatorDoctrineUnique(array('model' => 'Faq', 'column' => array('position'))),
      ))
    );

    $this->widgetSchema->setNameFormat('faq[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Faq';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['faqcategory_list']))
    {
      $this->setDefault('faqcategory_list', $this->object->Faqcategory->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveFaqcategoryList($con);

    parent::doSave($con);
  }

  public function saveFaqcategoryList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['faqcategory_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Faqcategory->getPrimaryKeys();
    $values = $this->getValue('faqcategory_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Faqcategory', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Faqcategory', array_values($link));
    }
  }

}
