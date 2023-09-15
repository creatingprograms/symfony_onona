<?php

/**
 * Pickpoint filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePickpointFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'Address'        => new sfWidgetFormFilterInput(),
      'Card'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'Cash'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'CitiId'         => new sfWidgetFormFilterInput(),
      'CitiName'       => new sfWidgetFormFilterInput(),
      'CitiOwnerId'    => new sfWidgetFormFilterInput(),
      'CountryName'    => new sfWidgetFormFilterInput(),
      'House'          => new sfWidgetFormFilterInput(),
      'dopid'          => new sfWidgetFormFilterInput(),
      'InDescription'  => new sfWidgetFormFilterInput(),
      'IndoorPlace'    => new sfWidgetFormFilterInput(),
      'Latitude'       => new sfWidgetFormFilterInput(),
      'Longitude'      => new sfWidgetFormFilterInput(),
      'Metro'          => new sfWidgetFormFilterInput(),
      'Name'           => new sfWidgetFormFilterInput(),
      'Number'         => new sfWidgetFormFilterInput(),
      'OutDescription' => new sfWidgetFormFilterInput(),
      'OwnerId'        => new sfWidgetFormFilterInput(),
      'PostCode'       => new sfWidgetFormFilterInput(),
      'Region'         => new sfWidgetFormFilterInput(),
      'Status'         => new sfWidgetFormFilterInput(),
      'Street'         => new sfWidgetFormFilterInput(),
      'TypeTitle'      => new sfWidgetFormFilterInput(),
      'WorkTime'       => new sfWidgetFormFilterInput(),
      'city_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)),
      'is_public'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'Address'        => new sfValidatorPass(array('required' => false)),
      'Card'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'Cash'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'CitiId'         => new sfValidatorPass(array('required' => false)),
      'CitiName'       => new sfValidatorPass(array('required' => false)),
      'CitiOwnerId'    => new sfValidatorPass(array('required' => false)),
      'CountryName'    => new sfValidatorPass(array('required' => false)),
      'House'          => new sfValidatorPass(array('required' => false)),
      'dopid'          => new sfValidatorPass(array('required' => false)),
      'InDescription'  => new sfValidatorPass(array('required' => false)),
      'IndoorPlace'    => new sfValidatorPass(array('required' => false)),
      'Latitude'       => new sfValidatorPass(array('required' => false)),
      'Longitude'      => new sfValidatorPass(array('required' => false)),
      'Metro'          => new sfValidatorPass(array('required' => false)),
      'Name'           => new sfValidatorPass(array('required' => false)),
      'Number'         => new sfValidatorPass(array('required' => false)),
      'OutDescription' => new sfValidatorPass(array('required' => false)),
      'OwnerId'        => new sfValidatorPass(array('required' => false)),
      'PostCode'       => new sfValidatorPass(array('required' => false)),
      'Region'         => new sfValidatorPass(array('required' => false)),
      'Status'         => new sfValidatorPass(array('required' => false)),
      'Street'         => new sfValidatorPass(array('required' => false)),
      'TypeTitle'      => new sfValidatorPass(array('required' => false)),
      'WorkTime'       => new sfValidatorPass(array('required' => false)),
      'city_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('City'), 'column' => 'id')),
      'is_public'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('pickpoint_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Pickpoint';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'Address'        => 'Text',
      'Card'           => 'Boolean',
      'Cash'           => 'Boolean',
      'CitiId'         => 'Text',
      'CitiName'       => 'Text',
      'CitiOwnerId'    => 'Text',
      'CountryName'    => 'Text',
      'House'          => 'Text',
      'dopid'          => 'Text',
      'InDescription'  => 'Text',
      'IndoorPlace'    => 'Text',
      'Latitude'       => 'Text',
      'Longitude'      => 'Text',
      'Metro'          => 'Text',
      'Name'           => 'Text',
      'Number'         => 'Text',
      'OutDescription' => 'Text',
      'OwnerId'        => 'Text',
      'PostCode'       => 'Text',
      'Region'         => 'Text',
      'Status'         => 'Text',
      'Street'         => 'Text',
      'TypeTitle'      => 'Text',
      'WorkTime'       => 'Text',
      'city_id'        => 'ForeignKey',
      'is_public'      => 'Boolean',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
