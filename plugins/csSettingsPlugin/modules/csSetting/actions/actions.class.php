<?php

require_once(dirname(dirname(__FILE__)) . '/lib/BasecsSettingActions.class.php');

/**
 * csSettingsActions
 *
 * @uses BasecsSettingsActions
 * @package
 * @version $id$
 * @copyright 2006-2007 Brent Shaffer
 * @author Brent Shaffer <cbshaffer@centresource.com>
 * @license See LICENSE that came packaged with this software
 */
class csSettingActions extends BasecsSettingActions {

    public function executeListSaveSettings(sfWebRequest $request) {
        parent::executeIndex($request);
        if ($settings = $request->getParameter('cs_setting')) {
            $this->form = new SettingsListForm();
            $this->form->bind($settings, $request->getfiles('cs_setting'));
            if ($this->form->isValid()) {

                foreach ($this->form->getValues() as $slug => $value) {
                    $setting = Doctrine::getTable('csSetting')->findOneBySlug($slug);
                    if ($setting) {
                        $setting->setValue($value);
                        $setting->save();
                    }
                }

                if ($files = $request->getFiles('cs_setting')) {
                    $this->processUpload($settings, $files);
                }


                //$frontend_cache_dir = '/var/www/ononaru/data/www/cache/frontend/*/template';
                //$cache = new sfFileCache(array('cache_dir' => $frontend_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
                //$cache->removePattern('/csSettings/*');
//                $newdis_cache_dir = '/var/www/ononaru/data/www/cache/newdis/*/template';
//                $cache = new sfFileCache(array('cache_dir' => $newdis_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
//                $cache->removePattern('/csSettings/*');

                // Update form with new values
                $this->form = new SettingsListForm();


                $this->getUser()->setFlash('notice', 'Your settings have been saved.');
            } else {
                $this->getUser()->setFlash('error', 'Your form contains some errors');
            }
        }
        $this->setTemplate('index');
    }

}
