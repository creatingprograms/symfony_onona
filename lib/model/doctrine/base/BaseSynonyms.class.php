<?php

/**
 * BaseSynonyms
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property clob $text
 * 
 * @method clob     getText() Returns the current record's "text" value
 * @method Synonyms setText() Sets the current record's "text" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSynonyms extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('synonyms');
        $this->hasColumn('text', 'clob', null, array(
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