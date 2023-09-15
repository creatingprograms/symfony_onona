<?php

/**
 * Comments filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCommentsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'text'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'product_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'add_empty' => true)),
      'page_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true)),
      'shops_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Shops'), 'add_empty' => true)),
      'article_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Article'), 'add_empty' => true)),
      'compare_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Compare'), 'add_empty' => true)),
      'customer_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'sort_index'      => new sfWidgetFormFilterInput(),
      'is_public'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_onmain'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_novice'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'username'        => new sfWidgetFormFilterInput(),
      'mail'            => new sfWidgetFormFilterInput(),
      'answer'          => new sfWidgetFormFilterInput(),
      'rate_plus'       => new sfWidgetFormFilterInput(),
      'rate_minus'      => new sfWidgetFormFilterInput(),
      'rate_set'        => new sfWidgetFormFilterInput(),
      'point'           => new sfWidgetFormFilterInput(),
      'comment_manager' => new sfWidgetFormFilterInput(),
      'manager_id'      => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'text'            => new sfValidatorPass(array('required' => false)),
      'product_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Product'), 'column' => 'id')),
      'page_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Page'), 'column' => 'id')),
      'shops_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Shops'), 'column' => 'id')),
      'article_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Article'), 'column' => 'id')),
      'compare_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Compare'), 'column' => 'id')),
      'customer_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'sort_index'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_public'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_onmain'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_novice'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'username'        => new sfValidatorPass(array('required' => false)),
      'mail'            => new sfValidatorPass(array('required' => false)),
      'answer'          => new sfValidatorPass(array('required' => false)),
      'rate_plus'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rate_minus'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rate_set'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'point'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_manager' => new sfValidatorPass(array('required' => false)),
      'manager_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('comments_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Comments';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'text'            => 'Text',
      'product_id'      => 'ForeignKey',
      'page_id'         => 'ForeignKey',
      'shops_id'        => 'ForeignKey',
      'article_id'      => 'ForeignKey',
      'compare_id'      => 'ForeignKey',
      'customer_id'     => 'ForeignKey',
      'sort_index'      => 'Number',
      'is_public'       => 'Boolean',
      'is_onmain'       => 'Boolean',
      'is_novice'       => 'Boolean',
      'username'        => 'Text',
      'mail'            => 'Text',
      'answer'          => 'Text',
      'rate_plus'       => 'Number',
      'rate_minus'      => 'Number',
      'rate_set'        => 'Number',
      'point'           => 'Number',
      'comment_manager' => 'Text',
      'manager_id'      => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
