<?php

/**
 * Article filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseArticleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'                 => new sfWidgetFormFilterInput(),
      'precontent'           => new sfWidgetFormFilterInput(),
      'content'              => new sfWidgetFormFilterInput(),
      'is_public'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_new'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'tags'                 => new sfWidgetFormFilterInput(),
      'img'                  => new sfWidgetFormFilterInput(),
      'img_preview'          => new sfWidgetFormFilterInput(),
      'views_count'          => new sfWidgetFormFilterInput(),
      'votes_count'          => new sfWidgetFormFilterInput(),
      'rating'               => new sfWidgetFormFilterInput(),
      'is_related'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'positionrelated'      => new sfWidgetFormFilterInput(),
      'title'                => new sfWidgetFormFilterInput(),
      'keywords'             => new sfWidgetFormFilterInput(),
      'description'          => new sfWidgetFormFilterInput(),
      'moder'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'user'                 => new sfWidgetFormFilterInput(),
      'expert_id'            => new sfWidgetFormFilterInput(),
      'category_slug'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'video'                => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'             => new sfWidgetFormFilterInput(),
      'articlecategory_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Articlecategory')),
    ));

    $this->setValidators(array(
      'name'                 => new sfValidatorPass(array('required' => false)),
      'slug'                 => new sfValidatorPass(array('required' => false)),
      'precontent'           => new sfValidatorPass(array('required' => false)),
      'content'              => new sfValidatorPass(array('required' => false)),
      'is_public'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_new'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'tags'                 => new sfValidatorPass(array('required' => false)),
      'img'                  => new sfValidatorPass(array('required' => false)),
      'img_preview'          => new sfValidatorPass(array('required' => false)),
      'views_count'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'votes_count'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rating'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_related'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'positionrelated'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'                => new sfValidatorPass(array('required' => false)),
      'keywords'             => new sfValidatorPass(array('required' => false)),
      'description'          => new sfValidatorPass(array('required' => false)),
      'moder'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'user'                 => new sfValidatorPass(array('required' => false)),
      'expert_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category_slug'        => new sfValidatorPass(array('required' => false)),
      'video'                => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'articlecategory_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Articlecategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('article_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addArticlecategoryListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.CategoryArticle CategoryArticle')
      ->andWhereIn('CategoryArticle.articlecategory_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Article';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'name'                 => 'Text',
      'slug'                 => 'Text',
      'precontent'           => 'Text',
      'content'              => 'Text',
      'is_public'            => 'Boolean',
      'is_new'               => 'Boolean',
      'tags'                 => 'Text',
      'img'                  => 'Text',
      'img_preview'          => 'Text',
      'views_count'          => 'Number',
      'votes_count'          => 'Number',
      'rating'               => 'Number',
      'is_related'           => 'Boolean',
      'positionrelated'      => 'Number',
      'title'                => 'Text',
      'keywords'             => 'Text',
      'description'          => 'Text',
      'moder'                => 'Boolean',
      'user'                 => 'Text',
      'expert_id'            => 'Number',
      'category_slug'        => 'Text',
      'video'                => 'Text',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
      'position'             => 'Number',
      'articlecategory_list' => 'ManyKey',
    );
  }
}
