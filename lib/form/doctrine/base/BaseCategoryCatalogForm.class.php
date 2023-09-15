<?php

/**
 * CategoryCatalog form base class.
 *
 * @method CategoryCatalog getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCategoryCatalogForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'category_id' => new sfWidgetFormInputHidden(),
      'catalog_id'  => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'category_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('category_id')), 'empty_value' => $this->getObject()->get('category_id'), 'required' => false)),
      'catalog_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('catalog_id')), 'empty_value' => $this->getObject()->get('catalog_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('category_catalog[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CategoryCatalog';
  }

}
