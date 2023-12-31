<?php

/**
 * BaseHoroscope
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $date
 * @property string $image
 * @property clob $info
 * @property clob $month
 * @property clob $year
 * @property clob $characteristic
 * @property clob $compatibility
 * @property Doctrine_Collection $Horoscopesovm
 * 
 * @method string              getName()           Returns the current record's "name" value
 * @method string              getDate()           Returns the current record's "date" value
 * @method string              getImage()          Returns the current record's "image" value
 * @method clob                getInfo()           Returns the current record's "info" value
 * @method clob                getMonth()          Returns the current record's "month" value
 * @method clob                getYear()           Returns the current record's "year" value
 * @method clob                getCharacteristic() Returns the current record's "characteristic" value
 * @method clob                getCompatibility()  Returns the current record's "compatibility" value
 * @method Doctrine_Collection getHoroscopesovm()  Returns the current record's "Horoscopesovm" collection
 * @method Horoscope           setName()           Sets the current record's "name" value
 * @method Horoscope           setDate()           Sets the current record's "date" value
 * @method Horoscope           setImage()          Sets the current record's "image" value
 * @method Horoscope           setInfo()           Sets the current record's "info" value
 * @method Horoscope           setMonth()          Sets the current record's "month" value
 * @method Horoscope           setYear()           Sets the current record's "year" value
 * @method Horoscope           setCharacteristic() Sets the current record's "characteristic" value
 * @method Horoscope           setCompatibility()  Sets the current record's "compatibility" value
 * @method Horoscope           setHoroscopesovm()  Sets the current record's "Horoscopesovm" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseHoroscope extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('horoscope');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 255,
             ));
        $this->hasColumn('date', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('image', 'string', 50, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 50,
             ));
        $this->hasColumn('info', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('month', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('year', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('characteristic', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('compatibility', 'clob', null, array(
             'type' => 'clob',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Horoscopesovm', array(
             'local' => 'id',
             'foreign' => 'horoscope_m_id'));

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