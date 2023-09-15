<?php

/**
 * sfGuardUser form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserStep3KurerForm extends PluginsfGuardUserForm {

    public function configure() {
        $this->widgetSchema->setLabels(array(
            'first_name' => '*Имя',
            'last_name' => 'Фамилия',
            'email_address' => '*E-mail (логин)',
            'username' => 'Логин*',
            'password' => '*Пароль',
            'password_again' => '*Повторите пароль',
            'birthday' => 'Дата рождения',
            'phone' => '*Телефон',
            'index_house' => 'Индекс',
            'city' => '*Город',
            'street' => '*Улица/Метро',
            'house' => 'Дом',
            'korpus' => 'Корпус/строение',
            'apartament' => 'Квартира/офис',
        ));
        $years = range(date('Y') - 18, date('Y') - 90);
        $years_list = array_combine($years, $years);
        $this->widgetSchema['birthday']->setOption('format', '%day%<span style="float: left">.</span>%month%<span style="float: left">.</span>%year%');
        $this->widgetSchema['birthday']->setOption('years', $years_list);



        $this->validatorSchema['first_name'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['last_name'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['phone'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['city'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['street'] = new sfValidatorString(array('max_length' => 255, 'required' => true));
        $this->validatorSchema['house'] = new sfValidatorString(array('max_length' => 255, 'required' => false));
        $this->validatorSchema['apartament'] = new sfValidatorString(array('max_length' => 255, 'required' => false));
        unset($this['algorithm'], $this['username'], $this['password'], $this['salt'], $this['is_active'], $this['is_super_admin'], $this['last_login'], $this['last_ip'], $this['personal_recomendation'], $this['groups_list'], $this['permissions_list'], $this['cart'], $this['last_name'], $this['index_house'], $this['korpus']);
    }

}