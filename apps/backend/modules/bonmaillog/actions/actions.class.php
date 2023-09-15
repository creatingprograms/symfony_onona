<?php

require_once dirname(__FILE__) . '/../lib/bonmaillogGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/bonmaillogGeneratorHelper.class.php';

/**
 * bonmaillog actions.
 *
 * @package    test
 * @subpackage bonmaillog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class bonmaillogActions extends autoBonmaillogActions {

    protected function addSortQuery($query) {
        $query->addOrderBy('created_at DESC ');
    }

}
