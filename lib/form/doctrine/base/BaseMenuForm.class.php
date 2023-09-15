<?php

/**
 * Menu form base class.
 *
 * @method Menu getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMenuForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'url'           => new sfWidgetFormInputText(),
      'text'          => new sfWidgetFormInputText(),
      'conter_target' => new sfWidgetFormInputText(),
      'positionmenu'  => new sfWidgetFormChoice(array('choices' => array('Под шапкой' => 'Под шапкой', 'Левое' => 'Левое', 'Над шапкой(новый дизайн)' => 'Над шапкой(новый дизайн)', 'Под шапкой(новый дизайн)' => 'Под шапкой(новый дизайн)', 'footer-fix' => 'footer-fix', 'top_menu_new' => 'top_menu_new', 'top_menu_new_frontend' => 'top_menu_new_frontend', 'main_frontend' => 'main_frontend', 'footer_frontend' => 'footer_frontend', 'mobile_frontend' => 'mobile_frontend'))),
      'parents_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'target_blank'  => new sfWidgetFormInputCheckbox(),
      'is_active'     => new sfWidgetFormInputCheckbox(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
      'position'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'url'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'text'          => new sfValidatorString(array('max_length' => 255)),
      'conter_target' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'positionmenu'  => new sfValidatorChoice(array('choices' => array(0 => 'Под шапкой', 1 => 'Левое', 2 => 'Над шапкой(новый дизайн)', 3 => 'Под шапкой(новый дизайн)', 4 => 'footer-fix', 5 => 'top_menu_new', 6 => 'top_menu_new_frontend', 7 => 'main_frontend', 8 => 'footer_frontend', 9 => 'mobile_frontend'), 'required' => false)),
      'parents_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'required' => false)),
      'target_blank'  => new sfValidatorBoolean(array('required' => false)),
      'is_active'     => new sfValidatorBoolean(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
      'position'      => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Menu', 'column' => array('position')))
    );

    $this->widgetSchema->setNameFormat('menu[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Menu';
  }

}
