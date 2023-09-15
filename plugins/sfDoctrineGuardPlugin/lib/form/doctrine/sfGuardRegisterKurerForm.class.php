<?php

/**
 * sfGuardRegisterForm for registering new users
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: BasesfGuardChangeUserPasswordForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardRegisterKurerForm extends BasesfGuardRegisterForm {

    /**
     * @see sfForm
     */
    public function configure() {


        $this->widgetSchema['password'] = new sfWidgetFormInput();
        $this->validatorSchema['password']->setOption('required', true);
        $this->widgetSchema['password_again'] = new sfWidgetFormInput();
        $this->validatorSchema['password_again'] = clone $this->validatorSchema['password'];
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
        $years = range(date('Y')-18, date('Y') - 90);
        $years_list = array_combine($years, $years);
        $this->widgetSchema['birthday']->setOption('format', '%day%<span style="float: left">.</span>%month%<span style="float: left">.</span>%year%');
        $this->widgetSchema['birthday']->setOption('years', $years_list);

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
        $this->validatorSchema['house'] = new sfValidatorString(array('max_length' => 255, 'required' => false));
        $this->validatorSchema['apartament'] = new sfValidatorString(array('max_length' => 255, 'required' => false));

        unset(
                $this['activephone'],$this['activephonecode'],$this['oldphone'],$this['last_name'], $this['index_house'], $this['korpus'], $this['permissions_list'], $this['cart']
        );
    }

}