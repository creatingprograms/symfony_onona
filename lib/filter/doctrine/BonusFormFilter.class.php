<?php

/**
 * Bonus filter form.
 *
 * @package    Magazin
 * @subpackage filter
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BonusFormFilter extends BaseBonusFormFilter {

    public function configure() {
       // $this->setWidget('user_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'method' => 'getSelectView')));
    
        $this->setWidget('user_id', new sfWidgetFormInputText());
    
        $this->setValidator('user_id', new sfValidatorString(array('required' => false)));
    
    }

}
