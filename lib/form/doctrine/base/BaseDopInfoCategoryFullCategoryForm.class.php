<?php

/**
 * DopInfoCategoryFullCategory form base class.
 *
 * @method DopInfoCategoryFullCategory getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDopInfoCategoryFullCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'dop_info_category_full_id' => new sfWidgetFormInputHidden(),
      'dop_info_category_id'      => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'dop_info_category_full_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('dop_info_category_full_id')), 'empty_value' => $this->getObject()->get('dop_info_category_full_id'), 'required' => false)),
      'dop_info_category_id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('dop_info_category_id')), 'empty_value' => $this->getObject()->get('dop_info_category_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('dop_info_category_full_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DopInfoCategoryFullCategory';
  }

}
