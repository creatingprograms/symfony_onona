<?php

/**
 * BaseShops
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property boolean $is_active
 * @property boolean $is_onmain
 * @property boolean $is_new
 * @property string $slug
 * @property string $Address
 * @property string $phone
 * @property boolean $Card
 * @property boolean $Cash
 * @property string $House
 * @property clob $Description
 * @property string $Latitude
 * @property string $Longitude
 * @property string $Metro
 * @property string $Iconmetro
 * @property string $preview_image
 * @property string $Name
 * @property clob $OutDescription
 * @property string $Status
 * @property string $Street
 * @property string $WorkTime
 * @property integer $city_id
 * @property integer $page_id
 * @property string $id1c
 * @property clob $google
 * @property clob $yandex
 * @property clob $2gis
 * @property City $City
 * @property Page $Page
 * @property Doctrine_Collection $Comments
 * 
 * @method boolean             getIsActive()       Returns the current record's "is_active" value
 * @method boolean             getIsOnmain()       Returns the current record's "is_onmain" value
 * @method boolean             getIsNew()          Returns the current record's "is_new" value
 * @method string              getSlug()           Returns the current record's "slug" value
 * @method string              getAddress()        Returns the current record's "Address" value
 * @method string              getPhone()          Returns the current record's "phone" value
 * @method boolean             getCard()           Returns the current record's "Card" value
 * @method boolean             getCash()           Returns the current record's "Cash" value
 * @method string              getHouse()          Returns the current record's "House" value
 * @method clob                getDescription()    Returns the current record's "Description" value
 * @method string              getLatitude()       Returns the current record's "Latitude" value
 * @method string              getLongitude()      Returns the current record's "Longitude" value
 * @method string              getMetro()          Returns the current record's "Metro" value
 * @method string              getIconmetro()      Returns the current record's "Iconmetro" value
 * @method string              getPreviewImage()   Returns the current record's "preview_image" value
 * @method string              getName()           Returns the current record's "Name" value
 * @method clob                getOutDescription() Returns the current record's "OutDescription" value
 * @method string              getStatus()         Returns the current record's "Status" value
 * @method string              getStreet()         Returns the current record's "Street" value
 * @method string              getWorkTime()       Returns the current record's "WorkTime" value
 * @method integer             getCityId()         Returns the current record's "city_id" value
 * @method integer             getPageId()         Returns the current record's "page_id" value
 * @method string              getId1c()           Returns the current record's "id1c" value
 * @method clob                getGoogle()         Returns the current record's "google" value
 * @method clob                getYandex()         Returns the current record's "yandex" value
 * @method clob                get2gis()           Returns the current record's "2gis" value
 * @method City                getCity()           Returns the current record's "City" value
 * @method Page                getPage()           Returns the current record's "Page" value
 * @method Doctrine_Collection getComments()       Returns the current record's "Comments" collection
 * @method Shops               setIsActive()       Sets the current record's "is_active" value
 * @method Shops               setIsOnmain()       Sets the current record's "is_onmain" value
 * @method Shops               setIsNew()          Sets the current record's "is_new" value
 * @method Shops               setSlug()           Sets the current record's "slug" value
 * @method Shops               setAddress()        Sets the current record's "Address" value
 * @method Shops               setPhone()          Sets the current record's "phone" value
 * @method Shops               setCard()           Sets the current record's "Card" value
 * @method Shops               setCash()           Sets the current record's "Cash" value
 * @method Shops               setHouse()          Sets the current record's "House" value
 * @method Shops               setDescription()    Sets the current record's "Description" value
 * @method Shops               setLatitude()       Sets the current record's "Latitude" value
 * @method Shops               setLongitude()      Sets the current record's "Longitude" value
 * @method Shops               setMetro()          Sets the current record's "Metro" value
 * @method Shops               setIconmetro()      Sets the current record's "Iconmetro" value
 * @method Shops               setPreviewImage()   Sets the current record's "preview_image" value
 * @method Shops               setName()           Sets the current record's "Name" value
 * @method Shops               setOutDescription() Sets the current record's "OutDescription" value
 * @method Shops               setStatus()         Sets the current record's "Status" value
 * @method Shops               setStreet()         Sets the current record's "Street" value
 * @method Shops               setWorkTime()       Sets the current record's "WorkTime" value
 * @method Shops               setCityId()         Sets the current record's "city_id" value
 * @method Shops               setPageId()         Sets the current record's "page_id" value
 * @method Shops               setId1c()           Sets the current record's "id1c" value
 * @method Shops               setGoogle()         Sets the current record's "google" value
 * @method Shops               setYandex()         Sets the current record's "yandex" value
 * @method Shops               set2gis()           Sets the current record's "2gis" value
 * @method Shops               setCity()           Sets the current record's "City" value
 * @method Shops               setPage()           Sets the current record's "Page" value
 * @method Shops               setComments()       Sets the current record's "Comments" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseShops extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('shops');
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
        $this->hasColumn('is_onmain', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('is_new', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('slug', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Address', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('phone', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Card', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('Cash', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('House', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Description', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('Latitude', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Longitude', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Metro', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Iconmetro', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('preview_image', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('OutDescription', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('Status', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Street', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('WorkTime', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('city_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('page_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('id1c', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('google', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('yandex', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('2gis', 'clob', null, array(
             'type' => 'clob',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('City', array(
             'local' => 'city_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Page', array(
             'local' => 'page_id',
             'foreign' => 'id'));

        $this->hasMany('Comments', array(
             'local' => 'id',
             'foreign' => 'shops_id'));

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