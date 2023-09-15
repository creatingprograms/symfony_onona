<?php

/**
 * video actions.
 *
 * @package    test
 * @subpackage video
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class videoActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        /* $this->videos = Doctrine_Core::getTable('Video')
          ->createQuery('a')
          ->execute(); */
    }

    public function executePreshow(sfWebRequest $request) {
        $videoKeys = explode(",", $request->getParameter('videoKeys'));
        $idForFindVideoKeys = $request->getParameter('id');
        if (array_search($idForFindVideoKeys, $videoKeys) !== false) {
            $keyId = array_search($idForFindVideoKeys, $videoKeys);
            if ($keyId != 0) {
                $this->preId = $videoKeys[$keyId - 1];
            }
            if ($keyId != count($videoKeys) - 1) {
                $this->postId = $videoKeys[$keyId + 1];
            }
        }
        $this->videoKeys = $videoKeys;
        $video = Doctrine_Core::getTable('Video')->findOneById(array($request->getParameter('id')));

        $this->video = $video;
    }

    public function executeShow(sfWebRequest $request) {
        if ($request->getParameter('slug') == "Рекомендованное") {

            $this->videos = Doctrine_Core::getTable('Video')
                    ->createQuery('a')
                    ->where("is_public='1'")
                    ->addWhere("is_related='1'")
                    ->addWhere("manager_id is null")
                    ->orderBy("created_at desc")
                    ->execute();
        } elseif ($request->getParameter('slug') == "Новое") {
            $this->videos = Doctrine_Core::getTable('Video')
                    ->createQuery('a')
                    ->where("is_public='1'")
                    ->addWhere("UNIX_TIMESTAMP(created_at)>" . (time() - 30 * 24 * 60 * 60))
                    ->addWhere("manager_id is null")
                    ->orderBy("created_at desc")
                    ->execute();
        } else {
            $this->videos = Doctrine_Core::getTable('Video')
                    ->createQuery('a')
                    ->where("is_public='1' and tag like ?", '%' . $request->getParameter('slug') . '%')
                    ->addWhere("manager_id is null")
                    ->orderBy("created_at desc")
                    ->execute();
        }
        /* $this->video = Doctrine_Core::getTable('Video')->find(array($request->getParameter('id')));
          $this->forward404Unless($this->video); */
    }


}
