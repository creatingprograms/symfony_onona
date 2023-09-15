<?php

/**
 * ProductPhotoalbum form base class.
 *
 * @method ProductPhotoalbum getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProductPhotoalbumForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'product_id'    => new sfWidgetFormInputHidden(),
      'photoalbum_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'product_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('product_id')), 'empty_value' => $this->getObject()->get('product_id'), 'required' => false)),
      'photoalbum_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('photoalbum_id')), 'empty_value' => $this->getObject()->get('photoalbum_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('product_photoalbum[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProductPhotoalbum';
  }

}
