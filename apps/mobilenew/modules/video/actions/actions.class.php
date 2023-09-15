<?php

/**
 * video actions.
 *
 * @package    test
 * @subpackage video
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class videoActions extends sfActions
{
    
    public function executeShow(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        
        $timerVideos = sfTimerManager::getTimer('Action: Загрузка видео');

        $this->videos = $q->execute("SELECT id, name, slug, photo, youtubelink from video "
                . "WHERE is_public='1' and youtubelink!='' "
                . "ORDER BY rand() ");
        $timerVideos->addTime();
    }
}
