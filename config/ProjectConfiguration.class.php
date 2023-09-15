<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->setWebDir($this->getRootDir().'/web');
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('csDoctrineActAsSortablePlugin');
    $this->enablePlugins('sfFCKEditorPlugin');
    $this->enablePlugins('sfWidgetFormInputSWFUploadPlugin');
    $this->enablePlugins('sfImageTransformPlugin');
    $this->enablePlugins('csSettingsPlugin');
    $this->enablePlugins('sfDoctrineGuardPlugin');
    $this->enablePlugins('sfCKEditorPlugin');
    
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('ahDoctrineEasyEmbeddedRelationsPlugin');
    $this->enablePlugins('sfJQueryUIPlugin');
  }
  
 
  /*public function configureDoctrine(Doctrine_Manager $manager)
  {	
    $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, new Doctrine_Cache_Apc());	
  } */
}

