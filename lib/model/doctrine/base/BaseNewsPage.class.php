<?php

/**
 * BaseNewsPage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $news_id
 * @property integer $page_id
 * @property News $News
 * @property Page $Page
 * 
 * @method integer  getNewsId()  Returns the current record's "news_id" value
 * @method integer  getPageId()  Returns the current record's "page_id" value
 * @method News     getNews()    Returns the current record's "News" value
 * @method Page     getPage()    Returns the current record's "Page" value
 * @method NewsPage setNewsId()  Sets the current record's "news_id" value
 * @method NewsPage setPageId()  Sets the current record's "page_id" value
 * @method NewsPage setNews()    Sets the current record's "News" value
 * @method NewsPage setPage()    Sets the current record's "Page" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseNewsPage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('news_page');
        $this->hasColumn('news_id', 'integer', 8, array(
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
        $this->hasOne('News', array(
             'local' => 'news_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Page', array(
             'local' => 'page_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}