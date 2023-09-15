<?php

require_once dirname(__FILE__) . '/../lib/horoscopesovmGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/horoscopesovmGeneratorHelper.class.php';

/**
 * horoscopesovm actions.
 *
 * @package    test
 * @subpackage horoscopesovm
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class horoscopesovmActions extends autoHoroscopesovmActions {

    /*public function executeSearch(sfWebRequest $request) {
        for ($m = 1; $m <= 12; $m++) {
            for ($g = 1; $g <= 12; $g++) {
                $horoscopesovm=new Horoscopesovm();
                $horoscopesovm->set("horoscope_m_id",$m);
                $horoscopesovm->set("horoscope_g_id",$g);
                $horoscopesovm->save();
            }
        }
        return true;
    }*/

}
