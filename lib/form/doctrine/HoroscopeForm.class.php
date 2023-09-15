<?php

/**
 * Horoscope form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class HoroscopeForm extends BaseHoroscopeForm
{
  public function configure()
  {
      
            $this->widgetSchema['info'] = new sfWidgetFormCKEditor();
            $this->widgetSchema['month'] = new sfWidgetFormCKEditor();
            $this->widgetSchema['year'] = new sfWidgetFormCKEditor();
            $this->widgetSchema['characteristic'] = new sfWidgetFormCKEditor();
            $this->widgetSchema['compatibility'] = new sfWidgetFormCKEditor();
            unset($this['image'],$this['compatibility']);
  }
}
