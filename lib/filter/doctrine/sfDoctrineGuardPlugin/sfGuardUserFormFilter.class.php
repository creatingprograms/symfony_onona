<?php

/**
 * sfGuardUser filter form.
 *
 * @package    Magazin
 * @subpackage filter
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrinePluginFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserFormFilter extends PluginsfGuardUserFormFilter {

    public function configure() {
      /*  $this->setWidget('ssidentity', new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', '' => 'yes', 0 => 'no'))));

        $this->setValidator('is_super_admin', new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))));*/
        $this->widgetSchema['ssidentity']->setLabel('Ssidentity<br/> Ввести: <br /> <b>http</b> - выведет зарегистрированных через соц.сеть.');
    }

}
