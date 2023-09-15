<?php

/**
 * Cardsmobile filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCardsmobileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'barcode'        => new sfWidgetFormFilterInput(),
      'user_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'is_public'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'phone'          => new sfWidgetFormFilterInput(),
      'city'           => new sfWidgetFormFilterInput(),
      'country'        => new sfWidgetFormFilterInput(),
      'email'          => new sfWidgetFormFilterInput(),
      'sex'            => new sfWidgetFormFilterInput(),
      'user_name'      => new sfWidgetFormFilterInput(),
      'user_family'    => new sfWidgetFormFilterInput(),
      'user_subname'   => new sfWidgetFormFilterInput(),
      'is_reserved'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'birthday'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'is_allow_email' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_allow_sms'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_allow_call'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'barcode'        => new sfValidatorPass(array('required' => false)),
      'user_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'is_public'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'phone'          => new sfValidatorPass(array('required' => false)),
      'city'           => new sfValidatorPass(array('required' => false)),
      'country'        => new sfValidatorPass(array('required' => false)),
      'email'          => new sfValidatorPass(array('required' => false)),
      'sex'            => new sfValidatorPass(array('required' => false)),
      'user_name'      => new sfValidatorPass(array('required' => false)),
      'user_family'    => new sfValidatorPass(array('required' => false)),
      'user_subname'   => new sfValidatorPass(array('required' => false)),
      'is_reserved'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'birthday'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'is_allow_email' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_allow_sms'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_allow_call'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('cardsmobile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cardsmobile';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'barcode'        => 'Text',
      'user_id'        => 'ForeignKey',
      'is_public'      => 'Boolean',
      'phone'          => 'Text',
      'city'           => 'Text',
      'country'        => 'Text',
      'email'          => 'Text',
      'sex'            => 'Text',
      'user_name'      => 'Text',
      'user_family'    => 'Text',
      'user_subname'   => 'Text',
      'is_reserved'    => 'Boolean',
      'birthday'       => 'Date',
      'is_allow_email' => 'Boolean',
      'is_allow_sms'   => 'Boolean',
      'is_allow_call'  => 'Boolean',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
