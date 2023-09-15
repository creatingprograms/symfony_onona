<?php

require_once dirname(__FILE__).'/../lib/vacancyGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/vacancyGeneratorHelper.class.php';

/**
 * vacancy actions.
 *
 * @package    test
 * @subpackage vacancy
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class vacancyActions extends autoVacancyActions
{

    public function executePublicChange() {
        $object = Doctrine::getTable('Vacancy')->findOneById($this->getRequestParameter('id'));

        $object->setIsPublic($object->getIsPublic() ? 0 : 1);
        $object->save();
        $this->vacancy = $object;
    }
    public function executePublicMainPageChange() {
        $object = Doctrine::getTable('Vacancy')->findOneById($this->getRequestParameter('id'));

        $object->setIsPublicmainpage($object->getIsPublicmainpage() ? 0 : 1);
        $object->save();
        $this->vacancy = $object;
    }
}
