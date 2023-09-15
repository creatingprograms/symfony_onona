<?php

require_once dirname(__FILE__) . '/../lib/collectionGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/collectionGeneratorHelper.class.php';

/**
 * collection actions.
 *
 * @package    test
 * @subpackage collection
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class collectionActions extends autoCollectionActions {

    public function __construct($context, $moduleName, $controllerName) {
        parent::__construct($context, $moduleName, $controllerName);
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры"))
            if ($controllerName != "index" and $controllerName != "editseo" and $controllerName != "filter" and $controllerName != "update")
                $this->redirect("@collection");
    }

    public function executeEditseo(sfWebRequest $request) {
        $this->collection = $this->getRoute()->getObject();
        $this->form = new CollectionSEOForm($this->collection);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->collection = $this->getRoute()->getObject();
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")) {
            $this->form = new CollectionSEOForm($this->collection);
        } else {
            $this->form = $this->configuration->getForm($this->collection);
        };
        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

}
