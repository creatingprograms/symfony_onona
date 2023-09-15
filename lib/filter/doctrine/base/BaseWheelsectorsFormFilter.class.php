<?php

/**
 * Wheelsectors filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseWheelsectorsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'percent'  => new sfWidgetFormFilterInput(),
      'discount' => new sfWidgetFormFilterInput(),
      'coupon'   => new sfWidgetFormFilterInput(),
      'color'    => new sfWidgetFormChoice(array('choices' => array('' => '', 'Синий' => 'Синий', 'Оранжевый' => 'Оранжевый', 'Зеленый' => 'Зеленый', 'Красный' => 'Красный', 'Желтый' => 'Желтый'))),
    ));

    $this->setValidators(array(
      'percent'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'discount' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'coupon'   => new sfValidatorPass(array('required' => false)),
      'color'    => new sfValidatorChoice(array('required' => false, 'choices' => array('Синий' => 'Синий', 'Оранжевый' => 'Оранжевый', 'Зеленый' => 'Зеленый', 'Красный' => 'Красный', 'Желтый' => 'Желтый'))),
    ));

    $this->widgetSchema->setNameFormat('wheelsectors_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Wheelsectors';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'percent'  => 'Number',
      'discount' => 'Number',
      'coupon'   => 'Text',
      'color'    => 'Enum',
    );
  }
}
