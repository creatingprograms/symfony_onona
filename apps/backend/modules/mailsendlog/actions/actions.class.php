<?php

require_once dirname(__FILE__).'/../lib/mailsendlogGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/mailsendlogGeneratorHelper.class.php';

/**
 * mailsendlog actions.
 *
 * @package    test
 * @subpackage mailsendlog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mailsendlogActions extends autoMailsendlogActions
{
    protected function addSortQuery($query) {
        $query->addOrderBy('id DESC');
    }
}
