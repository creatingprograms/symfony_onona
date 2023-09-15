<?php

/**
 * BaseBottombanner
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property timestamp $startaction
 * @property timestamp $endaction
 * @property string $text
 * @property string $link
 * @property boolean $is_active
 * 
 * @method timestamp    getStartaction() Returns the current record's "startaction" value
 * @method timestamp    getEndaction()   Returns the current record's "endaction" value
 * @method string       getText()        Returns the current record's "text" value
 * @method string       getLink()        Returns the current record's "link" value
 * @method boolean      getIsActive()    Returns the current record's "is_active" value
 * @method Bottombanner setStartaction() Sets the current record's "startaction" value
 * @method Bottombanner setEndaction()   Sets the current record's "endaction" value
 * @method Bottombanner setText()        Sets the current record's "text" value
 * @method Bottombanner setLink()        Sets the current record's "link" value
 * @method Bottombanner setIsActive()    Sets the current record's "is_active" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBottombanner extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('bottombanner');
        $this->hasColumn('startaction', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('endaction', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('text', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 255,
             ));
        $this->hasColumn('link', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
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