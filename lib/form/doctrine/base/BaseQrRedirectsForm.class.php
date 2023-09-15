<?php

/**
 * QrRedirects form base class.
 *
 * @method QrRedirects getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseQrRedirectsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'shop'       => new sfWidgetFormInputText(),
      'shop_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Shops'), 'add_empty' => true)),
      'type'       => new sfWidgetFormChoice(array('choices' => array('2gis' => '2gis', 'google' => 'google', 'yandex' => 'yandex'))),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'shop'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'shop_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Shops'), 'required' => false)),
      'type'       => new sfValidatorChoice(array('choices' => array(0 => '2gis', 1 => 'google', 2 => 'yandex'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('qr_redirects[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QrRedirects';
  }

}
