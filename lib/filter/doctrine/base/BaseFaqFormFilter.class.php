<?php

/**
 * Faq filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseFaqFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'             => new sfWidgetFormFilterInput(),
      'precontent'       => new sfWidgetFormFilterInput(),
      'content'          => new sfWidgetFormFilterInput(),
      'is_public'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'img'              => new sfWidgetFormFilterInput(),
      'img_preview'      => new sfWidgetFormFilterInput(),
      'views_count'      => new sfWidgetFormFilterInput(),
      'is_related'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'positionrelated'  => new sfWidgetFormFilterInput(),
      'title'            => new sfWidgetFormFilterInput(),
      'keywords'         => new sfWidgetFormFilterInput(),
      'description'      => new sfWidgetFormFilterInput(),
      'category_slug'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'         => new sfWidgetFormFilterInput(),
      'faqcategory_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Faqcategory')),
    ));

    $this->setValidators(array(
      'name'             => new sfValidatorPass(array('required' => false)),
      'slug'             => new sfValidatorPass(array('required' => false)),
      'precontent'       => new sfValidatorPass(array('required' => false)),
      'content'          => new sfValidatorPass(array('required' => false)),
      'is_public'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'img'              => new sfValidatorPass(array('required' => false)),
      'img_preview'      => new sfValidatorPass(array('required' => false)),
      'views_count'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_related'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'positionrelated'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'            => new sfValidatorPass(array('required' => false)),
      'keywords'         => new sfValidatorPass(array('required' => false)),
      'description'      => new sfValidatorPass(array('required' => false)),
      'category_slug'    => new sfValidatorPass(array('required' => false)),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'faqcategory_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Faqcategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('faq_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addFaqcategoryListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.CategoryFaq CategoryFaq')
      ->andWhereIn('CategoryFaq.faqcategory_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Faq';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'name'             => 'Text',
      'slug'             => 'Text',
      'precontent'       => 'Text',
      'content'          => 'Text',
      'is_public'        => 'Boolean',
      'img'              => 'Text',
      'img_preview'      => 'Text',
      'views_count'      => 'Number',
      'is_related'       => 'Boolean',
      'positionrelated'  => 'Number',
      'title'            => 'Text',
      'keywords'         => 'Text',
      'description'      => 'Text',
      'category_slug'    => 'Text',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
      'position'         => 'Number',
      'faqcategory_list' => 'ManyKey',
    );
  }
}
