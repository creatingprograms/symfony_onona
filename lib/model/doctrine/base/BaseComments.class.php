<?php

/**
 * BaseComments
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property clob $text
 * @property integer $product_id
 * @property integer $page_id
 * @property integer $shops_id
 * @property integer $article_id
 * @property integer $compare_id
 * @property integer $customer_id
 * @property integer $sort_index
 * @property boolean $is_public
 * @property boolean $is_onmain
 * @property boolean $is_novice
 * @property string $username
 * @property string $mail
 * @property clob $answer
 * @property integer $rate_plus
 * @property integer $rate_minus
 * @property integer $rate_set
 * @property integer $point
 * @property clob $comment_manager
 * @property integer $manager_id
 * @property Product $Product
 * @property sfGuardUser $sfGuardUser
 * @property Article $Article
 * @property Compare $Compare
 * @property Page $Page
 * @property Shops $Shops
 * 
 * @method clob        getText()            Returns the current record's "text" value
 * @method integer     getProductId()       Returns the current record's "product_id" value
 * @method integer     getPageId()          Returns the current record's "page_id" value
 * @method integer     getShopsId()         Returns the current record's "shops_id" value
 * @method integer     getArticleId()       Returns the current record's "article_id" value
 * @method integer     getCompareId()       Returns the current record's "compare_id" value
 * @method integer     getCustomerId()      Returns the current record's "customer_id" value
 * @method integer     getSortIndex()       Returns the current record's "sort_index" value
 * @method boolean     getIsPublic()        Returns the current record's "is_public" value
 * @method boolean     getIsOnmain()        Returns the current record's "is_onmain" value
 * @method boolean     getIsNovice()        Returns the current record's "is_novice" value
 * @method string      getUsername()        Returns the current record's "username" value
 * @method string      getMail()            Returns the current record's "mail" value
 * @method clob        getAnswer()          Returns the current record's "answer" value
 * @method integer     getRatePlus()        Returns the current record's "rate_plus" value
 * @method integer     getRateMinus()       Returns the current record's "rate_minus" value
 * @method integer     getRateSet()         Returns the current record's "rate_set" value
 * @method integer     getPoint()           Returns the current record's "point" value
 * @method clob        getCommentManager()  Returns the current record's "comment_manager" value
 * @method integer     getManagerId()       Returns the current record's "manager_id" value
 * @method Product     getProduct()         Returns the current record's "Product" value
 * @method sfGuardUser getSfGuardUser()     Returns the current record's "sfGuardUser" value
 * @method Article     getArticle()         Returns the current record's "Article" value
 * @method Compare     getCompare()         Returns the current record's "Compare" value
 * @method Page        getPage()            Returns the current record's "Page" value
 * @method Shops       getShops()           Returns the current record's "Shops" value
 * @method Comments    setText()            Sets the current record's "text" value
 * @method Comments    setProductId()       Sets the current record's "product_id" value
 * @method Comments    setPageId()          Sets the current record's "page_id" value
 * @method Comments    setShopsId()         Sets the current record's "shops_id" value
 * @method Comments    setArticleId()       Sets the current record's "article_id" value
 * @method Comments    setCompareId()       Sets the current record's "compare_id" value
 * @method Comments    setCustomerId()      Sets the current record's "customer_id" value
 * @method Comments    setSortIndex()       Sets the current record's "sort_index" value
 * @method Comments    setIsPublic()        Sets the current record's "is_public" value
 * @method Comments    setIsOnmain()        Sets the current record's "is_onmain" value
 * @method Comments    setIsNovice()        Sets the current record's "is_novice" value
 * @method Comments    setUsername()        Sets the current record's "username" value
 * @method Comments    setMail()            Sets the current record's "mail" value
 * @method Comments    setAnswer()          Sets the current record's "answer" value
 * @method Comments    setRatePlus()        Sets the current record's "rate_plus" value
 * @method Comments    setRateMinus()       Sets the current record's "rate_minus" value
 * @method Comments    setRateSet()         Sets the current record's "rate_set" value
 * @method Comments    setPoint()           Sets the current record's "point" value
 * @method Comments    setCommentManager()  Sets the current record's "comment_manager" value
 * @method Comments    setManagerId()       Sets the current record's "manager_id" value
 * @method Comments    setProduct()         Sets the current record's "Product" value
 * @method Comments    setSfGuardUser()     Sets the current record's "sfGuardUser" value
 * @method Comments    setArticle()         Sets the current record's "Article" value
 * @method Comments    setCompare()         Sets the current record's "Compare" value
 * @method Comments    setPage()            Sets the current record's "Page" value
 * @method Comments    setShops()           Sets the current record's "Shops" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseComments extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('comments');
        $this->hasColumn('text', 'clob', null, array(
             'type' => 'clob',
             'notnull' => true,
             ));
        $this->hasColumn('product_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('page_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('shops_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('article_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('compare_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('customer_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('sort_index', 'integer', 8, array(
             'type' => 'integer',
             'default' => 100,
             'length' => 8,
             ));
        $this->hasColumn('is_public', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('is_onmain', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('is_novice', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('username', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('mail', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('answer', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('rate_plus', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('rate_minus', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('rate_set', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('point', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('comment_manager', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('manager_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Product', array(
             'local' => 'product_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('sfGuardUser', array(
             'local' => 'customer_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Article', array(
             'local' => 'article_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Compare', array(
             'local' => 'compare_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Page', array(
             'local' => 'page_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Shops', array(
             'local' => 'shops_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}