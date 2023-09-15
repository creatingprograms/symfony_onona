<?php

class sfCacheManager {

    const app_name = 'newcat';

    static protected $env_types = array('prod'=>false, 'dev'=>true);
    static protected $is_debug;
    static protected $prev_application;
    static protected $prev_sfconfig;

    //general method for clearing cache when using sfFileCache, sfSQLiteCache and others...
    static public function clearCache($pattern) {
        self::$prev_sfconfig = sfConfig::getAll();
        $currentConfiguration = sfContext::getInstance()->getConfiguration();

        self::$env_types[] = $currentConfiguration->getEnvironment();
        self::$env_types = array_unique(self::$env_types);
        self::$prev_application = $currentConfiguration->getApplication();
        self::$is_debug = $currentConfiguration->isDebug();

        foreach (self::$env_types as $env=>$isDebug) {
            $frontend_context = self::getFrontendContext($env,$isDebug);
            $view_cache_manager = $frontend_context->getViewCacheManager();

            if ($view_cache_manager) {
                //$view_cache = $view_cache_manager->getCache();
                $view_cache_manager->remove($pattern);
            }
        }
        self::returnToDefaultContext();
    }

    static protected function getFrontendContext($env,$isDebug) {
        $configuration = ProjectConfiguration::getApplicationConfiguration(self::app_name, $env, $isDebug);
        //initializing frontend instance with different env types
        return sfContext::createInstance($configuration, sprintf('system_%s', $env));
    }

    static protected function returnToDefaultContext() {
        //switching back to previous context
        if (sfContext::hasInstance(self::$prev_application)) {
            sfContext::switchTo(self::$prev_application);
            $sf_config_diff = array_diff(self::$prev_sfconfig, sfConfig::getAll());
            sfConfig::add($sf_config_diff);
        }
    }

}
