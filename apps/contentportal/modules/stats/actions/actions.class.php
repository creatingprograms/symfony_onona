<?php

/**
 * stats actions.
 *
 * @package    test
 * @subpackage stats
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statsActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeGpa(sfWebRequest $request) {
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerComments = sfTimerManager::getTimer('Action: Загрузка статистики коментариев');
        $this->statsComments = $q->execute("SELECT u.id, "
                        . "DATE_FORMAT( com.created_at,  \"%m.%y\" ) AS mounth, "
                        . "AVG( com.point ) AS avg, "
                        . "COUNT( DATE_FORMAT( com.created_at,  \"%y-%m\" ) ) AS countRecord, "
                        . "DATE_FORMAT( com.created_at, \"%m.%y\" ) AS mounth, "
                        . "u.first_name, "
                        . "u.last_name, "
                        . "u.email_address "
                        . "FROM  `comments` AS com "
                        . "LEFT JOIN sf_guard_user AS u "
                        . "ON u.id = com.manager_id "
                        . "WHERE com.manager_id !=  \"\" "
                        . "AND com.point >0 "
                        . "GROUP BY com.manager_id, "
                        . "DATE_FORMAT( com.created_at,  \"%y-%m\" ) "
                        . "order by com.created_at desc")
                ->fetchAll(Doctrine_Core::FETCH_GROUP);
        if (count($this->statsComments) > 0) {
            foreach ($this->statsComments as $u_id => $stats) {
                foreach ($stats as $stat) {
                    $statRows[$stat['mounth']][$u_id] = $stat;
                    $statsCommentsUser[$u_id] = $stat;
                }
            }
            $this->statsComments = $statRows;
            $this->statsCommentsUser = $statsCommentsUser;
        }
        $timerComments->addTime();



        $timerPhotos = sfTimerManager::getTimer('Action: Загрузка статистики фотографий');
        $this->statsPhotos = $q->execute("SELECT u.id, "
                        . "DATE_FORMAT( ph.created_at,  \"%m.%y\" ) AS mounth, "
                        . "AVG( ph.point ) AS avg, "
                        . "COUNT( DATE_FORMAT( ph.created_at,  \"%y-%m\" ) ) AS countRecord, "
                        . "DATE_FORMAT( ph.created_at, \"%m.%y\" ) AS mounth, "
                        . "u.first_name, "
                        . "u.last_name, "
                        . "u.email_address "
                        . "FROM  `photos_user` AS ph "
                        . "LEFT JOIN sf_guard_user AS u "
                        . "ON u.id = ph.manager_id "
                        . "WHERE ph.manager_id !=  \"\" "
                        . "AND ph.point >0 "
                        . "GROUP BY ph.manager_id, "
                        . "DATE_FORMAT( ph.created_at,  \"%y-%m\" ) "
                        . "order by ph.created_at desc")
                ->fetchAll(Doctrine_Core::FETCH_GROUP);
        if (count($this->statsPhotos) > 0) {
            foreach ($this->statsPhotos as $u_id => $stats) {
                foreach ($stats as $stat) {
                    $statPhotosRows[$stat['mounth']][$u_id] = $stat;
                    $statsPhotosUser[$u_id] = $stat;
                }
            }
            $this->statsPhotos = $statPhotosRows;
            $this->statsPhotosUser = $statsPhotosUser;
        }
        $timerPhotos->addTime();



        $timerVideos = sfTimerManager::getTimer('Action: Загрузка статистики видео');
        $this->statsVideos = $q->execute("SELECT u.id, "
                        . "DATE_FORMAT( v.created_at,  \"%m.%y\" ) AS mounth, "
                        . "AVG( v.point ) AS avg, "
                        . "COUNT( DATE_FORMAT( v.created_at,  \"%y-%m\" ) ) AS countRecord, "
                        . "DATE_FORMAT( v.created_at, \"%m.%y\" ) AS mounth, "
                        . "u.first_name, "
                        . "u.last_name, "
                        . "u.email_address "
                        . "FROM  `video` AS v "
                        . "LEFT JOIN sf_guard_user AS u "
                        . "ON u.id = v.manager_id "
                        . "WHERE v.manager_id !=  \"\" "
                        . "AND v.point >0 "
                        . "GROUP BY v.manager_id, "
                        . "DATE_FORMAT( v.created_at,  \"%y-%m\" ) "
                        . "order by v.created_at desc")
                ->fetchAll(Doctrine_Core::FETCH_GROUP);
        if (count($this->statsVideos) > 0) {
            foreach ($this->statsVideos as $u_id => $stats) {
                foreach ($stats as $stat) {
                    $statVideosRows[$stat['mounth']][$u_id] = $stat;
                    $statsVideosUser[$u_id] = $stat;
                }
            }
            $this->statsVideos = $statVideosRows;
            $this->statsVideosUser = $statsVideosUser;
        }
        $timerPhotos->addTime();
    }

}
