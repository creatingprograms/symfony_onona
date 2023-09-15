<?php

/**
 * Page form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PageSEOForm extends BasePageForm {

    public function configure() {
        $this->widgetSchema['content'] = new sfWidgetFormCKEditor();

        $this->setWidget('city_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'order_by' => array('name', 'ASC'), 'add_empty' => true)));

        unset($this['news_list'],$this['city_id'],$this['slug'],$this['sitemapRate'],$this['is_public'],$this['is_show_right_block'],$this['position']);
    }

}
