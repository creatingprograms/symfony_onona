<?php

require_once dirname(__FILE__).'/../lib/catalogGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/catalogGeneratorHelper.class.php';

/**
 * catalog actions.
 *
 * @package    test
 * @subpackage catalog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class catalogActions extends autoCatalogActions
{

    public function executePromote() {
        $object = Doctrine::getTable('Catalog')->findOneById($this->getRequestParameter('id'));


        $object->promote();
        $this->redirect("@catalog");
    }

    public function executeDemote() {
        $object = Doctrine::getTable('Catalog')->findOneById($this->getRequestParameter('id'));

        $object->demote();
        $this->redirect("@catalog");
    }
}
