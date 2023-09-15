<?php

/**
 * Shops filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseShopsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'is_active'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_onmain'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_new'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'slug'           => new sfWidgetFormFilterInput(),
      'Address'        => new sfWidgetFormFilterInput(),
      'phone'          => new sfWidgetFormFilterInput(),
      'Card'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'Cash'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'House'          => new sfWidgetFormFilterInput(),
      'Description'    => new sfWidgetFormFilterInput(),
      'Latitude'       => new sfWidgetFormFilterInput(),
      'Longitude'      => new sfWidgetFormFilterInput(),
      'Metro'          => new sfWidgetFormFilterInput(),
      'Iconmetro'      => new sfWidgetFormFilterInput(),
      'preview_image'  => new sfWidgetFormFilterInput(),
      'Name'           => new sfWidgetFormFilterInput(),
      'OutDescription' => new sfWidgetFormFilterInput(),
      'Status'         => new sfWidgetFormFilterInput(),
      'Street'         => new sfWidgetFormFilterInput(),
      'WorkTime'       => new sfWidgetFormFilterInput(),
      'city_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)),
      'page_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true)),
      'id1c'           => new sfWidgetFormFilterInput(),
      'google'         => new sfWidgetFormFilterInput(),
      'yandex'         => new sfWidgetFormFilterInput(),
      '2gis'           => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'is_active'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_onmain'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_new'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'slug'           => new sfValidatorPass(array('required' => false)),
      'Address'        => new sfValidatorPass(array('required' => false)),
      'phone'          => new sfValidatorPass(array('required' => false)),
      'Card'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'Cash'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'House'          => new sfValidatorPass(array('required' => false)),
      'Description'    => new sfValidatorPass(array('required' => false)),
      'Latitude'       => new sfValidatorPass(array('required' => false)),
      'Longitude'      => new sfValidatorPass(array('required' => false)),
      'Metro'          => new sfValidatorPass(array('required' => false)),
      'Iconmetro'      => new sfValidatorPass(array('required' => false)),
      'preview_image'  => new sfValidatorPass(array('required' => false)),
      'Name'           => new sfValidatorPass(array('required' => false)),
      'OutDescription' => new sfValidatorPass(array('required' => false)),
      'Status'         => new sfValidatorPass(array('required' => false)),
      'Street'         => new sfValidatorPass(array('required' => false)),
      'WorkTime'       => new sfValidatorPass(array('required' => false)),
      'city_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('City'), 'column' => 'id')),
      'page_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Page'), 'column' => 'id')),
      'id1c'           => new sfValidatorPass(array('required' => false)),
      'google'         => new sfValidatorPass(array('required' => false)),
      'yandex'         => new sfValidatorPass(array('required' => false)),
      '2gis'           => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('shops_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Shops';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'is_active'      => 'Boolean',
      'is_onmain'      => 'Boolean',
      'is_new'         => 'Boolean',
      'slug'           => 'Text',
      'Address'        => 'Text',
      'phone'          => 'Text',
      'Card'           => 'Boolean',
      'Cash'           => 'Boolean',
      'House'          => 'Text',
      'Description'    => 'Text',
      'Latitude'       => 'Text',
      'Longitude'      => 'Text',
      'Metro'          => 'Text',
      'Iconmetro'      => 'Text',
      'preview_image'  => 'Text',
      'Name'           => 'Text',
      'OutDescription' => 'Text',
      'Status'         => 'Text',
      'Street'         => 'Text',
      'WorkTime'       => 'Text',
      'city_id'        => 'ForeignKey',
      'page_id'        => 'ForeignKey',
      'id1c'           => 'Text',
      'google'         => 'Text',
      'yandex'         => 'Text',
      '2gis'           => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
