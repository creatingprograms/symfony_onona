<?php

/**
 * BaseArticlecatalog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property clob $content
 * @property string $description
 * @property boolean $is_public
 * @property Doctrine_Collection $Category
 * @property Doctrine_Collection $CategoryCatalogs
 * 
 * @method string              getName()             Returns the current record's "name" value
 * @method clob                getContent()          Returns the current record's "content" value
 * @method string              getDescription()      Returns the current record's "description" value
 * @method boolean             getIsPublic()         Returns the current record's "is_public" value
 * @method Doctrine_Collection getCategory()         Returns the current record's "Category" collection
 * @method Doctrine_Collection getCategoryCatalogs() Returns the current record's "CategoryCatalogs" collection
 * @method Articlecatalog      setName()             Sets the current record's "name" value
 * @method Articlecatalog      setContent()          Sets the current record's "content" value
 * @method Articlecatalog      setDescription()      Sets the current record's "description" value
 * @method Articlecatalog      setIsPublic()         Sets the current record's "is_public" value
 * @method Articlecatalog      setCategory()         Sets the current record's "Category" collection
 * @method Articlecatalog      setCategoryCatalogs() Sets the current record's "CategoryCatalogs" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseArticlecatalog extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('articlecatalog');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('content', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('is_public', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Articlecategory as Category', array(
             'refClass' => 'CategoryarticleCatalog',
             'local' => 'articlecatalog_id',
             'foreign' => 'articlecategory_id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('CategoryarticleCatalog as CategoryCatalogs', array(
             'local' => 'id',
             'foreign' => 'articlecatalog_id'));

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