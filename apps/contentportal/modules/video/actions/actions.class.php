<?php

require_once dirname(__FILE__).'/../lib/videoGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/videoGeneratorHelper.class.php';

/**
 * video actions.
 *
 * @package    test
 * @subpackage video
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class videoActions extends autoVideoActions
{

    protected function getPager() {
        $pager = $this->configuration->getPager('Video');
        $pager->setQuery($this->buildQuery()->addWhere('manager_id IS not NULL')->orderBy("id desc"));
        $pager->setPage($this->getPage());
        $pager->init();

        return $pager;
    }
}
