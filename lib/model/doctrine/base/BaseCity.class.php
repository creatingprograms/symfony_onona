<?php

/**
 * BaseCity
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $slug
 * @property clob $pickpointpage
 * @property boolean $is_public
 * @property Doctrine_Collection $City
 * 
 * @method string              getName()          Returns the current record's "name" value
 * @method string              getSlug()          Returns the current record's "slug" value
 * @method clob                getPickpointpage() Returns the current record's "pickpointpage" value
 * @method boolean             getIsPublic()      Returns the current record's "is_public" value
 * @method Doctrine_Collection getCity()          Returns the current record's "City" collection
 * @method City                setName()          Sets the current record's "name" value
 * @method City                setSlug()          Sets the current record's "slug" value
 * @method City                setPickpointpage() Sets the current record's "pickpointpage" value
 * @method City                setIsPublic()      Sets the current record's "is_public" value
 * @method City                setCity()          Sets the current record's "City" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCity extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('city');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('slug', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('pickpointpage', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('is_public', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Page as City', array(
             'local' => 'id',
             'foreign' => 'city_id'));

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