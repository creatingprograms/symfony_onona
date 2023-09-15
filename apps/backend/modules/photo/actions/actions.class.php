<?php

require_once dirname(__FILE__) . '/../lib/photoGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/photoGeneratorHelper.class.php';

/**
 * photo actions.
 *
 * @package    Magazin
 * @subpackage photo
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class photoActions extends autoPhotoActions {

    public function executeSetFilterTag() {
        $this->setPage(1);
        $filtersTag['album_id'] = array("0" => $this->getRequestParameter('filter'));

        $this->setFilters($filtersTag);

        $this->redirect('@photo');
    }

    public function executeSimpledelete(sfWebRequest $request) {

        $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));

        if ($this->getRoute()->getObject()->delete()) {
            $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
        }

        $this->redirect('@photo');
    }

}
