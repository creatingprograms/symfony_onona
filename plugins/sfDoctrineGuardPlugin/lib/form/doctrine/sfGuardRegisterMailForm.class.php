<?php

/**
 * sfGuardRegisterForm for registering new users
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: BasesfGuardChangeUserPasswordForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardRegisterMailForm extends BasesfGuardRegisterForm {

    /**
     * @see sfForm
     */
    public function configure() {


        //print_r($this->widgetSchema);
        $positionForm[] = 'first_name';
        $positionForm[] = 'last_name';
        $positionForm[] = 'birthday';
        $positionForm[] = 'email_address';
        $positionForm[] = 'password';
        $positionForm[] = 'password_again';
        $positionForm[] = 'phone';
        $positionForm[] = 'username';
        $positionForm[] = 'city';
        $positionForm[] = 'street';
        $positionForm[] = 'house';
        $positionForm[] = 'index_house';
        $positionForm[] = 'korpus';
        $positionForm[] = 'apartament';
        $positionForm[] = 'id';
        $positionForm[] = 'sex';
        $positionForm[] = 'ssidentity';
        $positionForm[] = 'oldphone';
        $positionForm[] = 'activephone';
        $positionForm[] = 'activephonecode';

        $this->widgetSchema->setPositions($positionForm);
        //print_r($this->widgetSchema); print_r($positionForm); 

        $this->widgetSchema->setLabels(array(
            'first_name' => '*Имя',
            'last_name' => '*Фамилия',
            'email_address' => '*E-mail (логин)',
            'username' => 'Логин*',
            'password' => '*Пароль',
            'password_again' => '*Повторите пароль',
            'birthday' => 'Дата рождения',
            'phone' => '*Телефон',
            'index_house' => 'Индекс',
            'city' => '*Город',
            'street' => '*Улица',
            'house' => '*Дом',
            'korpus' => 'Корпус/строение',
            'apartament' => '*Квартира/офис',
        ));
        $username = 'user_' . rand(0, 9999999999999);
        $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
        if ($isExistUserName->count() != 0) {
            $username = 'user_' . rand(0, 9999999999999);
            $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
            if ($isExistUserName->count() != 0) {
                $username = 'user_' . rand(0, 9999999999999);
                $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                if ($isExistUserName->count() != 0) {
                    $username = 'user_' . rand(0, 9999999999999);
                }
            }
        }

        $this->widgetSchema['username'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['username']->setDefault($username);
        $this->validatorSchema['first_name'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['last_name'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['phone'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['city'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['street'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['house'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['apartament'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        /* $this->setValidators(array(
          'id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
          'first_name' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
          'last_name' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
          'email_address' => new sfValidatorString(array('max_length' => 255)),
          'username' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
          'algorithm' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
          'salt' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
          'password' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
          'is_active' => new sfValidatorBoolean(array('required' => false)),
          'is_super_admin' => new sfValidatorBoolean(array('required' => false)),
          'last_login' => new sfValidatorDateTime(array('required' => false)),
          'birthday' => new sfValidatorDate(array('required' => false)),
          'phone' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
          'index_house' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
          'city' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
          'street' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
          'house' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
          'korpus' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
          'apartament' => new sfValidatorString(array('max_length' => 255, 'required' => true)),
          'created_at' => new sfValidatorDateTime(),
          'updated_at' => new sfValidatorDateTime(),
          'groups_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
          'permissions_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission', 'required' => false)),
          )); */
        $years = range(date('Y')-18, date('Y') - 90);
        //print_r($years);
        $years_list = array_combine($years, $years);
        $this->widgetSchema['birthday']->setOption('format', '%day%<span style="float: left">.</span>%month%<span style="float: left">.</span>%year%');
        $this->widgetSchema['birthday']->setOption('years', $years_list);

        unset(
                $this['ssidentity'], $this['activephone'],$this['activephonecode'],$this['oldphone'],$this['is_active'], $this['is_super_admin'], $this['updated_at'], $this['groups_list'], $this['permissions_list'], $this['cart'], $this['last_login'], $this['last_ip'], $this['personal_recomendation'], $this['created_at'], $this['updated_at'], $this['salt'], $this['algorithm']
        );
    }

}