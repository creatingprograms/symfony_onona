<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version28 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->dropForeignKey('dop_info_product', 'dop_info_product_dop_info_id_dop_info_id');
        $this->dropForeignKey('dop_info_product', 'dop_info_product_product_id_product_id');
        $this->createForeignKey('dop_info_product', 'dop_info_product_dop_info_id_dop_info_id_1', array(
             'name' => 'dop_info_product_dop_info_id_dop_info_id_1',
             'local' => 'dop_info_id',
             'foreign' => 'id',
             'foreignTable' => 'dop_info',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->createForeignKey('dop_info_product', 'dop_info_product_product_id_product_id_1', array(
             'name' => 'dop_info_product_product_id_product_id_1',
             'local' => 'product_id',
             'foreign' => 'id',
             'foreignTable' => 'product',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('dop_info_product', 'dop_info_product_dop_info_id', array(
             'fields' => 
             array(
              0 => 'dop_info_id',
             ),
             ));
        $this->addIndex('dop_info_product', 'dop_info_product_product_id', array(
             'fields' => 
             array(
              0 => 'product_id',
             ),
             ));
    }

    public function down()
    {
        $this->createForeignKey('dop_info_product', 'dop_info_product_dop_info_id_dop_info_id', array(
             'name' => 'dop_info_product_dop_info_id_dop_info_id',
             'local' => 'dop_info_id',
             'foreign' => 'id',
             'foreignTable' => 'dop_info',
             ));
        $this->createForeignKey('dop_info_product', 'dop_info_product_product_id_product_id', array(
             'name' => 'dop_info_product_product_id_product_id',
             'local' => 'product_id',
             'foreign' => 'id',
             'foreignTable' => 'product',
             ));
        $this->dropForeignKey('dop_info_product', 'dop_info_product_dop_info_id_dop_info_id_1');
        $this->dropForeignKey('dop_info_product', 'dop_info_product_product_id_product_id_1');
        $this->removeIndex('dop_info_product', 'dop_info_product_dop_info_id', array(
             'fields' => 
             array(
              0 => 'dop_info_id',
             ),
             ));
        $this->removeIndex('dop_info_product', 'dop_info_product_product_id', array(
             'fields' => 
             array(
              0 => 'product_id',
             ),
             ));
    }
}