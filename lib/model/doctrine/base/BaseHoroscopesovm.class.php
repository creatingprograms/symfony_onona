<?php

/**
 * BaseHoroscopesovm
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $horoscope_m_id
 * @property integer $horoscope_g_id
 * @property clob $content
 * @property Horoscope $Horoscopem
 * @property Horoscope $Horoscopeg
 * 
 * @method integer       getHoroscopeMId()   Returns the current record's "horoscope_m_id" value
 * @method integer       getHoroscopeGId()   Returns the current record's "horoscope_g_id" value
 * @method clob          getContent()        Returns the current record's "content" value
 * @method Horoscope     getHoroscopem()     Returns the current record's "Horoscopem" value
 * @method Horoscope     getHoroscopeg()     Returns the current record's "Horoscopeg" value
 * @method Horoscopesovm setHoroscopeMId()   Sets the current record's "horoscope_m_id" value
 * @method Horoscopesovm setHoroscopeGId()   Sets the current record's "horoscope_g_id" value
 * @method Horoscopesovm setContent()        Sets the current record's "content" value
 * @method Horoscopesovm setHoroscopem()     Sets the current record's "Horoscopem" value
 * @method Horoscopesovm setHoroscopeg()     Sets the current record's "Horoscopeg" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseHoroscopesovm extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('horoscopesovm');
        $this->hasColumn('horoscope_m_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('horoscope_g_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('content', 'clob', null, array(
             'type' => 'clob',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Horoscope as Horoscopem', array(
             'local' => 'horoscope_m_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Horoscope as Horoscopeg', array(
             'local' => 'horoscope_g_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}