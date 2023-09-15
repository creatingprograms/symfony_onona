<?php

require_once dirname(__FILE__) . '/../lib/validw3GeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/validw3GeneratorHelper.class.php';

/**
 * validw3 actions.
 *
 * @package    test
 * @subpackage validw3
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class validw3Actions extends autoValidw3Actions {


    protected function addSortQuery($query) {
        if (@$_GET['sort'] == "")
            $query->addOrderBy('created_at DESC');
        else
            $query->addOrderBy($_GET['sort'] . ' ' . $_GET['sort_type']);
    }

}
