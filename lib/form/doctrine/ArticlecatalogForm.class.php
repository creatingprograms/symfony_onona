<?php

/**
 * Articlecatalog form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ArticlecatalogForm extends BaseArticlecatalogForm
{
  public function configure()
  {
      unset($this['position']);

        $timerCategory = sfTimerManager::getTimer('Form: Загрузка всех категорий');
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $choiseCategory = $q->execute("SELECT id,name FROM  `articlecategory`")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $this->setWidget('category_list', new sfWidgetFormDoctrineChoiceSearchArray(array('multiple' => true, 'choises' => $choiseCategory, 'add_empty' => true)));
        $timerCategory->addTime();
  }
}
