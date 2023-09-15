<?php

/**
 * CategorypagePage form base class.
 *
 * @method CategorypagePage getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCategorypagePageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'Categorypage_id' => new sfWidgetFormInputHidden(),
      'page_id'         => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'Categorypage_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('Categorypage_id')), 'empty_value' => $this->getObject()->get('Categorypage_id'), 'required' => false)),
      'page_id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('page_id')), 'empty_value' => $this->getObject()->get('page_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('categorypage_page[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CategorypagePage';
  }

}
