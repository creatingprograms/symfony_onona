<?php

/**
 * Project form base class.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormDoctrine extends sfFormDoctrine {
//abstract class BaseFormDoctrine extends ahBaseFormDoctrine {

    public function setup() {
        if (isset($this->widgetSchema['content']))
            $this->widgetSchema['content'] = new sfWidgetFormCKEditor();


        if (isset($this->widgetSchema['precontent']))
            $this->widgetSchema['precontent'] = new sfWidgetFormCKEditor();

        if (isset($this->widgetSchema['content_mobile']))
            $this->widgetSchema['content_mobile'] = new sfWidgetFormCKEditor();

        if (isset($this->widgetSchema['content_mo']))
            $this->widgetSchema['content_mo'] = new sfWidgetFormCKEditor();

        if (isset($this->widgetSchema['content_mo_mobile']))
            $this->widgetSchema['content_mo_mobile'] = new sfWidgetFormCKEditor();


        unset($this['updated_at']);
        //echo $this->getModelName();
        if ($this->getModelName() != "Product" and $this->getModelName() != "Comments" and $this->getModelName() != "Article" and $this->getModelName() != "News")
            unset($this['created_at']);
    }

    protected function doUpdateObject($values) {
        if (isset($this->widgetSchema['slug'])) {
            if ($this->object['slug'] != $values['slug']) {
                $logSlug = new Oldslug();
                $logSlug->setModule($this->getModelName());
                $logSlug->setOldslug($this->object['slug']);
                $logSlug->setNewslug($values['slug']);
                $logSlug->setDopid($this->object['id']);
                $logSlug->save();
            }
        }
        parent::doUpdateObject($values);
    }

}
