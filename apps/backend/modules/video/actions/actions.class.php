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

    public function executePublicChange() {
        $object = Doctrine::getTable('Video')->findOneById($this->getRequestParameter('id'));

        $object->setIsPublic($object->getIsPublic() ? 0 : 1);
        $object->save();
        $this->video = $object;
    }
    public function executePublicMainPageChange() {
        $object = Doctrine::getTable('Video')->findOneById($this->getRequestParameter('id'));

        $object->setIsPublicmainpage($object->getIsPublicmainpage() ? 0 : 1);
        $object->save();
        $this->video = $object;
    }
}
