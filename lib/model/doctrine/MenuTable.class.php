<?php

/**
 * MenuTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MenuTable extends Doctrine_Table
{
        protected $_options      = array('name'           => null,
                                     'tableName'      => null,
                                     'sequenceName'   => null,
                                     'inheritanceMap' => array(),
                                     'enumMap'        => array(),
                                     'type'           => null,
                                     'charset'        => null,
                                     'collate'        => null,
                                     'treeImpl'       => null,
                                     'treeOptions'    => array(),
                                     'indexes'        => array(),
                                     'parents'        => array(),
                                     'joinedParents'  => array(),
                                     'queryParts'     => array(),
                                     'versioning'     => null,
                                     'subclasses'     => array(),
                                     'orderBy'        => "position asc"
                                     );
    /**
     * Returns an instance of this class.
     *
     * @return object MenuTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Menu');
    }
}