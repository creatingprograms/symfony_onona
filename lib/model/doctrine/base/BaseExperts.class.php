<?php

/**
 * BaseExperts
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $description
 * @property string $photo_url
 * 
 * @method string  getName()        Returns the current record's "name" value
 * @method string  getDescription() Returns the current record's "description" value
 * @method string  getPhotoUrl()    Returns the current record's "photo_url" value
 * @method Experts setName()        Sets the current record's "name" value
 * @method Experts setDescription() Sets the current record's "description" value
 * @method Experts setPhotoUrl()    Sets the current record's "photo_url" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseExperts extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('experts');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('photo_url', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $sortable0 = new Doctrine_Template_Sortable();
        $this->actAs($sortable0);
    }
}