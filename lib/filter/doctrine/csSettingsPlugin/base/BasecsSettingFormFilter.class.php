<?php

/**
 * csSetting filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasecsSettingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'widget_options'  => new sfWidgetFormFilterInput(),
      'value'           => new sfWidgetFormFilterInput(),
      'setting_group'   => new sfWidgetFormFilterInput(),
      'setting_default' => new sfWidgetFormFilterInput(),
      'slug'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'            => new sfValidatorPass(array('required' => false)),
      'type'            => new sfValidatorPass(array('required' => false)),
      'widget_options'  => new sfValidatorPass(array('required' => false)),
      'value'           => new sfValidatorPass(array('required' => false)),
      'setting_group'   => new sfValidatorPass(array('required' => false)),
      'setting_default' => new sfValidatorPass(array('required' => false)),
      'slug'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cs_setting_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'csSetting';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'name'            => 'Text',
      'type'            => 'Text',
      'widget_options'  => 'Text',
      'value'           => 'Text',
      'setting_group'   => 'Text',
      'setting_default' => 'Text',
      'slug'            => 'Text',
    );
  }
}
