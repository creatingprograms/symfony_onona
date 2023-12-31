<?php

/**
 * BaseCategorypage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property Doctrine_Collection $CategorypagePages
 * 
 * @method string              getName()              Returns the current record's "name" value
 * @method Doctrine_Collection getCategorypagePages() Returns the current record's "CategorypagePages" collection
 * @method Categorypage        setName()              Sets the current record's "name" value
 * @method Categorypage        setCategorypagePages() Sets the current record's "CategorypagePages" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCategorypage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('categorypage');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Page as CategorypagePages', array(
             'refClass' => 'CategorypagePage',
             'local' => 'categorypage_id',
             'foreign' => 'page_id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}