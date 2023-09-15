<?php

/**
 * BaseCategorypagePage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $Categorypage_id
 * @property integer $page_id
 * @property Categorypage $Categorypage
 * @property Page $Page
 * 
 * @method integer          getCategorypageId()  Returns the current record's "Categorypage_id" value
 * @method integer          getPageId()          Returns the current record's "page_id" value
 * @method Categorypage     getCategorypage()    Returns the current record's "Categorypage" value
 * @method Page             getPage()            Returns the current record's "Page" value
 * @method CategorypagePage setCategorypageId()  Sets the current record's "Categorypage_id" value
 * @method CategorypagePage setPageId()          Sets the current record's "page_id" value
 * @method CategorypagePage setCategorypage()    Sets the current record's "Categorypage" value
 * @method CategorypagePage setPage()            Sets the current record's "Page" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCategorypagePage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('categorypage_page');
        $this->hasColumn('Categorypage_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
        $this->hasColumn('page_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Categorypage', array(
             'local' => 'categorypage_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Page', array(
             'local' => 'page_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}