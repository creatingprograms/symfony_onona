<?php

/**
 * CategoryFaq form base class.
 *
 * @method CategoryFaq getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCategoryFaqForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'faqcategory_id' => new sfWidgetFormInputHidden(),
      'faq_id'         => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'faqcategory_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('faqcategory_id')), 'empty_value' => $this->getObject()->get('faqcategory_id'), 'required' => false)),
      'faq_id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('faq_id')), 'empty_value' => $this->getObject()->get('faq_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('category_faq[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CategoryFaq';
  }

}
