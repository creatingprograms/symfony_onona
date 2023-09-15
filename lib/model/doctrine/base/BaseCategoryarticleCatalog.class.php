<?php

/**
 * BaseCategoryarticleCatalog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $articlecategory_id
 * @property integer $articlecatalog_id
 * @property Articlecategory $Articlecategory
 * @property Articlecatalog $Articlecatalog
 * 
 * @method integer                getArticlecategoryId()  Returns the current record's "articlecategory_id" value
 * @method integer                getArticlecatalogId()   Returns the current record's "articlecatalog_id" value
 * @method Articlecategory        getArticlecategory()    Returns the current record's "Articlecategory" value
 * @method Articlecatalog         getArticlecatalog()     Returns the current record's "Articlecatalog" value
 * @method CategoryarticleCatalog setArticlecategoryId()  Sets the current record's "articlecategory_id" value
 * @method CategoryarticleCatalog setArticlecatalogId()   Sets the current record's "articlecatalog_id" value
 * @method CategoryarticleCatalog setArticlecategory()    Sets the current record's "Articlecategory" value
 * @method CategoryarticleCatalog setArticlecatalog()     Sets the current record's "Articlecatalog" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCategoryarticleCatalog extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('categoryarticle_catalog');
        $this->hasColumn('articlecategory_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
        $this->hasColumn('articlecatalog_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Articlecategory', array(
             'local' => 'articlecategory_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Articlecatalog', array(
             'local' => 'articlecatalog_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}