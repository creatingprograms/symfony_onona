<?php

/**
 * BaseBonusMailsendLog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $mail
 * @property string $bonus
 * @property string $day
 * 
 * @method string           getMail()  Returns the current record's "mail" value
 * @method string           getBonus() Returns the current record's "bonus" value
 * @method string           getDay()   Returns the current record's "day" value
 * @method BonusMailsendLog setMail()  Sets the current record's "mail" value
 * @method BonusMailsendLog setBonus() Sets the current record's "bonus" value
 * @method BonusMailsendLog setDay()   Sets the current record's "day" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBonusMailsendLog extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('bonus_mailsend_log');
        $this->hasColumn('mail', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('bonus', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('day', 'string', 255, array(
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