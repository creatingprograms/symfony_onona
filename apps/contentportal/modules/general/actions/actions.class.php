<?php

/**
 * general actions.
 *
 * @package    test
 * @subpackage general
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class generalActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка лучших комментарий');
        $this->comments = $q->execute("SELECT * FROM comments where point > 0 ORDER BY point desc,id desc limit 30")->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerPage->addTime();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка лучших фото');
        $this->photos = $q->execute("SELECT * FROM photos_user where point > 0 ORDER BY point desc,id desc limit 30")->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerPage->addTime();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка лучших видео');
        $this->videos = $q->execute("SELECT * FROM video where point > 0 ORDER BY point desc,id desc limit 30")->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $timerPage->addTime();
  }
}
