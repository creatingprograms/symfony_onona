<?php

/**
 * sfGuardUser form base class.
 *
 * @method sfGuardUser getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'first_name'             => new sfWidgetFormInputText(),
      'last_name'              => new sfWidgetFormInputText(),
      'email_address'          => new sfWidgetFormInputText(),
      'username'               => new sfWidgetFormInputText(),
      'algorithm'              => new sfWidgetFormInputText(),
      'salt'                   => new sfWidgetFormInputText(),
      'password'               => new sfWidgetFormInputText(),
      'is_active'              => new sfWidgetFormInputCheckbox(),
      'is_super_admin'         => new sfWidgetFormInputCheckbox(),
      'last_login'             => new sfWidgetFormDateTime(),
      'birthday'               => new sfWidgetFormDate(),
      'phone'                  => new sfWidgetFormInputText(),
      'index_house'            => new sfWidgetFormInputText(),
      'city'                   => new sfWidgetFormInputText(),
      'region_fias_id'         => new sfWidgetFormInputText(),
      'street'                 => new sfWidgetFormInputText(),
      'house'                  => new sfWidgetFormInputText(),
      'korpus'                 => new sfWidgetFormInputText(),
      'apartament'             => new sfWidgetFormInputText(),
      'sex'                    => new sfWidgetFormInputText(),
      'ssidentity'             => new sfWidgetFormInputText(),
      'oldphone'               => new sfWidgetFormInputText(),
      'activephone'            => new sfWidgetFormInputCheckbox(),
      'activephonecode'        => new sfWidgetFormInputText(),
      'last_ip'                => new sfWidgetFormInputText(),
      'personal_recomendation' => new sfWidgetFormTextarea(),
      'cart'                   => new sfWidgetFormTextarea(),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
      'groups_list'            => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'permissions_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission')),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'first_name'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'last_name'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'email_address'          => new sfValidatorString(array('max_length' => 255)),
      'username'               => new sfValidatorString(array('max_length' => 128)),
      'algorithm'              => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'salt'                   => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'password'               => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'is_active'              => new sfValidatorBoolean(array('required' => false)),
      'is_super_admin'         => new sfValidatorBoolean(array('required' => false)),
      'last_login'             => new sfValidatorDateTime(array('required' => false)),
      'birthday'               => new sfValidatorDate(array('required' => false)),
      'phone'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'index_house'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'region_fias_id'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'street'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'house'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'korpus'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'apartament'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sex'                    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ssidentity'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'oldphone'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'activephone'            => new sfValidatorBoolean(array('required' => false)),
      'activephonecode'        => new sfValidatorInteger(array('required' => false)),
      'last_ip'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'personal_recomendation' => new sfValidatorString(array('required' => false)),
      'cart'                   => new sfValidatorString(array('required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
      'groups_list'            => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'permissions_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('email_address'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('username'))),
      ))
    );

    $this->widgetSchema->setNameFormat('sf_guard_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['groups_list']))
    {
      $this->setDefault('groups_list', $this->object->Groups->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['permissions_list']))
    {
      $this->setDefault('permissions_list', $this->object->Permissions->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveGroupsList($con);
    $this->savePermissionsList($con);

    parent::doSave($con);
  }

  public function saveGroupsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['groups_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Groups->getPrimaryKeys();
    $values = $this->getValue('groups_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Groups', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Groups', array_values($link));
    }
  }

  public function savePermissionsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['permissions_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Permissions->getPrimaryKeys();
    $values = $this->getValue('permissions_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Permissions', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Permissions', array_values($link));
    }
  }

}
