<?php

/**
 * BaseCaptcha
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $subid
 * @property string $val
 * @property clob $ip
 * @property string $type
 * 
 * @method string  getSubid() Returns the current record's "subid" value
 * @method string  getVal()   Returns the current record's "val" value
 * @method clob    getIp()    Returns the current record's "ip" value
 * @method string  getType()  Returns the current record's "type" value
 * @method Captcha setSubid() Sets the current record's "subid" value
 * @method Captcha setVal()   Sets the current record's "val" value
 * @method Captcha setIp()    Sets the current record's "ip" value
 * @method Captcha setType()  Sets the current record's "type" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCaptcha extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('captcha');
        $this->hasColumn('subid', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('val', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('ip', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('type', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}