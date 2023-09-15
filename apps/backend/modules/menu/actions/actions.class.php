<?php

require_once dirname(__FILE__).'/../lib/menuGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/menuGeneratorHelper.class.php';

/**
 * menu actions.
 *
 * @package    Magazin
 * @subpackage menu
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class menuActions extends autoMenuActions
{
    

    public function executePromote() {
        $object = Doctrine::getTable('Menu')->findOneById($this->getRequestParameter('id'));


        $object->promote();
       
        $this->redirect("@menu");
    }

    public function executeDemote() {
        $object = Doctrine::getTable('Menu')->findOneById($this->getRequestParameter('id'));

        $object->demote();
        
        $this->redirect("@menu");
    }
}
