<?php

/**
 * Faqcategory form base class.
 *
 * @method Faqcategory getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFaqcategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'name'               => new sfWidgetFormInputText(),
      'content'            => new sfWidgetFormTextarea(),
      'is_public'          => new sfWidgetFormInputCheckbox(),
      'title'              => new sfWidgetFormInputText(),
      'keywords'           => new sfWidgetFormInputText(),
      'description'        => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'position'           => new sfWidgetFormInputText(),
      'slug'               => new sfWidgetFormInputText(),
      'category_faqs_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Faq')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'            => new sfValidatorString(array('required' => false)),
      'is_public'          => new sfValidatorBoolean(array('required' => false)),
      'title'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
      'position'           => new sfValidatorInteger(array('required' => false)),
      'slug'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'category_faqs_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Faq', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Faqcategory', 'column' => array('position'))),
        new sfValidatorDoctrineUnique(array('model' => 'Faqcategory', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('faqcategory[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Faqcategory';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['category_faqs_list']))
    {
      $this->setDefault('category_faqs_list', $this->object->CategoryFaqs->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveCategoryFaqsList($con);

    parent::doSave($con);
  }

  public function saveCategoryFaqsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['category_faqs_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->CategoryFaqs->getPrimaryKeys();
    $values = $this->getValue('category_faqs_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('CategoryFaqs', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('CategoryFaqs', array_values($link));
    }
  }

}
