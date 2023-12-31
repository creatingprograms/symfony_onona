<?php

/**
 * BaseDopinfoaction
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property timestamp $startaction
 * @property timestamp $endaction
 * @property integer $dopinfo_id
 * @property boolean $is_active
 * 
 * @method timestamp     getStartaction() Returns the current record's "startaction" value
 * @method timestamp     getEndaction()   Returns the current record's "endaction" value
 * @method integer       getDopinfoId()   Returns the current record's "dopinfo_id" value
 * @method boolean       getIsActive()    Returns the current record's "is_active" value
 * @method Dopinfoaction setStartaction() Sets the current record's "startaction" value
 * @method Dopinfoaction setEndaction()   Sets the current record's "endaction" value
 * @method Dopinfoaction setDopinfoId()   Sets the current record's "dopinfo_id" value
 * @method Dopinfoaction setIsActive()    Sets the current record's "is_active" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDopinfoaction extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('dopinfoaction');
        $this->hasColumn('startaction', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('endaction', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('dopinfo_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}