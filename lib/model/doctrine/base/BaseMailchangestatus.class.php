<?php

/**
 * BaseMailchangestatus
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $status
 * @property string $titlemail
 * @property boolean $is_public
 * @property clob $content
 * 
 * @method string           getStatus()    Returns the current record's "status" value
 * @method string           getTitlemail() Returns the current record's "titlemail" value
 * @method boolean          getIsPublic()  Returns the current record's "is_public" value
 * @method clob             getContent()   Returns the current record's "content" value
 * @method Mailchangestatus setStatus()    Sets the current record's "status" value
 * @method Mailchangestatus setTitlemail() Sets the current record's "titlemail" value
 * @method Mailchangestatus setIsPublic()  Sets the current record's "is_public" value
 * @method Mailchangestatus setContent()   Sets the current record's "content" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMailchangestatus extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('mailchangestatus');
        $this->hasColumn('status', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('titlemail', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('is_public', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
        $this->hasColumn('content', 'clob', null, array(
             'type' => 'clob',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}