<?php

/**
 * BaseCategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property string $h1
 * @property clob $content
 * @property boolean $is_open
 * @property boolean $is_public
 * @property boolean $adult
 * @property boolean $show_in_catalog
 * @property string $img
 * @property string $icon_name
 * @property integer $icon_priority
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property integer $parents_id
 * @property string $lovepricename
 * @property integer $positionloveprice
 * @property clob $filters
 * @property string $tags
 * @property clob $rrproductid
 * @property clob $prodid_priority
 * @property integer $lastupdaterrproductid
 * @property clob $filtersnew
 * @property integer $minPrice
 * @property integer $maxPrice
 * @property integer $countProductActions
 * @property integer $views_count
 * @property string $canonical
 * @property Category $Parent
 * @property Doctrine_Collection $Children
 * @property Doctrine_Collection $CategoryProducts
 * @property Doctrine_Collection $CategoryCatalogs
 * 
 * @method string              getName()                  Returns the current record's "name" value
 * @method string              getH1()                    Returns the current record's "h1" value
 * @method clob                getContent()               Returns the current record's "content" value
 * @method boolean             getIsOpen()                Returns the current record's "is_open" value
 * @method boolean             getIsPublic()              Returns the current record's "is_public" value
 * @method boolean             getAdult()                 Returns the current record's "adult" value
 * @method boolean             getShowInCatalog()         Returns the current record's "show_in_catalog" value
 * @method string              getImg()                   Returns the current record's "img" value
 * @method string              getIconName()              Returns the current record's "icon_name" value
 * @method integer             getIconPriority()          Returns the current record's "icon_priority" value
 * @method string              getTitle()                 Returns the current record's "title" value
 * @method string              getKeywords()              Returns the current record's "keywords" value
 * @method string              getDescription()           Returns the current record's "description" value
 * @method integer             getParentsId()             Returns the current record's "parents_id" value
 * @method string              getLovepricename()         Returns the current record's "lovepricename" value
 * @method integer             getPositionloveprice()     Returns the current record's "positionloveprice" value
 * @method clob                getFilters()               Returns the current record's "filters" value
 * @method string              getTags()                  Returns the current record's "tags" value
 * @method clob                getRrproductid()           Returns the current record's "rrproductid" value
 * @method clob                getProdidPriority()        Returns the current record's "prodid_priority" value
 * @method integer             getLastupdaterrproductid() Returns the current record's "lastupdaterrproductid" value
 * @method clob                getFiltersnew()            Returns the current record's "filtersnew" value
 * @method integer             getMinPrice()              Returns the current record's "minPrice" value
 * @method integer             getMaxPrice()              Returns the current record's "maxPrice" value
 * @method integer             getCountProductActions()   Returns the current record's "countProductActions" value
 * @method integer             getViewsCount()            Returns the current record's "views_count" value
 * @method string              getCanonical()             Returns the current record's "canonical" value
 * @method Category            getParent()                Returns the current record's "Parent" value
 * @method Doctrine_Collection getChildren()              Returns the current record's "Children" collection
 * @method Doctrine_Collection getCategoryProducts()      Returns the current record's "CategoryProducts" collection
 * @method Doctrine_Collection getCategoryCatalogs()      Returns the current record's "CategoryCatalogs" collection
 * @method Category            setName()                  Sets the current record's "name" value
 * @method Category            setH1()                    Sets the current record's "h1" value
 * @method Category            setContent()               Sets the current record's "content" value
 * @method Category            setIsOpen()                Sets the current record's "is_open" value
 * @method Category            setIsPublic()              Sets the current record's "is_public" value
 * @method Category            setAdult()                 Sets the current record's "adult" value
 * @method Category            setShowInCatalog()         Sets the current record's "show_in_catalog" value
 * @method Category            setImg()                   Sets the current record's "img" value
 * @method Category            setIconName()              Sets the current record's "icon_name" value
 * @method Category            setIconPriority()          Sets the current record's "icon_priority" value
 * @method Category            setTitle()                 Sets the current record's "title" value
 * @method Category            setKeywords()              Sets the current record's "keywords" value
 * @method Category            setDescription()           Sets the current record's "description" value
 * @method Category            setParentsId()             Sets the current record's "parents_id" value
 * @method Category            setLovepricename()         Sets the current record's "lovepricename" value
 * @method Category            setPositionloveprice()     Sets the current record's "positionloveprice" value
 * @method Category            setFilters()               Sets the current record's "filters" value
 * @method Category            setTags()                  Sets the current record's "tags" value
 * @method Category            setRrproductid()           Sets the current record's "rrproductid" value
 * @method Category            setProdidPriority()        Sets the current record's "prodid_priority" value
 * @method Category            setLastupdaterrproductid() Sets the current record's "lastupdaterrproductid" value
 * @method Category            setFiltersnew()            Sets the current record's "filtersnew" value
 * @method Category            setMinPrice()              Sets the current record's "minPrice" value
 * @method Category            setMaxPrice()              Sets the current record's "maxPrice" value
 * @method Category            setCountProductActions()   Sets the current record's "countProductActions" value
 * @method Category            setViewsCount()            Sets the current record's "views_count" value
 * @method Category            setCanonical()             Sets the current record's "canonical" value
 * @method Category            setParent()                Sets the current record's "Parent" value
 * @method Category            setChildren()              Sets the current record's "Children" collection
 * @method Category            setCategoryProducts()      Sets the current record's "CategoryProducts" collection
 * @method Category            setCategoryCatalogs()      Sets the current record's "CategoryCatalogs" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCategory extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('category');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('h1', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('content', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('is_open', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('is_public', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
        $this->hasColumn('adult', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
        $this->hasColumn('show_in_catalog', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('img', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('icon_name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('icon_priority', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
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
        $this->hasColumn('parents_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('lovepricename', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('positionloveprice', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('filters', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('tags', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('rrproductid', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('prodid_priority', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('lastupdaterrproductid', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('filtersnew', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('minPrice', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('maxPrice', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('countProductActions', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('views_count', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('canonical', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Category as Parent', array(
             'local' => 'parents_id',
             'foreign' => 'id'));

        $this->hasMany('Category as Children', array(
             'local' => 'id',
             'foreign' => 'parents_id'));

        $this->hasMany('Product as CategoryProducts', array(
             'refClass' => 'CategoryProduct',
             'local' => 'category_id',
             'foreign' => 'product_id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('Catalog as CategoryCatalogs', array(
             'refClass' => 'CategoryCatalog',
             'local' => 'category_id',
             'foreign' => 'catalog_id',
             'onDelete' => 'CASCADE'));

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