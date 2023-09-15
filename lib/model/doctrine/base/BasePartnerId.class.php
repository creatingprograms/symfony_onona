<?php

/**
 * BasePartnerId
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $refid
 * @property string $webmaster
 * 
 * @method string    getRefid()     Returns the current record's "refid" value
 * @method string    getWebmaster() Returns the current record's "webmaster" value
 * @method PartnerId setRefid()     Sets the current record's "refid" value
 * @method PartnerId setWebmaster() Sets the current record's "webmaster" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePartnerId extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('partner_id');
        $this->hasColumn('refid', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('webmaster', 'string', 255, array(
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