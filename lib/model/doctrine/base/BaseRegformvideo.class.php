<?php

/**
 * BaseRegformvideo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $mail
 * 
 * @method string       getName() Returns the current record's "name" value
 * @method string       getMail() Returns the current record's "mail" value
 * @method Regformvideo setName() Sets the current record's "name" value
 * @method Regformvideo setMail() Sets the current record's "mail" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseRegformvideo extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('regformvideo');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('mail', 'string', 255, array(
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