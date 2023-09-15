<?php

/**
 * BaseFaqcategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property clob $content
 * @property boolean $is_public
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property Doctrine_Collection $CategoryFaq
 * 
 * @method string              getName()         Returns the current record's "name" value
 * @method clob                getContent()      Returns the current record's "content" value
 * @method boolean             getIsPublic()     Returns the current record's "is_public" value
 * @method string              getTitle()        Returns the current record's "title" value
 * @method string              getKeywords()     Returns the current record's "keywords" value
 * @method string              getDescription()  Returns the current record's "description" value
 * @method Doctrine_Collection getCategoryFaqs() Returns the current record's "CategoryFaqs" collection
 * @method Doctrine_Collection getCategoryFaq()  Returns the current record's "CategoryFaq" collection
 * @method Faqcategory         setName()         Sets the current record's "name" value
 * @method Faqcategory         setContent()      Sets the current record's "content" value
 * @method Faqcategory         setIsPublic()     Sets the current record's "is_public" value
 * @method Faqcategory         setTitle()        Sets the current record's "title" value
 * @method Faqcategory         setKeywords()     Sets the current record's "keywords" value
 * @method Faqcategory         setDescription()  Sets the current record's "description" value
 * @method Faqcategory         setCategoryFaqs() Sets the current record's "CategoryFaqs" collection
 * @method Faqcategory         setCategoryFaq()  Sets the current record's "CategoryFaq" collections
 * @property Doctrine_Collection $CategoryFaq
 * 
 * @method string              getName()         Returns the current record's "name" value
 * @method clob                getContent()      Returns the current record's "content" value
 * @method boolean             getIsPublic()     Returns the current record's "is_public" value
 * @method string              getTitle()        Returns the current record's "title" value
 * @method string              getKeywords()     Returns the current record's "keywords" value
 * @method string              getDescription()  Returns the current record's "description" value
 * @method Doctrine_Collection getCategoryFaqs() Returns the current record's "CategoryFaqs" collection
 * @method Doctrine_Collection getCategoryFaq()  Returns the current record's "CategoryFaq" collection
 * @method Faqcategory         setName()         Sets the current record's "name" value
 * @method Faqcategory         setContent()      Sets the current record's "content" value
 * @method Faqcategory         setIsPublic()     Sets the current record's "is_public" value
 * @method Faqcategory         setTitle()        Sets the current record's "title" value
 * @method Faqcategory         setKeywords()     Sets the current record's "keywords" value
 * @method Faqcategory         setDescription()  Sets the current record's "description" value
 * @method Faqcategory         setCategoryFaqs() Sets the current record's "CategoryFaqs" collection
 * @method Faqcategory         setCategoryFaq()  Sets the current record's "CategoryFaq" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFaqcategory extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('faqcategory');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('content', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('is_public', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('keywords', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Faq as CategoryFaqs', array(
             'refClass' => 'CategoryFaq',
             'local' => 'faqcategory_id',
             'foreign' => 'faq_id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('CategoryFaq', array(
             'local' => 'id',
             'foreign' => 'faqcategory_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $sortable0 = new Doctrine_Template_Sortable();
        $sluggable0 = new Doctrine_Template_Sluggable(array(
             'fields' => 
             array(
              0 => 'name',
             ),
             'unique' => true,
             'canUpdate' => false,
             'builder' => 
             array(
              0 => 'SlugifyClass',
              1 => 'Slugify',
             ),
             ));
        $this->actAs($timestampable0);
        $this->actAs($sortable0);
        $this->actAs($sluggable0);
    }
}