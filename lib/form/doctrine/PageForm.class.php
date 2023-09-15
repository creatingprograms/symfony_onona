<?php

/**
 * Page form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PageForm extends BasePageForm {

    public function configure() {
        unset($this['news_list']);
        $this->widgetSchema['content'] = new sfWidgetFormCKEditor();
        $this->widgetSchema['content_new_version'] = new sfWidgetFormCKEditor();

        $this->setWidget('city_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'order_by' => array('name', 'ASC'), 'add_empty'=> true)));
    }

}
