<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version239 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('video', 'video_product_id_product_id', array(
             'name' => 'video_product_id_product_id',
             'local' => 'product_id',
             'foreign' => 'id',
             'foreignTable' => 'product',
             'onUpdate' => '',
             'onDelete' => 'CASCADE',
             ));
        $this->addIndex('video', 'video_product_id', array(
             'fields' => 
             array(
              0 => 'product_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('video', 'video_product_id_product_id');
        $this->removeIndex('video', 'video_product_id', array(
             'fields' => 
             array(
              0 => 'product_id',
             ),
             ));
    }
}