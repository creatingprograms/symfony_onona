<?php

require_once dirname(__FILE__).'/../lib/manufacturerGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/manufacturerGeneratorHelper.class.php';

/**
 * manufacturer actions.
 *
 * @package    test
 * @subpackage manufacturer
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class manufacturerActions extends autoManufacturerActions
{
    public function __construct($context, $moduleName, $controllerName) {
        parent::__construct($context, $moduleName, $controllerName);
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры"))
            if ($controllerName != "index" and $controllerName != "editseo" and $controllerName != "filter" and $controllerName != "update")
                $this->redirect("@manufacturer");
    }

    public function executeEditseo(sfWebRequest $request) {
        $this->manufacturer = $this->getRoute()->getObject();
        $this->form = new ManufacturerSEOForm($this->manufacturer);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->manufacturer = $this->getRoute()->getObject();
        if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")) {
            $this->form = new ManufacturerSEOForm($this->manufacturer);
        } else {
            $this->form = $this->configuration->getForm($this->manufacturer);
        };
        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }
}
