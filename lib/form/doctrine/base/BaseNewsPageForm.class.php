<?php

/**
 * NewsPage form base class.
 *
 * @method NewsPage getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseNewsPageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'news_id' => new sfWidgetFormInputHidden(),
      'page_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'news_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('news_id')), 'empty_value' => $this->getObject()->get('news_id'), 'required' => false)),
      'page_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('page_id')), 'empty_value' => $this->getObject()->get('page_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('news_page[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'NewsPage';
  }

}
