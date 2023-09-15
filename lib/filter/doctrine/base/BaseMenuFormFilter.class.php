<?php

/**
 * Menu filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMenuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'url'           => new sfWidgetFormFilterInput(),
      'text'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'conter_target' => new sfWidgetFormFilterInput(),
      'positionmenu'  => new sfWidgetFormChoice(array('choices' => array('' => '', 'Под шапкой' => 'Под шапкой', 'Левое' => 'Левое', 'Над шапкой(новый дизайн)' => 'Над шапкой(новый дизайн)', 'Под шапкой(новый дизайн)' => 'Под шапкой(новый дизайн)', 'footer-fix' => 'footer-fix', 'top_menu_new' => 'top_menu_new', 'top_menu_new_frontend' => 'top_menu_new_frontend', 'main_frontend' => 'main_frontend', 'footer_frontend' => 'footer_frontend', 'mobile_frontend' => 'mobile_frontend'))),
      'parents_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'target_blank'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_active'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'url'           => new sfValidatorPass(array('required' => false)),
      'text'          => new sfValidatorPass(array('required' => false)),
      'conter_target' => new sfValidatorPass(array('required' => false)),
      'positionmenu'  => new sfValidatorChoice(array('required' => false, 'choices' => array('Под шапкой' => 'Под шапкой', 'Левое' => 'Левое', 'Над шапкой(новый дизайн)' => 'Над шапкой(новый дизайн)', 'Под шапкой(новый дизайн)' => 'Под шапкой(новый дизайн)', 'footer-fix' => 'footer-fix', 'top_menu_new' => 'top_menu_new', 'top_menu_new_frontend' => 'top_menu_new_frontend', 'main_frontend' => 'main_frontend', 'footer_frontend' => 'footer_frontend', 'mobile_frontend' => 'mobile_frontend'))),
      'parents_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Parent'), 'column' => 'id')),
      'target_blank'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_active'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('menu_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Menu';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'url'           => 'Text',
      'text'          => 'Text',
      'conter_target' => 'Text',
      'positionmenu'  => 'Enum',
      'parents_id'    => 'ForeignKey',
      'target_blank'  => 'Boolean',
      'is_active'     => 'Boolean',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
      'position'      => 'Number',
    );
  }
}
