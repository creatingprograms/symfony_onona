<?php

/**
 * BaseQuiz
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $slug
 * @property clob $content
 * @property boolean $is_public
 * @property string $img
 * @property integer $views_count
 * @property integer $votes_count
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property Doctrine_Collection $QuizQuestion
 * @property Doctrine_Collection $QuizResult
 * 
 * @method string              getName()         Returns the current record's "name" value
 * @method string              getSlug()         Returns the current record's "slug" value
 * @method clob                getContent()      Returns the current record's "content" value
 * @method boolean             getIsPublic()     Returns the current record's "is_public" value
 * @method string              getImg()          Returns the current record's "img" value
 * @method integer             getViewsCount()   Returns the current record's "views_count" value
 * @method integer             getVotesCount()   Returns the current record's "votes_count" value
 * @method string              getTitle()        Returns the current record's "title" value
 * @method string              getKeywords()     Returns the current record's "keywords" value
 * @method string              getDescription()  Returns the current record's "description" value
 * @method Doctrine_Collection getQuizQuestion() Returns the current record's "QuizQuestion" collection
 * @method Doctrine_Collection getQuizResult()   Returns the current record's "QuizResult" collection
 * @method Quiz                setName()         Sets the current record's "name" value
 * @method Quiz                setSlug()         Sets the current record's "slug" value
 * @method Quiz                setContent()      Sets the current record's "content" value
 * @method Quiz                setIsPublic()     Sets the current record's "is_public" value
 * @method Quiz                setImg()          Sets the current record's "img" value
 * @method Quiz                setViewsCount()   Sets the current record's "views_count" value
 * @method Quiz                setVotesCount()   Sets the current record's "votes_count" value
 * @method Quiz                setTitle()        Sets the current record's "title" value
 * @method Quiz                setKeywords()     Sets the current record's "keywords" value
 * @method Quiz                setDescription()  Sets the current record's "description" value
 * @method Quiz                setQuizQuestion() Sets the current record's "QuizQuestion" collection
 * @method Quiz                setQuizResult()   Sets the current record's "QuizResult" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseQuiz extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('quiz');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 255,
             ));
        $this->hasColumn('slug', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 255,
             ));
        $this->hasColumn('content', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('is_public', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 1,
             ));
        $this->hasColumn('img', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('views_count', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('votes_count', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('keywords', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('QuizQuestion', array(
             'local' => 'id',
             'foreign' => 'quiz_id'));

        $this->hasMany('QuizResult', array(
             'local' => 'id',
             'foreign' => 'quiz_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'fields' => 
             array(
              0 => 'name',
             ),
             'unique' => true,
             'canUpdate' => false,
             'builder' => 
             array(
              0 => 'SlugifyClass',
              1 => 'Slugify',
             ),
             ));
        $this->actAs($timestampable0);
        $this->actAs($sluggable0);
    }
}