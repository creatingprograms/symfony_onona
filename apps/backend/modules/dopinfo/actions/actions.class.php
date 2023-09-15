<?php

require_once dirname(__FILE__) . '/../lib/dopinfoGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/dopinfoGeneratorHelper.class.php';

/**
 * dopinfo actions.
 *
 * @package    Magazin
 * @subpackage dopinfo
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class dopinfoActions extends autoDopinfoActions {

    protected function addSortQuery($query) {
        $query->addOrderBy('name ASC, value ASC');
    }


    public function executeSetFilterTag() {
        $this->setPage(1);
        $filtersTag['dicategory_id'] = array("0" => $this->getRequestParameter('filter'));

        $this->setFilters($filtersTag);

        $this->redirect('@dop_info');
    }
}
