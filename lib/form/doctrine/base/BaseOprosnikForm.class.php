<?php

/**
 * Oprosnik form base class.
 *
 * @method Oprosnik getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseOprosnikForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'orderid'    => new sfWidgetFormInputText(),
      'dataans'    => new sfWidgetFormTextarea(),
      'rating'     => new sfWidgetFormInputText(),
      'shop'       => new sfWidgetFormChoice(array('choices' => array('Магазин' => 'Магазин', 'Интернет магазин' => 'Интернет магазин'))),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'orderid'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'dataans'    => new sfValidatorString(array('required' => false)),
      'rating'     => new sfValidatorInteger(array('required' => false)),
      'shop'       => new sfValidatorChoice(array('choices' => array(0 => 'Магазин', 1 => 'Интернет магазин'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('oprosnik[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Oprosnik';
  }

}
