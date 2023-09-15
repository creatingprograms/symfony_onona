<?php

/**
 * Horoscopesovm form base class.
 *
 * @method Horoscopesovm getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseHoroscopesovmForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'horoscope_m_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Horoscopem'), 'add_empty' => false)),
      'horoscope_g_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Horoscopeg'), 'add_empty' => false)),
      'content'        => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'horoscope_m_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Horoscopem'))),
      'horoscope_g_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Horoscopeg'))),
      'content'        => new sfValidatorString(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('horoscopesovm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Horoscopesovm';
  }

}
