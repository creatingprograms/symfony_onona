<?php

/**
 * BaseCategoryArticle
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $articlecategory_id
 * @property integer $article_id
 * @property Articlecategory $Articlecategory
 * @property Article $Article
 * 
 * @method integer         getArticlecategoryId()  Returns the current record's "articlecategory_id" value
 * @method integer         getArticleId()          Returns the current record's "article_id" value
 * @method Articlecategory getArticlecategory()    Returns the current record's "Articlecategory" value
 * @method Article         getArticle()            Returns the current record's "Article" value
 * @method CategoryArticle setArticlecategoryId()  Sets the current record's "articlecategory_id" value
 * @method CategoryArticle setArticleId()          Sets the current record's "article_id" value
 * @method CategoryArticle setArticlecategory()    Sets the current record's "Articlecategory" value
 * @method CategoryArticle setArticle()            Sets the current record's "Article" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCategoryArticle extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('category_article');
        $this->hasColumn('articlecategory_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
        $this->hasColumn('article_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Articlecategory', array(
             'local' => 'articlecategory_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Article', array(
             'local' => 'article_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}