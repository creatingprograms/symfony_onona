<?php

/**
 * Faqcategory filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseFaqcategoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'               => new sfWidgetFormFilterInput(),
      'content'            => new sfWidgetFormFilterInput(),
      'is_public'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'title'              => new sfWidgetFormFilterInput(),
      'keywords'           => new sfWidgetFormFilterInput(),
      'description'        => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'           => new sfWidgetFormFilterInput(),
      'slug'               => new sfWidgetFormFilterInput(),
      'category_faqs_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Faq')),
    ));

    $this->setValidators(array(
      'name'               => new sfValidatorPass(array('required' => false)),
      'content'            => new sfValidatorPass(array('required' => false)),
      'is_public'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'title'              => new sfValidatorPass(array('required' => false)),
      'keywords'           => new sfValidatorPass(array('required' => false)),
      'description'        => new sfValidatorPass(array('required' => false)),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'slug'               => new sfValidatorPass(array('required' => false)),
      'category_faqs_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Faq', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('faqcategory_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addCategoryFaqsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('CategoryFaq.faq_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Faqcategory';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'name'               => 'Text',
      'content'            => 'Text',
      'is_public'          => 'Boolean',
      'title'              => 'Text',
      'keywords'           => 'Text',
      'description'        => 'Text',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
      'position'           => 'Number',
      'slug'               => 'Text',
      'category_faqs_list' => 'ManyKey',
    );
  }
}
