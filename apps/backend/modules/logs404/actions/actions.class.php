<?php

require_once dirname(__FILE__).'/../lib/logs404GeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/logs404GeneratorHelper.class.php';

/**
 * logs404 actions.
 *
 * @package    test
 * @subpackage logs404
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class logs404Actions extends autoLogs404Actions
{
    protected function addSortQuery($query) {
        $query->addOrderBy('created_at DESC');
    }
}
