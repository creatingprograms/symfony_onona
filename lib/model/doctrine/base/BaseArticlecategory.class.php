<?php

/**
 * BaseArticlecategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property clob $content
 * @property boolean $is_public
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property Doctrine_Collection $CategoryArticles
 * @property Doctrine_Collection $CategoryarticleCatalogs
 * @property Doctrine_Collection $CategoryCatalogs
 * 
 * @method string              getName()                    Returns the current record's "name" value
 * @method clob                getContent()                 Returns the current record's "content" value
 * @method boolean             getIsPublic()                Returns the current record's "is_public" value
 * @method string              getTitle()                   Returns the current record's "title" value
 * @method string              getKeywords()                Returns the current record's "keywords" value
 * @method string              getDescription()             Returns the current record's "description" value
 * @method Doctrine_Collection getCategoryArticles()        Returns the current record's "CategoryArticles" collection
 * @method Doctrine_Collection getCategoryarticleCatalogs() Returns the current record's "CategoryarticleCatalogs" collection
 * @method Doctrine_Collection getCategoryCatalogs()        Returns the current record's "CategoryCatalogs" collection
 * @method Articlecategory     setName()                    Sets the current record's "name" value
 * @method Articlecategory     setContent()                 Sets the current record's "content" value
 * @method Articlecategory     setIsPublic()                Sets the current record's "is_public" value
 * @method Articlecategory     setTitle()                   Sets the current record's "title" value
 * @method Articlecategory     setKeywords()                Sets the current record's "keywords" value
 * @method Articlecategory     setDescription()             Sets the current record's "description" value
 * @method Articlecategory     setCategoryArticles()        Sets the current record's "CategoryArticles" collection
 * @method Articlecategory     setCategoryarticleCatalogs() Sets the current record's "CategoryarticleCatalogs" collection
 * @method Articlecategory     setCategoryCatalogs()        Sets the current record's "CategoryCatalogs" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseArticlecategory extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('articlecategory');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('content', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('is_public', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
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
        $this->hasMany('Article as CategoryArticles', array(
             'refClass' => 'CategoryArticle',
             'local' => 'articlecategory_id',
             'foreign' => 'article_id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('Articlecatalog as CategoryarticleCatalogs', array(
             'refClass' => 'CategoryarticleCatalog',
             'local' => 'articlecategory_id',
             'foreign' => 'articlecatalog_id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('CategoryarticleCatalog as CategoryCatalogs', array(
             'local' => 'id',
             'foreign' => 'articlecategory_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $sortable0 = new Doctrine_Template_Sortable();
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
        $this->actAs($sortable0);
        $this->actAs($sluggable0);
    }
}