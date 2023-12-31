<?php

/**
 * BaseIml
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $Address
 * @property string $Regioncode
 * @property string $Workmode
 * @property string $Code
 * @property string $Name
 * @property string $Latitude
 * @property string $Longitude
 * @property integer $city_id
 * @property City $City
 * 
 * @method string  getAddress()    Returns the current record's "Address" value
 * @method string  getRegioncode() Returns the current record's "Regioncode" value
 * @method string  getWorkmode()   Returns the current record's "Workmode" value
 * @method string  getCode()       Returns the current record's "Code" value
 * @method string  getName()       Returns the current record's "Name" value
 * @method string  getLatitude()   Returns the current record's "Latitude" value
 * @method string  getLongitude()  Returns the current record's "Longitude" value
 * @method integer getCityId()     Returns the current record's "city_id" value
 * @method City    getCity()       Returns the current record's "City" value
 * @method Iml     setAddress()    Sets the current record's "Address" value
 * @method Iml     setRegioncode() Sets the current record's "Regioncode" value
 * @method Iml     setWorkmode()   Sets the current record's "Workmode" value
 * @method Iml     setCode()       Sets the current record's "Code" value
 * @method Iml     setName()       Sets the current record's "Name" value
 * @method Iml     setLatitude()   Sets the current record's "Latitude" value
 * @method Iml     setLongitude()  Sets the current record's "Longitude" value
 * @method Iml     setCityId()     Sets the current record's "city_id" value
 * @method Iml     setCity()       Sets the current record's "City" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseIml extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('iml');
        $this->hasColumn('Address', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Regioncode', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Workmode', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Code', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Latitude', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('Longitude', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('city_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('City', array(
             'local' => 'city_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}