<?php

/**
 * BasesfGuardRegisterForm for registering new users
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: BasesfGuardChangeUserPasswordForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class BasesfGuardRegisterForm extends sfGuardUserAdminForm {

    public function setup() {
        parent::setup();

        unset(
                $this['is_active'], $this['is_super_admin'], $this['updated_at'], $this['groups_list'], $this['permissions_list'], $this['cart']
        );

        $years = range(date('Y') - 18, date('Y') - 90);
        $years_list = array_combine($years, $years);
        $this->widgetSchema['birthday']->setOption('format', '%day%.%month%.%year%');
        $this->validatorSchema['password']->setOption('required', false);


        $this->validatorSchema->setPostValidator(
                new sfValidatorAnd(array(
                    new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('email_address')), array("invalid" => "Данный email уже зарегистрирован. Если это ваш email, пожалуйста <a href=\"#\" class=\"login\" onClick=\"<?/*$('#BackgrounAddProductToCard').css('height',$('.mainWrap').height());*/?>$('#login').fadeIn();<?/*$('#BackgrounAddProductToCard').fadeIn();*/?>return false;\">авторизуйтесь</a>.")),
                    new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('username'))),
                ))
        );
    }

}
