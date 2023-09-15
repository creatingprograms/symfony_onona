<?php

/**
 * TestsResult form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TestsResultForm extends BaseTestsResultForm {

    public function configure() {
        //$this->widgetSchema['results'] = new sfWidgetFormCKEditor();
        $this->widgetSchema['balls_to']->setLabel('Баллы(от)');
        $this->widgetSchema['balls_from']->setLabel('Баллы(до)');
        $this->widgetSchema['results']->setLabel('Результат');

        if ($this->object->exists()) {
            $this->widgetSchema['delete'] = new sfWidgetFormInputCheckbox();
            $this->validatorSchema['delete'] = new sfValidatorPass();
        }

        unset($this['test_id']);
    }

}
