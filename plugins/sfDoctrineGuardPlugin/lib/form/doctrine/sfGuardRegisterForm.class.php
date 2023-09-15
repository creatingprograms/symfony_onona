<?php

/**
 * sfGuardRegisterForm for registering new users
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: BasesfGuardChangeUserPasswordForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardRegisterForm extends BasesfGuardRegisterForm {

    /**
     * @see sfForm
     */
    public function configure() {
        unset($this['activephone'],$this['activephonecode'],$this['oldphone'],$this['ssidentity']);

        $positionForm[] = 'first_name';
        $positionForm[] = 'last_name';
        $positionForm[] = 'sex';
        $positionForm[] = 'birthday';
        $positionForm[] = 'email_address';
        $positionForm[] = 'phone';
        $positionForm[] = 'password';
        $positionForm[] = 'password_again';
        $positionForm[] = 'username';
        $positionForm[] = 'index_house';
        $positionForm[] = 'city';
        $positionForm[] = 'street';
        $positionForm[] = 'house';
        $positionForm[] = 'korpus';
        $positionForm[] = 'apartament';
        $positionForm[] = 'id';




        $this->widgetSchema['sex'] = new sfWidgetFormChoice(array(
                    'choices' => sfGuardUser::getSexChoices(),
                    'expanded' => true,
                ));

        $this->validatorSchema['sex'] = new sfValidatorChoice(array(
                    'choices' => array_keys(sfGuardUser::getSexChoices())
                ));


        $this->widgetSchema->setPositions($positionForm);
        //print_r($this->widgetSchema); print_r($positionForm);

        $this->widgetSchema['email_address'] = new sfWidgetFormInput(array(),array("onblur"=>"var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;if(regex.test(this.value)) { try {rrApi.setEmail(this.value);sendRRWelcome(this.value);}catch(e){}}"));

        $this->widgetSchema['password'] = new sfWidgetFormInput();
        $this->validatorSchema['password']->setOption('required', false);
        $this->widgetSchema['password_again'] = new sfWidgetFormInput();
        $this->validatorSchema['password_again'] = clone $this->validatorSchema['password'];
        $this->widgetSchema->setLabels(array(
            'first_name' => '*Имя',
            'last_name' => 'Фамилия',
            'sex' => '*Пол',
            'email_address' => '*E-mail (логин)',
            'username' => 'Логин*',
            'password' => '*Пароль',
            'password_again' => '*Повторите пароль',
            'birthday' => 'Дата рождения',
            'phone' => '*Телефон',
            'index_house' => 'Индекс',
            'city' => '*Город',
            'street' => 'Улица',
            'house' => 'Дом',
            'korpus' => 'Корпус/строение',
            'apartament' => 'Квартира/офис',
        ));

        $years = range(date('Y') - 18, date('Y') - 90);
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

        $this->validatorSchema['phone']->setOption('required', true);


        $this->validatorSchema['first_name'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['last_name'] = new sfValidatorString(array('max_length' => 255, 'required' => false));
        $this->validatorSchema['phone'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['city'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        //$this->validatorSchema['street'] = new sfValidatorString(array('max_length' => 255, 'required' => true));

    }

}
