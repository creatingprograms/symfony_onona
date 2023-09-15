<?php

/**
 * sfGuardUser filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'first_name'             => new sfWidgetFormFilterInput(),
      'last_name'              => new sfWidgetFormFilterInput(),
      'email_address'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'username'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'algorithm'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'salt'                   => new sfWidgetFormFilterInput(),
      'password'               => new sfWidgetFormFilterInput(),
      'is_active'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_super_admin'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'last_login'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'birthday'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'phone'                  => new sfWidgetFormFilterInput(),
      'index_house'            => new sfWidgetFormFilterInput(),
      'city'                   => new sfWidgetFormFilterInput(),
      'region_fias_id'         => new sfWidgetFormFilterInput(),
      'street'                 => new sfWidgetFormFilterInput(),
      'house'                  => new sfWidgetFormFilterInput(),
      'korpus'                 => new sfWidgetFormFilterInput(),
      'apartament'             => new sfWidgetFormFilterInput(),
      'sex'                    => new sfWidgetFormFilterInput(),
      'ssidentity'             => new sfWidgetFormFilterInput(),
      'oldphone'               => new sfWidgetFormFilterInput(),
      'activephone'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'activephonecode'        => new sfWidgetFormFilterInput(),
      'last_ip'                => new sfWidgetFormFilterInput(),
      'personal_recomendation' => new sfWidgetFormFilterInput(),
      'cart'                   => new sfWidgetFormFilterInput(),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'groups_list'            => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'permissions_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission')),
    ));

    $this->setValidators(array(
      'first_name'             => new sfValidatorPass(array('required' => false)),
      'last_name'              => new sfValidatorPass(array('required' => false)),
      'email_address'          => new sfValidatorPass(array('required' => false)),
      'username'               => new sfValidatorPass(array('required' => false)),
      'algorithm'              => new sfValidatorPass(array('required' => false)),
      'salt'                   => new sfValidatorPass(array('required' => false)),
      'password'               => new sfValidatorPass(array('required' => false)),
      'is_active'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_super_admin'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'last_login'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'birthday'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'phone'                  => new sfValidatorPass(array('required' => false)),
      'index_house'            => new sfValidatorPass(array('required' => false)),
      'city'                   => new sfValidatorPass(array('required' => false)),
      'region_fias_id'         => new sfValidatorPass(array('required' => false)),
      'street'                 => new sfValidatorPass(array('required' => false)),
      'house'                  => new sfValidatorPass(array('required' => false)),
      'korpus'                 => new sfValidatorPass(array('required' => false)),
      'apartament'             => new sfValidatorPass(array('required' => false)),
      'sex'                    => new sfValidatorPass(array('required' => false)),
      'ssidentity'             => new sfValidatorPass(array('required' => false)),
      'oldphone'               => new sfValidatorPass(array('required' => false)),
      'activephone'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'activephonecode'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_ip'                => new sfValidatorPass(array('required' => false)),
      'personal_recomendation' => new sfValidatorPass(array('required' => false)),
      'cart'                   => new sfValidatorPass(array('required' => false)),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'groups_list'            => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'permissions_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addGroupsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.sfGuardUserGroup sfGuardUserGroup')
      ->andWhereIn('sfGuardUserGroup.group_id', $values)
    ;
  }

  public function addPermissionsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.sfGuardUserPermission sfGuardUserPermission')
      ->andWhereIn('sfGuardUserPermission.permission_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'first_name'             => 'Text',
      'last_name'              => 'Text',
      'email_address'          => 'Text',
      'username'               => 'Text',
      'algorithm'              => 'Text',
      'salt'                   => 'Text',
      'password'               => 'Text',
      'is_active'              => 'Boolean',
      'is_super_admin'         => 'Boolean',
      'last_login'             => 'Date',
      'birthday'               => 'Date',
      'phone'                  => 'Text',
      'index_house'            => 'Text',
      'city'                   => 'Text',
      'region_fias_id'         => 'Text',
      'street'                 => 'Text',
      'house'                  => 'Text',
      'korpus'                 => 'Text',
      'apartament'             => 'Text',
      'sex'                    => 'Text',
      'ssidentity'             => 'Text',
      'oldphone'               => 'Text',
      'activephone'            => 'Boolean',
      'activephonecode'        => 'Number',
      'last_ip'                => 'Text',
      'personal_recomendation' => 'Text',
      'cart'                   => 'Text',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
      'groups_list'            => 'ManyKey',
      'permissions_list'       => 'ManyKey',
    );
  }
}
