<?php

/**
 * Wheelsectors form base class.
 *
 * @method Wheelsectors getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseWheelsectorsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'percent'  => new sfWidgetFormInputText(),
      'discount' => new sfWidgetFormInputText(),
      'coupon'   => new sfWidgetFormInputText(),
      'color'    => new sfWidgetFormChoice(array('choices' => array('Синий' => 'Синий', 'Оранжевый' => 'Оранжевый', 'Зеленый' => 'Зеленый', 'Красный' => 'Красный', 'Желтый' => 'Желтый'))),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'percent'  => new sfValidatorInteger(array('required' => false)),
      'discount' => new sfValidatorInteger(array('required' => false)),
      'coupon'   => new sfValidatorPass(array('required' => false)),
      'color'    => new sfValidatorChoice(array('choices' => array(0 => 'Синий', 1 => 'Оранжевый', 2 => 'Зеленый', 3 => 'Красный', 4 => 'Желтый'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('wheelsectors[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Wheelsectors';
  }

}
