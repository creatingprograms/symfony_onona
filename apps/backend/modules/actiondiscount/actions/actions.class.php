<?php

require_once dirname(__FILE__).'/../lib/actiondiscountGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/actiondiscountGeneratorHelper.class.php';

/**
 * actiondiscount actions.
 *
 * @package    test
 * @subpackage actiondiscount
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class actiondiscountActions extends autoActiondiscountActions
{
    protected function addSortQuery($query) {
        if (@$_GET['sort'] == "")
            $query->addOrderBy('created_at DESC');
        else
            $query->addOrderBy($_GET['sort'] . ' ' . $_GET['sort_type']);
    }
}
