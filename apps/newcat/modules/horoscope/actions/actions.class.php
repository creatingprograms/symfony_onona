<?php

/**
 * horoscope actions.
 *
 * @package    test
 * @subpackage horoscope
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class horoscopeActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->horoscopes = Doctrine_Core::getTable('Horoscope')
                ->createQuery('a')
                ->execute();
    }

    public function executeSovm(sfWebRequest $request) {
       $this->horoscopesovm = HoroscopesovmTable::getInstance()->createQuery()->where("horoscope_m_id=?", $request->getParameter('horoscope_m_id'))->addWhere("horoscope_g_id=?",  $request->getParameter('horoscope_g_id'))->fetchOne();
    }

    public function executeShow(sfWebRequest $request) {
        $this->horoscope = Doctrine_Core::getTable('Horoscope')->findOneBySlug(array($request->getParameter('slug')));
        $this->forward404Unless($this->horoscope);

        $this->horoscopes = Doctrine_Core::getTable('Horoscope')
                ->createQuery('a')
                ->execute();
    }

}
