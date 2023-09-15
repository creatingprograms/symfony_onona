<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version134 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('dop_info_category_full_category', 'dddi', array(
             'name' => 'dddi',
             'local' => 'dop_info_category_full_id',
             'foreign' => 'id',
             'foreignTable' => 'dop_info_category_full',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('dop_info_category_full_category', 'dddi_1', array(
             'name' => 'dddi_1',
             'local' => 'dop_info_category_id',
             'foreign' => 'id',
             'foreignTable' => 'dop_info_category',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('dop_info_category_full_category', 'dop_info_category_full_category_dop_info_category_full_id', array(
             'fields' => 
             array(
              0 => 'dop_info_category_full_id',
             ),
             ));
        $this->addIndex('dop_info_category_full_category', 'dop_info_category_full_category_dop_info_category_id', array(
             'fields' => 
             array(
              0 => 'dop_info_category_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('dop_info_category_full_category', 'dddi');
        $this->dropForeignKey('dop_info_category_full_category', 'dddi_1');
        $this->removeIndex('dop_info_category_full_category', 'dop_info_category_full_category_dop_info_category_full_id', array(
             'fields' => 
             array(
              0 => 'dop_info_category_full_id',
             ),
             ));
        $this->removeIndex('dop_info_category_full_category', 'dop_info_category_full_category_dop_info_category_id', array(
             'fields' => 
             array(
              0 => 'dop_info_category_id',
             ),
             ));
    }
}