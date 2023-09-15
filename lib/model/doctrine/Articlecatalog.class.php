<?php

/**
 * Articlecatalog
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Articlecatalog extends BaseArticlecatalog {

    public function setUp() {
        parent::setUp();
        $this->hasMany('Articlecategory as Category', array(
            'refClass' => 'CategoryarticleCatalog',
            'local' => 'articlecatalog_id',
            'foreign' => 'articlecategory_id',
            'orderBy' => 'position ASC',
            'onDelete' => 'CASCADE'));
    }

}