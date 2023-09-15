<?php

require_once dirname(__FILE__) . '/../lib/senduserGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/senduserGeneratorHelper.class.php';

/**
 * senduser actions.
 *
 * @package    Magazin
 * @subpackage senduser
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class senduserActions extends autoSenduserActions {
    /* protected function addSortQuery($query) {
      //$query->addOrderBy('created_at DESC');
      } */

    protected function addSortQuery($query) {

        if (array(null, null) == ($sort = $this->getSort())) {
            return;
        }

        if (!in_array(strtolower($sort[1]), array('asc', 'desc'))) {
            $sort[1] = 'asc';
        }
        if ($sort[0] != "position"){
            if($sort[0]=="updated_at"){
            $query->addOrderBy('is_send ' . $sort[1]);
                
            }
            $query->addOrderBy($sort[0] . ' ' . $sort[1]);
        }
       
    }

}
