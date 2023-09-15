<?php

/**
 * QrRedirects filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseQrRedirectsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'shop'       => new sfWidgetFormFilterInput(),
      'shop_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Shops'), 'add_empty' => true)),
      'type'       => new sfWidgetFormChoice(array('choices' => array('' => '', '2gis' => '2gis', 'google' => 'google', 'yandex' => 'yandex'))),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'shop'       => new sfValidatorPass(array('required' => false)),
      'shop_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Shops'), 'column' => 'id')),
      'type'       => new sfValidatorChoice(array('required' => false, 'choices' => array('2gis' => '2gis', 'google' => 'google', 'yandex' => 'yandex'))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('qr_redirects_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QrRedirects';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'shop'       => 'Text',
      'shop_id'    => 'ForeignKey',
      'type'       => 'Enum',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
