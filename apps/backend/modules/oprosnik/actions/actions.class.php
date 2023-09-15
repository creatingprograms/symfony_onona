<?php

require_once dirname(__FILE__).'/../lib/oprosnikGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/oprosnikGeneratorHelper.class.php';

/**
 * oprosnik actions.
 *
 * @package    test
 * @subpackage oprosnik
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class oprosnikActions extends autoOprosnikActions
{
    protected function addSortQuery($query) {
        $query->addOrderBy('created_at DESC');
    }
}
